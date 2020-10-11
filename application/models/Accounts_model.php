<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('Smsgateway');
        $this->auth->check_admin_auth();
    }

     function get_userlist()
    {
        $this->db->select('*');
        $this->db->from('acc_coa');
        $this->db->where('IsActive',1);
        $this->db->order_by('HeadName');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function dfs($HeadName,$HeadCode,$oResult,$visit,$d)
    {
        if($d==0) echo "<li class=\"jstree-open \">$HeadName";
        else if($d==1) echo "<li class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaData('".$HeadCode."')\">$HeadName</a>";
        else echo "<li><a href='javascript:' onclick=\"loadCoaData('".$HeadCode."')\">$HeadName</a>";
        $p=0;
        for($i=0;$i< count($oResult);$i++)
        {

            if (!$visit[$i])
            {
                if ($HeadName==$oResult[$i]->PHeadName)
                {
                    $visit[$i]=true;
                    if($p==0) echo "<ul>";
                    $p++;
                    $this->dfs($oResult[$i]->HeadName,$oResult[$i]->HeadCode,$oResult,$visit,$d+1);
                }
            }
        }
        if($p==0)
            echo "</li>";
        else
            echo "</ul>";
    }

// Accounts list
    public function Transacc()
    {
      return  $data = $this->db->select("*")
            ->from('acc_coa')
            ->where('IsTransaction', 1)  
            ->where('IsActive', 1) 
            ->order_by('HeadName')
            ->get()
            ->result();
    }
// Credit Account Head
     public function Cracc()
    {
      return  $data = $this->db->select("*")
            ->from('acc_coa') 
            ->like('HeadCode',1020102, 'after')
            ->where('IsTransaction', 1) 
            ->order_by('HeadName')
            ->get()
            ->result();
    }
    // Insert Debit voucher 
    public function insert_debitvoucher(){
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="DV";
            $cAID = $this->input->post('cmbDebit',TRUE);
            $dAID = $this->input->post('txtCode',TRUE);
            $Debit =$this->input->post('txtAmount',TRUE);
            $Credit= $this->input->post('grand_total',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');
           
            for ($i=0; $i < count($dAID); $i++) {
                $dbtid=$dAID[$i];
                $Damnt=$Debit[$i];

     $debitheadinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$dbtid)->get()->row();  
     
                $debitinsert = array(
          'VNo'            =>  $voucher_no,
          'Vtype'          =>  $Vtype,
          'VDate'          =>  $VDate,
          'COAID'          =>  $dbtid,
          'Narration'      =>  $Narration,
          'Debit'          =>  $Damnt,
          'Credit'         =>  0,
          'IsPosted'       => $IsPosted,
          'CreateBy'       => $CreateBy,
          'CreateDate'     => $createdate,
          'IsAppove'       => 0
        ); 
           
              $this->db->insert('acc_transaction',$debitinsert);
              $headinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$cAID)->get()->row();
            

                          
              
          $cinsert = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  $cAID,
            'Narration'      =>  'Debit voucher from '.$headinfo->HeadName,
            'Debit'          =>  0,
            'Credit'         =>  $Damnt,
            'IsPosted'       => $IsPosted,
            'CreateBy'       => $CreateBy,
            'CreateDate'     => $createdate,
            'IsAppove'       => 0
          ); 
        
             $this->db->insert('acc_transaction',$cinsert);

    }
    return true;
}

