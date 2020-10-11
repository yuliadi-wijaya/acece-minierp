<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cattendance extends CI_Controller {
    public $menu;
    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('session');
        $this->load->library('lattendance');
        $this->load->model('Hrm_model');
        $this->load->model('Attendance_model');
        $this->load->model('Web_settings');
        $this->auth->check_admin_auth();
    }
    //Designation form
    public function add_attendance() {
        $data['title'] = display('attendance');
        $data['sub_title'] = display('attendance');
        $data['att_list'] = $this->Attendance_model->attendance_list();
        $data['dropdownatn'] = $this->Attendance_model->employee_list();
        $content = $this->parser->parse('attendance/attendance_view', $data, true);
        $this->template->full_admin_html_view($content);
    }
    public function single_checkin() {
        $data['title'] = display('attendance');
        $data['sub_title'] = display('checkin');
        $data['dropdownatn'] = $this->Attendance_model->employee_list();
        $content = $this->parser->parse('attendance/attendance_checkin', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // create designation
    public function create_atten() {
        $this->load->model('Web_settings');
        $this->form_validation->set_rules('employee_id', display('employee_id'), 'required|max_length[100]');
        $this->form_validation->set_rules('details', display('details'), 'max_length[250]');
        $Web_settings = $this->Web_settings->retrieve_setting_editdata();
        date_default_timezone_set($Web_settings[0]['timezone']);
        #-------------------------------#
        $date = $this->input->post('date', true);
        $signin = $this->input->post('intime', true);
        $time = substr($signin, 0, -2);
        $thelper = substr($signin, -2, 2);
        $intime = str_replace(' ', '', $time);
        $inputintime = $intime . ' ' . $thelper;
        $check_exist = $this->Attendance_model->check_exist($this->input->post('employee_id', true), $date);
        if ($check_exist > 0) {
            $this->session->set_flashdata('error_message', display('already_exist'));
            redirect("Cattendance/single_checkin");
        }
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            $postData = ['employee_id' => $this->input->post('employee_id', true), 'date' => $date, 'sign_in' => $inputintime, ];
            if ($this->Attendance_model->atten_create($postData)) {
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('error_message', display('please_try_again'));
            }
            redirect("Cattendance/add_attendance");
        }
        redirect("Cattendance/add_attendance");
    }
    public function checkout($id = null) {
        $data['title'] = display('attendance');
        $data['sub_title'] = display('checkout');

        $this->load->model('Web_settings');
        $Web_settings = $this->Web_settings->retrieve_setting_editdata();
        $this->form_validation->set_rules('sign_out', display('sign_out'), 'required|max_length[100]');
        date_default_timezone_set($Web_settings[0]['timezone']);
        $s_out = $this->input->post('sign_out', true);
        $time = substr($s_out, 0, -2);
        $thelper = substr($s_out, -2, 2);
        $outtime = str_replace(' ', '', $time);
        $sign_out = $outtime . ' ' . $thelper;
        $sign_in = $this->input->post('sign_in', true);
        $in = new DateTime($sign_in);
        $Out = new DateTime($sign_out);
        $interval = $in->diff($Out);
        $stay = $interval->format('%H:%I');
        if ($this->form_validation->run() === true) {
            $postData = ['att_id' => $this->input->post('att_id', true), 'sign_out' => $sign_out, 'staytime' => $stay, ];
            $update = $this->db->where('att_id', $this->input->post('att_id', true))->update("attendance", $postData);
            if ($update) {
                $this->session->set_flashdata('message', display('successfully_checkout'));
                redirect("Cattendance/add_attendance");
            }
        } else {
            $data['attendata'] = $this->Attendance_model->attendance_data_checkin($id);
            $content = $this->parser->parse('attendance/attendance_checkout', $data, true);
            $this->template->full_admin_html_view($content);
        }
    }
    public function manage_attendance() {
        $data['title'] = display('attendance');
        $data['sub_title'] = display('manage_attendance');
        $data['attendance_list'] = $this->Attendance_model->manage_attendance_list();
        $content = $this->parser->parse('attendance/manage_attendance', $data, true);
        $this->template->full_admin_html_view($content);;
    }
    // attendance delete
    public function delete_attendance($id) {
        $this->Attendance_model->delete_attendance($id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect("Cattendance/add_attendance");
    }
    public function edit_atn_form($id = null) {
        $data['title'] = display('attendance');
        $data['sub_title'] = display('update_attendance');

        $this->load->model('Web_settings');
        $Web_settings = $this->Web_settings->retrieve_setting_editdata();
        date_default_timezone_set($Web_settings[0]['timezone']);
        $this->form_validation->set_rules('att_id', null, 'required|max_length[11]');
        $this->form_validation->set_rules('employee_id', display('employee_id'), 'required');
        $this->form_validation->set_rules('date', display('date'), 'required');
        $this->form_validation->set_rules('sign_in', display('sign_in'), 'required');
        $this->form_validation->set_rules('sign_out', display('sign_out'));
        $this->form_validation->set_rules('staytime', display('staytime'));
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            $sign_out = date("h:i a", strtotime($this->input->post('sign_out', true)));
            $sign_in = date("h:i a", strtotime($this->input->post('sign_in', true)));
            $in = new DateTime($sign_in);
            $Out = new DateTime($sign_out);
            $interval = $in->diff($Out);
            $stay = $interval->format('%H:%I');
            $postData = ['att_id' => $this->input->post('att_id', true), 'employee_id' => $this->input->post('employee_id', true), 'date' => $this->input->post('date', true), 'sign_in' => $sign_in, 'sign_out' => $sign_out, 'staytime' => $stay, ];
            if ($this->Attendance_model->update_attn($postData)) {
                $this->session->set_flashdata('message', display('successfully_updated'));
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            redirect("Cattendance/manage_attendance");
        } else {
            $data['data'] = $this->Attendance_model->attn_updateForm($id);
            $data['dropdownatn'] = $this->Attendance_model->employee_list();
            $data['query'] = $this->Attendance_model->get_atn_dropdown($id);
            $content = $this->parser->parse('attendance/attendance_update_form', $data, true);
            $this->template->full_admin_html_view($content);
        }
    }
    //csv attendance bulk upload
    function attendance_bulkupload() {
        $count = 0;
        $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");
        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {
            while ($csv_line = fgetcsv($fp, 1024)) {
                //keep this if condition if you want to remove the first row
                for ($i = 0, $j = count($csv_line);$i < $j;$i++) {
                    $insert_csv = array();
                    $insert_csv['employee_id'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    $insert_csv['date'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                    $insert_csv['sign_in'] = (!empty($csv_line[2]) ? $csv_line[2] : null);
                    $insert_csv['sign_out'] = (!empty($csv_line[3]) ? $csv_line[3] : null);
                    $insert_csv['staytime'] = (!empty($csv_line[4]) ? $csv_line[4] : null);
                }
                $date = date("Y-m-d", strtotime($insert_csv['date']));
                $sign_out = date("h:i:s a", strtotime($insert_csv['sign_out']));
                $sign_in = date("h:i:s a", strtotime($insert_csv['sign_in']));
                $in = new DateTime($sign_in);
                $Out = new DateTime($sign_out);
                $interval = $in->diff($Out);
                $stay = $interval->format('%H:%I:%S');
                $attendancedata = array('employee_id' => $insert_csv['employee_id'], 'date' => $date, 'sign_in' => $sign_in, 'sign_out' => $sign_out, 'staytime' => $stay,);
                if ($count > 0) {
                    $this->db->insert('attendance', $attendancedata);
                }
                $count++;
            }
        }
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Cattendance/manage_attendance'));
    }
    // attendance report
    public function attendance_report() {
        $data['title'] = display('attendance');
        $data['sub_title'] = display('attendance_report');
        $data['attendance_list'] = $this->Attendance_model->attendance_list();
        $data['dropdownatn'] = $this->Attendance_model->employee_list();
        $content = $this->parser->parse('attendance/attendance_list', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // attendance report view
    public function datewiseattendancereport() {
        $data['title'] = display('attendance_report');
        $format_start_date = $this->input->post('start_date', true);
        $format_end_date = $this->input->post('end_date', true);
        $data['from_date'] = $format_start_date;
        $data['to_date'] = $format_end_date;
        $data['query'] = $this->Attendance_model->datewiseReport($format_start_date, $format_end_date);
        $content = $this->parser->parse('attendance/user_views_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
    //Employee wise attendance report
    public function employeewise_att_report() {
        $data['title'] = display('attendance_report');
        $id = $this->input->post('employee_id', true);
        $start_date = $this->input->post('s_date', true);
        $end_date = $this->input->post('e_date', true);
        $data['employee_id'] = $id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['ab'] = $this->Attendance_model->emp_information($id);
        $data['query'] = $this->Attendance_model->search($id, $start_date, $end_date);
        $content = $this->parser->parse('attendance/att_reportview', $data, true);
        $this->template->full_admin_html_view($content);
    }
    // In Time wise attendance report
    public function AtnTimeReport_view() {
        $data['title'] = display('attendance_report');
        $date = $this->input->post('date', true);
        $start_time = $this->input->post('s_time', true);
        $end_time = $this->input->post('e_time', true);
        $data['date'] = $date;
        $data['start'] = $start_time;
        $data['end'] = $end_time;
        $data['query'] = $this->Attendance_model->search_intime($date, $start_time, $end_time);
        $content = $this->parser->parse('attendance/Date_time_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
}
