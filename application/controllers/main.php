<?php

class main extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function index() {
        $data['include_view'] = $this->load->view("include_view", null, true);
        $this->load->view("search_view", $data);
    }

    private function search() {

        try {

            $d = $this->security->xss_clean($this->input->post("d"));

            if ($d == '')
                throw new Exception("Insert a word!");

            echo $this->finder->find($d);            
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function search_cli($data) {

        try {

            if (!$this->input->is_cli_request())
                throw new Exception("The request should be cli");

            if (is_null($data))
                throw new Exception("You should be insert a word\n");

            $this->finder->search_cli($data);
        } catch (Exception $ex) {
            print $ex->getMessage();
        }
    }

    private function update() {

        try {

            if ($this->input->is_cli_request())
                throw new Exception("The request is incorrect\n");
            $this->datamanager->update();
        } catch (Exception $ex) {
            print $ex->getMessage();
        }
    }

    public function _remap($method) {
        switch ($method) {
            case 'index' :
                $this->index();
                break;
            case 's' :
                $this->search();
                break;
            case 'scli' :
                $this->search_cli($_SERVER['argv'][3]);
                break;
            case 'upd' :
                $this->update();
                break;
            case 'g' :
                $this->getDataResult();
                break;
            case 'i' :
                $this->insertData();
                break;
        }
    }

}
