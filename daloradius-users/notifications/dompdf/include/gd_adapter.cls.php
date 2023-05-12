<?php





class GD_Adapter implements Canvas {


  private $_img;


  private $_width;


  private $_height;


  private $_aa_factor;


  private $_colors;


  private $_bg_color;
  

  function __construct($size, $orientation = "portrait", $aa_factor = 1, $bg_color = array(1,1,1,0) ) {

    if ( !is_array($size) ) {

      if ( isset(CPDF_Adapter::$PAPER_SIZES[ strtolower($size)]) ) 
        $size = CPDF_Adapter::$PAPER_SIZES[$size];
      else
        $size = CPDF_Adapter::$PAPER_SIZES["letter"];
    
    }

    if ( strtolower($orientation) == "landscape" ) {
      list($size[2],$size[3]) = array($size[3],$size[2]);
    }

    if ( $aa_factor < 1 )
      $aa_factor = 1;

    $this->_aa_factor = $aa_factor;
    
    $size[2] *= $aa_factor;
    $size[3] *= $aa_factor;
    
    $this->_width = $size[2] - $size[0];
    $this->_height = $size[3] - $size[1];

    $this->_img = imagecreatetruecolor($this->_width, $this->_height);

    if ( is_null($bg_color) || !is_array($bg_color) ) {
      // Pure white bg
      $bg_color = array(1,1,1,0);
    }

    $this->_bg_color = $this->_allocate_color($bg_color);
    imagealphablending($this->_img, false);
    imagesavealpha($this->_img, true);
    imagefill($this->_img, 0, 0, $this->_bg_color);
        
  }


  function get_image() { return $this->_img; }


  function get_width() { return $this->_width / $this->_aa_factor; }


  function get_height() { return $this->_height / $this->_aa_factor; }
  

  function get_page_number() {
    // FIXME
  }
   

  function get_page_count() {
    // FIXME
  }    


  function set_page_count($count) {
    // FIXME
  }    


  private function _allocate_color($color) {
    
    // Full opacity if no alpha set
    if ( !isset($color[3]) ) 
      $color[3] = 0;
    
    list($r,$g,$b,$a) = $color;
    
    $r *= 255;
    $g *= 255;
    $b *= 255;
    $a *= 127;
    
    // Clip values
    $r = $r > 255 ? 255 : $r;
    $g = $g > 255 ? 255 : $g;
    $b = $b > 255 ? 255 : $b;
    $a = $a > 127 ? 127 : $a;
      
    $r = $r < 0 ? 0 : $r;
    $g = $g < 0 ? 0 : $g;
    $b = $b < 0 ? 0 : $b;
    $a = $a < 0 ? 0 : $a;
      
    $key = sprintf("#%02X%02X%02X%02X", $r, $g, $b, $a);
      
    if ( isset($this->_colors[$key]) )
      return $this->_colors[$key];

    if ( $a != 0 ) 
      $this->_colors[$key] = imagecolorallocatealpha($this->_img, $r, $g, $b, $a);
    else
      $this->_colors[$key] = imagecolorallocate($this->_img, $r, $g, $b);
      
    return $this->_colors[$key];
    
  }
  

