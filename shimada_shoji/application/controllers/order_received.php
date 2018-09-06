<?php
defined('BASEPATH') or exit('No direct script access allowed');
include 'vendor/autoload.php';

/**
 * Received Order 's Controller
 */
class Order_Received extends MY_Controller
{
    /**
     * contructor function
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('common_helper');
        $this->load->library('common');
        $this->load->model('order_received_model');
        $this->load->model('employee_model');
        $this->load->model('company_model');
        $this->load->model('items_model');
        $this->load->model('order_received_details_model');
        $this->load->model('komoku_model');
        $this->load->model('store_item_model');
    }
    public function index($orderID = null)
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'ODS0010';
            $order_received = $this->order_received_model->getAllReceivedOrder();
            if ($orderID != null) {
                $order_received_temp = $this->order_received_model->getReceivedOrderByCondition($orderID);
                if (!empty($order_received_temp)) {
                    $order_received = $order_received_temp;
                    $this->data['orderID'] = $orderID;
                }   
            }
            $employeeList = $this->employee_model->getAllEmployee();
            $order_received_details = $this->order_received_details_model->getAllOrderReceivedDetails();
            $warehouse = $this->komoku_model->getComboboxData(KOMOKU_WAREHOUSE);
            $salesmanList = $this->komoku_model->get_all_endsaleman();
            $this->data['salesman_list'] = $salesmanList;
            $this->data['warehouse_list'] = $warehouse;
            $this->data['orderReceivedList'] = $order_received;
            $this->data['orderReceivedDetails'] = $order_received_details;
            $this->data['employeeList'] = $employeeList;
            $this->data['statusList'] = $this->komoku_model->get_status_by_kbnArr(explode(",", ORDER_RECEIVED_STATUS_SEARCH));
            $this->data["customer_list"] = $this->company_model->getAllCompanyName();
            // Load the subview
            $content = $this->load->view('order_received/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    // search order received
    public function search()
    {
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        $params = $this->input->post('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $column = $order[0]['column'];
        $order[0]['column'] = $columns[$column]['data'];
        $order_receives = $this->order_received_model->search($params, $start, $length, $recordsTotal, $recordsFiltered, $order);
        foreach ($order_receives as $key => &$order_receive) {
            $details = $this->order_received_details_model->getOrderReceivedByID(
                $order_receive['order_receive_no'],
                $order_receive['partition_no'],
                $order_receive['order_receive_date']
            );
            $or_no = encodeurl(trim($order_receive['order_receive_no']),3);
            $order_receive['details'] = $details;
            $order_receive['urlPrimaryKey'] = $or_no . "/" . trim($order_receive['partition_no']) . "/" . trim($order_receive['order_receive_date']);
            // calculator total{"unit"=> total}
            $quantityTotal = array();
            foreach($details as $detail){
                if(array_key_exists($detail['unit'], $quantityTotal)){
                    $quantityTotal[$detail['unit']] = !empty($detail['quantity']) ? $quantityTotal[$detail['unit']] + floatval($detail['quantity']) : $quantityTotal[$detail['unit']];
                }else{
                    $quantityTotal[$detail['unit']] = !empty($detail['quantity']) ? floatval($detail['quantity']) : 0;
                }
            }
            $order_receive['sum_quantity'] = $quantityTotal;
        }
        echo json_encode(array(
            'data' => $order_receives,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw'),
        ));
    }

    public function do_allocation(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        try {
            $params = $this->input->post();
            $result = null;
            if(isset($params['move_to']) && $params['move_to'] == '1'){
                $result = $this->order_received_model->moveToNextPartition($params);
            }else{
                $result = $this->order_received_model->doAllocation($params);
            }
            if ($result) {
                $data = $this->order_received_model->getAllReceivedOrder();
                $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
                $this->responseJsonSuccess($data, $this->lang->line("COMMON_I002"));
            } else {
                $this->responseJsonError($this->lang->line("save_fail"));
            }
        } catch(Error $e) {
            $this->responseJsonError($e->getMessage());
        }
    }

    public function do_unallocate_item(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        try {
            $params = $this->input->post();
            $result = null;
            $result = $this->order_received_model->doUnAllocation($params);
            if ($result) {
                $this->responseJsonSuccess(null, $this->lang->line("COMMON_I002"));
            } else {
                $this->responseJsonError($this->lang->line("save_fail"));
            }
        } catch(Error $e) {
            $this->responseJsonError($e->getMessage());
        }
    }
    
    public function do_delete_item() {
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }

        $result = $this->order_received_details_model->deleteOrderReceiveItem($this->input->post(), $this->session->userdata('user')['employee_id']);
        if ($result) {
            $this->responseJsonSuccess();
        } else {
            $this->responseJsonError('failed');
        }
    }

    public function do_save_item() {
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        $data = $this->input->post();
        $data["delivery_date"] = date_format(date_create($data["delivery_date"]), "Y-m-d");
        $result = $this->order_received_details_model->updateOrderReceiveItem($this->input->post(), $this->session->userdata('user')['employee_id']);
        if ($result) {
            $this->responseJsonSuccess($data["delivery_date"]);
        } else {
            $this->responseJsonError('failed');
        }
    }

    public function do_add_item() {
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }

        $post_data = $this->input->post();
        unset($post_data["colors"]);
        if($post_data['delivery_date'] == "") {
            unset($post_data['delivery_date']);
        }
        $result = $this->order_received_details_model->addOrderReceiveItem($post_data, $this->session->userdata('user')['employee_id']);
        if ($result != false) {
            $this->responseJsonSuccess($result);
        } else {
            $this->responseJsonError('failed');
        }
    }
    
    /**
     * Action for screen: *Order Received 's Input Header Information*
     * 
     * Method: `GET`
     * 
     * @return void
     */
    public function add()
    {
        if (!$this->is_logged_in()) {
            show_error('', 403);
        }
        $this->data['screen_id'] = 'ODS0020';
        $this->data['isAddNew'] = true;
        $this->add_edit($this->inputOrderReceived());
    }

