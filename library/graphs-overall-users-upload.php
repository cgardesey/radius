<?php

 
include('checklogin.php');

$type = $_REQUEST['type'];
$username = $_REQUEST['user'];
$size = $_REQUEST['size'];
switch ($size) {
	case "gigabytes":
		$sizeDivision = "1073741824"; 
		break;
	case "megabytes":
		$sizeDivision = "1048576";
		break;
	default:
		$sizeDivision = "1048576";
		break;
}

if ($type == "daily") {
	daily($username);
} elseif ($type == "monthly") {
	monthly($username);
} elseif ($type == "yearly") {
	yearly($username);
}



function daily($username) {

	global $sizeDivision;
	global $size;
	
	include 'opendb.php';
	include 'libchart/libchart.php';

	$username = $dbSocket->escapeSimple($username);
	
	header("Content-type: image/png");

	$chart = new VerticalChart(680,500);

	$sql = "SELECT UserName, sum(AcctInputOctets) as Uploads, day(AcctStartTime) AS day FROM ".
		$configValues['CONFIG_DB_TBL_RADACCT']." WHERE username='$username' AND acctstoptime>0 AND AcctStartTime>DATE_SUB(curdate(),INTERVAL (DAY(curdate())-1) DAY) AND AcctStartTime< now() GROUP BY day;";
	$res = $dbSocket->query($sql);


	while($row = $res->fetchRow()) {
		$uploads = floor($row[1]/$sizeDivision);
		$chart->addPoint(new Point("$row[2]", "$uploads"));
	}

	$chart->setTitle("Total Uploads based on Daily distribution ($size)");
	$chart->render();

	include 'closedb.php';


}






function monthly($username) {

	global $sizeDivision;
	global $size;
	
	include 'opendb.php';
	include 'libchart/libchart.php';

	$username = $dbSocket->escapeSimple($username);

	header("Content-type: image/png");
	
	$chart = new VerticalChart(680,500);

	$sql = "SELECT UserName, sum(AcctInputOctets) as Uploads, MONTHNAME(AcctStartTime) AS month FROM ".
		$configValues['CONFIG_DB_TBL_RADACCT']." WHERE username='$username' GROUP BY month;";
	$res = $dbSocket->query($sql);

	while($row = $res->fetchRow()) {
		$uploads = floor($row[1]/$sizeDivision);
		$chart->addPoint(new Point("$row[2]", "$uploads"));
	}

	$chart->setTitle("Total Uploads based on Monthly distribution ($size)");
	$chart->render();

	include 'closedb.php';
}








function yearly($username) {

	global $sizeDivision;
	global $size;
	
	include 'opendb.php';
	include 'libchart/libchart.php';

	$username = $dbSocket->escapeSimple($username);
	
	header("Content-type: image/png");

	$chart = new VerticalChart(680,500);

	$sql = "SELECT UserName, sum(AcctInputOctets) as Uploads, year(AcctStartTime) AS year FROM ".
		$configValues['CONFIG_DB_TBL_RADACCT']." WHERE username='$username' GROUP BY year;";
	$res = $dbSocket->query($sql);

	while($row = $res->fetchRow()) {
		$uploads = floor($row[1]/$sizeDivision);
		$chart->addPoint(new Point("$row[2]", "$uploads"));
	}

	$chart->setTitle("Total Uploads based on Yearly distribution ($size)");
	$chart->render();

	include 'closedb.php';

}






?>
