<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	function pdf_create($html, $filename='', $stream=TRUE) {
	    require_once("dompdf/dompdf_config.inc.php");

	    $new_filename = str_replace("/", "_", $filename);

	    $dompdf = new DOMPDF();
	    $dompdf->load_html($html);
	    $dompdf->set_base_path(base_url().'assets/css/style-surat.css');
	    $dompdf->render();
	    if ($stream) {
	        $dompdf->stream($new_filename.".pdf");
	    } 
	    else {
	        //return $dompdf->output();
	        $output = $dompdf->output();
	        $file_to_save = FCPATH . 'assets/surat_acara/' . $new_filename . '.pdf';
   			file_put_contents($file_to_save, $output);
	    }

	}


?>