

<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Supplier PIC</h3>
		
	<!--	<a href="<?php //echo base_url(); ?>supplier/add" class="btn_add btn btn-default btn-sm">
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
										<th>Pic Name</th>
										<th>Supplier Name</th>
										<th>Created Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										
										foreach ($list as $r) :
									?>
								    <tr>
										<td><?php echo $r->pic_name; ?></td>
										<td><?php echo $r->supplier_name; ?></td>
										<td><?php echo $r->created_date; ?></td>
										<td class="al-center">
											<a id="a-<?php echo $r->supplier_code; ?>" data-id='<?php echo $r->supplier_code; ?>'
												data-supplier_name='<?php echo $r->supplier_name; ?>'
												data-pic_name='<?php echo $r->pic_name; ?>'
												data-toggle='modal'
												data-target='#editForm' 
												class='btn_update btn btn-xs editTrigger' title="Edit">
												<i class='fa fa-pencil'></i> 
											</a>
										</td>
	                                </tr>
									<?php

										endforeach;
										
									?>
								</tbody>
							</table>
						</div><!-- end div responsive -->
					</section>
									
									
				</div><!-- /content-panel -->
			</div><!-- /col-lg-4 -->			
									
									
	<div id="editForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalAlertLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 500px;">
    	<form action="<?php echo base_url(); ?>pic/do_edit" id="frm_edit" name="frm_edit" class="style-form" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="myModalEditFormLabel" class="modal-title">Edit PIC</h4>
				<img id="imgLoading" class="hide" src="<?php echo base_url(); ?>assets/images/ui-anim_basic_16x16" border="0">
            </div>
            <div class="modal-body">
                <p>
					<div class="form-group">
						<label class="col-sm-2 control-label">Supplier </label>
						<div class="col-sm-10 required" style="margin-bottom: 10px;">
							<input type="text" class="form-control" readonly="readonly" id="supplier" name="supplier">
						</div>
					</div>
					<div class="form-group">
						<div class='col-sm-12'><hr style='border:1px solid #F2F2F2'></div>
					</div> 
					<div class="form-group">
						<label class="col-sm-10 control-label">PIC Name</label>
						<label class="col-sm-2 control-label">&nbsp;&nbsp;Action</label>
					</div>
					
					<table id='tambah' class='table table-condensed dataTable no-footer'></table>
					
					<p>&nbsp;</p>
                </p>
                <p><input type="hidden" name="idToUpdate" id="idToUpdate" value="">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmOk" >&nbsp; Save &nbsp;</button>
                <button type="button" class="btn btn-warning addRow"> Add Row </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
        </form>
    </div>
</div>									
									
									
																		