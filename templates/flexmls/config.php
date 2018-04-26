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
* mediaFormat = default is url (we download later), if this doesn't work change to binary
* classes_and_queries - ditto for these. query is in DMQL2 (see below resources for help)
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/
$incremental = false;
$incremental_field = "LIST_134";
$resource = 'Property';
$mlsNumQuery = "*";
$mediaFormat = "url";
$resource = 'Property';
$class_and_query = array(
    'A' => '(LIST_15=1537DVHF564V,PWC_1DDI44AXS447,PWC_1DD44AXS9NC,PWC_1DDI44AXSFXJ)',
    'B' => '(LIST_15=158UJY70DT12,PWC_1DDI449LWHSK,PWC_1DDI449LWN7J,PWC_1DDI449LWRRK)',
    'C' => '(LIST_15=158UJYES18ZT,PWC_1DDI44AXSN60,PWC_1DDI44AXSSFC,PWC_1DDI44AXSXN2)',
    'D' => '(LIST_15=158UJYIPOUFJ,PWC_1DDI44AXT304,PWC_1DDI44AXT8PW,PWC_1DDI44AXTI2S)',
    'E' => '(LIST_15=158UJYMZBIZE,PWC_1DDI44AXTNTP,PWC_1DDI44AXTTJ3,PWC_1DDI44AXTYDX)',
    'F' => '(LIST_15=158UJYU160ZE,PWC_1DDI44AXU1RJ,PWC_1DDI44AXU6RU,PWC_1DDI44AXUAIZ)'
);

//media search params - not likely to change from MLS to MLS
$resourcetype = "Property";
$mediatype = "HiRes";

?>
