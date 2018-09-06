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

    @page {
      size: A4;
      margin: 0;
    }

    @media print {
      html,
      body {
        width: 210mm;
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
        page-break-after: always;
      }
    }
    .content{
      padding-left:40px;
    }
    body {
      color:rgb(0, 112, 192);
    }

    .txt-black {
      color: black;
    }
    hr {
      border: 0;
      background-color: #fff;
      border-top: 1px dashed #8c8c8c;
    }
  </style>
</head>

<body>
  <div class="book">
  <?php for ($i=0; $i < 2; $i++){?>
    <div class="page">
      <div class="row" style="margin-top: 1mm">
        <div class="col-7 text-content">
          <span class="address-company-name">SHIMADA SHOJI (VIETNAM) CO.,LTD</span>
          <p class="address">28 VSIP, Street 3, Vietnam-Singapore Industrial Park
            <br/>Thuan An District, Binh Duong Province, Vietnam
            <br/>Tel:(84) 650.3768987
            <span>Fax: (84)650.3768986</span>
          </p>
        </div>
        <div class="col-4" style="margin-right:30px;">
          <h3 class="txt-black"><b>PACKING LIST.</b></h3><br/>
          <span class="text-content">SHIPPING MARKS</span><br/>
          <span class="text-content">DETAILS AS PER</span><br/>
          <span class="text-content">ATTACHED SHEET</span>
        </div>
      </div>
      <!-- <div class="row">
        <div class="col-4 col-offset-8 text-center" style="margin-top: -70px">

        </div>
      </div> -->
      <!-- <div class="row">
        <div class="col-4 col-offset-8 text-center" style="margin-bottom: -60px">

        </div>
      </div> -->
      <div class="row" style="margin-top: 5mm">
        <div class="col-9">
          <table>
            <tr>
              <td class="txt-black" style="padding-right: 15px; vertical-align: text-top;">CONSIGNED TO:</td>
              <td class="text-content">
                <span class="address-company-name"><br/>GARMENT RESOURCE CORPORATION</span>
                <p class="address">LOT 7,DIEN NAM DIEN NGOC<br/>
                  INDUSTRIAL ZONE,DIEN BAN DISTRICT<br/>
                  QUANG NAM PROVINCE,VIETNAM<br/>
                  ATTN:MS.LAN<br/>
                  TEL:84-235-3843051<br/>
                </p>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <p></p>
      <div class="row">
        <div class="col-8">
          <span class="txt-black">DATE :</span><span>7013030</span>
        </div>
      </div>
      <p></p>
      <div class="row">
        <div class="col-8">
          <span class="txt-black">CONT :</span><span>SH-VTEC-10AW-01</span>
        </div>
      </div>
      <p></p>
      <div class="row">
        <div class="col-8">
          <span class="txt-black">INV :</span><span>80800032</span>
        </div>
      </div>
      <p></p>
      <div class="row" style="margin-bottom:20px;">
        <div class="col-8">
          <span class="txt-black">NO :</span><span>DVT008665,DVT008646,DVT008631</span>
        </div>
      </div>
      <p></p>
      <hr>
      <div class="row">
        <div class="col-12 txt-black">
          <span>CARTON NO.  PACKING  DESCRIPTION OF GOODS AND QUANTITY     NETWT.     GROSSWT.   MEASUREM</span>
        </div>
      </div>
      <hr>
      <p></p>
      <div class="row" style="margin-top:120px;">
        <div class="col-12 text-center">
          <h4>GARMENTS  ACCESSORIES</h4>
        </div>
      </div>

      <div class="col-6 col-offset-3" style="width:50%;">
        <hr>
        <hr>
      </div>

      <div class="row" style="margin-top:30px;">
        <div class="col-12 text-center">
          <h4>- DETAILS  AS PER  ATTACHED  SHEETS -</h4>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</body>
</html>
