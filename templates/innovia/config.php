<?php

//login creds
$url = 'http://brm.rets.mlxinnovia.com/brm/login';
$user = 'farrell456';
$pass = 'kpDOa0qBlZ6UZwIRtIIw';
$UA = ''; 
$UAPass = '123456'; //shouldn't need
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
$incremental = false;
$incremental_field ="ModificationTimestamp";
$MLSNumQuery = "*";
$mediaFormat = "binary";
$resource = 'Property';
$class_and_query = array(
    'COM' => '(ListingStatus=$,A,P)',
    'LND' => '(ListingStatus=$,A,P)',
    'MUL' => '(ListingStatus=$,A,P)',
    'RNT' => '(ListingStatus=$,A,P)',
    'RES' => '(ListingStatus=$,A,P)'
);
//media search params - not likely to change too much
$resourcetype = "Property";
$mediatype = "HQPhoto";

//MLS notes

?>
