<script src="<?php echo base_url() ?>my-assets/js/admin_js/payroll.js" type="text/javascript"></script>
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


        <?php echo form_open_multipart('Cpayroll/salary_report', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'find_salary')) ?>

        <div class="row">
            <div class="panel panel-bd lobidrag">
                <div class="panel-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Employee Name</label>
                        <div class="input-group col-sm-2">
                            <input type="text" class="form-control" id="employee_name" name="employee_name" placeholder="Name" value="">
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="date" class="col-sm-2 col-form-label"><?php echo display('start_date') ?> <span class="text-danger">*</span></label>
                        <div class="input-group col-sm-2">

                            <input name="start_date" class="datepicker form-control" type="text" placeholder="<?php echo display('start_date') ?>" id="start_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="end_date" class="col-sm-2 col-form-label"><?php echo display('end_date') ?> <span class="text-danger">*</span></label>
                        <div class="input-group col-sm-2">
                            <input name="end_date" class="datepicker form-control" type="text" placeholder="<?php echo display('end_date') ?>" id="end_date">
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button> &nbsp;
                </div>
            </div>
            <?php echo form_close() ?>


            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo html_escape($sub_title) ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable " id="table">
                                    <thead>
                                        <tr>
                                            <th class="bg-primary" data-field="name" rowspan="2" rowspan="1">Name</th>
                                            <th class="bg-primary" data-field="basic" rowspan="1" colspan="2">Basic Salary</th>
                                            <th class="bg-primary" data-field="meal" rowspan="1" colspan="2">Meal</th>
                                            <th class="bg-primary" data-field="ot" rowspan="1" colspan="2">Overtime</th>
                                            <th class="bg-primary" data-field="meal2" rowspan="1" colspan="2">Meal 2</th>
                                            <th class="bg-info" data-field="gross" rowspan="2" rowspan="1">Gross</th>
                                            <th class="bg-danger" data-field="deduct" rowspan="2" rowspan="1">Deduct</th>
                                            <th class="bg-success" data-field="net" rowspan="2" rowspan="1">Net</th>

                                        </tr>
                                        <tr>
                                            <th class="bg-primary" data-field="absence" rowspan="1" colspan="1">Absence</th>
                                            <th  class="bg-primary" data-field="total" rowspan="1" colspan="1">Total</th>
                                            <th class="bg-primary" data-field="absence" rowspan="1" colspan="1">Absence</th>
                                            <th class="bg-primary" data-field="total" rowspan="1" colspan="1">Total</th>
                                            <th class="bg-primary" data-field="absence" rowspan="1" colspan="1">Absence</th>
                                            <th class="bg-primary" data-field="total" rowspan="1" colspan="1">Total</th>
                                            <th class="bg-primary" data-field="absence" rowspan="1" colspan="1">Absence</th>
                                            <th class="bg-primary" data-field="total" rowspan="1" colspan="1">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                   
                                    <?php foreach($res as $key => $r) {?>
                                        <tr>
                                            <td>
                                                <?php echo $r["name"];?>
                                            </td>
                                            <td>
                                                <?php echo $r["basic_absence"];?>
                                            </td>
                                            <td class="bg-success">
                                                <?php echo $r["basic_total"];?>
                                            </td>
                                            <td>
                                                <?php echo $r["meal_absence"];?>
                                            </td>
                                            <td class="bg-success">
                                                <?php echo $r["meal_total"];?>
                                            </td>
                                            <td>
                                                <?php echo $r["ot_absence"];?>
                                            </td>
                                            <td class="bg-success">
                                                <?php echo $r["ot_total"];?>
                                            </td>
                                            <td>
                                                <?php echo $r["ot_meal"];?>
                                            </td>
                                            <td class="bg-success">
                                                <?php echo $r["meal2_total"];?>
                                            </td>
                                            <td class="bg-info">
                                                <?php echo $r["gross"];?>
                                            </td>
                                            <td class="bg-danger">
                                                <?php echo $r["deduct"];?>
                                            </td>
                                            <td class="bg-success">
                                                <?php echo $r["total"];?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<!-- Add new beneficial end -->