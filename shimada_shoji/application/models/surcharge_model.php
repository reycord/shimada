<?php
class surcharge_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getSurchargeInfo($data)
    {
        $result[0] = array('surcharge_unit_color' => '[null]', 'surcharge_color' => '[null]', 'default_surcharge_po' => '[null]');
        $tail = strtolower('_'.$data['currency']);
        $this->db->select("array_to_json(array_agg(surcharge_unit_color$tail)) as surcharge_unit_color, array_to_json(array_agg(surcharge_color$tail)) as surcharge_color");
        $this->db->from('m_surcharge');
        $this->db->where('trim(item_code)', trim($data['item_code']));
        $this->db->where("(qty_by_color_from <= " . (float)$data['color_quantity'] . " )");
        $this->db->where("(qty_by_color_to > " . (float)$data['color_quantity'] . " )");
        $this->db->group_by("item_code");
        $query1 = $this->db->get();
        $result1 = $query1->result_array();
        if(count($result1) > 0){
            $result[0] = array_merge($result[0], $result1[0]);
        }
        $this->db->reset_query();
        $this->db->select("array_to_json(array_agg(surcharge_po)) as default_surcharge_po");
        $this->db->from('m_surcharge');
        $this->db->where('trim(item_code)', trim($data['item_code']));
        $this->db->where("( po_amount_min$tail > " . (float)$data['sum_amount'] . " OR qty_by_order > " . (float)$data['sum_quantity'] . " )");
        $this->db->group_by("item_code");
        $query2 = $this->db->get();
        $result2 = $query2->result_array();
        if(count($result2) > 0){
            $result[0] = array_merge($result[0], $result2[0]);
        }
        return $result;
    }

    public function getAllSurcharge(){
        $this->db->select("*")->from("m_surcharge")->where("del_flg","0");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSurchargeById($params)
    {
        $this->db->select("*");
        $this->db->from('m_surcharge');
        if(isset($params['item_code'])) {
            $this->db->where('trim(item_code)', trim($params['item_code']));
        }
        if(isset($params['color'])) {
            $this->db->where("(color = '' or color IS NULL or color = '".trim($params['color'])."')");
        }
        if(isset($params['size'])) {
            $this->db->where("(size = '' or size IS NULL or size = '".trim($params['size'])."')");
        }
        $this->db->where('del_flg', '0');
        $this->db->order_by('item_code', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSurchargeByItem($params)
    {
        $this->db->select("surcharge_unit_color_usd, surcharge_unit_color_vnd, surcharge_unit_color_jpy, surcharge_color_usd, surcharge_color_vnd, surcharge_color_jpy, surcharge_po");
        $this->db->from('m_surcharge');
        if(isset($params['item_code'])) {
            $this->db->where('trim(item_code)', trim($params['item_code']));
        }
        if(isset($params['color'])) {
            $this->db->where("(color = '' or color IS NULL or color = '".trim($params['color'])."')");
        }
        if(isset($params['size'])) {
            $this->db->where("(size = '' or size IS NULL or size = '".trim($params['size'])."')");
        }

        $this->db->where("(qty_by_color_from <= " . (float)$params['odr_quantity'] . " )");
        $this->db->where("(qty_by_color_to > " . (float)$params['odr_quantity'] . " )");
        $this->db->where('del_flg', '0');
        $this->db->order_by('item_code', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete($params)
    {
        $current_user = $this->session->userdata('user');
        $current_date = date("Y/m/d H:i:s");

        $this->db->trans_begin();
        $this->db->set('del_flg', '1');
        $this->db->set('edit_date', $current_date);
        $this->db->set('edit_user', $current_user['employee_id']);
        $this->db->where_in('id', $params);
        $this->db->update('m_surcharge');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function insert($params)
    {
        $this->db->trans_begin();
        unset($params['id']);
        unset($params['add_mode']);
        unset($params['edit_mode']);
        $this->db->insert('m_surcharge', $params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function update($params)
    {
        $this->db->trans_begin();
        unset($params['add_mode']);
        unset($params['edit_mode']);
        $this->db->where('id', $params['id']);
        unset($params['id']);
        $this->db->update('m_surcharge', $params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
}
?>