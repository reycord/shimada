<?php
class company_branch_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // insert company branch - create by: thanh
    public function insert($params)
    {
        $this->db->trans_begin();
        $query = $this->db->insert('m_company_branch', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $query;
    }

    // update company branch - create by: thanh
    public function update($params=null)
    {
        $this->db->trans_begin();
        $this->db->where('branch_id', $params['branch_id']);
        $this->db->where('company_id', $params['company_id']);
        unset($params['branch_id']);
        unset($params['company_id']);
        $this->db->update('m_company_branch', $params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // delete head office - create by: thanh
    public function delete($params=null)
    {
        $this->db->trans_begin();
        $data = array(
            'del_flg' => '1',
            'edit_user' => $params['edit_user'],
            'edit_date' => $params['edit_date']
        );

        if(isset($params['company_id'])) {
            $this->db->where('company_id', $params['company_id']);
        } 
        if(isset($params['branch_id'])) {
            $this->db->where('branch_id', $params['branch_id']);
        }
        $this->db->update('m_company_branch', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    } 

    // get company head office by company_id
    public function get_by_companyid($companyID)
    {
        $this->db->select(" company_id
                            ,branch_id
                            ,branch_name
                            ,branch_address
                            ,branch_address_vn
                            ,branch_phone
                            ,branch_tel
                            ,branch_fax
                            ,branch_contract_name
                            ,branch_position
                            ,branch_transportation
                            ,branch_tax_code
                            ,contract_from_date
                            ,contract_end_date
                            ,contract_end_flg
                            ,note
                            ,edit_date
                        ");
        $this->db->from('m_company_branch');
        $this->db->where('del_flg','0');
        $this->db->where('company_id', $companyID);
        $this->db->order_by('branch_id', 'ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    // get last head_office_id of company_id in table - create by: thanh
    public function getLastBranchID($params)
    {
        $this->db->select("company_id, branch_id");
        $this->db->from('m_company_branch');
        $this->db->where('company_id', $params['company_id']);
        $this->db->order_by('branch_id', 'desc');

        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return $result[0];
        } else {
            return FALSE;
        }
    }
    /**
     * Check company head office is exists ? - create by: thanh
     *
     * @param string $branch_id $company_id
     * @return object found company head office | null
     */
    public function check_branch_exists($params)
    {
        $this->db->select("1");
        $this->db->from('m_company_branch');
        $this->db->where('del_flg', '0');
        $this->db->where('branch_id', $params['branch_id']);
        $this->db->where('company_id', $params['company_id']);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Get branch by company_id
     * 
     * @author Ngoc Thanh
     * @return branch_id
     * @return branch_name
     */
    public function getBranchByCompanyID($params){
        $this->db->select("BR.branch_id, BR.branch_name");
        $this->db->from("m_company_branch BR");
        $this->db->join('m_company COM', 'BR.company_id = COM.company_id', 'left');
        $this->db->where('COM.short_name', $params['note1']);
        $this->db->where('BR.del_flg','0');
        $this->db->order_by('BR.branch_name', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }
}
?>