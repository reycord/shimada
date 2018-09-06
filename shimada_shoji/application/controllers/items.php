<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('employee_model');
        $this->load->model('komoku_model');
        $this->load->model('company_model');
        $this->load->model('company_branch_model');
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
        $this->data['administrator'] = $currentUser['admin_flg'];
        $this->data['title'] = $this->lang->line('configuration_list');
        $this->data['items_list'] = $this->komoku_model->get_item_notnull();
        $this->data['company_list'] = $this->company_model->getAllShortName();
        $this->data['screen_id'] = 'MTS0030';

        // Load the subview
        // Pass to the master view 
        $content = $this->load->view('items/index.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $data = $this->komoku_model->search();
        $dataArr = array();
        foreach($data as $item){
            $tmp = array(
                $item["komoku_id"],
                $item["kubun"],
                $item["komoku_name_1"],
                $item["komoku_name_2"],
                $item["komoku_name_3"],
                $item["use"],
                $item["sort"],
                $item["note1"],
                $item["note2"]
            );
            array_push($dataArr, $tmp);
        }
        /** write to excel */
        require_once 'vendor/autoload.php';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.'views/items/template.xlsx');
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "C4");
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="items.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function search() 
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $column_num = $this->input->get('order[0][column]');
        $column_name = $this->input->get('columns['.$column_num.'][data]');
        $sort = $this->input->get('order[0][dir]');

        // query (search items)
        $data = $this->komoku_model->search($params, $start, $length, $recordsTotal, $recordsFiltered, $column_name, $sort);
        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }

    public function save()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        $currentDate = date('Y/m/d H:i:s');

        $query = false;
        $data = array(
            'komoku_id' => $params['komoku_id'],
            'komoku_name_1' => $params['komoku_name_1'],
            'komoku_name_2' => $params['komoku_name_2'],
            'komoku_name_3' => $params['komoku_name_3'],
            'note1' => $params['note1'],
            'note2' => $params['note2'],
            'use' => $params['use'],
            'sort' => $params['sort'],
        );
        if(!isset($params['kubun'])) {
            $kubun_max = $this->komoku_model->getNextKubun($params['komoku_id']);
            foreach($data as $key => $value){
                if($value == '' || $value == null) {unset($data[$key]);}
            }
            $data['kubun'] = $kubun_max;
            $data['create_date'] = $currentDate;
            $data['create_user'] =  $currentUser['employee_id'];

            // Query Insert items
            $query = $this->komoku_model->insert($data);
        } else {
            $edit_date = null;
            if($params['edit_date'] != '' && $params['edit_date'] != "null" && $params['edit_date'] != null) {
                $edit_date = $params['edit_date'];
            }
            $data_check = array(
                'komoku_id' => $params['komoku_id'],
                'kubun' => $params['kubun'], 
                'edit_date' => $edit_date,
            );
            $result = $this->komoku_model->check_edit_date($data_check);
            if(!$result) {
                echo json_encode($this->_response(false,'COMMON_E001'));
                return;
            } else {
                foreach($data as $key => $value){
                    if($value == '') {$data[$key] = null;}
                }
                $data['kubun'] = $params['kubun'];  
                $data['edit_date'] = $currentDate;
                $data['edit_user'] =  $currentUser['employee_id'];

                // Query update items
                $query = $this->komoku_model->update($data);
            }
        }
        if($query) {
            echo json_encode($this->_response(true,'save_success', $query));
        } else {
            echo json_encode($this->_response(false,'save_fail'));
        }
    }

    // Delete items
    public function delete() 
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $currentUser = $this->session->userdata('user');
        
        // check items is exists ? 
        $check_item = $this->komoku_model->check_item_exists($params);
        if(!$check_item) {
            echo json_encode($this->_response(false, 'JOS0010_E002'));
            return;
        } else {
            $edit_date = null;
            if($params['edit_date'] != '' && $params['edit_date'] != "null" && $params['edit_date'] != null) {
                $edit_date = $params['edit_date'];
            }
            $data_check = array(
                'komoku_id' => $params['komoku_id'],
                'kubun' => $params['kubun'],
                'edit_date' => $edit_date,
            );
            $result = $this->komoku_model->check_edit_date($data_check);
            if(!$result) {
                echo json_encode($this->_response(false,'COMMON_E001'));
                return;
            } else {
                $params['edit_date'] = date('Y/m/d H:i:s');
                $params['edit_user'] =  $currentUser['employee_id'];
                // Delete items
                $result = $this->komoku_model->delete($params);
                if($result) {
                    echo json_encode($this->_response(true,'del_success'));
                } else {
                    echo json_encode($this->_response(false,'del_fail'));
                }
            }
        }
    }

    public function getBranchList()
    {
        if(!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $params = $this->input->post();
        $result = $this->company_branch_model->getBranchByCompanyID($params);
        echo json_encode($result);
    }
}
