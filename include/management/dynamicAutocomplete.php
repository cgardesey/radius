<?php


include('../../library/checklogin.php');




if(isset($_GET['getAjaxAutocompletePaymentName'])) {

        $getAjaxAutocompletePaymentName = $_GET['getAjaxAutocompletePaymentName'];

        if ( (isset($getAjaxAutocompletePaymentName)) && ($getAjaxAutocompletePaymentName) ) {

                include '../../library/opendb.php';

                $sql = "SELECT distinct(value) as value FROM ".$configValues['CONFIG_DB_TBL_DALOPAYMENTTYPES'].
                        " WHERE value LIKE '$getAjaxAutocompletePaymentName%' ORDER BY value ASC";
                $res = $dbSocket->query($sql);

                while($row = $res->fetchRow()) {
                        echo "$row[0]###$row[0]|";
                }

                include '../../library/closedb.php';
        }

}




if(isset($_GET['getAjaxAutocompleteContactPerson'])) {

        $getAjaxAutocompleteContactPerson = $_GET['getAjaxAutocompleteContactPerson'];

	if ( (isset($getAjaxAutocompleteContactPerson)) && ($getAjaxAutocompleteContactPerson) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(contactperson), id FROM ".$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].
			" WHERE contactperson LIKE '$getAjaxAutocompleteContactPerson%' ORDER BY contactperson ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}





if(isset($_GET['getAjaxAutocompleteBatchNames'])) {

        $getAjaxAutocompleteBatchNames = $_GET['getAjaxAutocompleteBatchNames'];

	if ( (isset($getAjaxAutocompleteBatchNames)) && ($getAjaxAutocompleteBatchNames) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(batch_name) as batchName FROM ".$configValues['CONFIG_DB_TBL_DALOBATCHHISTORY'].
			" WHERE batch_name LIKE '$getAjaxAutocompleteBatchNames%' ORDER BY batch_name ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}



if(isset($_GET['getAjaxAutocompleteNASHost'])) {

        $getAjaxAutocompleteNASHost = $_GET['getAjaxAutocompleteNASHost'];

	if ( (isset($getAjaxAutocompleteNASHost)) && ($getAjaxAutocompleteNASHost) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(nasname) as nasName FROM ".$configValues['CONFIG_DB_TBL_RADNAS'].
			" WHERE nasName LIKE '$getAjaxAutocompleteNASHost%' ORDER BY nasName ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}



if(isset($_GET['getAjaxAutocompleteHGHost'])) {

	$getAjaxAutocompleteHGHost = $_GET['getAjaxAutocompleteHGHost'];

	if ( (isset($getAjaxAutocompleteHGHost)) && ($getAjaxAutocompleteHGHost) ) {

		include '../../library/opendb.php';

		$sql = "SELECT distinct(nasipaddress) as nasipaddress FROM ".$configValues['CONFIG_DB_TBL_RADHG'].
		" WHERE nasipaddress LIKE '$getAjaxAutocompleteHGHost%' ORDER BY nasipaddress ASC";
		$res = $dbSocket->query($sql);

		while($row = $res->fetchRow()) {
				echo "$row[0]###$row[0]|";
		}

		include '../../library/closedb.php';
	}

}




if(isset($_GET['getAjaxAutocompleteGroupName'])) {

        $getAjaxAutocompleteGroupName = $_GET['getAjaxAutocompleteGroupName'];

	if ( (isset($getAjaxAutocompleteGroupName)) && ($getAjaxAutocompleteGroupName) ) {

	        include '../../library/opendb.php';

	        $sql = "(SELECT distinct(GroupName) AS GroupName FROM ".$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].
			" WHERE GroupName LIKE '$getAjaxAutocompleteGroupName%' ORDER BY GroupName ASC) ".
                        " UNION (SELECT distinct(GroupName) AS GroupName FROM ".$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].
			" WHERE GroupName LIKE '$getAjaxAutocompleteGroupName%' ORDER BY GroupName ASC)";

	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}






if(isset($_GET['getAjaxAutocompleteRateName'])) {

        $getAjaxAutocompleteRateName = $_GET['getAjaxAutocompleteRateName'];

	if ( (isset($getAjaxAutocompleteRateName)) && ($getAjaxAutocompleteRateName) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(rateName) as rateName FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGRATES'].
			" WHERE rateName LIKE '$getAjaxAutocompleteRateName%' ORDER BY rateName ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}





if(isset($_GET['getAjaxAutocompleteBillingPlans'])) {

        $getAjaxAutocompleteBillingPlans = $_GET['getAjaxAutocompleteBillingPlans'];

	if ( (isset($getAjaxAutocompleteBillingPlans)) && ($getAjaxAutocompleteBillingPlans) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(planName) as planName FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].
			" WHERE planName LIKE '$getAjaxAutocompleteBillingPlans%' ORDER BY planName ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}




if(isset($_GET['getAjaxAutocompleteHotspots'])) {

        $getAjaxAutocompleteHotspots = $_GET['getAjaxAutocompleteHotspots'];

	if ( (isset($getAjaxAutocompleteHotspots)) && ($getAjaxAutocompleteHotspots) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(Name) as Hotspot FROM ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].
			" WHERE Name LIKE '$getAjaxAutocompleteHotspots%' ORDER BY Name ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}




if(isset($_GET['getAjaxAutocompleteUsernames'])) {

        $getAjaxAutocompleteUsernames = $_GET['getAjaxAutocompleteUsernames'];


	if ( (isset($getAjaxAutocompleteUsernames)) && ($getAjaxAutocompleteUsernames) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(Username) as Username FROM ".$configValues['CONFIG_DB_TBL_RADCHECK'].
			" WHERE Username LIKE '$getAjaxAutocompleteUsernames%' ORDER BY Username ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';

	}

}





if(isset($_GET['getAjaxAutocompleteAttributes'])) {

        $getAjaxAutocompleteAttributes = $_GET['getAjaxAutocompleteAttributes'];

	if ( (isset($getAjaxAutocompleteAttributes)) && ($getAjaxAutocompleteAttributes) ) {
	
	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(Attribute) as Attribute FROM dictionary WHERE Attribute LIKE '$getAjaxAutocompleteAttributes%' ".
                "ORDER BY Vendor ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';

	}

}




if(isset($_GET['getAjaxAutocompleteVendorName'])) {

        $getAjaxAutocompleteVendorName = $_GET['getAjaxAutocompleteVendorName'];

	if ( (isset($getAjaxAutocompleteVendorName)) && ($getAjaxAutocompleteVendorName) ) {

	        include '../../library/opendb.php';

	        $sql = "SELECT distinct(Vendor) as VendorName FROM ".$configValues['CONFIG_DB_TBL_DALODICTIONARY'].
			" WHERE Vendor LIKE '$getAjaxAutocompleteVendorName%' ORDER BY VendorName ASC";
	        $res = $dbSocket->query($sql);

	        while($row = $res->fetchRow()) {
	                echo "$row[0]###$row[0]|";
	        }

	        include '../../library/closedb.php';
	}

}


?>
