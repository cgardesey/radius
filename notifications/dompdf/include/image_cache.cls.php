<?php





class Image_Cache {


  static protected $_cache = array();



  static function resolve_url($url, $proto, $host, $base_path) {
    global $_dompdf_warnings;

    
    $resolved_url = null;

    // Remove dynamic part of url to determine the file extension
    $tmp = preg_replace('/\?.*/','',$url);

    // We need to preserve the file extenstion
    $i = mb_strrpos($tmp, ".");
    if ( $i === false )
      throw new DOMPDF_Exception("Unknown image type: $url.");

    $ext = mb_strtolower(mb_substr($tmp, $i+1));

    $parsed_url = explode_url($url);

    $remote = ($proto != "" && $proto != "file://");
    $remote = $remote || ($parsed_url['protocol'] != "");

    if ( !DOMPDF_ENABLE_REMOTE && $remote ) {
      $resolved_url = DOMPDF_LIB_DIR . "/res/broken_image.png";
      $ext = "png";

    } else if ( DOMPDF_ENABLE_REMOTE && $remote ) {
      // Download remote files to a temporary directory
      $url = build_url($proto, $host, $base_path, $url);

      if ( isset(self::$_cache[$url]) ) {
        list($resolved_url,$ext) = self::$_cache[$url];
        //echo "Using cached image $url (" . $resolved_url . ")\n";

      } else {

        //echo "Downloading file $url to temporary location: ";
        $resolved_url = tempnam(DOMPDF_TEMP_DIR, "dompdf_img_");
        //echo $resolved_url . "\n";

        $old_err = set_error_handler("record_warnings");
        $image = file_get_contents($url);
        restore_error_handler();

        if ( strlen($image) == 0 ) {
          $image = file_get_contents(DOMPDF_LIB_DIR . "/res/broken_image.png");
          $ext = "png";
        }

        file_put_contents($resolved_url, $image);

        self::$_cache[$url] = array($resolved_url,$ext);

      }

    } else {

      $resolved_url = build_url($proto, $host, $base_path, $url);

      //echo $resolved_url . "\n";

    }

    if ( !is_readable($resolved_url) || !filesize($resolved_url) ) {
      $_dompdf_warnings[] = "File " .$resolved_url . " is not readable or is an empty file.\n";
      $resolved_url = DOMPDF_LIB_DIR . "/res/broken_image.png";
      $ext = "png";
    }

    // Assume for now that all dynamic images are pngs
    if ( $ext == "php" )
      $ext = "png";

    return array($resolved_url, $ext);

  }


  static function clear() {
    if ( count(self::$_cache) ) {
      foreach (self::$_cache as $entry) {
        list($file, $ext) = $entry;
        unlink($file);
      }
    }
  }

}
?>