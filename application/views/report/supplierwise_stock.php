
<!-- Stock List Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('stock_report') ?></h1>
            <small><?php echo display('all_stock_report') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('stock') ?></a></li>
                <li class="active"><?php echo display('stock_report') ?></li>
            </ol>
        </div>
    </section>

     <section class="content">

       

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title text-right">
                           
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                           
                            <div class="table-responsive"  id="printableArea">
                               <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="checkList">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('sl') ?></th>
                                            <th class="text-center"><?php echo display('product_name') ?></th>
                                            <th class="text-center"><?php echo display('product_model') ?></th>
                                             <th class="text-center"><?php echo display('supplier_name') ?></th>
                                            <th class="text-center"><?php echo display('sell_price') ?></th>
                                            <th class="text-center">Purchase Price</th>
                                            <th class="text-center"><?php echo display('in_qnty') ?></th>
                                            <th class="text-center"><?php echo display('out_qnty') ?></th>
                                            <th class="text-center"><?php echo display('stock') ?></th>
                                            <th class="text-center"><?php echo display('stock_sale')?></th>
                                            <th class="text-center"><?php echo display('stock_purchase_price')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tfoot>
                                            <tr>
                <th colspan="8">Total:</th>
                <th id="stockqty"></th>
                  <th></th>  <th></th> 
            </tr>
                                            
                                        </tfoot> 
                                
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
       
    </section>
</div>

