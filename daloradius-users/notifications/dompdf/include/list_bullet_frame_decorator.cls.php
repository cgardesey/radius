<?php





class List_Bullet_Frame_Decorator extends Frame_Decorator {

  const BULLET_SIZE = 5;   // Size of graphical bullets
  const BULLET_PADDING = 2.5; // Distance from bullet to text
  
  static $BULLET_TYPES = array("disc", "circle", "square");
  
  //........................................................................

  function __construct(Frame $frame, DOMPDF $dompdf) {
    parent::__construct($frame, $dompdf);
  }
  
  function get_margin_width() {
    return self::BULLET_SIZE + self::BULLET_PADDING;
  }

  function get_margin_height() {
    return self::BULLET_SIZE + self::BULLET_PADDING;
  }

  function get_width() {
    return self::BULLET_SIZE + 2 * self::BULLET_PADDING;
  }
  
  //........................................................................
}
?>