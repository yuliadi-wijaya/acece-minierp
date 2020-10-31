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
        $this->load->model('Workhour_model');
        $this->load->model('Web_settings');
        $this->load->model('Site');
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
        $data['dropdownsite'] = $this->Site->get_site_list_dropdown();
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
            $postData = ['employee_id' => $this->input->post('employee_id', true), 'date' => $date, 'sign_in' => $inputintime, 'site_id' => $this->input->post('site_id', true)];
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
            
            $flag = true;
            $data= array();
            while ($csv_line = fgetcsv($fp, 10000, ",")) {
                //keep this if condition if you want to remove the first row
                if($flag) { $flag = false; continue; }
                for ($i = 0, $j = count($csv_line);$i < $j;$i++) {
                    $insert_csv = array();
                    $insert_csv['employee_id'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    $insert_csv['date'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                    $insert_csv['sign_in'] = (!empty($csv_line[2]) ? $csv_line[2] : null);
                    $insert_csv['sign_out'] = (!empty($csv_line[3]) ? $csv_line[3] : null);
                    $insert_csv['site_id'] = (!empty($csv_line[4]) ? $csv_line[4] : null);
                }
                $date = date("Y-m-d", strtotime($insert_csv['date']));
                $sign_out = date("h:i a", strtotime($insert_csv['sign_out']));
                $sign_in = date("h:i a", strtotime($insert_csv['sign_in']));
                $in = new DateTime($sign_in);
                $Out = new DateTime($sign_out);
                $interval = $in->diff($Out);
                $stay = $interval->format('%H:%I');
                $attendancedata = array('employee_id' => $insert_csv['employee_id'], 'date' => $date, 'sign_in' => $sign_in, 'sign_out' => $sign_out, 'staytime' => $stay, 'site_id' => $insert_csv['site_id'],);
                
                array_push($data,$attendancedata);
                   
                
            }
            
            $this->db->insert_batch('attendance', $data);
            
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
        $data['site'] = $this->Workhour_model->work_hour_list();
        $mapData = array();
        
        //Map with key site_id and day , format id_day
        foreach( $data['site'] as $d) {
            $mapData[$d['site_id']."_".$d['day']] = $d;
        }

        $data['query'] = $this->Attendance_model->datewiseReport($format_start_date, $format_end_date);
        // TODO Process Data

        echo "<pre>";
        // print_r( $mapData);
        $data['result']=array();
        foreach ($data['query'] as $d) {

            $d->day = date('N', strtotime($d->date));
            if (DateTime::createFromFormat('H : i A',$mapData[$d->site_id."_".$d->day]['sign_in']) > DateTime::createFromFormat('H:i a',$d->sign_in) && $mapData[$d->site_id."_".$d->day]['is_active'] == 1) {
                if (DateTime::createFromFormat('H : i A',$mapData[$d->site_id."_".$d->day]['sign_out']) < DateTime::createFromFormat('H:i a',$d->sign_out)) {
                    $d->basic = 1;
                    $d->meal=1;
                    $timestamp1 = DateTime::createFromFormat('H:i a',$d->sign_out);
                    $timestamp2 = DateTime::createFromFormat('H : i A',$mapData[$d->site_id."_".$d->day]['sign_out']); 
                    $ot = floor(abs($timestamp2->getTimestamp() - $timestamp1->getTimestamp())/(60*60));
                    if ($ot > 0) {
                        $d->ot=$ot;
                        //Meal 2 ???
                    } else {
                        $d->ot=0;
                        
                    }
                    $d->meal2=0;
                } else {
                    $d->basic = 0;
                    $d->meal=0;
                    $d->ot=0;
                    $d->meal2=0;
                }
            } else {
                $d->basic = 0;
                $d->meal=0;
                $d->ot=0;
                $d->meal2=0;
            };

           
            $data['result'][$d->employee_id][$d->date] = $d;
            
           

        }
        print_r($data['result']);
        echo "</pre>";
        // die();
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

    public function add_working_hour() {
        $data['title'] = display('working_hour');
        $data['sub_title'] = display('add_working_hour');
        $data['site_list'] = $this->Site->get_site_list();
        $content = $this->parser->parse('attendance/working_hour_form', $data, true);
        $this->template->full_admin_html_view($content);
    }

    public function manage_working_hour() {
        $data['title'] = display('working_hour');
        $data['sub_title'] = display('manage_working_hour');
        $data['site_list'] = $this->Site->get_site_list_has_working_hour();
        $content = $this->parser->parse('attendance/manage_working_hour', $data, true);
        $this->template->full_admin_html_view($content);
    }

    public function get_data_working_hour() {
        $data['title'] = display('working_hour');
        $data['sub_title'] = display('manage_working_hour');
        $data['site_list'] = $this->Site->get_site_list_has_working_hour();
        $site_id = $this->input->post('site', true);
       
        if ($site_id == "Select One") {
            
            $this->session->set_flashdata('error_message', display('please_try_again'));
           
            redirect("Cattendance/manage_working_hour");
        } else {
            // Get detail working
            $data['sub_title'] = display('manage_working_hour');
            $data['site_list'] = $this->Site->get_site_list_has_working_hour();
            $data['site_work_hour'] = $this->Site->get_site_work_hour_by_id($site_id);
            $this->session->set_flashdata('data', $data['site_work_hour']);
            
            redirect("Cattendance/manage_working_hour");
        }
    }

    public function update_edit_working_hour(){
        $this->form_validation->set_rules('site', display('site'), 'required');
        
        $monday = array(
            'day' => 1,
            'site_id' => $this->input->post('site'),
            'sign_in' =>  $this->input->post('monday_checkin', true),
            'sign_out' =>  $this->input->post('monday_checkout', true),
            'count' =>  empty($this->input->post('monday_count')) ? 0 :$this->input->post('monday_count'),
            'is_active' =>   empty($this->input->post('monday_isactive')) ? 0 : 1
         );
         if( DateTime::createFromFormat('H : i A',$monday['sign_in']) >= DateTime::createFromFormat('H : i A',$monday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $tuesday = array(
            'day' => 2,
            'site_id' => $this->input->post('site'),
            'sign_in' =>  $this->input->post('tuesday_checkin', true),
            'sign_out' =>  $this->input->post('tuesday_checkout', true),
            'count' =>  empty($this->input->post('tuesday_count')) ? 0 :$this->input->post('tuesday_count') ,
            'is_active' =>   empty($this->input->post('tuesday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$tuesday['sign_in']) >= DateTime::createFromFormat('H : i A',$tuesday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $wednesday = array(
            'day' => 3,
            'site_id' => $this->input->post('site'),
            'sign_in' =>  $this->input->post('wednesday_checkin', true),
            'sign_out' => $this->input->post('wednesday_checkout', true),
            'count' =>  empty($this->input->post('wednesday_count')) ? 0 :$this->input->post('wednesday_count'),
            'is_active' =>  empty($this->input->post('wednesday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$wednesday['sign_in']) >= DateTime::createFromFormat('H : i A',$wednesday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $thursday = array(
            'day' => 4,
            'site_id' => $this->input->post('site'),
            'sign_in' => $this->input->post('thursday_checkin', true),
            'sign_out' =>  $this->input->post('thursday_checkout', true),
            'count' =>  empty($this->input->post('thursday_count')) ? 0 :$this->input->post('thursday_count'),
            'is_active' =>  empty($this->input->post('thursday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$thursday['sign_in']) >= DateTime::createFromFormat('H : i A',$thursday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $friday = array(
            'day' => 5,
            'site_id' => $this->input->post('site'),
           'sign_in' =>  $this->input->post('friday_checkin', true),
            'sign_out' => $this->input->post('friday_checkout', true),
            'count' =>  empty($this->input->post('friday_count')) ? 0 :$this->input->post('friday_count'),
            'is_active' =>  empty($this->input->post('friday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$friday['sign_in']) >= DateTime::createFromFormat('H : i A',$friday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $saturday = array(
            'day' => 6,
            'site_id' => $this->input->post('site'),
            'sign_in' =>  $this->input->post('saturday_checkin', true),
            'sign_out' =>  $this->input->post('saturday_checkout', true),
            'count' =>  empty($this->input->post('saturday_count')) ? 0 :$this->input->post('saturday_count'),
            'is_active' =>  empty($this->input->post('saturday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$saturday['sign_in']) >= DateTime::createFromFormat('H : i A',$saturday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $sunday = array(
            'day' => 7,
            'site_id' => $this->input->post('site'),
            'sign_in' =>  $this->input->post('sunday_checkin'),
            'sign_out' =>  $this->input->post('sunday_checkout'),
            'count' =>  empty($this->input->post('sunday_count')) ? 0 :$this->input->post('sunday_count'),
            'is_active' =>  empty($this->input->post('sunday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$sunday['sign_in']) >= DateTime::createFromFormat('H : i A',$sunday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $holiday = array(
            'day' => 8,
            'site_id' => $this->input->post('site'),
            'sign_in' =>  $this->input->post('holiday_checkin'),
            'sign_out' =>  $this->input->post('holiday_checkout'),
            'count' =>  empty($this->input->post('holiday_count')) ? 0 :$this->input->post('holiday_count'),
            'is_active' => empty($this->input->post('holiday_isactive')) ? 0 : 1
        );
        if( DateTime::createFromFormat('H : i A',$holiday['sign_in']) >= DateTime::createFromFormat('H : i A',$holiday['sign_out'])){
            $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
            redirect("Cattendance/manage_working_hour");
         }
        $data = array(
            $monday,
            $tuesday,
            $wednesday,
            $thursday,
            $friday,
            $saturday,
            $sunday,
            $holiday
        );

       

        $update = $this->Workhour_model->update_workhour_balk($data) ;
       
            if ($update) {
                $this->session->set_flashdata('message', display('update_successfully'));
            } else {
                $this->session->set_flashdata('error_message', display('please_try_again'));
            }

       
        redirect("Cattendance/add_working_hour");
    }

    

    public function submit_working_hour() {
        $this->form_validation->set_rules('site', display('site'), 'required');

        // Validate site_id already have working hour or not

        $working_data = $this->Workhour_model->work_hour_list_by_site_id($this->input->post('site'));
        if ($working_data) {
            $this->session->set_flashdata('error_message', display('already_exist'));
            redirect("Cattendance/add_working_hour");
        } else {
            
            // Insert All data
            //TODO check sign_in and sign_out data
            
             $monday = array(
                'day' => 1,
                'site_id' => $this->input->post('site'),
                'sign_in' =>  $this->input->post('monday_checkin', true),
                'sign_out' =>  $this->input->post('monday_checkout', true),
                'count' =>  empty($this->input->post('monday_count')) ? 0 :$this->input->post('monday_count'),
                'is_active' =>   empty($this->input->post('monday_isactive')) ? 0 : 1
             );
             if( DateTime::createFromFormat('H : i A',$monday['sign_in']) >= DateTime::createFromFormat('H : i A',$monday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $tuesday = array(
                'day' => 2,
                'site_id' => $this->input->post('site'),
                'sign_in' =>  $this->input->post('tuesday_checkin', true),
                'sign_out' =>  $this->input->post('tuesday_checkout', true),
                'count' =>  empty($this->input->post('tuesday_count')) ? 0 :$this->input->post('tuesday_count') ,
                'is_active' =>   empty($this->input->post('tuesday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$tuesday['sign_in']) >= DateTime::createFromFormat('H : i A',$tuesday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $wednesday = array(
                'day' => 3,
                'site_id' => $this->input->post('site'),
                'sign_in' =>  $this->input->post('wednesday_checkin', true),
                'sign_out' => $this->input->post('wednesday_checkout', true),
                'count' =>  empty($this->input->post('wednesday_count')) ? 0 :$this->input->post('wednesday_count'),
                'is_active' =>  empty($this->input->post('wednesday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$wednesday['sign_in']) >= DateTime::createFromFormat('H : i A',$wednesday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $thursday = array(
                'day' => 4,
                'site_id' => $this->input->post('site'),
                'sign_in' => $this->input->post('thursday_checkin', true),
                'sign_out' =>  $this->input->post('thursday_checkout', true),
                'count' =>  empty($this->input->post('thursday_count')) ? 0 :$this->input->post('thursday_count'),
                'is_active' =>  empty($this->input->post('thursday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$thursday['sign_in']) >= DateTime::createFromFormat('H : i A',$thursday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $friday = array(
                'day' => 5,
                'site_id' => $this->input->post('site'),
               'sign_in' =>  $this->input->post('friday_checkin', true),
                'sign_out' => $this->input->post('friday_checkout', true),
                'count' =>  empty($this->input->post('friday_count')) ? 0 :$this->input->post('friday_count'),
                'is_active' =>  empty($this->input->post('friday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$friday['sign_in']) >= DateTime::createFromFormat('H : i A',$friday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $saturday = array(
                'day' => 6,
                'site_id' => $this->input->post('site'),
                'sign_in' =>  $this->input->post('saturday_checkin', true),
                'sign_out' =>  $this->input->post('saturday_checkout', true),
                'count' =>  empty($this->input->post('saturday_count')) ? 0 :$this->input->post('saturday_count'),
                'is_active' =>  empty($this->input->post('saturday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$saturday['sign_in']) >= DateTime::createFromFormat('H : i A',$saturday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $sunday = array(
                'day' => 7,
                'site_id' => $this->input->post('site'),
                'sign_in' =>  $this->input->post('sunday_checkin'),
                'sign_out' =>  $this->input->post('sunday_checkout'),
                'count' =>  empty($this->input->post('sunday_count')) ? 0 :$this->input->post('sunday_count'),
                'is_active' =>  empty($this->input->post('sunday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$sunday['sign_in']) >= DateTime::createFromFormat('H : i A',$sunday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            $holiday = array(
                'day' => 8,
                'site_id' => $this->input->post('site'),
                'sign_in' =>  $this->input->post('holiday_checkin'),
                'sign_out' =>  $this->input->post('holiday_checkout'),
                'count' =>  empty($this->input->post('holiday_count')) ? 0 :$this->input->post('holiday_count'),
                'is_active' => empty($this->input->post('holiday_isactive')) ? 0 : 1
            );
            if( DateTime::createFromFormat('H : i A',$holiday['sign_in']) >= DateTime::createFromFormat('H : i A',$holiday['sign_out'])){
                $this->session->set_flashdata('error_message', 'Sign Out Time Cannot Earlier than Sign In Time');
                redirect("Cattendance/add_working_hour");
             }
            

            $data = array(
                $monday,
                $tuesday,
                $wednesday,
                $thursday,
                $friday,
                $saturday,
                $sunday,
                $holiday
            );
            $error_create = $this->Workhour_model->workhour_create($data) ;
            if ($error_create) {
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('error_message', display('please_try_again'));
            }

            redirect("Cattendance/add_working_hour");

        };

         redirect("Cattendance/add_working_hour");
    }
}
