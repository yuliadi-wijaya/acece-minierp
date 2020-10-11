<!-- Purchase Payment Ledger Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('purchase_ledger') ?></h1>
	        <small><?php echo display('purchase_ledger') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('purchase') ?></a></li>
	            <li class="active"><?php echo display('purchase_ledger') ?></li>
	        </ol>
	    </div>
	</section>

	<!-- Invoice information -->
	<section class="content">

		<!-- Alert Message -->
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
            <div class="col-sm-12">
                <?php if($this->permission1->method('add_supplier','create')->access()){ ?>
                  <a href="<?php echo base_url('Cpurchase')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_purchase')?> </a>
                  <?php }?>
                <?php if($this->permission1->method('manage_supplier','read')->access()){ ?>
                  <a href="<?php echo base_url('Cpurchase/manage_purchase')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('manage_purchase')?> </a>
              <?php }?>

               
            </div>
        </div>

	

		<!-- Purchase Ledger -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <span><?php echo display('purchase_details') ?></span>
		                    <span class="print-button">
		                     <button  class="btn btn-info " onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button></span>
		                </div>
		            </div>
		            <div class="panel-body" id="printableArea">
		    
           <div class="row purchasedetails-header">
                                
                                <div class="col-sm-8 purchasedetails-address">
                                    {company_info}
                                    <img src="<?php
                                    if (isset($Web_settings[0]['invoice_logo'])) {
                                        echo $Web_settings[0]['invoice_logo'];
                                    }
                                    ?>" class="" alt="">
                                    <br>
                                     
                                    <span class="label label-success-outline m-r-15 p-10" ><?php echo display('billing_from') ?></span>
                                    <address>
                                        <strong class="companyname" >{company_name}</strong><br>
                                        {address}<br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr> {mobile}<br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        {email}<br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        {website}<br>
                                         {/company_info}
                                        
                                    </address>
                                   
                                  

                                </div>
                                
                                 
                                <div class="col-sm-4 text-left invoice-details-billing">
                                    <h2 class="m-t-0"><?php echo display('purchase') ?></h2>
                                    <div><?php echo display('invoice_no') ?>: {chalan_no}</div>
                                    <div class="m-b-15"><?php echo display('billing_date') ?>: {final_date}</div>

                                    <span class="label label-success-outline m-r-15"><?php echo display('billing_to') ?></span>

                                    <address class="details-address">  
                                        <strong class="companyname">{supplier_name} </strong><br>
                                       
                                    </address>
                                </div>
                            </div> 

                          <br>


		                <div class="table-responsive">
		                    <table  class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('product_name') ?></th>
										<th class="text-center"><?php echo display('quantity') ?></th>
										<th class="text-center"><?php echo display('rate') ?></th>
										<th class="text-center"><?php echo display('total_ammount') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($purchase_all_data) {
								?>
								{purchase_all_data}
									<tr>
										<td>{sl}</td>
										<td>
											
											{product_name}-({product_model})
											
										</td>
										<td class="text-right">{quantity}</td>
										<td class="text-right"><?php echo (($position==0)?"$currency {rate}":"{rate} $currency") ?></td>
										<td class="text-right"><?php echo (($position==0)?"$currency {total_amount}":"{total_amount} $currency") ?></td>
									</tr>
								{/purchase_all_data}
								<?php
									}
								?>
								</tbody>
								<tfoot>
									<tr>
										<td class="text-right" colspan="4"><b><?php echo display('total') ?>:</b></td>
										<td  class="text-right"><b><?php echo (($position==0)?"$currency {total}":"{total} $currency") ?></b></td>
									</tr>
									 <?php if($discount > 0){?>
									<tr>
										<td class="text-right" colspan="4"><b><?php echo display('discounts') ?>:</b></td>
										<td  class="text-right"><b><?php echo (($position==0)?"$currency {discount}":"{discount} $currency") ?></b></td>
									</tr>
								<?php }?>
									<tr>
										<td class="text-right" colspan="4"><b><?php echo display('grand_total') ?>:</b></td>
										<td  class="text-right"><b><?php echo (($position==0)?"$currency {sub_total_amount}":"{sub_total_amount} $currency") ?></b></td>
									</tr>
									 <?php if($paid_amount > 0){?>
									<tr>
										<td class="text-right" colspan="4"><b><?php echo display('paid_amount') ?>:</b></td>
										<td  class="text-right"><b><?php echo (($position==0)?"$currency {paid_amount}":"{paid_amount} $currency") ?></b></td>
									</tr>
								<?php }?>
                              <?php if($due_amount > 0){?>
									<tr>
										<td class="text-right" colspan="4"><b><?php echo display('due_amount') ?>:</b></td>
										<td  class="text-right"><b><?php echo (($position==0)?"$currency {due_amount}":"{due_amount} $currency") ?></b></td>
									</tr>
								<?php }?>
								</tfoot>
		                    </table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Purchase ledger End  -->