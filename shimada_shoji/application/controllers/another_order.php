<?php
require_once 'vendor/autoload.php';
defined('BASEPATH') or exit('No direct script access allowed');
define("ANOTHER_ORDER_DVT_EXCEL_COL", 
array('A'=>'inv_flg','B'=>'order_date','C'=>'delivery_require_date',
'D'=>'dvt_no','E'=>'times','F'=>'komoku_name_2','G'=>'staff','H'=>'factory',
'I'=>'address','J'=>'contract_no','K'=>'stype_no','L'=>'o_no','M'=>'factory_require_date',
'N'=>'factory_plan_date','O'=>'delivery_plan_date','P'=>'pv_infor','Q'=>'pv_in_date'
,'R'=>'packing_date','S'=>'measurement_date','T'=>'passage_date','U'=>'factory_delivery_date'
,'V'=>'knq_delivery_date','W'=>'knq_fac_deli_date','X'=>'salesmanname','Y'=>'note','Z'=>'buyer'));
define("ANOTHER_ORDER_KVT_EXCEL_COL", 
array('A'=>'order_date','B'=>'dvt_no','C'=>'kvt_no',
'D'=>'times','E'=>'staff','F'=>'staff_id','G'=>'assistance','H'=>'item_code',
'I'=>'item_name','J'=>'color','K'=>'size','L'=>'quantity','M'=>'contract_no',
'N'=>'delivery_date','O'=>'address','P'=>'delivery_method','Q'=>'pv_no'
,'R'=>'arrival_date','S'=>'item_quantity'));
define("ANOTHER_ORDER_START_ROW",2);

