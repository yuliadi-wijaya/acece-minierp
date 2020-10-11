<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lreport {

// Retrieve All Stock Report
    public function stock_report($limit, $page, $links) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $stok_report = $CI->Reports->stock_report($limit, $page);

        if (!empty($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['stok_quantity'] = $stok_report[$k]['totalBuyQnty'] - $stok_report[$k]['totalSalesQnty'];
                $stok_report[$k]['totalSalesCtn'] = $stok_report[$k]['totalSalesQnty'] / $stok_report[$k]['cartoon_quantity'];
                $stok_report[$k]['totalPrhcsCtn'] = $stok_report[$k]['totalBuyQnty'] / $stok_report[$k]['cartoon_quantity'];

                $stok_report[$k]['stok_quantity_cartoon'] = $stok_report[$k]['stok_quantity'] / $stok_report[$k]['cartoon_quantity'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data = array(
            'title'       => display('stock_list'),
            'stok_report' => $stok_report,
            'links'       => $links,
            'currency'    => $currency_details[0]['currency'],
            'position'    => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/stock_report', $data, true);
        return $reportList;
    }

//Out of stock
    public function out_of_stock() {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $out_of_stock = $CI->Reports->out_of_stock();

        if (!empty($out_of_stock)) {
            $i = 0;
            foreach ($out_of_stock as $k => $v) {
                $i++;
                $out_of_stock[$k]['sl'] = $i;
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data = array(
            'title'        => display('out_of_stock'),
            'out_of_stock' => $out_of_stock,
            'currency'     => $currency_details[0]['currency'],
            'position'     => $currency_details[0]['currency_position'],
        );

        $reportList = $CI->parser->parse('report/out_of_stock', $data, true);
        return $reportList;
    }

// Retrieve Single Item Stock Stock Report
public function stock_report_single_item()
    {  
        $CI =& get_instance();
        $CI->load->model('Reports');
        $data['title']        = 'stock';
        $company_info         = $CI->Reports->retrieve_company();
        $currency_details     = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency']     = $currency_details[0]['currency'];
        $data['totalnumber']  = $CI->Reports->totalnumberof_product();
        $data['company_info'] = $company_info; 
        $reportList = $CI->parser->parse('report/stock_report',$data,true);
        return $reportList;
    }


    /// supplier wise stock report
    public function stock_supplierwise()
    {  
        $CI =& get_instance();
        $CI->load->model('Reports');
        $data['title']        = 'stock';
        $company_info         = $CI->Reports->retrieve_company();
        $currency_details     = $CI->Web_settings->retrieve_setting_editdata();
        $data['currency']     = $currency_details[0]['currency'];
        $data['totalnumber']  = $CI->Reports->totalnumberof_product();
        $data['company_info'] = $company_info; 
        $reportList = $CI->parser->parse('report/supplierwise_stock',$data,true);
        return $reportList;
    }

// Retrieve Single Item Stock Stock Report
    public function stock_report_supplier_wise($product_id, $supplier_id, $date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Suppliers');
        $CI->load->library('occational');
        $stok_report = $CI->Reports->stock_report_supplier_bydate($product_id, $supplier_id, $date, $per_page, $page);
        $supplier_list = $CI->Suppliers->supplier_list_report();
        $supplier_info = $CI->Suppliers->retrieve_supplier_editdata($supplier_id);

        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;
        $sub_total_stoksale = 0;
        if (!empty($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;

                $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty'] - $stok_report[$k]['totalSalesQnty']);

                $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $stok_report[$k]['totalSalesQnty']);
                $sub_total_out = $stok_report[$k]['SubTotalOut'];
                $stok_report[$k]['SubTotalIn'] = ($sub_total_in + $stok_report[$k]['totalPurchaseQnty']);
                $sub_total_in = $stok_report[$k]['SubTotalIn'];
                 $stok_report[$k]['total_sale_price'] = $stok_report[$k]['stok_quantity_cartoon'] * $stok_report[$k]['price'];
                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity_cartoon']);
                 $sub_total_stoksale += $stok_report[$k]['total_sale_price'];
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];
            }
        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'          => display('stock_report_supplier_wise'),
            'stok_report'    => $stok_report,
            'product_model'  => $stok_report[0]['product_model'],
            'links'          => $links,
            'date'           => $date,
            'sub_total_in'   => number_format($sub_total_in, 2, '.', ','),
            'sub_total_out'  => number_format($sub_total_out, 2, '.', ','),
            'sub_total_stock'=> number_format($sub_total_stock, 2, '.', ','),
            'supplier_list'  => $supplier_list,
            'supplier_info'  => $supplier_info,
            'company_info'   => $company_info,
             'stock_sale'    => number_format($sub_total_stoksale, 2, '.', ','),
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
            'software_info'  => $currency_details,
            'company'        => $company_info,
        );

        $reportList = $CI->parser->parse('report/stock_report_supplier_wise', $data, true);
        return $reportList;
    }

// product wise stock report
    public function stock_report_product_date_date_wise($from_date, $to_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Suppliers');
        $CI->load->library('occational');
        $stok_report = $CI->Reports->stock_report_product_date_date($from_date, $to_date, $per_page, $page);
        $supplier_list = $CI->Suppliers->supplier_list_report();
        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;

        if (!empty($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
                $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty'] - $stok_report[$k]['totalSalesQnty']);
                $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $stok_report[$k]['totalSalesQnty']);
                $sub_total_out = $stok_report[$k]['SubTotalOut'];
                $stok_report[$k]['SubTotalIn'] = ($sub_total_in + $stok_report[$k]['totalPurchaseQnty']);
                $sub_total_in = $stok_report[$k]['SubTotalIn'];
                 $stok_report[$k]['total_sale_price'] = $stok_report[$k]['stok_quantity_cartoon'] * $stok_report[$k]['price'];
                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity_cartoon']);
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];
            }
        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'           => display('stock_report_supplier_wise'),
            'stok_report'     => $stok_report,
            'product_model'   => $stok_report[0]['product_model'],
            'links'           => $links,
            'start_date'      => $from_date,
            'end_date'        => $to_date,
            'sub_total_in'    => number_format($sub_total_in, 2, '.', ','),
            'sub_total_out'   => number_format($sub_total_out, 2, '.', ','),
            'sub_total_stock' => number_format($sub_total_stock, 2, '.', ','),
            'supplier_list'   => $supplier_list,
            'company_info'    => $company_info,
            'currency'        => $currency_details[0]['currency'],
            'position'        => $currency_details[0]['currency_position'],
            'software_info'   => $currency_details,
            'company'         => $company_info,
        );

        $reportList = $CI->parser->parse('report/productwise_stockreport_datebetween', $data, true);
        return $reportList;
    }

// Retrieve Single Item Stock Stock Report
    public function stock_report_product_wise( $supplier_id = null, $from_date = null, $to_date = null, $links = null, $per_page = null, $page = null) {

        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Suppliers');
        $CI->load->library('occational');
        $stok_report = $CI->Reports->stock_report_product_bydate($supplier_id, $from_date, $to_date, $per_page, $page);


        $supplier_list = $CI->Suppliers->supplier_list_report();
        $supplier_info = $CI->Suppliers->retrieve_supplier_editdata($supplier_id);


        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;
        $sub_total_in_qnty = 0;
        $sub_total_out_qnty = 0;
        $sub_total_stoksale = 0;
        $stok_quantity_cartoon = 0;


        if (!empty($stok_report)) {

            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }

            foreach ($stok_report as $k => $v) {
                $i++;

                $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty'] - $stok_report[$k]['totalSalesQnty']);

                $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $stok_report[$k]['totalSalesQnty']);
                $sub_total_out = $stok_report[$k]['SubTotalOut'];


                $stok_report[$k]['SubTotalIn'] = ($sub_total_in + $stok_report[$k]['totalPurchaseQnty']);
                $sub_total_in = $stok_report[$k]['SubTotalIn'];

                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity_cartoon']);
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];

                $stok_report[$k]['SubTotalinQnty'] = ($sub_total_in_qnty + $stok_report[$k]['totalPurchaseQnty']);
                $sub_total_in_qnty = $stok_report[$k]['SubTotalinQnty'];
                 $stok_report[$k]['total_sale_price'] = $stok_report[$k]['stok_quantity_cartoon'] * $stok_report[$k]['price'];
                $stok_report[$k]['SubTotaloutQnty'] = ($sub_total_out_qnty + $stok_report[$k]['totalSalesQnty']);
                 $sub_total_stoksale += $stok_report[$k]['total_sale_price'];
                $sub_total_out_qnty = $stok_report[$k]['SubTotaloutQnty'];
            }
        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title' => display('stock_report_product_wise'),
            'stok_report'    => $stok_report,
            'product_model'  => $stok_report[0]['product_model'],
            'links'          => $links,
            'date'           => $to_date,
            'sub_total_in'   => number_format($sub_total_in, 2, '.', ','),
            'sub_total_out'  => number_format($sub_total_out, 2, '.', ','),
            'sub_total_stock'=> number_format($sub_total_stock, 2, '.', ','),
            'SubTotalinQnty' => number_format($sub_total_in_qnty, 2, '.', ','),
            'SubTotaloutQnty'=> number_format($sub_total_out_qnty, 2, '.', ','),
            'supplier_list'  => $supplier_list,
            'supplier_info'  => $supplier_info,
            'company_info'   => $company_info,
            'product_id'     => $product_id,
            'supplier_id'    => $supplier_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
            'stock_sale'     => number_format($sub_total_stoksale, 2, '.', ','),
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
              'software_info'=> $currency_details,
            'company'        => $company_info,
        );

        $reportList = $CI->parser->parse('report/stock_report_product_wise', $data, true);
        return $reportList;
    }

