<!-- Add new customer start -->


<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('attendance') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('attendance') ?></a></li>
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
                    <?php echo  form_open('Cattendance/create_atten') ?>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label for="employee_id" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                            <?php echo form_dropdown('employee_id',$dropdownatn,null,'class="form-control" id="employee_id" required') ?>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label for="employee_id" class="col-sm-3 col-form-label"><?php echo display('site') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                            <?php echo form_dropdown('site_id',$dropdownsite,null,'class="form-control" id="site_id" required') ?>
                            </div>
                        </div> 
                        <div class="form-group row ">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('date') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9 picker-container">                          
                              
                                 <input type="text" id="date" value="<?php echo  date('Y-m-d');?>" name="date" class="form-control datepicker">
                            </div>
                        </div>
                              <div class="form-group row ">
                            <label for="time" class="col-sm-3 col-form-label"><?php echo display('sign_in') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9 picker-container">                          
                              
                                 <input type="text" id="timepicker-12-hr" name="intime" class="form-control timepicker-12-hr">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="form-group text-center" style="margin-bottom:0%">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('check_in') ?></button>
                        </div>
                    </div>
                    <?php echo form_close() ?> 
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Start Modal -->

