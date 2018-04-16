<?php

function imageLoader($listing) {
    global $rets, $resourcetype, $mediatype;
    $resultArray = array();
    $images = $rets->GetObject($resourcetype, $mediatype, $listing);
    foreach ($images as $image) {
        $url = $image->getLocation();
        array_push($resultArray, $url);
    }
    return $resultArray;
}

?>