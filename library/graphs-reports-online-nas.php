<?php


       include('checklogin.php');

       include 'opendb.php';
       include 'libchart/libchart.php';

       header("Content-type: image/png");

       $chart = new VerticalChart(500, 300);

       $sql = "SELECT shortname,count(username) FROM
				".$configValues['CONFIG_DB_TBL_RADACCT']." Left Join
				".$configValues['CONFIG_DB_TBL_RADNAS']." ON nasname =
				".$configValues['CONFIG_DB_TBL_RADACCT'].".nasipaddress WHERE (acctstoptime
				IS NULL OR acctstoptime =  '0000-00-00 00:00:00') group by nasipaddress;";
       $res = $dbSocket->query($sql);
       while($row = $res->fetchRow()) {
               $chart->addPoint(new Point("$row[0]", "$row[1]"));
       }
       $chart->setTitle("Online Users By NAS");
       $chart->render();

       include 'closedb.php';


?>