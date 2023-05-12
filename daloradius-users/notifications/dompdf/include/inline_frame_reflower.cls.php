<?php





class Inline_Frame_Reflower extends Frame_Reflower {

  function __construct(Frame $frame) { parent::__construct($frame); }
  
  //........................................................................

  function reflow() {
    $style = $this->_frame->get_style();
    $this->_frame->position();

    $cb = $this->_frame->get_containing_block();

    // Add our margin, padding & border to the first and last children
    if ( ($f = $this->_frame->get_first_child()) && $f instanceof Text_Frame_Decorator ) {
      $f->get_style()->margin_left = $style->margin_left;
      $f->get_style()->padding_left = $style->padding_left;
      $f->get_style()->border_left = $style->border_left;
    }
    
    if ( ($l = $this->_frame->get_last_child()) && $l instanceof Text_Frame_Decorator ) {
      $f->get_style()->margin_right = $style->margin_right;
      $f->get_style()->padding_right = $style->padding_right;
      $f->get_style()->border_right = $style->border_right;
    }

    // Set the containing blocks and reflow each child.  The containing
    // block is not changed by line boxes.
    foreach ( $this->_frame->get_children() as $child ) {
      $child->set_containing_block($cb);
      $child->reflow();
    }
  }
}
?>