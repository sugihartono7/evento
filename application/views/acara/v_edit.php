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

<?php //echo $xinha_java; ?>

<link href="<?php echo base_url(); ?>assets/css/themes/cupertino/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css" />

<section id="main-content">
	<section class="wrapper"> 
		 
		<h3><i class="fa fa-angle-right"></i> Edit Acara</h3>
		
		<a class="btn_add btn btn-default btn-sm" href="<?php echo base_url(); ?>acara/list/0/0">
		<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
		
		<div class="row mt">
			<div style="padding-left:5px;padding-left:5px" class="col-lg-12">
				<form method="post" class="form-horizontal style-form" name="frmAcara" id="frmAcara" action="<?php echo base_url(); ?>acara/edit/<?php echo $id; ?>/next" novalidate="novalidate">
					
					<div class="alert alert-danger hide">
						<a class="close" data-dismiss="alert" href="#">&times;</a>
						<span id="alertMessage">&nbsp;</span>
					</div>
					
					<div style="padding:30px 10px 10px 10px;" class="form-panel">
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Unit Bisnis<span class="red-star"> *</span></label>
							<div class="col-sm-3 required">
								<input type="hidden" value="<?php echo (isset($event[0]->department) ? $event[0]->department : ""); ?>" id="department" name="department" >
								<input type="text" class="form-control" readonly="" value="<?php echo (isset($event[0]->department) ? $event[0]->department : ""); ?>" id="departmentLbl" name="departmentLbl" >
							</div>
							<label class="col-sm-1 control-label-right">Divisi<span class="red-star"> *</span></label>
							<div class="col-sm-6 pad-right required">
								<input type="hidden" value="<?php echo (isset($event[0]->division_code) ? $event[0]->division_code : ""); ?>" id="divisionCode" name="divisionCode" >
								<input type="text" class="form-control" readonly="" value="<?php echo (isset($divisionDesc) ? $divisionDesc : ""); ?>" id="divisionCodeLbl" name="divisionCodeLbl" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Nomor</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" readonly="" value="<?php echo (isset($acaraHolder["event_no"]) ? $acaraHolder["event_no"] : $event[0]->event_no); ?>" id="eventNo" name="eventNo" maxlength="26">
							</div>
							
							<label class="col-sm-1 control-label-right">Tgl. Surat<span class="red-star"> *</span></label>
							<div class="col-sm-6 pad-right required">
								<input type="text" class="form-control" readonly="" id="letterDate" name="letterDate" value="<?php echo (isset($acaraHolder["letterDate"]) ? $acaraHolder["letterDate"] : $event[0]->letter_date); ?>"  maxlength="10">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Lampiran</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" value="<?php echo (isset($acaraHolder["attach"]) ? $acaraHolder["attach"] : (empty($event[0]->attach) ? "-" : $event[0]->attach)); ?>" id="attach" name="attach" maxlength="50">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Hal.<span class="red-star"> *</span></label>
							<div class="col-sm-10 required">
								<input type="text" class="form-control" value="<?php echo (isset($acaraHolder["about"]) ? $acaraHolder["about"] :  $event[0]->about); ?>" id="about" name="about" maxlength="255">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Keperluan</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" value="<?php echo (isset($acaraHolder["purpose"]) ? $acaraHolder["purpose"] :  $event[0]->purpose); ?>" id="purpose" name="purpose" maxlength="50">
							</div>
						</div>
						
						<div class="form-group"  id='div_toward'>
							<label class="col-sm-2 control-label">Kepada</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" value="<?php echo (isset($acaraHolder["toward"]) ? $acaraHolder["toward"] :  $event[0]->toward); ?>" id="toward" name="toward" maxlength="250">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Template<span class="red-star"> *</span></label>
							<div class="col-sm-3 required">
								<select id="templateCode" class="form-control" name="templateCode">
									<option value="">Pilih template..</option>
									<?php if (isset($acaraHolder["templateCode"])) { ?>
										<?php
											foreach($templates as $template) {
											  if ($acaraHolder["templateCode"] == $template->tmpl_code) $sel = "selected='selected'"; else $sel = "";
										?>
											<option <?php echo $sel; ?> value="<?php echo $template->tmpl_code; ?>"><?php echo $template->tmpl_name; ?></option>
										<?php
											}
										?>
									<?php } else { ?>
										<?php
											foreach($templates as $template) {
											  if ($event[0]->template_code == $template->tmpl_code) $sel = "selected='selected'"; else $sel = "";
										?>
											<option <?php echo $sel; ?> value="<?php echo $template->tmpl_code; ?>"><?php echo $template->tmpl_name; ?></option>
										<?php
											}
										?>
									<?php } ?>
								</select>
							</div>
							
							<label class="col-sm-2 control-label-right">Penandatangan I<span class="red-star"> *</span></label>
							<div class="col-sm-3 pad-right required">
								<select id="firstSignature" class="form-control" name="firstSignature">
									<?php echo $opts; ?>
								</select>
							</div>
						</div>	
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Notes</label>
							<div class="col-sm-10">
								<!-- <input type="text" class="form-control" value="<?php //echo (isset($acaraHolder["notes"]) ? $acaraHolder["notes"] : $event[0]->notes); ?>" id="notes" name="notes" maxlength="255"> -->
								<textarea class="form-control" rows="5" id="notes" name="notes" >
									
										<?php echo (isset($acaraHolder["notes"]) ? $acaraHolder["notes"] : $event[0]->notes); ?>
									
								</textarea>
							</div>
						</div>
						
						<div class="form-group">
						  <label class="col-sm-2 control-label">&nbsp;</label>
							<div class="col-sm-5">
								<input type="checkbox" <?php if ($isSameDate) echo "checked='checked'"; ?> id="isSameDate" name="isSameDate"> Daftar <b>TANGGAL</b> berlaku untuk semua tillcode dalam satu surat.
							</div>
							<div class="col-sm-5">
								<input type="checkbox" <?php if ($isSameLocation) echo "checked='checked'"; ?> id="isSameLocation" name="isSameLocation"> Daftar <b>LOKASI</b> berlaku untuk semua tillcode dalam satu surat.
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Acara Khusus</label>
							<label class="col-sm-1 control-label" style="width: 18px;">
								  <input type="checkbox" <?php if ($isSpecialEvent) echo "checked='checked'"; ?> id="isSpecialEvent" name="isSpecialEvent"> 
							</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" <?php if (!$isSpecialEvent) { ?> disabled="disabled" <?php } ?>
								value="<?php echo ($isSpecialEvent ? (isset($acaraHolder["specialEventDesc"]) ? $acaraHolder["specialEventDesc"] : $event[0]->special_event_desc) : ""); ?>"
								id="specialEventDesc" name="specialEventDesc" maxlength="50">
							</div>
						</div>
						
						<div class="divider"></div>
						
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnNext" type="submit" class="btn btn-theme02">
								<?php echo NEXT_CAPTION; ?></button> 
							</div>
						</div>
						
					</div><!-- /content-panel -->
						
				</div><!-- /content-panel -->
				
			</div><!-- /col-lg-12 -->			
			<!--	  </div> /row -->
			
			<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
			
		</form>   
		
	</div></section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/acara.val.js"></script>
