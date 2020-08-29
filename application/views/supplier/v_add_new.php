
<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Supplier</h3>
		
		<a href="<?php echo base_url(); ?>supplier/list" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
		
		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<div class="form-panel" style="padding:30px 10px 10px 10px;">
					
					<form action="<?php echo base_url(); ?>supplier/do_add_new" id="frm" name="frm" class="form-horizontal style-form" method="post">
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Kode Gold</label>
							<div class="col-sm-10">
								<input type="text" name="txt_gold" id="txt_gold" class="form-control">
								<label id="txt_gold-error" for="txt_gold" style="color:red"></label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Kode YGHODS</label>
							<div class="col-sm-10">
								<input type="text" name="txt_yghods" id="txt_yghods" class="form-control">
								<label id="txt_yghods-error" for="txt_yghods" style="color:red"></label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Nama Supplier</label>
							<div class="col-sm-10">
								<input type="text" name="txt_nama" id="txt_nama" class="form-control">
							
							</div>
						</div>
						
						<div class="divider"></div>
						
						<div class="form-group">
							<div class="col-sm-12">
								<button class="btn btn-theme02" type="submit">
									<i class="fa fa-save"></i> <?php echo SAVE_CAPTION; ?>
								</button>
								<button class="btn btn-success" type="reset">
									<i class="fa fa-rotate-left"></i>
									<?php echo CANCEL_CAPTION; ?>
								</button>
							</div>
						</div>
						
					</form>
						
						
				</div><!-- /content-panel -->
			</div><!-- /col-lg-12 -->			
						
						
						
						
												