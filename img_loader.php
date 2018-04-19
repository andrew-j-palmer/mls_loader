<?php

/*NEED TO ADD PHOTOTIMESTAMP COMPARE*/

function imageLoader($listing) {
    //was going to make an array of images inside of listing array. that's stupid, just use a string
    global $rets, $resourcetype, $mediatype;
    $resultlist = "";
    $images = $rets->GetObject($resourcetype, $mediatype, $listing, '*', 1);

    foreach ($images as $image) {
        $resultlist .= $image -> getlocation();
        //need to add separator or the urls run together
        $resultlist .= " ";
    }
    return $resultlist;
}

?>