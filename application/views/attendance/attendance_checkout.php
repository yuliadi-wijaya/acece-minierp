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
                 <?php echo  form_open('Cattendance/checkout') ?>
                <div class="panel-body">
                    <input name="att_id" id="att_id" type="hidden" value="<?php echo $attendata[0]['att_id']?>">
                     <div class="form-group row">
                            <label for="sign_in" class="col-sm-3"><?php echo display('employee')?></label>
                            <div class="col-sm-6">
                                <input name="employee" class=" form-control" type="text"  value="<?php echo html_escape($attendata[0]['first_name']).' '.html_escape($attendata[0]['last_name'])?>" id="employee" readonly="readonly" >
                            </div>
                        </div>
                 
                        <div class="form-group row">
                            <label for="sign_in" class="col-sm-3"><?php echo display('sign_in')?></label>
                            <div class="col-sm-6">
                                <input name="sign_in" class=" form-control" type="text"  value="<?php echo html_escape($attendata[0]['sign_in'])?>" id="sign_in" readonly="readonly" >
                            </div>
                        </div>
                     
                       <div class="form-group row">
                         <label for="sign_in" class="col-sm-3"><?php echo display('sign_out')?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input name="sign_out" class="form-control timepicker-12-hr" type="text"  value=""  id="sign_out"  >
                            </div>
                        </div>
                </div>  
                <div class="panel-footer">
                    <div class="form-group text-center" style="margin-bottom:0%">
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('checkout')?></button>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</section>
</div>