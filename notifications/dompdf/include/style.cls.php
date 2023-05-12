<?php





class Style {


  static $default_font_size = 12;


  static $default_line_height = 1.2;


  static $INLINE_TYPES = array("inline");


  static $BLOCK_TYPES = array("block","inline-block", "table-cell", "list-item");


  static $TABLE_TYPES = array("table", "inline-table");


  static $BORDER_STYLES = array("none", "hidden", "dotted", "dashed", "solid",
                                "double", "groove", "ridge", "inset", "outset");


  static protected $_defaults = null;


  static protected $_inherited = null;


  protected $_stylesheet; // stylesheet this style is attached to


  protected $_props;


  protected $_prop_cache;
  

  protected $_parent_font_size; // Font size of parent element
  
  // private members

  private $__font_size_calculated; // Cache flag
  

  function __construct(Stylesheet $stylesheet) {
    $this->_props = array();
    $this->_stylesheet = $stylesheet;
    $this->_parent_font_size = null;
    $this->__font_size_calculated = false;
    
    if ( !isset(self::$_defaults) ) {
    
      // Shorthand
      $d =& self::$_defaults;
    
      // All CSS 2.1 properties, and their default values
      $d["azimuth"] = "center";
      $d["background_attachment"] = "scroll";
      $d["background_color"] = "transparent";
      $d["background_image"] = "none";
      $d["background_position"] = "0% 0%";
      $d["background_repeat"] = "repeat";
      $d["background"] = "";
      $d["border_collapse"] = "separate";
      $d["border_color"] = "";
      $d["border_spacing"] = "0";
      $d["border_style"] = "";
      $d["border_top"] = "";
      $d["border_right"] = "";
      $d["border_bottom"] = "";
      $d["border_left"] = "";
      $d["border_top_color"] = "";
      $d["border_right_color"] = "";
      $d["border_bottom_color"] = "";
      $d["border_left_color"] = "";
      $d["border_top_style"] = "none";
      $d["border_right_style"] = "none";
      $d["border_bottom_style"] = "none";
      $d["border_left_style"] = "none";
      $d["border_top_width"] = "medium";
      $d["border_right_width"] = "medium";
      $d["border_bottom_width"] = "medium";
      $d["border_left_width"] = "medium";
      $d["border_width"] = "medium";
      $d["border"] = "";
      $d["bottom"] = "auto";
      $d["caption_side"] = "top";
      $d["clear"] = "none";
      $d["clip"] = "auto";
      $d["color"] = "#000000";
      $d["content"] = "normal";
      $d["counter_increment"] = "none";
      $d["counter_reset"] = "none";
      $d["cue_after"] = "none";
      $d["cue_before"] = "none";
      $d["cue"] = "";
      $d["cursor"] = "auto";
      $d["direction"] = "ltr";
      $d["display"] = "inline";
      $d["elevation"] = "level";
      $d["empty_cells"] = "show";
      $d["float"] = "none";
      $d["font_family"] = "serif";
      $d["font_size"] = "medium";
      $d["font_style"] = "normal";
      $d["font_variant"] = "normal";
      $d["font_weight"] = "normal";
      $d["font"] = "";
      $d["height"] = "auto";
      $d["left"] = "auto";
      $d["letter_spacing"] = "normal";
      $d["line_height"] = "normal";
      $d["list_style_image"] = "none";
      $d["list_style_position"] = "outside";
      $d["list_style_type"] = "disc";
      $d["list_style"] = "";
      $d["margin_right"] = "0";
      $d["margin_left"] = "0";
      $d["margin_top"] = "0";
      $d["margin_bottom"] = "0";
      $d["margin"] = "";
      $d["max_height"] = "none";
      $d["max_width"] = "none";
      $d["min_height"] = "0";
      $d["min_width"] = "0";
      $d["orphans"] = "2";
      $d["outline_color"] = "invert";
      $d["outline_style"] = "none";
      $d["outline_width"] = "medium";
      $d["outline"] = "";
      $d["overflow"] = "visible";
      $d["padding_top"] = "0";
      $d["padding_right"] = "0";
      $d["padding_bottom"] = "0";
      $d["padding_left"] = "0";
      $d["padding"] = "";
      $d["page_break_after"] = "auto";
      $d["page_break_before"] = "auto";
      $d["page_break_inside"] = "auto";
      $d["pause_after"] = "0";
      $d["pause_before"] = "0";
      $d["pause"] = "";
      $d["pitch_range"] = "50";
      $d["pitch"] = "medium";
      $d["play_during"] = "auto";
      $d["position"] = "static";
      $d["quotes"] = "";
      $d["richness"] = "50";
      $d["right"] = "auto";
      $d["speak_header"] = "once";
      $d["speak_numeral"] = "continuous";
      $d["speak_punctuation"] = "none";
      $d["speak"] = "normal";
      $d["speech_rate"] = "medium";
      $d["stress"] = "50";
      $d["table_layout"] = "auto";
      $d["text_align"] = "left";
      $d["text_decoration"] = "none";
      $d["text_indent"] = "0";
      $d["text_transform"] = "none";
      $d["top"] = "auto";
      $d["unicode_bidi"] = "normal";
      $d["vertical_align"] = "baseline";
      $d["visibility"] = "visible";
      $d["voice_family"] = "";
      $d["volume"] = "medium";
      $d["white_space"] = "normal";
      $d["widows"] = "2";
      $d["width"] = "auto";
      $d["word_spacing"] = "normal";
      $d["z_index"] = "auto";

      // Properties that inherit by default
      self::$_inherited = array("azimuth",
                                 "border_collapse",
                                 "border_spacing",
                                 "caption_side",
                                 "color",
                                 "cursor",
                                 "direction",
                                 "elevation",
                                 "empty_cells",
                                 "font_family",
                                 "font_size",
                                 "font_style",
                                 "font_variant",
                                 "font_weight",
                                 "font",
                                 "letter_spacing",
                                 "line_height",
                                 "list_style_image",
                                 "list_style_position",
                                 "list_style_type",
                                 "list_style",
                                 "orphans",
                                 "page_break_inside",
                                 "pitch_range",
                                 "pitch",
                                 "quotes",
                                 "richness",
                                 "speak_header",
                                 "speak_numeral",
                                 "speak_punctuation",
                                 "speak",
                                 "speech_rate",
                                 "stress",
                                 "text_align",
                                 "text_indent",
                                 "text_transform",
                                 "visibility",
                                 "voice_family",
                                 "volume",
                                 "white_space",
                                 "widows",
                                 "word_spacing");
    }

  }


