<?php
defined('BASEPATH') or exit('No direct script access allowed');
define("JAPAN_ORDER_START_ROW", 2);
define("JAPAN_ORDER_DVT_EXCEL_COL", 
array('A'=>'inv_flg','B'=>'order_date','C'=>'delivery_require_date',
'D'=>'dvt_no','E'=>'times','F'=>'komoku_name_2','G'=>'staff','H'=>'factory',
'I'=>'address','J'=>'contract_no','K'=>'stype_no','L'=>'o_no','M'=>'factory_require_date',
'N'=>'factory_plan_date','O'=>'delivery_plan_date','P'=>'pv_infor','Q'=>'pv_in_date'
,'R'=>'packing_date','S'=>'measurement_date','T'=>'passage_date','U'=>'factory_delivery_date'
,'V'=>'knq_delivery_date','W'=>'knq_fac_deli_date','X'=>'salesmanname','Y'=>'note','Z'=>'buyer'));

define("JAPAN_ORDER_KVT_EXCEL_COL", 
array('A'=>'order_date','B'=>'dvt_no','C'=>'kvt_no',
'D'=>'times','E'=>'staff','F'=>'staff_id','G'=>'assistance','H'=>'item_code',
'I'=>'item_name','J'=>'color','K'=>'size','L'=>'quantity','M'=>'contract_no',
'N'=>'delivery_date','O'=>'address','P'=>'delivery_method','Q'=>'pv_no'
,'R'=>'arrival_date','S'=>'item_quantity'));
class Japan_Order extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('common_helper');
        $this->load->model('employee_model');
        $this->load->model('partners_model');
        $this->load->model('konpo_model');
        $this->load->model('dvt_model');
        $this->load->model('store_item_model');
        $this->load->model('order_received_details_model');
        $this->load->model('komoku_model');
        $this->load->model('order_received_model');
        $this->load->model('company_model');
        $this->load->model('items_model');
    }
    public function index( $search_dvt = null)
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'JOS0010';
            $this->data['search_dvt'] = $search_dvt;
            $dvt = $this->dvt_model->getAllDVT();
            $overrideDVT = array();
            for ($i = 0; $i < sizeof($dvt); $i++){
                $order_date = $dvt[$i]['order_date'];
                $times = $dvt[$i]['times'];
                $dvt_no = $dvt[$i]['dvt_no'];
                $flag = false; 
                foreach ($overrideDVT as $his){
                    if($his['order_date'] == $order_date && $his['times'] == $times && $his['dvt_no'] == $dvt_no ){
                        $flag = true;
                    }
                }
                if($flag){
                    continue;
                }
                $temp = array( 'inv_flg' => $dvt[$i]['inv_flg'], 
                                'order_date' => $dvt[$i]['order_date'], 
                                'delivery_require_date' => $dvt[$i]['delivery_require_date'],
                                'dvt_no' => $dvt[$i]['dvt_no'],
                                'times' => $dvt[$i]['times'],
                                'komoku_name_2' => $dvt[$i]['komoku_name_2'],
                                'staff' => $dvt[$i]['staff'],
                                'staff_id' => $dvt[$i]['staff_id'],
                                'measurement_date' => $dvt[$i]['measurement_date'],
                                'factory' => $dvt[$i]['factory'],
                                'address' => $dvt[$i]['address'],
                                'contract_no' => $dvt[$i]['contract_no'],
                                'stype_no' => $dvt[$i]['stype_no'],
                                'o_no' => $dvt[$i]['o_no'],
                                'factory_require_date' => $dvt[$i]['factory_require_date'],
                                'factory_plan_date' => $dvt[$i]['factory_plan_date'],
                                'delivery_plan_date' => $dvt[$i]['delivery_plan_date'],
                                'pv_infor' => $dvt[$i]['pv_infor'],
                                'pv_in_date' => $dvt[$i]['pv_in_date'],
                                'packing_date' => $dvt[$i]['packing_date'],
                                'passage_date' => $dvt[$i]['passage_date'],
                                'factory_delivery_date' => $dvt[$i]['factory_delivery_date'],
                                'knq_delivery_date' => $dvt[$i]['knq_delivery_date'],
                                'knq_fac_deli_date' => $dvt[$i]['knq_fac_deli_date'],
                                'note' => $dvt[$i]['note'],
                                'salesmanname' => $dvt[$i]['salesmanname'],
                                'buyer' => $dvt[$i]['buyer'],
                                'status' => $dvt[$i]['status'],
                                'status_name' => $dvt[$i]['status_name'],
                                'edit_date' => $dvt[$i]['edit_date'],
                                'employee_id' => $dvt[$i]['employee_id']);

                array_push($overrideDVT,$temp);
                for ($j = $i+1; $j < sizeof($dvt); $j++){
                    if($dvt[$i]['order_date'] == $dvt[$j]['order_date'] && $dvt[$i]['times'] == $dvt[$j]['times'] && $dvt[$i]['dvt_no'] == $dvt[$j]['dvt_no'] ){
                        $index = sizeof($overrideDVT) - 1;
                        $overrideDVT[$index]['contract_no'] .= ','.$dvt[$j]['contract_no'];
                        $overrideDVT[$index]['stype_no'] .= ','.$dvt[$j]['stype_no'];
                        if(NULL !== $dvt[$j]['o_no'] && "" !== $dvt[$j]['o_no']) $overrideDVT[$index]['o_no'] .= ','.$dvt[$j]['o_no'];
                    }
                }
            }
            foreach($overrideDVT as &$tmpDVT){
                $count = 0;
                foreach($overrideDVT as $tmp){
                    if($tmpDVT['order_date'] == $tmp['order_date'] && $tmpDVT['dvt_no'] == $tmp['dvt_no']){
                        $count++;
                    }
                }
                $tmpDVT['number_times'] = $count;
            }
            $salesman = $this->employee_model->get_fullname_emp();
            $this->data['title'] = $this->lang->line('export_schedule_list');
            $this->data['salesman'] = $salesman;
            $this->data['dvt'] = $overrideDVT;
            $this->data['shipping_method_list'] = $this->komoku_model->get_shipping_method_with_use("1");
            $this->data['currentDate'] = date('d M, Y');
            $data_order = $this->order_received_model->get_order_receive_add();
            if($data_order!=null) {
                foreach($data_order as $key => $value) {
                    $data_order[$key]['check'] = false;
                }
            }

            $order_receive = $data_order;
            if($order_receive != null){
                foreach($order_receive as $index=>$orderRe) {
                    $count = 0;
                    foreach($data_order as $orders) {
                        if($orderRe['order_receive_no'] == $orders['order_receive_no'] && $orderRe['order_receive_date'] == $orders['order_receive_date']) {
                            $count++;
                        }
                    }
                    if($count == 1) {
                        $order_receive[$index]['check_count'] = '1';
                    } else {
                        $order_receive[$index]['check_count'] = '2';
                    }
                }
            }

            $this->data['order_receive_list'] = $order_receive;

            // Load the subview // Pass to the master view
            $content = $this->load->view('japan_order/index.php', $this->data, true);
            $this->load->view('master_page', array('content' => $content));
        }
    }
    // save DVT
    public function save()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        // check dvt is exists ? 
        $check_item = $this->dvt_model->check_dvt_exists($params);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E003'));
            return;
        }
        $lastEditDate = $this->dvt_model->getEditDateDVTByID($params['order_date'], $params['dvt_no'], $params['times']);
        if(sizeof($lastEditDate) > 0){
            if( $params['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
                echo json_encode($this->_response(false, 'COMMON_E001'));
                return;
            }
        }
        $currentUser = $this->session->userdata('user');
            $query = false;
            $data = array(
                'order_date' => $params['order_date'],
                'times' => $params['times'],
                'dvt_no' => $params['dvt_no'],
                'factory_require_date' => $params['factory_require_date'],
                'factory_plan_date' => $params['factory_plan_date'],
                'delivery_plan_date' => $params['delivery_plan_date'],
                'measurement_date' => $params['measurement_date'],
                'packing_date' => $params['packing_date'],
                'passage_date' => $params['passage_date'],
                'pv_in_date' => $params['pv_in_date'],
                'factory_delivery_date' => $params['factory_delivery_date'],
                // 'knq_delivery_date' => $params['knq_delivery_date'],
                // 'knq_fac_deli_date' => $params['knq_fac_deli_date'],
                'salesman' => $params['salesman'],
                'pv_infor' => $params['pv_infor'],
                'note' => $params['note'],
                'buyer' => $params['buyer'],
                'inv_flg' => $params['inv_flg'],
            );
            foreach($data as $key => $value){
                if($value == '') {$data[$key] = null;}
            }
            $data['edit_date'] = date('Y/m/d H:i:s');
            $data['edit_user'] =  $currentUser['employee_id'];

            // Query update items
            $query = $this->dvt_model->update($data);
            if($query) {
                echo json_encode($this->_response(true,'save_success', $this->formatArray($query)));
            } else {
                echo json_encode($this->_response(false,'save_fail'));
            }
    }
    // format dvt array
	public function formatArray($dvt){
        $overrideDVT = array();
        for ($i = 0; $i < sizeof($dvt); $i++){
            $order_date = $dvt[$i]['order_date'];
            $times = $dvt[$i]['times'];
            $dvt_no = $dvt[$i]['dvt_no'];
            $flag = false; 
            foreach ($overrideDVT as $his){
                if($his['order_date'] == $order_date && $his['times'] == $times && $his['dvt_no'] == $dvt_no ){
                    $flag = true;
                }
            }
            if($flag){
                continue;
            }
            $temp = array( 'inv_flg' => $dvt[$i]['inv_flg'], 
                            'order_date' => $dvt[$i]['order_date'], 
                            'delivery_require_date' => $dvt[$i]['delivery_require_date'],
                            'dvt_no' => $dvt[$i]['dvt_no'],
                            'times' => $dvt[$i]['times'],
                            'komoku_name_2' => $dvt[$i]['komoku_name_2'],
                            'staff' => $dvt[$i]['staff'],
                            'staff_id' => $dvt[$i]['staff_id'],
                            'assistance' => $dvt[$i]['assistance'],
                            'measurement_date' => $dvt[$i]['measurement_date'],
                            'factory' => $dvt[$i]['factory'],
                            'address' => $dvt[$i]['address'],
                            'contract_no' => $dvt[$i]['contract_no'],
                            'stype_no' => $dvt[$i]['stype_no'],
                            'o_no' => $dvt[$i]['o_no'],
                            'factory_require_date' => $dvt[$i]['factory_require_date'],
                            'factory_plan_date' => $dvt[$i]['factory_plan_date'],
                            'delivery_plan_date' => $dvt[$i]['delivery_plan_date'],
                            'pv_infor' => $dvt[$i]['pv_infor'],
                            'pv_in_date' => $dvt[$i]['pv_in_date'],
                            'packing_date' => $dvt[$i]['packing_date'],
                            'passage_date' => $dvt[$i]['passage_date'],
                            'print_date' => $dvt[$i]['print_date'],
                            'factory_delivery_date' => $dvt[$i]['factory_delivery_date'],
                            'knq_delivery_date' => $dvt[$i]['knq_delivery_date'],
                            'knq_fac_deli_date' => $dvt[$i]['knq_fac_deli_date'],
                            'note' => $dvt[$i]['note'],
                            'salesmanname' => $dvt[$i]['salesmanname'],
                            'buyer' => $dvt[$i]['buyer'],
                            'status' => $dvt[$i]['status'],
                            'status_name' => $dvt[$i]['status_name'],
                            'edit_date' => $dvt[$i]['edit_date'],
                            'employee_id' => $dvt[$i]['employee_id']);
            array_push($overrideDVT,$temp);
            for ($j = $i+1; $j < sizeof($dvt); $j++){
                if($dvt[$i]['order_date'] == $dvt[$j]['order_date'] && $dvt[$i]['times'] == $dvt[$j]['times'] && $dvt[$i]['dvt_no'] == $dvt[$j]['dvt_no'] ){
                    $index = sizeof($overrideDVT) - 1;
                    $overrideDVT[$index]['contract_no'] .= ','.$dvt[$j]['contract_no'];
                    $overrideDVT[$index]['stype_no'] .= ','.$dvt[$j]['stype_no'];
                    $overrideDVT[$index]['o_no'] .= ','.$dvt[$j]['o_no'];
                }
            }
        }
        return $overrideDVT[0];
    }
    
    // Delete DVT
    public function delete() 
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        
        // check dvt is exists ? 
        $check_item = $this->dvt_model->check_dvt_exists($params);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } 
        $lastEditDate = $this->dvt_model->getEditDateDVTByID($params['order_date'], $params['dvt_no'], $params['times']);
        if(sizeof($lastEditDate) > 0){
            if( $params['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
                echo json_encode($this->_response(false, 'COMMON_E001'));
                return;
            }
        }
        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        // Delete dvt
        $result = $this->dvt_model->delete($params);
        if($result) {
            // update status of pv
            $pvInfor = explode(",",$params['pv_infor']);
            if(sizeof($pvInfor) > 0)
            foreach($pvInfor as $pv){
                $pvNo = explode("-", $pv);
                $data = array(
                    'order_receive_no' => $pvNo[0],
                    'partition_no' => (isset($pvNo[1]) ? $pvNo[1] : 1),
                    'status' => '002',
                    'edit_date' => date('Y/m/d H:i:s'),
                    'edit_user' => $currentUser['employee_id'],
                );
                $this->order_received_model->updatePVStatus($data);
            }
            echo json_encode($this->_response(true,'del_success'));
        } else {
            echo json_encode($this->_response(false,'del_fail'));
        }
    }
	public function detail($order_date = null, $times = null, $dvt_no = null)
    {
        $newdata = array(
            'fromScreenID'  => 'JOS0010',
            'toScreenID'    => 'JOS0020',
            'DeliveryNo'    => $dvt_no,
            'processID'     => 1
        );
        $this->session->set_userdata($newdata);
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'JOS0020';
            $this->data['title'] = $this->lang->line('export_schedule_detail');
            $listKVT = $this->dvt_model->getAllKVT();
            $pvNoList = $this->order_received_details_model->getAllPVNo();
            $editFlg = 0;
            $urlParams = array();
            if($order_date != null && $order_date != '' && $times != null && $times != '' && $dvt_no != null && $dvt_no != ''){
                $listKVT = $this->dvt_model->getAllKVTByID($order_date, $dvt_no, $times);
                $urlParams = array($order_date, $dvt_no, $times);
                $editFlg = 1;
            }
            $this->data['listKVT'] = formatKVTArray($listKVT);
            $this->data['pvNoList'] = $pvNoList;
            $this->data['editFlg'] = $editFlg;
            $this->data['urlParams'] = $urlParams;
            // Load the subview
            $content = $this->load->view('japan_order/detail.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    // save change KVT
	public function saveKVT()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
         // check kvt is exists ? 
         $check_item = $this->dvt_model->check_kvt_exists($params);
         if(!$check_item) {
             echo json_encode($this->_response(false, 'JOS0010_E002'));
             return;
         } 
         $lastEditDate = $this->dvt_model->getEditDateOfKVTByID($params['order_date'], $params['dvt_no'],$params['kvt_no'], $params['times']);
         if(sizeof($lastEditDate) > 0){
            if($params['edit_date'] != '' && $lastEditDate['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
                echo json_encode($this->_response(false, 'COMMON_E001'));
                return;
            }
        }
        $currentUser = $this->session->userdata('user');
            $query = false;
            $data = array(
                'order_date' => $params['order_date'],
                'times' => $params['times'],
                'dvt_no' => $params['dvt_no'],
                'kvt_no' => $params['kvt_no'],
                'stype_no' => $params['stype_no'],
                'o_no' => $params['o_no'],
            );
            foreach($data as $key => $value){
                if($value == '') {$data[$key] = null;}
            }
            $data['edit_date'] = date('Y/m/d H:i:s');
            $data['edit_user'] =  $currentUser['employee_id'];

            // Query update kvt
            $query = $this->dvt_model->updateKVT($data);
            if($query) {
                echo json_encode($this->_response(true,'save_success', formatKVTArray($query)[0]));
            } else {
                echo json_encode($this->_response(false,'save_fail'));
            }
    }
    // Delete KVT
    public function deleteKVT() 
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        
        // check kvt is exists ? 
        $check_item = $this->dvt_model->check_kvt_exists($params);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } 
        $lastEditDate = $this->dvt_model->getEditDateOfKVTByID($params['order_date'], $params['dvt_no'],$params['kvt_no'], $params['times']);
        if(sizeof($lastEditDate) > 0){
           if($params['edit_date'] != '' && $lastEditDate['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
               echo json_encode($this->_response(false, 'COMMON_E001'));
               return;
           }
       }
        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        // Delete kvt
        $result = $this->dvt_model->deleteKVT($params);
        if($result) {
            echo json_encode($this->_response(true,'del_success'));
        } else {
            echo json_encode($this->_response(false,'del_fail'));
        }
    }
    // save change Item
	public function saveKVTItem()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $lastEditDate = $this->dvt_model->getEditDateOfKVTByItemID($params['order_date'], $params['dvt_no'],$params['kvt_no'], $params['detail_no'], $params['times'], $params['item_code'], $params['color'], $params['size']);
        if(sizeof($lastEditDate) > 0){
           if($params['edit_date'] != '' && $lastEditDate['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
               echo json_encode($this->_response(false, 'COMMON_E001'));
               return;
           }
       }
        $currentUser = $this->session->userdata('user');
            $query = false;
            $data = array(
                'order_date' => $params['order_date'],
                'times' => $params['times'],
                'dvt_no' => $params['dvt_no'],
                'kvt_no' => $params['kvt_no'],
                'item_code' => $params['item_code'],
                'detail_no' => $params['detail_no'],
                'color' => $params['color'],
                'size' => $params['size'],
                'quantity' => $params['quantity'],
                'packing_date' => $params['packing_date'],
                'shosha_price' => $params['shosha_price'],
                'sell_price' => $params['sell_price'],
                'base_price' => $params['base_price'],
                'buy_price' => $params['buy_price'],
                'pv_no' => $params['pv_no'],
            );
            foreach($data as $key => $value){
                if($key == 'size' || $key == 'color'){
                    continue;
                }
                if($value == '') {$data[$key] = null;}
            }
            $data['edit_date'] = date('Y/m/d H:i:s');
            $data['edit_user'] =  $currentUser['employee_id'];

            // Query update kvt
            $query = $this->dvt_model->updateKVTItem($data);
            // update currency of dvt
            if(isset($params['currency']) && $params['currency'] != ''){
                $data['currency'] = $params['currency'];
                $this->dvt_model->updateDVTCurrency($data);
            }
            if($query) {
                echo json_encode($this->_response(true,'save_success', formatKVTArray($query)[0]));
            } else {
                echo json_encode($this->_response(false,'save_fail'));
            }
    }
    // Delete KVT Item
    public function deleteKVTItem() 
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        
        // check kvt is exists ? 
        $check_item = $this->dvt_model->check_kvt_item_exists($params);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } 
        $lastEditDate = $this->dvt_model->getEditDateOfKVTByItemID($params['order_date'], $params['dvt_no'],$params['kvt_no'], $params['detail_no'], $params['times'], $params['item_code'], $params['color'], $params['size']);
        if(sizeof($lastEditDate) > 0){
           if($params['edit_date'] != '' && $lastEditDate['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
               echo json_encode($this->_response(false, 'COMMON_E001'));
               return;
           }
       }
        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        // Delete kvt item
        $result = $this->dvt_model->deleteKVTItem($params);
        if($result === FALSE) {
            echo json_encode($this->_response(false,'del_fail'));
        } else {
            echo json_encode($this->_response(true,'del_success', sizeof(formatKVTArray($result)) > 0 ? formatKVTArray($result)[0] : [] ));
        }
    }
    // get item of KVT
    public function searchItems(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $kvtList = $this->dvt_model->getAllKVTByKVTID($params['order_date'],$params['dvt_no'],$params['kvt_no'],$params['times']);
        $price = array('sell_price','base_price','shosha_price','buy_price');
        foreach($kvtList as $index => $kvt){
            $currency = $this->order_received_model->getPVCurrency($kvt['pv_no']);
            if(sizeof($currency) > 0){
                $tail = strtolower('_'.$currency[0]['currency']);
                foreach($price as $pri){
                    $tempPrice = $pri.$tail;
                    if($kvt[$pri] == null){
                        $kvtList[$index][$pri] = $kvt[$tempPrice];
                    }
                }
            }
        }
        echo json_encode(array('data' => formatKVTArray($kvtList)[0]['detail']));
    }
    // search KVT
    public function searchKVT(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $listKVT = $this->dvt_model->getAllKVT();
        $params = $this->input->post('param');
        if($params['order_date'] != null && $params['order_date'] != '' && $params['dvt_no'] != null && $params['dvt_no'] != '' && $params['times'] != null && $params['times'] != ''){
            $listKVT = $this->dvt_model->getAllKVTByID($params['order_date'], $params['dvt_no'], $params['times']);
        }
        $data = formatKVTArray($listKVT);
        echo json_encode(array('data' => $data));
    }
    public function getStoreItems(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $kvtList = $this->dvt_model->getDVTByID($params['order_date'],$params['dvt_no'],$params['times']);
        if(sizeof($kvtList) <= 0){
            echo json_encode(array('data' => []));
            return;
        }
        $pvNoList = explode(",", $kvtList[0]['pv_infor']);
        $storeItemsList = $this->order_received_details_model->getItemsByOrderReceiveNo($pvNoList);
        foreach($storeItemsList as $id => $storeItems){
            $tail = '_'.($storeItems['currency'] != null && $storeItems['currency'] != '' ? $storeItems['currency'] : 'usd');
            $sell = strtolower('sell_price'.$tail);
            $shosha = strtolower('shosha_price'.$tail);
            // $storeItemsList[$id]['sell_price'] = '';
            if(($storeItems['sell_price'] == null || $storeItems['sell_price'] == '') && isset($storeItems[$sell])){
                $storeItemsList[$id]['sell_price'] = $storeItems[$sell];
            }
            $storeItemsList[$id]['shosha_price'] = '';
            if(isset($storeItems[$shosha])){
                $storeItemsList[$id]['shosha_price'] = $storeItems[$shosha];
            }
        }
        echo json_encode(array('data' => $storeItemsList));

    }
    //search Items when change PV_NO
    public function searchItemByPVNo(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $storeItems = $this->store_item_model->getStoreItemsByID($params);
        // echo json_encode(array('data' => $storeItems));
        echo json_encode($this->_response(true,'save_success', $storeItems));
    }
    // insert KVT
    public function insertKVT(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $query = false;
        $dvtInfo = $this->dvt_model->getDVTForKVTInsert($params['urlParams'][0], $params['urlParams'][1], $params['urlParams'][2]);

        $insertData = array();
        if(sizeof($params['items']) > 0){
            foreach($params['items'] as $item){
                $data = array(
                    'order_date' => $params['urlParams'][0],
                    'times' => $params['urlParams'][2],
                    'dvt_no' => $params['urlParams'][1],
                    'kvt_no' => $params['kvt_no'],
                    'stype_no' => $params['stype_no'],
                    'o_no' => $params['o_no'],
                    'delivery_date' => $params['delivery_date'],
                    'contract_no' => $params['contract_no'],
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'composition_1' => $item['composition_1'],
                    'composition_2' => $item['composition_2'],
                    'composition_3' => $item['composition_3'],
                    'item_jp_code' => ($item['jp_code'] == null) ? "" : $item['jp_code'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'unit' => $item['unit'],
                    'size_unit' => $item['size_unit'],
                    'quantity' => $item['quantity'],
                    'base_price' => $item['base_price'],
                    'sell_price' => $item['sell_price'],
                    'shosha_price' => $item['shosha_price'],
                    'inv_no' => $item['inv_no'],
                    'pv_no' => $item['order_receive_no'].'-'.$item['partition_no'].'*'.$item['quantity'],
                    'staff' => isset($dvtInfo['staff']) ? $dvtInfo['staff'] : '',
                    'staff_id' => isset($dvtInfo['staff_id']) ? $dvtInfo['staff_id'] : '',
                    'assistance' => isset($dvtInfo['assistance']) ? $dvtInfo['assistance'] : '',
                    'delivery_method' => isset($dvtInfo['delivery_method']) ? $dvtInfo['delivery_method'] : '',
                    'factory' => isset($dvtInfo['factory']) ? $dvtInfo['factory'] : '',
                    'address' => isset($dvtInfo['address']) ? $dvtInfo['address'] : '',
                );
                $check_item = $this->dvt_model->check_kvt_item_exists($data);
                if($check_item){
                    continue;
                }
                foreach($data as $key => $value){
                    if($key == 'size' || $key == 'color' || $key == 'item_jp_code'){
                        continue;
                    }
                    if($value == '') {$data[$key] = null;}
                }
                $data['create_date'] = date('Y/m/d H:i:s');
                $data['create_user'] =  $currentUser['employee_id'];
                array_push($insertData, $data);
            }
        }
        $countPV = array();
        if(sizeof($params['allPV']) > 0){
            foreach($params['allPV'] as $item){
                $pv_key = $item['order_receive_no'].'*'.$item['partition_no'].'*'.$item['order_receive_date'];
                if (array_key_exists($pv_key, $countPV)) {
                    $countPV[$pv_key] += 1;
                }else{
                    $countPV[$pv_key] = 1;
                }
            }
        }
        $result = $this->dvt_model->insertKVT($insertData);
        if ($result) {
            foreach($countPV as $key => $value){
                $pv = explode("*", $key);
                $numPV = $this->order_received_details_model->getCountPV($pv[0], $pv[1], $pv[2]);
                if($numPV[0]['count'] == $value){
                    $pvData = array(
                        'order_receive_no' => $pv[0],
                        'partition_no' => $pv[1],
                        'order_receive_date' => $pv[2],
                        'status' => '009', //梱包指図済み
                        'edit_user' => $currentUser['employee_id'],
                        'edit_date' => date('Y/m/d H:i:s'),
                    );
                    $this->order_received_model->updatePVStatus($pvData);
                }
            }
            echo json_encode($this->_response(true,'save_success'));
        }else{
            echo json_encode($this->_response(false,'save_fail'));
        }
    }
     // update KVT
     public function updateDetailKVT(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $query = false;
        $insertData = array();
        $updateData = array();
        $countPV = array();
        if(sizeof($params['items']) > 0){
            foreach($params['items'] as $item){
                $data = array(
                    'order_date' => $params['kvt']['order_date'],
                    'times' => $params['kvt']['times'],
                    'dvt_no' => $params['kvt']['dvt_no'],
                    'kvt_no' => $params['kvt_no'],
                    'stype_no' => $params['stype_no'],
                    'o_no' => $params['o_no'],
                    'edit_date' => $params['kvt']['edit_date'],
                    'contract_no' => $params['contract_no'],
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'composition_1' => $item['composition_1'],
                    'composition_2' => $item['composition_2'],
                    'composition_3' => $item['composition_3'],
                    'item_jp_code' => ($item['jp_code'] == null) ? "" : $item['jp_code'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'unit' => $item['unit'],
                    'size_unit' => $item['size_unit'],
                    'quantity' => $item['quantity'],
                    'inv_no' => $item['inv_no'],
                    'sell_price' => $item['sell_price'],
                    'base_price' => $item['base_price'],
                    'shosha_price' => $item['shosha_price'],
                    'pv_no' => $item['order_receive_no'].'-'.$item['partition_no'].'*'.$item['quantity'],
                );
                $pv_key = $item['order_receive_no'].'*'.$item['partition_no'].'*'.$item['order_receive_date'];
                if (array_key_exists($pv_key, $countPV)) {
                    $countPV[$pv_key] += 1;
                }else{
                    $countPV[$pv_key] = 1;
                }
                foreach($data as $key => $value){
                    if($key == 'size' || $key == 'color'){
                        continue;
                    }
                    if($value == '') {$data[$key] = null;}
                }
                // $lastEditDate = $this->dvt_model->getEditDateOfKVTByID($data['order_date'], $data['dvt_no'],$data['kvt_no'], $data['times']);
                // if(sizeof($lastEditDate) > 0){
                //     if($data['edit_date'] != '' && $lastEditDate['edit_date'] != '' && $lastEditDate['edit_date'] != $data['edit_date']){
                //         echo json_encode($this->_response(false, 'COMMON_E001'));
                //         return;
                //     }
                // }
                $check_item = $this->dvt_model->check_kvt_item_exists($data);
                if($check_item){
                    $data['edit_date'] = date('Y/m/d H:i:s');
                    $data['edit_user'] =  $currentUser['employee_id'];
                    unset($data['quantity']);
                    array_push($updateData, $data);
                }else{
                    $checkOtherItem = $this->dvt_model->check_other_kvt_item_exists($data);
                    if($checkOtherItem){
                        continue;
                    }
                    $data['create_date'] = date('Y/m/d H:i:s');
                    $data['create_user'] =  $currentUser['employee_id'];
                    array_push($insertData, $data);
                }
            }
        }
        $result = $this->dvt_model->insertKVT($insertData, $updateData);
        if ($result) {
            foreach($countPV as $key => $value){
                $pv = explode("*", $key);
                $numPV = $this->order_received_details_model->getCountPV($pv[0], $pv[1], $pv[2]);
                if($numPV[0]['count'] == $value){
                    $pvData = array(
                        'order_receive_no' => $pv[0],
                        'partition_no' => $pv[1],
                        'order_receive_date' => $pv[2],
                        'status' => '009', //梱包指図済み
                        'edit_user' => $currentUser['employee_id'],
                        'edit_date' => date('Y/m/d H:i:s'),
                    );
                    $this->order_received_model->updatePVStatus($pvData);
                }
            }
            echo json_encode($this->_response(true,'save_success'));
        }else{
            echo json_encode($this->_response(false,'save_fail'));
        }
    }
    // divide KVT
    public function divideKVT(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $lastEditDate = $this->dvt_model->getEditDateOfKVTByItemID($params['order_date'], $params['dvt_no'],$params['kvt_no'], $params['detail_no'], $params['times'], $params['item_code'], $params['color'], $params['size']);
        if(sizeof($lastEditDate) > 0){
           if($params['edit_date'] != '' && $lastEditDate['edit_date'] != '' && $lastEditDate['edit_date'] != $params['edit_date']){
               echo json_encode($this->_response(false, 'COMMON_E001'));
               return;
           }
       }
        $oldDVTInfo = array(
            'order_date' => $params['order_date'],
            'times' => $params['times'],
            'dvt_no' => $params['dvt_no'],
        );
        $oldKVTInfo = array(
            'order_date' => $params['order_date'],
            'times' => $params['times'],
            'dvt_no' => $params['dvt_no'],
            'kvt_no' => $params['kvt_no'],
            'detail_no' => $params['detail_no'],
            'item_code' => $params['item_code'],
            'color' => $params['color'],
            'size' => $params['size'],
            'quantity' => $params['time1'],
            'status' => '005',
        );
        $newDVTInfo = array(
            'order_date' => $params['order_date'],
            'times' => ((int)$params['times']+1),
            'dvt_no' => $params['dvt_no'],
        );
        $checkDVT = $this->dvt_model->check_dvt_exists($newDVTInfo);
        $kvt = $this->dvt_model->get_kvt_by_id($oldKVTInfo)[0];

        if($checkDVT == false){
            // get previous DVT
            $dvt = $this->dvt_model->check_dvt_exists($oldDVTInfo)[0];
            if($dvt == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }

            // update status of previous DVT
            $oldDVTInfo['edit_date'] = date('Y/m/d H:i:s');
            $oldDVTInfo['edit_user'] =  $currentUser['employee_id'];
            $resultUpdateDVT = $this->dvt_model->update($oldDVTInfo);
            if($resultUpdateDVT == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }
            // insert new DVT
            $dvt['times'] = $dvt['times'] +1;
            $dvt['create_date'] = date('Y/m/d H:i:s');
            $dvt['create_user'] =  $currentUser['employee_id'];
            // $dvt['status'] =  '001';
            $resultInsertDVT = $this->dvt_model->insert((array)$dvt);
            if($resultInsertDVT == false || $kvt == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }

            //update status and quantity of previous KVT
            $oldKVTInfo['edit_date'] = date('Y/m/d H:i:s');
            $oldKVTInfo['edit_user'] =  $currentUser['employee_id'];
            $resultUpdateKVT  = $this->dvt_model->updateKVTItem($oldKVTInfo);
            if($resultUpdateKVT == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }
            // insert new KVT
            $kvt['times'] = ((int)$kvt['times'] +1);
            $kvt['create_date'] = date('Y/m/d H:i:s');
            $kvt['create_user'] =  $currentUser['employee_id'];
            $kvt['quantity'] =  $params['time2'];
            $kvt['status'] =  '001';
            $resultInsertKVT = $this->dvt_model->insertKVT(array($kvt));
            if($resultInsertKVT == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }
        }else{
            // insert new KVT
            $kvt['times'] = $kvt['times'] +1;
            $check_item = $this->dvt_model->get_kvt_by_id($kvt);
            $resultInsertKVT = false;
            if($check_item != false){
                $check_item = $check_item[0];
                $check_item['edit_date'] = date('Y/m/d H:i:s');
                $check_item['edit_user'] =  $currentUser['employee_id'];
                $check_item['quantity'] +=  $params['time2'];
                $resultInsertKVT = $this->dvt_model->updateKVTItem($check_item);
                // echo json_encode($this->_response(false,'POD0020_E003'));
                // return;
            }else{
                $kvt['create_date'] = date('Y/m/d H:i:s');
                $kvt['create_user'] =  $currentUser['employee_id'];
                $kvt['status'] =  '001';
                $kvt['quantity'] =  $params['time2'];
                $resultInsertKVT = $this->dvt_model->insertKVT(array($kvt));
            }
            if($resultInsertKVT == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }

            //update status and quantity of previous KVT
            $oldKVTInfo['edit_date'] = date('Y/m/d H:i:s');
            $oldKVTInfo['edit_user'] =  $currentUser['employee_id'];
            $resultUpdateKVT = $this->dvt_model->updateKVTItem($oldKVTInfo);
            if($resultUpdateKVT == false){
                echo json_encode($this->_response(false,'save_fail'));
                return;
            }
        }
        echo json_encode($this->_response(true,'save_success'));
    }
	public function in_out_summary()
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $this->data['screen_id'] = 'JOS0030';
        $this->data['title'] = $this->lang->line('in_out_summary');
        $kubun = '1';
        $in_out_list = $this->konpo_model->IOSummaryType1($kubun);
        foreach ($in_out_list as $key => $value) {
            if($value['sum_store'] == '' && $value['sum_dvt'] == '') {
                $in_out_list[$key]['balance'] = '';
            } else {
                $in_out_list[$key]['balance'] = $value['sum_store'] - $value['sum_dvt'];
            }
        }
        $this->data['in_out_list1'] = $in_out_list;

        // Load the subview and Pass to the master view
        $content = $this->load->view('japan_order/in_out_summary', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    public function getIOSummary()
    {
        $params = $this->input->get('filter');
        $data = $this->konpo_model->IOSummaryType2($kubun='1', $params);
        $data_copy = $data;
        $no = 1;
        if($params == "OK-NG") {
            foreach ($data as $key => $value) {
                $sum_ok = 0;
                $sum_ng = 0;
                if($key == 0) {
                    $data[$key]['no'] = $no;
                } else {
                    if($data[$key-1]['item_code'] == $value['item_code'] && $data[$key-1]['size'] == $value['size'] && $data[$key-1]['color'] == $value['color']) {
                        $data[$key]['no'] = $no;
                    } else {
                        $no += 1;
                        $data[$key]['no'] = $no;
                    }
                }
                foreach ($data_copy as $index => $val) {
                    if($value['item_code'] == $val['item_code'] && $value['size'] == $val['size'] && $value['color'] == $val['color']) {
                        $sum_ok = $sum_ok + $val['quantity'];
                        $sum_ng = $sum_ng + $val['quantity_ng'];
                    }
                }
                $data[$key]['dates'] = null;
                $data[$key]['total'] = $sum_ok;   
                $data[$key]['sum_ng'] = $sum_ng; 
            }
        } else {
            if($params == 'P/L') {
                foreach($data as $index => $value) {
                    $data[$index]['dates'] = null;
                    $data[$index]['sum_ng'] = null;
                    $data[$index]['quantity_ng'] = null;
                    $data[$index]['names_ng'] = null;
                }
                foreach ($data as $key => $value) {
                    $sum = 0;
                    if($key == 0) {
                        $data[$key]['no'] = $no;
                    } else {
                        if($data[$key-1]['item_code'] == $value['item_code'] && $data[$key-1]['size'] == $value['size'] && $data[$key-1]['color'] == $value['color']) {
                            $data[$key]['no'] = $no;
                        } else {
                            $no += 1;
                            $data[$key]['no'] = $no;
                        }
                    }
                    foreach ($data_copy as $index => $val) {
                        if($value['item_code'] == $val['item_code'] && $value['size'] == $val['size'] && $value['color'] == $val['color']) {
                            $sum = $sum + $val['quantity'];
                        }
                    }
                    $data[$key]['total'] = $sum;
                }
            } else {
                if($params == 'POONWAY') {
                    foreach($data as $index => $value) {
                        $data[$index]['dates'] = null;
                        $data[$index]['sum_ng'] = null;
                        $data[$index]['quantity_ng'] = null;
                        $data[$index]['names_ng'] = null;
                        $data[$index]['total'] = null;
                        $data[$index]['names'] = null;
                    }
                } else {
                    foreach($data as $index => $value) {
                        $data[$index]['sum_ng'] = null;
                        $data[$index]['quantity_ng'] = null;
                        $data[$index]['names_ng'] = null;
                    }
                    foreach ($data as $key => $value) {
                        $sum = 0;
                        if($key == 0) {
                            $data[$key]['no'] = $no;
                        } else {
                            if($data[$key-1]['item_code'] == $value['item_code'] && $data[$key-1]['size'] == $value['size'] && $data[$key-1]['color'] == $value['color']) {
                                $data[$key]['no'] = $no;
                            } else {
                                $no += 1;
                                $data[$key]['no'] = $no;
                            }
                        }
                        foreach ($data_copy as $index => $val) {
                            if($value['item_code'] == $val['item_code'] && $value['size'] == $val['size'] && $value['color'] == $val['color']) {
                                $sum = $sum + $val['quantity'];
                            }
                        }
                        $data[$key]['total'] = $sum;
                    }
                }
            }
        }
        
        echo json_encode(array(
            'data' => $data,
        ));
    }

    public function dvt_kvt_upload() {
        if ($this->is_logged_in()) {
            $textpdf = "";
            $this->data['screen_id'] = 'JOS0040';
            $this->data['type'] = '0';
            $this->data['title'] = $this->lang->line('upload_dvt_kvt');
            $this->data['textpdf'] = $textpdf;

            $content = $this->load->view('japan_order/dvt_kvt_upload.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

	//upload pdf
    public function do_dvt_kvt_upload()
    {
        if (!$this->is_logged_in()) {
            show_error('', 401);
        }
        //parse pdf to text
        $pdffile = $_FILES["file_upload_hidden"]["tmp_name"];
        $this->data['screen_id'] = 'JOS0040';

        $textpdf = (new \Csv\PdfToText\Pdf(PDF_TO_TEXT_PATH))
            ->setPdf($pdffile)
            ->setOptions(['raw', 'enc UTF-8', 'eol unix'])
            ->text();

        $this->load->model('dvt_kvt_pdf_model');
        $default = array();
        $odrs = $this->dvt_kvt_pdf_model->parse($textpdf, $default);

        $this->data['title'] = $this->lang->line('upload_dvt_kvt');
        $this->data['type'] = $textpdf;
        $this->data['order_receives'] = $odrs;
        $this->data['textpdf'] = $textpdf;

        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
        $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
        $this->data['unit_list'] = $this->komoku_model->get_all_unit();
        $this->data['size_list'] = $this->komoku_model->get_all_size();
        $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
        $this->data['color_list'] = $this->komoku_model->get_all_color();
        $this->data['vendor_list'] = $this->company_model->getAllVendor();

        // render content
        $content = $this->load->view('japan_order/dvt_kvt_upload_success.php', $this->data, true);

        // pass to the master view
        $this->load->view('master_page', array('content' => $content));
    }

    public function save_dvt_kvt_upload(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
        }
        $this->data['screen_id'] = 'JOS0040';
        $order_receives = $this->input->post('order_receives');
        // decode details
        foreach ($order_receives['dvt'] as $key => &$order_receive) {
            $order_receive['details'] = json_decode($order_receive['details'], true);
        }
        foreach ($order_receives['kvt'] as $key => &$order_receive) {
            $order_receive['details'] = json_decode($order_receive['details'], true);
        }

        for($i = 0; $i < count($order_receives['kvt']); $i++) {
            $this->form_validation->set_rules("order_receives[kvt][$i][kvt_no]", $this->lang->line('kvt_no'), 'required');
            $this->form_validation->set_rules("order_receives[kvt][$i][order_date]", $this->lang->line('order_date'), 'required');
            $order_receives['kvt'][$i]['o_no'] = ($order_receives['kvt'][$i]['o_no'] == '') ? null : $order_receives['kvt'][$i]['o_no'];
        }

        $success = false;
        $insert_result_array = [];      
        if ($this->form_validation->run() === true) {
            $insert_result_array = $this->dvt_model->import($order_receives);
            if ($insert_result_array === true) {
                $success = true;
            }
        }

        if (!$success) {
            $this->data['title'] = $this->lang->line('upload_dvt_kvt');
            $this->data['order_receives'] = $order_receives;//json_decode($this->input->post('uploaded_order_receives'), true);
            $this->data['insert_result_array'] = $insert_result_array;
            $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
            $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
            $this->data['unit_list'] = $this->komoku_model->get_all_unit();
            $this->data['size_list'] = $this->komoku_model->get_all_size();
            $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
            $this->data['color_list'] = $this->komoku_model->get_all_color();
            $this->data['vendor_list'] = $this->company_model->getAllVendor();
            $this->form_validation->set_error_delimiters('<p style="color:#d42a38">', '</p>');

            // render content
            $content = $this->load->view('japan_order/dvt_kvt_upload_success.php', $this->data, true);
            // pass to the master view
            // $this->session->set_flashdata('error_msg', $this->lang->line('JOS0040_E001'));
            $this->load->view('master_page', array('content' => $content));
        } else {
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            redirect(base_url('japan_order'));
        }
    }

    // export pdf
    public function exportpdf($dvt = null, $times = null, $plReqDate = null)
    {
        if ($this->is_logged_in()) {
            require_once 'vendor/autoload.php';
            $currentUser = $this->session->userdata('user');
            $currentDate = date("Y/m/d H:i:s");
            $data = array(
                'order_date' => $plReqDate,
                'dvt_no' => $dvt,
                'times' => $times ,
                'print_date' => date("Y/m/d"),
                'edit_user' => $currentUser['employee_id'],
                'edit_date' => $currentDate,
            );
            $dvtInfo = $this->dvt_model->getDVTInfoByID($plReqDate, $dvt, $times);
            $kvtList = $this->dvt_model->getAllKVTInfoByID($plReqDate, $dvt, $times);
            $this->data['dvtInfo'] = $this->formatArray($dvtInfo);
            $this->data['kvtList'] = formatKVTArray($kvtList);
            $mpdf = new \Mpdf\Mpdf([
                'default_font_size' => 15,
                'default_font' => 'sjis']);
            $html = $this->load->view('japan_order/dvt_kvt_export', $this->data, true);
            // print_r($html);
            // return;
            $mpdf->WriteHTML($html);
            $dvt = str_replace(" ", "", $dvt);
            $fileName = $dvt.'_'.$times.'_'.$plReqDate;
            $mpdf->Output($fileName.'.pdf', 'D');
            // $mpdf->Output();
            //update print_date
            $this->dvt_model->update($data);
        }
    }
    public function getPVList(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
        }
        $params = $this->input->post();
        $result = $this->order_received_details_model->getListPVByItem($params);
        $params['pvList'] = null;
        $check = $this->order_received_details_model->getListPVByItem($params);
        foreach($result as $index=>$orderRe) {
            $count = 0;
            foreach($check as $orders) {
                if($orderRe['pv_no'] == $orders['pv_no'] && $orderRe['order_receive_date'] == $orders['order_receive_date']) {
                    $count++;
                }
            }
            if($count == 1) {
                $result[$index]['check_count'] = '1';
            } else {
                $result[$index]['check_count'] = '2';
            }
        }
        echo json_encode($result);
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
    function searchForId( $array, $cd, $id) {
        foreach ($array as $key => $val) {
            if ($val[$cd] == $id) {
                return $key;
            }
        }
        return false;
    }
    public function save_delivery()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");
        // set parammeter
        $params = $this->input->post();
        $query = false;

        // set data to insert delivery  
        $order_list = $this->input->post('pv_infor');
        $pv_infor = array();
        $pv_arr = array();
        foreach($order_list as $order) {
            $pv = $order['order_receive_no'].'-'.$order['partition_no'];
            array_push($pv_infor, $pv);
            array_push($pv_arr, $pv);
        }
        $update_pv = $this->order_received_model->update_pv($pv_arr);
        if(!$update_pv) {
            echo json_encode($this->_response(false,'save_fail'));
            return;
        } 
        $data_insert = array(
            'dvt_no'                    => $this->input->post('dvt_no'),
            'order_date'                => $this->input->post('order_date'),
            'times'                     => $this->input->post('times'),
            'delivery_require_date'     => $this->input->post('delivery_require_date'),
            'delivery_plan_date'        => $this->input->post('delivery_require_date'),
            'delivery_method'           => $this->input->post('delivery_method'),
            'kubun'                     => '1',
            'staff'                     => $this->input->post('staff'),
            'staff_id'                  => $this->input->post('staff_id'),
            'assistance'                => $this->input->post('assistance'),
            'department'                => $this->input->post('department'),
            'factory'                   => $this->input->post('factory'),
            'address'                   => $this->input->post('address'),
            'currency'                   => $this->input->post('currency'),
            'inv_flg'                   => '0',
            'status'                    => '019',
            'pv_infor'                  => implode(',', $pv_infor),
        );
        
        foreach ($data_insert as $key => $value) {
            if ($value == null || $value == '') {unset($data_insert[$key]);}
        }
        $data_insert['salesman'] = $currentUser['employee_id'];
        $data_insert['create_user'] = $currentUser['employee_id'];
        $data_insert['create_date'] = $currentDate;
        $result = $this->dvt_model->insert($data_insert);
        if($result != FALSE) {
            $result['buyer'] = '';
            $result['hightlight'] = true;
            echo json_encode($this->_response(true, 'save_success', $result));
        } else {
            echo json_encode($this->_response(false,'save_fail'));
        }
    }

    function check_delivery_order_exist()
    {
        $params = $this->input->post();
        $data_check = array(
            'dvt_no' => $params['dvt_no'],
            'order_date' => $params['order_date'],
            'times' => $params['times'],
        );
        $result = $this->dvt_model->check_delivery_order_exist($data_check);
        if($result){
            echo json_encode($this->_response(false, 'JOS0010_E004'));
        } else {
            echo json_encode($this->_response(true));
        }
    }
    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $dvt = $this->dvt_model->getAllDVTAndGroup();
        $kvt = $this->dvt_model->getAllKVT("1", true);
        $dvtList = $this->dvt_model->getItemsListForSalesExport('1');
        $staffList = $this->dvt_model->getItemsListForSalesExport2('1');
        $this->writeDataToExcel('views/japan_order/template.xlsx', $dvt, $kvt, $dvtList, $staffList);
    }
    private function writeDataToExcel($filePath, $dvt_list, $kvt_list, $dvtList, $staffList){
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);
        // DVT
        $kvt_key_array = array();
        $spreadsheet->setActiveSheetIndex(0);
        $dvtData = [];
        foreach($dvt_list as $index => $dvt) {
            $dvt["contract_no"] = implode(",", array_unique(array_map('trim',explode(",", $dvt["contract_no"]))));
            $dvt["stype_no"] = implode(",", array_unique(array_map('trim',explode(",", $dvt["stype_no"]))));
            $dvt["o_no"] = implode(",", array_unique(array_map('trim',explode(",", $dvt["o_no"]))));
            $dvt["staff"] =  $dvt["staff_id"] != "" ? "(".$dvt["staff_id"].")".$dvt["staff"] : $dvt["staff"];
            $pos = strrpos($dvt['pv_infor'], "-");
            if($pos !== FALSE) {
                $dvt['pv_infor'] = trim(substr($dvt['pv_infor'], 0, $pos));
            }
            $tempArr = array(
                $dvt['inv_flg'] == "1" ? "■" : null,
                $dvt['order_date'],
                $dvt['delivery_require_date'],
                trim($dvt['dvt_no']),
                $dvt['times_count'] >= 2 ? $dvt['times'] : null,
                $dvt['komoku_name_2'],
                $dvt['staff'],
                $dvt['factory'],
                $dvt['address'],
                $dvt['contract_no'],
                $dvt['stype_no'],
                $dvt['o_no'],
                $dvt['factory_require_date'],
                $dvt['factory_plan_date'],
                $dvt['delivery_plan_date'],
                $dvt['pv_infor'],
                $dvt['pv_in_date'],
                $dvt['packing_date'],
                $dvt['measurement_date'],
                $dvt['passage_date'],
                $dvt['factory_delivery_date'],
                $dvt['knq_delivery_date'],
                $dvt[ 'knq_fac_deli_date'],
                $dvt['salesmanname'],
                $dvt['note'],
                $dvt['buyer']
            );
            array_push($dvtData, $tempArr);
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore(JAPAN_ORDER_START_ROW + 1, count($dvtData));
        $spreadsheet->getActiveSheet()->fromArray( $dvtData, NULL, 'A'.(JAPAN_ORDER_START_ROW + 1));
        $spreadsheet->getActiveSheet()->removeRow(JAPAN_ORDER_START_ROW, 1);

        // KVT
        $spreadsheet->setActiveSheetIndex(1);
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
        $spreadsheet->getActiveSheet()->insertNewRowBefore(JAPAN_ORDER_START_ROW + 1, count($kvtData));
        $spreadsheet->getActiveSheet()->fromArray( $kvtData, NULL, 'A'.(JAPAN_ORDER_START_ROW + 1));
        $spreadsheet->getActiveSheet()->removeRow(JAPAN_ORDER_START_ROW, 1);

        // sheet 3
        $spreadsheet->setActiveSheetIndex(2);
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
                $dvt['assistance'],
                $dvt['item_code'],
                $dvt['item_name'],
                $dvt['size'],
                $dvt['color'],
                $dvt['quantity'],
                $dvt['unit'],
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
                $currency == 'USD' && $dvt['shosha_price'] &&  $dvt['base_price'] ? ($dvt['shosha_price'] - $dvt['base_price']) : null,
                $currency == 'JPY' && $dvt['shosha_price'] &&  $dvt['base_price'] ? ($dvt['shosha_price'] - $dvt['base_price']) : null,
            );
            array_push($dvtListData, $tempArray);
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore(JAPAN_ORDER_START_ROW + 2, count($dvtListData));
        $spreadsheet->getActiveSheet()->fromArray( $dvtListData, NULL, 'B3');
        // sheet 4
        $spreadsheet->setActiveSheetIndex(3);
        $staffData = array();
        $index = 0;
        foreach($staffList as $staff){
            $currency = isset($staff['currency']) ? $staff['currency'] : '';
            $existFlg = false;
            foreach ($staffData as &$tempStaff) {
                if ($staff['staff'] == $tempStaff[1] && $staff['staff_id'] == $tempStaff[2]) {
                    $existFlg = true;
                    if($currency == 'USD') {
                        $tempStaff[3] = $staff['sell_price'];
                        $tempStaff[5] = $staff['sum_sell_amount'];
                        $tempStaff[7] = $staff['base_price'];
                        $tempStaff[9] = $staff['sum_base_amount'];
                        $tempStaff[11] = $staff['shosha_price'];
                        $tempStaff[13] = $staff['sum_shosha_amount'];
                        $tempStaff[15] = ($staff['sell_price'] - $staff['base_price']);
                        $tempStaff[17] = ($staff['shosha_price'] - $staff['base_price']);
                    } else if ($currency == 'JPY') {
                        $tempStaff[4] = $staff['sell_price'];
                        $tempStaff[6] =  $staff['sum_sell_amount'];
                        $tempStaff[8] =  $staff['base_price'];
                        $tempStaff[10] = $staff['sum_base_amount'];
                        $tempStaff[12] = $staff['shosha_price'];
                        $tempStaff[14] = $staff['sum_shosha_amount'];
                        $tempStaff[16] =  ($staff['sell_price'] - $staff['base_price']);
                        $tempStaff[18] = ($staff['shosha_price'] - $staff['base_price']);
                    }
                }
            }
            if (!$existFlg ) {
                $tempArray = array(
                    (++$index),
                    $staff['staff'],
                    $staff['staff_id'],
                    ($currency == 'USD' && $staff['sell_price']) ? $staff['sell_price'] : null,
                    $currency == 'JPY' && $staff['sell_price'] ? $staff['sell_price'] : null,
                    $currency == 'USD' && $staff['sum_sell_amount'] ? $staff['sum_sell_amount'] : null,
                    $currency == 'JPY' && $staff['sum_sell_amount'] ? $staff['sum_sell_amount'] : null,
                    $currency == 'USD' && $staff['base_price'] ? $staff['base_price'] : null,
                    $currency == 'JPY' && $staff['base_price'] ? $staff['base_price'] : null,
                    $currency == 'USD' && $staff['sum_base_amount'] ? $staff['sum_base_amount'] : null,
                    $currency == 'JPY' && $staff['sum_base_amount'] ? $staff['sum_base_amount'] : null,
                    $currency == 'USD' && $staff['shosha_price'] ? $staff['shosha_price'] : null,
                    $currency == 'JPY' && $staff['shosha_price'] ? $staff['shosha_price'] : null,
                    $currency == 'USD' && $staff['sum_shosha_amount'] ? $staff['sum_shosha_amount'] : null,
                    $currency == 'JPY' && $staff['sum_shosha_amount'] ? $staff['sum_shosha_amount'] : null,
                    $currency == 'USD' && $staff['sell_price'] &&  $staff['base_price'] ? ($staff['sell_price'] - $staff['base_price']): null,
                    $currency == 'JPY' && $staff['sell_price'] &&  $staff['base_price'] ? ($staff['sell_price'] - $staff['base_price']) : null,
                    $currency == 'USD' && $staff['shosha_price'] &&  $staff['base_price'] ? ($staff['shosha_price'] - $staff['base_price']) : null,
                    $currency == 'JPY' && $staff['shosha_price'] &&  $staff['base_price'] ? ($staff['shosha_price'] - $staff['base_price']) : null,
                );
                array_push($staffData, $tempArray);
            }
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore(JAPAN_ORDER_START_ROW + 2, count($staffData));
        $spreadsheet->getActiveSheet()->fromArray( $staffData, NULL, 'B3');

        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="japan_order.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
    public function do_save_product() {
        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");

        foreach($params as $key => $value){
            if($value == null || $value == '') {unset($params[$key]);}
        }
        unset($params['customer_name']);
        $params['create_user'] = $currentUser['employee_id'];
        $params['create_date'] = $currentDate;
        $checkExistItem = $this->items_model->checkExistItemByAllKeys($params);
        if($checkExistItem) {
            $this->responseJsonError($this->lang->line("item_existed"));
        } else {
            $result = $this->items_model->insertItems($params);
            if($result !== FALSE) {
                $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
                $this->responseJsonSuccess(null, $this->lang->line("COMMON_I002"));
            } else {
                $this->responseJsonError($this->lang->line("save_fail"));
            }
        }
    }

    public function getOrderReceiveList()
    {
        $data_order = $this->order_received_model->get_order_receive_add();
        if($data_order!=null) {
            foreach($data_order as $key => $value) {
                $data_order[$key]['check'] = false;
            }
        }

        $order_receive = $data_order;
        if($order_receive != null){
            foreach($order_receive as $index=>$orderRe) {
                $count = 0;
                foreach($data_order as $orders) {
                    if($orderRe['order_receive_no'] == $orders['order_receive_no'] && $orderRe['order_receive_date'] == $orders['order_receive_date']) {
                        $count++;
                    }
                }
                $order_receive[$index]['check_count'] = $count;
            }
        }

        // $this->data['order_receive_list'] = $order_receive;
        echo json_encode(array('data'=>$order_receive));
    }

    public function getInfoDVT()
    {
        $params = $this->input->post();
        $params['kubun'] = '1';
        $result = $this->dvt_model->getDVT($params['order_date'], $params['dvt_no'],$params['times'], $params['kubun']);
        if($result['pv_infor'] != '' && $result['pv_infor'] != null) {
            $order_receive_arr = explode(',',$result['pv_infor']);
            $order_receive = explode('-',$order_receive_arr[0]);
            $params['order_receive_no'] = $order_receive[0];
            $params['partition_no'] = $order_receive[1];
            $data = $this->order_received_model->getReceivedOrderByID($order_receive[0], null, $order_receive[1]);
            $data['plan_delivery_date'] = $result['delivery_plan_date'];
            echo json_encode($this->_response(true,'save_success', $data));
        } else {
            echo json_encode($this->_response(false));
        }
    }
}
