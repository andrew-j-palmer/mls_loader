<?php

//login creds
$url = 'XXXXXXXXXX';
$user = 'XXXXXXXX';
$pass = 'XXXXXXX';
$UA = 'RETS-Connector/1.8';
$UAPass = 'XXXXXXX';
$version = 'RETS/1.7.2';

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
    'A' => '(LIST_15=OYACM1TCX9K,OYACM1TCXGJ)',
    'B' => '(LIST_15=REQY5SUJUZH,REQY5SUM1NI)',
    'C' => '(LIST_15=RERBA8RQXT0,RERBA8RSXT6)',
    'D' => '(LIST_15=REQY6CWXPJ2,REQY6CWZW5Q)'
);

//media search params
$resourcetype = "Property";
$mediatype = "HiRes";

?>
