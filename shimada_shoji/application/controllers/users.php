<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('common');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('employee_model');
        $this->load->model('komoku_model');
    }
    // public function _remap($param) {
    //     $this->index($param);
    // }
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
        $currentUser = $this->session->userdata('user');
        $this->data['administrator'] = $currentUser['admin_flg'];
        $this->data['position_list'] = $this->komoku_model->get_all_position();
        $this->data['title'] = $this->lang->line('user_list');
        $this->data['screen_id'] = 'MTS0040';

        // Load the subview and Pass to the master view
        $content = $this->load->view('users/index.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $data = $this->employee_model->search();
        $dataArr = array();
        foreach($data as $item){
            $tmp = array(
                $item["employee_id"],
                $item["first_name"],
                $item["last_name"],
                $item["email_job"],
                $item["email_personal"],
                $item["department"],
                $item["position"],
                $item["phone"],
                $item["birthday"],
                $item["admin_flg"] == 1? '■':'',
                $item["active_flg"] == 1? '■':'',
            );
            array_push($dataArr, $tmp);
        }
        /** write to excel */
        require_once 'vendor/autoload.php';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.'views/users/template.xlsx');
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "B2");
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="users.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function search() 
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->get('param');
        $start = $this->input->get('start');
        $length = $this->input->get('length');
        $column_num = $this->input->get('order[0][column]');
        $column_name = $this->input->get('columns['.$column_num.'][data]');
        $sort = $this->input->get('order[0][dir]');

        // print_r($params);

        $data = $this->employee_model->search($params, $start, $length, $recordsTotal, $recordsFiltered, $column_name, $sort);
        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }
    public function add()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $currentUser = $this->session->userdata('user');
        $this->data['administrator'] = $currentUser['admin_flg'];
        $this->data['position_list'] = $this->komoku_model->get_all_position();
        $this->data['department_list'] = $this->komoku_model->get_all_department();
        // $this->data['classify_list'] = $this->komoku_model->get_all_classify();
        $this->data['title'] = $this->lang->line('add_new_user');
        $this->data['type'] = '0';
        $this->data['screen_id'] = 'MTS0050';
        $content = $this->load->view('users/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }
    // add new user
    public function save()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $currentUser = $this->session->userdata('user');
        $currentDate = date('Y/m/d H:i:s');
        // get last ID in database
        // $lastID = $this->employee_model->getLastEmployeeID();
        // $lastID = ($lastID == null ? 'EM00000000' : $lastID['employee_id']); 
        // insert flag = 0; update flag = 1
        $flag = 0;
        
        $employeeID = $this->input->post('employee_id');
        $old_edit_date = $this->input->post('edit_date');
        
        $type = $this->input->post('type');
        if($type === '1') {
            $flag = 1;
        }

        // if($flag == 0){
            // $employeeID = $this->common->generateID($lastID);
        // }
        // set data to insert
        $params = array(
            'employee_id'       => trim($employeeID),
            'birthday'          => $this->input->post('birthday'),
            'first_name'        => $this->input->post('first_name'),
            'last_name'         => $this->input->post('last_name'),
            'first_name_kana'   => $this->input->post('first_name_kana'),
            'last_name_kana'    => $this->input->post('last_name_kana'),
            'email_job'         => $this->input->post('company_email'),
            'email_personal'    => $this->input->post('personal_email'),
            'phone'             => $this->input->post('mobile'),
            'tel'               => $this->input->post('telephone'),
            'postal_code'       => $this->input->post('postal_code'),
            'gender'            => $this->input->post('gender'),
            'address'           => $this->input->post('address'),
            'department'        => $this->input->post('department'),
            'position'          => $this->input->post('position'),
            'entry_date'        => $this->input->post('date_entry'),
            'active_flg'        => $this->input->post('retire'),
            'admin_flg'         => $this->input->post('admin'),
            'retire_date'       => $this->input->post('retirement_date'),
            'theme'             => $this->input->post('theme'),
            'note'              => $this->input->post('free_writing'),
            'icon'              => $this->input->post('icon')
        );
        $result = false;
        // insert or update
        if($flag == 0){
            foreach($params as $key => $value){
                if($value == null || $value == '') {unset($params[$key]);}
            }
            $params['create_user'] = $currentUser['employee_id'];
            $params['create_date'] = $currentDate;
            $params['permission_id'] = null;
            $password = $this->input->post('confirm_password');
            $params['password'] = password_hash($password, PASSWORD_HASH_METHOD);
            $result = $this->employee_model->insertEmployee($params);
        } else {
            if($flag == 1){
                $edit_date = null;
                if($old_edit_date != '' || $old_edit_date != null) {
                    $edit_date = $old_edit_date;
                }
                $data_check = array(
                    'employee_id' => $employeeID,
                    'edit_date' => $edit_date,
                );

                $result = $this->employee_model->check_edit_date($data_check);
                if(!$result) {
                    $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
                    return redirect(base_url('users/edit/'.$employeeID));
                }
                foreach($params as $key => $value){
                    if($value == '') {$params[$key] = null;}
                }
                $params['edit_user'] = $currentUser['employee_id'];
                $params['edit_date'] = $currentDate;
                $result = $this->employee_model->updateEmployee($params);
            }
        }
        if($result) {
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            $this->session->set_flashdata('data', json_encode($employeeID));
            $employee = $this->employee_model->getLoginInfo($currentUser['employee_id']);
            $this->session->set_userdata('user', $employee);
            redirect(base_url('users'));
        } else {
            $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
            if ($flag == 0) {
                redirect(base_url('users/add'));
            } else if ($flag == 1) {
                redirect(base_url('users/edit/'.$employeeID));
            }
        }
    }
    
    public function edit($id)
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $employee = $this->employee_model->getEmployeeByID($id);
        if($employee == null){
            redirect(base_url().'users');
        }
        $currentUser = $this->session->userdata('user');
        $this->data['administrator'] = $currentUser['admin_flg'];
        $this->data['position_list'] = $this->komoku_model->get_all_position();
        $this->data['department_list'] = $this->komoku_model->get_all_department();
        // $this->data['classify_list'] = $this->komoku_model->get_all_classify();
        $this->data['title'] = $this->lang->line('edit_user');
        $this->data['type'] = '1';
        if($currentUser['admin_flg'] != '1' && $currentUser['employee_id'] !== $employee['employee_id']){
            $this->data['type'] = '2';
            $this->data['change_pass_flg'] = '1';
        }
        $this->data['employee'] = $employee;
        $this->data['screen_id'] = 'MTS0050';

        // Load the subview
        $content = $this->load->view('users/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }
    public function profile($user)
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        // echo $user; 
        $employee = $this->employee_model->getEmployeeByID($user);
        if($employee == null){
            show_error('', 403);
        }

        $this->data['title'] = $this->lang->line('profile');
        $this->data['position_list'] = $this->komoku_model->get_all_position();
        $this->data['department_list'] = $this->komoku_model->get_all_department();
        $this->data['classify_list'] = $this->komoku_model->get_all_classify();
        $this->data['type'] = '2';
        $this->data['employee'] = $employee;

        // Load the subview and Pass to the master view
        $content = $this->load->view('users/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }
    public function change_password()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        
        $currentUser = $this->session->userdata('user');
        $employeeID = $this->input->post('employee_id');
        $new_password = $this->input->post('new_password');
        $currentDate = date('Y/m/d H:i:s');
        $params = array(
            'employee_id' => $employeeID,
            'password' => password_hash($new_password, PASSWORD_HASH_METHOD),
            'edit_user' => $currentUser['employee_id'],
            'edit_date' => $currentDate,
            'pw_update_date' => $currentDate,
        );
        $result = $this->employee_model->updateEmployee($params);

        if($result) {
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            redirect(base_url('users'));
        } else {
            $this->edit($employeeID);
        }
    }
    public function details($id)
    {
        if ($this->is_logged_in()) {
            $employee = $this->employee_model->getEmployeeByID($id);
            if($employee == null){
                redirect(base_url().'users');
            }
            // $departmentList = $this->department_model->getAllDepartment();
            // set variables
            // $this->data['departmentList'] = $departmentList;
            $this->data['type'] = '2';
            $this->data['employee'] = $employee;
            $this->data['title'] = 'User Details';

            // Load the subview
            $content = $this->load->view('users/add.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
    // check user already exist in database
    function check_exist_user_id()
    {
        $params = $this->input->post();
        $result = $this->employee_model->check_user_exists($params['employee_id']);
        if($result) {
            echo json_encode($this->_response(false, 'user_exist'));
        } else {
            echo json_encode($this->_response(true));
        }
    }
    function check_password()
    {
        $employee_id = $this->input->get('userID');
        $employee = $this->employee_model->getLoginInfo($employee_id);
        $password = $this->input->get('oldPassword');
        if (!password_verify($password, $employee['password'])) {
            echo json_encode($this->_response(false, 'old_pass_incorrect'));
        } else {
            echo json_encode($this->_response(true));
        }
    }
    function delete_user()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $currentUser = $this->session->userdata('user');
        $currentDate = date('Y/m/d H:i:s');

        $employee_id = $this->input->post('employee_id');
        $old_edit_date = $this->input->post('edit_date');

        // check edit date
        $edit_date = null;
        if($old_edit_date != '' || $old_edit_date != null) {
            $edit_date = $old_edit_date;
        }
        $data_check = array(
            'employee_id' => $employee_id,
            'edit_date' => $edit_date,
        );

        $result = $this->employee_model->check_edit_date($data_check);
        if(!$result) {
            $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
            return redirect(base_url('users/edit/'.$employee_id));
        } else {
            $params = array(
                'employee_id' => $employee_id,
                'edit_user' => $currentUser['employee_id'],
                'edit_date' => $currentDate,
            );
            
            $query = $this->employee_model->delete_employee($params);
            if($query) {
                $this->session->set_flashdata('success_msg', $this->lang->line('del_success'));
                redirect(base_url('users'));
            } else {
                $this->session->set_flashdata('error_msg', $this->lang->line('del_fail'));
                redirect(base_url('users/edit/'.$employee_id));
            }
        }

    }

    /** Set class name for body master page */
    public function setBodyClass()
    {
        $body_class = $this->input->get("bodyClass");
        $this->session->set_userdata('body_class', $body_class);
        echo json_encode($body_class);
    }
}
