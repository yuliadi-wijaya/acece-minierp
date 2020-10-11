<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cproduct extends CI_Controller {

    public $product_id;

    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->model('Suppliers');
         $this->load->library('auth');
    }

    //Index page load
    public function index() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Insert Product and uload
    public function insert_product() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $product_id = (!empty($this->input->post('product_id',TRUE))?$this->input->post('product_id',TRUE):$this->generator(8));
        $check_product = $this->db->select('*')->from('product_information')->where('product_id',$product_id)->get()->num_rows();
        if($check_product > 0){
            $this->session->set_userdata(array('error_message' => display('already_exists')));
                redirect(base_url('Cproduct'));

        }
        $sup_price = $this->input->post('supplier_price',TRUE);
        $s_id = $this->input->post('supplier_id',TRUE);
        $product_model = $this->input->post('model',TRUE);
        for ($i = 0, $n = count($s_id); $i < $n; $i++) {
            $supplier_price = $sup_price[$i];
            $supp_id = $s_id[$i];

            $supp_prd = array(
                'product_id'     => $product_id,
                'supplier_id'    => $supp_id,
                'supplier_price' => $supplier_price,
                'products_model' => $product_model = $this->input->post('model',TRUE)
            );

            $this->db->insert('supplier_product', $supp_prd);
        }

        //Supplier check
        if ($this->input->post('supplier_id',TRUE) == null) {
            $this->session->set_userdata(array('error_message' => display('please_select_supplier')));
            redirect(base_url('Cproduct'));
        }

        if ($_FILES['image']['name']) {
            //Chapter chapter add start
            $config['upload_path']   = './my-assets/image/product/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Cproduct'));
            } else {
                
            $imgdata = $this->upload->data();  
            $image = $config['upload_path'].$imgdata['file_name']; 
            $config['image_library']  = 'gd2';
            $config['source_image']   = $image;
            $config['create_thumb']   = false;
            $config['maintain_ratio'] = TRUE;
            $config['width']          = 100;
            $config['height']         = 100;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $image_url = base_url() . $image;
            }
           
        }

        $price = $this->input->post('price',TRUE);
        $tax_percentage = $this->input->post('tax',TRUE);
        $tax = $tax_percentage / 100;

        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn)-4;
        if($num_column > 0){
       $taxfield = [];
       for($i=0;$i<$num_column;$i++){
        $taxfield[$i] = 'tax'.$i;
       }
       foreach ($taxfield as $key => $value) {
        $data[$value] = $this->input->post($value)/100;
       }
    }

            $data['product_id']   = $product_id;
            $data['product_name'] = $this->input->post('product_name',TRUE);
            $data['category_id']  = $this->input->post('category_id',TRUE);
            $data['unit']         = $this->input->post('unit',TRUE);
            $data['tax']          = 0;
            $data['serial_no']    = $this->input->post('serial_no',TRUE);
            $data['price']        = $price;
            $data['product_model']= $this->input->post('model',TRUE);
            $data['product_details'] = $this->input->post('description',TRUE);
            $data['image']        = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
            $data['status']       = 1;
      
        $result = $CI->lproduct->insert_product($data);
        


        if ($result == 1) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-product'])) {
                redirect(base_url('Cproduct/manage_product'));
                exit;
            } elseif (isset($_POST['add-product-another'])) {
                redirect(base_url('Cproduct'));
                exit;
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
            redirect(base_url('Cproduct'));
        }
    }

    //Product Update Form
    public function product_update_form($product_id) {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_edit_data($product_id);
        $this->template->full_admin_html_view($content);
    }

    // Product Update
    public function product_update() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Products');

        $product_id = $this->input->post('product_id',TRUE);
        $this->db->where('product_id', $product_id);
        $this->db->delete('supplier_product');
        $sup_price = $this->input->post('supplier_price',TRUE);
        $s_id = $this->input->post('supplier_id',TRUE);
        for ($i = 0, $n = count($s_id); $i < $n; $i++) {
            $supplier_price = $sup_price[$i];
            $supp_id = $s_id[$i];

            $supp_prd = array(
                'product_id'     => $product_id,
                'supplier_id'    => $supp_id,
                'supplier_price' => $supplier_price
            );

            $this->db->insert('supplier_product', $supp_prd);
        }
        // configure for upload 
        $config = array(
            'upload_path'   => "./my-assets/image/product/",
            'allowed_types' => "png|jpg|jpeg|gif|bmp|tiff",
            'overwrite'     => TRUE,
             'encrypt_name' => TRUE,
            'max_size'      => '0',
        );
        $image_data = array();
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('image')) {
            $image_data = $this->upload->data();
            $image_name = base_url() . "my-assets/image/product/" . $image_data['file_name'];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_data['full_path']; 
            $config['maintain_ratio'] = TRUE;
            $config['height'] = '100';
            $config['width'] = '100';
            $this->load->library('image_lib', $config);
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
            }
        } else {
            $image_name = $this->input->post('old_image',TRUE);
        }


        $price = $this->input->post('price',TRUE);
       
        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn)-4;
        if($num_column > 0){
       $taxfield = [];
       for($i=0;$i<$num_column;$i++){
        $taxfield[$i] = 'tax'.$i;
       }
       foreach ($taxfield as $key => $value) {
        $data[$value] = $this->input->post($value)/100;
       }
    }
            $data['product_name']   = $this->input->post('product_name',TRUE);
            $data['category_id']    = $this->input->post('category_id',TRUE);
            $data['price']          = $price;
            $data['serial_no']      = $this->input->post('serial_no',TRUE);
            $data['product_model']  = $this->input->post('model',TRUE);
            $data['product_details']= $this->input->post('description',TRUE);
            $data['unit']           = $this->input->post('unit',TRUE);
            $data['tax']            = 0;
            $data['image']          = $image_name;
       
        $result = $CI->Products->update_product($data, $product_id);
        if ($result == true) {
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Cproduct/manage_product'));
        } else {
            $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
            redirect(base_url('Cproduct/manage_product'));
        }
    }

    //Manage Product
    public function manage_product()
    {   
    
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $CI->load->model('Products');
        $content =$this->lproduct->product_list();
        $this->template->full_admin_html_view($content);

    
    }

    public function CheckProductList(){
        // GET data
        $this->load->model('Products');
        $postData = $this->input->post();
        $data = $this->Products->getProductList($postData);
        echo json_encode($data);
    } 
    //Add Product CSV
    public function add_product_csv() {
        $CI = & get_instance();
        $data = array(
            'title' => display('add_product_csv')
        );
        $content = $CI->parser->parse('product/add_product_csv', $data, true);
        $this->template->full_admin_html_view($content);
    }

    //CSV Upload File
    function uploadCsv()
    {
         $this->load->model('suppliers');
         $filename = $_FILES['upload_csv_file']['name'];  
        $ext = end(explode('.', $filename));
        $ext = substr(strrchr($filename, '.'), 1);
        if($ext == 'csv'){
        $count=0;
        $fp = fopen($_FILES['upload_csv_file']['tmp_name'],'r') or die("can't open file");

        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE)
        {
  
         while($csv_line = fgetcsv($fp,1024)){
                //keep this if condition if you want to remove the first row
                for($i = 0, $j = count($csv_line); $i < $j; $i++)
                {                  
                  $product_id = $this->generator(10);                  
                  $insert_csv = array();
                  $insert_csv['supplier_id']    = (!empty($csv_line[1])?$csv_line[1]:null);
                  $insert_csv['product_name']   = (!empty($csv_line[2])?$csv_line[2]:null);
                  $insert_csv['product_model']  = (!empty($csv_line[3])?$csv_line[3]:null);
                  $insert_csv['category_id']    = (!empty($csv_line[4])?$csv_line[4]:null);
                  $insert_csv['price']          = (!empty($csv_line[5])?$csv_line[5]:null);
                  $insert_csv['supplier_price'] = (!empty($csv_line[6])?$csv_line[6]:null);
                }
                 $check_supplier = $this->db->select('*')->from('supplier_information')->where('supplier_name',$insert_csv['supplier_id'])->get()->row();
                if(!empty($check_supplier)){
                    $supplier_id = $check_supplier->supplier_id;
                }else{
                     $supplierinfo=array(
            'supplier_name' => $insert_csv['supplier_id'],
            'address'           => '',
            'mobile'            => '',
            'details'           => '',
            'status'            => 1
            );
                     if ($count > 0) {
            $this->db->insert('supplier_information',$supplierinfo);
        }
            $supplier_id = $this->db->insert_id();
            $coa = $this->suppliers->headcode();
        if($coa->HeadCode!=NULL){
            $headcode=$coa->HeadCode+1;
        }
        else{
            $headcode="50205000001";
        }
        $c_acc=$supplier_id.'-'.$insert_csv['supplier_id'];
        $createby=$this->session->userdata('user_id');
        $createdate=date('Y-m-d H:i:s');

       
         $supplier_coa = [
            'HeadCode'         => $headcode,
            'HeadName'         => $c_acc,
            'PHeadName'        => 'Account Payable',
            'HeadLevel'        => '3',
            'IsActive'         => '1',
            'IsTransaction'    => '1',
            'IsGL'             => '0',
            'HeadType'         => 'L',
            'IsBudget'         => '0',
            'IsDepreciation'   => '0',
            'supplier_id'      => $supplier_id,
            'DepreciationRate' => '0',
            'CreateBy'         => $createby,
            'CreateDate'       => $createdate,
        ];

        if ($count > 0) {
        $this->db->insert('acc_coa',$supplier_coa);
    }
                }

        $check_category = $this->db->select('*')->from('product_category')->where('category_name',$insert_csv['category_id'])->get()->row();
        if(!empty($check_category)){
            $category_id = $check_category->category_id;
        }else{
            $category_id =   $this->auth->generator(15);
            $categorydata=array(
            'category_id'           => $category_id,
            'category_name'         => $insert_csv['category_id'],
            'status'                => 1
            );
            if ($count > 0) {
        $this->db->insert('product_category',$categorydata);
}

        }
         $data = array(
                    'product_id'    => $product_id,
                    'category_id'   => $category_id,
                    'product_name'  => $insert_csv['product_name'],
                    'product_model' => $insert_csv['product_model'],
                    'price'         => $insert_csv['price'],
                    'unit'          => '',
                    'tax'           => '',
                    'product_details'=>'Csv Product',
                    'image'         => base_url('my-assets/image/product.png'),
                    'status'        => 1
                );

                if ($count > 0) {

                                 $result = $this->db->select('*')
                                        ->from('product_information')
                                        ->where('product_name',$data['product_name'])
                                        ->where('product_model',$data['product_model'])
                                        ->where('category_id',$category_id)
                                        ->get()
                                        ->row();
                    if (empty($result)){
                        $this->db->insert('product_information',$data);
                        $product_id = $product_id;
                         }else {
                    $product_id = $result->product_id;      
                      $udata = array(
                        'product_id'     => $result->product_id,
                        'category_id'    => $category_id,
                        'product_name'   => $result->product_name,
                        'product_model'  => $insert_csv['product_model'],
                        'price'          => $insert_csv['price'],
                        'unit'           => '',
                        'tax'            => '',
                        'product_details'=> 'Csv Uploaded Product',
                        'image'         => base_url('my-assets/image/product.png'),
                        'status'        => 1
                     );
                   $this->db->where('product_id',$result->product_id);
                   $this->db->update('product_information',$udata);
                        
                    }

                     $supp_prd = array(
                    'product_id'     => $product_id,
                    'supplier_id'    => $supplier_id,
                    'supplier_price' => $insert_csv['supplier_price'],
                    'products_model' => $insert_csv['product_model'],
                );

                       $splprd = $this->db->select('*')
                            ->from('supplier_product')
                            ->where('supplier_id', $supplier_id)
                            ->where('product_id', $product_id)
                            ->get()
                            ->num_rows();

                    if ($splprd == 0) {
                        $this->db->insert('supplier_product', $supp_prd);
                    } else {
                        $supp_prd = array(
                            'supplier_id'    => $supplier_id,
                            'supplier_price' => $insert_csv['supplier_price'],
                            'products_model' => $insert_csv['product_model']
                        );
                        $this->db->where('product_id', $product_id);
                        $this->db->where('supplier_id', $supplier_id);
                        $this->db->update('supplier_product', $supp_prd);
                    }
    
                }  
                $count++; 
            }
            
        }
                        $this->db->select('*');
                        $this->db->from('product_information');
                        $this->db->where('status',1);
                        $query = $this->db->get();
                        foreach ($query->result() as $row) {
                            $json_product[] = array('label'=>$row->product_name."-(".$row->product_model.")",'value'=>$row->product_id);
                        }
                        $cache_file = './my-assets/js/admin_js/json/product.json';
                        $productList = json_encode($json_product);
                        file_put_contents($cache_file,$productList);
        fclose($fp) or die("can't close file");
        $this->session->set_userdata(array('message'=>display('successfully_added')));
        redirect(base_url('Cproduct/manage_product'));
    }else{
        $this->session->set_userdata(array('error_message'=>'Please Import Only Csv File'));
        redirect(base_url('Cproduct/manage_product'));
    }
    
    }


    //Add supplier by ajax
    public function add_supplier() {
        $this->load->model('Suppliers');

        $data = array(
            'supplier_id'   => $this->auth->generator(20),
            'supplier_name' => $this->input->post('supplier_name',TRUE),
            'address'       => $this->input->post('address',TRUE),
            'mobile'        => $this->input->post('mobile',TRUE),
            'details'       => $this->input->post('details',TRUE),
            'status'        => 1
        );

        $supplier = $this->Suppliers->supplier_entry($data);

        if ($supplier == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            echo TRUE;
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            echo FALSE;
        }
    }

    // Insert category by ajax
    public function insert_category() {
        $this->load->model('Categories');
        $category_id = $this->auth->generator(15);

        //Customer  basic information adding.
        $data = array(
            'category_id'   => $category_id,
            'category_name' => $this->input->post('category_name',TRUE),
            'status'        => 1
        );

        $result = $this->Categories->category_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            echo TRUE;
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            echo FALSE;
        }
    }

    // product_delete
    public function product_delete($product_id) {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Products');
        $check_calculation = $CI->Products->check_calculaton($product_id);
        if($check_calculation > 0){
            $this->session->set_userdata(array('error_message' => display('you_cant_delete_this_product')));
            redirect(base_url('Cproduct/manage_product'));

        }else{
        $result = $CI->Products->delete_product($product_id);
         $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Cproduct/manage_product'));
    }
    }

    //Retrieve Single Item  By Search
    public function product_by_search() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $product_id = $this->input->post('product_id',TRUE);

        $content = $CI->lproduct->product_search_list($product_id);
        $this->template->full_admin_html_view($content);
    }

    //Retrieve Single Item  By Search
    public function product_details($product_id) {
        $this->product_id = $product_id;
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_details($product_id);
        $this->template->full_admin_html_view($content);
    }

    //Retrieve Single Item  By Search
    public function product_sales_supplier_rate($product_id = null, $startdate = null, $enddate = null) {
        if ($startdate == null) {
            $startdate = date('Y-m-d', strtotime('-30 days'));
        }
        if ($enddate == null) {
            $enddate = date('Y-m-d');
        }
        $product_id_input = $this->input->post('product_id',TRUE);
        if (!empty($product_id_input)) {
            $product_id = $this->input->post('product_id',TRUE);
            $startdate  = $this->input->post('from_date',TRUE);
            $enddate    = $this->input->post('to_date',TRUE);
        }

        $this->product_id = $product_id;

        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_sales_supplier_rate($product_id, $startdate, $enddate);
        $this->template->full_admin_html_view($content);
    }

    //This function is used to Generate Key
    public function generator($lenth) {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Products');

        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }

        $result = $this->Products->product_id_check($con);

        if ($result === true) {
            $this->generator(8);
        } else {
            return $con;
        }
    }

    //Export CSV
    public function exportCSV() {
        // file name 
        $this->load->model('Products');
        $filename = 'product_' . date('Ymd') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // get data 
        $usersData = $this->Products->product_csv_file();

        // file creation 
        $file = fopen('php://output', 'w');

        $header = array('product_id', 'supplier_id', 'category_id', 'product_name', 'price', 'supplier_price', 'unit', 'tax', 'product_model', 'product_details', 'image', 'status');
        fputcsv($file, $header);
        foreach ($usersData as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
    }

// product pdf download 
        public function product_downloadpdf(){
        $CI = & get_instance();
        $CI->load->model('Products');
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('pdfgenerator'); 
        $product_list = $CI->Products->product_list_pdf();
        if (!empty($product_list)) {
            $i = 0;
            if (!empty($product_list)) {
                foreach ($product_list as $k => $v) {
                    $i++;
                    $product_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
           $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_product'),
            'product_list'  => $product_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
            $this->load->helper('download');
            $content = $this->parser->parse('product/product_list_pdf', $data, true);
            $time = date('Ymdhi');
            $dompdf = new DOMPDF();
            $dompdf->load_html($content);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/'.'product'.$time.'.pdf', $output);
            $file_path = 'assets/data/pdf/'.'product'.$time.'.pdf';
           $file_name = 'product'.$time.'.pdf';
            force_download(FCPATH.'assets/data/pdf/'.$file_name, null);
    }

}
