<!-- Manage Invoice Start -->
<div class="content-wrapper">
    <section class="content">

        <!-- Manage Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('invoice_return_list') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table border="1" width="100%">
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
                                        <th><?php echo display('invoice_id') ?></th>
                                        <th><?php echo display('customer_name') ?></th>
                                        <th><?php echo display('date') ?></th>
                                        <th><?php echo display('payment_type') ?></th>
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
                                                <a href="<?php echo base_url() . 'Cretrun_m/invoice_inserted_data/{invoice_id}'; ?>">
                                                    {invoice_id}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url() . 'Ccustomer/customerledger/{customer_id}'; ?>">{customer_name}</a>				
                                            </td>

                                            <td>{final_date}</td>
                                             <td>Cash Payment</td>
                                            <td ><?php echo (($position == 0) ? "$currency {net_total_amount}" : "{net_total_amount} $currency") ?></td>
                                  
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

