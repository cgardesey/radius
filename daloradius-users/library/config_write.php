<?php


$configFile = dirname(__FILE__).'/daloradius.conf.php';
$date = date("D M j G:i:s T Y");

$fp = fopen($configFile, "w");
if ($fp) {
	fwrite($fp, 
		"<?php\n".
		"\n".
		"\n\n");
	foreach ($configValues as $_configOption => $_configElem) {
	        fwrite($fp, "\$configValues['" . $_configOption . "'] = '" . $configValues[$_configOption] . "';\n");
	}
	fwrite($fp, "\n\n?>");
	fclose($fp);
	$successMsg = "Updated database settings for configuration file";
} else {
	$failureMsg = "Could not open the file for writing: <b>$configFile</b>
	<br/>Check file permissions. The file should be writable by the webserver's user/group";
}

?>
