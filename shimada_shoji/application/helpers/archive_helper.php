<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require 'vendor/autoload.php';

function archiveFile($zipFiles, $type="file"){
  /** Merge Excel and PDF */
  $zip = new ZipArchive();
  $path = sys_get_temp_dir();
  $filename = $path.'/export.zip';
  $zip->open($filename, ZipArchive::CREATE);
  foreach($zipFiles as $file){
    $zip->addFile($file['path'],$file['filename'] );
  }
  $zip->close();
  /** send file to browser */
  if($type=="download"){
    // send $filename to browser
    downloadFile($filename);
  }
}
function downloadFile($filename){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $filename);
    $size = filesize($filename);
    $name = basename($filename);
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        // cache settings for IE6 on HTTPS
        header('Cache-Control: max-age=120');
        header('Pragma: public');
    } else {
        header('Cache-Control: private, max-age=120, must-revalidate');
        header("Pragma: no-cache");
    }
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // long ago
    header("Content-Type: $mimeType");
    header('Content-Disposition: attachment; filename="' . $name . '";');
    header("Accept-Ranges: bytes");
    header('Content-Length: ' . filesize($filename));
    print readfile($filename);
    exit;
};
?>