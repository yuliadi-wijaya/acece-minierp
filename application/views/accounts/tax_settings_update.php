<!-- Add new tax start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('tax') ?></h1>
            <small><?php echo display('tax_settings') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('tax') ?></a></li>
                <li class="active"><?php echo display('tax_settings') ?></li>
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


        <!-- new tax -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('tax_settings') ?> </h4>
                             <div id="outputPreview1" class="alert hide modal-title" role="alert" >
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                        </div>
                    </div>
                   <?php echo form_open_multipart('Caccounts/update_tax_settins',array('class' => 'form-vertical','id' => 'tax_settings_update' ))?>
                    <div class="panel-body">
                       
                    	<div class="form-group row">
                            <label for="number_of_tax" class="col-sm-1 col-form-label"><?php echo display('number_of_tax') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="nt" id="number_of_tax" required="" placeholder="<?php echo display('number_of_tax') ?>" onkeyup="add_columnTaxsettings(this.value)" value="<?php echo html_escape($setinfo[0]['nt']);?>"/>
                                <input type="hidden" name="id" value="<?php echo html_escape($setinfo[0]['id']);?>">
                            </div>
                        </div>
                         
                        <span id="taxfield" class="form-group row">
                            <?php  
                        $i=1;
                        foreach ($setinfo as $taxss) {?>
                          <div class="form-group row"><label for="fieldname" class="col-sm-1 col-form-label">Tax Name <?php echo $i;?>*</label><div class="col-sm-2"><input type="text" class="form-control" name="taxfield[]" required="" value="<?php echo html_escape($taxss['tax_name']);?>"></div>
                          <label for="default_value" class="col-sm-1 col-form-label"><?php echo display('default_value') ?><span class="text-danger">(%)</span></label><div class="col-sm-2"><input type="text" class="form-control" name="default_value[]" value="<?php echo html_escape($taxss['default_value']);?>" id="default_value"  placeholder="<?php echo display('default_value') ?>" /></div><label for="reg" class="col-sm-1 col-form-label"><?php echo display('reg_no'); ?></label>
                          <div class="col-sm-2"><input type="text" class="form-control" name="reg_no[]" value="<?php echo html_escape($taxss['reg_no']);?>" id="reg_no"  placeholder="<?php echo display('reg_no') ?>" /></div>
                          <div class="col-sm-1"><input type="checkbox" name="is_show" class="form-control" value="1" <?php if($taxss['is_show']==1){echo 'checked';}?>></div><label for="isshow" class="col-sm-1 col-form-label"><?php echo 'Is Show'; ?></label>
                      </div>  
                      <?php $i++;}?>
                        </span>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-settings" class="btn btn-success" name="add-settings" value="<?php echo display('save') ?>" />
                            </div>
                        </div>
                        <div class="row text-center">
                           <h3> <span class="text-danger">If you Update tax settings ,All of your previous tax record will be destroy.You Will Need to set tax product wise and Service wise</span></h3>
                        </div>
                    </div>
                    <?php echo form_close()?>

                </div>
            </div>
        </div>
    </section>
</div>


