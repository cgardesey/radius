<?php





class Image_Frame_Decorator extends Frame_Decorator {


  static protected $_cache = array();
 

  protected $_image_url;


  protected $_image_ext;


  function __construct(Frame $frame, DOMPDF $dompdf) {
    global $_dompdf_warnings;
    
    parent::__construct($frame, $dompdf);
    $url = $frame->get_node()->getAttribute("src");

    list($this->_image_url, $this->_image_ext) = Image_Cache::resolve_url($url,
                                                                          $dompdf->get_protocol(),
                                                                          $dompdf->get_host(),
                                                                          $dompdf->get_base_path());
    
  }


  function get_image_url() {
    return $this->_image_url;
  }


  function get_image_ext() {
    return $this->_image_ext;
  }
  

  static function clear_image_cache() {
    if ( count(self::$_cache) ) {
      foreach (self::$_cache as $file)
        unlink($file);
    }
  }
}
?>