<?php

//login creds
$url = 'http://retsgw.flexmls.com:80/rets2_2/Login';
$user = '*username*';
$pass = '*pass*';
$UA = ''; //shouldn't need
$UAPass = '123456'; //shouldn't need
$version = 'RETS/1.5'; //this may vary

//listing search params - queries will be different every time
$resource = 'Property';
$class = 'A';
$listingquery = '(LIST_15=1537DVHF564V,PWC_1DDI44AXS447,PWC_1DD44AXS9NC,PWC_1DDI44AXSFXJ)';

//media search params - not likely to change from MLS to MLS
$resourcetype = "Property";
$mediatype = "HiRes";

?>
