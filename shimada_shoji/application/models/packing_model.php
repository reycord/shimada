<?php
class Packing_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Search Pack by condition
     * @param param{invoice_no,dvt_no,packing_from,packing_to,status,customer}
     * @param start - start of page
     * @param length - length of page
     * @param recordsFiltered -> total row filter
     * @param recordsTotal -> total row withour filter
     * 
     * @version 1.0.0
     * @author Duc Tam
     * @return Pack/Array(0)
     */
     public function searchPack($param, $start, $length, &$recordsFiltered , &$recordsTotal){
        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id','KM0001');
        $this->db->where('kubun <>','000');
        $this->db->where('del_flg','0');
        $komoku = $this->db->get_compiled_select();
        $this->db->reset_query();

        // $this->db->distinct('dvt_no');
        // $this->db->select("pack_no,concat(trim(dvt_no),'-',times) as  dvt_no");
        $this->db->select("det.pack_no, trim(det.dvt_no) as dvt_no, dvt.kubun");
        $this->db->from('t_packing_details det');
        $this->db->join("t_dvt dvt",'trim(dvt.dvt_no) = trim(det.dvt_no) and dvt.times = det.times and dvt.del_flg = \'0\'','left');
        $this->db->where('det.del_flg','0');
        $details = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select("
                            pack.pack_no,
                            pack.packing_date,
                            pack.invoice_no,
                            pack.packages,
                            pack.quantity,
                            pack.netwt,
                            pack.grosswt,
                            pack.measure,
                            pack.customer,
                            pack.delry_from,
                            pack.delry_from_add,
                            pack.delry_to,
                            pack.delry_to_add,
                            pack.excel_print_date,
                            pack.pdf_print_date,
                            trim(pack.accept_user) as accept_user,
                            trim(pack.measurement_user) as measurement_user,
                            trim(pack.apply_user) as apply_user,
                            kmk.komoku_name_2 as status_name,
                            kmk.kubun as status,
                            pack.case_mark,
                            pack.edit_date,
                            pack.edit_user,
                            pack.note,
                            string_agg(distinct detail.dvt_no, ',') as dvt_no,
                            detail.kubun");
        $this->db->from('t_packing pack');
        $this->db->join("($komoku) kmk",'pack.status = kmk.kubun','left');
        $this->db->join("($details) detail",'pack.pack_no = detail.pack_no','left');
        // $this->db->where_in('pack.status',array('001','002','003','004','005'));
        $this->db->where('pack.del_flg','0');
        $this->db->group_by('
                            pack.pack_no,
                            pack.packing_date,
                            pack.invoice_no,
                            pack.packages,
                            pack.quantity,
                            pack.netwt,
                            pack.grosswt,
                            pack.measure,
                            pack.customer,
                            pack.delry_from,
                            pack.delry_from_add,
                            pack.delry_to,
                            pack.delry_to_add,
                            pack.excel_print_date,
                            pack.pdf_print_date,
                            pack.accept_user,
                            pack.apply_user,
                            kmk.komoku_name_2,
                            kmk.kubun,
                            pack.case_mark,
                            pack.edit_date,
                            pack.edit_user,
                            detail.kubun');
        $recordsTotal = $this->db->count_all_results(null, false);
        // search condition
        if(isset($param['pl_no'])){
            $this->db->like('CAST("pack"."pack_no" AS TEXT)', trim($param['pl_no']), 'both');
        }
        if(isset($param['dvt_no'])){
            $this->db->like('detail.dvt_no', trim($param['dvt_no']), 'both');
        }
        if(isset($param['status'])){
            $this->db->where('pack.status', trim($param['status']));
        }
        if(isset($param['customer'])){
            $this->db->where('pack.delry_to', trim($param['customer']));
        }
        if(isset($param['packing_from'])){
            $this->db->where('pack.packing_date >=', $param['packing_from']);
        }
        if(isset($param['packing_to'])){
            $this->db->where('pack.packing_date <=', $param['packing_to']);
        }
        $this->db->order_by("pack.pack_no", "ASC");
        
        $recordsFiltered = $this->db->count_all_results(null, false);

        $this->db->offset($start);
        $this->db->limit($length);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
     }

     /**
      * Check packing exist by Id
      *
      * @param id
      * @author Duc Tam
      * @return boolean
      */
     public function checkPackingById($pack_no){
        $this->db->select("1");
        $this->db->from("t_packing");
        $this->db->where("pack_no", $pack_no);
        $this->db->where("del_flg ", "0");
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
     }
      /**
      * Check packing exist by Id
      *
      * @param id
      * @author Duc Tam
      * @return boolean
      */
      public function checkCDTTNotYet($pack_no){
        $this->db->select("1");
        $this->db->from("t_packing");
        $this->db->where("pack_no", $pack_no);
        $this->db->where("measurement_user IS NULL", null, false);
        $this->db->where("del_flg ", "0");
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
     }

      /**
      * Update Print excel date
      *
      * @param id
      * @author Duc Tam
      * @return boolean
      */
      public function updatePrintExcelDate($pack_no, $user){
        $this->db->set("excel_print_date", date("Y-m-d"));
        $this->db->set("edit_user", $user);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->where("pack_no", $pack_no);
        return $this->db->update("t_packing");
     }
     

      /**
      * get edit date by pack no
      *
      * @param pack_no
      * @author Duc Tam
      * @return Editdate
      */
      public function getEditDateByPackNo($pack_no){
        $this->db->select("edit_date");
        $this->db->from("t_packing");
        $this->db->where("pack_no", $pack_no);
        $this->db->where("del_flg ", "0");
        $query = $this->db->get();
        return $query->result_array();
     }

      /**
      * Get last packing ID
      *
      * @author Duc Tam
      * @return maxPacking ID
      */
      public function getLastPackingId(){
            $this->db->select_max("pack_no");
            $this->db->from("t_packing");
            $query = $this->db->get();
            $result = $query->result_array();
            if(count($result) == 0){
                return array(array("pack_no", 0));
            }
            return $result;
        }

      /**
      * check packing is exist no matter it was been deleled or no
      * @param pack_no
      * @author Duc Tam
      * @return maxPacking ID
      */
      public function isPackingExist($pack_no){
            $this->db->select("1");
            $this->db->from("t_packing");
            $this->db->where("pack_no", $pack_no);
            $query = $this->db->get();
            $result = $query->result_array();
            if(count($result) == 0){
                return false;
            }
            return true;
        }
     

     /**
      * Update packing
      *
      * @param packing
      * @author Duc Tam
      * @return boolean
      */
      public function updatePacking($packing){
        $this->db->trans_begin();
        $this->db->where('pack_no', $packing['pack_no']);
        $this->db->update('t_packing', $packing);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
     }

     /**
      * Save packing
      *
      * @param packing
      * @author Duc Tam
      * @return Boolen
      */
      public function savePacking($packing){
        $this->db->trans_begin();
        $this->db->insert('t_packing', $packing);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
     }

     /**
      * Update accept packing
      *
      * @param packingNo
      * @param updateUser
      * @param status
      * @author Duc Tam
      * @return boolean
      */
      public function updateStatusPacking($packingNo, $updateUser, $status = null){
        $this->db->set("accept_date", date("Y-m-d H:i:s"));
        $this->db->set("accept_user", $updateUser);
        if($status != null){
            $this->db->set("status", $status);
        }
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->where("pack_no", $packingNo);
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing");
        return $result;
     }

     /**
      * Update accept packing
      *
      * @param packingNo
      * @param updateUser
      * @param status
      * @author Duc Tam
      * @return boolean
      */
      public function applyPacking($packingNo, $updateUser, $status){
        $this->db->set("apply_date", date("Y-m-d H:i:s"));
        $this->db->set("apply_user", $updateUser);
        $this->db->set("status", $status);
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->where("pack_no", $packingNo);
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing");
        return $result;
     }

     
      /**
      * Cdtt packing
      *
      * @param id
      * @param updateUser
      * @author Duc Tam
      * @return boolean
      */
      public function updateCDTTPacking($packingNo, $updateUser, $status){
        $this->db->set("measurement_user", $updateUser);
        $this->db->set("measurement_date", date("Y-m-d H:i:s"));
        $this->db->set("status", $status);
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->where("pack_no", $packingNo);
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing");
        return $result;
     }


     /**
      * Denied accept packing
      *
      * @param id
      * @param updateUser
      * @author Duc Tam
      * @return boolean
      */
     public function deniedPackingById($params, $updateUser){
        $this->db->set("accept_date",null);
        $this->db->set("accept_user",null);
        $this->db->set("apply_date",null);
        $this->db->set("apply_user",null);
        $this->db->set("measurement_user",null);
        $this->db->set("measurement_date",null);
        $this->db->set("status","019");
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->set("note", $params["reason"]);
        $this->db->where("pack_no", $params["id"]);
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing");
        return $result;
     }


     /**
      * Update delete packing
      *
      * @param id
      * @param updateUser
      * @author Duc Tam
      * @return boolean
      */
      public function deletePackingById($packingNo, $updateUser){
        $this->db->set("edit_user", $updateUser);
        $this->db->set("edit_date", date("Y-m-d H:i:s"));
        $this->db->set("del_flg",'1');
        $this->db->where("pack_no", $packingNo);
        $this->db->where("del_flg", "0");
        $result = $this->db->update("t_packing");
        return $result;
     }

     /**
     * Get Packing by ID
     * @param id
     * 
     * @author Duc Tam
     * @return Pack/Array(0)
     */
    public function getPackingById($id){

        $col_lang = getColStatusByLang($this);
        $this->db->select("kubun,$col_lang as komoku_name_2");
        $this->db->from('m_komoku');
        $this->db->where('komoku_id','KM0001');
        $this->db->where('kubun <>','000');
        $this->db->where('del_flg','0');
        $komoku = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select(" pack.pack_no,
                            pack.packing_date,
                            pack.invoice_no,
                            pack.packages,
                            pack.types,
                            pack.quantity,
                            pack.netwt,
                            pack.grosswt,
                            pack.measure,
                            pack.case_mark,
                            pack.note,
                            pack.customer,
                            pack.delry_from,
                            pack.delry_from_add,
                            pack.delry_to,
                            pack.delry_to_add,
                            pack.excel_print_date,
                            pack.pdf_print_date,
                            pack.accept_date,
                            trim(pack.accept_user) as accept_user,
                            kmk.komoku_name_2 as status_name,
                            pack.status as status,
                            pack.case_mark,
                            pack.edit_date,
                            pack.edit_user,
                            pack.create_user,
                            pack.create_date");
        $this->db->from('t_packing pack');
        $this->db->join("($komoku) kmk",'pack.status = kmk.kubun','left');
        // $this->db->where_in('pack.status',array('001','002','003','004','005'));
        $this->db->where('pack.pack_no', $id);
        $this->db->where('pack.del_flg','0');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}
?>