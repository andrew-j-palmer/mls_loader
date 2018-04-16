<?php

function imageLoader(&$listing) {
    global $rets, $resourcetype, $mediatype;
    $images = $rets->GetObject($resourcetype, $mediatype, $listing);
    foreach ($images as $image) {
        array_push($listing['ImageArray'], $image['Hires']);
    }
}

?>