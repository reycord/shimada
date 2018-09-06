<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Schedule extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('dvt_model');
        $this->load->model('order_received_details_model');
        $this->load->model('order_received_model');
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
        if ($this->is_logged_in()) {
            $this->data['title'] = $this->lang->line('input_schedule');
            $data_table = $this->order_received_model->getScheduledInput();
            $this->data['data_table'] = $data_table;
            $this->data['screen_id'] = 'SKS0010';
            // Load the subview
            $content = $this->load->view('schedule/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    // Created by Khanh
    // Date : 19/04/2018
    public function saveSchedule(){
        // check auth
        if (!$this->is_logged_in()) {
            return;
        }
        $currentUser = $this->session->userdata('user');
        $params = $this->input->post();
        $data = array(
            'order_receive_no'      => $params['order_receive_no'],
            'partition_no'          => $params['partition_no'],
            'order_receive_date'    => $params['order_receive_date'],
            // 'wish_delivery_date'    => $params['wish_delivery_date'],
            'plan_inspect_date'     => $params['plan_inspect_date'],
            'plan_packing_date'     => $params['plan_packing_date'],
            'plan_delivery_date'    => $params['plan_delivery_date'],
            'edit_date'             => $params['edit_date'],
        );

        $check_edit_date = $this->order_received_model->checkEditDate($data);   
        if($check_edit_date){
            if(strtotime($check_edit_date['edit_date']) > strtotime($params['edit_date'])){
                echo json_encode($this->_response(false,'COMMON_E001'));
                return;
            }
        }

        foreach($data as $key => $value){
            if($value == '') {$data[$key] = null;}
        }
        $data['edit_date'] = date('Y/m/d H:i:s');
        $data['edit_user'] =  $currentUser['employee_id'];
        $result = $this->order_received_model->updateSchedule($data);
        
        if($result) {
            echo json_encode($this->_response(true,'save_success', $result));
        } else {
            echo json_encode($this->_response(false,'save_fail'));
        }
    }

    // Created by Khanh
    // Date :19/04/2018
    public function acceptItem(){
        // check auth
        if (!$this->is_logged_in()) {
            return;
        }
        $currentUser = $this->session->userdata('user');
        $params = $this->input->post();
        $check_edit_date = $this->order_received_model->checkEditDate($params);   
        if($check_edit_date){
            if(strtotime($check_edit_date['edit_date']) > strtotime($params['edit_date'])){
                echo json_encode($this->_response(false,'COMMON_E001'));
                return;
            }
        }
        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        $accept = $this->order_received_model->acceptReceivedOrder($params);
        if ($accept == false) {
            echo json_encode($this->_response(false,'PLS0010_E002'));
        } else {
            echo json_encode($this->_response(true,'PLS0010_I004', $accept));
        }
    }

    // confirm order received
    public function confirmOrderReceived(){
        // check auth
        if (!$this->is_logged_in()) {
            return;
        }
        $currentUser = $this->session->userdata('user');
        $permissionList = explode(",", PERMISSION_MANAGER);
        if(!in_array($currentUser['permission_id'] ,$permissionList)){
            echo json_encode($this->_response(false,'COMMON_E002'));
            return;
        }
        $params = $this->input->post();
        $check_edit_date = $this->order_received_model->checkEditDate($params);   
        if($check_edit_date){
            if(strtotime($check_edit_date['edit_date']) > strtotime($params['edit_date'])){
                echo json_encode($this->_response(false,'COMMON_E001'));
                return;
            }
        }
        $params['edit_date'] = date('Y/m/d H:i:s');
        $params['edit_user'] =  $currentUser['employee_id'];
        $accept = $this->order_received_model->confirmReceivedOrder($params);
        if ($accept == true) {
            $data['success'] = true;
            $data['message'] = $this->lang->line('PLS0010_I001');
            echo json_encode($data);
        } else {
            $data['message'] = $this->lang->line('PLS0010_E002');
            echo json_encode($data);
        }
    }
}
