<div class="content-wrapper">
   <section class="content-header">
      <div class="header-icon">
         <i class="pe-7s-note2"></i>
      </div>
      <div class="header-title">
         <h1><?php echo display('site') ?></h1>
         <small><?php echo $title ?></small>
         <ol class="breadcrumb">
            <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
            <li><a href="#"><?php echo display('site') ?></a></li>
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
                              <th><?php echo display('description') ?></th>
                              <th><?php echo display('address') ?></th>
                              <th style='width:50px'><?php echo display('action') ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if ($site_list == FALSE): ?>
                           <tr>
                              <td colspan="7" class="text-center">There are currently No Site</td>
                           </tr>
                           <?php
                              else: ?>
                           <?php $sl = 1; ?> 
                           <?php foreach ($site_list as $row): ?>
                           <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                              <td><?php echo $sl; ?></td>
                              <td><?php echo html_escape($row['name']); ?></td>
                              <td><?php echo html_escape($row['description']); ?></td>
                              <td><?php echo html_escape($row['address']); ?></td>
                              <td class="center">
                                 <?php if ($this->permission1->method('manage_site', 'update')->access()) { ?>
                                 <a href="<?php echo base_url("Csite/update_site/" . $row['site_id']) ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><span class='glyphicon glyphicon-edit'></span></a> 
                                 <?php
                                    } ?>
                                 <?php if ($this->permission1->method('manage_site', 'delete')->access()) { ?>            
                                 <a href="<?php echo base_url("Csite/delete_site/" . $row['site_id']) ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="right" title="<?php echo display('delete') ?>" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><span class='glyphicon glyphicon-remove'></span></a> 
                                 <?php
                                    } ?>
                              </td>
                           </tr>
                           <?php $sl++; ?>
                           <?php
                              endforeach; ?>
                           <?php
                              endif; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>