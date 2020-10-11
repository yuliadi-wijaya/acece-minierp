 <section class="content">
  
        <!-- Manage Customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_customer') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <!--dataTableExample3-->
                            <table  border="1" width="100%">
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
                                        <th><?php echo display('customer_name') ?></th>
                                        <th><?php echo display('address') ?></th>
                                        <th><?php echo display('mobile') ?></th>
                                        <th><?php echo display('balance') ?></th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($customer_list) {
                                        $subtotal='0.00';
                                        foreach ($customer_list as $customer) {
                                            $sql = 'SELECT (SELECT SUM(amount) FROM customer_ledger WHERE d_c = "d" AND customer_id = "' . $customer['customer_id'] . '") dr, 
		(SELECT sum(amount) FROM customer_ledger WHERE d_c = "c" AND customer_id = "' . $customer['customer_id'] . '") cr';
                                            $result = $this->db->query($sql)->result();
                                            ?>
                                            <tr>
                                                <td><?php echo $customer['sl']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url() . 'Ccustomer/customerledger/' . $customer['customer_id']; ?>"><?php echo html_escape($customer['customer_name']); ?></a>				
                                                </td>
                                                <td><?php echo html_escape($customer['customer_address']); ?></td>
                                                <td><?php echo html_escape($customer['customer_mobile']); ?></td>

                                                <td align="right">
                                                    <?php
                                                    echo (($position == 0) ? "$currency " : " $currency");
                                                    $balance = $result[0]->dr - $result[0]->cr;
                                                    echo $total = html_escape(number_format($balance, '2', '.', ','));
                                                    $subtotal +=$balance;
                                                    ?>
                                                </td>
                                    
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <th class="text-center" colspan="5"><?php echo display('not_found'); ?></th>
                                    </tr> 
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>   <td></td>
                                        <td colspan="3"><b> <?php echo display('grand_total') ?></b></td>
                                        <td>
                                            <b><?php
                                            $sttle =html_escape(number_format($subtotal, 2, '.', ',')); echo (($position == 0) ? "$currency $sttle" : "$sttle $currency") ?></b>
                                         
                                         
                                        </td>
                                     
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
