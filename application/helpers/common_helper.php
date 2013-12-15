<?php

if (!function_exists("get_def_mp_path")) {

    function get_def_mp_path() {
        $ci = & get_instance();
        return $ci->config->item("mp_path");
    }

}

if (!function_exists("get_gmf")) {

    function get_gmf() {
        $ci = & get_instance();
        return $ci->config->item("gmf_path");
    }

}

if (!function_exists("pr")) {

    function pr($o) {
        echo "<pre>";
        print_r($o);
        echo "</pre>";
    }

}

if (!function_exists("logged")) {

    function logged() {
        $ci = & get_instance();
        if ($ci->session->userdata("login") === true) {
            return true;
        }
    }

}

if (!function_exists("resp_json")) {

    function resp_json($obj, $json = false) {
        
        echo json_encode(
                array(
                    "total" => $obj['total'],
                    "resp" => ($json) ? json_encode($obj['data']) : $obj['data']
                )
        );
        
    }

}