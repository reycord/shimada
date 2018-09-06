<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';

define("INV_PL_PRINT_EXPORT_EXCEL_COL", array(
    'date_lang_vn'=>"A6",
    "date_lang_en"=>"A7",
    "cont_no"=>"H5",
    "party_a_en"=>"A9",
    "party_a_vn"=>"A10",
    "party_a_add_en"=>"B11",
    "party_a_add_vn"=>"B13",
    "party_a_tel"=>"B15",
    "party_a_fax"=>"B16",
    "party_a_tax"=>"B17",
    "party_a_represent"=>"B18",
    "party_a_position"=>"B19",
    "party_b_en"=>"A21",
    "party_b_vn"=>"A22",
    "party_b_add_en"=>"B23",
    "party_b_add_vn"=>"B25",
    "party_b_tel"=>"B27",
    "party_b_fax"=>"B28",
    "party_b_tax"=>"B29",
    "party_b_represent"=>"B30",
    "party_b_position"=>"B31",

    "notify"=>"D34",
    "notify_add"=>"D35",
    "notify_tax"=>"D36",
    "notify_tel"=>"D37",
    "notify_fax"=>"H37",
    "notify_represent"=>"C38",
    "notify_position"=>"H38",

    "consignee"=>"D42",
    "consignee_add"=>"D43",
    "consignee_tax"=>"D44",
    "consignee_tel"=>"D45",
    "consignee_fax"=>"H45",
    "consignee_represent"=>"C46",
    "consignee_position"=>"H46",
));

