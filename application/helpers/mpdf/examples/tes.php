<?php
	
	include("../mpdf.php");
	
	$mpdf=new mPDF();
	
	$stylesheet = file_get_contents('styles.css');
	$mpdf->WriteHTML($stylesheet,1);
	
	$html = "<div class='container'>
			   <div class='column column-one column-offset-2'>Column oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn oneColumn one
			   </div>
			   <div class='column column-two column-inset-1'>Column two</div>
			   <div class='column column-three column-offset-1'>Column three</div>
			   <div class='column column-four column-inset-2'>Column four</div>
			</div>";
	$mpdf->WriteHTML($html);
	
	$mpdf->Output();
?>