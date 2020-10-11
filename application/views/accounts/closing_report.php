
<!-- Closing Report Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('closing_report') ?></h1>
            <small><?php echo display('account_closing_report') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('report') ?></a></li>
                <li class="active"><?php echo display('closing_report') ?></li>
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



        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body"> 
                        <?php echo form_open('Admin_dashboard/date_wise_closing_reports/', array('class' => 'form-inline', 'method' => 'get')) ?>
                        <?php $today = date('Y-m-d'); ?>
                        <label class="select"><?php echo display('search_by_date') ?>: <?php echo display('from') ?></label>
                        <input type="text" name="from_date"  value="<?php echo html_escape($today); ?>" class="datepicker form-control"/>
                        <label class="select"><?php echo display('to') ?></label>
                        <input type="text" name="to_date" class="datepicker form-control" value="<?php echo html_escape($today); ?>"/>
                        <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                        <a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                        <?php echo form_close() ?>		            
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('closing_report') ?> </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="printableArea">
                             <table class="print-table" width="100%">
                                                
                                                <tr>
                                                    <td align="left" class="print-table-tr">
                                                        <img src="<?php echo html_escape($software_info[0]['logo']);?>" alt="logo">
                                                    </td>
                                                    <td align="center" class="print-cominfo">
                                                        <span class="company-txt">
                                                            <?php echo html_escape($company[0]['company_name']);?>
                                                           
                                                        </span><br>
                                                        <?php echo html_escape($company[0]['address']);?>
                                                        <br>
                                                        <?php echo html_escape($company[0]['email']);?>
                                                        <br>
                                                         <?php echo html_escape($company[0]['mobile']);?>
                                                        
                                                    </td>
                                                   
                                                     <td align="right" class="print-table-tr">
                                                        <date>
                                                        <?php echo display('date')?>: <?php
                                                        echo date('d-M-Y');
                                                        ?> 
                                                    </date>
                                                    </td>
                                                </tr>            
                                   
                                </table>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <caption class="text-center"><?php
                                    $from_date = (!empty($from_date)?$from_date:'');
                                     if($from_date){?><?php echo display('closing_report').'('.display('from').' '?>{from_date} <?php echo display('to').' '?>{to_date})
                                        <?php }?></caption>
                                    <thead>
                                        <tr>
                                            <th><?php echo display('sl') ?></th>
                                            <th><?php echo display('date') ?></th>
                                          
                                            <th class="text-right"><?php echo display('cash_in') ?></th>
                                            <th class="text-right"><?php echo display('cash_out') ?></th>
                                            <th class="text-right"><?php echo display('balance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($daily_closing_data) {
                                            ?>
                                            <?php $i = 1;
                                            foreach ($daily_closing_data as $row) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><?php echo html_escape($row['final_date']); ?></td>
                                                    <td align="right"><?php
                                                        echo (($position == 0) ? "$currency " : " $currency");

                                                        echo html_escape(number_format($row['cash_in'], 2, '.', ','));
                                                        ?></td>
                                                    <td align="right"><?php
                                                        echo (($position == 0) ? "$currency " : " $currency");
                                                        echo html_escape(number_format($row['cash_out'], 2, '.', ','));
                                                        ?></td>
                                                    <td align="right"><?php
                                                echo (($position == 0) ? "$currency " : " $currency");

                                                echo html_escape(number_format($row['cash_in_hand'], 2, '.', ','));
                                                        ?></td>

                                                </tr>
                                                <?php $i++;
                                            }
                                            ?>
    <?php
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-right"><?php echo html_escape($links) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Closing Report End -->