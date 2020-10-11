
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('return')?></h1>
            <small><?php echo display('return_from')?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('return') ?></a></li>
                <li class="active"><?php echo display('return_from') ?></li>
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
              
                
                 <?php if($this->permission1->method('return_list','read')->access()){ ?>
                  <a href="<?php echo base_url('Cretrun_m/return_list')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('customer_return_list')?> </a>
              <?php }?>
               <?php if($this->permission1->method('supplier_return_list','read')->access()){ ?>
                    <a href="<?php echo base_url('Cretrun_m/supplier_return_list')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('supplier_return')?> </a>
                      <?php }?>
                      <?php if($this->permission1->method('wastage_return_list','read')->access()){ ?>
                      <a href="<?php echo base_url('Cretrun_m/wastage_return_list')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('wastage_list')?> </a>
                      <?php }?>

                
            </div>
        </div>
         <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                     <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('return_from_customer')?></h4>
                        </div>
                    </div>
                    <div class="panel-body"> 
                        <?php echo form_open('Cretrun_m/invoice_return_form',array('class' => 'form-inline'))?>

                            <div class="form-group">
                                <label for="to_date"> <?php echo  display('invoice_no') ?>:</label>
                                <input type="text" name="invoice_id" class="form-control" id="to_date" placeholder="<?php echo display('invoice_no')?>" required="required">
                            </div>  

                            <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                       <?php echo form_close()?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                     <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('return_to_supplier')?></h4>
                        </div>
                    </div>
                      <div class="panel-body"> 
                        <?php echo form_open('Cretrun_m/supplier_return_form',array('class' => 'form-inline'))?>

                            <div class="form-group">
                                <label for="to_date"><?php echo  display('purchase_id') ?>:</label>
                                <input type="text" name="purchase_id" class="form-control" id="to_date" placeholder="Return Purchase Id" required="required">
                            </div>  

                            <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                       <?php echo form_close()?>
                    </div>
                </div>
            </div>
        </div>
        
       
    </section>
</div>




