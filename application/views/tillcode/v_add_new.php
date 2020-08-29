
<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Tillcode</h3>
		
		<a href="<?php echo base_url(); ?>tillcode/list" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
		
		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<div class="form-panel" style="padding:30px 10px 10px 10px;">
					
					<form action="<?php echo base_url(); ?>tillcode/do_add_new" id="frm" name="frm" class="form-horizontal style-form" method="post">
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Tillcode</label>
							<div class="col-sm-10">
								<input type="text" name="txt_tillcode" id="txt_tillcode" class="form-control">
								<label id="txt_tillcode-error" for="txt_tillcode" style="color:red"></label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Article Code</label>
							<div class="col-sm-10">
								<input type="text" name="txt_article" id="txt_article" class="form-control">
								<label id="txt_article-error" for="txt_article" style="color:red"></label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<input type="text" name="txt_desc" id="txt_desc" class="form-control">
								<label id="txt_desc-error" for="txt_desc" style="color:red"></label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Art</label>
							<div class="col-sm-10">
								<select class="form-control">
									<option>Normal</option>
									<option>Obral</option>
								</select>	
							</div>
						</div>
						<br>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Cat</label>
							<div class="col-sm-10">
								<select class="form-control">
									<option>D1</option>
									<option>D2</option>
									<option>D3</option>
									<option>D4</option>
								</select>	
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

						
						
						
						
												