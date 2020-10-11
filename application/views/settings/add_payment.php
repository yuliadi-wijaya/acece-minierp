<!-- Add Prerson start -->
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('personal_loan') ?></h1>
			<small><?php echo $title ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('personal_loan') ?></a></li>
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
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-bd lobidrag">
					<div class="panel-heading">
						<div class="panel-title">
							<h4><?php echo html_escape($sub_title) ?> </h4>
						</div>
					</div>
					<?php echo form_open_multipart('Csettings/submit_payment',array('class' => 'form-vertical','id' => 'inflow_entry' ))?>
					<div class="panel-body">
						<div class="form-group row">
							<label for="name" class="col-sm-2 col-form-label"><?php echo display('name') ?> <i class="text-danger">*</i></label>
							<div class="col-sm-10">
								<select class="form-control" name="person_id" id="namepersonloan" tabindex="1">
									<option><?php echo display('select_one')?></option>
									{person_list}
									<option value="{person_id}">{person_name}</option>
									{/person_list}
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="phone" class="col-sm-2 col-form-label"><?php echo display('phone') ?> <i class="text-danger">*</i></label>
							<div class="col-sm-10">
								<input type="number" class="form-control phone" name="phone" id="phone" required="" placeholder="<?php echo display('phone') ?>" min="0" tabindex="2"/>
							</div>
						</div>
						<div class="form-group row">
							<label for="ammount" class="col-sm-2 col-form-label"><?php echo display('ammount') ?> <i class="text-danger">*</i></label>
							<div class="col-sm-10">
								<input type="number" class="form-control" name="ammount" id="ammount" required="" placeholder="<?php echo display('ammount') ?>" min="0" tabindex="3"/>
							</div>
						</div>
						<div class="form-group row">
							<label for="date" class="col-sm-2 col-form-label"><?php echo display('date') ?> <i class="text-danger"></i></label>
							<div class="col-sm-10">
								<input type="text" class="form-control datepicker" name="date" id="date"  value="<?php echo date("Y-m-d");?>" placeholder="<?php echo display('date') ?>" tabindex="4"/>
							</div>
						</div>
						<div class="form-group row">
							<label for="details" class="col-sm-2 col-form-label"><?php echo display('details') ?> <i class="text-danger"></i></label>
							<div class="col-sm-10">
								<textarea class="form-control" name="details" id="details"  placeholder="<?php echo display('details') ?>" tabindex="5"></textarea>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="form-group" style="margin-bottom:0%">
							<button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
							<button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
						</div>
					</div>
					<?php echo form_close()?>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Add Prerson end -->