    /**
     * Action for screen: *Order Received 's Input Header Information (Edit)*
     * 
     * Method: `GET`
     *
     * @param [type] $orderNo
     * @return void
     */
    public function edit($orderReceiveNo, $partitionNo, $orderReceiveDate)
    {
        if (!$this->is_logged_in()) {
            return;
        }
        $orderReceiveNo = rawurldecode(rawurldecode(rawurldecode($orderReceiveNo)));
        $this->data['screen_id'] = 'ODS0020';
        $orderReceived = $this->order_received_model->getReceivedOrderByID($orderReceiveNo, $orderReceiveDate, $partitionNo);
        $countPartition = $this->order_received_model->getCountReceivedOrderByID($orderReceiveNo, $orderReceiveDate);
        if ($orderReceived == null || $countPartition == NULL) {
            show_404('');
        }
        $orderReceived['partition_no_show'] = ($countPartition > 1) ? $orderReceived['partition_no'] : '';
        $this->data['isAddNew'] = false;
        $this->add_edit($orderReceived);
    }

    /**
     * render view when add, edit, save
     *
     * @param [type] $orderReceived
     * @return void
     */
    private function add_edit($orderReceived) {
		unset($customerList);
		$currentUser = $this->session->userdata('user');
        // get currencies
        $currency = $this->komoku_model->get_all_currency();
        // get employees
        $employeeList = $this->employee_model->getAllEmployee();
        // get customers
        $customerList = $this->company_model->getAllCustomer();
        // warehouse info
        $warehouseList = $this->komoku_model->getWarehouseWithUse("1");
        if($customerList != null) {
            foreach ($customerList as $key => &$customer) {
                $customer['branches'] = $this->company_model->getAllBranchOfCompany($customer['company_id']);
                $customer['head_offices'] = $this->company_model->getAllHeadOfficeOfCompany($customer['company_id']);
            };
        }

        if($orderReceived['order_receive_no'] != "" && $orderReceived['partition_no'] != "" && $orderReceived['order_receive_date'] != "") {
            $orderReceivedTemp = $this->order_received_model->getReceivedOrderByID($orderReceived['order_receive_no'], $orderReceived['order_receive_date'], $orderReceived['partition_no']);
            if(!in_array($orderReceivedTemp['customer'], array_column($customerList, 'company_name'), true)){
                array_unshift($customerList, Array('company_name' => $orderReceivedTemp['customer']));
                $customerList[0]['branches'][0]['branch_name'] = $orderReceivedTemp['delivery_to'];
                $customerList[0]['head_offices'][0]['head_office_name'] = $orderReceivedTemp['head_office'];
            }
            $orderReceived["urlPrimaryKey"] = encodeurl(trim($orderReceived['order_receive_no']),3)."/".trim($orderReceived['partition_no'])."/".trim($orderReceived['order_receive_date']);
        }

        // get banks
        $banks = $this->komoku_model->get_all_bank();
        // TODO: get tax_classification
        $taxClassifications = $this->komoku_model->get_all_tax();

        // set data for render
        $this->data['orderReceived'] = $orderReceived;
        $this->data['currency'] = $currency;
        $this->data['employeeList'] = $employeeList;
        $this->data['customerList'] = $customerList;
        $this->data['warehouseList'] = $warehouseList;
        $this->data['banks'] = $banks;
        $this->data['taxClassifications'] = $taxClassifications;
        $this->data['curUser'] = $currentUser;
        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();

        // render content
        $content = $this->load->view('order_received/add_edit.php', $this->data, true);

        // pass to the master view
        $this->load->view('master_page', array('content' => $content));
    }

