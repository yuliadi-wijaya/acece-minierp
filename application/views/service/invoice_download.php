<?php
$CI = & get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
$user_type = $this->session->userdata('user_type');
$user_id = $this->session->userdata('user_id');
$logo = $Web_settings[0]['logo'];
$currency = $currency_details[0]['currency'];
$position = $currency_details[0]['currency_position'];
?>
<style type="text/css">
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid black;
    }
    
</style>
<div class="printableArea" id="printableArea">
   
    <div class="firts_section" style="">
        <div class="first_section_left"  style="width: 38%; display: inline-block;">
           <img src="<?php  echo $currency_details[0]['invoice_logo']; ?>" class="img-responsive" alt="">
            <address>
                <strong style="font-size: 20px; "><?php echo $company_info[0]['company_name']; ?></strong><br>
                <abbr><b><?php echo display('mobile') ?>:</b></abbr> <?php echo $company_info[0]['mobile']; ?><br>
                <abbr><b><?php echo display('email') ?>:</b></abbr> <?php echo $company_info[0]['email']; ?><br>
                <abbr><b><?php echo display('website') ?>:</b></abbr> <?php echo $company_info[0]['website']; ?><br>
            </address>
            <br>
        </div>
        <div class="first_section_middle" style="width: 28%; display: inline-block;">
            
        </div>
        <div class="first_section_right"  style="width: 28%; display: inline-block;">
            <h2 style="margin: 0px;"><?php echo display('service') ?></h2>
                                    <div><?php echo display('voucher_no') ?>: <?php echo $invoice_id;?></div>
                                    <div class="m-b-15"><?php echo display('hanging_over') ?>: <?php echo $final_date;?></div>

                                    <span class="label label-success-outline m-r-15"><?php echo display('billing_to') ?></span>
            <address> 
                <strong style="font-size: 20px; "><?php echo $customer_info->customer_name; ?> </strong>
                <?php echo $customer_info->customer_address; ?>
                
                <?php if ($customer_info->customer_mobile) { ?>
                                                                    
                    <?php
                    echo $customer_info->customer_mobile;
                }
                ?>
                <br>
                <?php if ($customer_info->customer_email) { ?>
                                                                                  
                    <?php echo $customer_info->customer_email; ?>
                <?php } ?>
              
            </address>
        </div>
    </div>
    <div class="">
    

                 
                                    <div class="table">
                                        <table class="table table-striped" width="100%">
                                          
                                            <thead>
                                                <tr>
                                                    <th><?php echo display('sl') ?></th>
                                                    <th class="text-left"><?php echo display('service_name') ?></th>
                                                    <th class="text-center"><?php echo display('qty') ?></th>
                                                    <th class="text-right"><?php echo display('charge') ?></th>
                                                    <?php if ($discount_type == 1) { ?>
                                            <th class="text-right"><?php echo display('discount_percentage') ?> %</th>
                                        <?php } elseif ($discount_type == 2) { ?>
                                            <th class="text-right"><?php echo display('discount') ?> </th>
                                        <?php } elseif ($discount_type == 3) { ?>
                                            <th class="text-right"><?php echo display('fixed_dis') ?> </th>
                                        <?php } ?>
                                                    <th class="text-right"><?php echo display('total') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ssl = 1;
                                                $subtotalservice = 0;
                                                foreach ($invoice_detail as $service) {
                                           
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $ssl ?></td>
                                                            <td class="text-left"><?php echo $service['service_name']; ?></td>
                                                            <td style="text-align: right;padding-right: 10px; "><?php echo $service['qty']; ?></td>
                                                            <td style="text-align: right;padding-right: 10px;">
                                                                <?php
                                                                $charge = $service['charge'];
                                                                echo (($position == 0) ? "$currency $charge" : "$charge $currency");
                                                                ?>
                                                            </td>
                                                            <td style="text-align: right;padding-right: 10px;">
                                                                <?php
                                                                $diper = $service['discount'];
                                                                echo (!empty($diper)?$diper:'');
                                                                ?>
                                                            </td>
                                                            <td style="text-align: right;padding-right: 10px;">
                                                                <?php
                                                                $subtotalservice += $service['total'];
                                                                $totalcharge = $service['total'];
                                                                echo (($position == 0) ? "$currency $totalcharge" : "$totalcharge $currency");
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $ssl++;
                                                       
                                                    
                                                }
                                                ?>
                                                 <tr>
                                            <td style="text-align: right;padding-right: 10px; " colspan="2" style="border: 0px"><b><?php echo display('grand_total') ?>:</b></td>
                                            <td style="text-align: right;padding-right: 10px; "  style="border: 0px"><b><?php echo $subTotal_quantity;?></b></td>
                                            <td style="border: 0px"></td>
                                            <td style="border: 0px"></td>
                                            <td   style="border: 0px;text-align: right;padding-right: 10px;"><b><?php echo (($position == 0) ? "$currency $subTotal_ammount" : "$subTotal_ammount $currency") ?></b></td>
                                        </tr>
                                            </tbody>
                                        </table>
                                    </div>
                               
            <div class="row">
                                    
                                    <div class="table-responsive m-b-20">
                                         <table class="table">
                                        <?php
                                        if ($invoice_detail[0]['total_discount'] != 0) {
                                            ?>
                                            <tr>
                                                <th style="border-top: 0; border-bottom: 0;" ><?php echo display('total_discount') ?> : </th>
                                                <th class="text-right" style="border-top: 0; border-bottom: 0;text-align: right;padding-right: 10px;"><?php echo (($position == 0) ? "$currency $total_discount" : "$total_discount $currency") ?> </th>
                                            </tr>
                                            <?php
                                        }
                                        if ($invoice_detail[0]['total_tax'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left" style="border-top: 0; border-bottom: 0;text-align: right;"><?php echo display('tax') ?> : </th>
                                                <th  class="text-right" style="border-top: 0; border-bottom: 0;text-align: right;padding-right: 10px;"><?php echo (($position == 0) ? "$currency $total_tax" : "$total_tax $currency") ?> </th>
                                            </tr>
                                        <?php } ?>
                                         <?php if ($invoice_detail[0]['shipping_cost'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left" style="border-top: 0; border-bottom: 0;text-align: right;"><?php echo display('shipping_cost'); ?> : </th>
                                                <th class="text-right" style="border-top: 0; border-bottom: 0;text-align: right;padding-right: 10px;"><?php echo (($position == 0) ? "$currency $shipping_cost" : "$shipping_cost $currency") ?> </th>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <th style="text-align: right; "><?php echo display('previous'); ?> :</th>
                                            <th style="text-align: right;padding-right: 10px; "><?php echo (($position == 0) ? "$currency $previous" : "$previous $currency") ?></th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right; "><?php echo display('grand_total') ?> :</th>
                                            <th style="text-align: right;padding-right: 10px; "><?php echo (($position == 0) ? "$currency $total_amount" : "$total_amount $currency") ?></th>
                                        </tr>
                                        <tr>
                                            <th class="text-left grand_total" style="border-top: 0; border-bottom: 0;text-align: right;"><?php echo display('paid_ammount') ?> : </th>
                                            <th class="text-right grand_total" style="border-top: 0; border-bottom: 0;text-align: right;padding-right: 10px;"><?php echo (($position == 0) ? "$currency $paid_amount" : "$paid_amount $currency") ?></th>
                                        </tr>                
                                        <?php
                                        if ($invoice_detail[0]['due_amount'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left grand_total" style="text-align: right; "><?php echo display('due') ?> : </th>
                                                <th  style="text-align: right;padding-right: 10px; "><?php echo (($position == 0) ? "$currency $due_amount" : "$due_amount $currency") ?></th>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                  
                                  
                                    <div class="row" style="margin-top: 50px">
                                        <div style="width: 50%; margin-left: 30px; ">
                                            <strong><?php echo display('description') ?> : </strong> <?php echo $details; ?>
                                        </div>
                                    </div>
                                  
                                </div>
    </div>

</div>
