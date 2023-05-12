<?php




require_once(DOMPDF_LIB_DIR . "/class.pdf.php");


define('__DOMPDF_FONT_CACHE_FILE', DOMPDF_FONT_DIR . "dompdf_font_family_cache");



class Font_Metrics {


  const CACHE_FILE = __DOMPDF_FONT_CACHE_FILE;
  

  static protected $_pdf = null;


  static protected $_font_lookup = array();
  
  

  static function init() {
    if (!self::$_pdf) {
      self::load_font_families();
      self::$_pdf = Canvas_Factory::get_instance();
    }
  }


  static function get_text_width($text, $font, $size, $spacing = 0) {
    return self::$_pdf->get_text_width($text, $font, $size, $spacing);
  }


  static function get_font_height($font, $size) {
    return self::$_pdf->get_font_height($font, $size);
  }


  static function get_font($family, $subtype = "normal") {
    
    $family = str_replace( array("'", '"'), "", mb_strtolower($family));
    $subtype = mb_strtolower($subtype);
    
    if ( !isset(self::$_font_lookup[$family]) )
      $family = DOMPDF_DEFAULT_FONT;

    if ( !in_array($subtype, array("normal", "bold", "italic", "bold_italic")) )
      //throw new DOMPDF_Exception("Font subtype '$subtype' is unsupported.");
      return self::$_font_lookup[DOMPDF_DEFAULT_FONT]["normal"];
    
    if ( !isset(self::$_font_lookup[$family][$subtype]) )
      return null;
    
    return self::$_font_lookup[$family][$subtype];
  }


  static function save_font_families() {

    file_put_contents(self::CACHE_FILE, var_export(self::$_font_lookup, true));
    
  }


  static function load_font_families() {
    if ( !is_readable(self::CACHE_FILE) )
      return;

    $data = file_get_contents(self::CACHE_FILE);

    if ( $data != "" )
      eval ('self::$_font_lookup = ' . $data . ";");

  }


  static function get_font_families() {
    return self::$_font_lookup;
  }

  static function set_font_family($fontname, $entry) {
    self::$_font_lookup[mb_strtolower($fontname)] = $entry;
  }
}

Font_Metrics::init();

?>