    /**
     * create order received object from input
     *
     * @return array OrderReceived
     */
    private function inputOrderReceived() {
        $currentUser = $this->session->userdata('user');
        return array(
            'order_receive_no' => $this->input->post('order_receive_no'),
            'partition_no' => $this->input->post('partition_no'),
            'order_receive_date' => $this->input->post('order_receive_date'),
            'kubun' => $this->input->post('kubun'),
            'seller_kb' => $this->input->post('seller_kb'),
            'identify_name' => $this->input->post('identify_name'),
            'customer' => $this->input->post('customer'),
            'delivery_to' => $this->input->post('delivery_to'),
            'delivery_address' => $this->input->post('delivery_address'),
            'head_office' => $this->input->post('head_office'),
            'head_office_address' => $this->input->post('head_office_address'),
            'rate_usd' => $this->input->post('usd_vnd'),
            'rate_jpy' => $this->input->post('jpy_vnd'),
            'contract_no' => $this->input->post('contract_no'),
            'style' => $this->input->post('style'),
            'apparel' => $this->input->post('apparel'),
            'odr_department' => $this->input->post('odr_department'),
            'staff' => $this->input->post('staff'),
            'tax' => $this->input->post('tax_classification'),
            'bank' => $this->input->post('bank'),
            'currency' => $this->input->post('currency'),
            'delivery_date' => $this->input->post('delivery_date'),
            'input_user' => $this->input->post('input_user'),
            'assistance' => $this->input->post('assistance'),
            'note' => $this->input->post('note'),
            'create_user' => $this->input->post('create_user'),
            'create_date' => $this->input->post('create_date'),
            'status' => $this->input->post('status'),
            'edit_user' => $this->input->post('edit_user'),
            'edit_date' => $this->input->post('edit_date'),
            'edit_user_origin' => $this->input->post('edit_user_origin'),
            'edit_date_origin' => $this->input->post('edit_date_origin'),
            'payment_term' => $this->input->post('payment_term'),
            'delivery_term' => $this->input->post('delivery_term'),
            'fee_term' => $this->input->post('fee_term'),
            'vat_by' => $this->input->post('vat_by')
        );
    }