// Update debit voucher
   public function update_debitvoucher(){
           $voucher_no = $this->input->post('txtVNo',TRUE);
            $Vtype="DV";
            $cAID = $this->input->post('cmbDebit',TRUE);
            $dAID = $this->input->post('txtCode',TRUE);
            $Debit =$this->input->post('txtAmount',TRUE);
            $Credit= $this->input->post('grand_total',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');

            
              $this->db->where('VNo',$voucher_no)
                       ->delete('acc_transaction');

  
            for ($i=0; $i < count($dAID); $i++) {
                $dbtid=$dAID[$i];
                $Damnt=$Debit[$i];
           
            $debitheadinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$dbtid)->get()->row();          
                 
                  $debitinsert = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  $dbtid,
            'Narration'      =>  $Narration,
            'Debit'          =>  $Damnt,
            'Credit'         =>  0,
            'IsPosted'       => $IsPosted,
            'CreateBy'       => $CreateBy,
            'CreateDate'     => $createdate,
            'IsAppove'       => 0
          ); 
         
              $this->db->insert('acc_transaction',$debitinsert);
              $headinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$cAID)->get()->row();

       
          $cinsert = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  $cAID,
            'Narration'      =>  'Debit voucher from '.$headinfo->HeadName,
            'Debit'          =>  0,
            'Credit'         =>  $Damnt,
            'IsPosted'       => $IsPosted,
            'CreateBy'       => $CreateBy,
            'CreateDate'     => $createdate,
            'IsAppove'       => 0
          ); 
        
             $this->db->insert('acc_transaction',$cinsert);

    }
    return true;
}
//Generate Voucher No
public function voNO()
    {
      return  $data = $this->db->select("VNo as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'DV-', 'after')
            ->order_by('ID','desc')
            ->limit(1)
            ->get()
            ->result_array();
          
    }

    public function Cashvoucher()
    {
      return  $data = $this->db->select("VNo as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'CHV-', 'after')
            ->order_by('ID','desc')
            ->get()
            ->result_array();
           
    }
    // Credit voucher no
    public function crVno()
    {
      return  $data = $this->db->select("VNo as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'CV-', 'after')
            ->order_by('ID','desc')
            ->limit(1)
            ->get()
            ->result_array();
          
    }

 // Contra voucher 

    public function contra()
    {
      return  $data = $this->db->select("Max(VNo) as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'Contra-', 'after')
            ->order_by('ID','desc')
            ->get()
            ->result_array();
           
    }


  // Insert Credit voucher 
    public function insert_creditvoucher(){
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="CV";
            $dAID = $this->input->post('cmbDebit',TRUE);
            $cAID = $this->input->post('txtCode',TRUE);
            $Credit =$this->input->post('txtAmount',TRUE);
            $debit= $this->input->post('grand_total',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');

            
            for ($i=0; $i < count($cAID); $i++) {
                $crtid=$cAID[$i];
                $Cramnt=$Credit[$i];

        $debitheadinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$crtid)->get()->row();  
     

                       
           
            $debitinsert = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $VDate,
      'COAID'          =>  $crtid,
      'Narration'      =>  $Narration,
      'Debit'          =>  0,
      'Credit'         =>  $Cramnt,
      'IsPosted'       => $IsPosted,
      'CreateBy'       => $CreateBy,
      'CreateDate'     => $createdate,
      'IsAppove'       => 0
    ); 
          
              $this->db->insert('acc_transaction',$debitinsert);

    $headinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$dAID)->get()->row();
    
      $cinsert = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $VDate,
      'COAID'          =>  $dAID,
      'Narration'      =>  'Credit Vourcher from '.$headinfo->HeadName,
      'Debit'          =>  $Cramnt,
      'Credit'         =>  0,
      'IsPosted'       => $IsPosted,
      'CreateBy'       => $CreateBy,
      'CreateDate'     => $createdate,
      'IsAppove'       => 0
    ); 

       $this->db->insert('acc_transaction',$cinsert);


       $headinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$dAID)->get()->row();


    }
    return true;
}

