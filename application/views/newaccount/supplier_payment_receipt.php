<?php
$CI = & get_instance();
$CI->load->model('Web_settings');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>
<!--its for pos style css start--> 



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
  <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('accounts') ?></h1>
            <small><?php echo display('supplier_payment') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li class="active"><?php echo display('supplier_payment') ?></li>
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
            <div class="col-sm-4">
                <div class="panel panel-bd">
                    <div id="printableArea">
                        <div class="panel-body">
                            <div bgcolor='#e4e4e4' text='#ff6633' link='#666666' vlink='#666666' alink='#ff6633' class="phdiv" >
                                <table border="0" width="100%">
                                    <tr>
                                        <td>

                                            <table border="0" width="100%">
                                                
                                                <tr>
                                                    <td align="center">
                                                        {company_info}
                                                        <span class="company-txt">
                                                            {company_name}
                                                        </span><br>
                                                        {address}<br>
                                                        {mobile}<br>
                                                        {/company_info}
                                                        
                                                    </td>
                                                </tr>
                                                
                                                
                                                <tr>
                            <td align="center"><b><?php echo  html_escape($supplier_info[0]['supplier_name']);?></b><br>
                                <?php if ($supplier_info[0]['address']) { ?>
                                    <?php echo  html_escape($supplier_info[0]['address']);?><br>
                                <?php } ?>
                                <?php if ($supplier_info[0]['mobile']) { ?>
                                   <?php echo  html_escape($supplier_info[0]['mobile']);?>
                                <?php } ?>
                            </td>
                                                </tr>
                                                <tr>
                                                    <td align="center"><nobr>
                                                    <date>
                                                        <?php echo  display('date')?>: <?php echo  html_escape($payment_info[0]['VDate'])?> 
                                                    </date>
                                                </nobr></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><?php echo display('voucher_no'); ?> : <?php echo  html_escape($payment_info[0]['VNo'])?></td>
                                    </tr>
                                    <tr>
                                    <td class="text-left"><?php echo display('payment_type'); ?> : <?php echo  'Payment';?></td>
                                    </tr>
                                    <tr>
                                    <td class="text-left"><?php echo display('amount'); ?> : <?php echo  html_escape($payment_info[0]['Debit']);?></td>
                                    </tr>
                                     <tr>
                                    <td class="text-left"><?php echo display('remark'); ?> : <?php echo  html_escape($payment_info[0]['Narration']);?></td>
                                    </tr>
                                </table>

                               
                               
                                </td>
                                 <tr>
                                    
                                    <td> <?php echo display('paid_by')?>: <?php echo  $this->session->userdata('user_name');?>

                                        <div  class="psigpart">
                                        <?php echo display('signature') ?>
                                          
                                    </div></td>
                                   
                                </tr>
                                </tr>
                                <tr>{company_info}
                                    <td>Powered  By: <a href="{website}">{company_name}</a></td>
                                     {/company_info}
                                </tr>
                                </table>


                            </div>


                        </div>
                    </div>

                    <div class="panel-footer text-left">
                        <a  class="btn btn-danger" href="<?php echo base_url('accounts/supplier_payment'); ?>"><?php echo display('cancel') ?></a>
                        <a  class="btn btn-info" href="#" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></a>

                    </div>
                </div>
            </div>
        </div>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->

