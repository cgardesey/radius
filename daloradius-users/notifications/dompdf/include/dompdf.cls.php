<?php





class DOMPDF {
  
  

  protected $_xml;


  protected $_tree;


  protected $_css;


  protected $_pdf;


  protected $_paper_size;


  protected $_paper_orientation;

  private $_cache_id;


  protected $_base_host;


  protected $_base_path;


  protected $_protocol;
  


  function __construct() {
    $this->_messages = array();
    $this->_xml = new DomDocument();
    $this->_xml->preserveWhiteSpace = true;
    $this->_tree = new Frame_Tree($this->_xml);
    $this->_css = new Stylesheet();
    $this->_pdf = null;
    $this->_paper_size = "letter";
    $this->_paper_orientation = "portrait";
    $this->_base_host = "";
    $this->_base_path = "";
    $this->_cache_id = null;
  }


  function get_tree() { return $this->_tree; }

  //........................................................................ 


  // FIXME: validate these
  function set_protocol($proto) { $this->_protocol = $proto; }


  function set_host($host) { $this->_base_host = $host; }


  function set_base_path($path) { $this->_base_path = $path; }


  function get_protocol() { return $this->_protocol; }


  function get_host() { return $this->_base_host; }


  function get_base_path() { return $this->_base_path; }
  

  function get_canvas() { return $this->_pdf; }
  
  //........................................................................ 


  function load_html_file($file) {
    // Store parsing warnings as messages (this is to prevent output to the
    // browser if the html is ugly and the dom extension complains,
    // preventing the pdf from being streamed.)
    list($this->_protocol, $this->_base_host, $this->_base_path) = explode_url($file);
    
    if ( !DOMPDF_ENABLE_REMOTE &&
         ($this->_protocol != "" && $this->_protocol != "file://" ) )
      throw new DOMPDF_Exception("Remote file requested, but DOMPDF_ENABLE_REMOTE is false.");
         
    if ( !DOMPDF_ENABLE_PHP ) {
      set_error_handler("record_warnings");
      $this->_xml->loadHTMLFile($file);
      restore_error_handler();

    } else
      $this->load_html(file_get_contents($file));

  }


  function load_html($str) {

    // Parse embedded php, first-pass
    if ( DOMPDF_ENABLE_PHP ) {
      ob_start();
      eval("?" . ">$str");
      $str = ob_get_contents();      
      ob_end_clean();
    }

    // Store parsing warnings as messages
    set_error_handler("record_warnings");
    $this->_xml->loadHTML($str);
    restore_error_handler();
  }


  protected function _process_html() {
    $this->_tree->build_tree();
    
    $this->_css->load_css_file(Stylesheet::DEFAULT_STYLESHEET);    

    // load <link rel="STYLESHEET" ... /> tags
    $links = $this->_xml->getElementsByTagName("link");    
    foreach ($links as $link) {
      if ( mb_strtolower($link->getAttribute("rel")) == "stylesheet" ||
           mb_strtolower($link->getAttribute("type")) == "text/css" ) {
        $url = $link->getAttribute("href");
        $url = build_url($this->_protocol, $this->_base_host, $this->_base_path, $url);
        
        $this->_css->load_css_file($url);
      }

    }

    // load <style> tags
    $styles = $this->_xml->getElementsByTagName("style");
    foreach ($styles as $style) {

      // Accept all <style> tags by default (note this is contrary to W3C
      // HTML 4.0 spec:
      // http://www.w3.org/TR/REC-html40/present/styles.html#adef-media
      // which states that the default media type is 'screen'
      if ( $style->hasAttributes() &&
           ($media = $style->getAttribute("media")) &&
           !in_array($media, Stylesheet::$ACCEPTED_MEDIA_TYPES) )
        continue;
      
      $css = "";
      if ( $style->hasChildNodes() ) {
        
        $child = $style->firstChild;
        while ( $child ) {
          $css .= $child->nodeValue; // Handle <style><!-- blah --></style>
          $child = $child->nextSibling;
        }
        
      } else
        $css = $style->nodeValue;

      // Set the base path of the Stylesheet to that of the file being processed
      $this->_css->set_protocol($this->_protocol);
      $this->_css->set_host($this->_base_host);
      $this->_css->set_base_path($this->_base_path);

      $this->_css->load_css($css);
    }
    
  }

  //........................................................................ 


  function set_paper($size, $orientation = "portrait") {
    $this->_paper_size = $size;
    $this->_paper_orientation = $orientation;
  }
  
  //........................................................................ 


  function enable_caching($cache_id) {
    $this->_cache_id = $cache_id;
  }
  
  //........................................................................ 


  function render() {

    //enable_mem_profile();
    
    $this->_process_html();

    $this->_css->apply_styles($this->_tree);

    $root = null;
    
    foreach ($this->_tree->get_frames() as $frame) {

      // Set up the root frame
      if ( is_null($root) ) {
        $root = Frame_Factory::decorate_root( $this->_tree->get_root(), $this );
        continue;
      }

      // Create the appropriate decorators, reflowers & positioners.
      $deco = Frame_Factory::decorate_frame($frame, $this);
      $deco->set_root($root);

      // FIXME: handle generated content
      if ( $frame->get_style()->display == "list-item" ) {
        
        // Insert a list-bullet frame
        $node = $this->_xml->createElement("bullet"); // arbitrary choice
        $b_f = new Frame($node);

        $style = $this->_css->create_style();
        $style->display = "-dompdf-list-bullet";
        $style->inherit($frame->get_style());
        $b_f->set_style($style);
        
        $deco->prepend_child( Frame_Factory::decorate_frame($b_f, $this) );
      }
    }
    
    $this->_pdf = Canvas_Factory::get_instance($this->_paper_size, $this->_paper_orientation);

    $root->set_containing_block(0, 0, $this->_pdf->get_width(), $this->_pdf->get_height());
    $root->set_renderer(new Renderer($this));
    
    // This is where the magic happens:
    $root->reflow();
    
    // Clean up cached images
    Image_Cache::clear();
  }
    
  //........................................................................ 


  function stream($filename, $options = null) {
    if (!is_null($this->_pdf))
      $this->_pdf->stream($filename, $options);
  }


  function output() {
    global $_dompdf_debug;
    if ( is_null($this->_pdf) )
      return null;
    
    return $this->_pdf->output( $_dompdf_debug );
  }
  
  //........................................................................ 
  
}
?>