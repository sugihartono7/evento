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
	.ui-autocomplete-loading {
		background: white url('<?php echo base_url(); ?>assets/images/ui-anim_basic_16x16.gif') right center no-repeat;
	}
	.al-right {
		text-align: right;
	}
	.al-center {
		text-align: center;
	}
</style>

<?php //echo $xinha_java; ?>

<link href="<?php echo base_url(); ?>assets/css/themes/cupertino/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	var arrStore = [];
	var arrLocation = [];
	var arrCategory = [];
</script>

<section id="main-content">
	<section class="wrapper"> 
		
		<h3><i class="fa fa-angle-right"></i> Edit Acara</h3>
		
		<a id="btnBack" class="btn_add btn btn-default btn-sm" href="<?php echo base_url(); ?>acara/edit/<?php echo $id; ?>">
		<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
		
		<div class="row mt">
			<div style="padding-left:5px;padding-left:5px" class="col-lg-12">
				<form method="post" class="form-horizontal style-form" name="frmAcaraNext" id="frmAcaraNext" action="<?php echo base_url(); ?>acara/update"  novalidate="novalidate">
					
					<div class="alert alert-danger hide">
						<a class="close" data-dismiss="alert" href="#">&times;</a>
						<span id="alertMessage">&nbsp;</span>
					</div>
					
					<div style="padding:30px 10px 10px 10px;" class="form-panel">
						<div class="form-group">
							<label class="col-sm-2 control-label">Tillcode<span class="red-star"> *</span></label>
							<div class="col-sm-10 required">
								<input type="text" class="form-control" id="tillcode" name="tillcode">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Acara<span class="red-star"> *</span></label>
							<div class="col-sm-10 required">
								<!-- <input type="text" class="form-control" id="notes" name="notes" maxlength="200"> -->
								<textarea class="form-control" id="notes" rows="5" name="notes" ></textarea>
							</div>
						</div>
				
						<div class="form-group">
							<label class="col-sm-2 control-label">Supplier<span class="red-star"> *</span></label>
							<div class="col-sm-5 required">
								<input type="text" class="form-control" id="supplierCode" name="supplierCode">
								<label id='pic_label'></label>
								<input type="hidden" class="form-control" id="txt_pic" name="txt_pic">
							</div>
							<label class="col-sm-1 control-label-right">Brand</label>
							<div class="col-sm-4 pad-right required">
								<input type="text" class="form-control" id="brandCode" name="brandCode">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Kota<span class="red-star"> *</span></label>
							<div class="col-sm-5 required">
								<select name="cbKota" class="form-control" id="cbKota">
									
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Kategori<span class="red-star"> *</span></label>
							<div class="col-sm-10 required">
								<select id="categoryCode" class="form-control" name="categoryCode">
									<option value="">Pilih kategori..</option>
									<?php
										foreach($categories as $category) {
									?>
										<option value="<?php echo $category->category_desc; ?>"><?php echo $category->category_desc; ?></option>
									<?php
										  echo "<script type='text/javascript'>";
										  echo "arrCategory[\"" . $category->category_desc . "\"] = \"" . $category->category_code . "\"";
										  echo "</script>";
										}
									?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Jenis Pertanggungan<span class="red-star"> *</span></label>
							<div class="col-sm-3 required">
								<select id="kindOfResponsibility" name="kindOfResponsibility"  class="form-control">
									<option value="5050" <?php if ($responsibilityDefault == "5050") echo "selected='selected'"; ?>>YDS 50% : Supplier 50%</option>
									<option value="4060" <?php if ($responsibilityDefault == "4060") echo "selected='selected'"; ?>>YDS 40% : Supplier 60%</option>
									<option value="-1">Tanpa Pertanggungan</option>
									<option value="0">Custom</option>
								</select>
							</div>
							
							<label class="col-sm-1 control-label-right">Tipe Margin<span class="red-star"> *</span></label>
							<div class="col-sm-1 required">
								<select id="isPkp"  class="form-control">
									<option value="1">PKP</option>
									<option value="0">NPKP</option>
								</select>
							</div>
							
							<label class="col-sm-1 control-label-right">Margin<span class="red-star"> *</span></label>
							<div class="col-sm-1 pad-right required">
								<input type="text" class="form-control" id="margin" name="margin">
							</div>
							
							<label class="col-sm-1 control-label-right">
							  SP <input type="checkbox" id="cbSp" name="cbSp">
							</label>
							<div class="col-sm-2 pad-right required">
								<input type="text" class="form-control" id="sp" name="sp" disabled="disabled">
							</div>
						</div>
						<div id="responsibilityHolder" style="display: none;">
							<div class="form-group">
								<label class="col-sm-2 control-label">Pert. Supplier<span class="red-star"> *</span></label>
								<div class="col-sm-1 required">
									<input type="text" class="form-control" id="supplierResponsibility" name="supplierResponsibility">
								</div>
								
								<label class="col-sm-1 control-label-right">Pert. Yogya<span class="red-star"> *</span></label>
								<div class="col-sm-1 pad-right required">
									<input type="text" class="form-control" id="ydsResponsibility" name="ydsResponsibility">
								</div>
							</div>
						</div>
						
						<!--
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Brutto Margin</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="bruttoMargin" name="bruttoMargin">
							</div>
							<label class="col-sm-1 col-sm-1 control-label-right">Net Margin</label>
							<div class="col-sm-6 pad-right">
								<input type="text" class="form-control" id="netMargin" name="netMargin">
							</div>
						</div>
						-->
						
						<div class="divider"></div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Tanggal</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="eventStartDate" name="eventStartDate"  maxlength="10">
							</div>
							<label class="col-sm-1 control-label-right">s/d</label>
							<div class="col-sm-6 pad-right">
								<input type="text" class="form-control" id="eventEndDate" name="eventEndDate"  maxlength="10">
							</div>
						</div>
						
						<!--
						<div class="form-group">
							<div class="col-sm-6">
								<input type="checkbox" id="sameDate" name="sameDate" <?php #if ($isSameDate) echo "checked='checked'"; ?>> &nbsp; <b>Daftar tanggal berlaku untuk semua tillcode dalam satu surat.</b>
							</div>
						</div>
						-->
						
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnAddDate" type="button" class="btn btn-warning">
								<i class="fa fa-plus"></i> <?php echo ADD_DATE_CAPTION; ?></button>		
							</div>
						</div>
						
						<section id="unseen">
							<div class="add_detail table-responsive">
								<table class="table table-bordered table-striped table-condensed" id="datatableY">
									<thead>
										<tr>
										  <?php if (!$isSameDate) { ?>
											<th class="al-center">Tillcode</th>
										   <?php } ?>
											<th class="al-center">Tanggal</th>
											<th class="al-center">s/d Tanggal</th>
											<?php if ($isExc == "Y") { ?>
											<th class="al-right">Harga Faktur</th>
											<th class="al-right">Harga Jual</th>
											<?php } ?>
											<th colspan="2" class="action al-center" style="width:10%;">Action</th>
										</tr>
									</thead>
									<tbody>
											<?php echo $dateRows; ?>
									</tbody>
								</table>
							</div><!-- end div responsive -->
						</section>
						
						<div class="divider"></div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Lokasi</label>
							<div class="col-sm-3">
								<select id="locationCode" class="form-control" name="locationCode">
									<option value="">Pilih lokasi..</option>
									<?php
										foreach($locations as $location) {
									?>
									<option value="<?php echo $location->loc_desc; ?>"><?php echo $location->loc_desc; ?></option>
									<?php
											echo "<script type='text/javascript'>";
											echo "arrLocation['" . $location->loc_desc . "'] = '" . $location->loc_code . "'";
											echo "</script>";
										}
									?>
								</select>
							</div>
							<label class="col-sm-1 control-label-right">Cabang</label>
								<div class="col-sm-6 pad-right" id='div_store'>
						<!--	<div class="col-sm-6 pad-right">
								<input type="text" class="form-control" id="storeCode" name="storeCode">
									<?php
										/*echo "<script type='text/javascript'>";
										foreach($stores as $store) {
											echo "arrStore[\"" . $store->store_desc . "\"] = \"" . $store->store_code . "\";";
										}
										echo "</script>";*/
									?>
							</div>-->
								</div>

								<div class="col-sm-12 pad-right" id='div_checkboxes'>
								<div class="pull-right">
									<button id="btn_check" type="button" class="btn btn-primary btn-sm">
									<i class="fa fa-check"></i> Check All</button>
									
									<button id="btn_uncheck" type="button" class="btn btn-primary btn-sm">
									<i class="fa fa-check"></i> Uncheck All</button>

									<a id="trigger_modal_cabang" 
										type="button" 
										class="btn btn-primary btn-sm btn_add_cabang"
										data-toggle='modal'
										data-target='#modal_add_cabang' >
									<i class="fa fa-plus"></i> New</a>
								</div>  
							</div>

						</div>
						


						<!--
						<div class="form-group">
							<div class="col-sm-6">
								<input type="checkbox" id="sameLocation" name="sameLocation" <?php #if ($isSameLocation) echo "checked='checked'"; ?>> &nbsp; <b>Daftar lokasi berlaku untuk semua tillcode dalam satu surat.</b>
							</div>
						</div>
						-->
						
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnAddLocation" type="button" class="btn btn-warning">
								<i class="fa fa-plus"></i> <?php echo ADD_LOCATION_CAPTION; ?></button>
								<!--
								<?php //if (!$isSameLocation) { ?>
								  <button id="btnCopyLocation" type="button" class="btn btn-primary">
								  <i class="fa fa-copy"></i> <?php //echo COPY_LOCATION_CAPTION; ?></button>
								<?php //} ?>
								-->
							</div>
						</div>
						
						<section id="unseen">
							<div class="add_detail table-responsive">
								<table class="table table-bordered table-striped table-condensed" id="datatableZ">
									<thead>
										<tr>
										  <?php if (!$isSameLocation) { ?>
											<th class="al-center">Tillcode</th>
										  <?php } ?>
											<th class="al-center">Lokasi</th>
											<th class="al-center">Cabang</th>
											<th colspan="2" class="action al-center" style="width:10%;">Action</th>
										</tr>
									</thead>
									<tbody>
											<?php echo $locationRows; ?>
									</tbody>
								</table>
							</div><!-- end div responsive -->
						</section>
						
						
						<div class="divider"></div>
						
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnPoolTillcode" type="button" class="btn btn-warning">
								<i class="fa fa-plus"></i> <?php echo POOL_TILLCODE_CAPTION; ?></button>		
							</div>
						</div>
						
						<section id="unseen">
							<div class="add_detail table-responsive">
								<table class="table table-bordered table-striped table-condensed" id="datatableX">
									<thead>
										<tr>
											<th class="al-center">Acara</th>
											<th class="al-center">Tillcode</th>
											<th class="al-center">Supplier</th>
											<th class="al-center">Kota</th>
											<th class="al-center">Kategori</th>
											<th class="al-center">Pert. Supp</th>
											<th class="al-center">Pert. Yogya</th>
											<th class="al-center">Tipe Margin</th>
											<th class="al-center">Margin</th>
											<th class="al-center">SP</th>
											<th colspan="2" class="action al-center" style="width:10%;">Action</th>
										</tr>
									</thead>
									<tbody>
											<?php echo $tillcodeRows; ?>
									</tbody>
								</table>
							</div><!-- end div responsive -->
						</section>
				
					</div>
					<!-- /.form-panel -->
					
					
					<div style="padding:30px 10px 10px 10px;" class="form-panel">
						
						<div class="form-group">
							<div class="col-sm-12" style="float:right;">
								<button id="btnReset" type="reset" class="btn btn-success">
									<i class="fa fa-rotate-left"></i>
								<?php echo CANCEL_CAPTION; ?></button>
								
								<button id="btnSubmit" type="submit" class="btn btn-theme02">
								<i class="fa fa-save"></i> <?php echo SAVE_CAPTION; ?></button> 
							</div>
						</div>
					
					</div>
					<!-- /.form-panel -->
					
				</div><!-- /content-panel -->
				
			</div><!-- /col-lg-12 -->			
			<!--	  </div> /row -->
			
			<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
			
		</form>   
		
	</div></section>

