<?php

function imageLoader($listing) {
    global $rets, $resourcetype, $mediatype;
    $resultArray = array();
    $images = $rets->GetObject($resourcetype, $mediatype, $listing);
    foreach ($images as $image) {
        array_push($resultArray, $image['Hires']);
    }
    return $resultArray;
}

?>