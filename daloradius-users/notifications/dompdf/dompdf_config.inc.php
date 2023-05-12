<?php




error_reporting(E_STRICT | E_ALL);


define("DOMPDF_DIR", realpath(dirname(__FILE__)));


define("DOMPDF_INC_DIR", DOMPDF_DIR . "/include");


define("DOMPDF_LIB_DIR", DOMPDF_DIR . "/lib");


define("DOMPDF_FONT_DIR", DOMPDF_DIR . "/lib/fonts/");


define("DOMPDF_TEMP_DIR", "/tmp");


define("TTF2AFM", "/usr/bin/ttf2pt1");


define("DOMPDF_PDF_BACKEND", "auto");


#define("DOMPDF_PDFLIB_LICENSE", "your license key here");


define("DOMPDF_DEFAULT_PAPER_SIZE", "letter");



define("DOMPDF_DEFAULT_FONT", "serif");


define("DOMPDF_DPI", "150");


define("DOMPDF_ENABLE_PHP", true);



define("DOMPDF_ENABLE_REMOTE", true);
 

function DOMPDF_autoload($class) {
  $filename = mb_strtolower($class) . ".cls.php";
  require_once(DOMPDF_INC_DIR . "/$filename");
}

if ( !function_exists("__autoload") ) {

  function __autoload($class) {
    DOMPDF_autoload($class);
  }
}

// ### End of user-configurable options ###



$_dompdf_warnings = array();


$_dompdf_show_warnings = false;


$_dompdf_debug = false;

require_once(DOMPDF_INC_DIR . "/functions.inc.php");

?>
