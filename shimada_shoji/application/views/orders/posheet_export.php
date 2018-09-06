<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>PO出力</title>

  <style>
    body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      background-color: #fff;
      font-size: 10pt;
      font-family: 'Arial';
      /* font-family:'Dejavu Sans', Arial, Helvetica, sans-serif; */
    }
    html { 
      margin-top: 15mm;
      margin-left: 14mm;
    }

    * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6 {
      font-family: inherit;
      font-weight: 500;
      line-height: 1.1;
      color: inherit;
    }
    h1 small,
    h2 small,
    h3 small,
    h4 small,
    h5 small,
    h6 small,
    .h1 small,
    .h2 small,
    .h3 small,
    .h4 small,
    .h5 small,
    .h6 small,
    h1 .small,
    h2 .small,
    h3 .small,
    h4 .small,
    h5 .small,
    h6 .small,
    .h1 .small,
    .h2 .small,
    .h3 .small,
    .h4 .small,
    .h5 .small,
    .h6 .small {
      font-weight: normal;
      line-height: 1;
      color: black;
    }
    h1,
    .h1,
    h2,
    .h2,
    h3,
    .h3 {
      margin-top: 20px;
      margin-bottom: 10px;
    }
    h1 small,
    .h1 small,
    h2 small,
    .h2 small,
    h3 small,
    .h3 small,
    h1 .small,
    .h1 .small,
    h2 .small,
    .h2 .small,
    h3 .small,
    .h3 .small {
      font-size: 65%;
    }
    h4,
    .h4,
    h5,
    .h5,
    h6,
    .h6 {
      margin-top: 10px;
      margin-bottom: 10px;
    }
    h4 small,
    .h4 small,
    h5 small,
    .h5 small,
    h6 small,
    .h6 small,
    h4 .small,
    .h4 .small,
    h5 .small,
    .h5 .small,
    h6 .small,
    .h6 .small {
      font-size: 75%;
    }
    h1,
    .h1 {
      font-size: 36px;
    }
    h2,
    .h2 {
      font-size: 30px;
    }
    h3,
    .h3 {
      font-size: 24px;
    }
    h4,
    .h4 {
      font-size: 18px;
    }
    h5,
    .h5 {
      font-size: 14px;
    }
    h6,
    .h6 {
      font-size: 12px;
    }
    p {
      margin: 0 0 10px;
    }

    .row {
      margin-right: -15px;
      margin-left: -15px;
    }
    .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
      float: left;
    }

    .col-12 {
      width: 100%;
    }
    .col-11 {
      width: 91.66666667%;
    }
    .col-10 {
      width: 83.33333333%;
    }
    .col-9 {
      width: 75%;
    }
    .col-8 {
      width: 66.66666667%;
    }
    .col-7 {
      width: 58.33333333%;
    }
    .col-6 {
      width: 50%;
    }
    .col-5 {
      width: 41.66666667%;
    }
    .col-4 {
      width: 33.33333333%;
    }
    .col-3 {
      width: 25%;
    }
    .col-2 {
      width: 16.66666667%;
    }
    .col-1 {
      width: 8.33333333%;
    }
    .col-offset-12 {
      margin-left: 100%;
    }
    .col-offset-11 {
      margin-left: 91.66666667%;
    }
    .col-offset-10 {
      margin-left: 83.33333333%;
    }
    .col-offset-9 {
      margin-left: 75%;
    }
    .col-offset-8 {
      margin-left: 66.66666667%;
    }
    .col-offset-7 {
      margin-left: 58.33333333%;
    }
    .col-offset-6 {
      margin-left: 50%;
    }
    .col-offset-5 {
      margin-left: 41.66666667%;
    }
    .col-offset-4 {
      margin-left: 33.33333333%;
    }
    .col-offset-3 {
      margin-left: 25%;
    }
    .col-offset-2 {
      margin-left: 16.66666667%;
    }
    .col-offset-1 {
      margin-left: 8.33333333%;
    }
    .col-offset-0 {
      margin-left: 0;
    }

    .row:before,
    .row:after {
      display: table;
      content: " ";
    }

    .row:after {
      clear: both;
    }

    table {
      border-spacing: 0;
      border-collapse: collapse;
      background-color: transparent;
    }
    caption {
      padding-top: 8px;
      padding-bottom: 8px;
      color: black;
      text-align: left;
    }
    th {
      text-align: left;
    }
    .table {
      width: 100%;
      max-width: 100%;
      margin-bottom: 20px;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 3px;
      line-height: 1.42857143;
      vertical-align: top;
      border-top: 1px solid black;
      font-size: 10pt !important;
    }
    .table > thead > tr > th {
      vertical-align: bottom;
      border-bottom: 2px solid black;
    }
    .table > caption + thead > tr:first-child > th,
    .table > colgroup + thead > tr:first-child > th,
    .table > thead:first-child > tr:first-child > th,
    .table > caption + thead > tr:first-child > td,
    .table > colgroup + thead > tr:first-child > td,
    .table > thead:first-child > tr:first-child > td {
      border-top: 0;
    }
    .table > tbody + tbody {
      border-top: 2px solid black;
    }
    .table .table {
      background-color: #fff;
    }
    .table-condensed > thead > tr > th,
    .table-condensed > tbody > tr > th,
    .table-condensed > tfoot > tr > th,
    .table-condensed > thead > tr > td,
    .table-condensed > tbody > tr > td,
    .table-condensed > tfoot > tr > td {
      padding: 5px;
    }
    .table-bordered {
      border: 1px solid black;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > td {
      border-left: 1px solid black;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > thead > tr > td {
      border-bottom-width: 2px;
    }

    tr.no-top-bordered > td {
      border-top: 0px solid black !important;
    }
    .no-top-border {
      border-top: 0px solid black !important;
    }
    .text-bottom{
      vertical-align:bottom !important;
    }
    .text-content {
      color: black;
    }

    .text-left {
      text-align: left;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .text-justify {
      text-align: justify;
    }
    .text-nowrap {
      white-space: nowrap;
    }
    .text-lowercase {
      text-transform: lowercase;
    }
    .text-uppercase {
      text-transform: uppercase;
    }
    .text-capitalize {
      text-transform: capitalize;
    }

    .text-middle {
      vertical-align: middle !important;
    }

    .page {
      width: 185mm;
      min-height: 297mm;
      margin: auto;
      background: #fff;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      /* page-break-after: always; */
    }

    .address-company-name {
      font-size: 11pt;
    }

    .address {
      font-size: 8pt;
      color: #000;
      max-width: 200px;
      margin: 0;
    }

    th {
      text-align: center;
      vertical-align: middle !important;
    }

    td {
      vertical-align: text-top
    }

    th,
    td {
      line-height: 14pt
    }

    @page {
      /* size: A4; */
      margin: 1.5cm 1.5cm 1.5cm 2cm;
    }

    @media print {
      html,
      body {
        width: 185mm;
        height: 297mm;
        font-family:'Dejavu Sans', Arial, Helvetica, sans-serif;
      }
      .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        /* page-break-after: always; */
        page-break-inside: auto;
      }
    }
    .content{
      padding-left:40px;
    }
    body {
      color:black;
    }

    .txt-black {
      color: black;
    }
  </style>
</head>
<body>
<?php $outCurrency = isset($orderInfo[0]['currency']) ? $orderInfo[0]['currency'] : "USD";?>
  <div class="book">
    <div class="page">
      <div class="row">
        <div class="col-8 text-content" style="width: 100%">
          <!-- <span class="address-company-name">SHIMADA SHOJI (VIETNAM) CO.,LTD</span><br> -->
          <?php //$companyAddress = explode(PHP_EOL, $params['branch']['note2']);?>
          <?php $companyAddress = preg_split("/\r\n|\r|\n/", $params['branch']['note2']);?>
          <?php foreach($companyAddress as $id => $comAddress):?>
          <?php if($id == 0):?>
          <span class="address-company-name"><b><?php echo ($comAddress); ?></b></span><br>
          <?php else:?>
          <span class="address"><?php echo ($comAddress); ?></span><br>
          <?php endif?>
          <?php endforeach?>
            <!-- <br> <?php echo ($params['branch']['komoku_name_3']); ?>
            <br> <?php echo ($params['branch']['note1']); ?>
            <span><?php echo ($params['branch']['note2']); ?></span> -->
          <!-- </span> -->
        </div>
        <div class="col-4"></div>
      </div>
      <div class="row">
        <div class="col-4 col-offset-8 text-center" style="margin-top: -30px">
          DATE: <span class="text-content"><?php echo($orderInfo[0]['order_date']); ?></span><br>
          <?php echo($orderInfo[0]['revise_date'] != '' ? ('Revice Day : '.$orderInfo[0]['revise_date'].'<br>') : ''); ?></span>
        </div>
      </div>
      <div class="row" style="margin: 20px 0px 10px 0px">
        <div class="col-12">
          <h3 class="text-center" style="margin-top: 0px; margin-bottom: 0px; font-size: 20px;">
            <u><b>PURCHASE ORDER</b></u>
          </h3>
        </div>
      </div>
      <div class="row">
        <div class="col-4 col-offset-8  text-left" style="margin-bottom: -60px; font-size: 10pt;">
          PO No: <span class="text-content text-left"><?php echo($orderInfo[0]['order_no_1'].'-'.$orderInfo[0]['order_no_2'].'-'.$orderInfo[0]['order_no_3'].'-'.str_pad($orderInfo[0]['order_no_4'],4,'0', STR_PAD_LEFT).($params['branch_code'] == PO_VAT_KUBUN ? '' :'(HN)').'/'.$orderInfo[0]['order_no_5']); ?></span>
          <br />
          <span class="text-content text-left">
          <?php echo(trim($orderInfo[0]['reference']) != '' ? ('Reference: '.$orderInfo[0]['reference'].'<br>') : ''); ?></span>
          <p class="text-content text-center">
          <?php
            $itemType = array();
            echo ('(');
            for ($i=0; $i < sizeof($orderInfo); $i++){
              $res = in_array($orderInfo[$i]['item_type'], $itemType);
              if(!$res){
                if($i == 0) {
                  echo ($orderInfo[$i]['item_type']);
                } else{
                  echo ('+'.$orderInfo[$i]['item_type']); 
                }
              }
              array_push($itemType, $orderInfo[$i]['item_type']);
          } echo (')');?></p>
        </div>
      </div>
      <div class="row">
        <div class="col-8">
          <table>
            <tr>
              <td style="padding-right: 0px; vertical-align: text-top;">Seller:</td>
              <td class="text-content">
                <?php 
                  $companyAddress = preg_split("/\r\n|\r|\n/", $orderInfo[0]['address']);
                  $companyAddress = array_map(function($item){
                    if(preg_match("/tel:.*/i", trim($item), $matches)){
                      if(preg_match("/\d+/", trim($item), $matches)){
                        return $item;
                      }
                    }else{
                      return $item;
                    }
                    },$companyAddress);
                ?>
                <?php foreach($companyAddress as $id => $comAddress):?>
                <?php if($id == 0):?>
                <div style="width: 90%">
                <span class="address-company-name"><b><?php echo ($comAddress); ?></b></span><br>
                </div>
                <div>
                <?php else:?>
                <span class="address" style="font-size:10pt"><?php echo ($comAddress); ?></span><br>
                <?php endif?>
                <?php endforeach?>
                <div>
              </td>
            </tr>
          </table>
        </div>
      </div><br>
      <b style="font-size: 12pt">Commodity and Quality:</b><br><br>
      <div class="row">
        <div style="margin: 0 5mm 0 10mm;">
          <table class="table table-bordered">
            <tr>
              <th style="width: 20px">No</th>
              <th>Article</th>
              <th style="width: 50px">Col No.</th>
              <th style="width: 50px">Size
              <br/><?php  echo ($orderInfo[0]['size_unit'] ? '('.$orderInfo[0]['size_unit'].')' : '' );?></th>
              <th style="width: 85px">Quantity
              <br/><?php  echo ($orderInfo[0]['unit'] ? '('.$orderInfo[0]['unit'].')':'');?></th>
              <th style="width: 85px">Unit Price
                <br/><?php  echo ($outCurrency ? '('.$outCurrency.')' : '');?></th>
              <th style="width: 120px">Amount
                <br/>(<?php echo $outCurrency;?>)</th>
            </tr>
            <?php $itemCode = array();?>
            <?php $sumTotalAmount = 0;?>
            <?php $totalQuantity = array();?>
            <?php $itemIndex = 0;?>
          <?php for ($i = 0; $i < sizeof($orderInfo); $i++): ?>
          <?php $itemQuantity = 0;?>
          <?php $totalAmount = 0;?>
            <?php 
              if(in_array($orderInfo[$i]['item_code'], $itemCode)){
                continue;
              }
              $itemIndex++;
              array_push( $itemCode, $orderInfo[$i]['item_code']);
            ?>
            <tr>
              <td class="text-center text-middle"><?php echo ($itemIndex); ?></td>
              <td class="text-content text-left"><?php echo $orderInfo[$i]['item_name'];?>
                 <?php if(isset($orderInfo[$i]['composition_1']) && $orderInfo[$i]['composition_1'] != ''){ 
                  echo ('<br>'.$orderInfo[$i]['composition_1']);
                }?>
                 <?php if(isset($orderInfo[$i]['composition_2']) && $orderInfo[$i]['composition_2'] != ''){ 
                  echo ('<br>'.$orderInfo[$i]['composition_2']);
                }?>
                 <?php if(isset($orderInfo[$i]['composition_3']) && $orderInfo[$i]['composition_3'] != ''){ 
                  echo ('<br>'.$orderInfo[$i]['composition_3']);
                }?>
                <?php if(isset($orderInfo[$i]['surcharge_color']) /*|| isset($orderInfo[$i]['surcharge_unit_color']) || isset($orderInfo[$i]['default_surcharge_po'])*/):?><br> SURCHARGE <?php endif ?>
              </td>
              <td class="text-center text-content text-bottom">
                <?php if(isset($orderInfo[$i]['color']) && $orderInfo[$i]['color'] != ''){ 
                  echo ($orderInfo[$i]['color']);
                }?>
                <br>&nbsp;
              </td>
              <td class="text-center text-content text-bottom">
              <?php echo ($orderInfo[$i]['size']);?>
              <br>&nbsp;
              </td>
              <td class="text-right text-content text-bottom">
              <?php echo number_format($orderInfo[$i]['odr_quantity']);?> <?php echo ($orderInfo[$i]['unit']);?>
              <br>&nbsp;
              <?php $itemQuantity += (int)$orderInfo[$i]['odr_quantity'];?>
              <?php 
                if(array_key_exists($orderInfo[$i]['unit'], $totalQuantity )){
                  $totalQuantity[$orderInfo[$i]['unit']] += (int)$orderInfo[$i]['odr_quantity'];
                } else{
                  $totalQuantity[$orderInfo[$i]['unit']] = (int)$orderInfo[$i]['odr_quantity'];
                } 
              ?>
              </td>
              <td class="text-right text-content text-bottom">
              <?php $tempPriceUnit =  ($orderInfo[$i]['price'] + (isset($orderInfo[$i]['surcharge_unit_color']) ? $orderInfo[$i]['surcharge_unit_color'] : 0));?>
              <?php echo (parseMoney($tempPriceUnit, $outCurrency));?>
              <br>&nbsp; <?php //echo (isset($orderInfo[$i]['surcharge_unit_color']) ? '('.$orderInfo[$i]['surcharge_unit_color'].')' : '')?>
              </td>
              <td class="text-right text-content text-bottom">
              <?php $orderInfo[$i]['amount'] += ($orderInfo[$i]['odr_quantity'] * (isset($orderInfo[$i]['surcharge_unit_color']) ? $orderInfo[$i]['surcharge_unit_color'] : 0) );?>
              <?php echo (parseMoney($orderInfo[$i]['amount'], $outCurrency));?>
              <br> &nbsp; <?php echo isset($orderInfo[$i]['surcharge_color']) ? parseMoney($orderInfo[$i]['surcharge_color'], $outCurrency) : '';?>&nbsp;
              <?php $totalAmount += (float) isset($orderInfo[$i]['surcharge_color']) ? $orderInfo[$i]['surcharge_color']: 0;?>
              <?php $totalAmount += (float)$orderInfo[$i]['amount'];?>
              </td>
            </tr>
            <?php for ($j = $i+1; $j < sizeof($orderInfo); $j++): ?>
             <?php if($orderInfo[$i]['item_code'] == $orderInfo[$j]['item_code']) :?>
             <?php if($orderInfo[$i]['color'] == $orderInfo[$j]['color']) :?>
              <tr class="no-top-bordered">
                <td  class="no-top-border"></td>
                <td class="text-content">
                <?php if(isset($orderInfo[$j]['surcharge_color']) /*|| isset($orderInfo[$j]['surcharge_unit_color']) || isset($orderInfo[$j]['default_surcharge_po'])*/):?><br> SURCHARGE <?php endif ?>
                </td>
                <td class="text-center text-content"><?php echo ($orderInfo[$j]['color']);?></td>
                <td class="text-center text-content">
                <?php echo ($orderInfo[$j]['size']);?> 
                </td>
                <td class="text-right text-content">
                <?php echo number_format($orderInfo[$j]['odr_quantity']);?> <?php echo ($orderInfo[$j]['unit']);?>
                <?php $itemQuantity += (int)$orderInfo[$j]['odr_quantity'];?>
                <?php 
                  if(array_key_exists($orderInfo[$j]['unit'], $totalQuantity )){
                    $totalQuantity[$orderInfo[$j]['unit']] += (int)$orderInfo[$j]['odr_quantity'];
                  } else{
                    $totalQuantity[$orderInfo[$j]['unit']] = (int)$orderInfo[$j]['odr_quantity'];
                  } 
                ?>
                </td>
                <td class="text-right text-content">
                <?php $tempPriceUnit = ($orderInfo[$j]['price']+ (isset($orderInfo[$j]['surcharge_unit_color']) ? $orderInfo[$j]['surcharge_unit_color'] : 0));?>
                <?php echo parseMoney($tempPriceUnit, $outCurrency);?>
                <br>&nbsp; <?php // echo (isset($orderInfo[$i]['surcharge_unit_color']) ? '('.$orderInfo[$i]['surcharge_unit_color'].')' : '')?>
                </td>
                <td class="text-right text-content text-bottom">
                <?php $orderInfo[$j]['amount'] += ($orderInfo[$j]['odr_quantity'] * (isset($orderInfo[$j]['surcharge_unit_color']) ? $orderInfo[$j]['surcharge_unit_color'] : 0) );?>
                <?php echo (parseMoney($orderInfo[$j]['amount'], $outCurrency));?>
                <br> &nbsp;<?php echo isset($orderInfo[$j]['surcharge_color']) ? parseMoney($orderInfo[$j]['surcharge_color'], $outCurrency) : '';?>&nbsp;
                <?php $totalAmount += (float) isset($orderInfo[$j]['surcharge_color']) ? $orderInfo[$j]['surcharge_color']: 0;?>
                <?php $totalAmount += (float)$orderInfo[$j]['amount'];?>
                </td>
              </tr>
                <?php else: ?>
              <tr>
                <td class="no-top-border"></td>
                <td class="text-content">
                <?php if(isset($orderInfo[$j]['surcharge_color']) /*|| isset($orderInfo[$j]['surcharge_unit_color']) || isset($orderInfo[$j]['default_surcharge_po'])*/):?><br> SURCHARGE <?php endif ?></td>
                <td class="text-center text-content"><?php echo ($orderInfo[$j]['color']);?></td>
                <td class="text-center text-content">
                <?php echo ($orderInfo[$j]['size']);?> 
                </td>
                <td class="text-right text-content">
                <?php echo number_format($orderInfo[$j]['odr_quantity']);?> <?php echo ($orderInfo[$j]['unit']);?>
                <?php $itemQuantity += (int)$orderInfo[$j]['odr_quantity'];?>
                <?php 
                  if(array_key_exists($orderInfo[$j]['unit'], $totalQuantity )){
                    $totalQuantity[$orderInfo[$j]['unit']] += (int)$orderInfo[$j]['odr_quantity'];
                  } else{
                    $totalQuantity[$orderInfo[$j]['unit']] = (int)$orderInfo[$j]['odr_quantity'];
                  } 
                ?>
                </td>
                <td class="text-right text-content">
                <?php $tempPriceUnit = ($orderInfo[$j]['price'] + (isset($orderInfo[$j]['surcharge_unit_color']) ? $orderInfo[$j]['surcharge_unit_color'] : 0));?>
                <?php echo parseMoney($tempPriceUnit, $outCurrency);?>
                <br>&nbsp; <?php //echo (isset($orderInfo[$i]['surcharge_unit_color']) ? '('.$orderInfo[$i]['surcharge_unit_color'].')' : '')?>
                </td>
                <td class="text-right text-content text-bottom">
                <?php $orderInfo[$j]['amount'] += ($orderInfo[$j]['odr_quantity'] * (isset($orderInfo[$j]['surcharge_unit_color']) ? $orderInfo[$j]['surcharge_unit_color'] : 0) );?>
                <?php echo (parseMoney($orderInfo[$j]['amount'], $outCurrency));?>
                <br> &nbsp;<?php echo isset($orderInfo[$j]['surcharge_color']) ? parseMoney($orderInfo[$j]['surcharge_color'], $outCurrency) : '';?>&nbsp;
                <?php $totalAmount += (float) isset($orderInfo[$j]['surcharge_color']) ? $orderInfo[$j]['surcharge_color']: 0;?>
                <?php $totalAmount += (float)$orderInfo[$j]['amount'];?>
                </td>
              </tr>
              <?php endif?>
            <?php endif?>
            <?php endfor?>
            <tr style="font-weight: bold;">
              <td class="no-top-border"></td>
              <td class="text-content">Total
              <td></td>
              <td></td>
              <td class="text-right text-content"><?php echo number_format($itemQuantity);?> <?php echo $orderInfo[$i]['unit'];?></td>
              <td class="text-right text-content"></td>
              <td class="text-right text-content text-bottom"><?php echo (parseMoney($totalAmount, $outCurrency));?></td>
              <?php $sumTotalAmount += $totalAmount;?>
            </tr>
            <?php if(isset($orderInfo[$i]['default_surcharge_po']) && $orderInfo[$i]['default_surcharge_po'] != null && $orderInfo[$i]['default_surcharge_po'] != ''):?>
            <?php
              if(isset($orderInfo[$i]['surcharge_po']) && $orderInfo[$i]['surcharge_po'] != null && $orderInfo[$i]['surcharge_po'] != ''){
                $orderInfo[$i]['default_surcharge_po'] = $orderInfo[$i]['surcharge_po'];
              }
              // $regexString = str_replace(',','',$orderInfo[$i]['default_surcharge_po']);
              // preg_match('#\$?[\d,]+\d+\s?(\$|VND|JPY)?#i', $regexString, $matches, PREG_OFFSET_CAPTURE);
              // $tmp = array();
              // $tmp[0][0] = 0;
              // if(is_numeric(trim($matches[0][0])) === false){
              //   preg_match_all('!\d+!', $matches[0][0], $tmp);
              // }
              
            ?>
            <?php //$sumTotalAmount += (float)$tmp[0][0];?>
            <tr>
              <td class="no-top-border"></td>
              <td><?php echo $orderInfo[$i]['default_surcharge_po'];?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <?php endif?>
          <?php endfor?>
              <tr style="font-weight: bold;">
                <td></td>
                <td class="text-content">TOTAL: (VAT <?php echo ($params['branch_code'] == PO_VAT_KUBUN ? '0' : '10');?>%) </td>
                <td></td>
                <td></td>
                <td class="text-right text-content">
                <?php foreach($totalQuantity as $key => $value):?>
                <?php echo(number_format($value).' '.$key.'<br>'); ?>
                <?php endforeach?>
                </td>
                <td></td>
                <td class="text-right text-content text-bottom">
                <?php if ($params['branch_code'] != PO_VAT_KUBUN) {$sumTotalAmount += ($sumTotalAmount*0.1);} ?>
                <?php echo (parseMoney($sumTotalAmount, $outCurrency)); ?>
                </td>
              </tr>
          </table>
        </div>
      </div>
      <table>
        <tr>
          <td style="width: 40mm">SHIPMENT:</td>
          <td class="text-content"><?php echo ($params['transportation']); ?></td>
        </tr>
        <tr>
          <td style="width: 40mm">SHIPPER:</td>
          <td class="text-content"><?php echo($params['shipper']); ?></td>
        </tr>
        <tr>
          <td>PV NO:</td>
          <td class="text-content"><?php echo ($params['pv_no']); ?></td>
        </tr>
        <tr>
          <td>FREIGHT:</td>
          <td class="text-content"><?php echo ($params['freight']); ?></td>
        </tr>
        <tr>
          <td>HOPE DELIVERY TIME:</td>
          <td class="text-content"><?php echo ($orderInfo[0]['delivery_plan_date']); ?></td>
        </tr>
        <tr>
          <td>PAYMENT TERM:</td>
          <td class="text-content"><?php echo ($params['payment_term']); ?></td>
        </tr>
        <tr>
          <td>INSURANCE:</td>
          <td class="text-content"><?php echo ($params['insurance']); ?></td>
        </tr>
        <tr>
          <td>DESTINATION:</td>
          <?php if ($params['branch_code'] == PO_VAT_KUBUN): ?>
          <td class="text-content">Binh Duong Province, Viet Nam</td>
          <?php else:?>
          <td class="text-content">HANOI, Viet Nam</td>
          <?php endif?>
        </tr>
        <tr>
          <td>SHIPPING MARK:</td>
          <td class="text-content"><?php echo ($orderInfo[0]['shipping_mark']); ?></td>
        </tr>
        <tr>
          <td>CONSIGNEE:</td>
          <td class="text-content">SHIMADA SHOJI (VIETNAM) CO.,LTD
            <br> No.28 VSIP, STREET 3, VIETNAM-SINGAPORE INDUSTRIAL PARK, 
            <br> BINH HOA WARD, THUAN AN TOWN, BINH DUONG PROVINCE,VIETNAM 
            <br> TEL: 84-274-3-768-987 FAX: 84-274-3-768-987</td>
        </tr>
        <tr>
          <td>NOTIFY PARTY:</td>
          <td class="text-content">SHIMADA SHOJI (VIETNAM) CO.,LTD
            <br> No.28 VSIP, STREET 3, VIETNAM-SINGAPORE INDUSTRIAL PARK, 
            <br> BINH HOA WARD, THUAN AN TOWN, BINH DUONG PROVINCE,VIETNAM 
            <br> TEL: 84-274-3-768-987 FAX: 84-274-3-768-987</td>
        </tr>
        <?php if($params['note'] == '1'):?>
        <tr>
          <td>NOTE:</td>
          <?php if($params['note_detail'] == '0'): ?>
          <td class="text-content">&bull; SELLER agrees to use scanned signature on customs clearance documents as the content of above Principle Contract.</td>
            <?php else:?>
          <td class="text-content">&bull; Please ensure your original invoice has the required  information as below: 
            <br> <b>SHIMADA SHOJI (VIETNAM) CO., LTD</b>
            <br> No.28 VSIP, Street 3, Vietnam- Singapore Industrial Park, 
            <br> Binh Hoa Ward, Thuan An Town, Binh Duong Province, Viet Nam.</td>
            <?php endif?>
        </tr>
        <?php endif?>
      </table>
      <br>
      <div class="row">
        <div class="col-6">
          (SELLER)
          <br> <span class="text-content"><?php echo ($orderInfo[0]['supplier_name']); ?></span>
        </div>
        <div class="col-6">
          (BUYER)
          <br> <span class="text-content">SHIMADA SHOJI (VIETNAM) CO.,LTD</span>
        </div>
      </div>
      <div class="row" style="margin-top: 80px">
        <div class="col-6">
          <div  style="border-bottom: solid 1px black; margin-right: 10%;"></div>
        </div>
        <div class="col-6">
           <div style="border-bottom: solid 1px black; margin-right: 10%"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
        </div>
        <div class="col-6">
          <p><?php echo ($orderInfo[0]['last_name'].' '.$orderInfo[0]['first_name']); ?></p>
        </div>
        </div>
      </div>
    </div>
</body>
</html>
