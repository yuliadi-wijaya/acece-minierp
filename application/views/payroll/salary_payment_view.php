<script src="<?php echo base_url() ?>my-assets/js/admin_js/payroll.js" type="text/javascript"></script>
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('payroll') ?></h1>
			<small><?php echo $title ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('payroll') ?></a></li>
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
			<!--  table area -->
			<div class="col-sm-12">
				<div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo html_escape($sub_title) ?></h4>
                        </div>
                    </div>
					<div class="panel-body">
						<table width="100%" class="table table-bordered table-striped table-hover datatable table-color-primary">
							<thead>
								<tr>
									<th style='width:25px'><?php echo display('no') ?></th>
									<th><?php echo display('employee_name') ?></th>
									<th><?php echo display('salary_month') ?></th>
									<th><?php echo display('total_salary') ?></th>
									<th><?php echo display('total_working_minutes') ?></th>
									<th><?php echo display('working_period') ?></th>
									<th><?php echo display('payment_type') ?></th>
									<th><?php echo display('payment_date') ?></th>
									<th><?php echo display('paid_by') ?></th>
									<th style='width:50px'><?php echo display('action') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($emp_pay)) { ?>
								<?php $sl = 1; ?>
								<?php foreach ($emp_pay as $que) { ?>
								<tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
									<td><?php echo $sl; ?></td>
									<td><?php echo html_escape($que->first_name).' '.html_escape($que->last_name); ?></td>
									<td><?php echo html_escape($que->salary_month); ?></td>
									<td><?php echo html_escape($que->total_salary); ?></td>
									<td><?php echo html_escape($que->total_working_minutes); ?></td>
									<td><?php echo html_escape($que->working_period); ?></td>
									<td><?php echo html_escape($que->payment_due); ?></td>
									<td><?php echo html_escape($que->payment_date); ?></td>
									<td><?php echo html_escape($que->paid_by); ?></td>
									<td class="center">
										<?php 
											if($que->payment_due =='paid'){?>
										<a href='<?php echo base_url("Cpayroll/payslip/$que->emp_sal_pay_id") ?>' class='btn btn-info btn-sm'><?php echo display('payslip') ?></a>       
										<?php } 
											else {?>
										<a href='#' class='btn btn-success btn-sm' onclick='Payment(<?php echo $que->emp_sal_pay_id; ?>,"<?php echo $que->employee_id; ?>","<?php echo $que->total_salary; ?>","<?php echo $que->total_working_minutes; ?>","<?php echo $que->working_period; ?>","<?php echo $que->salary_month; ?>")'><?php echo display('pay_now') ?></a>
										<?php  }
											?>
									</td>
								</tr>
								<?php $sl++; ?>
								<?php } ?> 
								<?php } ?> 
							</tbody>
						</table>
						<!-- /.table-responsive -->
						<div class="text-right"><?php echo $links ?></div>
					</div>
				</div>
			</div>
			<div id="PaymentMOdal" class="modal fade" role="dialog">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<strong>
								<center> <?php echo display('payment')?></center>
							</strong>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-12 col-md-12">
									<div class="panel">
                                        <?php echo  form_open('Cpayroll/payconfirm/') ?>
										<div class="panel-body">
											<input name="emp_sal_pay_id" id="salType" type="hidden" value="">
											<div class="form-group row">
												<label for="employee_id" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> </label>
												<div class="col-sm-9">
													<input type="text" name="empname" class="form-control" id="employee_name" value="" readonly>
													<input type="hidden" name="employee_id" class="form-control" id="employee_id" value="">
												</div>
											</div>
											<div class="form-group row">
												<label for="total_salary" class="col-sm-3 col-form-label"><?php echo display('total_salary') ?> </label>
												<div class="col-sm-9">
													<input type="text" name="total_salary" class="form-control" id="total_salary" value="" readonly>
												</div>
											</div>
											<div class="form-group row">
												<label for="total_working_minutes" class="col-sm-3 col-form-label"><?php echo display('total_working_minutes') ?> </label>
												<div class="col-sm-9">
													<input type="text" name="total_working_minutes" class="form-control" id="total_working_minutes" value="" readonly>
												</div>
											</div>
											<div class="form-group row">
												<label for="working_period" class="col-sm-3 col-form-label"><?php echo display('working_period') ?> </label>
												<div class="col-sm-9">
													<input type="text" name="working_period" class="form-control" id="working_period" value="" readonly>
												</div>
											</div>
											<div class="form-group row">
												<label for="salary_month" class="col-sm-3 col-form-label"><?php echo display('salary_month') ?> </label>
												<div class="col-sm-9">
													<input type="text" name="salary_month" class="form-control" id="salary_month" value="" readonly>
												</div>
											</div>
											
                                        </div>
                                        <div class="panel-footer">
                                            <div class="form-group text-center" style="margin-bottom:0%">
                                                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('confirm') ?></button>
                                                <button type="submit" class="btn btn-danger w-md m-b-5" data-dismiss="modal"><?php echo display('cancel') ?></button>
                                            </div>
                                        </div>
                                        <?php echo form_close() ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
	</section>
</div>