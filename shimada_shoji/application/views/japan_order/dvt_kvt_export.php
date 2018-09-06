<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
      background-color: #FAFAFA;
      /* font: 9pt "Tahoma"; */
      font-family: 'sun-extA';
      font-size: 9pt;
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
      color: #777;
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
      color: #777;
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
      padding: 8px;
      line-height: 1.42857143;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }
    .table > thead > tr > th {
      vertical-align: bottom;
      border-bottom: 2px solid #ddd;
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
      border-top: 2px solid #ddd;
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
      border: 1px solid #ddd;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > td {
      border-left: 1px solid #ddd;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > thead > tr > td {
      border-bottom-width: 2px;
    }

    tr.no-top-bordered > td {
      border-top: 0px solid #ddd !important;
    }

    .text-content {
      color: rgb(0, 112, 192);
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
      width: 210mm;
      min-height: 297mm;
      padding: 5mm;
      margin: 5mm auto;
      background: white;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      page-break-after: always;
    }

    .address-company-name {
      font: 11pt "Tahoma";
    }

    .address {
      font-size: 9pt;
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

    /* @page {
      size: 'A4';
      margin: 0;
    } */

    @media print {
      html,
      body {
        width: 210mm;
        height: 297mm;
        /* font-family:sans-serif; */
      }
      .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
      }
    }
    .content{
      padding-left:40px;
    }
    body {
      color: black;
    }

    .txt-black {
      color: black;
    }

  </style>
</head>

