<?php





class Frame_Tree {
    

  static protected $_HIDDEN_TAGS = array("area", "base", "basefont", "head", "style",
                                         "meta", "title", "colgroup",
                                         "noembed", "noscript", "param", "#comment");  

  protected $_dom;


  protected $_root;


  protected $_registry;
  


  function __construct(DomDocument $dom) {
    $this->_dom = $dom;
    $this->_root = null;
    $this->_registry = array();
  }


  function get_dom() { return $this->_dom; }


  function get_root() { return $this->_root; }


  function get_frame($id) { return isset($this->_registry[$id]) ? $this->_registry[$id] : null; }


  function get_frames() { return new FrameTreeList($this->_root); }
      

  function build_tree() {
    $html = $this->_dom->getElementsByTagName("html")->item(0);
    if ( is_null($html) )
      $html = $this->_dom->firstChild;

    if ( is_null($html) )
      throw new DOMPDF_Exception("Requested HTML document contains no data.");

    $this->_root = $this->_build_tree_r($html);

  }


  protected function _build_tree_r(DomNode $node) {
    
    $frame = new Frame($node);
    $id = $frame->get_id();
    $this->_registry[ $id ] = $frame;
    
    if ( !$node->hasChildNodes() )
      return $frame;

    // Fixes 'cannot access undefined property for object with
    // overloaded access', fix by Stefan radulian
    // <stefan.radulian@symbion.at>    
    //foreach ($node->childNodes as $child) {

    // Store the children in an array so that the tree can be modified
    $children = array();
    for ($i = 0; $i < $node->childNodes->length; $i++)
      $children[] = $node->childNodes->item($i);

    foreach ($children as $child) {
      // Skip non-displaying nodes
      if ( in_array( mb_strtolower($child->nodeName), self::$_HIDDEN_TAGS) )  {
        if ( mb_strtolower($child->nodeName) != "head" &&
             mb_strtolower($child->nodeName) != "style" ) 
          $child->parentNode->removeChild($child);
        continue;
      }

      // Skip empty text nodes
      if ( $child->nodeName == "#text" && $child->nodeValue == "" ) {
        $child->parentNode->removeChild($child);
        continue;
      }
      
      // Add a container frame for images
      if ( $child->nodeName == "img" ) {
        $img_node = $child->ownerDocument->createElement("img_inner");
     
        // Move attributes to inner node        
        foreach ( $child->attributes as $attr => $attr_node ) {
          // Skip style, but move all other attributes
          if ( $attr == "style" )
            continue;
       
          $img_node->setAttribute($attr, $attr_node->value);
        }

        foreach ( $child->attributes as $attr => $node ) {
          if ( $attr == "style" )
            continue;
          $child->removeAttribute($attr);
        }

        $child->appendChild($img_node);
      }
   
      $frame->append_child($this->_build_tree_r($child), false);

    }
    
    return $frame;
  }
}

?>