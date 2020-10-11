<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('payroll') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('payroll') ?></a></li>
                <li class="active"><?php echo html_escape($sub_title) ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
            ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4>
                    <i class="icon fa fa-check"></i> Success!
                </h4>
                <?php echo $message ?>
            </div>
            <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4>
                    <i class="icon fa fa-ban"></i> Error!
                </h4>
                <?php echo $error_message ?>
            </div>
            <?php
            $this->session->unset_userdata('error_message');
        }
        ?>

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo html_escape($sub_title) ?></h4>
                        </div>
                    </div>
                    <?php echo form_open('Cpayroll/salary_setup_entry', 'id="validate"') ?>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label for="employee_id" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <?php echo form_dropdown('employee_id', $employee, null, 'class="form-control" id="employee_id" onchange="employechange(this.value)" required') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="payment_period" class="col-sm-3 col-form-label"><?php echo display('salary_type') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="sal_type_name" id="sal_type_name" readonly="">
                                <input type="hidden" class="form-control" name="sal_type" id="sal_type">
                            </div>
                        </div>

                        <table class="table table-bordered table-striped table-hover datatable table-color-primary" width="100%">
                            <tr>
                                <th class="col-sm-6 text-center">
                                    <h4 class="payrolladditiondeduction"><?php echo display('addition') ?></h4>
                                </th>
                                <th class="col-sm-6 text-center">
                                    <h4 class="payrolladditiondeduction"><?php echo display('deduction') ?></h4>
                                </th>
                            </tr>
                            <tr>
                                <td class="col-sm-6 text-center">
                                    <br>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-left"><?php echo display('basic') ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" id="basic" name="basic" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <?php $x = 0; foreach ($slname as $ab) { ?>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-left"><?php echo $ab->sal_name; ?> (%)</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="amount[<?php echo $ab->salary_type_id; ?>]" class="form-control addamount" onkeyup="summary()" id="add_<?php echo $x; ?>">
                                        </div>
                                    </div>
                                    <?php $x++; }?>
                                </td> 
                                <td class="col-sm-6 text-center">
                                    <br>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-left"><?php echo display('tax') ?></label>
                                        <div class="col-sm-6">
                                            <input type="text" name="amount[]"  onkeyup="summary()"  class="form-control deducamount" id="taxinput">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="tax_manager" id="taxmanager" onchange='handletax(this);' value="1"> Tax Manager
                                        </div>
                                        
                                    </div>
                                    <?php $y = 0; foreach ($sldname as $row) { ?>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-left"><?php echo $row->sal_name; ?> (%)</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="amount[<?php echo $row->salary_type_id; ?>]" onkeyup="summary()" class="form-control deducamount" id="dd_<?php echo $y; ?>">
                                        </div>
                                    </div>
                                    <?php $y++; } ?>
                                </td> 
                            </tr>
                            
                        </table>
                        <br/>
                     
                        <div class="form-group row">
                            <label for="payable" class="col-sm-3 col-form-label text-center"><?php echo display('gross_salary') ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="gross_salary" id="grsalary" readonly="">
                            </div>
                        </div>
                        
                    </div>
                    <div class="panel-footer">
                        <div class="form-group" style="margin-bottom:0%">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                            <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>  
            </div>
        </div>
    </section>
</div>
<script src="<?php echo base_url() ?>my-assets/js/admin_js/payroll.js" type="text/javascript"></script>
<!-- Add new beneficial end -->



