
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('hrm') ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('hrm') ?></a></li>
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
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                          <h4><?php echo html_escape($sub_title)?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="" class="table table-bordered table-striped table-hover datatable table-color-primary">
                                <thead>
                                    <tr>
                                        <th style='width:25px'><?php echo display('no') ?></th>
                                        <th><?php echo display('designation') ?></th>
                                        <th><?php echo display('details') ?></th>
                                        <th style='width:50px'><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($designation_list) {
                                        ?>
                                       
                                        <?php
                                        $sl =1;
                                         foreach($designation_list as $designations){?>
                                        <tr>
                                            <td><?php echo $sl;?></td>
                                            <td><?php echo html_escape($designations['designation']);?></td>
                                            <td><?php echo html_escape($designations['details']);?></td>
                                            <td>
                                    <center>
                                        <?php echo form_open() ?>
                                            <?php if($this->permission1->method('manage_designation','update')->access()){ ?>                          
                                                <a href="<?php echo base_url() . 'Chrm/designation_update_form/'.$designations['id']; ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><span class='glyphicon glyphicon-edit'></span></a>
                                            <?php } ?>
                                            <?php if($this->permission1->method('manage_designation','delete')->access()){ ?>                                
                                                <a href="<?php echo base_url('Chrm/designation_delete/'.$designations["id"]) ?>" class="btn btn-danger btn-xs"  onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><span class='glyphicon glyphicon-remove'></span></a>
                                            <?php }?>
                                   
                                            <?php echo form_close() ?>
                                    </center>
                                    </td>
                                    </tr>
                                   
                                    <?php
                                    $sl++;
                                }}
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






