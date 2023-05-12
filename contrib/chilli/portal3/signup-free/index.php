<?php



session_start();                                                // we keep a session to save the captcha key

	$status = "firstload";

        if (isset($_POST['submit'])) {

                isset($_POST['firstname']) ? $firstname = $_POST['firstname'] : $firstname = "";
                isset($_POST['lastname']) ? $lastname = $_POST['lastname'] : $lastname = "";
                isset($_POST['email']) ? $email = $_POST['email'] : $email = "";

                $captchaKey = substr($_SESSION['key'],0,5);
                $formKey = $_POST['formKey'];
                if ( $formKey == $captchaKey ) {

                        if ( ($firstname) && ($lastname) ) {

                                include('library/opendb.php');
                                include('include/common/common.php');


                                $firstname = $dbSocket->escapeSimple($firstname);
                                $lastname = $dbSocket->escapeSimple($lastname);
                                $email = $dbSocket->escapeSimple($email);



                                $rand = createPassword($configValues['CONFIG_USERNAME_LENGTH'], $configValues['CONFIG_USER_ALLOWEDRANDOMCHARS']);
                                $username = $configValues['CONFIG_USERNAME_PREFIX'] . $rand;

                                $password = createPassword($configValues['CONFIG_PASSWORD_LENGTH'], $configValues['CONFIG_USER_ALLOWEDRANDOMCHARS']);


                                $sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADCHECK']." (id, Username, Attribute, op, Value) ".
                                        " VALUES (0, '$username', 'User-Password', '==', '$password')";
                                $res = $dbSocket->query($sql);


                                $sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_DALOUSERINFO']." (username, firstname, lastname, email) ".
                                        " VALUES ('$username', '$firstname', '$lastname', '$email')";
                                $res = $dbSocket->query($sql);



                                if (isset($configValues['CONFIG_GROUP_NAME']) && $configValues['CONFIG_GROUP_NAME'] != "") {
                                        $sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." (UserName, GroupName, priority) ".
                                                " VALUES ('$username', '".$configValues['CONFIG_GROUP_NAME']."', '".$configValues['CONFIG_GROUP_PRIORITY']."')";
                                        $res = $dbSocket->query($sql);
                                }


                                include('library/closedb.php');

				$status = "success";
                        } else {
				$status = "fieldsFailure";
                        } 

                } else {
			$status = "captchaFailure";
                } 

        } 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Sign-Up</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<script src="library/javascript/common.js" type="text/javascript"></script>
<body onLoad="return setFocus();">
<div id="wrapper">
  <div id="header">
    <div id="nav">	<a href="index.html">Sign-Up</a> &nbsp;|&nbsp; 
			<a href="#">Terms Of Service</a> &nbsp;|&nbsp; 
			<a href="#">About us</a> &nbsp;|&nbsp; 
			<a href="#">Contact us</a> &nbsp;|&nbsp; 
     </div>
    <div id="bg"></div>
  </div>
  <div id="main-content">
    <div id="left-column">
      <div id="logo"><img src="images/big-paw.gif" alt="Pet Logo" width="42" height="45" align="left" />
		<span class="logotxt1">daloRADIUS</span>
		<span class="logotxt2">user Sign-Up</span><br />
      		<span style="margin-left:15px;">daloRADIUS, driving smart hotspots to the limit</span></div>
      <div class="box">

        <h1>Sign-Up</h1>
	<p>

	<?php



		include("library/daloradius.conf.php");

		function showForm() {

			include("library/daloradius.conf.php");

			echo "<b>".$configValues['CONFIG_SIGNUP_MSG_TITLE']."</b>
				<br/><br/>
				<form name='signup' action='".$_SERVER['PHP_SELF']."' method='post'>

				<ul>
				        First name:<li> <input type='text' value='' name='firstname' /> <br/></li>
				        Last name:<li> <input type='text' value='' name='lastname' /> <br/></li>
				        Email address:<li> <input type='text' value='' name='email' /> <br/><br/></li>

				        <img src='include/common/php-captcha.php'>
				        <li><input name='formKey' type='text' id='formKey' /> Enter the verification code in the image</li>
	
				        <br/>
				        <input type='submit' name='submit' value='Register' /> <br/>
				<ul>
				</form>
				";
		}


		switch ($status) {
			case "firstload":
				showForm();
				break;


			case "success":
				echo "<font color='blue'>Success</font><br/><br/>".
					$configValues['CONFIG_SIGNUP_SUCCESS_MSG_HEADER']."<b>".$_POST['firstname']."</b>,<br/><br/>".
					$configValues['CONFIG_SIGNUP_SUCCESS_MSG_BODY'].
					"<ul><li>Username: <b>$username</b></li><li>Password: <b>$password</b><br/></li></ul>".
					$configValues['CONFIG_SIGNUP_SUCCESS_MSG_LOGIN_LINK'];
				break;


			case "fieldsFailure":
                                echo "<font color='red'>".$configValues['CONFIG_SIGNUP_FAILURE_MSG_FIELDS']."</font><br/><br/>";
				showForm();
				break;


			case "captchaFailure":
                                echo "<font color='red'>".$configValues['CONFIG_SIGNUP_FAILURE_MSG_CAPTCHA']."</font><br/><br/>";
				showForm();
				break;

		}


	?>



	</p>
      </div>

      <h2>News</h2>
      <p><img src="images/dog.jpg" alt="Dog Template" width="92" height="129" align="left" style="margin-right:10px;margin-bottom:10px;" />
		daloRADIUS has released a new captive portal template which provides solutions for Free Sign-Up, PayPal Sign-Up with automatic
		provisioning in daloRADIUS's database server and a custom Hotspot Login/Welcome page.
      </p>
    </div>
    <div id="right-column">
      <div id="main-image"><img src="images/lady.jpg" alt="I love Pets" width="153" height="222" /></div>
      <div class="sidebar">

        <h3>About daloRADIUS</h3>
	<p>
		daloRADIUS is an advanced RADIUS web management application aimed at managing hotspots and
		general-purpose ISP deployments. It features user management, graphical reporting, accounting,
		a billing engine and integrates with GoogleMaps for geo-locating.		
	</p>
        <h3>Resources</h3>
        <div class="box">
          <ul>
            <li><a href="http://www.daloradius.com" target="_blank">daloRADIUS Official homepage</a></li>
            <li><a href="http://daloradius.wiki.sourceforge.net/" target="_blank">daloRADIUS Wiki</a></li>
          </ul>
        </div><a href="http://www.web-designers-directory.org/"></a><a href="http://www.medicine-pet.com/"></a>
      </div>
    </div>
  </div>
  <div id="footer">Copyright &copy; 2008 Liran Tal and daloRADIUS Project of Enginx.com, All rights reserved.<br />
    <a href="http://validator.w3.org/check?uri=referer" target="_blank">XHTML</a>  |  <a href="http://jigsaw.w3.org/css-validator/check/referer?warning=no&amp;profile=css2" target="_blank">CSS</a>  - Thanks to: <a href="http://www.medicine-pet.com/" target="_blank">Pet Medicine</a> | <span class="crd"><a href="http://www.web-designers-directory.org/">Web site Design</a></span> by : <a href="http://www.web-designers-directory.org/" target="_blank">WDD</a></div>
</div>

</body>
</html>
