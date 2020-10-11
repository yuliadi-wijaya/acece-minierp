

<!-- Supplier Ledger Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('supplier_ledger') ?></h1>
            <small><?php echo display('manage_supplier_ledger') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('supplier') ?></a></li>
                <li class="active"><?php echo display('supplier_ledger') ?></li>
            </ol>
        </div>
    </section>

    <!-- Supplier information -->
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
             
                    <a href="<?php echo base_url('Csupplier') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_supplier') ?> </a>

                    <a href="<?php echo base_url('Csupplier/manage_supplier') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('manage_supplier') ?> </a>

                    <a href="<?php echo base_url('Csupplier/supplier_sales_details_all') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('supplier_sales_details') ?> </a>

                
            </div>
        </div>



        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body"> 
                        <?php echo form_open('Csupplier/supplier_ledger', array('class' => '', 'id' => 'validate')) ?>
                        <?php $today = date('Y-m-d'); ?>
                       <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('supplier') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select name="supplier_id"  class="form-control" required="">
                                    <option value=""></option>
                                    <?php if ($supplier) { ?>
                                        {supplier}
                                        <option value="{supplier_id}">{supplier_name} 
                                        </option>
                                        {/supplier}
                                    <?php } ?>
                                </select>
                            </div>
                            </div>
                            </div> 
                            <div class="col-sm-5">
                        <div class="form-group row">
                                <label for="from_date " class="col-sm-2 col-form-label"> <?php echo display('from') ?></label>
                                <div class="col-sm-4">
                                     <input type="text" name="from_date"  value="<?php echo $today; ?>" class="datepicker form-control" id="from_date"/>
                                </div>
                                 <label for="to_date" class="col-sm-2 col-form-label"> <?php echo display('to') ?></label>
                                <div class="col-sm-4">
                                   <input type="text" name="to_date" value="<?php echo $today; ?>" class="datepicker form-control" id="to_date"/>
                                </div>
                          
                        </div>
                    </div>

                       <div class="col-sm-3">
                                <button type="submit" class="btn btn-success "><i class="fa fa-search-plus" aria-hidden="true"></i> <?php echo display('search') ?></button>
                                <button type="button" class="btn btn-warning"  onclick="printDiv('printableArea')"><?php echo display('print') ?></button>
                        
                        
                    </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier ledger -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('supplier_ledger') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="printableArea">

                            <?php if ($supplier_name) { ?>
                                <div class="text-center">
                                    <h3> {supplier_name} </h3>
                                    <h4><?php echo display('address') ?> : {address} </h4>
                                    <h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                                </div>
                            <?php } ?>

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                            <th class="text-center"><?php echo display('date') ?></th>
                            <th class="text-center"><?php echo display('description') ?></th>
                            <th class="text-center"><?php echo display('voucher_no') ?></th>
                            <th class="text-right"><?php echo display('debit') ?></th>
                            <th class="text-right"><?php echo display('credit') ?></th>
                            <th class="text-right"><?php echo display('balance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($ledgers) {
                                            $sl = 0;
                                            $debit = $credit = $balance = 0;
                                            foreach ($ledgers as $ledger) {
                                                $sl++;
                                                ?>
                                                <tr>
                            <td class="text-center"><?php echo $ledger['VDate']; ?></td>
                            <td><?php echo $ledger['Narration']; ?></td>
                            <td><?php echo $ledger['VNo']; ?></td>
                            <td align="right"><?php 
                                  echo (($position == 0) ? "$currency " : " $currency");
                                    echo number_format($ledger['Debit'], 2, '.', ',');
                                    $debit += $ledger['Debit']; ?></td>
                            <td align="right"> <?php
                                    echo (($position == 0) ? "$currency " : " $currency");
                                    echo number_format($ledger['Credit'], 2, '.', ',');
                                    $credit += $ledger['Credit'];?> </td>
                            <td align='right'>
                                <?php
                                $balance = $debit - $credit;
                                echo (($position == 0) ? "$currency " : " $currency");
                                echo number_format($balance, 2, '.', ',');
                                ?>
                            </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                    <td colspan="3" align="right"><b><?php echo display('grand_total') ?>:</b></td>
                    <td align="right"><b><?php
                            echo (($position == 0) ? "$currency " : "$currency");
                            echo number_format((@$debit), 2, '.', ',');
                            ?></b>
                    </td>
                    <td align="right"><b><?php
                            echo (($position == 0) ? "$currency " : "$currency");
                            echo number_format((@$credit), 2, '.', ',');
                            ?></b>
                    </td>
                    <td align="right"><b><?php
                            echo (($position == 0) ? "$currency " : "$currency");
                            echo number_format((@$balance), 2, '.', ',');
                            ?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                               
                            </div>
                        </div>
                         <div class="text-right"><?php echo $links ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

