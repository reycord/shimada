<?php

define('PASSWORD_HASH_METHOD', PASSWORD_BCRYPT);

function is64bitPHP() {
  return PHP_INT_SIZE === 8;
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
if( is64bitPHP() === false ) {
  define('PDF_TO_TEXT_PATH', 'xpdf-tools-win-4.00/bin32/pdftotext.exe');
} else {
  define('PDF_TO_TEXT_PATH', 'xpdf-tools-win-4.00/bin64/pdftotext.exe');
}
} else if (strtoupper(substr(PHP_OS, 0, 6)) === 'DARWIN'){
if( is64bitPHP() === false ) {
  define('PDF_TO_TEXT_PATH', 'xpdf-tools-mac-4.00/bin32/pdftotext');
} else {
  define('PDF_TO_TEXT_PATH', 'xpdf-tools-mac-4.00/bin64/pdftotext');
}
} else {
if (`which pdftotext`) {
  define('PDF_TO_TEXT_PATH', 'pdftotext');
} else {
  die('PDF_TO_TEXT_PATH not available. Download the Xpdf tools from http://www.xpdfreader.com/download.html then install');
}
}

define('ORDER_RECEIVED_ID_FORMAT', 'PV00000000');


define('ORDER_RECEIVED_TYPE_JP', '1'); // shimada japan
define('ORDER_RECEIVED_TYPE_OTHER', '2'); // other

define('KOMOKU_STATUS', 'KM0001');
define('KOMOKU_CURRENCY', 'KM0002');
define('KOMOKU_TAX', 'KM0003');
define('KOMOKU_WAREHOUSE', 'KM0004');
define('KOMOKU_DEPARTMENT', 'KM0005');
define('KOMOKU_POSITION', 'KM0006');
define('KOMOKU_PACKING', 'KM0007');
define('KOMOKU_BANK', 'KM0008');
define('KOMOKU_SIZE', 'KM0009');
define('KOMOKU_SIZEUNIT', 'KM0010');
define('KOMOKU_UNIT', 'KM0011');
define('KOMOKU_CLASSIFY', 'KM0012');
define('KOMOKU_CONTRACTTYPE', 'KM0013');
define('KOMOKU_ORIGIN', 'KM0014');
define('KOMOKU_CUSTOMER_CODE', 'KM0015');
define('KOMOKU_EXCHANGE_RATE', 'KM0016');
define('KOMOKU_COLOR', 'KM0017');
define('KOMOKU_SHIPPING_METHOD', 'KM0018');
define('KOMOKU_APPAREL', 'KM0019');
define('KOMOKU_ITEMTYPE', 'KM0020');
define('KOMOKU_PO_TYPE', 'KM0021');
define('KOMOKU_END_SALESMAN', 'KM0022');
define('KOMOKU_PAYMENT', 'KM0023');
define('KOMOKU_SHOUSHA', 'KM0024');
define('KOMOKU_INTERCOM', 'KM0025');
define('KOMOKU_INSURANCE', 'KM0026');
define('KOMOKU_FEE', 'KM0027');
define('KOMOKU_PARTY', 'KM0028');
define('KOMOKU_HEADER', 'KM0029');
define('COLOR_LIST', 'KM0030');
define('KOMOKU_FEE_TERM', 'KM0031');
define('KOMOKU_VAT_BY', 'KM0032');
define('KOMOKU_EXPORT_TERM', 'KM0033');
define('KOMOKU_PAYMENT_METHOD', 'KM0034');

define('INVENTORY_STATUS_KUBUN', '011,012');
define('ARRIVAL_STATUS_KUBUN', '013,014');
define('ORDER_RECEIVED_STATUS_OPEN', '001');
define('ORDER_RECEIVED_STATUS_CLOSE', '002');
define('PACKING_COMPLETED', '009');

define('ORDER_RECEIVED_STATUS_SEARCH', '001,002,008,009,014,015');
define('ORDER_STATUS_SEARCH', '003,004,006,007,015');
define('PACKING_STATUS_SEARCH', '013,018,019,020');
define('PO_VAT_KUBUN', '001');

define('UPLOAD_DIRECTORY', 'upload/');

define('STATUS_FINISH','014,015,016,018,006');
define('STATUS_FINISH_INVENTORY','014,015,016,018,005,006');

define('PERMISSION_MANAGER','002');

define('RESERVED_ITEM', '016');
define('NOT_YET', '019');
define('JAPAN_ORDER', '1');
define('ANOTHER_ORDER', '2');
define('WAREHOUSE_SMDJP', 'SMDJP');
define('WAREHOUSE_SMDVN', 'SMDVN');
define('WAREHOUSE_VATCN', 'VATCN');
define('SALESMAN_FREE', 'Free');
define('ALLOCATE_ITEM_STATUS', '010,011,012');
define('INVENTORY_INPUT_STATUS', '010,012');

define('INV_PL_PAYMENT', '001,002,003,004');
define('PAYMENT_TERM_VAT', array('010'));
define('MAX_ITEM_IN_LIST_CONTRACT', 5);

define('PO_SHEET_HEADER', '001,002,003');
define('INV_PL_HEARDER', '004,005,006,007,008,009,010,011,012,013,014,015,016,017,018,019,020,021,022,023,024,025,026,027,028,029,030,031,032,033,034,035,036,037,038,039,040,041,042,043,044,045,046,047,048,049,050,051,052,053,054,055,056,057,058,059,060,061,062,063,064,065,066,067,068,069,070,071,072,073,074,075,076,077');
