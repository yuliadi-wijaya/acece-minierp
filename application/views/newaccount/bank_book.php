<script src="<?php echo base_url() ?>my-assets/js/admin_js/account.js" type="text/javascript"></script>
<?php
include ('Class/CConManager.php');
include ('Class/CResult.php');
include ('Class/CAccount.php');
include ('Class/Ccommon.php');
?>

<?php
if(isset($_POST['btnSave']))
{

    $oAccount=new CAccount();
    $oResult=new CResult();
    $HeadCode=$_POST['txtCode'];
    $HeadName=$_POST['txtName'];
    $FromDate=$_POST['dtpFromDate'];
    $ToDate=$_POST['dtpToDate'];


    $sql=$this->accounts_model->bankbook_firstqury($FromDate,$HeadCode);

    $oResult=$oAccount->SqlQuery($sql);
    $PreBalance=0;

    if($oResult->num_rows>0)
    {
        $PreBalance=$oResult->row['Debit'];
        $PreBalance=$PreBalance- $oResult->row['Credit'];
    }

     $sql=$this->accounts_model->bankbook_secondqury($FromDate,$HeadCode,$ToDate);
    $oResult=$oAccount->SqlQuery($sql);

}
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('accounts') ?></h1>
            <small><?php echo display('bank_book') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo display('bank_book') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
            <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
            ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>                    
            </div>
            <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>                    
            </div>
            <?php
            $this->session->unset_userdata('error_message');
        }
        ?>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>

                    </h4>
                </div>
            </div>
            <div class="panel-body">
               
                    <?php echo form_open('','name="form1" id="form1"')?>
                <div class="row" id="">
                    <input type="hidden" id="txtName" name="txtName"/>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo display('gl_head')?></label>
                            <div class="col-sm-8">

                                <select name="cmbCode" class="form-control" onchange="cmbCode_bankbookonchange()" id="cmbCode">
                                    <option value="">Select One</option>
                                    <?php $oCommon=new CCommon();
                                    $oCommon->ReadAllBankCOA('HeadCode','HeadName','');
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo display('account_code') ?></label>
                            <div class="col-sm-8">
                                <input type="text" name="txtCode" id="txtCode" size="40" readonly="readonly" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo display('from_date') ?></label>
                            <div class="col-sm-8">
                                <input type="text" name="dtpFromDate" value="" placeholder="<?php echo display('date') ?>" class="datepicker form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label"><?php echo display('to_date') ?></label>
                            <div class="col-sm-8">
                                <input type="text"  name="dtpToDate" value="" placeholder="<?php echo display('date') ?>" class="datepicker form-control">
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" name="btnSave" class="btn btn-success w-md m-b-5"><?php echo display('find') ?></button>
                        </div>
                    </div>
                </div>
               <?php echo form_close()?>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
            <div class="panel-body"  id="printArea">
               
                <div class="">
                                  <table class="print-table" width="100%">
                                                
                                                <tr>
                                                    <td align="left" class="print-table-tr">
                                                        <img src="<?php echo $software_info[0]->logo;?>" alt="logo">
                                                    </td>
                                                    <td align="center" class="print-cominfo">
                                                        <span class="company-txt">
                                                            <?php echo html_escape($company[0]['company_name']);?>
                                                           
                                                        </span><br>
                                                        <?php echo html_escape($company[0]['address']);?>
                                                        <br>
                                                        <?php echo html_escape($company[0]['email']);?>
                                                        <br>
                                                         <?php echo html_escape($company[0]['mobile']);?>
                                                        
                                                    </td>
                                                   
                                                     <td align="right" class="print-table-tr">
                                                        <date>
                                                        <?php echo display('date')?>: <?php
                                                        echo date('d-M-Y');
                                                        ?> 
                                                    </date>
                                                    </td>
                                                </tr>            
                                   
                                </table>
                    <table width="100%" class="table table-stripped" cellpadding="1" cellspacing="1">
                        <caption class="text-center">
                            <font size="+1"> <strong><?php echo display('bank_book_report_of')?> <?php echo (!empty($HeadName)?html_escape($HeadName):'') ?> (<?php echo display('on')?> <?php echo (!empty($FromDate)?html_escape($FromDate):''); ?> <?php echo display('to')?> <?php echo (!empty($ToDate)?html_escape($ToDate):'');?>)</strong></font>
                        </caption> 
                        <tr class="table_data">
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td colspan="2" align="right"><b><?php echo display('opening_balance')?></b></td>
                            <td align="right"><?php echo html_escape(number_format((!empty($PreBalance)?$PreBalance:0),2,'.',',')); ?></td>
                        </tr>
                        <tr class="table_head">
                            <td height="25"><b><?php echo display('sl')?></b></td>
                            <td align="center"><b><?php echo display('date')?></b></td>
                            <td align="center" ><b><?php echo display('voucher_no')?></b></td>
                            <td align="center"><b><?php echo display('type')?></b></td>
                            <td align="center"><b><?php echo display('head_of_account')?></b></td>
                            <td align="right"><b><?php echo display('debit')?></b></td>
                            <td align="right"><b><?php echo display('credit')?></b></td>
                            <td align="right" ><b><?php echo display('balance')?></b></td>
                        </tr>
                        <?php
                        $TotalCredit=0;
                        $TotalDebit=0;
                        $VNo="";
                        $CountingNo=1;
                        for($i=0;$i<(!empty($oResult->num_rows)?$oResult->num_rows:0);$i++)
                        {
                            if($i&1)
                                $bg="#E7E0EE";
                            else
                                $bg="#FFFFFF";
                            ?>
                            <tr class="table_data">
                                <?php
                                if($VNo!=$oResult->rows[$i]['VNo'])
                                {
                                    ?>
                                    <td  height="25" bgcolor="<?php echo html_escape($bg); ?>"><?php echo $CountingNo++;?></td>
                                    <td align="center" bgcolor="<?php echo html_escape($bg); ?>"><?php echo html_escape(substr($oResult->rows[$i]['VDate'],0,10));?></td>
                                    <td align="center" bgcolor="<?php echo html_escape($bg); ?>"><?php
                                        
                                        echo html_escape($oResult->rows[$i]['VNo']);
                                        ?></td>
                                    <td align="center" bgcolor="<?php echo html_escape($bg); ?>">
                                            <?php echo html_escape(trim($oResult->rows[$i]['Vtype']));
                                            ?>

                                    </td>
                                    <?php
                                    $VNo=$oResult->rows[$i]['VNo'];
                                }
                                else
                                {
                                    ?>
                                    <td bgcolor="<?php echo html_escape($bg); ?>" colspan="4">&nbsp;</td>
                                    <?php
                                }
                                ?>
                                <td align="center" bgcolor="<?php echo html_escape($bg); ?>"><?php echo html_escape($oResult->rows[$i]['HeadName']);?></td>
                                <td align="right" bgcolor="<?php echo html_escape($bg); ?>"><?php
                                    $TotalDebit += $oResult->rows[$i]['Debit'];
                                    $PreBalance += $oResult->rows[$i]['Debit'];
                                    echo html_escape(number_format($oResult->rows[$i]['Debit'],2,'.',','));?></td>
                                <td  align="right" bgcolor="<?php echo $bg; ?>"><?php
                                    $TotalCredit += $oResult->rows[$i]['Credit'];
                                    $PreBalance -= $oResult->rows[$i]['Credit'];
                                    echo html_escape(number_format($oResult->rows[$i]['Credit'],2,'.',','));?></td>
                                <td align="right" bgcolor="<?php echo $bg; ?>"><?php printf("%.2f",$PreBalance); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr class="table_data print-footercolor">
                            <td bgcolor="green">&nbsp;</td>
                            <td align="center" bgcolor="green">&nbsp;</td>
                            <td align="center" bgcolor="green">&nbsp;</td>
                            <td align="center" bgcolor="green">&nbsp;</td>
                            <td  align="right" bgcolor="green"><strong><?php echo display('total')?></strong></td>
                            <td  align="right" bgcolor="green"><?php echo html_escape(number_format($TotalDebit,2,'.',',')); ?></td>
                            <td  align="right" bgcolor="green"><?php echo html_escape(number_format($TotalCredit,2,'.',',')); ?></td>
                            <td  align="right" bgcolor="green"><?php echo html_escape(number_format((!empty($PreBalance)?$PreBalance:0),2,'.',',')); ?></td>
                        </tr>

                    </table>

                </div>
               
            </div>
        </div>
         <div class="text-center" id="print">
                    <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv('printArea');"/>
                </div>
        </div>
    </div>
</div>
</section>
</div>

