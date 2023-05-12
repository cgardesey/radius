<?php





class Renderer extends Abstract_Renderer {


  protected $_renderers;
    

  function new_page() {
    $this->_canvas->new_page();
  }


  function render(Frame $frame) {    
    global $_dompdf_debug;

    if ( $_dompdf_debug ) {
      echo $frame;
      flush();
    }                      

    $display = $frame->get_style()->display;
    
    switch ($display) {
      
    case "block":
    case "inline-block":
    case "table":
    case "table-row-group":
    case "table-header-group":
    case "table-footer-group":
    case "inline-table":
      $this->_render_frame("block", $frame);
      break;

    case "inline":
      if ( $frame->get_node()->nodeName == "#text" )
        $this->_render_frame("text", $frame);
      else
        $this->_render_frame("inline", $frame);
      break;

    case "table-cell":
      $this->_render_frame("table-cell", $frame);
      break;

    case "-dompdf-list-bullet":
      $this->_render_frame("list-bullet", $frame);
      break;

    case "-dompdf-image":
      $this->_render_frame("image", $frame);
      break;
      
    case "none":
      $node = $frame->get_node();
          
      if ( $node->nodeName == "script" &&
           ( $node->getAttribute("type") == "text/php" ||
             $node->getAttribute("language") == "php" ) ) {
        // Evaluate embedded php scripts
        $this->_render_frame("php", $frame);
      }

      // Don't render children, so skip to next iter
      return;
      
    default:
      break;

    }

    foreach ($frame->get_children() as $child)
      $this->render($child);

  }


  protected function _render_frame($type, $frame) {

    if ( !isset($this->_renderers[$type]) ) {
      
      switch ($type) {
      case "block":
        $this->_renderers["block"] = new Block_Renderer($this->_dompdf);
        break;

      case "inline":
        $this->_renderers["inline"] = new Inline_Renderer($this->_dompdf);
        break;

      case "text":
        $this->_renderers["text"] = new Text_Renderer($this->_dompdf);
        break;

      case "image":
        $this->_renderers["image"] = new Image_Renderer($this->_dompdf);
        break;
      
      case "table-cell":
        $this->_renderers["table-cell"] = new Table_Cell_Renderer($this->_dompdf);
        break;

      case "list-bullet":
        $this->_renderers["list-bullet"] = new List_Bullet_Renderer($this->_dompdf);
        break;

      case "php":
        $this->_renderers["php"] = new PHP_Evaluator($this->_canvas);
        break;

      }
    }
    
    $this->_renderers[$type]->render($frame);

  }
}

?>