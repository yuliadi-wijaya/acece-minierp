<link href="<?php echo base_url('assets/css/payslip.css') ?>" rel="stylesheet" type="text/css"/>
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('payroll') ?></h1>
			<small><?php echo $title; ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('payroll') ?></a></li>
				<li class="active"><?php echo $sub_title; ?></li>
			</ol>
		</div>
	</section>
	<section class="content">
		<!-- Alert Message -->
		<?php
			$message = $this->session->userdata('message');
			if (isset($message)) {
			    ?>
		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4>
				<i class="icon fa fa-check"></i> Success!
			</h4>
			<?php echo $message ?>
		</div>
		<?php
			$this->session->unset_userdata('message');
			}
			$error_message = $this->session->userdata('error_message');
			if (isset($error_message)) {
			?>
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4>
				<i class="icon fa fa-ban"></i> Error!
			</h4>
			<?php echo $error_message ?>
		</div>
		<?php
			$this->session->unset_userdata('error_message');
			}
			?>
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="panel panel-bd">
					<div class="panel title text-right">
						<button  class="btn btn-primary" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button>
					</div>
					<div id="printableArea">
						<div class="panel-body" id="payslip">
							<div class="row" >
                                <div class="col-sm-12 text-center">
                                    <img src="<?php echo(!empty($setting[0]['invoice_logo'])?$setting[0]['invoice_logo']:'') ?>" width="75px;" alt="">
                                </div>
                                <div class="col-sm-12 text-center">
                                    <address>
                                        <strong class="font30" style="font-size: 30px;"><?php echo (!empty($company[0]['company_name'])?$company[0]['company_name']:'Bdtask Ltd')?></strong><br>
                                        <?php echo (!empty($company[0]['address'])?$company[0]['address']:'Demo Address')?><br>
                                        <b> Salary Slip - <?php echo  $paymentdata[0]['salary_month']?></b>
                                    </address>
                                </div>
                                <br/>
								<div class="col-sm-12 m-b-20 m-t-20">
									<div id="details">
                                        <div class="scope-entry">
                                            <table width="100%">
                                                <tr>
                                                    <th style="width:135px"><?php echo display('employee_name')?></th>
                                                    <td>: <?php echo  $paymentdata[0]['first_name'].' '.$paymentdata[0]['last_name']?><td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo display('designation')?></th>
                                                    <td>: <?php echo  $paymentdata[0]['position_name']?><td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo display('salary_date')?></th>
                                                    <td>: <?php echo  $paymentdata[0]['payment_date']?><td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
									<table class="table">
                                        <tbody style="border-bottom: 1px solid #ccc">
                                            <tr>
                                                <td class="left-panel borderright">
                                                    <table class="table-payslip" width="100%">
                                                        <thead>
                                                            <tr class="employee">
                                                                <th class="name text-center border-bottom" colspan="2"><?php echo display('earnings'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="details">
                                                            <tr class="entry">
                                                                <td class=""><?php if($paymentdata[0]['salarytype'] == 1){ echo display('basic_salary');}else{echo display('basic_salary');}?></td>
                                                                <td class="">
                                                                    <div><?php if($paymentdata[0]['salarytype'] == 1){ echo $basicsal = $paymentdata[0]['basic']*$paymentdata[0]['total_working_minutes'];}else{echo $basicsal = $paymentdata[0]['basic'];}?></div>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                                $totalAddition = 0;
                                                                foreach($addition as $additions){?>
                                                            <tr class="entry">
                                                                <td class=""><?php echo  $additions->sal_name;?></td>
                                                                <td class="">
                                                                    <div><?php echo  $basicsal*($additions->amount)/100;
                                                                        $totalAddition +=$basicsal*($additions->amount)/100;
                                                                        ?></div>
                                                                </td>
                                                            </tr>
                                                            <?php }?>
                                                            <tr class="entry nti" style="background-color: #f1f3f6; padding: 5px">
                                                                <td style="background-color: #f1f3f6" class="text-left"><?php echo  display('total_addition')?></td>
                                                                <td style="background-color: #f1f3f6" class="value"><b><?php echo number_format($totalAddition+$basicsal,2); ?></b></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="right-panel">
                                                    <table class="table-payslip" width="100%">
                                                        <thead>
                                                            <tr class="employee">
                                                                <th class="name text-center border-bottom" colspan="2"><?php echo display('deduction'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="details">
                                                            <?php 
                                                                $totalDeduction = 0;
                                                                foreach($deduction as $deductions){?>
                                                            <tr class="entry">
                                                                <td class=""><?php echo $deductions->sal_name; ?></td>
                                                                <td class="">
                                                                    <div><?php echo  $basicsal*($deductions->amount)/100;
                                                                        $totalDeduction +=$basicsal*($deductions->amount)/100;
                                                                        ?></div>
                                                                </td>
                                                            </tr>
                                                            <?php }?>
                                                            <?php $gross = $totalAddition+($basicsal-$totalDeduction);
                                                                if($paymentdata[0]['total_salary'] < $gross){
                                                                ?>
                                                            <tr class="entry">
                                                                <td class=""><?php echo  display('tax')?></td>
                                                                <td class="">
                                                                    <div><?php  $tax = $gross - intval(str_replace(',', '', $paymentdata[0]['total_salary']));
                                                                        echo $totaltax = number_format($tax,2);
                                                                        ?></div>
                                                                </td>
                                                            </tr>
                                                            <?php }?>
                                                            <tr class="entry nti" style="background-color: #f1f3f6; padding: 5px">
                                                                <td style="background-color: #f1f3f6" class="text-left"><?php echo  display('total_deduction')?></td>
                                                                <td style="background-color: #f1f3f6" class="value"><b><?php echo number_format($totalDeduction+(!empty($totaltax)?$totaltax:0),2); ?></b></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                        
                                    </table>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<table width="100%">
										<tr class="details">
										<tbody class="nti" style="border-top: 0px;">
											<td class=""><b><?php echo display('net_salary'); ?>:</b> <i>(<?php echo $amountinword; ?>)</i></td>
											<td class="value text-right"><b><?php echo number_format($paymentdata[0]['total_salary'], 2)?></b> </td>
										</tbody>
										</tr>
									</table>
								</div>
							</div>
							<div class="row m-t-20">
								<div class="col-sm-12">
									<b><?php echo  display('ref_number')?>: .........</b>
								</div>
							</div>
							<div class="row" style="margin-top: 65px;">
                                <div class="col-sm-12">
									<table width="100%">
                                        <tr>
                                            <td class="text-left">
                                                <div class="employee-signature">
                                                    <?php echo display('employee_signature'); ?>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div  class="paidby">
                                                    <?php echo display('paid_by'); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
								</div>
							</div>
                        </div>
                        <div class="panel-footer" style="background-color:#3c8dbc">
                            <div>
                                <p></p>
                                <span class="stats-small"></span>
                            </div>
                        </div>
                        <style>
                            .table-payslip tbody tr {
                                padding: 5px;
                            }
                            #scope > .scope-entry {
                                text-align: center;
                                padding-bottom: 10px;
                            }

                            #payslip {
                                background: #fff;
                                color: #000;
                                padding: 30px 40px;
                            }

                            #title {
                                margin-bottom: 0px;
                                font-size: 38px;
                                font-weight: 600;
                            }

                            #scope {
                                border-top: 1px solid #ccc;
                                border-bottom: 1px solid #ccc;
                                padding: 7px 0 4px 0;
                                display: flex;
                                
                            }

                            #scope > .scope-entry {
                                text-align: center;
                            }
                            .scope-entry > .title {
                                font-size: 15px;
                                font-weight: 700;
                                text-align: left;
                                }

                            #scope > .scope-entry > .value {
                                font-size: 14px;
                                font-weight: 700;
                            }

                            .content {
                                display: flex;
                                height: 100%;
                            }

                            .content .left-panel {
                                border-right: 1px solid #ccc;
                                width: 50%;
                                padding: 9px 16px 0 0;
                            }
                            #payslip #panel-footer {
                                width: 100%;
                                padding: 9px 16px 0 0;
                            }
                            .content .right-panel {
                                width: 50%;
                                padding: 10px 0  0 16px;
                            }

                            .employee {
                                text-align: center;
                                margin-bottom: 20px;
                            }
                            .employee .name {
                                font-size: 15px;
                                font-weight: 700;
                                border-bottom: 1px solid #ccc;
                            }

                            #employee #email {
                                font-size: 11px;
                                font-weight: 300;
                            }

                            .details, .contributions, .ytd, .gross {
                                margin-bottom: 20px;
                            }

                            .details .entry, .contributions .entry, .ytd .entry {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 6px;
                            }

                            .details .entry .value, .contributions .entry .value, .ytd .entry .value {
                                font-weight: 700;
                                max-width: 130px;
                                text-align: right;
                            }

                            .gross .entry .value {
                                font-weight: 700;
                                text-align: right;
                                font-size: 16px;
                            }

                            .contributions .title, .ytd .title, .gross .title {
                                font-size: 20px;
                                font-weight: 700;
                                border-bottom: 1px solid #ccc;
                                text-align: left;
                                padding-bottom: 4px;
                                margin-bottom: 6px;
                            }

                            .content .right-panel .details {
                                width: 100%;
                            }

                            .content .right-panel .details .label {
                                font-weight: 700;
                                width: 120px;
                            }

                            .content .right-panel .details .detail {
                                font-weight: 600;
                                width: 130px;
                            }

                            .content .right-panel .details .rate {
                                font-weight: 400;
                                width: 80px;
                                font-style: italic;
                                letter-spacing: 1px;
                            }

                            .content .right-panel .details .amount {
                                text-align: right;
                                font-weight: 700;
                                width: 90px;
                            }

                            .content .right-panel .details .net_pay div, .content .right-panel .details .nti div {
                                font-weight: 600;
                                font-size: 12px;
                            }

                            .content .right-panel .details .net_pay, .content .right-panel .details .nti {
                                padding: 3px 0 2px 0;
                                margin-bottom: 10px;
                                color:#000;
                                background: rgba(0, 0, 0, 0.04);
                            }

                            .content .left-panel .details .net_pay, .content .left-panel .details .nti {
                                padding: 3px 0 2px 0;
                                margin-bottom: 10px;
                                color:#000;
                                background: rgba(0, 0, 0, 0.04);
                            }

                            .content .right-panel .details .label {
                                font-weight: 600;
                                width: 130px;
                                color: #000;
                                font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                            }
                            #payslip .footer{
                                padding: 3px 0 2px 0;
                                margin-bottom: 10px;
                                color:#000;
                                background: rgba(0, 0, 0, 0.04);
                            }


                            .footertext{
                                font-weight: 600;
                                color: #000;
                                font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                                font-size: 20px;
                            }


                            .left-panel .details .nti {
                                padding: 3px 0 2px 0;
                                margin-bottom: 10px;
                                font-weight: 800;
                                color: #000;
                                background: rgba(0, 0, 0, 0.04);
                            }

                            .right-panel .details .nti {
                                padding: 3px 0 2px 0;
                                margin-bottom: 10px;
                                font-weight: 800;
                                color: #000;
                                background: rgba(0, 0, 0, 0.04);
                            }

                            .details .nti {
                                padding: 3px 0 2px 0;
                                margin-bottom: 10px;
                                font-weight: 800;
                                color: #000;
                                background: rgba(0, 0, 0, 0.04);
                            }

                            .margin-top10{
                                margin-top:10px;
                            }
                            .font30{
                                font-size: 30px;
                            }

                            .borderright{
                                border-right: 1px solid #ccc;
                            }

                            .border-bottom{
                                border-bottom: 1px solid #ccc;
                            }

                            .paddingbottom{
                                padding-bottom: 50px;
                            }

                            .employee-signature{
                                float:left;width:40%;
                                text-align:center;
                                border-top:1px solid #e4e5e7;
                                font-weight: bold;
                            }

                            .paidby{
                                float:right;width:40%;
                                text-align:center;
                                border-top:1px solid #e4e5e7;
                                font-weight: bold;
                            }
                        </style>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>