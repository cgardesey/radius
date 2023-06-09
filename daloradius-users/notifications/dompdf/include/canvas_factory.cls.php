<?php





class Canvas_Factory {


  private function __construct() { }

  static function get_instance($paper = null, $orientation = null,  $class = null) {

    $backend = strtolower(DOMPDF_PDF_BACKEND);
    
    if ( isset($class) && class_exists($class, false) )
      $class .= "_Adapter";
    
    else if ( (DOMPDF_PDF_BACKEND == "auto" || $backend == "pdflib" ) &&
              class_exists("PDFLib", false) )
      $class = "PDFLib_Adapter";

    else if ( (DOMPDF_PDF_BACKEND == "auto" || $backend == "cpdf") )
      $class = "CPDF_Adapter";

    else if ( $backend == "gd" )
      $class = "GD_Adapter";
    
    else
      $class = "CPDF_Adapter";

    return new $class($paper, $orientation);
        
  }
}
?>