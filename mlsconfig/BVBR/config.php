<?php

//login creds
$url = 'http://retsgw.flexmls.com:80/rets2_2/Login';
$user = 'bvb.rets.309-4';
$pass = 'drove-cephalus63';
$UA = 'RETS-Connector/1.8';
$UAPass = '123456';
$version = 'RETS/1.7.2';

/*listing search params - queries will be different every time
* incremental - true or false depending on whether you want to grab every record every run
* resource - check retsmd for resource name on particular MLS server
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/
$incremental = true;
$increment_field = "LIST_87";
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
