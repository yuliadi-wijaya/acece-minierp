<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Site extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_site_list() {
        $this->db->select('*');
        $this->db->from('site_information');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function get_site_by_id($site_id) {
        $this->db->select('*');
        $this->db->from('site_information');
        $this->db->where('site_id', $site_id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function insert_site($data) {
        $result = $this->db->insert('site_information', $data);
        if ($result) {
            return TRUE;
        } else {
            return false;
        }
    }

    public function update_site($data, $site_id) {
        $this->db->where('site_id', $site_id);
        $result = $this->db->update('site_information', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_site($site_id) {
        $this->db->where('site_id', $site_id)->delete('site_information');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
}
