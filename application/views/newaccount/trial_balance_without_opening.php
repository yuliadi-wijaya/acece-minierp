
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
                    <h4></h4>
                </div>
            </div>
            <div id="printArea">
                <div class="panel-body">
                <table width="100%" class="table table-bordered table_boxnew" cellpadding="5" cellspacing="0">
                <tr>
                 <td colspan="4" align="center">
                <h3><?php echo display('trial_balance')?><br/>
        <?php echo display('as_on')?> <?php echo $dtpFromDate; ?> <?php echo display('to')?> <?php echo $dtpToDate;?></h3></td></tr>
                <tr class="table_head">
                <td width="20%" align="center"><strong><?php echo display('code')?></strong></td>
                <td width="50%" align="center"><strong><?php echo display('account_name')?></strong></td>
                <td width="15%" align="center"><strong><?php echo display('debit')?></strong></td>
                <td width="15%" align="center"><strong><?php echo display('credit')?></strong></td>
                </tr>
        <?php
            $TotalCredit=0;
          $TotalDebit=0;  
          $k=0;
            for($i=0;$i<count($oResultTr);$i++)
          {
            $COAID=$oResultTr[$i]['HeadCode'];
            
              $sql="SELECT SUM(acc_transaction.Debit) AS Debit, SUM(acc_transaction.Credit) AS Credit FROM acc_transaction WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' ";
            
          

            $q1=$this->db->query($sql);
            $oResultTrial = $q1->row();


            $bg=$k&1?"#FFFFFF":"#E7E0EE";
            if($oResultTrial->Credit+$oResultTrial->Debit>0)
            {
              $k++; ?>
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
            
              $sql="SELECT SUM(acc_transaction.Debit) AS Debit, SUM(acc_transaction.Credit) AS Credit FROM acc_transaction WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' ";
          

            $q2=$this->db->query($sql);
            $oResultTrial = $q2->row();

            $bg=$k&1?"#FFFFFF":"#E7E0EE";
            if($oResultTrial->Credit+$oResultTrial->Debit>0)
            {
              $k++; ?>
                <tr class="table_data">
                  <td  align="left" bgcolor="<?php echo $bg;?>"><a href="javascript:"><?php echo $oResultInEx[$i]['HeadCode'];?>
                   </a>
                  </td>
                  <td  align="left" bgcolor="<?php echo $bg;?>"><?php echo $oResultInEx[$i]['HeadName'];?></td>
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
        ?>
                <tr class="table_head">
                  <td colspan="2" align="right"><strong><?php echo display('total')?></strong></td>
                  <td align="right"><strong><?php echo number_format($TotalDebit); ?></strong></td>
                  <td align="right"><strong><?php echo number_format( $TotalCredit,2); ?></strong></td>
                </tr>
                
            </table>

             <table width="100%" cellpadding="1" cellspacing="20" class="signaturetable">
                      <tr>
                          <td width="20%" class="footersignature" align="center"><?php echo display('prepared_by')?></td>
                            <td width="20%" class="footersignature" align="center"><?php echo display('account_official')?></td>
                            <td  width="20%" class="footersignature" align='center'><?php echo display('chairman')?></td>
                        </tr>
                    </table>
      
                </div>
            </div>

            <div class="text-center" id="print">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv('printArea');"/>
                <a href="<?php echo base_url($pdf)?>" download>
                    <input type="button" class="btn btn-success" name="btnPdf" id="btnPdf" value="PDF"/>
                </a>
            </div>
        </div>
    </div>
</div>
</section>
</div>