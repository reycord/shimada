<?php
class employee_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get user login info
    public function getLoginInfo($user_id)
    {
        $this->db->select("employee_id, password, first_name, last_name, position as permission_id, admin_flg, icon");
        $this->db->from('m_employee');
        $this->db->where('employee_id', $user_id);
        $this->db->where('active_flg', '0'); 
        // $this->db->where('status', '001'); 
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // get user login info
    public function getTheme($user_id)
    {
        $this->db->select("theme");
        $this->db->from('m_employee');
        $this->db->where('employee_id', $user_id);
        $this->db->where('active_flg', '0'); 
        // $this->db->where('status', '001'); 
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0]['theme'];
        }
        return null;
    }
    
    // get all employee actived
    public function getAllEmployee()
    {
        // $this->db->select("EM.employee_id, user_id, password, first_name, last_name, EM.department, EM.position, DE.department_id, PO.position_id, gender, birthday, address, phone, email, date_entry, status");
        $this->db->select("*");
        $this->db->from('m_employee EM');
        // $this->db->where('status',1);
        $this->db->where('EM.del_flg', '0');
        // $this->db->join('tbl_department DE', 'DE.department_id = EM.department', 'left');
        // $this->db->join('tbl_position PO', 'PO.position_id = EM.position', 'left');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    // search users - create by:thanh
    public function search($params = null, $start = null, $length = null, &$recordsTotal = null, &$recordsFiltered = null, $column_name=null, $sort=null)
    {
        $this->db->select('kubun, komoku_name_2');
        $this->db->from('m_komoku');
        $this->db->where('m_komoku.del_flg', '0');
        $this->db->where('m_komoku.komoku_id', KOMOKU_POSITION);
        $this->db->where('m_komoku.kubun <>', '000');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->select('kubun, komoku_name_2');
        $this->db->from('m_komoku');
        $this->db->where('m_komoku.del_flg', '0');
        $this->db->where('m_komoku.komoku_id', KOMOKU_DEPARTMENT);
        $this->db->where('m_komoku.kubun <>', '000');
        $kmk_department = $this->db->get_compiled_select();

        $this->db->from('m_employee a');
        $this->db->where('a.del_flg', '0');
        $this->db->join("($komoku_) b", 'a.position = b.kubun', 'left');
        $this->db->join("($kmk_department) c", 'a.department = c.kubun', 'left');

        $recordsTotal = $this->db->count_all_results(null, false);

        $this->db->select('a.employee_id, a.first_name, a.last_name, a.email_job, a.email_personal, a.phone, a.birthday, b.komoku_name_2 as position, c.komoku_name_2 as department, a.admin_flg, a.active_flg');
        if (isset($params['position'])) {
            $this->db->where('a.position', $params['position']);
        }
        if (isset($params['available_only'])) {
            $this->db->where('a.active_flg', $params['available_only']);
        }

        if(isset($column_name) && isset($sort)) {
            if($column_name == 'komoku_name_2'){
                $this->db->order_by('b.'.$column_name, $sort);
            } else {
                $this->db->order_by('a.'.$column_name, $sort);
            }
        } 

        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        return $query->result_array();
    }

    // get last employee id
    public function getLastEmployeeID()
    {
        $this->db->select("employee_id");
        $this->db->from('m_employee');
        $this->db->order_by('employee_id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // insert new employee - create by:thanh
    public function insertEmployee($data)
    {
        $this->db->trans_begin();
        $this->db->select("1");
        $this->db->from('m_employee');
        $this->db->where('employee_id', $data['employee_id']);
        $this->db->where('del_flg', '1');

        $check_exist = $this->db->get();
        if($check_exist->num_rows() > 0) {
            $this->db->where('employee_id', $data['employee_id']);
            $this->db->delete('m_employee');
            $this->db->insert('m_employee', $data);
        } else {
            $this->db->insert('m_employee', $data);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // update employee - create by:thanh
    public function updateEmployee($params)
    {
        $this->db->trans_begin();
        $this->db->where('employee_id', $params['employee_id']);
        $this->db->update('m_employee',$params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function delete_employee($params)
    {
        $this->db->trans_begin();
        $data = array(
            'edit_date' => $params['edit_date'],
            'edit_user' => $params['edit_user'],
            'del_flg' => '1',
        );
        $this->db->where('employee_id', $params['employee_id']);
        $this->db->update('m_employee', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function check_edit_date($params)
    {
        $this->db->select("1");
        $this->db->from('m_employee');
        $this->db->where('trim(employee_id)', trim($params['employee_id']));
        $this->db->where('edit_date', $params['edit_date']);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get employee info by employee_id
    public function getEmployeeByID($id)
    {
        // $this->db->select("EM.employee_id, user_id, password, first_name, last_name, EM.department, EM.position, DE.department_id, PO.position_id, gender, birthday, address, phone, email, date_entry, status");
        $this->db->select("*");
        $this->db->from('m_employee EM');
        // $this->db->where('status',1);
        $this->db->where('EM.employee_id', $id);
        $this->db->where('EM.del_flg', '0');
        // $this->db->join('tbl_department DE', 'DE.department_id = EM.department', 'left');
        // $this->db->join('tbl_position PO', 'PO.position_id = EM.position', 'left');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // get employee with name full - create by:thanh
    public function get_fullname_emp()
    {
        $this->db->select("CONCAT(m_employee.first_name,' ', m_employee.last_name) AS sales_man, employee_id");
        $this->db->from('m_employee');
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    // check old password when change password - create by:thanh
    public function check_old_password($params) {
        $this->db->select("employee_id, password, first_name, last_name");
        $this->db->from('m_employee');
        $this->db->where('employee_id', $params['employee_id']);
        $this->db->where('password', $params['password']);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // check user is exists? - create by:thanh
    public function check_user_exists($employee_id)
    {
        $this->db->select("1");
        $this->db->from('m_employee');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Created by Khanh
    // Date : 02/04/2018
    // Get all Salesman
    public function getAllUser($user_id = null){
        $this->db->select("employee_id, CONCAT(first_name,' ',last_name) as full_name");
        $this->db->from('m_employee');
        if(isset($user_id)){
            $this->db->where('employee_id', $user_id);
        }
        // $this->db->where_in('department', array('001','002'));
        $this->db->where('active_flg', '0');
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
}