  function dispose() {
    unset($this->_stylesheet);
  }
  

  function get_stylesheet() { return $this->_stylesheet; }
  

  function length_in_pt($length, $ref_size = null) {

    if ( !is_array($length) )
      $length = array($length);

    if ( !isset($ref_size) )
      $ref_size = self::$default_font_size;

    $ret = 0;
    foreach ($length as $l) {

      if ( $l === "auto" ) 
        return "auto";
      
      if ( $l === "none" )
        return "none";

      // Assume numeric values are already in points
      if ( is_numeric($l) ) {
        $ret += $l;
        continue;
      }
        
      if ( $l === "normal" ) {
        $ret += $ref_size;
        continue;
      }

      // Border lengths
      if ( $l === "thin" ) {
        $ret += 0.5;
        continue;
      }
      
      if ( $l === "medium" ) {
        $ret += 1.5;
        continue;
      }
    
      if ( $l === "thick" ) {
        $ret += 2.5;
        continue;
      }
      
      if ( ($i = mb_strpos($l, "pt"))  !== false ) {
        $ret += mb_substr($l, 0, $i);
        continue;
      }

      if ( ($i = mb_strpos($l, "px"))  !== false ) {
        $ret += mb_substr($l, 0, $i);
        continue;
      }

      if ( ($i = mb_strpos($l, "em"))  !== false ) {
        $ret += mb_substr($l, 0, $i) * $this->__get("font_size");
        continue;
      }
      
      // FIXME: em:ex ratio?
      if ( ($i = mb_strpos($l, "ex"))  !== false ) {
        $ret += mb_substr($l, 0, $i) * $this->__get("font_size");
        continue;
      }
      
      if ( ($i = mb_strpos($l, "%"))  !== false ) {
        $ret += mb_substr($l, 0, $i)/100 * $ref_size;
        continue;
      }
      
      if ( ($i = mb_strpos($l, "in")) !== false ) {
        $ret += mb_substr($l, 0, $i) * 72;
        continue;
      }
          
      if ( ($i = mb_strpos($l, "cm")) !== false ) {
        $ret += mb_substr($l, 0, $i) * 72 / 2.54;
        continue;
      }

      if ( ($i = mb_strpos($l, "mm")) !== false ) {
        $ret += mb_substr($l, 0, $i) * 72 / 25.4;
        continue;
      }
          
      if ( ($i = mb_strpos($l, "pc")) !== false ) {
        $ret += mb_substr($l, 0, $i) / 12;
        continue;
      }
          
      // Bogus value
      $ret += $ref_size;
    }

    return $ret;
  }

  

