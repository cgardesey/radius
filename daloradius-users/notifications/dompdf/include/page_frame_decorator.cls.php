<?php





class Page_Frame_Decorator extends Frame_Decorator {
  

  protected $_bottom_page_margin;
  

  protected $_page_full;
  

  protected $_in_table;


  protected $_renderer;
  
  //........................................................................


  function __construct(Frame $frame, DOMPDF $dompdf) {
    parent::__construct($frame, $dompdf);
    $this->_page_full = false;
    $this->_in_table = 0;
    $this->_bottom_page_margin = null;
  }

  

  function set_renderer($renderer) {
    $this->_renderer = $renderer;
  }


  function get_renderer() {
    return $this->_renderer;
  }
  

  function set_containing_block($x = null, $y = null, $w = null, $h = null) {
    parent::set_containing_block($x,$y,$w,$h);
    $w = $this->get_containing_block("w");
    if ( isset($h) )
      $this->_bottom_page_margin = $h; // - $this->_frame->get_style()->length_in_pt($this->_frame->get_style()->margin_bottom, $w);
  }


  function is_full() {
    return $this->_page_full;
  }


  function next_page() {
    $this->_renderer->new_page();
    $this->_page_full = false;
  }


  function table_reflow_start() {
    $this->_in_table++;
  }


  function table_reflow_end() {
    $this->_in_table--;
  }
  

  function check_forced_page_break(Frame $frame) {

    // Skip check if page is already split
    if ( $this->_page_full )
      return;
    
    $block_types = array("block", "list-item", "table");
    $page_breaks = array("always", "left", "right");
    
    $style = $frame->get_style();

    if ( !in_array($style->display, $block_types) )
      return false;

    // Find the previous block-level sibling
    $prev = $frame->get_prev_sibling();
    while ( $prev && !in_array($prev->get_style()->display, $block_types) )
      $prev = $prev->get_prev_sibling();

    if ( in_array($style->page_break_before, $page_breaks) ) {

      // Prevent cascading splits
      $frame->split();
      // We have to grab the style again here because split() resets
      // $frame->style to the frame's orignal style.
      $frame->get_style()->page_break_before = "auto";
      $this->_page_full = true;
      return true;
    }

    if ( ($prev && in_array($prev->get_style()->page_break_after, $page_breaks)) ) {
      // Prevent cascading splits
      $frame->split();
      $prev->get_style()->page_break_after = "auto";
      $this->_page_full = true;
      return true;
    }
    
    return false;
  }


  protected function _page_break_allowed(Frame $frame) {


    $block_types = array("block", "list-item", "table");
//      echo "\nbreak_allowed: " . $frame->get_node()->nodeName ."\n";
    $display = $frame->get_style()->display;
    
    // Block Frames (1):
    if ( in_array($display, $block_types) ) {

      // Avoid breaks within table-cells
      if ( $this->_in_table ) {
//          echo "In table: " . $this->_in_table ."\n";
        return false;
      }
      
      // Rules A & B
      
      if ( $frame->get_style()->page_break_before == "avoid" ) {
//          echo "before: avoid\n";
        return false;
      }
      
      // Find the preceeding block-level sibling
      $prev = $frame->get_prev_sibling();
      while ( $prev && !in_array($prev->get_style()->display, $block_types) )
        $prev = $prev->get_prev_sibling();

      // Does the previous element allow a page break after?
      if ( $prev && $prev->get_style()->page_break_after == "avoid" ) {
//        echo "after: avoid\n";
        return false;
      }

      // If both $prev & $frame have the same parent, check the parent's
      // page_break_inside property.
      $parent = $frame->get_parent();
      if ( $prev && $parent && $parent->get_style()->page_break_inside == "avoid" ) {
//          echo "parent inside: avoid\n";
        return false;
      }

      // To prevent cascading page breaks when a top-level element has
      // page-break-inside: avoid, ensure that at least one frame is
      // on the page before splitting.
      if ( $parent->get_node()->nodeName == "body" && !$prev ) {
        // We are the body's first child
//          echo "Body's first child.\n";
        return false;
      }
      
      // If the frame is the first block-level frame, use the value from
      // $frame's parent instead.
      if ( !$prev && $parent ) 
        return $this->_page_break_allowed( $parent );

//       echo "block: break allowed\n";      
      return true;
    
    }

    // Inline frames (2):    
    else if ( in_array($display, Style::$INLINE_TYPES) ) {
      
      // Avoid breaks within table-cells
      if ( $this->_in_table ) {
//          echo "In table: " . $this->_in_table ."\n";
        return false;
      }
      
      // Rule C
      $block_parent = $frame->find_block_parent();
      if ( count($block_parent->get_lines() ) < $frame->get_style()->orphans ) {
//          echo "orphans\n";
        return false;
      }
      
      // FIXME: Checking widows is tricky without having laid out the
      // remaining line boxes.  Just ignore it for now...

      // Rule D
      if ( $block_parent->get_style()->page_break_inside == "avoid" ) {
//          echo "parent->inside: avoid\n";
        return false;
      }

      // To prevent cascading page breaks when a top-level element has
      // page-break-inside: avoid, ensure that at least one frame with
      // some content is on the page before splitting.
      $prev = $frame->get_prev_sibling();
      while ( $prev && ($prev->get_node()->nodeName == "#text" && trim($prev->get_node()->nodeValue) == "") )
        $prev = $prev->get_prev_sibling();
      
      if ( $block_parent->get_node()->nodeName == "body" && !$prev ) {
        // We are the body's first child
//          echo "Body's first child.\n";
        return false;
      }

      // Skip breaks on empty text nodes
      if ( $frame->get_node()->nodeName == "#text" &&
           $frame->get_node()->nodeValue == "" )
        return false;
      
//       echo "inline: break allowed\n";
      return true;
 
    // Table-rows
    } else if ( $display == "table-row" ) {

      // Simply check if the parent table's page_break_inside property is
      // not 'avoid'
      $p = Table_Frame_Decorator::find_parent_table($frame);

      if ( $p->get_style()->page_break_inside == "avoid" ) {
//          echo "table: page-break-inside: avoid\n";
        return false;
      }

      // Check the table's parent element
      $table_parent = $p->get_parent();
      
      if ( $table_parent->get_style()->page_break_inside == "avoid" ) {
//          echo "table->parent: page-break-inside: avoid\n";
        return false;
      }

      // Avoid breaking after the first row of a table
      if ( $p->get_first_child() === $frame) {
//         echo "table: first-row\n";
        return false;
      }

//       echo "table-row/row-groups: break allowed\n";
      return true;

    } else if ( in_array($display, Table_Frame_Decorator::$ROW_GROUPS) ) {

      // Disallow breaks at row-groups: only split at row boundaries
      return false;

    } else {

//       echo "? " . $frame->get_style()->display . "\n";
      return false;
    }
  
  }
  

