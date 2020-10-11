<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Returnse extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lcustomer');
        $this->load->library('session');
        $this->load->model('Customers');
        $this->auth->check_admin_auth();
    }

    public function return_invoice_entry() {
        $invoice_id = $this->input->post('invoice_id',TRUE);
        $total          = $this->input->post('grand_total_price',TRUE);
        $customer_id    = $this->input->post('customer_id',TRUE);
        $isrtn          = $this->input->post('rtn',TRUE);

         $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
    $headn = $customer_id.'-'.$cusifo->customer_name;
    $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
    $customer_headcode = $coainfo->HeadCode;

        $date      = date('Y-m-d');
        $createby  = $this->session->userdata('user_id');
        $createdate= date('Y-m-d H:i:s');
      
  $cosdr = array(
      'VNo'            => $invoice_id,
      'Vtype'          => 'Return',
      'VDate'          => $date,
      'COAID'          => $customer_headcode,
      'Narration'      => 'Customer debit For Return '.$cusifo->customer_name,
      'Debit'          => 0,
      'Credit'         => $total,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 


      

        $ads      = $this->input->post('radio',TRUE);
        $quantity = $this->input->post('product_quantity',TRUE);
        $available_quantity = $this->input->post('available_quantity',TRUE);

        $rate         = $this->input->post('product_rate',TRUE);
        $p_id         = $this->input->post('product_id',TRUE);
        $total_amount = $this->input->post('total_price',TRUE);
        $discount_rate= $this->input->post('discount',TRUE);
        $tax_amount   = $this->input->post('tax',TRUE);
        $soldqty      = $this->input->post('sold_qty',TRUE);


        if (is_array($p_id))
            for ($i = 0; $i < count($p_id); $i++) {

                $product_quantity = $quantity[$i];
                $product_rate     = $rate[$i];
                $product_id       = $p_id[$i];
                $sqty             = $soldqty[$i];
                $total_price      = $total_amount[$i];
                $supplier_rate    = $this->supplier_rate($product_id);
                $discount         = $discount_rate[$i];
                $tax              = -$tax_amount[$i];

                $data1 = array(
                    'invoice_details_id'=> $this->generator(15),
                    'invoice_id'        => $invoice_id,
                    'product_id'        => $product_id,
                    'quantity'          => -$product_quantity,
                    'rate'              => $product_rate,
                    'discount'          => -is_numeric($discount),
                    'tax'               => $tax,
                    'supplier_rate'     => $supplier_rate[0]['supplier_price'],
                    'paid_amount'       => -$total,
                    'total_price'       => -$total_price,
                    'status'            => 1
                );


                $returns = array(
                    'return_id'     => $this->generator(15),
                    'invoice_id'    => $invoice_id,
                    'product_id'    => $product_id,
                    'customer_id'   => $this->input->post('customer_id',TRUE),
                    'ret_qty'       => $product_quantity,
                    'byy_qty'       => $sqty,
                    'date_purchase' => $this->input->post('invoice_date',TRUE),
                    'date_return'   => $date,
                    'product_rate'  => $product_rate,
                    'deduction'     => $discount,
                    'total_deduct'  => $this->input->post('total_discount',TRUE),
                    'total_tax'     => $this->input->post('total_tax',TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price',TRUE),
                    'reason'        => $this->input->post('details',TRUE),
                    'usablity'      => $this->input->post('radio',TRUE)
                );

                if ($ads == 1) {
                    $this->db->insert('invoice_details', $data1);
                }
                $this->db->insert('product_return', $returns);

            }

             $this->db->insert('acc_transaction',$cosdr);
            

        return $invoice_id;
    }
    //Get Supplier rate of a product
    public function supplier_rate($product_id) {
        $this->db->select('supplier_price');
        $this->db->from('supplier_product');
        $this->db->where(array('product_id' => $product_id));
        $query = $this->db->get();
        return $query->result_array();
    }
    ///#################### Supplier return  Entry ############///////////
    public function return_supplier_entry() {
        $purchase_id = $this->input->post('purchase_id',TRUE);
        $total       = $this->input->post('grand_total_price',TRUE);
        $supplier_id = $this->input->post('supplier_id',TRUE);
        $isrtn       = $this->input->post('rtn',TRUE);
        $supinfo     = $this->db->select('*')->from('supplier_information')->where('supplier_id',$supplier_id)->get()->row();
        $sup_head    = $supinfo->supplier_id.'-'.$supinfo->supplier_name;
        $sup_coa     = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();
        $receive_by   = $this->session->userdata('user_id');
        $receive_date = date('Y-m-d');
        $createdate   = date('Y-m-d H:i:s');

        $date  = date('Y-m-d');
       
           $supplierledger = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Return',
          'VDate'          =>  $date,
          'COAID'          =>  $sup_coa->HeadCode,
          'Narration'      =>  'Supplier Return to .'.$supinfo->supplier_name,
          'Debit'          =>  $total,
          'Credit'         =>  0,
          'IsPosted'       =>  1,
          'CreateBy'       =>  $receive_by,
          'CreateDate'     =>  $receive_date,
          'IsAppove'       =>  1
        ); 

        
        

        $quantity           = $this->input->post('product_quantity',TRUE);
        $available_quantity = $this->input->post('available_quantity',TRUE);
        $cartoon            = $this->input->post('cartoon',TRUE);
        $rate               = $this->input->post('product_rate',TRUE);
        $p_id               = $this->input->post('product_id',TRUE);
        $total_amount       = $this->input->post('total_price',TRUE);
        $discount_rate      = $this->input->post('discount',TRUE);
        $soldqty            = $this->input->post('ret_qty',TRUE);

        $pdid = $this->input->post('purchase_detail_id');


        if (is_array($p_id))
            for ($i = 0; $i < count($p_id); $i++) {
                $cartoon_quantity = $cartoon[$i];
                $product_quantity = $quantity[$i];
                $product_rate     = $rate[$i];
                $product_id       = $p_id[$i];
                $sqty             = $soldqty[$i];
                $total_price      = $total_amount[$i];
                $discount         = $discount_rate[$i];
                $detais_id        = $pdid[$i];

                $data1 = array(
                    'purchase_detail_id' => $detais_id,
                    'purchase_id'        => $purchase_id,
                    'product_id'         => $product_id,
                    'quantity'           => -$product_quantity,
                    'rate'               => $product_rate,
                    'discount'           => -is_numeric($discount),
                    'total_amount'       => -$total_price,
                    'status'             => 1
                );


                $returns = array(
                    'return_id'    => $this->generator(15),
                    'purchase_id'  => $purchase_id,
                    'product_id'   => $product_id,
                    'supplier_id'  => $this->input->post('supplier_id',TRUE),
                    'ret_qty'      => $product_quantity,
                    'byy_qty'      => $sqty,
                    'date_purchase'=> $this->input->post('return_date',TRUE),
                    'date_return'  => $date,
                    'product_rate' => $product_rate,
                    'deduction'    => $discount,
                    'total_deduct' => $this->input->post('total_discount',TRUE),
                    'total_ret_amount' => $total_price,
                    'net_total_amount' => $this->input->post('grand_total_price',TRUE),
                    'reason'       => $this->input->post('details',TRUE),
                    'usablity'     => $this->input->post('radio',TRUE)
                );


                $this->db->insert('product_purchase_details', $data1);

                $this->db->insert('product_return', $returns);
            }

            $this->db->insert('acc_transaction', $supplierledger);

        return $purchase_id;
    }

    // return list count
    public function return_list_count() {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

///start  return list
    public function return_list($perpage, $page) {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // date between search return list  invoice 
    public function return_dateWise_invoice($from_date, $to_date, $perpage, $page) {
        $dateRange = "a.date_return BETWEEN '$from_date' AND '$to_date'";

        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        return $query->result_array();
    }

// supplier return list
    public function supplier_return_list($perpage, $page) {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->group_by('a.purchase_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

// date between search return list  supplier/purchase 
    public function return_dateWise_supplier($from_date, $to_date, $perpage, $page) {
        $dateRange = "a.date_return BETWEEN '$from_date' AND '$to_date'";

        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->group_by('a.purchase_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function retrieve_invoice_html_data($invoice_id) {
        $this->db->select('c.total_ret_amount,
						c.*,
						b.*,
						d.product_id,
						d.product_name,
						d.product_details,
						d.product_model');
        $this->db->from('product_return c');
        $this->db->join('customer_information b', 'b.customer_id = c.customer_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('c.invoice_id', $invoice_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // supplier return html data 
    public function supplier_return_html_data($purchase_id) {
        $this->db->select('c.total_ret_amount,
						c.*,
                        c.product_rate as price,
						b.*,
						d.product_id,
						d.product_name,
						d.product_details,
						d.product_model');
        $this->db->from('product_return c');
        $this->db->join('supplier_information b', 'b.supplier_id = c.supplier_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('c.purchase_id', $purchase_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function generator($lenth) {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }

    // wastage return list bellow 
    public function wastage_return_list_count() {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 3);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

// supplier list count
    public function supplier_return_list_count() {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 2);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

///start  return list
    public function wastage_return_list($perpage, $page) {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 3);
        $this->db->group_by('a.invoice_id', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /////////// supplier returns form data
    public function supplier_return($purchase_id) {
        $this->db->select('c.*,a.*,b.*,a.product_id,d.product_name,d.product_model,e.supplier_id');
        $this->db->from('product_purchase c');
        $this->db->join('product_purchase_details a', 'a.purchase_id = c.purchase_id');
        $this->db->join('product_information d', 'd.product_id = a.product_id');
        $this->db->join('supplier_product e', 'e.product_id = d.product_id');
        $this->db->join('supplier_information b', 'b.supplier_id = e.supplier_id');

        $this->db->where('c.purchase_id', $purchase_id);
        $this->db->group_by('d.product_id', 'desc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

// return delete with invoice id  
    public function returninvoice_delete($invoice_id = null) {
        $this->db->where('invoice_id', $invoice_id)
                ->delete('product_return');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

// return delete with purchase id
    public function return_purchase_delete($purchase_id = null) {
        $this->db->where('purchase_id', $purchase_id)
                ->delete('product_return');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
// pdf invoice return list
        public function pdf_invoice_return_list() {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->group_by('a.invoice_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // supplier return list pdf
        public function pdf_supplier_return_list() {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->group_by('a.purchase_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
