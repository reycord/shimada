<?php
/**
 * Delivery
 */

class Delivery_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /**
     * Select Diction all Delivery
     * @author: Duc Tam
     * @return delivery/null
     */
    public function getAllDelivery(){
        $this->db->distinct();
        $this->db->select("dvt_no");
        $this->db->from("t_dvt");
        $this->db->where('del_flg', '0');
        $this->db->order_by('dvt_no');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Update status Delivery when packing success Status: 013 | P/L済み
     * @param dvtNo,orderDate,times
     * @param updateUser
     * @author: Duc Tam
     * @return delivery/null
     */
     public function updateDeliveryStatus($dvtNo, $updateUser, $orderDate, $times, $status = "013"){
        $this->db->set("status", $status);
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->where("dvt_no", $dvtNo);
        if($orderDate != null && $orderDate != ''){
            $this->db->where("order_date", $orderDate);
        }
        if($times != null && $times != ''){
            $this->db->where("times", $times);
        }
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_dvt");
        return $result;
     }

    /**
     * Get All DVT&KVT
     * @param DVT{kubun}
     * @author Duc Tam
     * @return DVT&kvt/Array(0)
     */
    public function getAllDeliveryAndKVTByKubun($kubun = array("1","2"), $start, $length, &$recordsTotal, &$recordsFiltered, $dvtFactory = NULL){
        $this->db->distinct();
        $this->db->select("dvt.order_date, dvt.dvt_no, dvt.times , dvt.factory, kvt.kvt_no, kvt.case_mark, dvt.buyer as company_name, dvt.factory as branch_name, dvt.address as branch_address, dvt.kubun, dvt.packing_date, dvt.measurement_date, dvt.factory_delivery_date");
        $this->db->from("t_dvt dvt");
        $this->db->join("t_kvt kvt","dvt.order_date = kvt.order_date and dvt.dvt_no = kvt.dvt_no and dvt.times = kvt.times","inner");
        $this->db->where('kvt.del_flg', '0');
        if(NULL !== $dvtFactory && "" !== trim($dvtFactory)) { $this->db->where('dvt.factory', $dvtFactory); }
        $this->db->where_in('dvt.kubun', $kubun);
        $this->db->where('dvt.del_flg', '0');
        $this->db->group_start();
        $this->db->where("dvt.status !=", "013");
        $this->db->or_where("dvt.status", null);
        $this->db->group_end();
        $this->db->order_by('dvt.dvt_no,dvt.times,kvt.kvt_no,dvt.order_date');
        $recordsFiltered = $this->db->count_all_results(null, false);
        $recordsTotal = $recordsFiltered;
        $this->db->offset($start);
        $this->db->limit($length);
        $query = $this->db->get();
        $deliveryList = $query->result_array();
        foreach( $deliveryList as &$delivery){
            $this->db->reset_query();
            $this->db->distinct();
            $this->db->select("kvt.item_code, kvt.item_jp_code, kvt.item_name, kvt.color, kvt.size, kvt.quantity, kvt.inv_no, item.net_wt as netwt, kvt.unit, kvt.inv_no");
            $this->db->from("t_kvt kvt");
            $this->db->join("m_items item","item.del_flg = '0' and kvt.item_code = item.item_code and kvt.color = item.color and kvt.size = item.size","left");
            $this->db->where("kvt.dvt_no", $delivery["dvt_no"]);
            $this->db->where("kvt.kvt_no", $delivery["kvt_no"]);
            $this->db->where("kvt.times", $delivery["times"]);
            $this->db->where("kvt.order_date", $delivery["order_date"]);
            $this->db->group_start();
            $this->db->where("kvt.status !=", "013");
            $this->db->or_where("kvt.status",null);
            $this->db->group_end();
            $this->db->where('kvt.del_flg', '0');
            $query = $this->db->get();
            $kvtList = $query->result_array();
            foreach ($kvtList as &$kvt) {
                if($kvt['inv_no'] == null || $kvt['inv_no'] == ""){
                    $kvt['types'] = [];
                    continue;
                }
                $invNoList = explode(",",$kvt['inv_no']);
                $typeList = [];
                foreach ($invNoList as $invNo) {
                    $lastIndex = strrpos($invNo, "-");
                    if($lastIndex !== FALSE){
                        $invNo = substr($invNo, 0, $lastIndex);
                    }
                    $this->db->select("kmk.komoku_name_2");
                    $this->db->from("t_store_item st");
                    $this->db->join("m_komoku kmk","st.item_type = kmk.kubun and kmk.komoku_id = '".KOMOKU_ITEMTYPE."'","inner");
                    $this->db->where("trim(st.invoice_no)", trim($invNo));
                    $query = $this->db->get();
                    $invoice = $query->result_array();
                    if(count($invoice) > 0){
                        array_push($typeList,($invoice[0]['komoku_name_2']));
                    }
                }
                $kvt['types'] = $typeList;
            }
            $delivery["kvt_list"] = $kvtList;
        }
        return  $deliveryList;
    }
    /**
     * Get All Item by kvt and dvt
     * 
     * @author Duc Tam
     * @return Item/Array(0)
     */
    public function getItemByDVTAndKVT($dvt, $kvt, $start = null, $length = null, &$recordsFiltered=null , &$recordsTotal=null){
        $this->db->distinct();
        $this->db->select("kvt.kvt_no, kvt.dvt_no, kvt.item_code, kvt.item_name, kvt.color, kvt.size, kvt.quantity, kvt.times, item.net_wt as netwt, kvt.unit");
        $this->db->from("t_kvt kvt");
        $this->db->join("m_items item","kvt.item_code = item.item_code","left");
        $this->db->where("kvt.dvt_no", $dvt);
        $this->db->where("kvt.kvt_no", $kvt);
        $this->db->group_start();
        $this->db->where("kvt.status !=", "013");
        $this->db->or_where("kvt.status",null);
        $this->db->group_end();
        $this->db->where('kvt.del_flg', '0');
        if($recordsTotal != null){
            $recordsTotal = $this->db->count_all_results(null, false);
        }
        if($recordsFiltered){
            $recordsFiltered = $this->db->count_all_results(null, false);
        }
        if($start != null){
            $this->db->offset($start);
        }
        if($length != null){
            $this->db->limit($length);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get All Item(itemcode, quantity) by kvt and dvt
     * 
     * @author Duc Tam
     * @return Item/Array(0)
     */
    public function getPartOfItemByDVTAndKVT($dvt, $kvt){
        $this->db->select("item_code, quantity");
        $this->db->from("t_kvt");
        $this->db->where("dvt_no", $dvt);
        $this->db->where("kvt_no", $kvt);
        $this->db->where('del_flg', '0');
        $query = $this->db->get();
        return $query->result_array();
    }
    
     /**
     * Update status of KVT
     * @param status
     * @param kvt
     * 
     * @author Duc Tam
     * @return DVT
     */
    function updateKVTStatus($status = "013", $kvt){
        $now = date('Y-m-d H:i:s');
        $user = $this->session->userdata("user")['employee_id'];
        $this->db->set("status", $status);
        $this->db->where("dvt_no", $kvt["dvt_no"]);
        $this->db->where("kvt_no", $kvt["kvt_no"]);
        $this->db->where("order_date", $kvt["order_date"]);
        $this->db->where("times", $kvt["times"]);
        $this->db->where("item_code", $kvt["item_code"]);
        $this->db->where("color", $kvt["color"]);
        $this->db->where("size", $kvt["size"]);
        $result = $this->db->update("t_kvt",array("edit_user"=>$user, "edit_date"=>$now));
        return $result;
    }

    /**
     * Update status of KVT
     * @param status
     * @param kvt
     * 
     * @author Duc Tam
     * @return DVT
     */
    function isDVTPacked($status = "013", $dvt){
        $this->db->select("1")
                 ->from("t_kvt")
                 ->group_start()
                 ->where("status !=", $status)
                 ->or_where("status",null)
                 ->group_end()
                 ->where("dvt_no",  $dvt['dvt_no'])
                 ->where("times",  $dvt['times'])
                 ->where("order_date",  $dvt['order_date'])
                 ->where("del_flg",  "0");
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) == 0){
            return true;
        }
        return false;
    }

}
?>