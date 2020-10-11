<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'accounts_model','Web_settings'
		));	
    $this->load->library('laccounting');
    $this->auth->check_admin_auth();
	}


		public function C_O_A() 
	{ 
        $content = $this->laccounting->coa_form();
        $this->template->full_admin_html_view($content);
	}


     // tree view controller
    public function show_tree($id = null){
		   $content = $this->laccounting->treeview_form();
        $this->template->full_admin_html_view($content);
    }
  

  public function selectedform($id){
        $CI = & get_instance();
        $CI->load->model('Accounts_model');
		   $role_reult = $CI->Accounts_model->treeview_selectform($id);
					$baseurl = base_url().'accounts/insert_coa';


		if ($role_reult){
			$html = "";
			$html .= form_open_multipart('accounts/insert_coa','id="form"');
      $html .= "<div id=\"newData\">
   <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr>
        <td>Head Code</td>
        <td><input type=\"text\" name=\"txtHeadCode\" id=\"txtHeadCode\" class=\"form_input\"  value=\"".$role_reult->HeadCode."\" readonly=\"readonly\"/></td>
      </tr>
      <tr>
        <td>Head Name</td>
        <td><input type=\"text\" name=\"txtHeadName\" id=\"txtHeadName\" class=\"form_input\" value=\"".$role_reult->HeadName."\"/>
<input type=\"hidden\" name=\"HeadName\" id=\"HeadName\" class=\"form_input\" value=\"".$role_reult->HeadName."\"/>
        </td>
      </tr>
      <tr>
        <td>Parent Head</td>
        <td><input type=\"text\" name=\"txtPHead\" id=\"txtPHead\" class=\"form_input\" readonly=\"readonly\" value=\"".$role_reult->PHeadName."\"/></td>
      </tr>
      <tr>

        <td>Head Level</td>
        <td><input type=\"text\" name=\"txtHeadLevel\" id=\"txtHeadLevel\" class=\"form_input\" readonly=\"readonly\" value=\"".$role_reult->HeadLevel."\"/></td>
      </tr>
       <tr>
        <td>Head Type</td>
        <td><input type=\"text\" name=\"txtHeadType\" id=\"txtHeadType\" class=\"form_input\" readonly=\"readonly\" value=\"".$role_reult->HeadType."\"/></td>
      </tr>

       <tr>
         <td>&nbsp;</td>
         <td><input type=\"checkbox\" name=\"IsTransaction\" value=\"1\" id=\"IsTransaction\" size=\"28\"  onchange=\"IsTransaction_change()\"";
         if($role_reult->IsTransaction==1){ $html .="checked";}
          $html .= "/><label for=\"IsTransaction\"> IsTransaction</label>
         <input type=\"checkbox\" value=\"1\" name=\"IsActive\" id=\"IsActive\" size=\"28\"";
          if($role_reult->IsActive==1){ $html .="checked";}
          $html .= "/><label for=\"IsActive\"> IsActive</label>
         <input type=\"checkbox\" value=\"1\" name=\"IsGL\" id=\"IsGL\" size=\"28\" onchange=\"IsGL_change();\"";
         if($role_reult->IsGL==1){ $html .="checked";}
          $html .= "/><label for=\"IsGL\"> IsGL</label>

        </td>";
      $html .= "</tr>
       <tr>
                    <td>&nbsp;</td>
                    <td>";
                     $html .="<input type=\"button\" name=\"btnNew\" id=\"btnNew\" value=\"New\" onClick=\"newHeaddata(".$role_reult->HeadCode.")\" />
                      <input type=\"submit\" name=\"btnSave\" id=\"btnSave\" value=\"Save\" disabled=\"disabled\"/>";
                     
          $html .=" <input type=\"submit\" name=\"btnUpdate\" id=\"btnUpdate\" value=\"Update\" />";
    $html .= "</tr></table>
 </form>
			";
		}

		echo json_encode($html);
	}

  public function newform($id){

    $newdata = $this->db->select('*')
            ->from('acc_coa')
            ->where('HeadCode',$id)
            ->get()
            ->row();

           
  $newidsinfo = $this->db->select('*,count(HeadCode) as hc')
            ->from('acc_coa')
            ->where('PHeadName',$newdata->HeadName)
            ->get()
            ->row();

$nid  = $newidsinfo->hc;
$n =$nid + 1;
if ($n / 10 < 1)
  $HeadCode = $id . "0" . $n;
else
  $HeadCode = $id . $n;

  $info['headcode'] =  $HeadCode;
  $info['rowdata'] =  $newdata;
  $info['headlabel'] =  $newdata->HeadLevel+1;
    echo json_encode($info);
  }

  public function insert_coa(){
    $headcode    = $this->input->post('txtHeadCode',TRUE);
    $HeadName    = $this->input->post('txtHeadName',TRUE);
    $PHeadName   = $this->input->post('txtPHead',TRUE);
    $HeadLevel   = $this->input->post('txtHeadLevel',TRUE);
    $txtHeadType = $this->input->post('txtHeadType',TRUE);
    $isact       = $this->input->post('IsActive',TRUE);
    $IsActive    = (!empty($isact)?$isact:0);
    $trns        = $this->input->post('IsTransaction',TRUE);
    $IsTransaction = (!empty($trns)?$trns:0);
    $isgl        = $this->input->post('IsGL',TRUE);
     $IsGL       = (!empty($isgl)?$isgl:0);
    $createby=$this->session->userdata('user_id');
    $createdate=date('Y-m-d H:i:s');
       $postData = array(
      'HeadCode'       =>  $headcode,
      'HeadName'       =>  $HeadName,
      'PHeadName'      =>  $PHeadName,
      'HeadLevel'      =>  $HeadLevel,
      'IsActive'       =>  $IsActive,
      'IsTransaction'  =>  $IsTransaction,
      'IsGL'           =>  $IsGL,
      'HeadType'       => $txtHeadType,
      'IsBudget'       => 0,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
    ); 
 $upinfo = $this->db->select('*')
            ->from('acc_coa')
            ->where('HeadCode',$headcode)
            ->get()
            ->row();
            if(empty($upinfo)){
  $this->db->insert('acc_coa',$postData);
}else{

$hname =$this->input->post('HeadName',TRUE);
$updata = array(
'PHeadName'      =>  $HeadName,
);

            
  $this->db->where('HeadCode',$headcode)
      ->update('acc_coa',$postData);
  $this->db->where('PHeadName',$hname)
      ->update('acc_coa',$updata);
}
    redirect($_SERVER['HTTP_REFERER']);
  }

   // Debit voucher Create 
  public function debit_voucher(){
    $data['title'] = display('debit_voucher');
    $data['acc'] = $this->accounts_model->Transacc();
    $data['voucher_no'] = $this->accounts_model->voNO();
    $data['crcc'] = $this->accounts_model->Cracc();
    $content = $this->parser->parse('newaccount/debit_voucher', $data, true);
    $this->template->full_admin_html_view($content); 
  }

  // cash adjustment
  public function cash_adjustment(){
    $data['title']      = display('cash_adjustment');
    $data['voucher_no'] = $this->accounts_model->Cashvoucher();
    $content = $this->parser->parse('newaccount/cash_adjustment', $data, true);
    $this->template->full_admin_html_view($content); 
  }

    //Create Cash Adjustment
 public function create_cash_adjustment(){
    $this->form_validation->set_rules('txtAmount', display('amount')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->insert_cashadjustment()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/cash_adjustment/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/cash_adjustment");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/cash_adjustment");
     }

}

   // Debit voucher code select onchange
  public function debtvouchercode($id){

    $debitvcode = $this->db->select('*')
            ->from('acc_coa')
            ->where('HeadCode',$id)
            ->get()
            ->row();
      $code = $debitvcode->HeadCode;       
echo json_encode($code);

   }
   //Supplier code 
    public function supplier_headcode($id){
$supplier_info = $this->db->select('supplier_name')->from('supplier_information')->where('supplier_id',$id)->get()->row();
$head_name =$id.'-'.$supplier_info->supplier_name;
    $supplierhcode = $this->db->select('*')
            ->from('acc_coa')
            ->where('HeadName',$head_name)
            ->get()
            ->row();
      $code = $supplierhcode->HeadCode;       
echo json_encode($code);

   }
   //Create Debit Voucher
 public function create_debit_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->insert_debitvoucher()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/debit_voucher/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/debit_voucher");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/debit_voucher");
     }

}

 // Update Debit voucher 
