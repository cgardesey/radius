<?php




require_once(DOMPDF_LIB_DIR . '/tcpdf/tcpdf.php');


class TCPDF_Adapter implements Canvas {


  static public $PAPER_SIZES = array(); // Set to
                                        // CPDF_Adapter::$PAPER_SIZES below.



  private $_pdf;


  private $_width;


  private $_height;


  private $_last_fill_color;


  private $_last_stroke_color;


  private $_last_line_width;
  

  private $_page_count;


  private $_page_text;


  private $_pages;


  function __construct($paper = "letter", $orientation = "portrait") {
   
    if ( is_array($paper) )
      $size = $paper;
    else if ( isset(self::$PAPER_SIZES[mb_strtolower($paper)]) )
      $size = self::$PAPER_SIZE[$paper];
    else
      $size = self::$PAPER_SIZE["letter"];

    if ( mb_strtolower($orientation) == "landscape" ) {
      $a = $size[3];
      $size[3] = $size[2];
      $size[2] = $a;
    }

    $this->_width = $size[2] - $size[0];
    $this->_height = $size[3] - $size[1];

    $this->_pdf = new TCPDF("P", "pt", array($this->_width, $this->_height));
    $this->_pdf->Setcreator("DOMPDF Converter");

    $this->_pdf->AddPage();

    $this->_page_number = $this->_page_count = 1;
    $this->_page_text = array();

    $this->_last_fill_color     =
      $this->_last_stroke_color =
      $this->_last_line_width   = null;

  }  
  

  protected function y($y) { return $this->_height - $y; }


  protected function _set_stroke_colour($colour) {
    $colour[0] = round(255 * $colour[0]);
    $colour[1] = round(255 * $colour[1]);
    $colour[2] = round(255 * $colour[2]);

    if ( is_null($this->_last_stroke_color) || $color != $this->_last_stroke_color ) {
      $this->_pdf->SetDrawColor($color[0],$color[1],$color[2]);
      $this->_last_stroke_color = $color;
    }

  }


  protected function _set_fill_colour($colour) {
    $colour[0] = round(255 * $colour[0]);
    $colour[1] = round(255 * $colour[1]);
    $colour[2] = round(255 * $colour[2]);

    if ( is_null($this->_last_fill_color) || $color != $this->_last_fill_color ) {
      $this->_pdf->SetDrawColor($color[0],$color[1],$color[2]);
      $this->_last_fill_color = $color;
    }

  }


  function get_tcpdf() { return $this->_pdf; }
  

  function get_page_number() {
    return $this->_page_number;
  }


  function get_page_count() {
    return $this->_page_count;
  }


  function set_page_count($count) {
    $this->_page_count = (int)$count;
  }


  function line($x1, $y1, $x2, $y2, $color, $width, $style = null) {

    if ( is_null($this->_last_line_width) || $width != $this->_last_line_width ) {
      $this->_pdf->SetLineWidth($width);
      $this->_last_line_width = $width;
    }

    $this->_set_stroke_colour($color);

    // FIXME: ugh, need to handle different styles here
    $this->_pdf->line($x1, $y1, $x2, $y2);
  }


  function rectangle($x1, $y1, $w, $h, $color, $width, $style = null) {

    if ( is_null($this->_last_line_width) || $width != $this->_last_line_width ) {
      $this->_pdf->SetLineWidth($width);
      $this->_last_line_width = $width;
    }

    $this->_set_stroke_colour($color);
    
    // FIXME: ugh, need to handle styles here
    $this->_pdf->rect($x1, $y1, $w, $h);
    
  }


  function filled_rectangle($x1, $y1, $w, $h, $color) {

    $this->_set_fill_colour($color);
    
    // FIXME: ugh, need to handle styles here
    $this->_pdf->rect($x1, $y1, $w, $h, "F");
  }


  function polygon($points, $color, $width = null, $style = null, $fill = false) {
    // FIXME: FPDF sucks
  }


  function circle($x, $y, $r, $color, $width = null, $style = null, $fill = false);


  function image($img_url, $img_type, $x, $y, $w, $h);


  function text($x, $y, $text, $font, $size, $color = array(0,0,0), $adjust = 0);


  function add_named_dest($anchorname);


  function add_link($url, $x, $y, $width, $height);
  

  function get_text_width($text, $font, $size, $spacing = 0);


  function get_font_height($font, $size);

  

  function new_page();


  function stream($filename, $options = null);


  function output($options = null);
  
}
    
// Workaround for idiotic limitation on statics...
PDFLib_Adapter::$PAPER_SIZES = CPDF_Adapter::$PAPER_SIZES;
?>