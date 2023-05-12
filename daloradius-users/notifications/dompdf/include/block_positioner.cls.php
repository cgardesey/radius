<?php





class Block_Positioner extends Positioner {


  function __construct(Frame_Decorator $frame) { parent::__construct($frame); }
  
  //........................................................................

  function position() {
    $cb = $this->_frame->get_containing_block();

    $p = $this->_frame->find_block_parent();
    
    if ( $p ) {
      $p->add_line();
      $y = $p->get_current_line("y");
      
    } else
      $y = $cb["y"];

    $x = $cb["x"];
    
    $this->_frame->set_position($x, $y);
  }
}
?>