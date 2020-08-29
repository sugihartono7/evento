

<script>
	$(function() {
		$("#divisionCode-error").hide();
		$('#btn_filter').click(function(){
			var department = $("#department").val();
			var divisionCode = $("#divisionCode").val();
			var is_printed = $("#is_printed").val();
			
			if (divisionCode==null || divisionCode==""){
				$("#divisionCode-error").show();
			}else {
				location.href="<?php echo base_url();?>"+"acara/list/0/"+is_printed+"/"+department+"/"+divisionCode;
			}
            

        });
	});
</script>

<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Acara <?php echo $title; ?></h3>
		
		<a href="<?php echo base_url(); ?>acara/add" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-plus-square"></i> <?php echo ADD_CAPTION; ?></a>
		

		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<?php echo $msg; ?>
				<div class="form-panel" style="padding:10px;">
					<section id="unseen">
						<?php
							if ($this->session->userdata['event_logged_in']['role']==2) {
						?>
							<form method="post" class="form-horizontal style-form" name="" id="" action="#" >
								<input type="hidden" value="<?php echo $is_printed;?>" name='is_printed' id='is_printed' />
								<div class="form-group">
									<label class="col-sm-2 control-label">Unit Bisnis<span class="red-star"> *</span></label>
									<div class="col-sm-3 required">
										<select id="department" class="form-control" name="department">
											<option <?php echo ('Fashion'==$dept?'selected':''); ?> value="Fashion">Fashion</option>
										</select>
									</div>

									<label class="col-sm-1 control-label-right">Divisi<span class="red-star"> *</span></label>
									<div class="col-sm-5 pad-right required">
										<select id="divisionCode" class="form-control" name="divisionCode">
											<option value="">Pilih divisi..</option>
											<?php
												foreach($divisions as $division) {
												  	echo "<option value='".$division->division_code."' ".($division->division_code==$div?'selected':'').">".$division->division_desc."</option>";
												}
											?>
										</select>
										<span id="divisionCode-error" class="help-inline">Divisi harus diisi.</span>
									</div>

									<div class="col-sm-1" style="float:right;">
										<button id="btn_filter" type="button" class="btn btn-theme02">
										Filter</button> 
									</div>
								</div>
								
							</form>
							<hr>
						<?php
							}
						?>
						<div class="table-responsive">

                            <table class="table table-bordered table-condensed" id="datatable">
								<thead>
									<tr>
										<th>id</th>
										<th>No</th>
										<th>No Surat</th>
										<th>Kode Supp</th>
										<th>Brand</th>
										<th>Tujuan</th>
										<th>Hal</th>
										<th>Created Date</th>
										<th>Created By</th>
										<th class="action">Action</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div><!-- end div responsive -->
					</section>
										
										
				</div><!-- /content-panel -->
			</div><!-- /col-lg-4 -->			
		<!--	  	</div> /row -->
										
						 				
<!-- delete confirmation -->
<div id="deleteConfirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalDeleteConfirmLabel" class="modal-title">Konfirmasi Penghapusan</h4>
            </div>
            <div class="modal-body">
                <p>
                    Surat dengan nomor <span id="letterNumber" style="font-weight: bold;"></span> akan dihapus.
                    <br>Lanjutkan?
                </p>
                <p><input type="hidden" name="idToDelete" id="idToDelete" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default confirmOk">&nbsp; Yes &nbsp;</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">&nbsp; No &nbsp;</button>
            </div>
        </div>
    </div>
</div>

<!-- cancel confirmation -->
<div id="cancelConfirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalDeleteConfirmLabel" class="modal-title">Konfirmasi Cancel</h4>
            </div>
            <div class="modal-body">
                <p>
                    Surat dengan nomor <span id="letterNumber" style="font-weight: bold;"></span> akan dibatalkan.
                    <br>Lanjutkan?
                </p>
                <p><input type="hidden" name="idToCancel" id="idToCancel" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default confirmOkCancel">&nbsp; Yes &nbsp;</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">&nbsp; No &nbsp;</button>
            </div>
        </div>
    </div>
</div>
							
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/acara.list.js"></script>		
					
																				