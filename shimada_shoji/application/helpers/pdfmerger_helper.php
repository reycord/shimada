<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require 'vendor/autoload.php';
use Csv\PDFMerger\PDFMerger;

function mergerPdf($dataArray = array(),$type = "download"){
  $pdf = new PDFMerger;
  foreach($dataArray as $data){
    $pdf->addPDF($data, 'all', true);
  }

  if($type=="file"){
    $path = sys_get_temp_dir();
    return $pdf->merge('file', $path.'\export.pdf');
  }
  else{
    return $pdf->merge('download','export.pdf');
  }
}














?>