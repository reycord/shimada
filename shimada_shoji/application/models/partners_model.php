<?php
class partners_model extends CI_Model
{

    public function __construct()
    {
        // parent::__construct();
        $this->load->database();
    }
    public function insertPartner($partner)
    {
        $this->db->insert('m_company', $partner);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return true;
        }
        return false;
    }
    public function deletePartner($id)
    {
        $this->db->where('company_id', $id);
        $this->db->delete('m_company');
    }
    public function updatePartner($partner)
    {
        $this->db->where('company_id', $partner['company_id']);
        $this->db->update('m_company', $partner);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return true;
        }
        return false;
    }

    public function getLastPartnerID()
    {
        $this->db->select("company_id");
        $this->db->from('m_company');
        $this->db->order_by('company_id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }
    public function getPartnerByID($id)
    {
        // $this->db->select("company_id, company_name, address, phone, fax, fixed_discount, add_user, add_date, update_user, update_date, del_flg");
		$this->db->select("*");
		$this->db->from('m_company');
        $this->db->where('company_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }
    public function getAllCustomer()
    {
        $this->db->select(" company_id
                            ,company_name
                            ,short_name
                            ,type
                            ,contract_type
                            ,reference
                            ,head_office_name
                            ,head_office_address
                            ,head_office_phone
                            ,head_office_tel
                            ,head_office_fax
                            ,head_office_contract_name
                            ,branch_name
                            ,branch_address
                            ,branch_phone
                            ,branch_tel
                            ,branch_fax
                            ,branch_contract_name
                            ,contract_from_date
                            ,contract_end_date
                            ,contract_end_flg
                            ,note
                            ,items_listtext
                            ,edit_date");
        $this->db->from('m_company');
		$this->db->where('del_flg', '0');
		$this->db->order_by('company_id', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    public function getAllCustomerAddress($customerId)
    {
        if (empty($customerId)) {
            return [];
        }
        $this->db->select("company_id, address_id, address, phone, fax");
        $this->db->from('m_company_address');
        $this->db->where('del_flg', false);
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    public function getAllSupplier()
    {
        $this->db->select("company_id, company_name, address, phone, fax, fixed_discount");
        $this->db->from('m_company');
        $this->db->where('kubun', 1);
        $this->db->where('del_flg', false);
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
}
