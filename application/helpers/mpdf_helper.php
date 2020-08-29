<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    if (!function_exists('create_pdf')) {

        function pdf_create($html_data, $file_name = "") {
            ini_set("memory_limit","1000M");


            require 'mpdf/mpdf.php';
            //$mypdf = new mPDF("a4");
           //$mypdf = new mPDF('', 'F4');
           
           #            mPDF($mode='',$format='A4',$default_font_size=0,$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P')
           #$mypdf = new mPDF('', 'F4', '', '', '5', '5', '10', '15', '13', '5');
           # F4
           $mypdf = new mPDF('utf-8', array(210, 330), '', '', '10', '5', '10', '15', '13', '8');
           # US Legal
           #$mypdf = new mPDF('utf-8', array(216, 356), '', '', '10', '5', '10', '15', '13', '5');
           # US Letter
           #$mypdf = new mPDF('utf-8', array(216, 279), '', '', '10', '5', '10', '15', '13', '5');
            
            //
           // ('', 'A4', '', '', $left, $right, $top, $bottom, $margin_header, $margin_footer)
           //salah ('', 'A4', '', '', $margin_bottom, $margin_top, $margin_left, $margin_right, $margin_header, $margin_footer)//salah

            //$header = "<img src='".base_url()."assets/img/yg_red.png'  />";
           // $mypdf->SetHTMLHeader($header);

            $footer = "<hr style='margin-bottom: 5px; height:1px'><div style='font-size:7pt;' align='center'>Head Office : Jl Terusan Buah Batu No. 12 Bandung  Telp +62-22-88884388  Fax +62-22-88884422</div>";
            $mypdf->SetHTMLFooter($footer);

            //$mypdf->shrink_tables_to_fit=1;

            $stylesheet = file_get_contents(base_url().'assets/css/style-surat.css');
			// $stylesheet = file_get_contents('evento.yogya.com/assets/css/style-surat.css');
            $mypdf->WriteHTML($stylesheet, 1);

            $mypdf->WriteHTML($html_data);
           //$mypdf->Output($file_name . '.pdf', 'D');

            $new_filename = str_replace("/", "_", $file_name);

            $file_to_save = FCPATH . 'assets/surat_acara/' . $new_filename . '.pdf';

            $mypdf->Output($file_to_save, 'F');

        }

    }