// Insert Countra voucher 
    public function insert_contravoucher(){
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="Contra";
            $dAID = $this->input->post('cmbDebit',TRUE);
            $cAID = $this->input->post('txtCode',TRUE);
            $debit =$this->input->post('txtAmount',TRUE);
            $credit= $this->input->post('txtAmountcr',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');

            for ($i=0; $i < count($cAID); $i++) {
                $crtid=$cAID[$i];
                $Cramnt=$credit[$i];
                $debits =$debit[$i]; 
           
                $contrainsert = array(
          'VNo'            =>  $voucher_no,
          'Vtype'          =>  $Vtype,
          'VDate'          =>  $VDate,
          'COAID'          =>  $crtid,
          'Narration'      =>  $Narration,
          'Debit'          =>  $debits,
          'Credit'         =>  $Cramnt,
          'IsPosted'       => $IsPosted,
          'CreateBy'       => $CreateBy,
          'CreateDate'     => $createdate,
          'IsAppove'       => 0
        ); 
          
              $this->db->insert('acc_transaction',$contrainsert);

    }
    return true;
}
// Insert journal voucher 
    public function insert_journalvoucher(){
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="JV";
            $dAID = $this->input->post('cmbDebit',TRUE);
            $cAID = $this->input->post('txtCode',TRUE);
            $debit =$this->input->post('txtAmount',TRUE);
            $credit= $this->input->post('txtAmountcr',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');

            for ($i=0; $i < count($cAID); $i++) {
                $crtid=$cAID[$i];
                $Cramnt=$credit[$i];
                $debits =$debit[$i]; 
           
                $contrainsert = array(
          'VNo'            =>  $voucher_no,
          'Vtype'          =>  $Vtype,
          'VDate'          =>  $VDate,
          'COAID'          =>  $crtid,
          'Narration'      =>  $Narration,
          'Debit'          =>  $debits,
          'Credit'         =>  $Cramnt,
          'IsPosted'       => $IsPosted,
          'CreateBy'       => $CreateBy,
          'CreateDate'     => $createdate,
          'IsAppove'       => 0
        ); 
           
              $this->db->insert('acc_transaction',$contrainsert);

    }
    return true;
}

 public function update_journalvoucher(){
         
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="JV";
            $dAID = $this->input->post('cmbDebit',TRUE);
            $cAID = $this->input->post('txtCode',TRUE);
            $debit =$this->input->post('txtAmount',TRUE);
            $credit= $this->input->post('txtAmountcr',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
            $createdate=date('Y-m-d H:i:s');
            $this->db->where(' VNo', $voucher_no);
            $this->db->delete('acc_transaction');

            for ($i=0; $i < count($cAID); $i++) {
                $crtid=$cAID[$i];
                $Cramnt=$credit[$i];
                $debits =$debit[$i]; 
               
                $contrainsert = array(
          'VNo'            =>  $voucher_no,
          'Vtype'          =>  $Vtype,
          'VDate'          =>  $VDate,
          'COAID'          =>  $crtid,
          'Narration'      =>  $Narration,
          'Debit'          =>  $debits,
          'Credit'         =>  $Cramnt,
          'IsPosted'       => $IsPosted,
          'CreateBy'       => $CreateBy,
          'CreateDate'     => $createdate,
          'IsAppove'       => 0
        ); 
           
              $this->db->insert('acc_transaction',$contrainsert);
            

    }
     
    return true;
}

 public function update_contravoucher(){
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="Contra";
            $dAID = $this->input->post('cmbDebit',TRUE);
            $cAID = $this->input->post('txtCode',TRUE);
            $debit =$this->input->post('txtAmount',TRUE);
            $credit= $this->input->post('txtAmountcr',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');
             $this->db->where(' VNo', $voucher_no);
            $this->db->delete('acc_transaction');

            for ($i=0; $i < count($cAID); $i++) {
                $crtid=$cAID[$i];
                $Cramnt=$credit[$i];
                $debits =$debit[$i]; 
           
                $contrainsert = array(
          'VNo'            =>  $voucher_no,
          'Vtype'          =>  $Vtype,
          'VDate'          =>  $VDate,
          'COAID'          =>  $crtid,
          'Narration'      =>  $Narration,
          'Debit'          =>  $debits,
          'Credit'         =>  $Cramnt,
          'IsPosted'       => $IsPosted,
          'CreateBy'       => $CreateBy,
          'CreateDate'     => $createdate,
          'IsAppove'       => 0
        ); 
              $this->db->insert('acc_transaction',$contrainsert);

    }
    return true;
}
// journal voucher
public function journal()
    {
      return  $data = $this->db->select("Max(VNo) as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'Journal-', 'after')
            ->order_by('ID','desc')
            ->get()
            ->result_array();
           
    }

    // voucher Aprove 
    public function approve_voucher(){
        $values = array("DV", "CV", "JV","Contra");
      
       return $approveinfo = $this->db->select('*,sum(Credit) as Credit,sum(Debit) as Debit')
                               ->from('acc_transaction')
                               ->where_in('Vtype',$values)
                               ->where('IsAppove',0)
                               ->group_by('VNo')
                               ->get()
                               ->result();

    }
//approved
        public function approved($data = [])
    {
        return $this->db->where('VNo',$data['VNo'])
            ->update('acc_transaction',$data); 
    } 


    public function delete_voucher($voucher){
      $this->db->where('VNo', $voucher)
               ->delete('acc_transaction');
      if ($this->db->affected_rows()) {
      return true;
    } else {
      return false;
    }
    }

    //debit update voucher
    public function dbvoucher_updata($id){
      return  $vou_info = $this->db->select('*')
                 ->from('acc_transaction')
                 ->where('VNo',$id)
                 ->where('Credit <',1)
                 ->get()
                 ->result();
    }

        public function journal_updata($id){
      return  $vou_info = $this->db->select('*')
                 ->from('acc_transaction')
                 ->where('VNo',$id)
                 ->get()
                 ->result_array();
    }

     //credit voucher update 
    public function crdtvoucher_updata($id){
      return  $vou_info = $this->db->select('*')
                 ->from('acc_transaction')
                 ->where('VNo',$id)
                 ->where('Debit <',1)
                 ->get()
                 ->result();

    }
    //Debit voucher inof

    public function debitvoucher_updata($id){
      return $cr_info = $this->db->select('*')
                 ->from('acc_transaction')
                 ->where('VNo',$id)
                 ->where('Credit<',1)
                 ->get()
                 ->result_array();

    }
     // debit update voucher credit info
    public function crvoucher_updata($id){
       return $v_info = $this->db->select('*')
                 ->from('acc_transaction')
                 ->where('VNo',$id)
                 ->where('Debit<',1)
                 ->get()
                 ->result_array();
    }

    // update Credit voucher
     public function update_creditvoucher(){
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="CV";
            $dAID = $this->input->post('cmbDebit',TRUE);
            $cAID = $this->input->post('txtCode',TRUE);
            $Credit =$this->input->post('txtAmount',TRUE);
            $debit= $this->input->post('grand_total',TRUE);
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=0;
            $CreateBy=$this->session->userdata('id');
           $createdate=date('Y-m-d H:i:s');

              $this->db->where('VNo',$voucher_no)
                       ->delete('acc_transaction');
      
            for ($i=0; $i < count($cAID); $i++) {
                $crtid=$cAID[$i];
                $Cramnt=$Credit[$i];
           
            $debitheadinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$crtid)->get()->row();  
               
                $debitinsert = array(
          'VNo'            =>  $voucher_no,
          'Vtype'          =>  $Vtype,
          'VDate'          =>  $VDate,
          'COAID'          =>  $crtid,
          'Narration'      =>  $Narration,
          'Debit'          =>  0,
          'Credit'         =>  $Cramnt,
          'IsPosted'       => $IsPosted,
          'CreateBy'       => $CreateBy,
          'CreateDate'     => $createdate,
          'IsAppove'       => 0
        ); 
         
        $this->db->insert('acc_transaction',$debitinsert);
    $headinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$dAID)->get()->row();
    
      $cinsert = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $VDate,
      'COAID'          =>  $dAID,
      'Narration'      =>  'Credit Vourcher from '.$headinfo->HeadName,
      'Debit'          =>  $Cramnt,
      'Credit'         =>  0,
      'IsPosted'       => $IsPosted,
      'CreateBy'       => $CreateBy,
      'CreateDate'     => $createdate,
      'IsAppove'       => 0
    ); 

       $this->db->insert('acc_transaction',$cinsert);


       $headinfo = $this->db->select('*')->from('acc_coa')->where('HeadCode',$dAID)->get()->row();
  

            }
    
    return true;
}

 //Trial Balance Report 
    public function trial_balance_report($FromDate,$ToDate,$WithOpening){

        if($WithOpening)
            $WithOpening=true;
        else
            $WithOpening=false;

        $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND IsActive=1 AND HeadType IN ('A','L') ORDER BY HeadCode";
        $oResultTr = $this->db->query($sql);
        
        $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND IsActive=1 AND HeadType IN ('I','E') ORDER BY HeadCode";
        $oResultInEx = $this->db->query($sql);

        $data = array(
            'oResultTr'   => $oResultTr->result_array(),
            'oResultInEx' => $oResultInEx->result_array(),
            'WithOpening' => $WithOpening
        );

        return $data;
    }


      public  function get_vouchar(){


         $date=date('Y-m-d');
          $sql="SELECT *, VNo, Vtype,VDate, SUM(Debit+Credit)/2 as Amount FROM acc_transaction  WHERE VDate='$date' AND VType IN ('DV','JV','CV') GROUP BY VNO, Vtype, VDate ORDER BY VDate";
     
          $query = $this->db->query($sql);
          return $query->result();
    }

    public  function get_vouchar_view($date){
        $sql="SELECT acc_income_expence.COAID,SUM(acc_income_expence.Amount) AS Amount, acc_coa.HeadName FROM acc_income_expence INNER JOIN acc_coa ON acc_coa.HeadCode=acc_income_expence.COAID WHERE Date='$date' AND acc_income_expence.IsApprove=1 AND acc_income_expence.Paymode='Cash' GROUP BY acc_income_expence.COAID, acc_coa.HeadName ORDER BY acc_coa.HeadName";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public  function get_cash(){
        $date=date('Y-m-d');


        $sql="SELECT SUM(Debit) as Amount FROM acc_transaction WHERE VDate='$date' AND COAID ='1020101' AND VType NOT IN ('DV','JV','CV') AND IsAppove='1'";
        $query = $this->db->query($sql);
        return $query->row();

    }

    public  function get_general_ledger(){

        $this->db->select('*');
        $this->db->from('acc_coa');
        $this->db->where('IsGL',1);
        $this->db->order_by('HeadName', 'asc');
        $query = $this->db->get();
        return $query->result();


    }

    public function general_led_get($Headid){

        $sql="SELECT * FROM acc_coa WHERE HeadCode='$Headid' ";
        $query = $this->db->query($sql);
        $rs=$query->row();


        $sql="SELECT * FROM acc_coa WHERE IsTransaction=1 AND PHeadName='".$rs->HeadName."' ORDER BY HeadName";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function voucher_report_serach($vouchar){
        $sql="SELECT SUM(Debit) as Amount FROM acc_transaction WHERE VDate='$vouchar' AND COAID ='1020101' AND VType NOT IN ('DV','JV','CV') AND IsAppove='1'";
        $query = $this->db->query($sql);
        return $query->row();

    }


    public function general_led_report_headname($cmbGLCode){
        $this->db->select('*');
        $this->db->from('acc_coa');
        $this->db->where('HeadCode',$cmbGLCode);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function general_led_report_headname2($cmbGLCode,$cmbCode,$dtpFromDate,$dtpToDate,$chkIsTransction){

            if($chkIsTransction){
        
                $this->db->select('acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Narration, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID,acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType');
                $this->db->from('acc_transaction');
                $this->db->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode', 'left');
                $this->db->where('acc_transaction.IsAppove',1);
                $this->db->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
                $this->db->where('acc_transaction.COAID',$cmbCode);
              

                $query = $this->db->get();
                return $query->result();
            }
            else{
               
                $this->db->select('acc_transaction.COAID,acc_transaction.Debit, acc_transaction.Credit,acc_coa.HeadName,acc_transaction.IsAppove, acc_coa.PHeadName, acc_coa.HeadType');
                $this->db->from('acc_transaction');
                $this->db->join('acc_coa','acc_transaction.COAID = acc_coa.HeadCode', 'left');
                $this->db->where('acc_transaction.IsAppove',1);
                $this->db->where('VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
                $this->db->where('acc_transaction.COAID',$cmbCode);
               
                $query = $this->db->get();
                return $query->result();
            }

    }
    // prebalance calculation
      public function general_led_report_prebalance($cmbCode,$dtpFromDate){

            
              
                $this->db->select('sum(acc_transaction.Debit) as predebit, sum(acc_transaction.Credit) as precredit');
                $this->db->from('acc_transaction');
                $this->db->where('acc_transaction.IsAppove',1);
                $this->db->where('VDate < ',$dtpFromDate);
                $this->db->where('acc_transaction.COAID',$cmbCode);
                

                $query = $this->db->get()->row();
         
                return $balance=$query->predebit - $query->precredit;

    }

    public function get_status(){

        $this->db->select('*');
        $this->db->from('acc_coa');
        $this->db->where('IsTransaction',1);
        $this->db->like('HeadCode','1020102','after');
        $this->db->order_by('HeadName', 'asc');
        $query = $this->db->get();
        return $query->result();
      
    }
   
     //Profict loss report search
    public function profit_loss_serach(){
       
        $sql="SELECT * FROM acc_coa WHERE acc_coa.HeadType='I'";
        $sql1 = $this->db->query($sql);

        $sql="SELECT * FROM acc_coa WHERE acc_coa.HeadType='E'";
        $sql2 = $this->db->query($sql);
        
        $data = array(
          'oResultAsset'     => $sql1->result(),
          'oResultLiability' => $sql2->result(),
        );
        return $data;
    } 
    public function profit_loss_serach_date($dtpFromDate,$dtpToDate){
       $sqlF="SELECT  acc_transaction.VDate, acc_transaction.COAID, acc_coa.HeadName FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.VDate BETWEEN '$dtpFromDate' AND '$dtpToDate' AND acc_transaction.IsAppove = 1 AND  acc_transaction.COAID LIKE '301%'";
       $query = $this->db->query($sqlF);
       return $query->result();
    }

    public function treeview_selectform($id){
     $data = $this->db->select('*')
            ->from('acc_coa')
            ->where('HeadCode',$id)
            ->get()
            ->row();
            return $data;

    }
     public function get_supplier(){
        $this->db->select('*');
        $this->db->from('supplier_information');
        $this->db->where('status',1);
        $this->db->order_by('supplier_id', 'desc');
        $query = $this->db->get();
        return $query->result();  
    }
    // Customer list
    public function get_customer(){
        $this->db->select('*');
        $this->db->from('customer_information');
        $query = $this->db->get();
        return $query->result();  
    }

    public function Spayment()
    {
      return  $data = $this->db->select("VNo as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'PM-', 'after')
            ->order_by('ID','desc')
            ->get()
            ->result_array();
           
    }
// customer code
     public function Creceive()
    {
      return  $data = $this->db->select("VNo as voucher")
            ->from('acc_transaction') 
            ->like('VNo', 'CR-', 'after')
            ->order_by('ID','desc')
            ->get()
            ->result_array();
           
    }
     public function supplier_payment_insert(){

       $bank_id = $this->input->post('bank_id',TRUE);
        if(!empty($bank_id)){
       $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;
    
       $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
   }else{
    $bankcoaid='';
   }
           $this->load->model('Web_settings');
           $currency_details = $this->Web_settings->retrieve_setting_editdata();
           $voucher_no = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype="PM";
            $cAID = $this->input->post('cmbDebit',TRUE);
            $dAID = $this->input->post('txtCode',TRUE);
            $Debit =$this->input->post('txtAmount',TRUE);
            $Credit= 0;
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=1;
            $sup_id = $this->input->post('supplier_id',TRUE);

            $CreateBy=$this->session->userdata('user_id');
           $createdate=date('Y-m-d H:i:s');

                $dbtid=$dAID;
                $Damnt=$Debit;
                $supplier_id = $sup_id;
                $supinfo =$this->db->select('*')->from('supplier_information')->where('supplier_id',$supplier_id)->get()->row();
                    $supplierdebit = array(
              'VNo'            =>  $voucher_no,
              'Vtype'          =>  $Vtype,
              'VDate'          =>  $VDate,
              'COAID'          =>  $dbtid,
              'Narration'      =>  $Narration,
              'Debit'          =>  $Damnt,
              'Credit'         =>  0,
              'IsPosted'       => $IsPosted,
              'CreateBy'       => $CreateBy,
              'CreateDate'     => $createdate,
              'IsAppove'       => 1
            ); 
             $cc = array(
              'VNo'            =>  $voucher_no,
              'Vtype'          =>  $Vtype,
              'VDate'          =>  $VDate,
              'COAID'          =>  1020101,
              'Narration'      =>  'Paid to '.$supinfo->supplier_name,
              'Debit'          =>  0,
              'Credit'         =>  $Damnt,
              'IsPosted'       =>  1,
              'CreateBy'       =>  $CreateBy,
              'CreateDate'     =>  $createdate,
              'IsAppove'       =>  1
            ); 
             $bankc = array(
              'VNo'            =>  $voucher_no,
              'Vtype'          =>  $Vtype,
              'VDate'          =>  $VDate,
              'COAID'          =>  $bankcoaid,
              'Narration'      =>  'Supplier Payment To '.$supinfo->supplier_name,
              'Debit'          =>  0,
              'Credit'         =>  $Damnt,
              'IsPosted'       =>  1,
              'CreateBy'       =>  $CreateBy,
              'CreateDate'     =>  $createdate,
              'IsAppove'       =>  1
            ); 
              

           
              $this->db->insert('acc_transaction',$supplierdebit);

              if($this->input->post('paytype',TRUE) == 2){
                 $this->db->insert('acc_transaction',$bankc); 
              }
                if($this->input->post('paytype',TRUE) == 1){
                   $this->db->insert('acc_transaction',$cc);
                }
 $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/supplier_paymentreceipt/'.$supplier_id.'/'.$voucher_no.'/'.$dbtid);
    
}

public function insert_cashadjustment(){
            $this->load->model('Web_settings');
           $currency_details = $this->Web_settings->retrieve_setting_editdata();
           $voucher_no       = $this->input->post('txtVNo',TRUE);
            $Vtype           = "AD";
            $amount          = $this->input->post('txtAmount',TRUE);
            $type            = $this->input->post('type',TRUE);
            if($type == 1){
              $debit = $amount;
              $credit = 0;
            }
            if($type == 2){
              $debit = 0;
              $credit = $amount;
            }
            $VDate = $this->input->post('dtpDate',TRUE);
            $Narration=$this->input->post('txtRemarks',TRUE);
            $IsPosted=1;
            $IsAppove=1;
            $CreateBy=$this->session->userdata('user_id');
           $createdate=date('Y-m-d H:i:s');
 
     $cc = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $VDate,
      'COAID'          =>  1020101,
      'Narration'      =>  $Narration,
      'Debit'          =>  $debit,
      'Credit'         =>  $credit,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

              $this->db->insert('acc_transaction',$cc);
          
 return true;

}

public function supplierinfo($supplier_id){
  return $this->db->select('*')
                  ->from('supplier_information')
                  ->where('supplier_id',$supplier_id)
                  ->get()
                  ->result_array();
}

public function supplierpaymentinfo($voucher_no,$coaid){
  return   $this->db->select('*')
                  ->from('acc_transaction')
                  ->where('VNo',$voucher_no)
                  ->where('COAID',$coaid)
                  ->get()
                  ->result_array();

}

     public function customer_receive_insert(){

      $bank_id = $this->input->post('bank_id',TRUE);
        if(!empty($bank_id)){
       $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;
    
       $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
   }else{
    $bankcoaid='';
   }
           $this->load->model('Web_settings');
           $currency_details = $this->Web_settings->retrieve_setting_editdata();
           $voucher_no       = addslashes(trim($this->input->post('txtVNo',TRUE)));
            $Vtype           = "CR";
            $cAID            = $this->input->post('cmbDebit',TRUE);
            $dAID            = $this->input->post('txtCode',TRUE);
            $Debit           = 0;
            $Credit          = $this->input->post('txtAmount',TRUE);
            $VDate           = $this->input->post('dtpDate',TRUE);
            $customer_id     = $this->input->post('customer_id',TRUE);
            $Narration       = addslashes(trim($this->input->post('txtRemarks',TRUE)));
            $IsPosted=1;
            $IsAppove=1;
            $CreateBy        = $this->session->userdata('user_id');
            $createdate      = date('Y-m-d H:i:s');
            $dbtid           = $dAID;
            $Credit          = $Credit;
            $customerid      = $customer_id;
             $customerinfo = $this->db->select('*')->from('customer_information')->where('customer_id',$customerid)->get()->row();
            $customercredit = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $VDate,
      'COAID'          =>  $dbtid,
      'Narration'      =>  $Narration,
      'Debit'          =>  0,
      'Credit'         =>  $Credit,
      'IsPosted'       => $IsPosted,
      'CreateBy'       => $CreateBy,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
           
             $cc = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $createdate,
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand For  '.$customerinfo->customer_name,
      'Debit'          =>  $Credit,
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 
       $bankc = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $createdate,
      'COAID'          =>  $bankcoaid,
      'Narration'      =>  'Customer Receive From '.$customerinfo->customer_name,
      'Debit'          =>  $Credit,
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 
       

            
          
              $this->db->insert('acc_transaction',$customercredit);
              if($this->input->post('paytype',TRUE) == 2){
                 $this->db->insert('acc_transaction',$bankc);
                
              }
                if($this->input->post('paytype',TRUE) == 1){
                   $this->db->insert('acc_transaction',$cc);
                }
             
               $message = 'Mr.'.$customerinfo->customer_name.',
        '.'You have Paid '.$Credit.' '.$currency_details[0]['currency'];
      
      $config_data = $this->db->select('*')->from('sms_settings')->get()->row();
        if($config_data->isreceive == 1){
          $this->smsgateway->send([
            'apiProvider' => 'nexmo',
            'username'    => $config_data->api_key,
            'password'    => $config_data->api_secret,
            'from'        => $config_data->from,
            'to'          => $customerinfo->customer_mobile,
            'message'     => $message
        ]);
      }
    
    $this->session->set_flashdata('message', display('save_successfully'));
          redirect('accounts/customer_receipt/'.$customerid.'/'.$voucher_no.'/'.$dbtid);
        }


public function custoinfo($customer_id){
  return $this->db->select('*')
                  ->from('customer_information')
                  ->where('customer_id',$customer_id)
                  ->get()
                  ->result_array();
}

public function customerreceiptinfo($voucher_no,$coaid){
  return   $this->db->select('*')
                  ->from('acc_transaction')
                  ->where('VNo',$voucher_no)
                  ->where('COAID',$coaid)
                  ->get()
                  ->result_array();

}
// =================== Settings data ==============================
public function software_setting_info(){
        $this->db->select('*');
        $this->db->from('web_setting');
        $this->db->where('setting_id', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
}


public function bankbook_firstqury($FromDate,$HeadCode){

  $sql = "SELECT SUM(Debit) Debit, SUM(Credit) Credit, IsAppove, COAID FROM acc_transaction
              WHERE VDate < '$FromDate 00:00:00' AND COAID = '$HeadCode' AND IsAppove =1 GROUP BY IsAppove, COAID";
              return  $sql;

}

public function bankbook_secondqury($FromDate,$HeadCode,$ToDate){
  $sql = "SELECT acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID, acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType, acc_transaction.Narration 
     FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode
         WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '$FromDate 00:00:00' AND '$ToDate 00:00:00' AND acc_transaction.COAID='$HeadCode' ORDER BY  acc_transaction.VDate, acc_transaction.VNo";

         return $sql;
}

public function cashbook_firstqury($FromDate,$HeadCode){
    $sql = "SELECT SUM(Debit) Debit, SUM(Credit) Credit, IsAppove, COAID FROM acc_transaction
              WHERE VDate < '$FromDate' AND COAID LIKE '$HeadCode%' AND IsAppove =1 GROUP BY IsAppove, COAID";
              return  $sql;
}


public function cashbook_secondqury($FromDate,$HeadCode,$ToDate){
   $sql = "SELECT acc_transaction.ID,acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID, acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType, acc_transaction.Narration 
        FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode
        WHERE acc_transaction.IsAppove =1 AND acc_transaction.VDate BETWEEN '$FromDate' AND '$ToDate' AND acc_transaction.COAID LIKE '$HeadCode%' GROUP BY acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.IsAppove, acc_transaction.COAID, acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType, acc_transaction.Narration
               HAVING SUM(acc_transaction.Debit)-SUM(acc_transaction.Credit)<>0
               ORDER BY  acc_transaction.VDate, acc_transaction.VNo";

         return $sql;
}


public function inventoryledger_firstqury($FromDate,$HeadCode){
   $sql = "SELECT SUM(Debit) Debit, SUM(Credit) Credit, IsAppove, COAID FROM acc_transaction
              WHERE VDate < '$FromDate 00:00:00' AND COAID = '$HeadCode' AND IsAppove =1 GROUP BY IsAppove, COAID";
              return  $sql;
}


public function inventoryledger_secondqury($FromDate,$HeadCode,$ToDate){
   $sql = "SELECT acc_transaction.VNo, acc_transaction.Vtype, acc_transaction.VDate, acc_transaction.Debit, acc_transaction.Credit, acc_transaction.IsAppove, acc_transaction.COAID, acc_coa.HeadName, acc_coa.PHeadName, acc_coa.HeadType, acc_transaction.Narration 
     FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode
         WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '$FromDate 00:00:00' AND '$ToDate 00:00:00' AND acc_transaction.COAID='$HeadCode' ORDER BY  acc_transaction.VDate, acc_transaction.VNo";
          return  $sql;
}


public function trial_balance_firstquery($dtpFromDate,$dtpToDate,$COAID){
  $sql = "SELECT SUM(acc_transaction.Debit) AS Debit, SUM(acc_transaction.Credit) AS Credit FROM acc_transaction WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' ";
  return $sql;
}


public function trial_balance_secondquery($dtpFromDate,$dtpToDate,$COAID){
  $sql = "SELECT SUM(acc_transaction.Debit) AS Debit, SUM(acc_transaction.Credit) AS Credit FROM acc_transaction WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' ";
  
  return $sql;
}

public function profitloss_firstquery($dtpFromDate,$dtpToDate,$COAID){

   $sql ="SELECT SUM(acc_transaction.Debit)-SUM(acc_transaction.Credit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE VDate BETWEEN '$dtpFromDate' AND '$dtpToDate' AND COAID LIKE '$COAID%'";
  
    return $sql;
}

public function profitloss_secondquery($dtpFromDate,$dtpToDate,$COAID){
  $sql = "SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '$dtpFromDate' AND '$dtpToDate' AND COAID LIKE '$COAID%'";
  
   return $sql;
}

public function cashflow_firstquery(){
   $sql = "SELECT * FROM acc_coa WHERE acc_coa.IsTransaction=1 AND acc_coa.HeadType='A' AND acc_coa.IsActive=1 AND acc_coa.HeadCode LIKE '1020101%'";
  
   return $sql;

}

public function cashflow_secondquery($dtpFromDate,$dtpToDate,$COAID){
    $sql = "SELECT SUM(acc_transaction.Debit)- SUM(acc_transaction.Credit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%'";
  
   return $sql;
}

public function cashflow_thirdquery(){
    $sql = "SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '102%' AND IsActive=1 AND HeadCode NOT LIKE '1020101%' AND HeadCode!='102' ";
  
   return $sql;
}

public function cashflow_forthquery($dtpFromDate,$dtpToDate,$COAID){
   $sql = "SELECT  SUM(acc_transaction.Credit) - SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%') ";
  
   return $sql;
}


public function cashflow_fifthquery($dtpFromDate,$dtpToDate,$COAID){
   $sql = "SELECT  SUM(acc_transaction.Credit) - SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '4%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%') ";
  
   return $sql;
}


public function cashflow_sixthquery(){
   $sql = "SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '3%' AND IsActive=1 ";
   return $sql;
}

public function cashflow_seventhquery($dtpFromDate,$dtpToDate,$COAID){
     $sql = "SELECT  SUM(acc_transaction.Credit) - SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%') ";
   return $sql;
}
}
