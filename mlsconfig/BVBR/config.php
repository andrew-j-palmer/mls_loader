<?php

//login creds
$url = 'http://retsgw.flexmls.com:80/rets2_2/Login';
$user = 'bvb.rets.309-4';
$pass = 'drove-cephalus63';
$UA = 'RETS-Connector/1.8';
$UAPass = '123456';
$version = 'RETS/1.7.2';

//listing search params
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
