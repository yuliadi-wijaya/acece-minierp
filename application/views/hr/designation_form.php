<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('hrm') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('hrm') ?></a></li>
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

        <!-- New Employee Type -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo html_escape($sub_title) ?> </h4>
                        </div>
                    </div>
                  <?php echo  form_open('Chrm/create_designation') ?>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label for="designation_name" class="col-sm-3 col-form-div"><?php echo display('designation') ?> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input name="designation" class="form-control" required type="text" placeholder="<?php echo display('designation') ?>" id="designation" value="<?php echo html_escape((!empty($designation_data[0]['designation'])?$designation_data[0]['designation']:''))?>"> 
                                <input type="hidden" name="id" value="<?php echo html_escape((!empty($designation_data[0]['id'])?$designation_data[0]['id']:''))?>">
                           
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="details" class="col-sm-3 col-form-div"><?php echo display('details') ?></label>
                            <div class="col-sm-9">
                                <textarea name="details" class="form-control" placeholder="<?php echo display('details') ?>" id="details"><?php echo html_escape((!empty($designation_data[0]['details'])?html_escape($designation_data[0]['details']):''))?></textarea> 
                            </div>
                        </div> 
                    </div>
                    <div class="panel-footer">
                        <div class="form-group" style="margin-bottom:0%">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                            <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>
