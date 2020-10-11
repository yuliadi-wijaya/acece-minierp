<!-- Account List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('office_loan') ?></h1>
            <small><?php echo display('manage_person') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('office_loan') ?></a></li>
                <li class="active"><?php echo display('manage_person') ?></li>
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
                
          <?php if($this->permission1->method('add1_person','create')->access()){ ?>
                    <a href="<?php echo base_url('Cloan/add_ofloan_person') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_person') ?> </a>
                <?php }?>
              
            </div>
        </div>

        <!-- Account List -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_person') ?> </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo display('name') ?></th>
                                        <th><?php echo display('address') ?></th>
                                        <th><?php echo display('phone') ?></th>
                                        <th class="text-right"><?php echo display('balance') ?></th>
                                        <th><?php echo display('action') ?>
                                        <?php echo form_open('Cloan/manage1_person', array('class' => 'form-inline', 'method' => 'post')) ?>
                                             <input type="hidden" name="all" value="all">
                                              <button type="submit" class="btn btn-success"><?php echo display('all') ?></button>
                                             <?php echo form_close() ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subtotal ='0.00';
                                    if ($person_list) {
                                      
                                        foreach ($person_list as $person) {
                                            $sql = 'SELECT (SELECT SUM(a.debit) FROM person_ledger a where a.person_id = "'.$person['person_id'].'") debit,
                                                    (SELECT SUM(b.credit) FROM person_ledger b WHERE b.person_id = "'.$person['person_id'].'") credit';
                                             $result = $this->db->query($sql)->result();
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo base_url('Csettings/person_ledger/' . $person['person_id']); ?>">
                                                        <?php echo html_escape($person['person_name']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo html_escape($person['person_address']); ?>
                                                </td>
                                                <td>
                                                    <?php echo html_escape($person['person_phone']); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    $balance = $result[0]->debit - $result[0]->credit;
                                                    echo $currency.' '.number_format($balance, '2', '.', ',');
                                                    $subtotal +=$balance;
                                                    ?>
                                                </td>
                                                <td>
                                                <?php if($this->permission1->method('manage1_person','read')->access()){ ?>
                                                    <a href="<?php echo base_url('Csettings/person_edit/' . $person['person_id']); ?>"class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="left" title="" data-original-title="Update">
                                                        <span class='glyphicon glyphicon-edit'></span>
                                                    </a>
                                                <?php }?>
                                                  <?php if($this->permission1->method('manage1_person','delete')->access()){ ?>
                                                    <a href="<?php echo base_url('Csettings/delete_office_loan/'.$person['person_id']) ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') ">
                                                        <span class='glyphicon glyphicon-remove'></span>
                                                    </a>
                                                  <?php }
                                        ?>
                                                </td>
                                            </tr>
                                        <?php }
                                        ?>
                                      
                                        <?php
                                    }
                                    ?>
                                </tbody>
                               <tfooter><tr><td class="text-center" colspan="3"><b><?php echo display('total')?></b></td><td class="text-right"><?php   if(($position==0)){
                                           echo  $currency.' '.number_format($subtotal, '2', '.', ',');
                                        }else{
                                            echo number_format($subtotal, '2', '.', ',').' '.$currency;
                                        } ?></td><td></td></tr></tfooter>
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

