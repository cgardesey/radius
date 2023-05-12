<?php



function errorHandler($err) {
	echo("<br/><b>Database error</b><br>
		<b>Error Message: </b>" . $err->getMessage() . "<br><b>Debug info: </b>" . $err->getDebugInfo() . "<br>");
}

?>
