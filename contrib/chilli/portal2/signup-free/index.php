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
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<script src="library/javascript/common.js" type="text/javascript"></script>
<body onLoad="return setFocus();">

<div id="wrap">

                <div class="header"><p>Hotspot<span>Login</span><sup>
                        By <a href="http://templatefusion.org">TemplateFusion.org</a></sup></p>
                </div>

        <div id="navigation">
                <ul class="glossymenu">
                        <li><a href="index.php" class="current"><b>Home</b></a></li>
                        <li><a href="#"><b>Services</b></a></li>
                        <li><a href="#"><b>About Us</b></a></li>
                        <li><a href="#"><b>Contact</b></a></li>
                </ul>
        </div>

        <div id="body">
                <h1>Sign-Up</h1>
                <p>
                        <center>

        <?php

                

		include("library/daloradius.conf.php");

                function showForm() {
			
			include("library/daloradius.conf.php");

                        echo "  <b>
				".$configValues['CONFIG_SIGNUP_MSG_TITLE']."
				</b>

                                <br/><br/>
                                <form name='signup' action='".$_SERVER['PHP_SELF']."' method='post'>

                                <table>
                                        <tr><td><b>First name:</b></td><td> <input type='text' value='' name='firstname' /> </td></tr>
                                        <tr><td><b>Last name:</b></td><td> <input type='text' value='' name='lastname' /> </td></tr>
                                        <tr><td><b>Email address:</b></td><td> <input type='text' value='' name='email' /> </td></tr>

                                        <tr><td><b>Enter the verification code in the image:</b> <img src='include/common/php-captcha.php'></td>
                                        <td><input name='formKey' type='text' id='formKey' /></td></tr>
				</table>
				<br/><br/>

                                        <tr><td><input type='submit' name='submit' value='Register' /> </td></tr>
				<br/><br/>
                                </form>
                                ";
                }


                switch ($status) {
                        case "firstload":
                                showForm();
                                break;


                        case "success":
                                echo "<font color='blue'><b>Success</b><br/><br/>".
                                        $configValues['CONFIG_SIGNUP_SUCCESS_MSG_HEADER']."<b>".$_POST['firstname']."</b>,<br/><br/>".
					$configValues['CONFIG_SIGNUP_SUCCESS_MSG_BODY']."<table>"
                                        ."<tr><td>Username:</td><td><b>$username</b></td></tr><tr><td>Password:</td><td><b>$password</b></td></tr>"
					."</table>".$configValues['CONFIG_SIGNUP_SUCCESS_MSG_LOGIN_LINK']
					."</font>";

                                break;


                        case "fieldsFailure":
                                echo "<font color='red'>".$configValues['CONFIG_SIGNUP_FAILURE_MSG_FIELDS']."</font><br/><br/>";
                                showForm();
                                break;


                        case "captchaFailure":
                                echo "<font color='red'><b>".$configValues['CONFIG_SIGNUP_FAILURE_MSG_CAPTCHA']."</b></font><br/><br/>";
                                showForm();
                                break;

                }


        ?>



                        </center>
                </p>


                <h1>Hotspot References</h1>
                <a href="#"><img src="images/portfolio1.jpg" alt="portfolio1" /></a>
                <a href="#"><img src="images/portfolio2.jpg" alt="portfolio2" /></a>
                <a href="#"><img src="images/portfolio3.jpg" alt="portfolio3" /></a>
                <a href="#"><img src="images/portfolio4.jpg" alt="portfolio4" /></a>
        </div>



        <div id="footer">Enginx&copy;2008 All Rights Reserved &bull; Enginx and daloRADIUS Hotspot Systems <br/>
                Design by <a href="http://templatefusion.org">TemplateFusion</a>
        </div>


</div>

</body>
</html>

