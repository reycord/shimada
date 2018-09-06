<?php
class Packing_Details_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get list Packing details by ID
     * @param id
     * 
     * @author Duc Tam
     * @return Packing_Detail/Array(0)
     */
    public function getPackingDetailsByPackingId($pack_no){
        $results = [];
        $this->db->distinct();
        $this->db->select("kvt_no,dvt_no");
        $this->db->from("t_packing_details detail");
        $this->db->where("detail.pack_no", $pack_no);
        $this->db->where("detail.del_flg", '0');
        $this->db->order_by("dvt_no,kvt_no");
        $query = $this->db->get();
        $distinctData = $query->result_array();
        foreach($distinctData as $distinct){
            $this->db->reset_query();
            $this->db->distinct();
            $this->db->select(" detail.pack_no,
                                detail.packing_details,
                                detail.order_date,
                                detail.dvt_no,
                                detail.times,
                                detail.kvt_no,
                                detail.package_type,
                                detail.package_type_1,
                                (CASE WHEN detail.number_from = '0' THEN null ELSE detail.number_from END) as number_from,
                                (CASE WHEN detail.number_to = '0' THEN null ELSE detail.number_to END) as number_to,
                                detail.details_no,
                                detail.jp_code,
                                detail.item_code,
                                detail.item_name,
                                detail.composition_1, detail.composition_2, detail.composition_3,
                                detail.color,
                                detail.size,
                                detail.unit,
                                detail.size_unit,
                                detail.quantity,
                                detail.quantity_detail,
                                detail.multiple,
                                detail.netwt,
                                detail.grosswt,
                                detail.measure,
                                detail.lot_no,
                                detail.inv_no,
                                detail.note,
                                detail.edit_date,
                                item.net_wt as origin_netwt");
            $this->db->from("t_packing_details detail");
            $this->db->where("detail.dvt_no",$distinct["dvt_no"]);
            $this->db->where("detail.kvt_no",$distinct["kvt_no"]);
            $this->db->where("detail.pack_no", $pack_no);
            $this->db->where("detail.del_flg", '0');
            $this->db->join("m_items item", 'detail.item_code = item.item_code and detail.color = item.color and detail.size = item.size ', 'left');
            $this->db->order_by("detail.item_code");
            $query = $this->db->get();
            $data = $query->result_array();
            if(count($data) > 0){
                foreach($data as &$dt) {
                    $typeList = [];
                    $dt['inv_no'] = ($dt['inv_no'] == null ? "" : $dt['inv_no']);
                    $invNoList = explode(",", $dt['inv_no']);
                    foreach ($invNoList as $invNo){
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
                    $dt['item_types'] = array_unique($typeList);
                }
            }
            array_push($results,$data);
        }
        return $results;
    }

    /**
     * Get list Packing details by ID
     * @param id
     * 
     * @author Duc Tam
     * @return Packing_Detail/Array(0)
     */
    public function getPackingDetailsByPackingNo($pack_no){
        $results = [];
        $this->db->distinct();
        $this->db->select("detail.kvt_no,detail.dvt_no, dvt.kubun");
        $this->db->from("t_packing_details detail");
        $this->db->join("t_dvt dvt",'dvt.dvt_no = detail.dvt_no','left');
        $this->db->where("detail.pack_no", $pack_no);
        $this->db->where("detail.del_flg", '0');
        $this->db->order_by("dvt_no,kvt_no");
        $query = $this->db->get();
        $distinctData = $query->result_array();

        $this->db->select("case_mark, types, packing_date, delry_to as factory, customer,
        (CASE WHEN edit_user is Null then create_user ELSE edit_user END) as user");
        $this->db->from("t_packing");
        $this->db->where("pack_no", $pack_no);
        $this->db->where("del_flg", '0');
        $packing = $this->db->get_compiled_select();
        $this->db->select("pack.*, em.first_name, em.last_name ,com.short_name ");
        $this->db->from("($packing) pack");
        $this->db->join("m_employee em",'trim(em.employee_id) = trim(pack.user)','left');
        $this->db->join("m_company com","trim(com.company_name) = trim(pack.factory) and com.type = '1'",'left');
        $query = $this->db->get();
        $case_mark_array = $query->result_array();

        $this->db->select("komoku_name_2 , kubun");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id", 'KM0007');
        $this->db->where("del_flg", '0');
        $komoku = $this->db->get_compiled_select();

        $this->db->select("komoku_name_2 , kubun");
        $this->db->from("m_komoku");
        $this->db->where("komoku_id", 'KM0020');
        $this->db->where("del_flg", '0');
        $item_type = $this->db->get_compiled_select();
        $this->db->reset_query();
        foreach($distinctData as $distinct){
            $this->db->reset_query();
            $this->db->select("detail.*, packing.types, kmk.komoku_name_2 as package_type_name, item_type.komoku_name_2 as item_type");
            $this->db->from("t_packing_details detail");
            $this->db->join("t_packing packing", "packing.pack_no = detail.pack_no", "left");
            $this->db->join("($komoku) kmk",'kmk.kubun = detail.package_type','left');
            $this->db->join("($item_type) item_type",'item_type.kubun = packing.types','left');
            $this->db->where("detail.dvt_no",$distinct["dvt_no"]);
            $this->db->where("detail.kvt_no",$distinct["kvt_no"]);
            $this->db->where("detail.pack_no", $pack_no);
            $this->db->where("detail.del_flg", '0');
            $this->db->order_by("detail.pack_no,detail.number_from,detail.item_code,detail.size,detail.color");
            $query = $this->db->get();
            array_push($results,array("dvt"=>$distinct["dvt_no"], 
                                      "kvt"=>$distinct["kvt_no"],
                                      "kubun"=>$distinct["kubun"],
                                      "packing_date"=>$case_mark_array[0]["packing_date"],
                                      "factory"=>$case_mark_array[0]["factory"],
                                      "customer"=>$case_mark_array[0]["short_name"],
                                      "types"=>$case_mark_array[0]["types"],
                                      "name_pdg"=>$case_mark_array[0]["first_name"]." ".$case_mark_array[0]["last_name"],
                                      "items"=>$query->result_array(),
                                      "case_mark"=>count($case_mark_array) > 0? $case_mark_array[0]["case_mark"]:"" ));
        }
        return $results;
    }

     /**
     * Get list Packing details by ID and DVT
     * @param pack_no
     * @param DVT
     * 
     * @author Duc Tam
     * @return Packing_Detail/Array(0)
     */
    public function getPackingDetailsByPackingIdAndDVT($pack_no, $DVT, $shosha = false){
        $this->db->distinct();
        $select01 = "
                item_code,
                jp_code,
                size_unit, 
                size, color,  
                split_part(string_agg(item_name_vn, '(~.~)' order by item_name_vn DESC), '(~.~)', 1) as item_name_vn,
                split_part(string_agg(item_name_com, '(~.~)' order by item_name_com DESC), '(~.~)', 1) as item_name_com,
                split_part(string_agg(item_name_dsk, '(~.~)' order by item_name_dsk DESC), '(~.~)', 1) as item_name_dsk,
                split_part(string_agg(item_name_des, '(~.~)' order by item_name_des DESC), '(~.~)', 1) as item_name_des,
                unit, 
                sell_price_vnd, 
                sell_price_usd, 
                sell_price_jpy,
                composition_1,
                composition_2,
                composition_3,
                origin";
        $this->db->select($select01, false);
        $this->db->from("m_items");
        $this->db->where("del_flg","0");
        $this->db->group_by("item_code, jp_code, size_unit, size, color, unit, sell_price_vnd, sell_price_usd, sell_price_jpy, composition_1, composition_2, composition_3, origin");
        $item = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->distinct();
        $this->db->select("kvt.dvt_no, kvt.kvt_no, kvt.times, dvt.currency, item_code, item_jp_code, color, size,".( $shosha?"shosha_price as sell_price" :"sell_price"));
        $this->db->from("t_kvt kvt");
        $this->db->join("t_dvt dvt", "dvt.del_flg = '0' and dvt.dvt_no = kvt.dvt_no and dvt.times = kvt.times and dvt.order_date = kvt.order_date", "inner");
        $this->db->where("trim(kvt.dvt_no)", trim($DVT));
        $this->db->where("kvt.del_flg","0");
        $kvt = $this->db->get_compiled_select();
        $this->db->reset_query();
        $select = "
                det.item_name,
                det.item_code,
                det.quantity,
                det.quantity_detail,
                det.multiple,
                det.number_from,
                det.number_from as number_to,
                string_agg(distinct concat(det.size,'---', det.color,'---', det.quantity), ',') as size_color,
                det.netwt,
                det.grosswt,
                det.measure,
                kvt.sell_price,
                kvt.currency,
                det.size_unit,
                item.item_name_vn,
                item.item_name_com,
                item.item_name_dsk,
                item.item_name_des,
                item.composition_1,
                item.composition_2,
                item.composition_3,
                det.unit,
                det.lot_no,
                item.origin,
                (quantity * sell_price) as amount";
        $this->db->select($select, false);
        $this->db->from("t_packing_details det");
        $this->db->join("($item) item","det.item_code = item.item_code and det.jp_code = item.jp_code and det.size = item.size and det.color = item.color","left");
        $this->db->join("($kvt) kvt","trim(det.dvt_no) = trim(kvt.dvt_no) and det.times = kvt.times and trim(det.kvt_no) = trim(kvt.kvt_no) and det.jp_code = kvt.item_jp_code and det.item_code = kvt.item_code and trim(det.size) = trim(kvt.size) and trim(det.color) = trim(kvt.color)","left");
        $this->db->where("det.pack_no", $pack_no);
        $this->db->where("det.del_flg", '0');
        $this->db->group_by("
                        det.item_name,
                        det.item_code,
                        det.size,
                        det.color,
                        det.quantity,
                        det.quantity_detail,
                        det.multiple,
                        det.number_from,
                        det.number_to,
                        det.netwt,
                        det.grosswt,
                        det.measure,
                        kvt.sell_price,
                        kvt.currency,
                        det.size_unit,
                        item.item_name_vn,
                        item.item_name_com,
                        item.item_name_dsk,
                        item.item_name_des,
                        det.unit,
                        det.lot_no,
                        item.origin,
                        item.composition_1,
                        item.composition_2,
                        item.composition_3
                        ");
        $this->db->order_by("det.number_from ASC, det.item_code ASC, det.size ASC, det.color ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getMaxDetailsNo($pack_no){
        $this->db->select_max('packing_details');
        $this->db->where('pack_no',$pack_no);
        $query = $this->db->get('t_packing_details');
        return $query->result_array();
    }

    public function savePackingDetails($detail = null){
        $this->db->trans_begin();
        $query = $this->db->insert('t_packing_details', $detail);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
    }

    /**
      * Update delete packing
      *
      * @param id
      * @param updateUser
      * @author Duc Tam
      * @return boolean
      */
      public function deletePackingDetailsByPackNo($packingNo, $updateUser){
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->set("del_flg",'1');
        $this->db->where("pack_no", $packingNo);
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing_details");
        return $result;
     }

     /**
      * Delete packing except list details no
      *
      * @param id
      * @param updateUser
      * @author Duc Tam
      * @return boolean
      */
      public function deletePackingDetailsExceptDetails($packingNo, $detailsList, $updateUser){
        $this->db->trans_begin();
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->set("del_flg",'1');
        $this->db->where("pack_no", $packingNo);
        if(count($detailsList) > 0){
            $this->db->where_not_in("packing_details", $detailsList);
        }
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing_details");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
     }

     /**
      * getPackingDetailsByList
      *
      * @param packingNo
      * @param detailsList
      * @author Duc Tam
      * @return boolean
      */

      function getPackingDetailsByList($packingNo, $detailsList){
        $this->db->distinct()
                 ->select("dvt_no, kvt_no, item_code, size, color, times, order_date")
                 ->from("t_packing_details")
                 ->where("pack_no", $packingNo);
        if(count($detailsList) > 0){
            $this->db->where_not_in("packing_details", $detailsList);
        }
        $query = $this->db->get();
        return $query->result_array();
      }


    public function updatePackingDetails($details = null){
        $this->db->trans_begin();
        $this->db->where('pack_no', $details['pack_no']);
        $this->db->where('packing_details', $details['packing_details']);
        $this->db->update('t_packing_details', $details);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
    }



}