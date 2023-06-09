#!/usr/bin/php
<?php


require_once("dompdf_config.inc.php");


define("_TTF2AFM", TTF2AFM . " -a -GAef -OW ");

if ( !file_exists(TTF2AFM) ) {
  die("Unable to locate the ttf2afm / ttf2pt1 executable (checked " . TTF2AFM . ").\n");
}
  
  

function usage() {

  echo <<<EOD

Usage: {$_SERVER["argv"][0]} font_family n_file [b_file] [i_file] [bi_file]

font_family:      the name of the font, e.g. Verdana, 'Times New Roman', 
                  monospace, sans-serif.

n_file:           the .pfb or .ttf file for the normal, non-bold, non-italic
                  face of the font.

{b|i|bi}_file:    the files for each of the respective (bold, italic, 
                  bold-italic) faces.


If the optional b|i|bi files are not specified, load_font.php will search
the directory containing normal font file (n_file) for additional files that
it thinks might be the correct ones (e.g. that end in _Bold or b or B).  If
it finds the files they will also be processed.  All files will be
automatically copied to the DOMPDF font directory, and afm files will be
generated using ttf2afm.

Examples:

./load_font.php silkscreen /usr/share/fonts/truetype/slkscr.ttf
./load_font.php 'Times New Roman' /mnt/c_drive/WINDOWS/Fonts/times.ttf


EOD;

}

if ( $_SERVER["argc"] < 3 ) {
  usage();
  die();
}


function install_font_family($fontname, $normal, $bold = null, $italic = null, $bold_italic = null) {

  // Check if the base filename is readable
  if ( !is_readable($normal) )
    throw new DOMPDF_Exception("Unable to read '$normal'.");
    
  $dir = dirname($normal);
  list($file, $ext) = explode(".", basename($normal), 2);  // subtract extension
    
  // Try $file_Bold.$ext etc.
  $ext = ".$ext";

  if ( !isset($bold) || !is_readable($bold) ) {
    $bold   = $dir . "/" . $file . "_Bold" . $ext;
    if ( !is_readable($bold) ) {
 
      // Try $file . "b"
      $bold = $dir . "/" . $file . "b" . $ext;
      if ( !is_readable($bold) ) {
          
        // Try $file . "B"
        $bold = $dir . "/" . $file . "B" . $ext;
        if ( !is_readable($bold) ) 
          $bold = null;
      }
    }
  }

  if ( is_null($bold) )
    echo ("Unable to find bold face file.\n");
  
  if ( !isset($italic) || !is_readable($italic) ) {
    $italic = $dir . "/" . $file . "_Italic" . $ext;
    if ( !is_readable($italic) ) {

      // Try $file . "i"
      $italic = $dir . "/" . $file . "i" . $ext;
      if ( !is_readable($italic) ) {
          
        // Try $file . "I"
        $italic = $dir . "/" . $file . "I" . $ext;
        if ( !is_readable($italic) ) 
          $italic = null;
      }
    }
  }

  if ( is_null($italic) )
    echo ("Unable to find italic face file.\n");

  if ( !isset($bold_italic) || !is_readable($bold_italic) ) {
    $bold_italic = $dir . "/" . $file . "_Bold_Italic" . $ext;
      
    if ( !is_readable($bold_italic) ) {

      // Try $file . "bi"
      $bold_italic = $dir . "/" . $file . "bi" . $ext;
      if ( !is_readable($bold_italic) ) {
          
        // Try $file . "BI"
        $bold_italic = $dir . "/" . $file . "BI" . $ext;
        if ( !is_readable($bold_italic) ) {
            
          // Try $file . "ib"
          $bold_italic = $dir . "/" . $file . "ib" . $ext;
          if ( !is_readable($bold_italic) ) {
              
            // Try $file . "IB"
            $bold_italic = $dir . "/" . $file . "IB" . $ext;
            if ( !is_readable($bold_italic) )
              $bold_italic = null;
          }
        }
      }
    }
  }
 
  if ( is_null($bold_italic) )
    echo ("Unable to find bold italic face file.\n");
 
  $fonts = compact("normal", "bold", "italic", "bold_italic");
  $entry = array();
    
  if ( mb_strtolower($ext) === ".pfb" || mb_strtolower($ext) === ".ttf" ) {

    // Copy the files to the font directory.
    foreach ($fonts as $var => $src) {

      if ( is_null($src) ) {
        $entry[$var] = DOMPDF_FONT_DIR . basename($normal);
        continue;
      }
      
      // Verify that the fonts exist and are readable
      if ( !is_readable($src) ) 
        throw new User_DOMPDF_Exception("Requested font '$pathname' is not readable");
      
      $dest = DOMPDF_FONT_DIR . basename($src);
      if ( !is_writeable(dirname($dest)) )
        throw new User_DOMPDF_Exception("Unable to write to destination '$dest'.");
        
      echo "Copying $src to $dest...\n";

      if ( !copy($src, $dest) )
        throw new DOMPDF_Exception("Unable to copy '$src' to '" . DOMPDF_FONT_DIR . "$dest'.");

      $entry[$var] = $dest;
    }

  } else 
    throw new DOMPDF_Exception("Unable to process fonts of type '$ext'.");
    
    
  // If the extension is a ttf, try and convert the fonts to afm too
  if ( mb_strtolower($ext) === ".ttf") {
    foreach ($fonts as $var => $font) {
      if ( is_null($font) ) {
        $entry[$var] = DOMPDF_FONT_DIR . mb_substr(basename($normal), 0, -4);
        continue;
      }
      $dest = DOMPDF_FONT_DIR . mb_substr(basename($font),0, -4);
      echo "Generating .afm for $font...\n";
      exec( _TTF2AFM . " " . escapeshellarg($font) . " " . $dest . " &> /dev/null", $output, $ret );
      
      $entry[$var] = $dest;
    }

  }

  // FIXME: how to generate afms from pfb?
  
  // Store the fonts in the lookup table
  Font_Metrics::set_font_family(mb_strtolower($fontname), $entry);
    
  // Save the changes
  Font_Metrics::save_font_families();
}


$normal = $_SERVER["argv"][2];
$bold   = isset($_SERVER["argv"][3]) ? $_SERVER["argv"][3] : null;
$italic = isset($_SERVER["argv"][4]) ? $_SERVER["argv"][4] : null;
$bold_italic = isset($_SERVER["argv"][5]) ? $_SERVER["argv"][5] : null;

install_font_family($_SERVER["argv"][1], $normal, $bold, $italic, $bold_italic);

?>