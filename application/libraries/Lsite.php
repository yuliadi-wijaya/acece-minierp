<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lsite {

    // Show site page
    public function add_site() {
        $CI = & get_instance();
        $CI->load->model('Site');

        $data = array(
            'title' => display('site'),
            'sub_title' => display('add_site'),
        );
        $content = $CI->parser->parse('site/site_form', $data, true);
        return $content;
    }

    // Show list of site
    public function manage_site() {
        $CI = & get_instance();
        $CI->load->model('Site');
        $site_list = $CI->Site->get_site_list();
        $data = array(
            'title'       => display('site'),
            'sub_title'   => display('manage_site'),
            'site_list' => $site_list,
        );
        $content = $CI->parser->parse('site/manage_site', $data, true);
        return $content;
    }

    public function update_site($site_id) {
        $CI = & get_instance();
        $CI->load->model('Site');
        $site = $CI->Site->get_site_by_id($site_id);

        $data = array(
            'title'         => display('site'),
            'sub_title'     => display('update_site'),
            'site_id'       => $site['site_id'],
            'name'          => $site['name'],
            'description'   => $site['description'],
            'address'       => $site['address'],
            'sign_in_standard' => $site['sign_in_standard'],
            'sign_out_standard' => $site['sign_out_standard'],
        );

        $content = $CI->parser->parse('site/site_update_form', $data, true);
        return $content;
    }

}

?>