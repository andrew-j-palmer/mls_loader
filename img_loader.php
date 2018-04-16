<?php

function imageLoader($listing) {
    global $rets, $resourcetype, $mediatype;
    $images = $rets->GetObject($resourcetype, $mediatype, $listing);
}

?>