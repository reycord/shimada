<?php
class print_contract_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function savePrint($print){
        $this->db->select("create_user, create_date");
        $this->db->from("t_contract_print");
        $this->db->where("contract_no",$print["contract_no"]);
        $this->db->where("del_flg",'0');
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            $this->db->trans_begin();
            $this->db->where("contract_no",$print["contract_no"]);
            $this->db->where("del_flg",'0');
            $print['create_user'] = $result[0]["create_user"]; 
            $print['create_date'] = $result[0]["create_date"]; 
            $this->db->update('t_contract_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }else{
            $this->db->trans_begin();
            $query = $this->db->insert('t_contract_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            return $query;
        }
    }
    public function saveContractPrint($print){
        $this->db->select("create_user, create_date");
        $this->db->from("t_contract_print");
        $this->db->where("contract_no",$print["contract_no"]);
        $this->db->where("delivery_no",$print["delivery_no"]);
        $this->db->where("times",$print["times"]);
        $this->db->where("delivery_date",$print["delivery_date"]);
        $this->db->where("pack_no",$print["pack_no"]);
        $this->db->where("del_flg",'0');
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            $this->db->trans_begin();
            $this->db->where("contract_no",$print["contract_no"]);
            $this->db->where("delivery_no",$print["delivery_no"]);
            $this->db->where("times",$print["times"]);
            $this->db->where("delivery_date",$print["delivery_date"]);
            $this->db->where("pack_no",$print["pack_no"]);
            $this->db->where("del_flg",'0');
            $print['create_user'] = $result[0]["create_user"]; 
            $print['create_date'] = $result[0]["create_date"]; 
            $this->db->update('t_contract_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }else{
            $this->db->trans_begin();
            $query = $this->db->insert('t_contract_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            return $query;
        }
        
    }

    public function saveAgreementEachtime($print)
    {
        $this->db->select("create_user, create_date");
        $this->db->from("t_contract_print");
        $this->db->where("contract_no",$print["contract_no"]);
        $this->db->where("delivery_no",$print["delivery_no"]);
        $this->db->where("times",$print["times"]);
        $this->db->where("delivery_date",$print["delivery_date"]);
        $this->db->where("pack_no",$print["pack_no"]);
        $this->db->where("del_flg",'0');
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            $this->db->trans_begin();
            $this->db->where("contract_no",$print['contract_no']);
            $this->db->where("delivery_no",$print["delivery_no"]);
            $this->db->where("times",$print["times"]);
            $this->db->where("delivery_date",$print["delivery_date"]);
            $this->db->where("kubun",$print["kubun"]);
            $this->db->where("pack_no",$print["pack_no"]);
            $this->db->where("del_flg",'0');
            $print['create_user'] = $result[0]["create_user"]; 
            $print['create_date'] = $result[0]["create_date"]; 
            $this->db->update('t_contract_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        } else {
            $query = $this->db->insert('t_contract_print', $print);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            return $query;
        }
    }

    public function getPOPrint($params){
        $sql = "select * from ( SELECT 1 as id, contract_no, contract_date, end_date, bank, party_a, payment_term, reference as reference_print
                                    FROM t_contract_print
                                    WHERE contract_no = '$params[contractNo]' AND del_flg = '0'
                                UNION
                                SELECT 2 as id, null as contract_no, null as contract_date, null as end_date, null as bank, header as party_a, null as payment_term, null as reference_print
                                    FROM t_po_print
                                    WHERE po_no = '$params[po_no]' AND order_date = '$params[order_date]') aa
                order by aa.id ASC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(count($result) > 0){
            return $result[0];
        }
        return [];
    }

