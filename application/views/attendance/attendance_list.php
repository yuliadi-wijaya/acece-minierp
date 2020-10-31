
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('attendance') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
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

        <!-- Manage Category -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group text-left">
                    <?php
                        $add0 = array('type' => 'button', 'class' => "btn btn-success", 'data-target' => "#add0", 'data-toggle' => "modal", 'value' => display('datewise_report'),);
                        $add = array('type' => 'button', 'class' => "btn btn-primary", 'data-target' => "#add", 'data-toggle' => "modal", 'value' => display('employee_wise_report'),);
                        echo form_input($add0);
                        echo form_input($add);
                    ?>
                </div>
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                          <h4><?php echo html_escape($sub_title) ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-bordered table-striped table-hover datatable table-color-primary">
                                <thead>
                                    <tr>
                                        <th style='width:25px'><?php echo display('no') ?></th>
                                        <th><?php echo display('name') ?></th>
                                        <th><?php echo display('date') ?></th>
                                        <th><?php echo display('check_in') ?></th>
                                        <th><?php echo display('checkout') ?></th>
                                        <th><?php echo display('stay') ?></th>
                                        <th><?php echo display('site') ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($attendance_list == FALSE): ?>

                                        <tr><td colspan="7" class="text-center">There are currently No Information</td></tr>
                                    <?php else: ?>
                                        <?php $sl = 1; ?> 
                                        <?php foreach ($attendance_list as $row): ?>
                                            <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                            <td><?php echo $sl; ?></td>
                                                <td><?php echo html_escape($row['first_name']) . ' ' . html_escape($row['last_name']); ?></td>
                                                <td><?php echo html_escape($row['date']); ?></td>
                                                <td><?php echo html_escape($row['sign_in']); ?></td>
                                                <td><?php echo html_escape($row['sign_out']); ?></td>
                                                <td><?php echo html_escape($row['staytime']); ?></td>
                                                <td><?php echo html_escape($row['name']); ?></td>
                                            
                                            </tr>
                                            <?php $sl++; ?>
                                        <?php  endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="add" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <strong><?php echo display('employee_wise_report') ?></strong>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="panel">
                                    <?php echo form_open('Cattendance/employeewise_att_report', 'name="myForm"') ?>
                                    <div class="panel-body">
                                        <div class="form-group row">
                                            <label for="employee_id" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <?php echo form_dropdown('employee_id', $dropdownatn, (!empty($employee_id) ? $employee_id : null), 'class="form-control" id="employee_id"') ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('start_date') ?> <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input name="s_date" class="datepicker form-control" type="text" placeholder="<?php echo display('start_date') ?>"  id="a_date">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="end_date" class="col-sm-3 col-form-label"><?php echo display('end_date') ?> <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input name="e_date" class="datepicker form-control" type="text" placeholder="<?php echo display('end_date') ?>" id="b_date" >
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="panel-footer">
                                        <div class="form-group text-left" style="margin-bottom:0%">
                                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('request') ?></button>
                                        </div>
                                    </div>
                                    <?php echo form_close() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div id="add0" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <strong><?php echo display('attendance_report') ?></strong>
                    </div>
                    <div class="modal-body">
                <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="panel">
                        <?php echo form_open('Cattendance/datewiseattendancereport') ?>
                        <div class="panel-body">
                            <div class="form-group row">
                                <label for="date" class="col-sm-3 col-form-label"><?php echo display('start_date') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input name="start_date" class="datepicker form-control" type="text" placeholder="<?php echo display('start_date') ?>"  id="start_date">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="end_date" class="col-sm-3 col-form-label"><?php echo display('end_date') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input name="end_date" class="datepicker form-control" type="text" placeholder="<?php echo display('end_date') ?>" id="end_date" >
                                </div>
                            </div>
                        </div>  
                        <div class="panel-footer">
                            <div class="form-group text-left" style="margin-bottom:0%">
                                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('request') ?></button>
                            </div>
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

