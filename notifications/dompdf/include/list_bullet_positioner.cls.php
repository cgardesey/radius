<?php





class List_Bullet_Positioner extends Positioner {

  function __construct(Frame_Decorator $frame) { parent::__construct($frame); }
  
  //........................................................................

  function position() {
    
    // Bullets & friends are positioned an absolute distance to the left of
    // the content edge of their parent element
    $cb = $this->_frame->get_containing_block();
    $style = $this->_frame->get_style();
    
    // Note: this differs from most frames in that we must position
    // ourselves after determining our width
    $x = $cb["x"] - $this->_frame->get_width();

    $p = $this->_frame->find_block_parent();

    $y = $p->get_current_line("y");

    // This is a bit of a hack...
    $n = $this->_frame->get_next_sibling();
    if ( $n ) {
      $style = $n->get_style();
      $y += $style->length_in_pt( array($style->margin_top, $style->padding_top),
                                  $n->get_containing_block("w") );
    }
    
    $this->_frame->set_position($x, $y);
    
  }
}
?>