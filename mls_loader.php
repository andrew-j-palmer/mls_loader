<?php
/* mls to be run - name is pulled from argv,
* which is used to open a folder with config vars
*
* set time zone, set up log of run times
* pull in all config and common functions
*/
global $mls,$rets;
$mls = $argv[1];
date_default_timezone_set('America/Chicago');
$starttime = date('Y-m-d H:i:s');


require("./mlsconfig/$mls/config.php");
require("./mlsconfig/$mls/mappings.php");
require("./mlsconfig/$mls/transform.php");
require("./img_loader.php");
require("./listing_db_functions.php");
require("./agent_db_functions.php");

//new hotness logging - start log here, write "success" to record later
$logId = startRunLog($mls, $starttime);



//I'm putting mapped records in these
$mappedresults = array();
$mappedagents = array();
$mappedoffices = array();


//PHRETS config and session
require_once("vendor/autoload.php");

$config = new \PHRETS\Configuration;
$config->setLoginUrl("$url")
        ->setUsername("$user")
        ->setPassword("$pass")
        ->setUserAgent("$UA")
        ->setUserAgentPassword("$UAPass")
        ->setRetsVersion("$version");

$rets = new \PHRETS\Session($config);
echo "starting at ".date("c")."\n";
//check connection
if (!($connect = $rets->Login())) {
    echo "\e[0;31mCan't connect to $mls \e[0m\n";
    //may need to add here to schedule a rerun or something
    exit;
}

/* compile a list of mls numbers that we especially DON'T want to delete.
* we need a separate query that returns all existing MLS numbers,
* then mark those listings in db as visible in data
*/

$mlsNumField = $listing['MLSNumber'];
$mlsNums = array();
foreach ($class_and_query as $class => $query) {
    $offsetAmt = 1;
    $mlsNumFinished = false;
    while ($mlsNumFinished == false) {
        $results = $rets->Search('Property', $class, $query,
        [
            'Select' => $mlsNumField,
            'Offset' => $offsetAmt    
        ]);
        $remainingRecords = $results->getTotalResultsCount()."\n";
        $remainingRecords -= $offsetAmt;
        echo "MLS Numbers left to grab: ".$remainingRecords."\n";
        foreach ($results as $result) {
            array_push($mlsNums, $result[$mlsNumField]);
        }

        if ($results->isMaxRowsReached() == 1) {
            echo "\e[0;33mBounced off of limit, applying offset and trying for more records\e[0m\n";
            $offsetAmt+= $offset;
        } else {
            $mlsNumFinished = true;
        }
    }
}
inData($mls, $mlsNums);

echo "Finished with MLS numbers at ".date("c")."\n";
//delete every listing we didn't just mark, then reset
echo "\e[0;31mdeleting listings not seen in current data\e[0m\n";
$deletes = deleteListings($mls);
echo $deletes." listings deleted\n";


//pull in agent data and enter/update agentsimport table
echo "\e[32mStarting Agents class at ".date("c")."\e[0m\n";
$offsetAmt = 1;
$finished = false;

$agentQuery = makeAgentIncremental($mls,$agentQuery,$agent['Timestamp']);
echo "Query:".$agentQuery."\n";

while ($finished == false) {
    $results = $rets->Search(
        $agentResource,
        $agentClass,
        $agentQuery,
        [
            'QueryType' => 'DMQL2',
            'Count' => 1, // count and records
            'Format' => 'COMPACT-DECODED',
            'Limit' => 999999,
            'StandardNames' => 0, // give system names
            //'Select' => queryFields($listing),
            'Offset' => $offsetAmt
        ]
    );
    $remainingRecords = $results->getTotalResultsCount()."\n";
    $remainingRecords -= $offsetAmt;
    echo "Records left to grab: ".$remainingRecords."\n";
    foreach ($results as $record) {
		$newagent = $agent;
		foreach ($newagent as $key => $item) {
			$item = $record[$key];
		}
		array_push($mappedagents, $newagent);
    }

    if ($results->isMaxRowsReached() == 1) {
        echo "\e[0;36mBounced off the limiter, applying offset and trying for more records\e[0m\n";
        $offsetAmt+= $offset;
    } else {
        $finished = true;
    }
    //results are loaded up, now decide what we need to do with them
    foreach ($mappedagents as $agent) {  
        $status = checkAgent($agent['MLS'], $agent['AgentID'], $agent['Timestamp']);
        switch ($status['action']) {
            case "update":
                updateAgent($agent, $status['id']);
                break;
            case "insert":
                insertAgent($agent);
                break;
            case "current":
                //do nothing, should already be marked as "in data"
                break;
        }
    }
}



//loop through the various property classes
foreach ($class_and_query as $class => $query) {
    echo "\e[32mNew property class at ".date("c")."\e[0m\n";
    $offsetAmt = 1;
    $finished = false;

    $query = makeIncremental($mls,$query,$listing['ModificationTimestamp']);
    echo "Query:".$query."\n";

    while ($finished == false) {
        $results = $rets->Search(
            $resource,
            $class,
            $query,
            [
                'QueryType' => 'DMQL2',
                'Count' => 1, // count and records
                'Format' => 'COMPACT-DECODED',
                'Limit' => 999999,
                'StandardNames' => 0, // give system names
                //'Select' => queryFields($listing),
                'Offset' => $offsetAmt
            ]
        );
        $remainingRecords = $results->getTotalResultsCount()."\n";
        $remainingRecords -= $offsetAmt;
        echo "Records left to grab: ".$remainingRecords."\n";
        foreach ($results as $record) {
            //init empty listing using mapping model
            $newlisting = $listing;
            //now go through each listing field and fill if possible
            foreach ($newlisting as $key => $item) {
                //echo $key."\t".$record[$item]."\t".redefineVals($key, $record[$item], $newlisting, $record)."\n";
                $newlisting[$key] = redefineVals($key, $record[$item], $newlisting,$record);
            }

            //let's try to add photos to array at the end while we're at it
            $newlisting['PhotoUrls'] = imageLoader($record, $mediaFormat);
            array_push($mappedresults, $newlisting);
            //var_dump($newlisting);
        }

        if ($results->isMaxRowsReached() == 1) {
            echo "\e[0;36mBounced off the limiter, applying offset and trying for more records\e[0m\n";
            $offsetAmt+= $offset;
        } else {
            $finished = true;
        }
        //results are loaded up, now decide what we need to do with them
        foreach ($mappedresults as $result) {  
            $status = checkListing($result['MLSName'], $result['MLSNumber'], $result['ModificationTimestamp']);
            switch ($status['action']) {
                case "update":
                    updateListing($result, $status['id']);
                    break;
                case "insert":
                    insertListing($result);
                    break;
                case "current":
                    //do nothing, should already be marked as "in data"
                    break;
            }
        }
    }
}
//almost done, let's reset that inData in DB for future runs
resetListings($mls);
resetAgents($mls);


//finish cool new log - mark run as successful
finishRunLog($logId);
?>

