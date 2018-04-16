<?php
include "./db_creds.php";

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


function checklisting($listing, $timestamp) {
    //compare downloaded timestamp to listing in db. if downloaded is newer, we need to update
    global $db;

    //may need to convert date strings into real date objects, not sure yet
    $query = $db->prepare("select ModificationTimestamp from listingsimport where mls = ?");
    $update = $query-> execute(array($listing));
    if ($timestamp > $update) {
        return "update";
    } elseif ($timestamp == $update) {
        return "current";
    } else {
        return "insert";
    }
}

function insertListing($listing) {
    global $db;
    $insert = $db->prepare("insert into listingsimport (
        AddressArea, AddressCity, AddressCountry,
        AddressCounty, AddressOneLine, AddressStateOrProvince, AddressStreetDirPrefix,
        AddressStreetDirSuffix, AddressStreetName, AddressStreetNumber, AddressStreetSuffix, 
        AddressUnitNumber, AllowIDX, Basement, BathsFull, BathsHalf, BathsOneQuarter, 
        BathsThreeQuarter, BathsTotal, Bedrooms, DrivingDirections, AddressDisplay, 
        ElementarySchool, GPSLatitude, GPSLongitude, HighSchool, ListingAgentEmail,
        ListingAgentFirstname, ListingAgentFullName, ListingAgentID, ListingAgentLastName, 
        ListingAgentPhone1, ListingAgentPhone2, ListingAgentUrl, ListingOfficeAddress, 
        ListingOfficeCity, ListingOfficeID, ListingOfficeName, ListingOfficeState, ListPrice, 
        ListStatus, ListType, LotSize, MiddleSchool, MLSName, MLSNumber, ModificationTimestamp, 
        OpenHousedate, OpenHouseEnd, OpenHouseStart, Parking, PhotoCount, PhotoModificationTimestamp, 
        Pool, PostalCode, PropertyStyle, PublicRemarks, SchoolDistrict, SquareFootage, Stories, 
        TotalRooms, TotalUnits, Waterfront, YearBuilt, ImageArray
        ) values (
             ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
             ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
             ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )");
    $insert = $db->execute($listing);
}

function updateListing($listing, $id) {
    global $db;
    $update = $db->prepare("update listingsimport set 
        AddressArea = ?, AddressCity = ?, AddressCountry = ?,
        AddressCounty = ?, AddressOneLine = ?, AddressStateOrProvince = ?, AddressStreetDirPrefix = ?,
        AddressStreetDirSuffix = ?, AddressStreetName = ?, AddressStreetNumber = ?, AddressStreetSuffix = ?, 
        AddressUnitNumber = ?, AllowIDX = ?, Basement = ?, BathsFull = ?, BathsHalf = ?, BathsOneQuarter = ?, 
        BathsThreeQuarter = ?, BathsTotal = ?, Bedrooms = ?, DrivingDirections = ?, AddressDisplay = ?, 
        ElementarySchool = ?, GPSLatitude = ?, GPSLongitude = ?, HighSchool = ?, ListingAgentEmail = ?,
        ListingAgentFirstname = ?, ListingAgentFullName = ?, ListingAgentID = ?, ListingAgentLastName = ?, 
        ListingAgentPhone1 = ?, ListingAgentPhone2 = ?, ListingAgentUrl = ?, ListingOfficeAddress = ?, 
        ListingOfficeCity = ?, ListingOfficeID = ?, ListingOfficeName = ?, ListingOfficeState = ?, ListPrice = ?, 
        ListStatus = ?, ListType = ?, LotSize = ?, MiddleSchool = ?, MLSName = ?, MLSNumber = ?, ModificationTimestamp = ?, 
        OpenHousedate = ?, OpenHouseEnd = ?, OpenHouseStart = ?, Parking = ?, PhotoCount = ?, PhotoModificationTimestamp = ?, 
        Pool = ?, PostalCode = ?, PropertyStyle = ?, PublicRemarks = ?, SchoolDistrict = ?, SquareFootage = ?, Stories = ?, 
        TotalRooms = ?, TotalUnits = ?, Waterfront = ?, YearBuilt = ?, ImageArray = ?
        where id = ?");
}
?>