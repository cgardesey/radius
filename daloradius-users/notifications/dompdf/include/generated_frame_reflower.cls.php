<?php





class Generated_Frame_Reflower extends Frame_Reflower {

  protected $_reflower; // Decoration target
  
  function __construct(Frame $frame) {
    parent::__construct($frame);
  }
  
  function set_reflower(Frame_Reflower $reflow) {
    $this->_reflower = $reflow;
  }

  //........................................................................

  protected function _parse_string($string) {
    $string = trim($string, "'\"");
    $string = str_replace(array("\\\n",'\\"',"\\'"),
                          array("",'"',"'"), $string);

    // Convert escaped hex characters into ascii characters (e.g. \A => newline)
    $string = preg_replace_callback("/\\\\([0-9a-fA-F]{0,6})(\s)?(?(2)|(?=[^0-9a-fA-F]))/",
                                    create_function('$matches',
                                                    'return chr(hexdec($matches[1]));'),
                                    $string);
    return $string;
  }
  
  protected function _parse_content() {
    $style = $this->_frame->get_style();
    
    // Matches generated content
    $re = "/\n".
      "\s(counters?\\([^)]*\\))|\n".
      "\A(counters?\\([^)]*\\))|\n".
      "\s([\"']) ( (?:[^\"']|\\\\[\"'])+ )(?<!\\\\)\\3|\n".
      "\A([\"']) ( (?:[^\"']|\\\\[\"'])+ )(?<!\\\\)\\5|\n" .
      "\s([^\s\"']+)|\n" .
      "\A([^\s\"']+)\n".
      "/xi";
    
    $content = $style->content;
    
    // split on spaces, except within quotes
    if (!preg_match_all($re, $content, $matches, PREG_SET_ORDER))
      return;
    
    $text = "";

    foreach ($matches as $match) {
      if ( isset($match[2]) && $match[2] !== "" )
        $match[1] = $match[1];
      
      if ( isset($match[6]) && $match[6] !== "" )
        $match[4] = $match[6];

      if ( isset($match[8]) && $match[8] !== "" )
        $match[7] = $match[8];
      
      if ( isset($match[1]) && $match[1] !== "" ) {
        // counters?(...)
        $match[1] = mb_strtolower(trim($match[1]));
                  
        // Handle counter() references:
        // http://www.w3.org/TR/CSS21/generate.html#content
        
        $i = mb_strpos($match[1], ")");
        if ( $i === false )
          continue;
        
        $args = explode(",", mb_substr($match[1], 7, $i - 7));
        $counter_id = $args[0];
        
        if ( $match[1]{7} == "(" ) {
          // counter(name [,style])
          
          if ( isset($args[1]) )
            $type = $args[1];
          else
            $type = null;
          
          
          $p = $this->_frame->find_block_parent();
          
          $text .= $p->counter_value($counter_id, $type);

        } else if ( $match[1]{7} == "s" ) {
          // counters(name, string [,style])
          if ( isset($args[1]) )
            $string = $this->_parse_string(trim($args[1]));
          else
            $string = "";
          
          if ( isset($args[2]) )
            $type = $args[2];
          else
            $type = null;
          
          $p = $this->_frame->find_block_parent();
          $tmp = "";
          while ($p) {
            $tmp = $p->counter_value($counter_id, $type) . $string . $tmp;
            $p = $p->find_block_parent();
          }
          $text .= $tmp;

        } else 
          // countertops?
          continue;
        
      } else if ( isset($match[4]) && $match[4] !== "" ) {
        // String match        
        $text .= $this->_parse_string($match[4]);

      } else if ( isset($match[7]) && $match[7] !== "" ) {
        // Directive match
        
        if ( $match[7] === "open-quote" ) {
          // FIXME: do something here
        } else if ( $match[7] === "close-quote" ) {
          // FIXME: do something else here
        } else if ( $match[7] === "no-open-quote" ) {
          // FIXME:
        } else if ( $match[7] === "no-close-quote" ) {
          // FIXME:
        } else if ( mb_strpos($match[7],"attr(") === 0 ) {

          $i = mb_strpos($match[7],")");
          if ( $i === false )
            continue;
          
          $attr = mb_substr($match[7], 6, $i - 6);
          if ( $attr == "" )
            continue;
          
          $text .= $this->_frame->get_node()->getAttribute($attr);
        } else
          continue;
        
      }      
    }

    return $text;
    
  }

  function reflow() {
    $style = $this->_frame->get_style();
    
    $text = $this->_parse_content();
    $t_node = $this->_frame->get_node()->ownerDocument->createTextNode($text);    
    $t_frame = new Frame($t_node);
    $t_style = $style->get_stylesheet()->create_style();
    $t_style->inherit($style);
    $t_frame->set_style($t_style);
    
    $this->_frame->prepend_child(Frame_Factory::decorate_frame($t_frame));
    $this->_reflower->reflow();
  }
}

?>