<?php

/**
 * Database handler for *order_recevied* 's table
 */
class order_received_model extends CI_Model
{
    public function __construct()
    {
        // parent::__construct();
        $this->load->database();
        $this->load->model('order_received_details_model');
    }

    public function getAllReceivedOrder()
    {
        $this->db->select("OR.order_receive_no, OR.partition_no, OR.order_receive_date, OR.delivery_date, OR.customer, OR.input_user, OR.status, EM.first_name");
        $this->db->from('t_orders_receive OR');
        $this->db->join('m_employee EM', 'EM.employee_id = OR.input_user', 'left');
        $this->db->where('OR.del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    public function get_order_receive_add()
    {
        $col_lang = getColStatusByLang($this);
        $this->db->select("OR.order_receive_no, OR.partition_no, OR.order_receive_date, OR.contract_no, MK.$col_lang as komoku_name_2, OR.staff, OR.assistance, OR.odr_department, OR.delivery_to, OR.delivery_address, OR.currency");
        $this->db->from('t_orders_receive OR');
        $this->db->join('m_komoku MK', "MK.komoku_id = 'KM0001' and MK.kubun = OR.status", 'left');
        $this->db->where('OR.del_flg', '0');
        $this->db->where('OR.kubun', '1');
        $this->db->where_in('OR.status', array('002'));
        $this->db->order_by('order_receive_no, order_receive_date, partition_no', 'ASC');
        
        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return $result;
        } else {
            return [];
        }
    }

    public function search($params, $start, $length, &$recordsTotal, &$recordsFiltered, $order) {
        if (isset($params['item'] ) && $params['item'] != '') {
            $this->db->select('count(*)');
            $this->db->from('t_orders_receive_details ORD');
            $this->db->where('ORD.del_flg', '0');
            $this->db->where('ORD.order_receive_no = OR.order_receive_no');
            $this->db->where('ORD.partition_no = OR.partition_no');
            $this->db->where('ORD.order_receive_date = OR.order_receive_date');
            $this->db->group_start();
            $this->db->like('ORD.item_code', $params['item']);
            $this->db->or_like('ORD.item_name', $params['item']);
            $this->db->group_end();
            $count_item = $this->db->get_compiled_select();
        }

        $this->db->from('t_orders_receive OR');
        $this->db->where('OR.del_flg', '0');
        $recordsTotal = $this->db->count_all_results(null, false);

        $this->db->select("OR.*, MK.komoku_name_2 as identify_name_1");
        $this->db->join('m_komoku MK', "MK.komoku_id = 'KM0004' and MK.kubun = OR.identify_name and MK.del_flg = '0'", 'left');
        if (isset($params['order_receive_no'] ) && $params['order_receive_no'] != '') {
            $this->db->like('OR.order_receive_no', $params['order_receive_no']);
        }
        if (isset($params['customer'] ) && $params['customer'] != '') {
            $this->db->like('OR.customer', $params['customer'], 'both');
        }
        if (isset($params['part_no'] ) && $params['part_no'] != '') {
            $this->db->where('OR.partition_no', $params['part_no']);
        }
        if (isset($params['input_user'] ) && $params['input_user'] != '') {
            $this->db->where('OR.input_user', $params['input_user']);
        }
        if (isset($params['order_receive_date_from'] ) && $params['order_receive_date_from'] != '') {
            $this->db->where('OR.order_receive_date >=', $params['order_receive_date_from']);
        }
        if (isset($params['order_receive_date_to'] ) && $params['order_receive_date_to'] != '') {
            $this->db->where('OR.order_receive_date <=', $params['order_receive_date_to'].' 23:59:59');
        }
        if (isset($params['status'] ) && $params['status'] != '') {
            $this->db->where('status', $params['status']);
        }
        if (isset($count_item)) {
            $this->db->where("($count_item) >", 0);
        }
        if(count($order) > 0){
            $dir = $order[0]['dir'];
            $col = $order[0]['column'];
            $this->db->order_by($col, $dir);
        }else{
            $this->db->order_by('order_receive_date', 'DESC');
            $this->db->order_by('order_receive_no, partition_no', 'ASC');
        }
        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function getReceivedOrderByCondition($orderNo = null, $dateFrom = null, $dateTo = null, $input_user = null, $status = null)
    {
        $this->db->select("*");
        $this->db->from('t_orders_receive OR');
        $this->db->where('OR.del_flg', '0');
        if ($orderNo != null && $orderNo != '') {
            $this->db->like('OR.order_receive_no', $orderNo, 'both');
        }
        if ($dateFrom != null && $dateFrom != '') {
            $this->db->where('OR.create_date  >=', $dateFrom);
        }
        if ($dateTo != null && $dateTo != '') {
            $this->db->where('OR.create_date  <=', $dateTo);
        }
        if ($input_user != null && $input_user != '') {
            $this->db->where('OR.input_user', $input_user);
        }
        if ($status != null && $status != '') {
            $this->db->where('OR.status', $status);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result;
        }
        return [];
    }

    /**
     * gets order_no in received_order 's table
     *
     * @return string|null last id | null
     */
    public function getNextPartitionNo($orderReceiveNo, $orderReceiveDate)
    {
        $this->db->select_max('partition_no');
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->where('del_flg', '0');
        $this->db->select_max('partition_no');
        $result = $this->db->get('t_orders_receive');  
        $value = $result->row()->partition_no;
        $value = (int)$value;
        $value += 1;
        $value  = str_pad("" . $value, 3, '0', STR_PAD_LEFT);
        return $value;
    }
    /**
     * insert order received
     *
     * @param array $data
     * @return void
     */
    public function insertReceivedOrder($data)
    {
        $this->db->trans_begin();
        $this->db->select('order_receive_no, partition_no, order_receive_date');
        $this->db->from('t_orders_receive');
        $this->db->where('partition_no', 1);
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('del_flg', '1');
        $query = $this->db->get();
        $result = $query->result_array();
        if(sizeof($result) > 0){
            $this->db->delete('t_orders_receive', $result[0]);
            $this->db->delete('t_orders_receive_details', $result[0]);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            }
        }
        
        // set next id for order
        $nextPartitionNo = $this->getNextPartitionNo($data['order_receive_no'], $data['order_receive_date']);
        $data['partition_no'] = $nextPartitionNo;
        $this->db->insert('t_orders_receive', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            $this->db->trans_commit();
            return $this->getReceivedOrderByID($data['order_receive_no'], $data['order_receive_date'], $data['partition_no']);
        }
        return null;
    }
    /**
     * author: duc
     * insert order received
     *
     * @param array $data
     * @return void
     */
    public function insertReceivedOrderUnique($data)
    {
        $data['partition_no'] = 1;
        $this->db->insert('t_orders_receive', $data);
        $num_inserts = $this->db->affected_rows();
        if ($num_inserts > 0) {
            return $this->getReceivedOrderByID($data['order_receive_no'], $data['order_receive_date'], $data['partition_no']);
        }
        return null;
    }
    /**
     * author: duc
     * gets order_no in received_order 's table
     *
     * @return string|null last id | null
     */
    public function getPartitionNo($orderReceiveNo, $orderReceiveDate, $del_flag)
    {
        $this->db->select_max('partition_no');
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->where('del_flg', $del_flag);
        $this->db->select_max('partition_no');
        $result = $this->db->get('t_orders_receive');  
        $value = $result->row()->partition_no;
        $value = (int)$value;
        return $value;
    }
    /**
     * author: duc
     * get order received by id
     *
     * @param string $orderNo
     * @return object found order received | null
     */
    public function getReceivedOrderByNoAndDate($orderReceiveNo, $orderReceiveDate)
    {
        $this->db->select("edit_date");
        $this->db->from('t_orders_receive');
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            return $result[0];
        }
        return null;
    }
    /**
     * update order received
     *
     * @param array $data
     * @return void
     */
    public function updateReceivedOrder($data)
    {
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        if(isset($data['identify_name_1'])  || is_null($data['identify_name_1'])){
            unset($data['identify_name_1']);
        }
        $this->db->update('t_orders_receive', $data);
        $num_update = $this->db->affected_rows();
        if ($num_update > 0) {
            return $this->getReceivedOrderByID($data['order_receive_no'], $data['order_receive_date'], $data['partition_no']);
        }
        return null;
    }

