<?php


$logfile_loc1 = '/var/log/dmesg';
$logfile_loc2 = '/usr/local/var/log/dmesg';

if (file_exists($logfile_loc1))
	$logfile = $logfile_loc1;
else if (file_exists($logfile_loc2))
	$logfile = $logfile_loc2;
else {
	$failureMsg = "error reading log file: <br/>".
		"looked for log file in $logfile_loc1 and $logfile_loc2 but couldn't find it.<br/>".
		"if you know where your dmesg (boot) log file is located, set it's location in " . $_SERVER[SCRIPT_NAME];
	exit;
}
	

if (is_readable($logfile) == false) {
	$failureMsg = "error reading log file: <u>$logfile</u> <br/>".
		"possible cause is file premissions or file doesn't exist.<br/>";
} else {
                if (file_get_contents($logfile)) {
                        $fileReversed = array_reverse(file($logfile));
                        $counter = $bootLineCount;
                        foreach ($fileReversed as $line) {
                                if (preg_match("/$bootFilter/i", $line)) {
                                        if ($counter == 0)
                                                break;
                                        $ret = preg_replace("/\n/i", "<br>", $line);
                                        echo $ret;
                                        $counter--;
                                }
                        }
                }
}

?>