<body>
  <div class="book">
    <div class="page">

      <div class="row" style="margin-top: 1mm">
        <div class="col-8">
          <p class="address"><span class="txt-black">TO:</span><span> VIETNAM</span></p>
        </div>
        <div class="col-4 col-offset-8 text-right" style="margin-top:-30px;">
          <span><?php echo (date('H:i:s')) ?></span>
          <br/><span><?php echo ($dvtInfo['order_date']);?> P.1</span>
        </div>
      </div>

      <div class="row">
        <div class="col-4">
          <h4 class="text-left">
            <b><?php echo($dvtInfo['dvt_no']);?></b>
          </h4>
        </div>
        <div class="col-4">
          <h2 class="text-left">
            <b class="txt-black">出荷指図書</b>
          </h2>
        </div>
        <div class="col-12 text-right" style="margin-top:-50px;">
          <h4>島田商事株式会社</h4>
          <h4 style="margin-right:100px;">G5U1</h4>
        </div>
      </div>

      <div class="row">
        <div class="col-3">
          <span class="txt-black">出荷日:</span> <?php echo ($dvtInfo['delivery_require_date']);?>
        </div>
        <div class="col-6 col-offset-2 text-center">
          <span class="txt-black">担当者:</span> <?php echo ($dvtInfo['staff']);?><?php echo ($dvtInfo['staff_id'] != '' ? ('('.$dvtInfo['staff_id'].')') : '');?> <?php echo ($dvtInfo['assistance'] != '' ? ('/'.$dvtInfo['assistance']) : '');?>
        </div>
      </div>

      <hr style="border-top: dashed 1px;" >

      <div class="content">
        <div class="row">
          <div class="col-12">
            <p><span class="txt-black">製品契約者１　:</span> <?php echo ($dvtInfo['factory']);?> </p>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <p><span class="txt-black">製品契約者２　:</span>  chua biet</p>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <p><span class="txt-black">配送方法　:</span> chua biet　<?php //echo ($dvtInfo['komoku_name_2'] != '' ? ($dvtInfo['komoku_name_2']) : '');?></p>
          </div>
          <div class="col-3">
            <p><span class="txt-black">B/L :</span> chua biet　</p>
          </div>
          <div class="col-3">
            <p><span class="txt-black">運賃区分 :</span> chua biet</p>
          </div>
        </div>
      </div>

      <hr style="border-top: dashed 1px;">

      <div class="row">
        <div style="margin: 0 12mm;">
          <table class="table table-bordered" style="border: 2px dashed">
            <tbody>
              <tr style="border: 2px dashed">
                <td class="text-left" style="border: 2px dashed">
                  <b><span class="txt-black">出荷先 <?php echo ($dvtInfo['factory']);?></span><br/>
                    <span style="padding-left:26px;">
                    　　 <?php echo ($dvtInfo['address']);?><br/><br/><br/>
                    </span>
                  </b>
                </td>
              </tr>
              <tr>
                <td style="height:100px;" class="txt-black">
                    備考
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <p></p>

      <div class="row">
        <div class="col-12 txt-black">
          (7013030)
        </div>
      </div>

      <hr style="border-top: dashed 1px;">

      <div class="row txt-black">
        <div style="margin: 0 12mm;">
          <div class="col-3">
            梱包指図NO
          </div>
          <div class="col-3">
            契約NO
          </div>
          <div class="col-3">
            品番
          </div>
        </div>
      </div>

      <hr>
      <?php $index = 1;?>
    <?php foreach ($kvtList as $kvt):?>
      <div class="row">
        <div style="margin: 0 12mm;">
          <div class="col-3">
            <p><?php echo ($index++)?>) <?php echo ($kvt['kvt_no'])?></p>
          </div>
          <div class="col-3">
            <p><?php echo ($kvt['contract_no'])?></p>
          </div>
          <div class="col-3">
            <p><?php echo ($kvt['stype_no'])?></p>
          </div>

        </div>
      </div>
      <?php endforeach?>
      <hr>

      <div class="row">
        <div style="margin: 0 12mm;">
          <div class="col-3 txt-black">
            <p>●引き取りPO情報</p>
          </div>
          <div class="col-6">
            <p><?php echo ($dvtInfo['pv_infor']);?></p>
          </div>
        </div>
      </div>
    </div>
    <?php foreach ($kvtList as $kvt):?>
    <div class="page">
      <div class="row" style="margin-top: 1mm">
        <div class="col-8">
          <p class="address"><span class="txt-black">TO:</span><span> VIETNAM</span></p>
        </div>
        <div class="col-4 col-offset-8 text-right" style="margin-top:-30px;">
          <span><?php echo (date('H:s:i'));?></span>
          <br/><span> <?php echo ($dvtInfo['order_date']);?> P.1</span>
        </div>
      </div>
      <div class="row">
        <div class="col-4">
          <h4 class="text-left">
           &nbsp;<b><?php echo ($kvt['kvt_no']);?></b><br>
          (<?php echo ($dvtInfo['dvt_no']);?>)
          </h4>
        </div>
        <div class="col-4">
          <h2 class="text-left">
            <b class="txt-black">梱包指図書</b>
          </h2>
        </div>
        <div class="col-12 text-right" style="margin-top:-50px;">
          <h4>島田商事株式会社</h4>
          <h4 style="margin-right:100px;">G5U1</h4>
        </div>
      </div>

      <div class="row">
        <div class="col-3">
          <span class="txt-black">出荷日:</span> <?php echo ($kvt['delivery_date']);?>
        </div>
        <div class="col-4 text-center">
          <span class="txt-black">担当者:</span> <?php echo ($dvtInfo['staff']);?>　<?php echo ($dvtInfo['staff_id'] != '' ? '('.$dvtInfo['staff_id'].')' : '');?>
        </div>
        <div class="col-3 text-center">
          <span class="txt-black">アシスタント:</span> <?php echo ($dvtInfo['assistance']);?>
        </div>
      </div>

      <hr>

      <div class="content">
        <div class="row txt-black">
          <div style="margin: 0 12mm;">
            <div class="col-3">
              7025056 (chua biet)
            </div>
            <div class="col-3">
              契約NO : <?php echo ($kvt['contract_no']);?>
            </div>
            <div class="col-3">
              品番 : <?php echo ($kvt['stype_no']);?>
            </div>
          </div>
        </div>
      </div>

      <hr>

      <div class="row">
        <div style="margin: 0 12mm;">
          <table class="table table-bordered" style="padding: 10px; border: 2px dashed">
            <tr style="border: 2px dashed">
              <td class="text-left" style="width: 65%; border: 2px dashed">
                <b><span class="txt-black">出荷先</span><br/>
                  <span style="padding-left:26px;">
                  　　  <?php echo ($kvt['factory']);?><br><br>
                  　　 <?php echo ($kvt['address']);?><br/><br/><br/>
                  </span>
                </b>
              </td>
              <td class="text-left" style="width: 35%; border: 2px dashed" rowspan="2">
                <span><span class="txt-black">CASE MARK</span><br/>
                  <?php echo ($kvt['contract_no']);?><br/>
                  <?php echo ($kvt['stype_no']);?><br/>
                  <?php echo ($kvt['o_no']);?>
                  </span>
                </span>
              </td>
            </tr>
            <tr>
              <td style="height:100px;" class="txt-black">
                  備考
              </td>
            </tr>
          </table>
        </div>
      </div>
    <?php
        $thead = str_repeat("",95);
        $thead .= "\n";
        $thead .= "|".str_repeat(" ",4).str_pad("品名",45).str_pad('梱包数量',45)."|";
        $thead .= "\n";
        $thead .= "|".str_repeat("-",93)."|";
    ?>

      <div class="row">
        <div class="col-12 txt-black">
          (DETAILS)
        </div>
        <div class="col-12">
            <pre style="font-family: sjis; font-size:15px"><?php echo $thead ?></pre>
        </div>
        
      </div>
        <table class="table table-bordered" style="border: 2px dashed">
            <tbody>
              <tr style=" border:2px dashed">
                <td class="text-left" style="padding: 5px; border: 2px dashed">
                 &nbsp;&nbsp;&nbsp;&nbsp;品名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;梱包数量
                </td>
              </tr>
              <?php $detailIndex = 1?>
              <?php $pageIndex = 1?>
              <?php $totalItem = array()?>
              <?php foreach ($kvt['detail'] as $item):?>
                <?php if(array_key_exists($item['unit'], $totalItem )){
                  $totalItem[$item['unit']] += $item['quantity'];
                } else{
                  $totalItem[$item['unit']] = $item['quantity'];
                }
                ?>
              <?php if(($detailIndex % 8 == 0 && $pageIndex == 1) || ( ($detailIndex - 7) != 0 && ($detailIndex - 7) % 14 == 0 && $pageIndex == 2)):?>
              <?php $pageIndex++;?>
                    </tbody>
              </table><br><br>
              <!-- </div>
                <div class="page"> -->
                  <div class="row" style="margin-top: 1mm">
                    <div class="col-8">
                      <p class="address"><span class="txt-black">&nbsp;<?php echo ($kvt['kvt_no']);?><br>
          (<?php echo ($dvtInfo['dvt_no']);?>)</span></p>
                    </div>
                    <div class="col-4 col-offset-8 text-right" style="margin-top:-30px;">
                      <span> <?php echo ($dvtInfo['order_date']);?> P.<?php echo $pageIndex; ?></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 txt-black">
                      (DETAILS)
                    </div>
                  </div>
                    <table class="table table-bordered" style="border: 2px dashed">
                        <tbody>
                          <tr style=" border:2px dashed">
                            <td class="text-left" style="padding: 5px; border: 2px dashed">
                            &nbsp;&nbsp;&nbsp;&nbsp;品名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;梱包数量
                            </td>
                          </tr>
              <?php else :?>

              <tr style="border: 2px dashed">
                <td class="text-left" style="padding: 5px; border: 2px dashed">
                  <?php echo ($detailIndex++)?>) <?php echo ($item['item_jp_code']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($item['item_name']);?><br>
                  <?php echo ('('.$item['item_code'].')');?>&nbsp;&nbsp;&nbsp;<?php echo ($item['composition_1']);?> <?php echo ($item['composition_2']);?> <?php echo ($item['composition_3']);?><br>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COL.<?php echo ($item['color']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SIZE: <?php echo ($item['size']);?>&nbsp;<?php echo ($item['size_unit']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($item['quantity']);?>&nbsp;<?php echo ($item['unit']);?>&nbsp;&nbsp;&nbsp;(J)chua biet
                </td>
              </tr>
              <?php endif?>
              <?php endforeach?>
            </tbody>
          </table>
      <hr>
      &nbsp;&nbsp;&nbsp;G.TOTAL ( 梱包数量 )<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php foreach ($totalItem as $key => $value) { echo ($value.' '. $key . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');}?>
      <hr>
    </div>
    <?php endforeach?>
  </div>
</body>
</html>