class Another_Order extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('employee_model');
        $this->load->model('partners_model');
        $this->load->model('order_received_details_model');
        $this->load->model('order_received_model');
        $this->load->model('konpo_model');
        $this->load->model('dvt_model');
        $this->load->model('store_item_model');
        $this->load->model('komoku_model');
    }

    // Created By Khanh 
    // Date : 10/04/2018
    public function index($search_dvt = null)
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'AOS0010';
            $this->data['search_dvt'] = $search_dvt;
            $kubun = '2' ;
            $dvt = $this->dvt_model->getAllDVT($kubun);
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
                                'case_mark' => $dvt[$i]['case_mark'],
                                'case_mark_text' => $dvt[$i]['case_mark_text'],
                                'salesmanname' => $dvt[$i]['salesmanname'],
                                'status_name' => $dvt[$i]['status_name'],
                                'buyer' => $dvt[$i]['buyer'],
                                'edit_date' => $dvt[$i]['edit_date'],
                                'employee_id' => $dvt[$i]['employee_id']);
                array_push($overrideDVT,$temp);
                // for ($j = $i+1; $j < sizeof($dvt); $j++){
                //     if($dvt[$i]['order_date'] == $dvt[$j]['order_date'] && $dvt[$i]['times'] == $dvt[$j]['times'] && $dvt[$i]['dvt_no'] == $dvt[$j]['dvt_no'] ){
                //         $index = sizeof($overrideDVT) - 1;
                //         $overrideDVT[$index]['contract_no'] .= ','.$dvt[$j]['contract_no'];
                //         $overrideDVT[$index]['stype_no'] .= ','.$dvt[$j]['stype_no'];
                //         $overrideDVT[$index]['o_no'] .= ','.$dvt[$j]['o_no'];
                //     }
                // }
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
            $kubun = '2';
            $pvNoList = $this->order_received_details_model->getAllPVNo();
            $salesman = $this->employee_model->get_fullname_emp();
            $status_list = $this->komoku_model->get_status_list_with_use("1");
            $receiveList = $this->order_received_model->getOrderRecForSchedule($kubun);
            $this->data['title'] = $this->lang->line('export_schedule_list');
            $this->data['salesman'] = $salesman;
            $this->data['status_list'] = $status_list;
            $this->data['dvt'] = $overrideDVT;
            $this->data['shipping_method_list'] = $this->komoku_model->get_shipping_method_with_use("1");
            $this->data['pvNoList'] = $pvNoList;
            $this->data['receiveList'] = $receiveList;
            // Load the subview
            $content = $this->load->view('another_order/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    public function getPOList(){
        $kubun = '2';
        $receiveList = $this->order_received_model->getOrderRecForSchedule($kubun);
        foreach($receiveList as &$tmpPV){
            $count = 0;
            foreach($receiveList as $tmp){
                if($tmpPV['order_receive_no'] == $tmp['order_receive_no'] && $tmpPV['order_receive_date'] == $tmp['order_receive_date']){
                    $count++;
                }
            }
            $tmpPV['number_partition'] = $count;
        }
        echo json_encode(array('data'=>$receiveList));
    }
	public function in_out_summary()
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $this->data['screen_id'] = 'AOS0020';
        $this->data['title'] = $this->lang->line('in_out_summary');
        $in_out_list = $this->konpo_model->IOSummaryType1($kubun = '2');
        foreach ($in_out_list as $key => $value) {
            if($value['sum_store'] == '' && $value['sum_dvt'] == '') {
                $in_out_list[$key]['balance'] = '';
            } else {
                $in_out_list[$key]['balance'] = $value['sum_store'] - $value['sum_dvt'];
            }
        }
        $this->data['in_out_list'] = $in_out_list;

        // Load the subview // Pass to the master view
        $content = $this->load->view('another_order/in_out_summary.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    public function getIOSummary()
    {
        $params = $this->input->get('filter');
        $data = $this->konpo_model->IOSummaryType2($kubun='2', $params);
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
                            $sum = $sum + floatval($val['quantity']);
                        }
                    }
                    $data[$key]['total'] = $sum;
                }
            }
        }
        
        echo json_encode(array(
            'data' => $data,
        ));
    }
    
    // Created by Khanh
    // Date : 13/04/2018
    // get item of KVT
    public function searchItems(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $kubun = '2';
        $params = $this->input->post('param');
        $kvtList = $this->dvt_model->getAllKVTByKVTID($params['order_date'],$params['dvt_no'],$params['kvt_no'] ,$params['times'], $kubun);
        if(sizeof($kvtList) > 0){
            echo json_encode(array('data' => $this->formatKVTArray($kvtList)[0]['detail']));
        }else{
            echo json_encode(array('data' => $kvtList));
        }
    }

    // Created by Khanh
    // Date : 13/04/2018
    // Delete item of KVT
    public function deleteKVTItem() 
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $kubun = '2';
        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        
        // check kvt is exists ? 
        $check_item = $this->dvt_model->check_kvt_item_exists($params);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } 

        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        // Delete kvt item
        $result = $this->dvt_model->deleteKVTItem($params, $kubun);
        if($result === FALSE) {
            echo json_encode($this->_response(false,'del_fail'));
        } else {
            echo json_encode($this->_response(true,'del_success'));
        }
    }

    
    // Created by Khanh
    // Date : 12/04/2018
    // Delete DVT
    public function delete() 
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $kubun = '2' ;
        $params = $this->input->post();
        if($params['pv_infor'] != "" && $params['pv_infor'] != NULL) {
            $orderReceivedDetailArr = explode(",", $params['pv_infor']);
            $orderReceivedDetail = [];
            foreach($orderReceivedDetailArr as $key => $value) {
                if($value != "") {
                    if(strpos($value, "-") !== FALSE) {
                        //split PV000X-Y
                        $tmp = explode("-", $value);
                        $pvNO = substr($value, 0, strrpos($value, '-'));
                        $partitionNo = substr($value, strrpos($value, '-') + 1);
                        if($partitionNo == null || $partitionNo == ''){
                            $partitionNo = '1';
                        }
                        $orderReceivedDetail[] = ["order_receive_no" => $pvNO, "partition_no" => $partitionNo];
                    } else {
                        $orderReceivedDetail[] = ["order_receive_no" => $value, "partition_no" => 1];
                    }
                }
            }
        } else {
            $orderReceivedDetail = NULL;
        }
        $currentUser = $this->session->userdata('user');

        // check dvt is exists ? 
        $check_item = $this->dvt_model->check_dvt_exists($params, $kubun );
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } 

        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        // Delete dvt
        $result = $this->dvt_model->delete($params, $kubun);
        // Update Order received status
        $updatePVResult = $this->order_received_model->updateStatusOrderReceived(NULL, ORDER_RECEIVED_STATUS_CLOSE, $orderReceivedDetail);
        if($result && $updatePVResult) {
            echo json_encode($this->_response(true,'del_success'));
        } else {
            echo json_encode($this->_response(false,'del_fail'));
        }
    }

    public function uploadcasemarkfile(){
        $target_folder = UPLOAD_DIRECTORY.'casemark/';
        if (!file_exists($target_folder)) {
            mkdir($target_folder, 0777, true);
        }
        $target_file = $target_folder.$_FILES['casemark_file']["name"];
        move_uploaded_file($_FILES["casemark_file"]["tmp_name"], $target_file);
    }

    // Created by Khanh
    // Date :12/04/2018
    // Save DVT
    public function save()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        // check dvt is exists ? 
        $kubun = '2';
        $check_item = $this->dvt_model->check_dvt_exists($params, $kubun);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } 
        if(strtotime($check_item[0]['edit_date']) != strtotime($params['edit_date'])){
            echo json_encode($this->_response(false, 'COMMON_E001'));
            return;
        }
        $currentUser = $this->session->userdata('user');
        $query = false;
        $data_dvt = array(
            'order_date' => $params['order_date'],
            'times' => $params['times'],
            'dvt_no' => $params['dvt_no'],
            'factory_require_date' => $params['factory_require_date'],
            'factory_plan_date' => $params['factory_plan_date'],
            'delivery_plan_date' => $params['delivery_plan_date'],
            'measurement_date' => $params['measurement_date'],
            'packing_date' => $params['packing_date'],
            'passage_date' => $params['passage_date'],
            // 'pv_in_date' => $params['pv_in_date'],
            'factory_delivery_date' => $params['factory_delivery_date'],
            // 'knq_delivery_date' => $params['knq_delivery_date'],
            // 'knq_fac_deli_date' => $params['knq_fac_deli_date'],
            'salesman' => $params['salesman'],
            'status' => $params['status_code'],
            'note' => $params['note'],
            'case_mark_text' => $params['case_mark_text'],
            'buyer' => $params['buyer'],
            'inv_flg' => $params['inv_flg'],
        );
        if(!empty($params['casemark_file_name'])){
            $data_dvt['case_mark'] = UPLOAD_DIRECTORY.'casemark/'.$params['casemark_file_name'];
        }
        $data_ktv = array(
            'order_date'        => $params['order_date'],
            'times'             => $params['times'],
            'dvt_no'            => $params['dvt_no'],
            'kvt_no'            => $params['dvt_no'],
            'contract_no'       => $params['contract_no'],
            'case_mark'         => $params['case_mark_text'],
            'stype_no'          => $params['stype_no'],
            'o_no'              => $params['o_no'],
            'edit_date'         => date('Y/m/d H:i:s'),
            'edit_user'         => $currentUser['employee_id'],
        );
        foreach($data_dvt as $key => $value){
            if($value == '') {$data_dvt[$key] = null;}
        }
        foreach($data_ktv as $key => $value){
            if($value == '') {$data_ktv[$key] = null;}
        }
        $data_dvt['edit_date'] = date('Y/m/d H:i:s');
        $data_dvt['edit_user'] =  $currentUser['employee_id'];

        // Query update items
        $update_kvt = $this->dvt_model->updateKVT($data_ktv, $kubun);
        $query = $this->dvt_model->update($data_dvt ,$kubun);
        if($query && $update_kvt) {
            echo json_encode($this->_response(true,'save_success', $this->formatArray($query)));
        } else {
            echo json_encode($this->_response(false,'save_fail'));
        }
    }

    // Created by Khanh
    // Date :12/04/2018
    // get data oreder receive detail
    public function getOrderReceiveDetail(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');

        if(!isset($params['order_receive_no']) || !isset($params['partition_no']) || !isset($params['order_receive_date']) ){
            echo json_encode(array(
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'draw' => $this->input->get('draw')
            ));
            return;
        }

        $data = $this->order_received_details_model->getOrderReceivedByID($params['order_receive_no'],$params['partition_no'],$params['order_receive_date'],$start, $length, $recordsTotal, $recordsFiltered);
        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }
    public function getOrderReceiveInfo(){
        $params = $this->input->post();
        $orderReceive = $this->order_received_model->getReceivedOrderByID($params['order_receive_no'], $params['order_receive_date'], $params['partition_no']);
        echo json_encode(array('data'=>$orderReceive));
    }
    // Created by Khanh
    // Date :12/04/2018
    // save delivery
    public function saveDelivery(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $kubun = '2';
        $dvt_no = explode("/", $params['dvt_no']);
        $orderReceivedDate = $dvt_no[count($dvt_no)-1];
        $partition_no = $dvt_no[count($dvt_no)-2];
        $pv_no = str_replace("/$partition_no/$orderReceivedDate", "", $params['dvt_no']);
        $maxPartition = $this->order_received_model->getPartitionNo($pv_no,  $orderReceivedDate, '0');
        // $params['dvt_no'] = trim($dvt_no[0]). "-" .$dvt_no[1];
        $params['pv_infor'] = str_pad($pv_no, 10) . "-" . $params['times'];

        $orderReceivedDetail = NULL;
        $orderReceivedDetail = [
            "order_receive_no"      => $pv_no,
            "partition_no"          => $params['times'],
            "order_receive_date"    => $orderReceivedDate,
            "edit_user"             =>  $currentUser['employee_id'],
            "edit_date"             =>  date('Y/m/d H:i:s'),
        ];
        $check_exist = $this->dvt_model->check_dvt_exists($params , $kubun);
        if($check_exist){
            echo json_encode($this->_response(false, 'JOS0010_E004'));
            return;
        }
        $data = array(
            'dvt_no'                =>  $pv_no,
            'order_date'            =>  $params['order_date'],
            'times'                 =>  $params['times'],
            'kubun'                 =>  '2',
            'delivery_method'       =>  $params['delivery_method'],
            'staff'                 =>  $params['staff'],
            'staff_id'              =>  $params['staff_id'],
            'assistance'            =>  $params['assistance'],
            'department'            =>  $params['department'],
            'factory'               =>  $params['factory'],
            'address'               =>  $params['address'],
            'currency'              =>  $params['currency'],
            'delivery_require_date' =>  $params['delivery_require_date'],
            'pv_infor'              =>  $params['pv_infor'],
            'salesman'              =>  $currentUser['employee_id'],
            'status'                =>  '019',
            'create_user'           =>  $currentUser['employee_id'],
            'create_date'           =>  date('Y/m/d H:i:s'),
            'case_mark_text'        =>  $params['text_case_mark'],
        );
        if(!empty($params['casemark_file_name'])){
            $data['case_mark'] = UPLOAD_DIRECTORY.'casemark/'.$params['casemark_file_name'];
        }
        $insertDVT = $this->dvt_model->insert($data);
        $findOrderReceiveDetail = $this->order_received_details_model->getOrderReceivedByID($pv_no, $partition_no, $orderReceivedDate);
        $data_kvt = array();
        if($findOrderReceiveDetail){
            foreach($findOrderReceiveDetail as $index=>$item){
                $temp = array(
                    'order_date'            =>  $params['order_date'],
                    'dvt_no'                =>  $pv_no,
                    'times'                 =>  $params['times'],
                    'kvt_no'                =>  $pv_no,
                    'detail_no'             =>  $index,
                    'staff'                 =>  $params['staff'],
                    'staff_id'              =>  $params['staff_id'],
                    'contract_no'           =>  $params['contract_no'],
                    'assistance'            =>  $params['assistance'],
                    'department'            =>  $params['department'],
                    'factory'               =>  $params['factory'],
                    'address'               =>  $params['address'],
                    'stype_no'              =>  $params['stype_no'],
                    'item_code'             =>  $item['item_code'],
                    'item_name'             =>  $item['item_name'],
                    'inv_no'                =>  $item['inv_no'],
                    'buy_price'             =>  $item['buy_price'],
                    'base_price'            =>  $item['base_price'],
                    'sell_price'            =>  $item['sell_price'],
                    'unit'                  =>  $item['unit'],
                    'item_jp_code'          =>  $item['jp_code'],
                    'pv_no'                 =>  $params['pv_infor'].'*'.$item['quantity'],
                    'color'                 =>  isset($item['color'])?$item['color']:'',
                    'size'                  =>  isset($item['size'])?$item['size']:'',
                    'quantity'              =>  $item['quantity'],
                    'create_user'           =>  $currentUser['employee_id'],
                    'create_date'           =>  date('Y/m/d H:i:s'),
                    'case_mark'             =>  $params['text_case_mark'],
                );
                array_push($data_kvt, $temp);
            }
            $insertKVT = $this->dvt_model->insertKVT($data_kvt);
        }
        // Update Order received status
        $updatePVResult = $this->order_received_model->updateStatusOrderReceived($orderReceivedDetail, PACKING_COMPLETED);
        if($insertDVT && $insertKVT && $updatePVResult){
            $data = $this->dvt_model->getDVT($params['order_date'],$pv_no,$params['times'],$kubun='2');
            echo json_encode($this->_response(true,'save_success',$data));
        }else{
            echo json_encode($this->_response(false,'save_fail'));
        }
    }

    // Created by Khanh 
    // Date :16/04/2018
    // save KVT
    // save change Item
	public function saveKVTItem()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $kubun = '2';
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
                'color' => $params['color'],
                'size' => $params['size'],
                'quantity' => $params['quantity'],
                'packing_date' => $params['packing_date'],
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
            $query = $this->dvt_model->updateKVTItem($data, $kubun);
            if($query) {
                echo json_encode($this->_response(true,'save_success', $this->formatKVTArray($query)[0]));
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
            $temp = array( 'inv_flg'                => $dvt[$i]['inv_flg'], 
                            'order_date'            => $dvt[$i]['order_date'], 
                            'delivery_require_date' => $dvt[$i]['delivery_require_date'],
                            'dvt_no'                => $dvt[$i]['dvt_no'],
                            'times'                 => $dvt[$i]['times'],
                            'komoku_name_2'         => $dvt[$i]['komoku_name_2'],
                            'staff'                 => $dvt[$i]['staff'],
                            'staff_id'              => $dvt[$i]['staff_id'],
                            'measurement_date'      => $dvt[$i]['measurement_date'],
                            'factory'               => $dvt[$i]['factory'],
                            'address'               => $dvt[$i]['address'],
                            'contract_no'           => $dvt[$i]['contract_no'],
                            'stype_no'              => $dvt[$i]['stype_no'],
                            'o_no'                  => $dvt[$i]['o_no'],
                            'factory_require_date'  => $dvt[$i]['factory_require_date'],
                            'factory_plan_date'     => $dvt[$i]['factory_plan_date'],
                            'delivery_plan_date'    => $dvt[$i]['delivery_plan_date'],
                            'pv_infor'              => $dvt[$i]['pv_infor'],
                            'pv_in_date'            => $dvt[$i]['pv_in_date'],
                            'packing_date'          => $dvt[$i]['packing_date'],
                            'passage_date'          => $dvt[$i]['passage_date'],
                            'factory_delivery_date' => $dvt[$i]['factory_delivery_date'],
                            'knq_delivery_date'     => $dvt[$i]['knq_delivery_date'],
                            'knq_fac_deli_date'     => $dvt[$i]['knq_fac_deli_date'],
                            'note'                  => $dvt[$i]['note'],
                            'salesmanname'          => $dvt[$i]['salesmanname'],
                            'status_name'           => $dvt[$i]['status_name'],
                            'buyer'                 => $dvt[$i]['buyer'],
                            'employee_id'           => $dvt[$i]['employee_id'],
                            'case_mark'             => $dvt[$i]['case_mark'],
                            'case_mark_text'        => $dvt[$i]['case_mark_text'],
                            'edit_date'             => $dvt[$i]['edit_date']);
            array_push($overrideDVT,$temp);
            // for ($j = $i+1; $j < sizeof($dvt); $j++){
            //     if($dvt[$i]['order_date'] == $dvt[$j]['order_date'] && $dvt[$i]['times'] == $dvt[$j]['times'] && $dvt[$i]['dvt_no'] == $dvt[$j]['dvt_no'] ){
            //         $index = sizeof($overrideDVT) - 1;
            //         $overrideDVT[$index]['contract_no'] .= ','.$dvt[$j]['contract_no'];
            //         $overrideDVT[$index]['stype_no'] .= ','.$dvt[$j]['stype_no'];
            //         $overrideDVT[$index]['o_no'] .= ','.$dvt[$j]['o_no'];
            //     }
            // }
        }
        return $overrideDVT[0];
    }

    // format kvt array
	public function formatKVTArray($kvt){
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
                            'stype_no' => $kvt[$i]['stype_no'],
                            'o_no' => $kvt[$i]['o_no'],
                            'contract_no' => $kvt[$i]['contract_no'],
                            'delivery_date' => $kvt[$i]['delivery_date'],
                            'delivery_method' => $kvt[$i]['delivery_method'],
                            'shipping_method' => $kvt[$i]['shipping_method'],
                            'detail' => array(
                                array('item_code' => $kvt[$i]['item_code'],
                                      'item_name' => $kvt[$i]['item_name'],
                                      'detail_no' => $kvt[$i]['detail_no'],
                                      'color' => $kvt[$i]['color'],
                                      'size' => $kvt[$i]['size'],
                                      'quantity' => $kvt[$i]['quantity'],
                                      'buy_price' => $kvt[$i]['buy_price'],
                                      'sell_price' => $kvt[$i]['sell_price'],
                                      'currency' => isset($kvt[$i]['currency']) ? $kvt[$i]['currency'] : '',
                                      'packing_date' => $kvt[$i]['packing_date'],
                                      'pv_no' => $kvt[$i]['pv_no'],
                                      'arrival_date' => $kvt[$i]['arrival_date'],
                                      'item_quantity' => $kvt[$i]['item_quantity'],
                                      'edit_date' => isset($kvt[$i]['edit_date']) ? $kvt[$i]['edit_date'] : null,
                                    )
                            ),
                        );
            array_push($overrideKVT,$temp);
            for ($j = $i+1; $j < sizeof($kvt); $j++){
                if($kvt[$i]['order_date'] == $kvt[$j]['order_date'] && $kvt[$i]['times'] == $kvt[$j]['times'] && $kvt[$i]['dvt_no'] == $kvt[$j]['dvt_no'] && $kvt[$i]['kvt_no'] == $kvt[$j]['kvt_no']){
                    $index = sizeof($overrideKVT) - 1;
                    $detail = array('item_code' => $kvt[$j]['item_code'],
                                    'item_name' => $kvt[$j]['item_name'],
                                    'color' => $kvt[$j]['color'],
                                    'detail_no' => $kvt[$j]['detail_no'],
                                    'size' => $kvt[$j]['size'],
                                    'quantity' => $kvt[$j]['quantity'],
                                    'buy_price' => $kvt[$j]['buy_price'],
                                    'sell_price' => $kvt[$j]['sell_price'],
                                    'currency' => isset($kvt[$j]['currency']) ? $kvt[$j]['currency'] : '',
                                    'packing_date' => $kvt[$j]['packing_date'],
                                    'pv_no' => $kvt[$j]['pv_no'],
                                    'arrival_date' => $kvt[$j]['arrival_date'],
                                    'item_quantity' => $kvt[$j]['item_quantity'],
                                    'edit_date' => isset($kvt[$j]['edit_date']) ? $kvt[$j]['edit_date'] : null,
                                );
                    array_push($overrideKVT[$index]['detail'],$detail);
                }
            }
        }
        return $overrideKVT;
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

    // export pdf
    public function exportpdf($dvt = null, $times = null, $plReqDate = null)
    {
        if ($this->is_logged_in()) {
            require_once 'vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf();
            $html = $this->load->view('another_order/export', $this->data, true);
            $mpdf->WriteHTML($html);
            $dvt = str_replace(" ", "", $dvt);
            $fileName = $dvt.'_'.$times.'_'.$plReqDate;
            $mpdf->Output($fileName.'.pdf', 'D');
        }
    }
    /**
     * Export Excel
     * 
     */
    // public function excel(){
    //     if (!$this->is_logged_in(false)) {
    //         show_error('', 403);
    //     }
    //     $dvt = $this->dvt_model->getAllDVTAndGroup("2");
    //     $kvt = $this->dvt_model->getAllKVT("2", true);
    //     $this->writeDataToExcel('views/another_order/template.xlsx', $dvt, $kvt);
    // }

    // private function writeDataToExcel($filePath, $dvt_list, $kvt_list){
    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $spreadsheet = $reader->load(APPPATH.$filePath);
    //     // DVT
    //     $kvt_key_array = array();
    //     $spreadsheet->setActiveSheetIndex(0);
    //     foreach($dvt_list as $index => $dvt) {
    //         $spreadsheet->getActiveSheet()->insertNewRowBefore(ANOTHER_ORDER_START_ROW + 1 + $index, 1);
    //         foreach(ANOTHER_ORDER_DVT_EXCEL_COL as $key=>$value){
    //             $spreadsheet->getActiveSheet()->setCellValue($key.( ANOTHER_ORDER_START_ROW + 1 + $index),  ($value == "inv_flg" ? ($dvt[$value] == "1" ? "■" : "") : $dvt[$value]));
    //         }
    //     }
    //     $spreadsheet->getActiveSheet()->removeRow(ANOTHER_ORDER_START_ROW, 1);

    //     //KVT
    //     $spreadsheet->setActiveSheetIndex(1);
    //     foreach($kvt_list as $index => $kvt){
    //         // $spreadsheet->getActiveSheet()->insertNewRowBefore(ANOTHER_ORDER_START_ROW + 1 + $index, 1);
    //         foreach(ANOTHER_ORDER_KVT_EXCEL_COL as $key=>$value){
    //             $spreadsheet->getActiveSheet()->setCellValue($key.( ANOTHER_ORDER_START_ROW + 1 + $index), $kvt[$value]);
    //         }
    //     }
    //     $spreadsheet->getActiveSheet()->removeRow(ANOTHER_ORDER_START_ROW, 1);

    //     $spreadsheet->setActiveSheetIndex(0);
    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment;filename="another_order.xlsx"');
    //     header('Cache-Control: max-age=0');
    //     $writer->save('php://output');
    // }
    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $dvt = $this->dvt_model->getAllDVTAndGroup('2');
        $kvt = $this->dvt_model->getAllKVT("2", true);
        $dvtList = $this->dvt_model->getItemsListForSalesExport("2");
        $staffList = $this->dvt_model->getItemsListForSalesExport2("2");
        $this->writeDataToExcel('views/another_order/template.xlsx', $dvt, $kvt, $dvtList, $staffList);
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
                $dvt['knq_fac_deli_date'],
                $dvt['salesmanname'],
                $dvt['note'],
                $dvt['buyer']
            );
            array_push($dvtData, $tempArr);
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore(ANOTHER_ORDER_START_ROW + 1, count($dvtData));
        $spreadsheet->getActiveSheet()->fromArray( $dvtData, NULL, 'A'.(ANOTHER_ORDER_START_ROW + 1));
        $spreadsheet->getActiveSheet()->removeRow(ANOTHER_ORDER_START_ROW, 1);

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
                null,
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
        $spreadsheet->getActiveSheet()->insertNewRowBefore(ANOTHER_ORDER_START_ROW + 1, count($kvtData));
        $spreadsheet->getActiveSheet()->fromArray( $kvtData, NULL, 'A'.(ANOTHER_ORDER_START_ROW + 1));
        $spreadsheet->getActiveSheet()->removeRow(ANOTHER_ORDER_START_ROW, 1);

        // sheet 3
        $spreadsheet->setActiveSheetIndex(2);
        $dvtListData = array();
        foreach($dvtList as $index => $dvt){
            $currency = isset($dvt['currency']) ? $dvt['currency'] : '';
            $tempArray = array(
                ($index+1),
                $dvt['order_date'],
                trim($dvt['dvt_no']),
                null,
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
        $spreadsheet->getActiveSheet()->insertNewRowBefore(ANOTHER_ORDER_START_ROW + 2, count($dvtListData));
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
        $spreadsheet->getActiveSheet()->insertNewRowBefore(ANOTHER_ORDER_START_ROW + 2, count($staffData));
        $spreadsheet->getActiveSheet()->fromArray( $staffData, NULL, 'B3');

        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="another_order.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
