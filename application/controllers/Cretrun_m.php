<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cretrun_m extends CI_Controller {

    public $menu;

    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('lreturn');
        $this->load->library('linvoice');
        $this->load->library('session');
        $this->auth->check_admin_auth();
    }

    public function index() {

        $content = $this->lreturn->return_form();

        $this->template->full_admin_html_view($content);
    }

    // invoice return form
    public function invoice_return_form() {
        $invoice_id = $this->input->post('invoice_id',TRUE);
        $invid = $this->db->select('invoice_id')->from('invoice')->where('invoice', $invoice_id)->get()->row();
        $query = $this->db->select('invoice_id')->from('invoice')->where('invoice', $invoice_id)->get();

        if ($query->num_rows() == 0) {
            $this->session->set_userdata(array('error_message' => display('please_input_correct_invoice_no')));
            redirect('Cretrun_m');
        }
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $content = $CI->lreturn->invoice_return_data($invid->invoice_id);
        $this->template->full_admin_html_view($content);
    }

// supplier return form
    public function supplier_return_form() {
        $purchase_id = $this->input->post('purchase_id',TRUE);
        $query = $this->db->select('purchase_id')->from('product_purchase')->where('purchase_id', $purchase_id)->get();


        if ($query->num_rows() == 0) {
            $this->session->set_userdata(array('error_message' => display('please_input_correct_purchase_id')));
            redirect('Cretrun_m');
        }
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $content = $CI->lreturn->supplier_return_data($purchase_id);
        $this->template->full_admin_html_view($content);
    }

    public function return_invoice() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Returnse');
        $invoice_id = $CI->Returnse->return_invoice_entry();
        $this->session->set_userdata(array('message' => display('successfully_added')));

        redirect("Cretrun_m/invoice_inserted_data/".$invoice_id);
    }

    // return supplier insert  start
    public function return_suppliers() {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Returnse');
        $purchase_id = $CI->Returnse->return_supplier_entry();
        $this->session->set_userdata(array('message' => display('successfully_added')));
         redirect("Cretrun_m/supplier_inserted_data/".$purchase_id);
    }

    // supplier inserted  data
    public function supplier_inserted_data($purchase_id) {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $content = $CI->lreturn->supplier_html_data($purchase_id);
        $this->template->full_admin_html_view($content);
    }

    // return list start
    public function return_list() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $CI->load->model('Returnse');

        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cretrun_m/return_list/');
        $config["total_rows"] = $this->Returnse->return_list_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
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
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content = $this->lreturn->return_list($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

// date between return report list
    public function datewise_invoic_return_list() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $CI->load->model('Returnse');
        $config["base_url"] = base_url('Cretrun_m/return_list/');
        $config["total_rows"] = $this->Returnse->return_list_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
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
        $links = $this->pagination->create_links();
        $from_date = $this->input->post('from_date',TRUE);
        $to_date = $this->input->post('to_date',TRUE);
        $content = $CI->lreturn->return_list_datebetween($from_date, $to_date, $links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    public function supplier_return_list() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $CI->load->model('Returnse');
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cretrun_m/supplier_return_list/');
        $config["total_rows"] = $this->Returnse->supplier_return_list_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
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
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content = $this->lreturn->supplier_return_list($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

// wastage return list start
    public function wastage_return_list() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $CI->load->model('Returnse');
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cretrun_m/wastage_return_list/');
        $config["total_rows"] = $this->Returnse->wastage_return_list_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
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
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content = $this->lreturn->wastage_return_list($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    //wastage return list end
    public function invoice_inserted_data($invoice_id) {
        $CI = & get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $content = $CI->lreturn->invoice_html_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }

// Return delete with invoice id
    public function delete_retutn_invoice($invoice_id = null) {
        $this->load->model('Returnse');
        if ($this->Returnse->returninvoice_delete($invoice_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("Cretrun_m/return_list");
    }

    // return delete with purchase id 
    public function delete_retutn_purchase($purchase_id = null) {
        $this->load->model('Returnse');
        if ($this->Returnse->return_purchase_delete($purchase_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("Cretrun_m/supplier_return_list");
    }

    // date wise supplier return list
    public function datebwteen_supplier_return_list() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreturn');
        $CI->load->model('Returnse');
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cretrun_m/supplier_return_list/');
        $config["total_rows"] = $this->Returnse->supplier_return_list_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
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
        $links = $this->pagination->create_links();
        $from_date = $this->input->post('from_date',TRUE);
        $to_date = $this->input->post('to_date',TRUE);
        $content = $this->lreturn->datewise_supplier_return_list($from_date, $to_date, $links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }
// stock return (invoice return list) pdf
    public function invoice_return_downloadpdf(){
        $CI = & get_instance();
        $CI->load->model('Returnse');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator'); 
        $CI->load->library('occational');
        $return_list = $CI->Returnse->pdf_invoice_return_list();
        if (!empty($return_list)) {
            $i = 0;
            if (!empty($return_list)) {
                foreach ($return_list as $k => $v) {
                    $i++;
                    $return_list[$k]['sl'] = $i + $CI->uri->segment(3);
                     $return_list[$k]['final_date'] = $CI->occational->dateConvert($return_list[$k]['date_return']);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_invoice_return'),
            'return_list'   => $return_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
            $this->load->helper('download');
            $content = $this->parser->parse('return/invoice_return_list_pdf', $data, true);
            $time = date('Ymdhi');
            $dompdf = new DOMPDF();
            $dompdf->load_html($content);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/'.'invoice_return'.$time.'.pdf', $output);
            $file_path = 'assets/data/pdf/'.'invoice_return'.$time.'.pdf';
           $file_name = 'invoice_return'.$time.'.pdf';
            force_download(FCPATH.'assets/data/pdf/'.$file_name, null);
    }
    // supplier return pdf
        public function supplier_return_downloadpdf(){
        $CI = & get_instance();
        $CI->load->model('Returnse');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator'); 
        $CI->load->library('occational');
        $return_list = $CI->Returnse->pdf_supplier_return_list();
        if (!empty($return_list)) {
            $i = 0;
            if (!empty($return_list)) {
                foreach ($return_list as $k => $v) {
                    $i++;
                    $return_list[$k]['sl'] = $i + $CI->uri->segment(3);
                     $return_list[$k]['final_date'] = $CI->occational->dateConvert($return_list[$k]['date_return']);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('supplier_return'),
            'return_list'   => $return_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
            $this->load->helper('download');
            $content = $this->parser->parse('return/supplier_return_list_pdf', $data, true);
            $time = date('Ymdhi');
            $dompdf = new DOMPDF();
            $dompdf->load_html($content);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/'.'supplier_return'.$time.'.pdf', $output);
            $file_path = 'assets/data/pdf/'.'supplier_return'.$time.'.pdf';
           $file_name = 'supplier_return'.$time.'.pdf';
            force_download(FCPATH.'assets/data/pdf/'.$file_name, null);
    }
}
