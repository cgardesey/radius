<?php





class Frame {
  
  
  protected $_node;

  
  protected $_id;

  
  protected $_style;

  
  protected $_original_style;
  
  
  protected $_parent;

  
  protected $_first_child;

  
  protected $_last_child;

  
  protected $_prev_sibling;

  
  protected $_next_sibling;
  
  
  protected $_containing_block;

  
  protected $_position;

  
  protected $_decorator;
    
  
  function __construct(DomNode $node) {
    $this->_node = $node;
      
    $this->_parent = null;
    $this->_first_child = null;
    $this->_last_child = null;
    $this->_prev_sibling = $this->_next_sibling = null;
    
    $this->_style = null;
    $this->_original_style = null;
    
    $this->_containing_block = array("x" => null,
                                     "y" => null,
                                     "w" => null,
                                     "h" => null);
    $this->_position = array("x" => null,
                             "y" => null);

    $this->_decorator = null;

    $this->set_id( uniqid(rand()) );
  }

  
  function dispose($recursive = false) {

    if ( $recursive ) {
      while ( $child = $this->_first_child )
        $child->dispose(true);
    }

    // Remove this frame from the tree
    if ( $this->_prev_sibling ) {
      $this->_prev_sibling->_next_sibling = $this->_next_sibling;      
    }

    if ( $this->_next_sibling ) {
      $this->_next_sibling->_prev_sibling = $this->_prev_sibling;
    }

    if ( $this->_parent && $this->_parent->_first_child === $this ) {
      $this->_parent->_first_child = $this->_next_sibling;
    }

    if ( $this->_parent && $this->_parent->_last_child === $this ) {
      $this->_parent->_last_child = $this->_prev_sibling;
    }

    if ( $this->_parent ) {
      $this->_parent->get_node()->removeChild($this->_node);
    }

    $this->_style->dispose();
    unset($this->_style);
    $this->_original_style->dispose();
    unset($this->_original_style);
    
  }

  // Re-initialize the frame
  function reset() {
    $this->_position = array("x" => null,
                             "y" => null);
    $this->_containing_block = array("x" => null,
                                     "y" => null,
                                     "w" => null,
                                     "h" => null);

    unset($this->_style);    
    $this->_style = clone $this->_original_style;
    
  }
  
  //........................................................................

  // Accessor methods
  function get_node() { return $this->_node; }
  function get_id() { return $this->_id; }
  function get_style() { return $this->_style; }
  function get_original_style() { return $this->_original_style; }
  function get_parent() { return $this->_parent; }
  function get_decorator() { return $this->_decorator; }
  function get_first_child() { return $this->_first_child; }
  function get_last_child() { return $this->_last_child; }
  function get_prev_sibling() { return $this->_prev_sibling; }
  function get_next_sibling() { return $this->_next_sibling; }

  function get_children() { return new FrameList($this); }
  
  // Layout property accessors
  function get_containing_block($i = null) {
    if ( isset($i) )
      return $this->_containing_block[$i];    
    return $this->_containing_block;
  }
  
  function get_position($i = null) {
    if ( isset($i) )
      return $this->_position[$i];
    return array($this->_position["x"],
                 $this->_position["y"],
                 "x"=>$this->_position["x"],
                 "y"=>$this->_position["y"]);
  }
    
  //........................................................................

  // Return the height of the margin box of the frame, in pt.  Meaningless
  // unless the height has been calculated properly.
  function get_margin_height() {      
    return $this->_style->length_in_pt(array($this->_style->height,
                                             $this->_style->margin_top,
                                             $this->_style->margin_bottom,
                                             $this->_style->border_top_width,
                                             $this->_style->border_bottom_width,
                                             $this->_style->padding_top,
                                             $this->_style->padding_bottom),
                                       $this->_containing_block["w"]);
  }

  // Return the width of the margin box of the frame, in pt.  Meaningless
  // unless the width has been calculted properly.
  function get_margin_width() {
    return $this->_style->length_in_pt(array($this->_style->width,
                                     $this->_style->margin_left,
                                     $this->_style->margin_right,
                                     $this->_style->border_left_width,
                                     $this->_style->border_right_width,
                                     $this->_style->padding_left,
                                     $this->_style->padding_right),
                               $this->_containing_block["w"]);
  }

