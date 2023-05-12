<?php




class Text_Renderer extends Abstract_Renderer {

  const UNDERLINE_OFFSET = 0.1;  // Relative to bottom of text, as fraction of height
  const OVERLINE_OFFSET = 0.25;    // Relative to top of text,         "
  const LINETHROUGH_OFFSET = 0.0;  // Relative to centre of text,      "
  const DECO_EXTENSION = 0.75;        // How far to extend lines past either end, in pt
    
  //........................................................................

  function render(Frame $frame) {
    
    $style = $frame->get_style();
    list($x, $y) = $frame->get_position();
    $cb = $frame->get_containing_block();

    if ( ($ml = $style->margin_left) == "auto" || $ml == "none" )
      $ml = 0;

    if ( ($pl = $style->padding_left) == "auto" || $pl == "none" )
      $pl = 0;

    if ( ($bl = $style->border_left_width) == "auto" || $bl == "none" )
      $bl = 0;

    $x += $style->length_in_pt( array($ml, $pl, $bl), $cb["w"] );

    $text = $frame->get_text();
    $font = $style->font_family;
    $size = $style->font_size;
    $height = $style->height;    
    $spacing = $frame->get_text_spacing() + $style->word_spacing;

    if ( preg_replace("/[\s]+/", "", $text) == "" )
      return;
    
    $this->_canvas->text($x, $y, $text,
                         $font, $size,
                         $style->color, $spacing);

    // Handle text decoration:
    // http://www.w3.org/TR/CSS21/text.html#propdef-text-decoration
    
    // Draw all applicable text-decorations.  Start with the root and work
    // our way down.
    $p = $frame;
    $stack = array();
    while ( $p = $p->get_parent() )
      $stack[] = $p;
    
    while ( count($stack) > 0 ) {
      $f = array_pop($stack);

      $deco_y = $y;
      if ( ($text_deco = $f->get_style()->text_decoration) === "none" )
        continue;

      $color = $f->get_style()->color;

      switch ($text_deco) {

      default:
        continue;

      case "underline":
        $deco_y += $height * (1 + self::UNDERLINE_OFFSET);
        break;

      case "overline":
        $deco_y += $height * self::OVERLINE_OFFSET;
        break;

      case "line-through":
        $deco_y -= $height * ( 0.25 + self::LINETHROUGH_OFFSET);
        break;

      }

      $dx = 0;
      
      $x1 = $x - self::DECO_EXTENSION;
      $x2 = $x + $style->width + $dx + self::DECO_EXTENSION;
      $this->_canvas->line($x1, $deco_y, $x2, $deco_y, $color, 0.5);

    }
  }

}

?>