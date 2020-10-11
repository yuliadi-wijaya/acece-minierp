<?php
$CI = & get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice_details') ?></h1>
            <small><?php echo display('invoice_details') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_details') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
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
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <div class="panel-body">
                         
                       
                                    <table width="100%">
                                        <tr><td width="40%"><img src="<?php
                                    if (isset($Web_settings[0]['logo'])) {
                                        echo $Web_settings[0]['logo'];
                                    }
                                    ?>" class="" alt=""></td><td class="text-center" width="80%"><h1 class="text-left"><?php echo display('invoice')?></h1></td></tr>
                                    </table>
                            
                             <div class="row">
                                 <div class="col-sm-2"></div><div class="col-sm-10"><b><?php echo display('invoice_no')?>:</b>{invoice_no} | <b><?php echo display('date')?> :</b> {final_date} </div>
                             <div class="col-sm-12">
                                 
                                        <strong> <?php echo display('name')?> </strong> : {customer_name} <br>
                                <strong><?php echo 'Cell No' ?> :</strong>
                                        <?php if ($customer_mobile) { ?>
                                            {customer_mobile}
                                        <?php }
                                            ?> <br>
                                    <strong> <?php echo display('address')?> </strong> :
                                        <?php if ($customer_address) { ?>
                                            {customer_address}
                                        <?php } ?>
                                       
                                    
                             </div>
                         </div>
                            <div class="table-responsive m-b-20">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo display('product_name') ?></th>
                                            <th class="text-right"><?php echo display('quantity') ?></th>
                                            <?php if($is_discount > 0){ ?>
                                            <?php if ($discount_type == 1) { ?>
                                                <th class="text-right"><?php echo display('discount_percentage') ?> %</th>
                                            <?php } elseif ($discount_type == 2) { ?>
                                                <th class="text-right"><?php echo display('discount') ?> </th>
                                            <?php } elseif ($discount_type == 3) { ?>
                                                <th class="text-right"><?php echo display('fixed_dis') ?> </th>
                                            <?php } ?>
                                           <?php }else{ ?>
                                       <th class="text-right"><?php echo ''; ?> </th>
                                          <?php }?>
                                            <th class="text-right"><?php echo display('rate') ?></th>
                                            <th class="text-right"><?php echo display('ammount') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {invoice_all_data}
                                        <tr>
                                            <td class="text-center">{sl}</td>
                                            <td class="text-center"><div><strong>{product_name} - ({product_model})</strong></div></td>
                                            <td align="right">{quantity}</td>

                                            <?php if ($discount_type == 1) { ?>
                                                <td align="right">{discount_per}</td>
                                            <?php } else { ?>
                                                <td align="right"><?php echo (($position == 0) ? "$currency {discount_per}" : "{discount_per} $currency") ?></td>
                                            <?php } ?>

                                            <td align="right"><?php echo (($position == 0) ? "$currency {rate}" : "{rate} $currency") ?></td>
                                            <td align="right"><?php echo (($position == 0) ? "$currency {total_price}" : "{total_price} $currency") ?></td>
                                        </tr>
                                        {/invoice_all_data}
                                        <tr>
                                            <td class="text-right" colspan="2"><b><?php echo display('grand_total') ?>:</b></td>
                                            <td align="right" ><b>{subTotal_quantity}</b></td>
                                            <td></td>
                                            <td></td>
                                            <td align="right" ><b><?php echo (($position == 0) ? "$currency {subTotal_ammount}" : "{subTotal_ammount} $currency") ?></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">

                                <div class="col-sm-12">

                            

                                    <table class="table">
                                        <?php
                                        if ($invoice_all_data[0]['total_discount'] != 0) {
                                            ?>
                                            <tr><td colspan="7" class="border-bottom-top"></td>
                                                <td class="text-right border-bottom-top"> <strong><?php echo display('total_discount') ?> : </strong> </td>
                                                <td class="text-right border-bottom-top"> <strong><?php echo html_escape((($position == 0) ? "$currency {total_discount}" : "{total_discount} $currency")) ?> </strong> </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($invoice_all_data[0]['total_tax'] != 0) {
                                            ?>
                                            <tr><td colspan="7"></td>
                                                <td class="text-right border-bottom-top"><strong><?php echo display('tax') ?> : </strong></td>
                                                <td  class="text-right border-bottom-top"><strong><?php echo html_escape((($position == 0) ? "$currency {total_tax}" : "{total_tax} $currency")) ?></strong> </td>
                                            </tr>
                                        <?php } ?>
                                         <?php if ($invoice_all_data[0]['shipping_cost'] != 0) {
                                            ?>
                                            <tr><td colspan="7" class="border-bottom-top"></td>
                                                <td class="text-right border-bottom-top"><strong><?php echo display('shipping_cost') ?> :</strong> </td>
                                                <td class="text-right border-bottom-top"><strong><?php echo html_escape((($position == 0) ? "$currency {shipping_cost}" : "{shipping_cost} $currency")) ?></strong> </td>
                                            </tr>
                                        <?php } ?>
                                         <?php if ($invoice_all_data[0]['previous'] != 0) {
                                            ?>
                                        <tr><td colspan="7" class="border-bottom-top"></td>
                                            <td class="text-right grand_total border-bottom-top"><strong><?php echo display('previous'); ?> :</strong></td>
                                            <td class="text-right grand_total border-bottom-top"><strong><?php echo html_escape((($position == 0) ? "$currency {previous}" : "{previous} $currency")) ?></strong></td>
                                        </tr>
                                         <?php } ?>
                                        <tr><td colspan="7" class="border-bottom-top"><strong><?php echo display('in_word')?>  {am_inword}</strong></td>
                                            <td class="text-right grand_total border-bottom-top"><strong><?php echo display('grand_total') ?> :</strong></td>
                                            <td class="text-right grand_total border-bottom-top" ><strong><?php echo html_escape((($position == 0) ? "$currency {total_amount}" : "{total_amount} $currency")) ?></strong></td>
                                        </tr>
                                        <tr><td colspan="7 border-bottom-top" ></td>
                                            <td class="text-right grand_total border-bottom-top" ><strong><?php echo display('paid_ammount') ?> :</strong> </td>
                                            <td class="text-right grand_total border-bottom-top" ><strong><?php echo html_escape((($position == 0) ? "$currency {paid_amount}" : "{paid_amount} $currency")) ?></strong></td>
                                        </tr>				 
                                        <?php
                                        if ($invoice_all_data[0]['due_amount'] != 0) {
                                            ?>
                                            <tr><td colspan="7" class="border-bottom-top"></td>
                                                <td class="text-right grand_total border-bottom-top"><strong><?php echo display('due') ?> :</strong> </td>
                                                <td  class="text-right grand_total border-bottom-top"><strong><?php echo html_escape((($position == 0) ? "$currency {due_amount}" : "{due_amount} $currency")) ?></strong></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <b>
                                    </table>

                                   

                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-sm-12">
                                <b> Payemnt Method : Cash On Delivery  </b> 
                                </div>
                                <div class="col-sm-12">
                                    <b>Terms &amp; Conditions</b><br>
Please check for any missing, wrong or damaged product before you pay to the
delivery man. Complaints later regarding this will not be considered.
<b>Thank You</b> for purchasing form <b> {company_info}{company_name} {/company_info}</b>. We are so happy to have a
customer like you. Please give your, Reviews &amp; picture to our page &amp; group.
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-sm-4">
                                
                                </div>
                               <div class="col-sm-2"></div>
                                     <div class="col-sm-6"> <div class="inv-footer-left">
                                        <?php echo 'Customer Signature' ?>
                                    </div></div>
                            </div>
                               <div class="row">

                                {company_info}
                                <div class="col-sm-12 text-center">
                                    
                                  
                                    <address>
                                        {address}<br>
                                        <abbr><b><?php echo display('phone') ?>:</b></abbr> {mobile}<br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        {email}<br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        {website},
                                        <b>Fb:</b> facebook/perfectobd1<br>
                           https://www.facebook.com/groups/1307369709371803/
                                    </address>
                                </div>
                                {/company_info}
                 {tax_regno}
                            </div> 
                        </div>
                    </div>

                    <div class="panel-footer text-left">
                        <a  class="btn btn-danger" href="<?php echo base_url('Cinvoice'); ?>"><?php echo display('cancel') ?></a>
                        <button  class="btn btn-info" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button>

                    </div>
                </div>
            </div>
        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->




