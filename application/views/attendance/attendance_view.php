<!-- Add new customer start -->
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
            <div class="col-sm-12 col-md-12">
                <div class="form-group text-left">
                    <a href="<?php echo base_url(); ?>Cattendance/single_checkin" class="btn btn-success"><?php echo display('single_checkin') ?></a>
                    <button type="button" class="btn btn-primary" data-target="#add1" data-toggle="modal"  >
                    <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo display('bulk_checkin') ?></button> 
                </div>
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo html_escape($sub_title)?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable table-color-primary">
                                <thead>
                                    <tr>
                                        <th style='width:25px'><?php echo display('no') ?></th>
                                        <th><?php echo display('name') ?></th>
                                        <th><?php echo display('date') ?></th>
                                        <th><?php echo display('checkin') ?></th>
                                        <th><?php echo display('checkout') ?></th>
                                        <th><?php echo display('stay') ?></th>
                                        <th style='width:75px'><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($att_list == FALSE): ?>
                                        <tr><td colspan="8" class="text-center">There are currently No Attendance</td></tr>
                                    <?php else: ?>
                                        <?php $sl = 1; ?> 
                                        <?php foreach ($att_list as $row): ?>
                                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                            <td><?php echo $sl; ?></td>
                                            <td><?php echo html_escape($row['first_name']) . ' ' . html_escape($row['last_name']); ?></td>
                                            <td><?php echo html_escape($row['date']); ?></td>
                                            <td><?php echo html_escape($row['sign_in']); ?></td>
                                            <td><?php echo html_escape($row['sign_out']); ?></td>
                                            <td><?php echo html_escape($row['staytime']); ?></td>
                                            
                                            <td> 
                                                <?php if ($row['staytime'] == '') {  $id = $row["att_id"];?>
                                                <a href='<?php echo base_url("Cattendance/checkout/" . $id) ?>' class='btn btn-success btn-sm'><i class='fa fa-clock-o' aria-hidden='true'></i> <?php echo display('checkout') ?></a>
                                                <?php
                                                    } else {
                                                        echo display('checked_out');
                                                    }
                                                ?> 
                                            </td>
                                        </tr>
                                            <?php $sl++; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                    <div id="add1" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <strong><?php echo display('add_attendance') ?></strong>
                                </div>
                                <div class="modal-body">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php echo form_open_multipart('Cattendance/attendance_bulkupload', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_attendance')) ?>
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label for="upload_csv_file" class="col-sm-4 col-form-label"><?php echo display('upload_csv_file') ?> <i class="text-danger">*</i></label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" name="upload_csv_file" type="file" id="upload_csv_file" placeholder="<?php echo display('upload_csv_file') ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo form_close() ?>
                                        </div>
                                        <div class="panel-footer">
                                            <div class="form-group text-left" style="margin-bottom:0%">
                                                <input type="submit" id="add-product" class="btn btn-success w-md m-b-5" name="add-product" value="<?php echo display('submit') ?>" />
                                                <button type="button" class="btn btn-danger w-md m-b-5" data-dismiss="modal">Close</button>
                                                <a href="<?php echo base_url('assets/data/csv/attendance_csv_sample.csv') ?>" class="btn btn-primary w-md m-b-5"><i class="fa fa-download"></i><?php echo display('download_sample_file') ?> </a>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
