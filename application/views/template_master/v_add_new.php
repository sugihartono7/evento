<?php 
	
	$msg = $this->session->flashdata('msg');
	if ($msg){
		echo "<script>success_msg('$msg');</script>";
	} 
	
?>

<section id="main-content">
	<section class="wrapper">
		
		<h3><i class="fa fa-angle-right"></i> Data Template</h3>
		<a href="<?php echo base_url(); ?>template/list" class="btn_add btn btn-default btn-sm">
		<i class="fa fa-backward "></i> <?php echo BACK_CAPTION; ?></a>
		
		<div class="row mt">
			<div class="col-lg-12" style="padding-left:5px;padding-left:5px">
				<div class="form-panel" style="padding:30px 10px 10px 10px;">
					
					<?php echo $xinha_java; ?>
					
					<form action="<?php echo base_url(); ?>template/do_add_new" id="frm" name="frm" class="form-horizontal style-form" method="post" enctype="multipart/form-data">
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Event Source</label>
							<div class="col-sm-2">
								<select id="cb_source" class="form-control" name="cb_source">
									<option value="C">Cabang</option>
									<option value="S">Supplier</option>
									<option value="Y">YOGYA</option>
								</select>
							</div>
							
							<label class="col-sm-1 col-sm-1 control-label-right">Name</label>
							<div class="col-sm-7 pad-right">
								<input type="text" class="form-control" id="txt_name" name="txt_name">
								<label id="txt_name-error" for="txt_name" style="color:red"></label>
							</div>
						</div>
						
						<!--------------------------------------------- header surat --------------------------------------------- -->
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Header</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="txt_header" id="txt_header" rows="18">
									
									<p align='left'>Bandung, #TGL_SURAT</p>
									<p align='left'>
										<table border='0' style="font-size:13px;" class='head_acara'>
											<tr>
												<td>Nomor</td>
												<td>:</td>
												<td>#NOMOR_SURAT_ACARA</td>
											</tr>
											<tr>
												<td>Lamp</td>
												<td>:</td>
												<td>#LAMPIRAN</td>
											</tr>
											<tr>
												<td>Hal</td>
												<td>:</td>
												<td><b>#ABOUT #PURPOSE</b></td>
											</tr>
										</table>
									</p>
									<br>
									<p align='left'>
										Kepada Yth,</br>
										<b>#TOWARD </b></br>
										#NAMA_SUPPLIER</br>
										#KOTA #FAX
									</p>
									
									
									<p align='left' class='notes'>
										Dengan hormat,<br>
										Sehubungan dengan diadakannya acara #ABOUT
										dalam rangka #DPURPOSE, berikut kami informasikan ketentuan acara tersebut :
									</p>
								</textarea>
								<label id="txt_header-error" for="txt_header" style="color:red"></label>
							</div>
						</div>
						
						<!--------------------------------------------- footer surat --------------------------------------------- -->
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Footer</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="txt_footer" id="txt_footer" rows="13">
									
									#PARENTNOTES
									

									<p align='left' id='penutup'>
										Demikian informasi ini kami sampaikan, atas perhatian dan kerjasamanya yang baik
										kami ucapkan terima kasih.
									</p>
									<br>
									<table border='0' style="font-size:13px;width:700px" id='ttd'>
										<tr>
											<td width="30%">Hormat kami</td>
											<td width="30%">Mengetahui,</td>
											<td width="30%">Menyetujui</td>
										</tr>
										<tr><td colspan="3"><br><br><br></td></tr>
										<tr>
											<td><b><u>Silvia Wening</u></b><br>MD. Shoes &amp; Bags Cons.</td>
											<td><b><u>Susan</u></b><br>DMM. Shoes &amp; Bags</td>
											<td valign="top">_____________<br>Mitra Usaha</td>
										</tr>
									</table>
									<br>
									<p align='left'>
										cc. Bapak Untara Hartono Somali, Fashion Director
										<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Ibu Lucia Lisdawaty, Senior Merchandising Manager
										<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Bapak Dede Martono, Internal Audit Manager
									</p>
								</textarea>
								
								<label id="txt_footer-error" for="txt_footer" style="color:red"></label>
							</div>
						</div>
						
						<!--------------------------------------------- notes surat --------------------------------------------- -->
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Notes</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="txt_notes" id="txt_notes">
									<p align='left'><b><i>
										NB: <br>
										* Acara dapat dihentikan sewaktu waktu jika evaluasi sales tidak menunjukkan hasil yang memuaskan.<br>
										* Surat perpanjangan acara harus kami terima paling lambat H-7 sebelum acara berakhir<br>
										* Apabila surat ini telah diterima dan ditandatangani harap difax kembali ke no. fax. 022-88884422.
										</i></b>
									</p>
								</textarea>
								<label id="txt_notes-error" for="txt_notes" style="color:red"></label>
							</div>
						</div>
						
						<div class="divider"></div>
						
						<div class="form-group">
							<div class="col-sm-12">
								<button class="btn btn-theme02" type="submit">
									<i class="fa fa-save"></i> <?php echo SAVE_CAPTION; ?>
								</button>
								<button class="btn btn-success" type="reset">
									<i class="fa fa-rotate-left"></i>
									<?php echo CANCEL_CAPTION; ?>
								</button>
							</div>
						</div>
						
					</form>
						
						
				</div><!-- /content-panel -->
			</div><!-- /col-lg-12 -->			
						
						
						
						
												