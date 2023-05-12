<?php




class Inline_Positioner extends Positioner {

  function __construct(Frame_Decorator $frame) { parent::__construct($frame); }

  //........................................................................

  function position() {
    $cb = $this->_frame->get_containing_block();

    // Find our nearest block level parent and access its lines property.
    $p = $this->_frame->find_block_parent();

    // Debugging code:

//     pre_r("\nPositioning:");
//     pre_r("Me: " . $this->_frame->get_node()->nodeName . " (" . (string)$this->_frame->get_node() . ")");
//     pre_r("Parent: " . $p->get_node()->nodeName . " (" . (string)$p->get_node() . ")");

    // End debugging

    if ( !$p )
      throw new DOMPDF_Exception("No block-level parent found.  Not good.");

    $line = $p->get_current_line();
    
    $this->_frame->set_position($cb["x"] + $line["w"], $line["y"]);

  }
}
?>