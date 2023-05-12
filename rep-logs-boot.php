<?php


    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');

        isset($_GET['bootLineCount']) ? $bootLineCount = $_GET['bootLineCount'] : $bootLineCount = 50;
        isset($_GET['bootFilter']) ? $bootFilter = $_GET['bootFilter'] : $bootFilter = ".";


	include_once('library/config_read.php');
    $log = "visited page: ";
    $logQuery = "performed query on page: ";
    include('include/config/logging.php');

?>

<?php

    include ("menu-reports-logs.php");
  	
?>	
		<div id="contentnorightbar">
		
		<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','replogsboot.php'); ?>
                :: <?php if (isset($bootLineCount)) { echo $bootLineCount . " Lines Count "; } ?>
                   <?php if (isset($bootFilter)) { echo " with filter set to " . $bootFilter; } ?>
		<h144>&#x2754;</h144></a></h2>

		<div id="helpPage" style="display:none;visibility:visible" >
			<?php echo t('helpPage','replogsboot') ?>
			<br/>
		</div>
		<br/>

<?php
	include 'library/exten-boot_log.php';
?>

                <?php
                        include_once('include/management/actionMessages.php');
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
