<?php
//listing mappings
//for FlexMLS, will need minimal customization

/*Need transform for:

    - total bath
    - AllowIDX
*/
$listing = array(
"inData" => 1,
"AddressArea" => "Subdivision",
"AddressCity" => "City",
"AddressCountry" => "",
"AddressCounty" => "Country",
"AddressOneLine" => "",
"AddressStateOrProvince" => "State",
"AddressStreetDirPrefix" => "Direction",
"AddressStreetDirSuffix" => "Addr_2",
"AddressStreetName" => "Address",
"AddressStreetNumber" => "Street_Num",
"AddressStreetSuffix" => "",
"AddressUnitNumber" => "",
"AllowIDX" => "Internet",
"Basement" => "Basement",
"BathsFull" => "Full_Bath",
"BathsHalf" => "Half_Bath",
"BathsOneQuarter" => "",
"BathsThreeQuarter" => "",
"BathsTotal" => "",
"Bedrooms" => "Bedroom",
"DrivingDirections" => "Directions",
"AddressDisplay" => "",
"ElementarySchool" => "Bus_to_School",
"GPSLatitude" => "Latitude",
"GPSLongitude" => "Longitude",
"HighSchool" => "",
"ListingAgentEmail" => "rets_la_email",
"ListingAgentFirstname" => "rets_la_first_name",
"ListingAgentFullName" => "",
"ListingAgentID" => "rets_list_agt_id",
"ListingAgentLastName" => "rets_la_last_name",
"ListingAgentPhone1" => "rets_la_phone1",
"ListingAgentPhone2" => "rets_la_phone2",
"ListingAgentUrl" => "rets_la_url",
"ListingOfficeAddress" => "listing_office_address",
"ListingOfficeCity" => "rets_lo_mail_city",
"ListingOfficeID" => "off_Number",
"ListingOfficeName" => "rets_lo_name",
"ListingOfficeState" => "rets_lo_mail_state",
"ListPrice" => "List_Price",
"ListStatus" => "Property_Status",
"ListType" => "",
"LotSize" => "Acres",
"MiddleSchool" => "",
"MLSName" => "",
"MLSNumber" => "MST_MLS_NUMBER",
"ModificationTimestamp" => "sys_Last_Modified",
"OpenHousedate" => "",
"OpenHouseEnd" => "",
"OpenHouseStart" => "",
"Parking" => "Car_Strg_Cap",
"PhotoCount" => "rets_photo_count",
"PhotoModificationTimestamp" => "rets_photo_timestamp",
"PhotoUrls" => "",
"Pool" => "",
"PostalCode" => "ZipCode",
"PropertyStyle" => "Property_Type",
"PublicRemarks" => "Remarks",
"SchoolDistrict" => "",
"SquareFootage" => "Apx_SqFt",
"Stories" => "",
"TotalRooms" => "",
"TotalUnits" => "",
"Waterfront" => "",
"YearBuilt" => "Year_Blt",
);

$agent = array(
    "AgentID" => '',
    "AgentEmail" => '',
    "AgentPhone1" => '',
    "AgentPhone2" => '',
    "AgentUrl" => '',
    "MLS" => '',
    "OfficeID" => ''
);

$office = array(
    "MLS" => '',
    "OfficeAddress" => '',
    "OfficeEmail" => '',
    "OfficeID" => '',
    "OfficeName" => '',
    "OfficePhone" => '',
    "OfficeState" => '',
    "OfficeStreetname" => '',
    "OfficeStreetNumber" => '',
    "OfficeUnitNumber" => '',
    "OfficeZip" => ''
)

?>