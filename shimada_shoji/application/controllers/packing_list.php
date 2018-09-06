<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

define('STATUS_NOT_YET','019');
define('STATUS_NOT_YET_NAME','Not yet');

define('COL_TYPE','C');
define('COL_NUMBER','D');
define('COL_SL','E');
define('COL_ITEM_CODE_FIRST','F');
define('COL_ITEM_CODE_LAST','G');
define('COL_ITEM_NAME_FIRST','H');
define('COL_ITEM_NAME_LAST','J');
define('COL_SIZE','K');
define('COL_COLOR','L');
define('COL_DETAIL','M');
define('COL_QUANTITY','N');
define('COL_NET','O');
define('COL_GROSS','P');
define('COL_MEA','Q');

define('COL_ROW_CASE_MARK','N3');
define('COL_ROW_DVT','G3');
define('COL_ROW_KVT','G4');
define('COL_ROW_PO','G5');
define('COL_PACK_DATE','D3');
define('COL_FACTORY','D4');
define('COL_CUSTOMER','J4');
define('COL_ITEM_TYPE','J3');

define('PAGE','N9');
define('TOTAL_PAGE','N10');

define('COL_TOTAL_CARTON', 20);
define('COL_TOTAL_BALE', 21);
define('COL_TOTAL_PACKAGE', 22);
//Fix 3 first row for copy style
define('START_ROW', 11);


define('CARTON_CODE','001');
define('BALE_CODE','002');
define('PACKAGE_CODE','003');
define('MAX_ITEM_PER_PAGE',56);

