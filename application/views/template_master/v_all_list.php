

<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Template</h3>
		
		<a href="<?php echo base_url(); ?>template/add" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-plus-square"></i> <?php echo ADD_CAPTION; ?></a>
	
		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<div class="form-panel" style="padding:10px;">
					
					<section id="unseen">
						<div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed" id="datatable">
								<thead>
									<tr>
										<th>Template Name</th>
										<th>Created Date</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										
										foreach ((array)$list as $r) :
									?>
								    <tr>
										<td><a href="<?php echo base_url()."template/preview/".$r->tmpl_code; ?>"><?php echo $r->tmpl_name; ?></a></td>
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
									
									
									
									
									
																		