  function line($x1, $y1, $x2, $y2, $color, $width, $style = null) {

    // Scale by the AA factor
    $x1 *= $this->_aa_factor;
    $y1 *= $this->_aa_factor;
    $x2 *= $this->_aa_factor;
    $y2 *= $this->_aa_factor;
    $width *= $this->_aa_factor;

    $c = $this->_allocate_color($color);

    // Convert the style array if required
    if ( !is_null($style) ) {
      $gd_style = array();

      if ( count($style) == 1 ) {
        for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) {
          $gd_style[] = $c;
        }

        for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) {
          $gd_style[] = $this->_bg_color;
        }

      } else {

        $i = 0;
        foreach ($style as $length) {

          if ( $i % 2 == 0 ) {
            // 'On' pattern
            for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) 
              $gd_style[] = $c;
            
          } else {
            // Off pattern
            for ($i = 0; $i < $style[0] * $this->_aa_factor; $i++) 
              $gd_style[] = $this->_bg_color;
            
          }
          $i++;
        }
      }
      
      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }
    
    imagesetthickness($this->_img, $width);

    imageline($this->_img, $x1, $y1, $x2, $y2, $c);
    
  }


  function rectangle($x1, $y1, $w, $h, $color, $width, $style = null) {

    // Scale by the AA factor
    $x1 *= $this->_aa_factor;
    $y1 *= $this->_aa_factor;
    $w *= $this->_aa_factor;
    $h *= $this->_aa_factor;

    $c = $this->_allocate_color($color);

    // Convert the style array if required
    if ( !is_null($style) ) {
      $gd_style = array();

      foreach ($style as $length) {
        for ($i = 0; $i < $length; $i++) {
          $gd_style[] = $c;
        }
      }

      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }

    imagesetthickness($this->_img, $width);

    imagerectangle($this->_img, $x1, $y1, $x1 + $w, $y1 + $h, $c);
    
  }


  function filled_rectangle($x1, $y1, $w, $h, $color) {

    // Scale by the AA factor
    $x1 *= $this->_aa_factor;
    $y1 *= $this->_aa_factor;
    $w *= $this->_aa_factor;
    $h *= $this->_aa_factor;

    $c = $this->_allocate_color($color);

    imagefilledrectangle($this->_img, $x1, $y1, $x1 + $w, $y1 + $h, $c);

  }


  function polygon($points, $color, $width = null, $style = null, $fill = false) {

    // Scale each point by the AA factor
    foreach (array_keys($points) as $i)
      $points[$i] *= $this->_aa_factor;

    $c = $this->_allocate_color($color);

    // Convert the style array if required
    if ( !is_null($style) && !$fill ) {
      $gd_style = array();

      foreach ($style as $length) {
        for ($i = 0; $i < $length; $i++) {
          $gd_style[] = $c;
        }
      }

      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }

    imagesetthickness($this->_img, $width);

    if ( $fill ) 
      imagefilledpolygon($this->_img, $points, count($points) / 2, $c);
    else
      imagepolygon($this->_img, $points, count($points) / 2, $c);
        
  }


  function circle($x, $y, $r, $color, $width = null, $style = null, $fill = false) {

    // Scale by the AA factor
    $x *= $this->_aa_factor;
    $y *= $this->_aa_factor;
    $r *= $this->_aa_factor;

    $c = $this->_allocate_color($color);

    // Convert the style array if required
    if ( !is_null($style) && !$fill ) {
      $gd_style = array();

      foreach ($style as $length) {
        for ($i = 0; $i < $length; $i++) {
          $gd_style[] = $c;
        }
      }

      imagesetstyle($this->_img, $gd_style);
      $c = IMG_COLOR_STYLED;
    }

    imagesetthickness($this->_img, $width);

    if ( $fill )
      imagefilledellipse($this->_img, $x, $y, $r, $r, $c);
    else
      imageellipse($this->_img, $x, $y, $r, $r, $c);
        
  }


  function image($img_url, $img_type, $x, $y, $w, $h) {

    switch ($img_type) {
    case "png":
      $src = @imagecreatefrompng($img_url);
      break;
      
    case "gif":
      $src = @imagecreatefromgif($img_url);
      break;
      
    case "jpg":
    case "jpeg":
      $src = @imagecreatefromjpeg($img_url);
      break;

    default:
      break;
      
    }

    if ( !$src )
      return; // Probably should add to $_dompdf_errors or whatever here
    
    // Scale by the AA factor
    $x *= $this->_aa_factor;
    $y *= $this->_aa_factor;

    $w *= $this->_aa_factor;
    $h *= $this->_aa_factor;
    
    $img_w = imagesx($src);
    $img_h = imagesy($src);

    
    imagecopyresampled($this->_img, $src, $x, $y, 0, 0, $w, $h, $img_w, $img_h);
    
  }


  function text($x, $y, $text, $font, $size, $color = array(0,0,0), $adjust = 0) {

    // Scale by the AA factor
    $x *= $this->_aa_factor;
    $y *= $this->_aa_factor;
    $size *= $this->_aa_factor;
    
    $h = $this->get_font_height($font, $size);
    
    $c = $this->_allocate_color($color);

    if ( strpos($font, '.ttf') === false )
      $font .= ".ttf";

    // FIXME: word spacing
    imagettftext($this->_img, $size, 0, $x, $y + $h, $c, $font, $text);
    
  }


  function add_named_dest($anchorname) {
    // Not implemented
  }


  function add_link($url, $x, $y, $width, $height) {
    // Not implemented
  }


  function get_text_width($text, $font, $size, $spacing = 0) {    

    if ( strpos($font, '.ttf') === false )
      $font .= ".ttf";

    // FIXME: word spacing
    list($x1,,$x2) = imagettfbbox($size, 0, $font, $text);
    return $x2 - $x1;
  }


  function get_font_height($font, $size) {
    if ( strpos($font, '.ttf') === false )
      $font .= ".ttf";

    // FIXME: word spacing
    list(,$y2,,,,$y1) = imagettfbbox($size, 0, $font, "MXjpqytfhl");  // Test string with ascenders, descenders and caps
    return $y2 - $y1;
  }

  

  function new_page() {
    // FIXME
  }    


  function stream($filename, $options = null) {

    // Perform any antialiasing
    if ( $this->_aa_factor != 1 ) {
      $dst_w = $this->_width / $this->_aa_factor;
      $dst_h = $this->_height / $this->_aa_factor;
      $dst = imagecreatetruecolor($dst_w, $dst_h);
      imagecopyresampled($dst, $this->_img, 0, 0, 0, 0,
                         $dst_w, $dst_h,
                         $this->_width, $this->_height);
    } else {
      $dst = $this->_img;
    }

    if ( !isset($options["type"]) )
      $options["type"] = "png";

    $type = strtolower($options["type"]);
    
    header("Cache-Control: private");
    
    switch ($type) {

    case "jpg":
    case "jpeg":
      if ( !isset($options["quality"]) )
        $options["quality"] = 75;
      
      header("Content-type: image/jpeg");
      imagejpeg($dst, '', $options["quality"]);
      break;

    case "png":
    default:
      header("Content-type: image/png");
      imagepng($dst);
      break;
    }

    if ( $this->_aa_factor != 1 ) 
      imagedestroy($dst);
  }


  function output($options = null) {

    if ( $this->_aa_factor != 1 ) {
      $dst_w = $this->_width / $this->_aa_factor;
      $dst_h = $this->_height / $this->_aa_factor;
      $dst = imagecreatetruecolor($dst_w, $dst_h);
      imagecopyresampled($dst, $this->_img, 0, 0, 0, 0,
                         $dst_w, $dst_h,
                         $this->_width, $this->_height);
    } else {
      $dst = $this->_img;
    }
    
    if ( !isset($options["type"]) )
      $options["type"] = "png";

    $type = $options["type"];
    
    ob_start();

    switch ($type) {

    case "jpg":
    case "jpeg":
      if ( !isset($options["quality"]) )
        $options["quality"] = 75;
      
      imagejpeg($dst, '', $options["quality"]);
      break;

    case "png":
    default:
      imagepng($dst);
      break;
    }

    $image = ob_get_contents();
    ob_end_clean();

    if ( $this->_aa_factor != 1 )
      imagedestroy($dst);
    
    return $image;
  }
  
  
}
?>