// Retrieve daily Report
    public function retrieve_all_reports($per_page = null, $page = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $sales_report = $CI->Reports->todays_sales_report($per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }

        $purchase_report = $CI->Reports->todays_purchase_report($per_page, $page);
        $purchase_amount = 0;
        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'           => display('todays_report'),
            'sales_report'    => $sales_report,
            'sales_amount'    => number_format($sales_amount, 2, '.', ','),
            'purchase_amount' => number_format($purchase_amount, 2, '.', ','),
            'purchase_report' => $purchase_report,
            'date'            => $today = date('Y-m-d'),
            'company_info'    => $company_info,
            'currency'        => $currency_details[0]['currency'],
            'position'        => $currency_details[0]['currency_position'],
            'software_info'   => $currency_details,
            'company'         => $company_info,
        );

// report/all_report
        $reportList = $CI->parser->parse('report/all_report', $data, true);
        return $reportList;
    }

// Retrieve todays_sales_report
    public function todays_sales_report($links = null, $per_page = null, $page = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $sales_report = $CI->Reports->todays_sales_report($per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'        => display('todays_sales_report'),
            'sales_amount' => number_format($sales_amount, 2, '.', ','),
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'currency'     => $currency_details[0]['currency'],
            'position'     => $currency_details[0]['currency_position'],
            'links'        => $links,
            'software_info'=> $currency_details,
            'company'      => $company_info,
        );
        $reportList = $CI->parser->parse('report/sales_report', $data, true);
        return $reportList;
    }
    //======================= user sales report ================================
        public function user_sales_report($links = null, $per_page = null, $page = null,$from_date,$to_date,$user_id) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $sales_report = $CI->Reports->user_sales_report($per_page, $page,$from_date,$to_date,$user_id);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
               
                $sales_amount += $sales_report[$k]['amount'];
            }
        }
        $user_list = $CI->Reports->userList();
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'         => display('user_sales_report'),
            'sales_amount'  => number_format($sales_amount, 2, '.', ','),
            'sales_report'  => $sales_report,
            'company_info'  => $company_info,
            'from'          => $CI->occational->dateConvert($from_date),
            'to'            => $CI->occational->dateConvert($to_date),
            'currency'      => $currency_details[0]['currency'],
            'user_list'     => $user_list,
            'position'      => $currency_details[0]['currency_position'],
            'links'         => $links,
            'software_info' => $currency_details,
            'company'       => $company_info,
        );
        $reportList = $CI->parser->parse('report/user_wise_sales_report', $data, true);
        return $reportList;
    }

    public function retrieve_dateWise_SalesReports($from_date, $to_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $sales_report = $CI->Reports->retrieve_dateWise_SalesReports($from_date, $to_date, $per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'        => display('sales_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
            'currency'     => $currency_details[0]['currency'],
            'position'     => $currency_details[0]['currency_position'],
            'links'        => $links,
             'software_info' => $currency_details,
            'company'      => $company_info,
        );
        $reportList = $CI->parser->parse('report/sales_report', $data, true);
        return $reportList;
    }
 // ======================= Due Report start ===============================
     public function retrieve_dateWise_DueReports($from_date, $to_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $sales_report = $CI->Reports->retrieve_dateWise_DueReports($from_date, $to_date, $per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'        => display('due_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
            'currency'     => $currency_details[0]['currency'],
            'position'     => $currency_details[0]['currency_position'],
            'links'        => $links,
            'software_info'=> $currency_details,
            'company'      => $company_info,
        );
        $reportList = $CI->parser->parse('report/due_report', $data, true);
        return $reportList;
    }
    // ======================= Due Report end =================================
