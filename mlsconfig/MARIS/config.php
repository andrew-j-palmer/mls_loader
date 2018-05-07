<?php

//login creds
$url = 'http://matrixrets.marismatrix.com/rets/login.ashx';
$user = 'ShowCaseIDX';
$pass = 'ScD@1Qty';
$UA = 'RETS-Connector/1.2'; 
$UAPass = '123456'; //shouldn't need
$version = 'RETS/1.8'; //this may vary

/*listing search params - queries may be different every time
* incremental - true or false depending on whether you want to grab every record every run
* resource - check retsmd for resource name on particular MLS server
* mediaFormat = default is url (we download later), if this doesn't work change to binary
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/

$mediaFormat = "url"; // set to "url" or "binary" - binary means we have to save photos
$resource = 'PROPERTY';// resource is almost always 'property'
$offset = "5000";
// class_and_query - keys are systemname classes, query is what you want(use retsmd to find this)
$class_and_query = array(
    'RES' => '(Status=A,C,D)',
    'COM' => '(Status=A,C,D)',
    'FARM' => '(Status=A,C,D)',
    'MUL' => '(Status=A,C,D)',
    'LND' => '(Status=A,C,D)'
);
//media search params - not likely to change as much from MLS to MLS
$resourcetype = "PROPERTY";
$mediatype = "1600x1200";
$mediaclass = "Media";
$mediaIdentifier = "matrix_unique_id";
//MLS notes

?>
