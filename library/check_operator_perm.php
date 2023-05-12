<?php


	// we format the php script file in the following manner:
	// we replace every instance of the - symbol with _ and we completely
	// remove the .php extension
	// this formatting is done to match the exact entry for the page as it
	// appears in the operators_acl table
	$currFile = basename($_SERVER['SCRIPT_NAME']);
	$currFile = str_replace("-", "_", $currFile);
	$currFile = str_replace(".php", "", $currFile);

    include 'library/opendb.php';
	
    $sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOOPERATORS_ACL'].
		" WHERE operator_id='".$_SESSION['operator_id']."' AND file='".$currFile."'";
		
    $res = $dbSocket->query($sql);
    $row = $res->fetchRow(DB_FETCHMODE_ASSOC);


	// the following checks if the access field is set to 1 or 0, 1 is access granted
	// to the page and 0 means no access, in which case we forward to an error page
	if ($row['access'] == 0) {
		header('Location: msg-error-permissions.php');
		exit;
	}
	
	include 'library/closedb.php';

?>
