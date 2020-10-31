<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Workhour_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

     //attendance List
     public function work_hour_list() {
        $this->db->select('*');
        $this->db->from('site_work_hour');
        $query = $this->db->get();
        return $query->result_array();
         
    }

    //attendance List
    public function work_hour_list_by_site_id($site_id) {
        $this->db->select('*');
        $this->db->from('site_work_hour')->where('site_id',$site_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }


    public function workhour_create($data = array())
    {
        return $this->db->insert_batch('site_work_hour', $data); 
    }

    public function update_workhour($data = []) {
        $this->db->where('site_id', $data['site_id']);
        $this->db->update('site_work_hour', $data);
        return true;
    }

    public function update_workhour_balk($data) {
       
        $this->db->trans_start();
        foreach ($data as $d) {
            $this->db->where('site_id', $d['site_id']);
            $this->db->where('day', $d['day']);
            $this->db->update('site_work_hour', $d);

        };
        $this->db->trans_complete();
       
        return true;
    }

    // Delete site work hour  Item
    public function delete_workhour($site_id) {
        $this->db->where('site_id', $site_id);
        $this->db->delete('site_work_hour');
        return true;
    }

}