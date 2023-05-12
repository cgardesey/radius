<?php





class Inline_Frame_Decorator extends Frame_Decorator {
  
  function __construct(Frame $frame, DOMPDF $dompdf) { parent::__construct($frame, $dompdf); }

  function split($frame = null) {

    if ( is_null($frame) ) {
      $this->get_parent()->split($this);
      return;
    }
    
    if ( $frame->get_parent() !== $this )
      throw new DOMPDF_Exception("Unable to split: frame is not a child of this one.");
        
    $split = $this->copy( $this->_frame->get_node()->cloneNode() ); 
    $this->get_parent()->insert_child_after($split, $this);

    // Unset the split node's left style properties since we don't want them
    // to propagate
    $style = $split->get_style();
    $style->margin_left = "0";
    $style->padding_left = "0";
    $style->border_left_width = "0";
    
    // Add $frame and all following siblings to the new split node
    $iter = $frame;
    while ($iter) {
      $frame = $iter;      
      $iter = $iter->get_next_sibling();
      $frame->reset();
      $split->append_child($frame);
    }
  }
  
} 
?>