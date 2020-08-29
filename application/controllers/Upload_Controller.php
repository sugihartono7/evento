<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Upload_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
			$this->load->model('Event_model');
		}
		
		public function add($m) {
			if ($m==1){
				$data['m'] = 1;
				
			}else {
				$data['m'] = 0;
			}
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_import_active'] = 'color:#FFF';
			$data['head'] = 'acara/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'acara/v_upload';
			$data['right_menu'] = 'acara/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$this->load->view('acara/v_acara', $data);
		}
		
		function do_add(){
			ini_set('max_execution_time', 3600);
			ini_set('memory_limit', '700M');

			$this->load->helper(array('form', 'url')); 
			$this->load->library('upload');
			$this->load->file(APPPATH.'third_party/PHPExcel.php');
			$this->load->file(APPPATH.'third_party/PHPExcel/IOFactory.php');
			
			$nama_file = $_FILES['txt_file']['name'];
			$vdir_upload = $_SERVER['DOCUMENT_ROOT'].'/assets/upload/';
			$vfile_upload = $vdir_upload . $nama_file  ;
			move_uploaded_file($_FILES["txt_file"]["tmp_name"], $vfile_upload);
			
			$this->import_tillcode($vfile_upload);
			$this->import_pic($vfile_upload);
			$this->insert_tillcode_detail($vfile_upload);

			redirect('upload/add/1');
			echo "<script>
					location.href='".base_url()."upload/add/1';
				</script>";
		}

		function import_tillcode($vfile_upload){
			try {
				$inputFileType 	= PHPExcel_IOFactory::identify($vfile_upload);
				$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel 	= $objReader->load($vfile_upload);
			} 
			catch(Exception $e) {
				die($e->getMessage());
			}

			$sheet = $objPHPExcel->getSheet(0); 
			

			if (is_numeric($sheet->getCell('B2')->getValue())){
				$sheet = $objPHPExcel->getSheet(0); 
			}else {
				$sheet = $objPHPExcel->getSheet(1); 
			}

			$highestRow 	= $sheet->getHighestRow(); 
			$highestColumn 	= $sheet->getHighestColumn();

			for ($row = 2; $row <= $highestRow; $row++){ 
				$tillcode = $sheet->getCell('B'.$row)->getValue();
				$disc_label = $sheet->getCell('C'.$row)->getValue();

				$disc1 = $sheet->getCell('E'.$row)->getValue();
				$disc1 = str_ireplace("DISCOUNT", "", $disc1);
				$disc1 = str_replace("%", "", $disc1);
				$disc1 = trim($disc1);
				$disc1 = (is_numeric($disc1) ? $disc1 : 0);

				$disc2 = $sheet->getCell('F'.$row)->getValue();
				$disc2 = str_ireplace("DISCOUNT", "", $disc2);
				$disc2 = str_replace("%", "", $disc2);
				$disc2 = trim($disc2);
				$disc2 = (is_numeric($disc2) ? $disc2 : 0);

				$special_price = null;
				$division_code = $sheet->getCell('K'.$row)->getValue();
				$division_code = substr($division_code, 0, 1);
				$brand_code = $sheet->getCell('Q'.$row)->getValue();

				$is_sp = strtolower($sheet->getCell('H'.$row)->getValue());
				if ($is_sp!=null){
					if ((strpos($is_sp, 'sp') !== false) || (strpos($is_sp, 'special price') !== false)){
						$is_sp = 1;
					}else {
						$is_sp = 0;
					}
				}
				else {
					$is_sp = 0;
				}
				
				$is_active = 1;
				$created_date = date('Y-m-d H:i:s');
				$article_code = $sheet->getCell('A'.$row)->getValue();
				$article_code = str_pad($article_code, 13, "0", STR_PAD_LEFT);
				$price = null;
				$cat_code = $sheet->getCell('K'.$row)->getValue();
				$article_type = $sheet->getCell('I'.$row)->getValue();
				$disc_label_2 = null;
				
				$disc3 = $sheet->getCell('I'.$row)->getValue();
				$disc3 = str_ireplace("DISCOUNT", "", $disc3);
				$disc3 = str_replace("%", "", $disc3);
				$disc3 = trim($disc3);
				$disc3 = (is_numeric($disc3) ? $disc3 : 0);

				$supp_code = $sheet->getCell('M'.$row)->getValue();
				$art = $sheet->getCell('A'.$row)->getValue();
				$brand_desc = $sheet->getCell('J'.$row)->getValue();
				$margin = $sheet->getCell('O'.$row)->getValue();
				$margin = (is_numeric($margin) ? $margin : 0);
				$is_pkp = $sheet->getCell('P'.$row)->getValue();
				$is_pkp = ($is_pkp == "PKP" ? 1 : 0);
				$updated_by = $this->session->userdata['event_logged_in']['username'];
				$updated_date = date('Y-m-d H:i:s');

				if (is_numeric($tillcode)){
					if ($this->Event_model->brand_code_is_exists($brand_code)>0 && $sheet->getCell('K'.$row)->getValue() != null){
						if ($tillcode!=null || $tillcode!=''){
							$ada = $this->Event_model->ada_tillcode($tillcode);
							if ($ada==1){
								$dt = array(
										'disc_label'			=> $disc_label,
										'disc1'					=> $disc1,
										'disc2'					=> $disc2,
										'special_price'			=> $special_price,
										'division_code'			=> $division_code,
										'brand_code'			=> $brand_code,
										'is_sp'					=> $is_sp,
										'created_date'			=> $created_date,
										'article_code'			=> $article_code,
										'price'					=> $price,
										'cat_code'				=> $cat_code,
										'article_type'			=> $article_type,
										'disc_label_2'			=> $disc_label_2,
										'disc3'					=> $disc3,
										'supp_code'				=> $supp_code,
										'brand_desc'			=> $brand_desc,
										'margin'				=> $margin,
										'is_pkp'				=> $is_pkp,
										'updated_by'			=> $updated_by,
										'updated_date'			=> $updated_date
									);
								$this->Event_model->edit_tillcode($tillcode, $disc_label, $disc1, $disc2, $special_price, $division_code, $brand_code, $is_sp, $article_code, $price, $cat_code, $article_type, $disc_label_2, $disc3, $supp_code, $brand_desc, $margin, $is_pkp, $updated_by, $updated_date);
								//echo $tillcode.' -> inserted<br>';
							}
							else {
								$dt = array(
										'tillcode'				=> $tillcode,
										'disc_label'			=> $disc_label,
										'disc1'					=> $disc1,
										'disc2'					=> $disc2,
										'special_price'			=> $special_price,
										'division_code'			=> $division_code,
										'brand_code'			=> $brand_code,
										'is_sp'					=> $is_sp,
										'is_active'				=> $is_active,
										'created_date'			=> $created_date,
										'article_code'			=> $article_code,
										'price'					=> $price,
										'cat_code'				=> $cat_code,
										'article_type'			=> $article_type,
										'disc_label_2'			=> $disc_label_2,
										'disc3'					=> $disc3,
										'supp_code'				=> $supp_code,
										'brand_desc'			=> $brand_desc,
										'margin'				=> $margin,
										'is_pkp'				=> $is_pkp,
										'updated_by'			=> null,
										'updated_date'			=> null
									);
								
								$this->Event_model->add_tillcode($dt);
								//echo $tillcode.' -> inserted<br>';							
							}
						}//end if check null
						else {
							//echo 'row '.$row.' tillcode is null -> skipped<br>';
						}	
					}else {
						//echo 'row '.$row.' brandcode '.$brand_code.', tillcode  '.$tillcode.'-'.$art.' is not exists -> skipped<br>';	
					}
					
				} else {
					//echo 'row '.$row.', art '.$art.' '.$tillcode.' '.$disc_label.' is not numeric -> skipped<br>';
				}	
			}

		}

		function import_pic($vfile_upload){
			try {
				$inputFileType 	= PHPExcel_IOFactory::identify($vfile_upload);
				$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel 	= $objReader->load($vfile_upload);
			} 
			catch(Exception $e) {
				die($e->getMessage());
			}

			$sheet = $objPHPExcel->getSheet(1); 

			if (strlen($sheet->getCell('A2')->getValue())<=4){
				$sheet = $objPHPExcel->getSheet(1); 
			}else {
				$sheet = $objPHPExcel->getSheet(2); 
			}

			$highestRow 	= $sheet->getHighestRow(); 
			$highestColumn 	= $sheet->getHighestColumn();
			
			for ($row = 2; $row <= $highestRow; $row++){ 
				$name = $sheet->getCell('C'.$row)->getValue();
				$supplier_code = $sheet->getCell('A'.$row)->getValue();
				$dt = array(
							'id'				=> $this->Event_model->get_nextval_pic_id_seq(),
							'name'				=> $name,
							'supplier_code'		=> $supplier_code,
							'created_date'		=> null,
							'created_by'		=> null,
							'updated_at'		=> null,
							'updated_by'		=> null
						);

				$ada = $this->Event_model->ada_supplier_pic($name, $supplier_code);
				if ($ada==1){
					//echo $name.' '.$supplier_code.' -> inserted<br>';
				}
				else {
					if ($name != NULL || $name != ''){
						$this->Event_model->insert_pic($dt);
						//echo $name.' '.$supplier_code.' -> inserted<br>';	
					}
				}
			}

		}
		

		function insert_tillcode_detail($vfile_upload){
			try {
				$inputFileType 	= PHPExcel_IOFactory::identify($vfile_upload);
				$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel 	= $objReader->load($vfile_upload);
			} 
			catch(Exception $e) {
				die($e->getMessage());
			}

			$sheet = $objPHPExcel->getSheet(2); 

			if (is_numeric($sheet->getCell('C4')->getValue())){
				$sheet = $objPHPExcel->getSheet(2); 
			}else {
				$sheet = $objPHPExcel->getSheet(3); 
			}

			$highestRow 	= $sheet->getHighestRow(); 
			$highestColumn 	= $sheet->getHighestColumn();
			
			$div_code = $sheet->getCell('B2')->getValue();

			for ($row = 4; $row <= $highestRow; $row++){ 
				$tillcode =  $sheet->getCell('C' . $row)->getValue(); 				
				$lastColumn = $sheet->getHighestColumn();
				$lastColumn++;

				for ($column = 'D'; $column != $lastColumn; $column++) {
				    $cell_cab = $sheet->getCell($column.$row)->getValue();
				    
				    $idx = 3;
				    if ($cell_cab!=null || $cell_cab!=""){
				    	$store =  $sheet->getCell($column.$idx)->getValue();//get cabang init
				    	$dt = array(
									'id'				=> $this->Event_model->get_nextval_tillcode_detail(),
									'store_init'		=> $store,
									'tillcode'			=> $tillcode,
									'division_code'		=> $div_code
								);
						$ada = $this->Event_model->ada_tillcode_detail($store, $tillcode);
						
						if ($ada==1){
							//echo $tillcode . ' -> inserted <br>';
						} 
						else {
							if ($this->Event_model->insert_tillcode_detail($dt)){
								//echo $tillcode . ' '.$store .' -> failed <br>';
							}
							else {
								//echo $tillcode . ' '.$store .' -> inserted<br>';	
							}
						}

				    } 
				    
				   

				}



			}

		}

		function get_phpinfo(){
			phpinfo();
		}
		
	}	
	

?>