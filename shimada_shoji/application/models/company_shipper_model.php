<?php
class company_shipper_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert($params)
    {
        $this->db->trans_begin();
        $query = $this->db->insert('m_company_shipper', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $query;
    }
    public function update($params=null)
    {
        $this->db->trans_begin();
        $this->db->where('shipper_id', $params['shipper_id']);
        $this->db->where('company_id', $params['company_id']);
        unset($params['shipper_id']);
        unset($params['company_id']);
        $this->db->update('m_company_shipper', $params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function getShipperList($company_id){
        $this->db->select('shipper_id, shipper_name');
        $this->db->from('m_company_shipper');
        $this->db->where('del_flg', '0');
        $this->db->where('company_id', $company_id);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function getLastShipperID($params)
    {
        $this->db->select("company_id, shipper_id");
        $this->db->from('m_company_shipper');
        $this->db->where('company_id', $params['company_id']);
        $this->db->order_by('shipper_id', 'desc');

        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return $result[0];
        } else {
            return FALSE;
        }
    }
    public function check_shipper_exists($params)
    {
        $this->db->select("1");
        $this->db->from('m_company_shipper');
        $this->db->where('del_flg', '0');
        $this->db->where('shipper_id', $params['shipper_id']);
        $this->db->where('company_id', $params['company_id']);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
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
        if(isset($params['shipper_id'])) {
            $this->db->where('shipper_id', $params['shipper_id']);
        }
        $this->db->update('m_company_shipper', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function get_by_companyid($companyID)
    {
        $this->db->select(" company_id
                            ,shipper_id
                            ,shipper_name
                            ,shipper_address
                            ,'' as shipper_address_vn
                            ,shipper_phone
                            ,shipper_tel
                            ,shipper_fax
                            ,shipper_contract_name
                            ,'' as shipper_position
                            ,contract_from_date
                            ,contract_end_date
                            ,contract_end_flg
                            ,note
                            ,edit_date
                        ");
        $this->db->from('m_company_shipper');
        $this->db->where('del_flg','0');
        $this->db->where('company_id', $companyID);
        $this->db->order_by('shipper_id', 'ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
}
