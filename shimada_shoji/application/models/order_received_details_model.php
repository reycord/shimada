<?php
class order_received_details_model extends CI_Model
{
    public function __construct()
    {
        // parent::__construct();
        $this->load->database();
        $this->load->library('common');
        $this->load->model('order_received_model');
    }
    public function getAllOrderReceivedDetails()
    {
        $this->db->select("*");
        $this->db->from('t_orders_receive_details ORD');
        $this->db->join('m_items IT', 'IT.item_code = ORD.item_code', 'left');
        $this->db->where('ORD.del_flg', '0');
        $this->db->order_by('ORD.order_receive_detail_no', 'asc');
        $this->db->order_by('ORD.item_code', 'asc');
        $this->db->order_by('ORD.size', 'asc');
        $this->db->order_by('ORD.color', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
    // get order received by id
    public function getOrderReceivedByID($orderReceiveNo, $partitionNo, $orderReceiveDate, $start = null, $length = null,&$recordsTotal=null, &$recordsFiltered=null)
    {
        $this->db->select(" det.order_receive_no,
                            det.partition_no,
                            det.order_receive_date,
                            det.order_receive_detail_no,
                            det.item_code,
                            det.jp_code,
                            det.item_name,
                            det.inv_no,
                            CONCAT(det.composition_1,' ',det.composition_2,' ',det.composition_3) as composition,
                            det.composition_1,
                            det.composition_2,
                            det.composition_3,
                            det.quantity,
                            det.size_unit,
                            det.size,
                            det.color,
                            det.unit,
                            det.hikiate_quantity,
                            det.buy_price,
                            det.sell_price,
                            det.base_price,
                            det.amount,
                            det.amount_base,
                            det.status,
                            det.delivery_date,
                            det.edit_user,
                            det.edit_date,
                            rece.staff as salesman");
        $this->db->from('t_orders_receive_details det');
        $this->db->join("t_orders_receive rece","det.order_receive_no = rece.order_receive_no AND det.partition_no = rece.partition_no AND det.order_receive_date = rece.order_receive_date", "inner");
        $this->db->where('det.del_flg', '0');
        $this->db->where('det.order_receive_no', $orderReceiveNo);
        $this->db->where('det.partition_no', $partitionNo);
        $this->db->where('det.order_receive_date', $orderReceiveDate);
        $this->db->order_by('det.order_receive_detail_no', 'asc');
        $this->db->order_by('det.item_code', 'asc');
        $this->db->order_by('det.size', 'asc');
        $this->db->order_by('det.color', 'asc');
        $recordsTotal = $this->db->count_all_results(null, false);
        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);

        $query = $this->db->get();
        // echo $this->db->last_query();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
    
    public function deleteOrderReceiveItem($data, $editUser) {
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $this->db->update('t_orders_receive_details', array('del_flg' => '1',
                                                            'edit_user' => $editUser,
                                                            'edit_date' => date("Y-m-d H:i:s")));
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->TotalCalculator($data['order_receive_no'], $data['partition_no'], $data['order_receive_date']);
            return true;
        }
        return false;
    }

    public function updateOrderReceiveItem($data, $editUser) {
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $update_data = array(
            'quantity' => $data['quantity'],
            'sell_price' => $data['sell_price'],
            'amount' => $data['amount'],
            'amount_base' => $data['amount_base'],
            'edit_user' => $editUser,
            'edit_date' => date("Y-m-d H:i:s")
        );
        if(!empty($data['base_price'])){
            $update_data['base_price'] = $data['base_price'];
        }
        if(!empty($data['delivery_date'])){
            $update_data['delivery_date'] = $data['delivery_date'];
        }
        $this->db->update('t_orders_receive_details', $update_data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->TotalCalculator( $data['order_receive_no'],  $data['partition_no'],  $data['order_receive_date']);
            return true;
        }
        return false;
    }

    public function addOrderReceiveItem($data, $createUser) {
        $lastOrderReceiveDetailNo = $this->getLastReceivedOrderReceiveDetailNo($data['order_receive_no'], $data['partition_no'], $data['order_receive_date']);
        $lastOrderReceiveDetailNo = (int)$lastOrderReceiveDetailNo;
        $lastOrderReceiveDetailNo += 1;
        $lastOrderReceiveDetailNo  = str_pad("" . $lastOrderReceiveDetailNo, 3, '0', STR_PAD_LEFT);
        $data['order_receive_detail_no'] = $lastOrderReceiveDetailNo;
        $data['create_user'] = $createUser;
        $data['create_date'] = date("Y-m-d H:i:s");
        if(empty($data['base_price'])){
            unset($data['base_price']);
        }
        if(empty($data['delivery_date'])){
            unset($data['delivery_date']);
        }
        $this->db->insert('t_orders_receive_details', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->TotalCalculator($data['order_receive_no'], $data['partition_no'], $data['order_receive_date'] );
            $result = $this->getOrderReceiveItem($data['order_receive_no'], $data['partition_no'], $data['order_receive_date'], $data['order_receive_detail_no']);
            $now = date('Y-m-d H:i:s');
            $currentEmployeeId = $this->session->userdata("user")['employee_id'];
            $this->db->where('order_receive_no', $data['order_receive_no']);
            $this->db->where('partition_no', $data['partition_no']);
            $this->db->where('order_receive_date', $data['order_receive_date']);
            $this->db->update('t_orders_receive', array(
                'status' => '001',
                'edit_date' => $now,
                'edit_user' => $currentEmployeeId
            ));
            return $result;
        }       
        return false;
    }

    public function getOrderReceiveItem($orderReceiveNo, $partitionNo, $orderReceiveDate, $order_receive_detail_no) {
        $this->db->select(" order_receive_no,
                            partition_no,
                            order_receive_date,
                            order_receive_detail_no,
                            item_code,
                            item_name,
                            CONCAT(composition_1,' ',composition_2,' ',composition_3) as composition,
                            composition_1,
                            composition_2,
                            composition_3,
                            quantity,
                            size_unit,
                            size,
                            color,
                            unit,
                            quantity,
                            hikiate_quantity,
                            buy_price,
                            sell_price,
                            base_price,
                            amount,
                            amount_base,
                            status,
                            delivery_date,
                            edit_user,
                            edit_date");
        $this->db->from('t_orders_receive_details det');
        $this->db->where('det.del_flg', '0');
        $this->db->where('det.order_receive_no', $orderReceiveNo);
        $this->db->where('det.partition_no', $partitionNo);
        $this->db->where('det.order_receive_date', $orderReceiveDate);
        $this->db->where('det.order_receive_detail_no', $order_receive_detail_no);

        $result = $this->db->get()->result_array();
        return $result[0];
    }

    // get last received order details id
    public function getLastReceivedOrderReceiveDetailNo($orderReceiveNo, $partitionNo, $orderReceiveDate)
    {
        $this->db->select_max('order_receive_detail_no');
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $result = $this->db->get('t_orders_receive_details');  
        return $result->row()->order_receive_detail_no;
    }
    // insert received order details
    public function insertReceivedOrder($orderReceiveNo, $partitionNo, $orderReceiveDate, $data)
    {
        if(isset($data['edit_date'])){
            unset($data['edit_date']);
        }
        if(isset($data['edit_user'])){
            unset($data['edit_user']);
        }
        if(!isset($data['hikiate_quantity'])){
            $data['hikiate_quantity'] = 0;
        }
        $lastOrderReceiveDetailNo = $this->getLastReceivedOrderReceiveDetailNo($orderReceiveNo, $partitionNo, $orderReceiveDate);
        // print_r($lastOrderReceiveDetailNo);
        $lastOrderReceiveDetailNo = (int)$lastOrderReceiveDetailNo;
        $lastOrderReceiveDetailNo += 1;
        $lastOrderReceiveDetailNo  = str_pad("" . $lastOrderReceiveDetailNo, 3, '0', STR_PAD_LEFT);
        // print_r($lastOrderReceiveDetailNo);
        $data['order_receive_no'] = $orderReceiveNo;
        $data['partition_no'] = $partitionNo;
        $data['order_receive_date'] = $orderReceiveDate;
        $data['order_receive_detail_no'] = $lastOrderReceiveDetailNo;
        $this->db->insert('t_orders_receive_details', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return true;
        }
        return false;
    }
    // update received order details
    public function updateReceivedOrder($orderReceiveNo, $partitionNo, $orderReceiveDate, $data)
    {   
        if(isset($data['create_date'])){
            unset($data['create_date']);
        }
        if(isset($data['create_user'])){
            unset($data['create_user']);
        }
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $this->db->update('t_orders_receive_details', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return true;
        }
        return false;
    }
    // save change
    public function saveReceivedOrder($orderReceiveNo, $partitionNo, $orderReceiveDate, $dataList)
    {
        $this->db->trans_begin();

        //delete
        $detailNos = array();
        foreach ($dataList as $data) {
            $data = (array) $data;
            if (isset($data['order_receive_detail_no'])) {
                $detailNos[] = $data['order_receive_detail_no'];
            }
        }
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        if (count($detailNos) > 0) {
            $this->db->where_not_in('order_receive_detail_no', $detailNos);
        }
        $this->db->update('t_orders_receive_details', array('del_flg' => '1'));
        $num_inserts = $this->db->affected_rows();

        // add or update
        foreach ($dataList as $data) {
            $data = (array) $data;
            // print_r($data);
            if (isset($data['order_receive_detail_no'])) {
                $this->updateReceivedOrder($orderReceiveNo, $partitionNo, $orderReceiveDate, $data);
            } else {
                $this->insertReceivedOrder($orderReceiveNo, $partitionNo, $orderReceiveDate, $data);
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
    public function TotalCalculator($order_receive_no, $partition_no, $order_receive_date){
        $this->db->trans_begin();
        $this->db->select('sum(quantity) as sum_quantity, sum(amount) as sum_amount, sum(amount_base) as sum_amount_base');
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $order_receive_no);
        $this->db->where('partition_no', $partition_no);
        $this->db->where('order_receive_date', $order_receive_date);
        $this->db->group_by('order_receive_no, partition_no, order_receive_date');
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            $result = $result[0];
            $this->db->where('del_flg', '0');
            $this->db->where('order_receive_no', $order_receive_no);
            $this->db->where('partition_no', $partition_no);
            $this->db->where('order_receive_date', $order_receive_date);
            $this->db->update('t_orders_receive', $result);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    /**
     * Get order receivered Detail
     * 
     * @author Duc Tam
     * @param orderReceiveNo
     * @param partitionNo
     * @param orderReceiveDate
     * 
     * @return OrderReceiveredDetails/Array(0)
     */
    public function getOrderReceivedDetails($orderReceiveNo, $partitionNo, $orderReceiveDate){
        $this->db->select(" det.order_receive_no,
                            det.partition_no,
                            det.order_receive_date,
                            det.order_receive_detail_no,
                            det.item_code,
                            det.item_name,
                            CONCAT(composition_1,' ',composition_2,' ',composition_3) as composition,
                            det.composition_1,
                            det.composition_2,
                            det.composition_3,
                            det.size_unit,
                            det.size,
                            det.color,
                            det.unit,
                            det.quantity,
                            concat(det.quantity, det.unit) as quantity_unit,
                            det.hikiate_quantity,
                            det.buy_price,
                            det.sell_price,
                            det.base_price,
                            concat(det.buy_price, rece.currency) as buy_price_currency,
                            concat(det.sell_price, rece.currency) as sell_price_currency,
                            concat(det.base_price, rece.currency) as base_price_currency,
                            det.amount,
                            (det.amount - det.amount_base) as dif_amount,
                            det.status,
                            det.edit_user,
                            det.edit_date");
        $this->db->from("t_orders_receive_details det");
        $this->db->join("t_orders_receive rece","det.order_receive_no = rece.order_receive_no AND det.partition_no = rece.partition_no AND det.order_receive_date = rece.order_receive_date","inner");
        $this->db->where("rece.order_receive_no", $orderReceiveNo);
        $this->db->where("rece.partition_no", $partitionNo);
        $this->db->where("rece.order_receive_date", $orderReceiveDate);
        $this->db->where("rece.del_flg", "0");
        $this->db->where("det.del_flg", "0");
        
        $query = $this->db->get();
        return $query->result_array();
    }

     /**
     * Get order receivered Detail
     * 
     * @author Duc Tam
     * @param orderReceiveNo
     * @param partitionNo
     * @param orderReceiveDate
     * 
     * @return OrderReceiveredDetails/Array(0)
     */
    public function getDistinctOrderReciveredDetails(){
        $this->db->select("order_receive_no, status");
        $this->db->from("t_orders_receive");
        $this->db->where("del_flg",'0');
        $orderReciever = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select("item_code, net_wt, color, size");
        $this->db->from("m_items");
        $this->db->where("del_flg",'0');
        $item = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->distinct();
        $this->db->select("detail.order_receive_no as po,
                           detail.item_code,
                           detail.item_name,
                           detail.size, 
                           detail.size_unit, 
                           detail.color,
                           detail.quantity,
                           item.net_wt as netwt");
        $this->db->from("t_orders_receive_details detail");
        $this->db->join("($orderReciever) ord",'ord.order_receive_no = detail.order_receive_no', 'inner');
        $this->db->join("($item) item",'item.item_code = detail.item_code and item.color = detail.color and item.size = detail.size', 'left');
        $this->db->where_not_in("ord.status", explode(STATUS_FINISH,",")); 
        $this->db->where("del_flg", "0"); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllPVNo(){
        $this->db->distinct();
        $this->db->select("order_receive_no, item_code, color, size");
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
    public function getCountPV($order_receive_no, $partition_no, $order_receive_date){
        $this->db->select("COUNT(order_receive_no)");
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $order_receive_no);
        $this->db->where('partition_no', $partition_no);
        $this->db->where('order_receive_date', $order_receive_date);
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }
    
    public function getItemsByOrderReceiveNo($pvNoList){
        $res = array();
        foreach($pvNoList as $pv){
            // $pvNo = explode("-", $pv);
            $last = strrpos($pv, '-');
            $partitionNo = 1;
            $pvNo = $pv;
            if( $last !== FALSE) {
                $pvNo = substr($pv, 0, $last);
                $partitionNo = substr($pv, $last + 1);
            }
            
            $this->db->distinct();
            $this->db->select("ORD.order_receive_no, ORD.jp_code, ORD.composition_1, ORD.composition_2, ORD.composition_3, ORD.partition_no, ORD.unit, ORD.size_unit, ORD.order_receive_date, ORD.item_code, ORD.item_name, ORD.color, ORD.size, ORD.quantity, ORD.base_price, ORD.sell_price, ORD.inv_no, IT.shosha_price_usd, IT.shosha_price_vnd, IT.shosha_price_jpy, IT.sell_price_usd, IT.sell_price_vnd, IT.sell_price_jpy , ODR.currency");
            $this->db->from('t_orders_receive_details ORD');
            $this->db->join('t_orders_receive ODR',"ODR.del_flg = '0' AND ODR.order_receive_no = ORD.order_receive_no AND ODR.partition_no = ORD.partition_no AND ODR.order_receive_date = ORD.order_receive_date",'left');
            $this->db->join('m_items IT',"IT.del_flg = '0' AND IT.item_code = ORD.item_code AND IT.size = ORD.size AND IT.color = ORD.color",'left');
            $this->db->where('ORD.del_flg', '0');
            $this->db->where('ORD.order_receive_no', $pvNo);
            if($partitionNo != null && $partitionNo != ''){
                $this->db->where('ORD.partition_no', $partitionNo);
            }
            $this->db->where("(ORD.status <> '015' OR ORD.status IS NULL)");
            $query = $this->db->get();
            $result = $query->result_array();
            if (sizeof($result) > 0) {
                $res = array_merge($res, $result);
            }
        }
        return $res;
    }
    public function deleteReceivedOrderDetailBeforeInsert($orderReceiveNo, $partitionNo, $orderReceiveDate)
    {
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->delete('t_orders_receive_details');
    }
    public function getListPVByItem($params = null){
        $res = array();
        if(isset($params['pvList']) && $params['pvList'] != null && sizeof($params['pvList']) > 0){
            foreach($params['pvList'] as $pv){
                $pv = explode("*",$pv);
                $quantityKvt = isset($pv[1]) ? $pv[1] : 0;
                $last = strrpos($pv[0], '-');
                $partitionNo = 1;
                $pvNo = $pv[0];
                if( $last !== FALSE) {
                    $pvNo = substr($pv[0], 0, $last);
                    $partitionNo = substr($pv[0], $last + 1);
                }
                $this->db->distinct();
                $this->db->select($quantityKvt." as quantity_kvt, "."ords.order_receive_no as pv_no, ords.partition_no, ords.order_receive_date, ords.quantity, odr.currency, ords.buy_price, ords.sell_price, ords.base_price, (case when odr.currency = 'VND' then it.shosha_price_vnd when odr.currency = 'JPY' then it.shosha_price_jpy when odr.currency = 'USD' then it.shosha_price_usd end) as shosha_price");
                $this->db->from('t_orders_receive_details ords');
                $this->db->join('t_orders_receive odr',"odr.del_flg = '0' AND ords.order_receive_no = odr.order_receive_no AND ords.partition_no = odr.partition_no AND ords.order_receive_date = odr.order_receive_date",'left');
                $this->db->join('m_items it',"it.del_flg = '0' AND it.item_code = ords.item_code AND it.color = ords.color AND it.size = ords.size",'left');
                $this->db->where('ords.del_flg', '0');
                $this->db->where('ords.item_code', $params['item_code']);
                if($params['size'] == null || $params['size'] == ''){
                    $this->db->where("(ords.size = '' OR ords.size IS NULL)");
                }else{
                    $this->db->where('ords.size', $params['size']);
                }
                if($params['color'] == null || $params['color'] == ''){
                    $this->db->where("(ords.color = '' OR ords.color IS NULL)");
                }else{
                    $this->db->where('ords.color', $params['color']);
                }
                $this->db->where('ords.order_receive_no',  $pvNo);
                if($partitionNo != null && $partitionNo != ''){
                    $this->db->where('ords.partition_no', $partitionNo);
                }
                $this->db->where("(ords.status <> '015' OR ords.status IS NULL)");
                $query = $this->db->get();
                // echo $this->db->last_query();
                $result = $query->result_array();
                if (sizeof($result) > 0) {
                    array_push($res, $result[0]) ;
                }
            }
        } else {
            $this->db->distinct();
            $this->db->select("ords.order_receive_no as pv_no, ords.partition_no, ords.order_receive_date, ords.quantity, ords.quantity as quantity_kvt, odr.currency, ords.buy_price, ords.sell_price, ords.base_price, (case when odr.currency = 'VND' then it.shosha_price_vnd when odr.currency = 'JPY' then it.shosha_price_jpy when odr.currency = 'USD' then it.shosha_price_usd end) as shosha_price");
            $this->db->from('t_orders_receive_details ords');
            $this->db->join('t_orders_receive odr',"odr.del_flg = '0' AND ords.order_receive_no = odr.order_receive_no AND ords.partition_no = odr.partition_no AND ords.order_receive_date = odr.order_receive_date",'left');
            $this->db->join('m_items it',"it.del_flg = '0' AND it.item_code = ords.item_code AND it.color = ords.color AND it.size = ords.size",'left');
            $this->db->where('ords.del_flg', '0');
            $this->db->where('ords.item_code', $params['item_code']);
            if($params['size'] == null || $params['size'] == ''){
                $this->db->where("(ords.size = '' OR ords.size IS NULL)");
            }else{
                $this->db->where('ords.size', $params['size']);
            }
            if($params['color'] == null || $params['color'] == ''){
                $this->db->where("(ords.color = '' OR ords.color IS NULL)");
            }else{
                $this->db->where('ords.color', $params['color']);
            }
            $this->db->where("(ords.status <> '015' OR ords.status IS NULL)");
            $query = $this->db->get();
            // echo $this->db->last_query();
            $result = $query->result_array();
            if (sizeof($result) > 0) {
                $res = $result;
            }
        }
        return $res;
    }
    public function getItemsForSalesContract($params){
        $res = array();
        $pvInfo = explode(",", $params['pv_no']);
        foreach($pvInfo as $orderReceived){
            $pv = explode("*", $orderReceived);
            $quantity = 0;
            $pvNo = array();
            if(sizeof($pv) > 0){
                $quantity = isset($pv[1]) ? $pv[1] : 0;
                $pvNo = explode("-", $pv[0]);
            }
            $this->db->distinct();
            $this->db->select("ORD.order_receive_no, ORD.partition_no, ORD.order_receive_date, ORD.item_code, ORD.item_name, ORD.color, ORD.size, ORD.size_unit, ORD.sell_price,ORD.unit, ORD.quantity, ODR.currency, ODR.rate_usd, ODR.rate_jpy");
            $this->db->from('t_orders_receive_details ORD');
            $this->db->join('t_orders_receive ODR',"ODR.del_flg = '0' AND ODR.order_receive_no = ORD.order_receive_no AND ODR.partition_no = ORD.partition_no AND ODR.order_receive_date = ORD.order_receive_date",'left');
            $this->db->join('m_items IT',"ORD.del_flg = '0' AND IT.item_code = ORD.item_code AND IT.size = ORD.size AND IT.color = ORD.color",'left');
            $this->db->where('ORD.del_flg', '0');
            $this->db->where('ORD.order_receive_no', $pvNo[0]);
            if(isset($pvNo[1])){
                $this->db->where('ORD.partition_no', $pvNo[1]);
            }
            $this->db->where("(ORD.status <> '015' OR ORD.status IS NULL)");
            $this->db->where('ORD.item_code', $params['item_code']);
            $this->db->where("(ORD.size = '".$params['size']."' OR ORD.size IS NULL)");
            $this->db->where("(ORD.color = '".$params['color']."' OR ORD.color IS NULL)");
            $query = $this->db->get();
            $result = $query->result_array();
            if (sizeof($result) > 0) {
                $result[0]['quantity'] = $quantity;
                array_push($res, $result[0]);
            }
        }
        return $res;
    }

    public function getCurrencyByOrderReceiveNo($conditions) {
        $this->db->select('odr.currency, ord.sell_price, ord.base_price');
        $this->db->from('t_orders_receive odr');
        $this->db->join('t_orders_receive_details ord', "odr.order_receive_no = ord.order_receive_no AND odr.partition_no = ord.partition_no AND odr.order_receive_date = ord.order_receive_date", 'left');
        $this->db->where('odr.del_flg', '0');
        $this->db->where('ord.del_flg', '0');
        $this->db->where('ord.order_receive_no', $conditions['order_receive_no']);
        $this->db->where('ord.item_code', $conditions['item_code']);
        $this->db->where('ord.size', $conditions['size']);
        $this->db->where('ord.color', $conditions['color']);

        $pv_query = $this->db->get();
        if(sizeof($pv_query->result_array()) > 0) {
            return $pv_query->result_array()[0];
        } else {
            return NULL;
        }
    }

    /** Get data to draw order receive chart */
    public function getOrdersReceiveData($params)
    {
        $arr_horizontal = array();
        $arr_vertical = array();
        $order_by = array();
        foreach ($params["horizontal_order_receive"] as $key => $value) {
            if($value == "jp_code") {
                array_push($arr_horizontal, 'trim("IT"."' . $value . '") as '.$value.'');
                array_push($order_by, 'trim("IT"."' . $value . '")');
            } else {
                if($value == "staff") {
                    array_push($arr_horizontal, 'trim("OR"."' . $value . '") as '.$value.'');
                    array_push($order_by, 'trim("OR"."' . $value . '")');
                } else {
                    if($value == "short_name") {
                        array_push($arr_horizontal, 'trim("CO"."' . $value . '") as '.$value.'');
                        array_push($order_by, 'trim("CO"."' . $value . '")');
                    } else {
                        array_push($arr_horizontal, 'trim("ORD"."' . $value . '") as '.$value.'');
                        array_push($order_by, 'trim("ORD"."' . $value . '")');
                    }
                }
            }
        }
        foreach ($params["vertical"] as $key => $value) {
            if($value == "quantity") {
                array_push($arr_vertical, 'sum("ORD"."' . $value . '") as quantity');
            } else {
                array_push($arr_vertical, 'sum("ORD"."' . $value . '") as amount');
            }
        }
        array_push($arr_horizontal, "OR.currency");
        array_push($order_by, "OR.currency");
        $select = implode(",", $arr_horizontal) . "," . implode(",", $arr_vertical);

        $this->db->select($select);
        $this->db->from("t_orders_receive_details ORD");
        $this->db->join("m_items IT", "IT.item_code = ORD.item_code and IT.size = ORD.size and IT.color = ORD.color", "left");
        $this->db->join("t_orders_receive OR", "OR.order_receive_no = ORD.order_receive_no and OR.partition_no = ORD.partition_no and OR.order_receive_date = ORD.order_receive_date", "left");
        $this->db->join("m_company CO", "CO.company_name = OR.customer and CO.type = '1'", "left");
        if(isset($params["date_from"])) {
            $this->db->where('OR.delivery_date >=', $params["date_from"]);
        }
        if(isset($params["date_to"])) {
            $this->db->where('OR.delivery_date <=', $params["date_to"]);
        }
        $this->db->where('ORD.del_flg' , '0');
        $this->db->group_by($order_by);
        $query = $this->db->get();
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }
}
