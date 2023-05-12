<?php





$configValues['CONFIG_DB_ENGINE'] = 'mysql';
$configValues['CONFIG_DB_HOST'] = 'localhost';
$configValues['CONFIG_DB_PORT'] = '3306';
$configValues['CONFIG_DB_USER'] = 'root';
$configValues['CONFIG_DB_PASS'] = 'dalodevPOLQWS1029';
$configValues['CONFIG_DB_NAME'] = 'radius_bluechip';
$configValues['CONFIG_DB_TBL_RADACCT'] = 'radacct';
$configValues['CONFIG_DB_TBL_RADCHECK'] = 'radcheck';
$configValues['CONFIG_DB_TBL_RADREPLY'] = 'radreply';
$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'] = 'userbillinfo';
$configValues['CONFIG_DB_TBL_DALOUSERINFO'] = 'userinfo';
$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'] = 'billing_plans';

//interval is specified in seconds
$configValues['INTERVAL'] = 60;
$configValues['GRACE'] = 30;


// Expire Accumulative definitions
// defines a threshold of 70% - if accumulative accounts used up 
// more than 70% of their alotted time then they are considered expired 
$configValues['TYPE_ACCUMULATIVE_THRESHOLD'] = 0.7;


// Expire Time To Finish definitions


// Expire Due Login definitions
$configValues['TYPE_DUELOGIN_DAYS_OVERDUE'] = 90;




require_once('DB.php');

function databaseConnect() {

	global $configValues;
	
    $mydbEngine = $configValues['CONFIG_DB_ENGINE'];
    $mydbUser = $configValues['CONFIG_DB_USER'];
    $mydbPass = $configValues['CONFIG_DB_PASS'];
    $mydbHost = $configValues['CONFIG_DB_HOST'];
    $mydbPort = $configValues['CONFIG_DB_Port'];
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



function clearExpiredAccumulative($dbSocket) {

	global $configValues;
	

	
	$sql = '
		# Create the temporary table
		CREATE TEMPORARY TABLE tmptable_1
		# Run the select query to fill the table with the result set
		SELECT
		    DISTINCT('.$configValues['CONFIG_DB_TBL_RADACCT'].'.username) as Username,
		    SUM('.$configValues['CONFIG_DB_TBL_RADACCT'].'.AcctSessionTime) as UserTotalTime,
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planTimeBank as UserAllowedTime
		FROM
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].',
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].',
		    '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].' 
		WHERE
		    '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].'.planName = '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planName
		    AND
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planTimeType = "Accumulative"
		
		    AND
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].'.username = '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].'.username
		GROUP BY
		   '.$configValues['CONFIG_DB_TBL_RADACCT'].'.username, '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planTimeBank
		HAVING (UserTotalTime/UserAllowedTime >= '.$configValues['TYPE_ACCUMULATIVE_THRESHOLD'].')
		;
		';
		
	$res = $dbSocket->query($sql);
	
	dbDeleteRecords($dbSocket, 'tmptable_1');	
}






function clearExpiredTimeToFinish($dbSocket) {

	global $configValues;
	

	
	$sql = '
		# Create the temporary table
		CREATE TEMPORARY TABLE tmptable_2
		# Run the select query to fill the table with the result set
		
		SELECT
		    DISTINCT('.$configValues['CONFIG_DB_TBL_RADACCT'].'.username) as Username,
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].'.acctstarttime,
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planname,
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.plantimetype
		
		FROM
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].',
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].',
		    '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].'
		WHERE
		    '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].'.planName = '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planName
		    AND
		    '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planTimeType = "Time-To-Finish"
		
		    AND
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].'.username = '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].'.username
		GROUP BY
		   '.$configValues['CONFIG_DB_TBL_RADACCT'].'.username, '.$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'].'.planTimeBank
		HAVING
		   (
		       (IFNULL(UNIX_TIMESTAMP(AcctStartTime),0)) / UNIX_TIMESTAMP() > 0.9
		   )
		ORDER BY
		   '.$configValues['CONFIG_DB_TBL_RADACCT'].'.acctstarttime ASC
		;
		';
		
	$res = $dbSocket->query($sql);
	
	dbDeleteRecords($dbSocket, 'tmptable_2');	

}








function clearExpiredDueLogin($dbSocket) {

	global $configValues;
	

	
	$sql = '
		CREATE TEMPORARY TABLE tmptable_3
		SELECT
		    DISTINCT('.$configValues['CONFIG_DB_TBL_RADACCT'].'.username) as Username,
		    AcctStartTime
		FROM
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].'
		WHERE
		    (
		        (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(AcctStartTime) >= '.(86400*$configValues['TYPE_DUELOGIN_DAYS_OVERDUE']).')
		        
		    )
		GROUP BY
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].'.username 
		ORDER BY
		    '.$configValues['CONFIG_DB_TBL_RADACCT'].'.radacctid DESC
		;';

	$res = $dbSocket->query($sql);
	
	dbDeleteRecords($dbSocket, 'tmptable_3');

}



function dbDeleteRecords($dbSocket, $tmpTableName) {
	
	global $configValues;
		
	$sql = '
		# Delete all the records from the related tables
		DELETE '.$configValues['CONFIG_DB_TBL_RADACCT'].' FROM '.$configValues['CONFIG_DB_TBL_RADACCT'].', '.$tmpTableName.'
			WHERE '.$configValues['CONFIG_DB_TBL_RADACCT'].'.username = '.$tmpTableName.'.username; 
		';
	$res = $dbSocket->query($sql);
	
	$sql = '
		DELETE '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].' FROM '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].', '.$tmpTableName.'
			WHERE '.$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].'.username = '.$tmpTableName.'.username;
			';
	$res = $dbSocket->query($sql);
	
	$sql = '
		DELETE '.$configValues['CONFIG_DB_TBL_DALOUSERINFO'].' FROM '.$configValues['CONFIG_DB_TBL_DALOUSERINFO'].', '.$tmpTableName.'
			WHERE '.$configValues['CONFIG_DB_TBL_DALOUSERINFO'].'.username = '.$tmpTableName.'.username;
		';
	$res = $dbSocket->query($sql);
	
	$sql = '
		DELETE '.$configValues['CONFIG_DB_TBL_RADCHECK'].' FROM '.$configValues['CONFIG_DB_TBL_RADCHECK'].', '.$tmpTableName.'
			WHERE '.$configValues['CONFIG_DB_TBL_RADCHECK'].'.username = '.$tmpTableName.'.username;
			';
	$res = $dbSocket->query($sql);
	
	$sql = '
		DELETE '.$configValues['CONFIG_DB_TBL_RADREPLY'].' FROM '.$configValues['CONFIG_DB_TBL_RADREPLY'].', '.$tmpTableName.'
			WHERE '.$configValues['CONFIG_DB_TBL_RADREPLY'].'.username = '.$tmpTableName.'.username;
		';
	$res = $dbSocket->query($sql);
	
	
    if (DB::isError ($res)) 
        die ("<b>Database connection error</b><br/>
            <b>Error Message</b>: " . $res->getMessage () . "<br/>" .
            "<b>Debug</b>: " . $res->getDebugInfo() . "<br/>");
	
	
	return true;
}


$dbh = databaseConnect();

// perform cleanup
clearExpiredDueLogin($dbh);
clearExpiredTimeToFinish($dbh);
clearExpiredAccumulative($dbh);

databaseDisconnect($dbh);


?>
