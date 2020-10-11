<?php
	$CI = & get_instance();
	$CI->load->model('Web_settings');
	$CI->load->model('Reports');
	$CI->load->model('Users');
	$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
	$users = $CI->Users->profile_edit_data();
	$out_of_stock = $CI->Reports->out_of_stock_count();
	
	?>
<header class="main-header">
	<a href="<?php echo base_url() ?>" class="logo">
		<!-- Logo -->
		<span class="logo-mini">
			<!--<b>A</b>BD-->
			<img src="<?php
				if (isset($Web_settings[0]['favicon'])) {
				    echo html_escape($Web_settings[0]['favicon']);
				}
				?>" alt="">
		</span>
		<span class="logo-lg">
			<!--<b>Admin</b>BD-->
			<img src="<?php
				if (isset($Web_settings[0]['logo'])) {
				    echo html_escape($Web_settings[0]['logo']);
				}
				?>" alt="">
		</span>
	</a>
	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top text-center">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<!-- Sidebar toggle button-->
			<span class="sr-only">Toggle navigation</span>
			<span class="pe-7s-keypad"></span>
		</a>
		<?php
			$urcolp = '0';
			if($this->uri->segment(2) =="gui_pos" ){
			  $urcolp = "gui_pos";
			}
			if($this->uri->segment(2) =="pos_invoice" ){
			  $urcolp = "pos_invoice";
			}
			
			 if($this->uri->segment(2) != $urcolp ){
			
			 if($this->permission1->method('new_invoice','create')->access()){
			
			
			 ?>
		<a href="<?php echo base_url('Cinvoice')?>" class="btn btn-success btn-outline"><i class="fa fa-balance-scale"></i> <?php  echo display('invoice') ?></a>
		<?php }?>
		<?php if($this->permission1->method('customer_receive','create')->access()){ ?>
		<a href="<?php echo base_url('accounts/customer_receive')?>" class="btn btn-success btn-outline"><i class="fa fa-money"></i> <?php echo display('customer_receive')?></a>
		<?php } ?>
		<?php if($this->permission1->method('supplier_payment','create')->access()){ ?>
		<a href="<?php echo base_url('accounts/supplier_payment')?>" class="btn btn-success btn-outline"><i class="fa fa-money" aria-hidden="true"></i> <?php echo display('supplier_payment')?></a>
		<?php } ?>
		<?php if($this->permission1->method('add_purchase','create')->access()){ ?>
		<a href="<?php echo base_url('Cpurchase')?>" class="btn btn-success btn-outline"><i class="ti-shopping-cart"></i> <?php echo display('purchase') ?></a>
		<?php }} ?>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown notifications-menu">
					<a href="<?php echo base_url('Creport/out_of_stock') ?>" >
					<i class="pe-7s-attention" title="<?php echo display('out_of_stock') ?>"></i>
					<span class="label label-danger"><?php echo html_escape($out_of_stock) ?></span>
					</a>
				</li>
				<!-- settings -->
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo base_url('Admin_dashboard/edit_profile') ?>"><i class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
						<li><a href="<?php echo base_url('Admin_dashboard/change_password_form') ?>"><i class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
						<li><a href="<?php echo base_url('Admin_dashboard/logout') ?>"><i class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
