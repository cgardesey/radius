<?php





class Page_Frame_Reflower extends Frame_Reflower {


  function __construct(Page_Frame_Decorator $frame) { parent::__construct($frame); }
  
  //........................................................................

  function reflow() {
    $style = $this->_frame->get_style();
    
    // Paged layout:
    // http://www.w3.org/TR/CSS21/page.html

    // Pages are only concerned with margins
    $cb = $this->_frame->get_containing_block();
    $left = $style->length_in_pt($style->margin_left, $cb["w"]);
    $right = $style->length_in_pt($style->margin_right, $cb["w"]);
    $top = $style->length_in_pt($style->margin_top, $cb["w"]);
    $bottom = $style->length_in_pt($style->margin_bottom, $cb["w"]);
    
    $content_x = $cb["x"] + $left;
    $content_y = $cb["y"] + $top;
    $content_width = $cb["w"] - $left - $right;
    $content_height = $cb["h"] - $top - $bottom;

    $child = $this->_frame->get_first_child();

    while ($child) {

      $child->set_containing_block($content_x, $content_y, $content_width, $content_height);
      $child->reflow();
      $next_child = $child->get_next_sibling();
      
      // Render the page
      $this->_frame->get_renderer()->render($child);
      if ( $next_child )
        $this->_frame->next_page();

      // Dispose of all frames on the old page
      $child->dispose(true);
      
      $child = $next_child;
    }
  }  
}
?>