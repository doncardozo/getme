<?php

/**
 * Short description of class SSH2
 *
 * @access public
 * @author firstname and lastname of author, <author@example.org>
 */
class SSH2 {
    // --- ASSOCIATIONS ---
    // --- ATTRIBUTES ---

    /**
     * Short description of attribute _ssh2
     *
     * @access private
     * @var null
     */
    private $_ssh2 = null;

    /**
     * Short description of attribute _stream
     *
     * @access private
     * @var null
     */
    private $_stream = null;

    // --- OPERATIONS ---

    /**
     * Short description of method connect
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string user
     * @param  string pass
     * @return mixed
     */
    public function connect($user, $pass) {
        try {
            if (!$this->_ssh2 = ssh2_connect('localhost', 22))
                throw new Exception("I can't connect!\n");

            if (!ssh2_auth_password($this->_ssh2, $user, $pass))
                throw new Exception("I can't authenticate!\n");

            return true;
            
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Short description of method exec
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string command
     * @return stream
     */
    public function exec($command) {
        try {

            if (!$this->_stream = ssh2_exec($this->_ssh2, $command))
                throw new Exception("I can't execute command!\n");
            
            return true;

        } catch (Exception $ex) {
            return $ex->getMessage();            
        }
    }

    /**
     * Short description of method getStringResult
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return string
     */
    public function getStringResult() {

        try {

            if (is_null($this->_stream))
                throw new Exception("No stream load");


            stream_set_blocking($this->_stream, true);

            $data = "";
            while ($buf = fread($this->_stream, 4096)) {
                $data .= $buf;
            }

            print $data;

            fclose($this->_stream);
            
        } catch (Exception $ex) {            
            return $ex->getMessage();               
        }
    }

}

/* end of class SSH2 */
?>