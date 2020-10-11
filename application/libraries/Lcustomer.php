<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lcustomer {

    //Retrieve  Customer List	
    public function customer_list() {
        $CI =& get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $company_info = $CI->Customers->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['total_customer']    = $CI->Customers->count_customer();
        $data['currency']          = $currency_details[0]['currency'];
        $data['title']             = display('manage_customer');
        $data['company_info']      = $company_info;
        $customerList = $CI->parser->parse('customer/customer',$data,true);
        return $customerList;
    }

    //Retrieve  Credit Customer List	
    public function credit_customer_list() {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $company_info = $CI->Customers->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency']       = $currency_details[0]['currency'];
        $data['company_info']   = $company_info;
        $data['total_customer'] = $CI->Customers->count_credit_customer();
        $customerList = $CI->parser->parse('customer/credit_customer', $data, true);
        return $customerList;
    }

    //##################  Paid  Customer List  ##########################	
    public function paid_customer_list() {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $company_info = $CI->Customers->retrieve_company();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency']       = $currency_details[0]['currency'];
        $data['total_customer'] = $CI->Customers->count_paid_customer();
        $data['company_info']   = $company_info;
        $customerList = $CI->parser->parse('customer/paid_customer', $data, true);
        return $customerList;
    }

    //Retrieve  Customer Search List	
    public function customer_search_item($customer_id) {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $customers_list = $CI->Customers->customer_search_item($customer_id);
        $all_customer_list = $CI->Customers->all_customer_list();
        $i = 0;
        $total = 0;
        if ($customers_list) {
            foreach ($customers_list as $k => $v) {
                $i++;
                $customers_list[$k]['sl'] = $i;
                $total += $customers_list[$k]['customer_balance'];
            }
            $currency_details = $CI->Web_settings->retrieve_setting_editdata();
            $data = array(
                'title'             => display('manage_customer'),
                'subtotal'          => number_format($total, 2, '.', ','),
                'all_customer_list' => $all_customer_list,
                'links'             => "",
                'pagenum'           => "",
                'customers_list'    => $customers_list,
                'currency'          => $currency_details[0]['currency'],
                'position'          => $currency_details[0]['currency_position'],
            );
            $customerList = $CI->parser->parse('customer/customer', $data, true);
            return $customerList;
        } else {
            redirect('Ccustomer/manage_customer');
        }
    }
    
        public function customer_ledger($customer_id, $start, $end) {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $customer = $CI->Customers->customer_list_ledger();
        $customer_detail = $CI->Customers->customer_personal_data($customer_id);
        $ledger   = $CI->Customers->customerledger_searchdata($customer_id, $start, $end);
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(
            'title'          => display('customer_ledger'),
            'ledgers'        => $ledger,
            'customer_name'  => $customer_detail[0]['customer_name'],
            'address'        => $customer_detail[0]['customer_address'],
            'customer'       => $customer,
            'customer_id'    => $customer_id,
            'start'          => $start,
            'end'            => $end,
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
            'links'          => '',
        );

        $singlecustomerdetails = $CI->parser->parse('customer/customer_ledger_report', $data, true);
        return $singlecustomerdetails;
    }

// all customer ledger data
        public function customer_ledger_report($links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $customer = $CI->Customers->customer_list_ledger();
        $ledger   = $CI->Customers->customer_product_buy($per_page, $page);
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();

        $data = array(
            'title'          => display('customer_ledger'),
            'ledgers'        => $ledger,
            'customer_name'  => '',
            'address'        => '',
            'customer'       => $customer,
            'customer_id'    => '',
            'start'          => '',
            'end'            => '',
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
            'links'          => $links,
        );

        $singlecustomerdetails = $CI->parser->parse('customer/customer_ledger_report', $data, true);
        return $singlecustomerdetails;
    }





    //Sub Category Add
    public function customer_add_form() {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $data = array(
            'title' => display('add_customer')
        );
        $customerForm = $CI->parser->parse('customer/add_customer_form', $data, true);
        return $customerForm;
    }

    public function insert_customer($data) {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->Customers->customer_entry($data);
        return true;
    }

    //customer Edit Data
    public function customer_edit_data($customer_id) {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $customer_detail = $CI->Customers->retrieve_customer_editdata($customer_id);
        $data = array(
            'title'           => display('customer_edit'),
            'customer_id'     => $customer_detail[0]['customer_id'],
            'customer_name'   => $customer_detail[0]['customer_name'],
            'customer_address'=> $customer_detail[0]['customer_address'],
            'address2'        => $customer_detail[0]['address2'],
            'customer_mobile' => $customer_detail[0]['customer_mobile'],
            'phone'           => $customer_detail[0]['phone'],
            'fax'             => $customer_detail[0]['fax'],
            'contact'         => $customer_detail[0]['contact'],
            'city'            => $customer_detail[0]['city'],
            'state'           => $customer_detail[0]['state'],
            'zip'             => $customer_detail[0]['zip'],
            'country'         => $customer_detail[0]['country'],
            'customer_email'  => $customer_detail[0]['customer_email'],
            'email_address'   => $customer_detail[0]['email_address'],
            'status'          => $customer_detail[0]['status']
        );
        $chapterList = $CI->parser->parse('customer/edit_customer_form', $data, true);
        return $chapterList;
    }

    //Customer ledger Data
    public function customer_ledger_data($customer_id) {
        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $customer_detail = $CI->Customers->customer_personal_data($customer_id);
        $invoice_info = $CI->Customers->customer_invoice_data($customer_id);
        $invoice_amount = 0;
        if (!empty($invoice_info)) {
            foreach ($invoice_info as $k => $v) {
                $invoice_info[$k]['final_date'] = $CI->occational->dateConvert($invoice_info[$k]['date']);
                $invoice_amount = $invoice_amount + $invoice_info[$k]['amount'];
            }
        }
        $receipt_info = $CI->Customers->customer_receipt_data($customer_id);
        $receipt_amount = 0;
        if (!empty($receipt_info)) {
            foreach ($receipt_info as $k => $v) {
                $receipt_info[$k]['final_date'] = $CI->occational->dateConvert($receipt_info[$k]['date']);
                $receipt_amount = $receipt_amount + $receipt_info[$k]['amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data = array(
            'title'           => display('customer_ledger'),
            'customer_id'     => $customer_detail[0]['customer_id'],
            'customer_name'   => $customer_detail[0]['customer_name'],
            'customer_address'=> $customer_detail[0]['customer_address'],
            'customer_mobile' => $customer_detail[0]['customer_mobile'],
            'customer_email'  => $customer_detail[0]['customer_email'],
            'receipt_amount'  => number_format($receipt_amount, 2, '.', ','),
            'invoice_amount'  => $invoice_amount,
            'invoice_info'    => $invoice_info,
            'currency'        => $currency_details[0]['currency'],
            'position'        => $currency_details[0]['currency_position'],
        );
        $chapterList = $CI->parser->parse('customer/customer_details', $data, true);
        return $chapterList;
    }

    


        public function advance_details_data($receiptid,$customer_id) {

        $CI = & get_instance();
        $CI->load->model('Customers');
        $CI->load->model('Purchases');
        $CI->load->model('Web_settings');
        $receiptdata      = $CI->Customers->advance_details($receiptid,$customer_id);
        $customer_details = $CI->Customers->credit_customer_search_item($customer_id);
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info     = $CI->Purchases->retrieve_company();
        $data = array(
            'title'            => display('customer_advance'),
            'details'          => $receiptdata,
            'customer_name'    => $customer_details[0]['customer_name'],
            'receipt_no'       => $receiptdata[0]['VNo'],
            'address'          => $customer_details[0]['customer_address'],
            'mobile'           => $customer_details[0]['customer_mobile'],
            'company_info'     => $company_info,
            'currency'         => $currency_details[0]['currency'],
            'position'         => $currency_details[0]['currency_position'],
        );

        $chapterList = $CI->parser->parse('customer/customer_advance_receipt', $data, true);
        return $chapterList;
    }
}

?>