    public function getSalesContractPrint($params){
        $sql = "select * from (
                    SELECT 1 as id, contract_no, agreement_contract_no, kubun, delivery_no, times, 
                        pack_no, contract_date, end_date, delivery_date, delivery_date_eachtime, 
                        term_delivery, agreement_no, bank, party_a, party_b, notify, 
                        consignee, party_charged, delivery_condition, agreement_date, 
                        signature, vat, payment_currency, payment_methods, payment_term, quantity_odds,
                        terms_overdue, scan_signature, feedback_day_num, fee_terms, rate, rate_jpy, rate_jpy_usd, edit_date
                    FROM t_contract_print
                    WHERE delivery_no = '$params[delivery_no]'
                    AND times = '$params[times]'
                    AND del_flg = '0'
                    AND delivery_date = '$params[delivery_date]'
                    AND kubun = '$params[kubun]'
                UNION
                    SELECT 2 as id, null as contract_no, null as agreement_contract_no, kubun, delivery_no, times, 
                        pack_no, contract_date, null as end_date, delivery_date, null as delivery_date_eachtime, 
                        term_delivery, agreement_no, bank, party_a, party_b, notify, 
                        consignee, party_charged, delivery_condition, agreement_date, 
                        signature, vat, payment_currency, payment_methods, payment_term, quantity_odds,
                        terms_overdue, scan_signature, feedback_day_num, fee_terms, rate, rate_jpy, rate_jpy_usd, edit_date
                    FROM t_contract_print
                    WHERE delivery_no = '$params[delivery_no]'
                    AND times = '$params[times]'
                    AND del_flg = '0'
                    AND delivery_date = '$params[delivery_date]'
                    AND kubun <> '$params[kubun]' 
                    AND kubun like '2%' ) aa
            ORDER BY aa.id ASC, aa.edit_date DESC";
        $query = $this->db->query($sql);

        $result = $query->result_array();
        if(count($result) > 0){
            return $result;
        }
        return [];
    }
    public function getAgreementContractPrint($params){
        $kubun = $params["kubun"];
        $partyb = trim($params["partyb"]);
        $sql = "SELECT contract_no, contract_date, end_date, party_a, party_b
                FROM t_contract_print
                WHERE kubun = '$kubun'
                    AND trim(party_b) = '$partyb'
                    AND del_flg='0'
                UNION
                SELECT reference as contract_no, contract_from_date as contract_date, contract_end_date as end_date, null as party_a, company_name as party_b
                FROM m_company
                WHERE company_name='$partyb'
                    AND del_flg='0'
                ORDER BY contract_date DESC";

        $query = $this->db->query($sql);
        return $query->result_array()[0];
    }
    public function get_eachtime_no($params)
    {
        $this->db->select("contract_no");
        $this->db->from("t_contract_print");
        $this->db->like('contract_no', $params['eachtime_no'], 'after');
        if($params['party_a']){
            $this->db->like('contract_no', $params['party_a'], 'both');
        }else{
            $this->db->not_like('contract_no', '(HN)', 'both');
        }
        $this->db->where("del_flg",'0');
        $this->db->order_by('edit_date','desc');
        if(isset($params['kubun'])) {
            $this->db->where("kubun", $params["kubun"]);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result) > 0){
            return $result;
        } else {
            return FALSE;
        }
    }
    public function getLastContractNo($kubun){
        $this->db->select("contract_no, create_date, edit_date");
        $this->db->from("t_contract_print");
        $this->db->where("kubun",$kubun);
        $this->db->where("del_flg",'0');
        $this->db->order_by("create_date DESC , edit_date");
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function check_update($params){
        $this->db->select("1");
        $this->db->from("t_contract_print");
        $this->db->where("contract_no", $params['contract_no']);
        $this->db->where("delivery_no", $params['delivery_no']);
        $this->db->where("delivery_date", $params['delivery_date']);
        $this->db->where("times", $params['times']);
        $this->db->where("edit_date", $params['edit_date']);
        $this->db->where("del_flg",'0');
        $query = $this->db->get();
        if(count($query->result_array()) > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function updateContractPrint($params){
        $this->db->trans_begin();
        $this->db->where("contract_no",$params["contract_no"]);
        $this->db->where("delivery_no",$params["delivery_no"]);
        $this->db->where("times",$params["times"]);
        $this->db->where("delivery_date",$params["delivery_date"]);
        $this->db->where("del_flg",'0');
        $this->db->update('t_contract_print', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        $this->db->trans_commit();
        return TRUE;
    }
    public function deleteContractPrint($params){
        $this->db->trans_begin();
        $this->db->delete('t_contract_print', $params);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        $this->db->trans_commit();
        return TRUE;
    }
}
?>