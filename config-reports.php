<?php


    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

        
	include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');
	
?>
		
<?php

    include ("menu-config-reports.php");

?>
		
		
		<div id="contentnorightbar">
		
			<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','configmain.php') ?>
			<h144>&#x2754;</h144></a></h2>
                
				<div id="helpPage" style="display:none;visibility:visible" >
					<?php echo t('helpPage','configmain') ?>
					<br/>
				</div>
				<br/>

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