    /**
     * Action for save *received order* 's info when add new *received order*
     * 
     * Method: `POST`
     *
     * @return void
     */
    public function save()
    {   
        // check auth
        if (!$this->is_logged_in()) {
            return;
        }

        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");

        $orderReceived = $this->inputOrderReceived();
        $orderReceived['status'] = ORDER_RECEIVED_STATUS_OPEN;
        
        $duplicate = $this->order_received_model->getReceivedOrderByNoAndDate($orderReceived['order_receive_no'], $orderReceived['order_receive_date']);
        $result = false;
        // insert or update
        if ($orderReceived['partition_no'] == '') {
            foreach ($orderReceived as $key => $value) {
                if ($value == null || $value == '') {unset($orderReceived[$key]);}
            }
            $orderReceived['create_user'] = $currentUser['employee_id'];
			$orderReceived['create_date'] = $currentDate;
			

            if($duplicate == null) {
                $result = $this->order_received_model->insertReceivedOrder($orderReceived);
                if (!$result) {
                    show_error('', 500);
                }
                $or_no = encodeurl(trim($result['order_receive_no']),3);
                redirect(base_url() . 'order_received/add_details/' . $or_no . '/' . trim($result['partition_no']) . '/' . trim($result['order_receive_date']));
            } else {
                $orderReceived = $this->inputOrderReceived();
                $orderReceived['partition_no'] = '';
				$orderReceived['error_message'] = $this->lang->line('item_existed');
				
                $this->add_edit($orderReceived);
            }
        } else {
            if($duplicate['edit_date'] == $orderReceived['edit_date_origin']) {
                foreach ($orderReceived as $key => $value) {
                    if ($value == '') {$orderReceived[$key] = null;}
                }
                $orderReceived['edit_user'] = $currentUser['employee_id'];
                $orderReceived['edit_date'] = $currentDate;
                unset($orderReceived['edit_user_origin']);
                unset($orderReceived['edit_date_origin']);
                $result = $this->order_received_model->updateReceivedOrder($orderReceived);
                if (!$result) {
                    show_error('', 500);
                }
                $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
                $or_no = encodeurl(trim($result['order_receive_no']),3);
                redirect(base_url() . 'order_received/edit/' . $or_no . '/' . trim($result['partition_no']) . '/' . trim($result['order_receive_date']));
            } else {
                $orderReceived = $this->inputOrderReceived();
                $orderReceived['error_message'] = $this->lang->line('COMMON_E001');
                $this->add_edit($orderReceived);
            }
        }
    }

    public function delete() {
        // check auth
        if (!$this->is_logged_in()) {
            return;
        }
        
        $order_receive_no = $this->input->post('order_receive_no');
        $partition_no = $this->input->post('partition_no');
        $order_receive_date = $this->input->post('order_receive_date');

        $result = $this->order_received_model->deleteReceivedOrderById($order_receive_no, $partition_no, $order_receive_date);
        $data = $this->order_received_model->getAllReceivedOrder();
        $this->responseJsonSuccess($data);
    }

