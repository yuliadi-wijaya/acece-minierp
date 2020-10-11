<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csite extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('lsite');
        $this->load->library('auth');
        $this->load->library('session');
        $this->load->model('Site');
        $this->auth->check_admin_auth();
    }

    // default page
    public function index() {
         $content = $this->lsite->add_site();
        $this->template->full_admin_html_view($content);
    }

    // show add site page
    public function add_site() {
        $content = $this->lsite->add_site();
        $this->template->full_admin_html_view($content);
    }

    // submit site data
    public function submit_add_site() {
        $site_id = $this->auth->generator(10);
        $data = array(
            'site_id'    => $site_id,
            'name'          => $this->input->post('name',TRUE),
            'description'   => $this->input->post('description',TRUE),
            'address'       => $this->input->post('address',TRUE),
            'status'        => 1
        );
       
        $result = $this->Site->insert_site($data);
        if ($result) {
            $this->session->set_userdata(array('message' => display('added_successfully')));
            redirect(base_url('Csite/add_site'));
        } else {
            $this->session->set_userdata(array('error_message' => display('failed_added')));
            redirect(base_url('Csite/add_site'));
        }
    }

    // show site list page
    public function manage_site() {
        $content = $this->lsite->manage_site();
        $this->template->full_admin_html_view($content);
    }

    //show update site form page
    public function update_site($site_id) {
        $content = $this->lsite->update_site($site_id);
        $this->template->full_admin_html_view($content);
    }

    // submit site data
    public function submit_update_site($site_id) {
        $data = array(
            'name'          => $this->input->post('name',TRUE),
            'description'   => $this->input->post('description',TRUE),
            'address'       => $this->input->post('address',TRUE),
            'status'        => 1
        );
       
        $result = $this->Site->update_site($data, $site_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('updated_successfully')));
            redirect(base_url('Csite/manage_site'));
        } else {
            $this->session->set_userdata(array('error_message' => display('failed_updated')));
            redirect(base_url('Csite/manage_site'));
        }
    }


    public function delete_site($site_id = null) 
    { 
        if ($this->Site->delete_site($site_id)) {
            $this->session->set_userdata(array('message' => display('deleted_successfully')));
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
}
