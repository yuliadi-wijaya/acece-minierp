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

    public function get_site_list_has_working_hour() {
        $this->db->distinct(' s.site_id,s.name');
        $this->db->select('s.site_id,s.name');
        $this->db->from('site_information s');
        $this->db->join('site_work_hour sw', 's.site_id = sw.site_id','inner');
        $this->db->order_by('s.name', 'asc');
        $query = $this->db->get();
      
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;

        
    
    }

    public function get_site_list_dropdown() {
        $this->db->select('*');
        $this->db->from('site_information');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        $data=$query->result();
       
        $list = array('' => 'Select One...');
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->site_id]=$value->name.' '."(".$value->site_id.")";
            }
        }
        return $list;
    }

    public function get_site_work_hour_by_id($site_id) {
        $this->db->select('*');
        $this->db->from('site_work_hour s');
        $this->db->join('site_information b','s.site_id = b.site_id', 'inner');
        $this->db->where('s.site_id', $site_id);

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

    public function update_site_rate($site_id,$basic) {
        $data = array('hrate' => $basic);    
        $this->db->where('site_id', $site_id);
        
       
        return $this->db->update('site_information', $data);
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
