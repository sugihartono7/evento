<style>
	/* overwrite */
	.alert {
	  padding: 10px;
	}
	/* end overwrite */
	.red-star {
		color: #FF0000;
	}
	.help-inline {
		color: #a94442;
	}
</style>

<section id="main-content">
	<section class="wrapper"> 
		
		<h3><i class="fa fa-angle-right"></i> Import Master Data</h3>
		
		
		<div class="row mt">
			<div style="padding-left:5px;padding-left:5px" class="col-lg-12">
				<form method="post" class="form-horizontal style-form" name="fupload" id="fupload" action="<?php echo base_url(); ?>upload/do_add" enctype="multipart/form-data">
					
					
					<div style="padding:30px 10px 10px 10px;" class="form-panel">
						<!-- <div class="form-group">
							<label class="col-sm-2 control-label">Jenis Import</label>
							<div class="col-sm-10">
								<select class="form-control" name="cb_jenis">
									<option value='brand'>Brand</option>
									<option value='supplier'>Supplier</option>
									<option value='cabang'>Cabang</option>
								</select>
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-2 control-label">Pilih File</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" required value="" id="txt_file" name="txt_file" maxlength="50" style="padding-bottom:35px">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-12 control-label"><i>untuk hasil yang optimal, file maksimal 2Mb dengan ekstensi .xls, jika lebih dari 2Mb silakan dipisahkan</i></label>
						</div>
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnNext" type="submit" class="btn btn-theme02">Import</button> 
							</div>
						</div>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
					</div><!-- /content-panel -->
						
				</div><!-- /content-panel -->
				
			</div><!-- /col-lg-12 -->			
			<!--	  </div> /row -->
			
		</form>   
		
	</div></section>
