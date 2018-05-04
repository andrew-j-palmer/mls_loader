<?php

function redefineVals($key,$val,$newlisting, $record) {
    global $valueRedefine,$brokeridlengthoverridearray,$mls;
    $redefineval = '';
    switch ($key) {

    //Define Country
        case 'AddressCountry':
            $redefineval = 'United States';
        break;

    //Residential,Land,Commercial,MultiFamily,Rental
    //Farm,Other,Common Interest,Condominium
        case 'ListType':

            case "3":
                $redefineval = "Lots And Land";
            break;
            case "4":
                $redefineval = "MultiFamily";
            break;
            case "5":
                $redefineval = "Commercial";
            break;
            case 'RES':
                $redefineval = "Residential";
            break;
            case 'RNT':
                $redefineval = "Rental";
            break;
            case 'CND':
                $redefineval = "Condominium";
            break; 
        break;

    //Enter a Common MLS Source Name for all property class imports (used for Grouping)
        case 'MLSName':
            $redefineval = $mls;
        break; 
    //Used if Street Number and Street name are in different fields  
        case 'AddressOneLine':
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

    //Matches Listing Status to Internal Status
        case 'listingstatus':
            //if(strtoupper(trim($val)) == 'Active') {
            $redefineval = 'Active';
            /*} elseif (strtoupper(trim($val)) == 'Pending') {
                $redefineval = 'Under Contract';
            } else {
                $redefineval = 'Active';
            }*/
        break;
    

    //Default just return value given
        default:
            $redefineval = trim($val);
        break;
    }
    return $redefineval;
}
?>