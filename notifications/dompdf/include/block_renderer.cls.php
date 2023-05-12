<?php





class Block_Renderer extends Abstract_Renderer {

  //........................................................................

  function render(Frame $frame) {
    $style = $frame->get_style();
    list($x, $y, $w, $h) = $frame->get_padding_box();

    // Draw our background, border and content
    if ( ($bg = $style->background_color) !== "transparent" ) {
      $this->_canvas->filled_rectangle( $x, $y, $w, $h, $style->background_color );
    }

    if ( ($url = $style->background_image) && $url !== "none" )
      $this->_background_image($url, $x, $y, $w, $h, $style);


    $this->_render_border($frame);

  }

  protected function _render_border(Frame_Decorator $frame, $corner_style = "bevel") {
    $cb = $frame->get_containing_block();
    $style = $frame->get_style();

    $bbox = $frame->get_border_box();
    $bp = $frame->get_style()->get_border_properties();

    $widths = array($style->length_in_pt($bp["top"]["width"]),
                    $style->length_in_pt($bp["right"]["width"]),
                    $style->length_in_pt($bp["bottom"]["width"]),
                    $style->length_in_pt($bp["left"]["width"]));

    foreach ($bp as $side => $props) {
      list($x, $y, $w, $h) = $bbox;

      if ( !$props["style"] || $props["style"] == "none" || $props["width"] <= 0 )
        continue;


      switch($side) {
      case "top":
        $length = $w;
        break;

      case "bottom":
        $length = $w;
        $y += $h;
        break;

      case "left":
        $length = $h;
        break;

      case "right":
        $length = $h;
        $x += $w;
        break;
      default:
        break;
      }
      $method = "_border_" . $props["style"];

      $this->$method($x, $y, $length, $props["color"], $widths, $side, $corner_style);
    }
  }
}

?>