    //upload pdf
    public function pv_upload()
    {
        if ($this->is_logged_in()) {
            $textpdf = "";
            $employeeList = $this->employee_model->getAllEmployee();
            $this->data['screen_id'] = 'ODS0040';
            $this->data['type'] = '0';
            $this->data['title'] = $this->lang->line('pv_upload_order_receied');
            $this->data['employeeList'] = $employeeList;
            $this->data['textpdf'] = $textpdf;

            $content = $this->load->view('order_received/pv_upload.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    
    public function do_upload_pv(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
        }
        //parse pdf to text
        $pdffile = $_FILES["file_upload_hidden"]["tmp_name"];
        $this->data['screen_id'] = 'ODS0050';
        
        $textpdf = (new \Csv\PdfToText\Pdf(PDF_TO_TEXT_PATH))
            ->setPdf($pdffile)
            ->setOptions(['raw', 'enc UTF-8', 'eol unix'])
            ->text();

        $textpdf = escape_json($textpdf);
        $this->load->model('pv_pdf_model');
        $default = array();
        $odrs = $this->pv_pdf_model->parse($textpdf, $default);
        
        $this->data['title'] = $this->lang->line('pv_upload_order_receied');
        $this->data['type'] = $textpdf;
        $this->data['order_receives'] = $odrs;
        $this->data['textpdf'] = $textpdf;

        // get currencies
        $currency = $this->komoku_model->get_all_currency();
        // get employees
        $employeeList = $this->employee_model->getAllEmployee();
        // get banks
        $banks = $this->komoku_model->get_all_bank();
        // TODO: get tax_classification
        $taxClassifications = $this->komoku_model->get_all_tax();

        // set data for render
        $this->data['currency'] = $currency;
        $this->data['employeeList'] = $employeeList;
        $this->data['banks'] = $banks;
        $this->data['taxClassifications'] = $taxClassifications;

        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
        $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
        $this->data['unit_list'] = $this->komoku_model->get_all_unit();
        $this->data['size_list'] = $this->komoku_model->get_all_size();
        $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
        $this->data['color_list'] = $this->komoku_model->get_all_color();
        $this->data['vendor_list'] = $this->company_model->getAllVendor();

        // render content
        $content = $this->load->view('order_received/pv_upload_success.php', $this->data, true);

        // pass to the master view
        $this->load->view('master_page', array('content' => $content));
    }

    public function save_pv_upload(){
        if (!$this->is_logged_in()) {
            show_error('', 401);
        }
        $this->data['screen_id'] = 'ODS0050';
        $order_receives = $this->input->post('order_receives');

        // decode details
        foreach ($order_receives as $key => &$order_receive) {
            $order_receive['details'] = str_replace("\\\\\"","\\\"", $order_receive['details']);
            $order_receive['details'] = json_decode($order_receive['details'], true);
        }

        for($i = 0; $i < count($order_receives); $i++) {
            $this->form_validation->set_rules("order_receives[$i][order_receive_no]", $this->lang->line('order_received_no'), 'required');
            $this->form_validation->set_rules("order_receives[$i][order_receive_date]", $this->lang->line('order_received_date'), 'required');
            $this->form_validation->set_rules("order_receives[$i][kubun]", 'kubun', 'required'); 
            $this->form_validation->set_rules("order_receives[$i][currency]", $this->lang->line('currency'), 'required');
            $this->form_validation->set_rules("order_receives[$i][input_user]", $this->lang->line('input_user'), 'required');
            $this->form_validation->set_rules("order_receives[$i][customer]", $this->lang->line('customer'), 'required');
            $this->form_validation->set_rules("order_receives[$i][delivery_date]", $this->lang->line('delivery_date'), 'required');
        }
        
        $success = false;
        $insert_result_array = [];      
        if ($this->form_validation->run() === true) {
            $insert_result_array = $this->order_received_model->import($order_receives);
            if ($insert_result_array === true) {
                $success = true;
            }
        }

        if (!$success) {
            $this->data['title'] = $this->lang->line('pv_upload_order_receied');

            // get currencies
            $currency = $this->komoku_model->get_all_currency();
            // get employees
            $employeeList = $this->employee_model->getAllEmployee();
            // get banks
            $banks = $this->komoku_model->get_all_bank();
            // TODO: get tax_classification
            $taxClassifications = $this->komoku_model->get_all_tax();
            
            $this->data['order_receives'] = $order_receives;//json_decode($this->input->post('uploaded_order_receives'), true);

            // set data for render
            $this->data['currency'] = $currency;
            $this->data['employeeList'] = $employeeList;
            $this->data['banks'] = $banks;
            $this->data['taxClassifications'] = $taxClassifications;

            $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
            $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
            $this->data['unit_list'] = $this->komoku_model->get_all_unit();
            $this->data['size_list'] = $this->komoku_model->get_all_size();
            $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
            $this->data['color_list'] = $this->komoku_model->get_all_color();
            $this->data['vendor_list'] = $this->company_model->getAllVendor();

            $this->data['insert_result_array'] = $insert_result_array;
            $this->form_validation->set_error_delimiters('<p style="color:#d42a38">', '</p>');

            // render content
            $content = $this->load->view('order_received/pv_upload_success.php', $this->data, true);

            // pass to the master view
            $this->load->view('master_page', array('content' => $content));
        } else {
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            redirect(base_url('order_received'));
        }
    }

    // REVIEW:
    // public function pv_upload_success()
    // {
    //     if ($this->is_logged_in()) {

    //         $this->data['title'] = $this->lang->line('pv_upload_complete');
            
    //         // Load the subview
    //         $content = $this->load->view('order_received/pv_upload_success.php', $this->data, true);

    //         // Pass to the master view
    //         $this->load->view('master_page', array('content' => $content));
    //     }
    // }

    // public function view()
    // {
    //     if ($this->is_logged_in()) {
    //         // Load the subview
    //         $content = $this->load->view('order_received/view.php', $this->data, true);

    //         // Pass to the master view
    //         $this->load->view('master_page', array('content' => $content));
    //     }
    // }
    public function add_details($orderReceiveNo, $partitionNo, $orderReceiveDate)
    {
        if (!$this->is_logged_in()) {
            return;
        }
        $this->data['screen_id'] = 'ODS0030';
        $encodeOrNo = $orderReceiveNo;
        $orderReceiveNo = rawurldecode(rawurldecode(rawurldecode($orderReceiveNo)));
        $orderReceived = $this->order_received_model->getReceivedOrderByID($orderReceiveNo, $orderReceiveDate, $partitionNo);
        $countPartition = $this->order_received_model->getCountReceivedOrderByID($orderReceiveNo, $orderReceiveDate);
        if ($orderReceived == null || $countPartition == null) {
            show_404('');
        }
        $orderReceived['partition_no_show'] = ($countPartition > 1) ? $orderReceived['partition_no'] : '';
        $staff_name = $orderReceived["staff"];
        if(preg_match("/\\(\\d+\\)(.*)/", $staff_name, $m)){
            $staff_name = $m[1];
        };
        $orderReceived['urlPrimaryKey'] = $encodeOrNo ."/".$partitionNo."/".$orderReceiveDate;
        $orderReceived['staff_name'] = $staff_name;
        // get customers
        $customerList = $this->company_model->getAllCustomer();
        $orderReceived['customer_short_name'] = "";
        foreach($customerList as $customer){
            if($customer["company_name"] === $orderReceived['customer']){
                $orderReceived['customer_short_name'] = $customer["short_name"];
                break;
            }
        }
        $items = $this->items_model->getAllItems();
        $orderReceivedDetails = $this->order_received_details_model->getOrderReceivedByID($orderReceiveNo, $partitionNo, $orderReceiveDate);
        $warehouse = $this->komoku_model->getComboboxData(KOMOKU_WAREHOUSE);
        $salesmanList = $this->komoku_model->get_all_endsaleman();
        $this->data['salesman_list'] = $salesmanList;
        $this->data['warehouse_list'] = $warehouse;
        $this->data['title'] = $this->lang->line('detail_info_input');
        $this->data['orderReceived'] = $orderReceived;
        $this->data['items'] = $items;
        $this->data['orderReceivedDetails'] = $orderReceivedDetails;
        if ($this->is_logged_in()) {
            // Load the subview
            $content = $this->load->view('order_received/add_details.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    public function save_details($orderReceiveNo, $partitionNo, $orderReceiveDate){
        if (!$this->is_logged_in()) {
            return;
        }
        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");
        $orderReceiveNo = rawurldecode(rawurldecode(rawurldecode($orderReceiveNo)));
        $orderReceived = $this->order_received_model->getReceivedOrderByID($orderReceiveNo, $orderReceiveDate, $partitionNo);
        if ($orderReceived == null) {
            show_400('');
        }
        $orderReceived['sum_quantity'] = 0;
        $orderReceived['sum_amount'] = 0;
        $orderReceived['sum_amount_base'] = 0;

        $data = json_decode($this->input->post('data'));
        foreach($data as &$ele) {
            unset($ele->edit_mode);
            unset($ele->composition);
            $ele->create_date = $currentDate;
            $ele->create_user = $currentUser['employee_id'];
            $ele->edit_date = $currentDate;
            $ele->edit_user = $currentUser['employee_id'];
            $orderReceived['sum_quantity'] += $ele->quantity;
            $orderReceived['sum_amount'] += $ele->amount;
            $orderReceived['sum_amount_base'] += $ele->amount_base;
        }
        $save = $this->order_received_details_model->saveReceivedOrder($orderReceiveNo, $partitionNo, $orderReceiveDate, $data);
        if ($save) {
            $update = $this->order_received_model->updateReceivedOrder($orderReceived);
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            redirect(base_url() . 'order_received?order_receive_no=' . trim($orderReceiveNo));
        } else {
            show_error('update failed');
        }
    }

    public function search_items()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->get('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');

        $data = $this->items_model->search_for_add($params, $start, $length, $recordsTotal, $recordsFiltered);
        $colorList = $this->komoku_model->getComboboxData("KM0030");
        $colorKey = array();

        foreach($colorList as $color){
            $key = str_replace("x","",$color["komoku_name_2"]);
            if(!in_array($key, $colorKey)){
                array_push($colorKey, $key);
            }
        }

        foreach($data as &$detail){
            $key = $detail["jp_code"];
            for($i = strlen($key); $i > 1; $i--){
                $tempKey = substr($key, 0, $i);
                if(in_array($tempKey, $colorKey)){
                    $filter = function($el) use ($tempKey){
                        return str_replace("x","",$el["komoku_name_2"]) == $tempKey;
                    };
                    $detail["colors"]  = array_filter($colorList, $filter);
                    break;
                }
            }
        }

        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw'),
        ));
    }

    public function details($orderReceiveNo, $partitionNo, $orderReceiveDate) {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $orderReceiveNo = rawurldecode(rawurldecode(rawurldecode($orderReceiveNo)));
        $orderReceivedDetails = $this->order_received_details_model->getOrderReceivedByID($orderReceiveNo, $partitionNo, $orderReceiveDate);
        $colorList = $this->komoku_model->getComboboxData("KM0030");
        $colorKey = array();
        foreach($colorList as $color){
            $key = str_replace("x","",$color["komoku_name_2"]);
            if(!in_array($key, $colorKey)){
                array_push($colorKey, $key);
            }
        }

        foreach($orderReceivedDetails as &$detail){
            $key = $detail["jp_code"];
            for($i = strlen($key); $i > 1; $i--){
                $tempKey = substr($key, 0, $i);
                if(in_array($tempKey, $colorKey)){
                    $filter = function($el) use ($tempKey){
                        return str_replace("x","",$el["komoku_name_2"]) == $tempKey;
                    };
                    $detail["colors"]  = array_filter($colorList, $filter);
                    break;
                }
            }
        }

        echo json_encode(array(
            "data" => $orderReceivedDetails,
        ));
    }

    public function check_date($date)
    {
        $result = $this->validateDate($date, 'Y/m/d');
        if ($date == '' || $result) {
            return true;
        } else {
            $this->form_validation->set_message('check_date', 'Invalid date format');
            return false;
        }
    }
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    // REVIEW: Neu xoa thi nho xoa file order_received/export
    // public function exportpdf()
    // {
    //     $showHtml = false; // set true when coding
    //     if ($this->is_logged_in()) {
    //         $this->load->helper(array('dompdf', 'file'));
    //         // set data when render template
    //         if ($showHtml) {
    //             $this->load->view('order_received/export', $this->data);
    //         } else {
    //             $html = $this->load->view('order_received/export', $this->data, true);
    //             pdf_create($html, 'export');
    //         }
    //     }
    // }

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
    public function do_change_inventory(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post();
        $flgDuplicate = false;
        $note = "";//include value of primary which change
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');

        // old value
        $final_customer = $params["oldsalesmanchange"];
        $inventory = $params["warehouse"];
        $wh = $this->komoku_model->getWarehouseByName($inventory);
        if($wh !== null){
            $inventory = $wh['kubun'];
        }

        // new value
        $new_salesman = $params["salesmanchange"];
        $new_warehouse = $params["warehousechange"];

        //Set note value
        if($final_customer != $new_salesman){
            $note = $new_salesman;
        }
        if($inventory != $new_warehouse){
            $warehouse_name = $this->komoku_model->getKomokuByID(KOMOKU_WAREHOUSE, $new_warehouse)["komoku_name_2"];                
            $note .= ($note != '')?', ':'';
            $note .= $warehouse_name;
        }
        // New check
        $new_check = array();
        $new_check["salesman"] = $new_salesman;
        $new_check["item_code"] = $params["item_code"];
        $new_check["color"] = $params["color"];
        $new_check["size"] = $params["size"];
        $new_check["item_type"] = $params["item_type"];
        $new_check["order_no"] = $params["order_no"];
        $new_check["warehouse"] = $new_warehouse;
        $new_check["order_receive_no"] = $params["order_receive_no"];
        $new_check["partition_no"] = $params["partition_no"];;
        $new_check["order_detail_no"] = $params["order_detail_no"];;

        // Old check
        $old_check = array();
        $old_check["salesman"] = $final_customer;
        $old_check["item_code"] = $params["item_code"];
        $old_check["color"] = $params["color"];
        $old_check["size"] = $params["size"];
        $old_check["order_no"] = $params["order_no"];
        $old_check["item_type"] = $params["item_type"];
        $old_check["warehouse"] = $inventory;
        $old_check["order_receive_no"] = $params["order_receive_no"];
        $old_check["partition_no"] = $params["partition_no"];
        $old_check["order_detail_no"] = $params["order_detail_no"];
        //Get old item
        $old_item = $this->store_item_model->get_item($old_check)[0];
        // Get new item
        $new_item = $this->store_item_model->get_item($new_check);
        if(!empty($new_item)){
            $new_item = $new_item[0];
            $flgDuplicate = true;
        }
        $dataUpdate = array(
            "salesman"          => $final_customer,
            "item_code"         => $params["item_code"],
            "item_type"         => $params["item_type"],
            "color"             => $params["color"],
            "size"              => $params["size"],
            "order_no"          => $params["order_no"],
            "warehouse"         => $inventory,
            "newsalesman"       => $new_salesman,
            "newwarehouse"      => $new_warehouse,
            "invoice_no"        => $params["old_inv_no"],
            "edit_date"         => $currentDate,
            "note"              => $note,
            "edit_user"         => $currentUser['employee_id'],
            "order_receive_no"  => $params["order_receive_no"],
            "partition_no"      => $params["partition_no"],
            "order_detail_no"   => $params["order_detail_no"]
        );
        // Insert new item if Change primary key
        if($new_salesman != $final_customer || $new_warehouse != $inventory || $params['old_inv_no'] != $params['invoice_no']){
            // Set update data
            if(!$flgDuplicate){
                // Update old data
                $dataUpdate["newsalesman"] = $final_customer;
                $dataUpdate["newwarehouse"] = $inventory;
                // $dataUpdate["quantity"] = 0;
                $dataUpdate["invoice_no"] =  $params["old_inv_no"];
                //Data insert
                $old_item["salesman"] = $new_salesman;
                $old_item["warehouse"] = $new_warehouse;
                $old_item["invoice_no"] =  $params["invoice_no"];
                $old_item["create_date"] = $currentDate;
                $old_item["create_user"] = $currentUser['employee_id'];
                $old_item["edit_date"] = NULL;
                $old_item["edit_user"] = NULL;
               
                $this->store_item_model->insertStoreItem($old_item);
            }else{
                // Update new data
                $dataUpdate = $new_item;
                $dataUpdate["newsalesman"] = $new_salesman;
                $dataUpdate["newwarehouse"] = $new_warehouse;
                $dataUpdate["quantity"] =   $old_item["quantity"];
                $dataUpdate["inspect_ok"] =   $old_item["arrival_ok"];
                $dataUpdate["invoice_no"] =  $params["invoice_no"];

                $dataUpdateOld = $old_item;
                $dataUpdateOld['edit_date'] = $currentDate;
                $dataUpdateOld['edit_user'] = $currentUser['employee_id'];
                $dataUpdateOld["newsalesman"] = $final_customer;
                $dataUpdateOld["newwarehouse"] = $inventory;
                $dataUpdateOld["order_receive_no"] = $params["order_receive_no"];
                $dataUpdateOld["partition_no"] = $params["partition_no"];
                $dataUpdateOld["order_detail_no"] = $params["order_detail_no"];
                $dataUpdateOld["invoice_no"] =  $params["old_inv_no"];
                $dataUpdateOld["quantity"] =   0;
                $dataUpdateOld["inspect_ok"] =  0;
                $dataUpdateOld["note"] = $note;
                $this->store_item_model->updateStoreItem($dataUpdateOld, true);
            }
           // Update old item
            if($this->store_item_model->updateStoreItem($dataUpdate, true)){
                $this->responseJsonSuccess(null,  "Update complete");
                return;
            }
        }
        $this->responseJsonSuccess(null,  "Update complete");
    }
}
