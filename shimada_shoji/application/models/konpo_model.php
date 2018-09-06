<?php
    class konpo_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        public function IOSummaryType1($kubun)
        {  
            $sql = "(SELECT ord.item_code, ord.unit, ord.item_name, ord.size, ord.color, sum(quantity) as sum_pv, poonway, sum_store, sum_ng, sum_pl, sum_dvt 
                    from (  select subord.order_receive_no, subord.partition_no, subord.item_code, subord.unit, subord.item_name, subord.color, subord.size, subord.quantity
                                from t_orders_receive_details subord
                                left join t_orders_receive ore
                            on subord.order_receive_no = ore.order_receive_no and subord.order_receive_date = ore.order_receive_date and subord.partition_no = ore.partition_no and subord.partition_no = ore.partition_no
                    where ore.kubun='$kubun') ord
                    left join (select a.item_code, a.item_name, a.size, a.color, sum(a.quantity) as sum_dvt 
                                from t_kvt a
                                left join t_dvt b on trim(b.dvt_no) = trim(a.dvt_no)
                                where a.del_flg = '0' and b.kubun = '$kubun'
                                group by a.item_code, a.item_name, a.size, a.color) kvt
                    on ord.item_code = kvt.item_code and ord.color = kvt.color and ord.size = kvt.size
                    left join (select a.item_code, a.item_name, a.size, a.color, sum(a.odr_quantity) as poonway 
                                from t_orders_details a
                                left join t_orders b on a.order_no_1 = b.order_no_1 and a.order_no_2 = b.order_no_2 and a.order_no_3 = b.order_no_3 and a.order_no_4 = b.order_no_4 and a.order_no_5 = b.order_no_5 and COALESCE(a.order_no_6,'') = COALESCE(b.order_no_6,'')
                                where a.del_flg = '0' and b.status='006'
                                group by a.item_code, a.item_name, a.size, a.color) orddetail
                            on ord.item_code = orddetail.item_code and ord.color = orddetail.color and ord.size = orddetail.size
                    left join (select item_code, item_name, size, color, sum(inspect_ok)  as sum_store, sum(inspect_ng) as sum_ng 
                                from t_store_item  where del_flg = '0'
                                group by item_code, item_name, size, color) si
                    on ord.item_code = si.item_code and ord.color = si.color and ord.size = si.size
                    left join (select item_code, item_name, size, color, sum(quantity) as sum_pl 
                                from t_packing_details where del_flg = '0'
                                group by item_code, item_name, size, color) pk
                    on ord.item_code = pk.item_code and ord.color = pk.color and ord.size = pk.size
                    where sum_store IS NOT NULL or sum_ng IS NOT NULL or sum_pl IS NOT NULL or sum_store IS NOT NULL or quantity IS NOT NULL
                    group by ord.item_code, ord.item_name, ord.size, ord.color, poonway, sum_store, sum_ng, sum_pl, sum_dvt, ord.unit
                    )
                    UNION
                    (SELECT sto.item_code, sto.unit, sto.item_name, sto.size, sto.color, 0 as sum_pv, 0 as poonway, sto.inspect_ok, sto.inspect_ng, 0 as sum_pl, 0 as sum_dvt
                        FROM t_store_item sto
                        WHERE (sto.item_code, sto.size, sto.color)
                        NOT IN  (select item_code,size,color from t_orders_receive_details subord
                                 left join t_orders_receive ore on subord.order_receive_no = ore.order_receive_no) AND sto.del_flg = '0'
                    )
                    UNION
                    (SELECT a.item_code, a.unit, a.item_name, a.size, a.color, 0 as sum_pv, 0 as poonway, 0 as sum_store, 0 as sum_ng, a.quantity, 0 as sum_dvt
                        FROM t_packing_details a
                        INNER JOIN t_dvt dvt on trim(dvt.dvt_no) = trim(a.dvt_no) and dvt.times = a.times and  dvt.kubun = '$kubun'
                        WHERE (a.item_code, a.size, a.color)
                        NOT IN  (select item_code,size,color from t_orders_receive_details subord
                                left join t_orders_receive ore on subord.order_receive_no = ore.order_receive_no) AND dvt.del_flg = '0'
                    )
                    ORDER BY item_code, size, color ASC";

            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }

        public function IOSummaryType2($kubun, $filter) 
        {
            if($filter == 'PV') {
                $sql = "SELECT a.item_code, a.item_name, a.size, a.color, sum(a.quantity) as quantity , a.order_receive_no as names, a.unit, a.delivery_date as dates
                        FROM t_orders_receive_details a
                        INNER JOIN t_orders_receive b on b.order_receive_no = a.order_receive_no and b.order_receive_date = a.order_receive_date and b.partition_no = a.partition_no
                        WHERE b.kubun = '$kubun' AND b.del_flg = '0'
                        GROUP BY a.item_name, a.item_code, a.size,a.color, a.order_receive_no, a.order_receive_no, a.unit, a.delivery_date
                        ORDER BY a.item_code, a.size,a.color, a.order_receive_no";
            }
            if($filter == 'POONWAY') {
                $sql = "SELECT a.item_code, a.item_name, a.size, a.color, sum(a.odr_quantity) as quantity, null as names, null as dates
                        FROM t_orders_details a
                        LEFT JOIN t_orders b on a.order_no_1 = b.order_no_1 and a.order_no_2 = b.order_no_2 and a.order_no_3 = b.order_no_3 and a.order_no_4 = b.order_no_4 and a.order_no_5 = b.order_no_5 and COALESCE(a.order_no_6,'') = COALESCE(b.order_no_6,'')
                        WHERE a.del_flg = '0' and b.status='006'
                        GROUP BY a.item_name, a.item_code, a.size,a.color
                        ORDER BY a.item_code, a.size,a.color";
            }
            if($filter == 'DVT') {
                $sql = "SELECT a.item_code, a.item_name, a.size, a.color, sum(a.quantity) AS quantity, b.dvt_no as names, a.unit, b.packing_date as dates
                        FROM t_kvt a
                        INNER JOIN t_dvt b
                            on trim(b.dvt_no) = trim(a.dvt_no) and b.times = a.times and b.order_date = a.order_date
                        WHERE b.kubun = '$kubun' AND b.del_flg = '0'
                        GROUP BY a.item_code, a.item_name, a.size, a.color , b.dvt_no, a.unit, b.packing_date
                        ORDER BY a.item_code, a.size,a.color, b.dvt_no";
            }
            if($filter == 'OK-NG') {
                $sql = "SELECT a.item_code, a.item_name, a.size, a.color, sum(a.inspect_ok) AS quantity , c.komoku_name_2 AS names, sum(a.inspect_ng) AS quantity_ng, c.komoku_name_2 AS names_ng, a.unit
                        FROM t_store_item a
                        LEFT JOIN m_komoku c ON c.kubun = a.warehouse AND c.komoku_id = 'KM0004'
                        -- LEFT JOIN t_orders_receive b ON b.order_receive_no = a.order_receive_no
                        WHERE a.del_flg = '0'
                        GROUP BY a.item_code, a.item_name, a.size, a.color , a.unit , c.komoku_name_2
                        ORDER BY a.item_code, a.size,a.color, c.komoku_name_2";
            }
            if($filter == 'P/L') {
                $sql = "SELECT a.item_code, a.item_name, a.size, a.color, sum(a.quantity) AS quantity , b.dvt_no AS names, a.unit
                        FROM t_packing_details a
                        LEFT JOIN t_dvt b ON trim(a.dvt_no) = trim(b.dvt_no)
                        WHERE b.kubun = '$kubun' AND b.del_flg = '0'
                        GROUP BY a.item_code, a.item_name, a.size, a.color , a.unit , b.dvt_no
                        ORDER BY a.item_code, a.size, a.color, b.dvt_no";
            }
            $query = $this->db->query($sql);
            $result = $query->result_array();
            return $result;
        }
    }
?>