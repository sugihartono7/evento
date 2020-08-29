<!-- **********************************************************************************************************************************************************
	MAIN SIDEBAR MENU
*********************************************************************************************************************************************************** -->
<!--sidebar start-->
<aside>
	<div id="sidebar"  class="nav-collapse " style="overflow: hidden;" tabindex="5000">
		<!-- sidebar menu start-->
		<ul class="sidebar-menu" id="nav-accordion">
			
			<p class="centered"><a href="#"><img src="<?php echo base_url(); ?>assets/img/yg_red.png"  ></a></p>
			<h5 class="centered">Welcome, <?php echo $this->session->userdata['event_logged_in']['username']; ?></h5>
			
			<li class="sub-menu">
				<a href="javascript:;" class="<?php if (isset($menu_active)){echo $menu_active;}else echo ""; ?>">
					<i class="fa fa-file-text"></i>
					<span>Master</span>
				</a>
				<ul class="sub">
					<li><a href="<?php echo base_url(); ?>brand/list" style="<?php if (isset($menu_brand_active)){echo $menu_brand_active;}else echo ""; ?>">Brand</a></li>
					<li><a href="<?php echo base_url(); ?>store/list" style="<?php if (isset($menu_store_active)){echo $menu_store_active;}else echo ""; ?>">Store</a></li>
					<li><a href="<?php echo base_url(); ?>supplier/list" style="<?php if (isset($menu_supplier_active)){echo $menu_supplier_active;}else echo ""; ?>">Supplier</a></li>
					<li><a href="<?php echo base_url(); ?>pic/list" style="<?php if (isset($menu_pic_active)){echo $menu_pic_active;}else echo ""; ?>">Supplier PIC</a></li>
					<li><a href="<?php echo base_url(); ?>template/list" style="<?php if (isset($menu_template_active)){echo $menu_template_active;}else echo ""; ?>">Template</a></li>
					<li><a href="<?php echo base_url(); ?>tillcode/list" style="<?php if (isset($menu_tillcode_active)){echo $menu_tillcode_active;}else echo ""; ?>">Tillcode</a></li>
					<li><a href="<?php echo base_url(); ?>upload/add/0" style="<?php if (isset($menu_import_active)){echo $menu_import_active;}else echo ""; ?>">Import Data</a></li>
				</ul>
			</li>
			
			<li class="sub-menu">
				<a href="javascript:;" class="<?php if (isset($trans_active)){echo $trans_active;}else echo ""; ?>">
					<i class="fa fa-book"></i>
					<span>Transaksi</span>
				</a>
				<ul class="sub">
					<li><a href="<?php echo base_url(); ?>acara/add/new" style="<?php if (isset($menu_input_active)){echo $menu_input_active;}else echo ""; ?>">Input Acara</a></li>
					<li><a href="<?php echo base_url(); ?>acara/list/0/0" style="<?php if (isset($menu_acarabaru_active)){echo $menu_acarabaru_active;}else echo ""; ?>">Acara Terbaru</a></li>
					<li><a href="<?php echo base_url(); ?>acara/list/0/1" style="<?php if (isset($menu_daftar_active)){echo $menu_daftar_active;}else echo ""; ?>">Daftar Acara</a></li>
					<li><a href="<?php echo base_url(); ?>acara/export" style="<?php if (isset($menu_export_active)){echo $menu_export_active;}else echo ""; ?>">Export Acara</a></li>
					<li><a href="<?php echo base_url(); ?>acara/export-2" style="<?php if (isset($menu_export2_active)){echo $menu_export2_active;}else echo ""; ?>">Export Acara (User)</a></li>
				</ul>
			</li>
			</div>
			</aside>
			<!--sidebar end-->			