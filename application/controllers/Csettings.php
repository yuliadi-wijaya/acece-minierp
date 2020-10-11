<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csettings extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('lsettings');
        $this->load->library('auth');
        $this->load->library('session');
        $this->load->model('Settings');
        $this->auth->check_admin_auth();
        $this->load->model('Web_settings');
    }

    public function index() {
        $data = array('title' => display('add_new_bank'));
        $content = $this->parser->parse('settings/new_bank', $data, true);
        $this->template->full_admin_html_view($content);
    }

    #================Add new bank==============#

    public function add_new_bank() {

        if ($_FILES['signature_pic']['name']) {

            $config['upload_path'] = './my-assets/image/logo/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['max_size'] = "*";
            $config['max_width'] = "*";
            $config['max_height'] = "*";
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('signature_pic')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Csettings/index'));
            } else {
                $image = $this->upload->data();
                $signature_pic = base_url() . "my-assets/image/logo/" . $image['file_name'];
            }
        }
        $coa = $this->Settings->headcode();
           if($coa->HeadCode!=NULL){
                $headcode=$coa->HeadCode+1;
           }else{
                $headcode="102010201";
            }

        $createby=$this->session->userdata('user_id');
        $createdate=date('Y-m-d H:i:s');
        $data = array(
            'bank_id'   => $this->auth->generator(10),
            'bank_name' => $this->input->post('bank_name',TRUE),
            'ac_name'   => $this->input->post('ac_name',TRUE),
            'ac_number' => $this->input->post('ac_no',TRUE),
            'branch'    => $this->input->post('branch',TRUE),
            'signature_pic' => (!empty($signature_pic) ? $signature_pic : null),
            'status'    => 1
        );
            $bank_coa = [
             'HeadCode'         => $headcode,
             'HeadName'         => $this->input->post('bank_name',TRUE),
             'PHeadName'        => 'Cash At Bank',
             'HeadLevel'        => '4',
             'IsActive'         => '1',
             'IsTransaction'    => '1',
             'IsGL'             => '0',
             'HeadType'         => 'A',
             'IsBudget'         => '0',
             'IsDepreciation'   => '0',
             'DepreciationRate' => '0',
             'CreateBy'         => $createby,
             'CreateDate'       => $createdate,
        ];
        $bankinfo = $this->Settings->bank_entry($data);

            $this->db->insert('acc_coa',$bank_coa);
             $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Csettings/bank_list'));
        exit;
       
    }

    public function bank_transaction() {
        $bank_list = $this->Settings->get_bank_list();
        $data = array(
            'title' => display('bank_transaction'),
            'bank_list' => $bank_list,
        );
        $content = $this->parser->parse('settings/bank_debit_credit_manage', $data, true);
        $this->template->full_admin_html_view($content);
    }

    public function bank_debit_credit_manage() {
        $bank_list = $this->Settings->get_bank_list();
        $data = array(
            'title' => display('bank_transaction'),
            'bank_list' => $bank_list,
        );
        $content = $this->parser->parse('settings/bank_debit_credit_manage', $data, true);
        $this->template->full_admin_html_view($content);
    }

    #===========Bank Debit Credit Manage==========#

    public function bank_debit_credit_manage_add() {

        if ($this->input->post('account_type',TRUE) == "Debit(+)") {
            $dr = $this->input->post('ammount',TRUE);
        } else {
            $cr = $this->input->post('ammount',TRUE);
        }
         $receive_by=$this->session->userdata('user_id');
        $receive_date=date('Y-m-d');
        $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$this->input->post('bank_id'))->get()->row()->bank_name;
       $coaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
       
        $coabanktransaction = array(
          'VNo'            =>  $this->input->post('withdraw_deposite_id',TRUE),
          'Vtype'          =>  'Bank Transaction',
          'VDate'          =>  $this->input->post('date',TRUE),
          'COAID'          =>  $coaid,
          'Narration'      =>  $this->input->post('description',TRUE),
          'Debit'          =>  (!empty($dr) ? $dr : 0),
          'Credit'         =>  (!empty($cr) ? $cr : 0),
          'IsPosted'       =>  1,
          'CreateBy'       =>  $receive_by,
          'CreateDate'     =>  date('Y-m-d H:i:s'),
          'IsAppove'       =>  1
        ); 
        $this->db->insert('acc_transaction',$coabanktransaction);
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Csettings/bank_list'));
        exit;
    }

    #==============Bank Ledger============#

    public function bank_ledger() {
        $bank_id = $this->input->post('bank_id',TRUE);
        $from    = $this->input->post('from_date',TRUE);
        $to      = $this->input->post('to_date',TRUE);
        $content = $this->lsettings->bank_ledger($bank_id,$from,$to);
        $this->template->full_admin_html_view($content);
    }

    #================Add Person==============#

    public function add_person() {
        $content = $this->lsettings->add_person();
        $this->template->full_admin_html_view($content);
    }

    #================Submit Person==============#

    public function submit_person() {
      $person_id = $this->auth->generator(10);
        $data = array(
            'person_id'      => $person_id,
            'person_name'    => $this->input->post('name',TRUE),
            'person_phone'   => $this->input->post('phone',TRUE),
            'person_address' => $this->input->post('address',TRUE),
            'status'         => 1
        );
       
        $result = $this->Settings->submit_person_personal_loan($data);
        if ($result) {
           
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Csettings/manage_person'));
        } else {
            $this->session->set_userdata(array('error_message' => display('not_added')));
            redirect(base_url('Csettings/manage_person'));
        }
    }

    //Phone search by name
    public function phone_search_by_name() {
        $person_id = $this->input->post('person_id',TRUE);
        $result = $this->db->select('person_phone')
                ->from('person_information')
                ->where('person_id', $person_id)
                ->get()
                ->row();
        if ($result) {
            echo $result->person_phone;
        } else {
            return false;
        }
    }

    //Person loan search by phone number
    public function loan_phone_search_by_name() {
        $person_id = $this->input->post('person_id',TRUE);
        $result = $this->db->select('person_phone')
                ->from('pesonal_loan_information')
                ->where('person_id', $person_id)
                ->get()
                ->row();
        if ($result) {
            echo $result->person_phone;
        } else {
            return false;
        }
    }

    #================Add loan==============#

    public function add_loan() {
        $content = $this->lsettings->add_loan();
        $this->template->full_admin_html_view($content);
    }

    #================Submit loan==============#

    public function submit_loan() {
        $transaction_id = $this->auth->generator(10);
        $data = array(
            'transaction_id' => $transaction_id,
            'person_id'      => $this->input->post('person_id',TRUE),
            'debit'          => $this->input->post('ammount',TRUE),
            'date'           => $this->input->post('date',TRUE),
            'details'        => $this->input->post('details',TRUE),
            'status'         => 1
        );
       

        $result = $this->Settings->submit_loan_personal($data);
        if ($result) {
           
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Csettings/add_loan'));
        } else {
            $this->session->set_userdata(array('error_message' => display('not_added')));
            redirect(base_url('Csettings/add_loan'));
        }
    }

    #================Add payment==============#

    public function add_payment() {
        $content = $this->lsettings->add_payment();
        $this->template->full_admin_html_view($content);
    }

    #================Submit loan==============#

    public function submit_payment() {
        $transaction_id = $this->auth->generator(10);
        $data = array(
            'transaction_id' => $transaction_id,
            'person_id'      => $this->input->post('person_id',TRUE),
            'credit'         => $this->input->post('ammount',TRUE),
            'date'           => $this->input->post('date',TRUE),
            'details'        => $this->input->post('details',TRUE),
            'status'         => 2
        );
       
        $result = $this->Settings->submit_payment_per_loan($data);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Csettings/add_payment'));
        } else {
            $this->session->set_userdata(array('error_message' => display('not_added')));
            redirect(base_url('Csettings/add_payment'));
        }
    }

    #================Manage Person==============#

    public function manage_person() {
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Csettings/manage_person/');
        $config["total_rows"] = $this->Settings->person_list_count();
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
        $content = $this->lsettings->manage_person_loan_person($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    #############===manage loan form ==#####################

    public function manage_loan() {
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Csettings/manage_loan/');
        $config["total_rows"] = $this->Settings->person_loan_count();
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
        $content = $this->lsettings->manage_loan($links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    #================Edit Person==============#

    public function person_edit($person_id) {
        $content = $this->lsettings->edit_person($person_id);
        $this->template->full_admin_html_view($content);
    }

    ########===========person loan edit data =========####

    public function person_loan_edit($person_id) {
        $content = $this->lsettings->edit_person_loan($person_id);
        $this->template->full_admin_html_view($content);
    }

    ### Personal loan update ============================#####

    public function loan_edit($person_id) {
        $content = $this->lsettings->edit_loan($person_id);
        $this->template->full_admin_html_view($content);
    }

    #================update Person==============#update_loan_person

    public function update_person($person_id) {
        $data = array(
            'person_name'    => $this->input->post('name',TRUE),
            'person_phone'   => $this->input->post('phone',TRUE),
            'person_address' => $this->input->post('address',TRUE),
            'status'         => 1
        );
        $result = $this->Settings->update_person($data, $person_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Cloan/manage1_person'));
        } else {
            $this->session->set_userdata(array('error_message' => display('not_added')));
            redirect(base_url('Cloan/manage1_person'));
        }
    }

    //############## update loan date##############
    public function update_loan($person_id) {
        $data = array(
            'per_loan_id' => $this->input->post('per_loan_id',TRUE),
            'person_id'   => $this->input->post('person_id',TRUE),
            'date'        => $this->input->post('date',TRUE),
            'debit'       => $this->input->post('debit',TRUE),
            'credit'      => $this->input->post('credit',TRUE),
            'details'     => $this->input->post('details',TRUE),
        );
        $result = $this->Settings->update_per_loan($data, $person_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Csettings/manage_loan'));
        } else {
            $this->session->set_userdata(array('error_message' => display('not_added')));
            redirect(base_url('Csettings/manage_loan'));
        }
    }

    // Update person loan
    public function update_loan_person($person_id) {
        $hname =$person_id.'-'.$this->input->post('oldname',TRUE);
        $data = array(
            'person_name'    => $this->input->post('name',TRUE),
            'person_phone'   => $this->input->post('phone',TRUE),
            'person_address' => $this->input->post('address',TRUE),
            'status'         => 1
        );
         $loan_person = [
             'HeadName'         => $person_id.'-'.$this->input->post('name',TRUE),
        ];
        $result = $this->Settings->update_loan_person($data, $person_id);
        if ($result) {
        $this->db->where('HeadName', $hname);
        $this->db->update('acc_coa', $loan_person);
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Csettings/manage_person'));
        } else {
            $this->session->set_userdata(array('error_message' => display('not_added')));
            redirect(base_url('Csettings/manage_person'));
        }
    }

    //Person ledger 
    public function person_ledger($person_id) {
        $content = $this->lsettings->person_ledger_data($person_id);
        $this->template->full_admin_html_view($content);
    }

    // Persona loan details
    public function person_loan_deails($person_id) {
        $content = $this->lsettings->person_loan_data($person_id);
        $this->template->full_admin_html_view($content);
    }

    //Ledger search
    public function ledger_search() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $CI->load->model('Reports');
        $today = date('Y-m-d');

        $person_id = $this->input->post('person_id',TRUE) ? $this->input->post('person_id',TRUE) : "";
        $from_date = $this->input->post('from_date',TRUE);

        $to_date = $this->input->post('to_date',TRUE) ? $this->input->post('to_date',TRUE) : $today;
        $content = $this->lsettings->ledger_search_by_date($person_id, $from_date, $to_date);

        $this->template->full_admin_html_view($content);
    }
    //person_loan_search 
    public function person_loan_search() {
        if (empty($this->input->post())) {
            redirect('Csettings/manage_person');
        }
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $CI->load->model('Reports');
        $today = date('Y-m-d');
        
        $person_id = $this->input->post('person_id',TRUE) ? $this->input->post('person_id',TRUE) : "";
        $from_date = $this->input->post('from_date',TRUE);

        $to_date = $this->input->post('to_date',TRUE) ? $this->input->post('to_date',TRUE) : $today;
        // echo '<pre>';
        // print_r($to_date);
        // echo '</pre>';
        // die();
        $content = $this->lsettings->person_loan_search_by_date($person_id, $from_date, $to_date);

        $this->template->full_admin_html_view($content);
    }

    #==============Bank list============#

    public function bank_list() {
        $content = $this->lsettings->bank_list();
        $this->template->full_admin_html_view($content);
    }

    #=============Bank edit==============#

    public function edit_bank($bank_id) {
        $content = $this->lsettings->bank_show_by_id($bank_id);
        $this->template->full_admin_html_view($content);
    }

    #============Update Bank=============#

    public function update_bank($bank_id) {
        $content = $this->lsettings->bank_update_by_id($bank_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect('Csettings/bank_list');
    }

    #==============Table list============#

    public function table_list() {
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Csettings/table_list/');
        $config["total_rows"] = $this->Settings->table_list_count();
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
        $content = $this->lsettings->table_list($links, $config["per_page"], $page);

        $this->template->full_admin_html_view($content);
    }

    #=============Commission==============#

    public function commission() {
        $customer_info = $this->Settings->customer_info();
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $data = array(
            'title'         => display('commission'),
            'customer_info' => $customer_info,
            'product_info'  => "",
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
        );
        $content = $this->parser->parse('commission/commission_generate', $data, true);
        $this->template->full_admin_html_view($content);
    }

    #==============Retrive commission=========#

    public function retrive_product_info() {
        $customer_id = $this->input->post('customer_id',true);
        $product_info = $this->db->select('
                                invoice_details.*,
                                product_information.product_model
                                ')
                ->from('invoice')
                ->join('invoice_details', 'invoice_details.invoice_id = invoice.invoice_id')
                ->join('product_information', 'invoice_details.product_id = product_information.product_id')
                ->where('invoice.customer_id', $customer_id)
                ->group_by('invoice_details.product_id')
                ->get()
                ->result();

        if ($product_info) {
            echo "<select class=\"form-control\" name=\"product_model\" id=\"product_model\">";
            echo "<option>" . display('select_one') . "</option>";
            foreach ($product_info as $product) {
                echo "<option value='" . $product->product_id . "'>" . $product->product_model . "</option>";
            }
            echo "</select>";
        }
    }

    //Commission generator
    public function commission_generate() {

        $customer_info = $this->Settings->customer_info();
        $product_info = $this->Settings->product_info();

        $commission_rate = $this->input->post('commission_rate',true);

        $subTotalCtn = 0;
        $subTotalQnty = 0;
        $subTotalComs = 0;
        if ($product_info) {
            foreach ($product_info as $k => $product) {

                $product_info[$k]['per_cartoon'] = $product_info[$k]['quantity'];

                $product_info[$k]['total_commission_rate'] = $product_info[$k]['quantity'] * $commission_rate;

                $product_info[$k]['commission'] = $commission_rate;

                $product_info[$k]['subTotalQnty'] = $subTotalQnty + $product_info[$k]['quantity'];
                $subTotalQnty = $product_info[$k]['subTotalQnty'];

                $product_info[$k]['subTotalComs'] = $subTotalComs + $product_info[$k]['total_commission_rate'];
                $subTotalComs = $product_info[$k]['subTotalComs'];
            }
        }

        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $data = array(
            'title' => display('commission'),
            'customer_info' => $customer_info,
            'product_info'  => $product_info,
            'subTotalCtn'   => $subTotalCtn,
            'subTotalQnty'  => $subTotalQnty,
            'subTotalComs'  => $subTotalComs,
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
        );

        $content = $this->parser->parse('commission/commission_generate', $data, true);
        $this->template->full_admin_html_view($content);
    }

    //This function is used to Generate Key
    public function generator($lenth) {
        $number = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "n", "m", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 34);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }

         public function delete_personal_loan($id = null) 
    { 
        if ($this->Settings->delete_personal_loan($id)) {
            #set success message
            $this->session->set_flashdata('message',display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception',display('please_try_again'));
        }
       redirect($_SERVER['HTTP_REFERER']);
    }

     public function delete_office_loan($id = null) 
    { 
        if ($this->Settings->delete_office_loan($id)) {
            #set success message
            $this->session->set_flashdata('message',display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception',display('please_try_again'));
        }
       redirect($_SERVER['HTTP_REFERER']);
    }

}
