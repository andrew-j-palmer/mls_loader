<?php
include "./db_creds.php";


$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//ACTIVATE SCOOTER MODE (prevent int cast to string maybe)
$scootermode = $db->query("set session sql_mode = '';");
$scootermode->execute();
$superscootermode = $db->query("set session sql_mode = 'NO_ENGINE_SUBSTITUTION';");
$superscootermode->execute();
//SCOOTER MODE ENGAGED

function startRunLog($mls, $datetime) {
    global $db;
    $runlog = $db->prepare('insert into prev_runs (mlsname, time) values (?, ?)');
    $id = $runlog->execute(array($mls, $datetime));
    return $db->lastInsertId();
}

function finishRunLog($id) {
    global $db;
    $runlog = $db->prepare('update prev_runs set success = 1 where id = ?');
    $runlog->execute(array($id));
}

function makeIncremental($mls, $query, $field) {
    global $db;
    $lastrun = $db->prepare('select time from prev_runs where mlsname = ? and success = 1 order by time desc limit 1');
    $lastrun-> execute(array($mls));
    $result = $lastrun->fetch();

    //if there's no successful runs in db, we want full pull (new mls)
    //therefore, don't add incremental query
    if ($result) {
        $incremental = '('.$field.'='.$result['time'].'+),'.$query;
        return $incremental;
    } else {
        return $query;
    }
}

function checkListing($mls, $mlsnum, $timestamp) {
    /* NEEDS TO:
    - see if an id exists for a listing (see if we have it yet)
    - if we DON'T have id, insert record
    - if we DO have id, compare downloaded timestamp to listing in db
    - if timestamps don't match, we need to update
    - if we have id and timestamps match, do nothing

    -IMPORTANT-
    need to RETURN ID if we have it
    */
    global $db;
    $query = $db->prepare("select * from listingsimport where MLSNumber = ? and MLSName = ? limit 1");
    $query-> execute(array($mlsnum, $mls));
    $return = $query->fetchAll();
    $update = array_pop($return);
    //echo $timestamp." - ".$update['ModificationTimestamp']."\n";
    $state = array('action' => '', 'id' => '');
    if (isset($update['id'])) {
        $state['id'] = $update['id'];
        if (strcmp($timestamp, $update['ModificationTimestamp']) !== 0) {
            $state['action'] = "update";
            return $state;
        } 
        else {
        //timestamps match, don't do nuthin'
        $state['action'] = "current";
        return $state;
        }
    }
    else {
        $state['action'] = "insert";
        return $state; 
    }

}

function insertListing($listing) {
    global $db;
    $insert = $db->prepare("insert into listingsimport (
        InData, AddressArea, AddressCity, AddressCountry,
        AddressCounty, AddressOneLine, AddressStateOrProvince, AddressStreetDirPrefix,
        AddressStreetDirSuffix, AddressStreetName, AddressStreetNumber, AddressStreetSuffix, 
        AddressUnitNumber, AllowIDX, Basement, BathsFull, BathsHalf, BathsOneQuarter, 
        BathsThreeQuarter, BathsTotal, Bedrooms, DrivingDirections, AddressDisplay, 
        ElementarySchool, GPSLatitude, GPSLongitude, HighSchool, ListingAgentEmail,
        ListingAgentFirstname, ListingAgentFullName, ListingAgentID, ListingAgentLastName, 
        ListingAgentPhone1, ListingAgentPhone2, ListingAgentUrl, ListingOfficeAddress, 
        ListingOfficeCity, ListingOfficeID, ListingOfficeName, ListingOfficeState, ListPrice, 
        ListStatus, ListType, LotSize, MiddleSchool, MLSName, MLSNumber, ModificationTimestamp, 
        OpenHousedate, OpenHouseEnd, OpenHouseStart, Parking, PhotoCount, PhotoModificationTimestamp, PhotoUrls,
        Pool, PostalCode, PropertyStyle, PublicRemarks, SchoolDistrict, SquareFootage, Stories, 
        TotalRooms, TotalUnits, Waterfront, YearBuilt
        ) values (
            :InData, :AddressArea, :AddressCity, :AddressCountry,
            :AddressCounty, :AddressOneLine, :AddressStateOrProvince, :AddressStreetDirPrefix,
            :AddressStreetDirSuffix, :AddressStreetName, :AddressStreetNumber, :AddressStreetSuffix, 
            :AddressUnitNumber, :AllowIDX, :Basement, :BathsFull, :BathsHalf, :BathsOneQuarter, 
            :BathsThreeQuarter, :BathsTotal, :Bedrooms, :DrivingDirections, :AddressDisplay, 
            :ElementarySchool, :GPSLatitude, :GPSLongitude, :HighSchool, :ListingAgentEmail,
            :ListingAgentFirstname, :ListingAgentFullName, :ListingAgentID, :ListingAgentLastName, 
            :ListingAgentPhone1, :ListingAgentPhone2, :ListingAgentUrl, :ListingOfficeAddress, 
            :ListingOfficeCity, :ListingOfficeID, :ListingOfficeName, :ListingOfficeState, :ListPrice, 
            :ListStatus, :ListType, :LotSize, :MiddleSchool, :MLSName, :MLSNumber, :ModificationTimestamp, 
            :OpenHousedate, :OpenHouseEnd, :OpenHouseStart, :Parking, :PhotoCount, :PhotoModificationTimestamp, :PhotoUrls, 
            :Pool, :PostalCode, :PropertyStyle, :PublicRemarks, :SchoolDistrict, :SquareFootage, :Stories, 
            :TotalRooms, :TotalUnits, :Waterfront, :YearBuilt
        )");
    $insert->execute($listing);
}

