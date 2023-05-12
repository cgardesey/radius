<?php


    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');

        isset($_GET['radiusLineCount']) ? $radiusLineCount = $_GET['radiusLineCount'] : $radiusLineCount = 50;
        isset($_GET['radiusFilter']) ? $radiusFilter = $_GET['radiusFilter'] : $radiusFilter = ".";


	include_once('library/config_read.php');
    $log = "visited page: ";
    $logQuery = "performed query on page: ";
    include('include/config/logging.php');


?>

<?php

    include ("menu-reports-logs.php");
  	
?>	
		
		
		<div id="contentnorightbar">
		
		<h2 id="Intro"><a href="#"  onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','replogsradius.php'); ?>
                :: <?php if (isset($radiusLineCount)) { echo $radiusLineCount . " Lines Count "; } ?>
                   <?php if (isset($radiusFilter)) { echo " with radiusFilter set to " . $radiusFilter; } ?>
		<h144>&#x2754;</h144></a></h2>

		<div id="helpPage" style="display:none;visibility:visible" >
			<?php echo t('helpPage','replogsradius') ?>
			<br/>
		</div>
		<br/>

<?php
	include 'library/exten-radius_log.php';
?>

		</div>
		
		<div id="footer">
		
								<?php
        include 'page-footer.php';
?>
		
		</div>
		
</div>
</div>


</body>
</html>