  function inherit(Style $parent) {

    // Set parent font size
    $this->_parent_font_size = $parent->get_font_size();
    
    foreach (self::$_inherited as $prop) {
      if ( !isset($this->_props[$prop]) && isset($parent->_props[$prop]) ) 
        $this->_props[$prop] = $parent->_props[$prop];
    }
      
    foreach (array_keys($this->_props) as $prop) {
      if ( $this->_props[$prop] == "inherit" )
        $this->$prop = $parent->$prop;
    }
          
    return $this;
  }

  

  function merge(Style $style) {
    $this->_props = array_merge($this->_props, $style->_props);

    if ( isset($style->_props["font_size"]) )
      $this->__font_size_calculated = false;    
  }

  

  function munge_colour($colour) {
    if ( is_array($colour) )
      // Assume the array has the right format...
      // FIXME: should/could verify this.
      return $colour;
    
    $r = 0;
    $g = 0;
    $b = 0;

    // Handle colour names
    switch ($colour) {

    case "maroon":
      $r = 0x80;
      break;

    case "red":
      $r = 0xff;
      break;

    case "orange":
      $r = 0xff;
      $g = 0xa5;
      break;

    case "yellow":
      $r = 0xff;
      $g = 0xff;
      break;

    case "olive":
      $r = 0x80;
      $g = 0x80;
      break;

    case "purple":
      $r = 0x80;
      $b = 0x80;
      break;

    case "fuchsia":
      $r = 0xff;
      $b = 0xff;
      break;

    case "white":
      $r = $g = $b = 0xff;
      break;

    case "lime":
      $g = 0xff;
      break;

    case "green":
      $g = 0x80;
      break;

    case "navy":
      $b = 0x80;
      break;

    case "blue":
      $b = 0xff;
      break;

    case "aqua":
      $g = 0xff;
      $b = 0xff;
      break;

    case "teal":
      $g = 0x80;
      $b = 0x80;
      break;

    case "black":
      break;

    case "sliver":
      $r = $g = $b = 0xc0;
      break;

    case "gray":
    case "grey":
      $r = $g = $b = 0x80;
      break;

    case "transparent":
      return "transparent";
      
    default:

      if ( mb_strlen($colour) == 4 && $colour{0} == "#" ) {
        // #rgb format
        $r = hexdec($colour{1} . $colour{1});
        $g = hexdec($colour{2} . $colour{2});
        $b = hexdec($colour{3} . $colour{3});

      } else if ( mb_strlen($colour) == 7 && $colour{0} == "#" ) {
        // #rrggbb format
        $r = hexdec(mb_substr($colour, 1, 2));
        $g = hexdec(mb_substr($colour, 3, 2));
        $b = hexdec(mb_substr($colour, 5, 2));

      } else if ( mb_strpos($colour, "rgb") !== false ) {

        // rgb( r,g,b ) format
        $i = mb_strpos($colour, "(");
        $j = mb_strpos($colour, ")");
        
        // Bad colour value
        if ($i === false || $j === false)
          return null;

        $triplet = explode(",", mb_substr($colour, $i+1, $j-$i-1));

        if (count($triplet) != 3)
          return null;
        
        foreach (array_keys($triplet) as $c) {
          $triplet[$c] = trim($triplet[$c]);
          
          if ( $triplet[$c]{mb_strlen($triplet[$c]) - 1} == "%" ) 
            $triplet[$c] = round($triplet[$c] * 0.255);
        }

        list($r, $g, $b) = $triplet;

      } else {
        // Who knows?
        return null;
      }
      
      // Clip to 0 - 1
      $r = $r < 0 ? 0 : ($r > 255 ? 255 : $r);
      $g = $g < 0 ? 0 : ($g > 255 ? 255 : $g);
      $b = $b < 0 ? 0 : ($b > 255 ? 255 : $b);
      break;
      
    }
    
    // Form array
    $arr = array(0 => $r / 0xff, 1 => $g / 0xff, 2 => $b / 0xff,
                 "r"=>$r / 0xff, "g"=>$g / 0xff, "b"=>$b / 0xff,
                 "hex" => sprintf("#%02X%02X%02X", $r, $g, $b));
    return $arr;
      
  }

  