  // Return the padding box (x,y,w,h) of the frame
  function get_padding_box() {
    $x = $this->_position["x"] +
      $this->_style->length_in_pt(array($this->_style->margin_left,
                                $this->_style->border_left_width),
                          $this->_containing_block["w"]);
    $y = $this->_position["y"] +
      $this->_style->length_in_pt(array($this->_style->margin_top,
                                $this->_style->border_top_width),
                          $this->_containing_block["w"]);
    
    $w = $this->_style->length_in_pt(array($this->_style->padding_left,
                                   $this->_style->width,
                                   $this->_style->padding_right),
                             $this->_containing_block["w"]);

    $h = $this->_style->length_in_pt(array($this->_style->padding_top,
                                   $this->_style->height,
                                   $this->_style->padding_bottom),
                             $this->_containing_block["w"]);

    return array(0 => $x, "x" => $x,
                 1 => $y, "y" => $y,
                 2 => $w, "w" => $w,
                 3 => $h, "h" => $h);
  }

  // Return the border box of the frame
  function get_border_box() {
    $x = $this->_position["x"] +
      $this->_style->length_in_pt($this->_style->margin_left,
                          $this->_containing_block["w"]);
    $y = $this->_position["y"] +
      $this->_style->length_in_pt($this->_style->margin_top,
                          $this->_containing_block["w"]);

    $w = $this->_style->length_in_pt(array($this->_style->border_left_width,
                                   $this->_style->padding_left,
                                   $this->_style->width,
                                   $this->_style->padding_right,
                                   $this->_style->border_right_width),
                             $this->_containing_block["w"]);

    $h = $this->_style->length_in_pt(array($this->_style->border_top_width,
                                   $this->_style->padding_top,
                                   $this->_style->height,
                                   $this->_style->padding_bottom,
                                   $this->_style->border_bottom_width),
                             $this->_containing_block["w"]);

    return array(0 => $x, "x" => $x,
                 1 => $y, "y" => $y,
                 2 => $w, "w" => $w,
                 3 => $h, "h" => $h);
    
  }
  
  //........................................................................

  // Set methods
  function set_id($id) {
    $this->_id = $id;

    // We can only set attributes of DOMElement objects (nodeType == 1).
    // Since these are the only objects that we can assign CSS rules to,
    // this shortcoming is okay.
    if ( $this->_node->nodeType == 1)
      $this->_node->setAttribute("frame_id", $id);
  }

  function set_style(Style $style) {
    if ( is_null($this->_style) )
      $this->_original_style = clone $style;
    
    $this->_style = $style;
  }
  
  function set_decorator(Frame_Decorator $decorator) {
    $this->_decorator = $decorator;
  }
  
  function set_containing_block($x = null, $y = null, $w = null, $h = null) {
    if ( is_array($x) ) 
      extract($x);
    
    if (is_numeric($x)) {
      $this->_containing_block[0] = $x;
      $this->_containing_block["x"] = $x;
    }
    
    if (is_numeric($y)) {
      $this->_containing_block[1] = $y;
      $this->_containing_block["y"] = $y;
    }
    
    if (is_numeric($w)) {
      $this->_containing_block[2] = $w;
      $this->_containing_block["w"] = $w;
    }
    
    if (is_numeric($h)) {
      $this->_containing_block[3] = $h;
      $this->_containing_block["h"] = $h;
    }
    
  }

  function set_position($x = null, $y = null) {
    if ( is_array($x) )
      extract($x);
    
    if ( is_numeric($x) ) {
      $this->_position[0] = $x;
      $this->_position["x"] = $x;
    }

    if ( is_numeric($y) ) {
      $this->_position[1] = $y;
      $this->_position["y"] = $y;
    }
  }

  //........................................................................

  function prepend_child(Frame $child, $update_node = true) {

    if ( $update_node ) 
      $this->_node->insertBefore($child->_node, $this->_first_child ? $this->_first_child->_node : null);

    // Remove the child from its parent
    if ( $child->_parent )
      $child->_parent->remove_child($child, false);
    
    $child->_parent = $this;
    $child->_prev_sibling = null;
    
    // Handle the first child
    if ( !$this->_first_child ) {
      $this->_first_child = $child;
      $this->_last_child = $child;
      $child->_next_sibling = null;
      
    } else {

      $this->_first_child->_prev_sibling = $child;
      $child->_next_sibling = $this->_first_child;      
      $this->_first_child = $child;
      
    }
  }
  
