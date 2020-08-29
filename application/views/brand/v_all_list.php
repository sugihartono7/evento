

<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Brand</h3>
		
		<!-- <a href="<?php // echo base_url(); ?>brand/add" class="btn_add btn btn-default btn-sm">
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
										<th>Code</th>
										<th>Description</th>
										<th>Created Date</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										
										foreach ((array)$list as $r) :
									?>
									<tr>
										<td><?php echo $r->brand_code; ?></td>
										<td><?php echo $r->brand_desc; ?></td>
										<td><?php echo $r->created_date; ?></td>
										
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
									
									
									
									
									
																		