  function check_page_break(Frame $frame) {
    // Do not split if we have already
    if ( $this->_page_full )
      return false;
    
    // Determine the frame's maximum y value
    $max_y = $frame->get_position("y") + $frame->get_margin_height();

    // If a split is to occur here, then the bottom margins & paddings of all
    // parents of $frame must fit on the page as well:
    $p = $frame->get_parent();
    while ( $p ) {
      $style = $p->get_style();
      $max_y += $style->length_in_pt(array($style->margin_bottom,
                                           $style->padding_bottom,
                                           $style->border_bottom_width));
      $p = $p->get_parent();
    }

    
    // Check if $frame flows off the page    
    if ( $max_y <= $this->_bottom_page_margin ) 
      // no: do nothing (?)
      return false;

//    echo "check_page_break\n";
    
    // yes: determine page break location
    $iter = $frame;
    $flg = false;

    $in_table = $this->_in_table;
    
//     echo "Starting search\n";
    while ( $iter ) {
//       echo "\nbacktrack: " .$iter->get_node()->nodeName ." ".(string)$iter->get_node(). "\n";
      if ( $iter === $this ) {
//         echo "reached root.\n";
        // We've reached the root in our search.  Just split at $frame.
        break;
      }
      
      if ( $this->_page_break_allowed($iter) ) {
//        echo "break allowed, splitting.\n";
        $iter->split();
        $this->_page_full = true;
        $this->_in_table = $in_table;
        return true;
      }

      if ( !$flg && $next = $iter->get_last_child() ) {
//         echo "following last child.\n";
        
        if ( in_array($next->get_style()->display, Style::$TABLE_TYPES) )
          $this->_in_table++;

        $iter = $next;
        continue;
      }

      if ( $next = $iter->get_prev_sibling() ) {
//         echo "following prev sibling.\n";

        if ( in_array($next->get_style()->display, Style::$TABLE_TYPES) &&
             !in_array($iter->get_style()->display, Style::$TABLE_TYPES) )
          $this->_in_table++;

        else if ( !in_array($next->get_style()->display, Style::$TABLE_TYPES) &&
                  in_array($iter->get_style()->display, Style::$TABLE_TYPES) )
          $this->_in_table--;
        
        $iter = $next;
        $flg = false;
        continue;
      }

      if ( $next = $iter->get_parent() ) {
//         echo "following parent.\n";

        if ( in_array($iter->get_style()->display, Style::$TABLE_TYPES) )
          $this->_in_table--;
        
        $iter = $next;
        $flg = true;
        continue;
      }

      break;
        
    }

    $this->_in_table = $in_table;

    // No valid page break found.  Just break at $frame.
//     echo "no valid break found, just splitting.\n";

    // If we are in a table, backtrack to the nearest table row
    if ( $this->_in_table ) {
      $tr = $frame;
      while ($tr && $tr->get_style()->display != "table-row" )
        $tr = $tr->get_parent();
      
      $tr->split();
      $this->_page_full = true;
      return true;
    }        

    $frame->split();
    $this->_page_full = true;
    return true;
    
  }
  
  //........................................................................

  function split($frame = null) {
    // Do nothing
  }

}
?>