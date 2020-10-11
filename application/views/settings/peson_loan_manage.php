<!-- Account List Start -->
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
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTableExample3" class="table table-bordered table-striped table-hover datatable table-color-primary">
                                <thead>
                                    <tr>
                                        <th><?php echo display('name') ?></th>
                                        <th><?php echo display('address') ?></th>
                                        <th><?php echo display('phone') ?></th>
                                        <th><?php echo display('balance') ?></th>
                                        <th style='width:50px'><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($person_list) {
                                        ?>
                                        {person_list}
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('Csettings/person_loan_deails/{person_id}'); ?>">{person_name}</a>
                                            </td>
                                            <td>{person_address}</td>
                                            <td>{person_phone}</td>
                                            <td><?php echo (($position == 0) ? "$currency {balance}" : "{balance} $currency"); ?></td>
                                            <td>
                                    <center>
                                        <?php echo form_open() ?>
                                        <?php if($this->permission1->method('manage_person','update')->access()){ ?>
                                        <a href="<?php echo base_url('Csettings/person_loan_edit/{person_id}'); ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="left" title="" data-original-title="Update"><span class='glyphicon glyphicon-edit'></span></a>
                                    <?php }?>
                                    <?php if($this->permission1->method('manage_person','delete')->access()){ ?>
                                     <a href="<?php echo base_url("Csettings/delete_personal_loan/{person_id}") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><span class='glyphicon glyphicon-remove'></span></a> 
                                     <?php }?>
                                        <?php echo form_close() ?>  
                                    </center>
                                    </td>
                                    </tr>
                                    {/person_list}
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right"><?php echo $links ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Account List End -->