  function munge_color($color) { return $this->munge_colour($color); }  
  


  function __set($prop, $val) {
    global $_dompdf_warnings;

    $prop = str_replace("-", "_", $prop);
    $this->_prop_cache[$prop] = null;
    
    if ( !isset(self::$_defaults[$prop]) ) {
      $_dompdf_warnings[] = "'$prop' is not a valid CSS2 property.";
      return;
    }
    
    if ( $prop !== "content" && is_string($val) && mb_strpos($val, "url") === false ) {
      $val = mb_strtolower(trim(str_replace(array("\n", "\t"), array(" "), $val)));
      $val = preg_replace("/([0-9]+) (pt|px|pc|em|ex|in|cm|mm|%)/S", "\\1\\2", $val);
    }
    
    $method = "set_$prop";

    if ( method_exists($this, $method) )
      $this->$method($val);
    else 
      $this->_props[$prop] = $val;
    
  }


  function __get($prop) {
    
    if ( !isset(self::$_defaults[$prop]) ) 
      throw new DOMPDF_Exception("'$prop' is not a valid CSS2 property.");

    if ( isset($this->_prop_cache[$prop]) )
      return $this->_prop_cache[$prop];
    
    $method = "get_$prop";

    // Fall back on defaults if property is not set
    if ( !isset($this->_props[$prop]) )
      $this->_props[$prop] = self::$_defaults[$prop];

    if ( method_exists($this, $method) )
      return $this->_prop_cache[$prop] = $this->$method();


    return $this->_prop_cache[$prop] = $this->_props[$prop];
  }



  function get_font_family() {
    // Select the appropriate font.  First determine the subtype, then check
    // the specified font-families for a candidate.

    // Resolve font-weight
    $weight = $this->__get("font_weight");
    
    if ( is_numeric($weight) ) {

      if ( $weight < 700 )
        $weight = "normal";
      else
        $weight = "bold";

    } else if ( $weight == "bold" || $weight == "bolder" ) {
      $weight = "bold";

    } else {
      $weight = "normal";

    }

    // Resolve font-style
    $font_style = $this->__get("font_style");

    if ( $weight == "bold" && $font_style == "italic" )
      $subtype = "bold_italic";
    else if ( $weight == "bold" && $font_style != "italic" )
      $subtype = "bold";
    else if ( $weight != "bold" && $font_style == "italic" )
      $subtype = "italic";
    else
      $subtype = "normal";
    
    // Resolve the font family
    $families = explode(",", $this->_props["font_family"]);
    reset($families);
    $font = null;
    while ( current($families) ) {
      list(,$family) = each($families);
      $font = Font_Metrics::get_font($family, $subtype);

      if ( $font )
        return $font;
    }
    throw new DOMPDF_Exception("Unable to find a suitable font replacement for: '" . $this->_props["font_family"] ."'");
    
  }


  function get_font_size() {

    if ( $this->__font_size_calculated )
      return $this->_props["font_size"];
    
    if ( !isset($this->_props["font_size"]) )
      $fs = self::$_defaults["font_size"];
    else 
      $fs = $this->_props["font_size"];
    
    if ( !isset($this->_parent_font_size) )
      $this->_parent_font_size = self::$default_font_size;
    
    switch ($fs) {
      
    case "xx-small":
      $fs = 3/5 * $this->_parent_font_size;
      break;

    case "x-small":
      $fs = 3/4 * $this->_parent_font_size;
      break;

    case "smaller":
    case "small":
      $fs = 8/9 * $this->_parent_font_size;
      break;

    case "medium":
      $fs = $this->_parent_font_size;
      break;

    case "larger":
    case "large":
      $fs = 6/5 * $this->_parent_font_size;
      break;

    case "x-large":
      $fs = 3/2 * $this->_parent_font_size;
      break;

    case "xx-large":
      $fs = 2/1 * $this->_parent_font_size;
      break;

    default:
      break;
    }

    // Ensure relative sizes resolve to something
    if ( ($i = mb_strpos($fs, "em")) !== false ) 
      $fs = mb_substr($fs, 0, $i) * $this->_parent_font_size;

    else if ( ($i = mb_strpos($fs, "ex")) !== false ) 
      $fs = mb_substr($fs, 0, $i) * $this->_parent_font_size;

    else
      $fs = $this->length_in_pt($fs);

    $this->_props["font_size"] = $fs;    
    $this->__font_size_calculated = true;
    return $this->_props["font_size"];

  }


