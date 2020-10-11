<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laccounts {

    //Retrieve  daily closing List	
    public function daily_closing_list($links = null, $per_page = null, $page = null) {
        $CI = & get_instance();
        $CI->load->model('Accounts');
        $CI->load->model('Web_settings');
        $CI->load->model('Reports');
        $CI->load->library('occational');
        $daily_closing_data = $CI->Accounts->get_closing_report($per_page, $page);

        $i = 0;
        if (!empty($daily_closing_data)) {
            foreach ($daily_closing_data as $k => $v) {
                $daily_closing_data[$k]['final_date'] = $CI->occational->dateConvert($daily_closing_data[$k]['date']);
            }
            foreach ($daily_closing_data as $k => $v) {
                $i++;
                $daily_closing_data[$k]['sl'] = $i + $CI->uri->segment(3);
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'              => display('closing_report'),
            'daily_closing_data' => $daily_closing_data,
            'currency'           => $currency_details[0]['currency'],
            'position'           => $currency_details[0]['currency_position'],
            'company_info'       => $company_info,
            'links'              => $links,
            'software_info'      => $currency_details,
            'company'            => $company_info,
        );
        $reportList = $CI->parser->parse('accounts/closing_report', $data, true);
        return $reportList;
    }

    //Retrieve  Customer List	
    public function get_date_wise_closing_reports($links = null, $per_page = null, $page = null, $from_date, $to_date) {
        $CI = & get_instance();
        $CI->load->model('Accounts');
        $CI->load->model('Reports');
        $CI->load->library('occational');
        $daily_closing_data = $CI->Accounts->get_date_wise_closing_report($per_page, $page, $from_date, $to_date);

        $i = 0;
        if (!empty($daily_closing_data)) {
            foreach ($daily_closing_data as $k => $v) {
                $daily_closing_data[$k]['final_date'] = $CI->occational->dateConvert($daily_closing_data[$k]['date']);
            }

            foreach ($daily_closing_data as $k => $v) {
                $i++;
                $daily_closing_data[$k]['sl'] = $i;
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'              => display('closing_report'),
            'company_info'       => $company_info,
            'daily_closing_data' => $daily_closing_data,
            'from_date'          => $from_date,
            'to_date'            => $to_date,
            'currency'           => $currency_details[0]['currency'],
            'position'           => $currency_details[0]['currency_position'],
            'links'              => $links,
            'software_info'      => $currency_details,
            'company'            => $company_info,
        );
        $reportList = $CI->parser->parse('accounts/closing_report', $data, true);
        return $reportList;
    }

}

?>