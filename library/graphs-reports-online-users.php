<?php

	include('checklogin.php');

	include 'opendb.php';
	include 'libchart/libchart.php';

	header("Content-type: image/png");

	$chart = new PieChart(620,320);

	// getting total users
	$sql = "SELECT DISTINCT(UserName) FROM ".$configValues['CONFIG_DB_TBL_RADCHECK'];
	$res = $dbSocket->query($sql);
	$totalUsers = $res->numRows();

	// get total users online
	$sql = "SELECT DISTINCT(UserName) FROM ".$configValues['CONFIG_DB_TBL_RADACCT']." WHERE (AcctStopTime is NULL OR AcctStopTime = '0000-00-00 00:00:00')";
	$res = $dbSocket->query($sql);
	$totalUsersOnline = $res->numRows();

	if ($totalUsers != 0) {
		$totalUsersOffline = $totalUsers - $totalUsersOnline;
		if ($totalUsersOnline == 0) {
			$chart->addPoint(new Point("$totalUsersOffline ($totalUsersOffline users offline)", "$totalUsersOffline"));
		} else {
			$chart->addPoint(new Point("$totalUsersOffline ($totalUsersOffline users offline)", "$totalUsersOffline"));
			$chart->addPoint(new Point("$totalUsersOnline ($totalUsersOnline users online)", "$totalUsersOnline"));
		}
	}

	$chart->setTitle("Online users");
	$chart->render();

	include 'closedb.php';




?>


