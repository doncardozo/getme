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

if (!function_exists("build_pagination")) {

    function build_pagination($url, $total_rows, $per_page, $cant_pages, $cur_page) {
                
        $config['base_url'] = $url;        
        $config['use_page_numbers'] = true;        
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $config['num_links'] = 20;
        $config['cur_page'] = $cur_page;
        $config['full_tag_open'] = "<div class='pagination'>";
        $config['full_tag_close'] = "</div>";
        $config['cur_tag_open'] = "&nbsp;<b style='font-size:15px'>";
        $config['cur_tag_close'] = '</b>';
        $config['first_link'] = "<<";
        $config['next_link'] = "";
        $config['prev_link'] = "";
        $config['last_link'] = ">>";      
        
        $ci =& get_instance();
        $ci->pagination->initialize($config);

        return $ci->pagination->create_links();

    }

}