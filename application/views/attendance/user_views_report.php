<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('attendance') ?></h1>
            <small><?php echo display('datewise_report') ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('attendance') ?></a></li>
                <li class="active"><?php echo display('datewise_report') ?></li>
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

       

        <!-- Manage Category -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                          <h4><?php echo display('datewise_report')?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
  
                <table width="100%" class="datatable table table-striped table-bordered table-hover" style="
                     display: block;
                     overflow: scroll;
                     overflow-x: auto;
                     white-space: nowrap;
                ">
                    <?php $end = new DateTime($to_date);
                            $end = $end->modify( '+1 day' );$period = new DatePeriod(
                            new DateTime($from_date),
                            new DateInterval('P1D'),
                            $end
                        );$end = $end->modify( '-1 day' );
                        ?>
                    <caption><center><?php echo display('from').' -'.$from_date.'=>'.display('to').' -'.$to_date?></center></caption>
                    <thead class="thtable">
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('employee_name') ?></th>
                            <th><?php echo display('site') ?></th>
                            <th><?php echo "type" ?></th>
                            <?php foreach ($period as $p) { 
                            ?>

                            <th><?php echo date_format($p,"Y-m-d"); ?></th>
                            
                           
                            <?php } ?> 
                            <th><?php echo "sum" ?></th>
                        </tr>
                    </thead>
                    <tbody class="thtable">
                    
                        <?php if (!empty($result)) 
                            
                        { ?>

                            <?php $sl = 1; ?>
                            
                            <?php foreach ($result as $que) { 
                         ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td rowspan="4"><?php echo $sl; ?></td>
                                    <td rowspan="4"><?php echo html_escape(array_values($que)[0]->first_name).' '.html_escape(array_values($que)[0]->last_name); ?></td>
                                    <td rowspan="4"><?php echo html_escape(array_values($que)[0]->name); ?></td>

                                    <td><?php echo "Basic"; $sum_basic = 0; ?></td>
                                   
                                    <?php foreach ($period as $p) { 
                                        $sum_basic+=$que[date_format($p,"Y-m-d")]->basic ? $que[date_format($p,"Y-m-d")]->basic : 0 ;
                                     ?>
                                     <td><?php echo $que[date_format($p,"Y-m-d")]->basic ? $que[date_format($p,"Y-m-d")]->basic : 0; ?></td>
                                    <?php  if ($p == $end) { ?>
                                        <td><?php echo $sum_basic  ?></td>
                                        
                                        <?php }?> 
                                    <?php } ?> 

                                    
                                
                                
                                </tr>

                                <tr>
                                <td><?php echo "Meal"; $sum_meal = 0; ?></td>
                                   
                                   <?php foreach ($period as $p) { 
                                       $sum_meal+=$que[date_format($p,"Y-m-d")]->meal ? $que[date_format($p,"Y-m-d")]->meal : 0 ;
                                    ?>
                                    <td><?php echo $que[date_format($p,"Y-m-d")]->meal ? $que[date_format($p,"Y-m-d")]->meal : 0; ?></td>
                                   <?php  if ($p == $end) { ?>
                                       <td><?php echo $sum_meal  ?></td>
                                       
                                       <?php }?> 
                                   <?php } ?> 
                                </tr>

                                <tr>
                                <td><?php echo "OT"; $sum_ot = 0; ?></td>
                                   
                                   <?php foreach ($period as $p) { 
                                       $sum_ot+=$que[date_format($p,"Y-m-d")]->ot ? $que[date_format($p,"Y-m-d")]->ot : 0 ;
                                    ?>
                                    <td><?php echo $que[date_format($p,"Y-m-d")]->ot ? $que[date_format($p,"Y-m-d")]->ot : 0; ?></td>
                                   <?php  if ($p == $end) { ?>
                                       <td><?php echo $sum_ot  ?></td>
                                       
                                       <?php }?> 
                                   <?php } ?> 
                                </tr>

                                <tr>
                                <td><?php echo "Meal2"; $sum_meal2 = 0; ?></td>
                                   
                                   <?php foreach ($period as $p) { 
                                       $sum_meal2+=$que[date_format($p,"Y-m-d")]->meal2 ? $que[date_format($p,"Y-m-d")]->meal2 : 0 ;
                                    ?>
                                    <td><?php echo $que[date_format($p,"Y-m-d")]->meal2 ? $que[date_format($p,"Y-m-d")]->meal2 : 0; ?></td>
                                   <?php  if ($p == $end) { ?>
                                       <td><?php echo $sum_meal2  ?></td>
                                       
                                       <?php }?> 
                                   <?php } ?> 
                                </tr>

                                    
                               
                                <?php $sl++; ?>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
            
</div>
                    </div>
                </div>
            </div>
 
        </div>
    </section>
</div>



