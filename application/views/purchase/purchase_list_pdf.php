<div class="content-wrapper">
	<section class="content">
		<!-- Manage Purchase report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                   <?php echo display('purchase_list')?>
		                </div>
		              
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table border="1" width="100%">
		                    	<caption>
                               {company_info}
                                     <address>
                                        <strong >{company_name}</strong><br>
                                        {address}<br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr> {mobile}<br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        {email}<br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        {website}
                                    </address>

                               {/company_info}
                           </caption>
								<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('invoice_no') ?></th>
										<th><?php echo display('purchase_id') ?></th>
										<th><?php echo display('supplier_name') ?></th>
										<th><?php echo display('purchase_date') ?></th>
										<th><?php echo display('total_ammount') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($purchase_list) {
								?>
							
								<?php 
								$subtotal ='0.00';
								foreach($purchase_list as $purchase){?>
									<tr>
										<td><?php echo $purchase['sl']; ?></td>
										<td>
											<?php echo $purchase['chalan_no'] ?>	
										</td>
										<td>
											
											<?php echo $purchase['purchase_id'] ?>	
			
										</td>
										<td>
											
											<?php echo $purchase['supplier_name'] ?>	
										
										</td>
										<td>	<?php echo $purchase['purchase_date'] ?></td>
										<td><?php 
										if(($position==0)){
										   echo  $currency.' '.$purchase['grand_total_amount'];
										}else{
										    echo $purchase['grand_total_amount'].' '.$currency;
										}
										$subtotal += $purchase['grand_total_amount'];
										?>
										</td>
							
									</tr>
							
								<?php
								}
									}
								?>
								</tbody>
								
		                    </table>
		                </div>
		               
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