<!-- back confirmation -->
<div id="backConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 480px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="backConfirmationLabel" class="modal-title">Alert</h4>
            </div>
            <div class="modal-body">
                <p>
					<b>Data belum disimpan!</b> <br><br>
					Dengan menekan tombol 'Back' anda akan kehilangan data yang belum disimpan. <br>
					Apakah anda ingin melanjutkan?
                </p>
                <p>&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary backConfirmOk">&nbsp;&nbsp; Yes &nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">&nbsp;&nbsp; No &nbsp;&nbsp;</button>
            </div>
        </div>
    </div>
</div>

<!-- date edit form -->
<div id="editForm3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalEditForm3Label" class="modal-title">Edit Data Tanggal</h4>
            </div>
            <div class="modal-body">
                <p>
					
					<div class="form-group" id="tillcode3_e_holder">
						<label class="col-sm-2 control-label">Tillcode<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="tillcode3_e" name="tillcode3_e">
						</div>
					</div> 
					<div class="form-group">
						<label class="col-sm-2 control-label">Tanggal</label>
						<div class="col-sm-4" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="eventStartDate_e" name="eventStartDate_e"  maxlength="10">
						</div>
						<label class="col-sm-1 control-label" style="text-align: center;">s/d</label>
						<div class="col-sm-5" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="eventEndDate_e" name="eventEndDate_e"  maxlength="10">
						</div>
					</div>
					
					<?php if ($isExc == "Y") { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Harga Faktur</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="eventHargaFaktur_e" name="eventHargaFaktur_e"  maxlength="12">
						</div>
						<label class="col-sm-2 control-label" style="text-align: center;">Harga Jual</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="eventHargaJual_e" name="eventHargaJual_e"  maxlength="12">
						</div>
					</div>
					<?php } ?>
											
                </p>
                <p><input type="hidden" name="idToUpdate3" id="idToUpdate3" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmOk3">&nbsp;&nbsp; OK &nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>

