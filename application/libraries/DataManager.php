<?php

/**
 * Short description of class DataManager
 *
 * @access public
 * @author firstname and lastname of author, <author@example.org>
 */
class DataManager {
    
    /**
     * Short description of attribute _mp
     *
     * @access private
     * @var array
     */
    private $_mp = array();

    /**
     * Short description of attribute _ci
     *
     * @access private
     */
    private $ci = null;

    // --- OPERATIONS ---

    public function __construct() {
        $this->ci = & get_instance();
    }

    /**
     * Short description of method read
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string mp
     * @return string
     */
    public function read() {

        $objects = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(get_def_mp_path()), RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($objects as $name => $object) {
            echo "$name\n";
        }
    }

    /**
     * Method update_cli
     */
    public function update_cli() {
        $cmd = "cd /var/www/getme; sudo php index.php dm read > " . get_gmf() . "/hdisk.data;";
        system($cmd);
    }

    /**
     * Short description of method load_current_mp
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return mixed
     */
    public function load_current_mp($u,$p) {
        $this->ci->ssh2->connect($u, $p);
        $this->ci->ssh2->exec("df -m | awk '{print $6}' | grep " . get_def_mp_path() . " > " . get_gmf() . "/mounted.tmp");
        $this->_mp = explode("\n", file_get_contents(get_gmf() . "/mounted.tmp"));
        array_pop($this->_mp);

        $aux_mp = array();
        foreach ($this->_mp as $data) {
            $aux = explode("/", $data);
            $aux_mp[strtolower(array_pop($aux))] = $data;
        }

        $this->_mp = $aux_mp;
    }

}

/* end of class DataManager */
?>