<?php
/* mls to be run - name is pulled from argv,
*which is used to open a folder with config vars 
*/
global $mls,$rets;
$mls = $argv[1];


$logstring = "Started $mls pull at ".date('m/d/Y h:i:s');
//pull in config, mappings, data transforms
require("./mlsconfig/$mls/config.php");
require("./mlsconfig/$mls/mappings.php");
require("./mlsconfig/$mls/transform.php");
require("./img_loader.php");

//I'm putting mapped listings in here
$mappedresults = array();


date_default_timezone_set('America/Chicago');

//PHRETS
require_once("vendor/autoload.php");

$config = new \PHRETS\Configuration;
$config->setLoginUrl("$url")
        ->setUsername("$user")
        ->setPassword("$pass")
        ->setRetsVersion("$version");

$rets = new \PHRETS\Session($config);

//check connection
if ($connect = $rets->Login()) {
    echo "connected to $mls \n";
} else {
    echo "connection failed\n";
}

$results = $rets->Search(
    $resource,
    $class,
    $listingquery,
    [
        'QueryType' => 'DMQL2',
        'Count' => 1, // count and records
        'Format' => 'COMPACT-DECODED',
        'Limit' => 10000,
        'StandardNames' => 0, // give system names
    ]
);


foreach ($results as $record) {
    //init empty listing using mapping model
    $newlisting = $listing;

    //now go through each listing field and fill if possible
    foreach ($newlisting as $key => $item) {
        //echo $key."\t".$record[$item]."\t".redefineVals($key, $record[$item], $newlisting, $record)."\n";
        $newlisting[$key] = redefineVals($key, $record[$item], $newlisting,$record);

    }
    //let's try to add photos to array at the end while we're at it
    imageLoader($newlisting['MLSNumber']);
    array_push($mappedresults, $newlisting);
}
//var_dump($mappedresults);
echo $logstring."- finished with ".count($mappedresults)." transformed results at".date('m/d/Y h:i:s');
?>
