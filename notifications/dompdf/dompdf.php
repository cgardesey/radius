<?php






function dompdf_usage() {
  echo
    "\nUsage: {$_SERVER["argv"][0]} [options] html_file\n\n".
    "html_file can be a filename, a url if fopen_wrappers are enabled, or the '-' \n".
    "character to read from standard input.\n\n".
    "Options:\n".
    " -h\t\tShow this message\n".
    " -l\t\tlist available paper sizes\n".
    " -p size\tpaper size; something like 'letter', 'A4', 'legal', etc.  The default is\n".
    "   \t\t'" . DOMPDF_DEFAULT_PAPER_SIZE . "'\n".
    " -o orientation\teither 'portrait' or 'landscape'.  Default is 'portrait'.\n".
    " -b path\tset the 'document root' of the html_file.  Relative urls (for \n".
    "        \tstylesheets) are resolved using this directory.  Default is the \n".
    "        \tdirectory of html_file.\n".
    " -f file\tthe output filename.  Default is the input [html_file].pdf.\n".
    " -v     \tverbose: display html parsing warnings and file not found errors.\n".
    " -d     \tvery verbose:  display oodles of debugging output: every frame\n".
    "        \tin the tree printed to stdout.\n\n";
  
}

function getoptions() {

  $opts = array();

  if ( $_SERVER["argc"] == 1 )
    return $opts;
  
  $i = 1;
  while ($i < $_SERVER["argc"]) {

    switch ($_SERVER["argv"][$i]) {

    case "--help":
    case "-h":
      $opts["h"] = true;
      $i++;
      break;

    case "-l":
      $opts["l"] = true;
      $i++;
      break;
      
    case "-p":
      if ( !isset($_SERVER["argv"][$i+1]) )
        die("-p switch requires a size parameter\n");
      $opts["p"] = $_SERVER["argv"][$i+1];
      $i += 2;
      break;

    case "-o":
      if ( !isset($_SERVER["argv"][$i+1]) )
        die("-o switch requires an orientation parameter\n");
      $opts["o"] = $_SERVER["argv"][$i+1];
      $i += 2;
      break;

    case "-b":
      if ( !isset($_SERVER["argv"][$i+1]) )
        die("-b switch requires an path parameter\n");
      $opts["b"] = $_SERVER["argv"][$i+1];
      $i += 2;
      break;

    case "-f":
      if ( !isset($_SERVER["argv"][$i+1]) )
        die("-f switch requires an filename parameter\n");
      $opts["f"] = $_SERVER["argv"][$i+1];
      $i += 2;
      break;
      
    case "-v":
      $opts["v"] = true;
      $i++;
      break;

    case "-d":
      $opts["d"] = true;
      $i++;
      break;

    default:
      $opts["filename"] = $_SERVER["argv"][$i];
      $i++;
      break;
    }
    
  }
  return $opts;  
}

require_once("dompdf_config.inc.php");
global $_dompdf_show_warnings;
global $_dompdf_debug;

$old_limit = ini_set("memory_limit", "80M");

$sapi = php_sapi_name();

switch ( $sapi ) {

 case "cli":
   
  $opts = getoptions();
 
  if ( isset($opts["h"]) || (!isset($opts["filename"]) && !isset($opts["l"])) ) {
    dompdf_usage();
    exit;
  }

  if ( isset($opts["l"]) ) {
    echo "\nUnderstood paper sizes:\n";
    
    foreach (array_keys(CPDF_Adapter::$PAPER_SIZES) as $size)
      echo "  " . mb_strtoupper($size) . "\n";
    exit;
  }
  $file = $opts["filename"];
  
  if ( isset($opts["p"]) )
    $paper = $opts["p"];
  else
    $paper = DOMPDF_DEFAULT_PAPER_SIZE;

  if ( isset($opts["o"]) )
    $orientation = $opts["o"];
  else
    $orientation = "portrait";

  if ( isset($opts["b"]) )
    $base_path = $opts["b"];

  if ( isset($opts["f"]) )
    $outfile = $opts["f"];
  else {
    if ( $file == "-" )
      $outfile = "dompdf_out.pdf";
    else
      $outfile = str_ireplace(array(".html", ".htm", ".php"), "", $file) . ".pdf";
  }

  if ( isset($opts["v"]) ) 
    $_dompdf_show_warnings = true;

  if ( isset($opts["d"]) ) {
    $_dompdf_show_warnings = true;
    $_dompdf_debug = true;
  }
  
  $save_file = true;
  
  break;

 default:

   if ( isset($_GET["input_file"]) )
     $file = rawurldecode($_GET["input_file"]);
   else
     throw new DOMPDF_Exception("An input file is required (i.e. input_file _GET variable).");
   
   if ( isset($_GET["paper"]) )
     $paper = rawurldecode($_GET["paper"]);
   else
     $paper = DOMPDF_DEFAULT_PAPER_SIZE;

   if ( isset($_GET["orientation"]) )
     $orientation = rawurldecode($_GET["orientation"]);
   else
     $orientation = "portrait";

   if ( isset($_GET["base_path"]) )
     $base_path = rawurldecode($_GET["base_path"]);

   if ( isset($_GET["output_file"]) )
     $outfile = rawurldecode($_GET["output_file"]);
   else
     $outfile = "dompdf_out.pdf";

   if ( isset($_GET["save_file"]) )
     $save_file = true;
   else
     $save_file = false;

   break;
}

$dompdf = new DOMPDF();

if ( $file == "-" ) {
  $str = "";
  while ( !feof(STDIN) )
    $str .= fread(STDIN, 4096);

  $dompdf->load_html($str);

} else 
  $dompdf->load_html_file($file);

if ( isset($base_path) ) {
  $dompdf->set_base_path($base_path);
}

$dompdf->set_paper($paper, $orientation);

$dompdf->render();

if ( $_dompdf_show_warnings ) {
  foreach ($_dompdf_warnings as $msg)
    echo $msg . "\n";
  flush();
}
     
if ( $save_file ) {
//   if ( !is_writable($outfile) ) 
//     throw new DOMPDF_Exception("'$outfile' is not writable.");
  if ( strtolower(DOMPDF_PDF_BACKEND) == "gd" ) 
    $outfile = str_replace(".pdf", ".png", $outfile);
    
  file_put_contents($outfile, $dompdf->output());
  exit(0);
}

if ( !headers_sent() ) {
  $dompdf->stream($outfile);
}
?>