    public function updateStatusOrderReceived($data, $status, $pv_info = NULL) {
        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];
        if($pv_info != NULL) {
            $this->db->where_in('order_receive_no', array_column($pv_info, "order_receive_no"));
            $this->db->where_in('partition_no', array_column($pv_info, "partition_no"));
        } else {
            if($data == NULL) return FALSE;
            $this->db->where('order_receive_no', $data['order_receive_no']);
            $this->db->where('partition_no', $data['partition_no']);
            $this->db->where('order_receive_date', $data['order_receive_date']);
        }    
        $this->db->where('del_flg', '0');
        $this->db->set('status', $status);
        $this->db->set('edit_user', $currentEmployeeId);
        $this->db->set('edit_date', $now );
        $this->db->update('t_orders_receive');
        $num_update = $this->db->affected_rows();
        if ($num_update > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * get order received by id
     *
     * @param string $orderNo
     * @return object found order received | null
     */
    public function getReceivedOrderByID($orderReceiveNo, $orderReceiveDate=null, $partitionNo = NULL)
    {
        $this->db->select("OR.*, MK.komoku_name_2 as identify_name_1");
        $this->db->from('t_orders_receive OR');
        $this->db->join('m_komoku MK', "MK.komoku_id = 'KM0004' and MK.kubun = OR.identify_name and MK.del_flg = '0'", 'left');
        $this->db->where('OR.del_flg', '0');
        $this->db->where('order_receive_no', $orderReceiveNo);
        if(isset($partitionNo)) {
            $this->db->where('partition_no', $partitionNo);
        }
        if(isset($orderReceiveDate)) {
            $this->db->where('order_receive_date', $orderReceiveDate);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if(isset($partitionNo)) {
            if (sizeof($result) > 0) {
                return $result[0];
            }
        } else {
            return sizeof($result);
        }
        return null;
    }

    /**
     * get count order received by id
     *
     * @param string $orderNo
     * @return object found order received | null
     */
    public function getCountReceivedOrderByID($orderReceiveNo, $orderReceiveDate)
    {
        $this->db->select("partition_no");
        $this->db->from('t_orders_receive');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $query = $this->db->get();
        $result = $query->result_array();
        if(sizeof($result) > 0) {
            return sizeof($result);
        }
        return null;
    }

    public function deleteReceivedOrderById($orderReceiveNo, $partitionNo, $orderReceiveDate) {
        $this->db->trans_begin();

        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->update('t_orders_receive', (array('del_flg' => '1')));
        $num_update = $this->db->affected_rows();

        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->update('t_orders_receive_details', (array('del_flg' => '1')));
        $num_update = $this->db->affected_rows();
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function deleteReceivedOrderBeforeInsert($orderReceiveNo, $partitionNo, $orderReceiveDate, $del_flg)
    {
        $this->db->where('order_receive_no', $orderReceiveNo);
        $this->db->where('partition_no', $partitionNo);
        $this->db->where('order_receive_date', $orderReceiveDate);
        $this->db->where('del_flg', $del_flg);
        $this->db->delete('t_orders_receive');
    }

    public function checkAllocation($data, $hikiateQuantity){
        $this->db->where('del_flg', '0');
        $this->db->where('salesman', $data['salesman']);
        $this->db->where('item_code', $data['item_code']);
        $this->db->where('color', $data['color']);
        $this->db->where('size', $data['size']);
        $this->db->where('order_receive_no', $data['item_order_receive_no']);
        $this->db->where('partition_no', $data['item_partition_no']);
        $this->db->where('order_no', $data['order_no']);
        $this->db->where('inspect_ok > ', $hikiateQuantity);
        return $this->db->count_all_results("t_store_item") > 0;
    }
    public function doAllocation($data) {
        // if (!$this->checkAllocation($data, "quantity >= ")) {
        //     throw new Error('quantity not enough');
        // }
        $this->load->model('komoku_model');
        $hikiateQuantity = $data['hikiate_quantity'];
        $inv_no = [];

        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];

        // get order receive
        $orderReceive = $this->getReceivedOrderByID($data['order_receive_no'], $data['order_receive_date'], $data['partition_no']);

        if (!$orderReceive) {
            throw new Error('order receive not exist');
        }
        $orderReceive = (array)$orderReceive;

        // get detail
        $this->db->select('*');
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $detail = $this->db->get()->row();

        if (!$detail) {
            throw new Error('detail not exist');
        }
        $detail = (array)$detail;

        $this->db->trans_begin();

        foreach ($data['selectedInventoryItem'] as $idx => $selectedInventoryItem) {
            $item_order_receive_no = $selectedInventoryItem['order_receive_no'];
            $item_partition_no = $selectedInventoryItem['partition_no'];
            $order_no = $selectedInventoryItem['order_no'];
            $sales_man = $selectedInventoryItem['salesman'];
            $data['item_order_receive_no'] =  $item_order_receive_no;
            $data['item_partition_no'] =  $item_partition_no;
            $data['order_no'] =  $order_no;
            $data['salesman'] =  $sales_man;
            // unset($data['item_order_receive_no']);
            // unset($data['item_partition_no']);

            $inventoryHikiate = min($hikiateQuantity, $selectedInventoryItem['inspect_ok']);
            $inventoryQuantity = ($selectedInventoryItem['inspect_ok'] - $hikiateQuantity);

            if ($idx == count($data['selectedInventoryItem']) - 1 &&
            $this->checkAllocation($data, $hikiateQuantity)) {
                // insert new inventory with quantity = selectedInventoryItem['inspect_ok']
                $this->db->select("*");
                $this->db->from('t_store_item');
                $this->db->where('del_flg', '0');
                $this->db->where('salesman', $sales_man);
                $this->db->where('item_code', $data['item_code']);
                $this->db->where('color', $data['color']);
                $this->db->where('size', $data['size']);
                $this->db->where('order_receive_no', $item_order_receive_no);
                $this->db->where('partition_no', $item_partition_no);
                $this->db->where('order_no', $order_no);

                $getInventoryItem = $this->db->get();
                $inventoryItem = $getInventoryItem->result_array();

                // update old inventory with quantity = quantity - $inventoryHikiate}
                $this->db->where('del_flg', '0');
                $this->db->where('salesman', $sales_man);
                $this->db->where('item_code', $data['item_code']);
                $this->db->where('color', $data['color']);
                $this->db->where('size', $data['size']);
                $this->db->where('order_receive_no', $item_order_receive_no);
                $this->db->where('order_no', $order_no);
                $this->db->where('partition_no', $item_partition_no);
                $this->db->set('inspect_ok', "inspect_ok - $inventoryHikiate", FALSE);
                $this->db->set('quantity', "quantity - $inventoryHikiate", FALSE);

                $this->db->update('t_store_item', array(
                    'edit_date' => $now,
                    'edit_user' => $currentEmployeeId,
                ));
                $warehouse = $this->komoku_model->getWarehouseWithName($inventoryItem[0]['warehouse']);
                $inventoryItem[0]['quantity'] = $inventoryHikiate;
                $inventoryItem[0]['inspect_ok'] = $inventoryHikiate;
                $inventoryItem[0]['inspect_ng'] = 0;
                $inventoryItem[0]['create_user'] = $currentEmployeeId;
                $inventoryItem[0]['create_date'] = $now;
                $inventoryItem[0]['order_receive_no'] = $detail['order_receive_no'];
                $inventoryItem[0]['partition_no'] = $detail['partition_no'];
                $inventoryItem[0]['odr_recv_date'] = $detail['order_receive_date'];
                $inventoryItem[0]['odr_recv_detail_no'] = $detail['order_receive_detail_no'];
                $inventoryItem[0]['status'] = RESERVED_ITEM;
                $inventoryItem[0]['warehouse'] = (count($warehouse) > 0 ? $warehouse[0]['kubun'] : $inventoryItem[0]['warehouse']);
                $inventoryItem[0]['note'] =  (count($warehouse) > 0 ? $warehouse[0]['warehouse'] : '');
                
                if(isset($inventoryItem[0]["edit_user"])){
                    unset($inventoryItem[0]["edit_user"]);
                }
                if(isset($inventoryItem[0]["edit_date"])){
                    unset($inventoryItem[0]["edit_date"]);
                }
                
                $this->db->insert('t_store_item', $inventoryItem[0]);  

                // - hikiate
                $hikiateQuantity -= $inventoryHikiate;
                //
                $inv_no[] = $selectedInventoryItem['invoice_no'].'-'.$inventoryHikiate;
            }else{
                // update old inventory with quantity = quantity - $inventoryHikiate}
                $this->db->where('del_flg', '0');
                $this->db->where('salesman', $sales_man);
                $this->db->where('item_code', $data['item_code']);
                $this->db->where('color', $data['color']);
                $this->db->where('size', $data['size']);
                $this->db->where('order_receive_no', $item_order_receive_no);
                $this->db->where('order_no', $order_no);
                $this->db->where('partition_no', $item_partition_no);
                $this->db->where('warehouse', $selectedInventoryItem['warehouse_kubun']);
                // $this->db->set('inspect_ok', "inspect_ok - $inventoryHikiate", FALSE);
                // $this->db->set('quantity', "quantity - $inventoryHikiate", FALSE);
                $this->db->set('warehouse', "(select km.kubun from m_komoku km 
                                                join m_komoku km2 on km.komoku_id = km2.komoku_id and km2.kubun = st.warehouse
                                                where km.komoku_name_2 = concat(km2.komoku_name_2, '_Cho xuat'))", FALSE);
                $this->db->set('note', "(select km.komoku_name_2 from m_komoku km 
                                                    join m_komoku km2 on km.komoku_id = km2.komoku_id and km2.kubun = st.warehouse
                                                    where km.komoku_name_2 = concat(km2.komoku_name_2, '_Cho xuat'))", FALSE);
                $this->db->update('t_store_item st', array(
                    'status' => RESERVED_ITEM,
                    'edit_date' => $now,
                    'edit_user' => $currentEmployeeId,
                    'order_receive_no' => $detail['order_receive_no'],
                    'partition_no' => $detail['partition_no'],
                    'odr_recv_date' => $detail['order_receive_date'],
                    'odr_recv_detail_no' => $detail['order_receive_detail_no'],
                ));

                // - hikiate
                $hikiateQuantity -= $inventoryHikiate;
                //
                $inv_no[] = $selectedInventoryItem['invoice_no'].'-'.$inventoryHikiate;

                }
            }
        
        // update current detail
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $this->db->set('amount', "\"sell_price\" * (${detail['hikiate_quantity']} + ${data['hikiate_quantity']})", FALSE);
        $this->db->set('amount_base', "\"base_price\" * (${detail['hikiate_quantity']} + ${data['hikiate_quantity']})", FALSE);
        $this->db->update('t_orders_receive_details', array(
            'quantity' => $detail['hikiate_quantity'] + $data['hikiate_quantity'],
            'hikiate_quantity' => $detail['hikiate_quantity'] + $data['hikiate_quantity'],
            'status' => 1,
            'edit_date' => $now,
            'edit_user' => $currentEmployeeId,
            'inv_no'    => implode(",", $inv_no),
        ));
        // update sum_amount, sum_base_amount, sum_quantity
        $this->order_received_details_model->TotalCalculator($data['order_receive_no'],  $data['partition_no'],  $data['order_receive_date']);
        
        $nextPartitionNo = intval($data['partition_no']) + 1;
        $afftectedRows = false;
        if ($detail['quantity'] > $data['hikiate_quantity'] + $detail['hikiate_quantity']) {
            // check partition_no exist
            $this->db->select("*");
            $this->db->from('t_orders_receive_details');
            $this->db->where('del_flg', '0');
            $this->db->where('order_receive_no', $data['order_receive_no']);
            $this->db->where('partition_no', $nextPartitionNo);
            $this->db->where('order_receive_date', $data['order_receive_date']);

            $getOrderReceivedDetailItem = $this->db->get();
            $orderReceivedDetailItem = $getOrderReceivedDetailItem->result_array();

            if (sizeof($orderReceivedDetailItem) == 0) {
                $afftectedRows = true;
            }

            // split current detail to next partition
            $splitData = array_merge($detail, [
                'partition_no' => $nextPartitionNo,
                'quantity' => $detail['quantity'] - $detail['hikiate_quantity'] - $data['hikiate_quantity'],
                'amount' => ($detail['sell_price'] * ($detail['quantity'] - $detail['hikiate_quantity'] - $data['hikiate_quantity'])),
                'amount_base' => ($detail['base_price'] * ($detail['quantity'] - $detail['hikiate_quantity'] - $data['hikiate_quantity'])),
                'hikiate_quantity' => 0,
                'create_date' => $now,
                'create_user' => $currentEmployeeId,
                'edit_date' => null,
                'edit_user' => null,
            ]);
            $this->db->insert('t_orders_receive_details', $splitData);
            // update sum_amount, sum_base_amount, sum_quantity
            $this->order_received_details_model->TotalCalculator( $splitData['order_receive_no'],  $splitData['partition_no'],  $splitData['order_receive_date']);
        }

        if ($afftectedRows) {
            $newOrderReceive = array_merge($orderReceive, [
                'partition_no' => $nextPartitionNo,
                'create_date' => $now,
                'create_user' => $currentEmployeeId,
                'edit_date' => null,
                'edit_user' => null,
            ]);
            unset($newOrderReceive['identify_name_1']);
            $this->db->insert('t_orders_receive', $newOrderReceive);
            // update sum_amount, sum_base_amount, sum_quantity
            $this->order_received_details_model->TotalCalculator( $newOrderReceive['order_receive_no'],  $newOrderReceive['partition_no'],  $newOrderReceive['order_receive_date']);
        } else {
            $this->db->where('order_receive_no', $orderReceive['order_receive_no']);
            $this->db->where('partition_no', $nextPartitionNo);
            $this->db->where('order_receive_date', $orderReceive['order_receive_date']);
            $this->db->update('t_orders_receive', array(
                'status' => '001',
                'edit_date' => $now,
                'edit_user' => $currentEmployeeId
            ));
        }

        if ($detail['quantity'] >= $data['hikiate_quantity'] + $detail['hikiate_quantity']) {
            // check all item in PV had allocate
            $this->db->select("*");
            $this->db->from('t_orders_receive_details');
            $this->db->where('del_flg', '0');
            $this->db->where('order_receive_no', $data['order_receive_no']);
            $this->db->where('order_receive_date', $data['order_receive_date']);
            $this->db->where('partition_no', $data['partition_no']);
            $this->db->where("(status IS NULL OR status <> '1')");

            $numRow = $this->db->get()->num_rows();
            // If allocate all item in PV, update status for PV in order recieve
            if ($numRow == 0) {
                // update orderReceive status
                $this->db->where('order_receive_no', $data['order_receive_no']);
                $this->db->where('partition_no', $data['partition_no']);
                $this->db->where('order_receive_date', $data['order_receive_date']);
                $this->db->update('t_orders_receive', array(
                    'status' => ORDER_RECEIVED_STATUS_CLOSE,
                    'edit_date' => $now,
                    'edit_user' => $currentEmployeeId,
                ));
                // update sum_amount, sum_base_amount, sum_quantity
                $this->order_received_details_model->TotalCalculator($data['order_receive_no'],  $data['partition_no'],  $data['order_receive_date']);
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
    public function moveToNextPartition($data) {
        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];

        // get order receive
        $orderReceive = $this->getReceivedOrderByID($data['order_receive_no'], $data['order_receive_date'], $data['partition_no']);

        if (!$orderReceive) {
            throw new Error('order receive not exist');
        }
        $orderReceive = (array)$orderReceive;

        // get detail
        $this->db->select('*');
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $detail = $this->db->get()->row();

        if (!$detail) {
            throw new Error('detail not exist');
        }
        $this->db->trans_begin();

        // update current detail
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $this->db->set('partition_no', "partition_no + 1", FALSE);
        $this->db->update('t_orders_receive_details', array(
            'edit_date' => $now,
            'edit_user' => $currentEmployeeId,
        ));

        $this->db->select("*");
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where("(status IS NULL OR status <> '1')");

        $numRow = $this->db->get()->num_rows();
        // If allocate all item in PV, update status for PV in order recieve
        if ($numRow == 0) {
            // update orderReceive status
            $this->db->where('order_receive_no', $data['order_receive_no']);
            $this->db->where('partition_no', $data['partition_no']);
            $this->db->where('order_receive_date', $data['order_receive_date']);
            $this->db->update('t_orders_receive', array(
                'status' => ORDER_RECEIVED_STATUS_CLOSE,
                'edit_date' => $now,
                'edit_user' => $currentEmployeeId,
            ));
        }

        // update sum_amount, sum_base_amount, sum_quantity
        $this->order_received_details_model->TotalCalculator($data['order_receive_no'],  $data['partition_no'],  $data['order_receive_date']);
        
        $nextPartitionNo = intval($data['partition_no']) + 1;
        $newOrderReceive = array_merge($orderReceive, [
            'partition_no' => $nextPartitionNo,
            'create_date' => $now,
            'create_user' => $currentEmployeeId,
            'edit_date' => null,
            'edit_user' => null,
        ]);
        $nextOrderReceive = $this->getReceivedOrderByID($newOrderReceive['order_receive_no'],  $newOrderReceive['order_receive_date'],  $newOrderReceive['partition_no']);
        if($nextOrderReceive == null){
            unset($newOrderReceive['identify_name_1']);
            $this->db->insert('t_orders_receive', $newOrderReceive);
        }
        // update sum_amount, sum_base_amount, sum_quantity
        $this->order_received_details_model->TotalCalculator( $newOrderReceive['order_receive_no'],  $newOrderReceive['partition_no'],  $newOrderReceive['order_receive_date']);


        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    

    public function doUnAllocation($data) {
        $this->load->model('komoku_model');

        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];
        $updateFlg = false;
        // get order receive
        $orderReceive = $this->getReceivedOrderByID($data['order_receive_no'], $data['order_receive_date'], $data['partition_no']);

        if (!$orderReceive) {
            throw new Error('order receive not exist');
        }
        $orderReceive = (array)$orderReceive;

        // get detail
        $this->db->select('*');
        $this->db->from('t_orders_receive_details');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $detail = $this->db->get()->row();

        if (!$detail) {
            throw new Error('detail not exist');
        }
        $detail = (array)$detail;
        // get store
        $this->db->select('*');
        $this->db->from('t_store_item');
        $this->db->where('del_flg', '0');
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('odr_recv_date', $data['order_receive_date']);
        $this->db->where('odr_recv_detail_no', $data['order_receive_detail_no']);
        $storeList = $this->db->get()->result_array();
        if(!$storeList){
            throw new Error('store not exist');
        }
        $this->db->trans_begin();

        foreach($storeList as $store){
            $dataSearch = $store;
            $dataSearch['order_receive_no'] = substr_replace($store['order_receive_no'], 'xx', 0, 2);
            $dataSearch['partition_no'] = 1;
            $storeInfo = $this->store_item_model->getStoreItemsByKey($dataSearch);
            if (count($storeInfo) > 0) {
                $storeInfo = $storeInfo[0];
                $storeInfo['inspect_ok'] += $store['inspect_ok'];
                $storeInfo['quantity'] += $store['quantity'];
                $storeInfo['edit_date'] = $now;
                $storeInfo['edit_user'] = $currentEmployeeId;
                $result = $this->store_item_model->updateStoreItem($storeInfo);
                if($result){
                    $updateFlg = $result;
                    $this->store_item_model->deleteStoreItem($store);
                }
            } else {
                $warehouse = $this->komoku_model->getWarehouseWithName($store['warehouse'], true);

                $dataUpdate = array(
                    "status" => '010',
                    "order_receive_no" => substr_replace($store['order_receive_no'], 'xx', 0, 2),
                    "partition_no" => 1,
                    "odr_recv_date" => null,
                    "odr_recv_detail_no" => null,
                    "warehouse" => $warehouse[0]['kubun'],
                    "note" => '',
                    "edit_date" => $now,
                    "edit_user" => $currentEmployeeId,
                );

                $where = array(
                    "salesman" => $store["salesman"],
                    "item_code" => $store["item_code"],
                    "item_type" => $store["item_type"],
                    "order_no" => $store["order_no"],
                    "order_detail_no" => $store["order_detail_no"],
                    "warehouse" => $store["warehouse"],
                    "size" => $store["size"],
                    "color" => $store["color"],
                    "order_receive_no" => $store["order_receive_no"],
                    "partition_no" => $store["partition_no"],
                    "del_flg" => '0',
                );

                $updateFlg = $this->store_item_model->update($where, $dataUpdate);
            }
        }
        if (!$updateFlg) {
            throw new Error('Update fail!');
        }
        // update current detail
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('order_receive_detail_no', $data['order_receive_detail_no']);
        $this->db->update('t_orders_receive_details', array(
            'hikiate_quantity' => 0,
            'inv_no' => null,
            'status' => null,
            'edit_date' => $now,
            'edit_user' => $currentEmployeeId,
        ));
        
        // update current order receive status
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->update('t_orders_receive', array(
            'status' => '001',
            'edit_date' => $now,
            'edit_user' => $currentEmployeeId,
        ));

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
    * @param string order_receive_no, partition_no, order_receive_date, edit_date
    *               status, edit_user, edit_date
    * @return null || order_receive
    */
    public function update_sr($data)
    {
        $this->db->trans_begin();
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('del_flg', '0');
        $this->db->update('t_orders_receive', $data);

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            return $this->search_sale($data['order_receive_no'], $data['partition_no'], $data['order_receive_date']);
        } else {
            $this->db->trans_rollback();
            return null;
        }
    }

    /**
    * @param string order_receive_no, partition_no, order_receive_date, edit_date
    *               status, edit_user, edit_date
    * @return null || order_receive
    */
    public function update_sales($data)
    {
        $this->db->trans_begin();
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->where('del_flg', '0');
        $this->db->update('t_orders_receive', $data);

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            return TRUE;
        } else {
            $this->db->trans_rollback();
            return FALSE;
        }
    }
    
    // Get all data order receive 
    public function getDataOderReceive($params = null, $start=null, $length=null, &$recordsTotal=null, &$recordsFiltered=null){
        // get company info
        $this->db->select('DISTINCT ON ("ho"."company_id") m_company.company_name , m_company.short_name, m_company.reference, ho.head_office_name, ho.company_id, ho.head_office_address, ho.head_office_phone, ho.head_office_tel, ho.head_office_fax');
        $this->db->from('m_company_headoffice ho');
        $this->db->join('m_company',"m_company.company_id = ho.company_id AND m_company.type='2' AND m_company.del_flg='0'",'inner');
        $this->db->where('ho.del_flg', '0');
        $this->db->order_by('ho.company_id');
        $head_office = $this->db->get_compiled_select();
        $this->db->distinct();
        $this->db->select("odr.currency,odr.sum_amount,
                            odr.identify_name,
                            (case when odr.staff = '' then 'Free' else odr.staff end) as staff,
                            det.order_receive_no,
                            odr.delivery_date,
                            det.partition_no,
                            det.order_receive_detail_no,
                            det.composition_1,
                            det.composition_2,
                            det.composition_3,
                            det.size_unit,
                            det.order_receive_date,
                            det.item_code,
                            det.item_name,
                            det.size,
                            det.color,
                            det.quantity,
                            det.amount,
                            det.unit,
                            odr.currency,
                            (case when odr.currency = 'VND' then item.buy_price_vnd
                                  when odr.currency = 'JPY' then item.buy_price_jpy
                                  when odr.currency = 'USD' then item.buy_price_usd end) as sell_price,
                            item.vendor,
                            item.note_lapdip,
                            ho.company_name,
                            ho.head_office_name,
                            ho.head_office_address,
                            ho.head_office_phone,
                            ho.head_office_fax,
                            ho.reference");
        $this->db->from('t_orders_receive odr');
        $this->db->join("t_orders_receive_details det", "det.del_flg = '0' and odr.order_receive_no = det.order_receive_no and odr.partition_no = det.partition_no and odr.order_receive_date = det.order_receive_date", 'inner');
        $this->db->join("m_items item", "item.del_flg = '0' and trim(item.item_code) = trim(det.item_code) and item.size = det.size and item.color = det.color", 'left');
        $this->db->join("($head_office) ho", 'trim(item.vendor) = trim(ho.short_name)','left');
        $recordsTotal = $this->db->count_all_results(null, false);
        
        if(!empty($params['item_code']) && !empty($params['item_name'])){
            $this->db->where("(det.item_code LIKE '%".$params['item_code']."%' OR det.item_name LIKE '%".$params['item_name']."%')");
        } else {
            if (!empty($params['item_code'])) {
                $this->db->like('det.item_code', $params['item_code']);
            }
            if (!empty($params['item_name'])) {
                $this->db->like('det.item_name', $params['item_name']);
            }
        }
        if (!empty($params['pv_no'])) {
            $this->db->like('odr.order_receive_no', $params['pv_no']);
        }
        if (!empty($params['saleman'])) {
            $this->db->like('staff', $params['saleman']);
        }
        if (!empty($params['color'])) {
            $this->db->like('det.color', $params['color']);
        }
        if (!empty($params['size'])) {
            $this->db->like('det.size', $params['size']);
        }
        if (!empty($params['supplier'])) {
            $this->db->where("(item.vendor IS NULL OR item.vendor LIKE '%".$params['supplier']."%')");
        }
        if(!empty($params["from_date"])){
            $this->db->where("det.create_date >= ", $params["from_date"]);
        }
        if(!empty($params["to_date"])){
            $this->db->where("det.create_date <=", $params["to_date"]);
        }
        $this->db->where('odr.del_flg', '0');
        $this->db->order_by('det.item_code', 'ASC');

        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function search_sale($order_date=null, $dvt_no=null, $times=null)
    {
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->select(" 'Contact No' as contract_no,
                            dvt.dvt_no,
                            dvt.times,
                            dvt.order_date,
                            dvt.passage_date,
                            dvt.buyer as company_name,
                            dvt.staff as input_user_nm,
                            dvt.status,
                            sum(kvt.quantity) as sum_quantity,
                            sum(kvt.quantity * kvt.sell_price) as sum_amount,
                            sum(kvt.quantity * kvt.base_price) as sum_amount_base,
                            (sum(kvt.quantity * kvt.sell_price) -sum(kvt.quantity * kvt.base_price)) as dif_amount,
                            kmk.komoku_name_2 as odr_status,
                            dvt.delivery_require_date as delivery_date,
                            dvt.payment_date,
                            dvt.edit_user,
                            dvt.edit_date");
        $this->db->from('t_dvt dvt');
        $this->db->join('t_kvt kvt', "dvt.dvt_no = kvt.dvt_no AND dvt.order_date = kvt.order_date AND dvt.times = kvt.times AND kvt.del_flg = '0'", 'left');
        $this->db->join("($komoku_) kmk", 'dvt.status = kmk.kubun', 'left');
        if($order_date != null ){
           $this->db->where('dvt.order_date', $order_date);
        }
        if($times != null ){
           $this->db->where('dvt.times', $times);
        }
        if($dvt_no != null ){
           $this->db->where('trim(dvt.dvt_no)', trim($dvt_no));
        }
        $this->db->where('dvt.del_flg', '0');
        $this->db->where('dvt.kubun', '2');
       $this->db->group_by('dvt.dvt_no, dvt.times, dvt.order_date, dvt.buyer, dvt.staff, dvt.delivery_require_date, dvt.payment_date, dvt.edit_user, dvt.edit_date, kmk.komoku_name_2');

        $this->db->order_by('dvt.dvt_no', 'ASC');
        $this->db->order_by('dvt.times', 'ASC');
        $this->db->order_by('dvt.order_date', 'ASC');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_update($params)
    {
        $this->db->select("1");
        $this->db->from('t_orders_receive');
        $this->db->like('order_receive_no', $params['order_receive_no']);
        $this->db->where('partition_no', $params['partition_no']);
        $this->db->where('order_receive_date', $params['order_receive_date']);
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

    public function import($dataOrderReceives) {
        $this->db->trans_begin();

        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];
        $error_array = [];

        foreach ($dataOrderReceives as $key => $dataOrderReceive) {
            if($this->getPartitionNo($dataOrderReceive['order_receive_no'], $dataOrderReceive['order_receive_date'], '0') != 0) {
                $error_array[$key] = ['order_receive_no' => $dataOrderReceive['order_receive_no'],
                                        'order_receive_date' => $dataOrderReceive['order_receive_date'],
                                        'message' => $this->lang->line('order_receive_existed')];
            }
        }
        if(count($error_array) == 0) {
            foreach ($dataOrderReceives as $key => $dataOrderReceive) {
                $details = $dataOrderReceive['details'];
                unset($dataOrderReceive['details']);
                // if($dataOrderReceive["style"] === "A-8170&#39;S"){
                //     echo "test";
                // }
                $dataOrderReceive["style"] = revert_json($dataOrderReceive["style"]);
                $dataOrderReceive['create_user'] = $currentEmployeeId;
                $dataOrderReceive['create_date'] = $now;
                $dataOrderReceive['status'] = '001';
                $dataOrderReceive['customer'] = "SHIMADA SHOJI CO., LTD";
                
                // delete orderReceive has del_flg = 1
                $this->deleteReceivedOrderBeforeInsert($dataOrderReceive['order_receive_no'], '001', $dataOrderReceive['order_receive_date'], '1');
                // insert order receive
                $orderReceive = $this->insertReceivedOrderUnique($dataOrderReceive);

                //delete details
                $this->order_received_details_model
                    ->deleteReceivedOrderDetailBeforeInsert(
                        $orderReceive['order_receive_no'],
                        $orderReceive['partition_no'],
                        $orderReceive['order_receive_date']
                    );
                // insert details
                foreach ($details as $key => $detail) {
                    $detail["sell_price"] = (double)$detail["sell_price"];
                    $detail["amount"] = (double)$detail["amount"];
                    $detail["base_price"] = (double)$detail["base_price"];
                    $detail["amount_base"] = (double)$detail["amount_base"];

                    $splitData = array_merge($detail, [
                        'partition_no' => $orderReceive['partition_no'],
                        'hikiate_quantity' => 0,
                        'create_date' => $now,
                        'create_user' => $currentEmployeeId,
                        'status' => Null,
                    ]);
                    unset($splitData['item_code_flg']);
                    $this->order_received_details_model
                        ->insertReceivedOrder(
                            $orderReceive['order_receive_no'],
                            $orderReceive['partition_no'],
                            $orderReceive['order_receive_date'],
                            $splitData
                        );
                }
                $this->order_received_details_model->TotalCalculator($orderReceive['order_receive_no'],  $orderReceive['partition_no'],  $orderReceive['order_receive_date']);
            }
        } else {
            $this->db->trans_complete();
            return $error_array;
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

    // Created by Khanh
    // Date : 13/04/2018
    // Get data order receive for schedule list
    public function getOrderRecForSchedule($kubun = null, $start = null, $length = null,&$recordsTotal=null, &$recordsFiltered=null){
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun, $col_lang as status");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        $status_ = $this->db->get_compiled_select();
        
        $this->db->select("odre.order_receive_no, odre.partition_no, odre.order_receive_date,
                        odre.contract_no, st.status");
        $this->db->from('t_orders_receive odre');
        $recordsTotal = $this->db->count_all_results(null, false);
        $this->db->join("($status_) st", 'odre.status = st.kubun', 'left');
        if($kubun != null){
            $this->db->where('odre.kubun', $kubun);
        }
        $this->db->where_in('odre.status', array('002'));
        $this->db->where('odre.del_flg', '0');

        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

	public function countOrder($target_tbl){
        $this->db->select('count(*) as count');
        $this->db->from($target_tbl);
		$this->db->where('del_flg', '0');
		$query = $this->db->get();
		$result = $query->result_array();
		if($query->num_rows() > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function getKomokuStatus() {
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun, $col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        return $this->db->get_compiled_select();
    }

    public function countScheduledInput($komoku_, $accpt_flg, $office = null) {
        $this->db->select('count(*) as count');
        $this->db->from('t_orders_receive odre');
        $this->db->where("odre.status != '015'");
        $this->db->where('del_flg', '0');
        if(isset($office)) {
            $this->db->where("accpt_flg is NULL or accpt_flg = '$accpt_flg'");
        } else {
            $this->db->where("accpt_flg is NULL");
        }
        $this->db->join("($komoku_) kmk", 'odre.status = kmk.kubun', 'left');

        $query = $this->db->get();
        $result = $query->result_array();
        if($query->num_rows() > 0) {
            return $result[0];
        } else {
            return [];
        }
    }

    public function countDeleveryDateInput($komoku_) {
        $this->db->select('count(*) as count');
        $this->db->from('t_orders_receive odre');
        $this->db->where('del_flg', '0');
        $this->db->where('plan_delivery_date is NULL');
        $this->db->where("odre.status != '015'");
        $this->db->join("($komoku_) kmk", 'odre.status = kmk.kubun', 'left');

        $query = $this->db->get();
        $result = $query->result_array();
        if($query->num_rows() > 0) {
            return $result[0];
        } else {
            return [];
        }
    }

    public function countSalesInput($komoku_) {
        $this->db->select("count(*) as count");
        $this->db->from('t_orders_receive odre');
        $this->db->join('m_employee emp', 'odre.input_user = emp.employee_id', 'left');
        $this->db->join("($komoku_) kmk", 'odre.status = kmk.kubun', 'left');
        $this->db->where('odre.del_flg', '0');
        $this->db->where('odre.payment_date is NULL');
        $this->db->where("odre.status != '015'");

        $query = $this->db->get();
        $result = $query->result_array();
        if($query->num_rows() > 0) {
            return $result[0];
        } else {
            return [];
        }
    }

    public function countInventoryInput() {
        $this->db->select("count(*) as count");
        $this->db->from('t_store_item SI');
        $this->db->join('m_komoku inventory_status', "SI.status = inventory_status.kubun AND inventory_status.komoku_id = '".KOMOKU_STATUS."'", 'left');
        $this->db->where('SI.del_flg', "0");
        $this->db->where_in('SI.status', array('010','012'));

        $query = $this->db->get();
        $result = $query->result_array();
        if($query->num_rows() > 0) {
            return $result[0];
        } else {
            return [];
        }
    }

    public function countDeliveryPackingOrder($columnIdx = 1) {
        $this->db->select("count(*) as count");
        $this->db->from('t_dvt');
        $this->db->join('m_komoku MK', "MK.del_flg = '0' AND MK.komoku_id = '" . KOMOKU_SHIPPING_METHOD . "' AND MK.kubun = t_dvt.delivery_method", 'left');
        $this->db->join('m_komoku MK2', "MK2.del_flg = '0' AND MK2.komoku_id = '" . KOMOKU_STATUS . "' AND MK2.kubun = t_dvt.status", 'left');
        $this->db->join('m_employee EM', "EM.employee_id = t_dvt.salesman", 'left');
        // $this->db->join('t_kvt', "t_dvt.dvt_no = t_kvt.dvt_no AND t_dvt.order_date = t_kvt.order_date AND t_dvt.times = t_kvt.times AND t_kvt.del_flg = '0'", 'left');
        $this->db->where('t_dvt.del_flg', "0");
        if($columnIdx === 1) {
            $this->db->where("t_dvt.packing_date <=", date('Y-m-d'));
        } else {
            $this->db->where("t_dvt.delivery_require_date <=", date('Y-m-d'));
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return ($query->num_rows() > 0) ? $result[0] : [];
    }

    public function countDeliveryDate() {
       $this->db->select("count(*)")
                ->from("t_orders_receive")
                ->where("delivery_date < plan_delivery_date", null, false)
                ->where("status != '015'")
                ->where("del_flg","0");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]["count"];
    }

    // Created by Khanh
    // Date : 18/04/2018
    // Get data for page Scheduled Input
    public function getScheduledInput($params = null){
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->select('odre.order_receive_no
                            , odre.partition_no
                            , odre.order_receive_date
                            , odre.kubun
                            , odre.staff
                            , kmk.komoku_name_2 as odre_status
                            , odre.delivery_date
                            , odre.plan_inspect_date
                            , ( CASE WHEN odre.plan_delivery_date IS NOT NULL THEN odre.plan_delivery_date ELSE  MAX (ord.delivery_date) END ) as plan_delivery_date
                            , odre.plan_packing_date
                            , odre.accpt_flg
                            , odre.edit_date
                            , odre.wish_delivery_date');
        $this->db->from('t_orders_receive odre');
        $this->db->join('t_orders_receive_details ord',"ord.order_receive_no = odre.order_receive_no and ord.partition_no = odre.partition_no and ord.order_receive_date = odre.order_receive_date and ord.del_flg = '0'", 'left');
        $this->db->group_by('odre.order_receive_no, odre.partition_no, odre.order_receive_date, odre.staff, kmk.komoku_name_2, odre.delivery_date, 
        odre.plan_inspect_date, odre.plan_delivery_date, odre.plan_packing_date, odre.accpt_flg, odre.edit_date, odre.wish_delivery_date');
        $this->db->where('odre.del_flg', '0');
        $this->db->join("($komoku_) kmk", 'odre.status = kmk.kubun', 'left');
        $this->db->order_by("odre.order_receive_no", "ASC");
        $this->db->order_by("odre.partition_no", "ASC");
        $this->db->order_by("odre.order_receive_date", "ASC");
        $this->db->order_by("odre.status", "ASC");
        if(isset($params['order_receive_no'])){
            $this->db->where('odre.order_receive_no', $params['order_receive_no']);
        }
        if(isset($params['partition_no'])){
            $this->db->where('odre.partition_no', $params['partition_no']);
        }
        if(isset($params['order_receive_date'])){
            $this->db->where('odre.order_receive_date', $params['order_receive_date']);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if($query->num_rows() > 0) {
            return $result;
        } else {
            return [];
        }
    }

    // Created by Khanh
    // Date : 20/04/2018
    // update for schedule list 
    public function updateSchedule($data = null){
        $this->db->where('order_receive_no', $data['order_receive_no']);
        $this->db->where('partition_no', $data['partition_no']);
        $this->db->where('order_receive_date', $data['order_receive_date']);
        $this->db->update('t_orders_receive', $data);
        $num_update = $this->db->affected_rows();
        if ($num_update > 0) {
            return $this->getScheduledInput($data);
        }
        return null;
    }
    // Created by Khanh
    // Date : 19/04/2018
    // Accept schedule list item
    public function acceptReceivedOrder($params){
        $this->db->trans_begin();
        $this->db->set('accpt_flg', '1');
        $this->db->set('edit_date', $params['edit_date']);
        $this->db->set('edit_user', $params['edit_user']);
        $this->db->where('order_receive_no', $params['order_receive_no']);
        $this->db->where('partition_no', $params['partition_no']);
        $this->db->where('order_receive_date', $params['order_receive_date']);
        $query = $this->db->update('t_orders_receive');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $this->order_received_model->getScheduledInput($params);
        }
    }
    // confirm order received
    public function confirmReceivedOrder($params){
        $this->db->trans_begin();
        $this->db->set('accpt_flg', '2');
        $this->db->set('edit_date', $params['edit_date']);
        $this->db->set('edit_user', $params['edit_user']);
        $this->db->where('accpt_flg','1');
        $this->db->where('order_receive_no', $params['order_receive_no']);
        $this->db->where('partition_no', $params['partition_no']);
        $this->db->where('order_receive_date', $params['order_receive_date']);
        $query = $this->db->update('t_orders_receive');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // Created by Khanh
    // Date :20/04/2018
    // Check edit date
    public function checkEditDate($params = null){
        $this->db->select('edit_date');
        $this->db->from('t_orders_receive');
        $this->db->where('order_receive_no', $params['order_receive_no']);
        $this->db->where('partition_no', $params['partition_no']);
        $this->db->where('order_receive_date', $params['order_receive_date']);
        $query = $this->db->get();

        $result = $query->result_array();
        if($query->num_rows() > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function update_pv($pv_arr)
    {
        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];
        $this->db->trans_begin();
        foreach($pv_arr as $pv){
            $pvNO = explode("-",$pv);
            $this->db->set('status', '008');
            $this->db->where('order_receive_no', $pvNO[0]);
            $this->db->where('partition_no', isset($pvNO[1]) ? $pvNO[1] : 1);
            $query = $this->db->update('t_orders_receive',array(
                'edit_date' => $now,
                'edit_user' => $currentEmployeeId
            ));
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function updatePVStatus($params)
    {
        $this->db->trans_begin();
        $this->db->where('order_receive_no', $params['order_receive_no']);
        $this->db->where('partition_no', $params['partition_no']);
        $this->db->where('del_flg', '0');
        if(isset($params['order_receive_date'])){
            $this->db->where('order_receive_date', $params['order_receive_date']);
        }
        $this->db->update('t_orders_receive', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function getPVCurrency($pvNoList){
        if($pvNoList == null || $pvNoList == ''){
            return [];
        }
        $res = array();
        $pvNoList = explode(",", $pvNoList);
        foreach($pvNoList as $pv){
            $pv = explode("*", $pv)[0];
            $pvNo = explode("-", $pv);
            $this->db->distinct();
            $this->db->select("ODR.currency");
            $this->db->from('t_orders_receive ODR');
            $this->db->where('ODR.del_flg', '0');
            $this->db->where('ODR.order_receive_no', $pvNo[0]);
            $this->db->where('ODR.partition_no', isset($pvNo[1]) ? $pvNo[1] : 1);
            $this->db->where("(ODR.status <> '015' OR ODR.status IS NULL)");
            $query = $this->db->get();
            $result = $query->result_array();
            if (sizeof($result) > 0) {
                $res = array_merge($res, $result);
            }
        }
        return $res;
    }
    public function getAnotherPV() {
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select("trim(order_receive_no) as dvt_no, '' as kvt_no, partition_no as times, order_receive_date as order_date, komoku_name_2 as status");
        $this->db->from('t_orders_receive odr');
        $this->db->join("($komoku_) kmk", 'odr.status = kmk.kubun', 'left');
        $this->db->where('odr.del_flg', '0');
        $this->db->where('odr.kubun', '2');
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0 ){
            foreach ($result as &$pv) {
                $maxTimes = $this->getPartitionNo($pv['dvt_no'], $pv['order_date'], '0');
                $pv['count'] = $maxTimes;
            }
        }
        return $result;
    }
}
