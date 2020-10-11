<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cunit extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('lunit');
        $this->load->library('session');
        $this->load->model('Units');
        $this->auth->check_admin_auth();
    }

    // ================ by default create unit page load. =============
    public function index() {
        $content = $this->lunit->unit_add_form();
        $this->template->full_admin_html_view($content);
    }

//    ========== close index method ============
//    =========== unit add is start ====================
    public function insert_unit() {
        $unit_id = $this->auth->generator(15);
        $data = array(
            'unit_id'   => $unit_id,
            'unit_name' => $this->input->post('unit_name',TRUE),
            'status'    => 1
        );
        $result = $this->Units->insert_unit($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
                redirect(base_url('Cunit'));
            
        } else {
            $this->session->set_userdata(array('error_message' => display('already_inserted')));
            redirect(base_url('Cunit'));
        }
    }

//    =========== unit add is close ====================
//    =========== its for all unit record show start====================
    public function manage_unit() {
        $content = $this->lunit->unit_list();
        $this->template->full_admin_html_view($content);
    }

//    ========== its for all unit record show close ================
//    =========== its for unit edit form start ===============
    public function unit_update_form($unit_id) {
        $content = $this->lunit->unit_editable_data($unit_id);
        $this->template->full_admin_html_view($content);
    }

//    =========== its for unit edit form close ===============
//    =========== its for unit update start  ===============
    public function unit_update() {
        $this->load->model('Units');
        $unit_id = $this->input->post('unit_id',TRUE);
        $data = array(
            'unit_name' => $this->input->post('unit_name',TRUE),
            'status'    => 1,
        );
        $this->Units->unit_update($data, $unit_id);
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Cunit'));
    }

//    =========== its for unit update close ===============
//    =========== its for unit delete start ===============
    public function unit_delete($unit_id) {
        $this->Units->unit_delete($unit_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Cunit'));
    }

      function uploadCsv_unit()
    {
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
                   $insert_csv = array();
                   $insert_csv['unit_name'] = (!empty($csv_line[0])?$csv_line[0]:null);
                }
             
                $product_unit = array(
                    'unit_id'      => $this->auth->generator(15),
                    'unit_name'    => $insert_csv['unit_name'],
                    'status'       => 1
                );


                if ($count > 0) {
                    $this->db->insert('units',$product_unit);
                    }  
                $count++; 
            }
            
        }              
        $this->session->set_userdata(array('message'=>display('successfully_added')));
        redirect(base_url('Cunit'));
         }else{
        $this->session->set_userdata(array('error_message'=>'Please Import Only Csv File'));
        redirect(base_url('Cunit'));
    }
    
    }
    //Unit pdf download
         public function unit_downloadpdf(){
        $CI = & get_instance();
        $CI->load->model('Units');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator'); 
        $unit_list = $CI->Units->unit_list();
        if (!empty($unit_list)) {
            $i = 0;
            if (!empty($unit_list)) {
                foreach ($unit_list as $k => $v) {
                    $i++;
                    $unit_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('unit_list'),
            'unit_list'     => $unit_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
             'company_info' => $company_info
        );
            $this->load->helper('download');
            $content = $this->parser->parse('units/unit_list_pdf', $data, true);
            $time = date('Ymdhi');
            $dompdf = new DOMPDF();
            $dompdf->load_html($content);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/'.'unit'.$time.'.pdf', $output);
            $file_path = 'assets/data/pdf/'.'unit'.$time.'.pdf';
           $file_name = 'unit'.$time.'.pdf';
            force_download(FCPATH.'assets/data/pdf/'.$file_name, null);
    }
}
