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
        ->setRetsVersion("$version");

$rets = new \PHRETS\Session($config);

//check connection
if (!($connect = $rets->Login())) {
    echo "Can't connect to $mls \n";
    //may need to add here to schedule a rerun or something
    exit;
}

/* compile a list of mls numbers that we especially DON'T want to delete.
* if incremental (default), we need a separate query that returns all existing MLS numbers,
* then mark those listings in db as visible in data
*/
if ($incremental) {
    $mlsNumArrayKeys = array_keys($class_and_query);
    $mlsNumField = $listing['MLSNumber'];
    foreach ($mlsNumArrayKeys as $class) {
        $results = $rets->Search('Property', $class, $mlsNumQuery, ['Select' => $mlsNumField]);
        $totalListings = $results->getTotalResultsCount();
        echo $totalListings." listings present in data\n";
        foreach ($results as $r) {
            inData($mls, $r[$mlsNumField]);
        }
    }
    //delete every listing we didn't just mark, then reset
    echo "deleting listings not seen in current data\n";
    $deletes = deleteListings($mls);
    echo $deletes." listings deleted\n";
} else {
    //if not incremental, just wipe them all out
    deleteListings($mls);
    echo "deleting all listings (full pull)...\n";
}

//loop through the various property classes
foreach ($class_and_query as $class => $query) {

    //if incremental = true add timestamp to query
    if ($incremental) {
        echo "Adding Incremental timestamp to existing query...\n";
        $query = makeIncremental($mls,$query,$increment_field);
        echo "Incremental Query:".$query."\n";
    }

    $results = $rets->Search(
        $resource,
        $class,
        $query,
        [
            'QueryType' => 'DMQL2',
            'Count' => 1, // count and records
            'Format' => 'COMPACT-DECODED',
            'Limit' => 10000,
            'StandardNames' => 0, // give system names
        ]
    );
    echo "Class results: ".$results->getTotalResultsCount()."\n";

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
        $newlisting['PhotoUrls'] = imageLoader($newlisting['MLSNumber'], $mediaFormat);
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

