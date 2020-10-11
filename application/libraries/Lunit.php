<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lunit {
// ========== its for unit add form page load start ===========
    public function unit_add_form(){
        $CI = & get_instance();
        $CI->load->model('Units');
        $unit_list = $CI->Units->unit_list();
        $i = 0;
        $total = 0;
        if(!empty($unit_list)){
            foreach ($unit_list as $keys => $values){
                $i++;
                $unit_list[$keys]['sl'] = $i + $CI->uri->segment(3);
            }
        }
        $data = array(
            'title'     => display('unit'),
            'unit_list' => $unit_list
        );
        $unitForm = $CI->parser->parse('units/add_unit_form', $data, TRUE);
        return $unitForm;
    }
//============ close ================================
//============ its for unit list show start ==================
    public function unit_list(){
        $CI = & get_instance();
        $CI->load->model('Units');
        $unit_list = $CI->Units->unit_list();
        $i = 0;
        $total = 0;
        if(!empty($unit_list)){
            foreach ($unit_list as $keys => $values){
                $i++;
                $unit_list[$keys]['sl'] = $i + $CI->uri->segment(3);
            }
        }
        $data = array(
            'title'     => display('manage_unit'),
            'unit_list' => $unit_list
        );
        $unitList = $CI->parser->parse('units/manage_unit', $data, TRUE);
        return $unitList;
    }
//============ its for unit list show close==================
//============ its for  unit_editable_data start==================
    public function unit_editable_data($unit_id){
        $CI = & get_instance();
        $CI->load->model('Units');
        $unit_details = $CI->Units->retrieve_unit_editdata($unit_id);
        $data = array(
        'title'     => display('unit_edit'),
        'unit_id'   => $unit_details[0]['unit_id'],
        'unit_name' => $unit_details[0]['unit_name'],
        'status'    => $unit_details[0]['status'],
        );
        $unitEditShow = $CI->parser->parse('units/edit_unit_form', $data, TRUE);
        return $unitEditShow;

    }

}

?>