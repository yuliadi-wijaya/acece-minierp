
<!-- Admin Home Start -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-world"></i>

        </div>
        <div class="header-title">
            <h1><?php echo display('dashboard') ?></h1>
            <small><?php echo display('home') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li class="active"><?php echo display('dashboard') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Alert Message -->
        <?php 
if(isset($_POST['btnSearch']))
{
   $postdate = $_POST['alldata'];
}
$searchdate =(!empty($postdate)?$postdate:date('F Y'));

?>
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
        <!-- First Counter -->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                 <div class="small-box bg-green whitecolor">
            <div class="inner">
              <h4><span class="count-number"><?php echo html_escape($total_customer) ?></span></h4>
              <p><?php echo display('total_customer')?></p>
            </div>
            <div class="icon">
             <i class="fa fa-users"></i>
            </div>
            <a href="<?php echo base_url('Ccustomer/manage_customer') ?>" class="small-box-footer"><?php echo display('total_customer')?></a>
          </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="small-box bg-pase whitecolor">
            <div class="inner">
              <h4><span class="count-number"><?php echo html_escape($total_product) ?></span></h4>

              <p><?php echo display('total_product')?></p>
            </div>
            <div class="icon">
             <i class="fa fa-shopping-bag"></i>
            </div>
            <a href="<?php echo base_url('Cproduct/manage_product') ?>" class="small-box-footer"><?php echo display('total_product')?></a>
          </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
         <div class="small-box bg-bringal whitecolor">
            <div class="inner">
              <h4><span class="count-number"><?php echo html_escape($total_suppliers)?></span></h4>

              <p><?php echo display('total_supplier')?></p>
            </div>
            <div class="icon">
             <i class="fa fa-user"></i>
            </div>
            <a href="<?php echo base_url('Csupplier/manage_supplier') ?>" class="small-box-footer"><?php echo display('total_supplier')?> </a>
          </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="small-box bg-darkgreen whitecolor">
            <div class="inner">
              <h4><span class="count-number"><?php echo html_escape($total_sales) ?></span> </h4>

              <p><?php echo display('total_invoice')?></p>
            </div>
            <div class="icon">
             <i class="fa fa-money"></i>
            </div>
            <a href="<?php echo base_url('Cinvoice/manage_invoice') ?>" class="small-box-footer"><?php echo display('total_invoice')?> </a>
          </div>
            </div>
        </div>
        <hr>
        <?php if ($this->session->userdata('user_type') == '1') { ?>
          
            <div class="row">
                <!-- This month progress -->
                <div class="col-sm-12 col-md-7">
                    <div class="panel panel-bd">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4 class="best-sale-title"> <?php echo display('best_sale_product') ?></h4>
                                <a href="<?php echo base_url(); ?>Admin_dashboard/see_all_best_sales" class="btn btn-success text-white best-sale-seeall">See All</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <canvas id="lineChart" height="160"></canvas>
                        </div>
                    </div>
                </div>
                 <div class="col-sm-12 col-md-5">
                    <div class="panel panel-bd">
                        <div class="panel-heading">
                            <div class="panel-title">
                                 
                               <?php echo form_open_multipart('','name="form1" id="form1"')?>   
                                  <div class="form-group row">
                                    <div class="col-sm-8 marginpadding-right0">
                                  <input type="text" class="form-control " value="<?php echo $searchdate;?>" name="alldata" id="alldata" ></div>
                                  <div class="col-sm-2 marginpaddingleft0">
                                  <button type="submit" name="btnSearch" class="btn filterbutton"><i class="fa fa-search"></i> <?php echo display('filter')?></button>
                                </div>
                                </div>
                                <?php echo form_close();?>
                            </div>
                            <div class="panel-body">
                             <div id="chartContainer" class="piechartcontainer"></div>
                            </div>
                        </div>
                        </div>
                        </div>

                    <div class="col-md-8">
                    <div class="panel panel-bd">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4 class="charttitle"></h4>
                            </div>
                            <div class="panel-body">
                              <canvas id="yearlyreport" width="600" height="350"></canvas>
                            </div>
                        </div>
                        </div>
                        </div>
                <!-- Total Report -->
                <div class="col-md-4">
                    <div class="panel panel-bd lobidisable">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('todays_overview') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="message_inner">
                                <div class="message_widgets">

                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th><?php echo display('todays_report') ?></th>
                                            <th><?php echo display('money') ?></th>
                                        </tr>
                                        <tr>
                                            <th><?php echo display('total_sales') ?></th>
                                            <td><?php echo html_escape((($position == 0) ? "$currency $sales_amount" : "$sales_amount $currency")) ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo display('total_purchase') ?></th>
                                            <td><?php echo html_escape((($position == 0) ? "$currency $todays_total_purchase" : "$todays_total_purchase $currency")) ?></td>
                                        </tr>

                                    </table>

                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <th><?php echo display('last_sales') ?></th>
                                            <th><?php echo display('money') ?></th>
                                        </tr>
                                        <?php
                                        if ($todays_sale_product) {
                                            ?>
                                            {todays_sale_product}
                                            <tr>
                                                <th>{product_name}</th>
                                                <td><?php echo (($position == 0) ? "$currency {price}" : "{price} $currency") ?></td>
                                            </tr>
                                            {/todays_sale_product}
                                        <?php } ?>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                   <!-- This today transaction progress -->
                <div class="col-sm-12 col-md-12">
                    <div class="panel panel-bd">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4 class="charttitle"> <?php echo display('todays_sales_report') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive todayssaletitle">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('sl') ?></th>
                                            <th><?php echo display('customer_name') ?></th>
                                            <th><?php echo display('invoice_no') ?></th>
                                            <th><?php echo display('total_amount') ?></th>
                                            <th><?php echo display('paid_ammount') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                         $ttl_amount = $ttl_paid = $ttl_due = $ttl_discout = $ttl_receipt = 0;
                                        $todays = date('Y-m-d');
                                        if ($todays_sales_report) {
                                            $sl = 0;
                     foreach ($todays_sales_report as $single) {
                     

                                                $sl++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $sl; ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>Ccustomer/customer_ledger_report">
                                                            <?php echo html_escape($single->customer_name); ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url() . 'Cinvoice/invoice_inserted_data/'; ?><?php echo html_escape($single->invoice_id); ?>">
                                                            <?php echo html_escape($single->invoice); ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php
                                                        $ttl_amount += $single->total_amount; 
                                                        echo html_escape(number_format($single->total_amount, '2','.',',')); 
                                                        ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php
                                                        $ttl_paid += $single->paid_amount;
                                                        echo html_escape(number_format($single->paid_amount, '2', '.', ',')); ?>
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
                                        
                                        <tr>
                                            <td colspan="3" align="right">&nbsp;<b><?php echo display('total') ?>:</b></td>
                                            <td class="text-right">
                                                <?php
                                                $ttl_amount_float = html_escape(number_format($ttl_amount, '2', '.',','));
                                                echo (($position == 0) ? "$currency $ttl_amount_float" : "$ttl_amount_float $currency"); ?>
                                            </td>
                                            <td class="text-right">
                                                <?php
                                                $ttl_paid_float = html_escape(number_format($ttl_paid, '2', '.',','));
                                                echo (($position == 0) ? "$currency $ttl_paid_float" : "$ttl_paid_float $currency"); ?>
                                            </td>
                                           
                                          
                                           
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>

                        
                    </div>
                
                </div>
                
      
               
             
            </div>
        <?php } ?>
   <input type="hidden" id="currency" value="<?php echo  html_escape($currency)?>" name="">
        <input type="hidden" id="totalsalep" value="<?php echo html_escape($this->Reports->total_sales_amount($searchdate))?>" name="">
      <input type="hidden" id="totalplurchasep" value="<?php
     echo html_escape($this->Reports->total_purchase_amount($searchdate))?>" name="">
      <input type="hidden" id="totalexpensep" value="<?php
     echo html_escape($this->Reports->total_expense_amount($searchdate))?>" name="">
     <input type="hidden" id="totalemployeesalaryp" value="<?php
     echo html_escape($this->Reports->total_employee_salary($searchdate))?>" name="">

      <input type="hidden" id="totalservicep" value="<?php
     echo html_escape($this->Reports->total_service_amount($searchdate))?>" name="">

      <input type="hidden" id="month" value="<?php echo html_escape($month);?>" name="">
      <input type="hidden" id="tlvmonthsale" value="<?php echo html_escape($tlvmonthsale);?>" name="">
      <input type="hidden" id="tlvmonthpurchase" value="<?php echo html_escape($tlvmonthpurchase);?>" name=""> 
      <input type="hidden" id="salspurhcaselabel"  value="<?php echo display("sales_and_purchase_report_summary");?>- <?php echo  date("Y")?>" name="">     


<input type="hidden" id="bestsalelabel" value='<?php echo html_escape($chart_label);?>' name=""> 
<input type="hidden" id="bestsaledata" value='<?php echo html_escape($chart_data);?>' name=""> 

<input type="hidden" value='<?php $seperatedData = explode(',', $chart_data); echo html_escape(($seperatedData[0] + 10));?>' name="" id="bestsalemax">     
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
<!-- Admin Home end -->

<!-- ChartJs JavaScript -->

<script src="<?php echo base_url() ?>assets/js/Chart.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/js/canvasjs.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/js/dashboard.js" type="text/javascript"></script>




