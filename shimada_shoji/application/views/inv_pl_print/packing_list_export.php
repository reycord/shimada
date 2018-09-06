<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
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
      font: 11pt "Tahoma";
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
      font-weight: 300;
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
      /* color: #777; */
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
      /* color: #777; */
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
      /* border-top: 1px solid #ddd; */
    }
    .table > thead > tr > th {
      vertical-align: bottom;
      border-top:1px dashed !important;
      border-bottom:1px dashed;
      /* border-bottom: 2px solid #ddd; */
    }
    /* .table > thead{
        border-top:1px dashed;
        border-bottom:1px dashed;
    } */
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
      /* color: rgb(0, 112, 192); */
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
    .text-bold{
      font-weight: bold;
    }

    .page {
     width: 185mm;
      min-height: 297mm;
      padding: 8mm 10mm 8mm 15mm;
      margin: 5mm auto;
      background: white;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      text-transform: uppercase;
      page-break-before: always;
    }
    .book .page:first-child{
        page-break-before: auto;
     }
    .no-float{
        float:none;
    }
    pre{
        font-size: 14.6px;
        color: #000;
    }

    /* .address-company-name {
      font: 11pt "Tahoma";
    } */

    /* .address {
      font-size: 9pt;
    } */

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
    .container{
        padding-right: 15px;
        padding-left: 15px;
    }

    @page {
      size: A4;
      margin: 0;
    }

    @media print {
      html,
      body {
        width: 185mm;
        height: 297mm;
        font-family:sans-serif;
      }
      .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-before: always;
      }
      .book .page:first-child{
        page-break-before: auto;
      }
    }
    .content{
      padding-left:40px;
    }
    body {
      /* color:rgb(0, 112, 192); */
    }

    .txt-black {
      color: black;
    }
    hr {
      border: 0;
      background-color: #fff;
      border-top: 1.5px dashed #8c8c8c;
    }
    .border-left{
      border-left:1.5px solid #000;
    }
    .border-top{
      border-top:1.5px solid #000;
    }
    .border-right{
      border-right:1.5px solid #000;
    }
    .border-bottom{
      border-bottom:1.5px solid #000;
    }
    .indent{
      margin-left:5px;
    }
    .nopadding {
        padding: 0 !important;
        /* margin: 0 !important; */
    }
    .double-line{
        border-top:1px dashed #000;
        border-bottom:1px dashed #000;
        height:4px;
    }
  </style>
</head>
<?php 
    $maxLine = 38;
    if(!function_exists("generatePrintLineByListItem")){
        function generatePrintLineByListItem($items, $data){
            $printLine = array();
            foreach($items as $index => $item){
                $itemStr = str_pad(($item['number_to'] != 0 && $item['number_to'] != '' ? $item['number_from'] . '-' . $item['number_to'] : $item['number_from']),18);
                $packages = ($item['number_to'] != 0 && $item['number_to'] != '' ? abs($item['number_to'] - $item['number_from'] + 1):1);
                $itemStr .= str_repeat(" ",5).str_pad($packages."PACKAGES",33);
                $itemStr .= ( $packages > 1 ? "(@":"").str_pad($item['netwt']."KG",7," ", STR_PAD_LEFT).str_pad($item['grosswt']."KG", 7, " ", STR_PAD_LEFT).str_pad($item['measure'], 7, " ", STR_PAD_LEFT).( $packages > 1 ? ")":"");
                array_push($printLine, $itemStr);
                $desStr = wordwrap($item['item_name'], 38, "###");
                $desLine = explode("###", $desStr);
                for($x = 0; $x < count($desLine); $x ++){
                    $itemStr = str_repeat(" ",18).$desLine[$x].str_repeat(" ",38);
                    array_push($printLine, $itemStr);
                }
                $sizeColorArray = explode(",", $item["size_color"]);
                foreach($sizeColorArray as $i=> $sizeColor){
                    $sCl = array_map("trim", explode("/",$sizeColor));
                    $size = str_repeat(" ",16).str_pad(($sCl[0] != '' && $sCl[0] != null ? 'SIZE:  '.$sCl[0]. $item['size_unit'] : ''), 13," ", STR_PAD_LEFT).str_pad(($sCl[1] != '' && $sCl[1] != null ? 'COL.' . $sCl[1] : ''), 13," ", STR_PAD_LEFT).str_pad($sCl[2] . " " . $item['unit'], 13," ", STR_PAD_LEFT);
                    array_push($printLine, $size);
                }
                array_push($printLine,str_repeat(" ",80));
            }
            return $printLine;
        }
    }
    $thead = str_repeat("-",80);
    $thead .= "\n";
    $thead .= str_pad('CARTON NO. PACKING',20);
    $thead .= str_pad('DESCRIPTION OF GOOODS AND QUANTITY',35);
    $thead .= str_pad('NETWT.',8);
    $thead .= str_pad('GROSSWT.',8);
    $thead .= str_pad('MEASUREM',8);
    $thead .= "\n";
    $thead .= str_repeat("-",80);