// Retrieve todays_purchase_report
    public function todays_purchase_report($links = null, $per_page = null, $page = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $purchase_report = $CI->Reports->todays_purchase_report($per_page, $page);
        $purchase_amount = 0;

        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'           => display('todays_purchase_report'),
            'purchase_amount' => number_format($purchase_amount, 2, '.', ','),
            'purchase_report' => $purchase_report,
            'company_info'    => $company_info,
            'currency'        => $currency_details[0]['currency'],
            'position'        => $currency_details[0]['currency_position'],
            'links'           => $links,
             'software_info'  => $currency_details,
            'company'         => $company_info,
        );
        $reportList = $CI->parser->parse('report/purchase_report', $data, true);
        return $reportList;
    }

//    ========== its for purchase_report_category_wise ===========
    public function purchase_report_category_wise($links = null, $per_page = null, $page = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->model('Categories');
        $category_list = $CI->Categories->category_list_product();
        $purchase_report_category_wise = $CI->Reports->purchase_report_category_wise($per_page, $page);
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'         => display('category_wise_purchase_report'),
            'category_list' => $category_list,
            'purchase_report_category_wise' => $purchase_report_category_wise,
            'company_info'  => $company_info,
            'currency'      => $currency_details[0]['currency'],
            'position'      => $currency_details[0]['currency_position'],
            'links'         => $links,
            'software_info' => $currency_details,
            'company'       => $company_info,
        );
        $reportList = $CI->parser->parse('report/purchase_report_category_wise', $data, true);
        return $reportList;
    }

    public function filter_purchase_report_category_wise($category = null, $from_date = null, $to_date = null, $links = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->model('Categories');
        $category_list = $CI->Categories->category_list_product();
        $filter_purchase_report_category_wise = $CI->Reports->filter_purchase_report_category_wise($category, $from_date, $to_date);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'             => display('category_wise_purchase_report'),
            'category_list'     => $category_list,
            'from_date'         => $from_date,
            'to_date'           => $to_date,
            'purchase_report_category_wise' => $filter_purchase_report_category_wise,
            'company_info'      => $company_info,
            'currency'          => $currency_details[0]['currency'],
            'position'          => $currency_details[0]['currency_position'],
            'links'             => $links,
            'software_info'     => $currency_details,
            'company'           => $company_info,
        );
        $reportList = $CI->parser->parse('report/purchase_report_category_wise', $data, true);
        return $reportList;
    }

