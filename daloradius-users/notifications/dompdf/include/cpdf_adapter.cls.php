<?php




// FIXME: Need to sanity check inputs to this class
require_once(DOMPDF_LIB_DIR . "/class.pdf.php");


class CPDF_Adapter implements Canvas {


  static $PAPER_SIZES = array("4a0" => array(0,0,4767.87,6740.79),
                              "2a0" => array(0,0,3370.39,4767.87),
                              "a0" => array(0,0,2383.94,3370.39),
                              "a1" => array(0,0,1683.78,2383.94),
                              "a2" => array(0,0,1190.55,1683.78),
                              "a3" => array(0,0,841.89,1190.55),
                              "a4" => array(0,0,595.28,841.89),
                              "a5" => array(0,0,419.53,595.28),
                              "a6" => array(0,0,297.64,419.53),
                              "a7" => array(0,0,209.76,297.64),
                              "a8" => array(0,0,147.40,209.76),
                              "a9" => array(0,0,104.88,147.40),
                              "a10" => array(0,0,73.70,104.88),
                              "b0" => array(0,0,2834.65,4008.19),
                              "b1" => array(0,0,2004.09,2834.65),
                              "b2" => array(0,0,1417.32,2004.09),
                              "b3" => array(0,0,1000.63,1417.32),
                              "b4" => array(0,0,708.66,1000.63),
                              "b5" => array(0,0,498.90,708.66),
                              "b6" => array(0,0,354.33,498.90),
                              "b7" => array(0,0,249.45,354.33),
                              "b8" => array(0,0,175.75,249.45),
                              "b9" => array(0,0,124.72,175.75),
                              "b10" => array(0,0,87.87,124.72),
                              "c0" => array(0,0,2599.37,3676.54),
                              "c1" => array(0,0,1836.85,2599.37),
                              "c2" => array(0,0,1298.27,1836.85),
                              "c3" => array(0,0,918.43,1298.27),
                              "c4" => array(0,0,649.13,918.43),
                              "c5" => array(0,0,459.21,649.13),
                              "c6" => array(0,0,323.15,459.21),
                              "c7" => array(0,0,229.61,323.15),
                              "c8" => array(0,0,161.57,229.61),
                              "c9" => array(0,0,113.39,161.57),
                              "c10" => array(0,0,79.37,113.39),
                              "ra0" => array(0,0,2437.80,3458.27),
                              "ra1" => array(0,0,1729.13,2437.80),
                              "ra2" => array(0,0,1218.90,1729.13),
                              "ra3" => array(0,0,864.57,1218.90),
                              "ra4" => array(0,0,609.45,864.57),
                              "sra0" => array(0,0,2551.18,3628.35),
                              "sra1" => array(0,0,1814.17,2551.18),
                              "sra2" => array(0,0,1275.59,1814.17),
                              "sra3" => array(0,0,907.09,1275.59),
                              "sra4" => array(0,0,637.80,907.09),
                              "letter" => array(0,0,612.00,792.00),
                              "legal" => array(0,0,612.00,1008.00),
                              "ledger" => array(0,0,1224.00, 792.00),
                              "tabloid" => array(0,0,792.00, 1224.00),
                              "executive" => array(0,0,521.86,756.00),
                              "folio" => array(0,0,612.00,936.00),
                              "commerical #10 envelope" => array(0,0,684,297),
                              "catalog #10 1/2 envelope" => array(0,0,648,864),
                              "8.5x11" => array(0,0,612.00,792.00),
                              "8.5x14" => array(0,0,612.00,1008.0),
                              "11x17"  => array(0,0,792.00, 1224.00));



  private $_pdf;


  private $_width;


  private $_height;


  private $_page_number;


  private $_page_count;


  private $_page_text;


  private $_pages;


  private $_image_cache;
  

  function __construct($paper = "letter", $orientation = "portrait") {    

    if ( is_array($paper) )
      $size = $paper;
    else if ( isset(self::$PAPER_SIZES[mb_strtolower($paper)]) )
      $size = self::$PAPER_SIZES[$paper];
    else
      $size = self::$PAPER_SIZES["letter"];

    if ( mb_strtolower($orientation) == "landscape" ) {
      $a = $size[3];
      $size[3] = $size[2];
      $size[2] = $a;
    }
    
    $this->_pdf = new Cpdf($size);
    $this->_pdf->addInfo("Creator", "DOMPDF Converter");

    // Silence pedantic warnings about missing TZ settings
    if ( function_exists("date_default_timezone_get") ) {
      $tz = @date_default_timezone_get();
      date_default_timezone_set("UTC");
      $this->_pdf->addInfo("CreationDate", date("Y-m-d"));
      date_default_timezone_set($tz);

    } else {
      $this->_pdf->addInfo("CreationDate", date("Y-m-d"));
    }

    $this->_width = $size[2] - $size[0];
    $this->_height= $size[3] - $size[1];
    $this->_pdf->openHere('Fit');
    
    $this->_page_number = $this->_page_count = 1;
    $this->_page_text = array();

    $this->_pages = array($this->_pdf->getFirstPageId());

    $this->_image_cache = array();
  }


