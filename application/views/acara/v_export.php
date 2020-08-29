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

<link href="<?php echo base_url(); ?>assets/css/themes/cupertino/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css" />


<section id="main-content">
	<section class="wrapper"> 
		
		<h3><i class="fa fa-angle-right"></i> Export Acara</h3>
		
		<div class="row mt">
			<div style="padding-left:5px;padding-left:5px" class="col-lg-12">
				<form method="post" class="form-horizontal style-form" name="frmAcara" id="frmAcara" action="<?php echo base_url(); ?>acara/export/excel" novalidate="novalidate">
					
					<div class="alert alert-danger hide">
						<a class="close" data-dismiss="alert" href="#">&times;</a>
						<span id="alertMessage">&nbsp;</span>
					</div>
					
					<div style="padding:30px 10px 10px 10px;" class="form-panel">
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Divisi<span class="red-star"> *</span></label>
							<div class="col-sm-3 required">
								<select id="divisionCode" class="form-control" name="divisionCode">
									<option value="">Pilih divisi..</option>
									<?php
										foreach($divisions as $division) {
									?>
										<option value="<?php echo $division->division_code; ?>"><?php echo $division->division_desc; ?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Nasional / Jateng<span class="red-star"> *</span></label>
							<div class="col-sm-3">
								<select id="nasionalJateng" class="form-control" name="nasionalJateng">
									<option value="Nasional">Nasional</option>
									<option value="Jateng">Jateng</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">User</label>
							<div class="col-sm-3">
								<select id="firstSignature" class="form-control" name="firstSignature">
									<?php if (isset($opts)) echo $opts; else ?>
									<option value="">Pilih MD..</option>
								</select>
							</div>
						</div>
						
						<div class="divider"></div>
						
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnNext" type="submit" class="btn btn-theme02">
								<?php echo EXPORT_CAPTION; ?></button> 
							</div>
						</div>
						
					</div><!-- /content-panel -->
						
				</div><!-- /content-panel -->
				
			</div><!-- /col-lg-12 -->			
			<!--	  </div> /row -->
			
		</form>   
		
	</div></section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/acara.export.val.js"></script>
