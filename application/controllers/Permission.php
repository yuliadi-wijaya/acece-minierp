<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('lpermission');
        $this->load->library('session');
        $this->load->model('Permission_model');
         $this->auth->check_admin_auth();
    }

    //Permission form
    public function index()
    {
        $content = $this->lpermission->permission_form();
        $this->template->full_admin_html_view($content);
    }

    public function create()

    {
        $CI =& get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Permission_model');


        $data['title'] = display('add_role_permission');
        /*-----------------------------------*/

        $data = array(
            'type' => $this->input->post('role_id',true),
        );
        $insert_id=$CI->Permission_model->insert_user_entry($data);
        /*-----------------------------------*/

        $fk_module_id = $this->input->post('fk_module_id',true);
        $create = $this->input->post('create',true);
        $read = $this->input->post('read',true);
        $update = $this->input->post('update',true);
        $delete = $this->input->post('delete',true);


        $new_array = array();
        for ($m = 0; $m < sizeof($fk_module_id); $m++) {
            for ($i = 0; $i < sizeof($fk_module_id[$m]); $i++) {
                for ($j = 0; $j < sizeof($fk_module_id[$m][$i]); $j++) {
                    $dataStore = array(
                        'role_id' => $insert_id,
                        'fk_module_id' => $fk_module_id[$m][$i][$j],
                        'create' => (!empty($create[$m][$i][$j]) ? $create[$m][$i][$j] : 0),
                        'read'   =>   (!empty($read[$m][$i][$j]) ? $read[$m][$i][$j] : 0),
                        'update' => (!empty($update[$m][$i][$j]) ? $update[$m][$i][$j] : 0),
                        'delete' => (!empty($delete[$m][$i][$j]) ? $delete[$m][$i][$j] : 0),
                    );
                    array_push($new_array, $dataStore);
                }
            }
        }
        /*-----------------------------------*/
            if ($this->Permission_model->create($new_array)) {
                $id = $this->db->insert_id();
                $this->session->set_flashdata('message', display('role_permission_added_successfully'));
            }
            else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            redirect("Permission/add_role");
    }

    public function user_assign()
    {
        $CI =& get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lpermission');
        $content = $this->lpermission->assign_form();
        $this->template->full_admin_html_view($content);
    }

    public function usercreate($id = null)
    {
        $data['title'] = display('list_Role_setup');
        #-------------------------------#
        $this->form_validation->set_rules('user_id', display('user_id'), 'required');
        $this->form_validation->set_rules('user_type', display('user_type'), 'required|max_length[30]');

        $user_id = $this->input->post('user_id',true);
        $roleid = $this->input->post('user_type',true);
        $create_by = $this->session->userdata('user_id');
        $create_date = date('Y-m-d h:i:s');
        #-------------------------------#
        $data['role_data'] = (Object)$postData = array(
            'id'         => $this->input->post('id',TRUE),
            'user_id'    => $user_id,
            'roleid'     => $roleid,
            'createby'   => $create_by,
            'createdate' => $create_date
        );
        if ($this->form_validation->run()) {
            if (empty($postData['id'])) {
                if ($this->Permission_model->role_create($postData)) {
                    $id = $this->db->insert_id();
                    $this->session->set_flashdata('exception', display('please_try_again'));
                } else {


                }
                $this->session->set_flashdata('message', display('save_successfully'));
                redirect("Permission/user_assign");

            } else {

                $this->permission->method('dashboard', 'update')->redirect();

                if ($this->user_model->update_role($postData)) {
                   
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }

                redirect("dashboard/user/create_user_role/" . $postData['id']);
            }

        } else {
            if (!empty($id)) {
                $data['title'] = display('update');
                $data['role']  = $this->user_model->findById($id);
            }
            $data['module']    = "dashboard";
            $data['user_list'] = $this->user_model->dropdown();
            $data['role_list'] = $this->user_model->role_list();
            $data['roles']     = $this->user_model->viewRole();
            $data['page']      = "user/role_setupform";
            redirect("Permission/user_assign");
        }
    }

    public function select_to_rol($id)
    {
        $role_reult = $this->db->select('sec_role.*,sec_userrole.*')
            ->from('sec_userrole')
            ->join('sec_role', 'sec_userrole.roleid=sec_role.id')
            ->where('sec_userrole.user_id', $id)
            ->group_by('sec_role.type')
            ->get()
            ->result();
        if ($role_reult) {
            $html = "";
            $html .= "<table id=\"dataTableExample2\" class=\"table table-bordered table-striped table-hover\">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>role_name</th>
                            </tr>
                        </thead>
                       <tbody>";
            $i = 1;
            foreach ($role_reult as $key => $role) {
                $html .= "<tr>
                                <td>$i</td>
                                <td>$role->type</td>
                            </tr>";
                $i++;
            }
            $html .= "</tbody>
                    </table>";
        }
        echo json_encode($html);
    }

    public function add_role()
    {
        $content = $this->lpermission->role_form();
        $this->template->full_admin_html_view($content);
    }
    public function role_list(){

        $content = $this->lpermission->role_view();
        $this->template->full_admin_html_view($content);
    }
    public function insert_role_user(){
        $CI =& get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lpermission');

        $data = array(
            'type' => $this->input->post('type',TRUE),
        );

        $this->lpermission->roleinsert_user($data);
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect("Permission/add_role");
    }

    public function edit_user($id){

        $content = $this->lpermission->user_edit_data($id);
        $this->template->full_admin_html_view($content);
    }

    public function role_update(){
        $this->load->model('Permission_model');
        $id = $this->input->post('id',TRUE);
        $data = array(
            'type' => $this->input->post('type',TRUE),

        );
        $this->Permission_model->update_role($data, $id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect("Permission/add_role");
    }

    public function role_delete($id){
        $this->load->model('Permission_model');
        $role=$this->Permission_model->delete_role($id);
        $role_per=$this->Permission_model->delete_role_permission($id);

             $data=array(
                 'role'     => $role,
                 'role_per' => $role_per
             );

        if($data){
            $this->session->set_userdata(array('message' => display('successfully_delete')));
        }
        else{
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("Permission/role_list");
    }
    public function edit_role($id){

        $content = $this->lpermission->edit_role_data($id);
        $this->template->full_admin_html_view($content);
    }

    public function update(){

        $CI =& get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Permission_model');

        $id = $this->input->post('rid',TRUE);

        $data = array(
            'type' => $this->input->post('role_id',TRUE),
            'id'   => $this->input->post('rid',TRUE),
        );

        $CI->Permission_model->role_update($data,$id);


        $fk_module_id = $this->input->post('fk_module_id',true);
        $create       = $this->input->post('create',true);
        $read         = $this->input->post('read',true);
        $update       = $this->input->post('update',true);
        $delete       = $this->input->post('delete',true);


        $new_array = array();
        for ($m = 0; $m < sizeof($fk_module_id); $m++) {
            for ($i = 0; $i < sizeof($fk_module_id[$m]); $i++) {
                for ($j = 0; $j < sizeof($fk_module_id[$m][$i]); $j++) {
                    $dataStore = array(
                        'role_id' =>$this->input->post('rid',TRUE),
                        'fk_module_id' => $fk_module_id[$m][$i][$j],
                        'create' => (!empty($create[$m][$i][$j]) ? $create[$m][$i][$j] : 0),
                        'read'   =>   (!empty($read[$m][$i][$j]) ? $read[$m][$i][$j] : 0),
                        'update' => (!empty($update[$m][$i][$j]) ? $update[$m][$i][$j] : 0),
                        'delete' => (!empty($delete[$m][$i][$j]) ? $delete[$m][$i][$j] : 0),
                    );
                    array_push($new_array, $dataStore);
                }
            }
        }
        if($this->Permission_model->create($new_array)){
            $id = $this->db->insert_id();
            $this->session->set_flashdata('message', display('role_permission_updated_successfully'));
        }
        else{
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("Permission/role_list");
    }
    public function module_form($id = null){
    if(!empty($id)){
            $data['title'] = 'Module Update';
        }else{
             $data['title'] = 'Add Module';
        }
    $data['moduleinfo'] = $this->Permission_model->moduleinfo($id);
    $content = $this->parser->parse('permission/add_module', $data, true);
    $this->template->full_admin_html_view($content); 
    }

     public function add_module(){
    $data = [
   'id'          => $this->input->post('id',TRUE),
   'name'        => $this->input->post('module_name',true),
   'description' => null,
   'image'       => null,
   'directory'   => null,
   'status'      => 1,
    ];
    if(!empty($this->input->post('id',TRUE))){
         $this->db->where('id',$this->input->post('id',TRUE))
         ->update('module',$data);
          $this->session->set_userdata(array('message' => display('successfully_updated')));
          redirect("Permission/module_form");
    }else{
        $this->db->insert('module',$data);
         $this->session->set_userdata(array('message' => display('successfully_inserted')));
         redirect("Permission/module_form");
    }

    }
    //Menu add 
    public function menu_form($id = null){
      if(!empty($id)){
            $data['title'] = 'Menu Update';
        }else{
             $data['title'] = 'Add Menu';
        }
    $data['module_list'] = $this->Permission_model->module_list($id);
    $data['menuinfo'] = $this->Permission_model->menuinfo($id);
    $content = $this->parser->parse('permission/add_menu', $data, true);
    $this->template->full_admin_html_view($content);    
    }
    // menu submit info
    public function add_menu(){
     $data = [
   'id'          => $this->input->post('id',true),
   'mid'         => $this->input->post('module_id',true),
   'name'        => $this->input->post('menu_name',true),
   'description' => null,
   'image'       => null,
   'directory'   => null,
   'status'      => 1,
    ];
    if(!empty($this->input->post('id',TRUE))){
         $this->db->where('id',$this->input->post('id',true))
         ->update('sub_module',$data);
          $this->session->set_userdata(array('message' => display('successfully_updated')));
          redirect("Permission/menu_form");
    }else{
        $this->db->insert('sub_module',$data);
         $this->session->set_userdata(array('message' => display('successfully_inserted')));
         redirect("Permission/menu_form");
    }   
    }
}