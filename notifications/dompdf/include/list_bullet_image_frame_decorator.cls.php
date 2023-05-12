<?php





class List_Bullet_Image_Frame_Decorator extends Frame_Decorator {


  protected $_img;


  protected $_width;
  

  protected $_height;


  function __construct(Frame $frame, DOMPDF $dompdf) {
    $url = $frame->get_style()->list_style_image;
    $frame->get_node()->setAttribute("src", $url);
    $this->_img = new Image_Frame_Decorator($frame, $dompdf);
    parent::__construct($this->_img, $dompdf);
    list($width, $height) = getimagesize($this->_img->get_image_url());

    // Resample the bullet image to be consistent with 'auto' sized images
    $this->_width = ((float)rtrim($width, "px")) * 72 / DOMPDF_DPI;
    $this->_height = ((float)rtrim($height, "px")) * 72 / DOMPDF_DPI;
    
  }


  function get_width() {
    return $this->_width;
  }


  function get_height() {
    return $this->_height;
  }
  

  function get_margin_width() {
    return $this->_width + List_Bullet_Frame_Decorator::BULLET_PADDING;
  }


  function get_margin_height() {
    return $this->_height + List_Bullet_Frame_Decorator::BULLET_PADDING;
  }


  function get_image_url() {
    return $this->_img->get_image_url();
  }


  function get_image_ext() {
    return $this->_img->get_image_ext();
  }
  
}

?>