function updateListing($listing, $id) {
    $listing['id'] = $id;
    //var_dump($listing); exit;
    global $db;
    //DEFINITELY 67 bound parameters
    $update = $db->prepare("update listingsimport set 
        InData = :InData, AddressArea = :AddressArea, AddressCity = :AddressCity, AddressCountry = :AddressCountry, 
        AddressCounty = :AddressCounty, AddressOneLine = :AddressOneLine, AddressStateOrProvince = :AddressStateOrProvince, AddressStreetDirPrefix = :AddressStreetDirPrefix,
        AddressStreetDirSuffix = :AddressStreetDirSuffix, AddressStreetName = :AddressStreetName, AddressStreetNumber = :AddressStreetNumber, AddressStreetSuffix = :AddressStreetSuffix, 
        AddressUnitNumber = :AddressUnitNumber, AllowIDX = :AllowIDX, Basement = :Basement, BathsFull = :BathsFull, BathsHalf = :BathsHalf, BathsOneQuarter = :BathsOneQuarter, 
        BathsThreeQuarter = :BathsThreeQuarter, BathsTotal = :BathsTotal, Bedrooms = :Bedrooms, DrivingDirections = :DrivingDirections, AddressDisplay = :AddressDisplay, 
        ElementarySchool = :ElementarySchool, GPSLatitude = :GPSLatitude, GPSLongitude = :GPSLongitude, HighSchool = :HighSchool, ListingAgentEmail = :ListingAgentEmail,
        ListingAgentFirstname = :ListingAgentFirstname, ListingAgentFullName = :ListingAgentFullName, ListingAgentID = :ListingAgentID, ListingAgentLastName = :ListingAgentLastName, 
        ListingAgentPhone1 = :ListingAgentPhone1, ListingAgentPhone2 = :ListingAgentPhone2, ListingAgentUrl = :ListingAgentUrl, ListingOfficeAddress = :ListingOfficeAddress, 
        ListingOfficeCity = :ListingOfficeCity, ListingOfficeID = :ListingOfficeID, ListingOfficeName = :ListingOfficeName, ListingOfficeState = :ListingOfficeState, ListPrice = :ListPrice, 
        ListStatus = :ListStatus, ListType = :ListType, LotSize = :LotSize, MiddleSchool = :MiddleSchool, MLSName = :MLSName, MLSNumber = :MLSNumber, ModificationTimestamp = :ModificationTimestamp, 
        OpenHousedate = :OpenHousedate, OpenHouseEnd = :OpenHouseEnd, OpenHouseStart = :OpenHouseStart, Parking = :Parking, PhotoCount = :PhotoCount, PhotoModificationTimestamp = :PhotoModificationTimestamp, 
        PhotoUrls = :PhotoUrls, Pool = :Pool, PostalCode = :PostalCode, PropertyStyle = :PropertyStyle, PublicRemarks = :PublicRemarks, SchoolDistrict = :SchoolDistrict, SquareFootage = :SquareFootage, Stories = :Stories, 
        TotalRooms = :TotalRooms, TotalUnits = :TotalUnits, Waterfront = :Waterfront, YearBuilt = :YearBuilt
        where id = :id");
    $update->execute($listing);
}

function inData($timestamp, $id) {
    global $db;
    $update = $db->prepare("update listingsimport set InData = ? where id = ?");
    $update->execute(array($timestamp, $id));
}
?>