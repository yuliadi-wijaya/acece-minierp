<?php
$CI = & get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('supplier') ?></h1>
            <small><?php echo display('supplier_advance') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('supplier') ?></a></li>
                <li class="active"><?php echo display('supplier_advance') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
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
            <div class="col-sm-5">
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <div class="panel-body">
                            <div bgcolor='#e4e4e4' text='#ff6633' link='#666666' vlink='#666666' alink='#ff6633' >
                                <table border="0" width="100%">
                                    <tr>
                                        <td>

                                            <table border="0" width="100%">
                                                
                                                <tr>
                                                    <td align="center" >
                                                        {company_info}
                                                        <span>
                                                            {company_name}
                                                        </span><br>
                                                        {address}<br>
                                                        {mobile}<br>
                                                        {/company_info}
                                                        
                                                    </td>
                                                </tr>
                                                
                                                
                                                <tr>
                                                    <td align="center"><b>{supplier_name}</b><br>
                                                        <?php if ($address) { ?>
                                                            {address}<br>
                                                        <?php } ?>
                                                        <?php if ($mobile) { ?>
                                                            {mobile}
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center"><nobr>
                                                    <date>
                                                        <?php echo  display('date')?>: <?php
                                                        echo date('d-M-Y');
                                                        ?> 
                                                    </date>
                                                </nobr></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong><?php echo display('receipt_no'); ?> : {receipt_no}</strong></td>
                                    </tr>
                                </table>

                                <table border="1" width="100%">
                                    <tr>
                                  
                                   <th class="text-center"><?php echo display('type'); ?></th>
                                   <th class="text-center"><?php echo display('amount'); ?></th>
                                    </tr>
                                              
                                    <tr>
                           
                         <td align="center"><nobr><?php if($details[0]['Debit'] > 0){
                             $status = 'Payment';}else{
                        	$status = 'Receive';
                        }
                        echo $status;
                        ?></nobr></td>
                        <td align="center"><nobr><?php echo ($status == 'Payment')?$details[0]['Debit']:$details[0]['Credit'];?></nobr></td>
                                   
                                    </tr>
                                    
                                      
                                   
                                </table>
                               
                                </td>
                                </tr>
                                <tr><td><?php echo $details[0]['Narration'];?></td></tr>
                                <tr>{company_info}
                                    <td>Powered  By: <a href="{website}"><strong>{company_name}</strong></a></td>
                                     {/company_info}
                                </tr>
                                </table>


                            </div>


                        </div>
                    </div>

                    <div class="panel-footer text-left">
                        <a  class="btn btn-danger" href="<?php echo base_url('Csupplier/supplier_advance'); ?>"><?php echo display('cancel') ?></a>
                        <a  class="btn btn-info" href="#" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></a>

                    </div>
                </div>
            </div>
        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->

