<?php





$configValues['CONFIG_DB_ENGINE'] = 'mysql';
$configValues['CONFIG_DB_HOST'] = 'localhost';
$configValues['CONFIG_DB_PORT'] = '3306';
$configValues['CONFIG_DB_USER'] = 'daloradius';
$configValues['CONFIG_DB_PASS'] = 'daloradius';
$configValues['CONFIG_DB_NAME'] = 'radius';
$configValues['CONFIG_DB_TBL_RADACCT'] = 'radacct';

//interval is specified in seconds
$configValues['INTERVAL'] = 60;
//grace time
$configValues['GRACE'] = 30;



require_once('DB.php');

function databaseConnect() {

	global $configValues;
	
    $mydbEngine = $configValues['CONFIG_DB_ENGINE'];
    $mydbUser = $configValues['CONFIG_DB_USER'];
    $mydbPass = $configValues['CONFIG_DB_PASS'];
    $mydbHost = $configValues['CONFIG_DB_HOST'];
    $mydbPort = $configValues['CONFIG_DB_PORT'];
    $mydbName = $configValues['CONFIG_DB_NAME'];

    $dbConnectString = $mydbEngine . "://".$mydbUser.":".$mydbPass."@".
               $mydbHost.":".$mydbPort."/".$mydbName;

    $dbSocket = DB::connect($dbConnectString);

    if (DB::isError ($dbSocket))
        die ("<b>Database connection error</b><br/>
            <b>Error Message</b>: " . $dbSocket->getMessage () . "<br/>" .
            "<b>Debug</b>: " . $dbSocket->getDebugInfo() . "<br/>");

	return $dbSocket;

}


function databaseDisconnect($dbSocket) {

    $dbSocket->disconnect();

}



function clearStaleSessions($dbSocket) {

	global $configValues;
	
	
	// get all entries which we are stale sessions

	
	$sql = " UPDATE ".
				$configValues['CONFIG_DB_TBL_RADACCT'].
			" SET ".
				$configValues['CONFIG_DB_TBL_RADACCT'].".AcctStopTime=NOW()".
				",".
				$configValues['CONFIG_DB_TBL_RADACCT'].".AcctTerminateCause='Stale-Session'".
			" WHERE ".
				"((UNIX_TIMESTAMP(NOW()) - (UNIX_TIMESTAMP(".$configValues['CONFIG_DB_TBL_RADACCT'].".acctstarttime) + ".$configValues['CONFIG_DB_TBL_RADACCT'].".acctsessiontime)) > (".$configValues['INTERVAL']."+".
				$configValues['GRACE']."))".
			" AND ".
				" (AcctStopTime = '0000-00-00 00:00:00' OR AcctStopTime IS NULL) ";

	$res = $dbSocket->query($sql);


}


$dbh = databaseConnect();
clearStaleSessions($dbh);
databaseDisconnect($dbh);


?>
