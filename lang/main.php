<?php

 
	include_once(dirname(__FILE__).'/../library/config_read.php');
	
	switch($configValues['CONFIG_LANG']) {
	
		case "en":
			include (dirname(__FILE__)."/en.php");
			break;
		case "ru":
			include (dirname(__FILE__)."/ru.php");
			break;
		case "hu":
			include (dirname(__FILE__)."/hu.php");
			break;
		case "it":
			include (dirname(__FILE__)."/it.php");
			break;
		case "es_VE":
			include (dirname(__FILE__)."/es_VE.php");
			break;

		case "pt_br":
			include (dirname(__FILE__)."/pt_br.php");
			break;
		case "ja":
			include (dirname(__FILE__)."/ja.php");
			break;
        case "zh":
            include (dirname(__FILE__)."/zh.php");
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
