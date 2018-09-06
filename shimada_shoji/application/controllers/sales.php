<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define("KVT_SHEET_START_ROW", 2);
define("AMOUNT_START_ROW", 2);
define("SALES_EXCEL_COL", array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W'));
define("SALES_FIRST_EXCEL_COL", array('C'=>'order_receive_no','D'=>'partition_no','E'=>'order_receive_date',
'F'=>'company_name','G'=>'input_user_nm','H'=>'sum_quantity','I'=>'sum_amount',
'J'=>'dif_amount','K'=>'delivery_date','L'=>'payment_date','M'=>'odr_status'));
define("SALES_SECOUND_EXCEL_COL", array('N'=>'item_code', 'O'=>'item_name','P'=>'composition',
'Q'=>'size','R'=>'color','S'=>'quantity_unit','T'=>'sell_price_currency','U'=>'base_price_currency',
'V'=>'amount','W'=>'dif_amount'));

define('SALES_START_ROW', 3);
require 'vendor/autoload.php';
// Excel export Column define


class Sales extends MY_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('order_received_model');
        $this->load->model('order_received_details_model');
        $this->load->model('dvt_model');
        $this->load->model('print_contract_model');
        define('STATUS_DVT_FINISH', '015');
        define('STATUS_DELIVERY_FINISH', '014');
    }

    // function set response to view
    public function _response($success, $code = null, $data = null) {
        $res = array(
			"success" => $success, 
			"code" => $code, 
			"message" => $this->lang->line($code), 
			"data" => $data
		);
		return $res;
    }

    public function index()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $this->data['title'] = $this->lang->line('sales_list');
        $currentUser = $this->session->userdata('user');
        $this->data['permissionID'] = $currentUser['permission_id'];
        $this->data['screen_id'] = 'SES0020';
        // Load the subview and Pass to the master view
        $content = $this->load->view('sales/index.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    public function sales_another()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $this->data['title'] = $this->lang->line('sales_list');
        $currentUser = $this->session->userdata('user');
        $this->data['permissionID'] = $currentUser['permission_id'];
        $this->data['screen_id'] = 'SES0020';
        // Load the subview and Pass to the master view
        $content = $this->load->view('sales/sales_another.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    public function searchDVT2()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');

        $orderReceivedList = $this->dvt_model->search_sale($recordsTotal, $recordsFiltered, "2", $params);
        foreach ($orderReceivedList as $key => &$order_receive) {
            $maxTimes = $this->dvt_model->getMaxTimes('2', $order_receive['dvt_no'],  $order_receive['order_date'], '0');
            if($maxTimes >= 2){
                $order_receive['times_count'] = $maxTimes;
            }

            $order_receive['edit_flg'] = '0'; 
        }
          
        echo json_encode(array(
            'data' => $orderReceivedList,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }

    public function searchDVT1()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');

        $orderReceivedList = $this->dvt_model->search_sale($recordsTotal, $recordsFiltered, "1", $params);
        foreach ($orderReceivedList as $key => &$order_receive) {
            $maxTimes = $this->dvt_model->getMaxTimes('1', $order_receive['dvt_no'],  $order_receive['order_date'], '0');
            if($maxTimes >= 2){
                $order_receive['times_count'] = $maxTimes;
            }
        }
          
        echo json_encode(array(
            'data' => $orderReceivedList,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }

    public function save_sales()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        
        $params = $this->input->post();

        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date('Y/m/d H:i:s');
        $edit_date = null;
        if($params['edit_date'] != '') {
            $edit_date = $params['edit_date'];
        }

        $data = array(
            'contract_no' => $params['contract_no'],
            'delivery_no' => $params['delivery_no'],
            'delivery_date' => $params['delivery_date'],
            'times' => $params['times'],
            'edit_date'   => $edit_date
        );
            
        $query = $this->print_contract_model->check_update($data);
        if(!$query) {
            echo json_encode($this->_response(false, 'COMMON_E001'));
            return;
        }
        
        if ($params['official_delivery_date'] != null && $params['official_delivery_date'] != '' ){
            $params['status'] = STATUS_DELIVERY_FINISH;
        } else {
            $params['official_delivery_date'] = null;
        }

        if($params['payment_date'] != null && $params['payment_date'] != '' ){

            $data = array();
            $data['dvt_no'] = $params['delivery_no'];
            $data['order_date'] = $params['delivery_date'];
            $data['times'] = $params['times'];
            $data['edit_date']   = $edit_date;
            $data['edit_user'] = $currentUser['employee_id'];
            $data['status'] = STATUS_DVT_FINISH;
            $updateResult = $this->dvt_model->update($data, $params['kubun_update']);

            if($updateResult === FALSE){
                echo json_encode($this->_response(false, 'save_fail'));
                return;
            }
        } else {
            $params['payment_date'] = null;
        }
        unset($params['kubun_update']);
        foreach($params as $key => $value) {
            if($value == ''){
                $params[$key] = null;
            }
        }
        $params['edit_date'] = $currentDate;
        $params['edit_user'] = $currentUser['employee_id'];

        $result = $this->print_contract_model->updateContractPrint($params);
        if(!$result) {
            echo json_encode($this->_response(false, 'save_fail'));
            return;
        }

        echo json_encode($this->_response(true, 'save_success'));
    }

    public function delete_sales()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        
        $params = $this->input->post();

        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date('Y/m/d H:i:s');
        $edit_date = null;
        if($params['edit_date'] != '') {
            $edit_date = $params['edit_date'];
        }

        $data = array(
            'contract_no' => $params['contract_no'],
            'delivery_no' => $params['delivery_no'],
            'delivery_date' => $params['delivery_date'],
            'times' => $params['times'],
            'edit_date'   => $edit_date
        );
            
        $query = $this->print_contract_model->check_update($data);
        if(!$query) {
            echo json_encode($this->_response(false, 'COMMON_E001'));
            return;
        }

        $result = $this->print_contract_model->deleteContractPrint($params);
        if(!$result) {
            echo json_encode($this->_response(false, 'del_fail'));
            return;
        }

        echo json_encode($this->_response(true, 'del_success'));
    }

    public function japan_order_excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = json_decode($this->input->get('data_export'), true);
        $epeData = $this->dvt_model->getDataForSalesExport('001', 1, $params);
        $hnData = $this->dvt_model->getDataForSalesExport('002', 1, $params);
        $arr = array_merge($epeData,$hnData);
        $dvtList = array();
        $staffList = array();
        $kvtList = array();
        if(sizeof($arr) > 0) {
            foreach ($arr as $key => $value) {
                $dvt = $this->dvt_model->getItemsListForSalesExport("1", $value);
                $staff = $this->dvt_model->getItemsListForSalesExport2("1", $value);
                $kvt = $this->dvt_model->getAllKVT("1", true, $value);
                array_push($dvtList, $dvt[0]);
                array_push($staffList, $staff[0]);
                array_push($kvtList, $kvt[0]);
            }
        }

        $epeList = array();
        foreach($epeData as $index => $epe){
            $tempArray = array(
                ($index+1),
                $epe['company_name'],
                $epe['invoice_no'],
                $epe['contract_no'],
                $epe['delivery_date'],
                $epe['passage_date'],
                $epe['official_delivery_date'],
                $epe['delivery_no'],
                $epe['note']
            );
            array_push($epeList, $tempArray);
        }
        
        $hnList = array();
        foreach($hnData as $index => $hn){
            $tempArray = array(
                ($index+1),
                $hn['company_name'],
                $hn['contract_no'],
                $hn['delivery_date'],
                $hn['inventory_voucher_excel_no'],
                $hn['invoice_no'],
                $hn['receipt_type'],
                $hn['passage_date'],
                $hn['official_delivery_date'],
                $hn['delivery_no'],
                $hn['submit_contract_date'],
                $hn['note']
            );
            array_push($hnList, $tempArray);
        }
        $this->writeDataToExcel('views/sales/template.xlsx', $epeList, $hnList, $kvtList, $dvtList, $staffList);
    }

    public function another_order_excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = json_decode($this->input->get('data_export'), true);
        $epeData = $this->dvt_model->getDataForSalesExport('001', 2, $params);
        $hnData = $this->dvt_model->getDataForSalesExport('002', 2, $params);
        $arr = array_merge($epeData,$hnData);
        $dvtList = array();
        $staffList = array();
        $kvtList = array();
        if(sizeof($arr) > 0) {
            foreach ($arr as $key => $value) {
                $dvt = $this->dvt_model->getItemsListForSalesExport("2", $value);
                $staff = $this->dvt_model->getItemsListForSalesExport2("2", $value);
                $kvt = $this->dvt_model->getAllKVT("2", true, $value);
                if(count($dvt) > 0) {
                    array_push($dvtList, $dvt[0]);
                }
                if(count($staff) > 0) {
                 array_push($staffList, $staff[0]);
                }
                if(count($kvt) > 0) {
                    array_push($kvtList, $kvt[0]);
                }
            }
        }
        
        $epeList = array();
        foreach($epeData as $index => $epe){
            $tempArray = array(
                ($index+1),
                $epe['company_name'],
                $epe['invoice_no'],
                $epe['contract_no'],
                $epe['contract_date'],
                $epe['passage_date'],
                $epe['official_delivery_date'],
                $epe['delivery_no'],
                $epe['note']
            );
            array_push($epeList, $tempArray);
        }
        
        $hnList = array();
        foreach($hnData as $index => $hn){
            $tempArray = array(
                ($index+1),
                $hn['company_name'],
                $hn['contract_no'],
                $hn['contract_date'],
                $hn['inventory_voucher_excel_no'],
                $hn['invoice_no'],
                $hn['receipt_type'],
                $hn['passage_date'],
                $hn['official_delivery_date'],
                $hn['delivery_no'],
                $hn['submit_contract_date'],
                $hn['note']
            );
            array_push($hnList, $tempArray);
        }
        $this->writeDataToExcel('views/sales/template.xlsx', $epeList, $hnList, $kvtList, $dvtList, $staffList);
    }

    private function writeDataToExcel($filePath, $epeList, $hnList, $kvt_list, $dvtList, $staffList){
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);

        // set data for sheet 1
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->insertNewRowBefore( 7 + 1, count($epeList) - 1);
        $spreadsheet->getActiveSheet()->fromArray( $epeList, NULL, 'A7');
        // $lastColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();
        // $lastRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
        // $lastColumn++;
        // for ($column = 'A'; $column != $lastColumn; $column++) {
        //     $spreadsheet->getActiveSheet()->duplicateStyle($spreadsheet->getActiveSheet()->getStyle("$column"."7"), "$column"."8:$column$lastRow");
        // }

        // set data for sheet 2
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->insertNewRowBefore( 6 + 1, count($hnList) - 1);
        $spreadsheet->getActiveSheet()->fromArray( $hnList, NULL, 'A6');
        // $lastColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();
        // $lastRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
        // $lastColumn++;
        // for ($column = 'A'; $column != $lastColumn; $column++) {
        //     $spreadsheet->getActiveSheet()->duplicateStyle($spreadsheet->getActiveSheet()->getStyle("$column"."6"), "$column"."7:$column$lastRow");
        // }

        
        /** Sheet 3 */
        $spreadsheet->setActiveSheetIndex(2);
        $style = $spreadsheet->getActiveSheet()->getStyle('B2');
        $kvtData = [];
        foreach($kvt_list as $index => $kvt){
            $pos = strrpos($kvt['pv_no'], "-");
            if($pos !== FALSE) {
                $kvt['pv_no'] = trim(substr($kvt['pv_no'], 0, $pos));
            }
            $tempArr = array(
                $kvt['order_date'],
                trim($kvt['dvt_no']),
                trim($kvt['kvt_no']),
                $kvt['times_count'] >= 2 ? $kvt['times'] : null,
                $kvt['staff'],
                $kvt['staff_id'],
                $kvt['assistance'],
                $kvt['item_code'],
                $kvt['item_name'],
                $kvt['color'],
                $kvt['size'],
                $kvt['quantity'],
                $kvt['contract_no'],
                $kvt['delivery_date'],
                $kvt['address'],
                $kvt['delivery_method'],
                $kvt['pv_no'],
                $kvt['arrival_date'],
                $kvt['item_quantity']
            );
            array_push($kvtData, $tempArr);
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore(KVT_SHEET_START_ROW + 1, count($kvtData));
        $spreadsheet->getActiveSheet()->fromArray( $kvtData, NULL, 'A'.(KVT_SHEET_START_ROW + 1));
        $spreadsheet->getActiveSheet()->removeRow(KVT_SHEET_START_ROW, 1);

        /** Sheet 4 */
        $spreadsheet->setActiveSheetIndex(3);
        $dvtListData = array();
        foreach($dvtList as $index => $dvt){
            $currency = isset($dvt['currency']) ? $dvt['currency'] : '';
            $tempArray = array(
                ($index+1),
                $dvt['order_date'],
                trim($dvt['dvt_no']),
                trim($dvt['kvt_no']),
                $dvt['times_count'] >= 2 ? $dvt['times'] : null,
                $dvt['staff'],
                $dvt['staff_id'],
                $dvt['item_code'],
                $dvt['size'],
                $dvt['color'],
                $dvt['quantity'],
                ($currency == 'USD' && $dvt['sell_price']) ? $dvt['sell_price'] : null,
                $currency == 'JPY' && $dvt['sell_price'] ? $dvt['sell_price'] : null,
                $currency == 'USD' && $dvt['sell_price'] ? ($dvt['sell_price'] * $dvt['quantity']) : null,
                $currency == 'JPY' && $dvt['sell_price'] ? ($dvt['sell_price'] * $dvt['quantity']) : null,
                $currency == 'USD' && $dvt['base_price'] ? $dvt['base_price'] : null,
                $currency == 'JPY' && $dvt['base_price'] ? $dvt['base_price'] : null,
                $currency == 'USD' && $dvt['base_price'] ? ($dvt['base_price'] * $dvt['quantity']) : null,
                $currency == 'JPY' && $dvt['base_price'] ? ($dvt['base_price'] * $dvt['quantity']) : null,
                $currency == 'USD' && $dvt['shosha_price'] ? $dvt['shosha_price'] : null,
                $currency == 'JPY' && $dvt['shosha_price'] ? $dvt['shosha_price'] : null,
                $currency == 'USD' && $dvt['shosha_price'] ? ($dvt['shosha_price'] * $dvt['quantity']) : null,
                $currency == 'JPY' && $dvt['shosha_price'] ? ($dvt['shosha_price'] * $dvt['quantity']) : null,
                $currency == 'USD' && $dvt['sell_price'] &&  $dvt['base_price'] ? ($dvt['sell_price'] - $dvt['base_price']): null,
                $currency == 'JPY' && $dvt['sell_price'] &&  $dvt['base_price'] ? ($dvt['sell_price'] - $dvt['base_price']) : null,
                $currency == 'USD' && $dvt['shosha_price'] &&  $dvt['sell_price'] ? ($dvt['shosha_price'] - $dvt['sell_price']) : null,
                $currency == 'JPY' && $dvt['shosha_price'] &&  $dvt['sell_price'] ? ($dvt['shosha_price'] - $dvt['sell_price']) : null,
            );
            array_push($dvtListData, $tempArray);
        }

        // echo '<pre>'; print_r($dvtListData); echo '</pre>'; return;
        $spreadsheet->getActiveSheet()->insertNewRowBefore(AMOUNT_START_ROW + 2, count($dvtListData));
        $spreadsheet->getActiveSheet()->fromArray( $dvtListData, NULL, 'B3');

        /** Sheet 5 */
        $spreadsheet->setActiveSheetIndex(4);
        $staffData = array();
        $index = 0;
        foreach($staffList as $staff){
            $currency = isset($staff['currency']) ? $staff['currency'] : '';
            $existFlg = false;
            foreach ($staffData as &$tempStaff) {
                if ($staff['staff'] == $tempStaff[1] && $staff['staff_id'] == $tempStaff[2]) {
                    $existFlg = true;
                    if($currency == 'USD') {
                        $tempStaff[3] = $staff['sum_sell_amount'];
                        $tempStaff[5] = $staff['sum_base_amount'];
                        $tempStaff[7] = $staff['sum_shosha_amount'];
                        $tempStaff[9] = ($staff['sum_sell_amount'] &&  $staff['sum_base_amount']) ? ($staff['sum_sell_amount'] - $staff['sum_base_amount']) : null;
                        $tempStaff[11] = ($staff['sum_shosha_amount'] &&  $staff['sum_sell_amount']) ? ($staff['sum_shosha_amount'] - $staff['sum_sell_amount']) : null;
                    } else if ($currency == 'JPY') {
                        $tempStaff[4] =  $staff['sum_sell_amount'];
                        $tempStaff[6] = $staff['sum_base_amount'];
                        $tempStaff[8] = $staff['sum_shosha_amount'];
                        $tempStaff[10] = ($staff['sum_sell_amount'] &&  $staff['sum_base_amount']) ? ($staff['sum_sell_amount'] - $staff['sum_base_amount']) : null;
                        $tempStaff[12] = ($staff['sum_shosha_amount'] &&  $staff['sum_sell_amount']) ? ($staff['sum_shosha_amount'] - $staff['sum_sell_amount']) : null;
                    }
                }
            }
            if (!$existFlg ) {
                $tempArray = array(
                    (++$index),
                    $staff['staff'],
                    $staff['staff_id'],
                    $currency == 'USD' && $staff['sum_sell_amount'] ? $staff['sum_sell_amount'] : null,
                    $currency == 'JPY' && $staff['sum_sell_amount'] ? $staff['sum_sell_amount'] : null,
                    $currency == 'USD' && $staff['sum_base_amount'] ? $staff['sum_base_amount'] : null,
                    $currency == 'JPY' && $staff['sum_base_amount'] ? $staff['sum_base_amount'] : null,
                    $currency == 'USD' && $staff['sum_shosha_amount'] ? $staff['sum_shosha_amount'] : null,
                    $currency == 'JPY' && $staff['sum_shosha_amount'] ? $staff['sum_shosha_amount'] : null,
                    $currency == 'USD' && $staff['sum_sell_amount'] &&  $staff['sum_base_amount'] ? ($staff['sum_sell_amount'] - $staff['sum_base_amount']): null,
                    $currency == 'JPY' && $staff['sum_sell_amount'] &&  $staff['sum_base_amount'] ? ($staff['sum_sell_amount'] - $staff['sum_base_amount']) : null,
                    $currency == 'USD' && $staff['sum_shosha_amount'] &&  $staff['sum_sell_amount'] ? ($staff['sum_shosha_amount'] - $staff['sum_sell_amount']) : null,
                    $currency == 'JPY' && $staff['sum_shosha_amount'] &&  $staff['sum_sell_amount'] ? ($staff['sum_shosha_amount'] - $staff['sum_sell_amount']) : null,
                );
                array_push($staffData, $tempArray);
            }
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore(AMOUNT_START_ROW + 2, count($staffData));
        $spreadsheet->getActiveSheet()->fromArray( $staffData, NULL, 'B3');
        
        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="sale.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

    }
}
