<?php

//login creds
$url = 'http://retsgw.flexmls.com:80/rets2_3/Login';
$user = 'fl.rets.erp';
$pass = 'bleat-tonia46';
$UA = ''; //shouldn't need
$UAPass = '123456'; //shouldn't need
$version = 'RETS/1.7.2'; //this may vary

/*listing search params - queries will be different every time
* incremental - true or false depending on whether you want to grab every record every run
* mediaFormat = default is url (we download later), if this doesn't work change to binary
* resource - check retsmd for resource name on particular MLS server
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/
$incremental = true;
$increment_field = "LIST_87";
$MLSNumQuery = "*";
$mediaFormat = "url";
$resource = 'Property';
$class_and_query = array(
    'A' => '(LIST_15=12LL26N0CFUH,12LL26N0CKTY,PWC_15429SGZYQIT)',
    'B' => '(LIST_15=12MKUJQH3QE8,12MKUJQH471V,PWC_15429SI5IHF3)',
    'C' => '(LIST_15=12MKULNSLMH4,12MKULNSM049,PWC_15429SI5IO7B)',
    'D' => '(LIST_15=12MKV68TFZZA,12MKV68TGEQG,PWC_15429SI5IV6P)',
    'E' => '(LIST_15=12ML73ZZOBCU,12ML73ZZOR40,PWC_15429SI5J1XZ)',
    'F' => '(LIST_15=12MKV6FH8HUD,12MKV6FH8VXQ,PWC_15429SI5J9U2)',
);

//media search params - not likely to change from MLS to MLS
$resourcetype = "Property";
$mediatype = "HiRes";

?>
