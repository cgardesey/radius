<?php





abstract class Frame_Decorator extends Frame {
  

  protected $_root;


  protected $_frame;


  protected $_positioner;


  protected $_reflower;
  

  protected $_dompdf;


  function __construct(Frame $frame, DOMPDF $dompdf) {
    $this->_frame = $frame;
    $this->_root = null;
    $this->_dompdf = $dompdf;
    $frame->set_decorator($this);
  }


  function dispose($recursive = false) {
    
    if ( $recursive ) {
      while ( $child = $this->get_first_child() )
        $child->dispose(true);
    }
    
    unset($this->_root);
    $this->_frame->dispose(false);
    unset($this->_frame);
    unset($this->_positioner);
    unset($this->_reflower);

  }

  // Return a copy of this frame with $node as its node
  function copy(DomNode $node) {
    $frame = new Frame($node);
    $frame->set_style(clone $this->_frame->get_original_style());
    $deco = Frame_Factory::decorate_frame($frame, $this->_dompdf);
    $deco->set_root($this->_root);
    return $deco;
  }


  function deep_copy() {
    $frame = new Frame($this->get_node()->cloneNode());
    $frame->set_style(clone $this->_frame->get_original_style());
    $deco = Frame_Factory::decorate_frame($frame, $this->_dompdf);
    $deco->set_root($this->_root);

    foreach ($this->get_children() as $child)
      $deco->append_child($child->deep_copy());

    return $deco;
  }
  //........................................................................
  
  // Delegate calls to decorated frame object
  function reset() {
    $this->_frame->reset();

    // Reset all children
    foreach ($this->get_children() as $child)
      $child->reset();

  }
  
  function get_node() { return $this->_frame->get_node(); }
  function get_id() { return $this->_frame->get_id(); }
  function get_style() { return $this->_frame->get_style(); }
  function get_original_style() { return $this->_frame->get_original_style(); }
  function get_containing_block($i = null) { return $this->_frame->get_containing_block($i); }
  function get_position($i = null) { return $this->_frame->get_position($i); }
//   function get_decorator() {
//     if ( isset($this->_decorator) )
//       return $this->_decorator;
//     else
//       return $this;
//   }

  function get_margin_height() { return $this->_frame->get_margin_height(); }
  function get_margin_width() { return $this->_frame->get_margin_width(); }
  function get_padding_box() { return $this->_frame->get_padding_box(); }
  function get_border_box() { return $this->_frame->get_border_box(); }

  function set_id($id) { $this->_frame->set_id($id); }
  function set_style(Style $style) { $this->_frame->set_style($style); }

  function set_containing_block($x = null, $y = null, $w = null, $h = null) {
    $this->_frame->set_containing_block($x, $y, $w, $h);
  }

  function set_position($x = null, $y = null) {
    $this->_frame->set_position($x, $y);
  }
  function __toString() { return $this->_frame->__toString(); }
  
  function prepend_child(Frame $child, $update_node = true) {
    while ( $child instanceof Frame_Decorator )
      $child = $child->_frame;
    
    $this->_frame->prepend_child($child, $update_node);
  }

  function append_child(Frame $child, $update_node = true) {
    while ( $child instanceof Frame_Decorator )
      $child = $child->_frame;

    $this->_frame->append_child($child, $update_node);
  }

  function insert_child_before(Frame $new_child, Frame $ref, $update_node = true) {
    while ( $new_child instanceof Frame_Decorator )
      $new_child = $new_child->_frame;

    if ( $ref instanceof Frame_Decorator )
      $ref = $ref->_frame;

    $this->_frame->insert_child_before($new_child, $ref, $update_node);
  }

  function insert_child_after(Frame $new_child, Frame $ref, $update_node = true) {
    while ( $new_child instanceof Frame_Decorator )
      $new_child = $new_child->_frame;

    while ( $ref instanceof Frame_Decorator )
      $ref = $ref->_frame;
    
    $this->_frame->insert_child_after($new_child, $ref, $update_node);
  }

