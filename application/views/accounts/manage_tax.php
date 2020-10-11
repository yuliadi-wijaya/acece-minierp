<!-- Cheaque Manager Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('manage_tax') ?></h1>
	        <small><?php echo display('manage_tax') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('accounts') ?></a></li>
	            <li class="active"><?php echo display('manage_tax') ?></li>
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
           <?php if($this->permission1->method('add_tax','create')->access()){ ?>
                  <a href="<?php echo base_url('Caccounts/add_tax')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"> </i>  <?php echo display('add_tax')?> </a>
              <?php }?>

                </div>
            </div>
        </div>

		<!-- Manage TAX -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('manage_tax') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
			           			<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('tax') ?></th>
										<th><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($tax_list) {
										$i=1;
								foreach ($tax_list as $tax) {
								?>
									<tr>
										<td><?php echo $i?></td>
										<td><?php echo $tax->tax?> %</td>
						                <td>
						                    <center>
						                    	 <?php if($this->permission1->method('manage_tax','update')->access()){ ?>
					                            <a href="<?php echo base_url().'Caccounts/tax_edit/'.$tax->tax_id; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('update') ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					                        <?php }?>
                   <?php if($this->permission1->method('manage_tax','delete')->access()){ ?>
					                            <a href="<?php echo base_url().'Caccounts/tax_delete/'.$tax->tax_id; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					                            <?php }?>
						                    </center>
						                </td>
									</tr>
								<?php
								$i++;
									}
								}
								?>
								</tbody>
		                    </table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Cheaque Manager End -->