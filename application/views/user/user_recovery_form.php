<link href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("assets/css/style.css"); ?>" rel="stylesheet" type="text/css"/>
 <link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css"/>
    <div class="panel panel-bd ">
        
        <div class="row" id="rcv_form">
            <div class="col-sm-2"></div>
        
        <div class="col-sm-6" align="centger">
        <div class="panel-body rcv">
            <?php echo form_open('Admin_dashboard/recovery_update', array('id' => 'recovery',)) ?>
            
           
             <div class="form-group row">
                            <label for="newpassword" class="col-sm-4 col-form-label"><?php echo display('newpassword')?> <i class="text-danger">*</i></label>
                            <div class="col-sm-4">
                                <input class="form-control" name ="newpassword" id="newpassword" type="text" placeholder="<?php echo display('newpassword') ?>"  required="">
                                <input type="hidden" name="token" value="<?php echo $token; ?>">
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-success"><?php echo display('send') ?></button> 
                            </div>
                        </div>

           
            <?php echo form_close() ?>
        </div>
</div>
<div class="col-sm-2"></div>
    </div>
    </div>
</div>
  

