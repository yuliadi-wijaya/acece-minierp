
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('attendance') ?></h1>
			<small><?php echo $title ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('attendance') ?></a></li>
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
                    <?php if (!empty($this->session->flashdata($data['site_work_hour']))): ?>
                        <?php $data_workhour= ($this->session->flashdata($data['site_work_hour']))['data'] ?>
					<?php echo form_open_multipart('Cattendance/update_edit_working_hour',array('class' => 'form-vertical','id' => 'inflow_entry' ))?>
					<div class="panel-body">
						
						<div class="form-group row">
                       
							<label for="phone" class="col-sm-2 col-form-label"><?php echo display('site') ?> <i class="text-danger">*</i></label>
							<div class="col-sm-10">
								<select class="form-control" name="site" id="site" tabindex="1">
                                   
                                <option value="<?php echo $data_workhour[0]['site_id'];?>"><?php echo $data_workhour[0]['name'];?></option>
                                   
                                </select>
							</div>
						</div>

                        <div class="table-responsive">
                            <table width="100%" class="table table-bordered table-striped table-hover table-color-primary">
                                <thead>
                                    <tr>
                                        <th>Days</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Count</th>
                                        <th>Is Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Monday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="monday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="monday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="monday_count" value="<?php echo $data_workhour[0]['count'] ?>"  tabindex="4"/></td>
                                        <td><input type="checkbox" name="monday_isactive" <?php echo $data_workhour[0]['is_active'] == "1" ? "checked value='1'" : "value='0'";?> >  Active</td>
                                    </tr>
                                    <tr>
                                        <td>Tuesday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="tuesday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="tuesday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="tuesday_count" value="<?php echo $data_workhour[1]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="tuesday_isactive" <?php echo $data_workhour[1]['is_active'] == "1" ? "checked value='1'" : "value='0'";?> > Active</td>
                                    </tr>
                                    <tr>
                                        <td>Wednesday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="wednesday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="wednesday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="wednesday_count" value="<?php echo $data_workhour[2]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="wednesday_isactive" <?php echo $data_workhour[2]['is_active'] == "1" ? "checked value='1'" : "value='0'";?>  > Active</td>
                                    </tr>
                                    <tr>
                                        <td>Thursday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="thursday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="thursday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="thursday_count" value="<?php echo $data_workhour[3]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="thursday_isactive" <?php echo $data_workhour[3]['is_active'] == "1" ? "checked value='1'" : "value='0'";?>  > Active</td>
                                    </tr>
                                    <tr>
                                        <td>Friday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="friday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="friday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="friday_count" value="<?php echo $data_workhour[4]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="friday_isactive" <?php echo $data_workhour[4]['is_active'] == "1" ? "checked value='1'" : "value='0'";?> > Active</td>
                                    </tr>
                                    <tr>
                                        <td>Saturday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="saturday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="saturday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="saturday_count" value="<?php echo $data_workhour[5]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="saturday_isactive" <?php echo $data_workhour[5]['is_active'] == "1" ? "checked value='1'" : "value='0'";?> > Active</td>
                                    </tr>
                                    <tr style="background-color: #dd4b39; color:#fff;">
                                        <td>Sunday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="sunday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="sunday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="sunday_count" value="<?php echo $data_workhour[6]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="sunday_isactive" <?php echo $data_workhour[6]['is_active'] == "1" ? "checked value='1'" : "value='0'";?> > Active</td>
                                    </tr>
                                    <tr style="background-color: #dd4b39; color:#fff;">
                                        <td>Holiday</td>
                                        <td><input type="text" id="timepicker-12-hr" name="holiday_checkin" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" id="timepicker-12-hr" name="holiday_checkout" class="form-control timepicker-12-hr"></td>
                                        <td><input type="text" class="form-control" name="holiday_count" value="<?php echo $data_workhour[7]['count'] ?>" tabindex="4"/></td>
                                        <td><input type="checkbox" name="holiday_isactive" <?php echo $data_workhour[7]['is_active'] == "1" ? "checked value='1'" : "value='0'";?> > Active</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
					</div>


                  
					<div class="panel-footer">
						<div class="form-group" style="margin-bottom:0%">
							<button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
							<button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
						</div>
					</div>

                    <?php
                            else: ?>
                    <?php echo form_open_multipart('Cattendance/get_data_working_hour',array('class' => 'form-vertical','id' => 'inflow_entry' ))?>
					<?php echo $data?>
                    <div class="panel-body">
						
						<div class="form-group row">
							<label for="phone" class="col-sm-2 col-form-label"><?php echo display('site') ?> <i class="text-danger">*</i></label>
							<div class="col-sm-10">
								<select class="form-control" name="site" id="site" tabindex="1">
                                    <option><?php echo display('select_one')?></option>
                                    {site_list}
                                    <option value="{site_id}">{name}</option>
                                    {/site_list}
                                </select>
                             
							</div>
						</div>

                        
					</div>
                    <div class="panel-footer">
						<div class="form-group" style="margin-bottom:0%">
							<button type="submit" class="btn btn-success w-md m-b-5">Edit</button>
							
						</div>
					</div>
                    <?php
                            endif; ?>

					<?php echo form_close()?>
				</div>
			</div>
		</div>
	</section>
</div>