  function append_child(Frame $child, $update_node = true) {

    if ( $update_node ) 
      $this->_node->appendChild($child->_node);

    // Remove the child from its parent
    if ( $child->_parent )
      $child->_parent->remove_child($child, false);

    $child->_parent = $this;
    $child->_next_sibling = null;
    
    // Handle the first child
    if ( !$this->_last_child ) {
      $this->_first_child = $child;
      $this->_last_child = $child;
      $child->_prev_sibling = null;
      
    } else {

      $this->_last_child->_next_sibling = $child;
      $child->_prev_sibling = $this->_last_child;
      $this->_last_child = $child;

    }
  }  

  // Inserts a new child immediately before the specified frame
  function insert_child_before(Frame $new_child, Frame $ref, $update_node = true) {

    if ( $ref === $this->_first_child ) {
      $this->prepend_child($new_child, $update_node);
      return;
    }

    if ( is_null($ref) ) {
      $this->append_child($new_child, $update_node);
      return;
    }
    
    if ( $ref->_parent !== $this )
      throw new DOMPDF_Exception("Reference child is not a child of this node.");

    // Update the node    
    if ( $update_node )
      $this->_node->insertBefore($new_child->_node, $ref->_node);

    // Remove the child from its parent
    if ( $new_child->_parent )
      $new_child->_parent->remove_child($new_child, false);
    
    $new_child->_parent = $this;
    $new_child->_next_sibling = $ref;
    $new_child->_prev_sibling = $ref->_prev_sibling;

    if ( $ref->_prev_sibling )
      $ref->_prev_sibling->_next_sibling = $new_child;
    
    $ref->_prev_sibling = $new_child;
  }
  
  // Inserts a new child immediately after the specified frame
  function insert_child_after(Frame $new_child, Frame $ref, $update_node = true) {    

    if ( $ref === $this->_last_child ) {
      $this->append_child($new_child, $update_node);
      return;
    }

    if ( is_null($ref) ) {
      $this->prepend_child($new_child, $update_node);
      return;
    }
    
    if ( $ref->_parent !== $this )
      throw new DOMPDF_Exception("Reference child is not a child of this node.");

    // Update the node
    if ( $update_node ) {
      if ( $ref->_next_sibling ) {
        $next_node = $ref->_next_sibling->_node;
        $this->_node->insertBefore($new_child->_node, $next_node);
      } else {
        $new_child->_node = $this->_node->appendChild($new_child);
      }
    }
    
    // Remove the child from its parent
    if ( $new_child->_parent)
      $new_child->_parent->remove_child($new_child, false);
    
    $new_child->_parent = $this;
    $new_child->_prev_sibling = $ref;
    $new_child->_next_sibling = $ref->_next_sibling;

    if ( $ref->_next_sibling ) 
      $ref->_next_sibling->_prev_sibling = $new_child;

    $ref->_next_sibling = $new_child;
  }


  function remove_child(Frame $child, $update_node = true) {

    if ( $child->_parent !== $this )
      throw new DOMPDF_Exception("Child not found in this frame");

    if ( $update_node )
      $this->_node->removeChild($child->_node);
    
    if ( $child === $this->_first_child )
      $this->_first_child = $child->_next_sibling;

    if ( $child === $this->_last_child )
      $this->_last_child = $child->_prev_sibling;

    if ( $child->_prev_sibling )
      $child->_prev_sibling->_next_sibling = $child->_next_sibling;

    if ( $child->_next_sibling )
      $child->_next_sibling->_prev_sibling = $child->_prev_sibling;    

    $child->_next_sibling = null;
    $child->_prev_sibling = null;
    $child->_parent = null;
    return $child;
        
  }

  //........................................................................