<aside class="main-sidebar">
	<!-- sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel text-center">
			<div class="image">
				<img src="<?php echo html_escape($users[0]['logo']) ?>" class="img-circle" alt="User Image">
			</div>
			<div class="info">
				<p><?php echo $this->session->userdata('user_name') ?></p>
				<a href="#"><i class="fa fa-circle text-success"></i> <?php echo display('online') ?></a>
			</div>
		</div>
		<!-- sidebar menu -->
		<ul class="sidebar-menu">
			<li class="<?php
				if ($this->uri->segment('1') == ("")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="<?php echo base_url() ?>"><i class="ti-dashboard"></i> <span><?php echo display('dashboard') ?></span>
				<span class="pull-right-container">
				<span class="label label-success pull-right"></span>
				</span>
				</a>
			</li>
			<!-- Invoice menu start -->
			<?php if($this->permission1->method('new_invoice','create')->access() || $this->permission1->method('manage_invoice','read')->access() || $this->permission1->method('pos_invoice','create')->access() || $this->permission1->method('gui_pos','create')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Cinvoice")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-balance-scale"></i><span><?php echo display('invoice') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('new_invoice','create')->access()){ ?>
					<li  class="treeview <?php
						if ($this->uri->segment('1') == ("Cinvoice") && $this->uri->segment('2') == ("")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cinvoice') ?>"><?php echo display('new_invoice') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('manage_invoice','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("manage_invoice")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cinvoice/manage_invoice') ?>"><?php echo display('manage_invoice') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('pos_invoice','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("pos_invoice")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cinvoice/pos_invoice') ?>"><?php echo display('pos_invoice') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('gui_pos','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("gui_pos")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cinvoice/gui_pos') ?>"><?php echo display('gui_pos') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- Invoice menu end -->
			<!-- Customer menu start -->
			<?php if($this->permission1->method('add_customer','create')->access() || $this->permission1->method('manage_customer','read')->access() || $this->permission1->method('credit_customer','read')->access() || $this->permission1->method('paid_customer','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Ccustomer")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-handshake-o"></i><span><?php echo display('customer') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_customer','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Ccustomer") && $this->uri->segment('2') == ("")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccustomer') ?>"><?php echo display('add_customer') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('manage_customer','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("manage_customer")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccustomer/manage_customer') ?>"><?php echo display('manage_customer') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('customer_ledger','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("customer_ledger_report")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccustomer/customer_ledger_report') ?>"><?php echo display('customer_ledger') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('credit_customer','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("credit_customer")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccustomer/credit_customer') ?>"><?php echo display('credit_customer') ?></a></li>
					<?php } ?> 
					<?php if($this->permission1->method('paid_customer','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("paid_customer")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccustomer/paid_customer') ?>"><?php echo display('paid_customer') ?></a></li>
					<?php } ?> 
					<?php if($this->permission1->method('customer_advance','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("customer_advance")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccustomer/customer_advance') ?>"><?php echo display('customer_advance') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php }?>
			<!-- Customer menu end -->
			<!-- Product menu start -->
			<?php if($this->permission1->method('create_product','create')->access() || $this->permission1->method('add_product_csv','create')->access() || $this->permission1->method('manage_product','read')->access() || $this->permission1->method('create_category','create')->access() || $this->permission1->method('manage_category','read')->access() || $this->permission1->method('add_unit','create')->access() || $this->permission1->method('manage_unit','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Cproduct") ||$this->uri->segment('1') == ("Cunit")|| $this->uri->segment('1') == ("Ccategory")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-bag"></i><span><?php echo display('product') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<!-- Category menu end -->
					<?php if($this->permission1->method('manage_category','create')->access() || $this->permission1->method('manage_category','read')->access()|| $this->permission1->method('manage_category','update')->access()|| $this->permission1->method('manage_category','delete')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Ccategory")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Ccategory') ?>"><?php echo display('category') ?></a></li>
					<?php } ?>
					<!-- Category menu end -->
					<!--Unit menu start--> 
					<?php if($this->permission1->method('manage_unit','create')->access() || $this->permission1->method('manage_unit','read')->access() || $this->permission1->method('manage_unit','delete')->access() || $this->permission1->method('manage_unit','update')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == "Cunit") {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cunit'); ?>"><?php echo display('unit'); ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('create_product','create')->access()){ ?>
					<li  class="treeview <?php
						if ($this->uri->segment('1') == ("Cproduct") && $this->uri->segment('2') == ("")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cproduct') ?>"><?php echo display('add_product') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('add_product_csv','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("add_product_csv")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cproduct/add_product_csv') ?>"><?php echo display('import_product_csv') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('manage_product','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("manage_product")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cproduct/manage_product') ?>"><?php echo display('manage_product') ?></a></li>
					<?php }?>
				</ul>
			</li>
			<?php }?>
			<!-- Product menu end -->
			<!-- --- supplier menu start -->
			<?php if($this->permission1->method('add_supplier','create')->access() || $this->permission1->method('manage_supplier','read')->access() || $this->permission1->method('supplier_ledger_report','read')->access() || $this->permission1->method('supplier_sales_details_all','read')->access()){?>
			<!-- Supplier menu start -->
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Csupplier")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-user"></i><span><?php echo display('supplier') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_supplier','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == "Csupplier" && $this->uri->segment('2') == "") {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Csupplier') ?>"><?php echo display('add_supplier') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('manage_supplier','read')->access()){ ?>
					<li class="treeview <?php
						if ( $this->uri->segment('2') == "manage_supplier") {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Csupplier/manage_supplier') ?>"><?php echo display('manage_supplier') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('supplier_ledger_report','read')->access()){ ?>
					<li  class="treeview <?php
						if ($this->uri->segment('2') == "supplier_ledger_report" || $this->uri->segment('2') == "supplier_ledger") {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Csupplier/supplier_ledger_report') ?>"><?php echo display('supplier_ledger') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('supplier_advance','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("supplier_advance")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csupplier/supplier_advance') ?>"><?php echo display('supplier_advance') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- Supplier menu end -->
			<!-- Purchase menu start -->
			<?php if($this->permission1->method('add_purchase','create')->access() || $this->permission1->method('manage_purchase','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Cpurchase")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-shopping-cart"></i><span><?php echo display('purchase') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_purchase','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Cpurchase") && $this->uri->segment('2') == ("")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cpurchase') ?>"><?php echo display('add_purchase') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('manage_purchase','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("manage_purchase")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cpurchase/manage_purchase') ?>"><?php echo display('manage_purchase') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- Purchase menu end -->  
			<!-- Quotation Menu Start -->
			<?php if($this->permission1->method('add_quotation','create')->access() || $this->permission1->method('manage_quotation','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Cquotation")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-book"></i><span><?php echo display('quotation') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_quotation','create')->access()){ ?>
					<li><a href="<?php echo base_url('Cquotation') ?>"><?php echo display('add_quotation') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('manage_quotation','read')->access()){ ?>
					<li><a href="<?php echo base_url('Cquotation/manage_quotation') ?>"><?php echo display('manage_quotation') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php }?>
			<!-- quotation Menu end -->

			<!-- Stock menu start -->
			<?php if($this->permission1->method('stock_report','read')->access() || $this->permission1->method('stock_report_sp_wise','read')->access() || $this->permission1->method('stock_report_pro_wise','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Creport")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-bar-chart"></i><span><?php echo display('stock') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('stock_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('1') == ("Creport") && $this->uri->segment('2') == ("")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Creport') ?>"><?php echo display('stock_report') ?></a></li>
					<?php }?>
				</ul>
			</li>
			<?php }?>
			<!-- Stock menu end -->


			<?php if($this->permission1->method('add_return','create')->access() || $this->permission1->method('return_list','read')->access() || $this->permission1->method('supplier_return_list','read')->access() || $this->permission1->method('wastage_return_list','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Cretrun_m")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-retweet" aria-hidden="true"></i><span><?php echo display('return'); ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_return','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('1') == ("Cretrun_m") && $this->uri->segment('2') == ("")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Cretrun_m') ?>"><?php echo display('return'); ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('return_list','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("return_list")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Cretrun_m/return_list') ?>"><?php echo display('stock_return_list') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('supplier_return_list','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("supplier_return_list")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Cretrun_m/supplier_return_list') ?>"><?php echo display('supplier_return_list') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('wastage_return_list','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("wastage_return_list")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Cretrun_m/wastage_return_list') ?>"><?php echo display('wastage_return_list') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- Report menu start -->
			<?php if($this->permission1->method('add_closing','create')->access() || $this->permission1->method('closing_report','read')->access() || $this->permission1->method('all_report','read')->access() || $this->permission1->method('todays_customer_receipt','read')->access() || $this->permission1->method('todays_sales_report','read')->access() || $this->permission1->method('retrieve_dateWise_DueReports','read')->access() || $this->permission1->method('todays_purchase_report','read')->access() || $this->permission1->method('purchase_report_category_wise','read')->access() || $this->permission1->method('product_sales_reports_date_wise','read')->access() || $this->permission1->method('sales_report_category_wise','read')->access() || $this->permission1->method('shipping_cost_report','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('2') == ("all_report") || $this->uri->segment('2') == ("todays_sales_report") || $this->uri->segment('2') == ("todays_purchase_report") || $this->uri->segment('2') == ("product_sales_reports_date_wise") || $this->uri->segment('2') == ("total_profit_report") || $this->uri->segment('2') == ("purchase_report_category_wise") || $this->uri->segment('2') == ("sales_report_category_wise") || $this->uri->segment('2') == ("filter_purchase_report_category_wise") || $this->uri->segment('2') == ("sales_report_category_wise") || $this->uri->segment('2') == ("todays_customer_receipt") || $this->uri->segment('2') == ("filter_sales_report_category_wise") || $this->uri->segment('2') == ("filter_customer_wise_receipt") || $this->uri->segment('2') == ("closing") || $this->uri->segment('2') == ("closing_report") || $this->uri->segment('2') == ("date_wise_closing_reports") || $this->uri->segment('2') == ("retrieve_dateWise_Shippingcost") || $this->uri->segment('2') == ("product_sales_search_reports") || $this->uri->segment('2') == ("user_sales_report") || $this->uri->segment('2') == ("retrieve_dateWise_DueReports") || $this->uri->segment('2') == ("sales_return") || $this->uri->segment('2') == ("supplier_return")|| $this->uri->segment('2') == ("retrieve_dateWise_tax")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-book"></i><span><?php echo display('report') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_closing','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("closing")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/closing') ?>"><?php echo display('closing') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('closing_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("closing_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/closing_report') ?>"><?php echo display('closing_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('all_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("all_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/all_report') ?>"><?php echo display('todays_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('todays_customer_receipt','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("todays_customer_receipt")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/todays_customer_receipt') ?>"><?php echo display('todays_customer_receipt') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('todays_sales_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("todays_sales_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/todays_sales_report') ?>"><?php echo display('sales_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('user_wise_sales_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("user_sales_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/user_sales_report') ?>"><?php echo display('user_wise_sales_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('retrieve_dateWise_DueReports','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("retrieve_dateWise_DueReports")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/retrieve_dateWise_DueReports') ?>"><?php echo display('due_report'); ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('shipping_cost_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("retrieve_dateWise_Shippingcost")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/retrieve_dateWise_Shippingcost') ?>"><?php echo display('shipping_cost_report'); ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('todays_purchase_report','read')->access()){ ?>
					<li><a href="<?php echo base_url('Admin_dashboard/todays_purchase_report') ?>"><?php echo display('purchase_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('purchase_report_category_wise','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("purchase_report_category_wise")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/purchase_report_category_wise') ?>"><?php echo display('purchase_report_category_wise') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('product_sales_reports_date_wise','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("product_sales_reports_date_wise")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/product_sales_reports_date_wise') ?>"><?php echo display('sales_report_product_wise') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('sales_report_category_wise','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("sales_report_category_wise")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/sales_report_category_wise') ?>"><?php echo display('sales_report_category_wise') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('invoice_return','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("sales_return")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/sales_return') ?>"><?php echo display('invoice_return') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('supplier_return','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("supplier_return")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/supplier_return') ?>"><?php echo display('supplier_return') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('tax_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("retrieve_dateWise_tax")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/retrieve_dateWise_tax') ?>"><?php echo display('tax_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('profit_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("total_profit_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Admin_dashboard/total_profit_report') ?>"><?php echo display('profit_report') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php }?>
			<!-- Report menu end -->
			<!--New Account start-->
			<?php if($this->permission1->method('show_tree','read')->access() || $this->permission1->method('supplier_payment','create')->access()|| $this->permission1->method('customer_receive','create')->access() || $this->permission1->method('debit_voucher','create')->access() || $this->permission1->method('credit_voucher','create')->access() || $this->permission1->method('aprove_v','read')->access() || $this->permission1->method('contra_voucher','create')->access() || $this->permission1->method('journal_voucher','create')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("accounts")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-money"></i><span><?php echo display('accounts') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('show_tree','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("show_tree")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/show_tree') ?>"><?php echo display('c_o_a'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('supplier_payment','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("supplier_payment")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/supplier_payment') ?>"><?php echo display('supplier_payment'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('customer_receive','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("customer_receive")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/customer_receive') ?>"><?php echo display('customer_receive'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('cash_adjustment','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("cash_adjustment")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/cash_adjustment') ?>"><?php echo display('cash_adjustment'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('debit_voucher','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("debit_voucher")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/debit_voucher') ?>"><?php echo display('debit_voucher') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('credit_voucher','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("credit_voucher")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/credit_voucher') ?>"><?php echo display('credit_voucher'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('contra_voucher','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("contra_voucher")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/contra_voucher') ?>"><?php echo display('contra_voucher'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('journal_voucher','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("journal_voucher")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/journal_voucher') ?>"><?php echo display('journal_voucher'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('aprove_v','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("aprove_v")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('accounts/aprove_v') ?>"><?php echo display('voucher_approval'); ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('ac_report','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("voucher_report") || $this->uri->segment('2') == ("cash_book") || $this->uri->segment('2') == ("bank_book") || $this->uri->segment('2') == ("general_ledger")|| $this->uri->segment('2') == ("trial_balance")|| $this->uri->segment('2') == ("profit_loss_report")|| $this->uri->segment('2') == ("cash_flow_report")|| $this->uri->segment('2') == ("inventory_ledger")|| $this->uri->segment('2') == ("coa_print")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href=""><?php echo display('report') ?>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('cash_book','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("cash_book")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/cash_book') ?>"><?php echo display('cash_book'); ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('inventory_ledger','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("inventory_ledger")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/inventory_ledger') ?>"><?php echo display('Inventory_ledger'); ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('bank_book','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("bank_book")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/bank_book') ?>"><?php echo display('bank_book'); ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('general_ledger','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("general_ledger")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/general_ledger') ?>"><?php echo display('general_ledger'); ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('trial_balance','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("trial_balance")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/trial_balance') ?>"><?php echo display('trial_balance'); ?></a></li>
							<?php } ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("profit_loss_report")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/profit_loss_report') ?>"><?php echo display('profit_loss'); ?></a></li>
							<?php if($this->permission1->method('cash_flow_report','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("cash_flow_report")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/cash_flow_report') ?>"><?php echo display('cash_flow'); ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('coa_print','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("coa_print")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('accounts/coa_print') ?>"><?php echo display('coa_print'); ?></a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- New Account End -->
			<!-- Bank menu start -->
			<?php if($this->permission1->method('add_bank','create')->access() || $this->permission1->method('bank_transaction','create')->access() || $this->permission1->method('bank_list','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('2') == ("index") || $this->uri->segment('2') == ("bank_list") || $this->uri->segment('2') == ("bank_ledger") || $this->uri->segment('2') == ("bank_transaction")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-briefcase"></i><span><?php echo display('bank') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_bank','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('1') == ("Csettings") && $this->uri->segment('2') == ("index")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csettings/index') ?>"><?php echo display('add_new_bank') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('bank_list','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("bank_list")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csettings/bank_list') ?>"><?php echo display('manage_bank') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('bank_transaction','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("bank_transaction")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csettings/bank_transaction') ?>"><?php echo display('bank_transaction') ?></a></li>
					<?php }?>
					<?php if($this->permission1->method('bank_ledger','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("bank_ledger")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csettings/bank_ledger') ?>"><?php echo display('bank_ledger') ?></a></li>
					<?php }?>
				</ul>
			</li>
			<?php } ?>
			<!-- Bank menu end -->
			<!-- Tax menu start -->
			<?php if($this->permission1->method('add_incometax','create')->access() || $this->permission1->method('manage_income_tax','read')->access()|| $this->permission1->method('tax_settings','create')->access() || $this->permission1->method('tax_report','read')->access() || $this->permission1->method('invoice_wise_tax_report','read')->access() || $this->permission1->method('tax_settings','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Caccounts") || $this->uri->segment('1') == ("Account_Controller") || $this->uri->segment('1') == ("Cpayment")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-money"></i><span><?php echo display('tax') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('tax_settings','create')->access()){ ?>       
					<li class="treeview <?php if ($this->uri->segment('2') == ("tax_settings")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Caccounts/tax_settings') ?>"><?php echo display('tax_settings') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('add_incometax','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("add_incometax")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Caccounts/add_incometax') ?>"><?php echo display('add_incometax') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('manage_income_tax','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("manage_income_tax")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Caccounts/manage_income_tax') ?>"><?php echo display('manage_income_tax') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('tax_report','read')->access()){ ?>    
					<li class="treeview <?php if ($this->uri->segment('2') == ("tax_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Caccounts/tax_report') ?>"><?php echo display('tax_report') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('invoice_wise_tax_report','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("invoice_wise_tax_report")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Caccounts/invoice_wise_tax_report') ?>"><?php echo display('invoice_wise_tax_report') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>

            <!-- Site menu start -->
			<?php 
				if($this->permission1->method('add_site','create')->access() || 
					$this->permission1->method('manage_site','read')->access()){
			?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Csite")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-building"></i><span><?php echo display('site') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_site','create')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("add_site")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csite/add_site') ?>"><?php echo display('add_site') ?></a></li>
					<?php }?>
                    <?php if($this->permission1->method('manage_site','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("manage_site")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csite/manage_site') ?>"><?php echo display('manage_site') ?></a></li>
					<?php }?>
				</ul>
			</li>
			<?php }?>
			<!-- Site menu end -->

			<!-- human resource management menu start -->
			<?php if($this->permission1->method('add_designation','create')->access() || $this->permission1->method('manage_designation','read')->access() || $this->permission1->method('add_employee','create')->access() || $this->permission1->method('manage_employee','read')->access() ||$this->permission1->method('add_person','create')->access() || $this->permission1->method('add_loan','create')->access() || $this->permission1->method('add_payment','create')->access() || $this->permission1->method('manage_person','read')->access()||$this->permission1->method('add_attendance','create')->access() || $this->permission1->method('manage_attendance','read')->access() || $this->permission1->method('attendance_report','read')->access() || $this->permission1->method('add_benefits','create')->access() || $this->permission1->method('manage_benefits','read')->access() || $this->permission1->method('add_salary_setup','create')->access() || $this->permission1->method('manage_salary_setup','read')->access() || $this->permission1->method('salary_generate','create')->access() || $this->permission1->method('manage_salary_generate','read')->access() || $this->permission1->method('salary_payment','create')->access() || $this->permission1->method('add_expense_item','create')->access() || $this->permission1->method('manage_expense_item','read')->access() || $this->permission1->method('add_expense','create')->access() || $this->permission1->method('manage_expense','read')->access() || $this->permission1->method('add_ofloan_person','create')->access() || $this->permission1->method('add_office_loan','create')->access() || $this->permission1->method('add_loan_payment','create')->access() || $this->permission1->method('manage_ofln_person','read')->access()){?>
			<!-- Supplier menu start -->
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Chrm") || $this->uri->segment('1') == ("Cattendance") || $this->uri->segment('1') == ("Cpayroll") || $this->uri->segment('1') == ("Cexpense") || $this->uri->segment('1') == ("Cloan") || $this->uri->segment('2') == ("add_person") || $this->uri->segment('2') == ("add_loan") || $this->uri->segment('2') == ("add_payment") || $this->uri->segment('2') == ("manage_person") || $this->uri->segment('2') == ("person_loan_edit") || $this->uri->segment('2') == ("person_loan_deails")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-users"></i><span><?php echo display('hrm_management') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('add_designation','create')->access() || $this->permission1->method('manage_designation','read')->access() || $this->permission1->method('add_employee','create')->access() || $this->permission1->method('manage_employee','read')->access()){?>
					<!-- Supplier menu start -->
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Chrm")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="fa fa-users"></i><span><?php echo display('hrm') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('add_designation','create')->access()){ ?>          
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_designation")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Chrm/add_designation') ?>"><?php echo display('add_designation') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('manage_designation','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_designation")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Chrm/manage_designation') ?>"><?php echo display('manage_designation') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('add_employee','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_employee")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Chrm/add_employee') ?>"><?php echo display('add_employee') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('manage_employee','read')->access()){ ?>        
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_employee")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Chrm/manage_employee') ?>"><?php echo display('manage_employee') ?></a></li>
							<?php } ?> 
						</ul>
					</li>
					<?php } ?>
					<!-- ================== Attendance menu start ================= -->
					<?php if(
							$this->permission1->method('add_attendance','create')->access() || 
							$this->permission1->method('manage_attendance','read')->access() || 
							$this->permission1->method('attendance_report','read')->access() ||
							$this->permission1->method('add_working_hour','create')->access() || 
							$this->permission1->method('manage_working_hour','read')->access()){?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Cattendance")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="fa fa-clock-o"></i><span><?php echo display('attendance') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('add_working_hour','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_working_hour")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cattendance/add_working_hour') ?>"><?php echo display('add_working_hour') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('manage_working_hour','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_working_hour")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cattendance/manage_working_hour') ?>"><?php echo display('manage_working_hour') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_attendance','create')->access()){ ?>       
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_attendance")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cattendance/add_attendance') ?>"><?php echo display('add_attendance') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('manage_attendance','read')->access()){ ?>   
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_attendance")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cattendance/manage_attendance') ?>"><?php echo display('manage_attendance') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('attendance_report','read')->access()){ ?>  
							<li class="treeview <?php if ($this->uri->segment('2') == ("attendance_report")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cattendance/attendance_report') ?>"><?php echo display('attendance_report') ?></a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
					<!-- ====================== Attendance menu end ================== -->
					<!-- ========================== Payroll menu start =================== -->
					<?php if($this->permission1->method('add_benefits','create')->access() || $this->permission1->method('manage_benefits','read')->access() || $this->permission1->method('add_salary_setup','create')->access() || $this->permission1->method('manage_salary_setup','read')->access() || $this->permission1->method('salary_generate','create')->access() || $this->permission1->method('manage_salary_generate','read')->access() || $this->permission1->method('salary_payment','create')->access()){?>
					<!-- Supplier menu start -->
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Cpayroll")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="fa fa-paypal"></i><span><?php echo display('payroll') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('add_benefits','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("Salarybenificial")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/Salarybenificial') ?>">
								<?php echo display('add_benefits') ?></a>
							</li>
							<?php }?>
							<?php if($this->permission1->method('manage_benefits','read')->access()){ ?>            
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_benefits")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/manage_benefits') ?>"><?php echo display('manage_benefits') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_salary_setup','create')->access()){ ?>          
							<li class="treeview <?php if ($this->uri->segment('2') == ("salary_setup_form")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/salary_setup_form') ?>"><?php echo display('add_salary_setup') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('manage_salary_setup','read')->access()){ ?> 
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_salary_setup")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/manage_salary_setup') ?>"><?php echo display('manage_salary_setup') ?></a></li>
							<?php }?> 
							<?php if($this->permission1->method('salary_generate','create')->access()){ ?>            
							<li class="treeview <?php if ($this->uri->segment('2') == ("salary_generate_form")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/salary_generate_form') ?>"><?php echo display('salary_generate') ?></a></li>
							<?php }?> 
							<?php if($this->permission1->method('manage_salary_generate','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_salary_generate")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/manage_salary_generate') ?>"><?php echo display('manage_salary_generate') ?></a></li>
							<?php }?> 
							<?php if($this->permission1->method('salary_payment','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("salary_payment")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cpayroll/salary_payment') ?>"><?php echo display('salary_payment') ?></a></li>
							<?php }?> 
						</ul>
					</li>
					<?php } ?>
					<!-- =============================== Payroll menu end =================== -->
					<!-- =======================   Expense menu start ========================= -->
					<?php if($this->permission1->method('add_expense_item','create')->access() || $this->permission1->method('manage_expense_item','read')->access() || $this->permission1->method('add_expense','create')->access() || $this->permission1->method('manage_expense','read')->access()){?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Cexpense")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="fa fa-credit-card"></i><span><?php echo display('expense') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php
								if($this->permission1->method('add_expense_item','create')->access()){ ?>
							<li class="treeview <?php  if ($this->uri->segment('2') == ("add_expense_item")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cexpense/add_expense_item') ?>"><?php echo display('add_expense_item') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('manage_expense_item','read')->access()){ ?>
							<li class="treeview <?php  if ($this->uri->segment('2') == ("manage_expense_item")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cexpense/manage_expense_item') ?>"><?php echo display('manage_expense_item') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_expense','create')->access()){ ?>
							<li class="treeview <?php  if ($this->uri->segment('2') == ("add_expense")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cexpense/add_expense') ?>"><?php echo display('add_expense') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('manage_expense','read')->access()){ ?>
							<li class="treeview <?php  if ($this->uri->segment('2') == ("manage_expense")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cexpense/manage_expense') ?>"><?php echo display('manage_expense') ?></a></li>
							<?php } ?>
							<?php if($this->permission1->method('expense_statement','read')->access()){ ?>
							<li  class="treeview <?php  if ($this->uri->segment('2') == ("expense_statement_form")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cexpense/expense_statement_form') ?>"><?php echo display('expense_statement') ?></a></li>
							<?php }?>
						</ul>
					</li>
					<?php }?>
					<!-- ========================== Expense menu end ========================== -->
					<!-- Office loan start -->
					<?php if($this->permission1->method('add_ofloan_person','create')->access() || $this->permission1->method('add_office_loan','create')->access() || $this->permission1->method('add_loan_payment','create')->access() || $this->permission1->method('manage_ofln_person','read')->access()){?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Cloan") && $this->uri->segment('2') == ("add_ofloan_person") || $this->uri->segment('2') == ("manage_ofln_person") || $this->uri->segment('2') == ("person_ledger") || $this->uri->segment('2') == ("add_office_loan") || $this->uri->segment('2') == ("add_loan_payment") || $this->uri->segment('2') == ("person_edit")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="fa fa-university" aria-hidden="true"></i>
						<span><?php echo display('office_loan') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('add_ofloan_person','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_ofloan_person")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cloan/add_ofloan_person') ?>"><?php echo display('add_person') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_office_loan','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_office_loan")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cloan/add_office_loan') ?>"><?php echo display('add_loan') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_loan_payment','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_loan_payment")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cloan/add_loan_payment') ?>"><?php echo display('add_payment') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('manage_ofln_person','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_ofln_person")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cloan/manage_ofln_person') ?>"><?php echo display('manage_person') ?></a></li>
							<?php }?>
						</ul>
					</li>
					<?php }?>
					<!-- Office loan end -->
					<!--  Personal loan start -->
					<?php if($this->permission1->method('add_person','create')->access() || $this->permission1->method('add_loan','create')->access() || $this->permission1->method('add_payment','create')->access() || $this->permission1->method('manage_person','read')->access()){?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("add_person") || $this->uri->segment('2') == ("add_loan") || $this->uri->segment('2') == ("add_payment") || $this->uri->segment('2') == ("manage_person") || $this->uri->segment('2') == ("person_loan_edit") || $this->uri->segment('2') == ("person_loan_deails")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="fa fa-user-circle" aria-hidden="true"></i>
						<span><?php echo display('personal_loan') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('add_person','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_person")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Csettings/add_person') ?>"><?php echo display('add_person') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_loan','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_loan")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Csettings/add_loan') ?>"><?php echo display('add_loan') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_payment','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_payment")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Csettings/add_payment') ?>"><?php echo display('add_payment') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('manage_person','read')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_person")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Csettings/manage_person') ?>"><?php echo display('manage_loan') ?></a></li>
							<?php }?>
						</ul>
					</li>
					<?php }?>
					<!-- loan end -->
				</ul>
			</li>
			<?php } ?>
			<!-- Human resource management menu end -->
			<!-- service menu start -->
			<?php if($this->permission1->method('create_service','create')->access() || $this->permission1->method('manage_service','read')->access() || $this->permission1->method('service_invoice','create')->access() || $this->permission1->method('manage_service_invoice','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Cservice")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="fa fa-asl-interpreting"></i><span><?php echo display('service') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('create_service','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Cservice") && $this->uri->segment('2') == ("")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cservice') ?>"><?php echo display('add_service') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('manage_service','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("manage_service")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cservice/manage_service') ?>"><?php echo display('manage_service') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('service_invoice','create')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("service_invoice_form")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cservice/service_invoice_form') ?>"><?php echo display('service_invoice') ?></a></li>
					<?php } ?>
					<?php if($this->permission1->method('manage_service_invoice','read')->access()){ ?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("manage_service_invoice")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>"><a href="<?php echo base_url('Cservice/manage_service_invoice') ?>"><?php echo display('manage_service_invoice') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- Comission start -->
			<?php if($this->permission1->method('commission','create')->access() || $this->permission1->method('commission','read')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('2') == ("commission") || $this->uri->segment('2') == ("commission_generate")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-layout-grid2"></i><span><?php echo display('commission') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<?php if($this->permission1->method('commission','read')->access()){ ?>
					<li class="treeview <?php if ($this->uri->segment('2') == ("commission")){
						echo "active";
						} else {
						echo " ";
						}?>"><a href="<?php echo base_url('Csettings/commission') ?>"><?php echo display('generate_commission') ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<!-- Comission end -->
			<!-- Software Settings menu start -->
			<?php if($this->permission1->method('manage_company','read')->access() ||$this->permission1->method('manage_company','create')->access() || $this->permission1->method('add_user','create')->access() || $this->permission1->method('manage_user','read')->access() || $this->permission1->method('add_language','create')->access() || $this->permission1->method('add_currency','create')->access() || $this->permission1->method('soft_setting','create')->access() || $this->permission1->method('add_role','create')->access() ||$this->permission1->method('role_list','read')->access() || $this->permission1->method('user_assign','create')->access() || $this->permission1->method('sms_configure','create')->access()){?>
			<li class="treeview <?php
				if ($this->uri->segment('1') == ("Company_setup") || $this->uri->segment('1') == ("User") || $this->uri->segment('1') == ("Cweb_setting") || $this->uri->segment('1') == ("Language") || $this->uri->segment('1') == ("Currency") || $this->uri->segment('1') == ("Permission")|| $this->uri->segment('1') == ("Csms") || $this->uri->segment('1') == ("Backup_restore")) {
				    echo "active";
				} else {
				    echo " ";
				}
				?>">
				<a href="#">
				<i class="ti-settings"></i><span><?php echo display('settings') ?></span>
				<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
				</span>
				</a>
				<ul class="treeview-menu">
					<!-- Software Settings menu start -->
					<?php if($this->permission1->method('manage_company','read')->access() ||$this->permission1->method('manage_company','create')->access() || $this->permission1->method('add_user','create')->access() || $this->permission1->method('manage_user','read')->access() || $this->permission1->method('add_language','create')->access() || $this->permission1->method('add_currency','create')->access() || $this->permission1->method('soft_setting','create')->access() || $this->permission1->method('back_up','create')->access() || $this->permission1->method('back_up','read')->access() || $this->permission1->method('restore','create')->access() || $this->permission1->method('sql_import','create')->access()){?>
					<li class="treeview <?php
						if ($this->uri->segment('1') == ("Company_setup") || $this->uri->segment('1') == ("User") || $this->uri->segment('1') == ("Cweb_setting") || $this->uri->segment('1') == ("Language") || $this->uri->segment('1') == ("Currency")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="ti-settings"></i> <span><?php echo display('web_settings') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('manage_company','read')->access() || $this->permission1->method('manage_company','update')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_company")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Company_setup/manage_company') ?>"><?php echo display('manage_company') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('add_user','create')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('1') == ("User") && $this->uri->segment('2') == ("")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('User') ?>"><?php echo display('add_user') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('manage_user','read')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("manage_user")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('User/manage_user') ?>"><?php echo display('manage_users') ?> </a></li>
							<?php }?>
							<?php if($this->permission1->method('add_language','create')->access() || $this->permission1->method('add_language','update')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('1') == ("Language") && $this->uri->segment('2') == ("")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Language') ?>"><?php echo display('language') ?> </a></li>
							<?php }?>
							<?php if($this->permission1->method('add_currency','create')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('1') == ("Currency") && $this->uri->segment('2') == ("")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Currency') ?>"><?php echo display('currency') ?> </a></li>
							<?php }?>
							<?php if($this->permission1->method('soft_setting','create')->access() || $this->permission1->method('soft_setting','update')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('1') == ("Cweb_setting") && $this->uri->segment('2') == ("")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cweb_setting') ?>"><?php echo display('setting') ?> </a></li>
							<?php }?>
							<?php if($this->permission1->method('mail_setting','create')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('1') == ("Cweb_setting") && $this->uri->segment('2') == ("mail_setting")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cweb_setting/mail_setting') ?>"><?php echo display('mail_setting') ?> </a></li>
							<?php }?>
							<li class="treeview <?php if ($this->uri->segment('1') == "Cweb_setting" && $this->uri->segment('2') == "app_setting"){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Cweb_setting/app_setting') ?>"><?php echo display('app_setting') ?> </a></li>
						</ul>
					</li>
					<?php }?>
					<!-- Role permission start -->
					<?php if($this->permission1->method('add_role','create')->access() ||$this->permission1->method('role_list','read')->access() || $this->permission1->method('user_assign','create')->access()){?>
					<li  class="treeview <?php
						if ($this->uri->segment('1') == ("Permission")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="ti-key"></i> <span><?php echo display('role_permission') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('add_role','create')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("add_role")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Permission/add_role')?>"><?php echo display('add_role') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('role_list','read')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("role_list")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Permission/role_list')?>"><?php echo display('role_list') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('user_assign','create')->access()){?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("user_assign")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Permission/user_assign')?>"><?php echo display('user_assign_role')?></a></li>
							<?php }?>
						</ul>
					</li>
					<?php }?>
					<!-- Role permission End -->
					<?php if($this->permission1->method('sms_configure','create')->access()){?>
					<!-- sms menu start -->
					<li class="treeview <?php if ($this->uri->segment('1') == ("Csms")) { echo "active";}else{ echo " ";}?>">
						<a href="#">
						<i class="fa fa-comments"></i> <span><?php echo display('sms'); ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<li class="treeview <?php if ($this->uri->segment('2') == ("configure")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Csms/configure')?>"><?php echo display('sms_configure'); ?></a></li>
						</ul>
					</li>
					<?php }?>
					<!-- sms menu end -->
					<!-- Synchronizer setting start -->
					<?php if($this->permission1->method('back_up','create')->access() || $this->permission1->method('back_up','read')->access() || $this->permission1->method('restore','create')->access() || $this->permission1->method('sql_import','create')->access()){?>
					<li class="treeview <?php
						if ($this->uri->segment('2') == ("form") || $this->uri->segment('2') == ("synchronize") || $this->uri->segment('1') == ("Backup_restore")) {
						    echo "active";
						} else {
						    echo " ";
						}
						?>">
						<a href="#">
						<i class="ti-reload"></i>  <span><?php echo display('data_synchronizer') ?></span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
							<?php if($this->permission1->method('restore','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("restore_form")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Backup_restore/restore_form') ?>"><?php echo display('restore') ?></a></li>
							<?php }?>
							<?php if($this->permission1->method('sql_import','create')->access()){ ?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("import_form")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Backup_restore/import_form') ?>"><?php echo display('import') ?></a></li>
							<?php }?>
							<li class="treeview <?php if ($this->uri->segment('2') == ("backup_create")){
								echo "active";
								} else {
								echo " ";
								}?>"><a href="<?php echo base_url('Backup_restore/download') ?>"><?php echo display('backup') ?></a></li>
						</ul>
					</li>
					<?php }?>
					<!-- Synchronizer setting end -->
				</ul>
			</li>
			<?php }?>
			<!-- Software Settings menu end -->
		</ul>
	</div>
	<!-- /.sidebar -->
</aside>