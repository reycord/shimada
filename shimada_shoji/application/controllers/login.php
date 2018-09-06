<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('employee_model');
    }
    public function index()
    {
        // Pass to the master view
        $this->load->view('login/index.php');
    }
    public function checkLogin()
    {
        $this->form_validation->set_rules('user', 'Username', 'required');
        $this->form_validation->set_rules('pass', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<p style="color:#d42a38">', '</p>');
        $data = array(
            'err_message' => '',
        );
        if ($this->form_validation->run() === true) {
            $userName = $this->input->post('user');
            $password = $this->input->post('pass');
            $result = $this->compareInfo($userName, $password);
            if ($result) {
                if(isset($_GET["location"])){
                    $url = urldecode($_GET["location"]);
                    redirect($url);
                }
                redirect('/dashboard');
            } else {
                $data['err_message'] = $this->lang->line('MLG0000_E001');
                $this->load->view('login/index.php', $data);
            }
        } else {
            $this->load->view('login/index.php', $data);
        }
    }
    private function compareInfo($userName, $password)
    {
        $employee = $this->employee_model->getLoginInfo($userName);
        if ($employee === null) {
            return false;
        } else if ( !password_verify( $password, $employee['password'] )) {
            return false;
        }
        $this->session->set_userdata('user', $employee);
        return true;
    }
    public function logout()
    {
        $user = $this->session->userdata('user');
        if (isset($user)) {
            $this->session->unset_userdata('user');
            // $this->session->sess_destroy();
        }
        redirect('/login');
    }
}
