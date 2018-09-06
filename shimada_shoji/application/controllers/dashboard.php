<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('common_helper');
        $this->load->model('order_received_model');
        $this->load->model('dvt_model');
    }
    public function index()
    {
        if ($this->is_logged_in()) {
            $this->data['screen_id'] = 'DAB0010';
            $this->data['title'] = $this->lang->line('dashboard');
            $komoku_ = $this->order_received_model->getKomokuStatus();
            
            $result['countOrderReceive'] = $this->order_received_model->countOrder('t_orders_receive');
            $result['countOrderOut'] = $this->order_received_model->countOrder('t_orders');

            $result['countHCMCScheduledInput'] = $this->order_received_model->countScheduledInput($komoku_, $accpt_flg = '1', "HCM");
            $result['countDeleveryDateInput'] = $this->order_received_model->countDeleveryDateInput($komoku_);
            $result['countSalesInput'] = $this->order_received_model->countSalesInput($komoku_);

            $result['countScheduledInput'] = $this->order_received_model->countScheduledInput($komoku_, $accpt_flg = '2');
            $result['countInventoryInput'] = $this->order_received_model->countInventoryInput();
            $result['countDeliveryOrder'] = $this->order_received_model->countDeliveryPackingOrder(2);
            $result['countPackingOrder'] = $this->order_received_model->countDeliveryPackingOrder(1);
            $result['countDeliveryDate'] = $this->order_received_model->countDeliveryDate();

            $this->data['order_receive_count'] = $result;

            // Load the subview
            $content = $this->load->view('dashboard/index.php', $this->data, true);

            // Pass to the master view
            $this->load->view('master_page', array('content' => $content));
        }
    }
}
