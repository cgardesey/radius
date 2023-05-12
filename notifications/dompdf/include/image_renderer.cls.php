<?php





class Image_Renderer extends Abstract_Renderer {

  function render(Frame $frame) {

    // Render background & borders
    //parent::render($frame);
    $p = $frame->get_parent();
    $style = $frame->get_style();
    
    $cb = $frame->get_containing_block();
    
    list($x, $y) = $frame->get_padding_box();
    $x += $style->length_in_pt($style->padding_left, $cb["w"]);
    $y += $style->length_in_pt($style->padding_top, $cb["h"]);

    $w = $style->length_in_pt($style->width, $cb["w"]);
    $h = $style->length_in_pt($style->height, $cb["h"]);

    $this->_canvas->image( $frame->get_image_url(), $frame->get_image_ext(), $x, $y, $w, $h);

  }
}
?>