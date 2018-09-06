<?php
class print_po_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function savePrint($print){
        $this->db->select("times, create_user, create_date");
        $this->db->from("t_po_print");
        $this->db->where("po_no",$print["po_no"]);
        $this->db->where("del_flg",'0');
        $this->db->where("order_date",$print["order_date"]);
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            $this->db->trans_begin();
            $this->db->where("po_no",$print["po_no"]);
            $this->db->where("order_date",$print["order_date"]);
            $this->db->where("del_flg",'0');
            $print['times'] = $result[0]["times"] + 1; 
            unset($print['create_user']); 
            unset($print['create_date']); 
            $this->db->update('t_po_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }else{
            $this->db->trans_begin();
            unset($print['edit_user']); 
            unset($print['edit_date']); 
            $query = $this->db->insert('t_po_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            return $query;
        }
        
    }
    public function getPOPrint($print){
        $sql = "select * from ( select 1 as id,po_no, order_date, times, print_date, print_user,  header, consignee, 
                                    insurance, freight, note_detail, payment_term, pv_no, transportation, shipper, note
                                    FROM t_po_print WHERE po_no = '$print[po_no]' AND order_date = '$print[order_date]'
                                UNION
                                select 2 as id,null as  po_no, null as order_date, null as times, null as  print_date, null as  print_user,  party_a as header, consignee, 
                                    null as insurance, null as freight, null as note_detail, null as  payment_term, null as pv_no, null as  transportation, null as shipper, null as note
                                    FROM t_contract_print WHERE contract_no = '$print[contractNo]') aa
                order by aa.id ASC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(count($result) > 0){
            return $result[0];
        }
        return [];
    }
    
    public function getSignatureInfoByPO($params){
        $this->db->select("ORD.accept_user, EMP.last_name, EMP.first_name, KOM.komoku_name_3 as payment_term, ORD.supplier_name, COM.bank_info, COM.reference");
        $this->db->from('t_orders ORD');
        $this->db->join('m_company COM', 'trim("COM"."short_name") = trim("ORD"."supplier_name") AND COM.del_flg = \'0\' AND COM.type = \'2\'', 'left');
        $this->db->join('m_komoku KOM', "KOM.kubun =  COM.payment_term AND KOM.komoku_id = '". KOMOKU_PAYMENT ."' AND KOM.del_flg = '0'", 'left');
        $this->db->join('m_employee EMP', "EMP.employee_id =  ORD.accept_user AND EMP.del_flg = '0'", 'left');
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
}
?>