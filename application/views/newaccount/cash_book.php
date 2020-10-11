
<?php
include ('Class/CConManager.php');
include ('Class/Ccommon.php');
include ('Class/CResult.php');
include ('Class/CAccount.php');
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


    $sql= $this->accounts_model->cashbook_firstqury($FromDate,$HeadCode);
    
    $oResult=$oAccount->SqlQuery($sql);
    $PreBalance=0;

    if($oResult->num_rows>0)
    {
        $PreBalance=$oResult->row['Credit'];
        $PreBalance=$PreBalance- $oResult->row['Debit'];
    }

    $sql=$this->accounts_model->cashbook_secondqury($FromDate,$HeadCode,$ToDate);

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
            <small><?php echo display('cash_book') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo display('cash_book') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
  
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo display('cash_book')?>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
           
            <?php echo form_open_multipart('','name="form1" id="form1" class="form-inline')?>        
                        <input type="hidden" id="txtCode" name="txtCode" value="1020101"/>
                        <input type="hidden"  id="txtName" name="txtName" size="40" value="Cash In Hand"/>
                         <label class="select"><?php echo display('from_date') ?></label> 
                       <input type="text" name="dtpFromDate" value="<?php echo (!empty($FromDate)?html_escape($FromDate):'')?>" placeholder="<?php echo display('from_date') ?>" class="datepicker form-control">
                        <label class="select"><?php echo display('to_date') ?></label>
                          <input type="text"  name="dtpToDate" value="<?php echo (!empty($ToDate)?html_escape($ToDate):'')?>" placeholder="<?php echo display('to_date') ?>" class="datepicker form-control">
                       <button type="submit" name="btnSave" class="btn btn-success"><?php echo display('find') ?></button>
                        <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv('printArea');"/>
                    
               
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
                <tr align="center">
                    <td id="ReportName"><b><?php echo display('cash_book_voucher')?></b></td>
                </tr>
                <div>
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
                
                    <table width="100%" class="table table-stripped" cellpadding="6" cellspacing="1">
                       <caption class="text-center"><font size="+1"> <strong> <?php echo display('cash_book_report')?>  (<?php echo display('from')?> <?php echo (!empty($FromDate)?html_escape($FromDate):''); ?> <?php echo display('to')?> <?php echo (!empty($ToDate)?html_escape($ToDate):'');?>)</font><strong></caption>
                        <tr class="table_data">
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td colspan="3" align="right"><strong><?php echo display('opening_balance')?></strong></td>
                            <td align="right"><?php echo number_format((!empty($PreBalance)?$PreBalance:0),2,'.',','); ?></td>
                        </tr>
                        <tr class="table_head">
                            <td align="center"><strong><?php echo display('sl')?></strong></td>
                            <td align="center"><strong><?php echo display('date')?></strong></td>
                            <td align="center" ><strong><?php echo display('voucher_no')?></strong></td>
                            <td align="center"><strong><?php echo display('voucher_type')?></strong></td>
                           
                             <td align="center"><strong><?php echo display('remark')?></strong></td>
                            <td align="right"><strong><?php echo display('debit')?></strong></td>
                            <td align="right"><strong><?php echo display('credit')?></strong></td>
                            <td align="right" ><strong><?php echo display('balance')?></strong></td>
                             
                        </tr>
                        <?php
                        $TotalCredit=0;
                        $TotalDebit=0;
                        $VNo="";
                        $CountingNo=1;
                        for($i=0;$i<(!empty($oResult->num_rows)?$oResult->num_rows:0);$i++)
                        {
                            if($i&1)
                                $bg="#F8F8F8";
                            else
                                $bg="#FFFFFF";
                            ?>
                            <tr class="table_data">
                                <?php
                                if($VNo!=$oResult->rows[$i]['VNo'])
                                {
                                    ?>
                                    <td  height="25" bgcolor="<?php echo $bg; ?>" align="center"><?php echo $CountingNo++;?></td>
                                    <td bgcolor="<?php echo $bg; ?>" align="center"><?php echo substr($oResult->rows[$i]['VDate'],0,10);?></td>
                                    <td align="center" bgcolor="<?php echo $bg; ?>" ><?php
                                        echo $oResult->rows[$i]['VNo'];
                                        ?></td>
                                       <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $oResult->rows[$i]['Vtype'];
                                            ?>

                                    </td>

                                    <?php
                                    $VNo=$oResult->rows[$i]['VNo'];
                                }
                                else
                                {
                                    ?>
                                    <td bgcolor="<?php echo $bg; ?>" colspan="4">&nbsp;</td>
                                    <?php
                                }
                                ?>
                                <td align="center" bgcolor="<?php echo $bg; ?>"><?php echo $oResult->rows[$i]['Narration'];?></td>
                                 <td  align="right" bgcolor="<?php echo $bg; ?>"><?php
                                    $TotalDebit += $oResult->rows[$i]['Debit'];
                                    $PreBalance += $oResult->rows[$i]['Debit'];
                                    echo number_format($oResult->rows[$i]['Debit'],2,'.',',');?></td>
                                <td align="right" bgcolor="<?php echo $bg; ?>"><?php
                                    $TotalCredit += $oResult->rows[$i]['Credit'];
                                    $PreBalance -= $oResult->rows[$i]['Credit'];
                                    echo number_format($oResult->rows[$i]['Credit'],2,'.',',');?></td>
                               
                                <td align="right" bgcolor="<?php echo $bg; ?>"><?php echo number_format((!empty($PreBalance)?$PreBalance:0),2,'.',','); ?></td>
                               
                            </tr>
                            <?php
                        }
                        ?>
                        <tr class="table_data print-footercolor">
                            <td align="center" bgcolor="green">&nbsp;</td>
                            <td align="center"  bgcolor="green">&nbsp;</td>
                            <td align="center"  bgcolor="green">&nbsp;</td>
                            <td align="center"  bgcolor="green">&nbsp;</td>
                            <td  align="right"  bgcolor="green"><strong><?php echo display('total')?></strong></td>
                            <td  align="right"  bgcolor="green"><?php echo number_format($TotalDebit,2,'.',','); ?></td>
                            <td  align="right"  bgcolor="green"><?php echo number_format($TotalCredit,2,'.',','); ?></td>
                            <td  align="right"  bgcolor="green"><?php echo number_format((!empty($PreBalance)?$PreBalance:0),2,'.',','); ?></td>
                            
                        </tr>

                    </table>

                   
                </div>

            </div>
            
        </div>

        </div>
    </div>
</div>
</section>
</div>


