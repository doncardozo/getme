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

            $resp = $this->finder->find($d);

            if ($resp['total'] == 0) {
                echo json_encode(
                        array(
                            "total" => $resp['total'],
                            "total_cur" => $resp['total'],
                            "resp" => json_encode($resp['data'])
                        )
                );
            } else {
                
                $total_max = 3000;
                
                if ($resp['total'] <= $total_max) {
                    echo json_encode(
                            array(
                                "total" => $resp['total'],
                                "total_cur" => $resp['total'],
                                "resp" => json_encode($resp['data'])
                            )
                    );
                } else {
                    
                    $tmp_dir = "/var/www/getme/files/tmp";
                    $cant_files = ceil($resp['total']/$total_max);
                    $cant_regs = $resp['total'];
                    $aux = null;

                    for ($i = 0; $i < $cant_files; $i++) {
                                                
                        $aux = array_splice($resp['data'], -$total_max);                        
                        $file = "$tmp_dir/{$resp['key']}_$i";
                        file_put_contents($file, json_encode($aux));
                        $aux = null;   
                        
                    }
                    
                    $file_data = file_get_contents($file = "$tmp_dir/{$resp['key']}_0");

                    echo json_encode(
                            array(
                                "total" => $resp['total'],
                                "total_cur" => $total_max,
                                "resp" => $file_data
                            )
                    );
                }
            }
        } catch (Exception $ex) {

            echo json_encode(
                    array(
                        "total" => 0, 
                        "total_cur" => 0,
                        "resp" => array($ex->getMessage())
                    )
            );
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