  function get_word_spacing() {
    if ( $this->_props["word_spacing"] === "normal" )
      return 0;

    return $this->_props["word_spacing"];
  }


  function get_line_height() {
    if ( $this->_props["line_height"] === "normal" )
      return self::$default_line_height * $this->get_font_size();

    if ( is_numeric($this->_props["line_height"]) ) 
      return $this->length_in_pt( $this->_props["line_height"] . "%", $this->get_font_size());
    
    return $this->length_in_pt( $this->_props["line_height"], $this->get_font_size() );
  }


  function get_color() {
    return $this->munge_color( $this->_props["color"] );
  }


  function get_background_color() {
    return $this->munge_color( $this->_props["background_color"] );
  }
  

  function get_background_position() {
    
    $tmp = explode(" ", $this->_props["background_position"]);

    switch ($tmp[0]) {

    case "left":
      $x = "0%";
      break;

    case "right":
      $x = "100%";
      break;

    case "top":
      $y = "0%";
      break;

    case "bottom":
      $y = "100%";
      break;

    case "center":
      $x = "50%";
      $y = "50%";
      break;

    default:
      $x = $tmp[0];
      break;
    }

    if ( isset($tmp[1]) ) {

      switch ($tmp[1]) {
      case "left":
        $x = "0%";
        break;
        
      case "right":
        $x = "100%";
        break;
        
      case "top":
        $y = "0%";
        break;
        
      case "bottom":
        $y = "100%";
        break;
        
      case "center":
        if ( $tmp[0] == "left" || $tmp[0] == "right" || $tmp[0] == "center" )
          $y = "50%";
        else
          $x = "50%";
        break;
        
      default:
        $y = $tmp[1];
        break;
      }

    } else {
      $y = "50%";
    }

    if ( !isset($x) )
      $x = "0%";

    if ( !isset($y) )
      $y = "0%";

    return array( 0 => $x, "x" => $x,
                  1 => $y, "y" => $y );
  }
           
        

  function get_border_top_color() {
    if ( $this->_props["border_top_color"] === "" )
      $this->_props["border_top_color"] = $this->__get("color");    
    return $this->munge_color($this->_props["border_top_color"]);
  }

  function get_border_right_color() {
    if ( $this->_props["border_right_color"] === "" )
      $this->_props["border_right_color"] = $this->__get("color");
    return $this->munge_color($this->_props["border_right_color"]);
  }

  function get_border_bottom_color() {
    if ( $this->_props["border_bottom_color"] === "" )
      $this->_props["border_bottom_color"] = $this->__get("color");
    return $this->munge_color($this->_props["border_bottom_color"]);;
  }

  function get_border_left_color() {
    if ( $this->_props["border_left_color"] === "" )
      $this->_props["border_left_color"] = $this->__get("color");
    return $this->munge_color($this->_props["border_left_color"]);
  }
  



