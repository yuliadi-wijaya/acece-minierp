<!-- Manage Invoice Start -->
<div class="content-wrapper">
	<section class="content">
		<!-- Alert Message -->
  
		<!-- Manage Invoice report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table border="1" width="100%" >
		                    	 <caption>
                               {company_info}
                                     <address>
                                        <strong>{company_name}</strong><br>
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
										<th><?php echo display('purchase_id') ?></th>
										<th><?php echo display('supplier_name') ?></th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('total_amount') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($return_list) {
								?>
								{return_list}
									<tr>
										<td>{sl}</td>
										<td>
											
											{purchase_id}
											
										</td>
										<td>
											{supplier_name}			
										</td>

										<td>{final_date}</td>
										<td class="text-right"><?php echo (($position==0)?"$currency {net_total_amount}":"{net_total_amount} $currency") ?></td>
									
									</tr>
								{/return_list}
								<?php
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
<!-- Manage Invoice End -->

