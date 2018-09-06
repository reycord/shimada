<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require 'vendor/autoload.php';
abstract class ArivalStatus
{
    const NOTCOMPLETE = 0;
    const COMPLETE = 1;
}

abstract class InspectionStatus
{
    const NOTCOMPLETE = 0;
    const COMPLETE = 1;
}

// format kvt array
function formatKVTArray($kvt){
    $overrideKVT = array();
    for ($i = 0; $i < sizeof($kvt); $i++){
        $order_date = $kvt[$i]['order_date'];
        $times = $kvt[$i]['times'];
        $kvt_no = $kvt[$i]['kvt_no'];
        $dvt_no = $kvt[$i]['dvt_no'];
        $flag = false; 
        foreach ($overrideKVT as $his){
            if($his['order_date'] == $order_date && $his['times'] == $times && $his['kvt_no'] == $kvt_no && $his['dvt_no'] == $dvt_no){
                $flag = true;
            }
        }
        if($flag){
            continue;
        }
        $temp = array( 'order_date' => $kvt[$i]['order_date'],
                        'dvt_no' => $kvt[$i]['dvt_no'],
                        'times' => $kvt[$i]['times'],
                        'kvt_no' => $kvt[$i]['kvt_no'],
                        'staff' => $kvt[$i]['staff'],
                        'staff_id' => $kvt[$i]['staff_id'],
                        'assistance' => $kvt[$i]['assistance'],
                        'factory' => $kvt[$i]['factory'],
                        'address' => $kvt[$i]['address'],
                        'stype_no' => $kvt[$i]['stype_no'],
                        'o_no' => $kvt[$i]['o_no'],
                        'contract_no' => $kvt[$i]['contract_no'],
                        'delivery_date' => $kvt[$i]['delivery_date'],
                        'delivery_method' => $kvt[$i]['delivery_method'],
                        'shipping_method' => isset($kvt[$i]['shipping_method']) ? $kvt[$i]['shipping_method'] : '',
                        'edit_date' => $kvt[$i]['edit_date'],
                        'dvt_status' => $kvt[$i]['dvt_status'],
                        'times_count' => (isset($kvt[$i]['times_count']) ? $kvt[$i]['times_count'] : 1),
                        'detail' => array(
                            array('item_code' => $kvt[$i]['item_code'],
                                  'item_name' => $kvt[$i]['item_name'],
                                  'item_jp_code' => $kvt[$i]['item_jp_code'],
                                  'detail_no' => isset($kvt[$i]['detail_no']) ? $kvt[$i]['detail_no'] : 0,
                                  'composition_3' => $kvt[$i]['composition_3'],
                                  'composition_2' => $kvt[$i]['composition_2'],
                                  'composition_1' => $kvt[$i]['composition_1'],
                                  'color' => $kvt[$i]['color'],
                                  'size' => $kvt[$i]['size'],
                                  'quantity' => $kvt[$i]['quantity'],
                                  'sell_price' => isset($kvt[$i]['sell_price']) ? $kvt[$i]['sell_price'] : '',
                                  'base_price' => isset($kvt[$i]['base_price']) ? $kvt[$i]['base_price'] : '',
                                  'shosha_price' => isset($kvt[$i]['shosha_price']) ? $kvt[$i]['shosha_price'] : '',
                                  'buy_price' => isset($kvt[$i]['buy_price']) ? $kvt[$i]['buy_price'] : '',
                                  'currency' => isset($kvt[$i]['currency']) ? $kvt[$i]['currency'] : '',
                                  'packing_date' => $kvt[$i]['packing_date'],
                                  'pv_no' => $kvt[$i]['pv_no'],
                                  'arrival_date' => $kvt[$i]['arrival_date'],
                                  'item_quantity' => $kvt[$i]['item_quantity'],
                                  'size_unit' => isset($kvt[$i]['size_unit']) ? $kvt[$i]['size_unit'] : '',
                                  'unit' => isset($kvt[$i]['unit']) ? $kvt[$i]['unit'] : '',
                                  'edit_date' => $kvt[$i]['edit_date'],
                                  'status' => $kvt[$i]['status'],
                                  'dvt_status' => $kvt[$i]['dvt_status'],
                                )
                        ),
                    );
        array_push($overrideKVT,$temp);
        for ($j = $i+1; $j < sizeof($kvt); $j++){
            if($kvt[$i]['order_date'] == $kvt[$j]['order_date'] && $kvt[$i]['times'] == $kvt[$j]['times'] && $kvt[$i]['dvt_no'] == $kvt[$j]['dvt_no'] && $kvt[$i]['kvt_no'] == $kvt[$j]['kvt_no']){
                $index = sizeof($overrideKVT) - 1;
                // $overrideFlg = false;
                // for ($k= 0; $k < sizeof($overrideKVT[$index]['detail']); $k++){
                //     if($overrideKVT[$index]['detail'][$k]['item_code'] == $kvt[$j]['item_code'] && $overrideKVT[$index]['detail'][$k]['color'] == $kvt[$j]['color'] && $overrideKVT[$index]['detail'][$k]['size'] == $kvt[$j]['size'] && $overrideKVT[$index]['detail'][$k]['pv_no'] == $kvt[$j]['pv_no']){
                //         $overrideFlg = true;
                //         $overrideKVT[$index]['detail'][$k]['arrival_date'] = $overrideKVT[$index]['detail'][$k]['arrival_date'] . (','.$kvt[$j]['arrival_date']);
                //         $overrideKVT[$index]['detail'][$k]['item_quantity'] = (int)$overrideKVT[$index]['detail'][$k]['item_quantity'] + (int)$kvt[$j]['item_quantity'];
                //     }
                // }
                // if($overrideFlg){
                //     continue;
                // }
                $detail = array('item_code' => $kvt[$j]['item_code'],
                                'item_name' => $kvt[$j]['item_name'],
                                'color' => $kvt[$j]['color'],
                                'size' => $kvt[$j]['size'],
                                'item_jp_code' => $kvt[$j]['item_jp_code'],
                                'detail_no' => isset($kvt[$j]['detail_no']) ? $kvt[$j]['detail_no'] : 0,
                                'composition_3' => $kvt[$j]['composition_3'],
                                'composition_2' => $kvt[$j]['composition_2'],
                                'composition_1' => $kvt[$j]['composition_1'],
                                'quantity' => $kvt[$j]['quantity'],
                                'sell_price' => isset($kvt[$j]['sell_price']) ? $kvt[$j]['sell_price'] : '',
                                'base_price' => isset($kvt[$j]['base_price']) ? $kvt[$j]['base_price'] : '',
                                'shosha_price' => isset($kvt[$j]['shosha_price']) ? $kvt[$j]['shosha_price'] : '',
                                'buy_price' => isset($kvt[$j]['buy_price']) ? $kvt[$j]['buy_price'] : '',
                                'currency' => isset($kvt[$j]['currency']) ? $kvt[$j]['currency'] : '',
                                'packing_date' => $kvt[$j]['packing_date'],
                                'pv_no' => $kvt[$j]['pv_no'],
                                'arrival_date' => $kvt[$j]['arrival_date'],
                                'item_quantity' => $kvt[$j]['item_quantity'],
                                'size_unit' => isset($kvt[$j]['size_unit']) ? $kvt[$j]['size_unit'] : '',
                                'unit' => isset($kvt[$j]['unit']) ? $kvt[$j]['unit'] : '',
                                'edit_date' => $kvt[$j]['edit_date'],
                                'status' => $kvt[$j]['status'],
                                'dvt_status' => $kvt[$j]['dvt_status'],
                            );
                array_push($overrideKVT[$index]['detail'],$detail);
            }
        }
    }
    return $overrideKVT;
}
function convert_number_to_words($number, $lang) {
    $number = str_replace(',','',$number);
    $hyphen      = ' ';
    $conjunction = ' ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    if($lang == 'vn'){
        $hyphen = ' ';
	    $conjunction = '  ';
	    $separator = ', ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = array(
            0 => 'không',
            1 => 'một',
            2 => 'hai',
            3 => 'ba',
            4 => 'bốn',
            5 => 'năm',
            6 => 'sáu',
            7 => 'bảy',
            8 => 'tám',
            9 => 'chín',
            10 => 'mười',
            11 => 'mười một',
            12 => 'mười hai',
            13 => 'mười ba',
            14 => 'mười bốn',
            15 => 'mười năm',
            16 => 'mười sáu',
            17 => 'mười bảy',
            18 => 'mười tám',
            19 => 'mười chín',
            20 => 'hai mươi',
            30 => 'ba mươi',
            40 => 'bốn mươi',
            50 => 'năm mươi',
            60 => 'sáu mươi',
            70 => 'bảy mươi',
            80 => 'tám mươi',
            90 => 'chín mươi',
            100 => 'trăm',
            1000 => 'ngàn',
            1000000 => 'triệu',
            1000000000 => 'tỷ',
            1000000000000 => 'nghìn tỷ',
            1000000000000000 => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
    }
    if (!is_numeric($number)) {
        return 0;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        return 0;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number), $lang);
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder, $lang);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits, $lang) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder, $lang);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction) && intval($fraction) !== 0) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return ($string);
}

