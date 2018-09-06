<?php
class Orders_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Created by Khanh
    // Date : 05/04/2018
    public function get_orders($params = null, $start=null, $length=null, &$recordsTotal=null, &$recordsFiltered=null, $order=null)
    {
        function multiexplode ($delimiters,$string) {
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
        }
        // get status name
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun, $col_lang as status_name");
        $this->db->from('m_komoku');
        $this->db->where('m_komoku.komoku_id', KOMOKU_STATUS);
        $this->db->where('m_komoku.kubun <>', '000');
        $this->db->where('m_komoku.del_flg', '0');
        $status_ = $this->db->get_compiled_select();

        $this->db->select('distinct on (t_orders_details.order_no_1, t_orders_details.buyer_kb ,t_orders_details.order_no_2,t_orders_details.order_no_3,t_orders_details.order_no_4, t_orders_details.order_no_5,t_orders_details.order_date)*');
        $this->db->from('t_orders_details');
        
        // if (isset($params['order_no'])) {
        //     $order_no_data =  multiexplode(array("/","-"),$params['order_no']);
        //     if(isset($order_no_data[0])){
        //         $this->db->where('t_orders_details.order_no_1', $order_no_data[0]);
        //     }
        //     if(isset($order_no_data[1])){
        //         $this->db->where('t_orders_details.order_no_2', $order_no_data[1]);
        //     }
        //     if(isset($order_no_data[2])){
        //         $this->db->where('t_orders_details.order_no_3', $order_no_data[2]);
        //     }
        //     if(isset($order_no_data[3]) && trim($order_no_data[3]) != ''){
        //         $temp = explode("(", $order_no_data[3]);
        //         $this->db->where('t_orders_details.order_no_4', $temp[0]);
        //         if(isset($temp[1])){
        //             $this->db->where('t_orders_details.buyer_kb', '2');
        //         } else {
        //             $this->db->where('t_orders_details.buyer_kb', '1');
        //         }
        //     }
        //     if(isset($order_no_data[4]) && trim($order_no_data[4]) != ''){
        //         $this->db->where('t_orders_details.order_no_5', $order_no_data[4]);
        //     }
        // }
        if (isset($params['item_code'])) {
            $this->db->like('t_orders_details.item_code', $params['item_code']);
        }
        $this->db->where('t_orders_details.del_flg', '0');
        $order_detail_ = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->from('t_orders odr');
        $this->db->join("($order_detail_) orde", "orde.order_no_1 = odr.order_no_1 
                                                    and orde.order_no_2 = odr.order_no_2 
                                                    and orde.order_no_3 = odr.order_no_3 
                                                    and orde.order_no_4 = odr.order_no_4
                                                    and orde.buyer_kb = odr.buyer_kb 
                                                    and orde.order_no_5 = odr.order_no_5 ", 'inner');
        $this->db->join('m_employee', 'odr.order_user = m_employee.employee_id', 'left');
        $this->db->join("m_company", "odr.supplier_name = m_company.company_name", 'left');
        $this->db->join("($status_) st", "odr.status = st.kubun", 'left');
        $recordsTotal = $this->db->count_all_results(null, false);

        $this->db->where('odr.del_flg', '0');
        if (isset($params['order_user'])) {
            $this->db->where('odr.order_user', $params['order_user']);
        }
        if(isset($params['select_date'])){
            if (isset($params['order_date_from'])) {
                $date= date_create($params['order_date_from']);
                $date->setTime(00, 00, 00);
                $params['order_date_from'] = date_format($date,"Y/m/d H:i:s");
                $this->db->where("odr.${params['select_date']} >=", $params['order_date_from']);
            }
            if (isset($params['order_date_to'])) {
                $date = date_create($params['order_date_to']);
                $date->setTime(23, 59, 59);
                $params['order_date_to'] = date_format($date,"Y/m/d H:i:s");
                $this->db->where("odr.${params['select_date']} <=", $params['order_date_to']);
            }
        }
        if (isset($params['status'])) {
            $this->db->where('odr.status', $params['status']);
        }
        
        $this->db->select(" odr.order_no_1,
                            odr.order_no_2,
                            odr.order_no_3,
                            odr.order_no_4,
                            odr.order_no_5,
                            odr.buyer_kb,
                            trim(odr.order_no_6) as order_no_6,
                            odr.order_date,
                            odr.supplier_name,
                            odr.amount,
                            odr.currency,
                            odr.delivery_date,
                            odr.apply_user,
                            odr.apply_date,
                            odr.accept_user,
                            odr.accept_date,
                            odr.denial_user,
                            odr.denial_date,
                            odr.po_sheet_date,
                            odr.create_user,
                            odr.edit_user,
                            odr.create_date,
                            odr.edit_date,
                            odr.customs_clearance_sheet_no,
                            odr.customs_clearance_fee,
                            odr.transport_fee,
                            odr.note_denial,
                            m_company.reference,
                            st.*,
                            CONCAT(m_employee.first_name,' ', m_employee.last_name)  as order_user");
        if($order && count($order) > 0){
            $dir = $order[0]['dir'];
            $col = $order[0]['column'];
            if($col == 'order_no'){
                $this->db->order_by("odr.order_no_2,odr.order_no_4,odr.order_no_5",  $dir);
            } else {
                $this->db->order_by($col, $dir);
            }
        }else{
            $this->db->order_by("odr.order_no_4", "ASC");
        }
        

        if(isset($params['order_no']) && $params['order_no'] != '' && $params['order_no'] != null){
            $query = $this->db->get();
            $result = $query->result_array();
            $responseDt = array();
            $tmpResult = array();
            foreach ($result as $key => $po) {
                $order_no_6 = ($po['order_no_6'] != null && $po['order_no_6'] != "") ? ('-'.$po['order_no_6']) : "";
                $order_no = $po['order_no_1'].'-'.$po['order_no_2'].'-'.$po['order_no_3'].'-'.substr('0000'.$po['order_no_4'], strlen($po['order_no_4'])).($po['buyer_kb'] == '2' ? '(HN)' : '').'/'.$po['order_no_5'].$order_no_6;
                if(stristr($order_no, $params['order_no'])) {
                    array_push($tmpResult, $po);
                }
            }
            
            $recordsFiltered = sizeof($tmpResult);

            foreach ($tmpResult as $key => $value) {
                if( $start <= $key && $key < ($start+$length)) {
                    array_push($responseDt, $value);
                }
            }
            return $responseDt;
        } 
        // TODO COUNT FILTER
        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    // Created by Khanh
    // Date : 05/04/2018
    public function get_orders_details($params){
        $this->db->select('odr.*, com.short_name');
        $this->db->from('t_orders odr');
        $this->db->join('m_company com', "com.del_flg = '0' AND odr.supplier_name = com.company_name",'left');
        $this->db->where('odr.order_no_1', $params['order_no_1']);
        $this->db->where('odr.order_no_2', $params['order_no_2']);
        $this->db->where('odr.order_no_3', $params['order_no_3']);
        $this->db->where('odr.order_no_4', $params['order_no_4']);
        $this->db->where('odr.order_no_5', $params['order_no_5']);
        $this->db->where('odr.buyer_kb', $params['buyer_kb']);

        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result_array();
    }

    // Delete orders - create by: thanh
    // Edit by Khanh
    // Date : 12/04/2018
    public function deleteOrders($params = null)
    {
        $data = array(
            'del_flg' => '1',
        );
        $this->db->where('order_no_1', $params['order_no_1']);
        $this->db->where('order_no_2', $params['order_no_2']);
        $this->db->where('order_no_3', $params['order_no_3']);
        $this->db->where('order_no_4', $params['order_no_4']);
        $this->db->where('order_no_5', $params['order_no_5']);
        $this->db->where('order_date', $params['order_date']);
        $query = $this->db->update('t_orders', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->db->trans_commit();
            return true;
        }
        $this->db->trans_rollback();
        return false;
    }

    // Update orders - create by: thanh
    // Edit by Khanh
    // Date : 06/04/2018
    public function updateOrderOut($params = null)
    {
        if(isset($params['order_no_1'])){
            $this->db->where('order_no_1', $params['order_no_1']);
        }
        if(isset($params['order_no_2'])){
            $this->db->where('order_no_2', $params['order_no_2']);
        }
        if(isset($params['order_no_3'])){
            $this->db->where('order_no_3', $params['order_no_3']);
        }
        if(isset($params['order_no_4'])){
            $this->db->where('order_no_4', $params['order_no_4']);
        }
        if(isset($params['order_no_5'])){
            $this->db->where('order_no_5', $params['order_no_5']);
        }
        if(isset($params['order_date'])){
            $this->db->where('order_date', $params['order_date']);
        }
        if(isset($params['buyer_kb'])){
            $this->db->where('buyer_kb', $params['buyer_kb']);
        }
        $this->db->where('del_flg', '0');
        if(isset($params['note'])){
            $new_note = $params['note'];
            $this->db->set('note', "coalesce(note,'') || '$new_note'", false);
            unset($params['note']);
        }
        $query = $this->db->update('t_orders', $params);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    // check orders is exists? create by: thanh
    public function check_orders_exists($params = null)
    {
        // set edit date = null
        $order_date = $params['order_date'];
        if ($params['order_date'] == '') {
            $order_date = null;
        }
        $this->db->select('order_no_1,order_no_2,order_no_3,order_no_4,order_no_5,order_no_6,order_date,edit_date');
        $this->db->from('t_orders');
        $this->db->where('order_no_1', $params['order_no_1']);
        $this->db->where('order_no_2', $params['order_no_2']);
        $this->db->where('order_no_3', $params['order_no_3']);
        $this->db->where('order_no_4', $params['order_no_4']);
        $this->db->where('order_no_5', $params['order_no_5']);
        $this->db->where('buyer_kb', $params['buyer_kb']);
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Created By Khanh
    // Date : 02/04/2018
    // get data for oders out header page
    public function getLastOderNo4($buyer_kb = '1'){
        $this->db->select('order_no_4');
        $this->db->from('t_orders');
        $this->db->where('order_no_5', substr(date("Y"), -2));
        $this->db->where('buyer_kb', $buyer_kb);
        $this->db->order_by('order_no_4', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array()[0]['order_no_4'];
        } else {
            return '1000';
        }
    }

    // Created By Khanh
    // Date : 03/04/2018
    // Insert data to orders table
    public function insertOrderOut($params = null){
        $this->db->trans_begin();
        $query = $this->db->insert('t_orders', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $query;
    }

    // Created By Khanh
    // Date : 04/04/2018
    // Insert data to orders detail table
    public function saveOrderDetail($params, $dataList)
    {
        $this->db->trans_begin();
        //delete
        $detailNo2 = array();
        $detailNo4 = array();
        $detailNo5 = array();
        foreach ($dataList as $data) {
            $data = (array) $data;
            if (isset($data['id'])) {
                $detailNo2[] = $data['order_no_2'];
                $detailNo4[] = $data['order_no_4'];
                $detailNo5[] = $data['order_no_5'];
            }
        }
        if(isset($params['order_no_1'])){
            $this->db->where('order_no_1', $params['order_no_1']);
        }
        if(isset($params['order_no_2'])){
            $this->db->where('order_no_2', $params['order_no_2']);
        }
        if(isset($params['order_no_3'])){
            $this->db->where('order_no_3', $params['order_no_3']);
        }
        if(isset($params['order_no_4'])){
            $this->db->where('order_no_4', $params['order_no_4']);
        }
        if(isset($params['order_no_5'])){
            $this->db->where('order_no_5', $params['order_no_5']);
        }
        if(isset($params['item_code'])){
            $this->db->where('item_code', $params['item_code']);
        }
        
        if (count($detailNo2) > 0) {
            $this->db->where_not_in('order_no_2', $detailNo2);
        }
        if (count($detailNo4) > 0) {
            $this->db->where_not_in('order_no_4', $detailNo4);
        }
        if (count($detailNo5) > 0) {
            $this->db->where_not_in('order_no_5', $detailNo5);
        }

        $this->db->update('t_orders_details', array('del_flg' => '1'));
        $num_inserts = $this->db->affected_rows();
        // add or update
        foreach ($dataList as $data) {
            $data = (array) $data;
            if (isset($data['order_no_2']) && isset($data['order_no_4']) && isset($data['order_no_5'])) {
                $this->updateDetailOrder($params, $data);
            } else {
                $this->insertDetailOrder($params, $data);
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

    // get data from order_details 
    public function getDetailOrders($order_no_1 = null, $order_no_2 = null, $order_no_3 = null, $order_no_4 = null, $order_no_5 = null, $buyer_kb = null, $order_no_6 = null,$params = null){
        // get color name
        $this->db->select('kubun, komoku_name_2 as color_name');
        $this->db->from('m_komoku');
        $this->db->where('m_komoku.komoku_id', KOMOKU_COLOR);
        $this->db->where('m_komoku.kubun <>', '000');
        $this->db->where('m_komoku.del_flg', '0');
        $color_ = $this->db->get_compiled_select();

        // get Warehouse name
        $this->db->select('kubun, komoku_name_2 as warehouse_name');
        $this->db->from('m_komoku');
        $this->db->where('m_komoku.komoku_id', KOMOKU_WAREHOUSE);
        $this->db->where('m_komoku.kubun <>', '000');
        $this->db->where('m_komoku.del_flg', '0');
        $warehouse_ = $this->db->get_compiled_select();

        // get type name
        $this->db->select('kubun, komoku_name_2 as type_name');
        $this->db->from('m_komoku');
        $this->db->where('m_komoku.komoku_id', KOMOKU_ITEMTYPE);
        $this->db->where('m_komoku.kubun <>', '000');
        $this->db->where('m_komoku.del_flg', '0');
        $type_ = $this->db->get_compiled_select();
        $this->db->distinct();
        $this->db->select(" 
                            odr.order_no_1,
                            odr.order_no_2,
                            odr.order_no_3,
                            odr.order_no_4,
                            odr.order_no_5,
                            trim(odr.order_no_6) as order_no_6,
                            odr.order_date,
                            odr.order_detail_no,
                            odr.item_type,
                            odr.warehouse,
                            odr.item_code,
                            odr.item_name,
                            odr.composition_1,
                            odr.composition_2,
                            odr.composition_3,
                            odr.odr_quantity,
                            odr.size,
                            odr.size_unit,
                            odr.color,
                            odr.unit,
                            odr.price,
                            odr.amount,
                            odr.note,
                            odr.status,
                            odr.salesman,
                            odr.edit_date,
                            odr.vendor,
                            odr.odr_recv_no,
                            odr.odr_recv_detail_no,
                            odr.partition_no,
                            odr.odr_recv_date,
                            odr.surcharge_po,
                            col.*,
                            ty.*,
                            wa.*,
                           ORDER.supplier_name,
                           ORDER.currency,
                           concat((case when (surcharge_unit_color_usd IS NOT NULL or surcharge_unit_color_vnd IS NOT NULL or surcharge_unit_color_jpy IS NOT NULL) then 'Surcharge by 1 UNIT/COLOR; ' else '' end), 
                           (case when (surcharge_color_usd IS NOT NULL or surcharge_color_vnd IS NOT NULL or surcharge_color_jpy IS NOT NULL) then 'Surcharge by 1 Color; ' else '' end)) as surcharge_text, 
                           ");
        $this->db->from('t_orders_details odr');
        $this->db->join("($color_) col", "odr.color = col.kubun", 'left');
        $this->db->join("($type_) ty", "odr.item_type = ty.kubun", 'left');
        $this->db->join("($warehouse_) wa", "odr.warehouse = wa.kubun", 'left');
        $this->db->join("t_orders ORDER", "ORDER.order_no_1 = odr.order_no_1 AND ORDER.order_no_2 = odr.order_no_2 
                                            AND ORDER.order_no_3 = odr.order_no_3 AND ORDER.order_no_4 = odr.order_no_4 
                                            AND ORDER.order_no_5 = odr.order_no_5 AND ORDER.buyer_kb = odr.buyer_kb
                                            AND ORDER.order_date = odr.order_date", 'left');
        $this->db->join('m_surcharge sur',"sur.item_code = odr.item_code and (sur.color = '' or sur.color IS NULL or sur.color = odr.color) and (sur.size = '' or sur.size IS NULL or sur.size = odr.size) and (qty_by_color_from <= odr.odr_quantity) and (qty_by_color_to >= odr.odr_quantity)",'left');
        $this->db->where('odr.del_flg', '0');
        $this->db->where('odr.order_no_1', $order_no_1);
        $this->db->where('odr.order_no_2', $order_no_2);
        $this->db->where('odr.order_no_3', $order_no_3);
        $this->db->where('odr.order_no_4', $order_no_4);
        $this->db->where('odr.order_no_5', $order_no_5);
        $this->db->where('odr.buyer_kb', $buyer_kb);
        
        if (!empty($params['item_type'])) {
            $this->db->where('item_type', $params['item_type']);
        }
        if (!empty($params['order_detail_no'])) {
            $this->db->where('order_detail_no', $params['order_detail_no']);
        }

        $this->db->order_by('item_code');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    //  get order detail no with order no
    public function getMaxDetailNo($params = null){
        $this->db->select('order_detail_no');
        $this->db->from('t_orders_details');
        if(!empty($params['order_no_1'])){
            $this->db->where('order_no_1', $params['order_no_1']);
        }
        if(!empty($params['order_no_2'])){
            $this->db->where('order_no_2', $params['order_no_2']);
        }
        if(!empty($params['order_no_3'])){
            $this->db->where('order_no_3', $params['order_no_3']);
        }
        if(!empty($params['order_no_4'])){
            $this->db->where('order_no_4', $params['order_no_4']);
        }
        if(!empty($params['order_no_5'])){
            $this->db->where('order_no_5', $params['order_no_5']);
        }
        if(!empty($params['buyer_kb'])){
            $this->db->where('buyer_kb', $params['buyer_kb']);
        }
        $this->db->order_by('order_detail_no' ,'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    // Update data to order detail and store item
    public function updateOrderDetail($params , $dataList){
        $this->db->trans_begin();

        $order_detail_exist = $this->checkOrderDetailsExist($params);
        if($order_detail_exist){
            foreach($order_detail_exist as $item){
                $result = $this->deleteOrderDetail($item);
            }
        }
        foreach ($dataList as &$data) {
            if(isset($params['edit_user'])){
                $data['edit_user'] = $params['edit_user'];
                $data['create_user'] = $params['edit_user'];
            }
            if(isset($params['edit_date'])){
                $data['edit_date'] = $params['edit_date'];
                $data['create_date'] = $params['edit_date'];
            }
            $this->insertOrderDetail($data);
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

    // Delete order detail 
    public function deleteOrderDetail($params){
        $this->db->trans_begin();

        $this->db->where('order_no_1', $params['order_no_1']);
        $this->db->where('order_no_2', $params['order_no_2']);
        $this->db->where('order_no_3', $params['order_no_3']);
        $this->db->where('order_no_4', $params['order_no_4']);
        $this->db->where('order_no_5', $params['order_no_5']);
        $this->db->where('buyer_kb', $params['buyer_kb']);
        $this->db->where('order_detail_no', $params['order_detail_no']);
        $this->db->delete('t_orders_details');

        $this->db->trans_complete();
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return true;
        }
        return false;
    }

    // Set del_flg order detail => 1
    public function updateFlgOrdersDetail($params){
        $this->db->trans_begin();

        $data = array(
            'del_flg' => '1',
        );
        
        $this->db->where('order_no_1', $params['order_no_1']);
        $this->db->where('order_no_2', $params['order_no_2']);
        $this->db->where('order_no_3', $params['order_no_3']);
        $this->db->where('order_no_4', $params['order_no_4']);
        $this->db->where('order_no_5', $params['order_no_5']);
        $query = $this->db->update('t_orders_details', $data);
        $this->db->trans_complete();
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->db->trans_commit();
            return true;
        }
        $this->db->trans_rollback();
        return false;
    }

    // Insert order details
    public function insertOrderDetail($data)
    {
        $this->db->insert('t_orders_details', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return true;
        }
        return false;
    }

    // check order detail exist
    public function checkOrderDetailsExist($params){
        $this->db->select('order_no_1,order_no_2,order_no_3,order_no_4,order_no_5,order_no_6,order_date,order_detail_no,odr_recv_no, buyer_kb');
        $this->db->from('t_orders_details');
        $this->db->where('order_no_1' , $params['order_no_1']);
        $this->db->where('order_no_2' , $params['order_no_2']);
        $this->db->where('order_no_3' , $params['order_no_3']);
        $this->db->where('order_no_4' , $params['order_no_4']);
        $this->db->where('order_no_5' , $params['order_no_5']);
        $this->db->where('order_date' , $params['order_date']);
        $this->db->where('buyer_kb' , $params['buyer_kb']);
        // $this->db->where('order_detail_no' , $params['order_detail_no']);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getOrderInfo($params){
        // $this->db->distinct();
        $this->db->select("OD.order_no_1, OD.order_no_2, OD.order_no_3, OD.order_no_4, OD.order_no_5, OD.order_date, company.company_name as supplier_name, OD.delivery_company, OD.address, OD.shipping_mark, OD.delivery_date, 
        ODD.warehouse, ko2.komoku_name_2 as item_type, OD.currency, OD.apparel, EM.last_name, EM.first_name, ODD.unit, ODD.size_unit, ODD.note, IT.note_lapdip, ODD.composition_1, ODD.composition_2, ODD.composition_3,
        ODD.item_code, ODD.item_name, ODD.size, ODD.color, ODD.price, ODD.amount, ODD.odr_quantity, ODD.surcharge_po, OD.delivery_plan_date, OD.payment, OD.reference, OD.revise_date");
        $this->db->from('t_orders OD');
        $this->db->join('t_orders_details ODD', "ODD.order_no_1 = OD.order_no_1 AND ODD.order_no_2 = OD.order_no_2 
                                                AND ODD.order_no_3 = OD.order_no_3 AND ODD.order_no_4 = OD.order_no_4 
                                                AND ODD.order_no_5 = OD.order_no_5 AND ODD.order_date = OD.order_date
                                                AND ODD.del_flg = '0' AND ODD.buyer_kb = '".$params['buyer_kb']."'", 'left');
        $this->db->join('m_items IT', "ODD.item_code = IT.item_code AND ODD.size = IT.size 
                                                AND ODD.color = IT.color AND IT.del_flg = '0'", 'left');
        $this->db->join('m_company company', "company.short_name = OD.supplier_name AND company.del_flg = '0' AND company.type = '2'", 'left');
        $this->db->join('m_komoku ko2', "ko2.komoku_id = '".KOMOKU_ITEMTYPE."' AND ODD.item_type = ko2.kubun AND ko2.del_flg = '0'", 'left');
        $this->db->join('m_employee EM', "EM.employee_id =  OD.accept_user AND EM.del_flg = '0'", 'left');
        $this->db->where('OD.order_no_1' , $params['order_no_1']);
        $this->db->where('OD.order_no_2' , $params['order_no_2']);
        $this->db->where('OD.order_no_3' , $params['order_no_3']);
        $this->db->where('OD.order_no_4' , $params['order_no_4']);
        $this->db->where('OD.order_no_5' , $params['order_no_5']);
        $this->db->where('OD.order_date' , $params['order_date']);
        $this->db->where('OD.buyer_kb' , $params['buyer_kb']);
        $this->db->where('OD.del_flg' , '0');
        $query = $this->db->get();
        // echo $this->db->last_query();
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }
    public function getOrderToPrint($params){
        $this->db->select("COM.company_name as supplier_name, ORD.address, COH.head_office_address, COH.head_office_phone, COH.head_office_tel, COH.head_office_fax");
        $this->db->from('t_orders ORD');
        $this->db->join('m_company COM', 'trim("COM"."short_name") =  trim("ORD"."supplier_name") AND COM.del_flg = \'0\'', 'left');
        $this->db->join('m_company_headoffice COH', "COM.company_id =  COH.company_id AND COH.del_flg = '0'", 'left');
        $this->db->where('ORD.order_no_1' , $params['order_no_1']);
        $this->db->where('ORD.order_no_2' , $params['order_no_2']);
        $this->db->where('ORD.order_no_3' , $params['order_no_3']);
        $this->db->where('ORD.order_no_4' , $params['order_no_4']);
        $this->db->where('ORD.order_no_5' , $params['order_no_5']);
        $this->db->where('ORD.order_date' , $params['order_date']);
        $this->db->where('ORD.buyer_kb' , $params['buyer_kb']);
        $this->db->where('ORD.del_flg' , '0');
        $query = $this->db->get();
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array()[0];
        }
        return [];
    }

    public function getSupplierInfoByPO($params){
        $this->db->select("KOM.komoku_name_3 as payment_term, COM.transportation, COM.company_id, EMP.last_name, EMP.first_name,
                         COM.bank_info, COM.short_name as supplier_name, string_agg(distinct \"DET\".surcharge_po, ' ; \r\n') as surcharge_infor");
        $this->db->from('t_orders ORD');
        $this->db->join('m_company COM', 'trim("COM"."short_name") = trim("ORD"."supplier_name") AND COM.del_flg = \'0\' AND COM.type = \'2\'', 'left');
        $this->db->join('m_komoku KOM', "KOM.kubun =  COM.payment_term AND KOM.komoku_id = '". KOMOKU_PAYMENT ."' AND KOM.del_flg = '0'", 'left');
        $this->db->join('m_employee EMP', "EMP.employee_id =  ORD.accept_user AND EMP.del_flg = '0'", 'left');
        $this->db->join('t_orders_details DET', "ORD.order_no_1 =  DET.order_no_1 AND ORD.order_no_2 = DET.order_no_2 AND ORD.order_no_3 = DET.order_no_3 AND ORD.order_no_4 = DET.order_no_4 AND ORD.order_no_5 = DET.order_no_5 AND ORD.buyer_kb = DET.buyer_kb AND DET.del_flg = '0'", 'inner');
        $this->db->where('ORD.order_no_1' , $params['order_no_1']);
        $this->db->where('ORD.order_no_2' , $params['order_no_2']);
        $this->db->where('ORD.order_no_3' , $params['order_no_3']);
        $this->db->where('ORD.order_no_4' , $params['order_no_4']);
        $this->db->where('ORD.order_no_5' , $params['order_no_5']);
        $this->db->where('ORD.order_date' , $params['order_date']);
        $this->db->where('ORD.del_flg' , '0');
        $this->db->group_by('KOM.komoku_name_3, COM.transportation, COM.company_id, EMP.last_name, EMP.first_name, COM.bank_info, COM.short_name');
        $query = $this->db->get();
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array()[0];
        }
        return [];
    }

    public function getDataToExport($params){
        $result = [];
        foreach($params as $para){
            $para = (array)$para;
            $this->db->select("salesman, concat(odr.order_no_1,'-',odr.order_no_2,'-',odr.order_no_3,'-',odr.order_no_4,(case when odr.buyer_kb = '2' then '(HN)' else '' end),'/',odr.order_no_5) as order_no, trim(ord.odr_recv_no) as odr_recv_no, ord.item_code, odr.delivery_date,
            odr.currency, odr.supplier_name, ord.amount, (CASE WHEN pop.payment_term IS NOT NULL THEN pop.payment_term ELSE kmk.komoku_name_3 END) as payment_term, (CASE WHEN pop.header IS NOT NULL THEN pop.header ELSE orv.seller_kb END) as header, ord.buyer_kb");
            $this->db->from('t_orders odr');
            $this->db->join('t_orders_details ord',"ord.del_flg = '0' and ord.order_date = odr.order_date and odr.order_no_1 = ord.order_no_1 and odr.order_no_2 = ord.order_no_2 and 
                             odr.order_no_3 = ord.order_no_3 and odr.order_no_4 = ord.order_no_4 and odr.order_no_5 = ord.order_no_5 and odr.buyer_kb = ord.buyer_kb",'left');
            $this->db->join('m_company com',"com.del_flg = '0' and com.short_name = odr.supplier_name",'left');
            $this->db->join('m_komoku kmk',"kmk.del_flg = '0' and com.payment_term = kmk.kubun and komoku_id = '".KOMOKU_PAYMENT."'",'left');
            $this->db->join('t_orders_receive orv',"orv.del_flg = '0' and orv.order_receive_no = ord.odr_recv_no and orv.partition_no = ord.partition_no and orv.order_receive_date = ord.odr_recv_date",'left');
            $this->db->join('t_po_print pop',"pop.del_flg = '0' and pop.order_date = odr.order_date and pop.po_no = concat(odr.order_no_1,'-',odr.order_no_2,'-',odr.order_no_3,'-',odr.order_no_4,'/',odr.order_no_5)",'left');
            $this->db->where('odr.order_no_1' , $para['order_no_1']);
            $this->db->where('odr.order_no_2' , $para['order_no_2']);
            $this->db->where('odr.order_no_3' , $para['order_no_3']);
            $this->db->where('odr.order_no_4' , $para['order_no_4']);
            $this->db->where('odr.order_no_5' , $para['order_no_5']);
            $this->db->where('odr.buyer_kb' , $para['buyer_kb']);
            $this->db->where('odr.order_date' , $para['order_date']);
            $this->db->where('odr.del_flg' , '0');
            $query = $this->db->get();
            if (sizeof($query->result_array()) > 0) {
                $result = array_merge($result, $query->result_array());
            }
        }
        return $result;
    }

    /** Get Order data for draw order out chart */
    public function getOrdersOutData($params) {
        $arr_horizontal = array();
        $arr_vertical = array();
        $order_by = array();
        foreach ($params["horizontal_order"] as $key => $value) {
            if($value == "jp_code") {
                array_push($arr_horizontal, 'trim("IT"."' . $value . '") as '.$value.'');
                array_push($order_by, 'trim("IT"."' . $value . '")');
            } else {
                if($value == "short_name") {
                    array_push($arr_horizontal, 'trim("CO"."' . $value . '") as '.$value.'');
                    array_push($order_by, 'trim("CO"."' . $value . '")');
                } else {
                    array_push($arr_horizontal, 'trim("OD"."' . $value . '") as '.$value.'');
                    array_push($order_by, 'trim("OD"."' . $value . '")');
                }
            }
        }
        foreach ($params["vertical"] as $key => $value) {
            if($value == "quantity") {
                array_push($arr_vertical, 'sum("OD"."odr_' . $value . '") as quantity');
            } else {
                array_push($arr_vertical, 'sum("OD"."' . $value . '") as amount');
            }
        }
        array_push($arr_horizontal, "O.currency");
        array_push($order_by, "O.currency");
        $select = implode(",", $arr_horizontal) . "," . implode(",", $arr_vertical);

        $this->db->select($select);
        $this->db->from("t_orders_details OD");
        $this->db->join("t_orders O", "O.order_no_1 = OD.order_no_1 and O.order_no_2 = OD.order_no_2 and O.order_no_3 = OD.order_no_3 and O.order_no_4 = OD.order_no_4 and O.order_no_5 = OD.order_no_5 and O.buyer_kb = OD.buyer_kb and O.order_date = OD.order_date", "left");
        $this->db->join("m_company CO", "CO.company_name = O.supplier_name and CO.type = '2'", "left");
        $this->db->join("m_items IT", "IT.item_code = OD.item_code and IT.size = OD.size and IT.color = OD.color", "left");
        if(isset($params["date_from"])) {
            $this->db->where('OD.order_date >=', $params["date_from"]);
        }
        if(isset($params["date_to"])) {
            $this->db->where('OD.order_date <=', $params["date_to"]);
        }
        $this->db->where('OD.del_flg' , '0');
        $this->db->group_by($order_by);
        $query = $this->db->get();
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }
}
