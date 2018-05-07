<?php

//login creds
$url = 'https://rets2.navicamls.net/login.aspx';
$user = 'Rets406-072016';
$pass = 'Rets406UzC';
$UA = ''; //shouldn't need
$UAPass = '123456'; //shouldn't need
$version = 'RETS/1.7.2'; //this may vary

/*listing search params - queries may be different every time
* *** DMQL HELP ***
* https://www.flexmls.com/developers/rets/tutorials/dmql-tutorial/
* https://www.tutorialspoint.com/data_mining/dm_query_language.htm
*/

$resource = 'Property'; // resource is almost always 'property'
$mediaFormat = "binary"; // set to "url" or "binary" - binary means we have to save photos
// class_and_query - keys are systemname classes, query is what you want(use retsmd to find this)
$class_and_query = array(
    'COMM' => '(Property_Status=|A,M)',
    'LAND' => '(Property_Status=|A,M)',
    'MFAM' => '(Property_Status=|A,M)',
    'RES' => '(Property_Status=|A,M)'
);

// media search params - not likely to change as much from MLS to MLS
$resourcetype = "Property";
$mediatype = "Photo";
$offset = 1000;
$mediaIdentifier = "MST_MLS_NUMBER";

//MLS notes

?>
