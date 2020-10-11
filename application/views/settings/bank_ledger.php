
<!-- Stock List Supplier Wise Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('bank_ledger') ?></h1>
	        <small><?php echo display('bank_ledger') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('bank') ?></a></li>
	            <li class="active"><?php echo display('bank_ledger') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">

		<div class="row">
            <div class="col-sm-12">

                	 <?php if($this->permission1->method('add_bank','create')->access()){ ?>
                	<a href="<?php echo base_url('Csettings/index')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('add_new_bank')?> </a>
                 <?php }?>
              <?php if($this->permission1->method('bank_transaction','create')->access()){ ?>
                  	<a href="<?php echo base_url('Csettings/bank_transaction')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('bank_transaction')?> </a>
                   <?php }?>
                <?php if($this->permission1->method('bank_list','read')->access()){ ?>   
                  	<a href="<?php echo base_url('Csettings/bank_list')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('manage_bank')?> </a>
                  	<?php }?>

               
            </div>
        </div>
         <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body"> 
                        <?php echo form_open('Csettings/bank_ledger/', array('class' => 'form-inline', 'method' => 'post')) ?>
                        <?php $today = date('Y-m-d'); ?>
                         <label class="select"><?php echo display('bank') ?> : </label>
                        <select name="bank_id" class="form-control">
                        	<option value="">Select Bank</option>
                        	<?php foreach($bank_list as $banks){?>
                        	<option value="<?php echo $banks['bank_id']?>"><?php echo $banks['bank_name']?></option>
                        <?php }?>
                        </select>
                        <label class="select"><?php echo display('search_by_date') ?>: <?php echo display('from') ?></label>
                        <input type="text" name="from_date"  value="<?php echo $today; ?>" class="datepicker form-control"/>
                        <label class="select"><?php echo display('to') ?></label>
                        <input type="text" name="to_date" class="datepicker form-control" value="<?php echo $today; ?>"/>
                        <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                        <a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                        <?php echo form_close() ?>		            
                    </div>
                </div>
            </div>
             </div>

		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('bank_ledger') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		            
			            <div class="text-right">
			            	<button  class="btn btn-warning text-right" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></button>
			            </div>
		            	
						<div id="printableArea">
							<table class="print-table" width="100%">
                                                
                                                <tr>
                                                    <td align="left" class="print-table-tr">
                                                        <img src="<?php echo $software_info[0]['logo'];?>" alt="logo">
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
				

			                <div class="table-responsive">
			                    <table id="" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('date') ?></th>
											<th class="text-center"><?php echo display('bank_name') ?></th>
											<th class="text-center"><?php echo display('description') ?></th>
											<th class="text-center"><?php echo display('withdraw_deposite_id') ?></th>
											<th class="text-center"><?php echo display('debit_plus') ?></th>
											<th class="text-center"><?php echo display('credit_minus') ?></th>
											<th class="text-center"><?php echo display('balance') ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										if ($ledger) {
									?>
									{ledger}
										<tr>
											<td>{VDate}</td>
											<td>{HeadName}</td>
											<td>{Narration}</td>
											<td align="center">{VNo}</td>
											<td align="right"><?php echo (($position==0)?"$currency {debit}":"{debit} $currency") ?></td>
											<td align="right"><?php echo (($position==0)?"$currency {credit}":"{credit} $currency") ?></td>

											<td align="right"><?php echo (($position==0)?"$currency {balance}":"{balance} $currency") ?></td>
										</tr>
									{/ledger}
									<?php
										}
									?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4" align="right"><b><?php echo display('grand_total')?>:</b></td>

											<td align="right"><b><?php echo (($position==0)?"$currency {total_debit}":"{total_debit} $currency") ?></b></td>

											<td align="right"><b><?php echo (($position==0)?"$currency {total_credit}":"{total_credit} $currency") ?></b></td>

											<td align="right"><b><?php echo (($position==0)?"$currency {balance}":"{balance} $currency") ?></b></td>
											
										</tr>
									</tfoot>
			                    </table>
			                </div>
			            </div>
		                <div class="text-center">
		                	<?php if (isset($link)) { echo $link ;} ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Stock List Supplier Wise End -->

