<?php





class Frame_Factory {

  static function decorate_root(Frame $root, DOMPDF $dompdf) {
    $frame = new Page_Frame_Decorator($root, $dompdf);
    $frame->set_reflower( new Page_Frame_Reflower($frame) );
    $root->set_decorator($frame);
    return $frame;
  }

  // FIXME: this is admittedly a little smelly...
  static function decorate_frame(Frame $frame, $dompdf) {
    if ( is_null($dompdf) )
      throw new Exception("foo");
    switch ($frame->get_style()->display) {
      
    case "block":
      $positioner = "Block";        
      $decorator = "Block";
      $reflower = "Block";
      break;
    
    case "inline-block":
      $positioner = "Inline";
      $decorator = "Block";
      $reflower = "Block";
      break;

    case "inline":
      $positioner = "Inline";
      if ( $frame->get_node()->nodeName == "#text" ) {
        $decorator = "Text";
        $reflower = "Text";
      } else {
        $decorator = "Inline";
        $reflower = "Inline";
      }
      break;   

    case "table":
      $positioner = "Block";
      $decorator = "Table";
      $reflower = "Table";
      break;
      
    case "inline-table":
      $positioner = "Inline";
      $decorator = "Table";
      $reflower = "Table";
      break;

    case "table-row-group":
    case "table-header-group":
    case "table-footer-group":
      $positioner = "Null";
      $decorator = "Table_Row_Group";
      $reflower = "Table_Row_Group";
      break;
      
    case "table-row":
      $positioner = "Null";
      $decorator = "Table_Row";
      $reflower = "Table_Row";
      break;

    case "table-cell":
      $positioner = "Table_Cell";
      $decorator = "Table_Cell";
      $reflower = "Table_Cell";
      break;
        
    case "list-item":
      $positioner = "Block";
      $decorator  = "Block";
      $reflower   = "Block";
      break;

    case "-dompdf-list-bullet":
      if ( $frame->get_style()->list_style_position == "inside" )
        $positioner = "Inline";
      else        
        $positioner = "List_Bullet";

      if ( $frame->get_style()->list_style_image != "none" )
        $decorator = "List_Bullet_Image";
      else
        $decorator = "List_Bullet";
      
      $reflower = "List_Bullet";
      break;

    case "-dompdf-image":
      $positioner = "Inline";
      $decorator = "Image";
      $reflower = "Image";
      break;
      
    case "-dompdf-br":
      $positioner = "Inline";
      $decorator = "Inline";
      $reflower = "Inline";
      break;

    default:
      // FIXME: should throw some sort of warning or something?
    case "none":
      $positioner = "Null";
      $decorator = "Null";
      $reflower = "Null";
      break;

    }

    $positioner .= "_Positioner";
    $decorator .= "_Frame_Decorator";
    $reflower .= "_Frame_Reflower";

    $deco = new $decorator($frame, $dompdf);
    $deco->set_positioner( new $positioner($deco) );
    $reflow = new $reflower($deco);
    
    // Generated content is a special case
    if ( $frame->get_node()->nodeName == "_dompdf_generated" ) {
      // Decorate the reflower
      $gen = new Generated_Frame_Reflower( $deco );
      $gen->set_reflower( $reflow );
      $reflow = $gen;
    }
    
    $deco->set_reflower( $reflow );

    // Images are a special case
//    if ( $frame->get_node()->nodeName == "img" ) {

//       // FIXME: This is a hack
//       $node =$frame->get_node()->ownerDocument->createElement("img_sub");
//       $node->setAttribute("src", $frame->get_node()->getAttribute("src"));
      
//       $img_frame = new Frame( $node );

//       $style = $frame->get_style()->get_stylesheet()->create_style();
//       $style->inherit($frame->get_style());
//       $img_frame->set_style( $style );

//       $img_deco = new Image_Frame_Decorator($img_frame, $dompdf);
//       $img_deco->set_reflower( new Image_Frame_Reflower($img_deco) );
//       $deco->append_child($img_deco);

//     }   
    
    return $deco;
  }
  
}
?>