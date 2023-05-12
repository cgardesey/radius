<?php

	include('checklogin.php');

	include 'opendb.php';
	include 'libchart/libchart.php';

	header("Content-type: image/png");

	$chart = new PieChart(620,320);

	// getting total downloads of days in a month
	$sql = "select ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].".name, count(distinct(UserName)), count(radacctid), ".
			" avg(AcctSessionTime), sum(AcctSessionTime) FROM ".$configValues['CONFIG_DB_TBL_RADACCT'].
			" JOIN ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].
			" ON (".$configValues['CONFIG_DB_TBL_RADACCT'].".calledstationid LIKE ".
			$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].".mac) GROUP BY ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].".name;";
	$res = $dbSocket->query($sql);

	while($row = $res->fetchRow()) {
		$chart->addPoint(new Point("$row[0] ($row[1] users)", "$row[1]"));
	}

	$chart->setTitle("Distribution of Unique users per Hotspot");
	$chart->render();

	include 'closedb.php';




?>