  function __destruct() {
    foreach ($this->_image_cache as $img) {
      unlink($img);
    }
  }
  

  function get_cpdf() { return $this->_pdf; }


  function open_object() {
    $ret = $this->_pdf->openObject();
    $this->_pdf->saveState();
    return $ret;
  }


  function reopen_object($object) {
    $this->_pdf->reopenObject($object);
    $this->_pdf->saveState();    
  }


  function close_object() {
    $this->_pdf->restoreState();
    $this->_pdf->closeObject();
  }


  function add_object($object, $where = 'all') {
    $this->_pdf->addObject($object, $where);
  }


  function stop_object($object) {
    $this->_pdf->stopObject($object);
  }


  function serialize_object($id) {
    // Serialize the pdf object's current state for retrieval later
    return $this->_pdf->serializeObject($id);
  }


  function reopen_serialized_object($obj) {
    return $this->_pdf->restoreSerializedObject($obj);
  }
    
  //........................................................................


  function get_width() { return $this->_width; }


  function get_height() { return $this->_height; }


  function get_page_number() { return $this->_page_number; }


  function get_page_count() { return $this->_page_count; }


  function set_page_number($num) { $this->_page_number = $num; }


  function set_page_count($count) {  $this->_page_count = $count; }
    

  protected function _set_stroke_color($color) {
    list($r, $g, $b) = $color;
    $this->_pdf->setStrokeColor($r, $g, $b);
  }
  

  protected function _set_fill_color($color) {
    list($r, $g, $b) = $color;      
    $this->_pdf->setColor($r, $g, $b);
  }


  protected function _set_line_transparency($mode, $opacity) {
    $this->_pdf->setLineTransparency($mode, $opacity);
  }
  

  protected function _set_fill_transparency($mode, $opacity) {
    $this->_pdf->setFillTransparency($mode, $opacity);
  }


  protected function _set_line_style($width, $cap, $join, $dash) {
    $this->_pdf->setLineStyle($width, $cap, $join, $dash);
  }
  
  //........................................................................

  

  protected function y($y) { return $this->_height - $y; }

  // Canvas implementation

  function line($x1, $y1, $x2, $y2, $color, $width, $style = array(),
                $blend = "Normal", $opacity = 1.0) {
    //pre_r(compact("x1", "y1", "x2", "y2", "color", "width", "style"));

    $this->_set_stroke_color($color);
    $this->_set_line_style($width, "butt", "", $style);
    $this->_set_line_transparency($blend, $opacity);
    
    $this->_pdf->line($x1, $this->y($y1),
                      $x2, $this->y($y2));
  }
                              
  //........................................................................


  protected function _convert_gif_to_png($image_url) {
    global $_dompdf_warnings;
    
    if ( !function_exists("imagecreatefromgif") ) {
      $_dompdf_warnings[] = "Function imagecreatefromgif() not found.  Cannot convert gif image: $image_url.";      
      return DOMPDF_LIB_DIR . "/res/broken_image.png";
    }

    $old_err = set_error_handler("record_warnings");
    $im = imagecreatefromgif($image_url);

    if ( $im ) {
      imageinterlace($im, 0);
    
      $filename = tempnam(DOMPDF_TEMP_DIR, "dompdf_img_");
      imagepng($im, $filename);

    } else {
      $filename = DOMPDF_LIB_DIR . "/res/broken_image.png";

    }

    restore_error_handler();

    $this->_image_cache[] = $filename;
    
    return $filename;
    
  }

  function rectangle($x1, $y1, $w, $h, $color, $width, $style = array(),
                     $blend = "Normal", $opacity = 1.0) {

    $this->_set_stroke_color($color);
    $this->_set_line_style($width, "square", "miter", $style);
    $this->_set_line_transparency($blend, $opacity);
    
    $this->_pdf->rectangle($x1, $this->y($y1) - $h, $w, $h);
  }

  //........................................................................
  
  function filled_rectangle($x1, $y1, $w, $h, $color, $blend = "Normal", $opacity = 1.0) {

    $this->_set_fill_color($color);
    $this->_set_line_style(1, "square", "miter", array());
    $this->_set_line_transparency($blend, $opacity);
    $this->_set_fill_transparency($blend, $opacity);
    
    $this->_pdf->filledRectangle($x1, $this->y($y1) - $h, $w, $h);
  }

  //........................................................................

