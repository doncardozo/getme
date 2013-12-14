<?php

class login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata("login") !== true){
            $this->session->set_userdata("login", false);    
        }        
    }
    
    private function index() {
        
        if ($this->session->userdata("login") === true) {
            redirect(base_url() . "index.php/main");
        } else {
            $this->show_login_form();            
        }
    }

    private function check() {  

        try {

            $u = $this->security->xss_clean($this->input->post("u"));
            $p = $this->security->xss_clean($this->input->post("p"));

            if ($u == '' || $p == '')
                throw new Exception("");

            if ($this->ssh2->connect($u, $p) !== true)
                throw new Exception("Incorrect User/Pass");

            $this->session->set_userdata(array(
                'login' => true,
                'u' => $u,
                'p' => $p
            ));

            redirect(base_url() . "index.php/main");
            
        } catch (Exception $ex) {            
            $this->show_login_form($ex->getMessage());            
        }
    }

    private function show_login_form($message = '') {        
        $data['include_view'] = $this->getMediaInclude();
        $data['message'] = $message;
        $this->load->view("login_view", $data);        
    }

    private function bye() {
        $this->session->sess_destroy();
        redirect(base_url() . "index.php/login");
    }

    private function getMediaInclude() {
        return $this->load->view("include_view", null, true);
    }

    public function _remap($method) {

        switch ($method) {
            case 'auth' :
                $this->check();
                break;
            case 'logout' :
                $this->bye();
                break;
            default :
                $this->index();
                break;
        }
    }

}
