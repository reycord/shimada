<?php
class items_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getAllItems()
    {
        $this->db->select("*");
        $this->db->from('m_items IT');
        // $this->db->join('tbl_currency CU', 'CU.currency_id = IT.currency', 'left');
        $this->db->where(' IT.del_flg', '0');
        $this->db->order_by('item_code, item_name, size, color', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    // public function getSalesman()
    // {
    //     $this->db->select('salesman');
    //     $this->db->distinct('salesman');
    //     $query = $this->db->get('m_items');
    //     $result = $query->result_array();
    //     if (sizeof($result) > 0) {
    //         return $result;
    //     }
    //     return null;
    // }
    public function getInventoryList()
    {
        $this->db->select("OD_D.id, OD.quantity, OD.sales_man, OD_D.item_code, IT.item_name, IT.quantity as it_quantity, IT.unit");
        $this->db->from('t_orders_details OD_D');
        $this->db->join('t_orders OD', 'OD_D.id = OD.id', 'left');
        $this->db->join('m_items IT', 'OD_D.item_code = IT.item_code', 'left');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    public function updateItemQuantity($itemID, $quantity)
    {
        $this->db->set('quantity', 'quantity +' . (int) (-$quantity), false);
        $this->db->where('item_code', $itemID);
        $this->db->update('m_items');
        $num_update = $this->db->affected_rows();
        if ($num_update > 0) {
            //return true;
            return $this->getItemQuantityByID($itemID);
        }
        return null;
    }
    public function getItemQuantityByID($id)
    {
        $this->db->select('quantity');
        $this->db->where('item_code', $id);
        $query = $this->db->get('m_items');
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }
    public function getItemByID($id)
    {
        $this->db->select('*');
        $this->db->from('m_items');
        $this->db->where('item_code', $id);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }

    public function getItemDistinct() 
    {
        $this->db->distinct();
        $this->db->select('item_code, item_name');
        $this->db->from('m_items');
        $this->db->where('del_flg', '0');
        $this->db->order_by('item_code, item_name', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return $result;
        }
        return null;
    }

    // get items by items_list from table m_company
    public function getByItemsList($items_list) 
    {
        if($items_list == '' || $items_list == null) {
            return null;
        }
        $this->db->distinct();
        $this->db->select('item_code, item_name');
        $this->db->from('m_items');
        $this->db->where_in('item_code', explode(",",$items_list));
        $this->db->where('del_flg', '0');
        $this->db->order_by('item_code, item_name', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    public function getByItemCodesList($items_list, $staff = null) 
    {
        if($items_list == '' || $items_list == null) {
            return null;
        }
        $jp_codes = array_unique(array_column($items_list, 'jp_code'));
        $size = array_unique(array_column($items_list, 'size'));
        $item_code = array_unique(array_column($items_list, 'item_code'));
        $query = "
            SELECT DISTINCT  mit.jp_code as jp_code_pattern, mit.salesman,  mit.item_code,  mit.jp_code,  mit.color,  mit.size,  mit.sell_price_vnd,  mit.sell_price_usd,  mit.sell_price_jpy,  mit.base_price_vnd,  mit.base_price_usd,  mit.base_price_jpy, kmk.komoku_name_3
            FROM m_items mit
            LEFT JOIN m_komoku kmk ON kmk.komoku_id = 'KM0030' AND kmk.del_flg = '0'
            WHERE (item_code IN "."('".implode($item_code, "','")."')"." OR 
            jp_code IN "."('".implode($jp_codes, "','")."'))"." AND 
            size IN "."('".implode(array_map("trim",$size), "','")."')"." AND 
            mit.del_flg = '0' ";
        $query .= "
            UNION ALL 
            SELECT DISTINCT split_part(km.komoku_name_2, 'x', 1) as jp_code_pattern,  itm.salesman, itm.item_code,  itm.jp_code,  itm.color,  itm.size,  itm.sell_price_vnd,  itm.sell_price_usd,  itm.sell_price_jpy,  itm.base_price_vnd,  itm.base_price_usd,  itm.base_price_jpy, km.komoku_name_3
            FROM m_items itm
            INNER JOIN m_komoku km ON km.komoku_id = 'KM0030' AND km.del_flg = '0' AND (left( itm.jp_code, length(split_part(km.komoku_name_2,'x',1))) = split_part(km.komoku_name_2,'x',1))
            WHERE trim( itm.size) IN "."('".implode(array_map("trim", $size), "','")."')"."
            AND itm.del_flg = '0' ";
        $query .= "ORDER BY jp_code_pattern, salesman DESC";

        $query = $this->db->query($query);
        $result = $query->result_array();

        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    // search items - create by: thanh
    public function search_items($params = null, $start=null, $length=null, &$recordsTotal=null, &$recordsFiltered=null, $column_name=null, $sort=null)
    {
        // $this->db->distinct();
        // $this->db->select('item_code, size, color, id as surcharge_id');
        // $this->db->from('m_surcharge');
        // $this->db->where('del_flg', '0');
        // $surcharge = $this->db->get_compiled_select();
        
        $this->db->select("a.*, SUBSTRING(a.jp_code,1,2) as area,
                         (SELECT id as surcharge_id FROM m_surcharge sur WHERE sur.del_flg = '0' and sur.item_code = a.item_code and sur.size = a.size and sur.color = a.color limit 1 ) as surcharge_id");
        $this->db->from('m_items a');
        // $this->db->join("($surcharge) sur", 'sur.item_code = a.item_code and sur.size = a.size and sur.color = a.color', 'left');
        $this->db->where('a.del_flg', '0');

        $recordsTotal = $this->db->count_all_results(null, false);

        if (isset($params['salesman'])) {
            $this->db->like('a.salesman', $params['salesman']);
        }
        if (isset($params['customer'])) {
            $this->db->like('a.customer', $params['customer'], 'both');
        }
        if (isset($params['apparel'])) {
            $this->db->like('a.apparel', $params['apparel'] ,'both');
        }
        if (isset($params['vendor'])) {
            $this->db->like('a.vendor', $params['vendor'], 'both');
        }
        if(isset($params['size'])) {
            $this->db->like('a.size', $params['size'], 'both');
        }
        if(isset($params['color'])) {
            $this->db->like('a.color', $params['color'], 'both');
        }
        if (isset($params['end_of_sales'])) {
            $this->db->where('a.end_of_sales', $params['end_of_sales']);
        }
        if (isset($params['item_code']) && isset($params['item_name'])) {
            $this->db->group_start()
                ->like('a.item_code', $params['item_code'])
                ->or_like('a.item_name', $params['item_name'])
                ->group_end();
        } else {
            if (isset($params['item_code'])) {
                $this->db->like('a.item_code', $params['item_code'], 'both');
            }
            if (isset($params['item_name'])) {
                $this->db->like('a.item_name', $params['item_name'], 'both');
            }
        }
        if (isset($params['flgExcel'])){
            $this->db->order_by('area', "ASC");
            $this->db->order_by('a.item_code', "ASC");
            $this->db->order_by('a.size', "ASC");
            $this->db->order_by('a.color', "ASC");
        }else if(isset($column_name) && isset($sort)) {
                if($column_name == 'apparel') {
                    $this->db->order_by('d.'.$column_name, $sort);
                    $this->db->order_by('a.item_code', $sort);
                    $this->db->order_by('a.size', $sort);
                    $this->db->order_by('a.color', $sort);
                } else {
                    $this->db->order_by('a.'.$column_name, $sort);
                    $this->db->order_by('a.item_code', $sort);
                    $this->db->order_by('a.size', $sort);
                    $this->db->order_by('a.color', $sort);
            }
        }

        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function checkExistItemByAllKeys($params) {
        $this->db->select("1");
        $this->db->from('m_items');
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('customer_code', $params['customer_code']);
        $this->db->where('salesman', $params['salesman']);
        $this->db->where('color', (isset($params['color'])?$params['color']:""));
        $this->db->where('size', (isset($params['size'])?$params['size']:""));
        $this->db->where('del_flg', '0');

        $check_exist = $this->db->get();
        $result = $check_exist->result_array();
        if (sizeof($result) > 0) {
            return TRUE;
        }
        return FALSE;
    }
    // insert items - create by: thanh
    public function insertItems($params)
    {
        $this->db->trans_begin();
        $params['color'] = (isset($params['color']) ? $params['color'] : "");
        $params['customer'] = (isset($params['customer']) ? $params['customer'] : "");
        $params['size'] = (isset($params['size']) ? $params['size'] : "");
        $params['item_name'] = (isset($params['item_name']) ? $params['item_name'] : "");
        $params['item_name_vn'] = (isset($params['item_name_vn']) ? $params['item_name_vn'] : "");
        $this->db->select("1");
        $this->db->from('m_items');
        $this->db->where('item_code', $params['item_code']);
        if(isset($params['customer_code'])) {
            $this->db->where('customer_code', $params['customer_code']);
        }
        $this->db->where('salesman', $params['salesman']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        $this->db->where('del_flg', '1');

        $check_exist = $this->db->get();
        if($check_exist->num_rows() > 0) {
            $data_del = array(
                'item_code' => $params['item_code'],
                'customer_code' => $params['customer_code'],
                'salesman' => $params['salesman'],
                'color' => $params['color'],
                'size' => $params['size'],
            );
            $this->db->delete('m_items', $data_del);
            $this->db->insert('m_items', $params);
        } else {
            $this->db->insert('m_items', $params);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            $result = $this->items_model->get_item_by_id($params['item_code'], $params['customer_code'], $params['customer'], $params['jp_code'], $params['item_name'], $params['item_name_vn'], $params['salesman'], $params['size'], $params['color']);
            return $result;
        }

    }
    // update items - create by: thanh
    public function updateItems($params)
    {
        $this->db->trans_begin();
        $this->db->where('trim(item_code)', trim($params['item_code_old']));
        // $this->db->where('trim(customer_code)', trim($params['customer_code_old']));
        $this->db->where('trim(customer)', trim($params['customer_old']));
        $this->db->where('trim(jp_code)', trim($params['jp_code_old']));
        $this->db->where('trim(item_name)', trim($params['item_name_old']));
        $this->db->where('trim(item_name_vn)', trim($params['item_name_vn_old']));
        $this->db->where('trim(salesman)', trim($params['salesman_old']));
        $this->db->where('trim(size)', trim($params['size_old']));
        $this->db->where('trim(color)', trim($params['color_old']));
        unset($params['item_code_old']);
        unset($params['customer_code_old']);
        unset($params['customer_old']);
        unset($params['jp_code_old']);
        unset($params['item_name_old']);
        unset($params['item_name_vn_old']);
        unset($params['salesman_old']);
        unset($params['color_old']);
        unset($params['size_old']);
        $this->db->update('m_items',$params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $this->items_model->get_item_by_id($params['item_code'], $params['customer_code'], $params['customer'], $params['jp_code'], $params['item_name'], $params['item_name_vn'], $params['salesman'], $params['size'], $params['color']);
        }
    }

    // update items - create by: thanh
    public function delete_products($params)
    {
        $this->db->trans_begin();
        $this->db->where('trim(item_code)', trim($params['item_code']));
        // $this->db->where('trim(customer_code)', trim($params['customer_code']));
        $this->db->where('trim(customer)', trim($params['customer']));
        $this->db->where('trim(jp_code)', trim($params['jp_code']));
        $this->db->where('trim(item_name)', trim($params['item_name']));
        $this->db->where('trim(item_name_vn)', trim($params['item_name_vn']));
        $this->db->where('trim(salesman)', trim($params['salesman']));
        $this->db->where('trim(size)', trim($params['size']));
        $this->db->where('trim(color)', trim($params['color']));
        $this->db->update('m_items',$params);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function get_item_by_id($item_code, $customer_code, $customer, $jp_code, $item_name, $item_name_vn, $salesman, $size, $color)
    {
        $this->db->select("a.item_code,
                        a.item_name,
                        a.item_name_vn,
                        a.item_name_com,
                        a.item_name_dsk,
                        a.item_name_des,
                        a.salesman,
                        a.customer,
                        d.komoku_name_2,
                        a.vendor,
                        a.jp_code,
                        a.vendor_parts,
                        a.end_of_sales,
                        a.color,
                        a.size,
                        a.customer_code,
                        a.edit_date,
                        a.edit_user,
                        a.composition_1, a.composition_2, a.composition_3,
                        a.end_of_sales,
                        a.customer,
                        a.apparel,
                        a.unit,
                        a.size_unit,
                        a.currency,
                        a.vendor,
                        a.vendor_parts,
                        a.vendor_color,
                        a.lot_quantity,
                        a.quantity,
                        a.moq,
                        a.net_wt,
                        a.buy_price_vnd, a.buy_price_usd, a.buy_price_jpy,
                        a.sell_price_vnd, a.sell_price_usd, a.sell_price_jpy,
                        a.base_price_vnd, a.base_price_usd, a.base_price_jpy,
                        a.inspection_rate,
                        a.origin,
                        a.selfobject_code,
                        a.note,
                        a.note_po_sheet as color_note,
                        a.note_lapdip,
                        a.create_user, a.edit_user, a.create_date, a.edit_date"
                    );
        $this->db->from('m_items a');
        $this->db->join('m_employee c', 'c.employee_id = a.salesman', 'left');
        $this->db->join('m_komoku d', "d.komoku_id = 'KM0019' and d.kubun = a.apparel", 'left');
        $this->db->where('trim(a.item_code)', trim($item_code));
        $this->db->where('trim(a.customer_code)', trim($customer_code));
        $this->db->where('trim(a.item_name_vn)', trim($item_name_vn));
        $this->db->where('trim(a.customer)', trim($customer));
        $this->db->where('trim(a.jp_code)', trim($jp_code));
        $this->db->where('trim(a.item_name)', trim($item_name));
        $this->db->where('trim(a.salesman)', trim($salesman));
        $this->db->where('trim(a.size)', trim($size));
        $this->db->where('trim(a.color)', trim($color));
        $this->db->where('a.del_flg', '0');
        
        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return $result;
        }
        return null;
    }

    // check product is exists ? - create by: thanh
    public function check_product_exists($params, $item_name_vn = null)
    {
        $this->db->select("1");
        $this->db->from('m_items');
        $this->db->where('trim(item_code)', trim($params['item_code']));
        // $this->db->where('trim(customer_code)', trim($params['customer_code']));
        if (isset($params['customer'])) {
            $this->db->where('trim(customer)', trim($params['customer']));
        }
        $this->db->where('trim(jp_code)', trim($params['jp_code']));
        $this->db->where('trim(item_name)', trim($params['item_name']));
        if(isset($item_name_vn) && $item_name_vn != null) {
            $this->db->where('trim(item_name_vn)', trim($item_name_vn));
        }
        $this->db->where('trim(salesman)', trim($params['salesman']));
        $this->db->where('trim(size)', trim($params['size']));
        $this->db->where('trim(color)', trim($params['color']));
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            if($query->result_array())
            return FALSE;
        }
    }

    public function search_for_add($params, $start, $length, &$recordsTotal, &$recordsFiltered)
    {
        
        $this->db->from('m_items IT');
        $this->db->where(' IT.del_flg', '0');

        $recordsTotal = $this->db->count_all_results(null, false);

        $this->db->select("trim(item_code) as item_code,
                        item_name,
                        trim(salesman) as salesman,
                        trim(customer) as customer,
                        vendor,
                        jp_code,
                        vendor_parts,
                        trim(color) as color,
                        trim(size) as size,
                        composition_1, composition_2, composition_3,
                        apparel,
                        unit,
                        size_unit,
                        currency,
                        vendor,
                        vendor_parts,
                        vendor_color,
                        lot_quantity,
                        quantity,
                        moq,
                        net_wt,
                        buy_price_vnd, buy_price_usd, buy_price_jpy,
                        sell_price_vnd, sell_price_usd, sell_price_jpy,
                        base_price_vnd, base_price_usd, base_price_jpy,
                        inspection_rate,
                        origin");
        if (!empty($params['item_code'])) {
            $this->db->like('item_code', $params['item_code']);
        }
        if (!empty($params['item_name'])) {
            $this->db->like('item_name', $params['item_name']);
        }
        if (!empty($params['input_date'])) {
            // TODO: input_date ???
            // $this->db->where('input_date', $params['input_date']);
        }
        if (!empty($params['saleman'])) {
            // $this->db->like('salesman', $params['saleman']);
            $this->db->where("( salesman like '%$params[saleman]%' or salesman is null or salesman = '') ");
        }
        if (!empty($params['input_customer'])) {
            $this->db->like('customer', $params['input_customer']);
        }

        $this->db->order_by('item_code, size, color', 'asc');

        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function check_edit_date($params)
    {
        $this->db->select("1");
        $this->db->from('m_items');
        $this->db->like('trim(item_code)', trim($params['item_code']));
        $this->db->like('trim(customer_code)', trim($params['customer_code']));
        $this->db->like('trim(jp_code)', trim($params['jp_code']));
        $this->db->like('trim(item_name)', trim($params['item_name']));
        $this->db->like('trim(item_name_vn)', trim($params['item_name_vn']));
        $this->db->like('trim(salesman)', trim($params['salesman']));
        $this->db->like('trim(size)', trim($params['size']));
        $this->db->like('trim(color)', trim($params['color']));
        $this->db->like('trim(customer)', trim($params['customer']));
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
    public function search_for_purchase_order($params, $start = null, $length = null, &$recordsTotal, &$recordsFiltered)
    {
        $currency = $params['currency'];
        if($currency == ""){
            $currency = "USD";
        }
        // get company info
        $this->db->select('DISTINCT ON ("m_company"."company_id") m_company.company_name, ho.company_id, m_company.short_name, m_company.reference, ho.head_office_address, ho.head_office_phone, ho.head_office_tel, ho.head_office_fax');
        $this->db->from('m_company');
        $this->db->join('m_company_headoffice ho',"m_company.company_id = ho.company_id AND ho.del_flg='0'",'left');
        $this->db->where('m_company.type', '2');
        $this->db->where('m_company.del_flg', '0');
        $head_office = $this->db->get_compiled_select();

        $this->db->from('m_items IT');
        $this->db->join("($head_office) ho", 'trim("IT"."vendor") = trim("ho"."short_name")','left');
        $this->db->where(' IT.del_flg', '0');
        $recordsTotal = $this->db->count_all_results(null, false);
        $respone = "ho.reference, 
                    ho.short_name,
                    ho.company_name, 
                    ho.head_office_address, 
                    ho.head_office_phone, 
                    ho.head_office_tel, 
                    ho.head_office_fax,
                    '' AS order_receive_no,
                    IT.composition_1,
                    IT.composition_2,
                    IT.composition_3,
                    IT.size_unit,
                    IT.item_code, 
                    IT.item_name, 
                    IT.size, 
                    IT.color,
                    '$currency' as currency,
                    quantity, unit, 
                    (case when salesman = '' then 'Free' else salesman end) AS staff, vendor, 0 As amount, 
                    IT.note_lapdip, customer, jp_code, item_name_vn";
        if(!empty($params['currency'])){
            if($params['currency'] == 'VND'){
                $respone .= ", buy_price_vnd AS sell_price";
            }else if($params['currency'] == 'USD'){
                $respone .= ", buy_price_usd AS sell_price";
            }else{
                $respone .= ", buy_price_jpy AS sell_price";
            }
        }else{
            $respone .= ", buy_price_usd AS sell_price";
        }
        $this->db->distinct();
        $this->db->select($respone);
        if(!empty($params['item_code']) && !empty($params['item_name'])){
            $this->db->where("(IT.item_code LIKE '%".$params['item_code']."%' OR item_name LIKE '%".$params['item_name']."%')");
        } else {
            if (!empty($params['item_code'])) {
                $this->db->like('IT.item_code', $params['item_code']);
            }
            if (!empty($params['item_name'])) {
                $this->db->like('item_name', $params['item_name']);
            }
        }
        if (!empty($params['supplier'])) {
            $this->db->where("(vendor IS NULL OR vendor LIKE '%".$params['supplier']."%')");
        }
        if (!empty($params['color'])) {
            $this->db->like('color', $params['color']);
        }
        if (!empty($params['size'])) {
            $this->db->like('size', $params['size']);
        }
        if (!empty($params['saleman'])) {
            $this->db->like('salesman', $params['saleman']);
        }
        if (!empty($params['to_date'])) {
            $this->db->where('create_date <=', $params['to_date']);
        }
        if (!empty($params['from_date'])) {
            $this->db->where('create_date >=', $params['from_date']);
        }
        $this->db->order_by('IT.item_code, IT.size, IT.color', 'asc');

        $recordsFiltered = $this->db->count_all_results(null, false);

        if($start != null){
            $this->db->offset($start);
        }
        if($length != null){
            $this->db->limit($length);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getItem($conditions, $currency) {
        if("USD" == $currency)
            $this->db->select('shosha_price_usd as shosha_price');
        else if("JPY" == $currency)
            $this->db->select('shosha_price_jpy as shosha_price');
        else if("VND" == $currency)
            $this->db->select('shosha_price_vnd as shosha_price');
        $this->db->from('m_items');
        $this->db->where('item_code', $conditions['item_code']);
        $this->db->where('size', $conditions['size']);
        $this->db->where('color', $conditions['color']);

        $pv_query = $this->db->get();
        if(sizeof($pv_query->result_array()) > 0) {
            return $pv_query->result_array()[0];
        } else {
            return NULL;
        }
    }
}