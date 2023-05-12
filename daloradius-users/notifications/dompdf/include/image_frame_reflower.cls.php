<?php





class Image_Frame_Reflower extends Frame_Reflower {

  function __construct(Image_Frame_Decorator $frame) {
    parent::__construct($frame);
  }

  function reflow() {
    
    // Set the frame's width
    $this->get_min_max_width();
    
  }

  function get_min_max_width() {

    // We need to grab our *parent's* style because images are wrapped...
    $style = $this->_frame->get_parent()->get_style();
    
    $width = $style->width;
    $height = $style->height;
    
    // Determine the image's size
    list($img_width, $img_height, $type) = getimagesize($this->_frame->get_image_url());

    if ( is_percent($width) )
      $width = ((float)rtrim($width,"%")) * $img_width / 100;

    if ( is_percent($height) )
      $height = ((float)rtrim($height,"%")) * $img_height / 100;
                 
    $width = $style->length_in_pt($width);
    $height = $style->length_in_pt($height);

    if ( $width === "auto" && $height === "auto" ) {
      $width = $img_width;
      $height = $img_height;
      
    } else if ( $width === "auto" && $height !== "auto" ) {
      $width = (float)$height / $img_height * $img_width;
      
    } else if ( $width !== "auto" && $height === "auto" ) {
      $height = (float)$width / $img_width * $img_height;
      
    } 
    
    // Resample images if the sizes were auto
    if ( $style->width === "auto" && $style->height === "auto" ) {
      $width = ((float)rtrim($width, "px")) * 72 / DOMPDF_DPI;
      $height = ((float)rtrim($height, "px")) * 72 / DOMPDF_DPI;
    }

    // Synchronize the styles
    $inner_style = $this->_frame->get_style();
    $inner_style->width = $style->width = $width . "pt";
    $inner_style->height = $style->height = $height . "pt";

    $inner_style->padding_top = $style->padding_top;
    $inner_style->padding_right = $style->padding_right;
    $inner_style->padding_bottom = $style->padding_bottom;
    $inner_style->padding_left = $style->padding_left;

    $inner_style->border_top_width = $style->border_top_width;
    $inner_style->border_right_width = $style->border_right_width;
    $inner_style->border_bottom_width = $style->border_bottom_width;
    $inner_style->border_left_width = $style->border_left_width;

    $inner_style->border_top_style = $style->border_top_style;
    $inner_style->border_right_style = $style->border_right_style;
    $inner_style->border_bottom_style = $style->border_bottom_style;
    $inner_style->border_left_style = $style->border_left_style;

    $inner_style->margin_top = $style->margin_top;
    $inner_style->margin_right = $style->margin_right;
    $inner_style->margin_bottom = $style->margin_bottom;
    $inner_style->margin_left = $style->margin_left;

    return array( $width, $width, "min" => $width, "max" => $width);
    
  }
}
?>