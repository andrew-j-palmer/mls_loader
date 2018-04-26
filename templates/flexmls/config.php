<?php

//login creds
$url = 'http://retsgw.flexmls.com:80/rets2_2/Login';
$user = '*username*';
$pass = '*pass*';
$UA = ''; //shouldn't need
$UAPass = '123456'; //shouldn't need
$version = 'RETS/1.5'; //this may vary

/*listing search params - queries may be different every time
* incremental - true or false depending on whether you want to grab every record every run
* resource - check retsmd for resource name on particular MLS server
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/
$incremental = false;
$incremental_field ="";
$resource = 'Property';
$class = 'A';
$listingquery = '(LIST_15=1537DVHF564V,PWC_1DDI44AXS447,PWC_1DD44AXS9NC,PWC_1DDI44AXSFXJ)';

//media search params - not likely to change from MLS to MLS
$resourcetype = "Property";
$mediatype = "HiRes";

?>
