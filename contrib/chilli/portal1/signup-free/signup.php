<?php



$configValues['CONFIG_DB_ENGINE'] = "mysql";
$configValues['CONFIG_DB_USER'] = "root";
$configValues['CONFIG_DB_PASS'] = "";
$configValues['CONFIG_DB_HOST'] = "127.0.0.1";
$configValues['CONFIG_DB_NAME'] = "radius097";

$configValues['CONFIG_GROUP_NAME'] = "somegroup";
$configValues['CONFIG_GROUP_PRIORITY'] = 0;

$usernamePrefix = "guest";




session_start();						// we keep a session to save the captcha key


function randomAlphanumeric($length) {

    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= ($length - 1)) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }

    return $pass;

}

        if (isset($_POST['submit'])) {

                isset($_POST['firstname']) ? $firstname = $_POST['firstname'] : $firstname = "";
                isset($_POST['lastname']) ? $lastname = $_POST['lastname'] : $lastname = "";
                isset($_POST['email']) ? $email = $_POST['email'] : $email = "";

		$captchaKey = substr($_SESSION['key'],0,5);
		$formKey = $_POST['formKey'];
		if ( $formKey == $captchaKey ) {

	                if ( ($firstname) && ($lastname) ) {

	                        include_once ('DB.php');
	                        $dbConnectString = $configValues['CONFIG_DB_ENGINE'] . "://".
					$configValues['CONFIG_DB_USER'].":".$configValues['CONFIG_DB_PASS']."@".
					$configValues['CONFIG_DB_HOST']."/".$configValues['CONFIG_DB_NAME'];

	                        $dbSocket = DB::connect($dbConnectString);


	                        $rand = randomAlphanumeric(4);
	                        $username = $usernamePrefix . $rand;
        	                $password = randomAlphanumeric(4);


	                        $sql = "INSERT INTO radcheck values (0, '$username', 'User-Password', '==', '$password')";
	                        $res = $dbSocket->query($sql);


	                        $sql = "INSERT INTO userinfo (username, firstname, lastname, email) values ".
					"('$username', '$firstname', '$lastname', '$email')";
	                        $res = $dbSocket->query($sql);


	                        $sql = "INSERT INTO usergroup values ('$username', '".$configValues['CONFIG_GROUP_NAME'].
					"', '".$configValues['CONFIG_GROUP_PRIORITY']."')";
	                        $res = $dbSocket->query($sql);



	                        echo "<br/><br/>
	                                Welcome ". $_POST['firstname']  . ",<br/>"
	                                ."Your username is: $username <br/>and your password is: $password <br/>";

	                        $dbSocket->disconnect();

	                        exit;
	                } else {

	                        echo "<br/><br/>
                                Please fill in your first and last name <br/>";
	                        exit;
                	} // if (firstname... lastname)...

		} else {
			echo 'bad capctah key...';

		} // if captcha key

	} // if submit
?>


<html>
<head>
<title>
User Sign-up Page
</title>

<script type="text/javascript">

function setFocus() {
        document.signup.firstname.focus();
}

</script>
</head>

<body onLoad="return setFocus();">

<br/><br/>

<h2>Contact Details </h2>

<form name="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

	First name <input type="text" value="" name="firstname" /> <br/>
	Last name <input type="text" value="" name="lastname" /> <br/>
	Email: <input type="text" value="" name="email" /> <br/><br/>


<h3>Authenticate</h3>

	Enter the number in the image: <input name="formKey" type="text" id="formKey" />
	<img src="php_captcha.php">

	<br/><br/>
	<input type="submit" name="submit" value="Register" /> <br/>
</form>

<br/><br/>

</body>
</html>