//    ========== its for sales_report_category_wise ===========
    public function sales_report_category_wise($links = null, $per_page = null, $page = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->model('Categories');
        $category_list = $CI->Categories->category_list_product();
        $sales_report_category_wise = $CI->Reports->sales_report_category_wise($per_page, $page);
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'                      => display('category_wise_purchase_report'),
            'category_list'              => $category_list,
            'sales_report_category_wise' => $sales_report_category_wise,
            'company_info'               => $company_info,
            'currency'                   => $currency_details[0]['currency'],
            'position'                   => $currency_details[0]['currency_position'],
            'links'                      => $links,
            'software_info'              => $currency_details,
            'company'                    => $company_info,
        );
        $reportList = $CI->parser->parse('report/sales_report_category_wise', $data, true);
        return $reportList;
    }

//    ============== its for filter_sales_report_category_wise============
    public function filter_sales_report_category_wise($category = null, $from_date = null, $to_date = null, $links = null) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->model('Categories');
        $category_list = $CI->Categories->category_list_product();
        $filter_sales_report_category_wise = $CI->Reports->filter_sales_report_category_wise($category, $from_date, $to_date);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'                      => display('category_wise_purchase_report'),
            'category_list'              => $category_list,
            'from_date'                  => $from_date,
            'to_date'                    => $to_date,
            'sales_report_category_wise' => $filter_sales_report_category_wise,
            'company_info'               => $company_info,
            'currency'                   => $currency_details[0]['currency'],
            'position'                   => $currency_details[0]['currency_position'],
            'links'                      => $links,
             'software_info'             => $currency_details,
            'company'                    => $company_info,
        );

        $reportList = $CI->parser->parse('report/sales_report_category_wise', $data, true);
        return $reportList;
    }