  function get_border_top_width() {
    $style = $this->__get("border_top_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_top_width"]) : 0;
  }
  
  function get_border_right_width() {
    $style = $this->__get("border_right_style");    
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_right_width"]) : 0;
  }

  function get_border_bottom_width() {
    $style = $this->__get("border_bottom_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_bottom_width"]) : 0;
  }

  function get_border_left_width() {
    $style = $this->__get("border_left_style");
    return $style !== "none" && $style !== "hidden" ? $this->length_in_pt($this->_props["border_left_width"]) : 0;
  }



  function get_border_properties() {
    return array("top" => array("width" => $this->__get("border_top_width"),
                                "style" => $this->__get("border_top_style"),
                                "color" => $this->__get("border_top_color")),
                 "bottom" => array("width" => $this->__get("border_bottom_width"),
                                   "style" => $this->__get("border_bottom_style"),
                                   "color" => $this->__get("border_bottom_color")),
                 "right" => array("width" => $this->__get("border_right_width"),
                                  "style" => $this->__get("border_right_style"),
                                  "color" => $this->__get("border_right_color")),
                 "left" => array("width" => $this->__get("border_left_width"),
                                 "style" => $this->__get("border_left_style"),
                                 "color" => $this->__get("border_left_color")));
  }


  protected function _get_border($side) {
    $color = $this->__get("border_" . $side . "_color");
    
    return $this->__get("border_" . $side . "_width") . " " .
      $this->__get("border_" . $side . "_style") . " " . $color["hex"];
  }


  function get_border_top() { return $this->_get_border("top"); }
  function get_border_right() { return $this->_get_border("right"); }
  function get_border_bottom() { return $this->_get_border("bottom"); }
  function get_border_left() { return $this->_get_border("left"); }




  function get_border_spacing() {
    return explode(" ", $this->_props["border_spacing"]);
  }
  
  

  function set_color($colour) {
    $col = $this->munge_colour($colour);

    if ( is_null($col) )
      $col = self::$_defaults["color"];
    
    $this->_props["color"] = $col["hex"];
  }


  function set_background_color($colour) {
    $col = $this->munge_colour($colour);
    if ( is_null($col) )
      $col = self::$_defaults["background_color"];
    
    $this->_props["background_color"] = is_array($col) ? $col["hex"] : $col;
  }


  function set_background_image($val) {

    if ( mb_strpos($val, "url") !== false ) {
      $val = preg_replace("/url\(['\"]?([^'\")]+)['\"]?\)/","\\1", trim($val));
    } else {
      $val = "none";
    }

    // Resolve the url now in the context of the current stylesheet
    $parsed_url = explode_url($val);
    if ( $parsed_url["protocol"] == "" && $this->_stylesheet->get_protocol() == "" )
      $url = realpath($this->_stylesheet->get_base_path() . $parsed_url["file"]);
    else
      $url = build_url($this->_stylesheet->get_protocol(),
                       $this->_stylesheet->get_host(),
                       $this->_stylesheet->get_base_path(),
                       $val);                     
                     
    $this->_props["background_image"] = $url;
  }


  function set_font_size($size) {
    $this->__font_size_calculated = false;
    $this->_props["font_size"] = $size;
  }
  

  function set_page_break_before($break) {
    if ($break === "left" || $break === "right")
      $break = "always";

    $this->_props["page_break_before"] = $break;
  }

  function set_page_break_after($break) {
    if ($break === "left" || $break === "right")
      $break = "always";

    $this->_props["page_break_after"] = $break;
  }

    
  //........................................................................


  function set_margin_top($val) {
    $this->_props["margin_top"] = str_replace("none", "0px", $val);
  }

  function set_margin_right($val) {
    $this->_props["margin_right"] = str_replace("none", "0px", $val);
  }

  function set_margin_bottom($val) {
    $this->_props["margin_bottom"] = str_replace("none", "0px", $val);
  }

  function set_margin_left($val) {
    $this->_props["margin_left"] = str_replace("none", "0px", $val);
  }
  
  function set_margin($val) {
    $val = str_replace("none", "0px", $val);
    $margins = explode(" ", $val);

    switch (count($margins)) {

    case 1:
      $this->_props["margin_top"] = $margins[0];
      $this->_props["margin_right"] = $margins[0];
      $this->_props["margin_bottom"] = $margins[0];
      $this->_props["margin_left"] = $margins[0];
      break;

    case 2:
      $this->_props["margin_top"] = $margins[0];
      $this->_props["margin_bottom"] = $margins[0];

      $this->_props["margin_right"] = $margins[1];
      $this->_props["margin_left"] = $margins[1];
      break;
        
    case 3:
      $this->_props["margin_top"] = $margins[0];
      $this->_props["margin_right"] = $margins[1];
      $this->_props["margin_bottom"] = $margins[1];
      $this->_props["margin_left"] = $margins[2];
      break;

    case 4:
      $this->_props["margin_top"] = $margins[0];
      $this->_props["margin_right"] = $margins[1];
      $this->_props["margin_bottom"] = $margins[2];
      $this->_props["margin_left"] = $margins[3];
      break;

    default:
      break;
    }

    $this->_props["margin"] = $val;
    
  }

     


  function set_padding_top($val) {
    $this->_props["padding_top"] = str_replace("none", "0px", $val);
  }

  function set_padding_right($val) {
    $this->_props["padding_right"] = str_replace("none", "0px", $val);
  }

  function set_padding_bottom($val) {
    $this->_props["padding_bottom"] = str_replace("none", "0px", $val);
  }

  function set_padding_left($val) {
    $this->_props["padding_left"] = str_replace("none", "0px", $val);
  }

  function set_padding($val) {
    $val = str_replace("none", "0px", $val);
    $paddings = explode(" ", $val);

    switch (count($paddings)) {

    case 1:
      $this->_props["padding_top"] = $paddings[0];
      $this->_props["padding_right"] = $paddings[0];
      $this->_props["padding_bottom"] = $paddings[0];
      $this->_props["padding_left"] = $paddings[0];
      break;

    case 2:
      $this->_props["padding_top"] = $paddings[0];
      $this->_props["padding_bottom"] = $paddings[0];

      $this->_props["padding_right"] = $paddings[1];
      $this->_props["padding_left"] = $paddings[1];
      break;
        
    case 3:
      $this->_props["padding_top"] = $paddings[0];
      $this->_props["padding_right"] = $paddings[1];
      $this->_props["padding_bottom"] = $paddings[1];
      $this->_props["padding_left"] = $paddings[2];
      break;

    case 4:
      $this->_props["padding_top"] = $paddings[0];
      $this->_props["padding_right"] = $paddings[1];
      $this->_props["padding_bottom"] = $paddings[2];
      $this->_props["padding_left"] = $paddings[3];
      break;

    default:
      break;
    }

    $this->_props["padding"] = $val;
  }



  protected function _set_border($side, $border_spec) {
    $border_spec = str_replace(",", " ", $border_spec);
    $arr = explode(" ", $border_spec);

    // FIXME: handle partial values
    
    $p = "border_" . $side;
    $p_width = $p . "_width";
    $p_style = $p . "_style";
    $p_color = $p . "_color";

    foreach ($arr as $value) {
      $value = trim($value);
      if ( in_array($value, self::$BORDER_STYLES) ) {
        $this->_props[$p_style] = $value;

      } else if ( preg_match("/[.0-9]+(?:px|pt|pc|em|ex|%|in|mm|cm)|(?:none|normal|thin|medium|thick)/", $value ) ) {
        $this->_props[$p_width] = str_replace("none", "0px", $value);

      } else {
        // must be colour
        $this->_props[$p_color] = $value;
      }
    }

    $this->_props[$p] = $border_spec;
  }


  function set_border_top($val) { $this->_set_border("top", $val); }
  function set_border_right($val) { $this->_set_border("right", $val); }
  function set_border_bottom($val) { $this->_set_border("bottom", $val); }
  function set_border_left($val) { $this->_set_border("left", $val); }
  
  function set_border($val) {
    $this->_set_border("top", $val);
    $this->_set_border("right", $val);
    $this->_set_border("bottom", $val);
    $this->_set_border("left", $val);
    $this->_props["border"] = $val;
  }

  function set_border_width($val) {
    $arr = explode(" ", $val);

    switch (count($arr)) {

    case 1:
      $this->_props["border_top_width"] = $arr[0];
      $this->_props["border_right_width"] = $arr[0];
      $this->_props["border_bottom_width"] = $arr[0];
      $this->_props["border_left_width"] = $arr[0];
      break;

    case 2:
      $this->_props["border_top_width"] = $arr[0];
      $this->_props["border_bottom_width"] = $arr[0];

      $this->_props["border_right_width"] = $arr[1];
      $this->_props["border_left_width"] = $arr[1];
      break;
        
    case 3:
      $this->_props["border_top_width"] = $arr[0];
      $this->_props["border_right_width"] = $arr[1];
      $this->_props["border_bottom_width"] = $arr[1];
      $this->_props["border_left_width"] = $arr[2];
      break;

    case 4:
      $this->_props["border_top_width"] = $arr[0];
      $this->_props["border_right_width"] = $arr[1];
      $this->_props["border_bottom_width"] = $arr[2];
      $this->_props["border_left_width"] = $arr[3];
      break;

    default:
      break;
    }

    $this->_props["border_width"] = $val;
  }
  
  function set_border_color($val) {
    
    $arr = explode(" ", $val);
    
    switch (count($arr)) {

    case 1:
      $this->_props["border_top_color"] = $arr[0];
      $this->_props["border_right_color"] = $arr[0];
      $this->_props["border_bottom_color"] = $arr[0];
      $this->_props["border_left_color"] = $arr[0];
      break;

    case 2:
      $this->_props["border_top_color"] = $arr[0];
      $this->_props["border_bottom_color"] = $arr[0];

      $this->_props["border_right_color"] = $arr[1];
      $this->_props["border_left_color"] = $arr[1];
      break;
        
    case 3:
      $this->_props["border_top_color"] = $arr[0];
      $this->_props["border_right_color"] = $arr[1];
      $this->_props["border_bottom_color"] = $arr[1];
      $this->_props["border_left_color"] = $arr[2];
      break;

    case 4:
      $this->_props["border_top_color"] = $arr[0];
      $this->_props["border_right_color"] = $arr[1];
      $this->_props["border_bottom_color"] = $arr[2];
      $this->_props["border_left_color"] = $arr[3];
      break;

    default:
      break;
    }

    $this->_props["border_color"] = $val;

  }

  function set_border_style($val) {
    $arr = explode(" ", $val);

    switch (count($arr)) {

    case 1:
      $this->_props["border_top_style"] = $arr[0];
      $this->_props["border_right_style"] = $arr[0];
      $this->_props["border_bottom_style"] = $arr[0];
      $this->_props["border_left_style"] = $arr[0];
      break;

    case 2:
      $this->_props["border_top_style"] = $arr[0];
      $this->_props["border_bottom_style"] = $arr[0];

      $this->_props["border_right_style"] = $arr[1];
      $this->_props["border_left_style"] = $arr[1];
      break;
        
    case 3:
      $this->_props["border_top_style"] = $arr[0];
      $this->_props["border_right_style"] = $arr[1];
      $this->_props["border_bottom_style"] = $arr[1];
      $this->_props["border_left_style"] = $arr[2];
      break;

    case 4:
      $this->_props["border_top_style"] = $arr[0];
      $this->_props["border_right_style"] = $arr[1];
      $this->_props["border_bottom_style"] = $arr[2];
      $this->_props["border_left_style"] = $arr[3];
      break;

    default:
      break;
    }

    $this->_props["border_style"] = $val;
  }

     


  function set_border_spacing($val) {

    $arr = explode(" ", $val);

    if ( count($arr) == 1 )
      $arr[1] = $arr[0];

    $this->_props["border_spacing"] = $arr[0] . " " . $arr[1];
  }


  function set_list_style_image($val) {
    
    // Strip url(' ... ') from url values
    if ( mb_strpos($val, "url") !== false ) {
      $val = preg_replace("/url\(['\"]?([^'\")]+)['\"]?\)/","\\1", trim($val));
    } else {
      $val = "none";
    }

    $this->_props["list_style_image"] = $val;
  }
  

  function set_list_style($val) {
    $arr = explode(" ", str_replace(",", " ", $val));

    $types = array("disc", "circle", "square", "decimal",
                   "decimal-leading-zero", "lower-roman",
                   "upper-roman", "lower-greek", "lower-latin",
                   "upper-latin", "armenian", "georgian",
                   "lower-alpha", "upper-alpha", "none");

    $positions = array("inside", "outside");
    
    foreach ($arr as $value) {
      if ( mb_strpos($value, "url") !== false ) {
        $this->set_list_style_image($value);
        continue;
      }

      if ( in_array($value, $types) ) {
        $this->_props["list_style_type"] = $value;
      } else if ( in_array($value, $positions) ) {
        $this->_props["list_style_position"] = $value;
      }
    }
  }


  function __toString() {
    return print_r(array_merge(array("parent_font_size" => $this->_parent_font_size),
                               $this->_props), true);
  }
}
?>