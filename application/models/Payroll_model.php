<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payroll_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    //insert beneficial type
   public function add_beneficial($data = array())
    {
        return $this->db->insert('salary_type',$data);
    }
    //beneficial list
  public function salary_setupView()
    {
        return $this->db->select('*')   
            ->from('salary_type')
            ->order_by('salary_type_id', 'desc')
            ->get()
            ->result();
    }
        public function salarysetup_updateForm($id){
        $this->db->where('salary_type_id',$id);
        $query = $this->db->get('salary_type');
        return $query->result_array();
    }
        public function update_benefits($data = array())
    {
        return $this->db->where('salary_type_id', $data["salary_type_id"])
            ->update("salary_type", $data);
    }
    public function benefits_delete($id = null)
    {
        $this->db->where('salary_type_id',$id)
            ->delete('salary_type');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

     public function salary_typeName()
    {
        return $this->db->select('*')   
            ->from('salary_type')
             ->where('salary_type',1)
            ->get()
            ->result();
    }
     public function salary_typedName()
    {
        return $this->db->select('*')   
            ->from('salary_type')
             ->where('salary_type',0)
            ->get()
            ->result();
    }

        public function empdropdown(){
        $this->db->select('*');
        $this->db->from('employee_history');
        $query = $this->db->get();
        $data = $query->result();
       
        $list = array('' => 'Select One...');
        if (!empty($data) ) {
            foreach ($data as $value) {
                $list[$value->id] = $value->first_name." ".$value->last_name;
            } 
        }
        return $list;
    }

    public function sitedropdown(){
        $this->db->select('*');
        $this->db->from('site_information');
        $query = $this->db->get();
        $data = $query->result();
       
        $list = array('' => 'Select One...');
        if (!empty($data) ) {
            foreach ($data as $value) {
                $list[$value->site_id] = $value->name;
            } 
        }
        return $list;
    }

    public function sitedropdownbyid($id){
        $this->db->select('*');
        $this->db->from('site_information')->where('site_id',$id);;
        $query = $this->db->get();
        $data = $query->result();
       
        $list = array();
        if (!empty($data) ) {
            foreach ($data as $value) {
                $list[$value->site_id] = $value->name;
            } 
        }
        return $list;
    }

        public function salary_setup_create($data = array())
    {
        return $this->db->insert('employee_salary_setup', $data);
    }

    public function site_salary_setup_create($data = array())
    {
        return $this->db->insert('site_salary_setup', $data);
    }

         public function salary_setupindex()
    {
             return $this->db->select('count(DISTINCT(sstp.e_s_s_id)) as e_s_s_id,sstp.*,p.id,p.first_name,p.last_name')   
            ->from('employee_salary_setup sstp')
            ->join('employee_history p', 'sstp.employee_id = p.id', 'left')
            ->group_by('sstp.employee_id')
            ->order_by('sstp.salary_type_id', 'desc')
            ->get()
            ->result();
    }

    public function site_salary_setupindex()
    {
             return $this->db->select('count(DISTINCT(sstp.id)) as id,sstp.*,p.id,p.name')   
            ->from('site_salary_setup sstp')
            ->join('site_information p', 'sstp.site_id = p.site_id', 'inner')
            ->group_by('sstp.site_id')
            ->order_by('sstp.salary_type_id', 'desc')
            ->get()
            ->result();
    }

    public function salary_s_updateForm($id){
        $this->db->where('site_id',$id);
        $query = $this->db->get('site_salary_setup');
        return $query->result_array();
    }

    public function site_salary_s_updateForm($id){
        $this->db->where('site_id',$id);
        $query = $this->db->get('site_salary_setup');
        return $query->result_array();
    }

         public function salary_amountlft($id)
    {
        return $result = $this->db->select('employee_salary_setup.*,salary_type.*') 
             ->from('employee_salary_setup')
             ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
             ->where('employee_salary_setup.employee_id',$id)
             ->where('salary_type.salary_type',0)
             ->get()
             ->result();
    }

    public function site_salary_amountlft($id)
    {
        return $result = $this->db->select('site_salary_setup.*,salary_type.*') 
             ->from('site_salary_setup')
             ->join('salary_type','salary_type.salary_type_id=site_salary_setup.salary_type_id')
             ->where('site_salary_setup.site_id',$id)
             ->where('salary_type.salary_type',0)
             ->get()
             ->result();
    }

       public function salary_amount($id)
    {
        return $result = $this->db->select('employee_salary_setup.*,salary_type.*') 
             ->from('employee_salary_setup')
             ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
             ->where('employee_salary_setup.employee_id',$id)
             ->where('salary_type.salary_type',1)
             ->get()
             ->result();
    }

    public function site_salary_amount($id)
    {
        return $result = $this->db->select('site_salary_setup.*,salary_type.*') 
             ->from('site_salary_setup')
             ->join('salary_type','salary_type.salary_type_id=site_salary_setup.salary_type_id')
             ->where('site_salary_setup.site_id',$id)
             ->where('salary_type.salary_type',1)
             ->get()
             ->result();
    }

        // employee Information
    public function employee_informationId($id)
    {
        return $result = $this->db->select('hrate as rate,rate_type')
                       ->from('employee_history')
                       ->where('id',$id)
                       ->get()
                       ->result_array();

    }

    public function update_sal_stup($data = array())
    {
        $term = array('employee_id' => $data['employee_id'], 'salary_type_id' => $data['salary_type_id']);

        return $this->db->where($term)
            ->update("employee_salary_setup", $data);
    }

    public function update_site_sal_stup($data = array())
    {
        $term = array('site_id' => $data['site_id'], 'salary_type_id' => $data['salary_type_id']);

        return $this->db->where($term)
            ->update("site_salary_setup", $data);
    }
    
    public function emp_salstup_delete($id = null)
    {
        $this->db->where('employee_id',$id)
            ->delete('employee_salary_setup');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

    public function site_salstup_delete($id = null)
    {
        $this->db->where('site_id',$id)
            ->delete('site_salary_setup');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

        public function salary_generateView($limit = null, $start = null)
    {
             return $this->db->select('*')   
            ->from('salary_sheet_generate')
            ->group_by('ssg_id')
            ->order_by('ssg_id', 'desc')
            ->limit($limit, $start)
            ->get()
            ->result();
    }
// Salary Generate delete
        public function sal_generate_delete($id = null)
    {
        $this->db->where('ssg_id',$id)
            ->delete('salary_sheet_generate');

            $this->db->where('generate_id',$id)
            ->delete('employee_salary_payment');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

    public function emp_paymentView($limit = null, $start = null)
    {
            return $this->db->select('count(DISTINCT(pment.emp_sal_pay_id)) as emp_sal_pay_id,pment.*,p.id as employee_id,p.first_name,p.last_name')   
            ->from('employee_salary_payment pment')
            ->join('employee_history p', 'pment.employee_id = p.id', 'left')
            ->group_by('pment.emp_sal_pay_id')
            ->order_by('pment.emp_sal_pay_id', 'desc')
            ->limit($limit, $start)
            ->get()
            ->result();
    }

        public function update_payment($data = array())
    {
        return $this->db->where('emp_sal_pay_id', $data["emp_sal_pay_id"])
            ->update("employee_salary_payment", $data);
    }
    
    
    	public function salary_paymentinfo($id = null){
			return $this->db->select('count(DISTINCT(pment.emp_sal_pay_id)) as emp_sal_pay_id,pment.*,p.id as employee_id,p.first_name,p.last_name,desig.designation as position_name,p.hrate as basic,p.rate_type as salarytype')   
            ->from('employee_salary_payment pment')
            ->join('employee_history p', 'pment.employee_id = p.id', 'left')
            ->join('designation desig', 'desig.id = p.designation', 'left')
            ->where('pment.emp_sal_pay_id',$id)
            ->group_by('pment.emp_sal_pay_id')
            ->get()
            ->result_array();

	}
	
		    public function salary_addition_fields($id)
	{
		return $result = $this->db->select('employee_salary_setup.*,salary_type.*')	
			 ->from('employee_salary_setup')
			 ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
	         ->where('employee_salary_setup.employee_id',$id)
	         ->where('salary_type.salary_type',1)
			 ->get()
			 ->result();
	}
	
			 public function salary_deduction_fields($id)
	{
		return $result = $this->db->select('employee_salary_setup.*,salary_type.*')	
			 ->from('employee_salary_setup')
			 ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
	         ->where('employee_salary_setup.employee_id',$id)
	         ->where('salary_type.salary_type',0)
			 ->get()
			 ->result();
	}
	
	public function setting()
	{
		return $this->db->get('web_setting')->result_array();
	}
	
		public function companyinfo()
	{
		return $this->db->get('company_information')->result_array();
	}

    public function check_exist($employee_id){
         return $this->db->select('*')   
            ->from('employee_salary_setup')
            ->where('employee_id',$employee_id)
            ->get()
            ->num_rows();

    }

    public function check_exist_site_salary($site_id){
        return $this->db->select('*')   
           ->from('site_salary_setup')
           ->where('site_id',$employee_id)
           ->get()
           ->num_rows();

   }
}
