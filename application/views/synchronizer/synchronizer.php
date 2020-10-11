<!-- Synchronizer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('data_synchronizer') ?></h1>
            <small><?php echo display('data_synchronizer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('data_synchronizer') ?></a></li>
                <li class="active"><?php echo display('data_synchronizer') ?></li>
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
                
                  <a href="<?php echo base_url('data_synchronizer/form')?>" class="btn btn-warning m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('setting')?> </a>
                  <a href="<?php echo base_url('Backup_restore')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('backup_restore')?> </a>
                </div>
            </div>
        </div>

        <!-- New supplier -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('data_synchronizer') ?> </h4>
                        </div>
                    </div>

                    <div class="panel-body">

 
                        <div id="message" class="alert hide"></div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo display('internet_connection') ?></label>
                            <div class="col-sm-9">  
                                <?php echo (($internet)? "<i class='fa fa-check text-success'></i> ".display('ok') : "<i class='fa fa-times text-danger'></i> ".display('not_available') ) ?>
                            </div> 
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo display('outgoing_file') ?></label>
                            <div class="col-sm-9">  
                                <?php echo (($outgoing)? "<i class='fa fa-check text-success'></i> ".display('available') : "<i class='fa fa-times text-danger'></i> ".display('not_available')) ?>
                            </div> 
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo display('incoming_file') ?></label>
                            <div class="col-sm-9">  
                                <?php echo (($incoming)? "<i class='fa fa-check text-success'></i> ".display('available') : "<i class='fa fa-times text-danger'></i> ".display('not_available')) ?>
                            </div> 
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4">  
                                <?php 
                                    $localhost=false;
                                    if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', 'localhost'))) {
                                        $localhost=true;
                                    } 
                                ?> 

                   

                                <?php if ($outgoing && $localhost) { ?>
                                    <button type="submit" id="upload" class="btn btn-success w-md m-b-5 btn-block"><i class="fa fa-upload"></i> <?php echo display('data_upload_to_server') ?></button>
                                <?php }elseif(!$incoming && $localhost) {
                                ?>
                                    <button type="submit" id="download" class="btn btn-primary w-md m-b-5 btn-block"><i class="fa fa-download"></i> <?php echo display('download_data_from_server') ?> </button>
                                <?php
                                }elseif($incoming){
                                ?>
                                    <button type="submit" id="import" class="btn btn-info w-md m-b-5 btn-block"><i class="fa fa-database"></i> <?php echo display('data_import_to_database') ?></button>
                                <?php
                                }
                                ?>
                            </div>  
                        </div> 

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Synchronizer end -->

