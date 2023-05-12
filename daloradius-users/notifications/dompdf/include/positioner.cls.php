<?php





abstract class Positioner {
  
  // protected members
  protected $_frame;
  
  //........................................................................

  function __construct(Frame_Decorator $frame) {
    $this->_frame = $frame;
  }

  //........................................................................

  abstract function position();
  
}
?>