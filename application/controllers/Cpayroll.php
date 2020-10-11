<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cpayroll extends CI_Controller {
    public $menu;
    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('session');
        $this->load->library('lpayroll');
        $this->load->library('numbertowords');
        $this->load->model('Payroll_model');
        $this->auth->check_admin_auth();
    }
    //Salary Beneficial form
    public function Salarybenificial() {
        $data['title'] = display('benefits');
        $data['sub_title'] = display('add_benefits');
        $content = $this->parser->parse('payroll/benefits_form', $data, true);
        $this->template->full_admin_html_view($content);
    }
    //Add Salary Beneficial
    public function beneficial_entry() {
        $this->load->model('Payroll_model');
        $postData = ['sal_name' => $this->input->post('sal_name', true), 'salary_type' => $this->input->post('emp_sal_type', true), 'status' => 1, ];
        if ($this->Payroll_model->add_beneficial($postData)) {
            $this->session->set_flashdata('message', display('save_successfully'));
        } else {
            $this->session->set_flashdata('error_message', display('please_try_again'));
        }
        redirect("Cpayroll/manage_benefits");
    }
    // Manage Beneficial
    public function manage_benefits() {
        $this->load->model('Payroll_model');
        $data['title'] = display('benefits');
        $data['sub_title'] = display('manage_benefits');
        $data['beneficial_list'] = $this->Payroll_model->salary_setupView();
        $content = $this->parser->parse('payroll/benefits_list', $data, true);
        $this->template->full_admin_html_view($content);
    }
    //Benefits update form
    public function benefits_update_form($id) {
        $this->load->model('Payroll_model');
        $data['title'] = display('benefits');
        $data['sub_title'] = display('update_benefits');
        $data['data'] = $this->Payroll_model->salarysetup_updateForm($id);
        $content = $this->parser->parse('payroll/benefits_update_form', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // benefits type update
    public function update_benefitstype() {
        $postData = ['salary_type_id' => $this->input->post('salary_type_id', true), 'sal_name' => $this->input->post('sal_name', true), 'salary_type' => $this->input->post('emp_sal_type', true), ];
        if ($this->Payroll_model->update_benefits($postData)) {
            $this->session->set_flashdata('message', display('successfully_updated'));
        } else {
            $this->session->set_flashdata('error_message', display('please_try_again'));
        }
        redirect("Cpayroll/manage_benefits");
    }
    public function delete_benefits($id = null) {
        if ($this->Payroll_model->benefits_delete($id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set error_message message
            $this->session->set_flashdata('error_message', display('please_try_again'));
        }
        redirect("Cpayroll/manage_benefits");
    }
    // ====================== Salary Setup part start ========================
    public function salary_setup_form() {
        $this->load->model('Payroll_model');
        $data['title'] = display('salary');
        $data['sub_title'] = display('add_salary_setup');
        $data['slname'] = $this->Payroll_model->salary_typeName();
        $data['sldname'] = $this->Payroll_model->salary_typedName();
        $data['employee'] = $this->Payroll_model->empdropdown();
        $content = $this->parser->parse('payroll/salary_setup_form', $data, true);
        $this->template->full_admin_html_view($content);
    }
    public function employeebasic() {
        $id = $this->input->post('employee_id', TRUE);
        $data = $this->db->select('hrate,rate_type')->from('employee_history')->where('id', $id)->get()->row();
        $basic = $data->hrate;
        if ($data->rate_type == 1) {
            $type = 'Hourly';
        } else {
            $type = 'Salary';
        }
        $sent = array('rate' => $data->hrate, 'rate_type' => $data->rate_type, 'stype' => $type);
        echo json_encode($sent);
    }
    public function salarywithtax() {
        $tamount = $this->input->post('amount', TRUE);
        $amount = $tamount * 12;
        $this->db->select('*');
        $this->db->from('payroll_tax_setup');
        $this->db->where("start_amount <", $amount);
        $query = $this->db->get();
        $taxrate = $query->result_array();
        $TotalTax = 0;
        foreach ($taxrate as $row) {
            // "Inside tax calculation";
            if ($amount > $row['start_amount'] && $amount > $row['end_amount']) {
                $diff = $row['end_amount'] - $row['start_amount'];
            }
            if ($amount > $row['start_amount'] && $amount < $row['end_amount']) {
                $diff = $amount - $row['start_amount'];
            }
            $tax = (($row['rate'] / 100) * $diff);
            $TotalTax+= $tax;
        }
        $salary = $TotalTax / 12;
        echo json_encode(number_format($salary, 2));
    }
    // Create salary setup
    public function salary_setup_entry() {
        $date = date('Y-m-d');
        $check_exist = $this->Payroll_model->check_exist($this->input->post('employee_id', true));
        if ($check_exist == 0) {
            $amount = $this->input->post('amount', TRUE);
            foreach ($amount as $key => $value) {
                $postData = ['employee_id' => $this->input->post('employee_id', true), 'sal_type' => $this->input->post('sal_type', true), 'salary_type_id' => $key, 'amount' => (!empty($value) ? $value : 0), 'create_date' => $date, 'gross_salary' => $this->input->post('gross_salary', true), ];
                $this->Payroll_model->salary_setup_create($postData);
            }
            $this->session->set_flashdata('message', display('save_successfully'));
            redirect("Cpayroll/salary_setup_form");
        } else {
            $this->session->set_flashdata('error_message', display('already_exist'));
            redirect("Cpayroll/salary_setup_form");
        }
    }
    // Manage Salary Setup
    public function manage_salary_setup() {
        $this->load->model('Payroll_model');
        $data['title'] = display('salary');
        $data['sub_title'] = display('manage_salary_setup');
        $data['emp_sl_setup'] = $this->Payroll_model->salary_setupindex();
        $content = $this->parser->parse('payroll/salary_setup_list', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // Salary Setup form
    public function salsetup_upform($id) {
        $this->load->model('Payroll_model');
        $data['title'] = display('salary');
        $data['sub_title'] = display('update_salary_setup');
        $data['data'] = $this->Payroll_model->salary_s_updateForm($id);
        $data['samlft'] = $this->Payroll_model->salary_amountlft($id);
        $data['amo'] = $this->Payroll_model->salary_amount($id);
        $data['employee'] = $this->Payroll_model->empdropdown();
        $data['EmpRate'] = $this->Payroll_model->employee_informationId($id);
        $content = $this->parser->parse('payroll/salary_setup_update_form', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // salary setup update
    public function salary_setup_update() {
        $amount = $this->input->post('amount', true);
        foreach ($amount as $key => $value) {
            $postData = array('employee_id' => $this->input->post('employee_id', true), 'sal_type' => $this->input->post('sal_type', true), 'salary_type_id' => $key, 'amount' => $value, 'gross_salary' => $this->input->post('gross_salary', true),);
            $this->Payroll_model->update_sal_stup($postData);
        }
        redirect("Cpayroll/manage_salary_setup");
    }
    public function delete_salsetup($id = null) {
        if ($this->Payroll_model->emp_salstup_delete($id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set error_message message
            $this->session->set_flashdata('error_message', display('please_try_again'));
        }
        redirect("Cpayroll/manage_salary_setup");
    }
    // Salary Generate
    public function salary_generate_form() {
        $this->load->model('Payroll_model');
        $data['title'] = display('salary');
        $data['sub_title'] = display('salary_generate');
        $content = $this->parser->parse('payroll/salary_generate_form', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // Entry salary generate
    public function create_salary_generate() {
        $employee = $this->db->select('employee_id')->from('employee_salary_setup')->group_by('employee_id')->get()->result();
        list($month, $year) = explode(' ', $this->input->post('myDate', TRUE));
        $query = $this->db->select('*')->from('salary_sheet_generate')->where('gdate', $this->input->post('myDate', TRUE))->get()->num_rows();
        if ($query > 0) {
            $this->session->set_userdata(array('error_message' => display('the_salary_of') . $month . display('already_generated')));
            redirect(base_url('Cpayroll/salary_generate_form'));
        }
        switch ($month) {
            case "January":
                $month = '1';
            break;
            case "February":
                $month = '2';
            break;
            case "March":
                $month = '3';
            break;
            case "April":
                $month = '4';
            break;
            case "May":
                $month = '5';
            break;
            case "June":
                $month = '6';
            break;
            case "July":
                $month = '7';
            break;
            case "August":
                $month = '8';
            break;
            case "September":
                $month = '9';
            break;
            case "October":
                $month = '10';
            break;
            case "November":
                $month = '11';
            break;
            case "December":
                $month = '12';
            break;
        }
        $fdate = $year . '-' . $month . '-' . '1';
        $lastday = date('t', strtotime($fdate));
        $edate = $year . '-' . $month . '-' . $lastday;
        $startd = $fdate;
        $ab = $this->input->post('myDate', TRUE);
        $postData = ['name' => '', 'gdate' => $ab, 'start_date' => $fdate, 'end_date' => $edate, 'generate_by' => $this->session->userdata('user_name'), ];
        $this->db->insert('salary_sheet_generate', $postData);
        $generate_id = $this->db->insert_id();
        if (sizeof($employee) > 0) foreach ($employee as $key => $value) {
            $aAmount = $this->db->select('gross_salary,sal_type,employee_id')->from('employee_salary_setup')->where('employee_id', $value->employee_id)->get()->row();
            $Amount = $aAmount->gross_salary;
            $startd = $fdate;
            $end = $edate;
            $times = $this->db->select('SUM(TIME_TO_SEC(staytime)) AS staytime')->from('attendance')->where('date BETWEEN "' . date('Y-m-d', strtotime($startd)) . '" and "' . date('Y-m-d', strtotime($end)) . '"')->where("employee_id", $value->employee_id)->get()->row()->staytime;
            $wormin = ($times / 60);
            $worhour = $wormin / 60;
            if ($aAmount->sal_type == 1) {
                $dStart = new DateTime($startd);
                $dEnd = new DateTime($end);
                $dDiff = $dStart->diff($dEnd);
                $numberofdays = $dDiff->days + 1;
                $totamount = $Amount * $worhour;
                $PYI = ($totamount / $numberofdays) * 365;
                $PossibleYearlyIncome = round($PYI);
                $this->db->select('*');
                $this->db->from('payroll_tax_setup');
                $this->db->where("start_amount <", $PossibleYearlyIncome);
                $query = $this->db->get();
                $taxrate = $query->result_array();
                $TotalTax = 0;
                foreach ($taxrate as $row) {
                    if ($PossibleYearlyIncome > $row['start_amount'] && $PossibleYearlyIncome > $row['end_amount']) {
                        $diff = $row['end_amount'] - $row['start_amount'];
                    }
                    if ($PossibleYearlyIncome > $row['start_amount'] && $PossibleYearlyIncome < $row['end_amount']) {
                        $diff = $PossibleYearlyIncome - $row['start_amount'];
                    }
                    $tax = (($row['rate'] / 100) * $diff);
                    $TotalTax+= $tax;
                }
                $TaxAmount = ($TotalTax / 365) * $numberofdays;
                $netAmount = $totamount - $TaxAmount;
            } else if ($aAmount->sal_type == 2) {
                $netAmount = $Amount;
            }
            $workingper = $this->db->select('COUNT(date) AS date')->from('attendance')->where('date BETWEEN "' . date('Y-m-d', strtotime($startd)) . '" and "' . date('Y-m-d', strtotime($end)) . '"')->where("employee_id", $value->employee_id)->get()->row()->date;
            $emp_info = $this->db->select('first_name,last_name')->from('employee_history')->where('id', $value->employee_id)->get()->row();
            $headname = $value->employee_id . '-' . $emp_info->first_name . '' . $emp_info->last_name;
            $headcode = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $headname)->get()->row()->HeadCode;
            $paymentData = array('generate_id' => $generate_id, 'employee_id' => $value->employee_id, 'total_salary' => $netAmount, 'total_working_minutes' => $worhour, 'working_period' => $workingper, 'salary_month' => $ab,);
            $empsalgen = array('VNo' => $ab, 'Vtype' => 'Salary', 'VDate' => date('Y-m-d'), 'COAID' => $headcode, 'Narration' => 'Employee Salary Generate Month of ' . $ab, 'Debit' => 0, 'Credit' => $netAmount, 'IsPosted' => 1, 'CreateBy' => $this->session->userdata('user_id'), 'CreateDate' => date('Y-m-d H:i:s'), 'IsAppove' => 1);
            if (!empty($aAmount->employee_id)) {
                $this->db->insert('employee_salary_payment', $paymentData);
                $this->db->insert('acc_transaction', $empsalgen);
            }
        }
        $this->session->set_flashdata('message', display('successfully_generated'));
        redirect("Cpayroll/manage_salary_generate");
    }
    //manage Salary Generate
    public function manage_salary_generate() {
        $data['title'] = display('salary');
        $data['sub_title'] = display('manage_salary_generate');
        $config["base_url"] = base_url('Cpayroll/manage_salary_generate');
        $config["total_rows"] = $this->db->count_all('salary_sheet_generate');
        $config["per_page"] = 20;
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
        $data['salgen'] = $this->Payroll_model->salary_generateView($config["per_page"], $page);
        $content = $this->parser->parse('payroll/salary_generate_list', $data, true);
        $this->template->full_admin_html_view($content);
    }
    public function delete_salgenerate($id = null) {
        if ($this->Payroll_model->sal_generate_delete($id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set error_message message
            $this->session->set_flashdata('error_message', display('please_try_again'));
        }
        redirect("Cpayroll/manage_salary_generate");
    }
    // Employee Salary Payment
    public function salary_payment() {
        $data['title'] = display('salary');
        $data['sub_title'] = display('salary_payment');
        $config["base_url"] = base_url('Cpayroll/salary_payment');
        $config["total_rows"] = $this->db->count_all('employee_salary_payment');
        $config["per_page"] = 20;
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
        $data['emp_pay'] = $this->Payroll_model->emp_paymentView($config["per_page"], $page);
        $content = $this->parser->parse('payroll/salary_payment_view', $data, true);
        $this->template->full_admin_html_view($content);
    }
    public function EmployeePayment() {
        $sal_id = $this->input->post('sal_id', TRUE);
        $employee_id = $this->input->post('employee_id', TRUE);
        $emplyeeinfo = $this->db->select('first_name,last_name')->from('employee_history')->where('id', $employee_id)->get()->row();
        $data = array('employee_id' => $employee_id, 'Ename' => $emplyeeinfo->first_name . ' ' . $emplyeeinfo->last_name, 'salP_id' => $sal_id,);
        echo json_encode($data);
    }
    public function payconfirm($id = null) {
        $postData = ['emp_sal_pay_id' => $this->input->post('emp_sal_pay_id', true), 'payment_due' => 'paid', 'payment_date' => date('Y-m-d'), 'paid_by' => $this->session->userdata('user_name'), ];
        $emp_id = $this->input->post('employee_id', true);
        $c_name = $this->db->select('first_name,last_name')->from('employee_history')->where('id', $emp_id)->get()->row();
        $c_acc = $emp_id . '-' . $c_name->first_name . '' . $c_name->last_name;
        $coatransactionInfo = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $c_acc)->get()->row();
        $COAID = $coatransactionInfo->HeadCode;
        $cashinhand_credit = array('VNo' => $this->input->post('emp_sal_pay_id', true), 'Vtype' => 'Salary', 'VDate' => date('Y-m-d'), 'COAID' => 1020101, 'Narration' => 'Cash in hand Credit For Employee Salary for-  ' . $c_name->first_name . ' ' . $c_name->last_name, 'Debit' => 0, 'Credit' => $this->input->post('total_salary', true), 'IsPosted' => 1, 'CreateBy' => $this->session->userdata('user_name'), 'CreateDate' => date('Y-m-d H:i:s'), 'IsAppove' => 1);
        $accpayable = array('VNo' => $this->input->post('emp_sal_pay_id', true), 'Vtype' => 'Salary', 'VDate' => date('Y-m-d'), 'COAID' => $COAID, 'Narration' => 'Salary paid For- ' . $c_name->first_name . ' ' . $c_name->last_name, 'Debit' => $this->input->post('total_salary', true), 'Credit' => 0, 'IsPosted' => 1, 'CreateBy' => $this->session->userdata('user_name'), 'CreateDate' => date('Y-m-d H:i:s'), 'IsAppove' => 1);
        //company expense for employee salary
        $expense = array('VNo' => $this->input->post('emp_sal_pay_id', true), 'Vtype' => 'Salary', 'VDate' => date('Y-m-d'), 'COAID' => 403, 'Narration' => 'Salary paid For- ' . $c_name->first_name . ' ' . $c_name->last_name, 'Debit' => $this->input->post('total_salary', true), 'Credit' => 0, 'IsPosted' => 1, 'CreateBy' => $this->session->userdata('user_name'), 'CreateDate' => date('Y-m-d H:i:s'), 'IsAppove' => 1);
        if ($this->Payroll_model->update_payment($postData)) {
            $this->db->insert('acc_transaction', $cashinhand_credit);
            $this->db->insert('acc_transaction', $expense);
            $this->db->insert('acc_transaction', $accpayable);
            $this->session->set_flashdata('message', display('successfully_paid'));
        } else {
            $this->session->set_flashdata('error_message', display('please_try_again'));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function payslip($id = null) {
        $data['title'] = display('salary');
        $data['sub_title'] = display('salary_slip');
        $data['paymentdata'] = $this->Payroll_model->salary_paymentinfo($id);
        $data['addition'] = $this->Payroll_model->salary_addition_fields($data['paymentdata'][0]['employee_id']);
        $data['deduction'] = $this->Payroll_model->salary_deduction_fields($data['paymentdata'][0]['employee_id']);
        $data['amountinword'] = $this->numbertowords->convert_number(intval(str_replace(',', '', $data['paymentdata'][0]['total_salary'])));
        $data['setting'] = $this->Payroll_model->setting();
        $data['company'] = $this->Payroll_model->companyinfo();
        $content = $this->parser->parse('payroll/salary_payslip', $data, true);
        $this->template->full_admin_html_view($content);
    }
}
