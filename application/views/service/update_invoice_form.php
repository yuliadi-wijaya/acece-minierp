
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/service.js.php" ></script>
<!-- service Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/service.js" type="text/javascript"></script>

<!-- Add New Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('service') ?></h1>
            <small><?php echo $title; ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('service') ?></a></li>
                <li class="active"><?php echo $title; ?></li>
            </ol>
        </div>
    </section>

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
      


        <!--Add Invoice -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo $title; ?></h4>
                        </div>
                    </div>
                    <?php echo form_open_multipart('Cservice/update_service_invoice', array('class' => 'form-vertical', 'id' => '', 'name' => '')) ?>
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-8" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="customer_name" class="col-sm-3 col-form-label"><?php
                                        echo display('customer_name').'/'.display('phone');
                                        ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                        <input type="text" size="100"  name="customer_name" class=" form-control" placeholder='<?php echo display('customer_name').'/'.display('phone') ?>' id="customer_name" tabindex="1" onkeyup="customer_autocomplete()" value="{customer_name}"/>

                                        <input id="autocomplete_customer_id" class="customer_hidden_value abc" type="hidden" name="customer_id" value="{customer_id}">
                                        <input type="hidden" name="invoice_id" value="{invoice_id}">
                                    </div>
                                     <?php if($this->permission1->method('add_customer','create')->access()){ ?>
                                   
                                <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group row">
                                     <label for="employee" class="col-sm-4 col-form-label"><?php
                                        echo display('employee');
                                        ?> <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <select name="employee_id[]" class="form-control" multiple="multiple">
                                                <option value=""> select One</option>
                                                <?php
                                                 $emloyee = explode(',', $employees);
                                                 foreach($employee_list as $employee){
                                                    foreach($emloyee as $emp){?>  
                                                <option value="<?php echo $employee['id']?>" <?php if($employee['id']==$emp){echo 'selected';}?>><?php echo $employee['first_name'].' '.$employee['last_name']?></option>
                                              <?php }} ?>

                                            </select>
                                        </div>
                                </div>
                            </div>

                        
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label"><?php echo display('hanging_over') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <?php
                               
                                        $date = date('Y-m-d');
                                        ?>
                                        <input class="datepicker form-control" type="text" size="50" name="invoice_date" id="date" required value="{date}" tabindex="6" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field" ><?php echo display('service_name') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center invoice_fields"><?php echo display('charge') ?> <i class="text-danger">*</i></th>

                                        <?php if ($discount_type == 1) { ?>
                                            <th class="text-center"><?php echo display('discount_percentage') ?> %</th>
                                        <?php } elseif ($discount_type == 2) { ?>
                                            <th class="text-center"><?php echo display('discount') ?> </th>
                                        <?php } elseif ($discount_type == 3) { ?>
                                            <th class="text-center"><?php echo display('fixed_dis') ?> </th>
                                        <?php } ?>

                                        <th class="text-center"><?php echo display('total') ?> 
                                        </th>
                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <?php $sl=1;
                                    foreach($invoice_detail as $details){?>
                                    <tr>
                                        <td class="product_field">
                                            <input type="text" name="service_name" onkeypress="invoice_serviceList(<?php echo $sl;?>);" class="form-control serviceSelection" placeholder='<?php echo display('service_name') ?>' required="" id="service_name" tabindex="7" value="<?php echo $details['service_name']?>">

                                            <input type="hidden" class="autocomplete_hidden_value service_id_<?php echo $sl;?>" name="service_id[]" id="SchoolHiddenId" value="<?php echo $details['service_id']?>"/>

                                            <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>" />
                                        </td>

                                        <td>
                                            <input type="text" name="product_quantity[]" onkeyup="quantity_calculate(<?php echo $sl;?>);" onchange="quantity_calculate(<?php echo $sl;?>);" class="total_qntt_<?php echo $sl;?> form-control text-right" id="total_qntt_<?php echo $sl;?>" placeholder="0.00" min="0" tabindex="8" required="required" value="<?php echo $details['qty']?>"/>
                                        </td>
                                        <td class="invoice_fields">
                                            <input type="text" name="product_rate[]" id="price_item_<?php echo $sl;?>" class="price_item<?php echo $sl;?> price_item form-control text-right" tabindex="9" required="" onkeyup="quantity_calculate(<?php echo $sl;?>);" onchange="quantity_calculate(<?php echo $sl;?>);" placeholder="0.00" min="0" value="<?php echo $details['charge']?>"/>
                                        </td>
                                        <!-- Discount -->
                                        <td>
                                            <input type="text" name="discount[]" onkeyup="quantity_calculate(<?php echo $sl;?>);"  onchange="quantity_calculate(<?php echo $sl;?>);" id="discount_<?php echo $sl;?>" class="form-control text-right" min="0" tabindex="10" placeholder="0.00" value="<?php echo $details['discount']?>"/>
                                            <input type="hidden" value="" name="discount_type" id="discount_type_<?php echo $sl;?>">
                                        </td>


                                        <td class="invoice_fields">
                                            <input class="total_price form-control text-right" type="text" name="total_price[]" id="total_price_<?php echo $sl;?>" value="<?php echo $details['total']?>" readonly="readonly" />
                                        </td>

                                        <td>
                                            <!-- Tax calculate start-->
                                      <?php $x=0;
                                     foreach($taxes as $taxfldt){?>
                                            <input id="total_tax<?php echo $x;?>_<?php echo $sl;?>" class="total_tax<?php echo $x;?>_<?php echo $sl;?>" type="hidden">
                                            <input id="all_tax<?php echo $x;?>_<?php echo $sl;?>" class="total_tax<?php echo $x;?>" type="hidden" name="tax[]">
                                           
                                            <!-- Tax calculate end-->

                                            <!-- Discount calculate start-->
                                           
                                            <?php $x++;} ?>
                                            <!-- Tax calculate end-->

                                            <!-- Discount calculate start-->
                                            <input type="hidden" id="total_discount_<?php echo $sl;?>" class="" />
                                            <input type="hidden" id="all_discount_<?php echo $sl;?>" class="total_discount" name="discount_amount[]" />
                                           
                                            <!-- Discount calculate end -->

                                            <button  class="btn btn-danger" type="button" value="<?php echo display('delete') ?>" onclick="deleteRow(this)" tabindex="11"><i class="fa fa-close"></i></button>
                                        </td>
                                    </tr>
                                <?php $sl++;}?>
                                </tbody>
                                <tfoot>

                                          <tr><td colspan="3" rowspan="2">
                                <center><label  for="details" class="text-center col-form-label"><?php echo display('invoice_details') ?></label></center>
                                <textarea name="inva_details" class="form-control" placeholder="<?php echo display('invoice_details') ?>">{details}</textarea>
                                </td>
                                <td class="text-right" colspan="1"><b><?php echo display('service_discount') ?>:</b></td>
                                <td class="text-right">
                                    <input type="text" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);" id="invoice_discount" class="form-control text-right" name="invoice_discount" placeholder="0.00"  value="{invoice_discount}" />
                                        <input type="hidden" id="txfieldnum">
                                </td>
                                <td><input type="button" id="add_invoice_item" class="btn btn-info" name="add-invoice-item"  onClick="addInputField('addinvoiceItem');" value="<?php echo display('add') ?>" /></td>
                                </tr>

                     
                                <tr>
                                    
                                    <td class="text-right" colspan="1"><b><?php echo display('total_discount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="total_discount_ammount" class="form-control text-right" name="total_discount" value="{total_discount}" readonly="readonly" />
                                    </td>
                                      <td></td>
                                </tr>                     
                                    <?php $x=0;
                                     foreach($taxes as $taxfldt){?>
                                    <tr class="hideableRow hiddenRow">
                                       
                                <td class="text-right" colspan="4"><b><?php echo $taxfldt['tax_name'] ?></b></td>
                                <td class="text-right">
                                    <input id="total_tax_ammount<?php echo $x;?>" tabindex="-1" class="form-control text-right valid totalTax" name="total_tax<?php echo $x;?>" value="<?php $txval ='tax'.$x;
                                     echo $taxvalu[0][$txval]?>" readonly="readonly" aria-invalid="false" type="text">
                                </td>
                               
                               <td></td>
                                 
                                </tr>
                            <?php $x++;}?>
                                 
                    <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right" colspan="1"><b><?php echo display('total_tax') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="total_tax_amount" class="form-control text-right" name="total_tax_amount" value="{total_tax}" readonly="readonly" />
                                    </td>
                                    <td><button type="button" class="toggle btn-warning">
                <i class="fa fa-angle-double-down"></i>
              </button></td>
                                </tr>                

                                 <tr>
                                    <td class="text-right" colspan="4"><b><?php echo display('shipping_cost') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="shipping_cost" class="form-control text-right" name="shipping_cost" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);"  placeholder="0.00"  value="{shipping_cost}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"  class="text-right"><b><?php echo display('grand_total') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="grandTotal" class="form-control text-right" name="grand_total_price" value="{stotal}" readonly="readonly" />
                                    </td>
                                </tr>
                                 <tr>
                                    <td colspan="4"  class="text-right"><b><?php echo display('previous'); ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="previous" class="form-control text-right" name="previous" value="{previous}" readonly="readonly" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"  class="text-right"><b><?php echo display('net_total'); ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="n_total" class="form-control text-right" name="n_total" value="{total_amount}" readonly="readonly" placeholder="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                       

                                        <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>"/>
                                    </td>
                                    <td class="text-right" colspan="3"><b><?php echo display('paid_ammount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="paidAmount" 
                                               onkeyup="invoice_paidamount();" class="form-control text-right" name="paid_amount" placeholder="0.00" tabindex="13" value="{paid_amount}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <input type="button" id="full_paid_tab" class="btn btn-warning" value="<?php echo display('full_paid') ?>" tabindex="14" onClick="full_paid()"/>

                                        <input type="submit" id="add_invoice" class="btn btn-success" name="add-invoice" value="<?php echo display('save_changes') ?>" tabindex="15"/>
                                    </td>

                                    <td class="text-right" colspan="3"><b><?php echo display('due') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="dueAmmount" class="form-control text-right" name="due_amount" value="{due_amount}" readonly="readonly"/>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
