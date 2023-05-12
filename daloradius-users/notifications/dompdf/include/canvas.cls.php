<?php





interface Canvas {


  function get_page_number();


  function get_page_count();


  function set_page_count($count);


  function line($x1, $y1, $x2, $y2, $color, $width, $style = null);


  function rectangle($x1, $y1, $w, $h, $color, $width, $style = null);


  function filled_rectangle($x1, $y1, $w, $h, $color);


  function polygon($points, $color, $width = null, $style = null, $fill = false);


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
?>