  // Debugging function:
  function __toString() {

    // Skip empty text frames
    if ( $this->_node->nodeName == "#text" &&
         preg_replace("/\s/", "", $this->_node->data) === "" )
      return "";
    
    
    $str = "<b>" . $this->_node->nodeName . ":</b><br/>";
    $str .= (string)$this->_node . "<br/>";
    $str .= "Id: " .$this->get_id() . "<br/>";
    $str .= "Class: " .get_class($this) . "<br/>";
    
    if ( $this->_node->nodeName == "#text" ) {
      $tmp = htmlspecialchars($this->_node->nodeValue);
      $str .= "<pre>'" .  mb_substr($tmp,0,70) .
        (mb_strlen($tmp) > 70 ? "..." : "") . "'</pre>";
    }
    if ( $this->_parent )
      $str .= "\nParent:" . $this->_parent->_node->nodeName .
        " (" . (string)$this->_parent->_node . ") " .
        "<br/>";

    if ( $this->_prev_sibling )
      $str .= "Prev: " . $this->_prev_sibling->_node->nodeName .
        " (" . (string)$this->_prev_sibling->_node . ") " .
        "<br/>";

    if ( $this->_next_sibling )
      $str .= "Next: " . $this->_next_sibling->_node->nodeName .
        " (" . (string)$this->_next_sibling->_node . ") " .
        "<br/>";

    $d = $this->get_decorator();
    while ($d && $d != $d->get_decorator()) {
      $str .= "Decorator: " . get_class($d) . "<br/>";
      $d = $d->get_decorator();
    }

    $str .= "Position: " . pre_r($this->_position, true);
    $str .= "\nContaining block: " . pre_r($this->_containing_block, true);
    $str .= "\nMargin width: " . pre_r($this->get_margin_width(), true);
    $str .= "\nMargin height: " . pre_r($this->get_margin_height(), true);
    
    $str .= "\nStyle: <pre>". $this->_style->__toString() . "</pre>";

    if ( $this->_decorator instanceof Block_Frame_Decorator ) {
      $str .= "Lines:<pre>";
      foreach ($this->_decorator->get_lines() as $line) {
        foreach ($line["frames"] as $frame) {
          if ($frame instanceof Text_Frame_Decorator) {
            $str .= "\ntext: ";          
            $str .= htmlspecialchars($frame->get_text());
          } else {
            $str .= "\nBlock: " . $frame->get_node()->nodeName . " (" . (string)$frame->get_node() . ")";
          }
        }
        
        $str .=
          //"\ncount => " . $line["count"] . "\n".
          "\ny => " . $line["y"] . "\n" .
          "w => " . $line["w"] . "\n" .
          "h => " . $line["h"] . "\n";
      }
      $str .= "</pre>";
    }
    $str .= "\n";
    if ( php_sapi_name() == "cli" )
      $str = strip_tags(str_replace(array("<br/>","<b>","</b>"),
                                    array("\n","",""),
                                    $str));
    
    return $str;
  }
        
}

//------------------------------------------------------------------------


class FrameList implements IteratorAggregate {
  protected $_frame;

  function __construct($frame) { $this->_frame = $frame; }
  function getIterator() { return new FrameListIterator($this->_frame); }
}
  

class FrameListIterator implements Iterator {

  protected $_parent;
  protected $_cur;
  protected $_num;

  function __construct(Frame $frame) {
    $this->_parent = $frame;
    $this->_cur = $frame->get_first_child();
    $this->_num = 0;
  }

  function rewind() { 
    $this->_cur = $this->_parent->get_first_child();
    $this->_num = 0;
  }

  function valid() {
    return isset($this->_cur);// && ($this->_cur->get_prev_sibling() === $this->_prev);
  }
  function key() { return $this->_num; }
  function current() { return $this->_cur; }

  function next() {

    $ret = $this->_cur;
    if ( !$ret )
      return null;
    
    $this->_cur = $this->_cur->get_next_sibling();
    $this->_num++;
    return $ret;
  }
}

//------------------------------------------------------------------------


class FrameTreeList implements IteratorAggregate {

  protected $_root;
  function __construct(Frame $root) { $this->_root = $root; }
  function getIterator() { return new FrameTreeIterator($this->_root); }

}


class FrameTreeIterator implements Iterator {

  protected $_root;
  protected $_stack = array();
  protected $_num;
  
  function __construct(Frame $root) {
    $this->_stack[] = $this->_root = $root;
    $this->_num = 0;
  }

  function rewind() {
    $this->_stack = array($this->_root);
    $this->_num = 0;
  }
    
  function valid() { return count($this->_stack) > 0; }
  function key() { return $this->_num; }
  function current() { return end($this->_stack); }

  function next() {
    $b = end($this->_stack);
    
    // Pop last element
    unset($this->_stack[ key($this->_stack) ]);
    $this->_num++;
    
    // Push all children onto the stack in reverse order
    if ( $c = $b->get_last_child() ) {
      $this->_stack[] = $c;
      while ( $c = $c->get_prev_sibling() )
        $this->_stack[] = $c;
    }
    return $b;
  }
}

?>