<?php


$type = $_REQUEST['type'];
$username = $_REQUEST['user'];


if ($type == "daily") {
	daily($username);
} elseif ($type == "monthly") {
	monthly($username);
} elseif ($type == "yearly") {
	yearly($username);
}



function daily($username) {

	
	include 'opendb.php';
	include 'libchart/libchart.php';

	$username = $dbSocket->escapeSimple($username);

	header("Content-type: image/png");

	$chart = new VerticalChart(680,500);

	$sql = "SELECT UserName, sum(AcctOutputOctets) as Downloads, day(AcctStartTime) AS day FROM ".
		$configValues['CONFIG_DB_TBL_RADACCT']." WHERE username='$username' AND acctstoptime>0 AND AcctStartTime>DATE_SUB(curdate(),INTERVAL (DAY(curdate())-1) DAY) AND AcctStartTime< now() GROUP BY day;";
	$res = $dbSocket->query($sql);

	while($row = $res->fetchRow()) {
		$downloads = floor($row[1]/1024/1024);
		$chart->addPoint(new Point("$row[2]", "$downloads"));
	}

	$chart->setTitle("Total Downloads based on Daily distribution");
	$chart->render();

	include 'closedb.php';


}






function monthly($username) {

	
	include 'opendb.php';
	include 'libchart/libchart.php';

	$username = $dbSocket->escapeSimple($username);
	
	header("Content-type: image/png");

	$chart = new VerticalChart(680,500);

	$sql = "SELECT UserName, sum(AcctOutputOctets) as Downloads, MONTHNAME(AcctStartTime) AS month FROM ".
		$configValues['CONFIG_DB_TBL_RADACCT']." WHERE username='$username' GROUP BY month;";
	$res = $dbSocket->query($sql);

	while($row = $res->fetchRow()) {
		$downloads = floor($row[1]/1024/1024);
		$chart->addPoint(new Point("$row[2]", "$downloads"));
	}

	$chart->setTitle("Total Downloads based on Monthly distribution");
	$chart->render();

	include 'closedb.php';
}








function yearly($username) {


	include 'opendb.php';
	include 'libchart/libchart.php';

	$username = $dbSocket->escapeSimple($username);
	
	header("Content-type: image/png");

	$chart = new VerticalChart(680,500);

	$sql = "SELECT UserName, sum(AcctOutputOctets) as Downloads, year(AcctStartTime) AS year FROM ".
		$configValues['CONFIG_DB_TBL_RADACCT']." WHERE username='$username' GROUP BY year;";
	$res = $dbSocket->query($sql);

	while($row = $res->fetchRow()) {
		$downloads = floor($row[1]/1024/1024);
		$chart->addPoint(new Point("$row[2]", "$downloads"));
	}

	$chart->setTitle("Total Downloads based on Yearly distribution");
	$chart->render();

	include 'closedb.php';

}






?>
