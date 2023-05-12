<?php





class Table_Row_Group_Frame_Decorator extends Frame_Decorator {


  function __construct(Frame $frame, DOMPDF $dompdf) {
    parent::__construct($frame, $dompdf);
  }


  function split($child = null) {

    if ( is_null($child) ) {
      parent::split();
      return;
    }


    // Remove child & all subsequent rows from the cellmap
    $cellmap = $this->get_parent()->get_cellmap();
    $iter = $child;

    while ( $iter ) {
      $cellmap->remove_row($iter);
      $iter = $iter->get_next_sibling();
    }

    // If we are splitting at the first child remove the
    // table-row-group from the cellmap as well
    if ( $child === $this->get_first_child() ) {
      $cellmap->remove_row_group($this);
      parent::split();
      return;
    }
    
    $cellmap->update_row_group($this, $child->get_prev_sibling());
    parent::split($child);
    
  }
}
 
?>