
        <!-- Manage Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    
                    <div class="panel-body">
                        <div class="table-responsive" id="invoic_list">
                            <div class="ticket-logo">
                         <img src="<?php echo $logo; ?>" class="img-responsive" alt=""> 
                                        </div>
                            <table  border="1" width="100%" >
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
                                        <th><?php echo display('invoice_no') ?></th>
                                        <th><?php echo display('invoice_id') ?></th>
                                        <th><?php echo display('customer_name') ?></th>
                                        <th><?php echo display('date') ?></th>
                                        <th><?php echo display('total_amount') ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = '0.00';
                                    if ($invoices_list) {
                                        foreach($invoices_list as $invoices_list){
                                        ?>
                                  
                                        <tr>
                                            <td><?php echo html_escape($invoices_list['sl']); ?></td>
                                            <td>
                                                                                                
                                                    <?php echo html_escape($invoices_list['invoice']); ?>
                                             
                                            </td>
                                            <td>

                                                  <?php echo html_escape($invoices_list['invoice_id'])?>
                                            </td>
                                            <td>
                                           <?php echo html_escape($invoices_list['customer_name'])?>			
                                            </td>

                                            <td><?php echo html_escape($invoices_list['final_date'])?></td>
                                            <td><?php 
                                            if($position == 0){
                                              echo html_escape($currency.' '.$invoices_list['total_amount']);  
                                            }else{
                                            echo html_escape($invoices_list['total_amount'].$currency); 
                                            }
                                            $total += $invoices_list['total_amount']; ?></td>
                                           
                                    </tr>
                                 
                                    <?php
                                }
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="5" ><?php echo display('total') ?></td><td><?php
                                    $sttle =html_escape(number_format($total, 2, '.', ','));
                                     if($position == 0){
                                              echo $currency.''.$sttle;  
                                            }else{
                                            echo $sttle.''.$currency; 
                                            } ?></td></tr>
                                </tfoot>
                            </table>
                           
                        </div>
                       


                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
