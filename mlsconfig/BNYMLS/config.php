<?php

//login creds
$url = 'http://matrixrets.bnymls.com/rets/login.ashx';
$user = 'thomas123';
$pass = 'lfdVAPWlZ02l93D3LTgu';
$UA = 'ShowcaseRETS/1.0'; 
$UAPass = 'ShowcaseRETS/1.0'; //shouldn't need
$version = 'RETS/1.5'; //this may vary

//listing search params - queries will be different every time
$resource = 'Property';
$class_and_query = array(
    'Listing' => '(Status=A)'
);
//media search params - not likely to change from MLS to MLS
$resourcetype = "Property";
$mediatype = "Largephoto";


//MLS notes

?>
