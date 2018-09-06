<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';

class Inventory extends MY_Controller
{
    var $ConfigColumnsImportExcel = Array(
        "item_code"             =>"2",
        "item_name"             =>"3",
        "composition_1"         =>"4",
        "size"                  =>"5",
        "size_unit"             =>"6",
        "color"                 =>"7",
        "unit"                  =>"8",
        "quantity"              =>"9",
        "arrival_ok"            =>"9",
        "inspect_ok"            =>"9",
        "salesman"              =>"10",
        "warehouse"             =>"10+11",
        "status"                =>"12",
        "item_type"             =>"13",
        "invoice_no"            =>"14"
    );
    var $from_to_cell = "B8";
    var $inventory_cell = "B10";
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('items_model');
        $this->load->model('employee_model');
        $this->load->model('store_item_model');
        $this->load->model('orders_model');
        $this->load->model('company_model');
        $this->load->model('komoku_model');
        $this->load->model('order_received_details_model');
        $this->load->helper('common_helper');
    }
    public function index()
    {
        if ($this->is_logged_in()) {
            //set variable
            $this->data['screen_id'] = 'STR0010';
            $storeItems = $this->store_item_model->getStoreItems();
            $employeeList = $this->employee_model->getAllEmployee();
            $customerList = $this->komoku_model->get_all_endsaleman();
            $itemType = $this->komoku_model->getComboboxData(KOMOKU_ITEMTYPE);
            $warehouse = $this->komoku_model->getComboboxData(KOMOKU_WAREHOUSE);
            $this->data['title'] = $this->lang->line('inventory_list');
            // set variables
            $this->data['storeItems'] = $storeItems;
            $this->data['employeeList'] = $employeeList;
            $this->data['customer_list'] = $customerList;
            $this->data['item_type_list'] = $itemType;
            $this->data['warehouse_list'] = $warehouse;
            // Load the subview
            $content = $this->load->view('inventory/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    public function add($updateFlg = FALSE)
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'STR0020';
            $this->data['title'] = $this->lang->line('inventory_update');
            // $employeeList = $this->employee_model->getAllEmployee();
            $customerList = $this->komoku_model->get_all_endsaleman();
            $size_list = $this->komoku_model->get_all_size();
            $color_list = $this->komoku_model->get_all_color();
            $unit_list = $this->komoku_model->get_all_unit();
            if($updateFlg){
                $salemanEditStoreItem = trim($this->data["editStoreItem"]["salesman"]);
                if(!in_array($salemanEditStoreItem, array_column($customerList, 'komoku_name_2'), true)&&$salemanEditStoreItem){
                    array_unshift($customerList, Array('komoku_name_2'=>$salemanEditStoreItem));
                }
                $sizeEditStoreItem = trim($this->data["editStoreItem"]["size"]);
                if(!in_array($sizeEditStoreItem, array_column($size_list, 'komoku_name_2'), true)&&$sizeEditStoreItem){
                    if($sizeEditStoreItem !== ''){
                        array_unshift($size_list, Array('komoku_name_2'=>$sizeEditStoreItem));
                    }
                }
                $colorEditStoreItem = trim($this->data["editStoreItem"]["color"]);
                if(!in_array($colorEditStoreItem, array_column($color_list, 'komoku_name_2'), true)&&$colorEditStoreItem){
                    if($colorEditStoreItem !== ''){
                        array_unshift($color_list, Array('komoku_name_2'=>$colorEditStoreItem));
                    }
                }
                $unitEditStoreItem = trim($this->data["editStoreItem"]["unit"]);
                if(!in_array($unitEditStoreItem, array_column($unit_list, 'komoku_name_2'), true)&&$unitEditStoreItem){
                    array_unshift($unit_list, Array('komoku_name_2'=>$unitEditStoreItem));
                }
            }
            
            $itemType = $this->komoku_model->getComboboxData(KOMOKU_ITEMTYPE);
            $warehouse = $this->komoku_model->getComboboxData(KOMOKU_WAREHOUSE);
            // $this->data['employeeList'] = $employeeList;
            $this->data['customerList'] = $customerList;
            $this->data['item_types'] = $itemType;
            $this->data['warehouses'] = $warehouse;
            $this->data['size_list'] = $size_list;
            $this->data['color_list'] = $color_list;
            $this->data['unit_list'] = $unit_list;
            // Handle add data
            if($this->input->post()){
                $filename = $this->input->post('inspect_note_path_filename');
                $oldFile = $this->data['editStoreItem']['inspect_note_path'];
                //delete old file
                if($filename != ''){
                    if (file_exists('./upload/'.substr($oldFile,-36))){
                        unlink('./upload/'.substr($oldFile,-36));
                    };
                    //upload pdf file 
                    $config['upload_path'] = './upload';
                    $config['allowed_types'] = 'pdf';
                    $config['overwrite'] = true;
                    $config['encrypt_name'] = true;
                    $config['file_name'] = $filename;
                    $this->load->library('upload', $config);
                    $uploadStatus = $this->upload->do_upload('inspection_note_file');
                    $uploadData = $this->upload->data();
                    $filename .= $uploadData['file_name'];
                }
                $item_arrival_NULL = array();
                $sales_man = $this->input->post('final_customer');
                $warehouse = $this->input->post('warehouse');
                $item_code = $this->input->post('item_code');
                $item_name = $this->input->post('item_name');
                $color = $this->input->post('color');
                $size = $this->input->post('size');
                $size = rawurldecode($size);
                if($size==='{}'){
                    $size='';
                }
                $color = rawurldecode($color);
                if($color==='{}'){
                    $color='';
                }
                $od_quantity = str_replace(',','',$this->input->post('od_quantity')) + 0;
                $inv_no = $this->input->post('inv_no');
                $order_no = trim($this->input->post('order_no'));
                $arrival_date = $this->input->post('arrival_date');
                if($arrival_date){
                    $arrival_date.= " ".date("H:i:s");
                }
                $item_type = $this->input->post('item_type');
                $arrival_note = $this->input->post('arrival_note');
                $arrival_quantity = str_replace(',','',$this->input->post('arrival_quantity')) + 0;
                $inspection_date = $this->input->post('inspection_date');
                if($inspection_date){
                    $inspection_date.= " ".date("H:i:s");
                }
                $inspect_ok = $this->input->post('ok_quantity') != '' ? str_replace(',','',$this->input->post('ok_quantity')) + 0 : 0;
                $inspect_ng = $this->input->post('ng_quantity') != '' ? str_replace(',','',$this->input->post('ng_quantity')) + 0 : 0;
                $inspection_quantity = $this->input->post('ng_quantity') != '' ? str_replace(',','',$this->input->post('inspection_quantity')) + 0 : 0;
                $inspection_note = $this->input->post('inspection_note');
                // $comment = $this->input->post('comment');
                $currentDate = date("Y/m/d H:i:s");
                $currentUser = $this->session->userdata('user');
                // Insert data
                $data = array(
                    'salesman'         => $sales_man,
                    'warehouse'         => $warehouse,
                    'item_code'         => $item_code,
                    'item_name'         => $item_name,
                    'color'             => $color,
                    'size'              => $size,
                    'quantity'          => $od_quantity,
                    'invoice_no'        => $inv_no,
                    'order_no'          => $order_no,
                    'item_type'         => $item_type,
                    'arrival_note'      => $arrival_note,
                    'arrival_ok'        => $arrival_quantity,
                    'arrival_ng'        => $inspection_quantity,
                    'inspect_ok'        => $inspect_ok,
                    'inspect_ng'        => $inspect_ng,
                    'inspect_note'      => $inspection_note,
                    'note'              => '',
                    'inspect_note_path' => $filename != ''? $filename : $oldFile
                );
                if(!empty($arrival_date)){
                    $data['arrival_date'] = $arrival_date;
                    $data['arrival_status'] = "011";
                    //Check PO complete
                    $where = Array(
                        "order_no" => $order_no,
                        "arrival_date" => NULL
                    );
                    $item_arrival_NULL = $this->store_item_model->get_item($where);
                    
                }
                if(!empty($inspection_date)){
                    $data['inspect_date'] = $inspection_date;
                    $data['inspect_status'] = "012";
                }
                if($updateFlg){
                    $data['edit_date'] = $currentDate;
                    $data['edit_user'] = $currentUser['employee_id'];
                    $data['order_receive_no'] = $this->data["editStoreItem"]["order_receive_no"];
                    $data['partition_no'] = $this->data["editStoreItem"]["partition_no"];
                    $data['order_detail_no'] = $this->data["editStoreItem"]["order_detail_no"];
                    if($this->store_item_model->updateStoreItem($data)){
                        //Update status for order table
                        if(!empty($order_no)&&count($item_arrival_NULL)==0){
                            //"PO-DMBK-I-0005/18"
                            $tmpOrderNo = explode("-",$order_no);
                            if(count($tmpOrderNo)>=4){
                                $params['order_no_1'] = $tmpOrderNo[0];
                                $params['order_no_2'] = $tmpOrderNo[1];
                                $params['order_no_3'] = $tmpOrderNo[2];
                                $params['order_no_4'] = intval(explode("/",$tmpOrderNo[3])[0]);
                                $params['order_no_5'] = intval(explode("/",$tmpOrderNo[3])[1]);
                                $params['status'] = "015";
                                $params['edit_date'] = $currentDate;
                                $params['edit_user'] = $currentUser['employee_id'];
                                $this->orders_model->updateOrderOut($params);
                            }
                        }
                        redirect(base_url("inventory?item_code=$item_code"));
                    }
                }else{
                    $data['order_detail_no'] = 1;
                    $data['create_date'] = $currentDate;
                    $data['create_user'] = $currentUser['employee_id'];
                    if($this->store_item_model->insertStoreItem($data)){
                        redirect(base_url("inventory?item_code=$item_code"));
                    }
                }
            }

            // Load the subview
            $content = $this->load->view('inventory/add.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    public function primarykey_check(){
        $final_customer = $this->input->post('final_customer');
        $item_code = $this->input->post('item_code');
        $color = $this->input->post('color');
        $size = $this->input->post('size');
        $item_type = $this->input->post('item_type');
        // $order_no = $this->input->post('order_no');
        $warehouse = $this->input->post('warehouse');
        $data = array();
        $data["salesman"] = $final_customer;
        $data["item_code"] = $item_code;
        $data["color"] = $color;
        $data["size"] = $size;
        $data["item_type"] = $item_type;
        // $data["order_no"] = $order_no;
        $data["warehouse"] = $warehouse;
        if($this->store_item_model->check_exists($data)){
            $this->form_validation->set_message(__FUNCTION__, 'This item is exist');
            return false;
        }
        return true;
    }
    public function updateQuantity()
    {
        if ($this->is_logged_in()) {
            $data = json_decode(stripslashes($_POST['data']));
            $result = $this->items_model->updateItemQuantity($data->item_no, $data->quantity);
            if ($result != null) {
                echo json_encode(["success" => true, "msg" => 'successful', 'quantity' => $result['quantity']]);
            } else {
                echo json_encode(["success" => false, "msg" => 'error', 'quantity' => null]);
            }

        }
    }
    public function details($product)
    {
        if ($this->is_logged_in()) {
            $this->data['title'] = 'Details Product';
            // Load the subview
            $content = $this->load->view('products/add.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }

    public function uploads()
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'STR0030';
            $this->data['title'] = $this->lang->line('inventory_upload');
            if($this->input->post()){
                $this->form_validation->set_rules('file_upload_inventory', $this->lang->line('pv_file'), 'required');
                $this->form_validation->set_error_delimiters('<p style="color:#d42a38">', '</p>');
                if ($this->form_validation->run() === true) {
                    $this->doUpload();
                }
            }
            // Load the subview
            $content = $this->load->view('inventory/upload.php', $this->data, true);
            $this->load->view('master_page', array('content' => $content));
        }
    }

    public function search(){
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
        $storeItems = $this->store_item_model->getStoreItems($params, false, $start, $length, $recordsTotal, $recordsFiltered, $order);
        if(!empty($storeItems)){
            foreach($storeItems as $key => $storeItem){
                $sales_man = encodeurl($storeItem["salesman"]);
                $item_code = encodeurl(trim($storeItem["item_code"]),3);
                $item_type = encodeurl($storeItem["item_type_cd"]);
                $order_no = encodeurl($storeItem["order_no"], 3);
                $warehouse = encodeurl($storeItem["warehouse_cd"]);
                $size = encodeurl($storeItem["size"],3);
                $color = encodeurl($storeItem["color"],3);
                $order_receive_no = encodeurl(trim($storeItem["order_receive_no"]));
                if($storeItem["inspect_ok"]=="0"){
                    $storeItems[$key]["status"] = "Completed Allocate";
                }
                $storeItems[$key]["encodeSalesman"] = $sales_man;
                $storeItems[$key]["urlPrimaryKey"] = $sales_man.'/'.$item_code.'/'.$item_type.'/'.$order_no.'/'.$warehouse.'/'.$size.'/'.$color.'/'.$order_receive_no.'/'.$storeItem["partition_no"].'/'.$storeItem["order_detail_no"];
                $where = Array(
                    "salesman"  => trim($storeItem["salesman"]),
                    "item_code" => trim($storeItem["item_code"]),
                    "item_type" => $item_type,
                    "order_no"  => trim($storeItem["order_no"]),
                    "order_detail_no"     => trim($storeItem["order_detail_no"]),
                    "warehouse" => $warehouse,
                    "size"      => trim($storeItem["size"]),
                    "color"     => trim($storeItem["color"]),
                    "order_receive_no"     => trim($storeItem["order_receive_no"]),
                    "partition_no"     => trim($storeItem["partition_no"]),
                    // "invoice_no"     => trim($storeItem["invoice_no"]),
                );
                $history = $this->store_item_model->getHistory($where);
                $storeItems[$key]["history"] = $history;
            }
        }
        $result = json_encode(array(
            'data' => $storeItems,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw'),
        ));
        echo $result;
    }

    public function edit($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $where = array(
            "salesman" => $final_customer,
            "item_code" => $item_code,
            "item_type" => $item_type,
            "order_no" => $order_no,
            "warehouse" => $inventory,
            "order_receive_no" => trim($order_receive_no),
            "partition_no" => $partition_no,
            "order_detail_no"     => $order_detail_no,
        );
        $where["size"] = $size;
        $where["color"] = $color;
        $where = $this->normalizeItemData($where);

        // print_r($size);
        // exit();
        $storeItem = $this->store_item_model->getStoreItems($where, TRUE);
        $sales_man = encodeurl($storeItem[0]["salesman"]);
        $item_code = encodeurl(trim($storeItem[0]["item_code"]),3);
        $item_type = encodeurl($storeItem[0]["item_type_cd"]);
        $order_no = encodeurl($storeItem[0]["order_no"],3);
        $warehouse = encodeurl($storeItem[0]["warehouse_cd"]);
        $size = encodeurl($storeItem[0]["size"],3);
        $color = encodeurl($storeItem[0]["color"],3);
        $order_receive_no = encodeurl(trim($storeItem[0]["order_receive_no"]));
        $storeItem[0]["urlPrimaryKey"] = $sales_man.'/'.$item_code.'/'.$item_type.'/'.$order_no.'/'.$warehouse.'/'.$size.'/'.$color.'/'.$order_receive_no.'/'.$storeItem[0]["partition_no"].'/'.$storeItem[0]["order_detail_no"];
        $this->data["editStoreItem"] = $storeItem[0];
        $this->add(TRUE);
    }
    public function _response($success, $code = null, $data = null) {
        $res = array(
			"success" => $success, 
			"code" => $code, 
			"message" => $this->lang->line($code), 
			"data" => $data
		);
		return $res;
    }

    public function checkEditDate($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        $where = array(
            "salesman" => $final_customer,
            "item_code" => $item_code,
            "item_type" => $item_type,
            "order_no" => $order_no,
            "warehouse" => $inventory,
            "order_receive_no" => trim($order_receive_no),
            "partition_no" => $partition_no,
            "order_detail_no" => $order_detail_no
        );
        $where["size"] = $size;
        $where["color"] = $color;
        $where = $this->normalizeItemData($where);

        $storeItem = $this->store_item_model->getStoreItems($where, TRUE);
        $last_edit_date = $this->input->post("edit_date");
        $new_edit_date = $storeItem[0]['edit_date'];
        if($last_edit_date!=$new_edit_date){
            echo json_encode($this->_response(false, 'COMMON_E001'));
            return;
        }
        echo json_encode($this->_response(true, 'save_success'));
    }

    public function changeItem($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $flgDuplicate = false;
        $note = "";//include value of primary which change
        // $this->form_validation->set_rules('salesmanchange', $this->lang->line('salesman'), 'required');
        $this->form_validation->set_rules('warehousechange', $this->lang->line('warehouse'), 'required');
        $this->form_validation->set_rules('item_type', $this->lang->line('type'), 'required');
        // $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required');
        if($this->form_validation->run()){
            $currentDate = date("Y/m/d H:i:s");
            $currentUser = $this->session->userdata('user');
            $new_salesman = $this->input->post("salesmanchange");
            if(empty($new_salesman)) $new_salesman = "";
            $new_warehouse = $this->input->post("warehousechange");
            $new_item_type = $this->input->post("item_type");
            $old_okquantity = intval($this->input->post("oldokquantity"));
            $new_okquantity = intval($this->input->post("okquantity"));
            $old_ngquantity = intval($this->input->post("oldngquantity"));
            $new_ngquantity = intval($this->input->post("ngquantity"));
            $diff_okquantity = $old_okquantity - $new_okquantity;
            $diff_ngquantity = $old_ngquantity - $new_ngquantity;
            //Set note value
            if($final_customer != $new_salesman){
                $saleman_name = rawurldecode($new_salesman);
                $note = $saleman_name;
            }
            if($item_type != $new_item_type){
                $item_type_name = $this->komoku_model->getKomokuByID(KOMOKU_ITEMTYPE, $new_item_type)["komoku_name_2"];                
                $note .= ($note != '')?', ':'';
                $note .= $item_type_name;
            }
            if($inventory != $new_warehouse){
                $warehouse_name = $this->komoku_model->getKomokuByID(KOMOKU_WAREHOUSE, $new_warehouse)["komoku_name_2"];                
                $note .= ($note != '')?', ':'';
                $note .= $warehouse_name;
            }
            // New check
            $new_check = array();
            $new_check["salesman"] = $new_salesman;
            $new_check["item_code"] = $item_code;
            $new_check["color"] = $color;
            $new_check["size"] = $size;
            $new_check["item_type"] = $new_item_type;
            $new_check["order_no"] = $order_no;
            $new_check["warehouse"] = $new_warehouse;
            $new_check["order_receive_no"] = trim($order_receive_no);
            $new_check["partition_no"] = $partition_no;
            $new_check["order_detail_no"] = $order_detail_no;
            $new_check = $this->normalizeItemData($new_check);
            // Old check
            $old_check = array();
            $old_check["salesman"] = $final_customer;
            $old_check["item_code"] = $item_code;
            $old_check["color"] = $color;
            $old_check["size"] = $size;
            $old_check["order_no"] = $order_no;
            $old_check["item_type"] = $item_type;
            $old_check["warehouse"] = $inventory;
            $old_check["order_receive_no"] = trim($order_receive_no);
            $old_check["partition_no"] = $partition_no;
            $old_check["order_detail_no"] = $order_detail_no;
            $old_check = $this->normalizeItemData($old_check);
            //Get old item
            $old_item = $this->store_item_model->get_item($old_check)[0];
            // Get new item
            $new_item = $this->store_item_model->get_item($new_check)[0];
            if(!empty($new_item)){
                $flgDuplicate = true;
            }
            $dataUpdate = array(
                "salesman"          => $final_customer,
                "item_code"         => $item_code,
                "item_type"         => $item_type,
                "color"             => $color,
                "size"              => $size,
                "order_no"          => $order_no,
                "warehouse"         => $inventory,
                "newsalesman"       => $new_salesman,
                "newwarehouse"      => $new_warehouse,
                "newitem_type"      => $new_item_type,
                "inspect_ok"        => $new_okquantity,
                "edit_date"         => $currentDate,
                "note"              => $note,
                "edit_user"         => $currentUser['employee_id'],
                "order_receive_no"  => trim($order_receive_no),
                "partition_no"      => $partition_no,
                "order_detail_no"   => $order_detail_no
            );
            // Insert new item if Change primary key
            if(($diff_okquantity >= 0)||($diff_ngquantity >= 0)
                || $new_salesman != $final_customer
                || $new_warehouse != $inventory
                || $new_item_type != $item_type){
                if($diff_okquantity >= 0) $dataUpdate["inspect_ok"] = $diff_okquantity;
                if($diff_ngquantity >= 0) $dataUpdate["inspect_ng"] = $diff_ngquantity;
                // Set update data
                if(!$flgDuplicate){
                    // Update old data
                    $dataUpdate["newsalesman"] = $final_customer;
                    $dataUpdate["newwarehouse"] = $inventory;
                    $dataUpdate["newitem_type"] = $item_type;
                    if($diff_okquantity==0 && $diff_ngquantity==0){
                        $dataUpdate["quantity"] = 0;
                    }else{
                        $dataUpdate["quantity"] = $old_item["quantity"] - $new_okquantity - $new_ngquantity;
                    }
                    //Data insert
                    $old_item["salesman"] = $new_salesman;
                    $old_item["item_type"] = $new_item_type;
                    $old_item["warehouse"] = $new_warehouse;
                    if($diff_okquantity!=0 || $diff_ngquantity!=0){
                        $old_item["quantity"] = $new_okquantity + $new_ngquantity;
                    }
                    if($diff_okquantity >= 0) $old_item["inspect_ok"] = $new_okquantity;
                    if($diff_ngquantity >= 0) $old_item["inspect_ng"] = $new_ngquantity;
                    $old_item["create_date"] = $currentDate;
                    $old_item["create_user"] = $currentUser['employee_id'];
                    $old_item["edit_date"] = NULL;
                    $old_item["edit_user"] = NULL;
                    $old_item = $this->normalizeItemData($old_item);
                    $this->store_item_model->insertStoreItem($old_item);
                }else{
                    // Update new data
                    $dataUpdate = $new_item;
                    $dataUpdate["newsalesman"] = $new_salesman;
                    $dataUpdate["newwarehouse"] = $new_warehouse;
                    $dataUpdate["newitem_type"] = $new_item_type;
                    // $dataUpdate["salesman"] = $new_salesman;
                    // $dataUpdate["warehouse"] = $new_warehouse;
                    // $dataUpdate["item_type"] = $new_item_type;
                    if($diff_okquantity==0 && $diff_ngquantity==0){
                        $dataUpdate["quantity"] += $old_item["quantity"];
                        $dataUpdate["inspect_ok"] += $old_item["inspect_ok"];
                        $dataUpdate["inspect_ng"] += $old_item["inspect_ng"];
                    }else{
                        $dataUpdate["quantity"] += $new_okquantity + $new_ngquantity;
                        $dataUpdate["inspect_ok"] -= $new_okquantity;
                        $dataUpdate["inspect_ng"] -= $new_ngquantity;
                    }
                    // Delete old data
                    // $this->store_item_model->deleteStoreItem($old_check);
                    // Update old data
                    $dataUpdateOld = $old_item;
                    $dataUpdateOld['edit_date'] = $currentDate;
                    $dataUpdateOld['edit_user'] = $currentUser['employee_id'];
                    $dataUpdateOld["newsalesman"] = $final_customer;
                    $dataUpdateOld["newwarehouse"] = $inventory;
                    $dataUpdateOld["newitem_type"] = $item_type;
                    $dataUpdateOld["order_receive_no"] = trim($order_receive_no);
                    $dataUpdateOld["partition_no"] = $partition_no;
                    $dataUpdateOld["order_detail_no"] = $order_detail_no;
                    $dataUpdateOld["note"] = $note;
                    // $dataUpdate["salesman"] = $new_salesman;
                    // $dataUpdate["warehouse"] = $new_warehouse;
                    // $dataUpdate["item_type"] = $new_item_type;
                    if($diff_okquantity==0 && $diff_ngquantity==0){
                        //Chuyen toan bo quantity
                        $dataUpdateOld["quantity"] = 0;
                        $dataUpdateOld["inspect_ok"] = 0;
                        $dataUpdateOld["inspect_ng"] = 0;
                    }else{
                        $dataUpdateOld["quantity"] -= $new_okquantity + $new_ngquantity;
                        $dataUpdateOld["inspect_ok"] -= $new_okquantity;
                        $dataUpdateOld["inspect_ng"] -= $new_ngquantity;
                    }
                    $dataUpdateOld = $this->normalizeItemData($dataUpdateOld);
                    $this->store_item_model->updateStoreItem($dataUpdateOld, true);
                }
           }
           // Update old item
           $dataUpdate = $this->normalizeItemData($dataUpdate);
            if($this->store_item_model->updateStoreItem($dataUpdate, true)){
                $this->session->set_flashdata('succes_msg', "Update complete");
                redirect(base_url("inventory?item_code=$item_code"));
            }
        }
    }

    public function delete($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');
        $where = array(
            "salesman"          => $final_customer,
            "item_code"         => $item_code,
            "item_type"         => $item_type,
            "order_no"          => $order_no,
            "warehouse"         => $inventory,
            "size"              => $size,
            "color"             => $color,
            "order_receive_no"  => trim($order_receive_no),
            "partition_no"      => $partition_no,
            "order_detail_no"   => $order_detail_no
        );
        $where = $this->normalizeItemData($where);
        $updateData = array(
            "del_flg" => "1",
            "edit_date" => $currentDate,
            "edit_user" => $currentUser['employee_id']
        );
        if($this->store_item_model->update($where, $updateData)){
            redirect(base_url('inventory'));
        }
    }

    public function keep($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');
        $where = array(
            "salesman"          => $final_customer,
            "item_code"         => $item_code,
            "item_type"         => $item_type,
            "order_no"          => $order_no,
            "warehouse"         => $inventory,
            "size"              => $size,
            "color"             => $color,
            "order_receive_no"  => trim($order_receive_no),
            "partition_no"      => $partition_no,
            "order_detail_no"   => $order_detail_no
        );
        $where = $this->normalizeItemData($where);
        $updateData = array(
            "status" => "017",
            "edit_date" => $currentDate,
            "edit_user" => $currentUser['employee_id']
        );
        if($this->store_item_model->update($where, $updateData)){
            redirect(base_url('inventory?item_code='.$item_code));
        }
    }

    public function unKeep($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');
        $where = array(
            "salesman"          => $final_customer,
            "item_code"         => $item_code,
            "item_type"         => $item_type,
            "order_no"          => $order_no,
            "warehouse"         => $inventory,
            "size"              => $size,
            "color"             => $color,
            "order_receive_no"  => trim($order_receive_no),
            "partition_no"      => $partition_no,
            "order_detail_no"   => $order_detail_no
        );
        $where = $this->normalizeItemData($where);
        $updateData = array(
            "edit_date" => $currentDate,
            "edit_user" => $currentUser['employee_id']
        );
        $this->db->set('status', 'inspect_status', FALSE);
        if($this->store_item_model->update($where, $updateData, false)){
            redirect(base_url('inventory?item_code='.$item_code));
        }
    }

    public function end($final_customer, $item_code, $item_type, $order_no, $inventory, $size, $color, $order_receive_no, $partition_no, $order_detail_no){
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');
        $where = array(
            "salesman" => $final_customer,
            "item_code" => $item_code,
            "item_type" => $item_type,
            "order_no" => $order_no,
            "warehouse" => $inventory,
            "size" => $size,
            "color" => $color,
            "order_receive_no"  => trim($order_receive_no),
            "partition_no"      => $partition_no,
            "order_detail_no"   => $order_detail_no
        );
        $where = $this->normalizeItemData($where);
        $updateData = array(
            "status" => "012",
            "edit_date" => $currentDate,
            "edit_user" => $currentUser['employee_id']
        );
        if($this->store_item_model->update($where, $updateData)){
            redirect(base_url('inventory?item_code='.$item_code));
        }
    }

    public function listProducts(){
        $items = $this->items_model->getAllItems();
        echo json_encode($items);
    }

    public function getItem($id){
        $items = $this->items_model->getItemByID($id);
        echo json_encode($items[0]);
    }

    public function search_allocation()
    {
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        $item_code = $this->input->get('item_code');
        $item_size = $this->input->get('size');
        $item_color = $this->input->get('color');
        $data = $this->store_item_model->searchForAllocate($item_code, $item_color, $item_size);

        echo json_encode(array("data" => $data));
    }

    // search order received item
    public function search_order_received_detail()
    {
        if (!$this->is_logged_in()) {
            show_error('', 401);
            return;
        }
        $params = $this->input->get('param');
        // $start = $this->input->get('start');
        // $length = $this->input->get('length');
        $order_receives_detail = $this->order_received_details_model->search_for_add_inventory($params);
        echo json_encode(array(
            'data' => $order_receives_detail
        ));
    }
    public function doUpload(){
        $excelfile = $_FILES["file_upload_inventory_hidden"]["tmp_name"];
        $resultreadexcel = $this->readExcel($excelfile);
        if($resultreadexcel['success']){
            $this->session->set_flashdata('success_msg', 'Upload and read data success');
            redirect(base_url('inventory?item_code='));
        }else{
            $this->session->set_flashdata('error_upload_msg', $resultreadexcel['error_msg']);
            redirect(base_url('inventory/uploads'));
        }
    }
    public function readexcel($file_path){
        $result = Array(
            'success' => true,
            'error_msg' => ''
        );
        $rowDublicate = Array();
        $data = Array();
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_path);
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($file_path);

        $itemType_list = $this->komoku_model->getComboboxData(KOMOKU_ITEMTYPE);
        $warehouse_list = $this->komoku_model->getComboboxData(KOMOKU_WAREHOUSE);
        $itemType_list = array_column($itemType_list, "kubun", "komoku_name_2");
        $warehouse_list = array_column($warehouse_list, "kubun", "komoku_name_2");
        // $itemType_list = array_map(function($key=>$value){

        //     },
        // );
        $worksheet = $spreadsheet->getActiveSheet();
        $endCol = $worksheet->getHighestColumn();
        $endRow = $worksheet->getHighestRow();
        $rowIndex = 19;
        $colIndex = 1;
        for($rowIndex; $rowIndex <= $endRow; $rowIndex++){
            // continue if row empty
            $dataArray = $worksheet->rangeToArray("A$rowIndex:$endCol$rowIndex")[0];
            if(count(array_unique($dataArray))==1 && end($dataArray) == null){
                continue;
            }
            $lineData = [];
            $status = trim($worksheet->getCellByColumnAndRow($this->ConfigColumnsImportExcel["status"], $rowIndex)->getValue());
            foreach($this->ConfigColumnsImportExcel as $column_name => $index_column){
                // Continue when in column status
                if($column_name == "status") continue;
                $columns = explode("+", $index_column);
                $val_columns = [];
                foreach($columns as $column){
                    $column = intval($column);
                    $val_columns[] = trim($worksheet->getCellByColumnAndRow($column, $rowIndex)->getValue());
                }
                $val = implode("_", $val_columns);
                if($column_name == "salesman"){
                    if(!empty($status) && strtolower($status) === "free"){
                        $val = $status;
                    }
                }else if($column_name == "arrival_date"){
                    if(!empty($val)){
                        $UNIX_DATE = ($val - 25569) * 86400;
                        $val = date("Y/m/d H:i:s", $UNIX_DATE);
                    }else{
                        $val = null;
                    }
                }else if($column_name == "warehouse"){
                    if(!empty($val)){
                        if(!empty($status) && strtolower($status) !== "free"){
                            $val .= "_".$status;
                        }
                        $val = array_key_exists($val,$warehouse_list)?$warehouse_list[$val]:"";
                    }
                }else if($column_name == "item_type"){
                    if(!empty($val)){
                        $val = array_key_exists($val,$itemType_list)?$itemType_list[$val]:"";
                    }
                }else if($column_name == "size"){
                    $val = (string)$val;
                }else if(preg_match('/quantity|ok/i', $column_name)){
                    $val = intval($val);
                }
                $lineData[$column_name] = $val;
            }
            $lineData["inspect_ng"] = 0;
            if(strcasecmp(trim($status),"NG")==0){
                $lineData["inspect_ng"] = $lineData["inspect_ok"];
                $lineData["inspect_ok"] = 0;
            }
            $lineData["arrival_ng"] = $lineData["quantity"];
            $lineData["create_date"] = $currentDate;
            $lineData["arrival_date"] = $currentDate;
            $lineData["inspect_date"] = $currentDate;
            $lineData["arrival_status"] = "011";
            $lineData["inspect_status"] = "012";
            $lineData["create_user"] = $currentUser['employee_id'];
            $lineData["order_receive_no"] = "PVxxx";
            $lineData["partition_no"] = 1;
            $lineData["order_no"] = "POxxx";
            $lineData["order_detail_no"] = "1";
            $lineData["status"] = "012";
            
            $check = array();
            $check["salesman"] = $lineData["salesman"];
            $check["item_code"] = $lineData["item_code"];
            $check["color"] = $lineData["color"];
            $check["size"] = $lineData["size"];
            $check["item_type"] = $lineData["item_type"];
            $check["order_no"] = (string)$lineData["order_no"];
            $check["warehouse"] = $lineData["warehouse"];
            if(!$this->store_item_model->check_exists($check)){
                $this->store_item_model->insertItem($lineData);
            }else{
                $result['success'] = false;
                $tmp = "Row %s: %s, %s, %s, %s, %s, %s.";
                $rowDublicate[] = sprintf($tmp,$rowIndex,$check['item_code'],$check['size'],$check['color'], str_replace("_",", ",array_search($check["warehouse"],$warehouse_list)),$check['salesman'],array_search($check["item_type"],$itemType_list));
            }
        }
        if(count($rowDublicate) > 0){
            $result['error_msg'] = "List duplicate data is shown as below:<br><br>".join("<br>", $rowDublicate);
        }
        return $result;
    }
    public function export_excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        // $from_date = $this->input->post("created_from");
        // $to_date = $this->input->post("created_to");
        // $warehouse = $this->input->post("warehouse");
        $params = $this->input->get();
        if(empty($params['created_from'])){
            $params['created_from'] = "01 Jan, 1970";
        }
        if(empty($params['created_to'])){
            $params['created_to'] = date('d M, Y');
        }
        $data = $this->store_item_model->getStoreItems($params);
        $summary_data = $this->store_item_model->getsummaryData($params);
        $detail_data = $this->store_item_model->getDetailData($params);

        $this->writeDataToExcel('views/inventory/template.xlsx', $data, $summary_data, $detail_data, $params);
    }
    private function writeDataToExcel($filePath, $data, $summary_data, $detail_data, $params, $flgAllWarehouse=false){
        $start_row_data_sheet = 3;
        $start_row_summary_sheet = 15;
        $start_row_detail_sheet = 15;

        $created_from = $params['created_from']!=''?$params['created_from']:'...';
        $create_to = $params['created_to']!=''?$params['created_to']:'...';
        //Handle summary data and detail data when user select all ware house
        $summary_data_warehouse = [];
        $detail_data_warehouse = [];
        // $warehousName = $params['created_from'];
        foreach($summary_data as $warehouse_data){
            $warehouseName = explode("_",$warehouse_data["warehouse"])[0];
            $summary_data_warehouse[$warehouseName][] = $warehouse_data;
        }
        foreach($detail_data as $warehouse_data){
            $warehouseName = explode("_",$warehouse_data["warehouse"])[0];
            $detail_data_warehouse[$warehouseName][] = $warehouse_data;
        }
        //End

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);
        // Fill Data sheet
        $spreadsheet->setActiveSheetIndex(0);
        $dataArr = array();
        foreach($data as $index => $line) {
            $tmpData= array(
                $index + 1,
                $line['salesman'],
                $line['item_code'],
                $line['item_name'],
                $line['color'],
                $line['size'],
                $line['invoice_no'],
                $line['order_no'],
                $line['order_detail_no'],
                $line['order_receive_no'],
                $line['quantity'],
                $line['unit'],
                $line['buy_price'],
                $line['buy_amount'],
                $line['item_type'],
                $line['warehouse'],
                $line['status'],
                $line['arrival_status'],
                $line['inspect_status'],
                substr($line['arrival_date'],0,10),
                $line['inspect_ok'],
                $line['inspect_ng']
            );
            array_push($dataArr, $tmpData);
        }
        $spreadsheet->getActiveSheet()->insertNewRowBefore($start_row_data_sheet + 1, count($dataArr));
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "B3");

        $datefromtoVal = "Từ ngày $created_from đến $create_to.";
        foreach($summary_data_warehouse as $warehouseName=>$warehouseData){
            $inventoryVal = "Kho: $warehouseName";  
            $clonedWorksheet = clone $spreadsheet->getSheetByName('Summary');
            $clonedWorksheet->setTitle("$warehouseName - Summary");
            // Fill Summary sheet
            $clonedWorksheet->setCellValue($this->from_to_cell, $datefromtoVal);
            $clonedWorksheet->setCellValue($this->inventory_cell, $inventoryVal);
            $dataArr = array();
            $clonedWorksheet->insertNewRowBefore($start_row_summary_sheet + 2, count($warehouseData));     
            foreach($warehouseData as $index => $line) {
                $tmpData= array(
                    $index + 1,
                    $line['item_code'],
                    $line['color'],
                    $line['size'],
                    $line['item_name'],
                    $line['unit'],
                    $line['tondau'],
                    $line['sl_nhap'],
                    $line['sl_xuat'],
                    $line['tondau'] + $line['sl_nhap'] - $line['sl_xuat'],
                    $line['item_type']
                );
                array_push($dataArr, $tmpData);
            }
            $clonedWorksheet->fromArray($dataArr, NULL, "A15");
            $spreadsheet->addSheet($clonedWorksheet);

            // Fill Detail sheet
            $clonedWorksheet = clone $spreadsheet->getSheetByName('Detail');
            $clonedWorksheet->setTitle("$warehouseName - Detail");
            $clonedWorksheet->setCellValue($this->from_to_cell, $datefromtoVal);
            $clonedWorksheet->setCellValue($this->inventory_cell, $inventoryVal);
            $dataArr = array();
            $detail_data = $detail_data_warehouse[$warehouseName];
            $clonedWorksheet->insertNewRowBefore($start_row_detail_sheet + 2, count($detail_data));
            foreach($detail_data as $index => $line) {
                $line['arrival_date'] = $line['arrival_date'] != ''? substr($line['arrival_date'],0,10): "";
                $isExportWaiting = (strpos($line['warehouse'],"_Cho xuat") !== false);
                $tmpData= array(
                    $index + 1,
                    $line['item_code'],
                    $line['color'],
                    $line['size'],
                    $line['item_name'],
                    $line['unit'],
                    $isExportWaiting ? '' : $line['tondau'],
                    $line['invoice_no'],
                    $line['arrival_date'],
                    $isExportWaiting ? '' :$line['sl_nhap'],
                    $isExportWaiting ? '' :$line['sl_xuat'],
                    $isExportWaiting ? '' :$line['tondau'] + $line['sl_nhap'] - $line['sl_xuat'],
                    $isExportWaiting ? '' :$line['sl_ok'],
                    $isExportWaiting ? '' :$line['sl_ng'],
                    $isExportWaiting ?$line['sl_ok'] : $line['sl_cho_xuat'],
                    $line['tondau'] + $line['sl_nhap'] - $line['sl_xuat'] - $line['sl_ng'] - $line['sl_cho_xuat'],
                    $line['warehouse']
                );
                array_push($dataArr, $tmpData);
            }
            $clonedWorksheet->fromArray($dataArr, NULL, "A15");
            $spreadsheet->addSheet($clonedWorksheet);
        }
        $spreadsheet->removeSheetByIndex($spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Summary')
        ));
        $spreadsheet->removeSheetByIndex($spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Detail')
        ));
        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="inventory.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    private function normalizeItemData($item){
        if(isset($item["item_code"])){
            $item_code = rawurldecode(rawurldecode(rawurldecode($item["item_code"])));
            $item["item_code"] =  $item_code!='{}'?$item_code:"";
        }
        if(isset($item["size"])){
            $size = rawurldecode(rawurldecode(rawurldecode($item["size"])));
            $item["size"] =  $size!='{}'?$size:"";
        }
        if(isset($item["color"])){
            $color = rawurldecode(rawurldecode(rawurldecode($item["color"])));
            $item["color"] =  $color!='{}'?$color:"";
        }
        if(isset($item["order_no"])){
            $order_no = rawurldecode(rawurldecode(rawurldecode($item["order_no"])));
            $item["order_no"] =  $order_no!='{}'?$order_no:"";            
        }
        if(isset($item["salesman"])){
            $salesman = rawurldecode($item["salesman"]);
            $item["salesman"] =  $salesman!='{}'?$salesman:"";
        }
        if(isset($item["item_type"])){
            $item_type = rawurldecode($item["item_type"]);
            $item["item_type"] =  $item_type!='{}'?$item_type:"";
        }
        if(isset($item["warehouse"])){
            $warehouse = rawurldecode($item["warehouse"]);
            $item["warehouse"] =  $warehouse!='{}'?$warehouse:"";
        }
        if(isset($item["newsalesman"])){
            $warehouse = rawurldecode($item["newsalesman"]);
            $item["newsalesman"] =  $warehouse!='{}'?$warehouse:"";
        }
        if(isset($item["newitem_type"])){
            $newitem_type = rawurldecode($item["newitem_type"]);
            $item["newitem_type"] =  $newitem_type!='{}'?$newitem_type:"";
        }
        if(isset($item["order_receive_no"])){
            $newitem_type = rawurldecode($item["order_receive_no"]);
            $item["order_receive_no"] =  $newitem_type!='{}'?$newitem_type:"";
        }
        return $item;
    }
}
