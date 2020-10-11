<?php
$CI = & get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>
<!--its for pos style css start--> 


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
                            <div class="row print_header">
                                
                                <div class="col-sm-8 company-content">
                                    {company_info}
                                    <img src="<?php
                                    if (isset($Web_settings[0]['invoice_logo'])) {
                                        echo $Web_settings[0]['invoice_logo'];
                                    }
                                    ?>" class="img-bottom-m" alt="" >
                                    <br>
                                    <span class="label label-success-outline m-r-15 p-10" ><?php echo display('billing_from') ?></span>
                                    <address class="margin-top10">
                                        <strong class="company_name_p">{company_name}</strong><br>
                                        {address}<br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr> {mobile}<br>
                                        <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                        {email}<br>
                                        <abbr><b><?php echo display('website') ?>:</b></abbr> 
                                        {website}<br>
                                         {/company_info}
                                         <abbr>{tax_regno}</abbr>
                                    </address>
                                   
                                  

                                </div>
                                
                                 
                                <div class="col-sm-4 text-left invoice-address">
                                    <h2 class="m-t-0"><?php echo display('invoice') ?></h2>
                                    <div><?php echo display('invoice_no') ?>: {invoice_no}</div>
                                    <div class="m-b-15"><?php echo display('billing_date') ?>: {final_date}</div>

                                    <span class="label label-success-outline m-r-15"><?php echo display('billing_to') ?></span>

                                    <address class="customer_name_p">  
                                        <strong  class="c_name" >{customer_name} </strong><br>
                                        <?php if ($customer_address) { ?>
                                            {customer_address}
                                        <?php } ?>
                                        <br>
                                        <abbr><b><?php echo display('mobile') ?>:</b></abbr>
                                        <?php if ($customer_mobile) { ?>
                                            {customer_mobile}
                                        <?php }if ($customer_email) {
                                            ?>
                                            <br>
                                            <abbr><b><?php echo display('email') ?>:</b></abbr> 
                                            {customer_email}
                                        <?php } ?>
                                    </address>
                                </div>
                            </div> 

                               
                                   

                                             <table width="100%" class="table-striped">
                                                <thead>
                                    <tr class="pthead" >
                                        <td><?php echo display('sl'); ?></td>
                                        <td><?php echo display('product_name'); ?></td>
                                         <th  align="center"><?php if($is_unit !=0){ echo display('unit');
                                              }?></th>
                                        <td><?php  if($is_desc !=0){ echo display('item_description');} ?></td>
                                        <td><?php if($is_serial !=0){ echo display('serial_no');} ?></td>
                                        <td align="right"><?php echo display('quantity'); ?></td>
                                        <td align="right"><?php if($is_discount > 0){ echo display('discount');
                                    }else{
                                            echo '';
                                        } ?></td>
                                        <td align="right"><?php echo display('rate'); ?></td>
                                        <td align="right"><?php echo display('ammount'); ?></td>
                                    </tr>
                                </thead>
                               <tbody>
                                    <?php 
                                    $sl =1;
                                    $s_total = 0;
                                    foreach($invoice_all_data as $invoice_data){?>
                                    <tr>
                                        <td align="left"><nobr><?php echo $sl;?></nobr></td>
                                    <td align="left"><nobr><?php echo html_escape($invoice_data['product_name']).'('.html_escape($invoice_data['product_model']).')';?></nobr></td>
                                  <td align="left"><nobr><?php echo html_escape($invoice_data['unit']);?></nobr></td>
                                    <td align="left"><nobr><?php echo html_escape($invoice_data['description']);?></nobr></td>
                                    <td align="left"><nobr><?php echo html_escape($invoice_data['serial_no']);?></nobr></td>
                                    <td align="right"><nobr><?php echo html_escape($invoice_data['quantity']);?></nobr></td>
                                    <td align="right">
                                    <nobr>
                                        <?php 
                                        if(!empty($invoice_data['discount_per'])){
                                            $curicon = $currency;
                                        }else{
                                            $curicon = '';
                                        }
                                    if($position == 0){
                                       echo  $curicon.' '.html_escape($invoice_data['discount_per']);
                                    }else{
                                    echo html_escape($invoice_data['discount_per']).' '.$curicon;
                                    }
                                         ?>
                                    </nobr>
                                    </td>
                                    <td align="right">
                                    <nobr>
                                        <?php 
                                         if($position == 0){
                                       echo  $currency.' '.html_escape($invoice_data['rate']);
                                    }else{
                                    echo html_escape($invoice_data['rate']).' '.$currency;
                                    }
                                         ?>
                                    </nobr>
                                    </td>
                                    <td align="right">
                                    <nobr>
                                        <?php 
                                       if($position == 0){
                                       echo  $currency.' '.html_escape($invoice_data['total_price']);
                                    }else{
                                    echo html_escape($invoice_data['total_price']).' '.$currency;
                                    }
                                    $s_total += $invoice_data['total_price'];
                                         ?>
                                    </nobr>
                                    </td>
                                    </tr>
                                    <?php $sl++; }?>
                                </tbody>
                          <tfoot>
                                    <tr>
                                        <td colspan="9" class="minpos-bordertop"><nobr></nobr></td>
                                    </tr>
                                    <tr>
                                        <td colspan="9" class="minpos-bordertop"><nobr></nobr></td>
                                    </tr>
                                     <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('total') ?></b></td>
                                    <td align="right">
                                    <b>
                                        <?php if($position == 0){
                                       echo  $currency.' '.html_escape(number_format($s_total, 2, '.', ','));
                                    }else{
                                    echo html_escape(number_format($s_total, 2, '.', ',')).' '.$currency;
                                    } ?>
                                    </b>
                                    </td>
                                    </tr>
                                    <?php if($total_tax > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('tax') ?></b></td>
                                    <td align="right">
                                    <b>
                                        <?php echo html_escape((($position == 0) ? "$currency {total_tax}" : "{total_tax} $currency")) ?>
                                    </b>
                                    </td>
                                    </tr>
                                    <?php }?>
                                     <?php if($invoice_discount > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('invoice_discount'); ?></b></td>
                                    <td align="right"><b>
                                        <?php echo html_escape((($position == 0) ? "$currency {invoice_discount}" : "{invoice_discount} $currency")) ?>
                                    </b></td>
                                    </tr>
                                    <?php }?>
                                    <?php if($total_discount > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('total_discount') ?></b></td>
                                    <td align="right">
                                    <b>
                                        <?php echo html_escape((($position == 0) ? "$currency {total_discount}" : "{total_discount} $currency")) ?>
                                    </b></td>
                                    </tr>
                                      <?php }?>
                                       <?php if($shipping_cost > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('shipping_cost') ?></b></td>
                                    <td align="right"><b>
                                        
                                            <?php echo html_escape((($position == 0) ? "$currency {shipping_cost}" : "{shipping_cost} $currency")) ?>
                                        </b></td>
                                    </tr>
                                    <?php }?>
                                       <?php if($previous > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('previous') ?></b></td>
                                    <td align="right"><b>
                                        
                                            <?php echo html_escape((($position == 0) ? "$currency {previous}" : "{prevous_due} $currency")) ?>
                                        </b></td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                        <td colspan="8" class="minpos-bordertop"><nobr></nobr></td>
                                    </tr>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td colspan="6"><nobr><span align="right"><b><?php echo display('in_word').' : ' ?></b>{am_inword}</span> <?php echo display('taka_only')?></td><td align="right"><strong><?php echo display('grand_total')?></strong></nobr></td>
                                    <td align="right"><nobr>
                                        <strong>
                                            <?php echo html_escape((($position == 0) ? "$currency {total_amount}" : "{total_amount} $currency"))
                                             ?>
                                        </strong></nobr></td>
                                    </tr>

                                    <tr>
                                        <td colspan="9" class="minpos-bordertop"><nobr></nobr></td>
                                    </tr>
                                     <?php if($paid_amount > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b>
                                        <?php echo display('paid_ammount') ?>
                                    </b></td>
                                    <td align="right"><b>
                                        <?php echo html_escape((($position == 0) ? "$currency {paid_amount}" : "{paid_amount} $currency")) ?>
                                    </b></td>
                                    </tr>
                                     <?php }?>
                                    <?php if($due_amount > 0){?>
                                    <tr>
                                        <td align="left"><nobr></nobr></td>
                                    <td align="right" colspan="7"><b><?php echo display('due') ?></b></td>
                                    <td align="right"><b>
                                        <?php echo html_escape((($position == 0) ? "$currency {due_amount}" : "{due_amount} $currency")) ?>
                                    </b></td>
                                    </tr>
                                <?php }?>
                                    <tr>
                                        <td colspan="8" class="minpos-bordertop"><nobr></nobr></td>
                                    </tr>
                                </tfoot>
                                </table>
                               <table class="table">
                                
                                <tr>
                                    <td>{invoice_details}</td><td></td><td></td><td></td>
                                </tr>
                                <tr>
                                    
                                    <td> <b><?php echo display('sold_by')?> </b>: {users_name}<br>{company_info}
                                        Website: <a href="{website}">{website}</a>
                                   {/company_info}
                                       </td>
                                       <td class="text-right" colspan="2"> <div class="sig_div">
                                        <?php echo display('signature') ?>
                                         
                                    </div></td>
                                   
                                </tr>
                               
                                </table>
                                </div>


                        </div>
                    </div>
                        <div class="panel-footer text-left">
                        <a  class="btn btn-danger" href="<?php echo base_url('Cinvoice'); ?>"><?php echo display('cancel') ?></a>
                        <a  class="btn btn-info" href="#" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></a>
                   
                    </div>

  </div>                     
</div> <!-- /.content-wrapper -->
</div>
</section>
</div>