public function update_debit_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->update_debitvoucher()) { 
          $this->session->set_flashdata('message', display('update_successfully'));
          redirect('accounts/aprove_v/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/aprove_v");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/accounts/aprove_v");
     }

}

 //Credit voucher 
 public function credit_voucher(){
    $data['title'] = display('credit_voucher');
    $data['acc'] = $this->accounts_model->Transacc();
    $data['voucher_no'] = $this->accounts_model->crVno();
    $data['crcc'] = $this->accounts_model->Cracc();
    $content = $this->parser->parse('newaccount/credit_voucher', $data, true);
    $this->template->full_admin_html_view($content);  
  }

   //Create Credit Voucher
 public function create_credit_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->insert_creditvoucher()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/credit_voucher/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/credit_voucher");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/credit_voucher");
     }

}
 // Contra Voucher form
 public function contra_voucher(){
    $data['title'] = display('contra_voucher');
    $data['acc'] = $this->accounts_model->Transacc();
    $data['voucher_no'] = $this->accounts_model->contra();
    $content = $this->parser->parse('newaccount/contra_voucher', $data, true);
    $this->template->full_admin_html_view($content);
  }

   //Create Contra Voucher
 public function create_contra_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->insert_contravoucher()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/contra_voucher/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/contra_voucher");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/contra_voucher");
     }

}
 // Journal voucher
 public function journal_voucher(){
    $data['title'] = display('journal_voucher');
    $data['acc'] = $this->accounts_model->Transacc();
    $data['voucher_no'] = $this->accounts_model->journal();
    $content = $this->parser->parse('newaccount/journal_voucher', $data, true);
    $this->template->full_admin_html_view($content); 
  }

    //Create Journal Voucher
 public function create_journal_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->insert_journalvoucher()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/journal_voucher/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/journal_voucher");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/journal_voucher");
     }

}

