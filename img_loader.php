<?php


//so the below works for servers that allow getting photo locations (the good kind). 
function imageLoader($listing, $mediaFormat) {
    echo "**IMAGELOADER** ->  ";
    //was going to make an array of images inside of listing array. that's stupid, just use a string
    global $rets, $resourcetype, $mediatype, $mls;
    if ($mediaFormat === "url") {
        echo "getting photo URLS\n";
        $resultlist = "";
        $images = $rets->GetObject($resourcetype, $mediatype, $listing, '*', 1);
        foreach ($images as $image) {
            $resultlist .= $image -> getlocation();
            //need to add separator or the urls run together
            $resultlist .= "|";
        }
        return $resultlist;
    } else {
        echo "Binary data files\n";
        //if the above doesn't work, we'll have to change $mediaFormat in config to "binary"
        //and save binary image data locally
        $filepath = './Photos/'.$mls;
        if ( (file_exists($filepath)) && (is_dir($filepath))) {
            echo "writing image files to $filepath...\n";
        } else {
            echo "directory structure doesn't exist, attempting to create $filepath...\n";
            mkdir($filepath, 0777, true);
        }
        //time for image stuff here, this is for ULS, need to tweak
        $images = $rets->GetObject($resourcetype, $mediatype, $listing, '*');
        var_dump($images); exit;
        foreach ($images as $image) {
            if ($image['success']) {
                $filefullpath = $filepath.'/'.$image['Content-ID'].'-'.$image['Object-ID'].'.jpg';
                file_put_contents($filefullpath, $image['Data']);
            }

        }
        return $resultlist;
    }
}

?>