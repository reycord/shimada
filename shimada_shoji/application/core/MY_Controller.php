<?php
class MY_Controller extends CI_Controller
{
    protected $data = [];
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Ho_Chi_Minh"); 
        $siteLang = $this->session->userdata('site_lang');
        $this->config->set_item('language', $siteLang ? $siteLang : 'japanese');
        $this->load->helper('url');
        $this->load->helper('common_helper');
        $this->load->library('common');
        $this->load->library('session');
        $this->load->library('encryption');
        $this->load->library('form_validation');
        $this->load->library('migration');
        $this->load->model('employee_model');
        // if ($this->migration->current() === FALSE)
        // {
        //     show_error($this->migration->error_string());
        // }
        $this->setUser();
        $this->data['screen_id'] = '';
        $this->data['controllerName'] = $this->uri->segment(1);
    }

    public function is_logged_in($redirect = true)
    {
        $user = $this->session->userdata('user');
        if(isset($user)){
            return true;
        } else {
            if ($redirect ) {
                $location = urlencode(current_url());
                redirect('/login'."?location=$location", 'refresh');
            }
            return false;
        }
    }
    public function setUser() 
   {
       if($this->is_logged_in()){
            $this->data['user'] = $this->session->userdata("user");
            $this->data['theme'] = $this->employee_model->getTheme($this->data['user']['employee_id']);
       }else{
            $this->data['user'] = null;
            $this->data['theme'] = null;
       }
    }

    public function responseJsonSuccess($data = null, $message = null){
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'data' => $data,
            'message' => $message
        ));
    }

    public function responseJsonError($message, $code = null){
        echo json_encode(array(
            'success' => false,
            'code' => $code,
            'message' => $message
        ));
    }
}