public function update_journal_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->update_journalvoucher()) { 
          $this->session->set_flashdata('message', display('successfully_updated'));
          redirect('accounts/aprove_v');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/aprove_v");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/aprove_v");
     }

}

public function update_contra_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->update_contravoucher()) { 
          $this->session->set_flashdata('message', display('successfully_updated'));
          redirect('accounts/aprove_v');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/aprove_v");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/aprove_v");
     }

}
 //Aprove voucher
  public function aprove_v(){
    $data['title'] = display('voucher_approve');
    $data['aprrove'] = $this->accounts_model->approve_voucher();  
    $content = $this->parser->parse('newaccount/voucher_approve', $data, true);
    $this->template->full_admin_html_view($content); 
}
 // isApprove
 public function isactive($id = null, $action = null)
  {
    $action = ($action=='active'?1:0);
    $postData = array(
      'VNo'     => $id,
      'IsAppove' => $action
    );

    if ($this->accounts_model->approved($postData)) {
      $this->session->set_flashdata('message', display('successfully_approved'));
    } else {
      $this->session->set_flashdata('error_message', display('please_try_again'));
    }

    redirect($_SERVER['HTTP_REFERER']);
  }

  public function voucher_delete($voucher){
     if ($this->accounts_model->delete_voucher($voucher)) {
      $this->session->set_flashdata('message', display('successfully_delete'));
    } else {
      $this->session->set_flashdata('error_message', display('please_try_again'));
    }

    redirect($_SERVER['HTTP_REFERER']);

  }

   //Update voucher 
  public function voucher_update($id= null){
    $vtype =$this->db->select('*')
                    ->from('acc_transaction')
                    ->where('VNo',$id)
                    ->get()
                    ->result_array();
                   
                    if($vtype[0]['Vtype'] =="DV"){
    $data['title'] = display('update_debit_voucher');
    $data['dbvoucher_info'] = $this->accounts_model->dbvoucher_updata($id);
    $data['credit_info'] = $this->accounts_model->crvoucher_updata($id);
    $page =  'newaccount/update_dbt_crtvoucher';    
    } 

     if($vtype[0]['Vtype'] =="JV"){
    $data['title'] = 'Update'.' '.display('journal_voucher');
    $data['acc'] = $this->accounts_model->Transacc();
    $data['voucher_info'] = $this->accounts_model->journal_updata($id);
    $page =  'newaccount/update_journal_voucher';    
    } 


     if($vtype[0]['Vtype'] =="Contra"){
    $data['title'] = 'Update'.' '.display('contra_voucher');
    $data['acc'] = $this->accounts_model->Transacc();
    $data['voucher_info'] = $this->accounts_model->journal_updata($id);
    $page =  'newaccount/update_contra_voucher';    
    } 

    if($vtype[0]['Vtype'] =="CV"){
    $data['title'] = display('update_credit_voucher');
    $data['crvoucher_info'] = $this->accounts_model->crdtvoucher_updata($id);
    $data['debit_info'] = $this->accounts_model->debitvoucher_updata($id);
    $page =  'newaccount/update_credit_bdtvoucher';  
    }
    $data['crcc'] = $this->accounts_model->Cracc();
    $data['acc'] = $this->accounts_model->Transacc();
     $content = $this->parser->parse($page, $data, true);
    $this->template->full_admin_html_view($content); 
  }
  // update credit voucher 
  public function update_credit_voucher(){
    $this->form_validation->set_rules('cmbDebit', display('cmbDebit')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->update_creditvoucher()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/aprove_v/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/aprove_v");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/aprove_v");
     }

}

  //Trial Balannce
    public function trial_balance(){
        $data['title']  = display('trial_balance');
         $content = $this->parser->parse('newaccount/trial_balance', $data, true);
    $this->template->full_admin_html_view($content); 
        }
     //Trial Balance Report
    public function trial_balance_report(){
      $CI = & get_instance();
      $CI->load->model('Accounts_model');
      $CI->load->model('Invoices');
      $data['company'] = $CI->Invoices->retrieve_company();
      $data['software_info'] = $CI->Accounts_model->software_setting_info();
       $dtpFromDate     = $this->input->post('dtpFromDate',TRUE);
       $dtpToDate       = $this->input->post('dtpToDate',TRUE);
       $chkWithOpening  = $this->input->post('chkWithOpening',TRUE);

       $results         = $this->accounts_model->trial_balance_report($dtpFromDate,$dtpToDate,$chkWithOpening);

       if ($results['WithOpening']) {
            $data['oResultTr']    = $results['oResultTr'];
            $data['oResultInEx']  = $results['oResultInEx'];
            $data['dtpFromDate']  = $dtpFromDate;
            $data['dtpToDate']    = $dtpToDate;

            // PDF Generator 
            $this->load->library('pdfgenerator');
            $dompdf = new DOMPDF();
            $page = $this->load->view('newaccount/trial_balance_with_opening_pdf',$data,true);
            $dompdf->load_html($page);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/Trial Balance With Opening As On '.$dtpFromDate.' To '.$dtpToDate.'.pdf', $output);


            $data['pdf']    = 'assets/data/pdf/Trial Balance With Opening As On '.$dtpFromDate.' To '.$dtpToDate.'.pdf';
            $data['title']  = display('trial_balance_report');
            $content = $this->parser->parse('newaccount/trial_balance_with_opening', $data, true);
    $this->template->full_admin_html_view($content);
       }else{

            $data['oResultTr']    = $results['oResultTr'];
            $data['oResultInEx']  = $results['oResultInEx'];
            $data['dtpFromDate']  = $dtpFromDate;
            $data['dtpToDate']    = $dtpToDate;

            // PDF Generator 
            $this->load->library('pdfgenerator');
            $dompdf = new DOMPDF();
            $page = $this->load->view('newaccount/trial_balance_without_opening_pdf',$data,true);
            $dompdf->load_html($page);
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents('assets/data/pdf/Trial Balance As On '.$dtpFromDate.' To '.$dtpToDate.'.pdf', $output);
            $data['pdf']    = 'assets/data/pdf/Trial Balance As On '.$dtpFromDate.' To '.$dtpToDate.'.pdf';

            $data['title']  = display('trial_balance_report');
            $content = $this->parser->parse('newaccount/trial_balance_without_opening', $data, true);
    $this->template->full_admin_html_view($content);
       }

    }



    public function vouchar_cash($date){
        $vouchar_view = $this->accounts_model->get_vouchar_view($date);
        $data = array(
            'vouchar_view' => $vouchar_view,
        );

    $data['title'] = display('accounts_form');
     $content = $this->parser->parse('newaccount/vouchar_cash', $data, true);
    $this->template->full_admin_html_view($content);
    }

    public function general_ledger(){

        $general_ledger = $this->accounts_model->get_general_ledger();
        $data = array(
            'general_ledger' => $general_ledger,
        );

        $data['title'] = display('general_ledger');
         $content = $this->parser->parse('newaccount/general_ledger', $data, true);
    $this->template->full_admin_html_view($content);
    }

    public function general_led($Headid = NULL){
        $Headid = $this->input->post('Headid',TRUE);
        $HeadName = $this->accounts_model->general_led_get($Headid);
        echo  "<option>Transaction Head</option>";
        $html = "";
        foreach($HeadName as $data){
            $html .="<option value='$data->HeadCode'>$data->HeadName</option>";
            
        }
        echo $html;
    }
