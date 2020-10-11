<!-- Edit Profile Page Start -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-user-female"></i></div>
        <div class="header-title">
            <h1><?php echo display('update_profile') ?></h1>
            <small><?php echo display('your_profile') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i><?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('profile') ?></a></li>
                <li class="active"><?php echo display('update_profile') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12 col-md-4">
            </div>
            <div class="col-sm-12 col-md-4">

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
            <?php echo form_open_multipart('Admin_dashboard/update_profile', array('id' => 'insert_product'))?>
                <div class="card">
                    <div class="card-content">
                        <div class="card-content-member">
                              <div > <?php echo "<img src='{logo}' width=100px; height=100px; class=img-circle>";?></div>
                              <br>
                            <h4 class="m-t-0" style="margin-bottom:0px;">{first_name} {last_name}</h4>
                        </div>
                        <div class="card-content-languages">
                            <div class="form-group row" style="margin-top:15px;margin-bottom:0px;">
                                <label for="first_name" class="col-sm-3 col-form-label"><?php echo display('first_name') ?></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="<?php echo display('first_name') ?>" class="form-control" id="first_name" name="first_name" value="{first_name}" required />
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top:0px;margin-bottom:0px;">
                                <label for="last_name" class="col-sm-3 col-form-label"><?php echo display('last_name') ?></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="<?php echo display('last_name') ?>" class="form-control" id="last_name" name="last_name" value="{last_name}" required  />
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top:0px;margin-bottom:0px;">
                                <label for="email" class="col-sm-3 col-form-label"><?php echo display('email') ?></label>
                                <div class="col-sm-9">
                                    <input type="email" placeholder="<?php echo display('email') ?>" class="form-control" id="user_name" name="user_name" value="{user_name}" required />
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top:12px">
                                <label for="image" class="col-sm-3 col-form-label"><?php echo display('image') ?></label>
                                <div class="col-sm-9">
                                    <input type="file" id="logo" name="logo" value="{logo}" />
                                    <input type="hidden" name="old_logo" value="{logo}" />
                                </div>
                            </div>
                            <div class="card-content text-center" style="margin-top:20px">
                                <button type="submit" class="btn btn-success"><?php echo display('update_profile') ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="card-footer-stats" style="background-color:#3c8dbc">
                            <div>
                                <p></p><span class="stats-small"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close()?>
            </div>
        </div> 
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
<!-- Edit Profile Page End -->