<?php
class store_item_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function getStoreItems($params=null, $flgUpdate=false, $start=0, $length=0, &$recordsTotal=0, &$recordsFiltered=0, $order=[]){
		$col_lang = getColStatusByLang($this);
		
		// $this->db->select("item_code, jp_code, customer_code, item_name, item_name_vn, customer, salesman, size, color, inspection_rate");
        // $this->db->from('m_items');
        // $this->db->where('del_flg','0');
        // $items = $this->db->get_compiled_select();
        // $this->db->reset_query();

        $this->db->select("SI.salesman as salesman
                            ,SI.size as size
                            ,SI.color as color
                            ,SI.item_code
                            ,SI.item_name
                            ,type.komoku_name_2 as item_type
                            ,SI.item_type as item_type_cd
                            ,SI.quantity
                            ,SI.unit as unit
                            ,warehouse.komoku_name_2 as warehouse
                            ,SI.warehouse as warehouse_cd
                            ,inventory_status.$col_lang as status
                            ,SI.status as status_cd
                            ,SI.arrival_ok
                            ,SI.arrival_ng
                            ,arrival_status.$col_lang as arrival_status
                            ,arrival_status.kubun as arrival_status_cd
                            ,SI.arrival_date
                            ,inspect_status.$col_lang as inspect_status
                            ,SI.inspect_date
                            ,SI.inspect_ok
                            ,SI.inspect_ng
                            ,SI.buy_price
                            ,SI.buy_amount
                            ,SI.sales_price
                            ,SI.apparel
                            ,SI.create_date
                            ,SI.order_detail_no
                            ,trim(invoice_no) as invoice_no
                            ,SI.order_no
                            ,SI.create_user
                            ,SI.arrival_note
                            ,SI.inspect_note
                            ,SI.inspect_note_path
                            ,SI.edit_date
                            ,SI.order_receive_no
                            ,SI.partition_no
                            ,(select inspection_rate from m_items where 'del_flg' = '0' and \"SI\".\"item_code\" = 'item_code' and \"SI\".\"size\" = 'size' and \"SI\".\"color\" = 'color' and \"SI\".\"item_name\" = 'item_name' order by inspection_rate DESC limit 1) as inspection_rate
                            ");
        $this->db->from('t_store_item SI');
        $this->db->join('m_komoku type', "SI.item_type = type.kubun AND type.komoku_id = '".KOMOKU_ITEMTYPE."'", 'left');
        $this->db->join('m_komoku warehouse', "SI.warehouse = warehouse.kubun AND warehouse.komoku_id = '".KOMOKU_WAREHOUSE."'", 'left');
        $this->db->join('m_komoku inventory_status', "SI.status = inventory_status.kubun AND inventory_status.komoku_id = '".KOMOKU_STATUS."'", 'left');
        $this->db->join('m_komoku arrival_status', "SI.arrival_status = arrival_status.kubun AND arrival_status.komoku_id = '".KOMOKU_STATUS."'", 'left');
		$this->db->join('m_komoku inspect_status', "SI.inspect_status = inspect_status.kubun AND inspect_status.komoku_id = '".KOMOKU_STATUS."'", 'left');
		// $this->db->join("($items) \"MI\"","SI.item_code = MI.item_code and SI.size = MI.size and SI.color = MI.color and SI.item_name = MI.item_name",'left');
        $this->db->where('SI.del_flg', "0");
        $this->db->group_start();
        $this->db->where_not_in('SI.status', '019');
        $this->db->or_where('SI.status', NULL);
        $this->db->group_end();
        if(count($order) > 0){
            $dir = $order[0]['dir'];
            $col = $order[0]['column'];
            $this->db->order_by($col, $dir);
        }else{
            // $this->db->order_by('create_date', 'DESC');
            // $this->db->order_by('salesman, item_code', 'ASC');
            $this->db->order_by('SI.salesman, SI.item_code, size, color, invoice_no, SI.order_detail_no, item_type, warehouse, status', 'ASC');
        }
        if(isset($params)){
            if(!empty($params["salesman"])){
                $this->db->like("SI.salesman", $params["salesman"],'both');
            }
            if(!empty($params["item_code"]) || !empty($params["item_name"])){
                $this->db->group_start();
                if(!empty($params["item_code"])){
                    $this->db->like("SI.item_code", $params["item_code"], 'both');
                }
                if(!empty($params["item_name"])){
                    $this->db->or_like("SI.item_name", $params["item_name"], 'both');
                }
                $this->db->group_end();
            }
            if(!empty($params["select_date"])){
                $select_date = $params['select_date'];
                if(!empty($params["created_from"])){
                    $this->db->where("SI.$select_date >=", $params["created_from"]);
                }
                if(!empty($params["created_to"])){
                    $this->db->where("SI.$select_date <=", $params["created_to"].' 23:59:59');
                }
            }
            if(!empty($params["nggreater0"])){
                $this->db->where("SI.inspect_ng >", 0);
            }
            if($flgUpdate){
                $this->db->where("item_type", $params["item_type"]);
                $this->db->like("order_no", $params["order_no"],'both');
                $this->db->where("warehouse", $params["warehouse"]);
                $this->db->where("order_receive_no", $params["order_receive_no"]);
                $this->db->where("partition_no", $params["partition_no"]);
                $this->db->where("order_detail_no", $params["order_detail_no"]);
                if(!empty($params["size"])){
                    $this->db->like("SI.size", $params["size"], 'both');
                }
                if(!empty($params["color"])){
                    $this->db->like("SI.color", $params["color"], 'both');
                }
            }else{
                //Set sql warehouse for where in
                if(!empty($params["warehouse"]) && !empty($params["place"])){
                    $warehouse = $params["warehouse"];
                    $place = $params["place"];
                    $sql="SELECT kubun
                        FROM m_komoku
                        WHERE komoku_id = 'KM0004'
                        AND  komoku_name_2 LIKE '$warehouse%$place%'";
                    $this->db->where_in("warehouse", $sql, false);
                } else {
                    if(!empty($params["place"]) && empty($params["warehouse"])){
                        $place = $params["place"];
                        $place_sql="SELECT kubun
                            FROM m_komoku
                            WHERE komoku_id = 'KM0004'
                            AND  komoku_name_2 LIKE '%$place%'";
                        $this->db->where_in("warehouse", $place_sql, false);
                    } else {
                        if(empty($params["place"]) && !empty($params["warehouse"])) {
                            $warehouse = $params["warehouse"];
                            $warehouse_sql="SELECT kubun
                                FROM m_komoku
                                WHERE komoku_id = 'KM0004'
                                AND  komoku_name_2 LIKE '$warehouse%'";
                            $this->db->where_in("warehouse", $warehouse_sql, false);
                        }
                    }
                }
            }
        }

        $recordsFiltered = $this->db->count_all_results(null, false);
        $recordsTotal = $this->db->count_all_results(null, false);
        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    /**
      * Get Store Item by Status
      *
      * @param Status: default{010:入荷待ち, 011:入荷済み, 012:検品済み, 016:引当済}
      * @param updateUser
      * @author Duc Tam
      * @return ListItems
      */
      public function getStoreItemsByStatus($params = null, $statusList = array('010', '011', '012', '016')){
        $col_lang = getColStatusByLang($this);
        $this->db->select("warehouse, IT.komoku_name_2 as item_type_nm, WH.komoku_name_2 as warehouse_nm, salesman, SI.status as status_code, MK.$col_lang as status, inspect_ok as store_quantity, item_code, item_name, size, color, inspect_ok as out_quantity, trim(invoice_no) as inv_no, arrival_date,order_receive_no,partition_no,order_no,salesman");
        $this->db->from('t_store_item SI');
        $this->db->join('m_komoku MK', "MK.komoku_id = 'KM0001' and MK.kubun = SI.status", 'left');
        $this->db->join('m_komoku WH', "WH.komoku_id = 'KM0004' and WH.kubun = SI.warehouse", 'left');
        $this->db->join('m_komoku IT', "IT.komoku_id = 'KM0020' and IT.kubun = SI.item_type", 'left');
        $this->db->where_in("SI.status", $statusList);
        $this->db->where("SI.del_flg", "0");
        $this->db->where("invoice_no IS NOT NULL and invoice_no <> ''");
        $this->db->where("inspect_ok <> 0");
        if($params != null){
            $this->db->where("trim(item_code)", trim($params['item_code']));
            $this->db->where("color", $params['color']);
            $this->db->where("size", $params['size']);
        }
        $this->db->order_by('item_code, size, color, arrival_date, inv_no');
        $query = $this->db->get();
        return $query->result_array();
     }

    public function getSummaryData($params){
        $created_from = $params['created_from'];
        $created_to = $params['created_to'];
        $warehouse = $params['warehouse'];
        $select_date = $params['select_date'];
        
        //Set sql warehouse for where in
        $warehouse_sql="SELECT kubun
        FROM m_komoku
        WHERE komoku_id = 'KM0004'
        AND  komoku_name_2 LIKE '$warehouse%'";

        //Set sql query for tondau
        $tondau_sql="";
        $this->db->select("item_code, color, size, item_type, warehouse");
        $this->db->select_sum("inspect_ok", "tondau");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, item_type, warehouse");
        if(!empty($created_from)){
            $this->db->where("$select_date<=", $created_from." 23:59:59");
        }
        $tondau_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for toncuoi
        $toncuoi_sql="";
        $this->db->select("item_code, color, size, item_type, warehouse");
        $this->db->select_sum("inspect_ok", "toncuoi");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, item_type, warehouse");
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $toncuoi_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for sl_nhap
        $sl_nhap_sql="";
        $this->db->select("item_code, color, size, item_type, warehouse");
        $this->db->select_sum("arrival_ok", "sl_nhap");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, item_type, warehouse");
        if(!empty($created_from)){
            $this->db->where("$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $sl_nhap_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for sl_xuat
        $sl_xuat_sql="";
        $this->db->select("item_code, color, size, item_type, warehouse");
        $this->db->select_sum("quantity", "sl_xuat");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, item_type, warehouse");
        $this->db->where("status", "016");
        if(!empty($created_from)){
            $this->db->where("$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $sl_xuat_sql = $this->db->get_compiled_select(null, true);

        $sql = "select SI.item_code, SI.color, SI.size, item_name, trim(unit) as unit, tondau, sl_nhap, sl_xuat, toncuoi, type.komoku_name_2 as item_type, warehouse.komoku_name_2 as warehouse
        from t_store_item SI
        LEFT JOIN m_komoku type ON SI.item_type = type.kubun AND type.komoku_id = 'KM0020'
        LEFT JOIN m_komoku warehouse ON SI.warehouse = warehouse.kubun AND warehouse.komoku_id = 'KM0004'
        LEFT JOIN (
            $tondau_sql
        ) tondau
        ON tondau.item_code = SI.item_code and tondau.color = SI.color and tondau.size = SI.size and tondau.item_type = SI.item_type
        LEFT JOIN (
            $toncuoi_sql
        ) toncuoi
        ON toncuoi.item_code = SI.item_code and toncuoi.color = SI.color and toncuoi.size = SI.size and toncuoi.item_type = SI.item_type
        LEFT JOIN (
            $sl_nhap_sql
        ) sl_nhap
        ON sl_nhap.item_code = SI.item_code and sl_nhap.color = SI.color and sl_nhap.size = SI.size and sl_nhap.item_type = SI.item_type
        LEFT JOIN (
            $sl_xuat_sql
        ) sl_xuat
        ON sl_xuat.item_code = SI.item_code and sl_xuat.color = SI.color and sl_xuat.size = SI.size and sl_xuat.item_type = SI.item_type
        ";
        //Get where for parent table
        if(!empty($created_from)){
            $this->db->where("SI.$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("SI.$select_date<=", $created_to.' 23:59:59');
        }
        $this->db->where_in("SI.warehouse", $warehouse_sql, false);
        if(!empty($params['item_code'])){
            $this->db->like("SI.item_code", $params['item_code'], "both");
        }
        if(!empty($params['item_name'])){
            $this->db->like("SI.item_name", $params['item_name']);
        }
        if(!empty($params['salesman'])){
            $this->db->where("SI.salesman", $params['salesman']);
        }
        $sql_compile = $this->db->get_compiled_select(null, true);
        $where = str_replace("\"","",substr($sql_compile, stripos($sql_compile, "where")));
        if($where != "") $sql .= $where;
        $sql .= " order by SI.item_code";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function getDetailData($params){
        $created_from = $params['created_from'];
        $created_to = $params['created_to'];
        $warehouse = $params['warehouse'];
        $select_date = $params['select_date'];

        //Set sql warehouse for where in
        $warehouse_sql="SELECT kubun
        FROM m_komoku
        WHERE komoku_id = 'KM0004'
        AND  komoku_name_2 LIKE '$warehouse%'";

        //Set sql query for tondau
        $tondau_sql="";
        $this->db->select("item_code, color, size, warehouse");
        $this->db->select_sum("inspect_ok", "tondau");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, warehouse");
        if(!empty($created_from)){
            $this->db->where("$select_date<=", $created_from." 23:59:59");
        }
        $tondau_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for toncuoi
        $toncuoi_sql="";
        $this->db->select("item_code, color, size, warehouse");
        $this->db->select_sum("inspect_ok", "toncuoi");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, warehouse");
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $toncuoi_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for sl_nhap
        $sl_nhap_sql="";
        $this->db->select("item_code, color, size, warehouse");
        $this->db->select_sum("arrival_ok", "sl_nhap");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, warehouse");
        if(!empty($created_from)){
            $this->db->where("$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $sl_nhap_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for sl_xuat
        $sl_xuat_sql="";
        $this->db->select("item_code, color, size, warehouse");
        $this->db->select_sum("quantity", "sl_xuat");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, warehouse");
        $this->db->where("status", "016");
        if(!empty($created_from)){
            $this->db->where("$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $sl_xuat_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for quantity
        $quantity_sql="";
        $this->db->select("item_code, color, size, warehouse");
        $this->db->select_sum("inspect_ok", "inspect_ok");
        $this->db->select_sum("inspect_ng", "inspect_ng");
        $this->db->from("t_store_item");
        $this->db->group_by("item_code, color, size, warehouse");
        if(!empty($created_from)){
            $this->db->where("$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("$select_date<=", $created_to.' 23:59:59');
        }
        $quantity_sql = $this->db->get_compiled_select(null, true);

        //Set sql query for packing detail
        $packingdetail_sql="";
        $this->db->select("item_code, color, size");
        $this->db->select_sum("quantity", "sl_cho_xuat");
        $this->db->from("t_packing_details");
        $this->db->group_by("item_code, color, size");
        $packingdetail_sql = $this->db->get_compiled_select(null, true);
        
        $sql = "select SI.item_code, SI.color, SI.size, item_name, trim(unit) as unit, tondau, trim(invoice_no) as invoice_no, arrival_date, sl_nhap, sl_xuat, toncuoi, quantity.inspect_ok as sl_ok, quantity.inspect_ng as sl_ng, sl_cho_xuat, quantity.inspect_ok - (case when sl_cho_xuat is NULL then 0 else sl_cho_xuat end) as sl_con_lai, warehouse.komoku_name_2 as warehouse
        from t_store_item SI
        LEFT JOIN m_komoku warehouse ON SI.warehouse = warehouse.kubun AND warehouse.komoku_id = 'KM0004'
        LEFT JOIN (
            $tondau_sql
        ) tondau
        ON tondau.item_code = SI.item_code and tondau.color = SI.color and tondau.size = SI.size and tondau.warehouse = SI.warehouse
        LEFT JOIN (
            $quantity_sql
        ) quantity
        ON quantity.item_code = SI.item_code and quantity.color = SI.color and quantity.size = SI.size and quantity.warehouse = SI.warehouse
        LEFT JOIN (
            $toncuoi_sql
        ) toncuoi
        ON toncuoi.item_code = SI.item_code and toncuoi.color = SI.color and toncuoi.size = SI.size and toncuoi.warehouse = SI.warehouse
        LEFT JOIN (
            $sl_nhap_sql
        ) sl_nhap
        ON sl_nhap.item_code = SI.item_code and sl_nhap.color = SI.color and sl_nhap.size = SI.size and sl_nhap.warehouse = SI.warehouse
        LEFT JOIN (
            $sl_xuat_sql
        ) sl_xuat
        ON sl_xuat.item_code = SI.item_code and sl_xuat.color = SI.color and sl_xuat.size = SI.size and sl_xuat.warehouse = SI.warehouse
        LEFT JOIN (
            $packingdetail_sql
        ) pd
        ON pd.item_code = SI.item_code and pd.color = SI.color and pd.size = SI.size        
        ";
        //Get where for parent table
        if(!empty($created_from)){
            $this->db->where("SI.$select_date>=", $created_from);
        }
        if(!empty($created_to)){
            $this->db->where("SI.$select_date<=", $created_to.' 23:59:59');
        }
        $this->db->where_in("SI.warehouse", $warehouse_sql, false);
        if(!empty($params['item_code'])){
            $this->db->like("SI.item_code", $params['item_code']);
        }
        if(!empty($params['item_name'])){
            $this->db->like("SI.item_name", $params['item_name']);
        }
        if(!empty($params['salesman'])){
            $this->db->where("SI.salesman", $params['salesman']);
        }
        $sql_compile = $this->db->get_compiled_select(null, true);
        $where = str_replace("\"","",substr($sql_compile, stripos($sql_compile, "where")));
        if($where != "") $sql .= $where;
        $sql .= " order by SI.item_code";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    public function getUnitList(){
        $this->db->distinct();
        $this->db->select("unit as komoku_name_2");
        $this->db->from('t_store_item');
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    public function getAllSalemans(){
        $this->db->select("sales_man as sales_man_cd, CONCAT(first_name, last_name) as name");
        $this->db->from('t_store_item SI');
        $this->db->join('m_employee', 'sales_man = employee_id');
        $this->db->where(' SI.del_flg', false);
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return null;
    }
    
    public function insertStoreItem($data){
        if($this->db->insert('t_store_item', $data)){
            return TRUE;
        }
        return FALSE;
    }

    public function updateStoreItem($data, $flgChangeItem=false){
        if($flgChangeItem){
            $updatedata['salesman'] = $data['newsalesman'];
            if(isset($data['newitem_type'])){
                $updatedata['item_type'] = $data['newitem_type'];
            }
            if(isset($data['invoice_no'])){
                $updatedata['invoice_no'] = $data['invoice_no'];
            }
            $updatedata['warehouse'] = $data['newwarehouse'];
            $updatedata['quantity'] = !empty($data['quantity'])?$data['quantity']:0;
            $updatedata['inspect_ok'] = !empty($data['inspect_ok'])?$data['inspect_ok']:0;
            $updatedata['inspect_ng'] = !empty($data['inspect_ng'])?$data['inspect_ng']:0;
            $updatedata['note'] = $data['note'];
            $updatedata['edit_date'] = $data['edit_date'];
            $updatedata['edit_user'] = $data['edit_user'];
            $this->db->set($updatedata);
        }else{
            $this->db->set($data);
        }
        $this->db->like('salesman', $data['salesman'], 'both');
        $this->db->where("item_code", $data['item_code']);
        $this->db->like('color', $data['color'], 'both');
        $this->db->like('size', $data['size'], 'both');
        $this->db->where("item_type", $data['item_type']);
        $this->db->like('order_no', $data['order_no'], 'both');
        $this->db->where("warehouse", $data['warehouse']);
        $this->db->where("order_receive_no", $data['order_receive_no']);
        $this->db->where("partition_no", $data['partition_no']);
        $this->db->where("order_detail_no", $data['order_detail_no']);
        if($this->db->update("t_store_item")){
            return TRUE;
        }
        return FALSE;
    }

    public function deleteStoreItem($where){
        $this->db->where($where);
        if($this->db->delete('t_store_item')){
            return TRUE;
        }
        return FALSE;
    }
    function check_exists($where = array())
    {
	    $this->db->where($where);
	    //thuc hien cau truy van lay du lieu
		$query = $this->db->get("t_store_item");
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
    }
    function get_item($where = array())
    {
	    $this->db->where($where);
	    //thuc hien cau truy van lay du lieu
        $query = $this->db->get("t_store_item");
        $result = $query->result_array();
		return $result;
    }
    public function update($where, $data){
        $this->db->where($where);
        $this->db->update("t_store_item",$data);
        $num_update = $this->db->affected_rows();
        if($num_update > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function searchForAllocate($item_code, $item_color, $item_size){
        $col_lang = getColStatusByLang($this);
        $this->db->select("SI.salesman
                            ,SI.order_no
                            ,SI.order_detail_no
                            ,SI.item_type as item_type_code
                            ,SI.warehouse as warehouse_kubun
                            ,quantity
                            ,invoice_no
                            ,arrival_date
                            ,unit
                            ,sta.$col_lang as status
                            ,sta.kubun as status_id
                            ,arrsta.komoku_name_2 as arrival_status
                            ,inssta.komoku_name_2 as inspect_status
                            ,inspect_ok
                            ,inspect_ng
                            ,buy_price
                            ,sales_price
                            ,wh.komoku_name_2 as warehouse
                            ,it.komoku_name_2 as item_type
                            ,order_receive_no
                            ,partition_no
                            ");
        $this->db->from('t_store_item SI');
        // $this->db->join('m_employee', 'salesman = employee_id', 'left');
        // $this->db->join('m_company CO', 'CO.company_id = SI.order_user', 'left');
        
        $this->db->join('m_komoku sta', "SI.status = sta.kubun AND sta.komoku_id = 'KM0001'", 'left');
        $this->db->join('m_komoku arrsta', "SI.arrival_status = arrsta.kubun AND arrsta.komoku_id = 'KM0001'", 'left');
        $this->db->join('m_komoku inssta', "SI.inspect_status = inssta.kubun AND inssta.komoku_id = 'KM0001'", 'left');
        $this->db->join('m_komoku wh', "SI.warehouse = wh.kubun AND wh.komoku_id = 'KM0004'", 'left');
        $this->db->join('m_komoku it', "SI.item_type = it.kubun AND it.komoku_id = 'KM0020'", 'left');

        $this->db->where('SI.del_flg', "0");
        $this->db->where('trim("SI"."item_code")', $item_code);
        // $this->db->where('SI.color', $item_color);
        $this->db->where("SI.color = '".$item_color."'");
        $this->db->where("SI.size = '".$item_size."'");
        $this->db->where_not_in('SI.status', array('015','019'));
        $this->db->order_by('SI.salesman');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    // Created by Khanh
    // Date : 09/04/2018
    public function updateStoreItems($data){
        $this->db->trans_begin();
        if(sizeof($data) > 0){
            if(isset($data[0]['order_no'])){
                $this->db->where('order_no', $data[0]['order_no']);
                $this->db->delete('t_store_item');
            }
        }
        foreach($data as $item){
            if($item['size'] == null){
                $item['size'] = '';
            }
            $check_exist = $this->checkStoreItemExist($item);
            if($check_exist){
                $this->updateItem($item);
            }else{
                $this->insertItem($item);
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    /**
     * Check store_item is existed
     * Created by Khanh
     * // Date 09/04/2018
     *
     * @param string $params
     * @return object found store_item | null
     */
    public function checkStoreItemExist($params){
        $this->db->select("arrival_date, salesman, item_code, item_type, order_no, order_detail_no, warehouse");
        $this->db->from('t_store_item');
        if(isset($params['salesman'])){
            $this->db->where('salesman', $params['salesman']);
        }
        if(isset($params['item_code'])){
            $this->db->where('item_code', $params['item_code']);
        }
        if(isset($params['item_type'])){
            $this->db->where('item_type', $params['item_type']);
        }
        if(isset($params['order_no'])){
            $this->db->where('order_no', $params['order_no']);
        }
        if(isset($params['order_detail_no'])){
            $this->db->where('order_detail_no', $params['order_detail_no']);
        }
        if(isset($params['warehouse'])){
            $this->db->where('warehouse', $params['warehouse']);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return false;
    }

    // Created by Khanh
    // Date 09/04/2018
    // Update store item
    public function updateItem($params){
        $this->db->trans_begin();
        
        $this->db->where('salesman', $params['salesman']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('item_type', $params['item_type']);
        $this->db->where('order_no', $params['order_no']);
        if(isset($params['order_detail_no'])){
            $this->db->where('order_detail_no', $params['order_detail_no']);
        }
        $this->db->where('warehouse', $params['warehouse']);
        $query = $this->db->update('t_store_item', $params);
        $this->db->trans_complete();
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->db->trans_commit();
            return true;
        }
        $this->db->trans_rollback();
        return false;
    }

    // Created by Khanh
    // Date 09/04/2018
    // Insert store item
    public function insertItem($params){
        $this->db->trans_begin();

        $query = $this->db->insert('t_store_item', $params);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $query;
    }

    // Created by Khanh 
    // Date 12/04/2018
    // Check store item
    public function updateFlgStoreItem($params){
        $order_no_6 = ($params['order_no_6'] != null && $params['order_no_6'] != '') ? ('-'.trim($params['order_no_6'])) : "";
        $order_no_4 = str_pad($params['order_no_4'], 4, '0', STR_PAD_LEFT);
        $order_no = $params['order_no_1'].'-'.$params['order_no_2'].'-'.$params['order_no_3'].'-'.$order_no_4.'/'.$params['order_no_5'].$order_no_6;
        $data = array('order_no' => $order_no);
        $check_exist = $this->checkStoreItemExist($data);
        $flag_fail = '0' ;
        if($check_exist){
            foreach($check_exist as $item){
                if($item['arrival_date'] == null){
                    $data_update = array(
                        'salesman'          => $item['salesman'],
                        'item_code'         => $item['item_code'],
                        'item_type'         => $item['item_type'],
                        'order_no'          => $item['order_no'],
                        'order_detail_no'   => $item['order_detail_no'],
                        'warehouse'         => $item['warehouse'],
                        'del_flg'           => '1'
                    );
                    $result = $this->updateItem($data_update);
                    if($result == false){
                        $flag_fail = '1' ;
                    }
                }
            }

            if($flag_fail == '1'){
                return false;
            }else{
                return true;
            }
        }
    }

    public function insertItemFromExcel($data){
        $this->db->trans_begin();
        $errorMessage = Array();
        foreach($data as $row){
            $this->db->insert('t_store_item', $row);
        }
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }
        $this->db->trans_commit();
        return TRUE;
    }
    public function getAllPVNo(){
        $this->db->select("order_receive_no, item_code, color, size");
        $this->db->from('t_store_item');
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
    public function getStoreItemsByOrderReceiveNo($pvNoList){
        $this->db->select("order_receive_no, item_code, item_name, color, size");
        $this->db->from('t_store_item');
        $this->db->where('del_flg', '0');
        $this->db->where_in('order_receive_no', $pvNoList);
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
    public function getStoreItemsByID($params){
        $result = array();
        foreach ($params['order_receive_no'] as $value){
            $pvNO = $value;
            $partitionNo = 1;
            if(strrpos($value, '-') !== FALSE){
                $pvNO = substr($value, 0, strrpos($value, '-'));
                $partitionNo = substr($value, strrpos($value, '-') + 1);
            }
            $this->db->select("TO_CHAR(arrival_date, 'YYYY-MM-DD') as arrival_date, quantity");
            $this->db->from('t_store_item');
            $this->db->where('del_flg', '0');
            $this->db->where('color', $params['color']);
            $this->db->where('size', $params['size']);
            $this->db->where('trim(order_receive_no)', trim($pvNO));
            $this->db->where('partition_no', $partitionNo);
            $this->db->where('item_code', $params['item_code']);
            $query = $this->db->get();
            $result = array_merge($result, $query->result_array());
        }
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    // Created by Khanh
    // Date : 24/04/2018
    // update status when accept orders out
    public function updateStatusAccept($params = null){
        $this->db->trans_begin();
        $this->db->set('status', '010');
        $this->db->set('edit_user', $params['edit_user']);
        $this->db->set('edit_date', $params['edit_date']);
        $this->db->where('order_no', $params['order_no']);
        if(isset($params['order_detail_no'])){
            $this->db->where('order_detail_no', $params['order_detail_no']);
        }
        $query = $this->db->update('t_store_item');
        $this->db->trans_complete();
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->db->trans_commit();
            return true;
        }
        $this->db->trans_rollback();
        return false;
    }

    public function updateStatus($params = null){
        $this->db->trans_begin();
        $this->db->where('order_no', $params['order_no']);
        $query = $this->db->update('t_store_item', $params);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function getHistory($params){
        $this->db->select("create_date, quantity, inspect_ok, inspect_ng, note");
        $this->db->from("t_store_item_his");
        $this->db->order_by("create_date", "des");
        $pvNo = substr($params["order_receive_no"],2);
        $this->db->where("trim(order_receive_no) like '__$pvNo'");
        unset($params["order_receive_no"]);
        $this->db->where($params);
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function getStoreItemsByKey($store){
        $this->db->select('*');
        $this->db->from('t_store_item');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $store['order_receive_no']);
        $this->db->where('partition_no', $store['partition_no']);
        $this->db->where('salesman', $store['salesman']);
        $this->db->where('item_code', $store['item_code']);
        $this->db->where('item_type', $store['item_type']);
        $this->db->where('order_no', $store['order_no']);
        $this->db->where('order_detail_no', $store['order_detail_no']);
        $this->db->where('size', $store['size']);
        $this->db->where('color', $store['color']);
        $this->db->where('inspect_ok > 0');
        return $this->db->get()->result_array();
    }
    public function checkStoreItemExistToUpdate($params){
        $this->db->select("*");
        $this->db->from('t_store_item');
        if(isset($params['salesman'])){
            $this->db->where('salesman', $params['salesman']);
        }
        if(isset($params['item_code'])){
            $this->db->where('item_code', $params['item_code']);
        }
        if(isset($params['item_type'])){
            $this->db->where('item_type', $params['item_type']);
        }
        if(isset($params['order_no'])){
            $this->db->where('order_no', $params['order_no']);
        }
        if(isset($params['warehouse'])){
            $this->db->where('warehouse', $params['warehouse']);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return false;
    }
}
?>
