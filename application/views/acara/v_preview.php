

<section id="main-content">
	<section class="wrapper">
		<div class="content_title">
			<h3><i class="fa fa-angle-right"></i> Data Acara</h3>
			
			<?php 
				if ($isnew==null){
			?>
			<a href="<?php echo base_url(); ?>acara/list/0/0" class="btn_add btn btn-default btn-sm">
			<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
			<?php
				}
			?>
			
			<?php

				//jika sudah aktif
				
				if ($active!=0){

			?>
			<a href="<?php echo base_url()."acara/duplicate/$id";?>" class="btn_add btn btn-default btn-sm" id="btn_print">
			<i class="fa fa-copy "></i> Duplicate</a>

			<?php
					if($this->session->userdata['event_logged_in']['role']==1 && $is_printed!=1){
			?>
			<a href="<?php echo base_url()."acara/print_data/$id/".$file.".pdf"; ?>" class="btn_add btn btn-default btn-sm" id="btn_print">
			<i class="fa fa-print "></i> <?php echo PRINT_N_SEND_CAPTION; ?></a>
			<?php

					}//end if role=1

			?>
			<?php

				}

			?>
			
			<div class="alert alert-info" style="padding: 6px 10px; margin-bottom: -6px; margin-top: 6px;">
				<strong>Info!</strong> Untuk hasil print yang optimal, gunakan <b><i>custom paper</i></b> dengan ukuran 210 x 330 mm dan margin di-set = 0. 
			</div>
			
		</div>
		
		<div class="row mt">
			<div class="col-lg-9" style="padding-left:5px;">
				<div class="form-panel" style="padding:10px;">
					<div style="width:100%;">
					<?php // $this->output->enable_profiler(TRUE);; ?>

						<div>
							<?php
								 
								echo "<img src='".base_url()."assets/img/yg_red.png' /><br />";
								echo $rheader;
								echo "<table class='view_acara' border=0>";
								echo $vlocation;
								echo "</table>";
								
								#echo "<div class='newspaper' style='vertical-align:top;'>";
								echo "<div style='vertical-align:top;'>";
								#echo $vcalculate;
								#echo $vcalculate_gold;
								
								for ($i = 0; $i < sizeof($aVcalculate); $i++) {
									echo "<table style='width:100%'><tr>";
									echo "<td style='width:50%; vertical-align:top;'>" . (isset($aVcalculate[$i]) ? $aVcalculate[$i] : "&nbsp;") . "</td>";
									echo "<td style='vertical-align:top;'>" . (isset($aVcalculateGold[$i]) ? $aVcalculateGold[$i] : "&nbsp;") . "</td>";
									echo "</tr></table>";
								}
								
								echo "</div><br>";
								
								echo $rfooter;
								echo $rnotes;
							?>				
						</div><!-- /printable -->	
						
					</div>			
					
				</div><!-- /content-panel -->
			</div><!-- /col-lg-12 -->			
		<!--</div> /row -->
										
										
										
										
										
																				