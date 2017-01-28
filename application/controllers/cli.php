<?php

class cli extends CI_Controller {     

    public function read() {
        
        try {

            if (!$this->input->is_cli_request())
                throw new Exception("The request should be cli.");

            $this->datamanager->read();
            
        } catch (Exception $ex) {
            print $ex->getMessage() . "\n";
        }
        
    }

    function update() {

        try {

            if (!$this->input->is_cli_request())
                throw new Exception("The request should be cli.");

            $this->datamanager->update_cli();
            
        } catch (Exception $ex) {
            print $ex->getMessage() . "\n";
        }
    }

}
