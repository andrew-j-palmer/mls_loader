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
$starttime = date('c');

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
}

//loop through the various property classes
foreach ($class_and_query as $class => $query) {

    //check if incremental = true, if so add to query
    if ($incremental) {
        $query = makeIncremental($mls,$query,$increment_field);
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
    echo "results: ".$results->getTotalResultsCount()."\n";

    foreach ($results as $record) {

        //init empty listing using mapping model
        $newlisting = $listing;

        //now go through each listing field and fill if possible
        foreach ($newlisting as $key => $item) {
            //echo $key."\t".$record[$item]."\t".redefineVals($key, $record[$item], $newlisting, $record)."\n";
            $newlisting[$key] = redefineVals($key, $record[$item], $newlisting,$record);
        }
        //let's try to add photos to array at the end while we're at it
        $newlisting['PhotoUrls'] = imageLoader($newlisting['MLSNumber']);
        //"InData" sets a timer for deletion
        $newlisting['InData'] = date("c");
        array_push($mappedresults, $newlisting);
    }


    //results are loaded up, now decide what we need to do with them
    foreach ($mappedresults as $result) {  
        //echo $result['InData'];
        $status = checkListing($result['MLSName'], $result['MLSNumber'], $result['ModificationTimestamp']);
        switch ($status['action']) {
            case "update":
                updateListing($result, $status['id']);
                break;
            case "insert":
                insertListing($result);
                break;
            case "current":
                InData($result['InData'], $status['id']);
                break;
        }
    }
}
/*more old-ass logging
$logstring .= "- finished with ".count($mappedresults)." transformed results at ".date('m/d/Y h:i:s')."\n";
file_put_contents($logfile, $logstring, FILE_APPEND);
*/

//finish cool new log - mark run as successful
finishRunLog($logId);
?>

