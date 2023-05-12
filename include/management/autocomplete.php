<?php



    include_once("library/config_read.php");


    if( (isset($configValues['CONFIG_IFACE_AUTO_COMPLETE'])) &&
                (strtolower($configValues['CONFIG_IFACE_AUTO_COMPLETE']) == "yes") )
    {
        $autoComplete = true; # Set boolean for throughout the page
        echo "
		<script type=\"text/javascript\" src=\"library/javascript/ajax.js\"></script>
		<script type=\"text/javascript\" src=\"library/javascript/dhtmlSuite-common.js\"></script>
		<script type=\"text/javascript\" src=\"library/javascript/auto-complete.js\"></script>
		<script>
			var DHTML_SUITE_THEME_FOLDER = './';
			var DHTML_SUITE_JS_FOLDER = 'library/javascript/';
			var DHTML_SUITE_THEME = '.';
		</script>
        ";
    }


?>
