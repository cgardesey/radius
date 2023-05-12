<?php

        include('checklogin.php');

        if (isset($_GET['startdate']))
                $startdate = $_GET['startdate'];
        if (isset($_GET['enddate']))
                $enddate = $_GET['enddate'];

        include 'opendb.php';
        include 'libchart/libchart.php';

        header("Content-type: image/png");

        $chart = new VerticalChart(680,500);

        $sql = "SELECT COUNT(*), CONCAT(YEAR(Creationdate),'-',MONTH(Creationdate), '-01') AS Month from ".
                        $configValues['CONFIG_DB_TBL_DALOUSERINFO'].
                        " WHERE CreationDate >='$startdate' AND CreationDate <='$enddate' ".
                        " GROUP BY Month ORDER BY Date(Month);";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow()) {
                $chart->addPoint(new Point("$row[1]", "$row[0]"));
        }

        $chart->setTitle("New Users by Month");
        $chart->render();

        include 'closedb.php';

?>