//      working
    public function voucher_report_serach($vouchar=NULL){
       echo $vouchar = $this->input->post('vouchar',TRUE);

        $voucher_report_serach = $this->accounts_model->voucher_report_serach($vouchar);

        if($voucher_report_serach->Amount==''){
             $pay='0.00';
        }else{
             $pay=$voucher_report_serach->Amount;
        }
        $baseurl = base_url().'accounts/vouchar_cash/'.$vouchar;
         $html = "";
         $html.="<td>
                   <a href=\"$baseurl\">CV-BAC-$vouchar</a>
                 </td>
                 <td>Aggregated Cash Credit Voucher of $vouchar</td>
                 <td>$pay</td>
                 <td align=\"center\">$vouchar</td>";
         echo $html;
    }
    //general ledger working
    public function accounts_report_search(){
      $CI = & get_instance();
      $CI->load->model('Accounts_model');
      $CI->load->model('Invoices');
        $cmbGLCode   = $this->input->post('cmbGLCode',TRUE);
        $cmbCode     = $this->input->post('cmbCode',TRUE);
        $dtpFromDate = $this->input->post('dtpFromDate',TRUE);
        $dtpToDate   = $this->input->post('dtpToDate',TRUE);
        $chkIsTransction = $this->input->post('chkIsTransction',TRUE);
        $HeadName    = $this->accounts_model->general_led_report_headname($cmbGLCode);
        $HeadName2   = $this->accounts_model->general_led_report_headname2($cmbGLCode,$cmbCode,$dtpFromDate,$dtpToDate,$chkIsTransction);
         $pre_balance = $this->accounts_model->general_led_report_prebalance($cmbCode,$dtpFromDate);

        $data = array(
            'dtpFromDate' => $dtpFromDate,
            'dtpToDate'   => $dtpToDate,
            'HeadName'    => $HeadName,
            'HeadName2'   => $HeadName2,
            'prebalance'  =>  $pre_balance,
            'chkIsTransction' => $chkIsTransction,

        );
        $data['company'] = $CI->Invoices->retrieve_company();
      $data['software_info'] = $CI->Accounts_model->software_setting_info();
        $data['ledger'] = $this->db->select('*')->from('acc_coa')->where('HeadCode',$cmbCode)->get()->result_array();
        $data['title'] = display('general_ledger_report');
         $content = $this->parser->parse('newaccount/general_ledger_report', $data, true);
    $this->template->full_admin_html_view($content);

    }


    public function cash_book(){
        $CI = & get_instance();
        $CI->load->model('Accounts_model');
        $CI->load->model('Invoices');
        $data['title'] = display('cash_book');
        $data['company'] = $CI->Invoices->retrieve_company();
        $data['software_info'] = $CI->Accounts_model->software_setting_info();
        $content = $this->parser->parse('newaccount/cash_book', $data, true);
       $this->template->full_admin_html_view($content);
    }
    public function bank_book(){
        $CI = & get_instance();
        $CI->load->model('Accounts_model');
        $CI->load->model('Invoices');
        $data['title'] = display('bank_book');
        $data['company'] = $CI->Invoices->retrieve_company();
        $data['software_info'] = $CI->Accounts_model->software_setting_info();
      $content = $this->parser->parse('newaccount/bank_book', $data, true);
    $this->template->full_admin_html_view($content);
    }
    // Inventory Report
     public function inventory_ledger(){
      $CI = & get_instance();
      $CI->load->model('Accounts_model');
      $CI->load->model('Invoices');
      $data['company'] = $CI->Invoices->retrieve_company();
      $data['software_info'] = $CI->Accounts_model->software_setting_info();
      $data['title'] = display('Inventory_ledger');
      $content = $this->parser->parse('newaccount/inventory_ledger', $data, true);
    $this->template->full_admin_html_view($content);
    }
     public function voucher_report(){
        $get_cash = $this->accounts_model->get_cash();
        $get_vouchar= $this->accounts_model->get_vouchar();
        $data = array(
            'get_cash' => $get_cash,
            'get_vouchar' => $get_vouchar,
        );
        $data['title']  = display('accounts_form');
    $content = $this->parser->parse('newaccount/coa', $data, true);
    $this->template->full_admin_html_view($content);
  }
   public function coa_print(){
     $CI = & get_instance();
      $CI->load->model('Accounts_model');
      $CI->load->model('Invoices');
      $data['company'] = $CI->Invoices->retrieve_company();
      $data['software_info'] = $CI->Accounts_model->software_setting_info();
      $data['title'] = display('accounts_form');
       $content = $this->parser->parse('newaccount/coa_print', $data, true);
    $this->template->full_admin_html_view($content);
    }
      //Profit loss report page
    public function profit_loss_report(){
        $data['title'] = display('profit_loss_report');
        $content = $this->parser->parse('newaccount/profit_loss_report', $data, true);
    $this->template->full_admin_html_view($content);
    }
    //Profit loss serch result
    public function profit_loss_report_search(){
        $dtpFromDate = $this->input->post('dtpFromDate',TRUE);
        $dtpToDate   = $this->input->post('dtpToDate',TRUE);

        $get_profit  = $this->accounts_model->profit_loss_serach();

        $data['oResultAsset'] = $get_profit['oResultAsset'];
        $data['oResultLiability']  = $get_profit['oResultLiability'];
        $data['dtpFromDate']  = $dtpFromDate;
        $data['dtpToDate']    = $dtpToDate;
        $data['pdf']    = 'assets/data/pdf/Statement of Comprehensive Income From '.$dtpFromDate.' To '.$dtpToDate.'.pdf';
        $data['title']  = display('profit_loss_report');
         $content = $this->parser->parse('newaccount/profit_loss_report_search', $data, true);
    $this->template->full_admin_html_view($content);
    }
     //Cash flow page
    public function cash_flow_report(){
        $data['title']  = display('cash_flow_report');
        $content = $this->parser->parse('newaccount/cash_flow_report', $data, true);
    $this->template->full_admin_html_view($content);
    }
     //Cash flow report search
    public function cash_flow_report_search(){
      $CI = & get_instance();
      $CI->load->model('Accounts_model');
      $CI->load->model('Invoices');
      $data['company'] = $CI->Invoices->retrieve_company();
      $data['software_info'] = $CI->Accounts_model->software_setting_info();
        $dtpFromDate          = $this->input->post('dtpFromDate',TRUE);
        $dtpToDate            = $this->input->post('dtpToDate',TRUE);
        $data['dtpFromDate']  = $dtpFromDate;
        $data['dtpToDate']    = $dtpToDate;

        // PDF Generator 
        $this->load->library('pdfgenerator');
        $dompdf = new DOMPDF();
        $page = $this->load->view('newaccount/cash_flow_report_search_pdf',$data,true);
        $dompdf->load_html($page);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/Cash Flow Statement '.$dtpFromDate.' To '.$dtpToDate.'.pdf', $output);

        $data['pdf']    = 'assets/data/pdf/Cash Flow Statement '.$dtpFromDate.' To '.$dtpToDate.'.pdf';
        $data['title']  = display('cash_flow_report');
        $content = $this->parser->parse('newaccount/cash_flow_report_search', $data, true);
    $this->template->full_admin_html_view($content);
    }

    //Supplier payment information 
    public function supplier_payment(){
        $get_supplier= $this->accounts_model->get_supplier();
        $bank_list   = $this->Web_settings->bank_list();
        $data = array(
            'supplier_list' => $get_supplier,
            'bank_list'     => $bank_list,
        );
        $data['voucher_no'] = $this->accounts_model->Spayment();
        $data['title']  = display('supplier_payment_form');
        $content = $this->parser->parse('newaccount/supplier_payment_form', $data, true);
    $this->template->full_admin_html_view($content);
    }

    //supplier payment submit
     public function create_supplier_payment(){
    $this->form_validation->set_rules('txtCode', display('txtCode')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->supplier_payment_insert()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/supplier_payment/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/supplier_payment");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/supplier_payment");
     }

}
public function supplier_paymentreceipt($supplier_id,$voucher_no,$coaid){
    $this->load->model('Purchases');
    $this->load->model('Web_settings');
    $data['supplier_info'] = $this->accounts_model->supplierinfo($supplier_id);
    $data['payment_info']  = $this->accounts_model->supplierpaymentinfo($voucher_no,$coaid);
    $currency_details      = $this->Web_settings->retrieve_setting_editdata();
    $company_info          = $this->Purchases->retrieve_company();
    $data['company_info']  = $company_info;
    $data['currency']      = $currency_details[0]['currency'];
    $data['position']      = $currency_details[0]['currency_position'];
    $data['title']         = display('supplier_payment_form');
    $content = $this->parser->parse('newaccount/supplier_payment_receipt', $data, true);
    $this->template->full_admin_html_view($content);
}

