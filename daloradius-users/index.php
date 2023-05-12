<?php


    include ("library/checklogin.php");
    $login = $_SESSION['login_user'];

    include ("menu-home.php");

	include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');

?>

<script src="library/javascript/pages_common.js" type="text/javascript"></script>

	<div id="contentnorightbar">

		<h2 id="Intro"><a href="#"></a></h2>
		<p>

<?php
	include 'library/exten-welcome_page.php';
	include_once('include/management/userReports.php');
	userPlanInformation($login, 1);
	userSubscriptionAnalysis($login, 1);			// userSubscriptionAnalysis with argument set to 1 for drawing the table
	userConnectionStatus($login, 1);			// userConnectionStatus (same as above)
?>
		</p>
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
