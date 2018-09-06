<?php
require_once 'vendor/autoload.php';
defined('BASEPATH') OR exit('No direct script access allowed');
define("ORDER_OUT_START_ROW",2);
define("ORDER_RECEIVE_START_ROW",2);

class Analysis extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('orders_model');
        $this->load->model('order_received_model');
        $this->load->model('komoku_model');
    }
    // function set response to view
    public function _response($success, $code = null, $data = null) {
        $res = array(
			"success" => $success, 
			"code" => $code, 
			"message" => $this->lang->line($code), 
			"data" => $data
		);
		return $res;
    }
    public function index()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $currentUser = $this->session->userdata('user');
        $this->data['rateList'] = $this->komoku_model->get_all_rate();
        $this->data['title'] = $this->lang->line('analysis');
        $this->data['screen_id'] = 'ANAS0010';

        // Load the subview and Pass to the master view
        $content = $this->load->view('analysis/index.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    /** get Data for draw chart */
    public function getChartData()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->get();
        $params["usd_vnd"] = $params["usd_vnd"] != '' ? str_replace(',','',$params["usd_vnd"]) + 0 : 1;
        $params["jpy_vnd"] = $params["jpy_vnd"] != '' ? str_replace(',','',$params["jpy_vnd"]) + 0 : 1;

        $data_order_out = $this->orders_model->getOrdersOutData($params);
        $data_order_receive = $this->order_received_details_model->getOrdersReceiveData($params);

        $result["data_xaxis"] = array();
        $result["data_series_amount"] = array();
        $result["data_series_quantity"] = array();
        $result["data_xaxis_receive"] = array();
        $result["data_series_amount_receive"] = array();
        $result["data_series_quantity_receive"] = array();

        /** Exchange rate for amount*/
        $data_order_out = $this->exchange_rate($data_order_out, $params);
        $data_order_receive = $this->exchange_rate($data_order_receive, $params);

        /** Sort theo $params['vertical'][0] */
        $data_order_sort = $this->array_sort($data_order_out, $params['vertical'][0], SORT_DESC);
        $data_order_receive_sort = $this->array_sort($data_order_receive, $params['vertical'][0], SORT_DESC);

        foreach ($data_order_sort as $key => $value) {
            $item_xaxis = array();
            if($value["currency"] != null && $value["currency"] != "") {
                foreach ($params["horizontal_order"] as $key => $horizontal) {
                    if($key != 0) {
                        if($value[$horizontal] != "" && $value[$horizontal] != null){
                            array_push($item_xaxis, "\n".trim($value[$horizontal]));
                        }
                    } else {
                        array_push($item_xaxis, trim($value[$horizontal]));
                    }
                }
                foreach ($params["vertical"] as $key => $vertical) {
                    if($vertical == "amount") {      
                        array_push($result["data_series_amount"], $value[$vertical]);
                    } else {
                        array_push($result["data_series_quantity"], $value[$vertical]);
                    }
                }
                array_push($result["data_xaxis"], implode("", $item_xaxis));
            }
        }

        foreach ($data_order_receive_sort as $key => $value) {
            $item_xaxis_receive = array();
            if($value["currency"] != null && $value["currency"] != "") {
                foreach ($params["horizontal_order_receive"] as $key => $horizontal) {
                    if($key != 0) {
                        if($value[$horizontal] != "" && $value[$horizontal] != null){
                            array_push($item_xaxis_receive, "\n".trim($value[$horizontal]));
                        }
                    } else {
                        array_push($item_xaxis_receive, trim($value[$horizontal]));
                    }
                }
                foreach ($params["vertical"] as $key => $vertical) {
                    if($vertical == "amount") {      
                        array_push($result["data_series_amount_receive"], $value[$vertical]);
                    } else {
                        array_push($result["data_series_quantity_receive"], $value[$vertical]);
                    }
                }
                array_push($result["data_xaxis_receive"], implode("", $item_xaxis_receive));
            }
        }

        $result["data_legend"] = $params["vertical"];
        $result["data_order_out"] = $data_order_sort;
        $result["data_order_receive"] = $data_order_receive_sort;

        echo json_encode($this->_response(true, "", $result));
        return;
    }

    /** Export excel */
    public function excel()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = json_decode($this->input->get("form_data"), true);
        if($params["usd_vnd"] == "") {
            $params["usd_vnd"] = 1;
        }
        if($params["jpy_vnd"] == "") {
            $params["jpy_vnd"] = 1;
        }

        $data_order_out = $this->orders_model->getOrdersOutData($params);
        $data_order_receive = $this->order_received_details_model->getOrdersReceiveData($params);

        /** Exchange rate for amount*/
        $data_order_out = $this->exchange_rate($data_order_out, $params);
        $data_order_receive = $this->exchange_rate($data_order_receive, $params);

        /** Sort theo $params['vertical'][0] */
        // $data_order_sort = $this->array_sort($data_order_out, $params['vertical'][0], SORT_DESC);
        // echo "<pre>";print_r($data_order_sort);echo "</pre>"; return;
        // $data_order_receive_sort = $this->array_sort($data_order_receive, $params['vertical'][0], SORT_DESC);

        $this->writeDataToExcel('views/analysis/template.xlsx', $data_order_out, $data_order_receive, $params);
    }

    private function writeDataToExcel($filePath, $order_out_list, $order_receive_list, $params){
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.$filePath);
        
        // Order out
        $spreadsheet->setActiveSheetIndex(0);
        $arr_header_order = array_merge($params["horizontal_order"], $params["vertical"]);
        $row = 1;

        foreach($arr_header_order as $key=>$value) {
            $key += 1;
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
        }

        foreach($order_out_list as $index => $order_out) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(ORDER_OUT_START_ROW + 1 + $index, 1);
            foreach($arr_header_order as $key=>$value){
                $key += 1;
                if($order_out[$value] == null || $order_out[$value] == "") {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key, ORDER_OUT_START_ROW + 1 + $index, "");
                } else {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key, ORDER_OUT_START_ROW + 1 + $index, $order_out[$value]);
                }
            }
        }
        $spreadsheet->getActiveSheet()->removeRow(ORDER_OUT_START_ROW, 1);

        // Order receive
        $spreadsheet->setActiveSheetIndex(1);
        $arr_header_order_receive = array_merge($params["horizontal_order_receive"], $params["vertical"]);
        $row = 1;

        foreach($arr_header_order_receive as $key=>$value) {
            $key += 1;
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
        }

        foreach($order_receive_list as $index => $order_receive){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(ORDER_RECEIVE_START_ROW + 1 + $index, 1);
            foreach($arr_header_order_receive as $key=>$value){
                $key += 1;
                if($order_receive[$value] == null || $order_receive[$value] == "") {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key, ORDER_RECEIVE_START_ROW + 1 + $index, "");
                } else {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key, ORDER_RECEIVE_START_ROW + 1 + $index, $order_receive[$value]);
                }
            }
        }
        $spreadsheet->getActiveSheet()->removeRow(ORDER_RECEIVE_START_ROW, 1);

        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="analysis.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function exchange_rate($data, $params)
    {
        foreach ($data as $key => $value) {  
            if(isset($value["amount"])) {
                if($params["currency"] == "VND") {
                    if($value["currency"] == "USD") {
                        $data[$key]["amount"] = $value["amount"] * $params["usd_vnd"];
                    } else {
                        if($value["currency"] == "JPY") {
                            $data[$key]["amount"] = $value["amount"] * $params["jpy_vnd"];
                        } else {
                            if($value["currency"] == "VND") {
                                $data[$key]["amount"] = $value["amount"];
                            }
                        }
                    }
                } else {
                    if($params["currency"] == "USD") {
                        if($value["currency"] == "VND") {
                            $data[$key]["amount"] = $value["amount"] / $params["usd_vnd"];
                        } else {
                            if($value["currency"] == "JPY") {
                                $data[$key]["amount"] = ($value["amount"] * $params["jpy_vnd"]) / $params["usd_vnd"];
                            } else {
                                if($value["currency"] == "USD") {
                                    $data[$key]["amount"] = $value["amount"];
                                }
                            }
                        }
                    } else {
                        if($value["currency"] == "USD") {
                            $data[$key]["amount"] = ($value["amount"] * $params["usd_vnd"]) / $params["jpy_vnd"];
                        } else {
                            if($value["currency"] == "VND") {
                                $data[$key]["amount"] = $value["amount"] * $params["jpy_vnd"];
                            } else {
                                if($value["currency"] == "JPY") {
                                    $data[$key]["amount"] = $value["amount"];
                                }
                            }
                        }
                    }
                }
            }    
        }
        return $data;
    }

    function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}