  function remove_child(Frame $child, $update_node = true) {
    while  ( $child instanceof Frame_Decorator )
      $child = $new_child->_frame;

    $this->_frame->remove_child($child, $update_node);
  }
  
  //........................................................................

  function get_parent() {

    $p = $this->_frame->get_parent();
    
    if ( $p && $deco = $p->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() )
        $deco = $tmp;      
      return $deco;
    } else if ( $p )
      return $p;
    else
      return null;
  }

  function get_first_child() {
    $c = $this->_frame->get_first_child();
    if ( $c && $deco = $c->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() )
        $deco = $tmp;      
      return $deco;
    } else if ( $c )
      return $c;
    else
      return null;
  }

  function get_last_child() {
    $c = $this->_frame->get_last_child();
    if ( $c && $deco = $c->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() )
        $deco = $tmp;      
      return $deco;
    } else if ( $c )
      return $c;
    else
      return null;
  }

  function get_prev_sibling() {
    $s = $this->_frame->get_prev_sibling();
    if ( $s && $deco = $s->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() )
        $deco = $tmp;      
      return $deco;
    } else if ( $s )
      return $s;
    else
      return null;
  }
  
  function get_next_sibling() {
    $s = $this->_frame->get_next_sibling();
    if ( $s && $deco = $s->get_decorator() ) {
      while ( $tmp = $deco->get_decorator() )
        $deco = $tmp;      
      return $deco;
    } else if ( $s )
      return $s;
    else
      return null;
  }

  function get_children() {
    return new FrameList($this);
  }

  function get_subtree() {
    return new FrameTreeList($this);
  }
  
  //........................................................................

  function set_positioner(Positioner $posn) {
    $this->_positioner = $posn;
    if ( $this->_frame instanceof Frame_Decorator )
      $this->_frame->set_positioner($posn);
  }
  
  //........................................................................

  function set_reflower(Frame_Reflower $reflower) {
    $this->_reflower = $reflower;
    if ( $this->_frame instanceof Frame_Decorator )
      $this->_frame->set_reflower( $reflower );
  }
  
  function get_reflower() { return $this->_reflower; }
  
  //........................................................................
  
  function set_root(Frame $root) {
    $this->_root = $root;
      if ( $this->_frame instanceof Frame_Decorator )
        $this->_frame->set_root($root);
  }
  
  function get_root() { return $this->_root; }
  
  //........................................................................

  function find_block_parent() {

    // Find our nearest block level parent
    $p = $this->get_parent();
    
    while ( $p ) {
      if ( in_array($p->get_style()->display, Style::$BLOCK_TYPES) )
        break;

      $p = $p->get_parent();
    }

    return $p;
  }

  //........................................................................


  function split($child = null) {

    if ( is_null( $child ) ) {
      $this->get_parent()->split($this);
      return;
    }
    
    if ( $child->get_parent() !== $this )
      throw new DOMPDF_Exception("Unable to split: frame is not a child of this one.");

    $split = $this->copy( $this->_frame->get_node()->cloneNode() );
    $split->reset();
    $this->get_parent()->insert_child_after($split, $this);

    // Add $frame and all following siblings to the new split node
    $iter = $child;
    while ($iter) {
      $frame = $iter;      
      $iter = $iter->get_next_sibling();
      $frame->reset();
      $split->append_child($frame);
    }

    $this->get_parent()->split($split);
  }

  //........................................................................

  final function position() { $this->_positioner->position();  }
  
  final function reflow() {
    // Uncomment this to see the frames before they're laid out, instead of
    // during rendering.
    //echo $this->_frame; flush();
    $this->_reflower->reflow();
  }

  final function get_min_max_width() { return $this->get_reflower()->get_min_max_width(); }
  
  //........................................................................


}

?>