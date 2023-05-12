<?php


if (isset($_REQUEST['csv_output'])) {

	$csv_output = $_REQUEST['csv_output'];
	$csv_formatted = str_replace("||", "\r\n", $csv_output);

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv; filename=export_" . date("Ymd") . ".csv; size=" . strlen($csv_formatted));
        //header("Content-disposition: csv; filename=document_; size=" . strlen($csv_output));
	print $csv_formatted;
	exit;

}

?>
