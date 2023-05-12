<?php


    include ("library/checklogin.php");
    $login = $_SESSION['login_user'];

	//setting values for the order by and order type variables
	isset($_REQUEST['orderBy']) ? $orderBy = $_REQUEST['orderBy'] : $orderBy = "username";
	isset($_REQUEST['orderType']) ? $orderType = $_REQUEST['orderType'] : $orderType = "asc";

	$username = $login;
	$type = $_REQUEST['type'];

	//feed the sidebar variables
	$overall_upload_username = $username;

	include_once('library/config_read.php');
    $log = "visited page: ";
    $logQuery = "performed query for user [$username] of type [$type] on page: ";


?>

<?php
        include_once ("library/tabber/tab-layout.php");
?>

<?php
	
	include ("menu-graphs.php");
	
?>		
		
		
		<div id="contentnorightbar">
		
		<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','graphsoverallupload.php'); ?>
		<h144>&#x2754;</h144></a></h2>

		<div id="helpPage" style="display:none;visibility:visible" >
			<?php echo t('helpPage','graphsoverallupload') ?>
			<br/>
		</div>
		<br/>

<div class="tabber">

     <div class="tabbertab" title="Graph">
        <br/>

<?php
    echo "<center>";
    echo "<img src=\"library/graphs-overall-users-upload.php?type=$type&user=$username\" />";
    echo "</center>";
?>
	</div>
     <div class="tabbertab" title="Statistics">
	<br/>
<?php
    include 'library/tables-overall-users-upload.php';
?>
	</div>
</div>


<?php
	include('include/config/logging.php');
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