<!-- location edit form -->
<div id="editForm2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalEditForm2Label" class="modal-title">Edit Data Lokasi</h4>
            </div>
            <div class="modal-body">
                <p>
					
					<div class="form-group" id="tillcode2_e_holder">
						<label class="col-sm-2 control-label">Tillcode<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="tillcode2_e" name="tillcode2_e">
						</div>
					</div> 
					<div class="form-group">
						<label class="col-sm-2 control-label">Lokasi<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<select id="locationCode_e" class="form-control" name="locationCode_e">
								<option value="">Pilih lokasi..</option>
								<?php
									foreach($locations as $location) {
								?>
								<option value="<?php echo $location->loc_desc; ?>"><?php echo $location->loc_desc; ?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
			
					<div class="form-group">
						<label class="col-sm-2 control-label">Cabang<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="storeCode_e" name="storeCode_e">
						</div>
					</div>
					
                </p>
                <p><input type="hidden" name="idToUpdate2" id="idToUpdate2" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmOk2">&nbsp;&nbsp; OK &nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>

<!-- pool tillcode edit form -->
<div id="editForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalEditFormLabel" class="modal-title">Edit Data Tillcode</h4>
            </div>
            <div class="modal-body">
                <p>
                    
					<div class="form-group">
						<label class="col-sm-2 control-label">Acara<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="notes_e" name="notes_e" maxlength="200">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Supplier<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="supplierCode_e" name="supplierCode_e">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Kota<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<select name="cbKota_e" class="form-control" id="cbKota_e">
								
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label-right">Kategori<span class="red-star"> *</span></label>
						<div class="col-sm-10 required" style="margin-bottom: 6px;">
							<select id="categoryCode_e" class="form-control" name="categoryCode_e">
								<option value="">Pilih kategori..</option>
								<?php
									foreach($categories as $category) {
								?>
									<option value="<?php echo $category->category_desc; ?>"><?php echo $category->category_desc; ?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Tillcode<span class="red-star"> *</span></label>
						<div class="col-sm-6 pad-right required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" readonly="readonly" id="tillcode_e" name="tillcode_e">
						</div>
						<label class="col-sm-1 control-label-right">
						  SP <input type="checkbox" id="cbSp_e" name="cbSp_e">
						</label>
						<div class="col-sm-3 pad-right required" style="margin-bottom: 6px;">
							<input type="text" class="form-control" id="sp_e" name="sp_e" disabled="disabled">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Jenis Pertanggungan<span class="red-star"> *</span></label>
						<div class="col-sm-9 required" style="margin-bottom: 6px;">
							<select id="kindOfResponsibility_e" name="kindOfResponsibility_e"  class="form-control">
								<option value="5050">YDS 50% : Supplier 50%</option>
								<option value="4060">YDS 40% : Supplier 60%</option>
								<option value="-1">Tanpa Pertanggungan</option>
								<option value="0">Custom</option>
							</select>
						</div>
					</div>
					
					<div id="responsibilityHolder_e" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">Pert. Supplier<span class="red-star"> *</span></label>
							<div class="col-sm-4 required" style="margin-bottom: 6px;">
								<input type="text" class="form-control" id="supplierResponsibility_e" name="supplierResponsibility_e">
							</div>
							
							<label class="col-sm-2 control-label" style="text-align: right;">Pert. Yogya<span class="red-star"> *</span></label>
							<div class="col-sm-4 required" style="margin-bottom: 6px;">
								<input type="text" class="form-control" id="ydsResponsibility_e" name="ydsResponsibility_e">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipe Margin<span class="red-star"> *</span> &nbsp;</label>
						<div class="col-sm-4 required">
							<select id="isPkp_e"  class="form-control">
								<option value="1">PKP</option>
								<option value="0">NPKP</option>
							</select>
						</div>
						
						<label class="col-sm-2 control-label" style="text-align: right;">Margin<span class="red-star"> *</span> &nbsp;</label>
						<div class="col-sm-4 required">
							<input type="text" class="form-control" id="margin_e" name="margin_e">
						</div>
					</div>
					
                </p>
                <p><input type="hidden" name="idToUpdate" id="idToUpdate" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmOk">&nbsp;&nbsp; OK &nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>

<div id="modal_add_cabang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 480px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="backConfirmationLabel" class="modal-title">Add Location</h4>
            </div>
            <div class="modal-body">
                <p>
					<div class="form-group">
						<label class="col-sm-3 control-label">Lokasi<span class="red-star"> *</span></label>
						<select id="locationCodeAdd" class="form-control" name="locationCodeAdd">
							<option value="">Pilih lokasi..</option>
							<?php
								foreach($locations as $location) {
							?>
							<option value="<?php echo $location->loc_desc; ?>"><?php echo $location->loc_desc; ?></option>
							<?php
									echo "<script type='text/javascript'>";
									echo "arrLocation[\"" . $location->loc_desc . "\"] = \"" . $location->loc_code . "\"";
									echo "</script>";
								}
							?>
						</select>
						<br>
						<label class="col-sm-3 control-label">Cabang<span class="red-star"> *</span></label>
					
						<input type="text" class="form-control" id="storeCode" name="storeCode">
							<?php
								echo "<script type='text/javascript'>";
								foreach($stores as $store) {
									echo "arrStore[\"" . $store->store_desc . "\"] = \"" . $store->store_code . "\";";
								}
								echo "</script>";
							?>
							
					</div>
                </p>
                <p>&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id='btn_add_cabang'>&nbsp;&nbsp; Add Location &nbsp;&nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">&nbsp;&nbsp; Cancel &nbsp;&nbsp;</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="isExc" value="<?php echo $isExc; ?>">
<input type="hidden" id="cntX" value="<?php echo $cntX; ?>">
<input type="hidden" id="cntY" value="<?php echo $cntY; ?>">
<input type="hidden" id="cntZ" value="<?php echo $cntZ; ?>">

<input type="hidden" id="todo" value="edit">
<input type="hidden" id="id" value="<?php echo $id; ?>">
<input type="hidden" id="division" value="<?php echo $division; ?>">
<input type="hidden" id="isSameDate" value="<?php echo $isSameDate; ?>">
<input type="hidden" id="isSameLocation" value="<?php echo $isSameLocation; ?>">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/autoNumeric.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/acara.js"></script>