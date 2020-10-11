<!-- Add new customer start -->
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
                        <table width="100%" class="table table-bordered table-striped table-hover datatable table-color-primary"  id="dataTableExample3">
                            <thead>
                                <tr>
                                    <th style='width:25px'><?php echo display('no') ?></th>
                                    <th><?php echo display('employee_name') ?></th>
                                    <th><?php echo display('salary_type') ?></th>
                                    <th><?php echo display('date') ?></th>
                                    <th style='width:50px'><?php echo display('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($emp_sl_setup)) { ?>
                                <?php $sl = 1; ?>
                                <?php foreach ($emp_sl_setup as $que) { ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo html_escape($que->first_name).' '.html_escape($que->last_name); ?></td>
                                    <td><?php if($que->sal_type==1){
                                        echo 'Hourly';
                                        }else{
                                        echo 'Salary';
                                        } ?></td>
                                    <td><?php echo html_escape($que->create_date); ?></td>
                                    <td class="center"> 
                                        <?php if($this->permission1->method('manage_salary_setup','update')->access()){ ?>                         
                                        <a href="<?php echo base_url("Cpayroll/salsetup_upform/$que->employee_id") ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><span class='glyphicon glyphicon-edit'></span></a> 
                                        <?php }?>
                                        <?php if($this->permission1->method('manage_salary_setup','delete')->access()){ ?>           
                                        <a href="<?php echo base_url("Cpayroll/delete_salsetup/$que->employee_id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') " data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><span class='glyphicon glyphicon-remove'></span></a>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php $sl++; ?>
                                <?php } ?> 
                                <?php } ?> 
                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>