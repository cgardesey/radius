<?php 


	include('library/config_read.php');

	$successMsg = $configValues['CONFIG_PAYPAL_SUCCESS_MSG_PRE'];
			
	$refresh = true;

	if (isset($_GET['txnId'])) {
		// txnId variable is set, let's check it against the database

		include('library/opendb.php');

		$txnId = $_GET['txnId'];

		$sql = "SELECT txnId, username, payment_status FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGPAYPAL'].
			" WHERE txnId='".$dbSocket->escapeSimple($txnId)."'";
		$res = $dbSocket->query($sql);

		$row = $res->fetchRow();

		if ( ($row[0] == $txnId) && ($row[2] == "Completed") ) {
			$successMsg = "Your user PIN is: <b>$row[1]</b> <br/>".$configValues['CONFIG_PAYPAL_SUCCESS_MSG_POST'];
			$refresh = false;
		}

		include('library/closedb.php');

	}

?> 


<html>
<head>
<?php
	if ($refresh == true)
		echo '<meta http-equiv="refresh" content="5">';
?>
</head>
<body>

<?php
	echo $configValues['CONFIG_PAYPAL_SUCCESS_MSG_HEADER'];
	echo $successMsg;
?>





</body>
</html>