//Customer Receive
public function customer_receive(){
   $get_customer= $this->accounts_model->get_customer();
   $bank_list        = $this->Web_settings->bank_list();
        $data = array(
            'customer_list' => $get_customer,
            'bank_list'     => $bank_list,
        );
        $data['voucher_no'] = $this->accounts_model->Creceive();
        $data['title']      = display('customer_receive_form');
    $content = $this->parser->parse('newaccount/customer_receive_form', $data, true);
    $this->template->full_admin_html_view($content);
}
 public function customer_headcode($id){
$customer_info = $this->db->select('customer_name')->from('customer_information')->where('customer_id',$id)->get()->row();
$head_name =$id.'-'.$customer_info->customer_name;
    $customerhcode = $this->db->select('*')
            ->from('acc_coa')
            ->where('HeadName',$head_name)
            ->get()
            ->row();
      $code = $customerhcode->HeadCode;       
echo json_encode($code);

   }

   public function create_customer_receive(){
     $this->form_validation->set_rules('txtCode', display('txtCode')  ,'max_length[100]');
         if ($this->form_validation->run()) { 
        if ($this->accounts_model->customer_receive_insert()) { 
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/customer_receive/');
        }else{
          $this->session->set_flashdata('error_message',  display('please_try_again'));
        }
        redirect("accounts/customer_receive");
    }else{
      $this->session->set_flashdata('error_message',  display('please_try_again'));
      redirect("accounts/customer_receive");
     }

   }


   public function customer_receipt($customer_id,$voucher_no,$coaid){
    $this->load->model('Purchases');
    $this->load->model('Web_settings');
    $data['customer_info'] = $this->accounts_model->custoinfo($customer_id);
    $data['payment_info']  = $this->accounts_model->customerreceiptinfo($voucher_no,$coaid);
    $currency_details      = $this->Web_settings->retrieve_setting_editdata();
    $company_info          = $this->Purchases->retrieve_company();
    $data['company_info']  = $company_info;
    $data['currency']      = $currency_details[0]['currency'];
    $data['position']      = $currency_details[0]['currency_position'];
    $data['title']         = display('supplier_payment_form');
    $content = $this->parser->parse('newaccount/customer_payment_receipt', $data, true);
    $this->template->full_admin_html_view($content);
}
}
