<?php

//login creds
$url = 'http://cwbr.rets.fnismls.com/rets/fnisrets.aspx/CWBR/login?rets-version=rets/1.5';
$user = 'exitrealtyprime21';
$pass = 'cwmls54455';
$UA = 'RETS-Connector/1.2'; 
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

$mediaFormat = "url"; // set to "url" or "binary" - binary means we have to save photos
$resource = 'PROPERTY';// resource is almost always 'property'
$offset = "5000";
// class_and_query - keys are systemname classes, query is what you want(use retsmd to find this)
$class_and_query = array(
    'RE_1' => '(L_StatusCatID=1,3)',
    'LD_2' => '(L_StatusCatID=1,3)',
    'CI_3' => '(L_StatusCatID=1,3)',
    'MF_4' => '(L_StatusCatID=1,3)',
    'FA_5' => '(L_StatusCatID=1,3)'
);
//media search params - not likely to change as much from MLS to MLS
$resourcetype = "Property";
$mediatype = "Photo";
//$mediaclass = "Media";
$mediaIdentifier = "L_ListingID";
//$useMediaClass = false; // T/F, T has separate Media Class Query and F uses GetObject during regular property data pull
/*MLS notes
There is a support Forum that Black Knight runs, it may come in handy some day:
vendorsupport.paragonrels.com
*/
?>