define("INV_PL_PRINT_EXPORT_MAX_ITEM",60);
define("INV_PL_PRINT_EXPORT_START_ITEM",61);
class Inv_Pl_Print extends MY_Controller
{
    var $config_columns_data_sheet = Array(
        "size" => 2,
        "quantity" => 3,
        "color" => 4
    );
    const TEL_PATTERN =
    '/'
    .'tel:?(?<tel>[+\-\s\d]*)'
    .'/i';
    const FAX_PATTERN =
    '/'
    .'fax:?(?<fax>[+\-\s\d]*)'
    .'/i';
    const REPRESENTED_PATTERN =
    '/'
    .'((attn|atten):(?<represented>.+))'
    .'/i';
    const POSITION_PATTERN =
    '/'
    .'((position):(?<position>.+))'
    .'/i';
    const TAXCODE_PATTERN =
    '/'
    .'((tax code):(?<tax_code>.+))'
    .'/i';
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('common_helper');
        $this->load->helper('archive_helper');
		$this->load->model('employee_model');
		$this->load->model('company_model');
		$this->load->model('partners_model');
		$this->load->model('komoku_model');
		$this->load->model('dvt_model');
		$this->load->model('packing_details_model');
		$this->load->model('packing_model');
        $this->load->model('print_model');
        $this->load->model('delivery_model');
        $this->load->model('order_received_details_model');
        $this->load->model('print_contract_model');
        $this->load->model('order_received_model');
    }
    public function index($search_dvt = null)
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'SES0010';
            $this->data['type'] = '1';
            $this->data['search_dvt'] = $search_dvt;
            $this->data['title'] = $this->lang->line('cont_inv_pl_export');
            $this->data['payment_by_list'] = $this->komoku_model->get_all_payment_by();
            $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
            $this->data['currency_list'] = $this->komoku_model->get_all_currency();
            $this->data['origin_list'] = $this->komoku_model->get_all_origin();
            $this->data['branchInfo'] = $this->komoku_model->get_distinct_Branch();
            
            $this->data['payment_method_list'] = $this->komoku_model->get_payment_method();
            // $this->data['payment_term_list'] = $this->komoku_model->get_payment_term();
            $this->data['payment_term_eachtime'] = $this->komoku_model->get_payment_term_eachtime();
            $this->data['payment_term_vat_list'] = $this->komoku_model->get_payment_term_vat();
            $this->data['fee_terms_list'] = $this->komoku_model->get_fee_term();
            $this->data['bank_list'] = $this->komoku_model->get_bank_export();
            $this->data['party_a_list'] = $this->komoku_model->get_party_export();
            $this->data['party_b_list'] = $this->company_model->get_customer_export();
            $this->data['delivery_condition_list'] = $this->komoku_model->get_all_intercom();
            $this->data['shipping_method_list'] = $this->komoku_model->get_shipping_method_with_use("1");

            $customerList = $this->company_model->getAllCustomer();
            $this->data['customer_list'] = $customerList;
            foreach ($customerList as $key => &$customer) {
                $customer['branches_offices'] = $this->company_model->getAllBranchOfCompany($customer['company_id']);
                $tmpArray = $this->company_model->getAllHeadOfficeOfCompany($customer['company_id']);
                $customer['branches_offices'] = array_merge($customer['branches_offices'], $tmpArray);
            };

            $this->data['customerList'] = $customerList;

            $consigneeto_list = $this->company_model->get_all_consignee();
            /* [branchname => [
                                name => branchname,
                                address => branchaddress,
                                shousha => [shoushaname=>[name => shoushaname, address => shoushaaddress]],
                                customer => [customername=>[name => customername, address => customeraddress]]
                ]             ]
            */
            $consignee_out_list = [];
            foreach($consigneeto_list as $consignee){
                $hashNote = hash('md5',$consignee["note"]);
                if(!array_key_exists($consignee["branch_name"], $consignee_out_list)){
                    $consignee_out_list[$consignee["branch_name"]] = [
                        "name" => $consignee["branch_name"],
                        "address" => $consignee["branch_address"],
                        "address_vn" => $consignee["branch_address_vn"],
                        "delivery_condition" => $consignee["delivery_condition"],
                        "delivery_place" => [$hashNote=>[
                                                "address"=>$consignee["note"]
                                            ]],
                        "shousha" => [$consignee["shousha_name"]=>[
                                                "name" => $consignee["shousha_name"],
                                                "address" => $consignee["shousha_address"]
                                    ]],
                        "customer" => [$consignee["customer_name"]=>[
                                                "name" => $consignee["customer_name"],
                                                "address" => $consignee["head_office_address"],
                                                "short_name" => $consignee["short_name"],
                                                "head_office_tel" => $consignee["head_office_tel"],
                                                "head_office_fax" => $consignee["head_office_fax"],
                                                "company_id" => $consignee["company_id"],
                                                "company_name" => $consignee["customer_name"],
                                                "head_office_address" => $consignee["head_office_address"],
                                                "head_office_address_vn" => $consignee["head_office_address_vn"],
                                                "head_office_contract_name" => $consignee["head_office_contract_name"],
                                                "head_office_position" => $consignee["head_office_position"],
                                                "reference" => $consignee["reference"],
                                                "contract_from_date" => $consignee["contract_from_date"],
                                                "contract_end_date" => $consignee["contract_end_date"],
                                                "payment_term" => $consignee["payment_term"]
                                    ]]
                    ];
                }else{
                    if(!empty($consignee["note"])){
                        $delivery_place = ["address"=>$consignee["note"]];
                        if(!array_key_exists($hashNote,$consignee_out_list[$consignee["branch_name"]]["delivery_place"])){
                            $consignee_out_list[$consignee["branch_name"]]["delivery_place"][$hashNote] = $delivery_place;
                        }
                    }
                    if(!empty($consignee["shousha_name"])){
                        $shousha = ["name"=>$consignee["shousha_name"], "address"=>$consignee["shousha_address"]];
                        if(!array_key_exists($shousha["name"],$consignee_out_list[$consignee["branch_name"]]["shousha"])){
                            $consignee_out_list[$consignee["branch_name"]]["shousha"][$consignee["shousha_name"]] = $shousha;
                        }
                    }
                    if(!empty($consignee["customer_name"])){
                        $customer = ["name"=>$consignee["customer_name"],
                                    "address"=>$consignee["head_office_address"],
                                    "short_name" => $consignee["short_name"],
                                    "head_office_tel" => $consignee["head_office_tel"],
                                    "head_office_fax" => $consignee["head_office_fax"],
                                    "company_id" => $consignee["company_id"],
                                    "company_name" => $consignee["customer_name"],
                                    "head_office_address" => $consignee["head_office_address"],
                                    "head_office_address_vn" => $consignee["head_office_address_vn"],
                                    "head_office_contract_name" => $consignee["head_office_contract_name"],
                                    "head_office_position" => $consignee["head_office_position"],
                                    "reference" => $consignee["reference"],
                                    "contract_from_date" => $consignee["contract_from_date"],
                                    "contract_end_date" => $consignee["contract_end_date"],
                                    "payment_term" => $consignee["payment_term"]
                                    ];
                        
                        if(!array_key_exists($customer["name"],$consignee_out_list[$consignee["branch_name"]]["customer"])){
                            $consignee_out_list[$consignee["branch_name"]]["customer"][$consignee["customer_name"]] = $customer;
                        }
                    }
                }
                
            }
            // Get list all customer from $consignee_out_list
            $list_customer = array();
            foreach($consignee_out_list as $consignee){
                $list_customer_tmp = $consignee["customer"];
                foreach($list_customer_tmp as $customer_tmp){
                    $customer_name = $customer_tmp["name"];
                    if(!array_key_exists($customer_name, $list_customer)){
                        $list_customer[$customer_name] = $customer_tmp;
                    }
                }
            }
            $this->data['consigneeto'] = $consignee_out_list;
            $this->data['list_partyb_consignee'] = $list_customer;

            // Data datatable
            $results = $this->dvt_model->getAllDVTToPrint($this->input->post('start'),$this->input->post('length'),$recordsFiltered, $recordsTotal);
            foreach($results as &$res){
                $count = 0;
                foreach($results as $temp){
                    if($res['delivery_no'] == $temp['delivery_no'] && $res['order_date'] == $temp['order_date']){
                        $count++;
                    }
                }
                $res['count'] = $count;
            }
            $this->data['data_datatable'] = $results;
            // Load the subview
            $content = $this->load->view('inv_pl_print/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    public function inv_pl(){
        if ($this->is_logged_in()) {
            $this->load->helper(array('dompdf', 'file'));
            $this->load->helper(array('pdfmerger', 'file'));
            //pre data

            $currencyUnit = array("VND"=>'[$VND]',"USD"=>"$","JPY"=>'[$JPY]');
            $data = $this->input -> post();
            $delivery_no_list = json_decode($data['delivery_no_list'], true);

            $data['delivery_no_list'] = $delivery_no_list;
            $data['today'] = date('d-M-Y');
            $data["rate_usd"] = $data["rate_usd"] != '' ? $data["rate_usd"] : 1;
            $data["rate_jpy"] = $data["rate_jpy"] != '' ? $data["rate_jpy"] : 1;
            $data["rate_jpy_usd"] = $data["rate_jpy_usd"] != '' ? $data["rate_jpy_usd"] : 1;
            $data["data_currency"] = $data["data_currency"] != '' ? $data["data_currency"] : 'USD';
            $data['details_as'] = "DETAILS AS PER ATTACHED SHEETS";
            $data["currency_unit"] = $currencyUnit[$data["output_currency"]];
            
            $data['total_quantity'] = 0;
            $data['total_unit'] = "";
            $data['total_amount'] = 0;
            $data['total_package'] = 0;
            $data['total_netwt'] = 0;
            $data['total_grosswt'] = 0;
            $data['total_measurem'] = 0;
            $data['delivery_method'] = "";
            $data['delivery_date'] = "";
            $data['delivery'] = array();
            $data['invoice_no'] = array();
            $data['delivery_no'] = array();
            $data['case_mark'] = array();
            //For Onepage
            $data['items'] = array();
            $data['customer'] = "";

            /** 
             * Generate inventory voucher
             * it dependen on HN or EPE
             */
            $voucher_no = array();
            $pack_no_list = array();
            foreach($delivery_no_list as $delivery){
                $delevery_no_list_temp = array_map(function($deliveryNo) {
                    return trim($deliveryNo['delivery_no']);
                }, $delivery_no_list);
                $delevery_no_list_temp = array_unique($delevery_no_list_temp);
                unset($delevery_no_list_temp[array_search(trim($delivery['delivery_no']), $delevery_no_list_temp)]);
                $flgAddCasemark = true; // Add case mark only difference pack no
                $delivery['total_quantity'] = 0;
                $delivery['total_unit'] = "";
                $delivery['total_amount'] = 0;
                $delivery['total_package'] = 0;
                $delivery['total_netwt'] = 0;
                $delivery['total_grosswt'] = 0;
                $delivery['total_measurem'] = 0;
                $delivery["pack"] = array();
                $delivery['invoice_no'] = array();
                $delivery['case_mark'] = array();

                $packNoArray = explode(",", $delivery['pack_no']);
                $packNoArray= array_unique(array_map('trim',$packNoArray));
                array_push($pack_no_list, $packNoArray);
                foreach($packNoArray as $packNo){
                    $pack = array();
                    $page = $this->packing_model->getPackingById($packNo);
                    if(count($page) > 0){
                        $page = $page[0];
                        $pack = $page;
                        if($pack["invoice_no"] != null && $pack["invoice_no"] != "" && !in_array($pack["invoice_no"], $delivery['invoice_no']) ){
                            array_push($delivery['invoice_no'], $pack["invoice_no"] );
                        }
                        if($pack["invoice_no"] != null && $pack["invoice_no"] != "" && !in_array($pack["invoice_no"], $data['invoice_no']) ){
                            array_push($data['invoice_no'], $pack["invoice_no"] );
                        }
                        if($pack["case_mark"] != null && $pack["case_mark"] != "" && $flgAddCasemark){
                            $flgAddCasemark = false;
                            array_push($data['case_mark'], $pack["case_mark"] );
                            if(!in_array($pack["case_mark"], $delivery['case_mark'])){
                                array_push($delivery['case_mark'], $pack["case_mark"] );
                            }
                        }
                        $printItems = $this->packing_details_model->getPackingDetailsByPackingIdAndDVT($packNo, $delivery["delivery_no"], isset($data["shosha_price"]));
                        // echo '<pre>'; print_r($printItems); echo '</pre>'; return;
                        if(!isset($data['origin'])) {
                            $printItems = array_map(function($printItem) { $printItem['origin'] = ""; return $printItem; }, $printItems);
                        }
                        if(!isset($data['lot_no'])) {
                            $printItems = array_map(function($printItem) { $printItem['lot_no'] = ""; return $printItem; }, $printItems);
                        }
                        // echo '<pre>'; print_r($printItems); echo '</pre>'; return;
                        $outputCurrency = trim($data['output_currency']);
                        foreach($printItems as &$item){
                            switch($data['customer_code']){
                                case "001":
                                    $item["item_name"] = $item["item_name_des"];
                                    break;
                                case "002":
                                    $item["item_name"] = $item["item_name_dsk"];
                                    break;
                                case "003":
                                    $item["item_name"] = $item["item_name_com"];
                                    break;
                                default:
                                    break;
                            }
                            // check output currency and convert currency
                            $itemCurrency = isset($item['currency']) ? trim($item['currency']) : '';
                            if ($itemCurrency !== '') {
                                if ($outputCurrency == 'USD') {
                                    if ($itemCurrency == 'VND') {
                                        $item['sell_price'] /= $data["rate_usd"];
                                    } else if ($itemCurrency == 'JPY') {
                                        $item['sell_price'] *= $data["rate_jpy_usd"];
                                    } 
                                } else if ($outputCurrency == 'VND') {
                                    if ($itemCurrency == 'USD') {
                                        $item['sell_price'] *= $data["rate_usd"];
                                    } else if ($itemCurrency == 'JPY') {
                                        $item['sell_price'] *= $data["rate_jpy"];
                                    } 
                                } else if ($outputCurrency == 'JPY') {
                                    if ($itemCurrency == 'USD') {
                                        $item['sell_price'] /= $data["rate_jpy_usd"];
                                    } else if ($itemCurrency == 'VND') {
                                        $item['sell_price'] /= $data["rate_jpy"];
                                    }
                                }
                                $item['sell_price'] = (float)str_replace(",","",parseMoney($item['sell_price'], $outputCurrency));
                                $item['amount'] = ($item['sell_price'] * $item['quantity']);
                            }
                            $item['currency'] = $outputCurrency;
                        }
                        $pack['items'] = $printItems;
                        if(count($printItems) > 0){
                            // only print pack has item
                            // $pack['total_unit'] = $printItems[0]['unit'];
                            $pack['total_unit'] = array_reduce($printItems,function($sum, $obj){
                                $unit = $obj['unit'] != null ? $obj['unit'] : 'UNKNOWN';
                                if (is_array($sum) && array_key_exists($unit,$sum)) {
                                    $sum[$unit.'S'] += ($obj['quantity'] != null && $obj['quantity'] != '' ? (float) $obj['quantity'] : 0);
                                } else {
                                    $sum[$unit.'S'] = ($obj['quantity'] != null && $obj['quantity'] != '' ? (float) $obj['quantity'] : 0);
                                }
                                return $sum;
                            });
                            $pack['total_quantity'] = array_reduce($printItems,function($sum, $obj){
                                return $sum+=($obj['quantity'] != null && $obj['quantity'] != '' ? $obj['quantity']:0);
                            });
                            $pack["total_netwt"] = array_reduce($printItems,function($sum, $obj){
                                return $sum+=($obj['netwt'] != '' && $obj['netwt'] != null ? $obj['netwt']:0);
                            });
                            $pack["total_grosswt"] = array_reduce($printItems,function($sum, $obj){
                                return $sum+=($obj['grosswt'] != '' && $obj['grosswt'] != null ? $obj['grosswt']:0);
                            });
                            $pack["total_measurem"] = array_reduce($printItems,function($sum, $obj){
                                return $sum+=($obj['measure'] != '' && $obj['measure'] != null ? $obj['measure']:0);
                            });
                            $pack["total_package"] = abs(array_reduce($printItems,function($sum, $obj){
                                return $sum+=($obj['number_to'] != '' && $obj['number_to'] != null && $obj['number_to'] != 0  ? abs($obj['number_to']-$obj['number_from']) + 1:1);
                            }));

                            // calculator amount
                            $pack['total_amount'] = array_reduce($printItems,function($sum, $obj){
                                return $sum+=($obj['amount'] != '' && $obj['amount'] != null ? $obj['amount']:0);
                            });
                            
                            // calculator total
                            $delivery['total_quantity'] += $pack['total_quantity'];
                            $delivery['total_amount'] += $pack['total_amount'];
                            $delivery['total_unit']  = [];//$pack['total_unit'];
                            foreach ($pack['total_unit'] as $key => $value) {
                                if (is_array($delivery['total_unit']) && array_key_exists($key, $delivery['total_unit'])) {
                                    $delivery['total_unit'][$key] += ($value != null && $value != '' ? (float) $value : 0);
                                } else {
                                    $delivery['total_unit'][$key] = ($value != null && $value != '' ? (float) $value : 0);
                                }
                            }
                            $delivery['total_netwt'] += $pack["total_netwt"];
                            $delivery['total_grosswt'] += $pack["total_grosswt"];
                            $delivery['total_measurem'] += $pack["total_measurem"];
                            $delivery['total_package'] += $pack["total_package"];

                            array_push($delivery["pack"], $pack);
                            
                            $data['items'] = $this->merge_array_by_condition($data['items'], $printItems);
                            // $data['items'] = array_merge($data['items'], $printItems);
                        }else{
                            array_push($delivery["pack"], $pack);
                        }

                    }
                }

                /**
                 * Generate inventory voucher
                 * check is HN or EPE
                 */

                /** For Inventory voucher excel no */
                $inventory_voucher = $this->print_model->getAllInvoucherNo();
                $invetory_no = null;
                if(strpos($data['header_name'], 'HANOI') !== false){
                    $filterFnc = function($el) use ($delivery){
                        return $el['delivery_no'] == $delivery['delivery_no']
                        && $el['times'] == $delivery['times']
                        && $el['delivery_date'] == $delivery['delivery_date']
                        && strpos($el['inventory_voucher_excel_no'], 'HN') !== false;
                    };
                    $filterFncHN = function($el){
                        return strpos($el['inventory_voucher_excel_no'], 'HN') !== false;
                    };
                    $tmp = array_filter($inventory_voucher, $filterFnc);
                    if(count($tmp) == 0){
                        $tmp = array_filter($inventory_voucher,$filterFncHN);
                        $invetory_no = "PXK0000".(count($tmp) + 1)."/18(HN)";
                    }else{
                        $invetory_no =  reset($tmp)['inventory_voucher_excel_no'];
                    }

                }else{

                    $filterFnc = function($el) use ($delivery){
                        return $el['delivery_no'] == $delivery['delivery_no']
                        && $el['times'] == $delivery['times']
                        && $el['delivery_date'] == $delivery['delivery_date']
                        && strpos($el['inventory_voucher_excel_no'], 'HN') !== true;
                    };

                    $filterFncNotHN = function($el){
                        return !strpos($el['inventory_voucher_excel_no'], 'HN') !== false;
                    };

                    $tmp = array_filter($inventory_voucher, $filterFnc);
                    if(count($tmp) == 0){
                        $tmp = array_filter($inventory_voucher, $filterFncNotHN);
                        $invetory_no = "PXK0000".(count($tmp) + 1)."/18";
                    }else{
                        $invetory_no = reset($tmp)['inventory_voucher_excel_no'];
                    }
                }
                
                $delivery['invetory_no'] = $invetory_no;
                array_push($data['delivery'], $delivery);
                $data['total_quantity'] += $delivery['total_quantity'];
                if(!is_array($data['total_unit'])){
                    $data['total_unit'] = [];
                }
                // $data['total_unit'] = [];//$delivery['total_unit'];
                foreach($delivery["pack"] as $delivery_tmp){
                    if(isset($delivery_tmp['total_unit']) && is_array($delivery_tmp['total_unit'])) {
                        foreach ($delivery_tmp['total_unit'] as $key => $value) {
                            if (array_key_exists($key, $data['total_unit'])) {
                                $data['total_unit'][$key] += ($value != null && $value != '' ? (float) $value : 0);
                            } else {
                                $data['total_unit'][$key] = ($value != null && $value != '' ? (float) $value : 0);
                            }
                        }
                    }
                }
                $data['total_amount'] += $delivery['total_amount'];
                $data['total_package'] += $delivery['total_package'];
                $data['total_netwt'] +=  $delivery['total_netwt'];
                $data['total_grosswt'] += $delivery['total_grosswt'];
                $data['total_measurem'] += $delivery['total_measurem'];
                $data['delivery_method'] = $delivery['delivery_method'] != null && $delivery['delivery_method'] != ''? $delivery['delivery_method'] : $data['delivery_method'];
                $data['delivery_date'] = $delivery['delivery_date'] != null && $delivery['delivery_date'] != ''? $delivery['delivery_date'] : $data['delivery_date'];
                array_push($data['delivery_no'], $delivery['delivery_no']);
                
                //print data;
                $print = array(
                    "delivery_no"               => $delivery['delivery_no'],
                    "times"                     => $delivery['times'],
                    "delivery_date"             => ($data['delivery_print_date'] != null ? $data['delivery_print_date']: date('Y/m/d H:i:s')),
                    "pack_no"                   => 0,
                    "inventory_voucher_excel_no"=> $invetory_no,
                    "packing_date"              => (count($delivery["pack"])> 0 ? $delivery["pack"][0]["packing_date"] : '9999/12/31'),
                    "print_date"                => $data['print_date'],
                    "print_user"                => $this->data['user']['employee_id'],
                    "customer_code"             => $data['customer_code'],
                    "payment"                   => isset($data['payment_by']) ? $data['payment_by'] : "",
                    "data_currency"             => sizeof(explode(",", $data['data_currency'])) == 1 ? $data['data_currency'] : null,
                    "print_currency"            => $data['output_currency'],
                    "rate"                      => $data["rate_usd"],
                    "rate_jpy"                  => $data["rate_jpy"],
                    "rate_jpy_usd"              => $data["rate_jpy_usd"],
                    "packages"                  => $delivery["total_package"],
                    "quantity"                  => $delivery['total_quantity'],
                    "netwt"                     => $delivery["total_netwt"],
                    "grosswt"                   => $delivery["total_grosswt"],
                    "measure"                   => $delivery["total_measurem"],
                    "seller"                    => $data["header_name"],
                    "seller_add"                => $data["header_address"],
                    "buyer"                     => $data["buyer"],
                    "buyer_add"                 => (isset($data["buyer_add"])&&$data["buyer_add"] != null ? $data["buyer_add"] :''),
                    // "customer"                  => !empty($data["customer"])?$data["customer"]:"",
                    "customer"                  => $data["consigned_name"],
                    "consignee"                 => $data["consigned_to"],
                    "other_reference"           => $data["other_reference"],
                    "notify"                    => $data["notify"],
                    "notify_add"                => $data["notify_address"],
                    "invoice_no"                => $data["invoice_no_excel"],
                    "red_invoice_no"            => $data["red_invoice_no_excel"],
                    "contract_no"               => $data["contract_no_pl"],
                    "invoice_flg"               => (isset($data["invoice"])?"1":"0"),
                    "packinglist_flg"           => (isset($data["packing_list"])?"1":"0"),
                    "delivery_note_flg"         => (isset($data["delivery_note"])?"1":"0"),
                    "delivery_voucher_flg"      => (isset($data["inv_del_voucher"])?"1":"0"),
                    "delivery_voucher_excel_flg"=> (isset($data["inv_del_voucher_excel"])?"1":"0"),
                    "status"                    => null,
                    "from"                      => $data["from"],
                    "to"                        => $data["to"],
                    "vessel_flight"             => $data["vessel_flight"],
                    "payment_term"              => $data["payment_by_name"],
                    "delivery_condition"        => $data["inv_delivery_condition"],
                    "case_mark"                 => (count($delivery["pack"])> 0 ? $delivery["pack"][0]["case_mark"] : null),
                    // "note"                      => (count($delivery["pack"])> 0 ? $delivery["pack"][0]["note"] : null),
                    "note"                      => (count($delevery_no_list_temp) > 0 ? implode(",",$delevery_no_list_temp) : null),
                    "create_user"               => $this->data['user']['employee_id'],
                    "create_date"               => date('Y/m/d H:i:s'),
                    "del_flg"                   => "0"
                );
                if($print['inventory_voucher_excel_no'] == null){
                    unset($print['inventory_voucher_excel_no']);
                }
                $this->print_model->savePrint($print);
            }
            unset($data['delivery_no_list']);
            $path = sys_get_temp_dir();
            $this->data['data'] = $data;
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load(APPPATH.'views/inv_pl_print/template_pl_dn_inv.xlsx');
            $remove_sheets = [];
            $remove_sheets_tmp1 = [];
            $remove_sheets_tmp2 = [];

            // Create packlist with format [packno=>[items=>[items list], case_mark=>case mark]]
            $pack_list = array();
            $remove_sheets = array();
            $data_tmp = $this->data["data"];
            $inv_list = array();
            $total_unit_pl = array();
            // echo '<pre>'; print_r($data_tmp['delivery']); echo '</pre>'; return;
            foreach( $data_tmp['delivery'] as $pIndex=> $delivery){
                // Add item casemark and total unit of packing by packing
                $pack_no = trim($delivery['delivery_no']);
                $pack_no = str_replace(array("/",":"), "_", $pack_no);
                foreach($delivery['pack'] as $packIndex => $pack){
                    if(!isset($pack["total_unit"])){
                        $pack["total_unit"] = [];
                    }
                    // $pack_no = $pack['delivery_no'];
                    if(!array_key_exists($pack_no, $pack_list)){
                        $pack_list[$pack_no]["items"] = array();
                        $pack_list[$pack_no]["items"] = $pack["items"];
                        $pack_list[$pack_no]["case_mark"] = $pack["case_mark"];
                        $pack_list[$pack_no]["total_unit"] = isset($pack["total_unit"]) ? $pack["total_unit"] : [];
                    }else{
                        $pack_list[$pack_no]["items"] = array_merge($pack_list[$pack_no]["items"], $pack["items"]);
                        foreach($pack["total_unit"] as $unit => $quantity){
                            $existFlg = false;
                            foreach ( $pack_list[$pack_no]["total_unit"] as $key => $value){
                                if($unit == $key) {
                                    $pack_list[$pack_no]["total_unit"][$key] += $quantity;
                                    $existFlg = true;
                                }
                            }
                            if(!$existFlg){
                                $pack_list[$pack_no]["total_unit"][$unit] = $quantity;
                            }
                        }
                        $pack_list[$pack_no]["case_mark"] = $pack["case_mark"];
                    }
                }
            }
            // echo '<pre>'; print_r($pack_list); echo '</pre>'; return;
            // Set data for inv sum quantity and amount by item_code size color
            if(isset($data["invoice"])){
                foreach($pack_list as $pack_no=>$pack){
                    $item_list_tmp = $pack["items"];
                    $inv_list[$pack_no]["items"] = array();
                    $inv_list[$pack_no]["total_unit"] = $pack_list[$pack_no]["total_unit"];
                    $inv_list[$pack_no]["case_mark"] = $pack["case_mark"];
                    $items = array();
                    foreach($item_list_tmp as &$item){
                        if(!empty($item)){
                            $size_color = trim($item["size_color"]);
                            $item_code = trim($item["item_code"]);
                            $size = trim(explode("---", $size_color)[0]);
                            $color = trim(explode("---", $size_color)[1]);
                            $key = $item_code."---".$size."---".$color;
                            if(!array_key_exists($key, $items)){
                                $item["sum_quantity"] = $item["quantity_detail"] * $item["multiple"];
                                $item["sum_amount"] = $item["quantity_detail"] * $item["multiple"] * $item["sell_price"];
                                $items[$key] = $item;
                            }else{
                                $items[$key]["sum_quantity"] += $item["quantity_detail"] * $item["multiple"];
                                $items[$key]["sum_amount"] += $item["quantity_detail"] * $item["multiple"] * $item["sell_price"];
                            }
                        }
                    }
                    $inv_list[$pack_no]["items"] = $items;
                }
            }
            $sheetName1 = "Sheet 1";
            $sheetName2 = "Sheet 2";
            $sheetName3 = "Sheet 3";
            $sheetCover = "Cover";
            $deleteCover = false;
            if(isset($data["packing_list"])){
                $remove_sheets_tmp1 = $this->exportPL_DN_INV($spreadsheet, $this->data, $pack_list, "PL");
            }
            if(isset($data["delivery_note"])){
                $remove_sheets_tmp2 = $this->exportPL_DN_INV($spreadsheet, $this->data, $pack_list, "DN");
            }
            if(isset($data["invoice"])){
                $remove_sheets_tmp3 = $this->exportPL_DN_INV($spreadsheet, $this->data, $inv_list, "INV");
            }
            if(isset($data["inv_del_voucher_excel"])){
                if(count($data['delivery']) == 1 || count($data['items']) == 1){
                    /** Type 1 */
                    $this->exportInventoryVoucherTypeOne($spreadsheet, $data);
                }else{
                    /** Type 2 */
                    $this->exportInventoryVoucherTypeTwo($spreadsheet, $data);
                    $deleteCover = true;
                }
            }
            $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName($sheetName1)));
            $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName($sheetName2)));
            $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName($sheetName3)));
            if(!$deleteCover){
                $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName($sheetCover)));
            }
            // remove sheet if not check
            if (!isset($data["packing_list"]) && !isset($data["delivery_note"]) && !isset($data["invoice"])) {
                $spreadsheet->removeSheetByIndex(0);
                $spreadsheet->removeSheetByIndex(0);
                $spreadsheet->removeSheetByIndex(0);
                $spreadsheet->removeSheetByIndex(0);
                $spreadsheet->removeSheetByIndex(0);
                $spreadsheet->removeSheetByIndex(0);
            }
            //Remove sheet not fill data in both packing_list and delivery_note
            $arr_remove_sheets = [$remove_sheets_tmp1, $remove_sheets_tmp2, $remove_sheets_tmp3];
            $filter_arr_remove_sheets = array_filter($arr_remove_sheets, function($element){
                return count($element) > 0;
            });
            if(count($filter_arr_remove_sheets)===1){
                $remove_sheets = $filter_arr_remove_sheets[array_keys($filter_arr_remove_sheets)[0]];
            }else{
                $remove_sheets = call_user_func_array("array_intersect", $filter_arr_remove_sheets);
            }
            array_map(function($sheet_name) use (&$spreadsheet){
                $sh = $spreadsheet->getSheetByName($sheet_name);
                $i = $spreadsheet->getIndex($sh);
                $spreadsheet->removeSheetByIndex($i);
            }, $remove_sheets);
            // Save data to excel file
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename='INV_PL_DN_PXK_${data['invoice_no_excel']}.xlsx'");
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $writer->save('php://output');
            // $spreadsheet->disconnectWorksheets();
            // unset($spreadsheet); 
        }
    }
    /**
     * Export excel Packing List and Delivery Note
     * @param $spreadsheet workbook fill data
     * @param $data: data contain summary info and detail of items export
     * @param $pack_list: list item by pack no get from $data
     * @param $type: accept "DN" or "PL"
     * @return array sheets should remove
     */
    private function exportPL_DN_INV($spreadsheet, $data, $pack_list, $type){
        $flagInv = false;
        $sheet_name_tmp = "";
        $dataArr = [];
        $data = $data["data"];
        $numberPacking = count($pack_list);
        $summary_pl_sheet = $spreadsheet->getSheetByName('PL (TOTAL)');
        $detail_pl_sheet = $spreadsheet->getSheetByName('PL (Detail)');
        $summary_dn_sheet = $spreadsheet->getSheetByName('DN (TOTAL)');
        $detail_dn_sheet = $spreadsheet->getSheetByName('DN (Detail)');
        $summary_inv_sheet = $spreadsheet->getSheetByName('INV (TOTAL)');
        $detail_inv_sheet = $spreadsheet->getSheetByName('ATTACH (Detail)');

        //Set sheet need remove and add data when $type is PL or DN
        $summary_sheet = null;
        $detail_sheet = null;
        if($type === "PL"){
            $sheet_name_tmp = "PL";
            $summary_sheet = $summary_pl_sheet;
            $detail_sheet = $detail_pl_sheet;
            $remove_sheets = [$summary_dn_sheet->getTitle(), $detail_dn_sheet->getTitle(), $detail_inv_sheet->getTitle(), $summary_inv_sheet->getTitle()];
        }else if($type === "DN"){
            $sheet_name_tmp = "DN";
            $summary_sheet = $summary_dn_sheet;
            $detail_sheet = $detail_dn_sheet;
            $remove_sheets = [$summary_pl_sheet->getTitle(), $detail_pl_sheet->getTitle(), $detail_inv_sheet->getTitle(), $summary_inv_sheet->getTitle()];
        }else if($type === "INV"){
            $sheet_name_tmp = "ATTACH";
            if ($numberPacking == 1) {
                $sheet_name_tmp = "INV";
            }
            $summary_sheet = $summary_inv_sheet;
            $detail_sheet = $detail_inv_sheet;
            $remove_sheets = [
                                $summary_pl_sheet->getTitle(), 
                                $detail_pl_sheet->getTitle(),
                                $summary_dn_sheet->getTitle(),
                                $detail_dn_sheet->getTitle()
                            ];
            $flagInv = true;
        }

        // P/L multiple packing
        if($numberPacking==1){
            // Set sheet Title
            $packNO = trim(array_keys($pack_list)[0]);
            $sheet_name =  "$sheet_name_tmp ($packNO)";
            $sheet_name = str_replace("/", "-", $sheet_name);
            // Fill Item
            reset($pack_list);
            $key = key($pack_list);
            $item_list = $pack_list[$key]['items'];
            $total_unit = $pack_list[$key]['total_unit'];
            $deleteRowList = array();
            if ($flagInv) {
                $summary_sheet_tmp = clone $summary_sheet;
                $summary_sheet_tmp->setTitle($sheet_name);
                $spreadsheet->addSheet($summary_sheet_tmp, $spreadsheet->getIndex($summary_sheet));
                // Fill header
                $this->fillHeader_Footer($summary_sheet_tmp, $data, $flagInv, true, array_keys($pack_list), $numberPacking);
                $this->fillItemList($summary_sheet_tmp, $item_list, $total_unit, $flagInv, $data['currency_unit'], $numberPacking);
                // delete blank rows
                // dvt no
                $rowData = $summary_sheet_tmp->rangeToArray('A12:I12', NULL, TRUE, FALSE);
                if($this->isEmptyRow(reset($rowData))) {
                    $summary_sheet_tmp->removeRow(12);
                }
                // address
                for ($i = 7; $i >  2; $i--) {
                    $rowData = $summary_sheet_tmp->rangeToArray('A' . $i . ':' . 'I' . $i, NULL, TRUE, FALSE);
                    if($this->isEmptyRow(reset($rowData))) {
                        $summary_sheet_tmp->removeRow($i);
                    }
                }
            } else {
                $detail_sheet_tmp = clone $detail_sheet;
                $detail_sheet_tmp->setTitle($detail_sheet_tmp->getTitle()."_temp");
                $spreadsheet->addSheet($detail_sheet_tmp, $spreadsheet->getIndex($detail_sheet));
                // Fill header
                $this->fillHeader_Footer($detail_sheet_tmp, $data, $flagInv, false, array_keys($pack_list),$numberPacking);
                $this->fillItemList($detail_sheet_tmp, $item_list, $total_unit, $flagInv, $data['currency_unit'], $numberPacking);
                $detail_sheet_tmp->setTitle($sheet_name);
            }
             // Remove sheet
            $remove_sheets[] = $summary_sheet->getTitle();
            $remove_sheets[] = $detail_sheet->getTitle();
        }else{
            // Fill header
            $this->fillHeader_Footer($summary_sheet, $data, $flagInv, true, array_keys($pack_list), $numberPacking);
            // remove blank rows
            if($flagInv) {
                // dvt no
                $rowData = $summary_sheet->rangeToArray('A12:I12', NULL, TRUE, FALSE);
                if($this->isEmptyRow(reset($rowData))) {
                    $summary_sheet->removeRow(12);
                }
                for ($i = 7; $i >  2; $i--) {
                    $rowData = $summary_sheet->rangeToArray('A' . $i . ':' . 'I' . $i, NULL, TRUE, FALSE);
                    if($this->isEmptyRow(reset($rowData))) {
                        $summary_sheet->removeRow($i);
                    }
                }
            }
            $this->fillHeader_Footer($detail_sheet, $data, $flagInv, false, null, $numberPacking);
            // Fill Item
            $arr_sum_quantity_all_pack = array();
            foreach($pack_list as $pack_no=>$pack){
                $this->changeDVTNo($detail_sheet, $flagInv, $pack_no);
                $arr_case_mark = array_chunk(preg_split('/\r\n|\r|\n/', $pack["case_mark"]),1);
                $item_list = $pack["items"];
                $total_unit = $pack['total_unit'];
                $sheet_name = "$sheet_name_tmp ($pack_no)";
                $detail_sheet_tmp = clone $detail_sheet;
                $detail_sheet_tmp->setTitle($sheet_name);
                if(!$flagInv){
                    $detail_sheet_tmp->fromArray($arr_case_mark, NULL,'N9');
                } else {
                    $detail_sheet_tmp->fromArray($arr_case_mark, NULL,'G6');
                }
                $spreadsheet->addSheet($detail_sheet_tmp, $spreadsheet->getIndex($detail_sheet));
                $sum_row = $this->fillItemList($detail_sheet_tmp, $item_list, $total_unit, $flagInv, $data['currency_unit'], $numberPacking); 
                $arr_sum_quantity_all_pack[] = "'$sheet_name'!A$sum_row";
            }
            // Fill formula for sum row in summary sheet only for sheet difference INVOIVE
            if(!$flagInv){
                $strFormula_ori = implode(",", $arr_sum_quantity_all_pack);
                $summary_sheet->setCellValue("A42", "=SUM($strFormula_ori)");
                // $strFormula = str_replace("!A", "!D", $strFormula_ori);
                // $summary_sheet->setCellValue("D41", "=SUM($strFormula)");
                // $strFormula = explode(",",str_replace("!A", "!E", $strFormula_ori))[0];
                // $summary_sheet->setCellValue("E41", "=$strFormula");
                $strFormula = str_replace("!A", "!N", $strFormula_ori);
                $summary_sheet->setCellValue("L42", "=SUM($strFormula)");
                $strFormula = str_replace("!A", "!O", $strFormula_ori);
                $summary_sheet->setCellValue("M42", "=SUM($strFormula)");
                $strFormula = str_replace("!A", "!P", $strFormula_ori);
                $summary_sheet->setCellValue("N42", "=SUM($strFormula)");
                
                if(is_array($data["total_unit"]) && count($data["total_unit"])>0){
                    $end_row_fill = 42;
                    $total_unit = $data["total_unit"];
                    $unit = key($total_unit);
                    $summary_sheet->setCellValue("D$end_row_fill",isset($total_unit[$unit]) ? $total_unit[$unit] : "");
                    $summary_sheet->setCellValue("E$end_row_fill",$unit);
                    if(count($total_unit) > 1){
                        $row=1;
                        $summary_sheet->insertNewRowBefore($end_row_fill+1, count($total_unit)-1);
                        // Remove first element because filled
                        array_shift($total_unit);
                        foreach($total_unit as $unit => $total) {
                            $summary_sheet->setCellValue("D".($row + $end_row_fill), $total);
                            $summary_sheet->setCellValue("E".($row + $end_row_fill), $unit);
                            $row++;
                        }
                    }
                }
            }
            $remove_sheets[] = $detail_sheet->getTitle();
        }
        $spreadsheet->setActiveSheetIndex(0);
        return $remove_sheets;
    }
    private function fillHeader_Footer($sheet, $data, $flagInv, $sumnaryFlg = false, $invNO = null, $numberPacking = 1){
        $arr_header_addr = array_chunk(preg_split('/\r\n|\r|\n/', $data["header_address"]),1);
        $arr_consignee_addr = array_chunk(preg_split('/\r\n|\r|\n/', $data["consigned_to"]),1);
        $arr_notify_addr = array_chunk(preg_split('/\r\n|\r|\n/', $data["notify_address"]),1);
        $arr_case_mark = array_chunk(preg_split('/\r\n|\r|\n/', $data["delivery"][0]["pack"][0]["case_mark"]),1);
        $sheetName = $sheet->getTitle();
        if($flagInv){
            if ($sumnaryFlg) {
                 // Fill header for invoice
                $arr_buyer_addr = array_chunk(preg_split('/\r\n|\r|\n/', $data["buyer_address"]),1);
                $sheet->setCellValue('A1', $data["header_name"]);
                $sheet->fromArray($arr_header_addr, NULL,'A2');
                $date=date_create($data["print_date"]);
                $sheet->setCellValue('H10', date_format($date,"d-M-Y"));
                $sheet->setCellValue('A10', 'INVOICE NO: '.$data["invoice_no_excel"]);
                if ($invNO != null) {
                    $invText = implode(",", $invNO);
                    if(strlen($invText) > 65) {
                        $invText1 = substr($invText, 0, 65);
                        $pos = strrpos($invText1, ",");
                        $invText1 = substr($invText, 0, $pos);
                        $invText2 = substr($invText, $pos);
                        $sheet->setCellValue('B11', $invText1);
                        $sheet->setCellValue('B12', $invText2);
                    } else {
                        $sheet->setCellValue('B11', $invText);
                    }
                }
                $sheet->setCellValue('B13', $data["contract_no_pl"]);
                $sheet->setCellValue('B15', $data["consigned_name"]);
                $sheet->fromArray($arr_consignee_addr, NULL,'B16');
                $sheet->setCellValue('B22', $data["vessel_flight"]);
                if($data["delivery_date"] != null && $data["delivery_date"] != ""){
                    $date=date_create($data["delivery_date"]);
                    $sheet->setCellValue('D22', date_format($date,"d-M-Y")); // date
                }
                $sheet->setCellValue('B24', $data["from"]); // From
                $sheet->setCellValue('D24', $data["to"]); // To
                $sheet->setCellValue('B26', explode(";",$data["payment_by_name"])[0]);
                $sheet->setCellValue('B29', $data["other_reference"]); // order reference
                $sheet->setCellValue('G31', $data["inv_delivery_condition"]); // delivery condition
                //Fill footer
                if ($numberPacking > 1) {
                    $sheet->setCellValue('G15', "DETAILS AS PER");
                    $sheet->setCellValue('G16', "ATTACHED SHEET");
                    $sheet->setCellValue('C34', "ＧＡＲＭＥＮＴＳ　ＡＣＣＥＳＳＯＲＩＥＳ      ");
                    $sheet->setCellValue('A36', "－　ＤＥＴＡＩＬＳ　ＡＳ　ＰＥＲ　ＡＴＴＡＣＨＥＤ　ＳＨＥＥＴＳ　－");
                    $sheet->getStyle("A34:C36")->getFont()->setSize(12)->setBold(true);;
                } else {
                    $sheet->fromArray($arr_case_mark, NULL,'G14');
                }
                // $sheet->setCellValue('L27',$total_amount);
                $sheet->setCellValue('B45',$data["buyer"]);
                $sheet->setCellValue('B46',$data["notify"]);
                $sheet->fromArray($arr_notify_addr, NULL,'B47');
                $sheet->setCellValue('H50', $data["header_name"]);
                $total_amount = $data["total_amount"];
                $end_row = 43;
                $row=0;
                $total_unit = $data["total_unit"];
                if(count($total_unit) == 1){
                    $sheet->setCellValue("D".($row + $end_row), number_format($total_unit[array_keys($total_unit)[0]]));
                    $sheet->setCellValue("E".($row + $end_row), array_keys($total_unit)[0]);
                } else if(count($total_unit) > 1){
                    $sheet->insertNewRowBefore($end_row+1, count($total_unit) - 1);
                    foreach($total_unit as $unit => $total) {
                        $sheet->setCellValue("D".($row + $end_row), number_format($total));
                        $sheet->setCellValue("E".($row + $end_row), $unit);
                        $row++;
                    }
                    $row--;
                }
                $currency = $data['currency_unit'];
                $sheet->setCellValue("G".($row + $end_row), "AMOUNT");
                $sheet->setCellValue("H".($row + $end_row),$total_amount);
                // Format number
                $currency = $data['currency_unit'];
                if($data["output_currency"] == 'JPY'){
                    $sheet->getStyle("H321:H".($row + $end_row))->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
                    $sheet->getStyle("G32:G".($row + $end_row))->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
                } else if($data["output_currency"] == 'VND') {
                    $sheet->getStyle("H32:H".($row + $end_row))->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
                    $sheet->getStyle("G32:G".($row + $end_row))->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
                } else {
                    $sheet->getStyle("H32:H".($row + $end_row))->getNumberFormat()->setFormatCode("$ #,##0.0000");
                    $sheet->getStyle("G32:G".($row + $end_row))->getNumberFormat()->setFormatCode("$ #,##0.0000");
                }
            }else {
                $sheet->setCellValue('B10',$data["invoice_no_excel"]);
                if ($invNO != null) {
                    $sheet->setCellValue('B11', implode(",", $invNO));
                }
                // $sheet->fromArray($arr_case_mark, NULL,'G6');
                $sheet->setCellValue('G15', $data["inv_delivery_condition"]); // delivery condition
            }

        }else{
            // Fill header for packing list and delivery note
           if(stripos($sheet->getTitle(),"detail") && (stripos($sheetName,"pl")===0 || stripos($sheetName,"dn")===0)){
                // Fill header
                $sheet->setCellValue('A1',$data["header_name"]);
                $sheet->fromArray($arr_header_addr, NULL,'A2');
                $sheet->setCellValue('A9',$data["consigned_name"]);
                $sheet->fromArray($arr_consignee_addr, NULL,'A10');
                $sheet->setCellValue('J9',$data["invoice_no_excel"]);
                $sheet->setCellValue('J10',$data["contract_no_pl"]);
                if ($numberPacking == 1) {
                    $sheet->fromArray($arr_case_mark, NULL,'N9');
                }
                $date=date_create($data["print_date"]);
                $sheet->setCellValue('B15', date_format($date,"d-M-Y"));
                if ($invNO != null) {
                    $sheet->setCellValue('B16', implode(",", $invNO));
                }
                
                // Fill footer
                if(stripos($sheetName,"dn")===0){
                    $sheet->setCellValue('A30',$data["consigned_name"]);
                }
                $sheet->setCellValue('L30',$data["header_name"]);
                
            } else  if((stripos($sheetName,"pl")===0 || stripos($sheetName,"dn")===0)){
                // Fill header
                $sheet->setCellValue('A1',$data["header_name"]);
                $sheet->fromArray($arr_header_addr, NULL,'A2');
                $sheet->setCellValue('A9',$data["consigned_name"]);
                $sheet->fromArray($arr_consignee_addr, NULL,'A10');
                $sheet->setCellValue('J9',$data["invoice_no_excel"]);
                $sheet->setCellValue('J10',$data["contract_no_pl"]);
                $date=date_create($data["print_date"]);
                $sheet->setCellValue('B16', date_format($date,"d-M-Y"));
                if ($invNO != null) {
                    $sheet->setCellValue('B17', implode(",", $invNO));
                }
                // Fill footer
                if(stripos($sheetName,"pl")===0){
                    $sheet->setCellValue('N45',$data["header_name"]);
                }else if(stripos($sheetName,"dn")===0){
                    $sheet->setCellValue('A48',$data["consigned_name"]);
                    $sheet->setCellValue('L48',$data["header_name"]);
                }
    
            }
        }
    }

    private function changeDVTNo($sheet, $flagInv, $dvtNo = ""){
        if ($dvtNo != "") {
            if($flagInv){
                $sheet->setCellValue('B11', $dvtNo);
            } else {
                $sheet->setCellValue('B16', $dvtNo);
            }
            
        }
    }
    private function fillItemList($sheet, $item_list, $total_unit, $flagInv, $currency = "", $numberPacking = 1){
        if($flagInv){
            $empty_row = [null, null,null, null,null, null,null, null];
        }else{
            $empty_row = [null, null,null, null,null, null,null, null,null, null,null, null,null, null,null, null];
        }
        $arr_data = array();
        $index = 1;
        $prev_from = "";
        $prev_to = "";
        $prev_item_name = "";
        $prev_item_size = "";
        $prev_item_color = "";
        $total_amount = 0;
        $outputCurrency = "USD";
        if($flagInv){
            foreach($item_list as $item){
                if(!empty($item)){
                    $outputCurrency = $item['currency'];
                    $total_amount += isset($item['sum_amount']) ? $item['sum_amount'] : $item['amount'];
                    $size_color_list = explode("---",$item['size_color']);
                    $size = count($size_color_list)===3?trim($size_color_list[0]):"";
                    $color = count($size_color_list)===3?trim($size_color_list[1]):"";
                // Create array data for fill into excel
                    $tempDataRow1=[
                        $index++,
                        $item['item_name'],
                        null,
                        null,
                        null,
                        ($item['origin'] != "" ? ("MADE IN ".$item['origin']) : ""),
                        null,
                        null
                    ];
                    array_push($arr_data, $tempDataRow1);
                    if($item['composition_1'] != null && $item['composition_1'] != ""){
                        $tempDataRow=[
                            null,
                            $item['composition_1'],
                            null,
                            null,
                            null,
                            null,
                            null,
                            null
                        ];
                        array_push($arr_data, $tempDataRow);
                    }
                    if($item['composition_2'] != null && $item['composition_2'] != ""){
                        $tempDataRow=[
                            null,
                            $item['composition_2'],
                            null,
                            null,
                            null,
                            null,
                            null,
                            null
                        ];
                        array_push($arr_data, $tempDataRow);
                    }
                    if($item['composition_3'] != null && $item['composition_3'] != ""){
                        $tempDataRow=[
                            null,
                            $item['composition_3'],
                            null,
                            null,
                            null,
                            null,
                            null,
                            null
                        ];
                        array_push($arr_data, $tempDataRow);
                    }
                    $tempDataRow2=[
                        null,
                        "COL. " . $color,
                        null,
                        number_format($item['sum_quantity']),
                        $item['unit'],
                        null,
                        $item['sell_price'],
                        $item['sum_amount']
                    ];
                    array_push($arr_data, $tempDataRow2, $empty_row);
                }
            }
        }else {
            $itemList = $this->detachArray($item_list);
            $total_netwt = 0;
            $total_grosswt = 0;
            $total_measure = 0;
            foreach($itemList as $item){
                $outputCurrency = $item['currency'];
                $total_amount += isset($item['sum_amount']) ? $item['sum_amount'] : $item['amount'];
                $size_color_list = explode("---",$item['size_color']);
                $size = count($size_color_list)===3?trim($size_color_list[0]):"";
                $color = count($size_color_list)===3?trim($size_color_list[1]):"";
                if(!empty($item)){
                    $tmpFrom = preg_replace('/\D/', '', $item['number_from']);
                    $tmpTo = preg_replace('/\D/', '', $item['number_to']);
                    $package_num = !empty($item['number_to']) ? $tmpTo - $tmpFrom + 1: 1;
                    // Case current item same prev item: not fill number from, item code, size, color
                    if($index++ > 1){
                        if($item['number_from']===$prev_from
                            && $item['item_name']===$prev_item_name
                            && $size===$prev_item_size
                            && $color===$prev_item_color){
                                //Remove empty row
                                array_pop($arr_data);
                                //add data
                                $tempDataRow1 = [
                                    null,
                                    null,
                                    null,
                                    null,
                                    $item['quantity_detail'],
                                    $item['unit'],
                                    "x",
                                    $item['multiple'],
                                    "/",
                                    $item['quantity'],
                                    $item['unit'],
                                    null,
                                    null,
                                    $item['lot_no'] == '' || $item['lot_no'] == null ?  $item['lot_no'] : "LOT: ". $item['lot_no'],
                                    null,
                                    null
                                ];
                                array_push($arr_data, $tempDataRow1, $empty_row);
                                // Update quantity
                                for($i=count($arr_data)-1;$i>=0;$i--){
                                    if($arr_data[$i][11]!==null){
                                        $arr_data[$i][11] += $item['quantity'];
                                        break;
                                    }
                                }
                                //Continue
                                continue;
                        } else if($item['number_from']===$prev_from ){
                            //Remove empty row
                            array_pop($arr_data);
                            //add data
                            $tempDataRow1 = [
                                null,
                                null,
                                $item['item_name'],
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                number_format($item['netwt'], 2),
                                number_format($item['grosswt'], 2),
                                number_format($item['measure'], 3)    
                            ];
                            $total_netwt += $item['netwt'] * $package_num;
                            $total_grosswt += $item['grosswt'] * $package_num;
                            $total_measure += $item['measure'] * $package_num;

                            $tempDataRow2 = [
                                null,
                                null,
                                isset($item['composition_1']) ? $item['composition_1'] : null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                $item['quantity'],
                                // $package_num * $item['quantity_detail'] * $item['multiple'],
                                $item['unit'],
                                ($package_num > 1) ? "(".number_format($item['netwt'], 2).")" : null,
                                ($package_num > 1) ? "(".number_format($item['grosswt'], 2).")" : null,
                                ($package_num > 1) ? "(".number_format($item['measure'], 3).")" : null
                            ];
                            $tempDataRow3 = [
                                null,
                                null,
                                "COL. " . $color,
                                null,
                                $item['quantity_detail'],
                                $item['unit'],
                                "x",
                                $item['multiple'],
                                "/",
                                $item['quantity'],
                                $item['unit'],
                                null,
                                null,
                                $item['lot_no'] == '' || $item['lot_no'] == null ?  $item['lot_no'] : "LOT: ". $item['lot_no'],
                                null,
                                null
                            ];
                            array_push($arr_data, $tempDataRow1, $tempDataRow2, $tempDataRow3, $empty_row);
                            //Continue
                            continue;
                        }
                    }
                    $prev_from = $item['number_from'];
                    $prev_to = $item['number_to'];
                    $prev_item_name = $item['item_name'];
                    $prev_item_size = $size;
                    $prev_item_color = $color;

                    $tempDataRow1 = [
                        !empty($item['number_to']) && $tmpTo > $tmpFrom ? $item['number_from'].'-'.$item['number_to'] : ($item['number_from'] != NULL && $item['number_from'] != 0 ? $item['number_from'] : null),
                        $package_num,
                        $item['item_name'],
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        number_format($item['netwt'], 2),
                        number_format($item['grosswt'], 2),
                        number_format($item['measure'], 3)
                    ];
                    $total_netwt += $item['netwt'];
                    $total_grosswt += $item['grosswt'];
                    $total_measure += $item['measure'];

                    $tempDataRow2 = [
                        null,
                        null,
                        isset($item['composition_1']) ? $item['composition_1'] : null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                       $item['quantity'],
                        // $package_num * $item['quantity_detail'] * $item['multiple'],
                        $item['unit'],
                        ($package_num > 1) ? "(".number_format($item['netwt'], 2).")" : null,
                        ($package_num > 1) ? "(".number_format($item['grosswt'], 2).")" : null,
                        ($package_num > 1) ? "(".number_format($item['measure'], 3).")" : null
                    ];
                    array_push($arr_data, $tempDataRow1, $tempDataRow2);
                    foreach($item_list as $itemLoop) {
                        $size_color_list_loop = explode("---",$itemLoop['size_color']);
                        $size_loop = count($size_color_list_loop)===3?trim($size_color_list_loop[0]):"";
                        $color_loop = count($size_color_list_loop)===3?trim($size_color_list_loop[1]):"";
                        if($itemLoop['item_code'] == $item['item_code'] && $size == $size_loop && $color == $color_loop) {
                            $tempDataRow3 = [
                                null,
                                null,
                                "COL. " . $color,
                                null,
                                $itemLoop['quantity_detail'],
                                $itemLoop['unit'],
                                "x",
                                $itemLoop['multiple'],
                                "/",
                                $itemLoop['quantity'],
                                $itemLoop['unit'],
                                null,
                                null,
                                $item['lot_no'] == '' || $item['lot_no'] == null ?  $item['lot_no'] : "LOT: ". $item['lot_no'],
                                null,
                                null
                            ];
                            array_push($arr_data, $tempDataRow3);
                        }
                    }
                    array_push($arr_data, $empty_row);
                }
            }
        }
        // echo '<pre>'; print_r($arr_data); echo '</pre>'; return;
        //Insert new row
        $sheetName = $sheet->getTitle();
        if((stripos($sheetName,"pl")===0 || stripos($sheetName,"dn")===0)){
            $start_row = 21;
        }else if(stripos($sheetName,"inv")===0 || stripos($sheetName,"attach")===0){
            $start_row = 17;
            if ($numberPacking == 1) {
                $start_row = 33;
            }
        }
        $sheet->insertNewRowBefore($start_row+1, count($arr_data));
        $end_row_fill = 0;
        $unit = key($total_unit);
        if($unit==="UNKNOWN") $unit = "";
        $end_row = $start_row + count($arr_data);
        if(!$flagInv){
            //Set formula
            $end_row_fill = $end_row + 4;
            $sheet->setCellValue("A$end_row_fill","=SUM(B$start_row:B$end_row)");
            $sheet->setCellValue("D$end_row_fill",isset($total_unit[$unit]) ? $total_unit[$unit] : "");
            $sheet->setCellValue("E$end_row_fill",$unit);
            $sheet->setCellValue("N$end_row_fill", $total_netwt == 0 ? '-' : $total_netwt);
            $sheet->setCellValue("O$end_row_fill", $total_grosswt == 0 ? '-' : $total_grosswt);
            $sheet->setCellValue("P$end_row_fill", $total_measure == 0 ? '-' : $total_measure);
            if(count($total_unit) > 1){
                $row=1;
                $sheet->insertNewRowBefore($end_row_fill+1, count($total_unit)-1);
                // Remove first element because filled
                array_shift($total_unit);
                foreach($total_unit as $unit => $total) {
                    $sheet->setCellValue("D".($row + $end_row_fill), $total);
                    $sheet->setCellValue("E".($row + $end_row_fill), $unit);
                    $row++;
                }
                $sheet->getStyle("A$end_row_fill".":P".($row + $end_row_fill))->getFont()->setBold(true);
            } 
        }else{
            $end_row_fill = $end_row + 1;
            $sheet->setCellValue("A".($end_row_fill-1), "-======================================================================================================");
            $sheet->setCellValue("A".($end_row_fill), "TOTAL");
            $row=1;
            if(count($total_unit) == 1){
                $sheet->setCellValue("D".($row + $end_row_fill), number_format($total_unit[array_keys($total_unit)[0]]));
                $sheet->setCellValue("E".($row + $end_row_fill), array_keys($total_unit)[0]);
            } else if(count($total_unit) > 1){
                $sheet->insertNewRowBefore($end_row_fill+$row+1, count($total_unit) - 1);
                foreach($total_unit as $unit => $total) {
                    $sheet->setCellValue("D".($row + $end_row_fill), number_format($total));
                    $sheet->setCellValue("E".($row + $end_row_fill), $unit);
                    $row++;
                }
                $row--;
            }
            $sheet->setCellValue("G".($row + $end_row_fill), "AMOUNT");
            $sheet->setCellValue("H".($row + $end_row_fill), $total_amount);
            $sheet->setCellValue("A".($row + $end_row_fill+1), "-======================================================================================================");
            $sheet->setCellValue("A".($row + $end_row_fill+2), "OTHER REFERENCE:");
            $sheet->getStyle("A2:H".($row + $end_row_fill+2))->getFont()->setSize(9);
            // Format number
            if($outputCurrency == 'JPY'){
                $sheet->getStyle("H16:H".($row + $end_row_fill))->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
                $sheet->getStyle("G16:G".($row + $end_row_fill))->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
            } else if ($outputCurrency == 'VND') {
                $sheet->getStyle("H16:H".($row + $end_row_fill))->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
                $sheet->getStyle("G16:G".($row + $end_row_fill))->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
            } else {
                $sheet->getStyle("H16:H".($row + $end_row_fill))->getNumberFormat()->setFormatCode("$ #,##0.0000");
                $sheet->getStyle("G16:G".($row + $end_row_fill))->getNumberFormat()->setFormatCode("$ #,##0.0000");
            }
            
            // delete blank rows
            if ($numberPacking == 1) {
                $sheet->removeRow($row + $end_row_fill + 2, 8);
            }
        }
        
        //Fill item
        $sheet->fromArray($arr_data, NULL, "A".$start_row);
        return $end_row_fill;
    }
    /**
     * Type One : 1 DVT
     */
    public function exportInventoryVoucherTypeOne($spreadsheet, $data){
        $path = sys_get_temp_dir();
        $dataArr = [];
        $sheetName1 = "Sheet 1";
        $sheetName2 = "Sheet 2";
        $sheetName3 = "Sheet 3";
        $sheetCover = "Cover";
        $reference = "";
        $spreadsheet->setActiveSheetIndexByName($sheetName1);
        $sumArr = array('M'=>0,'PCS'=>0,'ROLL'=>0,'CONE'=>0);
        $itemArr = [];
        foreach($data['items'] as &$item){
            $existFlg = false;
            foreach ($itemArr as &$tempItem) {
                $sizeColor1 = substr($item['size_color'], 0, strrpos($item['size_color'], "---"));
                $sizeColor2 = substr($tempItem['size_color'], 0, strrpos($tempItem['size_color'], "---"));
                if($item['item_code'] == $tempItem['item_code'] && $sizeColor1 == $sizeColor2) {
                    $existFlg = true;
                    $quantity1 = (int)substr($item['size_color'], strrpos($item['size_color'], "---") + 3);
                    $quantity2 = (int)substr($tempItem['size_color'], strrpos($tempItem['size_color'], "---") + 3);
                    $tempItem['size_color'] = $sizeColor1."---".($quantity1 + $quantity2);
                }
            }
            if(!$existFlg){
                array_push($itemArr, $item);
            }
        }
        foreach($itemArr as $index=> $item){
            $sCl = array_map("trim", explode("---",$item["size_color"]));
            $tmp = array(
                $index + 1,
                $item["item_code"],
                $item["item_name"],
                null,
                $sCl[0],
                $item["size_unit"],
                $sCl[1],
                $item["unit"],
                $sCl[2]
            );
            /** Calculate Sum */
            if(isset( $sumArr[$item['unit']])){
                $sumArr[$item['unit']] += $item['quantity'];
            }
            array_push($dataArr, $tmp);
        }
        $dataArr = array_chunk($dataArr, 45);
        foreach($dataArr as $index => $iteArr){
            /** clone new sheet */
            if(count($dataArr)- 1 == $index){
                $tempSheet = clone $spreadsheet->getSheetByName($sheetName1);
                $tempSheetName = str_replace("/","-",trim($data['delivery_no'][0]));
                $reference = $tempSheetName;
                $tempSheet->setTitle($tempSheetName.'-'.($index + 1));
                $spreadsheet->addSheet($tempSheet);
                $spreadsheet->setActiveSheetIndexByName($tempSheetName.'-'.($index + 1));
                /** Total */
                $spreadsheet->getActiveSheet()->setCellValue('D61',$sumArr['M'].'  M');
                $spreadsheet->getActiveSheet()->setCellValue('D62',$sumArr['PCS'].'  PCS');
                $spreadsheet->getActiveSheet()->setCellValue('D63',$sumArr['ROLL'].'  ROLL');
                $spreadsheet->getActiveSheet()->setCellValue('D64',$sumArr['CONE'].'  CONE');

                $spreadsheet->getActiveSheet()->setCellValue('H61',$data['total_netwt'].'  NETWT');
                $spreadsheet->getActiveSheet()->setCellValue('H62',$data['total_grosswt'].'  GROSSWT');
                $spreadsheet->getActiveSheet()->setCellValue('H63',$data['total_measurem'].'  MEASUREM');
                $spreadsheet->getActiveSheet()->setCellValue('H64',$data['total_package'].'  PACKAGES');
            }else{
                $tempSheet = clone $spreadsheet->getSheetByName($sheetName2);
                $tempSheetName = str_replace("/","-",trim($data['delivery_no'][0]));
                $tempSheet->setTitle($tempSheetName.'-'.($index + 1));
                $spreadsheet->addSheet($tempSheet);
                $spreadsheet->setActiveSheetIndexByName($tempSheetName.'-'.($index + 1));
            }
            
            /** Write header information */
            $spreadsheet->getActiveSheet()->setCellValue('J14',($index + 1).'/'.count($dataArr));

            $spreadsheet->getActiveSheet()->setCellValue('A2',$data['header_name']);
            /** Beautiful header Address */
            $beaHeader = preg_split('/\r\n|\r|\n/',$data['header_address']);
            switch(count($beaHeader)){
                case 1:
                    $spreadsheet->getActiveSheet()->setCellValue('A3',$data['header_address']);
                    break;
                case 2:
                    $spreadsheet->getActiveSheet()->setCellValue('A3',$beaHeader[0]);
                    $spreadsheet->getActiveSheet()->setCellValue('A4',$beaHeader[1]);
                    break;
                case 3:
                    $spreadsheet->getActiveSheet()->setCellValue('A3',$beaHeader[0]);
                    $spreadsheet->getActiveSheet()->setCellValue('A4',$beaHeader[1]);
                    $spreadsheet->getActiveSheet()->setCellValue('A5',$beaHeader[2]);
                    break;
                default:
                    $spreadsheet->getActiveSheet()->setCellValue('A3',$data['header_address']);
                    break;

            }
            $spreadsheet->getActiveSheet()->setCellValue('J3',$data['today']);
            $spreadsheet->getActiveSheet()->setCellValue('J4',$data['delivery'][0]['invetory_no']);
            $spreadsheet->getActiveSheet()->setCellValue('C6',$data['consigned_name']);
            $spreadsheet->getActiveSheet()->setCellValue('D7',$data['contract_no_pl']);
            $spreadsheet->getActiveSheet()->setCellValue('D8',$data['invoice_no_excel']);
            $spreadsheet->getActiveSheet()->setCellValue('C9',$reference);
            $spreadsheet->getActiveSheet()->setCellValue('J5',$data['red_invoice_no_excel']);
            $spreadsheet->getActiveSheet()->setCellValue('H7', implode('\n',$data['case_mark']));
            /** Write item */
            $spreadsheet->getActiveSheet()->fromArray($iteArr, NULL, "A17");
        }
    }

    /**
     * Type Two : Mult DVT
     */
    public function exportInventoryVoucherTypeTwo($spreadsheet, $data){
        $sheetName1 = "Sheet 1";
        $sheetName2 = "Sheet 2";
        $sheetName3 = "Sheet 3";
        $sheetCover = "Cover";
        $reference = "";
        /** Cover */
        $path = sys_get_temp_dir();
        $dataArr = [];
        $spreadsheet->setActiveSheetIndexByName($sheetCover);
        /** Write header */
        $spreadsheet->getActiveSheet()->setCellValue('A2',$data['header_name']);
        $beaHeader = preg_split('/\r\n|\r|\n/',$data['header_address']);
        switch(count($beaHeader)){
            case 1:
                $spreadsheet->getActiveSheet()->setCellValue('A3',$data['header_address']);
                break;
            case 2:
                $spreadsheet->getActiveSheet()->setCellValue('A3',$beaHeader[0]);
                $spreadsheet->getActiveSheet()->setCellValue('A4',$beaHeader[1]);
                break;
            case 3:
                $spreadsheet->getActiveSheet()->setCellValue('A3',$beaHeader[0]);
                $spreadsheet->getActiveSheet()->setCellValue('A4',$beaHeader[1]);
                $spreadsheet->getActiveSheet()->setCellValue('A5',$beaHeader[2]);
                break;
            default:
                $spreadsheet->getActiveSheet()->setCellValue('A3',$data['header_address']);
                break;

        }
        $spreadsheet->getActiveSheet()->setCellValue('N3',$data['today']);
        $spreadsheet->getActiveSheet()->setCellValue('D6',$data['consigned_name']);
        $spreadsheet->getActiveSheet()->setCellValue('D9',implode(",",array_map('trim',$data['delivery_no'])));
        $spreadsheet->getActiveSheet()->setCellValue('F7',$data['contract_no_pl']);
        $spreadsheet->getActiveSheet()->setCellValue('F8',$data['invoice_no_excel']);
        $spreadsheet->getActiveSheet()->setCellValue('N5',$data['red_invoice_no_excel']);
        /** Total */
        $sumArr = array('M'=>0,'PCS'=>0,'ROLL'=>0,'CONE'=>0);
        foreach($data['items'] as $index=> $item){
            if(isset( $sumArr[$item['unit']])){
                $sumArr[$item['unit']] += $item['quantity'];
            }
        }
        $spreadsheet->getActiveSheet()->setCellValue('H54',$sumArr['M'].' M');
        $spreadsheet->getActiveSheet()->setCellValue('H55',$sumArr['PCS'].' PCS');
        $spreadsheet->getActiveSheet()->setCellValue('H56',$sumArr['ROLL'].' ROLL');
        $spreadsheet->getActiveSheet()->setCellValue('H57',$sumArr['CONE'].' CONE');

        $spreadsheet->getActiveSheet()->setCellValue('L54',$data['total_netwt'].' NETWT');
        $spreadsheet->getActiveSheet()->setCellValue('L55',$data['total_grosswt'].' GROSSWT');
        $spreadsheet->getActiveSheet()->setCellValue('L56',$data['total_measurem'].' MEASUREM');
        $spreadsheet->getActiveSheet()->setCellValue('L57',$data['total_package'].' PACKAGES');
        
        /** Write case mark
         *  Each case mark is written in a difference block, and I can not determite location of each block
         *  So i create an array for location of each block.
         *  if case mark count > 9 we must copy this sheet and write on it
         */
        $blockLoc = array('B24','G24','L24','B34', 'G34', 'L34', 'B44' , 'G44', 'L44');
        foreach($data['case_mark'] as $index=>$case){
            $spreadsheet->getActiveSheet()->setCellValue($blockLoc[$index], $case);
        }
        
        /** Details */
        foreach($data['delivery'] as $deIndex => $delivery){
            /** I store item in each delivery in a pack
             *  so i have to add it together in an array
             */
            $itemArr = [];
            foreach($delivery['pack'] as $pack){
                foreach ($pack['items'] as $item) {
                    $existFlg = false;
                    foreach ($itemArr as &$tempItem) {
                        $sizeColor1 = substr($item['size_color'], 0, strrpos($item['size_color'], "---"));
                        $sizeColor2 = substr($tempItem['size_color'], 0, strrpos($tempItem['size_color'], "---"));
                        if($item['item_code'] == $tempItem['item_code'] && $sizeColor1 == $sizeColor2) {
                            $existFlg = true;
                            $quantity1 = (int)substr($item['size_color'], strrpos($item['size_color'], "---") + 3);
                            $quantity2 = (int)substr($tempItem['size_color'], strrpos($tempItem['size_color'], "---") + 3);
                            $tempItem['size_color'] = $sizeColor1."---".($quantity1 + $quantity2);
                        }
                    }
                    if(!$existFlg){
                        array_push($itemArr, $item);
                    }
                }
            }
            $sumArr = array('M'=>0,'PCS'=>0,'ROLL'=>0,'CONE'=>0);
            $dataArr = array();
            $numberOfItem = 0;
            foreach ($itemArr as $item) { 
                $numberOfItem ++;
                $sCl = array_map("trim", explode("---",$item["size_color"]));
                $tmp = array(
                    $numberOfItem,
                    $item["item_code"],
                    $item["item_name"],
                    null,
                    $sCl[0],
                    $item["size_unit"],
                    $sCl[1],
                    $item["unit"],
                    $sCl[2]
                );
                /** Calculate Sum */
                if(isset( $sumArr[$item['unit']])){
                    $sumArr[$item['unit']] += $item['quantity'];
                }
                array_push($dataArr, $tmp);
            }
            
            /**
             * Sort array of data by item_code
             */
            // $dataArr = usort($dataArr, function($a, $b){
            //     return strcmp($a[2], $b[2]);}
            // );

            /** 
             * Do some thing like write in type one
             */
            $dataArr = array_chunk($dataArr, 45);
            foreach($dataArr as $index => $iteArr){
                /** clone new sheet */
                if(count($dataArr)- 1 == $index){
                    /** 
                     * Clone new sheet and add it worksheet which position 
                     *  dependent on delivery index and page index 
                     */
                    $tempSheet = clone $spreadsheet->getSheetByName($sheetName1);
                    $tempSheetName = str_replace("/","-",trim($delivery['delivery_no']));
                    $reference = $tempSheetName;
                    $tempSheet->setTitle($tempSheetName.'-'.($index + 1));
                    $spreadsheet->addSheet($tempSheet);
                    $spreadsheet->setActiveSheetIndexByName($tempSheetName.'-'.($index + 1));

                    /** Total */
                    $spreadsheet->getActiveSheet()->setCellValue('D61',$sumArr['M'].' M');
                    $spreadsheet->getActiveSheet()->setCellValue('D62',$sumArr['PCS'].' PCS');
                    $spreadsheet->getActiveSheet()->setCellValue('D63',$sumArr['ROLL'].' ROLL');
                    $spreadsheet->getActiveSheet()->setCellValue('D64',$sumArr['CONE'].' CONE');

                    $spreadsheet->getActiveSheet()->setCellValue('H61',$delivery['total_netwt'].' NETWT');
                    $spreadsheet->getActiveSheet()->setCellValue('H62',$delivery['total_grosswt'].' GROSSWT');
                    $spreadsheet->getActiveSheet()->setCellValue('H63',$delivery['total_measurem'].' MEASUREM');
                    $spreadsheet->getActiveSheet()->setCellValue('H64',$delivery['total_package'].' PACKAGES');
                }else{
                    $tempSheet = clone $spreadsheet->getSheetByName($sheetName2);
                    $tempSheetName = str_replace("/","-",trim($delivery['delivery_no']));
                    $reference = $tempSheetName;
                    $tempSheet->setTitle($tempSheetName.'-'.($index + 1));
                    $spreadsheet->addSheet($tempSheet);
                    $spreadsheet->setActiveSheetIndexByName($tempSheetName.'-'.($index + 1));
                }
                
                /** Write header information */
                /** Page */
                $spreadsheet->getActiveSheet()->setCellValue('J14',($index + 1).'/'.count($dataArr));

                $spreadsheet->getActiveSheet()->setCellValue('A2',$data['header_name']);
                 /** Beautiful header Address */
                $beaHeader = preg_split('/\r\n|\r|\n/',$data['header_address']);
                switch(count($beaHeader)){
                    case 1:
                        $spreadsheet->getActiveSheet()->setCellValue('A3',$data['header_address']);
                        break;
                    case 2:
                        $spreadsheet->getActiveSheet()->setCellValue('A3',$beaHeader[0]);
                        $spreadsheet->getActiveSheet()->setCellValue('A4',$beaHeader[1]);
                        break;
                    case 3:
                        $spreadsheet->getActiveSheet()->setCellValue('A3',$beaHeader[0]);
                        $spreadsheet->getActiveSheet()->setCellValue('A4',$beaHeader[1]);
                        $spreadsheet->getActiveSheet()->setCellValue('A5',$beaHeader[2]);
                        break;
                    default:
                        $spreadsheet->getActiveSheet()->setCellValue('A3',$data['header_address']);
                        break;

                }
                $spreadsheet->getActiveSheet()->setCellValue('J3',$data['today']);
                $spreadsheet->getActiveSheet()->setCellValue('J4',$delivery['invetory_no']);
                $spreadsheet->getActiveSheet()->setCellValue('J5',$data['red_invoice_no_excel']);
                $spreadsheet->getActiveSheet()->setCellValue('C6',$data['consigned_name']);
                $spreadsheet->getActiveSheet()->setCellValue('C9',$reference);
                $spreadsheet->getActiveSheet()->setCellValue('D7',$data['contract_no_pl']);
                $spreadsheet->getActiveSheet()->setCellValue('D8',$data['invoice_no_excel']);
                /** Write case mark */
                $spreadsheet->getActiveSheet()->setCellValue('H7',implode('\n',$delivery['case_mark']));
                
                /** Write item */
                $spreadsheet->getActiveSheet()->fromArray($iteArr, NULL, "A17");
            }
        }
    }
    public function loadINVPL(){
        if ($this->is_logged_in()) {
            $results = $this->dvt_model->getAllDVTToPrint($this->input->post('start'),$this->input->post('length'),$recordsFiltered, $recordsTotal);
            foreach($results as &$res){
                $count = 0;
                foreach($results as $temp){
                    if($res['delivery_no'] == $temp['delivery_no'] && $res['order_date'] == $temp['order_date']){
                        $count++;
                    }
                }
                $res['count'] = $count;
            }
            echo json_encode(array(
                'data' => $results,
                'recordsTotal' => $recordsFiltered,
                'recordsFiltered' => $recordsTotal,
                'draw' => $this->input->post('draw')
            ));
        }
    }
    public function exportpdf()
    {
        $showHtml = false; // set true when coding
        if ($this->is_logged_in()) {
            $this->load->helper(array('dompdf', 'file'));
            // TODO: set data when render template
            if ($showHtml) {
                $this->load->view('packing_list/export', $this->data);
            } else {
                $html = $this->load->view('packing_list/export', $this->data, true);
                pdf_create($html, 'export');
            }
        }
    }
    public function SumSalesPrintWord()
    {
        $params = $this->input->post();
        $params['sales_rate'] = $params['sales_rate'] == "" ? 1 : $params['sales_rate'];
        $params['sales_rate_jpy'] = $params['sales_rate_jpy'] == "" ? 1 : $params['sales_rate_jpy'];
        $params['sales_rate_jpy_usd'] = $params['sales_rate_jpy_usd'] == "" ? 1 : $params['sales_rate_jpy_usd'];
        $contractNo = $params['contract_no'].'-'.$params['contract_no_1'].'-'.$params['contract_no_2'].'-'.$params['contract_no_3'].$params['contract_no_4'];
        $contractNo .= '/'.$params['contract_no_5'];
        if(!empty($params['contract_no_6'])){
            $contractNo .= '('.$params['contract_no_6'].')';
        }
        // get all items in DVT
         // Lấy danh sách dvt 
         $delivery_data = json_decode($params['delivery_data'], true);
         $po_dvt_add = json_decode($params['po_dvt_selected'], true);
         if(count($po_dvt_add)>0){
            $delivery_data = array_merge($delivery_data, $po_dvt_add);
         }
         $items = array();
         foreach( $delivery_data as $dvt ) {
             $data = array(
                 'dvt_no'=>$dvt['delivery_no'],
                 'order_date'=>$dvt['order_date'],
                 'times'=>$dvt['times']
             );
             if(!empty($dvt['pack_no'])){
                 $data["pack_no"] = $dvt["pack_no"]; 
             }
             $tempItem = $this->dvt_model->getKVTForSalesContract($data);
             $items = array_merge($items, $tempItem);
         }
         // If item same (itemcode, size, color), sum quantity and delete
         $tmpItem = [];
         foreach($items as $item){
            $itemCode = $item['item_code'];
            $size = $item['size'];
            $color = $item['color'];
            $key = $itemCode.$size.$color;
            if(!array_key_exists($key, $tmpItem)){
                $tmpItem[$key] = $item;
            }else{
                $tmpItem[$key]["quantity"] +=  $item["quantity"];
                $tmpItem[$key]["amount"] +=  $item["amount"];
            }
         }
         $items = array_values($tmpItem);
        $outputCurrency = trim($params['sales_output_currency']);
        $salesCurrency = trim($params['sales_currency']);

        if($outputCurrency != '' && $outputCurrency != $salesCurrency){
            foreach($items as $index => $item){
                $items[$index]['currency'] = $outputCurrency;
                $itemCurrency = isset($item['currency']) ? trim($item['currency']) : '';
                $items[$index]['sell_price'] = ($item['sell_price'] == null || $item['sell_price'] == "") ? 0 : $item['sell_price'];
                if ($itemCurrency !== '') {
                    if ($outputCurrency == 'USD') {
                        if ($itemCurrency == 'VND') {
                            $items[$index]['sell_price'] /= $params["sales_rate"];
                        } else if ($itemCurrency == 'JPY') {
                            $items[$index]['sell_price'] *= $params["sales_rate_jpy_usd"];
                        } 
                    } else if ($outputCurrency == 'VND') {
                        if ($itemCurrency == 'USD') {
                            $items[$index]['sell_price'] *= $params["sales_rate"];
                        } else if ($itemCurrency == 'JPY') {
                            $items[$index]['sell_price'] *= $params["sales_rate_jpy"];
                        } 
                    } else if ($outputCurrency == 'JPY') {
                        if ($itemCurrency == 'USD') {
                            $items[$index]['sell_price'] /= $params["sales_rate_jpy_usd"];
                        } else if ($itemCurrency == 'VND') {
                            $items[$index]['sell_price'] /= $params["sales_rate_jpy"];
                        }
                    }
                    $items[$index]['sell_price'] = (float)(str_replace(",","",parseMoney($items[$index]['sell_price'], $outputCurrency)));
                }
            }
        }
        $contract_date = new DateTime($params['contract_date']);
        $contract_date_en = date_format($contract_date,'F j, Y');
        $contract_date_vn = date_format($contract_date,'\n\g\à\y d \t\h\á\n\g m \n\ă\m Y');
        // $partyB = $this->company_model->getCompanyInfo($params['party_b']);
        // if(sizeof($partyB) > 0){
        //     $partyB = $partyB['0'];
        // }
        // $party_b = $params['party_b'];
        // $party_b_info = $params['party_b_info'];
        $filePath = "views/inv_pl_print/template_sum_sales_word.docx";
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(APPPATH.$filePath);
        // set contract no
        $templateProcessor->setValue('contract_no', $contractNo);
        // set contract date
        $templateProcessor->setValue('contract_date_vn', $contract_date_vn);
        // set contract date
        $templateProcessor->setValue('contract_date_en', $contract_date_en);
        // set party A
        $party_a_post = $params['party_a_info'];
        $party_a_name_vn = "";
        $party_a_name_en = "";
        $party_a_address_vn = "";
        $party_a_address_en = "";
        $party_a_tel = "";
        $party_a_fax = "";
        $party_a_taxcode = "";
        $represented_by = "";
        $position = "";
        if(preg_match("/BÊN A.*:(.*)/i", $party_a_post, $matches)){
            $party_a_name_vn = trim($matches[1]);
        }
        if(preg_match("/PARTY A.*:(.*)/i", $party_a_post, $matches)){
            $party_a_name_en = trim($matches[1]);
        }
        if(preg_match("/Địa chỉ:([\d\D]*)address/i", $party_a_post, $matches)){
            $party_a_address_vn = trim($matches[1]);
        }
        if(preg_match("/Address:([\d\D]*)Điện thoại/i", $party_a_post, $matches)){
            $party_a_address_en = trim($matches[1]);
        }
        if(preg_match("/Tel.*:(.*)/i", $party_a_post, $matches)){
            $party_a_tel = trim($matches[1]);
        }
        if(preg_match("/Fax.*:(.*)/i", $party_a_post, $matches)){
            $party_a_fax = trim($matches[1]);
        }
        if(preg_match("/Represented by.*:(.*)/i", $party_a_post, $matches)){
            $represented_by = trim($matches[1]);
        }
        if(preg_match("/Position.*:(.*)/i", $party_a_post, $matches)){
            $position = trim($matches[1]);
        }
        $templateProcessor->setValue('party_a_name_vn', $party_a_name_vn);
        $templateProcessor->setValue('party_a_name_en', $party_a_name_en);
        $templateProcessor->setValue('party_a_address_vn', preg_replace("/\r\n|\r|\n/","<w:br/>",$party_a_address_vn));
        $templateProcessor->setValue('party_a_address_en', preg_replace("/\r\n|\r|\n/","<w:br/>",$party_a_address_en));
        $templateProcessor->setValue('party_a_tel', $party_a_tel);
        $templateProcessor->setValue('party_a_fax', $party_a_fax);
        $templateProcessor->setValue('party_a_tax_code', $party_a_taxcode);
        $templateProcessor->setValue('party_a_represented_by', $represented_by);
        $templateProcessor->setValue('party_a_position', $position);

        // set party B
        $party_b_post = $params['party_b_info'];
        $party_b_name_vn = "";
        $party_b_name_en = "";
        $party_b_address_vn = "";
        $party_b_address_en = "";
        $party_b_tel = "";
        $party_b_fax = "";
        $party_b_taxcode = "";
        $represented_by = "";
        $position = "";
        if(preg_match("/(.*)/", $party_b_post, $matches)){
            $party_b_name_vn = trim($matches[1]);
        }
        if(preg_match("/\n(.*)/", $party_b_post, $matches)){
            $party_b_name_en = trim($matches[1]);
        }
        if(preg_match("/Địa chỉ:(.*)/i", $party_b_post, $matches)){
            $party_b_address_vn = trim($matches[1]);
        }
        if(preg_match("/Address:(.*)/i", $party_b_post, $matches)){
            $party_b_address_en = trim($matches[1]);
        }
        if(preg_match("/Tel.*:(.*)/i", $party_b_post, $matches)){
            $party_b_tel = trim($matches[1]);
        }
        if(preg_match("/Fax.*:(.*)/i", $party_b_post, $matches)){
            $party_b_fax = trim($matches[1]);
        }
        if(preg_match("/Represented by.*:(.*)/i", $party_b_post, $matches)){
            $represented_by = trim($matches[1]);
        }
        if(preg_match("/Position.*:(.*)/i", $party_b_post, $matches)){
            $position = trim($matches[1]);
        }

        $templateProcessor->setValue('party_b_name_vn', $this->convertSpecialChar($party_b_name_vn));
        $templateProcessor->setValue('party_b_name_en', $this->convertSpecialChar($party_b_name_en));
        $templateProcessor->setValue('party_b_address_vn', $this->convertSpecialChar($party_b_address_vn));
        $templateProcessor->setValue('party_b_address_en', $this->convertSpecialChar($party_b_address_en));
        $templateProcessor->setValue('party_b_tel', $party_b_tel);
        $templateProcessor->setValue('party_b_fax', $party_b_fax);
        $templateProcessor->setValue('party_b_tax_code', $party_b_taxcode);
        $templateProcessor->setValue('party_b_represented_by', $represented_by);
        $templateProcessor->setValue('party_b_position', $position);
        // set party charged
        $templateProcessor->setValue('party_charged_vn', 'Bên '.$params['party_charged']);
        $templateProcessor->setValue('party_charged_en', 'Party '.$params['party_charged']);
        // set payment methods
        $templateProcessor->setValue('payment_method_vn', $this->convertSpecialChar($params['payment_methods_vn']));
        $templateProcessor->setValue('payment_method_en', $this->convertSpecialChar($params['payment_methods']));
        // set bank info
        $bankID = $params['bank_name'];
        $bankInfoList = $this->komoku_model->get_bank_info($bankID);
        $bankInfo = $params['bank_info'];
        if(sizeof($bankInfoList) > 0){
            $bankInfo = $bankInfoList[0]['komoku_name_3'];
        }
        $templateProcessor->setValue('bank_info', preg_replace("/\r\n|\r|\n/","</w:t><w:br/><w:t>",$this->convertSpecialChar($bankInfo)));
        // Set term of delivery
        $partya = $params['party_a'] == "001"?"EPE":"HANOI";
        if($partya === "HANOI"){
            $delivery_proof = "<w:br/>Bằng chứng giao hàng gồm có:
Proof of delivery includes:
-  Hóa đơn VAT hợp lệ, phiếu giao hàng được ký nhận của hai bên.
   Valid  VAT invoice, delivery note to be signed by both parties.<w:br/>";
            $templateProcessor->setValue('delivery_proof', preg_replace("/\r\n|\r|\n/","<w:br/>", $delivery_proof));
        }else{
            $templateProcessor->setValue('delivery_proof',null);
        }
        $templateProcessor->setValue('quantity_odds', $params['quantity_odds']);
        // set payment term
        $tempPaymentTerm= explode(";",$params['payment_term']);
        $templateProcessor->setValue('pament_term_vn', isset($tempPaymentTerm[1]) ? $this->convertSpecialChar($tempPaymentTerm[1]) : '');
        $templateProcessor->setValue('pament_term_en', isset($tempPaymentTerm[0]) ? $this->convertSpecialChar($tempPaymentTerm[0]) : '');
        // set payment currency
        $templateProcessor->setValue('payment_currency', $params['sales_output_currency']);
        
        // set fee term
        $feeTerm_vn = "";
        $feeTerm_en = "";
        $tempFeeTerm = $this->komoku_model->get_fee_term_by_kubun($params['fee_terms']);
        if(preg_match("/[ \t-]*([\d\D]*)/", $tempFeeTerm[0]['komoku_name_3'], $matches)){
            $tempFeeTerm = preg_split("/\r\n|\r|\n/",$matches[1]);
            $feeTerm_vn = $tempFeeTerm[0];
            $feeTerm_en = $tempFeeTerm[1];
        }
        $templateProcessor->setValue('fee_term_vn', $this->convertSpecialChar($feeTerm_vn));
        $templateProcessor->setValue('fee_term_en', $this->convertSpecialChar($feeTerm_en));

        // Set export term
        $export_term = $this->komoku_model->get_export_term_by_party($partya)[0];
        $templateProcessor->setValue('export_term', preg_replace("/\r\n|\n/","<w:br/>",$this->convertSpecialChar($export_term["komoku_name_3"])));

        // set party A name
        // $partyAName = explode(":", $party_a_name_vn);
        $templateProcessor->setValue('party_a_name_vn', $this->convertSpecialChar($party_a_name_vn));
        // set party B name
        $templateProcessor->setValue('party_b_name_vn', $this->convertSpecialChar($party_b_name_vn));
        
        if(isset($params['sign_required']) && $params['sign_required']==="1"){
            // set notify
            $templateProcessor->setValue('notify_header', "BÊN CHỈ ĐỊNH");
            $templateProcessor->setValue('notify', $params['notify']);
            // set consignee
            $templateProcessor->setValue('consignee_header', "BÊN NHẬN HÀNG");
            $templateProcessor->setValue('consignee', $params['consignee']);
        }else{
            $templateProcessor->setValue('notify_header', "");
            $templateProcessor->setValue('consignee_header', "");
            $templateProcessor->setValue('consignee', "");
            $templateProcessor->setValue('notify', "");
        }
        // set table items info
        $templateProcessor->cloneRow('i', sizeof($items));
        $totalNotVAT = 0;
        $totalQuantity = array();
        for($i = 0; $i < sizeof($items); $i++){
            $temp = $items[$i];
            $index = $i+1;
            $templateProcessor->setValue('i#'.$index, $index);
            $templateProcessor->setValue('item_name#'.$index, $this->convertSpecialChar($temp['item_name']));
            $templateProcessor->setValue('col#'.$index, $temp['color']);
            $templateProcessor->setValue('siz#'.$index, $temp['size']);
            $templateProcessor->setValue('unit#'.$index, $temp['unit']);
            $templateProcessor->setValue('quan#'.$index, number_format($temp['quantity']));
            $templateProcessor->setValue('price#'.$index, parseMoney($temp['sell_price'],$outputCurrency));
            $templateProcessor->setValue('total_row#'.$index, parseMoney($temp['sell_price'] * $temp['quantity'], $outputCurrency));
            $totalNotVAT += ($temp['sell_price'] * $temp['quantity']);
            if(array_key_exists($temp['unit'],$totalQuantity)){
                $totalQuantity[$temp['unit']] += (int)$temp['quantity'];
            }else{
                $totalQuantity[$temp['unit']] = (int)$temp['quantity'];
            }
            // $totalQuantity += $temp['quantity'];
        }
        $totalQuan = '';
        $totalUnit = '';
        $numInd = 1;
        foreach ($totalQuantity as $key => $value){
            if($numInd == sizeof($totalQuantity)){
                $totalQuan .= (number_format($value));
                $totalUnit .= ($key);
            }else{
                $totalQuan .= (number_format($value).'</w:t><w:br/><w:t>');
                $totalUnit .= ($key.'</w:t><w:br/><w:t>');
            }
            $numInd++;
        }
        // Set VAT
        $vat = $params["tax_sales"]==="001"?"10%":"0%";
        $templateProcessor->setValue('tong_chua_vat', "TỔNG ( Chưa có VAT $vat )");
        $totalVAT = 0;
        if($vat === "10%"){
            $totalVAT = $totalNotVAT/10;
        }
        $total = $totalNotVAT + $totalVAT;
        $templateProcessor->setValue('total_not_vat', parseMoney($totalNotVAT,$outputCurrency));
        $templateProcessor->setValue('total _vat', parseMoney($totalVAT,$outputCurrency));
        $templateProcessor->setValue('total', parseMoney($total,$outputCurrency));
        $templateProcessor->setValue('t_quan', $totalQuan);
        $templateProcessor->setValue('uni', sizeof($totalQuantity) > 1 ? '' : '('.$items[0]['unit'].')');
        $templateProcessor->setValue('s_uni', $totalUnit);
        $templateProcessor->setValue('cur', $outputCurrency);
        $templateProcessor->setValue('vat', $vat);
         // set amount character
         $currencyChar = 'đồng.';
         if($items[0]['currency'] == 'USD'){
            $currencyChar = 'đô la Mỹ.';
         }else if($items[0]['currency'] == 'JPY'){
            $currencyChar = 'yên.';
         }
         $templateProcessor->setValue('total_vn', ucfirst(convert_number_to_words(number_format($total,2), 'vn').' '.$currencyChar));
         if($outputCurrency == 'USD') {
            $templateProcessor->setValue('total_en', makewords($total).' .');
         } else {
            $templateProcessor->setValue('total_en', ucfirst(convert_number_to_words(number_format($total,2), 'en').' '.($outputCurrency.'.')));
         }
        // save sales contract info into t_contract_print table in database
         if(sizeof($listKey) >= 1){
            $packNo = explode('*', $params['pack_no']);
            foreach ($listKey as $idx => $key){
                $dvtKey = explode(";",$key);
                $currentUser = $this->session->userdata('user');
                $data = array(
                    'contract_no'       => $contractNo,
                    'kubun'             => '2003',
                    'delivery_no'       => $dvtKey[0],
                    'times'             => $dvtKey[2],
                    'pack_no'           => 0,
                    'contract_date'     => $params['contract_date'],
                    'delivery_date'     => $dvtKey[1],
                    'bank'              => $params['bank_name'],
                    'party_a'           => $params['party_a'],
                    'party_b'           => $params['party_b'],
                    'notify'            => $params['notify'],
                    'consignee'         => $params['consignee'],
                    'party_charged'     => $params['party_charged'],
                    'signature'         => isset($params['sign_required']) ? true : false,
                    'payment_currency'  => $params['sales_output_currency'],
                    'payment_methods'   => $params['payment_methods'],
                    'payment_term'      => $params['payment_term'],
                    'fee_terms'         => $params['fee_terms'],
                    'rate'              => $params['sales_rate'],
                    'rate_jpy'          => $params['sales_rate_jpy'],
                    'rate_jpy_usd'      => $params['sales_rate_jpy_usd'],
                    'quantity_odds'     => $params['quantity_odds'],
                    'create_user'       => $currentUser['employee_id'],
                    'create_date'       => date('Y/m/d H:i:s'),
                    'edit_user'         => $currentUser['employee_id'],
                    'edit_date'         => date('Y/m/d H:i:s'),
                    'del_flg'           => '0',
                );
                $this->print_contract_model->saveContractPrint($data);
            }
        }
        $tmp = $templateProcessor->save();
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment;filename='Contract_$contractNo.docx'");
        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        ob_clean();
        flush();
        readfile($tmp);
        unlink($tmp);
    }
    // Create by: Thanh
    public function HDNTPrintWord()
    {
        $params = $this->input->post();

        $contractNo = $params['principal_contract_no'].'-'.$params['principal_1'].'-'.$params['principal_2'].$params['principal_3'];
        $contractNo .= '/'.$params['principal_4'];
        if(!empty($params['principal_5'])){
            $contractNo .= '('.$params['principal_5'].')';
        }
        $currentUser = $this->session->userdata('user');

        $dvtKey = $params['dvt_key'];
        $listKey = explode("*", $dvtKey);

        $today = new DateTime();
        $year = date_format($today, 'y');
        $contract_date = new DateTime($params['contract_date_principal']);
        $contract_date_en = date_format($contract_date,'F j, Y');
        $contract_date_vn = date_format($contract_date,'\n\g\à\y d \t\h\á\n\g m \n\ă\m Y');
        $end_date = new DateTime($params['end_date_principal']);
        $end_date_vn = date_format($end_date,'d/m/Y');
        $end_date_en = date_format($end_date,'F j, Y');
        
        $filePath = "views/inv_pl_print/template_cont_word.docx";
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(APPPATH.$filePath);
        
        $templateProcessor->setValue('contract_no', $contractNo);

        $party_a = array();
        $party_a = preg_split('/\r\n|\r|\n/', $params['party_a_info']);
        $party_b = array();
        $party_b = preg_split('/\r\n|\r|\n/', $params['party_b_info']);

        // Write contract date
        $templateProcessor->setValue('contract_date_en', $contract_date_en);
        $templateProcessor->setValue('contract_date_vn', $contract_date_vn);

        // Write party a information
        if(isset($party_a[0])) {
            $party_a_name_en = explode(":", $party_a[0]);
            if(isset($party_a_name_en[1])) {
                $templateProcessor->setValue('party_a_name_en', trim($party_a_name_en[1]));
            }
        }

        if(isset($party_a[1])) {
            $party_a_name_vn = explode(":", $party_a[1]);
            if(isset($party_a_name_vn[1])) {
                $templateProcessor->setValue('party_a_name_vn',  trim($party_a_name_vn[1]));
            }
        }
        $address_en = "";
        $address_vn = "";
        $tel = "";
        $fax = "";
        $tax_code = "";
        $represented = "";
        $position = "";
        if(preg_match("/address.*:([\s\S]*)Điện thoại/i", $params['party_a_info'], $match)){
            $address_en = trim($match[1]);
            $address_en = preg_replace("/\r\n|\n|\r/", "<w:br />", $address_en);
        }
        if(preg_match("/Địa chỉ.*:([\s\S]*)address/i", $params['party_a_info'], $match)){
            $address_vn = trim($match[1]);
            $address_vn = preg_replace("/\r\n|\n|\r/", "<w:br />", $address_vn);
        }
        if(preg_match("/tel.*:(.*)/i", $params['party_a_info'], $match)){
            $tel = trim($match[1]);
        }
        
        if(preg_match("/fax.*:(.*)/i", $params['party_a_info'], $match)){
            $fax = trim($match[1]);
        }
        
        if(preg_match("/Tax code.*:(.*)/i", $params['party_a_info'], $match)){
            $tax_code = trim($match[1]);
        }
        
        if(preg_match("/represented.*:(.*)/i", $params['party_a_info'], $match)){
            $represented = trim($match[1]);
        }
        
        if(preg_match("/position.*:(.*)/i", $params['party_a_info'], $match)){
            $position = trim($match[1]);
        }
        $templateProcessor->setValue('address_a_en', $address_en, true);
        $templateProcessor->setValue('address_a_vn', $address_vn, true);
        $templateProcessor->setValue('tel_a', $tel);
        $templateProcessor->setValue('fax_a', $fax);
        $templateProcessor->setValue('tax_code_a', $tax_code);
        $templateProcessor->setValue('represented_by_a', $represented);
        $templateProcessor->setValue('position_a', $position);
        
        // Write party b information
        $templateProcessor->setValue('party_b_name_en', $params['company_name_b']);
        $templateProcessor->setValue('party_b_name_vn', $party_b[0]);
        $templateProcessor->setValue('address_b', $params['address_b']);
        $templateProcessor->setValue('tel_b', $params['tel_b']);
        $templateProcessor->setValue('fax_b', $params['fax_b']);
        $templateProcessor->setValue('represented_by_b', $params['represented_by_b']);
        $templateProcessor->setValue('position_b', $params['position_b']);

        // Write sign
        if(isset($params['sign']) && $params['sign'] == '1') {
            $templateProcessor->setValue('sign', '(mà không cần ký tên)<w:t xml:space="preserve"> </w:t>');
            $templateProcessor->setValue('sign_en', '(even without signature)<w:t xml:space="preserve"> </w:t>');
        } else {
            $templateProcessor->setValue('sign', '');
            $templateProcessor->setValue('sign_en', '');
        }

        // Write term of delivery(điều kiện giao hàng)
        $templateProcessor->setValue('term_of_delivery_vn', $params['delivery_condition_principal']);
        $templateProcessor->setValue('term_of_delivery_en', $params['delivery_condition_principal']);
        
        // Write party charged, payment method, currency
        $templateProcessor->setValue('party_charged', $params['party_charged_principal']);
        $templateProcessor->setValue('payment_methods', $params['payment_methods_principal']);   
        $templateProcessor->setValue('payment_currency', $params['payment_currency']);

        // write bank information
        $bank_info = array();
        $bank_info = preg_split('/\r\n|\r|\n/', $params['bank_info']);
        if(isset($bank_info[0])) {
            $templateProcessor->setValue('beneficiary_name', $bank_info[0]);
        } else {
            $templateProcessor->setValue('beneficiary_name', '');
        }  
        if(isset($bank_info[1])) {
            $templateProcessor->setValue('account_no', $bank_info[1]);
        } else {
            $templateProcessor->setValue('account_no', '');
        }   
        if(isset($bank_info[2])) {
            $templateProcessor->setValue('bank_name', $bank_info[2]);
        } else {
            $templateProcessor->setValue('bank_name', '');
        }   
        if(isset($bank_info[3])) {
            $templateProcessor->setValue('bank_address', $bank_info[3]);
        } else {
            $templateProcessor->setValue('bank_address', '');
        }
        if(isset($bank_info[4])) {
            $templateProcessor->setValue('swift_code', $bank_info[4]);
        } else {
            $templateProcessor->setValue('swift_code', '');
        }
        
        // Write payment term(hạn thanh toán)
        $payment_term = array();
        $payment_term = explode(";", $params['payment_term_principal']);
        $payment_term_en = "";
        $payment_term_vn = "";
        if(isset($payment_term[0])) {
            $payment_term_en = strtolower($payment_term[0]);
        }
        if(isset($payment_term[1])) {
            $payment_term_vn = strtolower($payment_term[1]);
        }
        $templateProcessor->setValue('payment_term_vn', $payment_term_vn);
        $templateProcessor->setValue('payment_term_en', $payment_term_en);

        // Write phí điều khoản
        $tempFeeTerm = $this->komoku_model->get_fee_term_by_kubun($params['fee_terms_principal']);
        $tempFeeTerm = preg_split('/\r\n|\r|\n/', $tempFeeTerm[0]['komoku_name_3']);
        $fee_terms_vn = "";
        $fee_terms_en = "";
        if(isset($tempFeeTerm[0])){
            $fee_terms_vn = trim($tempFeeTerm[0], "- ");
        }
        if(isset($tempFeeTerm[1])){
            $fee_terms_en = "<w:br />".trim($tempFeeTerm[1], "- ")."<w:br />";
        }
        $templateProcessor->setValue('fee_terms_vn', $fee_terms_vn);
        $templateProcessor->setValue('fee_terms_en', $fee_terms_en);

        // write điều khoản quá hạn
        if(isset($params['terms_overdue']) && $params['terms_overdue'] == '1') {
            $templateProcessor->setValue('terms_overdue_vn', '<w:br />Nếu Bên B thanh toán trễ hạn quá 3 ngày so với hạn thanh toán, bên B sẽ phải chịu phạt 3% trên tổng giá trị của hóa đơn cho mỗi ngày trễ hạn.');
            $templateProcessor->setValue('terms_overdue_en', '<w:br />In case of overdue payment more than 3 days compared to payment date, Party B has to pay 3% of red invoice  value for each day of delay.<w:br />');
        } else {
            $templateProcessor->setValue('terms_overdue_vn', '');
            $templateProcessor->setValue('terms_overdue_en', '');
        }

        // write fee_terms_party ngay phia truoc dieu 5: trach nhiem cua cac ben. Neu party b la shimada japan
        if(isset($params['party_b']) && $params['party_b'] === 'SHIMADA SHOJI CO., LTD') {
            $fee_terms_party_b_vn = "<w:br />Phí thông quan xuất, phí vận chuyển,"
                . " phí chứng từ và các phí khác phát sinh từ Bên A (nếu có) sẽ do Bên B chịu."
                . " Bên A sẽ trả các phí trên tại thời điểm phát sinh thay Bên B,"
                . " sau đó Bên A sẽ phát hành công nợ cho Bên B."
                . " Bên B sẽ trả đầy đủ cho bên A vào ngày 20 của tháng sau.";
            $fee_terms_party_b_en = "<w:br />Export custom clearance fees, transportation fees,"
                . " documentation fees and other fees of Party A (if any) whose fees shall be borne by Party B."
                . " Party A pay the above fees for party B at the accrued time"
                . " and then the Party A will issue a debit note to Party B."
                . " Party B will pay in full to the Party A on 20th the following next month.<w:br />";
            $templateProcessor->setValue('fee_terms_party_b_vn', $fee_terms_party_b_vn);
            $templateProcessor->setValue('fee_terms_party_b_en', $fee_terms_party_b_en);
        } else {
            $templateProcessor->setValue('fee_terms_party_b_vn', '');
            $templateProcessor->setValue('fee_terms_party_b_en', '');
        }

        // Write số ngày phản hồi
        $templateProcessor->setValue('feedback_within', $params['feedback_within']);

        // Write chữ ký scan
        if(isset($params['scan_sign']) && $params['scan_sign'] == '1') {
            $templateProcessor->setValue('scan_sign_vn', '<w:br />Bên B cho phép sử dụng chữ ký scan trên Đơn hàng<w:br />');
            $templateProcessor->setValue('scan_sign_en', 'Party B allows to use of scan signature in Purchase Order<w:br />');
        } else {
            $templateProcessor->setValue('scan_sign_vn', '');
            $templateProcessor->setValue('scan_sign_en', '');
        }

        // Write ngày hết hạn hợp đồng
        $templateProcessor->setValue('end_date_vn', $end_date_vn);
        $templateProcessor->setValue('end_date_en', $end_date_en);

        // Write chu ky
        if(isset($party_a_name_en[1])) {
            $templateProcessor->setValue('party_a', $party_a_name_en[1]);
        } else {
            $templateProcessor->setValue('party_a', '');
        }
        $templateProcessor->setValue('party_b', $party_b[0]);
        if(sizeof($listKey) >= 1) {
            $packNo = explode('*', $params['pack_no']);
            foreach ($listKey as $idx => $key){
                $dvtKey = explode(";",$key);
                $params['delivery_no'] = $dvtKey[0];
                $params['delivery_date'] = $dvtKey[1];
                $params['times'] = $dvtKey[2];
                // Set data to insert
                $dataInsertHDNT = array(
                    // "type"                      => $params['type'],
                    "kubun"                     => '2001',
                    "contract_no"               => $contractNo,
                    "contract_date"             => $params['contract_date_principal'],
                    "delivery_no"               => $params['delivery_no'],
                    "times"                     => $params['times'],
                    "pack_no"                   => 0,
                    "delivery_date"             => $params['delivery_date'],
                    "end_date"                  => $params['end_date_principal'],
                    "party_a"                   => $params['party_a'],
                    "party_b"                   => $params['party_b'],
                    "bank"                      => $params['bank_name'],
                    "delivery_condition"        => $params['delivery_condition_principal'],
                    "party_charged"             => $params['party_charged_principal'],
                    "payment_currency"          => $params['payment_currency'],
                    "payment_methods"           => $params['payment_methods_principal'],
                    "payment_term"              => $params['payment_term_principal'],
                    "feedback_day_num"          => $params['feedback_within'],
                    "fee_terms"                 => $params['fee_terms_principal'],
                    'create_user'               => $currentUser['employee_id'],
                    'create_date'               => date('Y/m/d H:i:s'),
                    'edit_user'                 => $currentUser['employee_id'],
                    'edit_date'                 => date('Y/m/d H:i:s'),
                );

                if(isset($params['sign'])) {
                    $dataInsertHDNT['signature'] = TRUE;
                } else {
                    $dataInsertHDNT['signature'] = FALSE;
                }
                if(isset($params['scan_sign'])) {
                    $dataInsertHDNT['scan_signature'] = TRUE;
                } else {
                    $dataInsertHDNT['scan_signature'] = FALSE;
                }
                if(isset($params['terms_overdue'])) {
                    $dataInsertHDNT['terms_overdue'] = TRUE;
                } else {
                    $dataInsertHDNT['terms_overdue'] = FALSE;
                }

                foreach ($dataInsertHDNT as $key => $value) {
                    if($value === '' || $value === null) {
                        unset($dataInsertHDNT[$key]);
                    }
                }
                // THIS IS saveHDNTWord
                $this->print_contract_model->saveContractPrint($dataInsertHDNT);
            }
        }

        $tmp = $templateProcessor->save();
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment;filename='Contract_$contractNo.docx'");
        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        ob_clean();
        flush();
        readfile($tmp);
        unlink($tmp);
    }

    // Create by: Thanh
    function agreementPrintExcel()
    {
        $params = $this->input->post();
        $agreementContractNo = $params['agreement_contract_no'].'-'.$params['agreement_1'].'-'.$params['agreement_2'].$params['agreement_3'];
        $agreementContractNo .= '/'.$params['agreement_4'];

        $currentUser = $this->session->userdata('user');
        $filePath = "views/inv_pl_print/template_agreement_print.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);

        // set data
        $contract_date = new DateTime($params['contract_date_ae_agreement']);
        $end_date = new DateTime($params['end_date_ae']);
        $contract_date_en = date_format($contract_date,"d-M-y");
        $end_date_en = date_format($end_date,"d-M-Y");
        $end_date_vn = date_format($end_date,"d/m/Y");

        // Write mã hợp đồng, ngày làm hợp đồng
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setCellValue('J4', $agreementContractNo);
        $spreadsheet->getActiveSheet()->setCellValue('J5', $contract_date_en);

        // Write infor bên A
        $party_a = array();
        $partya_name_en = "";
        $partya_name_vn = "";
        $address_en = "";
        $address_vn = "";
        $tel = "";
        $fax = "";
        $tax_code = "";
        $represented = "";
        $position = "";
        if(!empty($params['party_a_info'])){
            $party_a = preg_split('/\r\n|\r|\n/', $params['party_a_info']);
            $partya_name_en = $party_a[0];
            $partya_name_vn = $party_a[1];
            
            if(preg_match("/address.*:(.*)/i", $params['party_a_info'], $match)){
                $address_en = trim($match[1]);
            }
            if(preg_match("/Địa chỉ.*:(.*)/i", $params['party_a_info'], $match)){
                $address_vn = trim($match[1]);
            }
            if(preg_match("/tel.*:(.*)/i", $params['party_a_info'], $match)){
                $tel = trim($match[1]);
            }
            
            if(preg_match("/fax.*:(.*)/i", $params['party_a_info'], $match)){
                $fax = trim($match[1]);
            }
            
            if(preg_match("/Tax code.*:(.*)/i", $params['party_a_info'], $match)){
                $tax_code = trim($match[1]);
            }
            
            if(preg_match("/represented.*:(.*)/i", $params['party_a_info'], $match)){
                $represented = trim($match[1]);
            }
            
            if(preg_match("/position.*:(.*)/i", $params['party_a_info'], $match)){
                $position = trim($match[1]);
            }
            $spreadsheet->getActiveSheet()->setCellValue('A7', $partya_name_en);
            $spreadsheet->getActiveSheet()->setCellValue('A8', $partya_name_vn);
            $spreadsheet->getActiveSheet()->setCellValue('B9', $address_en);
            $spreadsheet->getActiveSheet()->setCellValue('B11', $address_vn);
            $spreadsheet->getActiveSheet()->setCellValue('B13', ":".$tel);
            $spreadsheet->getActiveSheet()->setCellValue('B14', ":".$fax);
            $spreadsheet->getActiveSheet()->setCellValue('B15', ":".$tax_code);
            $spreadsheet->getActiveSheet()->setCellValue('B16', ":".$represented);
            $spreadsheet->getActiveSheet()->setCellValue('B17', ":".$position);            
        }
        

        // Write infor bên B
        $address_en = "";
        $address_vn = "";
        $tel = "";
        $fax = "";
        $tax_code = "";
        $represented = "";
        $position = "";
        if(!empty($params['company_name_b']) && !empty($params['party_b_info'])){
            $spreadsheet->getActiveSheet()->setCellValue('A19', "PARTY B or THE SELLER:  ".$params['company_name_b']);
            $spreadsheet->getActiveSheet()->setCellValue('A20', "BÊN B hoặc BÊN BÁN:  ".$params['company_name_b']);
            if(preg_match("/address.*:(.*)/i", $params['party_b_info'], $match)){
                $address_vn = trim($match[1]);
            }
            if(preg_match("/tel.*:(.*)/i", $params['party_b_info'], $match)){
                $tel = trim($match[1]);
            }
            
            if(preg_match("/fax.*:(.*)/i", $params['party_b_info'], $match)){
                $fax = trim($match[1]);
            }
            if(preg_match("/represented.*:(.*)/i", $params['party_b_info'], $match)){
                $represented = trim($match[1]);
            }
            
            if(preg_match("/position.*:(.*)/i", $params['party_b_info'], $match)){
                $position = trim($match[1]);
            }
            $spreadsheet->getActiveSheet()->setCellValue('B21', ":".$address_vn);
            $spreadsheet->getActiveSheet()->setCellValue('B22', ":".$address_vn);
            $spreadsheet->getActiveSheet()->setCellValue('B23', ":".$tel);
            $spreadsheet->getActiveSheet()->setCellValue('B24', ":".$fax);
            $spreadsheet->getActiveSheet()->setCellValue('B25', ":".$represented);
            $spreadsheet->getActiveSheet()->setCellValue('B26', ":".$position);
        }

        // Write ngày hiệu lực
        $partya_name = "";
        if(preg_match("/.*:(.*)/i", $partya_name_en, $match)){
            $partya_name = trim($match[1]);
        }
        $spreadsheet->getActiveSheet()->setCellValue('A50', "This general agreement takes effect from the date of signing to ".$end_date_en.".");
        $spreadsheet->getActiveSheet()->setCellValue('A51', "Thỏa thuận chung này có hiệu lực kể từ ngày ký đến hết ngày ".$end_date_vn.".");
        $spreadsheet->getActiveSheet()->setCellValue('A56', $partya_name);
        $spreadsheet->getActiveSheet()->setCellValue('H56', $params['company_name_b']);

        // Save data to contract_print
        $agreementNo = $params['agreement_contract_no'].'-'.$params['agreement_1'].'-'.$params['agreement_2'].$params['agreement_3'];
        $agreementNo .= '/'.$params['agreement_4'];
        $dataInsertAgreement = array(
            // "type"                      => $params['type'],
            // "eachtime_no_old"           => $params['agreement_no_old'],
            "contract_no"               => $agreementNo,
            "kubun"                     => '2004',
            "delivery_no"               => '',
            "times"                     => 1,
            "delivery_date"             => date('Y/m/d H:i:s'),
            "pack_no"                   => 0,
            "contract_date"             => $params['contract_date_ae_agreement'],
            "end_date"                  => $params['end_date_ae'],
            "party_a"                   => $params['party_a'],
            "party_b"                   => $params['party_b'],
            'create_user'               => $currentUser['employee_id'],
            'create_date'               => date('Y/m/d H:i:s')
        );
        $this->print_contract_model->saveContractPrint($dataInsertAgreement);

        // Export Excel Files
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Contract_$agreementNo.xlsx");
        header("Pragma: no-cache");
        header("Expires: 0");
        ob_end_clean();
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
    // create by: Thanh
    public function eachTimePrintExcel()
    {
        // get param
        $params = $this->input->post();
        $params['rate_eachtime'] = ($params['rate_eachtime'] == "" ? 1 : $params['rate_eachtime']);
        $params['rate_eachtime_jpy'] = ($params['rate_eachtime_jpy'] == "" ? 1 : $params['rate_eachtime_jpy']);
        $params['rate_eachtime_jpy_usd'] = ($params['rate_eachtime_jpy_usd'] == "" ? 1 : $params['rate_eachtime_jpy_usd']);
        // $eachtime_no_old = $params['eachtime_no'].'-'.$params['eachtime_1'].'-'.$params['eachtime_2'].'-'.$params['eachtime_3_old'];
        // $eachtime_no_old .= '/'.$params['eachtime_5'];
        
        $eachtimeNo = $params['eachtime_no'].'-'.$params['eachtime_1'].'-'.$params['eachtime_2'].'-'.$params['eachtime_3'].$params['eachtime_4'];
        $eachtimeNo .= '/'.$params['eachtime_5'];
        if(!empty($params['eachtime_6'])){
            $eachtimeNo .= '('.$params['eachtime_6'].')';
        }

        $currentUser = $this->session->userdata('user');
        $dvtKey = $params['dvt_key'];
        $listKey = explode("*", $dvtKey);
        
        // set filepath template
        $filePath = "views/inv_pl_print/template_eachtime_print.xlsx";

        // get contract date
        $contract_date = new DateTime($params['contract_date_ae']);
        $contract_date_en = date_format($contract_date,"F j, Y");
        $contract_date_vn = date_format($contract_date,'\n\g\à\y d \t\h\á\n\g m \n\ă\m Y');
        
        // Create new workbook
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);
        $hd_sheet = $spreadsheet->getSheet(0);
        $detail_sheet = $spreadsheet->getSheet(1);
        // Write mã contract, ngày làm hợp đồng và nội dung bên A
        $hd_sheet->setCellValue('K6','Số /No: '.$eachtimeNo);

        $hd_sheet->setCellValue('A7', "Hôm nay, ".$contract_date_vn.", chúng tôi gồm có:");
        $hd_sheet->setCellValue('A8', "Today, ".$contract_date_en." we are:");
        $party_a_info = $params['party_a_info'];
        $partya_en = "";
        $partya_vn = "";
        $address_en = "";
        $address_vn = "";
        $tel = "";
        $fax = "";
        $tax_code = "";
        $represented = "";
        $position = "";
        if(preg_match_all("/PARTY A.*:(.*)/i", $party_a_info, $matches)){
            $partya_en = trim($matches[1][0]);
        }
        if(preg_match_all("/BÊN A.*:(.*)/i", $party_a_info, $matches)){
            $partya_vn = trim($matches[1][0]);
        }
        if(preg_match_all("/Address.*:([\s\S]*)Điện thoại/i", $party_a_info, $matches)){
            $address_en = trim($matches[1][0]);
        }
        if(preg_match_all("/Địa chỉ.*:([\s\S]*)Address/i", $party_a_info, $matches)){
            $address_vn = trim($matches[1][0]);
        }
        if(preg_match_all("/Tel.*:(.*)/i", $party_a_info, $matches)){
            $tel = $matches[1][0];
        }
        if(preg_match_all("/Fax.*:(.*)/i", $party_a_info, $matches)){
            $fax = $matches[1][0];
        }
        if(preg_match_all("/Tax code.*:(.*)/i", $party_a_info, $matches)){
            $tax_code = $matches[1][0];
        }
        if(preg_match_all("/Represented.*:(.*)/i", $party_a_info, $matches)){
            $represented = $matches[1][0];
        }
        if(preg_match_all("/Position.*:(.*)/i", $party_a_info, $matches)){
            $position = $matches[1][0];
        }
        $hd_sheet->setCellValue('A10', "BÊN A (BÊN BÁN):  ".$partya_vn);
        $hd_sheet->setCellValue('A11', "PARTY A (THE SELLER):  ".$partya_en);
        $hd_sheet->setCellValue('B12', $address_vn);
        $hd_sheet->setCellValue('B13', $address_en);
        $hd_sheet->setCellValue('B14', $tel);
        $hd_sheet->setCellValue('B15', $fax);
        $hd_sheet->setCellValue('B16', $tax_code);
        $hd_sheet->setCellValue('B17', $represented);
        $hd_sheet->setCellValue('B18', $position);

        // Write infor bên B
        $partyb_en = "";
        $partyb_vn = "";
        $address_en = "";
        $address_vn = "";
        $tel = "";
        $fax = "";
        $represented = "";
        $position = "";
        $party_b_info = $params['party_b_info'];
        if(preg_match("/PARTY A.*:(.*)/i", $party_b_info, $matches)){
            $partyb_en = trim($matches[1]);
        }
        if(preg_match("/BÊN A.*:(.*)/i", $party_b_info, $matches)){
            $partyb_vn = trim($matches[1]);
        }
        if(preg_match("/Address.*:([\s\S]*)Điện thoại/i", $party_b_info, $matches)){
            $address_en = trim($matches[1]);
        }
        if(preg_match("/Địa chỉ.*:([\s\S]*)Address/i", $party_b_info, $matches)){
            $address_vn = trim($matches[1]);
        }
        if(preg_match("/Tel.*:(.*)/i", $party_b_info, $matches)){
            $tel = $matches[1];
        }
        if(preg_match("/Fax.*:(.*)/i", $party_b_info, $matches)){
            $fax = $matches[1];
        }
        if(preg_match("/Represented.*:(.*)/i", $party_b_info, $matches)){
            $represented = $matches[1];
        }
        if(preg_match("/Position.*:(.*)/i", $party_b_info, $matches)){
            $position = $matches[1];
        }
        // $address_b = preg_replace("/(fax|tel|attn).*/i", "", $params['address_b']);
        $hd_sheet->setCellValue('A20', "BÊN B (BÊN MUA):  ".$params["party_b"]);
        $hd_sheet->setCellValue('A21', "PARTY B (THE BUYER):  ".$params["party_b"]);
        $hd_sheet->setCellValue('B22', $address_vn);
        $hd_sheet->setCellValue('B23', $address_en);
        $hd_sheet->setCellValue('B24', $tel);
        $hd_sheet->setCellValue('B25', $fax);
        $hd_sheet->setCellValue('C26', $represented);
        $hd_sheet->setCellValue('C27', $position);

        // Write notify 
        $notify = array();
        $notify = preg_split('/\r\n|\r|\n/', $params['notify_info']);
        $str_notify = implode(" ",$notify);
        $notify_address = preg_replace("/(fax|tel|attn).*/i", "", $str_notify);
        $notify_represented = "";
        if(preg_match(self::REPRESENTED_PATTERN, $str_notify, $matches)){
            $notify_represented = $match["represented"];
        }
        $hd_sheet->setCellValue('E29', $params['notify']);
        if(!empty($notify_address)) {
            $hd_sheet->setCellValue('D30', $notify_address);
        }
        if(!empty($notify_represented)){
            $hd_sheet->setCellValue('E31', $notify_represented);
        }

        // Write Consignee
        $consignee = array();
        $consignee = preg_split('/\r\n|\r|\n/', $params['consignee_info']);
        $consigneeTel = "";
        $consigneeFax = "";
        $represented = "";
        if(preg_match_all(self::TEL_PATTERN, $params['consignee_info'], $matches)){
            $consigneeTel = $matches['tel'][0];
        }
        if(preg_match_all(self::FAX_PATTERN, $params['consignee_info'], $matches)){
            $consigneeFax = $matches['fax'][0];
        }
        if(preg_match_all(self::REPRESENTED_PATTERN, $params['consignee_info'], $matches)){
            $represented = $matches['represented'][0];
        }
        
        $str_consignee = implode(" ",preg_split('/\r\n|\r|\n/', $params['consignee_info']));
        $consignee_address = preg_replace("/(fax|tel|attn).*/i", "", $str_consignee);
        // Write consignee vn info
        $consignee_vn = "";
        $consignee_address_vn = "";
        if(!empty($params['consignee_info_vn'])){
            $consignee_vn = preg_split('/\r\n|\r|\n/', $params['consignee_info_vn'])[0];
            $consignee_address_vn = str_replace($consignee_vn, "", $params["consignee_info_vn"]);
        }
        $represented = "";
        $position = "";
        $tax_code = "";
        if(preg_match_all(self::REPRESENTED_PATTERN, $consignee_address, $matches)){
            $represented = $matches['represented'][0];
        }
        if(preg_match_all(self::POSITION_PATTERN, $consignee_address, $matches)){
            $position = $matches['position'][0];
        }
        if(preg_match_all(self::TAXCODE_PATTERN, $consignee_address, $matches)){
            $tax_code = $matches['tax_code'][0];
        }
        $hd_sheet->setCellValue('D33', $consignee_vn);
        $hd_sheet->setCellValue('D34', $params['consignee']);
        $hd_sheet->setCellValue('D35', $consignee_address_vn);
        $hd_sheet->setCellValue('D36', $consignee_address);
        $hd_sheet->setCellValue('E37', $tax_code);
        $hd_sheet->setCellValue('D38', $consigneeTel);
        $hd_sheet->setCellValue('H38', $consigneeFax);
        $hd_sheet->setCellValue('E39', $represented);
        $hd_sheet->setCellValue('I39', $position);

        // Write điều kiện giao hàng
        $hd_sheet->setCellValue('C66', $params['delivery_condition']);
        $hd_sheet->setCellValue('C67', $params['delivery_condition']);

        // Write delivery term
        $partyb = $params['party_b'];
        $export_term = $this->komoku_model->get_delivery_term_by_party($partyb)[0];
        $arr_tmp = preg_split('/\r\n|\r|\n/', $export_term["delivery_term"]);
        $arr_export_term = array_chunk(array_map("trim", $arr_tmp),1);
        $hd_sheet->fromArray($arr_export_term, NULL,'A72');

        // Write địa điểm giao hàng
        $hd_sheet->setCellValue('B81', $consignee_address_vn);
        $hd_sheet->setCellValue('B83', $consignee_address);

        // Write ngày giao hàng
        $delivery_date = new DateTime($params['delivery_date']);
        $delivery_date_en = date_format($delivery_date,"F j, Y");
        $delivery_date_vn = date_format($delivery_date,"d-m-Y");

        $hd_sheet->setCellValue('B88', $delivery_date_vn);
        $hd_sheet->setCellValue('B89', $delivery_date_en);
        // Write payment term
            // Write payment method
        if(!empty($params['payment_methods_eachtime'])){
            $hd_sheet->setCellValue('A94', "Việc thanh toán sẽ được thực hiện bằng phương thức ${params['payment_methods_eachtime_vn']}.");
            $hd_sheet->setCellValue('A95', "The payment will be made by ${params['payment_methods_eachtime']}.");
        }

        // Write bank
        $bank = array();
        $bank = preg_split('/\r\n|\r|\n/', $params['bank_info']);
        if(isset($bank[0])) {
            $bank_account = explode(":", $bank[0]);
            if(isset($bank_account[1])) {
                $hd_sheet->setCellValue('D102', $bank_account[1]);
            }
        }
        if(isset($bank[1])) {
            $bank_no = explode(":", $bank[1]);
            if(isset($bank_no[1])) {
                $hd_sheet->setCellValue('D103', $bank_no[1]);
            }
        }
        if(isset($bank[2])) {
            $bank_name = explode(":", $bank[2]);
            if(isset($bank_name[1])) {
                $hd_sheet->setCellValue('D104', $bank_name[1]);
            }
        }
        if(isset($bank[3])) {
            $bank_address = explode(":", $bank[3]);
            if(isset($bank_address[1])) {
                $hd_sheet->setCellValue('D105', $bank_address[1]);
            }
        }
        if(isset($bank[4])) {
            $bank_code = explode(":", $bank[4]);
            if(isset($bank_code[1])) {
                $hd_sheet->setCellValue('D106', $bank_code[1]);
            }
        }
        // write export term
        $partya = $params['party_a'] == "001"?"EPE":"HANOI";
        $export_term = $this->komoku_model->get_export_term_by_party($partya)[0];
        $arr_export_term = array_chunk(preg_split('/\r\n|\r|\n/', $export_term["komoku_name_3"]),1);
        // Write export term cho dung chu in nghieng trong template
        if($partya=="HANOI"){
            $hd_sheet->fromArray($arr_export_term, NULL,'A121');
        }else{
            $hd_sheet->fromArray($arr_export_term, NULL,'A116');
        }

        // write số thỏa thuận và ngày thỏa thuận
        if(isset($params["scan_sign_ae"]) && $params["scan_sign_ae"] == "scan_sign_ae"){
            if(!empty($params['reference']) && !empty($params['contract_from_date'])){
                $agreement_date = new DateTime($params['contract_from_date']);
                $agreement_date_vn = date_format($agreement_date, 'd-m-Y');
                $agreement_date_en = date_format($agreement_date, "d-M-Y");
                $hd_sheet->setCellValue('F128', $params['reference'].", ngày ".$agreement_date_vn);
                $hd_sheet->setCellValue('F130', $params['reference'].", date ".$agreement_date_en);
                // write ngày hết hạn hợp đồng
                $str_date = date("Y")."/12/31";
                $end_date = new DateTime($str_date);
                if(!empty($params['contract_end_date'])){
                    $end_date = new DateTime($params['contract_end_date']);
                }

                $end_date_vn = date_format($end_date, 'd-m-Y');
                $end_date_en = date_format($end_date, "d-M-Y");
                
                $hd_sheet->setCellValue('E132', $end_date_vn);
                $hd_sheet->setCellValue('E133', $end_date_en);
            }
        }else{
            $hd_sheet->setCellValue('A128', "");
            $hd_sheet->setCellValue('A130', "");
            $hd_sheet->setCellValue('A132', "");
            $hd_sheet->setCellValue('A133', "");
        }

        // Update number copy contract
        if(trim($params['consignee']) === trim($params['party_b'])){
            $old_value_vn = $hd_sheet->getCell('A151')->getValue();
            $old_value_en = $hd_sheet->getCell('A153')->getValue();
            $update_vn = str_replace("06", "04", $old_value_vn);
            $update_vn = str_replace(" và Bên nhận hàng", "", $update_vn);
            $update_en = str_replace("06", "04", $old_value_en);
            $update_en = str_replace(" and The consignee, ", "", $update_en);
            $hd_sheet->setCellValue('A155',$update_vn);
            $hd_sheet->setCellValue('A157',$update_en);
        }

        // Write dai dien ben b
        $hd_sheet->setCellValue('I164', $params['party_b']);
        if($params['party_b'] !== "SHIMADA SHOJI CO., LTD" || (!isset($params["scan_sign_ae"]) || $params["scan_sign_ae"] !== "scan_sign_ae")){
            // Remove signature
            // If permit use scan_sign_ae is NOT remove
            $drawing_collection = $hd_sheet->getDrawingCollection();
            foreach ($drawing_collection as $key => $drawing){
                $drawing->setWidth(0);
                $drawing->setHeight(0);
            }
        }
        // Write ben chi dinh
        $hd_sheet->setCellValue('B175', $params['notify']);
        // Write ben nhan hang
        $hd_sheet->setCellValue('I175', $params['consignee']);
        
        if(empty($params['notify'])){
            $hd_sheet->setCellValue('B174', "");
        }
        // Remove all row not satify
        // Remove empty row in ARTICLE 4: DOCUMENTS & EXPORT TERM
        $tmpRow = 135 - 2;
        for($i=$tmpRow;$i>128;$i--){
            $dataArray = $hd_sheet->rangeToArray("A$i:L$i")[0];
            if(count(array_unique($dataArray))==1 && end($dataArray) == null){
                $hd_sheet->removeRow($i);
            }else{
                break;
            }
        }
        $tmpRow = 128 - 3;
        if(isset($params["scan_sign_ae"]) && $params["scan_sign_ae"] !== "scan_sign_ae"){
            $tmpRow = 128;
        }
        for($i=$tmpRow;$i>121;$i--){
            $dataArray = $hd_sheet->rangeToArray("A$i:L$i")[0];
            if(count(array_unique($dataArray))==1 && end($dataArray) == null){
                $hd_sheet->removeRow($i);
            }else{
                break;
            }
        }
        // Delete empty row khi thu tuc xuat hang co danh la HANOI
        $tmpRow = 121 - 1;
        for($i=$tmpRow;$i>115;$i--){
            $dataArray = $hd_sheet->rangeToArray("A$i:L$i")[0];
            if(count(array_unique($dataArray))==1 && end($dataArray) == null){
                $hd_sheet->removeRow($i);
            }else{
                break;
            }
        }

        // Remove empty row in ARTICLE 2: TERM OF DELIVERY
        $tmpRow = 80 - 2;
        for($i=$tmpRow;$i>70;$i--){
            $dataArray = $hd_sheet->rangeToArray("A$i:L$i")[0];
            if(count(array_unique($dataArray))==1 && end($dataArray) == null){
                $hd_sheet->removeRow($i);
            }else{
                break;
            }
        }

        // Write table 
        // Lấy danh sách dvt 
        $delivery_data = json_decode($params['delivery_data'], true);
        $item_list = array();
        foreach( $delivery_data as $dvt ) {
            $data = array(
                'dvt_no'=>$dvt['delivery_no'],
                'order_date'=>$dvt['order_date'],
                'times'=>$dvt['times'],
                'pack_no'=>$dvt['pack_no']
            );
            $items = $this->dvt_model->get_items_list($data);
            $item_list = array_merge($item_list, $items);
        }
        
        $tmp_itemlist = $tmp_item_unit = Array();
        $item_list_temp = $item_list;
        $total_quantity_arr = array();
        $styleArray = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        // Array total money
        $outCurrency = trim($params['output_currency_eachtime']);
        foreach($item_list as &$item){
            // check output currency and convert currency
            $itemCurrency = isset($item['currency']) ? trim($item['currency']) : '';
            $item['sell_price'] = ($item['sell_price'] == null || $item['sell_price'] == "") ? 0 : $item['sell_price'];
            if ($itemCurrency !== '') {
                if ($outCurrency == 'USD') {
                    if ($itemCurrency == 'VND') {
                        $item['sell_price'] /= $params["rate_eachtime"];
                    } else if ($itemCurrency == 'JPY') {
                        $item['sell_price'] *= $params["rate_eachtime_jpy_usd"];
                    } 
                } else if ($outCurrency == 'VND') {
                    if ($itemCurrency == 'USD') {
                        $item['sell_price'] *= $params["rate_eachtime"];
                    } else if ($itemCurrency == 'JPY') {
                        $item['sell_price'] *= $params["rate_eachtime_jpy"];
                    } 
                } else if ($outCurrency == 'JPY') {
                    if ($itemCurrency == 'USD') {
                        $item['sell_price'] /= $params["rate_eachtime_jpy_usd"];
                    } else if ($itemCurrency == 'VND') {
                        $item['sell_price'] /= $params["rate_eachtime_jpy"];
                    }
                }
            }
            $item['sell_price'] = (float)(str_replace(",","",parseMoney($item['sell_price'], $outCurrency)));
            $item['currency'] = $outCurrency;
            $tmp_itemlist[$item["item_code"]][] = $item;
            $unit = $item['unit'];
            $quantity = $item['quantity'];
            if(!array_key_exists($unit,$total_quantity_arr)){
                $total_quantity_arr[$unit] = $quantity;
            }else{
                $total_quantity_arr[$unit] += $quantity;
            }
        }

        $total_money = 0;
        // array total money
        $arr_total = array();

        // Trường hợp nhiều item code
        if(sizeof($item_list) > MAX_ITEM_IN_LIST_CONTRACT) {
            if($outCurrency == 'VND'){
                $currency = 'VND';
                $detail_sheet->setCellValue('J2', 'TOTAL (VND)');
            } else if($outCurrency == 'JPY') {
                $currency = 'JPY';
                $detail_sheet->setCellValue('J2', 'TOTAL (JPY)');
            } else {
                $currency = '$';
                $detail_sheet->setCellValue('J2', 'TOTAL (USD)');
            }

            $start_row_data_sheet = 3;
            $row = 0;
            $stt = 1;
            foreach($tmp_itemlist as $item_code => $items) {
                $detail_sheet->insertNewRowBefore($start_row_data_sheet + 1 + $row, 1);
                $border_from = $start_row_data_sheet + $row;
                $detail_sheet->getStyle('A'.$border_from.':K'.$border_from)->applyFromArray($styleArray);
                $detail_sheet->setCellValueByColumnAndRow(1, $row + $start_row_data_sheet, $stt);
                $detail_sheet->setCellValueByColumnAndRow(2, $row + $start_row_data_sheet, $items[0]["item_name"]);
                foreach ($items as $item) {
                    $total = 0;
                    $row += 1;
                    $detail_sheet->insertNewRowBefore($start_row_data_sheet + 1 + $row, 1);
                    $detail_sheet->setCellValueByColumnAndRow(2, $row + $start_row_data_sheet,
                        ( (!empty($item["size"]) && $item["size"] != "''") ? ("SIZE: ".$item["size"] ." ") : "")
                        . (!empty($item["color"]) ? ("COL.".$item["color"]) : "")
                    );
                    $detail_sheet->setCellValueByColumnAndRow(6, $row + $start_row_data_sheet, number_format($item['quantity']));
                    $detail_sheet->setCellValueByColumnAndRow(7, $row + $start_row_data_sheet, $item['unit']);
                    $total = $item['sell_price'] * $item['quantity'];
                    $detail_sheet->setCellValueByColumnAndRow(9, $row + $start_row_data_sheet, parseMoney($item['sell_price'], $outCurrency));
                    $detail_sheet->setCellValueByColumnAndRow(11, $row + $start_row_data_sheet, parseMoney(($item['sell_price']*$item['quantity']), $outCurrency));
                    array_push($arr_total, $total);
                }
                $row += 1;
                $stt += 1;
            }
            // Set number format
            $end_row = $border_from + 10;
            if($outCurrency == 'JPY'){
                $detail_sheet->getStyle("I$start_row_data_sheet:I$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
                $detail_sheet->getStyle("K$start_row_data_sheet:K$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
            } else if($outCurrency == 'VND'){
                $detail_sheet->getStyle("I$start_row_data_sheet:I$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
                $detail_sheet->getStyle("K$start_row_data_sheet:K$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
            } else {
                $detail_sheet->getStyle("I$start_row_data_sheet:I$end_row")->getNumberFormat()->setFormatCode("$ #,##0.0000");
                $detail_sheet->getStyle("K$start_row_data_sheet:K$end_row")->getNumberFormat()->setFormatCode("$ #,##0.0000");
            }

            $hd_sheet->setCellValue('A52', 1);
            $hd_sheet->setCellValue('B52', 'CHI TIẾT KÈM THEO PHỤ LỤC');
            $hd_sheet->setCellValue('B53', 'DETAILS AS PER ATTACH SHEET');

            // Calculate total money
            foreach ($arr_total as $key => $value) {
                $total_money = $total_money + $value;
            }
            $total_money_vat = $total_money;
            if($params["tax_eachtime"] === "001"){
                $total_money_vat = $total_money * 1.1;
            }
            // $total_money_vat = number_format_drop_zero_decimals($total_money_vat, 2);
            if($params['output_currency_eachtime'] == 'VND'){
                $hd_sheet->setCellValue('J51', 'TOTAL (VND)');
                $hd_sheet->setCellValue('A60', 'Bằng chữ:'.ucfirst(convert_number_to_words($total_money_vat, 'vn').' đồng'));
                $hd_sheet->setCellValue('A61', 'In Words:'.ucfirst(convert_number_to_words($total_money_vat, 'en').' Dong'));
            } else if($params['output_currency_eachtime'] == 'JPY') {
                    $hd_sheet->setCellValue('J51', 'TOTAL (JPY)');
                    $hd_sheet->setCellValue('A60', 'Bằng chữ:'.ucfirst(convert_number_to_words($total_money_vat, 'vn').' Yên Nhật'));
                    $hd_sheet->setCellValue('A61', 'In Words:'.ucfirst(convert_number_to_words($total_money_vat, 'en').' Japan Yen'));
            } else {
                $hd_sheet->setCellValue('J51', 'TOTAL (USD)');
                $hd_sheet->setCellValue('A60', 'Bằng chữ:'.ucfirst(convert_number_to_words($total_money_vat, 'vn').' USD'));
                $hd_sheet->setCellValue('A61', 'In Words:'.ucfirst(makewords($total_money_vat)));
            }

            $start_row_data_sheet = 56;
            $row = 0;
            if(count($total_quantity_arr)>1){
                $hd_sheet->insertNewRowBefore($start_row_data_sheet + 1, count($total_quantity_arr));
            }
            foreach($total_quantity_arr as $unit => $total) {
                $hd_sheet->setCellValue("F".($row + $start_row_data_sheet), $total);
                $hd_sheet->setCellValue("G".($row + $start_row_data_sheet), $unit);
                $row++;
            }

            // Format price
            if($outCurrency == 'JPY'){
                $hd_sheet->getStyle("J52:J56")->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
            } else if($outCurrency == 'VND'){
                $hd_sheet->getStyle("J52:J56")->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
            } else {
                $hd_sheet->getStyle("J52:J56")->getNumberFormat()->setFormatCode("$ #,##0.0000");
            }
            //Fill total case vat and not vat
            $hd_sheet->setCellValue("J56", parseMoney($total_money_vat,$outCurrency));
            if($params["tax_eachtime"] === "001"){
                $hd_sheet->setCellValue("J54", parseMoney($total_money,$outCurrency));
                $hd_sheet->setCellValue("J55", parseMoney(($total_money*0.1),$outCurrency));
            }else{
                $hd_sheet->removeRow(54,2);
            }
        } else {
            $total_money = 0;
            foreach($tmp_itemlist as $item_code => $items) {
                foreach ($items as $item) {
                    $total = $item['sell_price'] * $item['quantity'];
                    $total_money += $total;
                }
            }
            // Calculate total money
            $total_money_vat = $total_money;
            if($params["tax_eachtime"] === "001"){
                $total_money_vat = $total_money * 1.1;
            }
            // $total_money = number_format_drop_zero_decimals($total_money, 2);
            $currency = '$';
            if($params['output_currency_eachtime'] == 'VND'){
                $currency = 'VND';
                $hd_sheet->setCellValue('J51', 'TOTAL (VND)');
                $hd_sheet->setCellValue('A60', 'Bằng chữ:'.ucfirst(convert_number_to_words($total_money_vat, 'vn').' đồng'));
                $hd_sheet->setCellValue('A61', 'In Words:'.ucfirst(convert_number_to_words($total_money_vat, 'en').' Dong'));
            } else if($params['output_currency_eachtime'] == 'JPY') {
                $currency = 'JPY';
                $hd_sheet->setCellValue('J51', 'TOTAL (JPY)');
                $hd_sheet->setCellValue('A60', 'Bằng chữ:'.ucfirst(convert_number_to_words($total_money_vat, 'vn').' Yên Nhật'));
                $hd_sheet->setCellValue('A61', 'In Words:'.ucfirst(convert_number_to_words($total_money_vat, 'en').' Japan Yen'));
            } else {
                $currency = '$';
                $hd_sheet->setCellValue('J51', 'TOTAL (USD)');
                $hd_sheet->setCellValue('A60', 'Bằng chữ:'.ucfirst(convert_number_to_words($total_money_vat, 'vn').' USD'));
                $hd_sheet->setCellValue('A61', 'In Words:'.ucfirst(makewords($total_money_vat)));
            }
            // Set row data from 52
            $start_row_data_sheet = 52;
            $row = 0;
            $stt = 1;

            foreach($tmp_itemlist as $item_code => $items) {
                $spreadsheet->getActiveSheet()->insertNewRowBefore($start_row_data_sheet + 1 + $row, 1);
                $border_from = $start_row_data_sheet + $row;
                $hd_sheet->getStyle('A'.$border_from.':K'.$border_from)->applyFromArray($styleArray);
                $hd_sheet->setCellValueByColumnAndRow(1, $row + $start_row_data_sheet, $stt);
                $hd_sheet->setCellValueByColumnAndRow(2, $row + $start_row_data_sheet, $items[0]["item_name"]);
                foreach ($items as $item) {
                    $row += 1;
                    $hd_sheet->insertNewRowBefore($start_row_data_sheet + 1 + $row, 1);
                    $unit_price = $item['sell_price'];
                    $hd_sheet->setCellValueByColumnAndRow(2, $row + $start_row_data_sheet,
                         (!empty($item["size"]) ? ("SIZE: ".$item["size"] ." ") : "")
                        . (!empty($item["color"]) ? ("COL.".$item["color"]) : "")
                    );
                    $hd_sheet->setCellValueByColumnAndRow(6, $row + $start_row_data_sheet, $item['quantity']);
                    $hd_sheet->setCellValueByColumnAndRow(7, $row + $start_row_data_sheet, $item['unit']);
                    $hd_sheet->setCellValueByColumnAndRow(9, $row + $start_row_data_sheet, parseMoney($unit_price,$outCurrency) );
                    $hd_sheet->setCellValueByColumnAndRow(11, $row + $start_row_data_sheet, parseMoney(($unit_price * $item['quantity']),$outCurrency)); 
                }
                $row += 1;
                $stt += 1;
            }

            if(count($total_quantity_arr)>1){
                $hd_sheet->insertNewRowBefore($start_row_data_sheet + 3 + $row, count($total_quantity_arr));
            }
            $row_total = $row;
            foreach($total_quantity_arr as $unit => $total) {
                $hd_sheet->setCellValueByColumnAndRow(6, $row_total + 4 + $start_row_data_sheet, $total);
                $hd_sheet->setCellValueByColumnAndRow(7, $row_total + 4 + $start_row_data_sheet, $unit);
                $row_total++;
            }
            
            // Set number format
            $end_row = $start_row_data_sheet + $row + 6;
            if($outCurrency == 'JPY'){
                $hd_sheet->getStyle("I$start_row_data_sheet:I$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
                $hd_sheet->getStyle("J$start_row_data_sheet:K$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0.00");
            } else if($outCurrency == 'VND'){
                $hd_sheet->getStyle("I$start_row_data_sheet:I$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
                $hd_sheet->getStyle("J$start_row_data_sheet:K$end_row")->getNumberFormat()->setFormatCode("[\$$currency] #,##0");
            } else {
                $hd_sheet->getStyle("I$start_row_data_sheet:I$end_row")->getNumberFormat()->setFormatCode("$ #,##0.0000");
                $hd_sheet->getStyle("J$start_row_data_sheet:K$end_row")->getNumberFormat()->setFormatCode("$ #,##0.0000");
            }

            $hd_sheet->setCellValue("J".($start_row_data_sheet + $row + 4),  parseMoney($total_money_vat,$outCurrency));
            if($params["tax_eachtime"] === "001"){
                $hd_sheet->setCellValue("J".($start_row_data_sheet + $row + 2), parseMoney($total_money,$outCurrency));
                $hd_sheet->setCellValue("J".($start_row_data_sheet + $row + 3),  parseMoney(($total_money*0.1),$outCurrency));
            }else{
                $hd_sheet->removeRow($start_row_data_sheet + $row + 2, 2);
            }
            
            // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(10, $row + $start_row_data_sheet,  $currency.' '.$total_money);

            // Remove sheet index 1
            $spreadsheet->removeSheetByIndex(1);
        }
         
        // Remove notify if not exist
        if(empty($params['notify'])){
            $hd_sheet->removeRow(29, 5);
        }

        // Insert or update data into print contract table
        if(sizeof($listKey) >= 1) {
            $packNo = explode('*', $params['pack_no']);
            foreach ($listKey as $idx => $key){
                $dvtKey = explode(";",$key);
                $params['delivery_no'] = $dvtKey[0];
                $params['times'] = $dvtKey[2];
               
                $dataInsertEachtime = array(
                    // "type"                      => $params['type'],
                    "contract_no"               => $eachtimeNo,
                    "kubun"                     => '2002',
                    "contract_date"             => $params['contract_date_ae'],
                    "delivery_no"               => $params['delivery_no'],
                    "times"                     => $params['times'],
                    "pack_no"                   => 0,
                    "delivery_date"             => $dvtKey[1],
                    "delivery_date_eachtime"    => $params['delivery_date'],
                    "delivery_condition"        => $params['delivery_condition'],
                    "payment_currency"          => $params['output_currency_eachtime'],
                    "payment_term"              => $params['payment_term_eachtime'],
                    "payment_methods"           => $params['payment_methods_eachtime'],
                    "party_a"                   => $params['party_a'],
                    "party_b"                   => $params['party_b'],
                    "consignee"                 => $params['consignee'],
                    "notify"                    => $params['notify'],
                    "bank"                      => $params['bank_name'],
                    "rate"                      => $params['rate_eachtime'],
                    "rate_jpy"                  => $params['rate_eachtime_jpy'],
                    "rate_jpy_usd"              => $params['rate_eachtime_jpy_usd'],
                    'create_user'               => $currentUser['employee_id'],
                    'create_date'               => date('Y/m/d H:i:s'),
                    'edit_user'                 => $currentUser['employee_id'],
                    'edit_date'                 => date('Y/m/d H:i:s'),
                );
                if(isset($params['scan_sign_ae']) && $params['scan_sign_ae'] === "scan_sign_ae") {
                    $dataInsertEachtime['scan_signature'] = TRUE;
                } else {
                    $dataInsertEachtime['scan_signature'] = FALSE;
                }
                $this->print_contract_model->saveContractPrint($dataInsertEachtime);
            }
        }

        // Create Writer, congig and download
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        // $writer->save('upload/eachTime.xlsx');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Contract_$eachtimeNo.xlsx");
        header("Pragma: no-cache");
        header("Expires: 0");
        ob_end_clean();
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }

    public function get_eachtime_no()
    {
        $params = $this->input->post();
        $params['eachtime_no'] = $params['eachtime_no'].'-'.$params['contract_type'].'-';
        $result = $this->print_contract_model->get_eachtime_no($params);
        if($result != FALSE) {
            $contract_no = $result[0]['contract_no'];
            $key_list = explode('-', $contract_no);
            $arr = explode('/', $key_list[3]);
            $number_arr = explode('(', $arr[0]);
            $number = $number_arr[0];
            $eachtime_3 = intval($number);
            $eachtime_3  = str_pad("" . $eachtime_3 + 1, 3, '0', STR_PAD_LEFT);
            $response = array(
                'success' => true,
                'eachtime_3' => $eachtime_3,
            );
        } else {
            $response = array(
                'success' => false,
            );
        }
        echo json_encode($response);
    }

    public function get_consignee() 
    {
        $company_id = $this->input->post('company_id');
        $result = $this->company_model->get_consignee_export($company_id);
        echo json_encode($result);
    }
    public function get_notify() 
    {
        $company_id = $this->input->post('company_id');
        $result = $this->company_model->get_notify_export($company_id);
        echo json_encode($result);
    }
    private function checkNumberCurrency($arr){
        $result = array();
        $res = array();
        foreach($arr as $ele){
            if(in_array($ele['currency'], $result) !== TRUE){
                array_push($result, $ele['currency']);
            }
        }
        if(sizeof($result) > 1){
            foreach($arr as $index => $value){
                if($value['currency'] != 'VND'){
                    $arr[$index]['currency'] = 'VND'; //strtolower
                    $rate = strtolower('rate_'.$value['currency']);
                    $arr[$index]['sell_price'] = $value['sell_price'] * $value[$rate];
                }
            }
        }
        foreach($arr as $index => $value){
            $temp1 = $value;
            $existFlag = false;
            for($i = 0; $i < sizeof($res); $i++){
                $temp2 = $res[$i];
                if($temp1['item_code'] == $temp2['item_code'] && $temp1['size'] == $temp2['size'] && $temp1['color'] == $temp2['color'] && $temp1['sell_price'] == $temp2['sell_price']){
                    $res[$i]['quantity'] += $temp1['quantity'];
                    $existFlag = true;
                }
            }
            if(sizeof($res) == 0 || !$existFlag){
                array_push($res, $temp1);
            }
        }
        return $res;
    }

    public function getSalesContractPrinted(){
        $params = $this->input->post();
        $print = $this->print_contract_model->getLastContractNo($params['kubun']);
        $printInfo = array();
        $newFlg = false;
        if($params['packingList']){
            foreach($params['packingList'] as &$dt){
                $dt['kubun'] = $params['kubun'];
                $dt['delivery_date'] = $dt['order_date'];
                $result = $this->print_contract_model->getSalesContractPrint($dt);
                if(count($result) > 0){
                    $printInfo = $result[0];
                }else{
                    $newFlg = true;
                }
            }
        }
        if($newFlg){
            $printInfo = [];
        }
        if($params['kubun'] != '2001') {
            $increNo = '001';
            if(count($print) > 0){
                $contractNo = $print[0]['contract_no'];
                $contractNo = explode('-',$contractNo);
                if(isset($contractNo[3])){
                    $contNo = intval($contractNo[3]);
                    $contNo = str_pad(($contNo+1), 3, "0", STR_PAD_LEFT);
                    $increNo = $contNo;
                }
            }
            echo json_encode(array('data'=>$printInfo, 'increNo'=>$increNo));
        } else {
            echo json_encode(array('data'=>$printInfo));
        }
    }
    public function getAgreementContract(){
        $params = $this->input->post();
        $result = $this->print_contract_model->getAgreementContractPrint($params);
        echo json_encode(array('data'=>$result));
    }

    public function getPODVT(){
        $params = $this->input->post();
        $packing_selected = json_decode($params['delivery_data'], true);
        $po_dvt_selected = json_decode($params['po_dvt_selected'], true);
        $result = [];
        if($params['kubun'] == '1') {
            $result = $this->dvt_model->getDVTForContractPrint();
        } else if($params['kubun'] == '2') {
            $result = $this->order_received_model->getAnotherPV();
        }
        $tmp_result = $result;
        if(!empty($packing_selected)){
            $tmp_result = array_map(function($item) use ($packing_selected, $po_dvt_selected){
                $item["disabled"] = false;
                $item["checked"] = false;
                foreach($packing_selected as $pack){
                    if(trim($pack["delivery_no"]) === $item["dvt_no"] && 
                    $pack["order_date"] === $item["order_date"] &&
                    $pack["times"] === $item["times"]){
                        $item["disabled"] = true;
                        $item["checked"] = true;
                    }
                }
                if(!empty($po_dvt_selected)){
                    foreach($po_dvt_selected as $pack){
                        if(trim($pack["delivery_no"]) === $item["dvt_no"] && 
                        $pack["order_date"] === $item["order_date"] &&
                        $pack["times"] === $item["times"]){
                            $item["checked"] = true;
                        }
                    }
                }
                return $item;
            }, $result);
        }
        echo json_encode(array('data'=>array_values($tmp_result)));
    }
    function convertSpecialChar($input){
        $output = '';
        $output = str_replace("&","&amp;",$input);
        $output = str_replace("<","&lt;",$output);
        $output = str_replace(">","&gt;",$output);
        return $output;
    }
    function merge_array_by_condition($arr1, $arr2){
        if(count($arr1) <= 0){
            return $arr2;
        }
        $existFlg = false;
        foreach ($arr2 as $tempArr) {
            foreach ($arr1 as &$arr) {
                // check item existed
                if ($arr['item_code'] == $tempArr['item_code'] && $arr['item_name'] == $tempArr['item_name']) {
                    $existFlg = true;
                    $arr['quantity'] += $tempArr['quantity'];
                    $arr['netwt'] += $tempArr['netwt'];
                    $arr['grosswt'] += $tempArr['grosswt'];
                    $arr['measure'] += $tempArr['measure'];
                    $arr['amount'] += $tempArr['amount'];
                    $sizeColorList = explode(",", $arr['size_color']);
                    $tempSizeColorList = explode(",", $tempArr['size_color']);
                    foreach ($tempSizeColorList as &$tempSizeColor){
                        if (count($sizeColorList) > 0) {
                            $sameFlg = false;
                            foreach($sizeColorList as &$sizeColor) {
                                $condition1 = substr($sizeColor, 0, strrpos($sizeColor, '---'));
                                $condition2 = substr($tempSizeColor, 0, strrpos($tempSizeColor, '---'));
                                // if item existed then add quantity
                                if($condition1 == $condition2){
                                    $number1 = substr($sizeColor, strrpos($sizeColor, '---') + 3);
                                    $number2 = substr($tempSizeColor, strrpos($tempSizeColor, '---') + 3);
                                    $sizeColor = $condition1."---".($number1 + $number2);
                                    $sameFlg = true;
                                    break;
                                }
                            }
                             // if item not existed then add item into list
                            if (!$sameFlg) {
                                array_push($sizeColorList, $tempSizeColor);
                            }
                        } else {
                            $arr['size_color'] = $tempArr['size_color'];
                        }
                    }
                    $arr['size_color'] = implode(",", $sizeColorList);
                }
            }
            if (!$existFlg) {
                array_push($arr1, $tempArr);
            }
        }
        return $arr1;
    }
    function isEmptyRow($row) {
        foreach($row as $cell){
            if (null !== $cell) return false;
        }
        return true;
    }
    function detach(array &$array, $key) {
        if (!array_key_exists($key, $array)) {
            return null;
        }
        $value = $array[$key];
        unset($array[$key]);
        return $value;
    }

    // gather the same item in succession
    function detachArray($item_list) {
        // divide array by number_from
        $gatherPackage = [];
        foreach($item_list as $index => $item){
            $tmpFrom = preg_replace('/\D/', '', $item['number_from']);
            if($index == 0) {
                $gatherPackage[$tmpFrom] = array($item);
                continue;
            }
            $sameFromFlg = false;
            foreach($gatherPackage as $index => $package){
                if ($tmpFrom == $index) {
                    array_push( $gatherPackage[$tmpFrom], $item);
                    $sameFromFlg = true;
                    break;
                }
            }
            if(!$sameFromFlg) {
                $gatherPackage[$item['number_from']] = array($item);
            }
        }
         // sort number_from
        ksort($gatherPackage);
        $itemList = [];
        $mergePackage = [];
        $idxPackage = 0;
        // check all item in array number_from are same all item in array next number_from
        foreach($gatherPackage as $index => &$package) {
            $idxPackage ++;
            $addItem = [];
            $mergeFlg = false;
            if(in_array($index, $mergePackage)){
                continue;
            }
            if( $idxPackage == count($gatherPackage)) {
                $itemList = array_merge($itemList, $package);
                break;
            }
            foreach($gatherPackage as $idx => &$packageTmp) {
                if($index >= $idx) {
                    continue;
                }
                if(count($package) != count($packageTmp)){
                    $mergeFlg = true;
                    if(count($addItem) > 0) {
                        $itemList = array_merge($itemList, $addItem);
                    } else {
                        $itemList = array_merge($itemList, $package);
                    }
                    break;
                }
                foreach($package as &$item){
                    $sameFlg = false;
                    $pos = strrpos($item['size_color'], "---");
                    $itemSizeColor = substr($item['size_color'], 0, $pos);
                    foreach($packageTmp as $itemTmp){
                        $posTmp = strrpos($itemTmp['size_color'], "---");
                        $itemTmpSizeColor = substr($itemTmp['size_color'], 0, $posTmp);
                        if($item['item_code'] == $itemTmp['item_code'] && $itemTmpSizeColor == $itemSizeColor && ($item['number_to'] + 1) == $itemTmp['number_from']) {
                            $item['quantity'] += $itemTmp['quantity'];
                            $item['number_to'] = $itemTmp['number_from'];
                            $sameFlg = true;
                        }
                    }
                    if(!$sameFlg) {
                        $mergeFlg = true;
                        $itemList = array_merge($itemList, $package);
                        break 2;
                    }
                }
                $addItem = $package;
                array_push($mergePackage, $idx);
            }
            if(!$mergeFlg) {
                $mergeFlg = false;
                $itemList = array_merge($itemList, $addItem);
            }
        }
        return $itemList;
    }
}