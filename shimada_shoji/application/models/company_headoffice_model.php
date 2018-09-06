<?php
class company_headoffice_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // insert company head office - create by: thanh
    public function insert($params)
    {
        $this->db->trans_begin();
        $query = $this->db->insert('m_company_headoffice', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $query;
    }

    // update company head office - create by: thanh
    public function update($params)
    {
        $this->db->trans_begin();
        $this->db->where('head_office_id', $params['head_office_id']);
        $this->db->where('company_id', $params['company_id']);
        unset($params['company_id']);
        unset($params['head_office_id']);
        $this->db->update('m_company_headoffice',$params);

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
        if(isset($params['head_office_id'])) {
            $this->db->where('head_office_id', $params['head_office_id']);
        }
        $this->db->update('m_company_headoffice', $data);

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
        $this->db->select("*");
        $this->db->from('m_company_headoffice');
        $this->db->where('del_flg','0');
        $this->db->where('company_id', $companyID);
        $this->db->order_by('head_office_id', 'ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    // get last head_office_id of company_id in table - create by: thanh
    public function getLastHeadOfficeID($params)
    {
        $this->db->select("company_id, head_office_id");
        $this->db->from('m_company_headoffice');
        $this->db->where('company_id', $params['company_id']);
        $this->db->order_by('head_office_id', 'desc');

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
     * @param string $head_office_id $company_id
     * @return object found company head office | null
     */
    public function check_headoffice_exists($params)
    {
        $this->db->select("1");
        $this->db->from('m_company_headoffice');
        $this->db->where('del_flg', '0');
        $this->db->where('head_office_id', $params['head_office_id']);
        $this->db->where('company_id', $params['company_id']);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>