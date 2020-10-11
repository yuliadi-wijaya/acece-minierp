<!-- Add new customer start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo $title ?></h1>
            <small><?php echo $title ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('permission') ?></a></li>
                <li class="active"><?php echo $title ?></li>
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

     

        <!-- New customer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo $title ?> </h4>
                        </div>
                    </div>
                   
                    <div class="panel-body">
                         <?php echo form_open("Permission/update/") ?>
                          <div class="form-group row">
                                <label for="type" class="col-sm-3 col-form-label"><?php echo display('role_name') ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-6">
                                    <input type="text" value="<?php echo  $role['0']->type;?>" tabindex="2" class="form-control" name="role_id" id="type" placeholder="<?php echo display('role_name') ?>" required />
                                </div>
                            </div>
                            <input type="hidden" name="rid" value="<?php echo $role['0']->id?>">

                          <?php
            $m=0;
            foreach ($module as $key=>$value) {
                $account_sub = $this->db->select('*')->from('sub_module')->where('mid',$value->id)->get()->result();

                ?>
                <table class="table table-bordered hidetable">
                    <h2 class="hidetable"><?php echo display($value->name);?></h2>
                    <thead>
                    <tr>
                        <th><?php echo display('sl_no');?></th>
                        <th><?php echo display('menu_name');?></th>
                        <th><?php echo display('create');?> (<label for="checkAllcreate<?php echo $m?>"><input type="checkbox" onclick="checkallcreate(<?php echo $m?>)" id="checkAllcreate<?php echo $m?>"  name="" > All)</label></th>
                        <th><?php echo display('read');?> (<input type="checkbox" onclick="checkallread(<?php echo $m?>)" id="checkAllread<?php echo $m?>"  name="" > all)</th>
                        <th><?php echo display('update');?> (<input type="checkbox" onclick="checkalledit(<?php echo $m?>)" id="checkAlledit<?php echo $m?>"  name="" > all)</th>
                        <th><?php echo display('delete');?> (<input type="checkbox" onclick="checkalldelete(<?php echo $m?>)" id="checkAlldelete<?php echo $m?>"  name="" > all)</th>
                    </tr>
                    </thead>
                    <?php $sl = 0 ?>
                    <?php if (!empty($account_sub)) { ?>
                        <?php
                        foreach ($account_sub as $key1 => $module_name){
                            $ck_data = $this->db->select('*')
                                     ->where('fk_module_id',$module_name->id)
                                     ->where('role_id',$role['0']->id)->get('role_permission')->row();
                            ?>
                            <?php
                            $createID = 'id="create'.$m.''.$sl.'" class="create'.$m.'"';
                            $readID   = 'id="read'.$m.''.$sl.'" class="read'.$m.'"';
                            $updateID = 'id="update'.$m.''.$sl.'" class="edit'.$m.'"';
                            $deleteID = 'id="delete'.$m.''.$sl.'" class="delete'.$m.'"';
                            ?>
                            <tbody>
                            <tr>
                                <td><?php echo ($sl+1) ?></td>
                                <td>
                                    <?php echo display($module_name->name)?>
                                    <input type="hidden" name="fk_module_id[<?php echo $m?>][<?php echo $sl?>][]" value="<?php echo $module_name->id ?>" id="id_<?php echo $module_name->id ?>">
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" class="create<?php echo $m?>" name="create[<?php echo $m?>][<?php echo $sl ?>][]" value="1" <?php echo ((@$ck_data->create==1)?"checked":null) ?> id="create[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="create[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="read[<?php echo $m?>][<?php echo $sl ?>][]" class="read<?php echo $m?>" value="1" <?php echo ((@$ck_data->read==1)?"checked":null) ?> id="read[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="read[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="update[<?php echo $m?>][<?php echo $sl ?>][]" class="edit<?php echo $m?>" value="1" <?php echo ((@$ck_data->update==1)?"checked":null) ?> id="update[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="update[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox checkbox-success text-center">
                                        <input type="checkbox" name="delete[<?php echo $m?>][<?php echo $sl ?>][]" class="delete<?php echo $m?>" value="1" <?php echo ((@$ck_data->delete==1)?"checked":null) ?> id="delete[<?php echo $m?>]<?php echo $sl?>">
                                        <label for="delete[<?php echo $m ?>]<?php echo $sl ?>"></label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <?php $sl++ ?>
                        <?php } ?>
                    <?php } //endif ?>
                </table>
                <?php $m++; } ?>

            <div class="form-group text-right">
                <button type="reset" class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
            </div>
            <?php echo form_close() ?>
                    </div>
                   
                </div>
            </div>
        </div>

    </section>
</div>



