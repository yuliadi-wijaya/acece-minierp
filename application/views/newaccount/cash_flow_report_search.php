
<!-- Printable area end -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('accounts') ?></h1>
            <small><?php echo display('cash_flow_statement') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo display('cash_flow_statement') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
      
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('cash_flow_statement') ?></h4>
                </div>
            </div>
            <div id="printArea">
                <div class="panel-body">
                  <div>
                       
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
                      <table width="100%" class="table_boxnew" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="3" align="center"><b><?php echo display('cash_flow_statement');?>  </b></td>
                        </tr>
                        <tr class="table_head">
                            <td colspan="3" align="center"><b>On <?php echo $dtpFromDate; ?> To <?php echo $dtpToDate;?></b></td>
                        </tr>
                        <tr class="table_head">
                            <td width="73%" height="29" align="center" class="cashflowparticular"><b><?php echo display('particulars');?></b></td>
                            <td width="2%">&nbsp;</td>
                            <td width="30%" align="right" class="cashflowamount"><b><?php echo display('amount');?></b></td>
                        </tr>
                         <tr class="table_head">
                          <td colspan="3" class="paddingleft10px"><strong><?php echo display('opening_cash_and_equivalent');?>:</strong></td>
                        </tr>
                        <?php
                          $sql=$this->accounts_model->cashflow_firstquery();

                          $sql = $this->db->query($sql);
                          $oResultAsset = $sql->result();
                  
                          $TotalOpening=0;
                          for($i=0;$i<count($oResultAsset);$i++)
                          {
                            $COAID=$oResultAsset[$i]->HeadCode;
                            $sql=$this->accounts_model->cashflow_secondquery($dtpFromDate,$dtpToDate,$COAID); 

                            $sql1 = $this->db->query($sql);
                            $oResultAmountPre = $sql1->row();
                            if($oResultAmountPre->Amount!=0)
                            {
                        ?>
                          <tr >
                              <td align="left" class="paddingleft10px"><?php echo $oResultAsset[$i]->HeadName; ?></td>
                              <td>&nbsp;</td>
                              <td align="right" class="cashflowamnt <?php if($TotalOpening==0) echo 'footersignature' ?>" >
                                  <?php 
                                      $Total=$oResultAmountPre->Amount;
                                      echo number_format($Total);
                              
                                      $TotalOpening+=$Total; 
                                  ?>
                              </td>
                          </tr>
                                <?php
                            }
                          }
                        ?>
                        <tr>
                          <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr>
                         <td align="left" class="paddingleftright10"><strong>Total Opening Cash & Cash Equivalent</strong></td>
                          <td>&nbsp;</td>
                           <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalOpening); ?></strong></td>
                        </tr>
                        <tr class="table_head">
                            <td colspan="3" class="padddingwithunderline"><b>Cashflow from Operating Activities</b></td>
                        </tr>
                        <?php
                            $TotalCurrAsset=0;
                            $sql=$this->accounts_model->cashflow_thirdquery();

                            $sql2 = $this->db->query($sql);
                            $oResultCurrAsset = $sql2->result();

                            for($s=0;$s<count($oResultCurrAsset);$s++)
                            {
                              $COAID=$oResultCurrAsset[$s]->HeadCode;
                              $sql= $this->accounts_model->cashflow_forthquery($dtpFromDate,$dtpToDate,$COAID);

                              $sql3 = $this->db->query($sql);
                              $oResultAmount = $sql3->row();

                              if($oResultAmount->Amount!=0)
                              {
                                ?>
                                <tr >
                                    <td align="left" class="paddingleft10px"><?php echo $oResultCurrAsset[$s]->HeadName; ?></td>
                                    <td>&nbsp;</td>
                                    <td align="right" class="cashflowamnt <?php if($TotalCurrAsset==0) echo 'footersignature' ?>">
                                        <?php 
                                            $Total=$oResultAmount->Amount;
                                            echo number_format($Total);
                                            $TotalCurrAsset+=$Total; 
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                          }
                        $sql=$this->accounts_model->cashflow_fifthquery($dtpFromDate,$dtpToDate,$COAID);

                        $sql4 = $this->db->query($sql);
                        $oResultAmount = $sql4->row();

                        if($oResultAmount->Amount!=0)
                        {
                          ?>
                         <tr>
                            <td align="left" class="paddingleft10px">Payment for Other Operating Activities</td>
                            <td>&nbsp;</td>
                            <td align="right"  class="cashflowamnt">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total,2);
                                    $TotalCurrAsset+=$Total; 
                                ?>
                            </td>
                        </tr>
                        <?php
                      }
                      ?>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr >
                            <td align="left" class="paddingleftright10"><strong>Cash generated from Operating Activites before Changing in Opereating Assets &amp; Liabilities</strong></td>
                             <td>&nbsp;</td>
                           <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrAsset); ?></strong></td>
                        </tr>
                        
                        <tr class="table_head">
                            <td colspan="3" class="padddingwithunderline"><b>Cashflow from Non Operating Activities</b></td>
                        </tr>
                        <?php
                          $TotalCurrAssetNon=0;
                          $sql=$this->accounts_model->cashflow_sixthquery();

                          $sql5 = $this->db->query($sql);
                          $oResultCurrAsset = $sql5->result();

                          for($s=0;$s<count($oResultCurrAsset);$s++)
                          {
                          $COAID=$oResultCurrAsset[$s]->HeadCode;
                          $sql=$this->accounts_model->cashflow_seventhquery($dtpFromDate,$dtpToDate,$COAID);

                          $sql6 = $this->db->query($sql);
                          $oResultAmount = $sql6->row();

                          if($oResultAmount->Amount!=0)
                          {
                        ?>
                          <tr>
                              <td align="left" class="paddingleft10px"><?php echo $oResultCurrAsset[$s]->HeadName; ?></td>
                              <td>&nbsp;</td>
                              <td align="right" class="cashflowamnt <?php if($TotalCurrAssetNon==0) echo 'footersignature' ?>">
                          <?php 
                              $Total=$oResultAmount->Amount;
                              echo number_format($Total);
                              $TotalCurrAssetNon+=$Total; 
                          ?>
                              </td>
                          </tr>
                        <?php
                            }
                          }
                        ?>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr >
                            <td align="left" class="paddingleftright10"><strong>Cash generated from Non Operating Activites before Changing in Opereating Assets &amp; Liabilities</strong></td>
                             <td>&nbsp;</td>
                           <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrAssetNon); ?></strong></td>
                        </tr>
                        <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td >&nbsp;</td>
                        </tr>
                         <tr class="table_head">
                            <td align="left" class="paddingleftright10"><strong>Increase/Decrease in Operating Assets &amp; Liabilites</strong></td>
                           <td>&nbsp;</td>
                           <td align="right" class="pddingright10">&nbsp;</td>
                      </tr>
                        
                      <?php
                      $TotalCurrLiab=0;
                      $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '20101%' AND HeadCode!=20101 AND IsActive=1";

                      $sql6 = $this->db->query($sql);
                      $oResultLiab = $sql6->result();

                      for($t=0;$t<count($oResultLiab);$t++)
                      {
                      $COAID=$oResultLiab[$t]->HeadCode;

                      $sql="SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%')";
                      $oResultAmount=$oAccount->SqlQuery($sql);

                      $sql7 = $this->Db->query($sql);
                      $oResultAmount = $sql7->row();

                      if($oResultAmount->Amount!=0)
                      {
                        ?>
                        <tr >
                            <td align="left" class="paddingleft10px"><?php echo $oResultLiab[$t]->HeadName; ?></td>
                            <td>&nbsp;</td>
                            <td align="right" class="cashflowamnt <?php if($TotalCurrLiab==0) echo 'footersignature' ?>">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total);
                                    $TotalCurrLiab+=$Total;
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                          }
                        ?>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr >
                            <td align="left" class="paddingleftright10"><strong>Total Increase/Decrease</strong></td>
                             <td>&nbsp;</td>
                           <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrLiab); ?></strong></td>
                        </tr>
                       <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="left" class="paddingleftright10"><strong>Net Cash From Operating/Non Operating Activities</strong></td>
                            <td>&nbsp;</td>
                            <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrAsset+$TotalCurrAssetNon+$TotalCurrLiab); ?></strong></td>
                        </tr>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td >&nbsp;</td>
                        </tr>
                        <tr class="table_head">
                            <td colspan="3" class="padddingwithunderline"><b>Cash Flow from Investing Activities</b></td>
                        </tr>
                        <?php
                        $TotalNonCurrAsset=0;
                        $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '101%' AND HeadCode!=101 AND IsActive=1";

                        $sql9 = $this->db->query($sql);
                        $oResultNonCurrAsset = $sql9->result();

                        for($t=0;$t<count($oResultNonCurrAsset);$t++)
                        {
                        $COAID=$oResultNonCurrAsset[$t]->HeadCode;

                        $sql="SELECT SUM(acc_transaction.Debit)-SUM(acc_transaction.Credit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%')";

                        $sql8 = $this->db->query($sql);
                        $oResultAmount = $sql8->row();

                        if($oResultAmount->Amount!=0)
                        {
                        ?>
                        <tr >
                            <td align="left" class="paddingleft10px"><?php echo $oResultNonCurrAsset[$t]->HeadName; ?></td>
                            <td>&nbsp;</td>
                            <td align="right" class="cashflowamnt <?php if($TotalNonCurrAsset==0) echo 'footersignature' ?>">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total,2);
                                    $TotalNonCurrAsset+=$Total;
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                          }
                        ?>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr >
                            <td align="left" class="paddingleftright10"><strong>Net Cash Used Investing Activities</strong></td>
                            <td>&nbsp;</td>
                            <td align="right" class="noncurcss"><strong><?php echo number_format($TotalNonCurrAsset); ?></strong></td>
                        </tr>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td >&nbsp;</td>
                        </tr>
                       
                        <tr class="table_head">
                            <td colspan="3" class="padddingwithunderline"><b>Cash Flow from Financing Activities</b></td>
                        </tr>
                        <?php
                        $TotalNonCurrLiab=0;
                        $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '20102%' AND IsActive=1";

                        $sql10 = $this->db->query($sql);
                        $oResultNonCurrLiab = $sql10->result();

                        for($t=0;$t<count($oResultNonCurrLiab);$t++)
                        {
                        $COAID=$oResultNonCurrLiab[$t]->HeadCode;

                        $sql="SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%')";

                        $sql11 = $this->db->query($sql);
                        $oResultAmount = $sql11->row();

                        if($oResultAmount->Amount!=0)
                        {
                            ?>
                        <tr >
                            <td align="left" class="paddingleft10px"><?php echo $oResultNonCurrLiab[$t]->HeadName; ?></td>
                            <td>&nbsp;</td>
                            <td align="right" class="cashflowamnt <?php if($TotalNonCurrLiab==0) echo 'footersignature' ?>">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total);
                                    $TotalNonCurrLiab+=$Total;
                                ?>
                            </td>
                        </tr>
                        <?php
                          }
                        }
                        ?>
                        <?php
                        $TotalFund=0;
                        $sql="SELECT * FROM acc_coa WHERE IsGL=1 AND HeadCode LIKE '202%' AND IsActive=1";

                        $sql12 = $this->db->query($sql);
                        $oResultFund = $sql12->result();


                        for($t=0;$t<count($oResultFund);$t++)
                        {
                        $COAID=$oResultFund[$t]->HeadCode;

                        $sql="SELECT SUM(acc_transaction.Credit)-SUM(acc_transaction.Debit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove = 1 AND VDate BETWEEN '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%' AND VNo in (SELECT VNo FROM acc_transaction WHERE COAID LIKE '1020101%')";

                        $sql13 = $this->db->query($sql);
                        $oResultAmount = $sql13->row();

                        if($oResultAmount->Amount!=0)
                        {
                        ?>
                        <tr >
                            <td align="left" class="paddingleft10px"><?php echo $oResultFund[$t]->HeadName; ?></td>
                            <td>&nbsp;</td>
                            <td align="right" class="cashflowamnt">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total,2);
                                    $TotalFund+=$Total;
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                          }
                        ?>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr >
                            <td align="left" class="paddingleftright10"><strong>Net  Cash Used Financing Activities</strong></td>
                            <td>&nbsp;</td>
                            <td align="right" class="cashflowamnt"><strong><?php echo number_format($TotalFund+$TotalNonCurrLiab); ?></strong></td>
                        </tr>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td >&nbsp;</td>
                        </tr>
                        <tr >
                            <td align="left" class="paddingleft10px"><strong>Net Cash Inflow/Outflow (Profit Loss <?php echo number_format($TotalCurrAsset+$TotalCurrAssetNon+$TotalCurrLiab+$TotalNonCurrAsset+$TotalFund+$TotalNonCurrLiab); ?>)</strong></td>
                            <td>&nbsp;</td>
                            <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalCurrAsset+$TotalCurrAssetNon+$TotalCurrLiab+$TotalNonCurrAsset+$TotalFund+$TotalNonCurrLiab+$TotalOpening); ?></strong></td>
                        </tr>
                         <tr >
                            <td >&nbsp;</td>
                             <td>&nbsp;</td>
                           <td >&nbsp;</td>
                        </tr>
                      
                      <tr class="table_head">
                          <td colspan="3" class="paddingleft10px"><strong>Closing Cash & Cash Equivalent:</strong></td>
                        </tr>
                      <?php
                        $sql="SELECT * FROM acc_coa WHERE acc_coa.IsTransaction=1 AND acc_coa.HeadType='A' AND acc_coa.IsActive=1 AND acc_coa.HeadCode LIKE '1020101%' ";

                        $sql14 = $this->db->query($sql);
                        $oResultAsset = $sql14->result();

                        $TotalAsset=0;
                        for($i=0;$i<count($oResultAsset);$i++)
                        {
                          $COAID=$oResultAsset[$i]->HeadCode;
                          $sql="SELECT SUM(acc_transaction.Debit)- SUM(acc_transaction.Credit) AS Amount FROM acc_transaction INNER JOIN acc_coa ON acc_transaction.COAID = acc_coa.HeadCode WHERE acc_transaction.IsAppove =1 AND VDate BETWEEN  '".$dtpFromDate."' AND '".$dtpToDate."' AND COAID LIKE '$COAID%'";

                          $sql15 = $this->db->query($sql);
                          $oResultAmount = $sql15->row();

                          if($oResultAmount->Amount!=0)
                          {
                      ?>
                        <tr >
                            <td align="left" class="paddingleft10px"><?php echo $oResultAsset[$i]->HeadName; ?></td>
                            <td>&nbsp;</td>
                            <td align="right" class="cashflowamnt <?php if($TotalAsset==0) echo 'footersignature' ?>">
                                <?php 
                                    $Total=$oResultAmount->Amount;
                                    echo number_format($Total);
                                        $TotalAsset+=$Total; 
                                ?>
                            </td>
                        </tr>
                              <?php
                          }
                        }
                      ?>
                        <tr>
                          <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="footersignature">&nbsp;</td>
                        </tr>
                        <tr>
                         <td align="left" class="paddingleftright10"><strong>Total Closing Cash & Cash Equivalent</strong></td>
                          <td>&nbsp;</td>
                           <td align="right" class="totalopeninig"><strong><?php echo number_format($TotalAsset); ?></strong></td>
                        </tr>
                       
                    </table>
<br>
<br><br>
                   <table width="100%" cellpadding="1" cellspacing="20">
                            <tr>
                              <td width="20%" class="footersignature" align="center"><?php echo display('prepared_by')?></td>
                                <td width="20%" class="footersignature" align="center"><?php echo display('accounts')?></td>
                                <td width="20%" class="footersignature" align="center"><?php echo display('authorized_signature')?></td>
                                <td  width="20%" class="footersignature" align='center'><?php echo display('chairman')?></td>
                            </tr>
                          </table>
              </div>
                </div>
            </div>
            <div class="text-center" id="print" >
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

