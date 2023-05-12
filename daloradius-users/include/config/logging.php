<?php





if ($configValues['CONFIG_LOG_PAGES'] == "yes") {
	if (isset($log)) {
	        $msgNotice = $login . " " . $log;
	        logMessage("NOTICE", $msgNotice, $configValues['CONFIG_LOG_FILE'], $_SERVER["SCRIPT_NAME"]);
	}
}



if ($configValues['CONFIG_LOG_QUERIES'] == "yes") {
	if (isset($logQuery)) {
	        $msgQuery = $login . " " . $logQuery;
	        logMessage("QUERY", $msgQuery, $configValues['CONFIG_LOG_FILE'], $_SERVER["SCRIPT_NAME"]);
	}
}



if ($configValues['CONFIG_LOG_ACTIONS'] == "yes") {
	if (isset($logAction)) {
	        $msgAction = $login . " " . $logAction;
	        logMessage("ACTION", $msgAction, $configValues['CONFIG_LOG_FILE'], $_SERVER["SCRIPT_NAME"]);
	}
}



if ($configValues['CONFIG_DEBUG_SQL'] == "yes") {
	if (isset($logDebugSQL)) {
	        $msgDebugSQL = "- SQL -" . " " . $logDebugSQL . " on page: ";
	        logMessage("DEBUG", $msgDebugSQL, $configValues['CONFIG_LOG_FILE'], $_SERVER["SCRIPT_NAME"]);
	}
}


if ($configValues['CONFIG_DEBUG_SQL_ONPAGE'] == "yes") {
	if (isset($logDebugSQL)) {
			echo "<br/><br/>";
			echo "Debugging SQL Queries: <br/>";
			echo $logDebugSQL;
			echo "<br/><br/>";
		}
}




function logMessage($type, $msg, $logFile, $currPage) {


        $date = date('M d G:i:s');
        $msgString = $date . " " . $type . " " . $msg . " " . $currPage;

        $fp = fopen($logFile, "a");
        if ($fp) {
        fwrite($fp, $msgString  . "\n");
                fclose($fp);
        } else {
                echo "<font color='#FF0000'>error: could not open the file for writing:<b> $logFile </b><br/></font>";
                        echo "Check file permissions. The file should be writable by the webserver's user/group<br/>";
                echo "
                    <script language='JavaScript'>
                    <!--
                    alert('could not open the file $logFile for writing!\\nCheck file permissions.');
                    -->
                    </script>
                        ";
        }

}

?>