class Packing_List extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('partners_model');
        $this->load->model('delivery_model');
        $this->load->model('komoku_model');
        $this->load->model('company_model');
        $this->load->model('packing_model');
        $this->load->model('packing_details_model');
        $this->load->model('store_item_model');
        $this->load->model('konpo_model');
        $this->load->model('order_received_details_model');
        $this->data['screen_id'] = 'PLS0010';
        
    }
    public function index()
    {
        if ($this->is_logged_in()) {

            $this->data['title'] = $this->lang->line('packing_list');
            // Load Delivery list
            $this->data["delivery_list"] = $this->delivery_model->getAllDelivery();
            // Load Status list
            $this->data["komoku_list"] = $this->komoku_model->getKomokuName();
            // Load Company Name 
            $this->data["customer_list"] = $this->company_model->getDistinctBranchName();
            
            // Load the subview
            $content = $this->load->view('packing_list/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    public function search(){
        if ($this->is_logged_in()) {
            $param = $this->input->post("param");
            $result = $this->packing_model->searchPack(
                $param, $this->input->post("start"), $this->input->post("length"),$recordsFiltered
                ,$recordsTotal);
            echo json_encode(array(
                'data' => $result,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'draw' => $this->input->post('draw')
                ));
        }
    }
    public function apply(){
        
        if ($this->is_logged_in()) {
            $id = $this->input->post("id");
            if($this->packing_model->checkPackingById($id)){
                $result = $this->packing_model->applyPacking($id, $this->data['user']['employee_id'], "013");
                echo json_encode(array(
                    'success'=>$result,
                    'message'=>($result?$this->lang->line("PLS0010_I004"):$this->lang->line("PLS0010_E002"))
                ));
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message'=>$this->lang->line("PLS0010_E001")
                ));
            }

        }

    }
    public function accept1(){
        
        if ($this->is_logged_in()) {
            $id = $this->input->post("id");
            if($this->packing_model->checkPackingById($id)){
                $result = "";
                if($this->packing_model->checkCDTTNotYet($id)){
                    $result = $this->packing_model->updateStatusPacking($id, $this->data['user']['employee_id'], "021");
                }else{
                    $result = $this->packing_model->updateStatusPacking($id, $this->data['user']['employee_id']);
                }
                echo json_encode(array(
                    'success'=>$result,
                    'message'=>($result?$this->lang->line("PLS0010_I001"):$this->lang->line("PLS0010_E002"))
                ));
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message'=>$this->lang->line("PLS0010_E001")
                ));
            }

        }

    }
    public function cdtt(){
        
        if ($this->is_logged_in()) {
            $id = $this->input->post("id");
            if($this->packing_model->checkPackingById($id)){
                $result = $this->packing_model->updateCDTTPacking($id, $this->data['user']['employee_id'], "022");
                echo json_encode(array(
                    'success'=>$result,
                    'message'=>($result?$this->lang->line("PLS0010_I013"):$this->lang->line("PLS0010_E002"))
                ));
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message'=>$this->lang->line("PLS0010_E001")
                ));
            }

        }

    }

    public function accept(){
        
        if ($this->is_logged_in()) {
            $id = $this->input->post("id");
            // $msg = $this->common->getMessage("PLS0010","COM0010_E_001");
            if($this->packing_model->checkPackingById($id)){
                $result = $this->packing_model->updateStatusPacking($id, $this->data['user']['employee_id'], "018");
                echo json_encode(array(
                    'success'=>$result,
                    'message'=>($result?$this->lang->line("PLS0010_I001"):$this->lang->line("PLS0010_E002"))
                ));
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message'=>$this->lang->line("PLS0010_E001")
                ));
            }

        }

    }

    public function denied(){
        
        if ($this->is_logged_in()) {
            $params = $this->input->post();
            // $msg = $this->common->getMessage("PLS0010","COM0010_E_001");
            if($this->packing_model->checkPackingById($params["id"])){
                $result = $this->packing_model->deniedPackingById($params, $this->data['user']['employee_id']);
                echo json_encode(array(
                    'success'=>$result,
                    'message'=>($result?$this->lang->line("PLS0010_I003"):$this->lang->line("PLS0010_E002"))
                ));
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message'=>$this->lang->line("PLS0010_E001")
                ));
            }

        }

    }

    public function delete(){
        if ($this->is_logged_in()) {
            $id = $this->input->post("id");
            if($this->packing_model->checkPackingById($id)){
                $result = $this->packing_model->deletePackingById($id, $this->data['user']['employee_id']);
                $this->packing_details_model->deletePackingDetailsByPackNo($id, $this->data['user']['employee_id']);
                $detailList = $this->packing_details_model->getPackingDetailsByList($id, array());
                echo json_encode(array(
                    'success'=>$result,
                    'message'=>($result ? $this->lang->line("PLS0010_I002"):$this->lang->line("PLS0010_E002"))
                ));
                $dvtObj = array();
                //update kvt status
                foreach($detailList as $de){
                    $this->delivery_model->updateKVTStatus(null, $de);
                    $dvt = array("dvt_no"=>$de['dvt_no'],'times'=>$de['times'], 'order_date' => $de['order_date']);
                    if(!in_array($dvt ,  $dvtObj)){
                        array_push($dvtObj,  $dvt);
                    }
                };
                // update dvt status
                foreach($dvtObj as $dvt){
                    $isPacked = $this->delivery_model->isDVTPacked("013", $dvt);
                    if($isPacked){
                        $this->delivery_model->updateDeliveryStatus($dvt['dvt_no'], $this->data['user']['employee_id'], $dvt['order_date'], $dvt['times'], '013');
                    }else{
                        $this->delivery_model->updateDeliveryStatus($dvt['dvt_no'], $this->data['user']['employee_id'], $dvt['order_date'], $dvt['times'], "019");
                    }
                };
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message'=>$this->lang->line("PLS0010_E001")
                ));
            }
        }
    }

    public function add()
    {
        if ($this->is_logged_in()) {

            $this->data['package_type_list'] = $this->komoku_model->getAllPackingType();
            $this->data['types_list'] = $this->komoku_model->getAllTypes();

            $this->data['packing_details_list'] = array();

            $this->data["komoku_list"] = $this->komoku_model->getStatusFinishOrNotYet();
            $this->data['screen_id'] = 'PLS0020';
            // get customers
            $customerList = $this->company_model->getAllCustomer();
            foreach ($customerList as $key => &$customer) {
                $customer['branches'] = $this->company_model->getAllBranchOfCompany($customer['company_id']);
                $customer['head_offices'] = $this->company_model->getAllHeadOfficeOfCompany($customer['company_id']);
            };
            // $this->data['customerList'] = $customerList;
            $this->data["delivery_to"] = $this->company_model->getAllBranchName();

            $this->data["provisionals"] = $this->order_received_details_model->getDistinctOrderReciveredDetails();
            $this->data["INV"] = $this->store_item_model->getStoreItemsByStatus();
            
            $this->data['title'] = $this->lang->line('packing_input');
            $this->data['status_not_yet'] = STATUS_NOT_YET;
            $this->data['status_not_yet_name'] = STATUS_NOT_YET_NAME;
            $lastPackOj = $this->packing_model->getLastPackingId();
            $this->data["pack_no"] = $lastPackOj[0]["pack_no"] + 1;
            // Load the subview
            $content = $this->load->view('packing_list/add.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
	}

	public function edit()
    {
        if ($this->is_logged_in()) {
            $packing_id = $this->input->get("id");
            $packing = $this->packing_model->getPackingById($packing_id);
            if(count($packing) == 0){
                show_error('', 401);
                return;
            }

            $this->data["INV"] = $this->store_item_model->getStoreItemsByStatus();
            $this->data['packing'] = $packing[0];

            $this->data["komoku_list"] = $this->komoku_model->getStatusFinishOrNotYet();

            $this->data['package_type_list'] = $this->komoku_model->getAllPackingType();
            $this->data['types_list'] = $this->komoku_model->getAllTypes();

            $this->data['packing_details_list'] = $this->packing_details_model->getPackingDetailsByPackingId($packing_id);
            
            $this->data['screen_id'] = 'PLS0020';
            // get customers
            $customerList = $this->company_model->getAllCustomer();
            foreach ($customerList as $key => &$customer) {
                $customer['branches'] = $this->company_model->getAllBranchOfCompany($customer['company_id']);
                $customer['head_offices'] = $this->company_model->getAllHeadOfficeOfCompany($customer['company_id']);
            };
            $new_index = count($customerList);
            if($packing[0]["customer"] != "") {
                if(!in_array($packing[0]["customer"], array_column($customerList, "company_name"))) {
                    $customerList[$new_index] = ["company_name" => $packing[0]["customer"]];
                }
            }
            if($packing[0]["delry_to"] != "") {
                if(!in_array($packing[0]["delry_to"], array_column(array_column($customerList, "branches"), "branch_name"))) {
                    $customerList[$new_index]['branches'][] = [
                        "branch_name" => $packing[0]["delry_to"],
                        "branch_address" => $packing[0]["delry_to_add"],
                    ];
                    $customerList[$new_index]['head_offices'][] = [];
                }
            }

            $this->data['customerList'] = $customerList;
            
			$this->data['title'] = $this->lang->line('packing_update');
            // Load the subview
            $content = $this->load->view('packing_list/edit.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    public function save(){
        if ($this->is_logged_in()) {
            $isSuccess =  true;
            // save packing
            $packing = $this->input->post();
            if(isset($packing["invoice_no"])){
                $packing["invoice_no"] = strtoupper($packing["invoice_no"]);
            }
            $isExist = $this->packing_model->isPackingExist($packing['pack_no']);
            // this DVT list use to update status of dvt
            $dvtObj = json_decode($packing["dvt_obj"], true);
            $packingDetail = json_decode($this->input->post("packing_details"), true);
            unset($packing['dvt_obj']);
            if($isExist){
                $packing['quantity'] = $packing['quantity'] != '' ? str_replace(',','',$packing['quantity']) : 0;
                $packing['netwt'] = $packing['netwt'] != '' ? str_replace(',','',$packing['netwt']) : 0;
                $packing['grosswt'] = $packing['grosswt'] != '' ? str_replace(',','',$packing['grosswt']) : 0;
                $packing['measure'] = $packing['measure'] != '' ? str_replace(',','',$packing['measure']) : 0;
                $packing['packages'] = $packing['packages'] != '' ? str_replace(',','',$packing['packages']) : 0;
                if($packing['accept_date'] == ''){
                    unset($packing['accept_date']);
                }
                // update packing
                unset($packing['packing_details']);
                unset($packing['status_name']);
                $packing["customer"] = $packing['company_name'];
                unset($packing['company_name']);
                $packing['edit_user'] = $this->data['user']['employee_id'];
                $packing['edit_date'] = date("Y-m-d H:i:s");
                $isSuccess &= $this->packing_model->updatePacking($packing);

                // save packing_details 
                
                $unDelete = array();
                $total = array('quantity'=>0, 'netwt' => 0, 'grosswt'=>0,
                 'measure'=>0, 'pack_no'=>$packing['pack_no'], 'packages'=>0);
                foreach($packingDetail as $detail){
                    $tmpFrom = preg_replace('/\D/', '', $detail['number_from']);
                    $tmpTo = preg_replace('/\D/', '', $detail['number_to']);
                    $detail['lot_no'] = $detail['lot_no'] == ''?0:$detail['lot_no'];
                    $detail['number_to'] = $detail['number_to'] == ''?0:$detail['number_to'];
                    $detail['quantity_detail'] = $detail['quantity_detail'] != '' ? str_replace(',','',$detail['quantity_detail']) : 0;
                    $detail['multiple'] = $detail['multiple'] != '' ? str_replace(',','',$detail['multiple']) : 1;
                    $detail['quantity'] = $detail['quantity_detail'] * $detail['multiple'];
                    $detail['netwt'] = $detail['netwt'] == ''?0:$detail['netwt'];
                    $detail['grosswt'] = $detail['grosswt'] == ''?0:$detail['grosswt'];
                    $detail['measure'] = $detail['measure'] == ''?0:$detail['measure'];
                    $detail['number_from'] = $detail['number_from'] == ''?0:$detail['number_from'];

                    //caculator total
                    $total['quantity'] += $detail['quantity'];
                    $total['netwt'] += $detail['netwt'];
                    $total['grosswt'] += $detail['grosswt'];
                    $total['measure'] += $detail['measure'];
                    $total['packages'] += $detail['number_to']!==0?$tmpTo - $tmpFrom + 1:1;

                    if(isset($detail['pack_no']) && $detail['pack_no'] != null){
                        array_push($unDelete, $detail['packing_details']);
                        $detail['edit_user'] = $this->data['user']['employee_id'];
                        $detail['edit_date'] = date("Y-m-d H:i:s"); 
                        if(isset($detail['item_types'])){
                            unset($detail['item_types']);
                        }
                        if(isset($detail[''])){
                            unset($detail['']);
                        }
                        $isSuccess &= $this->packing_details_model->updatePackingDetails($detail);

                    }else{
                        // add new packing details
                        $result = $this->packing_details_model->getMaxDetailsNo($packing['pack_no']);
                        $nextDetailsNo = 1;
                        if(count($result) > 0){
                            $nextDetailsNo = $result[0]['packing_details'] + 1;
                        }
                        array_push($unDelete,  $nextDetailsNo);
                        $detail['pack_no'] = $packing['pack_no'];
                        $detail['packing_details'] = $nextDetailsNo;
                        $detail['create_user'] = $this->data['user']['employee_id'];
                        $detail['create_date'] = date("Y-m-d H:i:s");
                        if(isset($detail['item_types'])){
                            unset($detail['item_types']);
                        }
                        if(isset($detail[''])){
                            unset($detail['']);
                        }
                        $isSuccess &=$this->packing_details_model->savePackingDetails($detail);
                    } 
                }
                
                $isSuccess &= $this->packing_model->updatePacking($total);
                // delete packing
                $detailList = $this->packing_details_model->getPackingDetailsByList($packing['pack_no'], $unDelete);
                $isSuccess &= $this->packing_details_model->deletePackingDetailsExceptDetails($packing['pack_no'], $unDelete,  $this->data['user']['employee_id']);
                foreach($detailList as $de){
                    $isSuccess &= $this->delivery_model->updateKVTStatus(null, $de);
                }
            }else{
                // add new Packing
                unset($packing['packing_details']);
                unset($packing['status_name']);
                $packing['create_user'] = $this->data['user']['employee_id'];
                $packing['create_date'] = date("Y-m-d H:i:s"); 
                $packing['del_flg'] = '0';
                $packing["customer"] = $packing['company_name'];
                unset($packing['company_name']);
                if(!$this->packing_model->savePacking($packing)){
                    // roll back!
                    return true;
                }
                $pack_no = $packing['pack_no']; 
                // add packing details
                $total = array('pack_no'=> $pack_no, );
                $total = array('quantity'=>0, 'netwt' => 0, 'grosswt'=>0,
                 'measure'=>0, 'pack_no'=>$pack_no, 'packages'=>0);
                foreach($packingDetail as $detail){
                    $tmpFrom = preg_replace('/\D/', '', $detail['number_from']);
                    $tmpTo = preg_replace('/\D/', '', $detail['number_to']);
                    $detail['lot_no'] = $detail['lot_no'] == ''?0:$detail['lot_no'];
                    $detail['number_to'] = $detail['number_to'] == ''?0:$detail['number_to'];
                    $detail['quantity_detail'] = $detail['quantity_detail'] != '' ? str_replace(',','',$detail['quantity_detail']) : 0;
                    $detail['multiple'] = $detail['multiple'] != '' ? str_replace(',','',$detail['multiple']) : 1;
                    $detail['quantity'] = $detail['quantity_detail'] * $detail['multiple'];
                    $detail['netwt'] = $detail['netwt'] == ''?0:$detail['netwt'];
                    $detail['grosswt'] = $detail['grosswt'] == ''?0:$detail['grosswt'];
                    $detail['measure'] = $detail['measure'] == ''?0:$detail['measure'];
                    $detail['number_from'] = $detail['number_from'] == ''?0:$detail['number_from'];

                    //caculator total
                    $total['quantity'] += $detail['quantity'];
                    $total['netwt'] += $detail['netwt'];
                    $total['grosswt'] += $detail['grosswt'];
                    $total['measure'] += $detail['measure'];
                    $total['packages'] += $detail['number_to']!==0?$tmpTo - $tmpFrom + 1:1;

                    $result = $this->packing_details_model->getMaxDetailsNo($pack_no);
                    $nextDetailsNo = 1;
                    if(count($result) > 0){
                        $nextDetailsNo = $result[0]['packing_details'] + 1;
                    }

                    $detail['pack_no'] = $pack_no;
                    $detail['packing_details'] = $nextDetailsNo;
                    $detail['create_user'] = $this->data['user']['employee_id'];
                    $detail['create_date'] = date("Y-m-d H:i:s");
                    if(isset($detail['item_types'])){
                        unset($detail['item_types']);
                    }
                    if(isset($detail[''])){
                        unset($detail['']);
                    }
                    $isSuccess &= $this->packing_details_model->savePackingDetails($detail);
                }
                $isSuccess &= $this->packing_model->updatePacking($total);
            }
            if($isSuccess){
                $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            }else{
                $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
            }
            foreach($packingDetail as $detail){
                $this->delivery_model->updateKVTStatus("013", $detail);
            }
            if($dvtObj != null){
                foreach($dvtObj as $dvt){
                    $isPacked = $this->delivery_model->isDVTPacked("013", $dvt);
                    if($isPacked){
                        $this->delivery_model->updateDeliveryStatus($dvt['dvt_no'], $this->data['user']['employee_id'], $dvt['order_date'], $dvt['times'], '013');
                    }else{
                        $this->delivery_model->updateDeliveryStatus($dvt['dvt_no'], $this->data['user']['employee_id'], $dvt['order_date'], $dvt['times'], "019");
                    }
                }
            }
            redirect(base_url("packing_list/edit?id=".$packing['pack_no']));
        }
    }
    public function getStoreItemsList(){
        $params = $this->input->post('param');
        if($params == ''){
            $result = $this->store_item_model->getStoreItemsByStatus();
            echo json_encode(array('data' => $result));
            return;
        }
        $result = $this->store_item_model->getStoreItemsByStatus($params);
        echo json_encode(array('data' => $result));
    }
    // public function loadPackingDetail(){
    //     if ($this->is_logged_in()) {

    //         $packing_id = $this->input->post("id");
    //         $result = $this->packing_details_model->getPackingDetailsByPackingId($packing_id, 
    //         $this->input->post("start"),
    //         $this->input->post("length"),
    //         $recordsTotal, $recordsFiltered);
    //         echo json_encode(array(
    //             'data' => $result,
    //             'recordsTotal' => $recordsTotal,
    //             'recordsFiltered' => $recordsFiltered,
    //             'draw' => $this->input->get('draw')
    //             ));
    //     }
    // }
    public function loadPackingOrder(){
        if ($this->is_logged_in()) {
            $orderReceiveNo = $this->input->post("order_receive_no");
            $partitionNo = $this->input->post("partition_no");
            $orderReceiveDate = $this->input->post("order_receive_date");
            $results = $this->order_received_details_model->getOrderReceivedDetails($orderReceiveNo, $partitionNo, $orderReceiveDate);
            echo json_encode(array('data'=>$results));
        }
    }
    public function loadDeliveryKDV(){
        if ($this->is_logged_in()) {
            $kubun = $this->input->get("kubun");
            if(NULL !== $this->input->get("factory")) { $dvtFactory = $this->input->get("factory"); }
            $kubun = ($kubun == null || $kubun == "") ? array("1","2") : array($kubun);
            if(isset($dvtFactory)) {
                $results = $this->delivery_model->getAllDeliveryAndKVTByKubun($kubun, $this->input->post("start"), $this->input->post("length"), $recordsTotal, $recordsFiltered, $dvtFactory);
            } else {
                $results = $this->delivery_model->getAllDeliveryAndKVTByKubun($kubun, $this->input->post("start"), $this->input->post("length"), $recordsTotal, $recordsFiltered);
            }
            foreach($results as &$rs){
                $count = 1;
                foreach($results as $temp){
                    if($rs['order_date'] == $temp['order_date'] && $rs['dvt_no'] == $temp['dvt_no'] && $rs['times'] != $temp['times']){
                        $count++;
                    }
                }
                $rs['count'] = $count;
            }
            echo json_encode(array(
                'data' => $results,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'draw' => $this->input->post('draw')
            ));
        }
    }
    public function loadItemByDVTandKVT(){
        if ($this->is_logged_in()) {
            $results = $this->delivery_model->getItemByDVTAndKVT(
                $this->input->post("dvt_no"), $this->input->post("kvt_no"),
                $this->input->post("start"),
                $this->input->post("length"),
                $recordsTotal, $recordsFiltered);
            echo json_encode(array(
                'data' => $results,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'draw' => $this->input->post('draw')
            ));
        }
    }

    public function excel(){
        if ($this->is_logged_in()) {
            $pack_no = $this->input->get("packing_slip_packing_no");
            $packDetails = $this->packing_details_model->getPackingDetailsByPackingNo($pack_no);
            $this->writePackingSlip('views/packing_list/template.xlsx', $packDetails);
            $this->packing_model->updatePrintExcelDate($pack_no, $this->data['user']['employee_id']);
        }
    }
    public function checkprovisionaldata(){
        if( $this->is_logged_in() ){
            $data = $this->input->get();
            $KVTDVT = $this->delivery_model->getPartOfItemByDVTAndKVT($data['dvt_no'], $data['kvt_no']);
            $isMatch = false;
            $message = $this->lang->line('PLS0010_E003');
            if(count($KVTDVT) > 0 && count($KVTDVT) == count($data["items"])){
                //compare array
                $isMatch = ($KVTDVT == $data["items"]);     
            }
            echo json_encode(array(
                'isMatch' => $isMatch,
                'message'=>$message
            ));
        }
    }

    public function checkdatachange(){
        if( $this->is_logged_in() ){
            $data = $this->input->get();
            $result =$this->packing_model->getEditDateByPackNo($data['pack_no']);
            if(count($result) > 0){
                $format = 'Y-m-d H:i:s';
                $dbDate = DateTime::createFromFormat($format, $result[0]['edit_date']);
                $clDate = DateTime::createFromFormat($format, $data['last_edit_date']);
                echo json_encode(array(
                    'hasChange' => ($dbDate  != $clDate),
                    'message'=>$this->lang->line('COMMON_E001')
                ));

            }else{
                echo json_encode(array(
                    'hasChange' => true,
                    'message'=>$this->lang->line('COMMON_E001')
                ));

            }
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

    private function writePackingSlip($filePath, $packings){
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);
        $spreadsheet->setActiveSheetIndex(0);
        $totalPage = 0;
        $from_prev = "";
        $item_code_prev = "";
        $item_size_prev = "";
        $item_color_prev = "";
        $unit_item = array();
        $empty_row = [null, null,null, null,null, null,null, null,null, null,null, null,null, null,null, null,null, null];
        $dataArr = array();
        $arr_dvt = array();
        $arr_kvt = array();
        $items = [];
        foreach($packings as $sheetIndex => $packing) {
            array_push($arr_dvt, $packing['dvt']);
            array_push($arr_kvt, $packing['kvt']);
            $itemList = $packing["items"];
            $items = array_merge($items, $itemList);
        }
        usort($items, function ($a, $b) {
            return strcmp($a['number_from'], $b['number_from']);
        });
        $item_type = array();
        foreach($items as $index => $item){
            if($item['unit']){
                $unit = $item['unit'];
                if(!in_array($unit,$unit_item)){
                    array_push($unit_item, $unit);
                }
            }
            if($index !== 0){
                $from_prev = $items[$index - 1]["number_from"];
                $item_code_prev = $items[$index - 1]["item_code"];
                $item_size_prev = $items[$index - 1]["size"];
                $item_color_prev = $items[$index - 1]["color"];
                $item_name_prev = $items[$index - 1]["item_name"];
            }
            if(!in_array($item["item_type"], $item_type)){
                array_push($item_type, $item["item_type"]);
            }

            $packageTypeName = $item['package_type_name'] ? $item['package_type_name'] : "";
            if($packageTypeName === "CARTON"){
                $packageTypeName = "CT";
            }else if($packageTypeName === "BALE"){
                $packageTypeName = "BALE";
            }else if($packageTypeName === "PACKAGE"){
                $packageTypeName = "PK";
            }
            $tempData = array();
            // Same from not print 5 column first
            if($index !== 0 && $from_prev === $item["number_from"]){
                // Same item code not print 10 column first
                if($item_code_prev === $item["item_code"]){
                    // Same size not print 10 column first
                    if($item_size_prev === $item["size"] && $item_color_prev === $item["color"] && $item_name_prev === $item["item_name"]){
                            $tempData = array(
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
                                $item['quantity_detail'],
                                "x".$item['multiple'],
                                $item['quantity'] != 0 ? $item['quantity'] : null,
                                $item['unit'] ? $item['unit'] : null,
                                $item['netwt'] != 0 ? $item['netwt'] : null,
                                $item['grosswt'] != 0 ? $item['grosswt'] : null,
                                $item['package_type_1'] != "" ? $item['package_type_1'] : null,
                                $item['measure'] != 0 ? $item['measure'] : null,
                                $item['note'],
                            );
                        } else{
                        $tempData = array(
                            null,
                            null,
                            null,
                            null,
                            null,
                            $item['item_code'] ? $item['item_code'] : null,
                            $item['item_name'],
                            $item['size'] ? $item['size'] : null,
                            $item['size_unit'] ? $item['size_unit'] : null,
                            $item['color'],
                            $item['quantity_detail'],
                            "x".$item['multiple'],
                            $item['quantity'] != 0 ? $item['quantity'] : null,
                            $item['unit'] ? $item['unit'] : null,
                            $item['netwt'] != 0 ? $item['netwt'] : null,
                            $item['grosswt'] != 0 ? $item['grosswt'] : null,
                            $item['package_type_1'] != "" ? $item['package_type_1'] : null,
                            $item['measure'] != 0 ? $item['measure'] : null,
                            $item['note'],
                        );
                    }
                }else{
                    array_push($dataArr, $empty_row);
                    $tempData = array(
                        null,
                        null,
                        null,
                        null,
                        null,
                        $item['item_code'] ? $item['item_code'] : null,
                        $item['item_name'],
                        $item['size'] ? $item['size'] : null,
                        $item['size_unit'] ? $item['size_unit'] : null,
                        $item['color'],
                        $item['quantity_detail'],
                        "x".$item['multiple'],
                        $item['quantity'] != 0 ? $item['quantity'] : null,
                        $item['unit'] ? $item['unit'] : null,
                        $item['netwt'] != 0 ? $item['netwt'] : null,
                        $item['grosswt'] != 0 ? $item['grosswt'] : null,
                        $item['package_type_1'] != "" ? $item['package_type_1'] : null,
                        $item['measure'] != 0 ? $item['measure'] : null,
                        $item['note'],
                    );
                }
            }else{
                $tmpFrom = preg_replace('/\D/', '', $item['number_from']);
                $tmpTo = preg_replace('/\D/', '', $item['number_to']);
                $invNo = $item['inv_no'];
                $lastIndex = strrpos($invNo, "-");
                if($lastIndex !== FALSE){
                    $invNo = substr($invNo, 0, $lastIndex);
                }
                $tempData = array(
                    (!empty($item['number_to']) ? $item['number_from'].'-'.$item['number_to'] : ($item['number_from'] != NULL && $item['number_from'] != 0 ? $item['number_from'] : null)),
                    !empty($item['number_to']) ? $tmpTo - $tmpFrom + 1: 1,
                    $packageTypeName,
                    $invNo,
                    $item['lot_no'],
                    $item['item_code'] ? $item['item_code'] : null,
                    $item['item_name'],
                    $item['size'] ? $item['size'] : null,
                    $item['size_unit'] ? $item['size_unit'] : null,
                    $item['color'],
                    $item['quantity_detail'],
                    "x".$item['multiple'],
                    $item['quantity'] != 0 ? $item['quantity'] : null,
                    $item['unit'] ? $item['unit'] : null,
                    $item['netwt'] != 0 ? $item['netwt'] : null,
                    $item['grosswt'] != 0 ? $item['grosswt'] : null,
                    $item['package_type_1'] != "" ? $item['package_type_1'] : null,
                    $item['measure'] != 0 ? $item['measure'] : null,
                    $item['note'],
                );
            }
            // Add empty row if number from new
            if($index !== 0 && $from_prev !== $item["number_from"]){
                array_push($dataArr, $empty_row);
            }
            array_push($dataArr, $tempData);
        }

        $dvt = implode(", ", $arr_dvt);
        $kvt = implode(", ", $arr_kvt);
        
        //insert new rows
        $spreadsheet->getActiveSheet()->insertNewRowBefore(START_ROW+1, count($dataArr));
        $startRowFormula = intval(START_ROW);
        $endRowFormula = $startRowFormula + count($dataArr);
        // set data
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "A".START_ROW);
        $spreadsheet->getActiveSheet()->setCellValue(COL_PACK_DATE, $packing["packing_date"]);
        $spreadsheet->getActiveSheet()->setCellValue(COL_FACTORY, $packing["factory"]);
        if($packing["kubun"]==="1"){
            $spreadsheet->getActiveSheet()->setCellValue(COL_ROW_DVT, $dvt);
            $spreadsheet->getActiveSheet()->setCellValue(COL_ROW_KVT, $kvt);
        }else{
            $spreadsheet->getActiveSheet()->setCellValue(COL_ROW_PO, $dvt);
        }
        
        $spreadsheet->getActiveSheet()->setCellValue(COL_CUSTOMER, $packing["customer"]);
        $spreadsheet->getActiveSheet()->setCellValue(COL_ITEM_TYPE, $packing['types']);
        $spreadsheet->getActiveSheet()->setCellValue(COL_ROW_CASE_MARK, $packing["case_mark"]);
        // Set formula
        $count_unit = count($unit_item);
        for($a = 0; $a < $count_unit; $a++){
            if($a <= $count_unit+1){
                $spreadsheet->getActiveSheet()->setCellValue("N".($endRowFormula+$a+2), $unit_item[$a]);
                $spreadsheet->getActiveSheet()->setCellValue("M".($endRowFormula+$a+2), '=SUMIF($N$'.$startRowFormula.':$N$'.$endRowFormula.',$N'.($endRowFormula+$a+2).',$M$'.$startRowFormula.':$M$'.$endRowFormula.')');
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue("O".($endRowFormula+2), '=SUM(O'.$startRowFormula.':O'.$endRowFormula.')');
        $spreadsheet->getActiveSheet()->setCellValue("P".($endRowFormula+2), '=SUM(P'.$startRowFormula.':P'.$endRowFormula.')');
        $spreadsheet->getActiveSheet()->setCellValue("Q".($endRowFormula+2), '=SUM(R'.$startRowFormula.':R'.$endRowFormula.')');

        $spreadsheet->getActiveSheet()->setCellValue("K".($endRowFormula+10), $packing["name_pdg"]);
        // Set background color for empty row
        for($i = START_ROW; $i <= count($dataArr) + START_ROW; $i++){
            // if row empty
            $dataArray = $spreadsheet->getActiveSheet()->rangeToArray("A$i:R$i")[0];
            if(count(array_unique($dataArray))==1 && end($dataArray) == null){
                $spreadsheet->getActiveSheet()->getStyle("A$i:R$i")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFFFFFFF');
                $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(25);
            }
        }
        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="packing.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function do_allocation(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        try {
            $params = $this->input->post();
            $result = null;
            $result = $this->doAllocationData($params);
            if(count( $params['unAllocateList']) > 0) {
                $this->doUnAllocationData($params['unAllocateList']);
            }
            if ($result["success"]) {
                $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
                $this->responseJsonSuccess(implode(",",$result["inv_no"]), $this->lang->line("COMMON_I002"));
            } else {
                $this->responseJsonError($this->lang->line("save_fail"));
            }
        } catch(Error $e) {
            $this->responseJsonError($e->getMessage());
        }
    }
    private function doAllocationData($datas) {
        // if (!$this->checkAllocation($data, "quantity >= ")) {
        //     throw new Error('quantity not enough');
        // }
        $this->load->model('komoku_model');
        $this->load->model('order_received_model');
        $inv_no = [];

        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];

        $this->db->trans_begin();
        $inv_no_quantity = $datas['inv_no_quantity'];
        $arr_inv_quantity = [];
        foreach(explode(",",$inv_no_quantity) as $inv_quantity){
            $tmpArr = explode("-",$inv_quantity);
            $quantity = array_pop($tmpArr);
            $tmp_inv = implode("-",$tmpArr);
            if(!array_key_exists($tmp_inv, $arr_inv_quantity)){
                $arr_inv_quantity[$tmp_inv] = $quantity;
            }
        }

        foreach ($datas['selectedInventoryItem'] as $idx => $selectedInventoryItem) {
            $quantity_allocated = 0;
            if(array_key_exists($selectedInventoryItem["inv_no"], $arr_inv_quantity)){
                $quantity_allocated = $arr_inv_quantity[$selectedInventoryItem["inv_no"]];
            }
            $hikiateQuantity = $selectedInventoryItem['hikiate_quantity'];
            $item_order_receive_no = $selectedInventoryItem['order_receive_no'];
            $item_partition_no = $selectedInventoryItem['partition_no'];
            $order_no = $selectedInventoryItem['order_no'];
            $sales_man = $selectedInventoryItem['salesman'];
            
            $data = $selectedInventoryItem;
            $data["item_order_receive_no"] = $data["order_receive_no"];
            $data["item_partition_no"] = $data["partition_no"];
            // unset($data['item_order_receive_no']);
            // unset($data['item_partition_no']);

            $inventoryHikiate = min($hikiateQuantity, $selectedInventoryItem['out_quantity']);
            $inventoryQuantity = ($selectedInventoryItem['out_quantity'] - $hikiateQuantity);

            if ($idx == count($datas['selectedInventoryItem']) - 1 &&
            $this->order_received_model->checkAllocation($data, $hikiateQuantity)) {
                // insert new inventory with quantity = selectedInventoryItem['inspect_ok']
                $this->db->select("*");
                $this->db->from('t_store_item');
                $this->db->where('del_flg', '0');
                $this->db->where('salesman', $sales_man);
                $this->db->where('item_code', $data['item_code']);
                $this->db->where('color', $data['color']);
                $this->db->where('size', $data['size']);
                $this->db->where('order_receive_no', $item_order_receive_no);
                $this->db->where('partition_no', $item_partition_no);
                $this->db->where('order_no', $order_no);

                $getInventoryItem = $this->db->get();
                $inventoryItem = $getInventoryItem->result_array();

                $warehouse = $this->komoku_model->getWarehouseWithName($inventoryItem[0]['warehouse']);
                $inventoryItem[0]['inspect_ng'] = 0;
                $inventoryItem[0]['create_user'] = $currentEmployeeId;
                $inventoryItem[0]['create_date'] = $now;
                // $inventoryItem[0]['order_receive_no'] = $inventoryItem['order_receive_no'];
                // $inventoryItem[0]['partition_no'] = $inventoryItem['partition_no'];
                // $inventoryItem[0]['odr_recv_date'] = $inventoryItem['order_receive_date'];
                // $inventoryItem[0]['odr_recv_detail_no'] = $inventoryItem['order_receive_detail_no'];
                $inventoryItem[0]['status'] = RESERVED_ITEM;
                $inventoryItem[0]['warehouse'] = (count($warehouse) > 0 ? $warehouse[0]['kubun'] : $inventoryItem[0]['warehouse']);
                $inventoryItem[0]['note'] =  (count($warehouse) > 0 ? $warehouse[0]['warehouse'] : '');
                
                if(isset($inventoryItem[0]["edit_user"])){
                    unset($inventoryItem[0]["edit_user"]);
                }
                if(isset($inventoryItem[0]["edit_date"])){
                    unset($inventoryItem[0]["edit_date"]);
                }
                //Check da allocate chua
                // $this->db->reset_query();
                $where_check = Array();
                $where_check["salesman"] = $inventoryItem[0]["salesman"];
                $where_check["item_code"] = $inventoryItem[0]["item_code"];
                $where_check["item_type"] = $inventoryItem[0]["item_type"];
                $where_check["order_receive_no"] = $inventoryItem[0]["order_receive_no"];
                $where_check["partition_no"] = $inventoryItem[0]["partition_no"];
                $where_check["order_no"] = $inventoryItem[0]["order_no"];
                $where_check["order_detail_no"] = $inventoryItem[0]["order_detail_no"];
                $where_check["warehouse"] = $inventoryItem[0]["warehouse"];
                $where_check["size"] = $inventoryItem[0]["size"];
                $where_check["color"] = $inventoryItem[0]["color"];
                $this->db->where($where_check);
                $query = $this->db->get("t_store_item");
                $numrow = $query->num_rows();
                if($numrow>0){
                    //Update cho xuat
                    // $inventoryItem[0]['quantity'] += $inventoryHikiate - $quantity_allocated;
                    // $inventoryItem[0]['inspect_ok'] += $inventoryHikiate - $quantity_allocated;
                    $numUpdate = $inventoryHikiate - $quantity_allocated;
                    $this->db->set("quantity", $inventoryHikiate);
                    $this->db->set("inspect_ok", $inventoryHikiate);
                    $this->db->set("edit_date", $now);
                    $this->db->set("edit_user", $currentEmployeeId);
                    $this->db->where($where_check);
                    $this->db->update('t_store_item');

                    //Update origin inventory
                    $warehouse = $this->komoku_model->getWarehouseWithName($inventoryItem[0]['warehouse'], true);
                    $where_check["warehouse"] = (count($warehouse) > 0 ? $warehouse[0]['kubun'] : $inventoryItem[0]['warehouse']);
                    $this->db->set("quantity", "quantity - $numUpdate", false);
                    $this->db->set("inspect_ok", "inspect_ok - $numUpdate", false);
                    $this->db->set("edit_date", $now);
                    $this->db->set("edit_user", $currentEmployeeId);
                    $this->db->where($where_check);
                    $this->db->update('t_store_item');
                }else{
                    // update old inventory with quantity = quantity - $inventoryHikiate}
                    $warehouse = $this->komoku_model->getWarehouseWithName($inventoryItem[0]['warehouse'], true);
                    $this->db->where("warehouse", (count($warehouse) > 0 ? $warehouse[0]['kubun'] : $inventoryItem[0]['warehouse']));
                    $this->db->where('del_flg', '0');
                    $this->db->where('salesman', $sales_man);
                    $this->db->where('item_code', $data['item_code']);
                    $this->db->where('color', $data['color']);
                    $this->db->where('size', $data['size']);
                    $this->db->where('order_receive_no', $item_order_receive_no);
                    $this->db->where('order_no', $order_no);
                    $this->db->where('partition_no', $item_partition_no);
                    $this->db->set('inspect_ok', "inspect_ok - $inventoryHikiate", FALSE);
                    $this->db->set('quantity', "quantity - $inventoryHikiate", FALSE);

                    $this->db->update('t_store_item', array(
                        'edit_date' => $now,
                        'edit_user' => $currentEmployeeId,
                    ));
                    $inventoryItem[0]['quantity'] = $inventoryHikiate;
                    $inventoryItem[0]['inspect_ok'] = $inventoryHikiate;
                    $this->db->insert('t_store_item', $inventoryItem[0]); 
                } 

                // - hikiate
                $hikiateQuantity -= $inventoryHikiate;
                //
                $inv_no[] = $selectedInventoryItem['inv_no'].'-'.$inventoryHikiate;
            }else{
                // update old inventory with quantity = quantity - $inventoryHikiate}
                $this->db->where('del_flg', '0');
                $this->db->where('salesman', $sales_man);
                $this->db->where('item_code', $data['item_code']);
                $this->db->where('color', $data['color']);
                $this->db->where('size', $data['size']);
                $this->db->where('order_receive_no', $item_order_receive_no);
                $this->db->where('order_no', $order_no);
                $this->db->where('partition_no', $item_partition_no);
                $this->db->where('warehouse', $selectedInventoryItem['warehouse']);
                // $this->db->set('inspect_ok', "inspect_ok - $inventoryHikiate", FALSE);
                // $this->db->set('quantity', "quantity - $inventoryHikiate", FALSE);
                $this->db->set('warehouse', "(select km.kubun from m_komoku km 
                                                join m_komoku km2 on km.komoku_id = km2.komoku_id and km2.kubun = st.warehouse
                                                where km.komoku_name_2 = concat(km2.komoku_name_2, '_Cho xuat'))", FALSE);
                $this->db->set('note', "(select km.komoku_name_2 from m_komoku km 
                                                    join m_komoku km2 on km.komoku_id = km2.komoku_id and km2.kubun = st.warehouse
                                                    where km.komoku_name_2 = concat(km2.komoku_name_2, '_Cho xuat'))", FALSE);
                $this->db->update('t_store_item st', array(
                    'status' => RESERVED_ITEM,
                    'edit_date' => $now,
                    'edit_user' => $currentEmployeeId
                ));
                
                // - hikiate
                $hikiateQuantity -= $inventoryHikiate;
                $inv_no[] = $selectedInventoryItem['inv_no'].'-'.$inventoryHikiate;

                }
            }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $result["success"] = false;
        } else {
            $this->db->trans_commit();
            $result["success"] = true;
        }
        $result["inv_no"] = $inv_no; 
        return $result; 
    }
    private function doUnAllocationData($storeList) {
        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];
        foreach($storeList as $store){
            $storeInfo = $this->store_item_model->checkStoreItemExistToUpdate($store);
            $warehouse = $this->komoku_model->getWarehouseWithName($store['warehouse'], true);
            if(count($warehouse) > 0){
                $warehouse = $warehouse[0]['kubun'];
            }else{
                $warehouse = '001';
            }
            if ($storeInfo !== FALSE) {
                $dataUpdate = $storeInfo;
                $dataUpdate['order_receive_no'] = substr_replace($store['order_receive_no'], 'xx', 0, 2);
                $dataUpdate['partition_no'] = 1;
                $dataUpdate['status'] = '010';
                $dataUpdate['odr_recv_date'] = null;
                $dataUpdate['odr_recv_detail_no'] = null;
                $dataUpdate['note'] = '';
                $dataUpdate['warehouse'] = $warehouse;
                $dataUpdate['edit_date'] = $now;
                $dataUpdate['edit_user'] = $currentEmployeeId;
                $result = $this->store_item_model->update($storeInfo, $dataUpdate);
            }
        }
    }
}
