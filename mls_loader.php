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

/*old and busted logging
$logstring = "Started $mls pull at ".$starttime;
$logfile = "./mlsconfig/$mls/datarun.log";
*/

require("./mlsconfig/$mls/config.php");
require("./mlsconfig/$mls/mappings.php");
require("./mlsconfig/$mls/transform.php");
require("./img_loader.php");
require("./db_functions.php");

//new hotness logging - start log here, write "success" to record later
$logId = startRunLog($mls, $starttime);



//I'm putting mapped listings in here
$mappedresults = array();


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

//check connection
if (!($connect = $rets->Login())) {
    echo "Can't connect to $mls \n";
    //may need to add here to schedule a rerun or something
    exit;
}

/* compile a list of mls numbers that we especially DON'T want to delete.
* we need a separate query that returns all existing MLS numbers,
* then mark those listings in db as visible in data
*/

$mlsNumField = $listing['MLSNumber'];

foreach ($class_and_query as $class => $query) {
    $offsetAmt = 1;
    $mlsNumFinished = false;
    while ($mlsNumFinished == false) {
        $results = $rets->Search('Property', $class, $query,
        [
            'Select' => $mlsNumField,
            'Offset' => $offsetAmt    
        ]);
        $totalListings = $results->getTotalResultsCount();
        echo $totalListings." listings present in data\n";
        foreach ($results as $r) {
            inData($mls, $r[$mlsNumField]);
        }
        if ($results->isMaxRowsReached() == 1) {
            echo "Bounced off of limit, applying offset and trying for more records\n";
            $offsetAmt+= $offset;
        } else {
            $mlsNumFinished = true;
        }
    }
}

//delete every listing we didn't just mark, then reset
echo "deleting listings not seen in current data\n";
$deletes = deleteListings($mls);
echo $deletes." listings deleted\n";


//loop through the various property classes
foreach ($class_and_query as $class => $query) {
    $offsetAmt = 1;
    $finished = false;

    echo "Adding Timestamp to existing query...\n";
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
                'Offset' => $offsetAmt
            ]
        );
        $remainingRecords = $results->getTotalResultsCount()."\n";
        $remainingRecords -= $offsetAmt;
        echo "Records: ".$remainingRecords;
        foreach ($results as $record) {
            //init empty listing using mapping model
            $newlisting = $listing;
            //now go through each listing field and fill if possible
            foreach ($newlisting as $key => $item) {
                //echo $key."\t".$record[$item]."\t".redefineVals($key, $record[$item], $newlisting, $record)."\n";
                $newlisting[$key] = redefineVals($key, $record[$item], $newlisting,$record);
            }
            $newlisting['inData'] = 1;
            //let's try to add photos to array at the end while we're at it
            $newlisting['PhotoUrls'] = imageLoader($record, $mediaFormat);
            array_push($mappedresults, $newlisting);
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

        if ($results->isMaxRowsReached() == 1) {
            echo "Bounced off the limiter, applying offset and trying for more records\n";
            $offsetAmt+= $offset;
        } else {
            $finished = true;
        }
    }
}
//almost done, let's reset that inData in DB for future runs
resetListings($mls);


/*more old-ass logging
$logstring .= "- finished with ".count($mappedresults)." transformed results at ".date('m/d/Y h:i:s')."\n";
file_put_contents($logfile, $logstring, FILE_APPEND);
*/

//finish cool new log - mark run as successful
finishRunLog($logId);
?>

