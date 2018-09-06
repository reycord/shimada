<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners extends MY_Controller 
{
	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('common');
        $this->load->model('company_model');
        $this->load->model('komoku_model');
        $this->load->model('items_model');
        $this->load->model('company_headoffice_model');
        $this->load->model('company_branch_model');
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
        $this->data['title'] = $this->lang->line('partner_list');
        $this->data['items_list'] = $this->items_model->getItemDistinct();
        $this->data['screen_id'] = 'MTS0060';

        // Pass to the master view
        $content = $this->load->view('partners/index.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }
    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $data = $this->company_model->search_partners_for_excel();
        $dataArr = array();
        foreach($data as $i=>$item){
            $tmp = array(
                ($i + 1),
                $item["company_id"],
                $item["company_name"],
                $item["short_name"],
                $item["head_office_id"],
                $item["head_office_address"],
                $item["head_office_name"].PHP_EOL.$item["head_office_address"],
                $item["shipper_id"],
                $item["shipper_name"].PHP_EOL.$item["shipper_address"],
                $item["transportation"],
                $item["payment_term"],
                $item["payment"],
                $item["reference"],
                $item["bank_info"]
            );
            array_push($dataArr, $tmp);
        }
        /** write to excel */
        require_once 'vendor/autoload.php';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.'views/partners/template.xlsx');
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "A3");
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="partners.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    // Search Partners from m_company table - create by: thanh
    public function search() 
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $data = $this->company_model->search_partners();
        foreach($data as $key => $value) {
            $data[$key]['list_explode'] = explode(",", $value['items_list']);
        }
        echo json_encode(array(
            'data' => $data
        ));
    }

    // Save partners into m_company table - create by: thanh
    public function save()
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");
        // set flag
        $flag = 0;

        // get company_id and type from view
        $companyID = $this->input->post('company_id');
        $old_edit_date = $this->input->post('edit_date');
        $type = $this->input->post('type');

        // set flag = 1 if type = 1
        if($type === '1') {
            $flag = 1;
        }
        
        $items = json_decode($this->input->post('items'));
        $this->db->trans_begin();

        $item_codes = array_map(
            function($item){
                return $item->item_code;
            }, 
            $items
        );
        $params = array(
            'company_id'                    => $companyID,
            'company_name'                  => $this->input->post('company_name'),
            'contract_type'                 => $this->input->post('contract_type'),
            'short_name'                    => $this->input->post('short_name'),
            'contract_from_date'            => $this->input->post('contract_from_date'),
            'contract_end_date'             => $this->input->post('contract_end_date'),
            'contract_end_flg'              => $this->input->post('contract_end_flg'),
            'note'                          => $this->input->post('note'),
            'reference'                     => $this->input->post('reference'),
            'transportation'                => $this->input->post('transportation'),
            'payment_term'                  => $this->input->post('payment_term'),
            'type'                          => '2',
            'items_list'                    => implode(',', $item_codes),
        );
        $result = false;
        
        // insert into m_company table or update from m_company table
        if($flag == 0) {
            foreach ($params as $key => $value) {
                if ($value==null || $value== '') {unset($params[$key]);}
            }
            $params['create_user'] = $currentUser['employee_id'];
            $params['create_date'] = $currentDate;
            $result = $this->company_model->insert($params);
        } else {
            if($flag == 1) {
                $edit_date = null;
                if($old_edit_date != '' || $old_edit_date != null) {
                    $edit_date = $old_edit_date;
                }
                $data_check = array(
                    'company_id' => $companyID,
                    'edit_date' => $edit_date,
                );
                $result = $this->company_model->check_edit_date($data_check);
                if(!$result) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
                    return redirect(base_url('partners/edit/'.$companyID));
                }
                foreach($params as $key => $value){
                    if($value == '') {$params[$key] = null;}
                }
                $params['edit_user'] = $currentUser['employee_id'];
                $params['edit_date'] = $currentDate;
                $result = $this->company_model->update($params);
            }
        }

        // check query insert, update
        if($result == FALSE){
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
            if($flag == 0) {
                redirect(base_url('partners/add'));
            } else {
                if($flag == 1) {
                    redirect(base_url('partners/edit/'.$companyID));
                }
            }
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            $this->session->set_flashdata('data', json_encode($this->input->post('company_name')));
            redirect(base_url('partners'));
        }
    }

    // Add new partners - create by: thanh
    public function add()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $lastID = $this->company_model->getLastID();
        $lastID = ($lastID == null ? '0' : $lastID['company_id']);
        $companyID = $this->common->generateID($lastID); 

        $this->data['screen_id'] = 'MTS0070';
        $this->data['title'] = $this->lang->line('add_new_partner');
        $this->data['type'] = '0';
        $this->data['contract_type_list'] = $this->komoku_model->get_all_contract();
        $this->data['items_list'] = $this->items_model->getItemDistinct();
        $this->data['partners'] = array('company_id' => $companyID);
        $this->data['payment_term_list'] = $this->komoku_model->get_payment_term_list();
        
        // Load the subview and Pass to the master view
        $content = $this->load->view('partners/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    // Edit partners when click from search page - create by: thanh
    public function edit($companyID)
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $partners_current = $this->company_model->getPartnerByID($companyID);
        $items_list = $partners_current['items_list'];
        $this->data['data_items'] = $this->items_model->getByItemsList($items_list);  
        $this->data['title'] = $this->lang->line('edit_partner');
        $this->data['partners'] = $partners_current;
        $this->data['contract_type_list'] = $this->komoku_model->get_all_contract();
        $this->data['items_list'] = $this->items_model->getItemDistinct();
        $this->data['type'] = '1';
        $this->data['head_office_list'] = $this->company_headoffice_model->get_by_companyid($companyID);
        $this->data['branch_list'] = $this->company_branch_model->get_by_companyid($companyID);
        $this->data['shipper_list'] = $this->company_shipper_model->get_by_companyid($companyID);
        $this->data['payment_term_list'] = $this->komoku_model->get_payment_term_list();

        $this->data['screen_id'] = 'MTS0070';

        // Load the subview and Pass to the master view
        $content = $this->load->view('partners/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    // Delete partners - create by: thanh
    public function delete()
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        
        $companyID = $this->input->post('company_id');
        $old_edit_date = $this->input->post('edit_date');
        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");
        $this->db->trans_begin();

        // check partners is exists and real time ?
        $edit_date = null;
        if($old_edit_date != '' || $old_edit_date != null) {
            $edit_date = $old_edit_date;
        }
        $data_check = array(
            'company_id' => $companyID,
            'edit_date' => $edit_date,
        );
        $result = $this->company_model->check_edit_date($data_check);
        if(!$result) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
            return redirect(base_url('partners/edit/'.$companyID));
        } else {
            $params = array(
                'edit_user' => $currentUser['employee_id'],
                'edit_date' => $currentDate,
                'company_id' => $companyID,
            );

            // Query Delete Partners 
            $query = $this->company_model->delete($params);
            if($query) {

                // Query delete head office in m_company_headoffice table
                $query_del_head = $this->company_headoffice_model->delete($params);
                if($query_del_head) {

                    // Query delete branch in m_company_branch table
                    $query_del_branch = $this->company_branch_model->delete($params);
                    if($query_del_branch) {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('success_msg', $this->lang->line('del_success'));
                        redirect(base_url('partners'));
                    } else {
                        $this->db->trans_rollback();
                    }
                } else {
                    $this->db->trans_rollback();
                }
            } else {
                $this->db->trans_rollback();
            }
            $this->session->set_flashdata('error_msg', $this->lang->line('del_fail'));
            redirect(base_url('partners/edit/'.$companyID));
        }
    }

    // check partners already exist in database - create by: thanh
    function check_partners_exists() 
    {
        $params = $this->input->post();
        $result = $this->company_model->check_exists($params['company_id']);
        if($result) {
            echo json_encode($this->_response(false, 'partner_exist'));
        } else {
            echo json_encode($this->_response(true));
        }
    }

    // Insert or update company head office
    public function save_head_branch()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        // set current user 
        $currentUser = $this->session->userdata('user');
        $query = false;
        
        $data_head = array(
            'company_id' => $params['company_id'],
            'head_office_address' => $params['address'],
            'head_office_id' => $params['id'],
            'head_office_name' => $params['name'],
            'head_office_phone' => $params['phone'],
            'head_office_tel' => $params['tel'],
            'head_office_fax' => $params['fax'],
            'head_office_contract_name' => $params['contract_name'],
        );
        $data_branch = array(
            'company_id' => $params['company_id'],
            'branch_address' => $params['address'],
            'branch_id' => $params['id'],
            'branch_name' => $params['name'],
            'branch_phone' => $params['phone'],
            'branch_tel' => $params['tel'],
            'branch_fax' => $params['fax'],
            'branch_contract_name' => $params['contract_name'],
        );
        $data_shipper = array(
            'company_id' => $params['company_id'],
            'shipper_address' => $params['address'],
            'shipper_id' => $params['id'],
            'shipper_name' => $params['name'],
            'shipper_phone' => $params['phone'],
            'shipper_tel' => $params['tel'],
            'shipper_fax' => $params['fax'],
            'shipper_contract_name' => $params['contract_name'],
        );
        if(isset($params['add_mode']) && $params['add_mode'] == true) {

            // Query Insert 
            if($params['type'] == '0') {

                $data_head['create_date'] = date('Y/m/d H:i:s');
                $data_head['create_user'] = $currentUser['employee_id'];
                foreach($data_head as $key=>$value) {
                    if($value == '' || $value == null) {unset($data_head[$key]);}
                }

                $query = $this->company_headoffice_model->insert($data_head);
            } else {
                if($params['type'] == '1') {
                    
                    $data_branch['create_date'] = date('Y/m/d H:i:s');
                    $data_branch['create_user'] = $currentUser['employee_id'];
                    foreach($data_branch as $key=>$value) {
                        if($value == '' || $value == null) {unset($data_branch[$key]);}
                    }
                    $query = $this->company_branch_model->insert($data_branch);
                } else {
                    if($params['type'] == '2') {
                        $data_shipper['create_date'] = date('Y/m/d H:i:s');
                        $data_shipper['create_user'] = $currentUser['employee_id'];
                        foreach($data_shipper as $key=>$value) {
                            if($value == '' || $value == null) {unset($data_shipper[$key]);}
                        }
                        $query = $this->company_shipper_model->insert($data_shipper);
                    }
                }
            }
            
        } else {

            // Query update 
            if($params['type'] == '0') {
                $data_head['edit_date'] = date('Y/m/d H:i:s');
                $data_head['edit_user'] = $currentUser['employee_id'];
                foreach($data_head as $key => $value){
                    if($value == '') {$data_head[$key] = null;}
                }
                $query = $this->company_headoffice_model->update($data_head);
            } else {
                if($params['type'] == '1') {
                    $data_branch['edit_date'] = date('Y/m/d H:i:s');
                    $data_branch['edit_user'] = $currentUser['employee_id'];
                    foreach($data_branch as $key => $value){
                        if($value == '') {$data_branch[$key] = null;}
                    }
                    $query = $this->company_branch_model->update($data_branch);
                } else {
                    if($params['type'] == '2') {
                        $data_shipper['edit_date'] = date('Y/m/d H:i:s');
                        $data_shipper['edit_user'] = $currentUser['employee_id'];
                        foreach($data_shipper as $key => $value){
                            if($value == '') {$data_shipper[$key] = null;}
                        }
                        $query = $this->company_shipper_model->update($data_shipper);
                    }
                }
            }
        }
        if($query) {
            echo json_encode($this->_response(true,'save_success'));
        } else {
            echo json_encode($this->_response(false,'save_fail'));
        }
    }

    // get last head office of company id
    public function get_last_id()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $result = false;

        if($params['type'] == '0') {
            $result = $this->company_headoffice_model->getLastHeadOfficeID($params);
            if($result) {
                $result['type'] = '0';
            }
        } else {
            if($params['type'] == '1') {
                $result = $this->company_branch_model->getLastBranchID($params);
                if($result) {
                    $result['type'] = '1';
                }
            }  
            if($params['type'] == '2') {
                $result = $this->company_shipper_model->getLastShipperID($params);
                if($result) {
                    $result['type'] = '2';
                }
            }
        }
        if($result) {
            echo json_encode($this->_response(true, null, $result));
        } else {
            echo json_encode($this->_response(false));
        }
    }

    // Delete company head office
    public function delete_head_branch()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $result = false;
        if($params['type'] == '0') {
            unset($params['type']);
            // check head office is exists ? 
            $check_head = $this->company_headoffice_model->check_headoffice_exists($params);
            if(!$check_head) {
                echo json_encode($this->_response(false, 'JOS0010_E002'));
            } 

            $params['edit_date'] = date('Y/m/d H:i:s');
            $params['edit_user'] =  $currentUser['employee_id'];

            // query delete head office
            $result = $this->company_headoffice_model->delete($params);
        } else {
            if($params['type'] == '1') {
                unset($params['type']);
                // check head office is exists ? 
                $check_branch = $this->company_branch_model->check_branch_exists($params);
                if(!$check_branch) {
                    echo json_encode($this->_response(false, 'JOS0010_E002'));
                } 

                $params['edit_date'] = date('Y/m/d H:i:s');
                $params['edit_user'] =  $currentUser['employee_id'];

                // query delete head office
                $result = $this->company_branch_model->delete($params);
            } else {
                if($params['type'] == '2') {
                    unset($params['type']);
                    // check head office is exists ? 
                    $check_shipper = $this->company_shipper_model->check_shipper_exists($params);
                    if(!$check_shipper) {
                        echo json_encode($this->_response(false, 'JOS0010_E002'));
                    } 

                    $params['edit_date'] = date('Y/m/d H:i:s');
                    $params['edit_user'] =  $currentUser['employee_id'];

                    // query delete head office
                    $result = $this->company_shipper_model->delete($params);
                }
            }
        }
    
        // check query
        if($result) {
            echo json_encode($this->_response(true,'del_success'));
        } else {
            echo json_encode($this->_response(false,'del_fail'));
        }
    }

    // get item list by company_id
    public function get_item_list()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $result = $this->items_model->getByItemsList($params['items_list']);
        echo json_encode($result);
    }

    // update items list from search partners page
    public function update_item_list()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        // get current login user
        $currentUser = $this->session->userdata('user');
        // get current date
        $currentDate = date("Y/m/d H:i:s");

        $items = $params['item_code'];
        $company_id = $params['company_id'];

        if($items == "") {
            $item_code = null;
        } else {
            $item_codes = array_map(
                function($item){
                    return $item['item_code'];
                }, 
                $items
            );
            $item_code = implode(',', $item_codes);
        }
        
        
        $data = array(
            'company_id' => $company_id,
            'items_list' => $item_code,
            'edit_user' => $currentUser['employee_id'],
            'edit_date' => $currentDate,
        );        
        
        $result = $this->company_model->update($data);
        if($params['mode'] == '1') {
            if($result) {
                echo json_encode($this->_response(true, 'del_success'));
            } else {
                echo json_encode($this->_response(false, 'del_fail'));
            }
        } else {
            if($params['mode'] == '0') {
                if($result) {
                    echo json_encode($this->_response(true, 'add_success'));
                } else {
                    echo json_encode($this->_response(false, 'add_fail'));
                }
            }
        }
    }
}