  function polygon($points, $color, $width = null, $style = array(),
                   $fill = false, $blend = "Normal", $opacity = 1.0) {

    $this->_set_fill_color($color);
    $this->_set_stroke_color($color);

    $this->_set_line_transparency($blend, $opacity);
    $this->_set_fill_transparency($blend, $opacity);
    
    if ( !$fill && isset($width) )
      $this->_set_line_style($width, "square", "miter", $style);
    
    // Adjust y values
    for ( $i = 1; $i < count($points); $i += 2)
      $points[$i] = $this->y($points[$i]);
    
    $this->_pdf->polygon($points, count($points) / 2, $fill);
  }

  //........................................................................

  function circle($x, $y, $r1, $color, $width = null, $style = null,
                  $fill = false, $blend = "Normal", $opacity = 1.0) {

    $this->_set_fill_color($color);
    $this->_set_stroke_color($color);
    
    $this->_set_line_transparency($blend, $opacity);
    $this->_set_fill_transparency($blend, $opacity);

    if ( !$fill && isset($width) )
      $this->_set_line_style($width, "round", "round", $style);

    $this->_pdf->filledEllipse($x, $this->y($y), $r1, 0, 0, 8, 0, 360, 1, $fill);

  }
  
  //........................................................................

  function image($img_url, $img_type, $x, $y, $w, $h) {

    $img_type = mb_strtolower($img_type);
    
    switch ($img_type) {
    case "jpeg":
    case "jpg":
      $this->_pdf->addJpegFromFile($img_url, $x, $this->y($y) - $h, $w, $h);
      break;

    case "png":
      $this->_pdf->addPngFromFile($img_url, $x, $this->y($y) - $h, $w, $h);
      break;

    case "gif":
      // Convert gifs to pngs
      $img_url = $this->_convert_gif_to_png($img_url);
      $this->_pdf->addPngFromFile($img_url, $x, $this->y($y) - $h, $w, $h);
      break;
      
    default:      
      break;
    }
    
    return;
  }

  //........................................................................

  function text($x, $y, $text, $font, $size, $color = array(0,0,0),
                $adjust = 0, $angle = 0, $blend = "Normal", $opacity = 1.0) {

    list($r, $g, $b) = $color;
    $this->_pdf->setColor($r, $g, $b);

    $this->_set_line_transparency($blend, $opacity);
    $this->_set_fill_transparency($blend, $opacity);
    $font .= ".afm";
    
    $this->_pdf->selectFont($font);
    $this->_pdf->addText($x, $this->y($y) - Font_Metrics::get_font_height($font, $size), $size, utf8_decode($text), $angle, $adjust);

  }

  //........................................................................


  function add_named_dest($anchorname) {
    $this->_pdf->addDestination($anchorname,"Fit");
  }

  //........................................................................


  function add_link($url, $x, $y, $width, $height) {

    $y = $this->y($y) - $height;

    if ( strpos($url, '#') === 0 ) {
      // Local link
      $name = substr($url,1);
      if ( $name )
        $this->_pdf->addInternalLink($name, $x, $y, $x + $width, $y + $height);

    } else {
      $this->_pdf->addLink(rawurldecode($url), $x, $y, $x + $width, $y + $height);
    }
    
  }

  //........................................................................

  function get_text_width($text, $font, $size, $spacing = 0) {
    $this->_pdf->selectFont($font);
    return $this->_pdf->getTextWidth($size, utf8_decode($text), $spacing);
  }

  //........................................................................

  function get_font_height($font, $size) {
    $this->_pdf->selectFont($font);
    return $this->_pdf->getFontHeight($size);
  }


  function page_text($x, $y, $text, $font, $size, $color = array(0,0,0),
                     $adjust = 0, $angle = 0,  $blend = "Normal", $opacity = 1.0) {
    
    $this->_page_text[] = compact("x", "y", "text", "font", "size", "color", "adjust", "angle");
  }
  
  //........................................................................

  function new_page() {
    $this->_page_count++;

    $ret = $this->_pdf->newPage();
    $this->_pages[] = $ret;
    return $ret;
  }
  
  //........................................................................


  protected function _add_page_text() {
    
    if ( !count($this->_page_text) )
      return;

    $page_number = 1;

    foreach ($this->_pages as $pid) {

      foreach ($this->_page_text as $pt) {
        extract($pt);

        $text = str_replace(array("{PAGE_NUM}","{PAGE_COUNT}"),
                            array($page_number, $this->_page_count), $text);

        $this->reopen_object($pid);        
        $this->text($x, $y, $text, $font, $size, $color, $adjust, $angle);
        $this->close_object();        
      }

      $page_number++;
      
    }
  }
  

  function stream($filename, $options = null) {
    // Add page text
    $this->_add_page_text();
    
    $options["Content-Disposition"] = $filename;
    $this->_pdf->stream($options);
  }

  //........................................................................


  function output($options = null) {
    // Add page text
    $this->_add_page_text();

    if ( isset($options["compress"]) && $options["compress"] != 1 )
      $debug = 1;
    else
      $debug = 0;
    
    return $this->_pdf->output($debug);
    
  }
  
  //........................................................................


  function get_messages() { return $this->_pdf->messages; }
  
}

?>