<?php

//login creds
$url = 'http://matrixrets.*MLSNAMEHERE*.com/rets/login.ashx';
$user = 'XXXXXX';
$pass = 'XXXXX';
$UA = 'ShowcaseRETS/1.0'; 
$UAPass = 'XXXXXXXXX'; //shouldn't need
$version = 'RETS/1.5'; //this may vary

/*listing search params - queries may be different every time
* incremental - true or false depending on whether you want to grab every record every run
* resource - check retsmd for resource name on particular MLS server
* mediaFormat = default is url (we download later), if this doesn't work change to binary
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/

$mediaFormat = "url"; // set to "url" or "binary" - binary means we have to save photos
$resource = 'Property';// resource is almost always 'property'
// class_and_query - keys are systemname classes, query is what you want(use retsmd to find this)
$class_and_query = array(
    'Listing' => '(Status=A)'
);
//media search params - not likely to change too much from MLS to MLS
$resourcetype = "Property";
$mediatype = "Largephoto";

//MLS notes

?>
