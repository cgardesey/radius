<?php



$configValues['CONFIG_DB_ENGINE'] = 'mysql';
$configValues['CONFIG_DB_HOST'] = 'localhost';
$configValues['CONFIG_DB_PORT'] = '3306';
$configValues['CONFIG_DB_USER'] = 'root';
$configValues['CONFIG_DB_PASS'] = '';
$configValues['CONFIG_DB_NAME'] = 'radius';
$configValues['CONFIG_DB_TBL_RADCHECK'] = 'radcheck';
$configValues['CONFIG_DB_TBL_RADREPLY'] = 'radreply';
$configValues['CONFIG_DB_TBL_RADGROUPREPLY'] = 'radgroupreply';
$configValues['CONFIG_DB_TBL_RADGROUPCHECK'] = 'radgroupcheck';
$configValues['CONFIG_DB_TBL_RADUSERGROUP'] = 'radusergroup';
$configValues['CONFIG_DB_TBL_RADNAS'] = 'nas';
$configValues['CONFIG_DB_TBL_RADPOSTAUTH'] = 'radpostauth';
$configValues['CONFIG_DB_TBL_RADACCT'] = 'radacct';
$configValues['CONFIG_DB_TBL_RADIPPOOL'] = 'radippool';
$configValues['CONFIG_DB_TBL_DALOOPERATOR'] = 'operators';
$configValues['CONFIG_DB_TBL_DALOBILLINGRATES'] = 'rates';
$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'] = 'hotspots';
$configValues['CONFIG_DB_TBL_DALOUSERINFO'] = 'userinfo';
$configValues['CONFIG_DB_TBL_DALODICTIONARY'] = 'dictionary';
$configValues['CONFIG_DB_TBL_DALOREALMS'] = 'realms';
$configValues['CONFIG_DB_TBL_DALOPROXYS'] = 'proxys';
$configValues['CONFIG_DB_TBL_DALOBILLINGPAYPAL'] = 'billing_paypal';
$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS'] = 'billing_plans';
$configValues['CONFIG_LANG'] = 'en';
$configValues['CONFIG_LOG_FREE_SIGNUP_FILENAME'] = '/tmp/free-signup.log';
$configValues['CONFIG_SIGNUP_MSG_TITLE'] = "We provide free registration service to our hotspots. <br/>".
					"Complete the form and click the Register button to generate a username and password.";
$configValues['CONFIG_SIGNUP_SUCCESS_MSG_HEADER'] = "Welcome to our Hotspot ";
$configValues['CONFIG_SIGNUP_SUCCESS_MSG_BODY'] = "we have created a username and password for you to use <br/>".
					" to login to our system, and they are as follows:<br/><br/>";
$configValues['CONFIG_SIGNUP_SUCCESS_MSG_LOGIN_LINK'] = "<br/>Click <b><a href='http://192.168.182.1:3990/prelogin'>here</a></b>".
					" to return to the Login page and start your surfing<br/><br/>";
$configValues['CONFIG_SIGNUP_FAILURE_MSG_FIELDS'] = "You didn't fill in your first and last name, please fill-in the form again";
$configValues['CONFIG_SIGNUP_FAILURE_MSG_CAPTCHA'] = "The image verification code is in-correct, please try again";


$configValues['CONFIG_GROUP_NAME'] = "somegroup";
$configValues['CONFIG_GROUP_PRIORITY'] = 0;
$configValues['CONFIG_USERNAME_PREFIX'] = "GST_";
$configValues['CONFIG_USERNAME_LENGTH'] = "4";
$configValues['CONFIG_PASSWORD_LENGTH'] = "4";
$configValues['CONFIG_USER_ALLOWEDRANDOMCHARS'] = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789";


?>