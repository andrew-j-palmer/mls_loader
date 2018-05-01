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
              //convert boolean yes/no to 1/0

        case 'AllowIDX':
        if (strtoUpper($val) == 'Y') {
            $redefineval = 1;
        } else {
            $redefineval = 0;
        }
        break;
    //Residential,Land,Commercial,MultiFamily,Rental
    //Farm,Other,Common Interest,Condominium                           
        case 'ListType':
        switch($val) {
            case 'RES':
                $redefineval = "Residential";
                break;
            case 'LAND':
                $redefineval = "Land";
                break;
            case 'MFAM':
                $redefineval = "MultiFamily";
                break;
            case 'COMM':
                $redefineval = "Commercial";
                break;
        } 
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
    );
    $redefineval = implode(" ", $addresscat);
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