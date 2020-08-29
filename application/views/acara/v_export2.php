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
		
		<h3><i class="fa fa-angle-right"></i> Export Acara (User)</h3>
		
		<div class="row mt">
			<div style="padding-left:5px;padding-left:5px" class="col-lg-12">
				<form method="post" class="form-horizontal style-form" name="frmAcara" id="frmAcara" action="<?php echo base_url(); ?>acara/export-2/excel" novalidate="novalidate">
					
					<div class="alert alert-danger hide">
						<a class="close" data-dismiss="alert" href="#">&times;</a>
						<span id="alertMessage">&nbsp;</span>
					</div>
					
					<div style="padding:30px 10px 10px 10px;" class="form-panel">
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Divisi<span class="red-star"> *</span></label>
							<div class="col-sm-3 required">
								<select id="divisionCode" class="form-control" name="divisionCode">
									<option value="All">All</option>
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
							<label class="col-sm-2 control-label">Tahun<span class="red-star"> *</span></label>
							<div class="col-sm-3 required">
								<select id="year" class="form-control" name="year">
									<?php foreach($years as $year) { ?>
										<option value="<?php echo $year->year; ?>"><?php echo $year->year; ?></option>
									<?php } ?>
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
<!--
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/acara.export.val.js"></script>
-->