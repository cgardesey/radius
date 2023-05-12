<?php


	include_once('library/config_read.php');

	switch($configValues['CONFIG_LANG']) {

		case "en":
			include (dirname(__FILE__)."/en.php");
			break;
		case "ru":
			include (dirname(__FILE__)."/ru.php");
			break;
		case "ro":
			include (dirname(__FILE__)."/ro.php");
			break;
		default:
			include (dirname(__FILE__)."/en.php");
			break;
	}

	// Translation function
	function t($a, $b = null, $c = null, $d = null)
	{
		global $l;

		$t = null;

		if($b === null) {
			$t = isset($l[$a]) ? $l[$a] : null;
		}
		else if($c === null) {
			$t = isset($l[$a][$b]) ? $l[$a][$b] : null;
		}
		else if($d === null) {
			$t = isset($l[$a][$b][$c]) ? $l[$a][$b][$c] : null;
		}
		else {
			$t = isset($l[$a][$b][$c][$d]) ? $l[$a][$b][$c][$d] : null;
		}

		if($t === null) {
			$t = 'Lang Error!';
		}

		return $t;
	}

?>
