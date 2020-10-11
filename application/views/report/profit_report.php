

<!-- Profit Report Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('profit_report') ?></h1>
	        <small><?php echo display('total_profit_report')?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('report') ?></a></li>
	            <li class="active"><?php echo display('profit_report') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">

		<div class="row">
            <div class="col-sm-12">
               
                
              <?php if($this->permission1->method('todays_sales_report','read')->access()){ ?>
                    <a href="<?php echo base_url('Admin_dashboard/todays_sales_report') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('sales_report') ?> </a>
                <?php }?>
        <?php if($this->permission1->method('todays_purchase_report','read')->access()){ ?>
                    <a href="<?php echo base_url('Admin_dashboard/todays_purchase_report') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('purchase_report') ?> </a>
                  <?php }?>
                  <?php if($this->permission1->method('product_sales_reports_date_wise','read')->access()){ ?>
                    <a href="<?php echo base_url('Admin_dashboard/product_sales_reports_date_wise') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('sales_report_product_wise') ?> </a>
                    <?php }?>
    

               
            </div>
        </div>

		<!-- Profit report -->
		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
		                <?php echo form_open('Admin_dashboard/retrieve_dateWise_profit_report',array('class' => 'form-inline','method' => 'get'))?>
		                <?php date_default_timezone_set("Asia/Jakarta"); $today = date('Y-m-d'); ?>
		                    <div class="form-group">
		                        <label for="from_date"><?php echo display('start_date') ?>:</label>
		                        <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" >
		                    </div> 

		                    <div class="form-group">
		                        <label for="to_date"><?php echo display('end_date') ?>:</label>
		                        <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $today?>">
		                    </div>  

		                    <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
		                    <a  class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo display('print') ?></a>
		               <?php echo form_close()?>
		            </div>
		        </div>
		    </div>
	    </div>

		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('profit_report') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		            	<div id="purchase_div">
			            	 <table class="print-table" width="100%">
                                                
                                                <tr>
                                                    <td align="left" class="print-table-tr">
                                                        <img src="<?php echo $software_info[0]['logo'];?>" alt="logo">
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
			                <div class="table-responsive">
			                    <table class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th><?php echo display('sales_date') ?></th>
											<th class="text-center"><?php echo display('invoice_no') ?></th>
											<th class="text-center"><?php echo display('supplier_ammount') ?></th class="text-center">
											<th class="text-center"><?php echo display('my_sale_ammount') ?></th>
											<th class="text-center"><?php echo display('total_profit') ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										if($total_profit_report) {
									?>
										{total_profit_report}
											<tr>
												<td>{prchse_date}</td>
												<td>
													
														{invoice}
												
												</td>
												<td class="text-right"><?php echo (($position==0)?"$currency {total_supplier_rate}":"{total_supplier_rate} $currency") ?></td>
												<td class="text-right">
												<?php echo (($position==0)?"$currency {total_sale}":"{total_sale} $currency") ?>
												</td>
												<td class="text-right"><?php echo (($position==0)?"$currency {total_profit}":"{total_profit} $currency") ?></td>
											</tr>
										{/total_profit_report}
									<?php
										}
									?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" align="right">&nbsp; <b><?php echo display('total') ?>: </b></td>

											<td class="text-right"><b><?php echo (($position==0)?"$currency {SubTotalSupAmnt}":"{SubTotalSupAmnt} $currency") ?></b></td>	

											<td class="text-right"><b><?php echo (($position==0)?"$currency {SubTotalSaleAmnt}":"{SubTotalSaleAmnt} $currency") ?></b></td>

											<td class="text-right"><b><?php echo (($position==0)?"$currency {profit_ammount}":"{profit_ammount} $currency") ?></b></td>
										</tr>
									</tfoot>
			                    </table>
			                </div>
			            </div>
		                <div class="text-right"><?php echo $links?></div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
 <!-- Profit Report End -->