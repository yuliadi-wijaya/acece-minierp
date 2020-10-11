
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('attendance') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
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

        <!-- Manage Category -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                          <h4><?php echo html_escape($sub_title)?></h4>
                        </div>
                    </div>
                     <?php echo  form_open('Cattendance/edit_atn_form/'. $data[0]['att_id']) ?>
                    <div class="panel-body">  
                        <input name="att_id" type="hidden" value="<?php echo html_escape($data[0]['att_id']) ?>">

                        <div class="form-group row">
                            <label for="emp_id" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <?php echo form_dropdown('employee_id',$dropdownatn,(!empty($data[0]['employee_id'])?$data[0]['employee_id']:null),'class="form-control" id="employee_id"') ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('date') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input name="date" class="form-control datepicker" required type="text" id="date" value="<?php echo html_escape($data[0]['date'])?>">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label for="sign_in" class="col-sm-3 col-form-label"><?php echo display('sign_in') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input name="sign_in" value="<?php echo @$sign_in=html_escape($data[0]['sign_in'])?>" required class="form-control " type="text"    id="sign_in">
                            </div>
                        </div>                
                        <div class="form-group row">
                            <label for="sign_out" class="col-sm-3 col-form-label"><?php echo display('sign_out') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sign_out" required value="<?php echo @$sign_out=html_escape($data[0]['sign_out']) ;?>" class="form-control"   id="" > 
                            </div>
                        </div>    
                    </div>
                    <div class="panel-footer">
                        <div class="form-group text-center" style="margin-bottom:0%">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>

