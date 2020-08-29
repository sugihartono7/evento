<style>
	.red-star {
		color: #FF0000;
	}
	.al-right {
		text-align: right;
	}
	.al-center {
		text-align: center;
	}
</style>

<script type="text/javascript">
	baseUrl = "<?php echo base_url(); ?>";
</script>

<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Tillcode</h3>
		
	<!--	<a href="<?php //echo base_url(); ?>tillcode/add" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-plus-square"></i> <?php //echo ADD_CAPTION; ?></a>
	-->	
		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<div class="form-panel" style="padding:10px;">
					<section id="unseen">
						<div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed" id="datatable">
								<thead>
									<tr>
										<th>Tilcode</th>
										<th>Artcode</th>
										<th>Description</th>
										<th>Brand</th>
										<th>Disc 1</th>
										<th>Disc 2</th>
										<th>Disc 3</th>
										<th>SP</th>
										<th style="width:7%;">Action</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div><!-- end div responsive -->
					</section>	
								
				</div><!-- /content-panel -->
			</div><!-- /col-lg-4 -->			
								
<!-- // added by jerry@22-Oct-15 -->
<!-- date edit form -->
<div id="editForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalEditFormLabel" class="modal-title">Edit Tillcode</h4>
				<img id="imgLoading" class="hide" src="<?php echo base_url(); ?>assets/images/ui-anim_basic_16x16" border="0">
            </div>
            <div class="modal-body">
                <p>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Tillcode<span class="red-star"> *</span></label>
						<div class="col-sm-4 required" style="margin-bottom: 10px;">
							<input type="text" class="form-control" readonly="readonly" id="tillcode" name="tillcode">
						</div>
						<label class="col-sm-3 control-label" style="text-align: right; margin-right: 4px;">Special Price</label>
						<div class="col-sm-2 required" style="margin-bottom: 10px;">
							<select id="issp" name="issp" class="form-control">
								<option value="0">N</option>
								<option value="1">Y</option>
							</select>
						</div>
					</div> 
					<div class="form-group">
						<label class="col-sm-2 control-label">Disc 1</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="disc1" name="disc1"  maxlength="6">
						</div>
						<label class="col-sm-1 control-label" style="text-align: right; margin-right: 4px; margin-left: 18px;">Disc 2</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="disc2" name="disc2"  maxlength="6">
						</div>
						<label class="col-sm-1 control-label" style="text-align: right; margin-right: 4px; margin-left: 18px;">Disc 3</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="disc3" name="disc3"  maxlength="6">
						</div>
					</div>
					<p>&nbsp;</p>
                </p>
                <p><input type="hidden" name="idToUpdate" id="idToUpdate" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmOk">&nbsp; Save &nbsp;</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>									
									
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/autoNumeric.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tillcode.js"></script>
																		