?>
<body>
  <div class="book">
    <!-- Cover page -->
    <div class="page" style="page-break-before: auto;">
        <div class="container">
            <div class="row" style="margin-top: 1mm">
                <div class="col-6 nopadding text-content">
                    <h5 class="address-company-name"><?php echo $data['header_name'] ?></h5>
                    <h5 class="address col-10 nopadding  no-float">
                        <?php echo $data['header_address'] ?>
                    </h5>
                </div>
                <div class="col-4 col-offset-2">
                    <?php if($type=="packing_list"): ?>
                        <h3 class="txt-black text-center">
                            <b>PACKING LIST</b>
                            <h5 class="double-line"></h5>
                            <h5>SHIPPING MARKS</h5>
                        </h3>
                    <?php elseif($type=="delivery_note"): ?>
                        <h4 class="txt-black text-center">
                            <b>DELIVERY NOTE</b>
                            <h5 class="double-line"></h5>
                            <h5>SHIPPING MARKS</h5>
                        </h4>
                    <?php elseif($type=="inv_del_voucher"): ?>
                        <h4 class="txt-black text-center">
                            <b>INVENTORY DELIVERY VOUCHER</b>
                            <h5 class="double-line"></h5>
                            <h5>SHIPPING MARKS</h5>
                        </h4>
                    <?php endif; ?>
                    <h5><?php echo  $data['details_as'] ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-2 nopadding"><h5>CONSIGNED TO:</h5></div>
                <div class="col-4"><h5><?php echo $data["consigned_to"] ?></h5></div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>DATE: <?php echo $data['delivery_date'] ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>CONT: </h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>INV: <?php echo implode(",", $data['invoice_no']) ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>NO. <?php echo implode(",", $data['delivery_no']) ?></h5>
                </div>
            </div>
            <div class="row">
                <pre> <?php echo $thead; ?> </pre>
            </div>
            <div class="row" style="margin:30px 0px;">
                <div class="text-center"><h3>GARMENTS ACCESSORIES</h3></div>
                <div class="col-6 col-offset-3 double-line"></div>
                <div class="text-center"><h3>--<?php echo $data['details_as'] ?>--</h3></div>
            </div>
            <div class="row">
                <?php
                    $index = 0;
                    $total = str_repeat("=",80);
                    $total .= "\n";
                    $total .= str_pad("TOTAL".str_repeat(" ",5).$data['total_package']."PACKAGES",56);
                    $total .= $data['total_netwt']."KG".str_repeat(" ",3)
                            .$data['total_grosswt']."KG".str_repeat(" ",3)
                            .$data['total_measurem'];
                    $total .= "\n";
                    if(is_array($data['total_unit'])) {
                        foreach ($data['total_unit'] as $key => $value) {
                            $total .= str_repeat(" ",22).$value.' '.$key;
                            $index ++;
                            if($index !== count($data['total_unit'])){
                            $total .= "\n";
                            }
                        }
                    } else {
                        $total .= str_repeat(" ",22).$data['total_quantity'];
                    }
                    $total .= "\n";
                    $total .= str_repeat("=",80);
                ?>
                <pre><?php echo $total; ?></pre>
            </div>
            <?php if($type=="packing_list") : ?>
                <div class="row">
                    <div class="col-4 col-offset-8 text-center">
                        <h5><?php echo $data['header_name'] ?></h5>
                    </div>
                </div>
            <?php elseif($type=="delivery_note"): ?>
                 <div class="row" style="margin-top:20px">
                    <div class="col-4"><h5>RECEIVER DATE:</h5></div>
                    <div class="col-4 col-offset-2"><h5>DATE:</h5></div>
                </div>
                <div class="row">
                    <div class="col-4"><h5><?php echo $data['customer']?></h5></div>
                    <div class="col-4 col-offset-2"><h5><?php echo $data['header_name']?></div>
                </div>
            <?php elseif($type=="inv_del_voucher"): ?>    
                <div class="row" style="margin-top:20px">
                    <div class="col-2"><h5>RECEIVER</h5></div>
                    <div class="col-2"><h5>STOCK KEEPER</h5></div>
                    <div class="col-2"><h5>MANAGER</h5></div>
                    <div class="col-2"><h5>APPROVAL</h5></div>
                </div>
                <div class="row">
                    <div class="col-2"><h5>DATE: </h5></div>
                </div>
            <?php endif; ?>
            <div class="row" style="position:absolute; bottom:0%">
                <div class="col-4 col-offset-4 text-center">
                    <h5>---  TO BE CONTINUED  ---</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Mark No page -->
    <div class="page">
        <div class="container">
            <div class="row" style="margin-top: 1mm">
                <div class="col-6 nopadding text-content">
                    <h5 class="address-company-name"><?php echo $data['header_name'] ?></h5>
                    <h5 class="address col-10 nopadding  no-float">
                        DATE: <?php echo $data['delivery_date'] ?>
                    </h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>CONT: </h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>INV:  <?php echo implode(",", $data['invoice_no']) ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 nopadding txt-black">
                    <h5>NO. <?php echo implode(",", $data['delivery_no']) ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-4 nopadding txt-black" style="margin-top:4px">
                    <h5>MARKS &#x26; NO</h5>
                </div>
                <div class="col-4 text-center txt-black">
                    <?php if($type=="packing_list"): ?>
                        <h4>PACKING LIST</h4>
                    <?php elseif($type=="delivery_note"): ?>
                        <h4>DELIVERY NOTE</h4>
                    <?php elseif($type=="inv_del_voucher"): ?>
                        <h4>INVENTORY DELIVERY VOUCHER</h4>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row" style="width:90%">
                <?php foreach ($data['case_mark'] as $case_mark): ?>
                    <div class="col-4 text-center noppading" >
                        <h5><?php echo $case_mark ?></h5>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="row" style="position:absolute; bottom:0%">
                <div class="col-4 col-offset-4 text-center">
                    <h5>---  TO BE CONTINUED  ---</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- attach page -->
    <?php foreach( $data['delivery'] as $pIndex=> $delivery):  ?>
        <?php foreach($delivery['pack'] as $packIndex => $pack): ?>
            <?php
                $printLine = generatePrintLineByListItem($pack["items"], $data);
                $printPages = array_chunk($printLine, $maxLine, true);  
            ?>
            <!-- child attach page -->
            <?php foreach($printPages as $item=> $print): ?>
                <div class="page">
                    <div class="container">
                        <div class="row" style="margin-top: 1mm">
                            <div class="col-6 nopadding text-content">
                                <h5 class="address-company-name"><?php echo $data['header_name'] ?></h5>
                                <h5 class="address col-10 nopadding  no-float">
                                    <?php echo $data['header_address'] ?>
                                </h5>
                            </div>
                            <div class="col-4 col-offset-2">
                                <?php if($type=="packing_list"): ?>
                                    <h3 class="txt-black text-center">
                                        <b>PACKING LIST</b>
                                        <h5 class="double-line"></h5>
                                        <h5>SHIPPING MARKS</h5>
                                    </h3>
                                    <h5><?php echo $pack['case_mark'] ?></h5>
                                <?php elseif($type=="delivery_note"): ?>
                                    <h4 class="txt-black text-center">
                                        <b>DELIVERY NOTE</b>
                                        <h5 class="double-line"></h5>
                                        <h5>SHIPPING MARKS</h5>
                                    </h4>
                                    <h5><?php echo  $data['details_as'] ?></h5>
                                <?php elseif($type=="inv_del_voucher"): ?>
                                    <h4 class="txt-black text-center">
                                        <b>INVENTORY DELIVERY VOUCHER</b>
                                        <h5 class="double-line"></h5>
                                        <h5>SHIPPING MARKS</h5>
                                    </h4>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 nopadding txt-black">
                                <h5>DATE: <?php echo $delivery['delivery_date'] ?></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 nopadding txt-black">
                                <h5>CONT: <?php echo $delivery['delivery_no'] ?></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 nopadding txt-black">
                                <h5>INV: <?php echo $pack['invoice_no'] ?></h5>
                            </div>
                        </div>
                        <div class="row">
                            <pre><?php  echo $thead."\n".implode($print,"\n") ?></pre>
                        </div>
                        <?php if($item == count($printPages)-1): ?>
                            <div class="row">
                                <?php
                                    $index = 0;
                                    $total = str_repeat("=",80);
                                    $total .= "\n";
                                    $total .= str_pad("TOTAL".str_repeat(" ",5).$pack['total_package']."PACKAGES",56);
                                    $total .= $pack['total_netwt']."KG".str_repeat(" ",3)
                                            .$pack['total_grosswt']."KG".str_repeat(" ",3).$pack['total_measurem'];
                                    $total .= "\n";
                                    if(is_array($pack['total_unit'])) {
                                        foreach ($pack['total_unit'] as $key => $value) {
                                            $total .= str_repeat(" ",22).$value.' '.$key;
                                            $index ++;
                                            if($index !== count($pack['total_unit'])){
                                            $total .= "\n";
                                            }
                                        }
                                    } else {
                                        $total .= str_repeat(" ",22).$pack['total_quantity'];
                                    }
                                    $total .= "\n";
                                    $total .= str_repeat("=",80);
                                ?>
                                <pre><?php echo $total; ?></pre>
                            </div>
                            <?php if($type=="packing_list") : ?>
                                <div class="row" style="margin-top:20px;">
                                    <div class="row">
                                        <div class="col-4 col-offset-8 text-center">
                                            <h5><?php echo $data['header_name'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($type=="delivery_note"): ?>
                                <div class="row" style="margin-top:20px">
                                    <div class="col-4"><h5>RECEIVER DATE:</h5></div>
                                    <div class="col-4 col-offset-2"><h5>DATE:</h5></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><h5><?php echo $data['customer']?></h5></div>
                                    <div class="col-4 col-offset-2"><h5><?php echo $data['header_name']?></div>
                                </div>
                            <?php elseif($type=="inv_del_voucher"): ?>    
                                <div class="row" style="margin-top:20px">
                                    <div class="col-2"><h5>RECEIVER</h5></div>
                                    <div class="col-2"><h5>STOCK KEEPER</h5></div>
                                    <div class="col-2"><h5>MANAGER</h5></div>
                                    <div class="col-2"><h5>APPROVAL</h5></div>
                                </div>
                                <div class="row">
                                    <div class="col-2"><h5>DATE: </h5></div>
                                </div>
                            <?php endif; ?>
                            <?php if($pIndex == count($data['delivery'])-1 && $packIndex == count($delivery['pack']) - 1): ?>
                                <div class="row" style="position:absolute; bottom:2%">
                                    <div class="col-4 col-offset-4 text-center">
                                        <h5>---  E. &#x26; O.E.  ---</h5>
                                    </div>
                                    <div class="col-3  text-center">
                                        <h5>NO.</h5>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="row" style="position:absolute; bottom:2%">
                                    <div class="col-4 col-offset-4 text-center">
                                        <h5>--- TO BE CONTINUED ---</h5>
                                    </div>
                                    <div class="col-3  text-center">
                                        <h5>NO.</h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else:  ?>
                            <div class="row" style="position:absolute; bottom:0%">
                                <div class="col-4 col-offset-4 text-center">
                                    <h5>---  TO BE CONTINUED  ---</h5>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
  </div>
</body>
</html>
