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

            $command = "clear; cd " . get_gmf() . "; cat hdisk.data | grep $data;";

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

    /**
     * Short description of method search
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string data     
     * @return string
     */
    public function find($data) {
        $command = "clear; cd " . get_gmf() . "; cat hdisk.data | grep $data;";
        system($command, $out);
        return $out[0];        
    }

    /**
     * Method search_cli
     * @param type $data
     */
    public function search_cli($data) {
        $cmd = "clear; cd " . get_gmf() . "; cat hdisk.data | grep $data;";
        system($cmd, $output);
        print $output[0];
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