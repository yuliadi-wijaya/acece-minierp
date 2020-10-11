
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/service_quotation.js.php" ></script>
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/productquotation.js" ></script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('quotation') ?></h1>
            <small><?php echo display('add_quotation') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('quotation') ?></a></li>
                <li class="active"><?php echo display('add_quotation') ?></li>
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
        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        ?>


        <!-- New category -->
        <div class="row">
            <div class="col-sm-12">                
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_quotation') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Cquotation/insert_quotation', array('class' => 'form-vertical', 'id' => 'insert_quotation')) ?>
                    <div class="panel-body">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="customer" class="col-sm-4 col-form-label"><?php echo display('customer') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                               
                                    <?php if ($user_type == 3) { ?>
                                        <input type="text" name="cname" value="<?php echo $this->session->userdata('user_name') ?>" class="form-control" readonly>
                                        <input type="hidden" name="customer_id" value="<?php echo $this->session->userdata('user_id') ?>" class="form-control">
                                    <?php } else { ?>
                                        <select name="customer_id" class="form-control" onchange="get_customer_info(this.value)"  data-placeholder="<?php echo display('select_one'); ?>">
                                            <option value=""></option>
                                            <?php
                                            foreach ($customers as $customer) {
                                                ?>
                                                <option value="<?php echo $customer['customer_id'] ?>">
                                                    <?php echo $customer['customer_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="quotation_no" class="col-sm-4 col-form-label"><?php echo display('quotation_no') ?> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="quotation_no" id="quotation_no" class="form-control" placeholder="<?php echo display('quotation_no') ?>" value="<?php echo $quotation_no; ?>" readonly>
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="address" class="col-sm-4 col-form-label"><?php echo display('address') ?> <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input type="text" name="address" class="form-control" value="" id="address" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="qdate" class="col-sm-4 col-form-label"><?php echo display('quotation_date') ?> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="qdate" class="form-control datepicker" id="qdate" value="<?php echo date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="mobile" class="col-sm-4 col-form-label"><?php echo display('mobile') ?> <i class="text-danger"></i></label>
                                <div class="col-sm-8">
                                    <input type="text" name="mobile" class="form-control" value="" id="mobile" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                 <label for="expiry_date" class="col-sm-4 col-form-label"><?php echo display('expiry_date') ?> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="expiry_date" class="form-control datepicker" id="expiry_date" value="<?php echo date('Y-m-d') ?>">
                                </div>
                            </div>


                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="details" class="col-sm-2 col-form-label"><?php echo display('details') ?> <i class="text-danger"></i></label>
                                <div class="col-sm-10">
                                    <textarea  name="details" class="form-control" id="details"></textarea>
                                </div>
                            </div>
                        </div>

                              <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive margin-top10">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field"><?php echo display('item_information') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('item_description')?></th>
                                         <th class="text-center"><?php echo display('serial_no')?></th>
                                        <th class="text-center"><?php echo display('available_qnty') ?></th>
                                        <th class="text-center"><?php echo display('unit') ?></th>
                                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center invoice_fields"><?php echo display('rate') ?> <i class="text-danger">*</i></th>

                                        <?php if ($discount_type == 1) { ?>
                                            <th class="text-center invoice_fields"><?php echo display('discount_percentage') ?> %</th>
                                        <?php } elseif ($discount_type == 2) { ?>
                                            <th class="text-center invoice_fields"><?php echo display('discount') ?> </th>
                                        <?php } elseif ($discount_type == 3) { ?>
                                            <th class="text-center invoice_fields"><?php echo display('fixed_dis') ?> </th>
                                        <?php } ?>

                                        <th class="text-center invoice_fields"><?php echo display('total') ?> 
                                        </th>
                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <tr>
                                        <td class="product_field">
                                            <input type="text" name="product_name" onkeypress="invoice_productList(1);" class="form-control productSelection" placeholder='<?php echo display('product_name') ?>'  id="product_name_1" tabindex="5">

                                            <input type="hidden" class="autocomplete_hidden_value product_id_1" name="product_id[]" id="SchoolHiddenId"/>

                                            <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>" />
                                        </td>
                                          <td>
                                            <input type="text" name="desc[]" class="form-control text-right "  tabindex="6"/>
                                        </td>
                                        <td class="invoice_fields">
                                             <select class="form-control" id="serial_no_1" name="serial_no[]"   tabindex="7">
                                                <option></option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="available_quantity[]" class="form-control text-right available_quantity_1" value="0" readonly="" />
                                        </td>
                                        <td>
                                            <input name="" id="" class="form-control text-right unit_1 valid" value="None" readonly="" aria-invalid="false" type="text">
                                        </td>
                                        <td>
                                            <input type="text" name="product_quantity[]" onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" class="total_qntt_1 form-control text-right" id="total_qntt_1" placeholder="0.00" min="0" tabindex="8"  value="1"/>
                                        </td>
                                        <td>
                                            <input type="text" name="product_rate[]" id="price_item_1" class="price_item1 price_item form-control text-right" tabindex="9"  onkeyup="quantity_calculate(1);" onchange="quantity_calculate(1);" placeholder="0.00" min="0" />
                                             <input type="hidden" name="supplier_price[]" id="supplier_price_1">
                                        </td>
                                        <!-- Discount -->
                                        <td>
                                            <input type="text" name="discount[]" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);" id="discount_1" class="form-control text-right" min="0" tabindex="10" placeholder="0.00"/>
                                            <input type="hidden" value="" name="discount_type" id="discount_type_1">

                                        </td>


                                        <td>
                                            <input class="total_price form-control text-right" type="text" name="total_price[]" id="total_price_1" value="0.00" readonly="readonly" />
                                        </td>

                                        <td>
                                            <!-- Tax calculate start-->
                                            <?php $x=0;
                                     foreach($taxes as $taxfldt){?>
                                            <input id="total_tax<?php echo $x;?>_1" class="total_tax<?php echo $x;?>_1" type="hidden">
                                            <input id="all_tax<?php echo $x;?>_1" class="total_tax<?php echo $x;?>" type="hidden" name="tax[]">
                                           
                                            <!-- Tax calculate end-->

                                            <!-- Discount calculate start-->
                                           
                                            <?php $x++;} ?>
                                            <!-- Tax calculate end-->

                                            <!-- Discount calculate start-->
                                            <input type="hidden" id="total_discount_1" class="" />
                                            <input type="hidden" id="all_discount_1" class="total_discount dppr" name="discount_amount[]" />
                                            <!-- Discount calculate end -->

                                        
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                     <tr>
                                       
                                    <td class="text-right" colspan="8"><b><?php echo display('invoice_discount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" onkeyup="quantity_calculate(1);"  onchange="quantity_calculate(1);" id="invoice_discount" class="form-control text-right total_discount" name="invoice_discount" placeholder="0.00"   tabindex="13"/>
                                        <input type="hidden" id="txfieldnum">
                                    </td>
                                    <td><a  id="add_invoice_item" class="btn btn-info" name="add-invoice-item"  onClick="addInputField('addinvoiceItem');"  tabindex="11"><i class="fa fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="8"><b><?php echo display('total_discount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="total_discount_ammount" class="form-control text-right" name="total_discount" value="0.00" readonly="readonly" />
                                    </td>
                                </tr>
                                    <tr>
                                <td class="text-right" colspan="8"><b><?php echo display('total_tax') ?>:</b></td>
                                <td class="text-right">
                                    
                                    <?php $x=0;
                                     foreach($taxes as $taxfldt){?>
                                  
                                    <input id="total_tax_ammount<?php echo $x;?>" tabindex="-1" class="form-control text-right valid totalTax" name="total_tax<?php echo $x;?>" value="0.00" readonly="readonly" aria-invalid="false" type="hidden">
                                
                            <?php $x++;}?>
                                    <input id="total_tax_amount" tabindex="-1" class="form-control text-right valid" name="total_tax" value="0.00" readonly="readonly" aria-invalid="false" type="text">
                                </td>
                                
                                </tr>
                               
                                <tr>
                                    <td colspan="8"  class="text-right"><b><?php echo display('grand_total') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="grandTotal" class="form-control text-right" name="grand_total_price" value="0.00" readonly="readonly" />
                                    </td>
                                </tr>
                                
                               
                               
                               
                               
                                </tfoot>
                            </table>
                        </div>


                            </div>
                        </div>
                    
                        <hr>
                        <div>
                          <button type="button" class="btn btn-primary"  id="service_quotation_div"><?php echo display('add_service_quotation')?></button>  
                        </div>

                         <div class="row" id="quotation_service">
                            <div class="col-sm-12">
                               
                           <div class="table-responsive margin-top10">
                            <table class="table table-bordered table-hover" id="serviceInvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field"><?php echo display('service_name') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center invoice_fields"><?php echo display('charge') ?> <i class="text-danger">*</i></th>

                                        <?php if ($discount_type == 1) { ?>
                                            <th class="text-center"><?php echo display('discount_percentage') ?> %</th>
                                        <?php } elseif ($discount_type == 2) { ?>
                                            <th class="text-center"><?php echo display('discount') ?> </th>
                                        <?php } elseif ($discount_type == 3) { ?>
                                            <th class="text-center"><?php echo display('fixed_dis') ?> </th>
                                        <?php } ?>

                                        <th class="text-center product_field"><?php echo display('total') ?> 
                                        </th>
                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="addservicedata">
                                    <tr>
                                        <td class="product_field">
                                            <input type="text" name="service_name" onkeypress="invoice_serviceList(1);" class="form-control serviceSelection" placeholder='<?php echo display('service_name') ?>'  id="service_name" tabindex="7">

                                            <input type="hidden" class="autocomplete_hidden_value service_id_1" name="service_id[]"/>

                                            <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>" />
                                        </td>

                                        <td>
                                            <input type="text" name="service_quantity[]" onkeyup="serviceCAlculation(1);" onchange="serviceCAlculation(1);" class="total_service_qty_1 form-control text-right" id="total_service_qty_1" placeholder="0.00" min="0" tabindex="8"/>
                                        </td>
                                        <td>
                                            <input type="text" name="service_rate[]" id="service_rate_1" class="service_rate1 service_rate form-control text-right" tabindex="9" onkeyup="serviceCAlculation(1);" onchange="serviceCAlculation(1);" placeholder="0.00" min="0" />
                                           
                                        </td>
                                        <!-- Discount -->
                                        <td>
                                         <input type="text" name="sdiscount[]" onkeyup="serviceCAlculation(1);" onchange="serviceCAlculation(1);" id="sdiscount_1" class="form-control text-right common_servicediscount" placeholder="0.00" min="0">
                                            <input type='hidden' value='' name='discount_type' id='sdiscount_type_1'>
                                        </td>


                                        <td>
                                            <input class="total_serviceprice form-control text-right" type="text" name="total_service_amount[]" id="total_service_amount_1" value="0.00" readonly="readonly" />
                                        </td>

                                        <td>
                                            <!-- Tax calculate start-->
                                      <?php $x=0;
                                     foreach($taxes as $taxfldt){?>
                                            <input id="total_service_tax<?php echo $x;?>_1" class="total_service_tax<?php echo $x;?>_1" type="hidden">
                                            <input id="all_servicetax<?php echo $x;?>_1" class="total_service_tax<?php echo $x;?>" type="hidden" name="stax[]">
                                           
                                            <!-- Tax calculate end-->

                                            <!-- Discount calculate start-->
                                           
                                            <?php $x++;} ?>
                                            <!-- Tax calculate end-->
    <input type="hidden" id="totalServiceDicount_1" class="totalServiceDicount_1">

<input type="hidden" id="all_service_discount_1" class="totalServiceDicount" name="sdiscount_amount[]">
                                           
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>

                                <tr>
                                <td class="text-right" colspan="4"><b><?php echo display('service_discount') ?>:</b></td>
                                <td class="text-right">
                                    <input type="text" onkeyup="serviceCAlculation(1);"  onchange="serviceCAlculation(1);" id="service_discount" class="form-control text-right" name="service_discount" placeholder="0.00"  />
                                        <input type="hidden" id="sertxfieldnum">
                                </td>
                                <td><button type="button" id="add_service_item" class="btn btn-info" name="add-invoice-item"  onClick="addService('addservicedata');"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                <tr>
                                    
                                    <td class="text-right" colspan="4"><b><?php echo display('totalServiceDicount') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="total_service_discount" class="form-control text-right" name="totalServiceDicount" value="0.00" readonly="readonly" />
                                    </td>
                                      
                                </tr>   
                                
                                    
                                 
                    <tr>
                                    
                                    <td class="text-right" colspan="4"><b><?php echo display('total_service_tax') ?>:</b></td>
                                    <td class="text-right">
                                          <?php $x=0;
                                     foreach($taxes as $taxfldt){?>
                                         <input id="total_service_tax_amount<?php echo $x;?>" tabindex="-1" class="form-control text-right valid totalServiceTax" name="total_service_tax<?php echo $x;?>" value="0.00" readonly="readonly" aria-invalid="false" type="hidden">
                                      <?php $x++;}?> 

                                        <input type="text" id="total_service_tax" class="form-control text-right" name="total_service_tax" value="0.00" readonly="readonly" />
                                    </td>
                                    
                                </tr>                

                                
                                <tr>
                                    <td colspan="4"  class="text-right"><b><?php echo display('grand_total') ?>:</b></td>
                                    <td class="text-right">
                                        <input type="text" id="serviceGrandTotal" class="form-control text-right" name="grand_total_service_amount" value="0.00" readonly="readonly" />
                                        <input type="hidden" id="is_quotation" value="" name="">
                                    </td>
                                </tr>
                                
                           
                                
                                </tfoot>
                            </table>
                        </div>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                        
                                    <input type="submit" id="add-quotation" class="btn btn-success btn-large" name="add-quotation" value="<?php echo display('save') ?>" />
                                   
                            </div>
                        </div>
                    </div>               
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/quotation.js" ></script>



