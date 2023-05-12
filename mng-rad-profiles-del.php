<?php


    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');

	isset($_REQUEST['profile']) ? $profile = $_REQUEST['profile'] : $profile = "";
	isset($_REQUEST['attribute']) ? $attribute = $_REQUEST['attribute'] : $attribute = "";
	isset($_REQUEST['tablename']) ? $tablename = $_REQUEST['tablename'] : $tablename = "";

	isset($_REQUEST['profile_delete_assoc']) ? $removeProfileAssoc = $_REQUEST['profile_delete_assoc'] : $removeProfileAssoc = "";
	if ($removeProfileAssoc == '1')
		$removeProfileAssoc = true;
	else
		$removeProfileAssoc = false;


	$logAction = "";
	$logDebugSQL = "";

	$showRemoveDiv = "block";

	if ( (isset($_REQUEST['profile'])) && (!(isset($_REQUEST['attribute']))) && (!(isset($_REQUEST['tablename']))) ) {

		$allProfiles = "";
		$isSuccessful = 0;

		if (!is_array($profile))
			$profile = array($profile, NULL);

		foreach ($profile as $variable=>$value) {

			if (trim($variable) != "") {

				$profile = $value;
				$allProfiles .= $profile . ", ";

				include 'library/opendb.php';

				// delete all attributes associated with a profile
				$sql = "DELETE FROM ".$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].
					" WHERE GroupName='".$dbSocket->escapeSimple($profile)."'";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";

				$sql = "DELETE FROM ".$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].
					" WHERE GroupName='".$dbSocket->escapeSimple($profile)."'";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";

				// delete all user associations with the profile
				if ($removeProfileAssoc == true) {
					$sql = "DELETE FROM ".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].
						" WHERE GroupName='".$dbSocket->escapeSimple($profile)."'";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}


				$successMsg = "Deleted profile(s): <b> $allProfiles </b>";
				$logAction .= "Successfully deleted profile(s) [$allProfiles] on page: ";

				include 'library/closedb.php';

			}  else {
				$failureMsg = "no profile was entered, please specify a profile to remove from database";
				$logAction .= "Failed deleting profile(s) [$allProfiles] on page: ";
			}

		} //foreach

		$showRemoveDiv = "none";

	} else  if ( (isset($_REQUEST['profile'])) && (isset($_REQUEST['attribute'])) && (isset($_REQUEST['tablename'])) ) {



		include 'library/opendb.php';

                if (isset($attribute)) {
                        if (preg_match('/__/', $attribute))
                                list($columnId, $attribute) = explode("__", $attribute);
                        else
                                $attribute = $attribute;
                }

		$sql = "DELETE FROM ".$dbSocket->escapeSimple($tablename)." WHERE GroupName='".$dbSocket->escapeSimple($profile).
				"' AND Attribute='".$dbSocket->escapeSimple($attribute)."' AND id=".$dbSocket->escapeSimple($columnId);
		$res = $dbSocket->query($sql);
		$logDebugSQL .= $sql . "\n";

		$successMsg = "Deleted attribute: <b> $attribute </b> for profile(s): <b> $profile </b> from database";
		$logAction .= "Successfully deleted attribute [$attribute] for profile [$profile] on page: ";

		include 'library/closedb.php';

		$showRemoveDiv = "none";
	}

	include_once('library/config_read.php');
	$log = "visited page: ";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<script src="library/javascript/pages_common.js" type="text/javascript"></script>

<title>2C UFi Web Management Server</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />

</head>


<?php
	include ("menu-mng-rad-profiles.php");
?>

		<div id="contentnorightbar">

				<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','mngradprofilesdel.php') ?>
				:: <?php if (isset($profile)) { echo $profile; } ?><h144>&#x2754;</h144></a></h2>

				<div id="helpPage" style="display:none;visibility:visible" >
					<?php echo t('helpPage','mngradprofilesdel') ?>
					<br/>
				</div>
                <?php
					include_once('include/management/actionMessages.php');
                ?>

	<div id="removeDiv" style="display:<?php echo $showRemoveDiv ?>;visibility:visible" >
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <fieldset>

                <h302> <?php echo t('title','ProfileInfo') ?> </h302>
                <br/>

                <label for='profile' class='form'>Profile Name</label>
                <input name='profile[]' type='text' id='profile' value='<?php echo $profile ?>' tabindex=100 />
                <br/>

                <label for='profile' class='form'>Remove all user associations with this profile(s)</label>
                <input name='profile_delete_assoc' type='checkbox' id='profile_delete_assoc' value='1' tabindex=100 />
                <br/>

                <br/><br/>
                <hr><br/>

                <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' class='button' />

        </fieldset>

	        </form>
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
