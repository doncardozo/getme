<?php

class main extends CI_Controller {

    private $tmp_dir = null;
    private $url;

    public function __construct() {
        parent::__construct();
        $this->tmp_dir = get_gmf() . "/tmp";
        $this->url = base_url() . "index.php/main/spage/p";
    }

    private function index() {
        $this->remove_all_old_files();
        $data['include_view'] = $this->load->view("include_view", null, true);
        $this->load->view("search_view", $data);
    }

    private function search() {

        try {

            $d = $this->security->xss_clean($this->input->post("d"));

            if ($d == '')
                throw new Exception("Insert a word!");

            if ($this->session->userdata('keyword') === md5($d)) {
                $this->show_page();
                return;
            } else {
                $this->remove_old_serch();
            }

            $resp = $this->finder->find($d);

            if ($resp['total'] == 0) {
                
                $this->responseJSON( "Total: {$resp['total']}", "", json_encode(array()) );
                
            } else {

                $total_max = 3000;

                if ($resp['total'] <= $total_max) {
                    
                    $this->responseJSON( 
                            "Total: {$resp['total']} | Per page: {$resp['total']}<br />",
                            "",
                            json_encode($resp['data'])
                    );
                    
                } else {
                    
                    $cant_files = ceil($resp['total'] / $total_max);
                    $cant_regs = $resp['total'];
                    $aux = null;
                    $this->session->set_userdata("keyword", $resp['key']);
                    $this->session->set_userdata("total_regs", $resp['total']);
                    $this->session->set_userdata("cant_files", $cant_files);
                    $this->session->set_userdata("per_page", $total_max);
                    $first_page = 0;

                    for ($i = $first_page; $i < $cant_files; $i++) {
                        $aux = array_splice($resp['data'], -$total_max);
                        $file = "{$this->tmp_dir}/{$resp['key']}_$i";
                        file_put_contents($file, json_encode($aux));
                        $aux = null;
                    }

                    $file_data = file_get_contents("{$this->tmp_dir}/{$resp['key']}_{$first_page}");

                    $this->responseJSON(
                            "Total: {$resp['total']} | Per page: $total_max<br />", 
                             build_pagination($this->url, $resp['total'], $total_max, $cant_files, $first_page), 
                             $file_data
                    );
                }
            }
        } catch (Exception $ex) {
            $this->responseJSON('', '', json_encode(array($ex->getMessage())));
        }
    }

    private function responseJSON($msg = '', $pag = '', $resp = '') {
        echo json_encode(
                array(
                    "total_msg" => $msg,
                    "pag" => $pag,
                    "resp" => $resp
                )
        );
    }

    private function remove_old_serch() {

        if ($this->session->userdata("keyword")) {
            $this->remove_all_old_files();
        }
    }

    private function remove_all_old_files() {
        $files = glob("{$this->tmp_dir}/*");
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
        $this->session->sess_destroy();
    }

    private function show_page() {
        
        $page = $this->input->post("page");
        #$keyword = $this->session->userdata("keyword");
        #$total_regs = $this->session->userdata("total_regs");
        #$cant_files = $this->session->userdata("cant_files");
        #$per_page = $this->session->userdata("per_page");

        $sess_data = $this->session->all_userdata();
        
        if (empty($page) || $page == '<<') {
            $page = 0;
        } elseif ($page == '>>') {
            $page = --$sess_data['cant_files'];
        } else {
            $page--;
        }

        $file_data = file_get_contents("{$this->tmp_dir}/{$sess_data['keyword']}_{$page}");

        $total_per_page = sizeof(json_decode($file_data));

        $this->responseJSON(
                "Total: {$sess_data['total_regs']} | Per page: {$total_per_page}<br />",
                build_pagination( 
                        $this->url, 
                        $sess_data['total_regs'], 
                        $sess_data['per_page'], 
                        $sess_data['cant_files'], 
                        $page 
               ),
                $file_data
        );
        
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
            case 'spage' :
                $this->show_page();
                break;
            case 'index' :
                $this->index();
                break;
        }
    }

}
