<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('employee_model');
        $this->load->model('company_model');
        $this->load->model('komoku_model');
        $this->load->model('items_model');
        $this->load->model('surcharge_model');        
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
        $this->data['title'] = $this->lang->line('item_search');
        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
        $this->data['customer_list'] = $this->company_model->getAllCustomer();
        $this->data['vendor_list'] = $this->company_model->getAllVendor();
        $this->data['size_list'] = $this->komoku_model->get_all_size();
        $this->data['color_list'] = $this->komoku_model->get_all_color();
        $currentUser = $this->session->userdata('user');
        $this->data['user_login'] = $currentUser['employee_id'];
        $this->data['screen_id'] = 'MTS0010';
        // Load the subview and pass to master view
        $content = $this->load->view('products/index.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    // Save product - create by: thanh
    public function save() 
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");
        $flag = 0;
        $item_code = $this->input->post('item_code');
        $customer_code = $this->input->post('customer_code');
        $old_edit_date = $this->input->post('edit_date');
        $jp_code = $this->input->post('jp_code');
        $item_name = $this->input->post('item_name');
        $item_name_vn = $this->input->post('item_name_vn');
        $salesman = $this->input->post('salesman');
        $size = $this->input->post('size');
        $color = $this->input->post('color');
        $customer = $this->input->post('customer');
        $type = $this->input->post('type');
        $surchargeDt = json_decode($this->input->post('data_surcharge'), true);
        $id_del_list = json_decode($this->input->post('id_del_list'), true);

        if($type == '1') {
            $flag = 1;
        }

        $params = array(
            'item_code'       => trim($item_code),
            'jp_code'         => $this->input->post('jp_code'),
            'item_name'       => $this->input->post('item_name'),
            'item_name_vn'    => $this->input->post('item_name_vn'),
            'item_name_com'   => $this->input->post('item_name_com'),
            'item_name_dsk'   => $this->input->post('item_name_dsk'),
            'item_name_des'   => $this->input->post('item_name_des'),
            'salesman'        => $this->input->post('salesman'),
            'customer'        => $this->input->post('customer'),
            'customer_code'   => $this->input->post('customer_code'),
            'apparel'         => $this->input->post('apparel'),
            'vendor'          => $this->input->post('vendor'),
            'vendor_parts'    => $this->input->post('vendor_parts'),
            'composition_1'   => $this->input->post('composition_1'),
            'composition_2'   => $this->input->post('composition_2'),
            'composition_3'   => $this->input->post('composition_3'),
            'unit'            => $this->input->post('unit'),
            'size_unit'       => $this->input->post('size_unit'),
            'size'            => $this->input->post('size'),
            'moq'             => $this->input->post('moq'),
            'net_wt'          => $this->input->post('net_wt'),
            'buy_price_usd'   => $this->input->post('buy_price_usd'),
            'buy_price_vnd'   => $this->input->post('buy_price_vnd'),
            'buy_price_jpy'   => $this->input->post('buy_price_jpy'),
            'sell_price_usd'  => $this->input->post('sell_price_usd'),
            'sell_price_vnd'  => $this->input->post('sell_price_vnd'),
            'sell_price_jpy'  => $this->input->post('sell_price_jpy'),
            'base_price_usd'  => $this->input->post('base_price_usd'),
            'base_price_vnd'  => $this->input->post('base_price_vnd'),
            'base_price_jpy'  => $this->input->post('base_price_jpy'),
            'lot_quantity'    => $this->input->post('lot_quantity'),
            'color'           => $this->input->post('color'),
            'vendor_color'    => $this->input->post('vendor_color'),
            'end_of_sales'    => $this->input->post('end_of_sales'),
            'inspection_rate' => $this->input->post('inspection_rate'),
            'origin'          => $this->input->post('origin'),
            'note_po_sheet'   => $this->input->post('color_note'),
            'note'            => $this->input->post('note'),
            'note_lapdip'     => $this->input->post('lapdip_note'),
        );
        $this->db->trans_begin();

        if($flag == 0) {
            foreach($params as $key => $value){
                if($key == 'item_code' || $key == 'item_name' || $key == 'item_name_vn' 
                        || $key == 'jp_code' || $key == 'customer' || $key == 'customer_code' 
                        || $key == 'salesman' || $key == 'size' || $key == 'color') {
                    if($value == '' || $value == null) {
                        {$params[$key] = "";}
                    }
                } else {
                    if($value == '' || $value == null) {unset($params[$key]);}
                }
            }
            $params['create_user'] = $currentUser['employee_id'];
            $params['create_date'] = $currentDate;
            $result = $this->items_model->insertItems($params);
            $this->surcharge($result, $surchargeDt, $id_del_list);
        } else {
            if($flag == 1){
                $edit_date = null;
                if($old_edit_date != '' || $old_edit_date != null) {
                    $edit_date = $old_edit_date;
                }
                $data_check = array(
                    'item_code' => $this->input->post('item_code_old'),
                    'customer_code' => $this->input->post('customer_code_old'),
                    'customer' => $this->input->post('customer_old'),
                    'jp_code' => $this->input->post('jp_code_old'),
                    'item_name' => $this->input->post('item_name_old'),
                    'item_name_vn' => $this->input->post('item_name_vn_old'),
                    'salesman' => $this->input->post('salesman_old'),
                    'size' => $this->input->post('size_old'),
                    'color' => $this->input->post('color_old'),
                    'edit_date' => $edit_date,
                );
                $result = $this->items_model->check_edit_date($data_check);
                if(!$result) {
                    $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
                    return redirect(base_url('products/edit?item_code='. rawurlencode(trim($this->input->post('item_code_old'))) 
                                                .'&customer_code='. rawurlencode(trim($this->input->post('customer_code_old'))) 
                                                .'&customer='. rawurlencode(trim($this->input->post('customer_old'))) 
                                                .'&jp_code='. rawurlencode(trim($this->input->post('jp_code_old'))) 
                                                .'&item_name='. rawurlencode(trim($this->input->post('item_name_old'))) 
                                                .'&item_name_vn='. rawurlencode(trim($this->input->post('item_name_vn_old'))) 
                                                .'&salesman='. rawurlencode(trim($this->input->post('salesman_old'))) 
                                                .'&size='. rawurlencode(trim($this->input->post('size_old'))) 
                                                .'&color='. rawurlencode(trim($this->input->post('color_old')))
                                            ));
                }
                foreach($params as $key => $value){
                    if($key == 'item_code' || $key == 'item_name' || $key == 'item_name_vn' 
                        || $key == 'jp_code' || $key == 'customer' || $key == 'customer_code' 
                        || $key == 'salesman' || $key == 'size' || $key == 'color') {
                        if($value == '' || $value == null) {
                            {$params[$key] = "";}
                        }
                    } else {
                        if($value == '') {$params[$key] = null;}
                    }
                }
                $params['item_code_old'] = $this->input->post('item_code_old');
                $params['jp_code_old'] = $this->input->post('jp_code_old');
                $params['item_name_old'] = $this->input->post('item_name_old');
                $params['item_name_vn_old'] = $this->input->post('item_name_vn_old');
                $params['customer_code_old'] = $this->input->post('customer_code_old');
                $params['customer_old'] = $this->input->post('customer_old');
                $params['salesman_old'] = $this->input->post('salesman_old');
                $params['size_old'] = $this->input->post('size_old');
                $params['color_old'] = $this->input->post('color_old');
                $params['edit_user'] = $currentUser['employee_id'];
                $params['edit_date'] = $currentDate;

                $result = $this->items_model->updateItems($params);
                $this->surcharge($result, $surchargeDt, $id_del_list);
            }
        }
    }

    // Save product - create by: thanh
    public function save_as() 
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        $flag = 1;
        $currentUser = $this->session->userdata('user');
        $currentDate = date("Y/m/d H:i:s");
        $id_del_list = json_decode($this->input->post('id_del_list'), true);
        $surchargeDt = json_decode($this->input->post('data_surcharge'), true);
        foreach ($surchargeDt as $key => $surcharge) {
            if(!empty($surcharge['id'])) {
                $surchargeDt[$key]['id'] = null;
            }
        }

        $params = array(
            'item_code'       => trim($this->input->post('item_code')),
            'jp_code'         => $this->input->post('jp_code'),
            'item_name'       => $this->input->post('item_name'),
            'item_name_vn'    => $this->input->post('item_name_vn'),
            'item_name_com'   => $this->input->post('item_name_com'),
            'item_name_dsk'   => $this->input->post('item_name_dsk'),
            'item_name_des'   => $this->input->post('item_name_des'),
            'salesman'        => $this->input->post('salesman'),
            'customer'        => $this->input->post('customer'),
            'customer_code'   => $this->input->post('customer_code'),
            'apparel'         => $this->input->post('apparel'),
            'vendor'          => $this->input->post('vendor'),
            'vendor_parts'    => $this->input->post('vendor_parts'),
            'composition_1'   => $this->input->post('composition_1'),
            'composition_2'   => $this->input->post('composition_2'),
            'composition_3'   => $this->input->post('composition_3'),
            'unit'            => $this->input->post('unit'),
            'size_unit'       => $this->input->post('size_unit'),
            'size'            => $this->input->post('size'),
            'moq'             => $this->input->post('moq'),
            'net_wt'          => $this->input->post('net_wt'),
            'buy_price_usd'   => $this->input->post('buy_price_usd'),
            'buy_price_vnd'   => $this->input->post('buy_price_vnd'),
            'buy_price_jpy'   => $this->input->post('buy_price_jpy'),
            'sell_price_usd'  => $this->input->post('sell_price_usd'),
            'sell_price_vnd'  => $this->input->post('sell_price_vnd'),
            'sell_price_jpy'  => $this->input->post('sell_price_jpy'),
            'base_price_usd'  => $this->input->post('base_price_usd'),
            'base_price_vnd'  => $this->input->post('base_price_vnd'),
            'base_price_jpy'  => $this->input->post('base_price_jpy'),
            'lot_quantity'    => $this->input->post('lot_quantity'),
            'color'           => $this->input->post('color'),
            'vendor_color'    => $this->input->post('vendor_color'),
            'end_of_sales'    => $this->input->post('end_of_sales'),
            'inspection_rate' => $this->input->post('inspection_rate'),
            'origin'          => $this->input->post('origin'),
            'note_po_sheet'   => $this->input->post('color_note'),
            'note'            => $this->input->post('note'),
            'note_lapdip'     => $this->input->post('lapdip_note'),
        );
        $this->db->trans_begin();

        foreach($params as $key => $value){
            if($key == 'item_code' || $key == 'item_name' || $key == 'item_name_vn' 
                    || $key == 'jp_code' || $key == 'customer' || $key == 'customer_code' 
                    || $key == 'salesman' || $key == 'size' || $key == 'color') {
                if($value == '' || $value == null) {
                    {$params[$key] = "";}
                }
            } else {
                if($value == '' || $value == null) {unset($params[$key]);}
            }
        }
        $params['create_user'] = $currentUser['employee_id'];
        $params['create_date'] = $currentDate;

        $result = $this->items_model->insertItems($params);
        $this->surcharge($result, $surchargeDt, $id_del_list);
    }

    function surcharge($result, $surchargeDt, $id_del_list)
    {
        $current_user = $this->session->userdata('user');
        $current_date = date("Y/m/d H:i:s");
        $item_code = $this->input->post('item_code');
        $customer_code = $this->input->post('customer_code');
        $customer = $this->input->post('customer');
        $jp_code = $this->input->post('jp_code');
        $item_name = $this->input->post('item_name');
        $item_name_vn = $this->input->post('item_name_vn');
        $salesman = $this->input->post('salesman');
        $size = $this->input->post('size');
        $color = $this->input->post('color');
        
        if($result === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
            if($flag == 0) {
                redirect(base_url('products/add'));
            } else if($flag == 1) {
                redirect(base_url('products/edit?item_code='. rawurlencode(trim($this->input->post('item_code_old'))) 
                                                    .'&customer_code='. rawurlencode(trim($this->input->post('customer_code_old'))) 
                                                    .'&customer='. rawurlencode(trim($this->input->post('customer_old'))) 
                                                    .'&jp_code='. rawurlencode(trim($this->input->post('jp_code_old'))) 
                                                    .'&item_name='. rawurlencode(trim($this->input->post('item_name_old'))) 
                                                    .'&item_name_vn='. rawurlencode(trim($this->input->post('item_name_vn_old'))) 
                                                    .'&salesman='. rawurlencode(trim($this->input->post('salesman_old'))) 
                                                    .'&size='. rawurlencode(trim($this->input->post('size_old'))) 
                                                    .'&color='. rawurlencode(trim($this->input->post('color_old')))
                                                ));
            }
        } else {
            if(sizeof($surchargeDt) > 0) {
                foreach ($surchargeDt as $key => $surcharge) {
                    if(empty($surcharge['id'])) {
                        foreach ($surcharge as $key => $value) {
                           if($value == '') {
                                $surcharge[$key] = null;
                           }
                        }
                        $surcharge['create_user'] = $current_user['employee_id'];
                        $surcharge['create_date'] = $current_date;
                        $result_insert = $this->surcharge_model->insert($surcharge);
                        if($result_insert === FALSE) {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
                            if($flag == 0) {
                                redirect(base_url('products/add'));
                            } else if($flag == 1) {
                                redirect(base_url('products/edit?item_code='. rawurlencode(trim($this->input->post('item_code_old'))) 
                                                    .'&customer_code='. rawurlencode(trim($this->input->post('customer_code_old'))) 
                                                    .'&customer='. rawurlencode(trim($this->input->post('customer_old'))) 
                                                    .'&jp_code='. rawurlencode(trim($this->input->post('jp_code_old'))) 
                                                    .'&item_name='. rawurlencode(trim($this->input->post('item_name_old'))) 
                                                    .'&item_name_vn='. rawurlencode(trim($this->input->post('item_name_vn_old'))) 
                                                    .'&salesman='. rawurlencode(trim($this->input->post('salesman_old'))) 
                                                    .'&size='. rawurlencode(trim($this->input->post('size_old'))) 
                                                    .'&color='. rawurlencode(trim($this->input->post('color_old')))
                                                ));
                            }
                        }
                    } else {
                        foreach ($surcharge as $key => $value) {
                            if($value == '') {
                                $surcharge[$key] = null;
                            }
                        }
                        $surcharge['edit_user'] = $current_user['employee_id'];
                        $surcharge['edit_date'] = $current_date;
                        $result_update = $this->surcharge_model->update($surcharge);
                        if($result_update === FALSE) {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
                            if($flag == 0) {
                                redirect(base_url('products/add'));
                            } else if($flag == 1) {
                                redirect(base_url('products/edit?item_code='. rawurlencode(trim($this->input->post('item_code_old'))) 
                                                    .'&customer_code='. rawurlencode(trim($this->input->post('customer_code_old'))) 
                                                    .'&customer='. rawurlencode(trim($this->input->post('customer_old'))) 
                                                    .'&jp_code='. rawurlencode(trim($this->input->post('jp_code_old'))) 
                                                    .'&item_name='. rawurlencode(trim($this->input->post('item_name_old'))) 
                                                    .'&item_name_vn='. rawurlencode(trim($this->input->post('item_name_vn_old'))) 
                                                    .'&salesman='. rawurlencode(trim($this->input->post('salesman_old'))) 
                                                    .'&size='. rawurlencode(trim($this->input->post('size_old'))) 
                                                    .'&color='. rawurlencode(trim($this->input->post('color_old')))
                                                ));
                            }
                        }
                    }
                }
            }
            if(sizeof($id_del_list) > 0) {
                $result_delete = $this->surcharge_model->delete($id_del_list);
                if($result_delete === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
                    if($flag == 0) {
                        redirect(base_url('products/add'));
                    } else if($flag == 1) {
                        redirect(base_url('products/edit?item_code='. rawurlencode(trim($this->input->post('item_code_old'))) 
                                            .'&customer_code='. rawurlencode(trim($this->input->post('customer_code_old'))) 
                                            .'&customer='. rawurlencode(trim($this->input->post('customer_old'))) 
                                            .'&jp_code='. rawurlencode(trim($this->input->post('jp_code_old'))) 
                                            .'&item_name='. rawurlencode(trim($this->input->post('item_name_old'))) 
                                            .'&item_name_vn='. rawurlencode(trim($this->input->post('item_name_vn_old'))) 
                                            .'&salesman='. rawurlencode(trim($this->input->post('salesman_old'))) 
                                            .'&size='. rawurlencode(trim($this->input->post('size_old'))) 
                                            .'&color='. rawurlencode(trim($this->input->post('color_old')))
                                        ));
                    }
                }
            }

            $this->db->trans_commit();
            $data = $result;
            foreach ($data as &$value) {
                $value['url_encode'] = base_url('products/edit?item_code='. rawurlencode(trim($value["item_code"])) 
                                                .'&customer_code='. rawurlencode(trim($value['customer_code'])) 
                                                .'&customer='. rawurlencode(trim($value['customer'])) 
                                                .'&jp_code='. rawurlencode(trim($value['jp_code'])) 
                                                .'&item_name='. rawurlencode(trim($value['item_name'])) 
                                                .'&item_name_vn='. rawurlencode(trim($value['item_name_vn'])) 
                                                .'&salesman='. rawurlencode(trim($value['salesman'])) 
                                                .'&size='. rawurlencode(trim($value['size'])) 
                                                .'&color='. rawurlencode(trim($value['color']))
                                            );
            }
            $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            $this->session->set_flashdata('data', json_encode($data));
            redirect(base_url('products'));


            // Delete by item_code in table surcharge
            // $result_delete = $this->surcharge_model->delete_surcharge(trim($item_code));
            
            // if($result_delete) {
            //     if(sizeof($surchargeDt) > 0) {
            //         foreach($surchargeDt as $index=>$value) {
            //             $data = array(
            //                 'item_code'                 => trim($item_code),
            //                 'color'                     => $value['color'],
            //                 'size'                      => $value['size'],
            //                 'size_unit'                 => $value['size_unit'],
            //                 'qty_by_color_from'         => $value['qty_by_color_from'],
            //                 'qty_by_color_to'           => $value['qty_by_color_to'],
            //                 'qty_by_order'              => $value['qty_by_order'],
            //                 'po_amount_min_usd'         => $value['po_amount_min_usd'],
            //                 'surcharge_unit_color_usd'  => $value['surcharge_unit_color_usd'],
            //                 'surcharge_color_usd'       => $value['surcharge_color_usd'],
            //                 'po_amount_min_vnd'         => $value['po_amount_min_vnd'],
            //                 'surcharge_unit_color_vnd'  => $value['surcharge_unit_color_vnd'],
            //                 'surcharge_color_vnd'       => $value['surcharge_color_vnd'],
            //                 'po_amount_min_jpy'         => $value['po_amount_min_jpy'],
            //                 'surcharge_unit_color_jpy'  => $value['surcharge_unit_color_jpy'],
            //                 'surcharge_color_jpy'       => $value['surcharge_color_jpy'],
            //                 'surcharge_po'              => $value['surcharge_po' ],
            //             );
            //             foreach ($data as $key => $value) {
            //                 if($value == '') {
            //                     $data[$key] = null;
            //                 }
            //             }
            //             $result_insert = $this->surcharge_model->insert_surcharge($data);
            //             if($result_insert === FALSE) {
            //                 $this->db->trans_rollback();
            //                 $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
            //                 if($flag == 0) {
            //                     redirect(base_url('products/add'));
            //                 } else if($flag == 1) {
            //                     redirect(base_url('products/edit?item_code='.$item_code.'&customer_code='.$customer_code.'&salesman='.$salesman.'&size='.$size.'&color='.$color));
            //                 }
            //             }
            //         }
            //         $this->db->trans_commit();
            //         $data = $result;
            //         $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            //         $this->session->set_flashdata('data', json_encode($data));
            //         redirect(base_url('products'));
            //     } else {
            //         $this->db->trans_commit();
            //         $data = $result;
            //         $this->session->set_flashdata('success_msg', $this->lang->line('save_success'));
            //         $this->session->set_flashdata('data', json_encode($data));
            //         redirect(base_url('products'));
            //     }
            // } else {
            //     $this->db->trans_rollback();
            //     $this->session->set_flashdata('error_msg', $this->lang->line('save_fail'));
            //     if($flag == 0) {
            //         redirect(base_url('products/add'));
            //     } else if($flag == 1) {
            //         redirect(base_url('products/edit?item_code='.$item_code.'&customer_code='.$customer_code.'&salesman='.$salesman.'&size='.$size.'&color='.$color));
            //     }
            // }
        }
    }

    // Add new product - create by: thanh
    public function add()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }

        // set data array
        $this->data['screen_id'] = 'MTS0020';
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');
        $this->data['user_login'] = $currentUser['employee_id'];
        $this->data['current_date'] = $currentDate;
        $this->data['title'] = $this->lang->line('add_new_product');
        $this->data['type'] = '0';
        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
        $this->data['customer_list'] = $this->company_model->getAllCustomer();
        $this->data['unit_list'] = $this->komoku_model->get_all_unit();
        $this->data['size_list'] = $this->komoku_model->get_all_size();
        $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
        $this->data['currency_list'] = $this->komoku_model->get_all_currency();
        $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
        $this->data['apparel_list'] = $this->komoku_model->get_all_apparel();
        $this->data['color_list'] = $this->komoku_model->get_all_color();
        $this->data['origin_list'] = $this->komoku_model->get_all_origin();
        $this->data['vendor_list'] = $this->company_model->getAllVendor();

        // Load the subview and pass to master view
        $content = $this->load->view('products/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    // Edit product when click from search page - create by: thanh
    public function edit()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        
        $params = $this->input->get();

        // echo '<pre>'; print_r($params); echo '</pre>';return;
        // set data array 
        $this->data['screen_id'] = 'MTS0020';
        $items = $this->items_model->get_item_by_id(rawurldecode($params['item_code']), $params['customer_code'], $params['customer'], $params['jp_code'], $params['item_name'], $params['item_name_vn'], $params['salesman'], $params['size'], $params['color']);
        $this->data['items'] = $items[0];
        $this->data['type'] = '1';
        $this->data['title'] = $this->lang->line('edit_product');

        $this->data['salesman_list'] = $this->komoku_model->get_all_endsaleman();
        $this->data['customer_list'] = $this->company_model->getAllCustomer();
        $this->data['unit_list'] = $this->komoku_model->get_all_unit();
        $this->data['size_list'] = $this->komoku_model->get_all_size();
        $this->data['size_unit_list'] = $this->komoku_model->get_all_size_unit();
        $this->data['currency_list'] = $this->komoku_model->get_all_currency();
        $this->data['customer_code_list'] = $this->komoku_model->get_all_customer_code();
        $this->data['apparel_list'] = $this->komoku_model->get_all_apparel();
        $this->data['color_list'] = $this->komoku_model->get_all_color();
        $this->data['origin_list'] = $this->komoku_model->get_all_origin();
        $this->data['vendor_list'] = $this->company_model->getAllVendor();

        // Load the subview and Pass to the master view
        $content = $this->load->view('products/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    
    }

    public function details($product)
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $this->data['title'] = $this->lang->line('detail_product');
        
        // Load the subview and Pass to the master view
        $content = $this->load->view('products/add.php', $this->data, true);
        $this->load->view('master_page', array('content' => $content));
    }

    // search product - create by: thanh
    public function search()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $column_num = $this->input->post('order[0][column]');
        $column_name = $this->input->post('columns['.$column_num.'][data]');
        $sort = $this->input->post('order[0][dir]');

        // query (search items)
        $data = $this->items_model->search_items($params, $start, $length, $recordsTotal, $recordsFiltered, $column_name, $sort);
        foreach ($data as &$value) {
            $value['url_encode'] = base_url('products/edit?item_code='. rawurlencode(trim($value["item_code"])) .'&customer_code='. rawurlencode(trim($value['customer_code'])) .'&customer='. rawurlencode(trim($value['customer'])) .'&jp_code='. rawurlencode(trim($value['jp_code'])) .'&item_name='. rawurlencode(trim($value['item_name'])).'&item_name_vn='. rawurlencode(trim($value['item_name_vn'])) .'&salesman='. rawurlencode(trim($value['salesman'])) .'&size='. rawurlencode(trim($value['size'])) .'&color='. rawurlencode(trim($value['color'])));
        }
        echo json_encode(array(
            'data' => $data,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'draw' => $this->input->get('draw')
        ));
    }

    /**
     * Export Excel
     */
    public function excel(){
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->get();
        $params['sales_man'] = $params['salesman'];
        $params['flgExcel'] = True;
        $params = array_filter($params, function($value){
            return !empty($value);
        }); 
        $data = $this->items_model->search_items($params);
        /** write to excel Item List*/
        require_once 'vendor/autoload.php';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(APPPATH.'views/products/template.xlsx');
        $spreadsheet->setActiveSheetIndex(0);
        $dataArr = array();
        foreach($data as $item){
            $tmp = array(
                substr($item["jp_code"],0,2),
                $item["jp_code"],
                $item["item_code"],
                $item["note_lapdip"],
                $item["item_name_vn"],
                $item["item_name"],
                $item["item_name_com"],
                $item["item_name_des"],
                $item["item_name_dsk"],
                $item["composition_1"],
                $item["composition_2"],
                $item["composition_3"],
                null,
                $item["size"],
                $item["size_unit"],
                $item["color"],
                $item["unit"],
                $item["origin"],
                $item["salesman"],
                null,
                $item["customer"],
                $item["apparel"],
                $item["vendor"],
                $item["vendor_parts"],
                $item["net_wt"],
                $item["note_po_sheet"],
                $item["buy_price_vnd"],
                $item["buy_price_usd"],
                $item["buy_price_jpy"],
                $item["sell_price_vnd"],
                $item["sell_price_usd"],
                $item["sell_price_jpy"],
                $item["base_price_vnd"],
                $item["base_price_usd"],
                $item["base_price_jpy"],
                $item["shosha_price_vnd"],
                $item["shosha_price_usd"],
                $item["shosha_price_jpy"],
                $item["end_of_sales"] == 1 ? "■" : null,
                $item["surcharge_id"] != null ? "■" : null,
            );
            array_push($dataArr, $tmp);
        }
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "B2");

        /** Surcharge List */
        $spreadsheet->setActiveSheetIndex(1);
        $data = $this->surcharge_model->getAllSurcharge();
        $dataArr = array();
        foreach($data as $item){
            $tmp = array(
                $item["id"],
                $item["item_code"],
                $item["size"],
                $item["size_unit"],
                $item["color"],
                $item["qty_by_color_from"],
                $item["qty_by_color_to"],
                $item["qty_by_order"],
                $item["po_amount_min_usd"],
                $item["po_amount_min_vnd"],
                $item["po_amount_min_jpy"],
                $item["surcharge_unit_color_usd"],
                $item["surcharge_unit_color_vnd"],
                $item["surcharge_unit_color_jpy"],
                $item["surcharge_color_usd"],
                $item["surcharge_color_vnd"],
                $item["surcharge_color_jpy"],
                $item["surcharge_po"]
            );
            array_push($dataArr, $tmp);
        }
        $spreadsheet->getActiveSheet()->fromArray($dataArr, NULL, "A5");
        $spreadsheet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="items_master.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    // Delete product - create by: thanh
    public function delete()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $currentDate = date("Y/m/d H:i:s");
        $currentUser = $this->session->userdata('user');

        $item_code = $this->input->post('item_code_old');
        $old_edit_date = $this->input->post('edit_date');
        $customer_code = $this->input->post('customer_code_old');
        $customer = $this->input->post('customer_old');
        $jp_code = $this->input->post('jp_code_old');
        $item_name = $this->input->post('item_name_old');
        $item_name_vn = $this->input->post('item_name_vn_old');
        $salesman = $this->input->post('salesman_old');
        $size = $this->input->post('size_old');
        $color = $this->input->post('color_old');

        // check items is exists ?
        $edit_date = null;
        if($old_edit_date != '' || $old_edit_date != null) {
            $edit_date = $old_edit_date;
        }
        $data_check = array(
            'item_code' => $item_code,
            'customer_code' => $customer_code,
            'customer' => $customer,
            'jp_code' => $jp_code,
            'item_name' => $item_name,
            'item_name_vn' => $item_name_vn,
            'salesman' => $salesman,
            'size' => $size,
            'color' => $color,
            'edit_date' => $edit_date,
        );

        $result = $this->items_model->check_edit_date($data_check);
        if(!$result) {
            $this->session->set_flashdata('error_msg', $this->lang->line('COMMON_E001'));
            return redirect(base_url('products/edit?item_code='.$item_code.'&customer_code='.$customer_code.'&customer='.$customer.'&jp_code='.$jp_code.'&item_name='.$item_name.'&item_name_vn='.$item_name_vn.'&salesman='.$salesman.'&size='.$size.'&color='.$color));
        }
        $params = array(
            'item_code' => $item_code,
            'customer_code' => $customer_code,
            'customer' => $customer,
            'jp_code' => $jp_code,
            'item_name' => $item_name,
            'item_name_vn' => $item_name_vn,
            'salesman' => $salesman,
            'size' => $size,
            'color' => $color,
            'edit_date' => $currentDate,
            'edit_user' => $currentUser['employee_id'],
            'del_flg' => '1',
        );
        $query = $this->items_model->delete_products($params);
        if($query) {
            $this->session->set_flashdata('success_msg', $this->lang->line('del_success'));
            redirect(base_url('products'));
        } else {
            $this->session->set_flashdata('error_msg', $this->lang->line('del_fail'));
            return redirect(base_url('products/edit?item_code='. rawurlencode(trim($item_code)) 
                                        .'&customer_code='. rawurlencode(trim($customer_code)) 
                                        .'&customer='. rawurlencode(trim($customer)) 
                                        .'&jp_code='. rawurlencode(trim($jp_code)) 
                                        .'&item_name='. rawurlencode(trim($item_name)) 
                                        .'&item_name_vn='. rawurlencode(trim($item_name_vn)) 
                                        .'&salesman='. rawurlencode(trim($salesman)) 
                                        .'&size='. rawurlencode(trim($size)) 
                                        .'&color='. rawurlencode(trim($color))
                                    ));
        }
    }
    
    // check product already exist in database - create by: thanh
    function check_exist_product()
    {
        $params = $this->input->post();
        $data_check = array(
            'item_code' => $params['item_code'],
            // 'customer_code' => $params['customer_code'],
            'customer' => $params['customer'],
            'jp_code' => $params['jp_code'],
            'item_name' => $params['item_name'],
            'salesman' => $params['salesman'],
            'size' => $params['size'],
            'color' => $params['color'],
        );
        $item_name_vn = $params['item_name_vn'];
        $result = $this->items_model->check_product_exists($data_check, $item_name_vn);
        if($result) {
            echo json_encode($this->_response(false, 'product_exist'));
        } else {
            echo json_encode($this->_response(true));
        }
    }

    public function search_surcharge()
    {
        if (!$this->is_logged_in(false)) {
            show_error('', 403);
        }
        $params = $this->input->post('param');

        $data = $this->surcharge_model->getSurchargeById($params);
        echo json_encode(array(
            'data' => $data,
        ));
    }
}
