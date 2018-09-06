<?php
class company_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllCustomer()
    {
        $this->db->select('komoku_id, kubun, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_VAT_BY);
        $this->db->where('del_flg', '0');
        $vat_by_ = $this->db->get_compiled_select();

        $this->db->select('komoku_id, kubun, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_FEE_TERM);
        $this->db->where('del_flg', '0');
        $fee_term_ = $this->db->get_compiled_select();

        $this->db->select('komoku_id, kubun, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('del_flg', '0');
        $payment_term_ = $this->db->get_compiled_select();

        $this->db->select(" company_id
							,company_name
                            ,short_name
                            ,transportation as delivery_term
                            ,COM.vat_by as vat_by_code
                            ,a.komoku_name_3 as vat_by
                            ,COM.fee_term as fee_term_code
                            ,b.komoku_name_3 as fee_term
                            ,COM.payment_term as payment_term_code
                            ,c.komoku_name_3 as payment_term
                            ,COM.edit_date as edit_date"); 
        $this->db->from('m_company COM');
        $this->db->join("($vat_by_) a", 'COM.vat_by = a.kubun', 'left');
        $this->db->join("($fee_term_) b", 'COM.fee_term = b.kubun', 'left');
        $this->db->join("($payment_term_) c", 'COM.payment_term = c.kubun', 'left');
        $this->db->where('del_flg','0');
        $this->db->where('type','1');
        $this->db->order_by('company_name','ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    // Created by Khanh
    // Date : 02/04/2018
    // get all partner 
    public function getAllVendor() 
    {
        $this->db->select('short_name');
        $this->db->from('m_company');
        $this->db->where('del_flg', '0');
        $this->db->where('type','2');
        $this->db->order_by('short_name','ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    public function getAllSupplier() 
    {
        // $this->db->distinct('CH.company_id');
        $this->db->select('DISTINCT ON ("COM"."short_name") "COM"."company_name", COM.company_id,  COM.short_name, COM.reference, CH.head_office_name, CH.head_office_address, CH.head_office_phone, CH.head_office_tel, CH.head_office_fax');
        $this->db->from('m_company COM');
        $this->db->join('m_company_headoffice CH',"COM.company_id = CH.company_id and CH.del_flg = '0'",'left');
        $this->db->where('COM.del_flg', '0');
        $this->db->where('COM.type','2');
        $this->db->order_by('COM.short_name','ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    // get partners by company_id
    public function getPartnerByID($companyID) 
    {
        $this->db->select('*');
        $this->db->from('m_company');
        $this->db->where('company_id', $companyID);
        $this->db->where('del_flg', '0');
        $this->db->where('type','2');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // get customers by company_id
    public function getCustomerByID($companyID) 
    {
        $this->db->select('*');
        $this->db->from('m_company');
        $this->db->where('company_id', $companyID);
        $this->db->where('del_flg', '0');
        $this->db->where('type','1');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // get all partners - create by: thanh
    public function search_partners() 
    {
        $this->db->select("company_id, company_name, short_name, reference, items_list, contract_from_date, contract_end_date, type"); 
        $this->db->from('m_company a');
        $this->db->where('a.del_flg','0');
        $this->db->where('a.type','2');  
        $this->db->order_by('company_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    // get all partners - create by: thanh
    public function search_partners_for_excel() 
    {
        $this->db->select('komoku_id, kubun, komoku_name_2');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_CONTRACTTYPE);
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->select('komoku_id, kubun, komoku_name_3 as payment');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('del_flg', '0');
        $komoku_payment = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('company_id as id, head_office_id, head_office_name, head_office_address');
        $this->db->from('m_company_headoffice');
        $this->db->where('del_flg', '0');
        $head_office = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('company_id as id, shipper_id, shipper_name, shipper_address');
        $this->db->from('m_company_shipper');
        $this->db->where('del_flg', '0');
        $shipper = $this->db->get_compiled_select();

        $this->db->select("a.*, hea.*, c.*, shi.*"); 
        $this->db->from('m_company a');
        $this->db->join("($komoku_) b", 'a.contract_type = b.kubun', 'left');
        $this->db->join("($komoku_payment) c", 'a.payment_term = c.kubun', 'left');
        $this->db->join("($head_office) hea", 'a.company_id = hea.id', 'left');
        $this->db->join("($shipper) shi", 'a.company_id = shi.id', 'left');
        $this->db->where('a.del_flg','0');
        $this->db->where('a.type','2');  
        $this->db->order_by('company_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }
    public function search_customer() 
    {
        $this->db->select('komoku_id, kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_CONTRACTTYPE);
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();
        $this->db->select("a.*, b.*"); 
        $this->db->from('m_company a');
        $this->db->join("($komoku_) b", 'a.contract_type = b.kubun', 'left');
        $this->db->where('a.del_flg','0');
        $this->db->where('a.type','1');  
        $this->db->order_by('company_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function search_customer_for_excel()
    {
        $this->db->select('komoku_id, kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_CONTRACTTYPE);
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('company_id as id, head_office_id, head_office_name, head_office_address');
        $this->db->from('m_company_headoffice');
        $this->db->where('del_flg', '0');
        $head_office = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('company_id as id, branch_id, branch_name, branch_address, note as branch_note');
        $this->db->from('m_company_branch');
        $this->db->where('del_flg', '0');
        $branch = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('company_id as id, shipper_id, shipper_name, shipper_address');
        $this->db->from('m_company_shipper');
        $this->db->where('del_flg', '0');
        $shipper = $this->db->get_compiled_select();

        $this->db->select("a.*, b.* , hea.* , bra.*, shi.*"); 
        $this->db->from('m_company a');
        $this->db->join("($komoku_) b", 'a.contract_type = b.kubun', 'left');
        $this->db->join("($head_office) hea", 'a.company_id = hea.id', 'left');
        $this->db->join("($branch) bra", 'a.company_id = bra.id', 'left');
        $this->db->join("($shipper) shi", 'a.company_id = shi.id', 'left');
        $this->db->where('a.del_flg','0');
        $this->db->where('a.type','1');  
        $this->db->order_by('company_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllBranchOfCompany($companyId) {
        $this->db->select("company_id,branch_id,branch_name,branch_address,branch_transportation as delivery_term"); 
        $this->db->from('m_company_branch');
        $this->db->where('del_flg','0');
        $this->db->where('company_id',$companyId);
        $this->db->order_by('branch_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllHeadOfficeOfCompany($companyId) {
        $this->db->select("company_id,head_office_id,head_office_name,head_office_address"); 
        $this->db->from('m_company_headoffice');
        $this->db->where('del_flg','0');
        $this->db->where('company_id',$companyId);
        $this->db->order_by('head_office_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get Company Name
     * 
     * @author Duc Tam
     * @return company_id
     * @return company_name
     */
    public function getAllCompanyName(){
        $this->db->select("company_id,company_name");
        $this->db->from("m_company");
        $this->db->where("type","1");
        $this->db->where('del_flg','0');

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get all short name
     * 
     * @author Ngoc Thanh
     * @return company_id
     * @return short_name
     */
    public function getAllShortName(){
        $this->db->select("company_id, short_name");
        $this->db->from("m_company");
        $this->db->where("type","1");
        $this->db->where('del_flg','0');
        $this->db->order_by('short_name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
        /**
     * Get Branch Name
     * 
     * @author Duc Tam
     * @return branch_name
     */
    public function getDistinctBranchName(){
        $this->db->distinct();
        $this->db->select("branch_name");
        $this->db->from("m_company_branch");
        $this->db->where('del_flg','0');
        $this->db->order_by('branch_name','ASC');

        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get Branch Name
     * 
     * @author Duc Tam
     * @return branch_name + Address
     */
    public function getAllBranchName(){
        $this->db->distinct();
        $this->db->select("branch_name, branch_address, branch_tel, branch_fax");
        $this->db->from("m_company_branch");
        $this->db->where('del_flg','0');
        $this->db->order_by('branch_name','ASC');

        $query = $this->db->get();
        return $query->result_array();
    }
    // get last partners in table - create by: thanh
    public function getLastID()
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

    // check partners is exists ? - create by: thanh
    public function check_exists($companyID)
    {
        $this->db->select("1");
        $this->db->from('m_company');
        $this->db->where('company_id', $companyID);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // insert partners - create by: thanh
    public function insert($params)
    {
        $this->db->trans_begin();
        $query = $this->db->insert('m_company', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // update partners - create by: thanh
    public function update($params)
    {
        $this->db->trans_begin();
        $this->db->where('company_id', $params['company_id']);
        $this->db->update('m_company',$params);

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
        $this->db->where('company_id', $params['company_id']);
        $this->db->update('m_company', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    } 

    // Get All Brand And HeadOffice for Supplier
    public function GetBrandHeadOff($companyId){
        $this->db->select("COB.* , COH.head_office_name"); 
        $this->db->from('m_company_branch COB');
        $this->db->join('m_company_headoffice COH', 'COH.company_id = COB.company_id', 'left');
        $this->db->where('COB.del_flg','0');
        $this->db->where('COB.company_id',$companyId);
        $this->db->order_by('branch_id', 'ASC');

        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result_array();
    }

    public function check_edit_date($params)
    {
        $this->db->select("1");
        $this->db->from('m_company');
        $this->db->where('company_id', $params['company_id']);
        $this->db->where('edit_date', $params['edit_date']);
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_customer_export()
    {
        $this->db->select('komoku_id, kubun, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_VAT_BY);
        $this->db->where('del_flg', '0');
        $vat_by_ = $this->db->get_compiled_select();

        $this->db->select('komoku_id, kubun, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_FEE_TERM);
        $this->db->where('del_flg', '0');
        $fee_term_ = $this->db->get_compiled_select();

        $this->db->select('komoku_id, kubun, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('del_flg', '0');
        $payment_term_ = $this->db->get_compiled_select();

        $this->db->distinct('CH.company_id');
        $this->db->select('COM.company_id, COM.company_name, COM.short_name, COM.reference,
                            CH.head_office_name, CH.head_office_address, CH.head_office_phone, CH.head_office_tel, CH.head_office_fax,
                            COM.transportation as delivery_term,
                            a.komoku_name_3 as vat_by,
                            b.komoku_name_3 as fee_term,
                            c.komoku_name_3 as payment_term');
        $this->db->from('m_company COM');
        $this->db->join('m_company_headoffice CH',"COM.company_id = CH.company_id and CH.del_flg = '0'",'left');
        $this->db->join("($vat_by_) a", 'COM.vat_by = a.kubun', 'left');
        $this->db->join("($fee_term_) b", 'COM.fee_term = b.kubun', 'left');
        $this->db->join("($payment_term_) c", 'COM.payment_term = c.kubun', 'left');
        $this->db->where('COM.del_flg', '0');
        $this->db->where('COM.type','1');
        $this->db->order_by('COM.company_name','ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function get_consignee_export($companyID)
    {
        $sql = "SELECT company_id, head_office_address as consignee_address, head_office_name as consignee_name
                FROM m_company_headoffice
                WHERE company_id = '$companyID'";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    public function get_notify_export($companyID)
    {
        $sql = "SELECT company_id, branch_address as notify_address, branch_name as notify_name
                FROM  m_company_branch
                WHERE company_id = '$companyID' ";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    public function get_all_consignee(){
        $sql = "
        SELECT m_company_branch.company_id, m_company_branch.branch_id, m_company_branch.note,
            trim(branch_name) as branch_name, trim(branch_address) as branch_address, trim(branch_address_vn) as branch_address_vn,
            trim(shousha_name) as shousha_name, trim(shousha_address) as shousha_address, 
            trim(customer_name) as customer_name, trim(head_office_address) as head_office_address,
            trim(head_office_address_vn) as head_office_address_vn,
            head_office_contract_name,
            head_office_position,
            company.short_name, customer.head_office_tel, customer.head_office_fax,
            company.reference, company.contract_from_date, company.contract_end_date,
            COALESCE(
                (SELECT delivery_condition
                FROM t_contract_print
                WHERE party_b = m_company_branch.branch_name
                ORDER BY create_date DESC
                LIMIT 1), m_company_branch.branch_transportation) as delivery_condition,
            company.payment_term
        FROM m_company_branch
        LEFT JOIN (
        SELECT komoku_name_2 as shousha_name, komoku_name_3 as shousha_address, note1 as company_id, note2 as branch_id
        FROM m_komoku
        WHERE komoku_id='KM0024' and kubun<>'000'
        ) as shousha
        ON CAST(shousha.company_id as text) = CAST(m_company_branch.company_id as text) and CAST(shousha.branch_id as text) = CAST(m_company_branch.branch_id as text)
        LEFT JOIN (
        SELECT head_office_address, company_id, head_office_tel, head_office_fax,
                head_office_address_vn,
                head_office_contract_name,
                head_office_position
        FROM m_company_headoffice
        ) as customer
        ON customer.company_id = m_company_branch.company_id
        LEFT JOIN (
        SELECT company_name as customer_name, company_id, short_name,
            COALESCE(
            (SELECT contract_no
            FROM t_contract_print
            WHERE party_b = m_company.company_name and kubun='2004'
            ORDER BY create_date DESC
            LIMIT 1), m_company.reference) as reference,
            COALESCE(
            (SELECT contract_date
            FROM t_contract_print
            WHERE party_b = m_company.company_name and kubun='2004'
            ORDER BY contract_date DESC
            LIMIT 1), m_company.contract_from_date) as contract_from_date,
            COALESCE(
            (SELECT end_date
            FROM t_contract_print
            WHERE party_b = m_company.company_name and kubun='2004'
            ORDER BY contract_date DESC
            LIMIT 1), m_company.contract_end_date) as contract_end_date,
            COALESCE(
            (SELECT payment_term
            FROM t_contract_print
            WHERE party_b = m_company.company_name
            ORDER BY create_date DESC
            LIMIT 1), m_komoku.komoku_name_3) as payment_term
        FROM m_company
        INNER JOIN m_komoku on m_company.payment_term = m_komoku.kubun and m_komoku.komoku_id = 'KM0023'
        ) as company
        ON company.company_id = m_company_branch.company_id
        ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function getCompanyInfo($companyID)
    {
        $this->db->distinct('CH.company_id');
        $this->db->select('COM.company_id, COM.company_name, COM.short_name, COM.reference, CH.head_office_name, CH.head_office_address, CH.head_office_phone, CH.head_office_tel, CH.head_office_fax');
        $this->db->from('m_company COM');
        $this->db->join('m_company_headoffice CH',"COM.company_id = CH.company_id and CH.del_flg = '0'",'left');
        $this->db->where('COM.del_flg', '0');
        $this->db->where('COM.company_name', $companyID);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}
