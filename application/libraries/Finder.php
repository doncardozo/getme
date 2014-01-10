<?php

/**
 * Short description of class Finder
 *
 * @access public
 * @author firstname and lastname of author, <author@example.org>
 */
class Finder {

    // --- ATTRIBUTES ---
    /**
     * ci property for codeigniter object.
     * @var type 
     */
    private $ci = null;

    /**
     * $udat property for user data
     * @var type 
     */
    private $_udat = array();

    // --- OPERATIONS ---

    /**
     * Method Construct
     */
    public function __construct() {
        $this->ci = & get_instance();
    }

    /**
     * Method setUserData
     * @param type $u
     * @param type $p
     */
    public function setUserData($u, $p) {
        $this->_udat = array(
            $u, $p
        );
    }

    /**
     * Short description of method search
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string data     
     * @return string
     */
    public function findData($data) {
        try {
            if ($this->ci->ssh2->connect($this->_udat[0], $this->_udat[1]) !== true)
                throw new Exception("I can't connect!");

            $command = "egrep $data " . get_gmf() . "/hdisk.data;";

            if ($this->ci->ssh2->exec($command) !== true)
                throw new Exception("I don't search!");

            return array(
                'status' => true,
                'msg' => $this->ci->ssh2->getStringResult()
            );
        } catch (Exception $ex) {
            return array(
                'status' => false,
                'msg' => $ex->getMessage()
            );
        }
    }
    
    private function getCount($data){
        $command = "egrep $data " . get_gmf() . "/hdisk.data | wc -l";
        exec($command, $out);             
        return $out[0];
    }

    /**
     * Short description of method search
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string data     
     * @return string
     */
    public function find($data) {
        
        $total = $this->getCount($data);
        
        if($total == 0){
            return array("total" => $total, "key"=>"", "data" => "");
        }
        else {
            $command = "egrep $data " . get_gmf() . "/hdisk.data";
            exec($command, $out);        
            
            return array("total" => $total, "key"=> md5($data), "data" => $out);
        }
    }

    /**
     * Short description of method load_mp
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return mixed
     */
    public function load_mp() {
        
    }

}

/* end of class Finder */
?>