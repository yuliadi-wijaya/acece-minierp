<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function retrieve_company($id) {
       $this->db->select('a.first_name,a.last_name,a.address,a.phone,a.company_name,b.username as email');
        $this->db->from('users a');
        $this->db->join('user_login b','b.user_id=a.user_id','left');
        $this->db->where('a.user_id',$id);
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    
       public function user_entry($data) {
        $users = array(
            'user_id'     => $data['user_id'],
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'company_name'=> $data['company_name'],
            'address'     => $data['address'],
            'phone'       => $data['phone'],
            'status'      => $data['status'],
        );
        $this->db->insert('users', $users);


        $user_login = array(
            'user_id'    => $data['user_id'],
            'username'   => $data['email'],
            'password'   => $data['password'],
            'user_type'  => $data['user_type'],
            'status'     => $data['status'],
        );

        $this->db->insert('user_login', $user_login);
        return true;
       }


public function product_list($limit = null,$start = null){
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false; 
}


public function searchproduct_list(){
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->order_by('product_name','asc');
        $this->db->limit(30);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false; 
}

 public function invoice_list($limit = null,$start = null) {
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->order_by('a.invoice', 'desc');
       $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

   public function get_total_product($product_id) {
        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('product_purchase_details a');
        $this->db->where('a.product_id', $product_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $total_sale = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where(array('product_id' => $product_id, 'status' => 1));
        $product_information = $this->db->get()->row();

        $available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);
        $serial = array();
        $serial = array_map('trim', explode(',', $product_information->serial_no));

        $tablecolumn = $this->db->list_fields('tax_collection');
               $num_column = count($tablecolumn)-4;
  $taxfield='';
  $taxvar = [];
   for($i=0;$i<$num_column;$i++){
    $taxfield = 'tax'.$i;
    $data2[$taxfield] = $product_information->$taxfield;
    $taxvar[$i]       = $product_information->$taxfield;
    $data2['taxdta']  = $taxvar;
    
   }
            $data2['stock']          = $available_quantity;
            $data2['product_name']   = $product_information->product_name;
            $data2['product_id']     = $product_information->product_id;
            $data2['serial']         = $serial;
            $data2['price']          = $product_information->price;
            $data2['unit']           = $product_information->unit;
        

        return $data2;
    }

    // Category List
    public function category_list($limit = null,$start = null){
        $this->db->select('*');
        $this->db->from('product_category');
        $this->db->order_by('category_name','asc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;  
    }
// category Entry
     public function category_create($data = array()){
        return $this->db->insert('product_category', $data);

    }
    //edit data
    public function category_edit_data($id){
        $this->db->select('*');
        $this->db->from('product_category');
        $this->db->where('category_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false; 
    }


        public function product_editdata($id){
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('product_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $product_information = $query->row();


 $txfields = $this->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();


   $tablecolumn = $this->db->list_fields('tax_collection');
  $num_column = count($tablecolumn)-4;
  $taxfield='';
  $taxvar = [];
  if($num_column > 0){
   for($i=0;$i<$num_column;$i++){
    $taxfield = 'tax'.$i;
    $taxvar[$i]['tax']  = (!empty($product_information->$taxfield)?$product_information->$taxfield:0);
    $taxvar[$i]['fieldname']  = $txfields[$i]['tax_name'];
    $data2['taxdta'] = (!empty($taxvar)?$taxvar:0);
    
   }
  
}


$data2['product_data'] = $product_information;
return $data2;
        }
        return false; 
       }


        public function productsupplier_editdata($id){
        $this->db->select('*');
        $this->db->from('supplier_product');
        $this->db->where('product_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false; 
       }

    public function customer_edit_data($id){
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->where('customer_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false; 
    }

    public function update_category($data = []){ 
        return $this->db->where('category_id',$data['category_id'])
            ->update('product_category',$data); 
    } 


    public function delete_category($id = null)
    {
        $this->db->where('category_id',$id)
            ->delete('product_category');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 


    public function delete_product($id = null)
    {
        $this->db->where('product_id',$id)
            ->delete('product_information');
    $this->db->where('product_id',$id)
            ->delete('supplier_product');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 


public function customer_list($limit=null,$start=null){

 $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance ");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->group_by('a.customer_id');
        $this->db->order_by('a.customer_name','asc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
}

public function total_customer(){

        $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance ");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->group_by('a.customer_id');
        $this->db->order_by('a.customer_name','asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
}

public function searchcustomer_list($query){

 $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance ");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->like('a.customer_name',$query,'after');
        $this->db->or_like('a.customer_mobile',$query,'after');
        $this->db->group_by('a.customer_id');
        $this->db->limit(20);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
}

// customer Entry
     public function customer_create($data = array()){
        return $this->db->insert('customer_information', $data);

    }

    // product Entry
     public function product_create($data = array()){
        return $this->db->insert('product_information', $data);

    }

     // product Update
     public function product_update($data = array()){
           $this->db->where('product_id', $data['product_id']);
          $this->db->update('product_information',$data);
          return TRUE;

    }

// supplier list for drop down
    public function supplier_list($limit = null,$start = null){
         $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
         $this->db->from('supplier_information a');
         $this->db->join('acc_coa b','a.supplier_id = b.supplier_id','left');
         $this->db->group_by('a.supplier_id');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false; 
}


   //Count supplier
    public function supplier_entry($data = array()) {

        $this->db->select('*');
        $this->db->from('supplier_information');
        $this->db->where('supplier_name', $data['supplier_name']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {

            $this->db->insert('supplier_information', $data);
            //Data is sending for syncronizing

            $this->db->select('*');
            $this->db->from('supplier_information');
            $this->db->where('status', 1);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_product[] = array('label' => $row->supplier_name, 'value' => $row->supplier_id);
            }
            $cache_file = './my-assets/js/admin_js/json/supplier.json';
            $productList = json_encode($json_product);
            file_put_contents($cache_file, $productList);
            return TRUE;
        }
    }

    //Supplier Previous balance adjustment
   public function previous_balance_add($balance, $supplier_id,$c_acc,$supplier_name) {
        $this->load->library('auth');
        $transaction_id = $this->auth->generator(10);
    $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$c_acc)->get()->row();
    $supplier_headcode = $coainfo->HeadCode;

             $cosdr = array(
      'VNo'            =>  $transaction_id,
      'Vtype'          =>  'PR Balance',
      'VDate'          =>  date("Y-m-d"),
      'COAID'          =>  $supplier_headcode,
      'Narration'      =>  'supplier debit For '.$supplier_name,
      'Debit'          =>  0,
      'Credit'         =>  $balance,
      'IsPosted'       => 1,
      'CreateBy'       => $this->session->userdata('user_id'),
      'CreateDate'     => date('Y-m-d H:i:s'),
      'IsAppove'       => 1
    );
       $inventory = array(
      'VNo'            =>  $transaction_id,
      'Vtype'          =>  'PR Balance',
      'VDate'          =>  date("Y-m-d"),
      'COAID'          =>  10107,
      'Narration'      =>  'Inventory credit For  '.$supplier_name,
      'Debit'          =>  $balance,
      'Credit'         =>  0,
      'IsPosted'       => 1,
      'CreateBy'       => $this->session->userdata('user_id'),
      'CreateDate'     => date('Y-m-d H:i:s'),
      'IsAppove'       => 1
    ); 

       
        if(!empty($balance)){
           $this->db->insert('acc_transaction', $cosdr); 
           $this->db->insert('acc_transaction', $inventory); 
        }
    }


   public function delete_supplier($supplier_id = null)
    {
        $supplier_info = $this->db->select('supplier_name')->from('supplier_information')->where('supplier_id',$supplier_id)->get()->row();
        $supplier_head = $supplier_id.'-'.$supplier_info->supplier_name;
        $this->db->where('HeadName', $supplier_head);
        $this->db->delete('acc_coa');
        $this->db->where('supplier_id', $supplier_id);
        $this->db->delete('supplier_information');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

        //edit data
    public function supplier_edit_data($id){
        $this->db->select('*');
        $this->db->from('supplier_information');
        $this->db->where('supplier_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false; 
    }


       public function update_supplier($data, $supplier_id) {
        $this->db->where('supplier_id', $supplier_id);
        $this->db->update('supplier_information', $data);
        $this->db->select('*');
        $this->db->from('supplier_information');
        $this->db->where('status', 1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_product[] = array('label' => $row->supplier_name, 'value' => $row->supplier_id);
        }
        $cache_file = './my-assets/js/admin_js/json/supplier.json';
        $productList = json_encode($json_product);
        file_put_contents($cache_file, $productList);
        return true;
    }
  //update customer
     public function update_customer($data, $customer_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->update('customer_information', $data);
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->where('status', 1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_customer[] = array('label' => $row->customer_name, 'value' => $row->customer_id);
        }
        $cache_file   = './my-assets/js/admin_js/json/customer.json';
        $customerlist = json_encode($json_customer);
        file_put_contents($cache_file, $customerlist);
        return true;
    }

    // unit add
        public function insert_unit($data) {
        $this->db->select('*');
        $this->db->from('units');
        $this->db->where('status', 1);
        $this->db->where('unit_name', $data['unit_name']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->insert('units', $data);
            return TRUE;
        }
    }
//unit list
     public function unit_list($limit = null,$start = null) {
        $this->db->select('*');
        $this->db->from('units');
        $this->db->where('status', 1);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }


        public function unit_edit_data($id){
        $this->db->select('*');
        $this->db->from('units');
        $this->db->where('unit_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false; 
    }

        public function delete_unit($id = null)
    {
        $this->db->where('unit_id',$id)
            ->delete('units');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

       public function update_unit($data = []){ 
        return $this->db->where('unit_id',$data['unit_id'])
            ->update('units',$data); 
    } 

    // supplier advance
 
      public function supplier_advance_insert(){
        return true;

    }


     public function suppliers_ledger($supplier_id, $start, $end,$limit=null,$limit_start=null) {
        $this->db->select('a.*,b.HeadName');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b','a.COAID=b.HeadCode');
        $this->db->where('b.supplier_id', $supplier_id);
        $this->db->where(array('VDate >=' => $start, 'VDate <=' => $end));
        $this->db->where('a.IsAppove',1);
        $this->db->order_by('a.VDate','desc');
        $this->db->limit($limit, $limit_start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // supplier search 
    public function supplier_seach($search = null){
        $this->db->select('*');
        $this->db->from('supplier_information');
        $this->db->like('supplier_name', $search,'both');
        $this->db->or_like('mobile', $search,'both');
        $this->db->order_by('supplier_name','asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;

    }

    // product stock 
        public function product_stock($limit=null,$start=null) {
        $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->group_by('a.product_id');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
         $result = $query->result_array();
         $stock = [];
         $i = 0;
         foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];
         

             $stock[$i]['stock_qty']     = (!empty($stokqty)?$stokqty:0);
             $stock[$i]['product_id']    = $stockproduct['product_id'];
             $stock[$i]['product_name']  = $stockproduct['product_name'];
             $stock[$i]['product_model'] = $stockproduct['product_model'];
             $stock[$i]['unit']          = $stockproduct['unit'];
             $stock[$i]['price']         = $stockproduct['price'];
             $stock[$i]['totalPurchaseQnty'] = (!empty($stockproduct['totalBuyQnty'])?$stockproduct['totalBuyQnty']:0);
             $stock[$i]['totalSalesQnty'] = (!empty($stockproduct['totalSalesQnty'])?$stockproduct['totalSalesQnty']:0);
             $stock[$i]['total_sale_price'] = (!empty($stokqty)?$stokqty*$stockproduct['price']:0);
         
             $i++;
         }
        return $stock;
    }


     public function supplier_wise_stock($supplier_id = null) {

    $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,b.supplier_id,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->join('supplier_product b','b.product_id = a.product_id','left');
        $this->db->where('b.supplier_id',$supplier_id);
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
         $result = $query->result_array();
         $stock = [];
         $i = 0;
         foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];
         

             $stock[$i]['stock_qty']     = (!empty($stokqty)?$stokqty:0);
             $stock[$i]['product_id']    = $stockproduct['product_id'];
             $stock[$i]['product_name']  = $stockproduct['product_name'];
             $stock[$i]['product_model'] = $stockproduct['product_model'];
             $stock[$i]['unit']          = $stockproduct['unit'];
             $stock[$i]['price']         = (!empty($stockproduct['price'])?$stockproduct['price']:0);
             $stock[$i]['totalPurchaseQnty'] = (!empty($stockproduct['totalBuyQnty'])?$stockproduct['totalBuyQnty']:0);
             $stock[$i]['totalSalesQnty'] = (!empty($stockproduct['totalSalesQnty'])?$stockproduct['totalSalesQnty']:0);
             $stock[$i]['supplier_id']   = $stockproduct['supplier_id'];
             $stock[$i]['total_sale_price'] =(!empty($stokqty)?$stokqty*$stockproduct['price']:0);
         
             $i++;
         }
        return $stock;

    }

      public function stock_report_product($product_id) {
      $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->where('product_id',$product_id);
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
         $result = $query->result_array();
         $stock = [];
         $i = 0;
         foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];
         

             $stock[$i]['stock_qty']     = (!empty($stokqty)?$stokqty:0);
             $stock[$i]['product_id']    = $stockproduct['product_id'];
             $stock[$i]['product_name']  = $stockproduct['product_name'];
             $stock[$i]['product_model'] = $stockproduct['product_model'];
             $stock[$i]['unit']          = $stockproduct['unit'];
             $stock[$i]['price']         = $stockproduct['price'];
             $stock[$i]['totalPurchaseQnty'] = (!empty($stockproduct['totalBuyQnty'])?$stockproduct['totalBuyQnty']:0) ;
             $stock[$i]['totalSalesQnty'] = (!empty($stockproduct['totalSalesQnty'])?$stockproduct['totalSalesQnty']:0);
             $stock[$i]['total_sale_price'] = (!empty($stokqty)?$stokqty*$stockproduct['price']:0);
         
             $i++;
         }
        return $stock;

    }


     public function product_info_bybarcode($product_id){
        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('product_purchase_details a');
        $this->db->where('a.product_id', $product_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $total_sale = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where(array('product_id' => $product_id, 'status' => 1));
        $product_information = $this->db->get()->row();

        $available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);
        $serial = array();
        $serial = array_map('trim', explode(',', $product_information->serial_no));
        $tablecolumn = $this->db->list_fields('tax_collection');
               $num_column = count($tablecolumn)-4;
  $taxfield='';
  $taxvar = [];
  if($num_column > 0){
   for($i=0;$i<$num_column;$i++){
    $taxfield = 'tax'.$i;
    $data2[$taxfield] = (!empty($product_information->$taxfield)?$product_information->$taxfield:0);
    $taxvar[$i]       = (!empty($product_information->$taxfield)?$product_information->$taxfield:0);
    $data2['taxdta']  = (!empty($taxvar)?$taxvar:0);
    
   }
}



        
            $data2['stock']          = $available_quantity;
            $data2['product_name']   = $product_information->product_name;
            $data2['product_model']   = $product_information->product_model;
            $data2['product_id']     = $product_information->product_id;
            $data2['serial']         = $serial;
            $data2['price']          = $product_information->price;
            $data2['unit']           = $product_information->unit;
        

        return $data2;
}

public function taxfield(){
        $this->db->select('tax_name as fieldname');
        $this->db->from('tax_settings');
        $this->db->order_by('id','asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false; 
}

public function credit_customer_list($limit=null,$start=null) {
       $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->group_by('a.customer_id');
        $this->db->order_by('a.customer_name','asc');
        $this->db->having('customer_balance > 0');
        $this->db->limit($limit, $start); 
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function countcredit_customer_list() {
       $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->group_by('a.customer_id');
        $this->db->order_by('a.customer_name','asc');
        $this->db->having('customer_balance > 0');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
    
         public function paid_customer_list($limit=null,$start=null) {
         $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->group_by('a.customer_id');
        $this->db->order_by('a.customer_name','asc');
        $this->db->having('customer_balance <= 0');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;

    }
    

           public function countpaid_customer_list() {
         $this->db->select("a.*,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as customer_balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b','a.customer_id = b.customer_id','left');
        $this->db->group_by('a.customer_id');
        $this->db->order_by('a.customer_name','asc');
        $this->db->having('customer_balance <= 0');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;

    }
  public function purchase_entry() {
        $purchase_id = date('YmdHis');
        $supplier_id = $this->input->post('supplier_id');
        $supinfo     = $this->db->select('*')->from('supplier_information')->where('supplier_id',$supplier_id)->get()->row();

        $sup_head    = $supinfo->supplier_id.'-'.$supinfo->supplier_name;
        $sup_coa     = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();

       $receive_by   = 1;
       $receive_date = date('Y-m-d');
       $createdate   = date('Y-m-d H:i:s');

        

    $data = array(
        'purchase_id'        => $purchase_id,
        'chalan_no'          => $this->input->post('chalan_no'),
        'supplier_id'        => $this->input->post('supplier_id'),
        'grand_total_amount' => $this->input->post('grand_total_price'),
        'total_discount'     => $this->input->post('total_discount'),
        'purchase_date'      => $this->input->post('purchase_date'),
        'purchase_details'   => 'From app',
        'status'             => 1,
        'bank_id'            => null,
        'payment_type'       => 1,
    );
 
        //Supplier Credit
        $purchasecoatran = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Purchase',
          'VDate'          =>  $this->input->post('purchase_date'),
          'COAID'          =>  $sup_coa->HeadCode,
          'Narration'      =>  'Supplier .'.$supinfo->supplier_name,
          'Debit'          =>  0,
          'Credit'         =>  $this->input->post('grand_total_price'),
          'IsPosted'       =>  1,
          'CreateBy'       =>  0,
          'CreateDate'     =>  $createdate,
          'IsAppove'       =>  1
        ); 

          ///Inventory Debit
           $coscr = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Purchase',
          'VDate'          =>  $this->input->post('purchase_date'),
          'COAID'          =>  10107,
          'Narration'      =>  'Inventory Debit For Supplier '.$supinfo->supplier_name,
          'Debit'          =>  $this->input->post('grand_total_price'),
          'Credit'         =>  0,
          'IsPosted'       => 1,
          'CreateBy'       => 0,
          'CreateDate'     => $createdate,
          'IsAppove'       => 1
        ); 


  

       // Expense for company
         $expense = array(
      'VNo'            => $purchase_id,
      'Vtype'          => 'Purchase',
      'VDate'          => $this->input->post('purchase_date'),
      'COAID'          => 402,
      'Narration'      => 'Company Credit For  '.$supinfo->supplier_name,
      'Debit'          => $this->input->post('grand_total_price'),
      'Credit'         => 0,//purchase price asbe
      'IsPosted'       => 1,
      'CreateBy'       => 1,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 

             $cashinhand = array(
      'VNo'            =>  $purchase_id,
      'Vtype'          =>  'Purchase',
      'VDate'          =>  $this->input->post('purchase_date'),
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand For Supplier '.$supinfo->supplier_name,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('grand_total_price'),
      'IsPosted'       =>  1,
      'CreateBy'       =>  $receive_by,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 
            

     $supplierdebit = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Purchase',
          'VDate'          =>  $this->input->post('purchase_date'),
          'COAID'          =>  $sup_coa->HeadCode,
          'Narration'      =>  'Supplier .'.$supinfo->supplier_name,
          'Debit'          =>  $this->input->post('grand_total_price'),
          'Credit'         =>  0,
          'IsPosted'       =>  1,
          'CreateBy'       =>  0,
          'CreateDate'     =>  $createdate,
          'IsAppove'       =>  1
        ); 



     if($this->db->insert('product_purchase', $data)){

        $this->db->insert('acc_transaction',$coscr);
        $this->db->insert('acc_transaction',$purchasecoatran);  
        $this->db->insert('acc_transaction',$expense);
        
        $this->db->insert('acc_transaction',$cashinhand);
        $this->db->insert('acc_transaction',$supplierdebit);  


        $detailsinfo  = $this->input->post('detailsinfo');
        $saledetails     = json_decode($detailsinfo,true);
           
        $products[] = '';
        $quant[]    = '';
        $rate[]     = '';
        $total[]    = '';
        $i=0;
        foreach ($saledetails as $key => $value) {
             $products[$i]   = $saledetails[$i]['product_id'];
              $quant[$i]     = $saledetails[$i]['product_quantity'];
               $rate[$i]     = $saledetails[$i]['product_rate'];
               
        $i++;}
        $p_id     = $products;
        $quantity = $quant;
        $rate     = $rate;
        
        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate = $rate[$i];
            $product_id = $p_id[$i];
            $total_price = $quantity[$i]*$rate[$i];
            $data1 = array(
                'purchase_detail_id' => $this->generator(15),
                'purchase_id'        => $purchase_id,
                'product_id'         => $product_id,
                'quantity'           => $product_quantity,
                'rate'               => $product_rate,
                'total_amount'       => $total_price,
                'discount'           => 0,
                'status'             => 1
            );
           

          
                $this->db->insert('product_purchase_details', $data1);
            
        }

        return true;
    }else{
        return false;
    }
}


     // Random Id generator
    public function generator($lenth) {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 61);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }

//purchase List
    public function purchase_list($limit = null, $start = null) {
        $this->db->select('a.*,b.supplier_name');
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id','left');
        $this->db->order_by('a.purchase_date', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }



        public function search_purchase_list($startdate,$enddate) {
        $this->db->select('a.*,b.supplier_name');
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id','left');
        $this->db->where('a.purchase_date BETWEEN "'.$startdate. '" and "'.$enddate.'"');
        $this->db->order_by('a.purchase_date', 'desc');
        $query = $this->db->get();

        $last_query = $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function search_purchase_list_byinvoice($invoice_id){
         $this->db->select('a.*,b.supplier_name');
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id','left');
        $this->db->where('a.chalan_no',$invoice_id);
        $this->db->order_by('a.purchase_date', 'desc');
        $query = $this->db->get();

        $last_query = $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

  public function delete_customer($id){
      $customer_info = $this->db->select('customer_name')->from('customer_information')->where('customer_id',$id)->get()->row();
        $customer_head = $id.'-'.$customer_info->customer_name;
        $this->db->where('customer_id', $id);
        $this->db->delete('customer_information');
         $this->db->where('HeadName', $customer_head);
        $this->db->delete('acc_coa');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }

}

public function delete_purchase($id = null){  

   $this->db->where('purchase_id',$id)
            ->delete('product_purchase');//VNo

   $this->db->where('purchase_id',$id)
            ->delete('product_purchase_details');
    $this->db->where('VNo',$id)
            ->delete('acc_transaction');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
}

/*
|_______________________________________________________
|
|PURCHASE UPDATE DATA
|_______________________________________________________
*/
  public function purchase_update() {
        $purchase_id = $this->input->post('purchase_id');
        $supplier_id = $this->input->post('supplier_id');
        $supinfo     = $this->db->select('*')->from('supplier_information')->where('supplier_id',$supplier_id)->get()->row();

        $sup_head    = $supinfo->supplier_id.'-'.$supinfo->supplier_name;
        $sup_coa     = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();

       $receive_by   = $this->input->post('creatby');
       $receive_date = date('Y-m-d');
       $createdate   = date('Y-m-d H:i:s');

        

    $data = array(
         'purchase_id'        => $purchase_id,
        'chalan_no'          => $this->input->post('chalan_no'),
        'supplier_id'        => $this->input->post('supplier_id'),
        'grand_total_amount' => $this->input->post('grand_total_price'),
        'total_discount'     => $this->input->post('total_discount'),
        'purchase_date'      => $this->input->post('purchase_date'),
        'purchase_details'   => 'From app',
        'status'             => 1,
        'bank_id'            => null,
        'payment_type'       => 1,
    );
        //Supplier Credit
        $purchasecoatran = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Purchase',
          'VDate'          =>  $this->input->post('purchase_date'),
          'COAID'          =>  $sup_coa->HeadCode,
          'Narration'      =>  'Supplier .'.$supinfo->supplier_name,
          'Debit'          =>  0,
          'Credit'         =>  $this->input->post('grand_total_price'),
          'IsPosted'       =>  1,
          'CreateBy'       =>  $receive_by,
          'CreateDate'     =>  $receive_date,
          'IsAppove'       =>  1
        ); 
          ///Inventory Debit
           $coscr = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Purchase',
          'VDate'          =>  $this->input->post('purchase_date'),
          'COAID'          =>  10107,
          'Narration'      =>  'Inventory Debit For Supplier '.$supinfo->supplier_name,
          'Debit'          =>  $this->input->post('grand_total_price'),
          'Credit'         =>  0,//purchase price asbe
          'IsPosted'       => 1,
          'CreateBy'       => $receive_by,
          'CreateDate'     => $createdate,
          'IsAppove'       => 1
        ); 

     

       // Expense for company
         $expense = array(
      'VNo'            => $purchase_id,
      'Vtype'          => 'Purchase',
      'VDate'          => $this->input->post('purchase_date'),
      'COAID'          => 402,
      'Narration'      => 'Company Credit For  '.$supinfo->supplier_name,
      'Debit'          => $this->input->post('grand_total_price'),
      'Credit'         => 0,//purchase price asbe
      'IsPosted'       => 1,
      'CreateBy'       => $receive_by,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
             $cashinhand = array(
      'VNo'            =>  $purchase_id,
      'Vtype'          =>  'Purchase',
      'VDate'          =>  $this->input->post('purchase_date'),
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand For Supplier '.$supinfo->supplier_name,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('grand_total_price'),
      'IsPosted'       =>  1,
      'CreateBy'       =>  $receive_by,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

     $supplierdebit = array(
          'VNo'            =>  $purchase_id,
          'Vtype'          =>  'Purchase',
          'VDate'          =>  $this->input->post('purchase_date'),
          'COAID'          =>  $sup_coa->HeadCode,
          'Narration'      =>  'Supplier .'.$supinfo->supplier_name,
          'Debit'          =>  $this->input->post('grand_total_price'),
          'Credit'         =>  0,
          'IsPosted'       =>  1,
          'CreateBy'       =>  $receive_by,
          'CreateDate'     =>  $receive_date,
          'IsAppove'       =>  1
        ); 
             
        

     if($this->update_purchase($data)){

        $this->db->where('VNo', $purchase_id);
        $this->db->delete('acc_transaction');
        $this->db->where('purchase_id', $purchase_id);
        $this->db->delete('product_purchase_details');

        $this->db->insert('acc_transaction',$coscr);
        $this->db->insert('acc_transaction',$purchasecoatran);  
        $this->db->insert('acc_transaction',$expense);
        
        $this->db->insert('acc_transaction',$cashinhand);
        $this->db->insert('acc_transaction',$supplierdebit);  


       $detailsinfo  = $this->input->post('detailsinfo');
        $saledetails     = json_decode($detailsinfo,true);
           
        $products[] = '';
        $quant[]    = '';
        $rate[]     = '';
        $total[]    = '';
        $i=0;
        foreach ($saledetails as $key => $value) {
             $products[$i]   = $saledetails[$i]['product_id'];
              $quant[$i]     = $saledetails[$i]['product_quantity'];
               $rate[$i]     = $saledetails[$i]['product_rate'];
               $total[$i]    = $saledetails[$i]['total_price'];
        $i++;}
        $p_id     = $products;
        $quantity = $quant;
        $rate     = $rate;
        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate = $rate[$i];
            $product_id = $p_id[$i];
            $total_price = $quantity[$i]*$rate[$i];
            $data1 = array(
                'purchase_detail_id' => $this->generator(15),
                'purchase_id'        => $purchase_id,
                'product_id'         => $product_id,
                'quantity'           => $product_quantity,
                'rate'               => $product_rate,
                'total_amount'       => $total_price,
                'discount'           => 0,
                'status'             => 1
            );
        

          
                $this->db->insert('product_purchase_details', $data1);
            
        }

         $message = 'Mr.'.$supinfo->supplier_name.',
        '.'You have Sold '.$this->input->post('grand_total_price');
       

        return true;
    }else{
        return false;
    }
}

       public function update_purchase($data = []){ 
        return $this->db->where('purchase_id',$data['purchase_id'])
            ->update('product_purchase',$data); 
    } 
    
    
   //purchase details data
     public function retrieve_purchase_editdata($purchase_id) {
        $this->db->select('a.*,
                        b.*,
                        c.product_id,
                        c.product_name,
                        c.product_model,
                        d.supplier_id,
                        d.supplier_name'
        );
        $this->db->from('product_purchase a');
        $this->db->join('product_purchase_details b', 'b.purchase_id =a.purchase_id');
        $this->db->join('product_information c', 'c.product_id =b.product_id');
        $this->db->join('supplier_information d', 'd.supplier_id = a.supplier_id');
        $this->db->where('a.purchase_id', $purchase_id);
        $this->db->order_by('a.purchase_details', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    
    
     public  function get_general_ledger(){
        $this->db->select('*');
        $this->db->from('acc_coa');
        $this->db->where('IsGL',1);
        $this->db->order_by('HeadName', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    
      public function general_led_get($Headid){
        $sql="SELECT * FROM acc_coa WHERE HeadCode='$Headid' ";
        $query = $this->db->query($sql);
        $rs=$query->row();


        $sql="SELECT * FROM acc_coa WHERE IsTransaction=1 AND PHeadName='".$rs->HeadName."' ORDER BY HeadName";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function general_led_report_headname($cmbGLCode){
        $this->db->select('*');
        $this->db->from('acc_coa');
        $this->db->where('HeadCode',$cmbGLCode);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function general_led_report_headname2($cmbGLCode,$cmbCode,$dtpFromDate,$dtpToDate,$chkIsTransction){

            if($chkIsTransction){
                
                $this->db->select('acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType');
                $this->db->from('acc_transaction');
                $this->db->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode', 'left');
                $this->db->where('acc_transaction.IsAppove',1);
                $this->db->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
                $this->db->where('acc_transaction.COAID',$cmbCode);
                $query = $this->db->get();
                return $query->result();
            }
            else{
               
                $this->db->select('acc_transaction.COAID,acc_transaction.Debit, acc_transaction.Credit,acc_coa.HeadName,acc_transaction.IsAppove, acc_coa.PHeadName, acc_coa.HeadType');
                $this->db->from('acc_transaction');
                $this->db->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode', 'left');
                $this->db->where('acc_transaction.IsAppove',1);
                $this->db->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
                $this->db->where('acc_transaction.COAID',$cmbCode);
               
                $query = $this->db->get();
                return $query->result();
            }

    }
   

         public function general_led_report_prebalance($cmbCode,$dtpFromDate){
                $this->db->select('sum(acc_transaction.Debit) as predebit, sum(acc_transaction.Credit) as precredit');
                $this->db->from('acc_transaction');
                $this->db->where('acc_transaction.IsAppove',1);
                $this->db->where('VDate < ',$dtpFromDate);
                $this->db->where('acc_transaction.COAID',$cmbCode);
                $query = $this->db->get()->row();
                return $balance=$query->predebit - $query->precredit;

    }
    
           public function profit_loss_serach(){
       
        $sql="SELECT * FROM acc_coa WHERE acc_coa.HeadType='I'";
        $sql1 = $this->db->query($sql);

        $sql="SELECT * FROM acc_coa WHERE acc_coa.HeadType='E'";
        $sql2 = $this->db->query($sql);
        
        $data = array(
          'oResultAsset'     => $sql1->result(),
          'oResultLiability' => $sql2->result(),
        );
        return $data;
    }
    
     public function retrieve_product_search_sales_report($product_id,$start_date, $end_date) {
       
        $this->db->select("a.*,b.product_name,b.product_model,c.date,d.customer_name,c.total_amount");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('c.date >=', $start_date);
        $this->db->where('c.date <=', $end_date);
        $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;

        
    }
    
    
      public function retrieve_product_search_purchase_report($product_id,$start_date, $end_date) {
        
        $this->db->select("a.*,b.product_name,b.product_model,c.purchase_date,d.supplier_name,c.grand_total_amount,c.chalan_no");
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id','left');
        $this->db->join('product_purchase c', 'c.purchase_id = a.purchase_id','left');
        $this->db->join('supplier_information d', 'd.supplier_id = c.supplier_id','left');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('c.purchase_date >=', $start_date);
        $this->db->where('c.purchase_date <=', $end_date);
        $this->db->order_by('c.purchase_date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;

        
    }
    
      public function retrieve_dateWise_DueReports($from_date, $to_date) {
       
        $this->db->select("a.*,b.*,c.*,CONCAT_WS(' ', d.first_name, d.last_name) AS saller");
        $this->db->from('invoice a');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id','left');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
        $this->db->join('users d', 'd.user_id = a.sales_by','left');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->where('c.due_amount >',0);
         $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }



     public function search_invoice($query,$limit = null,$start = null) {
        $this->db->select('a.*,b.*');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->like('a.invoice',$query,'after');
        $this->db->or_like('a.date',$query,'after');
        $this->db->or_like('b.customer_name',$query,'after');
        $this->db->or_like('b.customer_mobile',$query,'after');
        $this->db->order_by('a.date', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


         public function count_search_invoice($query) {
        $this->db->select('a.*,b.*');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
        $this->db->like('a.invoice',$query,'after');
        $this->db->or_like('a.date',$query,'after');
        $this->db->or_like('b.customer_name',$query,'after');
        $this->db->or_like('b.customer_mobile',$query,'after');
        $this->db->order_by('a.date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }



     public function search_product($query,$limit = null,$start = null) {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->like('product_name',$query,'after');
        $this->db->or_like('product_model',$query,'after');
        $this->db->or_like('price',$query,'after');
        $this->db->or_like('unit',$query,'after');
        $this->db->order_by('product_name', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }



     public function count_search_product($query) {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->like('product_name',$query,'after');
        $this->db->or_like('product_model',$query,'after');
        $this->db->or_like('price',$query,'after');
        $this->db->or_like('unit',$query,'after');
        $this->db->order_by('product_name', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
   


public function supplier_productprice($supplier_id,$product_id){
    return $date = $this->db->select('supplier_price')->from('supplier_product')->where('supplier_id',$supplier_id)->where('product_id',$product_id)->get()->row()->supplier_price;
}


 public function supplier_products($supplier_id) {
        $this->db->select('a.*,b.supplier_price');
        $this->db->from('product_information a');
        $this->db->join('supplier_product b', 'b.product_id = a.product_id','left');
        $this->db->where('b.supplier_id',$supplier_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    //paymentcheckout
    public function insert_paymentcheckout($data){
    
         $this->db->insert('payment_checkout',$data);
            return TRUE;
    }
    
    public function check_duration($device_id){
        $currdate = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('payment_checkout');
        $this->db->where('device_id',$device_id);
        $this->db->order_by('id','desc');
        $query = $this->db->get()->row();
        
$d1 = new DateTime(!empty($query->date)?$query->date:'');
$d2 = new DateTime($currdate);
$months = 0;

$d1->add(new \DateInterval('P1M'));
while ($d1 <= $d2){
    $months ++;
    $d1->add(new \DateInterval('P1M'));
}
if(!empty($query)){

if($months >= $query->payment_duration){
    return 'expired';
}else{
    return 'have';
}
}else{
    return false;
}

                
    }
    
        
    public function expiredate_check($device_id){
         $this->db->select('*');
        $this->db->from('payment_checkout');
        $this->db->where('device_id',$device_id);
        $this->db->order_by('id','desc');
        $query = $this->db->get()->row();
        
         $final_date = new DateTime($query->date);
    $final_date->modify('+'.$query->payment_duration.' month');
    $output = $final_date->format('d/m/Y');
    return $output;
        
    }


    public function settings(){
        $this->db->select('*');
        $this->db->from('web_setting');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function user_list(){
        $this->db->select('users.*,user_login.*,users.status as status');
        $this->db->from('user_login');
        $this->db->join('users', 'users.user_id = user_login.user_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


      public function bank_list(){
        $this->db->select('*');
        $this->db->from('bank_add');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}



