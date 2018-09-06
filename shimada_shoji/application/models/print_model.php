<?php
class print_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function savePrint($print){
        $this->db->select("1");
        $this->db->from("t_print");
        $this->db->where("delivery_no",$print["delivery_no"]);
        $this->db->where("times",$print["times"]);
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            $this->db->trans_begin();
            $this->db->where("delivery_no",$print["delivery_no"]);
            $this->db->where("times",$print["times"]);
            $this->db->update('t_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }else{
            $this->db->trans_begin();
            $query = $this->db->insert('t_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            return $query;
        }
        
    }

    public function getAllInvoucherNo(){
        $this->db->distinct()
                ->select("delivery_no, times, delivery_date, inventory_voucher_excel_no")
                ->from("t_print")
                ->where("inventory_voucher_excel_no is NOT NULL", NULL, FALSE);
        $query = $this->db->get();
        return $query->result_array();
        
    }
}
?>