<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('common_helper');
        $this->load->model('orders_model');
        $this->load->model('order_received_details_model');
        $this->load->model('order_received_model');
        $this->load->model('partners_model');
        $this->load->model('employee_model');
        $this->load->model('company_model');
        $this->load->model('komoku_model');
        $this->load->model('store_item_model');
        $this->load->model('print_po_model');
        $this->load->model('print_contract_model');
        $this->load->model('items_model');
        $this->load->model('surcharge_model');
        $this->load->model('company_shipper_model');
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
        $this->data['screen_id'] = 'POD0010';
        $this->data['employee_list'] = $this->employee_model->get_fullname_emp();
        $komokuid = KOMOKU_STATUS;
        $rangeStatus = explode(",", ORDER_STATUS_SEARCH);
        $this->data['status_list'] = $this->komoku_model->getStatusSearch($komokuid, $rangeStatus);
        $currentUser = $this->session->userdata('user');
        $this->data['branchInfo'] = $this->komoku_model->get_all_Branch();
        $this->data['partyList'] = $this->komoku_model->get_all_party();
        // $this->data['bankList'] = $this->komoku_model->get_all_bank_info();
        $this->data['user_login'] = $currentUser['employee_id'];
        
        // Load the subview
        $content = $this->load->view('orders/index.php', $this->data, true);
        // Pass to the master view
        $this->load->view('master_page', array('content' => $content));
    }

    public function search()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $column = $order[0]['column'];
        $order[0]['column'] = $columns[$column]['data'];

        $data = $this->orders_model->get_orders($params, $start, $length, $recordsTotal, $recordsFiltered,$order);
        
        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }

    // show data for search product modal
    public function searchProduct()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $data = [];
        if($params['search_from'] == 1){
            $data = $this->order_received_model->getDataOderReceive($params, $start, $length, $recordsTotal, $recordsFiltered);
        }else{
            $data = $this->items_model->search_for_purchase_order($params, $start, $length, $recordsTotal, $recordsFiltered);
        }
        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }


    // get surcharge info
    public function getSurchrge()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $data = $this->surcharge_model->getSurchargeByItem($params);
        echo json_encode(array('data' => $data));
    }

    // Created by Khanh
    // Date : 05/04/2018
    // Get data show table order detail 
    public function tableDetailOrderOut($order_no_1 = null , $order_no_2 = null, $order_no_3 = null, $order_no_4 = null, $order_no_5 = null, $buyer_kb = null, $order_no_6 = null){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $data = $this->orders_model->getDetailOrders($order_no_1, $order_no_2, $order_no_3, $order_no_4, $order_no_5, $buyer_kb, $order_no_6);
        echo json_encode(array(
            "data" => $data,
        ));

    }

    // Created by Khanh
    // Date : 06/04/2018
    // Get order_detail_no with orderno
    public function getDetailNo(){
        $params = $this->input->post();
        $data = $this->orders_model->getMaxDetailNo($params);
        echo json_encode(array(
            "data" => $data,
        ));
    } 

    // add page 
    public function add()
    {
        if (!$this->is_logged_in()) {
            show_error('', 403);
        }
        $this->data['screen_id'] = 'POD0020';
        $this->data['isAddNew'] = true;
        $this->add_edit($this->inputOrderOut(), 0);
    }

    // edit page 
    public function edit($order_no_1, $order_no_2, $order_no_3, $order_no_4, $order_no_5, $buyer_kb, $order_no_6 = null)
    {
        if (!$this->is_logged_in()) {
            return;
        }
        
        $params = array(
                    'order_no_1' => $order_no_1,
                    'order_no_2' => $order_no_2,
                    'order_no_3' => $order_no_3,
                    'order_no_4' => $order_no_4,
                    'order_no_5' => $order_no_5,
                    'buyer_kb'   => $buyer_kb,
                    'order_no_6' => $order_no_6,
        );
        $this->data['screen_id'] = 'POD0020';
        $get_orders_out = $this->orders_model->get_orders_details($params);
        $kubun = $get_orders_out[0]['status'];
        // $getstatus = $this->komoku_model->getStatusFinish($kubun);
        // if (strpos($getstatus[0]['komoku_name_2'], '完了') !== false) {
        if(in_array($kubun, explode(",", STATUS_FINISH))){
            $get_orders_out[0]['flag_finish'] = '1';
        }else{
            $get_orders_out[0]['flag_finish'] = '0';
        }
        if ($get_orders_out == null) {
            show_404('');
        }
        $this->data['isAddNew'] = false;
        $this->add_edit($get_orders_out, 1);
    }

    /**
     * create order object from input
     *
     * @return array OrderOut
     */
    private function inputOrderOut() {
        $currentUser = $this->session->userdata('user');
        return array (
            0 => array(
                'order_no_1' => $this->input->post('order_no_1'),
                'order_no_2' => $this->input->post('order_no_2'),
                'order_no_3' => $this->input->post('order_no_3'),
                'order_no_4' => $this->input->post('order_no_4'),
                'order_no_5' => $this->input->post('order_no_5'),
                'order_no_6' => $this->input->post('order_no_6'),
                'delivery_address' => $this->input->post('delivery_address'),
                'head_office' => $this->input->post('head_office'),
                'head_office_address' => $this->input->post('head_office_address'),
                'contract_no' => $this->input->post('contract_no'),
                'style' => $this->input->post('style'),
                'apparel' => $this->input->post('apparel'),
                'odr_department' => $this->input->post('odr_department'),
                'manager' => $this->input->post('staff'),
                'tax' => $this->input->post('tax'),
                'buyer_kb' => $this->input->post('buyer_kb'),
                'bank' => $this->input->post('bank'),
                'currency' => $this->input->post('currency'),
                'delivery_date' => $this->input->post('delivery_date'),
                'salesman' => $this->input->post('salesman'),
                'assistance' => $this->input->post('assistance'),
                'note' => $this->input->post('note'),
            )
        );
    }
 
    /**
     * render view when add, edit, save
     *
     * @param [type] $orderReceived
     * @return void
     */
    private function add_edit($orderOut, $flg) {
		$currentUser = $this->session->userdata('user');
        // get all customer code
        $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
        // get all sales man
        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
        // get unit list
        $this->data['unit_list'] = $this->komoku_model->get_all_unit();
        // get size list
        $this->data['size_list'] = $this->komoku_model->get_all_size();
        // get size unit list
        $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
        // get all color list
        $this->data['color_list'] = $this->komoku_model->get_all_color();
        // get tax_classification
        $this->data['taxClassifications'] = $this->komoku_model->get_all_tax();
        // get currencies
        $currency = $this->komoku_model->get_all_currency();
        // get employees
        $orderUserList = $this->employee_model->getAllUser();
        // get supplier
        $supplierList = $this->company_model->getAllSupplier();
        if(count($supplierList) > 0){
            foreach ($supplierList as $key => &$supplier) {
                $supplier['branches_headoff'] = $this->company_model->GetBrandHeadOff($supplier['company_id']);
            };
        }
       
        // get customers
        $item_id = KOMOKU_ITEMTYPE;
        $items_list = $this->komoku_model->getByKomokuid($item_id);
        // warehouse info
        $warehouseList = $this->komoku_model->getWarehouseWithUse("1");
        // komoku po.no
        $po_no_id = KOMOKU_PO_TYPE;
        $poNoList = $this->komoku_model->getByKomokuid($po_no_id);
        // get final customer list 
        $final_customer_id = KOMOKU_END_SALESMAN;
        // $final_customer_list = $this->komoku_model->getByKomokuid($final_customer_id);
        // Get Max Po_no_4
        $orders_no_4 = $this->orders_model->getLastOderNo4();
        // set data for render
        if($flg == 1){
            $this->data['title'] = $this->lang->line('update_purchase_order');
        } else{
			$this->data['title'] = $this->lang->line('add_new_purchase_order');
		}
		$this->data['curUser'] = $currentUser;
        $this->data['orderOut']             = $orderOut;
        $this->data['orderUserList']        = $orderUserList;
        $this->data['supplierList']         = $supplierList;
        // $this->data['final_customer_list']  = $final_customer_list;
        $this->data['currency']             = $currency;
        $this->data['warehouseList']        = $warehouseList;
        $this->data['poNoList']             = $poNoList;
        $this->data['orders_no_4']          = $orders_no_4;
        $this->data['items_list']           = $items_list;
        //Set history delivery date
        if(isset($orderOut[0]["note"])){
            $arr_lines = preg_split("/\r\n|\r|\n/", $orderOut[0]["note"]);
            $arr_note = array();
            for($i=count($arr_lines)-1;$i>=0;$i--){
                $note = $arr_lines[$i];
                if($note!==""){
                    array_push($arr_note, explode(";", $note));
                }
            }
            $this->data['history_list']     = $arr_note;
        }
		
        // render content
        $content = $this->load->view('orders/add.php', $this->data, true);

        // pass to the master view
        $this->load->view('master_page', array('content' => $content));
    }
 
    // Apply orderss
    public function apply()
    {
        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        // Set params
        $params = $this->input->post();
        $params['accept_user'] = null;
        $params['accept_date'] = null;
        $params['denial_user'] = null;
        $params['denial_date'] = null;
        $params['status'] = '004'; //発注入力済

        // check orders exist ?
        $check_orders_exists = $this->orders_model->check_orders_exists($params);
        // if khong co data
        if ($check_orders_exists == false) {
            $data['message'] = $this->lang->line('POD0010_I004');
            echo json_encode($data);
        } else {
            if(strtotime($params['edit_date']) != strtotime($check_orders_exists[0]['edit_date'])){
                $data['message'] = $this->lang->line('COMMON_E001');
                echo json_encode($data);
                return;
            }
            $params['edit_date'] = $currentDate;
            $params['edit_user'] = $currentUser['employee_id'];
            $result = $this->orders_model->updateOrderOut($params);
            // begin update store item
            if($result == true){
                $order_no_6 = (trim($params['order_no_6']) != null && trim($params['order_no_6']) != '') ? ('-'.trim($params['order_no_6'])) : "";
                $order_no_4 = str_pad($params['order_no_4'], 4, '0', STR_PAD_LEFT);
                $order_no = $params['order_no_1'].'-'.$params['order_no_2'].'-'.$params['order_no_3'].'-'.$order_no_4.($params['buyer_kb'] == '2' ? '(HN)' : '').'/'.$params['order_no_5'].$order_no_6;
                $data['status'] = "005"; // 発注申請中
                $data['order_no'] = $order_no;
                $data['edit_date'] = $currentDate;
                $data['edit_user'] = $currentUser['employee_id'];
                $this->store_item_model->updateStatus($data);
            }
            // end update store item
            if ($result == true) {
                $data['success'] = true;
                $data['message'] = $this->lang->line('PLS0010_I004');
                echo json_encode($data);
            } else {
                $data['message'] = $this->lang->line('PLS0010_E002');
                echo json_encode($data);
            }
        }
    }

    // Accept orders
    public function accept()
    {
        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $permissionList = explode(",", PERMISSION_MANAGER);
        if(!in_array($currentUser['permission_id'] ,$permissionList)){
            echo json_encode($this->_response(false,'COMMON_E002'));
            return;
        }
        // Set params
        $params = $this->input->post();
        $params['denial_user'] = null;
        $params['denial_date'] = null;
        $params['status'] = '006'; //発注承認済

        // check orders exist ?
        $check_orders_exists = $this->orders_model->check_orders_exists($params);
        // if khong co data
        if ($check_orders_exists == false) {
            $data['message'] = $this->lang->line('POD0010_I004');
            echo json_encode($data);
        } else {
            if(strtotime($params['edit_date']) != strtotime($check_orders_exists[0]['edit_date'])){
                $data['message'] = $this->lang->line('COMMON_E001');
                echo json_encode($data);
                return;
            }
            $params['edit_date'] = $currentDate;
            $params['edit_user'] = $currentUser['employee_id'];
            $result = $this->orders_model->updateOrderOut($params);
            if(trim($params['order_no_6']) != null && trim($params['order_no_6']) != ""){
                $order_no_6 = '-'.$params['order_no_6'] ;
            }else{
                $order_no_6 = "";
            }
            $order_no_4 = str_pad($params['order_no_4'], 4, '0', STR_PAD_LEFT);
            $data['order_no'] = $params['order_no_1'].'-'.$params['order_no_2'].'-'.$params['order_no_3'].'-'.$order_no_4.($params['buyer_kb'] == '2' ? '(HN)' : '').'/'.$params['order_no_5'].$order_no_6;
            $data['edit_user'] = $currentUser['employee_id'];
            $data['edit_date'] = $currentDate;
            $update_status = $this->store_item_model->updateStatusAccept($data);
            if ($result == true) {
                $data['success'] = true;
                $data['message'] = $this->lang->line('PLS0010_I001');
                echo json_encode($data);
            } else {
                $data['message'] = $this->lang->line('PLS0010_E002');
                echo json_encode($data);
            }
        }
    }

    // Denial orders
    public function denial()
    {
        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $permissionList = explode(",", PERMISSION_MANAGER);
        if(!in_array($currentUser['permission_id'] ,$permissionList)){
            echo json_encode($this->_response(false,'COMMON_E002'));
            return;
        }
        // Set params
        $params = $this->input->post();
        $params['accept_user'] = null;
        $params['accept_date'] = null;
        $params['apply_user'] = null;
        $params['apply_date'] = null;
        $params['status'] = '007';  //発注差し戻し

        // check orders exist ?
        $check_orders_exists = $this->orders_model->check_orders_exists($params);

        // if khong co data
        if ($check_orders_exists == false) {
            $data['message'] = $this->lang->line('POD0010_I004');
            echo json_encode($data);
        } else {
            if(strtotime($params['edit_date']) != strtotime($check_orders_exists[0]['edit_date'])){
                $data['message'] = $this->lang->line('COMMON_E001');
                echo json_encode($data);
                return;
            }
            $params['edit_date'] = $currentDate;
            $params['edit_user'] = $currentUser['employee_id'];
            $result = $this->orders_model->updateOrderOut($params);

            // begin update store item
            if($result == true){
                $order_no_6 = (trim($params['order_no_6']) != null && trim($params['order_no_6']) != '') ? ('-'.trim($params['order_no_6'])) : "";
                $order_no_4 = str_pad($params['order_no_4'], 4, '0', STR_PAD_LEFT);
                $order_no = $params['order_no_1'].'-'.$params['order_no_2'].'-'.$params['order_no_3'].'-'.$order_no_4.($params['buyer_kb'] == '2' ? '(HN)' : '').'/'.$params['order_no_5'].$order_no_6;
                $data['status'] = "019";
                $data['order_no'] = $order_no;
                $data['edit_date'] = $currentDate;
                $data['edit_user'] = $currentUser['employee_id'];
                $this->store_item_model->updateStatus($data);
            }
            // end update store item

            if ($result == true) {
                $data['success'] = true;
                $data['message'] = $this->lang->line('PLS0010_I003');
                echo json_encode($data);
            } else {
                $data['message'] = $this->lang->line('PLS0010_E002');
                echo json_encode($data);
            }
        }
    }

    // Po sheet date orders
    public function posh(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");

        // Set params
        $params = $this->input->post();
        $params['edit_date'] = $currentDate;
        $params['edit_user'] = $currentUser['employee_id'];

        // check orders exist ?
        $check_orders_exists = $this->orders_model->check_orders_exists($params);

        // if khong co data
        if ($check_orders_exists == false) {
            $data['message'] = $this->lang->line('POD0010_I004');
            echo json_encode($data);
        } else {
            $result = $this->orders_model->updateOrderOut($params);
            if ($result == true) {
                $data['success'] = true;
                $data['message'] = "PO sheet Successfully!";
                echo json_encode($data);
            } else {
                $data['message'] = "PO sheet Failed!";
                echo json_encode($data);
            }
        }
    }

    // Delete orders
    public function delete()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        // check orders exist ?
        $check_orders_exists = $this->orders_model->check_orders_exists($params);

        // if khong co data
        if ($check_orders_exists == false) {
            $data['message'] = $this->lang->line('POD0010_I004');
            echo json_encode($data);
        } else {
            // $this->db->trans_begin();
            $deleteOrder = $this->orders_model->deleteOrders($params);
            $deleteOrderDetail = $this->orders_model->updateFlgOrdersDetail($params);
            $deleteStoreItem = $this->store_item_model->updateFlgStoreItem($params);
        
            if ($deleteOrder && $deleteOrderDetail) {
                // $this->db->trans_commit();
                $data['success'] = true;
                $data['message'] = $this->lang->line('del_success');
                echo json_encode($data);
            } else {
                // $this->db->trans_rollback();
                $data['message'] = $this->lang->line('del_fail');;
                echo json_encode($data);
            }
        }
    }

    // Po sheet date orders
    public function getPONo(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $order_no_4 = $this->orders_model->getLastOderNo4($params['buyer_kb']);
        $order_no_4 =str_pad(($order_no_4 + 1 ), 4, '0', STR_PAD_LEFT);
        $data['order_no_4'] = $order_no_4;
        echo json_encode($data);
    }
    public function view()
    {
        if ($this->is_logged_in()) {
            // Load the subview
            $content = $this->load->view('orders/view.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    // handle data show to view
    public function add_details()
    {
        if ($this->is_logged_in()) {
            // Load the subview
            $content = $this->load->view('orders/add_details.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    // Handle save data 
    public function addOrders()
    {
        // check auth
        if (!$this->is_logged_in()) {
            return;
        }
        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");
        $this->form_validation->set_rules('order_user', $this->lang->line('order_user'), 'required');
        $this->form_validation->set_rules('currency', $this->lang->line('currency'), 'required');
        $this->form_validation->set_rules('order_date', 'Order Date', 'required');
        $this->form_validation->set_rules('supplier_name', $this->lang->line('supplier_name'), 'required');
        $this->form_validation->set_error_delimiters('<p style="color:#d42a38">', '</p>');
        $insert_update = $this->input->post('insert_update');
        $data_table = json_decode($this->input->post('data_table'));
        $order_no_1 = $this->input->post('po_no1');
        $order_no_2 = $this->input->post('po_no2');
        $order_no_3 = $this->input->post('po_no3');
        $order_no_4 = $this->input->post('po_no4');
        $order_no_5 = $this->input->post('po_no5');
        $buyer_kb = $this->input->post('buyer_kb');
        $order_no_6 = trim($this->input->post('po_no6'));
        // check validate result
        if ($this->form_validation->run() === true) {
            // set data for insert
            $orders_out = array(
                'order_no_1'                     => $order_no_1,
                'order_no_2'                     => $order_no_2,
                'order_no_3'                     => $order_no_3,
                'order_no_4'                     => $order_no_4,
                'order_no_5'                     => $order_no_5,
                'order_no_6'                     => $order_no_6 ? $order_no_6 : "",
                'order_date'                     => $this->input->post('order_date'),
                'revise_date'                    => $this->input->post('revise_date'),
                'reference'                      => $this->input->post('reference'),
                'order_user'                     => $this->input->post('order_user'),
                'quantity'                       => str_replace(',','',$this->input->post('po_quant')),
                'currency'                       => $this->input->post('currency'),
                'contract_no'                    => $this->input->post('cont_no'),
                'tax'                            => $this->input->post('tax'),
                'buyer_kb'                       => $this->input->post('buyer_kb'),
                'customs_clearance_sheet_no'     => $this->input->post('customs_clearance_sheet_no'),
                'customs_clearance_fee'          => $this->input->post('customs_clearance_fee'),
                'transport_fee'                  => $this->input->post('transport_fee'),
                'amount'                         => str_replace(',','',$this->input->post('amount')),
                'invoice_no'                     => $this->input->post('inv_no'),
                'delivery_date'                  => $this->input->post('delivery_date'),
                'supplier_name'                  => $this->input->post('supplier_name'),
                'delivery_company'               => $this->input->post('delivery_company'),
                'address'                        => $this->input->post('address'),
                'shipping_mark'                  => $this->input->post('shipping_mark'),
                'status'                         => '003',
            );
            
            // data for order detail table
            $data_row = array();
            foreach ($data_table as $item) {
                // $warehouse = ($item->warehouse) != null  
                $temp = array(
                    'order_no_1'                     => $order_no_1,
                    'order_no_2'                     => $order_no_2,
                    'order_no_3'                     => $order_no_3,
                    'order_no_4'                     => $order_no_4,
                    'order_no_5'                     => $order_no_5,
                    'order_no_6'                     => $order_no_6 ? $order_no_6 : "",
                    'buyer_kb'                       => $this->input->post('buyer_kb'),
                    'order_detail_no'                => $item->order_detail_no,
                    'order_date'                     => $this->input->post('order_date'),
                    'item_code'                      => $item->item_code,
                    'item_name'                      => $item->item_name,
                    'size'                           => ($item->size != null ? $item->size : ''),
                    'color'                          => ($item->color != null ? $item->color : ''),
                    'odr_quantity'                   => $item->odr_quantity,
                    'unit'                           => $item->unit,
                    'price'                          => $item->price,
                    'amount'                         => str_replace(',','',$item->amount),
                    'warehouse'                      => $item->warehouse,
                    'item_type'                      => $item->item_type,
                    'note'                           => $item->note,
                    'salesman'                       => $item->salesman,
                    'vendor'                         => $item->vendor,
                    'odr_recv_no'                    => $item->odr_recv_no,
                    'surcharge_po'                   => $item->surcharge_po,
                    'odr_recv_detail_no'             => $item->odr_recv_detail_no,
                    'partition_no'                   => $item->partition_no,
                    'odr_recv_date'                  => $item->odr_recv_date,
                    'composition_1'                  => $item->composition_1,
                    'composition_2'                  => $item->composition_2,
                    'composition_3'                  => $item->composition_3,
                    'size_unit'                      => $item->size_unit
                );
                array_push($data_row, $temp);
            }
            // data for store item table
            $store_row = array();
            $order_no_6 = ($order_no_6 != null) ? ('-'.$order_no_6) : "";
            $order_no = $order_no_1.'-'.$order_no_2.'-'.$order_no_3.'-'.$order_no_4.($buyer_kb == '2' ? '(HN)' : '').'/'.$order_no_5.$order_no_6;
            
            foreach ($data_table as $item) {
                $temp = array(
                    'salesman'                       => trim($item->salesman),
                    'item_code'                      => $item->item_code,
                    'item_type'                      => $item->item_type,
                    'order_no'                       => $order_no,
                    'order_detail_no'                => $item->order_detail_no,
                    'invoice_no'                     => $this->input->post('inv_no'),
                    'warehouse'                      => $item->warehouse,
                    'item_name'                      => $item->item_name,
                    'size'                           => ($item->size != null ? $item->size : ''),
                    'color'                          => ($item->color != null ? $item->color : ''),
                    'quantity'                       => $item->odr_quantity,
                    'buy_price'                      => $item->price,
                    'buy_amount'                     => $item->amount,
                    'unit'                           => $item->unit,
                    // 'note'                           => $item->note,
                    'create_date'                    => $currentDate,
                    'create_user'                    => $currentUser['employee_id'],
                    'status'                         => '019',
                    'order_receive_no'               => substr_replace($item->odr_recv_no, 'xx', 0, 2),
                    'partition_no'                   => 1,
                );
                array_push($store_row, $temp);
            }
            
            // // insert or update
            if ($insert_update == 0) {
                $orders_out['create_date'] = $currentDate;
                $orders_out['create_user'] = $currentUser['employee_id'];
                // History change delivery date
                $orders_out['note'] = $currentUser['employee_id'].";".$orders_out['delivery_date'].";".$currentDate."\r\n";
                foreach ($orders_out as $key => $value) {
                    if ($value == null || $value== '') {unset($orders_out[$key]);}
                }
                foreach ($data_row as $dt_row) {
                    foreach ($dt_row as $key => $value) {
                        if($key == 'size' || $key == 'color' || $key == 'odr_recv_no'){
                            continue;
                        }
                        if ($value == '') {$dt_row[$key] = null;}
                    }
                }
                foreach ($store_row as $st_row) {
                    foreach ($st_row as $key => $value) {
                        if($key == 'size' || $key == 'color' || $key == 'order_receive_no'){
                            continue;
                        }
                        if ($value == '') {$st_row[$key] = null;}
                    }
                }
                $check_orders = $this->orders_model->check_orders_exists($orders_out);
                if($check_orders != false){
                    $this->session->set_flashdata('error_msg', $this->lang->line('POD0020_E004'));
                    $this->add();
                    return;
                }
                $insert_update_order = $this->orders_model->insertOrderOut($orders_out);

                // insert to order details
                foreach($data_row as &$row){
                    $row['create_date'] = $currentDate;
                    $row['create_user'] = $currentUser['employee_id'];
                    $insert_order_detail = $this->orders_model->insertOrderDetail($row);
                    if(!$insert_order_detail){
                        return false;
                    }
                }   
                // insert to store item
                foreach($store_row as $row){
                    $insert_store_item = $this->store_item_model->insertStoreItem($row);
                }
                if ($insert_update_order && $insert_order_detail && $insert_store_item) {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
                }else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
                }
                redirect(base_url() . 'orders?search=1&order_no='.$order_no);
            } 
            else if ($insert_update == 1) {
                // When update not update status
                unset($orders_out['status']);
                $edit_date_old = $this->input->post('edit_date');
                $delivery_date_old = $this->input->post('delivery_date_old');
                if($delivery_date_old !== $orders_out["delivery_date"]){
                    $orders_out['note'] = $currentUser['employee_id'].";".$orders_out['delivery_date'].";".$currentDate."\r\n";
                }
                $check_edit_date = $this->orders_model->check_orders_exists($orders_out);
                if(!$check_edit_date){
                    $this->session->set_flashdata('error_msg', $this->lang->line('JOS0010_E002'));
                    $this->edit($order_no_1 ,$order_no_2 ,$order_no_3, $order_no_4, $order_no_5, $buyer_kb);
                    return;
                }else{
                    if(strtotime($check_edit_date[0]['edit_date']) != strtotime($edit_date_old )){
                        $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
                        $this->edit($order_no_1 ,$order_no_2 ,$order_no_3, $order_no_4, $order_no_5, $buyer_kb);
                        return;
                    }
                }

                $orders_out['edit_user'] = $currentUser['employee_id'];
                $orders_out['edit_date'] = $currentDate;
                foreach ($orders_out as $key => $value) {
                    if ($value == '') {$orders_out[$key] = null;}
                }
                foreach ($data_row as $dt_row) {
                    foreach ($dt_row as $key => $value) {
                        if($key == 'size' || $key == 'color' || $key == 'odr_recv_no'){
                            continue;
                        }
                        if ($value == '') {$dt_row[$key] = null;}
                    }
                }
                foreach ($store_row as $st_row) {
                    foreach ($st_row as $key => $value) {
                        if($key == 'size' || $key == 'color' || $key == 'order_receive_no'){
                            continue;
                        }
                        if ($value == '') {$st_row[$key] = null;}
                    }
                }
                $insert_update_order = $this->orders_model->updateOrderOut($orders_out);
                $insert_order_detail = $this->orders_model->updateOrderDetail($orders_out, $data_row);
                $insert_store_item = $this->store_item_model->updateStoreItems($store_row);
                if ($insert_update_order && $insert_order_detail && $insert_store_item) {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
                }else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
                }
                redirect(base_url() . 'orders?search=1&order_no='.$order_no);
            }
        } 
        else {
            if ($insert_update == 0) {
                $this->add();
            } else if ($insert_update == 1) {
                $this->edit($order_no_1 ,$order_no_2 ,$order_no_3, $order_no_4, $order_no_5, $buyer_kb);
            }
        }
    }
    public function saveItem(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");
        $params = $this->input->post();
        $params['item_code'] = strtoupper($params['item_code']);
        $params['jp_code'] = strtoupper($params['jp_code']);
        $params['customer_code'] = "";
        if( $params['buy_price_usd'] == ""){
            unset($params['buy_price_usd']);
        }
        if( $params['sell_price_usd'] == ""){
            unset($params['sell_price_usd']);
        }
        if( $params['base_price_usd'] == ""){
            unset($params['base_price_usd']);
        }
        $result = $this->items_model->check_product_exists($params);
        if($result) {
            echo json_encode($this->_response(false, 'product_exist'));
        } else {
            $params['create_user'] = $currentUser['employee_id'];
            $params['create_date'] = $currentDate;
            $resultInsert = $this->items_model->insertItems($params);
            if($resultInsert == FALSE) {
                echo json_encode($this->_response(false, 'save_fail'));
            }else{
                echo json_encode($this->_response(true, 'save_success', $resultInsert[0]));
            }
        }
    }
    public function getPOPrintInfo(){
        $params = $this->input->post();
        $poNo = $params['order_no_1'].'-'.$params['order_no_2'].'-'.$params['order_no_3'].'-'.$params['order_no_4'].($params['buyer_kb'] == '2' ? '(HN)' : '').'/'.$params['order_no_5'];
        if($params['order_no_6'] != null && $params['order_no_6'] != ''){
            $poNo .= ('-'.$params['order_no_6']);
        }
        $data = array(
            'po_no' => $poNo,
            'order_date' => $params['order_date'],
            'contractNo' => $params['order_date'].$params['order_no_1'].$params['order_no_2'].$params['order_no_3'].$params['order_no_4'].$params['order_no_5'],
        );
        // get history info
        $poPrintInfo = $this->print_po_model->getPOPrint($data);
        // get shipper list
        $supplierInfo = $this->orders_model->getSupplierInfoByPO($params);
        $shipperList = array();
        if($supplierInfo){
            $shipperList = $this->company_shipper_model->getShipperList($supplierInfo['company_id']);
        }

        $supplierInfo['shippers'] = $shipperList;

        // Get PV info
        $poInfo = $this->orders_model->checkOrderDetailsExist($params);
        $pv_info = '';
        $index = 0;
        foreach($poInfo as $idx => $po){
            if($po['odr_recv_no'] && trim($po['odr_recv_no']) != ''){
                if($index == 0){
                    $index = 1;
                    $pv_info .= trim($po['odr_recv_no']);
                    continue;
                }
                $pv_info .= ','.(trim($po['odr_recv_no']));
            }
        }
        $pv_info = explode(",", $pv_info);
        if(sizeof($pv_info) != 0) {
            $pv_info = array_unique($pv_info);
        } else {
            $pv_info = '';
        }
        $pv_info = implode(",", $pv_info);
        echo json_encode(array('data' => $poPrintInfo, 'pv_info' => $pv_info, 'supplier' => $supplierInfo));
    }

    public function getContractPrintInfo(){
        $params = $this->input->post();
        $poNo = $params['order_no_1'].'-'.$params['order_no_2'].'-'.$params['order_no_3'].'-'.$params['order_no_4'].($params['buyer_kb'] == '2' ? '(HN)' : '').'/'.$params['order_no_5'];
        if($params['order_no_6'] != null && $params['order_no_6'] != ''){
            $poNo .= ('-'.$params['order_no_6']);
        }
        $data = array(
            'po_no' => $poNo,
            'order_date' => $params['order_date'],
            'contractNo' => $params['order_date'].$params['order_no_1'].$params['order_no_2'].$params['order_no_3'].$params['order_no_4'].$params['order_no_5'].$params['buyer_kb'],
        );
        $contractPrintInfo = $this->print_contract_model->getPOPrint($data);
        // get Signature
        $SignatureInfo = $this->print_po_model->getSignatureInfoByPO($params);
        $contractPrintInfo = array_merge($contractPrintInfo, $SignatureInfo);
        echo json_encode(array('data' => $contractPrintInfo));
    }

    public function exportpdf()
    {
        $params = $this->input->post();
        $poNo = $params['po_no_hidden'];
        $poNo = explode(";",$poNo);
        $orderNo1 = isset($poNo[0]) ? $poNo[0] : '';
        $orderNo2 =  isset($poNo[1]) ? $poNo[1] : '';
        $orderNo3 =  isset($poNo[2]) ? $poNo[2] : '';
        $orderNo4 =  isset($poNo[3]) ? $poNo[3] : '';
        $orderNo5 =  isset($poNo[4]) ? $poNo[4] : '';
        $buyerKb =  isset($poNo[5]) ? $poNo[5] : '';
        $orderNo6 =  isset($poNo[6]) ? $poNo[6] : '';
        $orderDate =  isset($poNo[7]) ? $poNo[7] : null;
        $freight = $params['freight'];
        $insurance = $params['insurance'];
        $branch = $params['header_name'];
        $paymentTerm = $params['payment_term'];
        $pv_no = $params['pv_no'];
        $transportation = $params['transportation'];
        $shipper =  isset($params['shipper']) ? $params['shipper'] : '' ;
        $params['shipper'] = $shipper;
        $note = isset($params['note']) ? '1' : '0';
        $params['note'] =  $note;
        $note_detail = isset($params['note_detail']) ? $params['note_detail'] : '';
        $showHtml = false; // set true when coding
        if ($this->is_logged_in()) {
            // $params = array();
            $params['order_no_1'] = $orderNo1;
            $params['order_no_2'] = $orderNo2;
            $params['order_no_3'] = $orderNo3;
            $params['order_no_4'] = $orderNo4;
            $params['order_no_5'] = $orderNo5;
            $params['order_date'] = $orderDate;
            $params['buyer_kb']   = $buyerKb;

            $orderInfo = $this->orders_model->getOrderInfo($params);
            $orderList = array();
            foreach($orderInfo as $index => $order){
                $existColorFlg = false;
                $existFlg = false;
                $sumOrder = 0;
                $sumAmount = 0;
                if($index == 0){
                    array_push($orderList, array('item_code'=> $order['item_code'], 'color'=> $order['color'], 'color_quantity'=> $order['odr_quantity'], 'sum_quantity'=> $order['odr_quantity'], 'sum_amount' => ($order['odr_quantity'] * $order['price'])));
                    continue;
                }
                foreach($orderList as $idx => $ord){
                    if($ord['item_code'] == $order['item_code']){
                        $orderList[$idx]['sum_quantity'] += $order['odr_quantity'];
                        $orderList[$idx]['sum_amount'] += ($order['odr_quantity'] * $order['price']);
                        if($ord['color'] == $order['color'] ){
                            $orderList[$idx]['color_quantity'] += $order['odr_quantity'];
                            $existColorFlg = true;
                        }
                        $sumOrder = $orderList[$idx]['sum_quantity'];
                        $sumAmount = $orderList[$idx]['sum_amount'];
                        $existFlg = true;
                    }
                }
                if (!$existFlg){
                    array_push($orderList, array('item_code'=> $order['item_code'], 'color'=> $order['color'], 'color_quantity'=> $order['odr_quantity'], 'sum_quantity'=> $order['odr_quantity'], 'sum_amount' => ($order['odr_quantity'] * $order['price'])));
                } else if(!$existColorFlg){
                    array_push($orderList, array('item_code'=> $order['item_code'], 'color'=> $order['color'], 'color_quantity'=> $order['odr_quantity'], 'sum_quantity'=> $sumOrder, 'sum_amount' => $sumAmount));
                }
            }
            foreach($orderInfo as $index => $order){
                foreach($orderList as $idx => $ord){
                    if($ord['item_code'] == $order['item_code'] && $ord['color'] == $order['color']){
                        $orderInfo[$index]['color_quantity'] = $ord['color_quantity'];
                        $orderInfo[$index]['sum_quantity'] = $ord['sum_quantity'];
                        $orderInfo[$index]['sum_amount'] = $ord['sum_amount'];
                    }
                }
                $surchargeInfo = $this->surcharge_model->getSurchargeInfo($orderInfo[$index]);
                if(isset($surchargeInfo) && sizeof($surchargeInfo) > 0){
                    foreach($surchargeInfo as &$surcharge){
                        $flg = false;
                        foreach(json_decode($surcharge['surcharge_unit_color']) as $surcharge_unit_color){
                            if($surcharge_unit_color != null && $surcharge_unit_color != ''){
                                $surcharge['surcharge_unit_color'] = $surcharge_unit_color;
                                $flg = true;
                                break;
                            }
                        }
                        if(!$flg){
                            $surcharge['surcharge_unit_color'] = null;
                        }
                        $flg = false;
                        foreach(json_decode($surcharge['surcharge_color']) as $surcharge_color){
                            if($surcharge_color != null && $surcharge_color != ''){
                                $surcharge['surcharge_color'] = $surcharge_color;
                                $flg = true;
                                break;
                            }
                        }
                        if(!$flg){
                            $surcharge['surcharge_color'] = null;
                        }
                        $flg = false;
                        foreach(json_decode($surcharge['default_surcharge_po']) as $default_surcharge_po){
                            if($default_surcharge_po != null && $default_surcharge_po != ''){
                                $surcharge['default_surcharge_po'] = $default_surcharge_po;
                                $flg = true;
                                break;
                            }
                        }
                        if(!$flg){
                            $surcharge['default_surcharge_po'] = null;
                        }
                    }
                    $orderInfo[$index] = array_merge($orderInfo[$index], $surchargeInfo[0]);
                }
            }
            $branchInfo = $this->komoku_model->getKomokuByID(KOMOKU_PARTY, $branch);
            $params['branch'] = $branchInfo;
            $params['branch_code'] = $branch;
            $this->data['orderInfo'] = $orderInfo;
            $this->data['params'] = $params;
            $this->load->helper(array('dompdf', 'file'));
             // get current login user 
            $currentUser = $this->session->userdata('user'); 
            // get current date
            $currentDate = date("Y/m/d H:i:s");
            $poNo = $orderNo1.'-'.$orderNo2.'-'.$orderNo3.'-'.$orderNo4.($buyerKb == '2' ? '(HN)' : '').'/'.$orderNo5;
            if($orderNo6 != null && $orderNo6 != '' && $orderNo6 != 'null'){
                $poNo .= ('-'.$orderNo6);
            }
            $data = array(
                'po_no' => $poNo,
                'order_date' => $orderDate,
                'times' => 1,
                'print_date' => $currentDate ,
                'print_user' => $currentUser['employee_id'],
                'header' => $branch,
                'insurance' => $insurance,
                'freight' => $freight,
                'payment_term' => $paymentTerm,
                'pv_no' => $pv_no,
                'transportation' => $transportation,
                'shipper' => $shipper,
                'note' => $note,
                'note_detail' => $note_detail,
                'create_user' =>  $currentUser['employee_id'],
                'edit_user' =>  $currentUser['employee_id'],
                'create_date' => $currentDate,
                'edit_date' => $currentDate,
            );
            $this->print_po_model->savePrint($data);
            if ($showHtml) {
                $this->load->view('orders/posheet_export', $this->data);
            } else {
                $html = $this->load->view('orders/posheet_export', $this->data, true);
                pdf_create($html, 'purchase_orders');
            }
        }
    }
    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $currentUser = $this->session->userdata('user'); 
        $currentDate = date("Y/m/d H:i:s");
        $params = $this->input->post();
        $poInfo = explode(",", $params['po_no']);
        $data = array(
            'order_date' => isset($poInfo[0]) ? $poInfo[0] : '',
            'order_no_1' => isset($poInfo[1]) ? $poInfo[1] : '',
            'order_no_2' => isset($poInfo[2]) ? $poInfo[2] : '',
            'order_no_3' => isset($poInfo[3]) ? $poInfo[3] : '',
            'order_no_4' => isset($poInfo[4]) ? $poInfo[4] : '',
            'order_no_5' => isset($poInfo[5]) ? $poInfo[5] : '',
            'buyer_kb'   => isset($poInfo[6]) ? $poInfo[6] : '',
        );
        $orderInfo = $this->orders_model->getOrderToPrint($data);
        if($orderInfo != null && sizeof($orderInfo ) > 0){
            $params = array_merge($params, $orderInfo);
        }

        $companyInfo = $this->komoku_model->getKomokuByID(KOMOKU_PARTY, $params['contract_header_name']);
        $company = array();
        if(isset($companyInfo['komoku_name_3'])){
            $company = preg_split("/\r\n|\r|\n/", $companyInfo['komoku_name_3']);
        }
        $params['company'] = $company;

        // $paymentInfo = $this->komoku_model->getKomokuByID(KOMOKU_PAYMENT, $params['contract_payment_term']);
        $payment = array();
        if(isset($params['contract_payment_term'])){
            $payment = explode(";", $params['contract_payment_term']);
        }
        $params['payment_vn'] = isset($payment[1]) ? $payment[1] : '';
        $params['payment_en'] = isset($payment[0]) ? $payment[0] : '';
        
        $bankInfo = $this->komoku_model->getKomokuByID(KOMOKU_BANK, $params['contract_bank']);
        $bank = array();
        if(isset($bankInfo['komoku_name_3'])){
            $bank = preg_split("/\r\n|\r|\n/", $bankInfo['komoku_name_3']);
        }
        $params['bank'] = $bank;
        $contractNo = '';
        foreach($poInfo as $po){
            $contractNo .= $po;
        }
        $data = array(
            'contract_no' => $contractNo,
            'delivery_no' => '',
            'times' => 0,
            'pack_no' => 0,
            'delivery_date' => $currentDate,
            'kubun' => '1001',
            'contract_date' => $params['contract_date'] ,
            'end_date' => $params['contract_expire_date'],
            'bank' => $params['contract_bank'],
            'party_a' => $params['contract_header_name'],
            'payment_term' => $params['contract_payment_term'],
            'reference' => $params['reference'],
            'create_user' =>  $currentUser['employee_id'],
            'edit_user' =>  $currentUser['employee_id'],
            'create_date' => $currentDate,
            'edit_date' => $currentDate,
        );
        $this->print_contract_model->savePrint($data);
        $this->writeDataToExcel('views/orders/contract_template.xlsx', $params);
    }
    private function writeDataToExcel($filePath, $params){
        ob_clean();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setCellValue('F4', (isset($params['reference']) ? ': '.$params['reference'] : ''));
        $spreadsheet->getActiveSheet()->setCellValue('F5', ': '.date_format(date_create($params['contract_date']), 'M/d/Y'));
        $idx = 7;
        foreach($params['company'] as $company){
            $parts = preg_split('/\t/', $company);
            $spreadsheet->getActiveSheet()->setCellValue('A'.$idx,  isset($parts[0]) ? $parts[0] : '');
            if(isset($parts[1])){
                $spreadsheet->getActiveSheet()->setCellValue('B'.$idx,  $parts[1]);
            }
            $idx++;
        }
        $cellValue = $spreadsheet->getActiveSheet()->getCell('A19')->getValue();
        $spreadsheet->getActiveSheet()->setCellValue('A19', $cellValue.$params['supplier_name']);
        $cellValue = $spreadsheet->getActiveSheet()->getCell('A20')->getValue();
        $spreadsheet->getActiveSheet()->setCellValue('A20', $cellValue.$params['supplier_name']);
        $spreadsheet->getActiveSheet()->setCellValue('B21', ': '.$params['head_office_address']);
        $spreadsheet->getActiveSheet()->setCellValue('B22', ': '.$params['head_office_address']);
        $spreadsheet->getActiveSheet()->setCellValue('B23', ': '.$params['head_office_tel']);
        $spreadsheet->getActiveSheet()->setCellValue('B24', ': '.$params['head_office_fax']);
        $spreadsheet->getActiveSheet()->setCellValue('B25', '');
        $spreadsheet->getActiveSheet()->setCellValue('B26', '');
        $spreadsheet->getActiveSheet()->setCellValue('A110', "- ".$params['payment_en']);
        $spreadsheet->getActiveSheet()->setCellValue('A111', "- ".$params['payment_vn']);
        $cellValue = $spreadsheet->getActiveSheet()->getCell('A112')->getValue();
        $index = 113;
        // $spreadsheet->getActiveSheet()->setCellValue('A'.$index, $params['contract_bank_address']);
        foreach(explode("\n", $params['contract_bank_address']) as $info){
            $spreadsheet->getActiveSheet()->setCellValue('A'.$index, $info);
            $index++;
        }
        $cellValue = $spreadsheet->getActiveSheet()->getCell('A157')->getValue();
        $spreadsheet->getActiveSheet()->setCellValue('A157', $cellValue.(isset($params['contract_expire_date']) ? date_format(date_create($params['contract_expire_date']), 'M/d/Y') : ''));
        $cellValue = $spreadsheet->getActiveSheet()->getCell('A158')->getValue();
        $spreadsheet->getActiveSheet()->setCellValue('A158', $cellValue.(isset($params['contract_expire_date']) ? date_format(date_create($params['contract_expire_date']), 'd/m/Y') : ''));
        $spreadsheet->getActiveSheet()->setCellValue('F162', (isset($params['supplier_name']) ? $params['supplier_name'] : ''));
        $sheet = $spreadsheet->getActiveSheet(); 
        $highestRow = 118;
        $numEmptyRow = 0;
        $deleteRowList = array();
        for ($row = 6; $row <= $highestRow; $row++){ 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . 'F' . $row, NULL, TRUE, FALSE);
            if($this->isEmptyRow(reset($rowData))) {
                $numEmptyRow ++;
            } else{
                $numEmptyRow = 0;
            }
            if($numEmptyRow >= 2){
                array_push($deleteRowList, $row);
            }
        }
        for( $i = sizeof($deleteRowList) - 1; $i >= 0;  $i--){
            $spreadsheet->getActiveSheet()->removeRow($deleteRowList[$i]);
        }
        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="principle_contract.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    // PO export to excel
    public function po_excel(){
        $params = $this->input->post();
        $currentMonth = date("M-Y");
        $poList = json_decode($params['export_data']);
        $dataExport = $this->orders_model->getDataToExport($poList);
        $result = array();
        foreach($dataExport as $dt){
            $idx = array_search($dt['item_code'], array_column($result, 'item_code'));
            if($idx !== FALSE){
                if( $dt['currency'] == 'USD'){
                    $result[$idx]['amount_usd'] += $dt['amount'];
                } else if ( $dt['currency'] == 'VND') {
                    $result[$idx]['amount_vnd'] += $dt['amount'];
                }    
                $result[$idx]['order_no'] = is_array($result[$idx]['order_no']) ? 
                                            (in_array($dt['order_no'], $result[$idx]['order_no']) ? $result[$idx]['order_no'] : array_merge($result[$idx]['order_no'], array($dt['order_no']))) : 
                                            ($result[$idx]['order_no'] == $dt['order_no'] ? $result[$idx]['order_no'] : array($result[$idx]['order_no'], $dt['order_no']));
                $result[$idx]['odr_recv_no'] = is_array($result[$idx]['odr_recv_no']) ? 
                                               (in_array($dt['odr_recv_no'], $result[$idx]['odr_recv_no']) ? $result[$idx]['odr_recv_no'] : array_merge($result[$idx]['odr_recv_no'], array($dt['odr_recv_no']))) : 
                                               ($result[$idx]['odr_recv_no'] == $dt['odr_recv_no'] ? $result[$idx]['odr_recv_no'] : array($result[$idx]['odr_recv_no'], $dt['odr_recv_no']));
                $result[$idx]['salesman'] =  is_array($result[$idx]['salesman']) ? 
                                             (in_array($dt['salesman'], $result[$idx]['salesman']) ? $result[$idx]['salesman'] : array_merge($result[$idx]['salesman'], array($dt['salesman']))) : 
                                             ($result[$idx]['salesman'] == $dt['salesman'] ? $result[$idx]['salesman'] : array($result[$idx]['salesman'], $dt['salesman']));
                $result[$idx]['buyer_kb'] =  is_array($result[$idx]['buyer_kb']) ? 
                                             (in_array($dt['buyer_kb'], $result[$idx]['buyer_kb']) ? $result[$idx]['buyer_kb'] : array_merge($result[$idx]['buyer_kb'], array($dt['buyer_kb']))) : 
                                             ($result[$idx]['buyer_kb'] == $dt['buyer_kb'] ? $result[$idx]['buyer_kb'] : array($result[$idx]['buyer_kb'], $dt['buyer_kb']));
            } else {
                if( $dt['currency'] == 'USD'){
                    $dt['amount_usd'] = $dt['amount'];
                    $dt['amount_vnd'] = 0;
                } else if ( $dt['currency'] == 'VND') {
                    $dt['amount_vnd'] = $dt['amount'];
                    $dt['amount_usd'] = 0;
                }
                array_push($result, $dt);
            }
        }
        $poInfoList = array();
        foreach($result as $re){
            $tempArray = array(
                is_array($re['salesman']) ? implode(', ',$re['salesman']) : $re['salesman'] ,
                is_array($re['order_no']) ? implode(', ',$re['order_no']) : $re['order_no'] ,
                is_array($re['odr_recv_no']) ? implode(', ',$re['odr_recv_no']) : $re['odr_recv_no'] ,
                $re['item_code'],
                is_array($re['buyer_kb']) ? ( in_array('1', $re['buyer_kb']) ? '〇' : null) : ($re['buyer_kb'] == '1' ? '〇' : null),
                is_array($re['buyer_kb']) ? ( in_array('2', $re['buyer_kb']) ? '〇' : null) : ($re['buyer_kb'] == '2' ? '〇' : null),
                $re['delivery_date'],
                $re['amount_usd'] !== 0 ?  $re['amount_usd'] : null,
                $re['amount_vnd'] !== 0 ?  $re['amount_vnd'] : null,
                $re['supplier_name'],
                $re['payment_term'],
                null
            );
            array_push($poInfoList, $tempArray);
        }
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $filePath = "views/orders/template.xlsx";
        $spreadsheet = $reader->load(APPPATH.$filePath); 
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle((string)$currentMonth);
        $spreadsheet->getActiveSheet()->insertNewRowBefore(6 + 1, count($poInfoList) - 1);  
        $spreadsheet->getActiveSheet()->fromArray( $poInfoList, NULL, 'A6');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Du_Kien_Tra_NCC.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    function isEmptyRow($row) {
        foreach($row as $cell){
            if (null !== $cell) return false;
        }
        return true;
    }
}
