<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quotation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

//    ========== its for quotation_list ==============
    public function quotation_list($offset, $limit) {
        $this->db->select('a.*, b.customer_name');
        $this->db->from('quotation a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->order_by('a.id', 'desc');
        $this->db->limit($offset, $limit);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

   

    //quotation insert
    public function quotation_entry($data) {
        $this->db->select('*');
        $this->db->from('quotation');
        $this->db->where('quot_no', $data['quot_no']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->insert('quotation', $data);
            return TRUE;
        }
    }

// Delete Quotation 
    public function quotation_delete($quot_id) {
        //quotation
        $this->db->where('quotation_id', $quot_id);
        $this->db->delete('quotation');
        //used product
        $this->db->where('quot_id', $quot_id);
        $this->db->delete('quot_products_used');
        // used labour
        $this->db->where('quot_id', $quot_id);
        $this->db->delete('quotation_service_used');
        return true;
    }

    // ================  Quotation edit information ===================
    public function quot_main_edit($quot_id) {
        return $this->db->select('*')
                        ->from('quotation')
                        ->where('quotation_id', $quot_id)
                        ->get()
                        ->result_array();
    }
    //Item tax details 
    public function itemtaxdetails($quot_no){
        $taxdetector = 'item'.$quot_no;
          return $this->db->select('*')
                        ->from('quotation_taxinfo')
                        ->where('relation_id', $taxdetector)
                        ->get()
                        ->result_array();
    }
     //Service tax details 
    public function servicetaxdetails($quot_no){
        $taxdetector = 'serv'.$quot_no;
          return $this->db->select('*')
                        ->from('quotation_taxinfo')
                        ->where('relation_id', $taxdetector)
                        ->get()
                        ->result_array();
    }

    public function bank_list(){
        
        return $this->db->select('*')
                        ->from('bank_add')
                        ->get()
                        ->result_array();
    }


    public function quot_product_edit($quot_id) {
        return $this->db->select('*')
                        ->from('quot_products_used')
                        ->where('quot_id', $quot_id)
                        ->order_by('id', 'asc')
                        ->get()
                        ->result_array();
    }

    public function customerinfo($customer_id) {
        return $this->db->select('*')
                        ->from('customer_information')
                        ->where('customer_id', $customer_id)
                        ->get()
                        ->result_array();
    }

    // quotation update
    public function quotation_update($data) {
        $this->db->select('*');
        $this->db->from('quotation');
        $this->db->where('quotation_id', $data['quotation_id']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('quotation_id', $data['quotation_id']);
            $this->db->update('quotation', $data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Retrieve company Edit Data
    public function retrieve_company() {
        $this->db->select('*');
        $this->db->from('company_information');
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }



    public function quot_service_detail($quot_id)
    {
          $result = $this->db->select('a.*,b.*')
                        ->from('quotation_service_used a')
                        ->join('product_service b', 'a.service_id=b.service_id')
                        ->where('a.quot_id', $quot_id)
                        ->order_by('a.id', 'asc')
                        ->get()
                        ->result_array();
                        if(!empty($result)){
                            return $result;
                        }else{
                            return false;
                        }
    }

       


    public function quot_product_detail($quot_id) {
        return $this->db->select('a.*,b.*')
                        ->from('quot_products_used a')
                        ->join('product_information b', 'a.product_id=b.product_id','left')
                        ->where('a.quot_id', $quot_id)
                        ->order_by('a.id', 'asc')
                        ->get()
                        ->result_array();
    }



//    ========= its for quotation onkeyup search ============
    public function quotationonkeyup_search($keyword) {
        $this->db->select('a.*, b.customer_name');
        $this->db->from('quotation a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        if ($this->session->userdata('user_type') == 3) {
            $this->db->where('a.customer_id', $this->session->userdata('user_id'));
            $this->db->where('a.cust_show', 1);
        }
        $this->db->like('b.customer_name', $keyword, 'both');
        $this->db->or_like('a.quot_no', $keyword, 'both');
        $this->db->order_by('a.id', 'desc');
        $this->db->limit(100);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_allproduct()
    {
       return $this->db->select('*')->from('product_information')->get()->result();
    }


    public function get_allcustomer(){
          return $this->db->select('*')
                        ->from('customer_information')
                        ->order_by('customer_name', 'asc')
                        ->get()
                        ->result_array();
    }

}
