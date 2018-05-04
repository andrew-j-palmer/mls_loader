<?php


function redefineVals($key,$val,$newlisting, $record) {
    global $brokeridlength,$valueRedefine,$brokeridlengthoverridearray,$mls;
    $redefineval = '';
    switch ($key) {
           
    /*Trim OfficeKeys
        case 'officekey':
            $brokeridlengthovr = brokeridlengthoverride($brokeridlengthoverridearray, $val);
            $brokeridlengthuse = ($brokeridlengthovr > 0) ? $brokeridlengthovr : $brokeridlength;
            $redefineval = substr($val,0,$brokeridlengthuse);
        break;
    */     
    //Define Country
        case 'AddressCountry':
            $redefineval = 'United States';
        break;

    //Residential,Land,Commercial,MultiFamily,Rental
    //Farm,Other,Common Interest,Condominium
        case 'ListType':
        switch($val) {
            case 'CI_3':
                $redefineval = "Commercial";
            break;
            case 'LD_2':
                $redefineval = "Land";
            break;
            case 'FA_5':
                $redefineval = "Farm";
            break;
            case 'RE_1':
                $redefineval = "Residential";
            break;
            case 'MF_4':
                $redefineval = "MultiFamily";
            break;
        } 
        break;

    //Enter a Common MLS Source Name for all property class imports (used for Grouping)
        case 'MLSName':
            $redefineval = $mls;
        break; 
    //Used if Street Number and Street name are in different fields  
        case 'AddressOneLine':
            if ($newlisting['AddressDisplay'] == 'Y') {
                $addresscat = array(
                    $record[$newlisting['AddressStreetNumber']],
                    $record[$newlisting['AddressStreetDirPrefix']],
                    $record[$newlisting['AddressStreetName']],
                    $record[$newlisting['AddressStreetSuffix']],
                    $record[$newlisting['AddressStreetDirSuffix']],
                    $record[$newlisting['AddressUnitNumber']]
                );
                $redefineval = implode(" ", $addresscat);
                break;
            } else {
                $redefineval = "Adress Not Disclosed";
            }

        break;

    /*Matches Listing Status to Internal Status
        case 'listingstatus':
            if(strtoupper(trim($val)) == 'Active') {
            $redefineval = 'Active';
            } elseif (strtoupper(trim($val)) == 'Pending') {
                $redefineval = 'Under Contract';
            } else {
                $redefineval = 'Active';
            }
        break;
    */

    //Default just return value given
        default:
            $redefineval = trim($val);
        break;
    }
    return $redefineval;
}
?>