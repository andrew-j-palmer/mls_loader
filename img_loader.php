<?php


//so the below works for servers that allow getting photo locations (the good kind). 
function imageLoader($listing, $mediaFormat) {
    //echo "**IMAGELOADER** ->  ";
    //was going to make an array of images inside of listing array. that's stupid, just use a string
    global $rets, $resourcetype, $mediatype, $mls, $useMediaClass, $mediaClass,
     $mediaIdentifier, $PhotoOrderField, $PhotoUrlField;

    $resultlist = "";

    //this portion uses GetObject as a sort of sub-query within the regular property search
    if ($mediaFormat === "url") {
        //echo "getting photo URLS\n";
        $images = $rets->GetObject($resourcetype, $mediatype, $listing[$mediaIdentifier], '*', 1);
        foreach ($images as $image) {
            $resultlist .= $image -> getlocation();
            //need to add separator or the urls run together
            $resultlist .= "|";
        }
        return $resultlist;
    } elseif ($mediaFormat === "binary") {
        //echo "Binary data files - ";
        //if the above doesn't work, we'll have to change $mediaFormat in config to "binary"
        //and save binary image data locally
        $filepath = './Photos/'.$mls;
        if ( (file_exists($filepath)) && (is_dir($filepath))) {
            /*add something here to determine if we need new photos or not. I don't
            want to keep writing the same images every pull like I'm doing now */
            //echo "writing image files to $filepath...\n";
        } else {
            //echo "directory structure doesn't exist, attempting to create $filepath...\n";
            mkdir($filepath, 0777, true);
        }
        //time for image stuff here, this is for URLS, need to tweak
        $images = $rets->GetObject($resourcetype, $mediatype, $listing[$mediaIdentifier], '*');
        
        foreach ($images as $image) {
            $mlsnum = $image->getContentId();
            $mlsnum .= $image->getObjectId();
            $filenameHash = hash('md5', $mlsnum);
            $data = $image->getContent();
            $filefullpath = $filepath.'/'.$filenameHash.'.jpg';
            file_put_contents($filefullpath, $data);
            $resultlist .=$filefullpath;
            $resultlist .= "|";
        }
        return $resultlist;
    }
    else {
        // use this portion if separate query to class "media" is necessary
        $mediaquery = '('.$mediaIdentifier.'=|'.$listing[$mediaIdentifier].')';
        //echo $mediaquery."\n";
        $images = $rets->Search($resourcetype, $mediaClass, $mediaquery,
        [
            'QueryType' => 'DMQL2',
            'Count' => 1, // count and records
            'Format' => 'COMPACT-DECODED',
            'Limit' => 999999,
            'StandardNames' => 0, // give system names
        ]);
        $sortArray = array();
        foreach ($images as $image) {
            $sortArray += [$image[$PhotoOrderField] => $image[$PhotoUrlField]];
        }
        ksort($sortArray);
        $resultlist = implode("|", $sortArray);
        return $resultlist;
    }
}
?>
