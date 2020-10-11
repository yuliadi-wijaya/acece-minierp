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
            <h1 style="margin-top:0px;margin-bottom: 0px;"><?php echo display('quotation'); ?></h1>
            <address> 
                <strong style="font-size: 20px; "><?php echo $customer_info[0]['customer_name']; ?> </strong><br>
                <?php echo $customer_info[0]['customer_address']; ?>
                <br>
                <?php if ($customer_info[0]['customer_mobile']) { ?>
                                                                    
                    <?php
                    echo $customer_info[0]['customer_mobile'];
                }
                ?>
                <br>
                <?php if ($customer_info[0]['customer_email']) { ?>
                                                                                  
                    <?php echo $customer_info[0]['customer_email']; ?>
                <?php } ?>
                <br>
                <abbr><b><?php echo display('date') ?>:</b></abbr> <?php echo $quot_main[0]['quotdate'] ?><br>
                <b><?php echo display('expiry') ?>:</b></abbr> <?php echo $quot_main[0]['expire_date'] ?><br>
                <abbr><b><?php echo display('quotation_no') ?>:</b></abbr> <?php echo $quot_main[0]['quot_no'] ?><br>
            </address>
        </div>
    </div>
    <div class="">
    
       <?php 
       $amount = 0;
       if (!empty($quot_product[0]['product_name'])) {
            ?>
            <div class="">
                <table class="table table-striped">
                    <caption class="text-center">  <h2 style="border:1px;background-color: #DDE4EB;color:#000"><?php echo display('item').' '.display('quotation')?> </h2></caption>
                    <thead>
                        

                         <tr>
                            <th  style="text-align: center; "><?php echo display('sl'); ?></th>
                            <th  style="text-align: center;  padding: 5px;"><?php echo display('item') ?></th>
                            <th style="text-align: center;  padding: 5px;"><?php echo display('qty') ?></th>
                            <th  style="text-align: center;  padding: 5px;"><?php echo display('price') ?></th>
                    <?php if ($discount_type == 1) { ?>
                    <th style="text-align: center;  padding: 5px;"><?php echo display('discount_percentage') ?> %</th>
                <?php } elseif ($discount_type == 2) { ?>
                    <th style="text-align: center;  padding: 5px;"><?php echo display('discount') ?> </th>
                <?php } elseif ($discount_type == 3) { ?>
                    <th  style="text-align: center;  padding: 5px;"><?php echo display('fixed_dis') ?> </th>
                <?php } ?>
                            <th style="text-align: center;  padding: 5px;"><?php echo display('total') ?></th>
                                                </tr>
                    </thead>
                    <tbody>
                    <?php
                                                $sl = 1;
                                                $amount = 0;
                                                foreach ($quot_product as $item) {
                                           
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $sl ?></td>
                                                            <td align="left"><?php echo $item['product_name'].' ('.$item['product_model'].')'; ?></td>
                                                            <td align="center"><?php echo $item['used_qty']; ?></td>
                                                            <td align="right">
                                                                <?php
                                                                $rate = $item['rate'];
                                                                echo (($position == 0) ? "$currency $rate" : "$rate $currency");
                                                                ?>
                                                            </td>
                                                             <td align="right">
                                                                <?php
                                                                $itemdiscountper = $item['discount_per'];
                                                                echo (!empty($itemdiscountper)?$itemdiscountper:'');
                                                                ?>
                                                            </td>
                                                            <td align="right">
                                                                <?php
                                                                $amount += $item['total_price'];
                                                                $rate_total = $item['total_price'];
                                                                echo (($position == 0) ? "$currency $rate_total" : "$rate_total $currency");
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $sl++;
                                                       
                                                    
                                                }
                                                ?>
                        
                    </tbody>
                
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;padding:5px;"><b><?php echo display('sub_total'); ?></b></td>
                                                    <td align="right" style="padding:5px;"><b>
                                                            <?php $amount = number_format($amount,2);
                                                            echo (($position == 0) ? "$currency $amount" : "$amount $currency");
                                                            ?>
                                                        </b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;"><b><?php echo display('discount') ?></b></td>
                                                    <td align="right" style="padding:5px;"><b>
                                                            <?php
                                                            $ttldiscountamount = $quot_main[0]['item_total_dicount'];
                                                            echo (($position == 0) ? "$currency $ttldiscountamount" : "$ttldiscountamount $currency");
                                                            ?>
                                                        </b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;"><b><?php echo display('total_tax') ?></b></td>
                                                    <td align="right" style="padding:5px;"><b>
                                                            <?php
                                                            $itemtotaltax = $quot_main[0]['item_total_tax'];
                                                            echo (($position == 0) ? "$currency $itemtotaltax" : "$itemtotaltax $currency");
                                                            ?>
                                                        </b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;"><b><?php echo display('grand_total') ?></b></td>
                                                    <td align="right" style="padding:5px;"><b>
                                                            <?php
                                                            $ttlamount = number_format($quot_main[0]['item_total_amount'], 2);
                                                            echo (($position == 0) ? "$currency $ttlamount" : "$ttlamount $currency");
                                                            ?>
                                                        </b>
                                                        <input type="hidden" name="" id="quotation_id" value="<?php echo $quot_main[0]['quotation_id'] ?>">
                                                    </td>
                                                </tr>
                                            </tfoot>
                </table>
            </div>
            <?php
        }
        ?>
                        <?php
                                 if (!empty($quot_service[0]['service_name'])) {
                                    ?>
                                    <div class="table-responsive m-b-20">
                                        <table class="table table-striped">
                                            <caption class="text-center"> <h2 style="border:1px;background-color: #DDE4EB;color:#000"><?php echo display('service').' '.display('quotation')?> </h2></caption>
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
                                                foreach ($quot_service as $service) {
                                           
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $ssl ?></td>
                                                            <td align="left"><?php echo $service['service_name']; ?></td>
                                                            <td align="center"><?php echo $service['qty']; ?></td>
                                                            <td class="text-right">
                                                                <?php
                                                                $charge = $service['charge'];
                                                                echo (($position == 0) ? "$currency $charge" : "$charge $currency");
                                                                ?>
                                                            </td>
                                                            <td align="right">
                                                                <?php
                                                                $diper = $service['discount'];
                                                                echo (!empty($diper)?$diper:'');
                                                                ?>
                                                            </td>
                                                            <td align="right">
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
                                            </tbody>
                                        <tfoot>
                                            
                                            </tfoot>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;  padding: 5px;"><b><?php echo display('sub_total'); ?></b></td>
                                                    <td align="right" style="padding: 5px;"><b>
                                                            <?php
                                                            $subtotalservice = number_format($subtotalservice,2);
                                                            echo (($position == 0) ? "$currency $subtotalservice" : "$subtotalservice $currency");
                                                            ?>
                                                        </b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;  padding: 5px;"><b><?php echo display('discount') ?></b></td>
                                                    <td align="right" style="padding: 5px;"><b>
                                                            <?php
                                                            $servicediscount = $quot_main[0]['service_total_discount'];
                                                            echo (($position == 0) ? "$currency $servicediscount" : "$servicediscount $currency");
                                                            ?>
                                                        </b></td>
                                                </tr>
                                                  <tr>
                                                    <td colspan="5" style="text-align: right;  padding: 5px;"><b><?php echo display('tax') ?></b></td>
                                                    <td align="right" style="padding: 5px;"><b>
                                                            <?php
                                                            $servicetotaltax = $quot_main[0]['service_total_tax'];
                                                            echo (($position == 0) ? "$currency $servicetotaltax" : "$servicetotaltax $currency");
                                                            ?>
                                                        </b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right;  padding: 5px;"><b><?php echo display('grand_total') ?></b></td>
                                                    <td align="right" style="padding: 5px;"><b>
                                                            <?php
                                                            $servicetotalamount = number_format($quot_main[0]['service_total_amount'], 2);
                                                            echo (($position == 0) ? "$currency $servicetotalamount" : "$servicetotalamount $currency");
                                                            ?>
                                                        </b>
                                                    
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                <?php } ?>
            <div class="row">
                                    
                                    <div class="table-responsive m-b-20">
                                    <table class="table table-stripped">
                                      
                                        <tbody>
                                            <tr>
                                               
                                                <th colspan="2" style="text-align: right;  padding: 5px;"><?php echo display('net_total') ?> :</th>
                                                <th style="text-align: right;  padding-right: 45px;"><?php $nettotal = (!empty($quot_main[0]['item_total_amount'])?$quot_main[0]['item_total_amount']:0)+(!empty($quot_main[0]['service_total_amount'])?$quot_main[0]['service_total_amount']:0);
                                                $ntotal =  number_format($nettotal,2);
                                                  echo (($position == 0) ? "$currency $ntotal" : "$ntotal $currency");
                                                ?> </th>
                                            </tr>

                                        </tbody>
                                       
                                    </table>
                                </div>
                                  
                                   
                                    <div class="row" style="margin-top: 50px">
                                        <div style="width: 50%; margin-left: 30px; ">
                                            <strong><?php echo display('quot_description') ?> : </strong> <?php echo $quot_main[0]['quot_description'] ?>
                                        </div>
                                    </div>
                                  
                                </div>
    </div>

</div>
