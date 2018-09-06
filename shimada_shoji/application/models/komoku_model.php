<?php 

class komoku_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // search items create by: thanh
    public function search($params = null, $start=null, $length=null, &$recordsTotal=null, &$recordsFiltered=null,  $column_name=null, $sort=null)
    {
        $this->db->select('komoku_id, kubun, note1, note2, com.short_name, trim(bra.branch_name) as branch_name');
        $this->db->from('m_komoku kmk');
        $this->db->join('m_company com','CAST(com.company_id as text) = note1 and com.del_flg = \'0\' and com.type = \'1\'','left');
        $this->db->join('m_company_branch bra','CAST(bra.company_id as text) = note1 and bra.branch_id = note2 and bra.del_flg = \'0\'','left');
        $this->db->where('kubun <>','000');
        $this->db->where('komoku_id', KOMOKU_SHOUSHA);
        $this->db->where('kmk.del_flg','0');
        $shoshaList = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->from('m_komoku kmk');
        $this->db->where('del_flg','0');
        $this->db->where('kmk.kubun <>','000');
        $recordsTotal = $this->db->count_all_results(null, false);

        $this->db->select("kmk.komoku_id, kmk.kubun, komoku_name_1, komoku_name_2, komoku_name_3, use, sort, edit_date,
                            (case when ssl.short_name is null then kmk.note1 else ssl.short_name end) as note1,
                            (case when ssl.branch_name is null then kmk.note2 else ssl.branch_name end) as note2,
                            kmk.note1 as company_id, kmk.note2 as branch_id
                        ");
        $this->db->join("($shoshaList) ssl",'kmk.komoku_id = ssl.komoku_id and kmk.kubun = ssl.kubun','left');

        if(isset($params['komoku_id'])) {
            $this->db->where('kmk.komoku_id', $params['komoku_id']);
        }
        $this->db->order_by('kmk.komoku_name_1');
        $this->db->order_by('kmk.komoku_name_2');
        if (isset($column_name) && isset($sort)) {
            $this->db->order_by($column_name, $sort);
        }

        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);

        $query = $this->db->get();
        return $query->result_array();
    }

    // insert items - create by: thanh
    public function insert($params)
    {
        $this->db->trans_begin();
        $this->db->insert('m_komoku', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            if($params['komoku_id'] == KOMOKU_SHOUSHA) {
                return $this->komoku_model->getKomokuShouSha($params['kubun']);
            }
            return $this->komoku_model->getKomokuByID($params['komoku_id'], $params['kubun']);
        }
    }

    // get komoku by kubun and komoku_id
    public function getKomokuByID($komoku_id, $kubun)
    {
        $this->db->select("*");
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', $komoku_id);
        $this->db->where('kubun', $kubun);

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }

    // Check edit_date 
    public function check_edit_date($params)
    {
        $this->db->select("1");
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', $params['komoku_id']);
        $this->db->where('kubun', $params['kubun']);
        $this->db->where('edit_date', $params['edit_date']);

        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // update items - create by: thanh
    public function update($params)
    {
        $this->db->trans_begin();
        $this->db->where('komoku_id', $params['komoku_id']);
        $this->db->where('kubun', $params['kubun']);
        $query = $this->db->update('m_komoku',$params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            if($params['komoku_id'] == KOMOKU_SHOUSHA) {
                return $this->komoku_model->getKomokuShouSha($params['kubun']);
            }
            return $this->komoku_model->getKomokuByID($params['komoku_id'], $params['kubun']);
        }
    }

    //  get all item at kubun == 000 - create by: thanh
    public function get_item_notnull()
    {
        $this->db->select("komoku_id,kubun,komoku_name_1");
        $this->db->from('m_komoku');
        $this->db->where('del_flg','0');
        $this->db->where('kubun','000');
        $this->db->order_by('komoku_name_1', 'ASC');
        // $this->db->order_by('kubun', 'ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    /**
     * Get All Status
     * 
     * @author Duc Tam
     * @return status
     */
    public function getKomokuName(){
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id","KM0001");
        $this->db->where_in("kubun", explode(',', PACKING_STATUS_SEARCH));
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get All Status with condition USE
     * 
     * @author Duc Tam
     * @return status
     */
    public function get_status_list_with_use($use){
        $this->db->select("kubun as status_code, komoku_name_2, note1, note2");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id", "KM0001");
        $this->db->where("use", $use);
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get All Packing Type
     * 
     * @author Duc Tam
     * @return status
     */
    public function getAllPackingType(){
        $this->db->select("kubun as type_code,komoku_name_2 as type_name");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id","KM0007");
        $this->db->where("kubun <>","000");
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAllTypes(){
        $this->db->select("kubun as types_code,komoku_name_2 as types_name");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id","KM0020");
        $this->db->where("kubun <>","000");
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get STATUS FINISH/NOT YET
     * 
     * @author Duc Tam
     * @return status
     */
    public function getStatusFinishOrNotYet(){
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id","KM0001");
        $this->db->where_in("kubun",array("018","019"));
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getComboboxData($komokuId, $select="*", $kubunArr = NULL){
        $this->db->reset_query();
        $this->db->select($select);
        $this->db->from('m_komoku');
        $this->db->where('del_flg','0');
        $this->db->where('komoku_id',$komokuId);
        $this->db->where('komoku_name_2 IS NOT NULL');
        if($kubunArr != null) {
            $this->db->where_in('kubun', $kubunArr);
        }
        $this->db->order_by('komoku_id', 'ASC');
        $this->db->order_by('kubun', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // public function getKomokuByKubun($komokuId, $kubun){
    //     $this->db->select("*");
    //     $this->db->from('m_komoku');
    //     $this->db->where('del_flg','0');
    //     $this->db->where('komoku_id',$komokuId);
    //     $this->db->where_in('kubun',explode(",", $kubun));
    //     $this->db->where('komoku_name_2 IS NOT NULL');
    //     $this->db->order_by('komoku_id', 'ASC');
    //     $this->db->order_by('kubun', 'ASC');
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

    // delete items - create by: thanh
    public function delete($params)
    {
        $this->db->trans_begin();
        $data = array(
            'del_flg' => '1',
            'edit_user' => $params['edit_user'],
            'edit_date' => $params['edit_date']
        );

        $this->db->where('komoku_id', $params['komoku_id']);
        $this->db->where('kubun', $params['kubun']);
        $this->db->update('m_komoku', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    } 

    // check item is exists ? - create by: thanh
    public function check_item_exists($params)
    {
        $this->db->select("1");
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', $params['komoku_id']);
        $this->db->where('kubun', $params['kubun']);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getWarehouseWithUse($use)
    {
        $this->db->select("komoku_id, komoku_name_2, kubun");
        $this->db->from('m_komoku');
        $this->db->where('del_flg','0');
        $this->db->where('komoku_id', KOMOKU_WAREHOUSE);
        $this->db->where('kubun <>', '000');
        $this->db->where('use', $use);
        $this->db->order_by('komoku_id', 'ASC');
        $this->db->order_by('kubun', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_shipping_method_with_use($use)
    {
        $this->db->select("komoku_id, komoku_name_2, kubun");
        $this->db->from('m_komoku');
        $this->db->where('del_flg','0');
        $this->db->where('komoku_id', KOMOKU_SHIPPING_METHOD);
        $this->db->where('kubun <>', '000');
        $this->db->where('use', $use);
        $this->db->order_by('komoku_id', 'ASC');
        $this->db->order_by('kubun', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    // Edit by Khanh
    // Update Date : 02/04/2018
    public function getByKomokuid($komoku_id) 
    {
        $this->db->select("komoku_id, komoku_name_2, kubun");
        $this->db->from('m_komoku');
        $this->db->where('del_flg','0');
        $this->db->where('komoku_id', $komoku_id);
        $this->db->where('kubun <>', '000');
        $this->db->order_by('komoku_id', 'ASC');
        $this->db->order_by('kubun', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    // Update Date : 07/05/2018
    public function getStatusSearch($komoku_id, $range = []) 
    {
        $col_lang = getColStatusByLang($this);
        $this->db->select("komoku_id, $col_lang as komoku_name_2, kubun");
        $this->db->from('m_komoku');
        $this->db->where('del_flg','0');
        $this->db->where('komoku_id', $komoku_id);
        $this->db->where_in('kubun ', $range);
        $this->db->order_by('komoku_id', 'ASC');
        $this->db->order_by('kubun', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    // get max kubun follow komoku_id
    public function getNextKubun($komoku_id)
    {
        $this->db->select_max('kubun');
        $this->db->where('komoku_id', $komoku_id);
        $this->db->select_max('kubun');
        $result = $this->db->get('m_komoku');  
        $value = $result->row()->kubun;
        $value = (int)$value;
        $value += 1;
        $value  = str_pad("" . $value, 3, '0', STR_PAD_LEFT);
        return $value;
    }

    // Created by Khanh
    // Date : 23/04/2018
    // get status like finish 
    public function getStatusFinish($kubun){
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id",KOMOKU_STATUS);
        $this->db->where_in("kubun", $kubun);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        return $query->result_array();
    }

    // get all branch info
    public function getBranchInfo()
    {
        $this->db->select('*');
        $this->db->where('komoku_id', KOMOKU_SHOUSHA);
        $result = $this->db->get('m_komoku');
        return $result->result_array();
    }
    public function get_bank_export()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_BANK);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_party_export()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3, note1, note2');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PARTY);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_payment_method()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PAYMENT_METHOD);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_payment_term_list()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->like('kubun', '1', 'after');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_payment_by_code($komoku_name_3)
    {
        $this->db->select('kubun');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('komoku_name_3', $komoku_name_3);
        $this->db->order_by('komoku_name_2', 'ASC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            return $result[0];
        }
        return null;
    }
    public function get_party_info($kubun)
    {
        $this->db->select('*');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PARTY);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->where_in('kubun', $kubun);
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_bank_info($kubun)
    {
        $this->db->select('*');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_BANK);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->where_in('kubun', $kubun);
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_payment_term_vat()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->where_in('kubun', PAYMENT_TERM_VAT);
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_payment_term_eachtime()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_PAYMENT);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->like('kubun', '2', 'after');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_fee_term()
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_FEE);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_fee_term_by_kubun($kubun)
    {
        $this->db->select('kubun, komoku_name_2, komoku_name_3');
        $this->db->from('m_komoku');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_FEE);
        $this->db->where('kubun', $kubun);
        $this->db->where('komoku_name_2 IS NOT NULL');
        $this->db->order_by('komoku_name_2', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getWarehouseWithName($warehouse, $conFlg = false)
    {
        $this->db->select('km.kubun, km.komoku_name_2 as warehouse');
        $this->db->from('m_komoku km');
        $this->db->join('m_komoku km2',"km2.del_flg = '0' and km.komoku_id = km2.komoku_id and km2.kubun = '$warehouse'",'inner');
        $this->db->where('km.del_flg', '0');
        $this->db->where('km.komoku_id', KOMOKU_WAREHOUSE);
        if($conFlg){
            $this->db->where("km.komoku_name_2 = replace(km2.komoku_name_2, '_Cho xuat', '')");
        } else {
            $this->db->where("km.komoku_name_2 = concat(km2.komoku_name_2, '_Cho xuat')");
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getWarehouseByName($warehouse)
    {
        $this->db->select('km.kubun, km.komoku_name_2');
        $this->db->from('m_komoku km');
        $this->db->where('km.del_flg', '0');
        $this->db->where('km.komoku_id', KOMOKU_WAREHOUSE);
        $this->db->where('trim(km.komoku_name_2)', trim($warehouse));
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            return $result[0];
        }
        return null;
    }

    public function getShippingMethodByName($shippingName)
    {
        $this->db->reset_query();
        $this->db->select('km.kubun');
        $this->db->from('m_komoku km');
        $this->db->where('km.del_flg', '0');
        $this->db->where('km.komoku_id', KOMOKU_SHIPPING_METHOD);
        $this->db->where('trim(km.komoku_name_2)', trim($shippingName));
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            return $result[0];
        }
        return null;
    }

    public function get_distinct_Branch()
    {
        $this->db->distinct();
        $this->db->select('komoku_name_2, komoku_name_3');
        $this->db->where('del_flg', '0');
        $this->db->where('komoku_id', KOMOKU_SHOUSHA);
        $this->db->order_by('komoku_name_2', 'ASC');
        $result = $this->db->get('m_komoku');
        return $result->result_array();
    }

    // function get items follow komoku_id - create by: thanh
    public function get_all_position() 
    {
        return $this->getComboboxData(KOMOKU_POSITION);
    }
    public function get_all_department() 
    {
        return $this->getComboboxData(KOMOKU_DEPARTMENT);
    }
    public function get_all_classify() 
    {
        return $this->getComboboxData(KOMOKU_CLASSIFY);
    }
    public function get_all_unit() 
    {
        return $this->getComboboxData(KOMOKU_UNIT);
    }
    public function get_all_size_unit() 
    {
        return $this->getComboboxData(KOMOKU_SIZEUNIT);
    }
    public function get_all_size() 
    {
        return $this->getComboboxData(KOMOKU_SIZE);
    }
    public function get_all_currency()
    {
        return $this->getComboboxData(KOMOKU_CURRENCY, 'kubun as currency_id,komoku_name_2 as currency_name');
    }
    public function get_all_rate()
    {
        return $this->getComboboxData(KOMOKU_EXCHANGE_RATE, 'kubun as rate_id,komoku_name_2 as rate_name,komoku_name_3 as rate');
    }
    public function get_all_contract()
    {
        return $this->getComboboxData(KOMOKU_CONTRACTTYPE);
    }
    public function get_all_tax()
    {
        return $this->getComboboxData(KOMOKU_TAX, 'kubun as tax_id,komoku_name_2 as tax_name');
    }
    public function get_all_warehouse()
    {
        return $this->getComboboxData(KOMOKU_WAREHOUSE);
	}
	public function get_all_customer_code()
    {
        return $this->getComboboxData(KOMOKU_CUSTOMER_CODE);
	}
	public function get_all_apparel()
    {
        return $this->getComboboxData(KOMOKU_APPAREL);
	}
	public function get_all_color()
    {
        return $this->getComboboxData(KOMOKU_COLOR);
    }
    public function get_all_status()
    {
        $col_lang = getColStatusByLang($this);
        return $this->getComboboxData(KOMOKU_STATUS, "kubun as status_id,$col_lang  as status_name");
	}
	public function get_all_payment_by()
    {
        return $this->getComboboxData(KOMOKU_PAYMENT, "*", explode(",",INV_PL_PAYMENT));
    }
    public function get_all_endsaleman(){
        return $this->getComboboxData(KOMOKU_END_SALESMAN);
	}
	public function get_all_origin(){
        return $this->getComboboxData(KOMOKU_ORIGIN);
    }
    public function get_all_Branch(){
        return $this->getComboboxData(KOMOKU_SHOUSHA);
    }
    public function get_all_shipping_method(){
        return $this->getComboboxData(KOMOKU_SHIPPING_METHOD);
    }
    public function get_all_bank(){
        return $this->getComboboxData(KOMOKU_BANK, 'kubun as bank_id,komoku_name_2 as bank_name');
    }
    public function get_all_intercom(){
        return $this->getComboboxData(KOMOKU_INTERCOM);
    }
    public function get_all_party(){
        return $this->getComboboxData(KOMOKU_PARTY);
    }
    public function get_status_by_kbnArr($kubunArr)
    {
        $col_lang = getColStatusByLang($this);
        return $this->getComboboxData(KOMOKU_STATUS, "kubun as status_id,$col_lang as status_name", $kubunArr);
    }
    public function get_all_bank_info(){
        return $this->getComboboxData(KOMOKU_BANK);
    }
    public function get_export_term_by_party($party)
    {
        $this->db->reset_query();
        $this->db->select('km.komoku_name_3');
        $this->db->from('m_komoku km');
        $this->db->where('km.del_flg', '0');
        $this->db->where('km.komoku_id', KOMOKU_EXPORT_TERM);
        $this->db->where('trim(km.komoku_name_2)', trim($party));
        $query = $this->db->get();
        return $query->result_array();;
    } 
    public function get_delivery_term_by_party($party)
    {
        $this->db->reset_query();
        if($party === "SHIMADA SHOJI CO., LTD"){
            $this->db->select('km.komoku_name_3 as delivery_term');
            $this->db->from('m_komoku km');
            $this->db->where('km.del_flg', '0');
            $this->db->where('km.komoku_id', KOMOKU_FEE);
            $this->db->where('km.kubun', '004');
        }else{
            $this->db->select('ko.komoku_name_3 as delivery_term');
            $this->db->from('m_company co');
            $this->db->join('m_komoku ko', 'co.vat_by=ko.kubun and ko.komoku_id=\'KM0032\'','both');
            $this->db->where('co.del_flg', '0');
            $this->db->where('company_name', $party);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    } 

    public function getKomokuShouSha($kubun)
    {   
        $this->db->select("kmk.komoku_id, kmk.kubun, kmk.komoku_name_1, kmk.komoku_name_2, kmk.komoku_name_3, kmk.use, kmk.sort, kmk.edit_date, com.short_name as note1, bra.branch_name as note2, kmk.note1 as company_id, kmk.note2 as branch_id");
        $this->db->from('m_komoku kmk');
        $this->db->join('m_company com','CAST(com.company_id as text) = note1 and com.del_flg = \'0\' and com.type = \'1\'','left');
        $this->db->join('m_company_branch bra','CAST(bra.company_id as text) = note1 and bra.branch_id = note2 and bra.del_flg = \'0\'','left');
        $this->db->where('komoku_id', KOMOKU_SHOUSHA);
        $this->db->where('kmk.kubun', $kubun);
        $this->db->where('kmk.del_flg','0');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }
}

?>
