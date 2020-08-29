
<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Template</h3>
		<a href="<?php echo base_url(); ?>template/list" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
		
		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<div class="form-panel" style="padding:30px 10px 10px 10px;">
					<?php
						
						echo "<img src='".base_url()."assets/img/yg_red.png' /><br />";
						foreach ($list as $r) {
							echo $r->header;
							echo $r->footer;
							echo $r->notes;
						}

					?>
					
				</div><!-- /content-panel -->
			</div><!-- /col-lg-12 -->			
						
						
						
						
												