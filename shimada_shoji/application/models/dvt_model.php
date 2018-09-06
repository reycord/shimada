<?php

/**
 * Apparel
 */

class DVT_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    // get all DVT
    public function getAllDVT($kubun = 1)
    {
        $col_lang = getColStatusByLang($this);
        $shipping = KOMOKU_SHIPPING_METHOD;
        $status = KOMOKU_STATUS;
        $sql = "SELECT DISTINCT t_dvt.inv_flg, t_dvt.order_date, t_dvt.delivery_require_date, t_dvt.dvt_no, t_dvt.times, (CASE WHEN MK.komoku_name_2 is null THEN t_dvt.delivery_method ELSE MK.komoku_name_2 END ) as komoku_name_2,
                                t_dvt.staff, t_dvt.staff_id, t_dvt.measurement_date, t_dvt.factory, t_dvt.address, trim(t_kvt.contract_no) AS contract_no, t_kvt.stype_no, t_kvt.o_no, t_dvt.factory_require_date, t_dvt.factory_plan_date,
                                t_dvt.delivery_plan_date, t_dvt.pv_infor, t_dvt.pv_in_date, t_dvt.packing_date, t_dvt.passage_date, t_dvt.factory_delivery_date, t_dvt.knq_delivery_date, t_dvt.print_date, t_dvt.assistance, t_dvt.status,
                                t_dvt.knq_fac_deli_date, t_dvt.note, t_dvt.case_mark, t_dvt.case_mark_text ,concat(EM.first_name ,' ', EM.last_name) AS SalesmanName, EM.employee_id, t_dvt.edit_date, t_dvt.buyer, MK2.$col_lang AS status_name
                FROM t_dvt
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_dvt.delivery_method
                LEFT JOIN m_komoku MK2 ON MK2.del_flg = '0' AND MK2.komoku_id = '$status' AND MK2.kubun = t_dvt.status
                LEFT JOIN m_employee EM ON EM.employee_id = t_dvt.salesman
                LEFT JOIN t_kvt ON t_dvt.dvt_no = t_kvt.dvt_no AND t_dvt.order_date = t_kvt.order_date AND t_dvt.times = t_kvt.times AND t_kvt.del_flg = '0'
                WHERE t_dvt.del_flg = '0' 
                    AND t_dvt.kubun = '$kubun' ORDER BY t_dvt.dvt_no, t_dvt.times ASC";
        $query = $this->db->query($sql);
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function getDVTForContractPrint($kubun = '1')
    {
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select("trim(dvt.dvt_no) as dvt_no, trim(kvt.kvt_no) as kvt_no, dvt.times, dvt.order_date, komoku_name_2 as status");
        $this->db->from("t_dvt dvt");
        $this->db->join("t_kvt kvt","dvt.order_date = kvt.order_date and dvt.dvt_no = kvt.dvt_no and dvt.times = kvt.times","inner");
        $this->db->join("($komoku_) kmk", 'dvt.status = kmk.kubun', 'left');
        $this->db->where("dvt.del_flg", "0");
        $this->db->where("dvt.kubun", $kubun);
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0 ){
            foreach ($result as &$dvt) {
                $maxTimes = $this->getMaxTimes($kubun, $dvt['dvt_no'], $dvt['order_date'], '0');
                $dvt['count'] = $maxTimes;
            }
        }
        return $result;
    }

    /**
     * Get all dvt group concat (contract_no, stype_no, o_no)
     * 
     * @param kubun
     * @author DucTam
     * @return Array{DVT}
     */
    public function getAllDVTAndGroup($kubun = '1')
    {
        $col_lang = getColStatusByLang($this);
        $shipping = KOMOKU_SHIPPING_METHOD;
        $status = KOMOKU_STATUS;
        $sql = "SELECT DISTINCT t_dvt.inv_flg, t_dvt.order_date, t_dvt.delivery_require_date, t_dvt.dvt_no, t_dvt.times,  (CASE WHEN MK.komoku_name_2 is null THEN t_dvt.delivery_method ELSE MK.komoku_name_2 END ) AS komoku_name_2, t_dvt.staff, t_dvt.staff_id, t_dvt.measurement_date,
                    t_dvt.factory, t_dvt.address, string_agg(trim(t_kvt.contract_no), ', ') AS contract_no, string_agg(distinct  t_kvt.stype_no, ', ') as stype_no, string_agg(distinct t_kvt.o_no, ', ') as o_no, t_dvt.factory_require_date, t_dvt.factory_plan_date, t_dvt.delivery_plan_date,
                    t_dvt.pv_infor, t_dvt.pv_in_date, t_dvt.packing_date, t_dvt.passage_date, t_dvt.factory_delivery_date, t_dvt.knq_delivery_date, t_dvt.print_date, t_dvt.assistance, t_dvt.status,
                    t_dvt.knq_fac_deli_date, t_dvt.note, t_dvt.case_mark ,concat(EM.first_name ,' ', EM.last_name) AS SalesmanName, EM.employee_id, t_dvt.edit_date, t_dvt.buyer, MK2.$col_lang AS status_name
                    FROM t_dvt
                    LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_dvt.delivery_method
                    LEFT JOIN m_komoku MK2 ON MK2.del_flg = '0' AND MK2.komoku_id = '$status' AND MK2.kubun = t_dvt.status
                    LEFT JOIN m_employee EM ON EM.employee_id = t_dvt.salesman
                    LEFT JOIN t_kvt ON t_dvt.dvt_no = t_kvt.dvt_no AND t_dvt.order_date = t_kvt.order_date AND t_dvt.times = t_kvt.times AND t_kvt.del_flg = '0'
                    WHERE t_dvt.del_flg = '0'  AND t_dvt.kubun = '$kubun' 
                    GROUP BY t_dvt.order_date, t_dvt.delivery_require_date, t_dvt.dvt_no, t_dvt.times, MK.komoku_name_2, t_dvt.staff, t_dvt.staff_id, t_dvt.measurement_date,
                    t_dvt.factory, t_dvt.address, t_dvt.factory_require_date, t_dvt.factory_plan_date, t_dvt.delivery_plan_date,
                            t_dvt.pv_infor, t_dvt.pv_in_date, t_dvt.packing_date, t_dvt.passage_date, t_dvt.factory_delivery_date, t_dvt.knq_delivery_date, t_dvt.print_date, t_dvt.assistance, t_dvt.status,
                    t_dvt.knq_fac_deli_date, t_dvt.note, t_dvt.case_mark,SalesmanName,EM.employee_id, t_dvt.edit_date, t_dvt.buyer, status_name
                    ORDER BY t_dvt.dvt_no ASC";
        $query = $this->db->query($sql);
        $res = $query->result_array();
        foreach ($res as &$dvt) {
            $maxTimes = $this->getMaxTimes($kubun, $dvt['dvt_no'], $dvt['order_date'], '0');
            $dvt['times_count'] = $maxTimes;
        }
        return $res;
    }


    // get DVT by ID
    public function getAllDVTByID($order_date = null, $dvt_no = null, $times = null, $kubun = 1)
    {
        $col_lang = getColStatusByLang($this);
        $shipping = KOMOKU_SHIPPING_METHOD;
        $status = KOMOKU_STATUS;
        $sql = "SELECT DISTINCT t_dvt.inv_flg, t_dvt.order_date, t_dvt.delivery_require_date, t_dvt.dvt_no, t_dvt.times, (CASE WHEN MK.komoku_name_2 is null THEN t_dvt.delivery_method ELSE MK.komoku_name_2 END ) as komoku_name_2, t_dvt.staff, t_dvt.staff_id, t_dvt.measurement_date,
                                t_dvt.factory, t_dvt.address, t_kvt.contract_no, t_kvt.stype_no, t_kvt.o_no, t_dvt.factory_require_date, t_dvt.factory_plan_date, t_dvt.delivery_plan_date,
                                t_dvt.pv_infor, t_dvt.pv_in_date, t_dvt.packing_date, t_dvt.passage_date, t_dvt.factory_delivery_date, t_dvt.knq_delivery_date, t_dvt.print_date, t_dvt.assistance, t_dvt.status,
                                t_dvt.knq_fac_deli_date, t_dvt.note, t_dvt.case_mark, t_dvt.case_mark_text, concat(EM.first_name ,' ', EM.last_name) AS SalesmanName, EM.employee_id, t_dvt.edit_date, t_dvt.buyer, MK2.$col_lang AS status_name
                FROM t_dvt
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_dvt.delivery_method
                LEFT JOIN m_komoku MK2 ON MK2.del_flg = '0' AND MK2.komoku_id = '$status' AND MK2.kubun = t_dvt.status
                LEFT JOIN m_employee EM ON EM.employee_id = t_dvt.salesman
                LEFT JOIN t_kvt ON t_dvt.dvt_no = t_kvt.dvt_no AND t_dvt.order_date = t_kvt.order_date AND t_dvt.times = t_kvt.times AND t_kvt.del_flg = '0'
                WHERE t_dvt.del_flg = '0' 
                    AND t_dvt.kubun = '$kubun'
                    AND t_dvt.order_date = '$order_date' 
                    AND trim(t_dvt.dvt_no) = trim('$dvt_no') 
                    AND t_dvt.times = $times";
        $query = $this->db->query($sql);
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }
    // get DVT by ID
    public function getEditDateDVTByID($order_date = null, $dvt_no = null, $times = null, $kubun = 1)
    {
        $sql = "SELECT DISTINCT edit_date, edit_user
                FROM t_dvt
                WHERE t_dvt.del_flg = '0' 
                    AND t_dvt.kubun = '$kubun'
                    AND t_dvt.order_date = '$order_date' 
                    AND trim(t_dvt.dvt_no) = trim('$dvt_no') 
                    AND t_dvt.times = $times";
        $query = $this->db->query($sql);
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array()[0];
        }
        return [];
    }
    // get DVT by ID
    public function getDVTInfoByID($order_date = null, $dvt_no = null, $times = null, $kubun = 1)
    {
        $col_lang = getColStatusByLang($this);
        $shipping = KOMOKU_SHIPPING_METHOD;
        $status = KOMOKU_STATUS;
        $sql = "SELECT DISTINCT t_dvt.inv_flg, t_dvt.order_date, t_dvt.delivery_require_date, t_dvt.dvt_no, t_dvt.times, MK.komoku_name_2, t_dvt.staff, t_dvt.staff_id, t_dvt.measurement_date,
                    t_dvt.factory, t_dvt.address, t_kvt.contract_no, t_kvt.stype_no, t_kvt.o_no, t_dvt.factory_require_date, t_dvt.factory_plan_date, t_dvt.delivery_plan_date,
                    t_dvt.pv_infor, t_dvt.pv_in_date, t_dvt.packing_date, t_dvt.passage_date, t_dvt.factory_delivery_date, t_dvt.knq_delivery_date, t_dvt.print_date, t_dvt.assistance, t_dvt.assistance, t_dvt.status,
                    t_dvt.knq_fac_deli_date, t_dvt.note, concat(EM.last_name ,' ', EM.first_name) AS SalesmanName, EM.employee_id, t_dvt.edit_date, t_dvt.buyer, MK2.$col_lang AS status_name
                FROM t_dvt
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_dvt.delivery_method
                LEFT JOIN m_komoku MK2 ON MK2.del_flg = '0' AND MK2.komoku_id = '$status' AND MK2.kubun = t_dvt.status
                LEFT JOIN m_employee EM ON EM.employee_id = t_dvt.salesman
                LEFT JOIN t_kvt ON t_dvt.dvt_no = t_kvt.dvt_no AND t_dvt.order_date = t_kvt.order_date AND t_dvt.times = t_kvt.times AND t_kvt.del_flg = '0'
                WHERE t_dvt.del_flg = '0' 
                    AND t_dvt.kubun = '$kubun'
                    AND t_dvt.order_date = '$order_date' 
                    AND trim(t_dvt.dvt_no) = trim('$dvt_no') 
                    AND t_dvt.times = $times";
        $query = $this->db->query($sql);
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }
    public function getDVTForKVTInsert($order_date = null, $dvt_no = null, $times = null, $kubun = '1')
    {
        $this->db->select('staff, staff_id, assistance, delivery_method, factory, address');
        $this->db->from('t_dvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $order_date);
        $this->db->where('trim(dvt_no)', trim($dvt_no));
        $this->db->where('times', $times);
        $this->db->where('kubun', $kubun);
        $query = $this->db->get();
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function updateDVTCurrency($params)
    {
        $this->db->trans_begin();
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->set('currency', $params['currency']);
        $query = $this->db->update('t_dvt');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    // get DVT by ID
    public function getDVTByID($order_date = null, $dvt_no = null, $times = null, $kubun = 1)
    {
        if ($order_date == null || $order_date == '') {
            return [];
        }
        $sql = "SELECT t_dvt.pv_infor
                FROM t_dvt
                WHERE t_dvt.del_flg = '0' AND t_dvt.order_date = '$order_date' AND trim(t_dvt.dvt_no) = trim('$dvt_no') AND t_dvt.times = $times AND t_dvt.kubun = '$kubun'";
        $query = $this->db->query($sql);
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array();
        }
        return [];
    }
    // update DVT
    public function update($params, $kubun = 1)
    {
        $this->db->trans_begin();
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kubun', '' . $kubun);
        $this->db->where('times', $params['times']);
        $query = $this->db->update('t_dvt', $params);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $this->getAllDVTByID($params['order_date'], $params['dvt_no'], $params['times'], $kubun);
        }
    }
    // check DVT is exists
    public function check_dvt_exists($params, $kubun = '1')
    {
        $this->db->select("*");
        $this->db->from('t_dvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kubun', $kubun);
        $this->db->where('times', $params['times']);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    // Check delivery_order_exist
    public function check_delivery_order_exist($params, $kubun = '1')
    {
        $this->db->select("*");
        $this->db->from('t_dvt');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kubun', $kubun);
        $this->db->where('times', $params['times']);
        $this->db->where('del_flg', '0');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // delete DVT
    public function delete($params, $kubun = '1')
    {
        $this->db->trans_begin();
        $data = array(
            'del_flg' => '1',
            'edit_user' => $params['edit_user'],
            'edit_date' => $params['edit_date']
        );

        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('kubun', $kubun);
        $this->db->update('t_dvt', $data);

        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->update('t_kvt', $data);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    // get all KVT
    public function getAllKVT($kubun = '1', $orderBy = false, $dvt=null)
    {
        $shipping = KOMOKU_SHIPPING_METHOD;
        $sql = "SELECT DISTINCT t_kvt.kvt_no, t_kvt.dvt_no, t_kvt.order_date, t_kvt.staff, t_kvt.assistance, trim(t_kvt.contract_no) as contract_no, t_kvt.o_no, t_kvt.times, t_kvt.quantity,t_kvt.packing_date, t_kvt.composition_2,t_kvt.detail_no,
                        t_dvt.factory_delivery_date as delivery_date, t_kvt.factory, t_kvt.staff_id, t_kvt.stype_no, t_kvt.size, t_kvt.pv_no, t_kvt.address, t_kvt.item_jp_code, t_kvt.composition_1, t_kvt.edit_date, t_kvt.status,
                        t_kvt.color, t_kvt.item_code, t_kvt.item_name, '1' as times_count, (CASE WHEN MK.komoku_name_2 is null THEN t_dvt.delivery_method ELSE MK.komoku_name_2 END ) as delivery_method, t_kvt.composition_3, t_dvt.status as dvt_status 
                FROM t_kvt
                JOIN t_dvt ON t_dvt.del_flg = '0' AND t_dvt.kubun = '$kubun' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_kvt.delivery_method
                WHERE t_kvt.del_flg = '0'";
        if($dvt != null) {
            $sql = $sql." AND trim(t_kvt.dvt_no) = "."'".$dvt['delivery_no']."'";
            $sql = $sql." AND t_kvt.order_date = "."'".$dvt['delivery_date']."'";
            $sql = $sql." AND t_kvt.times = "."'".$dvt['times']."'";
        }
        if ($orderBy) {
            $sql .= " ORDER BY t_kvt.kvt_no, t_kvt.dvt_no, t_kvt.times, t_kvt.item_code ASC";
        } else {
            $sql .= " ORDER BY t_kvt.detail_no ASC";
        }

        $query = $this->db->query($sql);
        $kvtList = $query->result_array();
        if (sizeof($kvtList) > 0) {
            //return $query->result_array();
            foreach ($kvtList as &$kvt) {
                $maxPartition = $this->getMaxTimes($kubun, $kvt['dvt_no'], $kvt['order_date'], '0');
                if ($maxPartition >= 2) {
                    $kvt['times_count'] = $maxPartition;
                }
                $arrival_date = '';
                $item_quantity = 0;
                if ($kvt['pv_no'] != '') {
                    $pvList = explode(',', $kvt['pv_no']);
                    if (count($pvList) > 0) {
                        foreach ($pvList as $pv) {
                            $pvNO = explode('*', $pv);
                            $pvNO = explode('-', $pvNO[0]);
                            if (isset($pvNO[0])) {
                                $this->db->select("TO_CHAR(arrival_date, 'YYYY-MM-DD') as arrival_date, quantity");
                                $this->db->from('t_store_item');
                                $this->db->where('del_flg', '0');
                                $this->db->where('color', $kvt['color']);
                                $this->db->where('size', $kvt['size']);
                                $this->db->where('order_receive_no', $pvNO[0]);
                                $this->db->where('item_code', $kvt['item_code']);
                                $queryStore = $this->db->get();
                                $storeItem = $queryStore->result_array();
                                if (count($storeItem) > 0) {
                                    $arrival_date .= $storeItem[0]['arrival_date'];
                                    $item_quantity += (int)$storeItem[0]['quantity'];
                                }
                            }
                        }
                    }
                }
                $kvt['arrival_date'] = $arrival_date;
                $kvt['item_quantity'] = $item_quantity;
            }
            return $kvtList;
        }
        return [];
    }

    // get KVT by ID
    public function getAllKVTByID($order_date = null, $dvt_no = null, $times = null, $kubun = '1')
    {
        $shipping = KOMOKU_SHIPPING_METHOD;
        $sql = "SELECT DISTINCT t_kvt.kvt_no, t_kvt.dvt_no, t_kvt.order_date, t_kvt.staff, t_kvt.assistance, t_kvt.contract_no, t_kvt.o_no, t_kvt.times, t_kvt.quantity,t_kvt.packing_date,t_kvt.composition_2,t_kvt.detail_no,
                        t_dvt.factory_delivery_date as delivery_date, t_kvt.factory, t_kvt.staff_id, t_kvt.stype_no, t_kvt.size, t_kvt.pv_no, t_kvt.address, t_kvt.item_jp_code,t_kvt.composition_3, t_kvt.edit_date, t_kvt.status,
                        t_kvt.color, t_kvt.item_code, t_kvt.item_name, (CASE WHEN MK.komoku_name_2 is null THEN t_dvt.delivery_method ELSE MK.komoku_name_2 END ) as delivery_method, t_kvt.composition_1, t_dvt.status as dvt_status 
                FROM t_kvt
                JOIN t_dvt ON t_dvt.del_flg = '0' AND t_dvt.kubun = '$kubun' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_kvt.delivery_method
                WHERE t_kvt.del_flg = '0'
                AND t_kvt.order_date = '$order_date'
                AND trim(t_kvt.dvt_no) = trim('$dvt_no')
                AND t_kvt.times = $times
                ORDER BY t_kvt.detail_no, t_kvt.size, t_kvt.color ASC";
        $query = $this->db->query($sql);
        $kvtList = $query->result_array();
        if (sizeof($kvtList) > 0) {
            foreach ($kvtList as &$kvt) {
                $maxPartition = $this->getMaxTimes($kubun, $kvt['dvt_no'], $kvt['order_date'], '0');
                if ($maxPartition >= 2) {
                    $kvt['times_count'] = $maxPartition;
                }
                $arrival_date = '';
                $item_quantity = 0;
                if ($kvt['pv_no'] != '') {
                    $pvList = explode(',', $kvt['pv_no']);
                    if (count($pvList) > 0) {
                        foreach ($pvList as $pv) {
                            $pvNO = explode('*', $pv);
                            $pvNO = explode('-', $pvNO[0]);
                            if (isset($pvNO[0])) {
                                $this->db->select("TO_CHAR(arrival_date, 'YYYY-MM-DD') as arrival_date, quantity");
                                $this->db->from('t_store_item');
                                $this->db->where('del_flg', '0');
                                $this->db->where('color', $kvt['color']);
                                $this->db->where('size', $kvt['size']);
                                $this->db->where('order_receive_no', $pvNO[0]);
                                $this->db->where('item_code', $kvt['item_code']);
                                $queryStore = $this->db->get();
                                $storeItem = $queryStore->result_array();
                                if (count($storeItem) > 0) {
                                    $arrival_date .= $storeItem[0]['arrival_date'];
                                    $item_quantity += (int)$storeItem[0]['quantity'];
                                }
                            }
                        }
                    }
                }
                $kvt['arrival_date'] = $arrival_date;
                $kvt['item_quantity'] = $item_quantity;
            }
            return $kvtList;
        }
        return [];
    }
    public function getMaxTimes($kubun, $dvt_no, $order_date, $del_flag)
    {
        $this->db->select_max('times');
        $this->db->where('trim(dvt_no)', trim($dvt_no));
        $this->db->where('order_date', $order_date);
        $this->db->where('kubun', $kubun);
        $this->db->where('del_flg', $del_flag);
        $result = $this->db->get('t_dvt');
        $value = $result->row()->times;
        $value = (int)$value;
        return $value;
    }
     // get KVT info by ID
    public function getAllKVTInfoByID($order_date = null, $dvt_no = null, $times = null, $kubun = 1)
    {
        $shipping = KOMOKU_SHIPPING_METHOD;
        $sql = "SELECT DISTINCT t_kvt.kvt_no, t_kvt.dvt_no, t_kvt.order_date, t_kvt.staff, t_kvt.assistance, t_kvt.contract_no, t_kvt.o_no, t_kvt.times, t_kvt.quantity,t_kvt.packing_date,t_kvt.composition_2,t_kvt.detail_no,
                        t_kvt.delivery_date, t_kvt.factory, t_kvt.delivery_method, t_kvt.staff_id, t_kvt.stype_no, t_kvt.size, t_kvt.pv_no, t_kvt.address, t_kvt.item_jp_code,t_kvt.composition_3,t_kvt.edit_date,
                        t_kvt.color, t_kvt.item_code, t_kvt.item_name, MK.komoku_name_2 shipping_method, t_kvt.composition_1,
                        IT.size_unit, IT.unit, t_kvt.status, t_dvt.status as dvt_status
                FROM t_kvt
                JOIN t_dvt ON t_dvt.del_flg = '0' AND t_dvt.kubun = '$kubun' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_kvt.delivery_method
                LEFT JOIN m_items IT ON IT.del_flg = '0' AND IT.color  = t_kvt.color AND IT.size = t_kvt.size AND t_kvt.item_code = IT.item_code
                WHERE t_kvt.del_flg = '0'
                AND t_kvt.order_date = '$order_date' 
                AND trim(t_kvt.dvt_no) = trim('$dvt_no') 
                AND t_kvt.times = $times
                ORDER BY t_kvt.detail_no, t_kvt.size, t_kvt.color ASC";
        $query = $this->db->query($sql);
        $kvtList = $query->result_array();
        if (sizeof($kvtList) > 0) {
            //return $query->result_array();
            foreach ($kvtList as &$kvt) {
                $arrival_date = '';
                $item_quantity = 0;
                if ($kvt['pv_no'] != '') {
                    $pvList = explode(',', $kvt['pv_no']);
                    if (count($pvList) > 0) {
                        foreach ($pvList as $pv) {
                            $pvNO = explode('*', $pv);
                            $pvNO = explode('-', $pvNO[0]);
                            if (isset($pvNO[0])) {
                                $this->db->select("TO_CHAR(arrival_date, 'YYYY-MM-DD') as arrival_date, quantity");
                                $this->db->from('t_store_item');
                                $this->db->where('del_flg', '0');
                                $this->db->where('color', $kvt['color']);
                                $this->db->where('size', $kvt['size']);
                                $this->db->where('order_receive_no', $pvNO[0]);
                                $this->db->where('item_code', $kvt['item_code']);
                                $queryStore = $this->db->get();
                                $storeItem = $queryStore->result_array();
                                if (count($storeItem) > 0) {
                                    $arrival_date .= $storeItem[0]['arrival_date'];
                                    $item_quantity += (int)$storeItem[0]['quantity'];
                                }
                            }
                        }
                    }
                }
                $kvt['arrival_date'] = $arrival_date;
                $kvt['item_quantity'] = $item_quantity;
            }
            return $kvtList;
        }
        return [];
    }
    // update KVT
    public function updateKVT($params, $kubun = 1)
    {
        $this->db->trans_begin();
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $query = $this->db->update('t_kvt', $params);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $this->getAllKVTByKVTID($params['order_date'], $params['dvt_no'], $params['kvt_no'], $params['times'], $kubun);
        }
    }
    // get KVT by KVTID
    public function getAllKVTByKVTID($order_date = null, $dvt_no = null, $kvt_no = null, $times = null, $kubun = 1)
    {
        $shipping = KOMOKU_SHIPPING_METHOD;
        $sql = "SELECT DISTINCT t_kvt.kvt_no, t_kvt.dvt_no, t_kvt.order_date, t_kvt.detail_no, t_kvt.staff, t_kvt.assistance, t_kvt.contract_no, t_kvt.o_no, t_kvt.times, t_kvt.quantity, t_kvt.packing_date, t_kvt.composition_3,t_kvt.edit_date,
                        t_kvt.delivery_date, t_kvt.factory, t_kvt.delivery_method, t_kvt.staff_id, t_kvt.stype_no, t_kvt.size, t_kvt.pv_no, t_kvt.create_date, t_kvt.address, t_kvt.item_jp_code, t_kvt.composition_2, t_kvt.detail_no,
                        t_kvt.color, t_kvt.item_code, t_kvt.item_name, MK.komoku_name_2 shipping_method, t_kvt.composition_1, t_kvt.status, t_dvt.status as dvt_status,
                        t_kvt.sell_price, t_kvt.buy_price, t_kvt.base_price, t_kvt.shosha_price,mit.sell_price_vnd,mit.base_price_vnd,mit.shosha_price_vnd,mit.sell_price_usd,mit.base_price_usd,mit.shosha_price_usd, mit.sell_price_jpy,mit.base_price_jpy,mit.shosha_price_jpy,
                        mit.buy_price_vnd,mit.buy_price_usd,mit.buy_price_jpy,t_dvt.currency
                FROM t_kvt
                JOIN t_dvt ON t_dvt.del_flg = '0' AND t_dvt.kubun = '$kubun' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times
                LEFT JOIN m_komoku MK ON MK.del_flg = '0' AND MK.komoku_id = '$shipping' AND MK.kubun = t_kvt.delivery_method
                LEFT JOIN (select DISTINCT ON (jp_code, item_code, size, color) jp_code, item_code, size, color, 
                                            buy_price_vnd ,sell_price_vnd,base_price_vnd,shosha_price_vnd,
                                            buy_price_usd ,sell_price_usd,base_price_usd,shosha_price_usd,
                                            buy_price_jpy ,sell_price_jpy,base_price_jpy,shosha_price_jpy
                          FROM m_items WHERE del_flg = '0'
                          ORDER BY jp_code, item_code, size, color) mit
                          ON t_kvt.item_code = mit.item_code AND t_kvt.item_jp_code = mit.jp_code AND t_kvt.color  = mit.color AND t_kvt.size = mit.size
                WHERE t_kvt.del_flg = '0'
                AND t_kvt.order_date = '$order_date'
                AND trim(t_kvt.dvt_no) = trim('$dvt_no')
                AND t_kvt.kvt_no = '$kvt_no' 
                AND t_kvt.times = $times
                ORDER BY t_kvt.detail_no, t_kvt.size, t_kvt.color ASC";
        $query = $this->db->query($sql);
        $kvtList = $query->result_array();
        if (sizeof($kvtList) > 0) {
            //return $query->result_array();
            foreach ($kvtList as &$kvt) {
                $arrival_date = '';
                $item_quantity = 0;
                if ($kvt['pv_no'] != '') {
                    $pvList = explode(',', $kvt['pv_no']);
                    if (count($pvList) > 0) {
                        foreach ($pvList as $pv) {
                            $pvNO = explode('*', $pv)[0];
                            if (strrpos($pvNO, '-') !== false) {
                                $pvNO = substr($pvNO, 0, strrpos($pvNO, '-'));
                            }
                            if (isset($pvNO)) {
                                $this->db->select("TO_CHAR(arrival_date, 'YYYY-MM-DD') as arrival_date, quantity");
                                $this->db->from('t_store_item');
                                $this->db->where('del_flg', '0');
                                $this->db->where('color', $kvt['color']);
                                $this->db->where('size', $kvt['size']);
                                $this->db->where('order_receive_no', $pvNO);
                                $this->db->where('item_code', $kvt['item_code']);
                                $queryStore = $this->db->get();
                                $storeItem = $queryStore->result_array();
                                if (count($storeItem) > 0) {
                                    $arrival_date .= $storeItem[0]['arrival_date'];
                                    $item_quantity += (int)$storeItem[0]['quantity'];
                                }
                            }
                        }
                    }
                }
                $kvt['arrival_date'] = $arrival_date;
                $kvt['item_quantity'] = $item_quantity;
            }
            return $kvtList;
        }
        return [];
    }
    // get edit date KVT by KVTID
    public function getEditDateOfKVTByID($order_date = null, $dvt_no = null, $kvt_no = null, $times = null)
    {
        $sql = "SELECT DISTINCT edit_date, edit_user, detail_no
                FROM t_kvt
                WHERE t_kvt.del_flg = '0'
                AND t_kvt.order_date = '$order_date' 
                AND trim(t_kvt.dvt_no) = trim('$dvt_no')
                AND t_kvt.kvt_no = '$kvt_no' 
                AND t_kvt.times = $times
                ORDER BY t_kvt.detail_no ASC";
        $query = $this->db->query($sql);
        // echo $this->db->last_query()
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array()[0];
        }
        return [];
    }
    // get edit date KVT by Item ID
    public function getEditDateOfKVTByItemID($order_date = null, $dvt_no = null, $kvt_no = null, $detail_no = null, $times = null, $item_code = null, $color = null, $size = null)
    {
        $sql = "SELECT DISTINCT edit_date, edit_user
                FROM t_kvt
                WHERE t_kvt.del_flg = '0'
                AND t_kvt.order_date = '$order_date'
                AND trim(t_kvt.dvt_no) = trim('$dvt_no')
                AND t_kvt.kvt_no = '$kvt_no'
                AND t_kvt.detail_no = '$detail_no'
                AND t_kvt.item_code = '$item_code'
                AND t_kvt.color = '$color'
                AND t_kvt.size = '$size'
                AND t_kvt.times = $times";
        $query = $this->db->query($sql);
        // echo $this->db->last_query()
        if (sizeof($query->result_array()) > 0) {
            return $query->result_array()[0];
        }
        return [];
    }
    // delete KVT
    public function deleteKVT($params)
    {
        $this->db->trans_begin();
        $data = array(
            'del_flg' => '1',
            'edit_user' => $params['edit_user'],
            'edit_date' => $params['edit_date']
        );
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->update('t_kvt', $data);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    // check KVT is exists
    public function check_kvt_exists($params)
    {
        $this->db->select("1");
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // check Item of KVT is exists
    public function check_kvt_item_exists($params)
    {
        $this->db->select("1");
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        if (isset($params['detail_no'])) {
            $this->db->where('detail_no', $params['detail_no']);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // get KVT by id
    public function get_kvt_by_id($params)
    {
        $this->db->select("*");
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        if (isset($params['detail_no'])) {
            $this->db->where('detail_no', $params['detail_no']);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    // check Item of delete KVT is exists
    public function check_kvt_deleted_item_exists($params)
    {
        $this->db->select("1");
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '1');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //  delete KVT
    public function deleteOldKVT($params)
    {
        $this->db->where('del_flg', '1');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        $this->db->delete('t_kvt');
        $this->db->affected_rows();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // check other KVT is exists
    public function check_other_kvt_item_exists($params)
    {
        $this->db->select("1");
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no !=', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // delete KVT
    public function deleteKVTItem($params, $kubun = 1)
    {
        $this->db->trans_begin();
        $data = array(
            'del_flg' => '1',
            'edit_user' => $params['edit_user'],
            'edit_date' => $params['edit_date']
        );
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        if (isset($params['kvt_no'])) {
            $this->db->where('kvt_no', $params['kvt_no']);
        }
        if (isset($params['detail_no'])) {
            $this->db->where('detail_no', $params['detail_no']);
        }
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        $this->db->update('t_kvt', $data);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $this->getAllKVTByKVTID($params['order_date'], $params['dvt_no'], $params['kvt_no'], $params['times'], $kubun);;
        }
    }
    // update KVT Item
    public function updateKVTItem($params, $kubun = 1)
    {
        $this->db->trans_begin();
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('kvt_no', $params['kvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('item_code', $params['item_code']);
        $this->db->where('color', $params['color']);
        $this->db->where('size', $params['size']);
        if (isset($params['detail_no'])) {
            $this->db->where('detail_no', $params['detail_no']);
        }
        $query = $this->db->update('t_kvt', $params);
        // echo $this->db->last_query();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $this->getAllKVTByKVTID($params['order_date'], $params['dvt_no'], $params['kvt_no'], $params['times'], $kubun);
        }
    }
    // insert to kvt
    public function insertKVT($insertData = [], $updateData = [])
    {
        $this->db->trans_begin();
        foreach ($insertData as $data) {
            $resultCheck = $this->check_kvt_deleted_item_exists($data);
            if ($resultCheck) {
                $this->deleteOldKVT($data);
            }
            $detail_no = $this->getMaxKVT($data['order_date'], $data['dvt_no'], $data['kvt_no'], $data['times']);
            $data['detail_no'] = $detail_no + 1;
            $this->db->insert('t_kvt', $data);
        }
        foreach ($updateData as $data) {
            $this->db->where('order_date', $data['order_date']);
            $this->db->where('dvt_no', $data['dvt_no']);
            $this->db->where('kvt_no', $data['kvt_no']);
            $this->db->where('times', $data['times']);
            $this->db->where('item_code', $data['item_code']);
            $this->db->where('color', $data['color']);
            $this->db->where('size', $data['size']);
            $query = $this->db->update('t_kvt', $data);
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    // get max detail_no in kvt
    public function getMaxKVT($order_date = null, $dvt_no = null, $kvt_no = null, $times = null)
    {
        $this->db->select_max("detail_no");
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '0');
        $this->db->where('order_date', $order_date);
        $this->db->where('trim(dvt_no)', trim($dvt_no));
        $this->db->where('kvt_no', $kvt_no);
        $this->db->where('times', $times);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array()[0]['detail_no'];
        } else {
            return 0;
        }
    }
    // insert dvt - create by: thanh
    public function insert($params)
    {
        $this->db->trans_begin();
        $this->db->select("1");
        $this->db->from('t_dvt');
        $this->db->where('dvt_no', $params['dvt_no']);
        $this->db->where('order_date', $params['order_date']);
        $this->db->where('times', $params['times']);
        $this->db->where('kubun', $params['kubun']);
        $this->db->where('del_flg', '1');

        $check_exist = $this->db->get();
        if ($check_exist->num_rows() > 0) {
            $data_del = array(
                'dvt_no' => $params['dvt_no'],
                'order_date' => $params['order_date'],
                'times' => $params['times'],
                'kubun' => $params['kubun'],
            );
            $this->db->delete('t_dvt', $data_del);

            foreach ($params as $key => $value) {
                if ($value == '') {
                    $params[$key] = null;
                }
            }
            $this->db->insert('t_dvt', $params);
        } else {
            foreach ($params as $key => $value) {
                if ($value == '') {
                    $params[$key] = null;
                }
            }
            $this->db->insert('t_dvt', $params);
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $this->dvt_model->getDVT($params['order_date'], $params['dvt_no'], $params['times'], $params['kubun']);
        }
    }

    public function getDVT($order_date, $dvt_no, $times, $kubun = 1)
    {
        $col_lang = getColStatusByLang($this);
        $shipping = KOMOKU_SHIPPING_METHOD;
        $status = KOMOKU_STATUS;
        $sql = "SELECT t_dvt.inv_flg, t_dvt.order_date, t_dvt.delivery_require_date, t_dvt.dvt_no, t_dvt.times, 
                        MK.komoku_name_2, t_dvt.staff, t_dvt.staff_id, t_dvt.measurement_date, t_dvt.edit_date,
                        t_dvt.factory, t_dvt.address, t_kvt.contract_no, t_kvt.stype_no, t_kvt.o_no, t_dvt.case_mark,t_dvt.case_mark_text,
                        t_dvt.factory_require_date, t_dvt.factory_plan_date, t_dvt.delivery_plan_date,
                        t_dvt.pv_infor, t_dvt.pv_in_date, t_dvt.packing_date, t_dvt.passage_date, 
                        t_dvt.factory_delivery_date, t_dvt.knq_delivery_date, t_dvt.buyer, t_dvt.status, MK2.$col_lang AS status_name,
                        t_dvt.knq_fac_deli_date, t_dvt.note, concat(EM.last_name ,' ', EM.first_name) AS salesmanname, EM.employee_id
                from t_dvt
                left join m_komoku MK on MK.del_flg = '0' and MK.komoku_id = '$shipping' and MK.kubun = t_dvt.delivery_method
                LEFT JOIN m_komoku MK2 ON MK2.del_flg = '0' AND MK2.komoku_id = '$status' AND MK2.kubun = t_dvt.status
                left join m_employee EM on EM.employee_id = t_dvt.salesman
                left join t_kvt on t_dvt.dvt_no = t_kvt.dvt_no and t_dvt.order_date = t_kvt.order_date and t_dvt.times = t_kvt.times and t_kvt.del_flg = '0'
                where t_dvt.del_flg = '0'
                and t_dvt.kubun = '$kubun'
                and t_dvt.order_date = '$order_date'
                and t_dvt.times = '$times'
                and trim(t_dvt.dvt_no) = trim('$dvt_no') ";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        if ($query->num_rows() > 0) {
            return $result[0];
        } else {
            return null;
        }
    }
    /**
     * get All DVT to print
     * @param datatable page
     * 
     * @author Duc Tam
     * @return DVT
     */
    function getAllDVTToPrint($start, $length, &$recordsFiltered, &$recordsTotal)
    {
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', 'KM0001');
        $this->db->where('del_flg', '0');
        $komoku = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select('kubun,komoku_name_2');
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_SHIPPING_METHOD);
        $this->db->where('del_flg', '0');
        $komoku2 = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->distinct();
        $this->db->select('delivery_no, times, payment, buyer, buyer_add, seller, seller_add, customer, customer_code, consignee,  print_date, print_currency, rate, rate_jpy, rate_jpy_usd,  notify, notify_add, other_reference, invoice_no, red_invoice_no, contract_no, delivery_date, from, to, vessel_flight, payment_term, delivery_condition, note');
        $this->db->from('t_print');
        $this->db->where('del_flg', '0');
        $print = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->distinct();
        $this->db->select('dvt_no , contract_no');
        $this->db->from('t_kvt');
        $this->db->where('del_flg', '0');
        $kvt = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->distinct();
        $this->db->select("det.dvt_no , trim(to_char(det.pack_no,'9999999999')) as pack_no, det.times, pac.customer, pac.delry_to, pac.delry_to_add");
        $this->db->from('t_packing_details det');
        $this->db->join('t_packing pac', 'det.pack_no = pac.pack_no', 'left');
        $this->db->where('det.del_flg', '0');
        $this->db->where('pac.del_flg', '0');
        $det = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->distinct();
        $this->db->select("dvt.dvt_no as delivery_no, 
                            dvt.order_date,
                            dvt.times,
                            dvt.kubun,
                            dvt.passage_date,
                            (case when pri.delivery_date IS NOT NULL then pri.delivery_date else dvt.delivery_plan_date end ) as delivery_date, 
                            dvt.delivery_method as delivery_method_code,
                            dvt.currency,
                            kmk2.komoku_name_2 as delivery_method,
                            dvt.status as status_code,
                            kmk.komoku_name_2 as status,
                            pri.buyer,
                            pri.buyer_add,
                            pri.print_date,
                            pri.payment,
                            pri.customer,
                            pri.consignee,
                            pri.customer_code,
                            pri.print_currency,
                            pri.rate,
                            pri.rate_jpy,
                            pri.rate_jpy_usd,
                            pri.from,
                            pri.to,
                            pri.vessel_flight,
                            pri.seller as header_name,
                            pri.seller_add as header_address,
                            pri.other_reference,
                            pri.notify,
                            pri.note,
                            pri.notify_add as notify_address,
                            pri.payment_term,
                            pri.delivery_condition,
                            pri.contract_no as contract_no_print,
                            string_agg(distinct kvt.contract_no, ',') as contract_no,
                            string_agg(distinct det.customer, ',') as pack_customer,
                            string_agg(distinct det.delry_to, ',') as pack_consigned_name,
                            string_agg(distinct det.delry_to_add, ',') as pack_consigned_to,
                            det.pack_no,
                            pri.invoice_no as invoice_no,
                            pri.red_invoice_no");
        $this->db->from('t_dvt dvt');
        $this->db->join("($komoku) kmk", 'dvt.status = kmk.kubun', 'left');
        $this->db->join("($komoku2) kmk2", 'dvt.delivery_method = kmk2.kubun', 'left');
        $this->db->join("($print) pri", 'pri.delivery_no = dvt.dvt_no AND pri.times = dvt.times', 'left');
        $this->db->join("($det) det", 'trim(dvt.dvt_no) = trim(det.dvt_no) AND dvt.times = det.times', 'inner');
        // $this->db->join("t_packing pack","trim(to_char(pack.pack_no,'9999999999')) = det.pack_no",'inner');
        $this->db->join("($kvt) kvt", 'dvt.dvt_no = kvt.dvt_no', 'left');
        $this->db->where('dvt.del_flg', '0');
        $this->db->group_by('dvt.dvt_no ,
                            dvt.order_date,
                            dvt.times,
                            dvt.delivery_method,
                            dvt.currency,
                            kmk.komoku_name_2,
                            pri.buyer,
                            pri.buyer_add,
                            pri.customer,
                            pri.consignee,
                            pri.seller,
                            pri.seller_add,
                            pri.customer_code,
                            pri.print_currency,
                            pri.rate,
                            pri.rate_jpy,
                            pri.rate_jpy_usd,
                            dvt.passage_date,
                            dvt.delivery_require_date,
                            kmk2.komoku_name_2,
                            pri.print_date,
                            pri.payment,
                            pri.other_reference,
                            pri.notify,
                            pri.notify_add,
                            pri.invoice_no,
                            dvt.status,
                            pri.invoice_no,
                            pri.red_invoice_no,
                            pri.contract_no,
                            pri.from,
                            pri.to,
                            pri.payment_term,
                            pri.delivery_condition,
                            pri.vessel_flight,
                            pri.note,
                            pri.delivery_date,
                            det.pack_no');
        $this->db->order_by('dvt.dvt_no, dvt.times, dvt.order_date, pri.buyer, pri.customer, pri.seller');
        $recordsTotal = $this->db->count_all_results(null, false);
        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function import($dataOrderReceives)
    {
        $this->db->trans_begin();
        $dvt_delivery_date = array();
        $now = date('Y-m-d H:i:s');
        $currentEmployeeId = $this->session->userdata("user")['employee_id'];
        $error_array = [];

        foreach ($dataOrderReceives['dvt'] as $key => $dataOrderReceive) {
            $countResult = $this->selectByConditions("t_dvt", $dataOrderReceive['order_date'], $dataOrderReceive['dvt_no'], '0', 1);
            if ($countResult > 0) {
                array_push($error_array, [
                    'order_date' => $dataOrderReceive['order_date'],
                    'dvt_no' => $dataOrderReceive['dvt_no'],
                    'message' => $this->lang->line('JOS0040_E001')
                ]);
            }
        }
        if (count($error_array) > 0) {
            $this->db->trans_complete();
            return $error_array;
        }

        foreach ($dataOrderReceives['dvt'] as $key => $dataOrderReceive) {
            $details = $dataOrderReceive['details'];
            $shippingMethod = $this->komoku_model->getShippingMethodByName($dataOrderReceive['delivery_method']);
            if ($shippingMethod !== null) {
                $dataOrderReceive['delivery_method'] = $shippingMethod['kubun'];
            }
            unset($dataOrderReceive['details']);
            $dvt_delivery_date[$dataOrderReceive['dvt_no']] = $dataOrderReceive['delivery_require_date'];
            $countResult = $this->selectByConditions("t_dvt", $dataOrderReceive['order_date'], $dataOrderReceive['dvt_no'], '0', 1);
            if ($countResult > 0) {
                return false;
            }
            foreach ($details as $key => $detail) {
                $countResult = $this->deleteByConditions("t_dvt", $dataOrderReceive['order_date'], $dataOrderReceive['dvt_no'], '1', 1);
                $countResult = $this->selectByConditions("t_dvt", $dataOrderReceive['order_date'], $dataOrderReceive['dvt_no'], '0', 1);
                $splitData = array_merge($dataOrderReceive, [
                    'times' => 1,
                    'inv_flg' => '0',
                    'print_date' => $dataOrderReceive['order_date'],
                    'delivery_require_date' => $dataOrderReceive['delivery_require_date'],
                    'delivery_plan_date' => $dataOrderReceive['delivery_require_date']
                ]);
                if ($countResult > 0) {
                    $splitData = array_merge($splitData, [
                        'edit_date' => $now,
                        'edit_user' => $currentEmployeeId,
                    ]);
                    $this->updateDvtKvt("t_dvt", $splitData, '0', 1);
                } else {
                    $splitData = array_merge($splitData, [
                        'create_date' => $now,
                        'create_user' => $currentEmployeeId,
                    ]);
                    $this->insertDvtKvt("t_dvt", $splitData);
                }
            }
        }

        foreach ($dataOrderReceives['kvt'] as $key => $dataOrderReceive) {
            $details = $dataOrderReceive['details'];
            unset($dataOrderReceive['details']);
            $keydvt = $key;
            foreach ($details as $key => $detail) {
                $countResult = $this->deleteByConditions("t_kvt", $dataOrderReceive['order_date'], $dataOrderReceive['dvt_no'], '1', 1, $dataOrderReceive['kvt_no'], $detail['detail_no'], $detail['item_code'], $detail['color'], $detail['size']);
                $countResult = $this->selectByConditions("t_kvt", $dataOrderReceive['order_date'], $dataOrderReceive['dvt_no'], '0', 1, $dataOrderReceive['kvt_no'], $detail['detail_no'], $detail['item_code'], $detail['color'], $detail['size']);

                $splitData = array_merge($dataOrderReceive, [
                    'stype_no' => $dataOrderReceive['style_no'],
                    'item_jp_code' => $detail['jp_code'],
                    'times' => 1,
                    'print_date' => $dataOrderReceive['order_date'],
                    'delivery_date' => $dvt_delivery_date[$dataOrderReceive['dvt_no']]
                ]);
                $splitData = array_merge($splitData, $detail);
                unset($splitData['style_no']);
                unset($splitData['jp_code']);
                unset($splitData['item_code_flg']);
                if ($countResult > 0) {
                    $splitData = array_merge($splitData, [
                        'edit_date' => $now,
                        'edit_user' => $currentEmployeeId,
                    ]);
                    $splitData['quantity'] = str_replace(",", "", $splitData['quantity']);
                    $this->updateDvtKvt("t_kvt", $splitData, '0', 1);
                } else {
                    $splitData = array_merge($splitData, [
                        'create_date' => $now,
                        'create_user' => $currentEmployeeId,
                    ]);
                    $splitData['quantity'] = str_replace(",", "", $splitData['quantity']);
                    $this->insertDvtKvt("t_kvt", $splitData);
                }
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

    public function selectByConditions($table, $order_date, $dvt_no, $del_flg, $times, $kvt_no = null, $detail_no = null, $item_code = null, $color = null, $size = null)
    {
        $this->db->reset_query();
        $this->db->select("*");
        $this->db->from($table);
        $this->db->where("order_date", $order_date);
        $this->db->where("trim(dvt_no)", trim($dvt_no));
        $this->db->where("del_flg", $del_flg);
        $this->db->where("times", $times);
        if ($kvt_no != null) {
            $this->db->where("kvt_no", $kvt_no);
            $this->db->where("detail_no", $detail_no);
            $this->db->where("item_code", $item_code);
            $this->db->where("color", $color);
            $this->db->where("size", $size);
        }
        return $this->db->count_all_results(null, false);
    }

    public function deleteByConditions($table, $order_date, $dvt_no, $del_flg, $times, $kvt_no = null, $detail_no = null, $item_code = null, $color = null, $size = null)
    {
        $this->db->reset_query();
        $this->db->where("order_date", $order_date);
        $this->db->where("trim(dvt_no)", trim($dvt_no));
        $this->db->where("del_flg", $del_flg);
        $this->db->where("times", $times);
        if ($kvt_no != null) {
            $this->db->where("kvt_no", $kvt_no);
            $this->db->where("detail_no", $detail_no);
            $this->db->where("item_code", $item_code);
            $this->db->where("color", $color);
            $this->db->where("size", $size);
        }
        $this->db->delete($table);
    }
    /**
     * 
     */
    public function checkDVTByConditions()
    {

    }

    public function updateDvtKvt($table, $data, $del_flg, $times)
    {
        $this->db->trans_begin();

        $this->db->where('order_date', $data['order_date']);
        $this->db->where('dvt_no', $data['dvt_no']);
        $this->db->where('del_flg', $del_flg);
        $this->db->where('times', $times);
        if ($table == "t_kvt") {
            $this->db->where('kvt_no', $data['kvt_no']);
        }
        $query = $this->db->update($table, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insertDvtKvt($table, $data)
    {
        $this->db->trans_begin();

        $this->db->insert($table, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    // get KVT info by ID
    public function getKVTForSalesContract($params)
    {
        // $this->db->select("
        //     t_kvt.kvt_no, 
        //     t_kvt.dvt_no, 
        //     t_kvt.order_date, 
        //     t_kvt.item_code, 
        //     t_kvt.item_name, 
        //     t_kvt.color,
        //     t_kvt.size,
        //     trim(t_kvt.unit) as unit,
        //     t_kvt.quantity,
        //     t_kvt.sell_price,
        //     t_kvt.composition_1,
        //     t_kvt.composition_2,
        //     t_kvt.composition_3,
        //     t_kvt.pv_no,
        //     t_dvt.currency,
        //     concat(t_kvt.buy_price, t_dvt.currency) as buy_price_currency,
        //     concat(t_kvt.sell_price, t_dvt.currency) as sell_price_currency,
        //     concat(t_kvt.base_price, t_dvt.currency) as base_price_currency,
        //     (t_kvt.quantity * t_kvt.sell_price) as amount,
        //     ((t_kvt.quantity * t_kvt.sell_price) - (t_kvt.quantity * t_kvt.base_price)) as dif_amount
        //     ");
        // $this->db->from('t_kvt');
        // $this->db->join('t_dvt', "t_dvt.del_flg = '0' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times ", 'inner');
        // $this->db->where("t_kvt.order_date", $params['order_date']);
        // $this->db->where("t_kvt.dvt_no", $params['dvt_no']);
        // $this->db->where("t_kvt.del_flg", '0');
        // $this->db->where("t_kvt.times", $params['times']);
        // $query = $this->db->get();
        // return $query->result_array();
        $this->db->select("
            det.kvt_no, 
            det.dvt_no, 
            det.order_date, 
            det.item_code, 
            det.item_name, 
            det.color,
            det.size,
            trim(det.unit) as unit,
            det.quantity,
            t_kvt.sell_price,
            t_kvt.composition_1,
            t_kvt.composition_2,
            t_kvt.composition_3,
            t_kvt.pv_no,
            t_dvt.currency,
            (det.quantity * t_kvt.sell_price) as amount
            ");
        $this->db->from("t_packing_details det");
        $this->db->join("t_kvt", "t_kvt.del_flg = '0' AND t_kvt.order_date = det.order_date AND trim(t_kvt.dvt_no) = trim(det.dvt_no) AND trim(t_kvt.kvt_no) = trim(det.kvt_no) AND t_kvt.times = det.times AND trim(det.jp_code) = trim(t_kvt.item_jp_code) AND trim(det.item_code) = trim(t_kvt.item_code) AND trim(det.size) = trim(t_kvt.size) AND trim(det.color) = trim(t_kvt.color)", 'inner');
        $this->db->join('t_dvt', "t_dvt.del_flg = '0' AND t_kvt.order_date = t_dvt.order_date AND trim(t_kvt.dvt_no) = trim(t_dvt.dvt_no) AND t_kvt.times = t_dvt.times ", 'inner');
        $this->db->where('det.del_flg', '0');
        if(!empty($params['pack_no'])){
            $this->db->where("det.pack_no", $params['pack_no']);
        }
        $this->db->where("trim(det.dvt_no)", trim($params['dvt_no']));
        $this->db->where("det.order_date", $params['order_date']);
        $this->db->where("det.times", $params['times']);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get KVT info for export eachtime contract 
    public function get_items_list($params)
    {
        $this->db->select("det.kvt_no, det.dvt_no, det.order_date, det.item_code, det.item_name, det.color,det.size,det.quantity,t_kvt.sell_price,t_kvt.pv_no, det.unit, t_dvt.currency");
        $this->db->from("t_packing_details det");
        $this->db->join("t_kvt", "t_kvt.del_flg = '0' AND t_kvt.order_date = det.order_date AND trim(t_kvt.dvt_no) =  trim(det.dvt_no) AND  trim(t_kvt.kvt_no) =  trim(det.kvt_no) AND t_kvt.times = det.times AND trim(det.jp_code) = trim(t_kvt.item_jp_code) AND trim(det.item_code) = trim(t_kvt.item_code) AND trim(det.size) = trim(t_kvt.size) AND trim(det.color) = trim(t_kvt.color)", 'inner');
        $this->db->join('t_dvt', "t_dvt.del_flg = '0' AND t_kvt.order_date = t_dvt.order_date AND  trim(t_kvt.dvt_no) =  trim(t_dvt.dvt_no) AND t_kvt.times = t_dvt.times ", 'inner');
        $this->db->where('det.del_flg', '0');
        $this->db->where("det.pack_no", $params['pack_no']);
        $this->db->where("trim(det.dvt_no)", trim($params['dvt_no']));
        $this->db->where("det.order_date", $params['order_date']);
        $this->db->where("det.times", $params['times']);
        $this->db->order_by('det.item_code', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }

    // Get KVT info for export eachtime contract 
    public function getItemsListForSalesExport($kubun, $dvt=null)
    {
        if($dvt != null) {
            $this->db->where('trim(t_kvt.dvt_no)', $dvt['delivery_no']);
            $this->db->where('t_kvt.order_date', $dvt['delivery_date']);
            $this->db->where('t_kvt.times', $dvt['times']);
        }
        $this->db->select("t_kvt.kvt_no, t_kvt.times, t_kvt.dvt_no, t_kvt.order_date, t_kvt.item_code, t_kvt.item_name, t_kvt.color, t_kvt.size, t_kvt.quantity, 
        t_kvt.base_price, t_kvt.shosha_price, t_kvt.sell_price, t_kvt.pv_no, t_kvt.unit, t_dvt.staff, t_dvt.staff_id, t_dvt.assistance, t_dvt.currency");
        $this->db->from("t_kvt");
        $this->db->join('t_dvt', "t_dvt.del_flg = '0' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times ", 'inner');
        $this->db->where('t_kvt.del_flg', '0');
        $this->db->where('t_dvt.kubun', $kubun);
        $this->db->order_by('t_kvt.item_code', 'asc');
        $query = $this->db->get();
        $res = $query->result_array();
        if (count($res) > 0) {
            foreach ($res as &$dvt) {
                $maxTimes = $this->getMaxTimes($kubun, $dvt['dvt_no'], $dvt['order_date'], '0');
                $dvt['times_count'] = $maxTimes;
            }
        }
        return $res;
    }

     // Get KVT info for export eachtime contract 
    public function getItemsListForSalesExport2($kubun, $dvt = null)
    {
        if($dvt != null) {
            $this->db->where('trim(t_kvt.dvt_no)', $dvt['delivery_no']);
            $this->db->where('t_kvt.order_date', $dvt['delivery_date']);
            $this->db->where('t_kvt.times', $dvt['times']);
        }
        $this->db->select("sum(t_kvt.quantity) as quantity, sum(t_kvt.quantity * t_kvt.base_price) as sum_base_amount, sum(t_kvt.quantity * t_kvt.sell_price) as sum_sell_amount, 
         sum(t_kvt.quantity * t_kvt.shosha_price) as sum_shosha_amount, sum(t_kvt.base_price) as base_price, sum(t_kvt.shosha_price) as shosha_price, sum(t_kvt.sell_price) as sell_price, 
         t_dvt.staff, t_dvt.staff_id, string_agg(distinct pv_no, ', ') as pv_no, t_dvt.currency");
        $this->db->from("t_kvt");
        $this->db->join('t_dvt', "t_dvt.del_flg = '0' AND t_kvt.order_date = t_dvt.order_date AND t_kvt.dvt_no = t_dvt.dvt_no AND t_kvt.times = t_dvt.times ", 'inner');
        $this->db->where('t_kvt.del_flg', '0');
        $this->db->where('t_dvt.kubun', $kubun);
        $this->db->group_by('t_dvt.staff, t_dvt.staff_id, t_dvt.currency');
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function search_sale(&$recordsTotal=null, &$recordsFiltered=null, $kubun, $params=null)
    {
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id', KOMOKU_STATUS);
        $this->db->where('kubun <>', '000');
        $this->db->where('del_flg', '0');
        $komoku_ = $this->db->get_compiled_select();

        $this->db->select(" trim(trailing '-' from cont.contract_no) as contract_no,
                             cont.customs_clearance_fee,
                             cont.transport_fee,
                             cont.receipt_type,
                             cont.submit_contract_date,
                             cont.note,
                             cont.delivery_no as dvt_no,
                             cont.times,
                             dvt.order_date,
                             dvt.delivery_require_date,
                             dvt.passage_date as passage_date,
                             com.short_name as company_name,
                             dvt.staff as input_user_nm,
                             sum(kvt.quantity) as sum_quantity,
                             sum(kvt.quantity * kvt.sell_price) as sum_amount,
                             sum(kvt.quantity * kvt.base_price) as sum_amount_base,
                             (sum(kvt.quantity * kvt.sell_price) - sum(kvt.quantity * kvt.base_price)) as dif_amount,
                             kmk.komoku_name_2 as odr_status,
                             cont.official_delivery_date,
                             cont.payment_date,
                             cont.edit_user,
                             cont.edit_date,
                             kmk2.komoku_name_2 as sales_status,
                             print.invoice_no,
                             print.vessel_flight,
                             print.customer as consignee,
                             print.seller,
                             dvt.currency,
                             dvt.status");
        $this->db->from('t_contract_print cont');
        $this->db->join('t_dvt dvt', "cont.delivery_no = dvt.dvt_no AND cont.times = dvt.times AND cont.delivery_date = dvt.order_date AND dvt.del_flg = '0' AND dvt.kubun = '$kubun'");
        $this->db->join('m_company com', "com.type = '1' and cont.party_b = com.company_name AND com.del_flg = '0'");
        $this->db->join('t_kvt kvt', "dvt.dvt_no = kvt.dvt_no AND dvt.order_date = kvt.order_date AND dvt.times = kvt.times AND kvt.del_flg = '0'");
        $this->db->join("($komoku_) kmk", 'dvt.status = kmk.kubun', 'left');
        $this->db->join("($komoku_) kmk2", 'cont.status = kmk2.kubun', 'left');
        $this->db->join('t_print print', "print.del_flg = '0' AND print.delivery_no = dvt.dvt_no AND print.delivery_date = dvt.delivery_require_date AND print.times = dvt.times", 'left');
        
        $this->db->where('cont.del_flg', '0');
        $this->db->where_in('cont.kubun', array('2001', '2002', '2003'));

        $recordsTotal = $this->db->count_all_results(null, false);

        if (isset($params['delivery_date_from'])) {
            $this->db->where('cont.official_delivery_date >=', $params['delivery_date_from']);
        }
        if (isset($params['delivery_date_to'])) {
            $this->db->where('cont.official_delivery_date <=', $params['delivery_date_to']);
        }
        $this->db->group_by('cont.contract_no, cont.delivery_no, cont.delivery_date, cont.times, dvt.delivery_require_date, com.short_name, dvt.staff, dvt.passage_date, dvt.payment_date, cont.edit_user, 
                              cont.edit_date, kmk.komoku_name_2,dvt.order_date, cont.customs_clearance_fee,cont.transport_fee,cont.official_delivery_date,cont.payment_date, kmk2.komoku_name_2,print.invoice_no,print.vessel_flight,print.customer,print.seller,
                              dvt.status, cont.receipt_type,cont.submit_contract_date,dvt.currency,cont.note');

        $this->db->order_by('cont.contract_no', 'DESC');

        $recordsFiltered = $this->db->count_all_results(null, false);
    
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getDataForSalesExport($partyKbn = '001', $kubun, $params=null)
    {
        $this->db->select(" trim(trailing '-' from cont.contract_no) as contract_no,
                             cont.times,
                             cont.contract_date,
                             cont.receipt_type,
                             cont.submit_contract_date,
                             cont.note,
                             trim(cont.delivery_no) as delivery_no,
                             cont.delivery_date,
                             dvt.delivery_require_date,
                             dvt.passage_date,
                             com.short_name as company_name,
                             cont.official_delivery_date,
                             print.invoice_no,
                             print.inventory_voucher_excel_no");
        $this->db->from('t_contract_print cont');
        $this->db->join('m_company com', "com.type = '1' and cont.party_b = com.company_name AND com.del_flg = '0'");
        $this->db->join('t_dvt dvt', "cont.delivery_no = dvt.dvt_no AND cont.times = dvt.times AND cont.delivery_date = dvt.order_date AND dvt.del_flg = '0' AND dvt.kubun = '$kubun'");
        $this->db->join('t_kvt kvt', "dvt.dvt_no = kvt.dvt_no AND dvt.order_date = kvt.order_date AND dvt.times = kvt.times AND kvt.del_flg = '0'");
        $this->db->join('t_print print', "print.del_flg = '0' AND print.delivery_no = dvt.dvt_no AND print.delivery_date = dvt.delivery_require_date AND print.times = dvt.times", 'left');
        $this->db->where('cont.del_flg', '0');
        $this->db->where('party_a', $partyKbn);
        $this->db->where_in('cont.kubun', array('2001', '2002', '2003'));
        if($params != null) {
            if(isset($params['delivery_date_from']) && $params['delivery_date_from'] != '') {
                $this->db->where('cont.official_delivery_date >=', $params['delivery_date_from']);
            }
            if(isset($params['delivery_date_to']) && $params['delivery_date_to'] != '') {
                $this->db->where('cont.official_delivery_date <=', $params['delivery_date_to']);
            }
        }
        $this->db->group_by('cont.contract_no, cont.times, cont.delivery_no, cont.delivery_date, dvt.delivery_require_date, com.short_name, dvt.passage_date, cont.contract_date,
                              cont.official_delivery_date, print.invoice_no, cont.receipt_type, cont.submit_contract_date, cont.note, print.inventory_voucher_excel_no');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_update($params)
    {
        $this->db->select("1");
        $this->db->from('t_dvt');
        $this->db->like('dvt_no', $params['dvt_no']);
        $this->db->where('times', $params['times']);
        $this->db->where('order_date', $params['order_date']);
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

    public function update_sales($data)
    {
        $this->db->trans_begin();
        $this->db->where('dvt_no', $data['dvt_no']);
        $this->db->where('times', $data['times']);
        $this->db->where('order_date', $data['order_date']);
        $this->db->where('del_flg', '0');
        $this->db->update('t_dvt', $data);

        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            return $this->search_sale($data['order_date'], $data['dvt_no'], $data['times']);
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }
}
