<?php
$CARTON = 18;
$DESCRIPTION = 80-18-24;
$NETW = 24;

?>
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
      /* border-bottom: 2px solid #ddd; */
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
    .table > thead{
        border-top:1px dashed;
        border-bottom:1px dashed;
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
      margin: 15mm 14mm 15mm 14mm;
      /* margin: 5mm auto; */
      background: white;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      text-transform: uppercase;
      page-break-before: always;
    }
    .last-page{
        page-break-before: auto !important;
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
      .last-page{
        page-break-before:avoid !important;
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
      border-top: 1px dashed #8c8c8c;
    }
    .border-left{
      border-left:1px solid #000;
    }
    .border-top{
      border-top:1px solid #000;
    }
    .border-right{
      border-right:1px solid #000;
    }
    .border-bottom{
      border-bottom:1px solid #000;
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
    function generatePrintLineByListItemInv($items, $data){
        $printLine = array();
        foreach($items as $index => $item){
            $desStr = wordwrap($item['item_name'], 40, "###");
            $desLine = explode("###", $desStr);
            if(isset($data['origin'])){
                $itemStr = str_pad(str_repeat(" ",2).($index + 1).'.'.$desLine[0],45).str_repeat(" ",18).'MADE IN '.$item["origin"];
                array_push($printLine, $itemStr);
            }else{
                $itemStr = str_repeat(" ",2).($index + 1).'.'.$desLine[0].str_repeat(" ",40);
                array_push($printLine, $itemStr);
            }
            for($x = 1; $x < count($desLine); $x ++){
                $itemStr = str_repeat(" ",4).$desLine[$x].str_repeat(" ",40);
                array_push($printLine, $itemStr);
            }
            $sizeColorArray = explode(",", $item["size_color"]);
            foreach($sizeColorArray as $i=> $sizeColor){
                $sCl = array_map("trim", explode("/",$sizeColor));
                $size = str_pad(($sCl[0] != '' && $sCl[0] != null ? 'SIZE:  '.str_pad($sCl[0]. $item['size_unit'],4," ",STR_PAD_LEFT) : ''), 15," ", STR_PAD_LEFT).str_pad(($sCl[1] != '' && $sCl[1] != null ? 'COL.' . str_pad($sCl[1],4," ", STR_PAD_LEFT) : ''), 15," ", STR_PAD_LEFT).str_pad($sCl[2] . " " . $item['unit'], 15," ", STR_PAD_LEFT);
                if($i == count($sizeColorArray)-1){
                    $size = str_pad($size, 45 , " ", STR_PAD_LEFT);
                    $val1 = round($item['sell_price']* $data["rate"], 2);
                    $size .= str_repeat(" ",15-strlen( $val1)).'@'.'<span style="font-family:DejaVu Sans; font-size:12px">'.$data['currency_unit'].'</span>'.$val1;
                    $val2 = round($item['amount']* $data["rate"], 2);
                    $size .= str_repeat(" ",15-strlen( $val2)).'<span style="font-family:DejaVu Sans; font-size:12px">'.$data['currency_unit'].'</span>'.'<span>'.$val2.'</span>';
                }
                array_push($printLine, $size);
            }
            array_push($printLine,str_repeat(" ",80));
        }
        return $printLine;
    } 
    $thead = str_repeat("-",80);
    $thead .= "\n";
    $thead .= str_repeat(" ",4).str_pad('DESCRIPTION OF GOODS',30);
    if(isset($data['origin'])){
        $thead .= str_pad('QUANTITY',17);
        $thead .= str_pad('UNIT PRICE',12);
        $thead .= str_pad('ORIGIN',10);
        $thead .= str_pad('AMOUNT',10);
    }else{
        $thead .= str_pad('QUANTITY',17);
        $thead .= str_pad('UNIT PRICE',17);
        $thead .= str_pad('AMOUNT',17);
    }
    $thead .= "\n";
    $thead .= str_repeat("-",80);
?>
<body>
  <div class="book">
    <?php 
        $printLine = generatePrintLineByListItemInv($data['items'],$data);
        $isOnePage = false;
        if(count($printLine) < 16){
          $isOnePage = true;
        }
    ?>
      <!-- cover page -->
     <div class="page" style="page-break-before: auto">
        <div class="container">
            <div class="row" style="margin-top: 1mm">
                <div class="col-8 nopadding text-content">
                    <div class="address-company-name"><?php echo $data['header_name'] ?></div>
                    <div style="margin-left:40px"><?php echo str_replace("\n","<br>",$data['header_address']) ?></div>
                </div>
                <div class="col-4">
                    <h3 class="txt-black text-center">INVOICE</h3><br/>
                </div>
            </div>
            <div class="row" style="margin-top: 5mm">
                <div class="col-6 nopadding txt-black">
                    <div>INVOICE NO: <span style="margin-left: 20px"><?php echo $data['invoice_no_excel'] ?></span></div>
                </div>
                <div class="col-4 col-offset-2 txt-black">
                    <h5>DATE:   <?php echo $data['print_date'] ?></h5>
                </div>
            </div>
            <div class = "row border-top border-bottom">
                <div class="border-right col-7">
                    <div class="border-bottom row">
                        <h5 class="txt-black">NO:   <?php echo implode(",", $data['delivery_no']) ?></h5>
                    </div>
                    <div class="border-bottom row" >
                        <div class="txt-black">CONSIGNED TO:</div>
                        <div class="col-10 no-float" style="margin-left:20px"><?php echo $data["consigned_name"]; echo '<br>'.$data["consigned_to"] ?></div>
                    </div>
                    <div class="border-bottom row">
                        <div class="col-6 nopadding border-right">
                            <h5 class="txt-black ">VESSEL/FIGHT: <?php echo $data['vessel_flight'] ?></h5>
                        </div>
                        <div class="col-6 col-offset-6" style="float:none">
                            <h5 class="txt-black">DATE:   <?php echo $data['delivery_print_date'] ?></h5>
                        </div>
                    </div>
                    <div class="border-bottom row">
                        <div class="col-6 nopadding border-right">
                            <h5 class="txt-black ">FROM: </h5>
                        </div>
                        <div class="col-6 col-offset-6" style="float:none">
                            <h5 class="txt-black">TO: </h5>
                        </div>
                    </div>
                    <div class="border-bottom row">
                        <h5 class="txt-black" style="margin-top:2px">PAYMENT BY: </h5>
                        <h5 class="nopadding"><?php foreach(explode(PHP_EOL, $data['payment_by_name']) as $tmp){ echo ($tmp.'<br>');} ?></h5>
                    </div>
                    
                    <div class="row">
                        <h5 class="txt-black">OTHER PREFERENCE: <?php echo $data['other_reference'];?></h5>
                        <h5></h5>
                    </div>

                </div>
                <div class="col-4">
                    <h5 class="txt-black">MARK &#x26; NO</h5>
                    <!-- <h5><?php echo $isOnePage?implode($data['case_mark'],"\n"): $data['details_as'] ?></h5> -->
										<h5>
												<?php 
													$casemark = $isOnePage?implode($data['case_mark'],"\n"): $data['details_as'];
													echo str_replace("\n", "<br>", $casemark);
												?>
										</h5>
                </div> 
            </div>
            <?php if($isOnePage): ?>
                <div class ="row">
                    <pre><?php echo $thead."\n".implode($printLine,"\n") ?></pre>
                </div>
            <?php else: ?>
                <div class="row">
                    <table class="table">
                        <thead style="border:none">
                            <tr>
                                <th style="color:#000">DESCRIPTION OF GOODS</th>
                                <th style="color:#000">QUANTITY</th>
                                <th style="color:#000">UNIT PRICE</th>
                                <?php if(isset($data['origin'])):  ?>
                                    <th style="color:#000">ORIGIN</th>
                                <?php endif; ?>
                                <th style="color:#000">AMOUNT</th>
                            <!-- <th style="width:10%">ORIGIN</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row" style="margin:30px 0px;">
                    <div class="text-center"><h3>GARMENTS ACCESSORIES</h3></div>
                    <div class="col-6 col-offset-3 double-line"></div>
                    <div class="text-center"><h3>--<?php echo $data['details_as'] ?>--</h3></div>
                </div>
            <?php endif ?>
            <div class="row">
                    <?php
                        $index = 0;
                        $total = str_repeat("=",80);
                        $total .= "\n";
                        $total .= str_repeat(" ",2)."TOTAL";
                        $total .= "\n";
                        if(is_array($data['total_unit'])) {
                          foreach ($data['total_unit'] as $key => $value) {
                            $total .= str_pad(str_repeat(" ",15).$value.' '.$key, 55);
                            $index ++;
                            if($index !== count($data['total_unit'])){
                              $total .= "\n";
                            }
                          }
                        } else {
                          $total .= str_pad(str_repeat(" ",15).$data['total_quantity'], 55);
                        }
                        $total .= "AMOUNT".str_repeat(" ",5).$data['currency_unit'].round($data['total_amount'], 2);
                        $total .= "\n";
                        $total .= str_repeat("=",80);
                    ?>
                    <pre><?php echo $total; ?></pre>
                </div>
            <?php if($data['buyer_address'] != ""): ?>
                <div class="row">
                    <div class="col-1">BUYER: </div>
                    <?php if($data['notify'] == null || $data['notify'] == ""):?>
                      <div class="col-7"> <?php echo $data['buyer']; echo '<br>'.str_replace("\n","<br>",$data['buyer_address']) ?></div>
                    <?php else:?>
                      <div class="col-7"> <?php echo $data['buyer']; echo '<br>'.'NOTIFY: '.'<br>'.$data['notify'].'<br>'.str_replace("\n","<br>",$data['notify_address']) ?></div>
                    <?php endif?>
                </div>
            <?php endif ?>

            <p></p>
            <div class="row" style="margin-top:20px;">
                <div class="col-4 text-center">
                  <h5>STANDARD EXPORT CARTONS</h5>
                </div>
                <div class="col-4 col-offset-3 text-center">
                  <h5><?php echo $data['header_name']  ?></h5>
                </div>
            </div>
            <div class="col-4 col-offset-8 text-center text-bold text-black">
                <br>
                <br>
                ------------------------------
            </div>
            <?php if($isOnePage): ?>
                <div class="row" style="position:absolute; bottom:0%">
                    <div class="col-4 col-offset-4 text-center">
                        <h5>---  E. &#x26; O.E.  ---</h5>
                    </div>
                    <div class="col-3  text-center">
                        <h5>NO.</h5>
                    </div>
                </div>
            <?php else: ?>
                <div class="row" style="position:absolute; bottom:0%">
                    <div class="col-4 col-offset-4 text-center">
                        <h5>--- TO BE CONTINUED ---</h5>
                    </div>
                    <div class="col-3  text-center">
                        <h5>NO. 5424</h5>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
    <?php if(!$isOnePage): ?>
        <?php foreach( $data['delivery'] as $pIndex=> $delivery):  ?>
        <!-- attach page -->
            <?php foreach($delivery['pack'] as $packIndex => $pack): ?>

                <?php 
                    $printLine = generatePrintLineByListItemInv($pack["items"],$data);
                    $maxLine = 38;
                    $printPages = array_chunk($printLine, $maxLine, true);
                ?>
                <!-- child attach page -->
                <?php foreach($printPages as $item=> $print): ?>
                    <div class="page ">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 col-offset-8 text-black"><h5>MARKS &#x26; NO</h5></div>
                            </div>
                            <div class = "row">
                                <div class="col-4 col-offset-4 text-center text-black" style="text-decoration: underline;"><h3>ATTACHED SHEET</h3></div>
                                <div class="col-3" style="text-align:left"><h5><?php echo str_replace("\n","<br>",$pack['case_mark']) ?></h5></div>
                            </div>
                            <div style="height:30px;"></div>
                            <div class = "row">
                                <div class="col-4 col-offset-5 text-center text-black"><h5>P.0.NO:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trim($delivery['delivery_no'])  ?></h5></div>
                            </div>
                            <div class="row">
                                <div class="col-6 nopadding text-black"><h5>INVOICE NO:&nbsp;<?php echo $data["invoice_no_excel"] ?></h5></div>
                            </div>
                            <div class="row">
                                <div class="col-1 col-offset-11">
                                    P.<?php echo ($item + 1) ?>
                                </div>
                            </div>
                            <div class ="row">
                                <pre><?php echo $thead."\n".implode($print,"\n") ?></pre>
                            </div>
                            <?php if($item == count($printPages)-1): ?>
                                <div class="row">
                                    <?php
                                        $index = 0;
                                        $total = str_repeat("=",80);
                                        $total .= "\n";
                                        $total .= str_repeat(" ",2)."TOTAL";
                                        $total .= "\n";
                                        if(is_array($data['total_unit'])) {
                                          foreach ($data['total_unit'] as $key => $value) {
                                            $total .= str_pad(str_repeat(" ",15).$value.' '.$key, 55);
                                            $index ++;
                                            if($index !== count($data['total_unit'])){
                                              $total .= "\n";
                                            }
                                          }
                                        } else {
                                          $total .= str_pad(str_repeat(" ",15).$data['total_quantity'], 55);
                                        }
                                        $total .= "AMOUNT".str_repeat(" ",5).$data['currency_unit'].round($pack['total_amount'],2);
                                        $total .= "\n";
                                        $total .= str_repeat("=",80);
                                    ?>
                                    <pre><?php echo $total; ?></pre>
                                </div>
                                <div class="row"><h5>OTHER REFERENCE:</h5></div>
                                <?php if($pIndex == count($data['delivery'])-1 && $packIndex == count($delivery['pack']) - 1): ?>
                                    <div class="row" style="position:absolute; bottom:0%">
                                        <div class="col-4 col-offset-4 text-center">
                                            <h5>---  E. &#x26; O.E.  ---</h5>
                                        </div>
                                        <div class="col-3  text-center">
                                            <h5>NO.</h5>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row" style="position:absolute; bottom:0%">
                                        <div class="col-4 col-offset-4 text-center">
                                            <h5>--- TO BE CONTINUED ---</h5>
                                        </div>
                                        <div class="col-3  text-center">
                                            <h5>NO.</h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="row" style="position:absolute; bottom:0%">
                                    <div class="col-4 col-offset-4 text-center">
                                        <h5>--- TO BE CONTINUED ---</h5>
                                    </div>
                                    <div class="col-3  text-center">
                                        <h5>NO.</h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif ?>
  </div>
</body>
</html>
