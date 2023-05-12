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
		if (is_array($configValues[$_configOption])) {
			$var = "\$configValues['" . $_configOption . "'] = \t\t";
			$var .= var_export($configValues[$_configOption], true);
			$var .= ";\n";
			fwrite($fp, $var);
		} else
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
