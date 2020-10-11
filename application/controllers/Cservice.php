<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cservice extends CI_Controller {

    public $menu;

    function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('lservice');
        $this->load->library('session');
        $this->load->model('Service');
        $this->auth->check_admin_auth();
    }

    //Default loading for service system.
    public function index() {
        $content = $this->lservice->service_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Manage service form
    public function manage_service() {
        $content = $this->lservice->service_list();
        $this->template->full_admin_html_view($content);
        
    }

    //Insert service and upload
    public function insert_service() {
       $tablecolumn = $this->db->list_fields('product_service');
       $num_column = count($tablecolumn)-4;
       $taxfield = [];
       for($i=0;$i<$num_column;$i++){
        $taxfield[$i] = 'tax'.$i;
       }
       foreach ($taxfield as $key => $value) {
        $data[$value] = $this->input->post($value)/100;
       }
       $data['service_name'] = $this->input->post('service_name',true);
       $data['charge'] = $this->input->post('charge',true);
       $data['description'] = $this->input->post('description',true);

        $result = $this->Service->service_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
          
                redirect(base_url('Cservice/manage_service'));
           
        } else {
            $this->session->set_userdata(array('error_message' => display('already_inserted')));
            redirect(base_url('Cservice'));
        }
    }

    //service Update Form
    public function service_update_form($service_id) {
        $content = $this->lservice->service_edit_data($service_id);
        $this->template->full_admin_html_view($content);
    }

    // service Update
    public function service_update() {
        $this->load->model('Service');
        $service_id = $this->input->post('service_id',true);

        $tablecolumn = $this->db->list_fields('product_service');
                   $num_column = count($tablecolumn)-4;
       $taxfield = [];
       for($i=0;$i<$num_column;$i++){
        $taxfield[$i] = 'tax'.$i;
       }
       foreach ($taxfield as $key => $value) {
        $data[$value] = $this->input->post($value)/100;
       }

            $data['service_name'] = $this->input->post('service_name',true);
            $data['charge']       = $this->input->post('charge',true);
            $data['description']  = $this->input->post('description',true);
           
    

        $this->Service->update_service($data, $service_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cservice/manage_service'));
    }

    // service delete
    public function service_delete($service_id) {
        $this->load->model('Service');
       
        $this->Service->delete_service($service_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Cservice/manage_service'));
    }
    //csv upload
        function uploadCsv_service()
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
                $insert_csv['service_name'] = (!empty($csv_line[0])?$csv_line[0]:null);
                $insert_csv['charge'] = (!empty($csv_line[1])?$csv_line[1]:null);
                $insert_csv['description'] = (!empty($csv_line[2])?$csv_line[2]:null);
                $insert_csv['tax1'] = (!empty($csv_line[3])?$csv_line[3]:null);
                $insert_csv['tax2'] = (!empty($csv_line[4])?$csv_line[4]:null);
                $insert_csv['tax3'] = (!empty($csv_line[5])?$csv_line[5]:null);
                }
             
                $servicedata = array(
                    'service_name'    => $insert_csv['service_name'],
                    'charge'          => $insert_csv['charge'],
                    'description'     => $insert_csv['description'],
                    'tax1'            => $this->input->post('tax1',TRUE)/100,
                    'tax2'            => $this->input->post('tax2',TRUE)/100,
                    'tax3'            => $this->input->post('tax3',TRUE)/100,
                );


                if ($count > 0) {
                    $this->db->insert('product_service',$servicedata);
                    }  
                $count++; 
            }
            
        }              
        $this->session->set_userdata(array('message'=>display('successfully_added')));
        redirect(base_url('Cservice/manage_service'));
         }else{
        $this->session->set_userdata(array('error_message'=>'Please Import Only Csv File'));
        redirect(base_url('Cservice/manage_service'));
    }
    
    }
    // service pdf download
        public function service_downloadpdf(){
        $CI = & get_instance();
        $CI->load->model('Service');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator'); 
        $service_list = $CI->Service->service_list();
        if (!empty($service_list)) {
            $i = 0;
            if (!empty($service_list)) {
                foreach ($service_list as $k => $v) {
                    $i++;
                    $service_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_service'),
            'service_list'  => $service_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
            $this->load->helper('download');
            $content = $this->parser->parse('service/service_list_pdf', $data, true);
            $time = date('Ymdhi');
            $dompdf = new DOMPDF();
            $dompdf->load_html($content);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/'.'service'.$time.'.pdf', $output);
            $file_path = 'assets/data/pdf/'.'service'.$time.'.pdf';
           $file_name = 'service'.$time.'.pdf';
            force_download(FCPATH.'assets/data/pdf/'.$file_name, null);
    }

      public function service_invoice_form() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->service_invoice_add_form();
        $this->template->full_admin_html_view($content);
    }

  // Service retrieve
     public function retrieve_service_data_inv() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Service');
        $service_id = $this->input->post('service_id',true);
        $service_info = $CI->Service->get_total_service_invoic($service_id);

        echo json_encode($service_info);
    }
// Service Invoice Entry
public function insert_service_invoice(){
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Service');
        $invoice_id = $CI->Service->invoice_entry();
        $mailsetting = $this->db->select('*')->from('email_config')->get()->result_array();
        if($mailsetting[0]['isservice']==1){
          $mail = $this->invoice_pdf_generate($invoice_id);
          if($mail == 0){
          $this->session->set_userdata(array('error_message' => display('please_config_your_mail_setting')));
          }
        }
        
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Cservice/service_invoice_data/'.$invoice_id));


}

// sent pdf copy to customer after invoice
       public function invoice_pdf_generate($invoice_id = null) {
        $id = $invoice_id; 
        $this->load->model('Service');
        $this->load->model('Web_settings');
        $this->load->model('Invoices');
        $this->load->library('occational');
        $employee_list = $this->Service->employee_list();
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $service_inv_main = $this->Service->service_invoice_updata($invoice_id);
        $customer_info =  $this->Service->customer_info($service_inv_main[0]['customer_id']);
        $taxinfo = $this->Service->service_invoice_taxinfo($invoice_id);
        $taxfield = $this->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();
        $company_info = $this->Invoices->retrieve_company();

        $subTotal_quantity = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;

        if (!empty($service_inv_main)) {
            foreach ($service_inv_main as $k => $v) {
                $service_inv_main[$k]['final_date'] = $this->occational->dateConvert($service_inv_main[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $service_inv_main[$k]['qty'];
                $subTotal_ammount = $subTotal_ammount + $service_inv_main[$k]['total'];
            }

            $i = 0;
            foreach ($service_inv_main as $k => $v) {
                $i++;
                $service_inv_main[$k]['sl'] = $i;
            }
        }
        $name    = $customer_info->customer_name;
        $email   = $customer_info->customer_email;
        $data = array(
            'title'         => display('service_details'),
            'employee_list' => $employee_list,
            'invoice_id'    => $service_inv_main[0]['voucher_no'],
            'final_date'    => $service_inv_main[0]['final_date'],
            'customer_id'   => $service_inv_main[0]['customer_id'],
            'customer_info' => $customer_info,
            'customer_name' => $customer_info->customer_name,
            'customer_address'=> $customer_info->customer_address,
            'customer_mobile'=> $customer_info->customer_mobile,
            'customer_email'=> $customer_info->customer_email,
            'details'       => $service_inv_main[0]['details'],
            'total_amount'  => $service_inv_main[0]['total_amount'],
            'total_discount'=> $service_inv_main[0]['total_discount'],
            'invoice_discount'=> $service_inv_main[0]['invoice_discount'],
            'subTotal_ammount'=> number_format($subTotal_ammount, 2, '.', ','),
            'subTotal_quantity'=>number_format($subTotal_quantity, 2, '.', ','),
            'total_tax'     => $service_inv_main[0]['total_tax'],
            'paid_amount'   => $service_inv_main[0]['paid_amount'],
            'due_amount'    => $service_inv_main[0]['due_amount'],
            'shipping_cost' => $service_inv_main[0]['shipping_cost'],
            'invoice_detail'=> $service_inv_main,
            'taxvalu'       => $taxinfo,
            'discount_type' => $currency_details[0]['discount_type'],
            'currency_details'=>$currency_details,
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
            'taxes'         => $taxfield,
            'stotal'        => $service_inv_main[0]['total_amount']-$service_inv_main[0]['previous'],
            'employees'     => $service_inv_main[0]['employee_id'],
            'previous'      => $service_inv_main[0]['previous'],
            'company_info'  => $company_info,

        );
        $this->load->library('pdfgenerator');
        $html = $this->load->view('service/invoice_download', $data, true);
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/service/' . $id . '.pdf', $output);
        $file_path = getcwd() . '/assets/data/pdf/service/' . $id . '.pdf';
        $send_email = '';
        if (!empty($email)) {
            $send_email = $this->setmail($email, $file_path, $id, $name);
            
            if($send_email){
                return 1;
                
           
            }else{
             
            return 0;
               
            }
           
        }
        return 0;
       
    }

     public function setmail($email, $file_path, $id = null, $name = null) {
        $setting_detail = $this->db->select('*')->from('email_config')->get()->row();
        $subject = 'Service Purchase  Information';
        $message = strtoupper($name) . '-' . $id;
        $config = Array(
            'protocol' => $setting_detail->protocol,
            'smtp_host' => $setting_detail->smtp_host,
            'smtp_port' => $setting_detail->smtp_port,
            'smtp_user' => $setting_detail->smtp_user,
            'smtp_pass' => $setting_detail->smtp_pass,
            'mailtype'  => 'html', 
            'charset'   => 'utf-8',
            'wordwrap'  => TRUE
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($setting_detail->smtp_user);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($file_path);
        $check_email = $this->test_input($email);
        if (filter_var($check_email, FILTER_VALIDATE_EMAIL)) {
            if ($this->email->send()) {
               return true;
            } else {
               
                return false;
            }
        } else {
           
            return true;
        }
    }

    //Email testing for email
    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function service_invoice_data($invoice_id) {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lservice');
        $content = $CI->lservice->service_invoice_view_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }
  //pdf download service invoice details
     public function servicedetails_download($invoice_id = null) {
     
        $this->load->model('Service');
        $this->load->model('Web_settings');
        $this->load->model('Invoices');
        $this->load->library('occational');
        $employee_list = $this->Service->employee_list();
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $service_inv_main = $this->Service->service_invoice_updata($invoice_id);
        $customer_info =  $this->Service->customer_info($service_inv_main[0]['customer_id']);
        $taxinfo = $this->Service->service_invoice_taxinfo($invoice_id);
        $taxfield = $this->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();
        $company_info = $this->Invoices->retrieve_company();

        $subTotal_quantity = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;

        if (!empty($service_inv_main)) {
            foreach ($service_inv_main as $k => $v) {
                $service_inv_main[$k]['final_date'] = $this->occational->dateConvert($service_inv_main[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $service_inv_main[$k]['qty'];
                $subTotal_ammount = $subTotal_ammount + $service_inv_main[$k]['total'];
            }

            $i = 0;
            foreach ($service_inv_main as $k => $v) {
                $i++;
                $service_inv_main[$k]['sl'] = $i;
            }
        }
        $data = array(
            'title'         => display('service_details'),
            'employee_list' => $employee_list,
            'invoice_id'    => $service_inv_main[0]['voucher_no'],
            'final_date'    => $service_inv_main[0]['final_date'],
            'customer_id'   => $service_inv_main[0]['customer_id'],
            'customer_info' => $customer_info,
            'customer_name' => $customer_info->customer_name,
            'customer_address'=> $customer_info->customer_address,
            'customer_mobile'=> $customer_info->customer_mobile,
            'customer_email'=> $customer_info->customer_email,
            'details'       => $service_inv_main[0]['details'],
            'total_amount'  => $service_inv_main[0]['total_amount'],
            'total_discount'=> $service_inv_main[0]['total_discount'],
            'invoice_discount'=> $service_inv_main[0]['invoice_discount'],
            'subTotal_ammount'=> number_format($subTotal_ammount, 2, '.', ','),
            'subTotal_quantity'=>number_format($subTotal_quantity, 2, '.', ','),
            'total_tax'     => $service_inv_main[0]['total_tax'],
            'paid_amount'   => $service_inv_main[0]['paid_amount'],
            'due_amount'    => $service_inv_main[0]['due_amount'],
            'shipping_cost' => $service_inv_main[0]['shipping_cost'],
            'invoice_detail'=> $service_inv_main,
            'taxvalu'       => $taxinfo,
            'discount_type' => $currency_details[0]['discount_type'],
            'currency_details'=>$currency_details,
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
            'taxes'         => $taxfield,
            'stotal'        => $service_inv_main[0]['total_amount']-$service_inv_main[0]['previous'],
            'employees'     => $service_inv_main[0]['employee_id'],
            'previous'      => $service_inv_main[0]['previous'],
            'company_info'  => $company_info,

        );



        $this->load->library('pdfgenerator');
        $dompdf = new DOMPDF();
        $page = $this->load->view('service/invoice_download', $data, true);
        $file_name = time();
        $dompdf->load_html($page);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents("assets/data/pdf/service/$file_name.pdf", $output);
        $filename = $file_name . '.pdf';
        $file_path = base_url() . 'assets/data/pdf/service/' . $filename;

        $this->load->helper('download');
        force_download('./assets/data/pdf/service/' . $filename, NULL);
        redirect("Cservice/manage_service");
    }


  public function service_invoice_update_form($invoic_id) {
        $content = $this->lservice->service_invoice_edit_data($invoic_id);
        $this->template->full_admin_html_view($content);
    }

        public function manage_service_invoice(){
        $data['title']  = display('manage_service_invoice');
        $config["base_url"] = base_url('Cservice/manage_service_invoice');
        $config["total_rows"]  = $this->db->count_all('service_invoice');
        $config["per_page"]    = 20;
        $config["uri_segment"] = 3;
        $config["last_link"] = "Last"; 
        $config["first_link"] = "First"; 
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';  
        $config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['service'] = $this->Service->service_invoice_list($config["per_page"], $page);
          $content     = $this->parser->parse('service/service_invoice', $data, true);
          $this->template->full_admin_html_view($content);  
    }

    public function service_invoic_delete($service_id) {
        $this->load->model('Service');
        $this->Service->delete_service_invoice($service_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
         redirect(base_url('Cservice/manage_service_invoice'));
    }
    public function update_service_invoice(){
      $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Service');
        $invoice_id = $CI->Service->invoice_update();
    $this->session->set_userdata(array('message' => display('successfully_updated')));
    redirect(base_url('Cservice/service_invoice_data/'.$invoice_id));  
    }
}