function number_format_drop_zero_decimals($n, $n_decimals)
{
    return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
}
function escape_json($jsonString){
    $search  = array("\"", "'");
    $replace = array("\\\"", "&#39;");
    $jsonString = str_replace($search, $replace, $jsonString);
    return $jsonString;
}
function revert_json($jsonString){
    $replace  = array("\"", "'");
    $search = array("&quot;", "&#39;");
    $jsonString = str_replace($search, $replace, $jsonString);
    return $jsonString;
}
function getColStatusByLang($context){
    $language = $context->session->userdata("site_lang");//english, japanese, vietnamese
    //Default japanese
    $col_lang = "note2";
    switch($language){
        case "japanese":
            $col_lang = "komoku_name_2";
            break;
        case "vietnamese":
            $col_lang = "note1";
            break;
        case "english":
            $col_lang = "note2";
            break;
    }
    return $col_lang;
}

function encodeurl($val, $numencode=1){
    $val = trim($val);
    $val = $val==''?'{}':$val;
    for($i=0; $i<$numencode; $i++){
        $val = rawurlencode($val);
    }
    return $val;
}
function hashCode($str){
    $search  = array("/", " ");
    $replace = array("-", "-");
    $ret = str_replace($search, $replace, $str);
    return $ret;
}
function print_test($data){
    echo "<pre>";
    print_r($data);
    echo "<pre>";
    exit();
}
function parseMoney($money, $currency){
    $fractionDigits = 4;
    $outputCurrency = trim($currency);
    if ($outputCurrency == 'JPY') {
        $fractionDigits = 2;
    }
    $x = (float)($money);
    $num = explode(".", $x);
    if(isset($num[1])){
        $v = $num[1];
        if ($outputCurrency == 'VND') {
            $result = (float)($num[0]);
            if((float)(substr($v, 0, 1)) >= 5){
                return number_format(++$result);
            } else {
                return number_format($result);
            }
        }
        if (strlen($v) > $fractionDigits){
            $v = substr($v, 0, $fractionDigits);
            return (number_format($num[0]).'.'.$v);
        } else {
            $x = number_format($x, $fractionDigits);
        }
    } else {
        if($outputCurrency == 'VND') { 
            return number_format($x);
        } else {
            $x = number_format($x, $fractionDigits);
        }
    }
    return $x;
}
function makewords($numval){
    $moneystr = "";
    // handle the millions
    $milval = (integer)($numval / 1000000);
    if($milval > 0)  {
      $moneystr = getwords($milval) . " Million";
      }
     
    // handle the thousands
    $workval = $numval - ($milval * 1000000); // get rid of millions
    $thouval = (integer)($workval / 1000);
    if($thouval > 0)  {
      $workword = getwords($thouval);
      if ($moneystr == "")    {
        $moneystr = $workword . " Thousand";
        }else{
        $moneystr .= " " . $workword . " Thousand";
        }
      }
     
    // handle all the rest of the dollars
    $workval = $workval - ($thouval * 1000); // get rid of thousands
    $tensval = (integer)($workval);
    if ($moneystr == ""){
      if ($tensval > 0){
        $moneystr = getwords($tensval);
        }else{
        $moneystr = "Zero";
        }
      }else // non zero values in hundreds and up
      {
      $workword = getwords($tensval);
      $moneystr .= " " . $workword;
      }
     
    // plural or singular 'dollar'
    $workval = (integer)($numval);
    if ($workval == 1){
      $moneystr .= " Dollar And ";
      }else{
      $moneystr .= " Dollars And ";
      }
     
    // do the cents - use printf so that we get the
    // same rounding as printf
    $workstr = sprintf("%3.2f",$numval); // convert to a string
    $intstr = substr($workstr,strlen($workstr) - 2, 2);
    $workint = (integer)($intstr);
    if ($workint == 0){
      $moneystr .= "Zero";
      }else{
      $moneystr .= getwords($workint);
      }
    if ($workint == 1){
      $moneystr .= " Cent";
      }else{
      $moneystr .= " Cents";
      }
     
    // done 
    return $moneystr;
    }
     

    function getwords($workval){
        $numwords = array(
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen",
            20 => "Twenty",
            30 => "Thirty",
            40 => "Forty",
            50 => "Fifty",
            60 => "Sixty",
            70 => "Seventy",
            80 => "Eighty",
            90 => "Ninety"
        );
     
    // handle the 100's
    $retstr = "";
    $hundval = (integer)($workval / 100);
    if ($hundval > 0){
      $retstr = $numwords[$hundval] . " Hundred";
      }
     
    // handle units and teens
    $workstr = "";
    $tensval = $workval - ($hundval * 100); // dump the 100's
     
    // do the teens
    if (($tensval < 20) && ($tensval > 0)){
      $workstr = $numwords[$tensval];
       // got to break out the units and tens
      }else{
      $tempval = ((integer)($tensval / 10)) * 10; // dump the units
      $workstr = $numwords[$tempval]; // get the tens
      $unitval = $tensval - $tempval; // get the unit value
      if ($unitval > 0){
        $workstr .= " " . $numwords[$unitval];
        }
      }
     
    // join the parts together 
    if ($workstr != ""){
        if ($retstr != ""){
            $retstr .= " " . $workstr;
        }else{
            $retstr = $workstr;
        }
      }
    return $retstr;
}
?>
