
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('web_settings') ?></h1>
            <small><?php echo display('app_setting') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('web_settings') ?></a></li>
                <li class="active"><?php echo display('app_setting') ?></li>
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

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('app_setting') ?> </h4>
                        </div>
                    </div>

          <table  id="" class="table table-responsive">
            <tr><td> <img src="<?php echo base_url('my-assets/image/qr/{qr_image}') ?>" class="img-responsive center-block appsettingqr" alt="">
                <span class="appsettingqrtxt">Localhost Server QR Code</span>
            </td>
                <td> <img src="<?php echo base_url('my-assets/image/qr/{server_image}') ?>" class="img-responsive center-block appsettingqr" alt=""><span class="appsettingqrtxt">Online Server QR Code</span>
                </td>
                <td> <img src="<?php echo base_url('my-assets/image/qr/{hotspotqrimg}') ?>" class="img-responsive center-block appsettingqr" alt=""><span class="appsettingqrtxt">Hotspot Ip/Url QR Code</span>
                </td>
            </tr>

    </table>
                 
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8">
                           <?php echo form_open_multipart('Cweb_setting/update_app_setting', array('class' => 'form-vertical', 'id' => 'app_settings')) ?>

                        <div class="form-group row">
                            <label for="local_server_url" class="col-sm-4 col-form-label"><?php echo display('local_server_url') ?> <i class="text-danger"></i></label>
                            <div class="col-sm-8">
                                <input type="text" name="localurl" class="form-control" placeholder="<?php echo display('local_server_url') ?>" value="<?php echo  $localhserver?>">
                                <span class="text-danger">http://localhost/saleserp</span>
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="local_server_url" class="col-sm-4 col-form-label"><?php echo display('online_server_url') ?> <i class="text-danger"></i></label>
                            <div class="col-sm-8">
                                <input type="hidden" name="id" value="<?php echo  $id;?>">
                                <input type="text" name="onlineurl" class="form-control" placeholder="<?php echo display('online_server_url') ?>" value="<?php echo  $onlineserver?>">
                                <span  class="text-danger">http://bdtask.com/saleserp</span>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="local_server_url" class="col-sm-4 col-form-label"><?php echo display('connet_url') ?> <i class="text-danger"></i></label>
                            <div class="col-sm-8">
                                <input type="text" name="hotspoturl" class="form-control" placeholder="<?php echo display('connet_url') ?>" value="<?php echo  $hotspot?>">
                                <span  class="text-danger">http://192.168.1.154/saleserp</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-customer" class="btn btn-success btn-large" name="add-customer" value="<?php echo display('save_changes') ?>" tabindex="13"/>
                            </div>
                        </div>
                        <?php echo form_close() ?>
                    </div>
 <div class="col-sm-4 playstorelink">
                <a href="https://play.google.com/store/apps/details?id=com.bdtask.pos" target="blank"><h3><b>Download Sales erp Apps From </b> <br>
                <p class="text-center">Our Playstore</p></h3></a>
                <h1 class="text-center"><a href="#" class="text-center"><i class="fa fa-android"></i></a></h1>
                
            </div>
                </div>

            </div>

        </div>
            </div>
        </div>
    </section>
</div>
<!-- Add new customer end -->