//Retrive date wise purchase report
    public function retrieve_dateWise_PurchaseReports($start_date, $end_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $purchase_report = $CI->Reports->retrieve_dateWise_PurchaseReports($start_date, $end_date, $per_page, $page);
        $purchase_amount = 0;
        if (!empty($purchase_report)) {
            $i = 0;
            foreach ($purchase_report as $k => $v) {
                $i++;
                $purchase_report[$k]['sl'] = $i;
                $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
                $purchase_amount = $purchase_amount + $purchase_report[$k]['grand_total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'           => display('purchase_report'),
            'purchase_amount' => number_format($purchase_amount,2),
            'purchase_report' => $purchase_report,
            'company_info'    => $company_info,
            'from_date'       => $start_date,
            'to_date'         => $end_date,
            'currency'        => $currency_details[0]['currency'],
            'position'        => $currency_details[0]['currency_position'],
            'links'           => $links,
             'software_info'  => $currency_details,
            'company'         => $company_info,
        );
        $reportList = $CI->parser->parse('report/purchase_report', $data, true);
        return $reportList;
    }

//Product report sales wise
    public function get_products_report_sales_view($links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $product_report = $CI->Reports->retrieve_product_sales_report($per_page, $page);
        $product_list = $CI->Reports->product_list();
        if (!empty($product_report)) {
            $i = 0;
            foreach ($product_report as $k => $v) {
                $i++;
                $product_report[$k]['sl'] = $i;
            }
        }
        $sub_total = 0;
        if (!empty($product_report)) {
        foreach ($product_report as $k => $v) {
            $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
            $sub_total = $sub_total + $product_report[$k]['total_amount'];
        }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'          => display('sales_report_product_wise'),
            'sub_total'      => number_format($sub_total, 2, '.', ','),
            'product_report' => $product_report,
            'links'          => $links,
            'product_list'   => $product_list,
            'product_id'     => '',
            'from_date'      => '',
            'to_date'        => '',
            'company_info'   => $company_info,
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
             'software_info' => $currency_details,
            'company'        => $company_info,
        );
        $reportList = $CI->parser->parse('report/product_report', $data, true);
        return $reportList;
    }

//Get Product Report Search
    public function get_products_search_report($from_date, $to_date,$product_id, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $product_report = $CI->Reports->retrieve_product_search_sales_report($from_date, $to_date,$product_id, $per_page, $page);
        $product_list = $CI->Reports->product_list();

        if (!empty($product_report)) {
            $i = 0;
            foreach ($product_report as $k => $v) {
                $i++;
                $product_report[$k]['sl'] = $i;
            }
        }
        $sub_total = 0;
        if (!empty($product_report)) {
            foreach ($product_report as $k => $v) {
                $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
                $sub_total = $sub_total + $product_report[$k]['total_price'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'          => display('sales_report_product_wise'),
            'sub_total'      => number_format($sub_total, 2, '.', ','),
            'product_report' => $product_report,
            'product_list'   => $product_list,
            'product_id'     => $product_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
            'links'          => $links,
            'company_info'   => $company_info,
            'currency'       => $currency_details[0]['currency'],
            'position'       => $currency_details[0]['currency_position'],
            'software_info'  => $currency_details,
            'company'        => $company_info,
        );
        $reportList = $CI->parser->parse('report/product_report', $data, true);
        return $reportList;
    }

//Total profit report
    public function total_profit_report($links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $total_profit_report = $CI->Reports->total_profit_report($per_page, $page);


        $profit_ammount = 0;
        $SubTotalSupAmnt = 0;
        $SubTotalSaleAmnt = 0;
        if (!empty($total_profit_report)) {
            $i = 0;
            foreach ($total_profit_report as $k => $v) {
        $total_profit_report[$k]['sl'] = $i;
        $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);
        $profit_ammount = $profit_ammount + $total_profit_report[$k]['total_profit'];
        $SubTotalSupAmnt = $SubTotalSupAmnt + $total_profit_report[$k]['total_supplier_rate'];
        $SubTotalSaleAmnt = $SubTotalSaleAmnt + $total_profit_report[$k]['total_sale'];
            }
        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'               => display('profit_report'),
            'profit_ammount'      => number_format($profit_ammount, 2, '.', ','),
            'total_profit_report' => $total_profit_report,
            'SubTotalSupAmnt'     => number_format($SubTotalSupAmnt, 2, '.', ','),
            'SubTotalSaleAmnt'    => number_format($SubTotalSaleAmnt, 2, '.', ','),
            'links'               => $links,
            'company'             => $company_info,
            'software_info'       => $currency_details,
            'currency'            => $currency_details[0]['currency'],
            'position'            => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/profit_report', $data, true);
        return $reportList;
    }

//Retrive date wise total profit report
    public function retrieve_dateWise_profit_report($start_date, $end_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $total_profit_report = $CI->Reports->retrieve_dateWise_profit_report($start_date, $end_date, $per_page, $page);

        $profit_ammount = 0;
        $SubTotalSupAmnt = 0;
        $SubTotalSaleAmnt = 0;
        if (!empty($total_profit_report)) {
            $i = 0;
    foreach ($total_profit_report as $k => $v) {
    $total_profit_report[$k]['sl'] = $i;
    $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);
    $profit_ammount = $profit_ammount + $total_profit_report[$k]['total_profit'];
    $SubTotalSupAmnt = $SubTotalSupAmnt + $total_profit_report[$k]['total_supplier_rate'];
    $SubTotalSaleAmnt = $SubTotalSaleAmnt + $total_profit_report[$k]['total_sale'];
            }
        }

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'              => display('profit_report'),
            'profit_ammount'     => number_format($profit_ammount, 2, '.', ','),
            'total_profit_report'=> $total_profit_report,
            'SubTotalSupAmnt'    => number_format($SubTotalSupAmnt, 2, '.', ','),
            'SubTotalSaleAmnt'   => number_format($SubTotalSaleAmnt, 2, '.', ','),
            'links'              => $links,
            'company_info'       => $company_info,
            'company'             => $company_info,
            'software_info'       => $currency_details,
            'currency'           => $currency_details[0]['currency'],
            'position'           => $currency_details[0]['currency_position'],
        );
        $reportList = $CI->parser->parse('report/profit_report', $data, true);
        return $reportList;
    }
    // ==================================== Shippin cost report ===================
     public function retrieve_dateWise_shippingcost($from_date, $to_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $sales_report = $CI->Reports->retrieve_dateWise_Shippingcost($from_date, $to_date, $per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl'] = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'        => display('shipping_cost_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
            'currency'     => $currency_details[0]['currency'],
            'position'     => $currency_details[0]['currency_position'],
            'links'        => $links,
             'software_info' => $currency_details,
            'company'       => $company_info,
        );
        $reportList = $CI->parser->parse('report/shippincost_report', $data, true);
        return $reportList;
    }
    //sales return report
        public function sales_return_data($links, $perpage, $page,$start,$end) {

        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $return_list = $CI->Reports->sales_return_list($perpage, $page,$start,$end);
        if (!empty($return_list)) {
            foreach ($return_list as $k => $v) {
                $return_list[$k]['final_date'] = $CI->occational->dateConvert($return_list[$k]['date_return']);
            }
            $i = 0;
            if (!empty($return_list)) {
                foreach ($return_list as $k => $v) {
                    $i++;
                    $return_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
         $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'      => display('invoice_return'),
            'return_list'=> $return_list,
            'company'    => $company_info,
            'links'      => $links,
            'from_date'  => $start,
            'to_date'    => $end,
            'software_info' => $currency_details,
            'currency'   => $currency_details[0]['currency'],
            'position'   => $currency_details[0]['currency_position'],
        );
        $returnList = $CI->parser->parse('report/sales_return', $data, true);
        return $returnList;
    }
    // supplier return report
       public function supplier_return_data($links, $perpage, $page,$start,$end) {

        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');

        $return_list = $CI->Reports->supplier_return($perpage,$page,$start,$end);
        if (!empty($return_list)) {
            foreach ($return_list as $k => $v) {
                $return_list[$k]['final_date'] = $CI->occational->dateConvert($return_list[$k]['date_return']);
            }
            $i = 0;
            if (!empty($return_list)) {
                foreach ($return_list as $k => $v) {
                    $i++;
                    $return_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'       => display('supplier_return'),
            'return_list' => $return_list,
            'company'    => $company_info,
            'start_date'  => $start,
            'end_date'    => $end,
            'links'       => $links,
            'software_info' => $currency_details,
            'currency'    => $currency_details[0]['currency'],
            'position'    => $currency_details[0]['currency_position'],
        );
        $returnList = $CI->parser->parse('report/supplier_return', $data, true);
        return $returnList;
    }

// Tax report 
         public function retrieve_dateWise_tax($from_date, $to_date, $links, $per_page, $page) {
        $CI = & get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $sales_report = $CI->Reports->retrieve_dateWise_tax($from_date, $to_date, $per_page, $page);
        $sales_amount = 0;
        if (!empty($sales_report)) {
            $i = 0;
            foreach ($sales_report as $k => $v) {
                $i++;
                $sales_report[$k]['sl']         = $i;
                $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
                $sales_amount = $sales_amount + $sales_report[$k]['total_amount'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'        => display('tax_report'),
            'sales_amount' => $sales_amount,
            'sales_report' => $sales_report,
            'company_info' => $company_info,
            'from_date'    => $from_date,
            'to_date'      => $to_date,
            'currency'     => $currency_details[0]['currency'],
            'position'     => $currency_details[0]['currency_position'],
            'links'        => $links,
            'software_info'=> $currency_details,
            'company'      => $company_info,
        );
        $reportList = $CI->parser->parse('report/tax_report', $data, true);
        return $reportList;
    }

}

?>