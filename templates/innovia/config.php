<?php

//login creds
$url = 'http://brm.rets.mlxinnovia.com/brm/login';
$user = 'XXXXX';
$pass = 'XXXXX';
$UA = ''; 
$UAPass = 'XXXXXX'; //shouldn't need
$version = 'RETS/1.5'; //this may vary

/*listing search params - queries may be different every time
* incremental - true or false depending on whether you want to grab every record every run
* incremental_field -> should be MLS's timestamp field
* MLSNumQuery = this varies, try the star and if that doesn't work, adjust
* mediaFormat = default is url (we download later), if this doesn't work change to binary
* resource - check retsmd for resource name on particular MLS server
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/

$mediaFormat = "binary"; // set to "url" or "binary" - binary means we have to save photos
$resource = 'Property'; // resource is almost always 'property'
// class_and_query - keys are systemname classes, query is what you want(use retsmd to find this)
$class_and_query = array(
    'COM' => '(ListingStatus=$,A,P)',
    'LND' => '(ListingStatus=$,A,P)',
    'MUL' => '(ListingStatus=$,A,P)',
    'RNT' => '(ListingStatus=$,A,P)',
    'RES' => '(ListingStatus=$,A,P)'
);
//media search params - not likely to change as much from MLS to MLS
$resourcetype = "Property";
$mediatype = "HQPhoto";

//MLS notes

?>
