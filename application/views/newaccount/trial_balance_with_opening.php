
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('accounts') ?></h1>
            <small><?php echo display('trial_balance') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo display('trial_balance') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('trial_balance') ?></h4>
                </div>
            </div>
            <div id="printArea">
                <div class="panel-body">
                  
                                         <table class="print-table" width="100%">
                                                
                                                <tr>
                                                    <td align="left" class="print-table-tr">
                                                        <img src="<?php echo $software_info[0]->logo;?>" alt="logo">
                                                    </td>
                                                    <td align="center" class="print-cominfo">
                                                        <span class="company-txt">
                                                            <?php echo $company[0]['company_name'];?>
                                                           
                                                        </span><br>
                                                        <?php echo $company[0]['address'];?>
                                                        <br>
                                                        <?php echo $company[0]['email'];?>
                                                        <br>
                                                         <?php echo $company[0]['mobile'];?>
                                                        
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
                
                    <table width="100%" class="table table-bordered">
                        <tr>
                            <td colspan="4" align="center">
                                <h3><?php echo display('trial_balance_with_opening_as_on');?><br/>
                             <?php echo display('from');?> <?php echo $dtpFromDate; ?> <?php echo display('to');?> <?php echo $dtpToDate;?></h3>
                            </td>
                        </tr>
                        <tr class="table_head">
                            <td width="20%" align="center"><strong><?php echo display('code');?></strong></td>
                            <td width="50%" align="center"><strong><?php echo display('account_name');?></strong></td>
                            <td width="15%" align="center"><strong><?php echo display('debit');?></strong></td>
                            <td width="15%" align="center"><strong><?php echo display('credit');?></strong></td>
                        </tr>
                        <?php
                            $TotalCredit=0;
                            $TotalDebit=0;  
                            $k=0;

                            for($i=0;$i<count($oResultTr);$i++)
                            {

                                $COAID=$oResultTr[$i]['HeadCode'];
                                
                                $sql=$this->accounts_model->trial_balance_firstquery($dtpFromDate,$dtpToDate,$COAID);
                                
                                $q1=$this->db->query($sql);
                                $oResultTrial = $q1->row();

                                $bg=$k&1?"#FFFFFF":"#E7E0EE";

                                if($oResultTrial->Credit != $oResultTrial->Debit)
                                {
                                  
                                    $k++; 
                        ?>
                            <tr class="table_data">
                              <td  align="left" bgcolor="<?php echo $bg;?>"><a href="javascript:"><?php echo $oResultTr[$i]['HeadCode'];?>
                               </a>
                              </td>
                              <td  align="left" bgcolor="<?php echo $bg;?>"><?php echo $oResultTr[$i]['HeadName'];?></td>
                              <?php
                                if($oResultTrial->Debit>$oResultTrial->Credit)
                                {
                              ?>
                              <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                                $TotalDebit += $oResultTrial->Debit-$oResultTrial->Credit;
                               echo number_format($oResultTrial->Debit-$oResultTrial->Credit,2);
                               ?></td>
                              <td  align="right" bgcolor="<?php echo $bg;?>"><?php
                               echo number_format('0.00',2);?></td>
                               <?php
                                }
                                else
                                {
                                ?>
                                 <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                               echo number_format('0.00',2);
                               ?></td>
                              <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                                $TotalCredit += $oResultTrial->Credit-$oResultTrial->Debit;
                               echo number_format($oResultTrial->Credit-$oResultTrial->Debit,2);?></td>
                               <?php
                                }
                                ?>
                            </tr>
                        <?php
                                }
                            }
                            for($i=0;$i<count($oResultInEx);$i++)
                            {
                            $COAID=$oResultInEx[$i]['HeadCode'];
                            
                            $sql=$this->accounts_model->trial_balance_secondquery($dtpFromDate,$dtpToDate,$COAID);
                            
                            $q2=$this->db->query($sql);
                            $oResultTrial = $q2->row();

                            $bg=$k&1?"#FFFFFF":"#E7E0EE";
                            if($oResultTrial->Credit!=$oResultTrial->Debit)
                            {
                               
                                $k++; ?>
                            <tr class="table_data">
                              <td  align="center" bgcolor="<?php echo $bg;?>"><a href="javascript:"><?php echo $oResultInEx[$i]['HeadCode'];?>
                               </a>
                              </td>
                              <td  align="center" bgcolor="<?php echo $bg;?>"><?php echo $oResultInEx[$i]['HeadName'];?></td>
                              <?php
                                if($oResultTrial->Debit>$oResultTrial->Credit)
                                {
                              ?>
                              <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                                $TotalDebit += $oResultTrial->Debit-$oResultTrial->Credit;
                               echo number_format($oResultTrial->Debit-$oResultTrial->Credit,2);
                               ?></td>
                              <td  align="right" bgcolor="<?php echo $bg;?>"><?php
                               echo number_format('0.00',2);?></td>
                               <?php
                                }
                                else
                                {
                                ?>
                                 <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                               echo number_format('0.00',2);
                               ?></td>
                              <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                                $TotalCredit += $oResultTrial->Credit-$oResultTrial->Debit;
                               echo number_format($oResultTrial->Credit-$oResultTrial->Debit,2);?></td>
                               <?php
                                }
                                ?>
                            </tr>
                        <?php
                                }
                            }
            
                        $ProfitLoss=$TotalDebit-$TotalCredit;
                        if($ProfitLoss!=0)
                        {
                        ?>
                        <tr class="table_data">
                          <td  align="left" bgcolor="<?php echo $bg;?>">&nbsp;</td>
                          <td  align="left" bgcolor="<?php echo $bg;?>">Profit-Loss</td>
                         <?php
                        }
                         if($ProfitLoss<0)
                         {
                         ?>
                         <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                            $TotalDebit += abs($ProfitLoss);
                           echo number_format( abs($ProfitLoss),2);
                           ?></td>
                          <td  align="right" bgcolor="<?php echo $bg;?>"><?php
                           echo number_format('0.00',2);?></td>
                        <?php
                         echo "</tr>";
                        }
                        else if($ProfitLoss>0)
                        {
                        ?>
                        <td  align="right" bgcolor="<?php echo $bg;?>"><?php 
                           echo number_format('0.00',2);
                           ?></td>
                          <td  align="right" bgcolor="<?php echo $bg;?>"><?php
                          $TotalCredit+= abs($ProfitLoss);
                           echo number_format(abs($ProfitLoss),2);?></td>
                         <?php
                         echo "</tr>";
                        }
                        ?>

                        <tr class="table_head">
                          <td colspan="2" align="right"><strong><?php echo display('total')?></strong></td>
                          <td align="right"><strong><?php echo number_format($TotalDebit,2); ?></strong></td>
                          <td align="right"><strong><?php echo number_format( $TotalCredit,2); ?></strong></td>
                        </tr>
                       
                         
                    </table>



                            <table width="100%" cellpadding="1" cellspacing="20" class="signaturetable">
                                <tr>
                                    <td width="20%" class="footersignature" align="center"><?php echo display('prepared_by');?></td>
                                    <td width="20%" class="footersignature" align="center"><?php echo display('accounts');?></td>
                                    <td  width="20%" class="footersignature" align='center'><?php echo display('chairman');?></td>
                                </tr>
                            </table>
                </div>
            </div>
            <div class="text-center" id="print">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv('printArea');"/>
                <a href="<?php echo base_url($pdf)?>"download>
                    <input type="button" class="btn btn-success" name="btnPdf" id="btnPdf" value="PDF"/>
                </a>
            </div>
        </div>
    </div>
</div>
</section>
</div>