<?php

//login creds
$url = 'XXXXXXXXX';
$user = 'XXXX';
$pass = 'XXXXXX';
$UA = 'ShowcaseTech/1.0'; 
$UAPass = 'XXXXXX'; //shouldn't need
$version = 'RETS/1.5'; //this may vary

/*listing search params - queries may be different every time
* incremental - true or false depending on whether you want to grab every record every run
* resource - check retsmd for resource name on particular MLS server
* mediaFormat = default is url (we download later), if this doesn't work change to binary
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/

$offset = 500;
$mediaFormat = "mediaClass"; // set to "url" or "binary" - binary means we have to save photos
$resource = 'Property';// resource is almost always 'property'
// class_and_query - keys are systemname classes, query is what you want(use retsmd to find this)
$class_and_query = array(
    'CND' => '(StandardStatus=301001,301002)',
    'MLS' => '(StandardStatus=301001,301002),(PropertyClassID=3,4,5)',
    'RES' => '(StandardStatus=301001,301002)',
    'RNT' => '(StandardStatus=301001,301002)'
);
//media search params - not likely to change too much from MLS to MLS
$resourcetype = "Media";
$mediaClass = "media";
$mediaIdentifier = "MlsNum";
$PhotoOrderField = "DisplayOrder";
$PhotoUrlField = "PhotoHR";
//MLS notes

?>
