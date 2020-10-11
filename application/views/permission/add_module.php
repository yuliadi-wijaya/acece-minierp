<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('add_module') ?></h1>
            <small><?php echo display('add_module') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('permission') ?></a></li>
                <li class="active"><?php echo display('add_module') ?></li>
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

        <div class="row">
            <div class="col-sm-12">
                <div class="column">

                    <a href="<?php echo base_url('Permission/add_module') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('module_list') ?> </a>

                   

                </div>
            </div>
        </div>

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('add_module') ?> </h4>
                        </div>
                    </div>
                    <?php echo form_open('Permission/add_module', array('class' => 'form-vertical', 'id' => 'insert_module')) ?>
                    <div class="panel-body">

                        <div class="form-group row">
                            <label for="module_name" class="col-sm-3 col-form-label"><?php echo display('module_name') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="hidden" name="id" value="<?php echo (!empty($moduleinfo->id)?$moduleinfo->id:'')?>">
                                <input class="form-control" name ="module_name" id="module_name" type="text" placeholder="<?php echo display('module_name') ?>"  required="" tabindex="1" value="<?php echo (!empty($moduleinfo->name)?$moduleinfo->name:'')?>">
                            </div>
                        </div>

                      


                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-module" class="btn btn-primary btn-large" name="add-module" value="<?php echo display('save') ?>" tabindex="7"/>
                              
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>

    </section>
</div>
<!-- Add new customer end -->



