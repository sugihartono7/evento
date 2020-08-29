<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Acara_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
			
			$this->load->model("Event_model");
			$this->load->model("Acara");
			$this->load->model("Division");

		}
		
		function index() {
			
		}
		
		// added @15-Mar-16
		function sliceNotes($notes, $dlmtr = '(') {
			$notes = str_replace("&gt;", ">", $notes);
			$data["form"] = "";
			$data["mechanism"] = "";
			
			$pos = strpos($notes, $dlmtr);
			if ($pos !== false) {
				$data["form"] = trim(substr($notes, 0, $pos));
				
				$text = substr($notes, $pos);
				$text = str_replace('(', '', $text);
				$text = str_replace(')', '', $text);
				$data["mechanism"] = trim($text);
			}
			else {
				$data["form"] = $notes;
			}
			
			return $data;
		}
		
		function readMonth($month) {
			switch($month) {
				case 2:
					$sheetName = 'FEB';
					break;
				case 3:
					$sheetName = 'MAR';
					break;
				case 4:
					$sheetName = 'APR';
					break;
				case 5:
					$sheetName = 'MAY';
					break;
				case 6:
					$sheetName = 'JUN';
					break;
				case 7:
					$sheetName = 'JUL';
					break;
				case 8:
					$sheetName = 'AUG';
					break;
				case 9:
					$sheetName = 'SEP';
					break;
				case 10:
					$sheetName = 'OCT';
					break;
				case 11:
					$sheetName = 'NOV';
					break;
				case 12:
					$sheetName = 'DEC';
					break;
				default:
					$sheetName = 'JAN';
					break;
			}
			return $sheetName;
		}
		
		// export event to excel
		function export($todo = null) {
			if ($todo == null) {
				# clear acaraHolder
				$this->session->unset_userdata("acaraHolder");
				
				$data['trans_active'] = 'dcjq-parent active';
				$data['menu_export_active'] = 'color:#FFF';
				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_export';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				$data['divisions'] = $this->Division->loadAll();
				$this->load->view('acara/v_acara', $data);	
			}
			else if ($todo == 'excel') {
				$curYear = date('y');
				$inputs = $this->input->post();
				$divisionCode = $inputs["divisionCode"];
				$firstSignature = $inputs["firstSignature"];
				$nasionalJateng = $inputs["nasionalJateng"];
				
				//load our new PHPExcel library
				$this->load->library('excel');
				
				$objPHPExcel = new PHPExcel();
				
				$objPHPExcel->getProperties()->setCreator('evento.yogya.com')
							 ->setLastModifiedBy('evento.yogya.com developer')
							 ->setTitle('evento document')
							 ->setSubject('surat acara')
							 ->setDescription('rekap surat acara')
							 ->setKeywords('evento surat acara')
							 ->setCategory('evento file');
				
				// create 12 sheets for months
				for ($month = 1; $month <= 12; $month++) {
					
					$sheetIndex = $month - 1;
					//sheet with index 0 already create, start from index 1
					if ($sheetIndex > 0) {
						$objPHPExcel->createSheet($sheetIndex);
					}
					
					//activate worksheet
					$objPHPExcel->setActiveSheetIndex($sheetIndex);
					
					//name the worksheet
					$sheetName = $this->readMonth($month);
					$objPHPExcel->getActiveSheet()->setTitle($sheetName . ' ' . $curYear);
					
					// period to filter data
					$period = $curYear . str_pad($month, 2, '0', STR_PAD_LEFT);
					$dataToExport = $this->Acara->loadData($period, $divisionCode, $firstSignature, false, $nasionalJateng);
					
					//set cell content with some text
					$rowNum = 1;
					$objPHPExcel->getActiveSheet()
								->setCellValue('A' . $rowNum, 'NO')
								->setCellValue('B' . $rowNum, 'ART CODE GOLD')
								->setCellValue('C' . $rowNum, 'TILLCODE')
								->setCellValue('D' . $rowNum, 'SHORT DESC')
								->setCellValue('E' . $rowNum, 'BENTUK ACARA')
								->setCellValue('F' . $rowNum, 'MEKANISME')
								->setCellValue('G' . $rowNum, 'SUPP CODE')
								->setCellValue('H' . $rowNum, 'SUPP DESC')
								->setCellValue('I' . $rowNum, 'COM CONTRACT')
								->setCellValue('J' . $rowNum, 'NET MARGIN')
								->setCellValue('K' . $rowNum, 'PERIODE')
								->setCellValue('L' . $rowNum, 'SITE GROUP')
								->setCellValue('M' . $rowNum, 'KETERANGAN')
								->setCellValue('N' . $rowNum, 'NO SURAT')
								->setCellValue('O' . $rowNum, 'STATUS')
								;
					//make the font become bold
					$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('L' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('M' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('N' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('O' . $rowNum)->getFont()->setBold(true);
					
					// vertical align
					$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('L' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('M' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('N' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('O' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
					// set row height
					$objPHPExcel->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(20);
					
					// set column width
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(26);
					$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
					
					$createdDate = "";
					$rowNum++;
					$no = 1;
					foreach($dataToExport as $export) {
						
						if ($createdDate != $export->created_date) {
							
							$objPHPExcel->getActiveSheet()->setCellValue('A' . $rowNum, 'Update Tanggal ' . $export->created_date); 
							//change the font size
							//$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setSize(14);
							//make the font become bold
							$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
							//merge cell A until N
							$objPHPExcel->getActiveSheet()->mergeCells('A' . $rowNum . ':N' . $rowNum);
							//set aligment to center for that merged cell (A to N)
							$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							// set row height
							$objPHPExcel->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(15);
					
							$rowNum++;
						}
						
						# slice notes
						$aNotes = $this->sliceNotes($export->notes);
						$sForm = $aNotes['form'];
						$sForm = str_replace('&amp;', '&', $sForm);
						$sForm = str_replace('&gt;', '>', $sForm);
						$sMechanism = $aNotes['mechanism'];
						
						$objPHPExcel->getActiveSheet()
									->setCellValue('A' . $rowNum, $no)
									->setCellValueExplicit('B' . $rowNum, $export->article_code)
									->setCellValue('C' . $rowNum, $export->tillcode)
									->setCellValue('D' . $rowNum, $export->tillcode_desc)
									->setCellValue('E' . $rowNum, $sForm)
									->setCellValue('F' . $rowNum, $sMechanism)
									->setCellValue('G' . $rowNum, $export->supp_code)
									->setCellValue('H' . $rowNum, $export->supp_desc)
									->setCellValue('I' . $rowNum, $export->com_contract)
									->setCellValue('J' . $rowNum, $export->net_margin)
									->setCellValue('K' . $rowNum, $export->period)
									->setCellValue('L' . $rowNum, $export->site_group)
									->setCellValue('M' . $rowNum, '')
									->setCellValue('N' . $rowNum, $export->event_no)
									->setCellValue('O' . $rowNum, $export->status)
									;
						
						$createdDate = $export->created_date;
						$no++;
						$rowNum++;
					}
					
				}
				
				// special event
				// period (year) to filter data
				$period = $curYear;
				
				$specialEvents = $this->Acara->loadAllSpecialEvent($period, $divisionCode, $firstSignature);
				if (!empty($specialEvents)) {
					// create sheets for special events
					foreach($specialEvents as $special) {
						$sheetIndex++;
						$specialEventName = $special->special_event_desc;
						
						$objPHPExcel->createSheet($sheetIndex);
						
						//activate worksheet
						$objPHPExcel->setActiveSheetIndex($sheetIndex);
						
						//name the worksheet
						$objPHPExcel->getActiveSheet()->setTitle(substr($specialEventName, 0, 31));
						
						$dataToExport = $this->Acara->loadSpecialEventData($period, $specialEventName, $divisionCode, $firstSignature, false, $nasionalJateng);
						
						//set cell content with some text
						$rowNum = 1;
						$objPHPExcel->getActiveSheet()
									->setCellValue('A' . $rowNum, 'NO')
									->setCellValue('B' . $rowNum, 'ART CODE GOLD')
									->setCellValue('C' . $rowNum, 'TILLCODE')
									->setCellValue('D' . $rowNum, 'SHORT DESC')
									->setCellValue('E' . $rowNum, 'BENTUK ACARA')
									->setCellValue('F' . $rowNum, 'MEKANISME')
									->setCellValue('G' . $rowNum, 'SUPP CODE')
									->setCellValue('H' . $rowNum, 'SUPP DESC')
									->setCellValue('I' . $rowNum, 'COM CONTRACT')
									->setCellValue('J' . $rowNum, 'NET MARGIN')
									->setCellValue('K' . $rowNum, 'PERIODE')
									->setCellValue('L' . $rowNum, 'SITE GROUP')
									->setCellValue('M' . $rowNum, 'KETERANGAN')
									->setCellValue('N' . $rowNum, 'NO SURAT')
									->setCellValue('O' . $rowNum, 'STATUS')
									;
						//make the font become bold
						$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('L' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('M' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('N' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('O' . $rowNum)->getFont()->setBold(true);
						
						// vertical align
						$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('L' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('M' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('N' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('O' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						
						// set row height
						$objPHPExcel->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(20);
						
						// set column width
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
						$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
						$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
						$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
						$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
						$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
						$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(26);
						$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
						
						$createdDate = "";
						$rowNum++;
						$no = 1;
						foreach($dataToExport as $export) {
							
							if ($createdDate != $export->created_date) {
								
								$objPHPExcel->getActiveSheet()->setCellValue('A' . $rowNum, 'Update Tanggal ' . $export->created_date); 
								//change the font size
								//$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setSize(14);
								//make the font become bold
								$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
								//merge cell A until N
								$objPHPExcel->getActiveSheet()->mergeCells('A' . $rowNum . ':N' . $rowNum);
								//set aligment to center for that merged cell (A to N)
								$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								// set row height
								$objPHPExcel->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(15);
						
								$rowNum++;
							}
							
							# slice notes
							$aNotes = $this->sliceNotes($export->notes);
							$sForm = $aNotes['form'];
							$sForm = str_replace('&amp;', '&', $sForm);
							$sForm = str_replace('&gt;', '>', $sForm);
							$sMechanism = $aNotes['mechanism'];
						
							$objPHPExcel->getActiveSheet()
										->setCellValue('A' . $rowNum, $no)
										->setCellValueExplicit('B' . $rowNum, $export->article_code)
										->setCellValue('C' . $rowNum, $export->tillcode)
										->setCellValue('D' . $rowNum, $export->tillcode_desc)
										->setCellValue('E' . $rowNum, $sForm)
										->setCellValue('F' . $rowNum, $sMechanism)
										->setCellValue('G' . $rowNum, $export->supp_code)
										->setCellValue('H' . $rowNum, $export->supp_desc)
										->setCellValue('I' . $rowNum, $export->com_contract)
										->setCellValue('J' . $rowNum, $export->net_margin)
										->setCellValue('K' . $rowNum, $export->period)
										->setCellValue('L' . $rowNum, $export->site_group)
										->setCellValue('M' . $rowNum, '')
										->setCellValue('N' . $rowNum, $export->event_no)
										->setCellValue('O' . $rowNum, $export->status)
										;
							
							$createdDate = $export->created_date;
							$no++;
							$rowNum++;
						}
					}
				}
				
				// set active sheet to 0
				$objPHPExcel->setActiveSheetIndex(0);
				$filename = empty($firstSignature) ?  "Acara_Div_" . $divisionCode . "_" . $nasionalJateng . "_" . $curYear . ".xls" : "Acara_Div_" . $divisionCode . "_" . str_replace(" ", "_", $firstSignature) . "_" . $nasionalJateng . "_" . $curYear . ".xls";
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save(FORGE_EXPORT . '/' . $filename);
				$files[] = $filename;
				
				// check cross year
				$curYearCheck = date('Y');
				$nextYearCheck = $curYearCheck + 1;
				$isCrossYear = $this->Acara->isCrossYear($curYearCheck, $nextYearCheck, $nasionalJateng);
				
				if ($isCrossYear) {
					
					$objPHPExcel2 = new PHPExcel();
					
					$curYear = date('y', strtotime('+1 year'));
					
					// create 12 sheets for months
					for ($month = 1; $month <= 12; $month++) {
						
						$sheetIndex = $month - 1;
						//sheet with index 0 already create, start from index 1
						if ($sheetIndex > 0) {
							$objPHPExcel2->createSheet($sheetIndex);
						}
						
						//activate worksheet
						$objPHPExcel2->setActiveSheetIndex($sheetIndex);
						
						//name the worksheet
						$sheetName = $this->readMonth($month);
						$objPHPExcel2->getActiveSheet()->setTitle($sheetName . ' ' . $curYear);
						
						// period to filter data
						$period = $curYear . str_pad($month, 2, '0', STR_PAD_LEFT);
						$dataToExport = $this->Acara->loadData($period, $divisionCode, $firstSignature, false, $nasionalJateng);
						
						//set cell content with some text
						$rowNum = 1;
						$objPHPExcel2->getActiveSheet()
									->setCellValue('A' . $rowNum, 'NO')
									->setCellValue('B' . $rowNum, 'ART CODE GOLD')
									->setCellValue('C' . $rowNum, 'TILLCODE')
									->setCellValue('D' . $rowNum, 'SHORT DESC')
									->setCellValue('E' . $rowNum, 'BENTUK ACARA')
									->setCellValue('F' . $rowNum, 'MEKANISME')
									->setCellValue('G' . $rowNum, 'SUPP CODE')
									->setCellValue('H' . $rowNum, 'SUPP DESC')
									->setCellValue('I' . $rowNum, 'COM CONTRACT')
									->setCellValue('J' . $rowNum, 'NET MARGIN')
									->setCellValue('K' . $rowNum, 'PERIODE')
									->setCellValue('L' . $rowNum, 'SITE GROUP')
									->setCellValue('M' . $rowNum, 'KETERANGAN')
									->setCellValue('N' . $rowNum, 'NO SURAT')
									->setCellValue('O' . $rowNum, 'STATUS')
									;
						//make the font become bold
						$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('B' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('C' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('D' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('E' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('F' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('G' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('H' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('I' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('J' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('K' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('L' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('M' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('N' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel2->getActiveSheet()->getStyle('O' . $rowNum)->getFont()->setBold(true);
						
						// vertical align
						$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('B' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('C' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('D' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('E' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('F' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('G' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('H' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('I' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('J' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('K' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('L' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('M' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('N' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel2->getActiveSheet()->getStyle('O' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						
						// set row height
						$objPHPExcel2->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(20);
						
						// set column width
						$objPHPExcel2->getActiveSheet()->getColumnDimension('A')->setWidth(5);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('B')->setWidth(20);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('C')->setWidth(12);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('D')->setWidth(40);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('E')->setWidth(20);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('F')->setWidth(20);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('G')->setWidth(12);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('H')->setWidth(22);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('I')->setWidth(14);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('J')->setWidth(12);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('K')->setWidth(30);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('L')->setWidth(40);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('M')->setWidth(14);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('N')->setWidth(26);
						$objPHPExcel2->getActiveSheet()->getColumnDimension('O')->setWidth(12);
						
						$createdDate = "";
						$rowNum++;
						$no = 1;
						foreach($dataToExport as $export) {
							
							if ($createdDate != $export->created_date) {
								
								$objPHPExcel2->getActiveSheet()->setCellValue('A' . $rowNum, 'Update Tanggal ' . $export->created_date); 
								//change the font size
								//$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setSize(14);
								//make the font become bold
								$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
								//merge cell A until N
								$objPHPExcel2->getActiveSheet()->mergeCells('A' . $rowNum . ':N' . $rowNum);
								//set aligment to center for that merged cell (A to N)
								$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								// set row height
								$objPHPExcel2->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(15);
						
								$rowNum++;
							}
							
							# slice notes
							$aNotes = $this->sliceNotes($export->notes);
							$sForm = $aNotes['form'];
							$sForm = str_replace('&amp;', '&', $sForm);
							$sForm = str_replace('&gt;', '>', $sForm);
							$sMechanism = $aNotes['mechanism'];
						
							$objPHPExcel2->getActiveSheet()
										->setCellValue('A' . $rowNum, $no)
										->setCellValueExplicit('B' . $rowNum, $export->article_code)
										->setCellValue('C' . $rowNum, $export->tillcode)
										->setCellValue('D' . $rowNum, $export->tillcode_desc)
										->setCellValue('E' . $rowNum, $sForm)
										->setCellValue('F' . $rowNum, $sMechanism)
										->setCellValue('G' . $rowNum, $export->supp_code)
										->setCellValue('H' . $rowNum, $export->supp_desc)
										->setCellValue('I' . $rowNum, $export->com_contract)
										->setCellValue('J' . $rowNum, $export->net_margin)
										->setCellValue('K' . $rowNum, $export->period)
										->setCellValue('L' . $rowNum, $export->site_group)
										->setCellValue('M' . $rowNum, '')
										->setCellValue('N' . $rowNum, $export->event_no)
										->setCellValue('O' . $rowNum, $export->status)
										;
							
							$createdDate = $export->created_date;
							$no++;
							$rowNum++;
						}
						
					}
					
					// special event
					// period (year) to filter data
					$period = $curYear;
							
					$specialEvents = $this->Acara->loadAllSpecialEvent($period, $divisionCode, $firstSignature);
					if (!empty($specialEvents)) {
						// create sheets for special events
						foreach($specialEvents as $special) {
							$sheetIndex++;
							$specialEventName = $special->special_event_desc;
							
							$objPHPExcel2->createSheet($sheetIndex);
							
							//activate worksheet
							$objPHPExcel2->setActiveSheetIndex($sheetIndex);
							
							//name the worksheet
							$objPHPExcel2->getActiveSheet()->setTitle(substr($specialEventName, 0, 31));
							
							$dataToExport = $this->Acara->loadSpecialEventData($period, $specialEventName, $divisionCode, $firstSignature, false, $nasionalJateng);
							
							//set cell content with some text
							$rowNum = 1;
							$objPHPExcel2->getActiveSheet()
										->setCellValue('A' . $rowNum, 'NO')
										->setCellValue('B' . $rowNum, 'ART CODE GOLD')
										->setCellValue('C' . $rowNum, 'TILLCODE')
										->setCellValue('D' . $rowNum, 'SHORT DESC')
										->setCellValue('E' . $rowNum, 'BENTUK ACARA')
										->setCellValue('F' . $rowNum, 'MEKANISME')
										->setCellValue('G' . $rowNum, 'SUPP CODE')
										->setCellValue('H' . $rowNum, 'SUPP DESC')
										->setCellValue('I' . $rowNum, 'COM CONTRACT')
										->setCellValue('J' . $rowNum, 'NET MARGIN')
										->setCellValue('K' . $rowNum, 'PERIODE')
										->setCellValue('L' . $rowNum, 'SITE GROUP')
										->setCellValue('M' . $rowNum, 'KETERANGAN')
										->setCellValue('N' . $rowNum, 'NO SURAT')
										->setCellValue('O' . $rowNum, 'STATUS')
										;
							//make the font become bold
							$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('B' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('C' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('D' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('E' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('F' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('G' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('H' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('I' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('J' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('K' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('L' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('M' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('N' . $rowNum)->getFont()->setBold(true);
							$objPHPExcel2->getActiveSheet()->getStyle('O' . $rowNum)->getFont()->setBold(true);
							
							// vertical align
							$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('B' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('C' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('D' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('E' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('F' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('G' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('H' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('I' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('J' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('K' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('L' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('M' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('N' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$objPHPExcel2->getActiveSheet()->getStyle('O' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							
							// set row height
							$objPHPExcel2->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(20);
							
							// set column width
							$objPHPExcel2->getActiveSheet()->getColumnDimension('A')->setWidth(5);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('B')->setWidth(20);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('C')->setWidth(12);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('D')->setWidth(40);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('E')->setWidth(20);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('F')->setWidth(20);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('G')->setWidth(12);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('H')->setWidth(22);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('I')->setWidth(14);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('J')->setWidth(12);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('K')->setWidth(30);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('L')->setWidth(40);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('M')->setWidth(14);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('N')->setWidth(26);
							$objPHPExcel2->getActiveSheet()->getColumnDimension('O')->setWidth(12);
							
							$createdDate = "";
							$rowNum++;
							$no = 1;
							foreach($dataToExport as $export) {
								
								if ($createdDate != $export->created_date) {
									
									$objPHPExcel2->getActiveSheet()->setCellValue('A' . $rowNum, 'Update Tanggal ' . $export->created_date); 
									//change the font size
									//$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setSize(14);
									//make the font become bold
									$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
									//merge cell A until N
									$objPHPExcel2->getActiveSheet()->mergeCells('A' . $rowNum . ':N' . $rowNum);
									//set aligment to center for that merged cell (A to N)
									$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel2->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									// set row height
									$objPHPExcel2->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(15);
							
									$rowNum++;
								}
								
								# slice notes
								$aNotes = $this->sliceNotes($export->notes);
								$sForm = $aNotes['form'];
								$sForm = str_replace('&amp;', '&', $sForm);
								$sForm = str_replace('&gt;', '>', $sForm);
								$sMechanism = $aNotes['mechanism'];
							
								$objPHPExcel2->getActiveSheet()
											->setCellValue('A' . $rowNum, $no)
											->setCellValueExplicit('B' . $rowNum, $export->article_code)
											->setCellValue('C' . $rowNum, $export->tillcode)
											->setCellValue('D' . $rowNum, $export->tillcode_desc)
											->setCellValue('E' . $rowNum, $sForm)
											->setCellValue('F' . $rowNum, $sMechanism)
											->setCellValue('G' . $rowNum, $export->supp_code)
											->setCellValue('H' . $rowNum, $export->supp_desc)
											->setCellValue('I' . $rowNum, $export->com_contract)
											->setCellValue('J' . $rowNum, $export->net_margin)
											->setCellValue('K' . $rowNum, $export->period)
											->setCellValue('L' . $rowNum, $export->site_group)
											->setCellValue('M' . $rowNum, '')
											->setCellValue('N' . $rowNum, $export->event_no)
											->setCellValue('O' . $rowNum, $export->status)
											;
								
								$createdDate = $export->created_date;
								$no++;
								$rowNum++;
							}
						}
					}
					
					// set active sheet to 0
					$objPHPExcel2->setActiveSheetIndex(0);
					$filename = empty($firstSignature) ?  "Acara_Div_" . $divisionCode . "_" . $nasionalJateng . "_" . $curYear . ".xls" : "Acara_Div_" . $divisionCode . "_" . str_replace(" ", "_", $firstSignature) . "_" . $nasionalJateng . "_" . $curYear . ".xls";
							
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel2, 'Excel5');
					$objWriter->save(FORGE_EXPORT . '/' . $filename);
					$files[] = $filename;
					
				} // cross year
				
				$zipname = 'Div_' . $divisionCode . '_' . date('ymdHis') .  '.zip';
				$zip = new ZipArchive;
				$zip->open(FORGE_EXPORT . '/' . $zipname, ZipArchive::CREATE);
				foreach ($files as $file) {
					$zip->addFile(FORGE_EXPORT . '/' . $file, $file);
				}
				$zip->close();
				
				// delete files
				foreach ($files as $file) {
					unlink(FORGE_EXPORT . '/' . $file);
				}
				
				header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename = ' . $zipname);
				header('Content-Length: ' . filesize(FORGE_EXPORT . '/' . $zipname));
				readfile(FORGE_EXPORT . '/' . $zipname);
				
				exit;
			}
			
		}
		// end @15-Mar-16
		
		# ZZZZZZZZZ
		// export event to excel
		function export2($todo = null) {
			if ($todo == null) {
				# clear acaraHolder
				$this->session->unset_userdata("acaraHolder");
				
				$data['trans_active'] = 'dcjq-parent active';
				$data['menu_export2_active'] = 'color:#FFF';
				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_export2';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				$data['divisions'] = $this->Division->loadAll();
				$data['years'] = $this->Acara->loadYear();
				$this->load->view('acara/v_acara', $data);	
			}
			else if ($todo == 'excel') {
				$inputs = $this->input->post();
				$divisionCode = $inputs["divisionCode"];
				$year = $inputs["year"];
				
				//load our new PHPExcel library
				$this->load->library('excel');
				
				$objPHPExcel = new PHPExcel();
				
				$objPHPExcel->getProperties()->setCreator('evento.yogya.com')
							 ->setLastModifiedBy('evento.yogya.com developer')
							 ->setTitle('evento document')
							 ->setSubject('surat acara')
							 ->setDescription('rekap surat acara')
							 ->setKeywords('evento surat acara')
							 ->setCategory('evento file');
							 
				if ($divisionCode == 'All') {
					$aDivision = array('A', 'B', 'C', 'D', 'E');
					
					for ($sheetIndex = 0; $sheetIndex < sizeof($aDivision); $sheetIndex++) {
						$divisionCode = $aDivision[$sheetIndex];
						
						//sheet with index 0 already create, start from index 1
						if ($sheetIndex > 0) {
							$objPHPExcel->createSheet($sheetIndex);
						}
						
						//activate worksheet
						$objPHPExcel->setActiveSheetIndex($sheetIndex);
						$objPHPExcel->getActiveSheet()->setTitle($divisionCode);
						
						# ZZZZZZZZZ
						#$dataToExport = $this->Acara->loadRekapData($divisionCode);
						$dataToExport = $this->Acara->loadRekapDataNew($divisionCode, $year);
						
						//set cell content with some text
						$rowNum = 1;
						$objPHPExcel->getActiveSheet()
									->setCellValue('A' . $rowNum, 'NO SURAT')
									->setCellValue('B' . $rowNum, 'BRAND')
									->setCellValue('C' . $rowNum, 'NAMA PERUSAHAAN')
									->setCellValue('D' . $rowNum, 'KATEGORI')
									->setCellValue('E' . $rowNum, 'BENTUK ACARA')
									->setCellValue('F' . $rowNum, 'CABANG')
									->setCellValue('G' . $rowNum, 'MEKANISME ACARA')
									->setCellValue('H' . $rowNum, 'TANGGAL ACARA')
									->setCellValue('I' . $rowNum, 'TANGGAL APPROVE')
									->setCellValue('J' . $rowNum, 'NAMA MD')
									->setCellValue('K' . $rowNum, 'KETERANGAN')
									;
						
						//make the font become bold
						$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getFont()->setBold(true);
						
						// vertical align
						$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						
						// set row height
						$objPHPExcel->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(20);
						
						// set column width
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
						$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
						$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
						$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
						
						$rowNum++;
						foreach($dataToExport as $export) {
							
							# slice notes
							$aNotes = $this->sliceNotes($export->notes);
							$sForm = $aNotes['form'];
							$sForm = str_replace('&amp;', '&', $sForm);
							$sForm = str_replace('&gt;', '>', $sForm);
							$sMechanism = $aNotes['mechanism'];
							
							$objPHPExcel->getActiveSheet()
										->setCellValue('A' . $rowNum, $export->event_no)
										->setCellValue('B' . $rowNum, $export->brand)
										->setCellValue('C' . $rowNum, $export->supp_desc)
										->setCellValue('D' . $rowNum, $export->category_code)
										->setCellValue('E' . $rowNum, $sMechanism)
										->setCellValue('F' . $rowNum, $export->site_group)
										->setCellValue('G' . $rowNum, $sForm)
										->setCellValue('H' . $rowNum, $export->period)
										->setCellValue('I' . $rowNum, $export->tgl_approve)
										->setCellValue('J' . $rowNum, $export->first_signature)
										->setCellValue('K' . $rowNum, '')
										;
							
							$rowNum++;
						}
					}
				}
				else {
					$sheetIndex = 0;
					
					//activate worksheet
					$objPHPExcel->setActiveSheetIndex($sheetIndex);
					$objPHPExcel->getActiveSheet()->setTitle($divisionCode);
					
					# ZZZZZZZZZ
					#$dataToExport = $this->Acara->loadRekapData($divisionCode);
					$dataToExport = $this->Acara->loadRekapDataNew($divisionCode, $year);
					
					//set cell content with some text
					$rowNum = 1;
					$objPHPExcel->getActiveSheet()
								->setCellValue('A' . $rowNum, 'NO SURAT')
								->setCellValue('B' . $rowNum, 'BRAND')
								->setCellValue('C' . $rowNum, 'NAMA PERUSAHAAN')
								->setCellValue('D' . $rowNum, 'KATEGORI')
								->setCellValue('E' . $rowNum, 'BENTUK ACARA')
								->setCellValue('F' . $rowNum, 'CABANG')
								->setCellValue('G' . $rowNum, 'MEKANISME ACARA')
								->setCellValue('H' . $rowNum, 'TANGGAL ACARA')
								->setCellValue('I' . $rowNum, 'TANGGAL APPROVE')
								->setCellValue('J' . $rowNum, 'NAMA MD')
								->setCellValue('K' . $rowNum, 'KETERANGAN')
								;
					
					//make the font become bold
					$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getFont()->setBold(true);
					
					// vertical align
					$objPHPExcel->getActiveSheet()->getStyle('A' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('B' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('C' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('D' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('E' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('I' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('J' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('K' . $rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					
					// set row height
					$objPHPExcel->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(20);
					
					// set column width
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
					
					$rowNum++;
					foreach($dataToExport as $export) {
						# slice notes
						$aNotes = $this->sliceNotes($export->notes);
						$sForm = $aNotes['form'];
						$sForm = str_replace('&amp;', '&', $sForm);
						$sForm = str_replace('&gt;', '>', $sForm);
						$sMechanism = $aNotes['mechanism'];
						
						$objPHPExcel->getActiveSheet()
									->setCellValue('A' . $rowNum, $export->event_no)
									->setCellValue('B' . $rowNum, $export->brand)
									->setCellValue('C' . $rowNum, $export->supp_desc)
									->setCellValue('D' . $rowNum, $export->category_code)
									->setCellValue('E' . $rowNum, $sForm)
									->setCellValue('F' . $rowNum, $export->site_group)
									->setCellValue('G' . $rowNum, $sMechanism)
									->setCellValue('H' . $rowNum, $export->period)
									->setCellValue('I' . $rowNum, $export->tgl_approve)
									->setCellValue('J' . $rowNum, $export->first_signature)
									->setCellValue('K' . $rowNum, '')
									;
						
						$rowNum++;
					}
				}
				
				 // set active sheet to 0
				$objPHPExcel->setActiveSheetIndex(0);
				$filename = "rekap_acara.xls";
				
				header('Content-Type: application/vnd.ms-excel');
				header('Content-disposition: attachment; filename = ' . $filename);
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
				exit;
			}
		}
		
		public function ajax_list($is_printed, $is_md, $department=null, $division=null) {
			$list = $this->Event_model->get_datatables($is_printed, $is_md, $department, $division);
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $r) {
				$no++;
				$row = array();	

				// if (strlen($r->about)>70){
				// 	$about = substr($r->about, 0, 70)."...";
				// } else 	$about = $r->about;

				// if (strlen($r->propose_notes)>30){
				// 	$propose_notes = substr($r->propose_notes, 0, 30)."...";
				// } else 	$propose_notes = $r->propose_notes;


				if ($r->active==0){
					$style = "#FAD9DB";	
					if (!empty($r->id_venditore)) {
						$by = $r->updated_by;	
					}
					else {
						$by = $r->created_by;
					}
				}
				else {
					if (!empty($r->id_venditore)) {
						$style = "#DFF0D8";	
						$by = $r->updated_by;	
					}
					else {
						$style = "";	
						$by = $r->created_by;
					}
				}

				$row[] = $r->id;
				$row[] = $no;
				$row[] = $r->event_no;
				$row[] = $r->propose_by;
				$row[] = $r->propose_brand;
				$row[] = $r->propose_notes;
				$row[] = $r->about;
				$row[] = $r->tanggal;
				$row[] = $by;

				// button
				$delete_btn = "<a href='#deleteConfirm' data-id='".$r->id."' data-letter_number='".$r->event_no."' data-toggle='modal' class='btn_update btn btn-xs deleteTrigger' title='delete'>
									<i class='fa fa-trash-o'></i> 
								</a>&nbsp;";

				$preview_btn = "<a href='".base_url()."acara/preview/".$r->id."/0' class='btn_update btn btn-xs' title='preview' target='".$r->id."' name=''>
									<i class='fa fa-search'></i>
								</a>&nbsp;";

				$duplicate_btn = "<a href='".base_url()."acara/duplicate/".$r->id."' class='btn_update btn btn-xs' title='duplicate'>
									<i class='fa fa-copy'></i>
								</a>&nbsp;";

				if ($r->is_printed=='0' || $r->is_printed==NULL){
					$edit_btn =  "<a href='".base_url()."acara/edit/".$r->id."' class='btn_update btn btn-xs' title='edit'>
									<i class='fa fa-pencil'></i> 
								</a>&nbsp;";
					$cancel_btn = "";			
				} 
				else {
					$edit_btn = "";

					if ($r->active==0){
						$cancel_btn = "";
						$delete_btn = "";
						$duplicate_btn = "";
					} else {
						$cancel_btn = "<a href='#cancelConfirm' data-id='".$r->id."' data-letter_number='".$r->event_no."' data-toggle='modal' class='btn_update btn btn-xs cancelTrigger' title='cancel'>
										<i class='fa fa-times'></i>
									</a>";
					}
				}

				$row[] = $edit_btn . $delete_btn . $preview_btn . $duplicate_btn . $cancel_btn;
				$row[] = $style;

				$data[] = $row;
			}

			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->Event_model->count_all($is_printed, $is_md, $department, $division),
							"recordsFiltered" => $this->Event_model->count_filtered($is_printed, $is_md, $department, $division),
							"data" => $data,
					);
			//output to json format
			echo json_encode($output);

		}

		function all_list($msg, $is_printed=null, $department=null, $division=null) {
			$data['trans_active'] = 'dcjq-parent active';
			if ($is_printed==0 || $is_printed==null){
				$data['menu_acarabaru_active'] = 'color:#FFF';
				$data['title'] = "Terbaru";
				$data['is_printed'] = 0;
			}else {
				$data['menu_daftar_active'] = 'color:#FFF';
				$data['title'] = "";
				$data['is_printed'] = 1;
			}
			
			$data['msg'] = $this->my_message($msg);
			
			$data['head'] = 'acara/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'acara/v_all_list';
			$data['right_menu'] = 'acara/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			# clear acaraHolder
			$this->session->unset_userdata("acaraHolder");
			
			// load division
			$data['divisions'] = $this->Division->loadAll();

			$data['dept'] = $department;
			$data['div'] = $division;
			
			$this->load->view('acara/v_acara', $data);
		}
		
		public function isArticleHasDiscount() {
			$inputs = $this->input->post();
			$ret = $this->Acara->isArticleHasDiscount($inputs["tillcode"]);
			
			if ($ret)
				echo "has";
			else
				echo "hasnot";
		}
		
		public function isValidSpArticle() {
			$inputs = $this->input->post();
			$ret = $this->Acara->isValidSpArticle($inputs["tillcode"]);
			
			if ($ret)
				echo "validsp";
			else
				echo "invalidsp";
		}
		
		public function loadMdByDivision() {
			$inputs = $this->input->post();
			$mds = $this->Acara->loadMdByDivision($inputs["divisionCode"]);
			
			$opts = '<option value="">Pilih MD..</option>';
			foreach($mds as $md) {
				$opts .= '<option value="' . $md->name . '">' . $md->name . '</option>';
			}
			echo $opts;
		}
		
		public function add($step = null) {
			$data['trans_active'] = 'dcjq-parent active';
			$data['menu_input_active'] = 'color:#FFF';
			
			// // attach xinha editor
			// $this->load->file(APPPATH.'third_party/xinha_pi_acara.php');
			// $data['xinha_java']= javascript_xinha(array('notes')); // this line for the xinha

			if ($step == null) {
				$acaraHolder = $this->session->userdata("acaraHolder");
				
				$divisionCode = isset($acaraHolder["divisionCode"]) ? $acaraHolder["divisionCode"] : "";
				$firstSignature = isset($acaraHolder["firstSignature"]) ? $acaraHolder["firstSignature"] : "";
				$mds = $this->Acara->loadMdByDivision($divisionCode);
			    $opts = '<option value="">Pilih MD..</option>';
			    foreach($mds as $md) {
				    if ($firstSignature == $md->name) $sel = 'selected="selected"'; else $sel = '';
					$opts .= '<option ' . $sel . ' value="' . $md->name . '">' . $md->name . '</option>';
			    }
				
				$data['isSameDate'] = isset($acaraHolder["isSameDate"]) ? 1 : 0;
				$data['isSameLocation'] = isset($acaraHolder["isSameLocation"]) ? 1 : 0;
				$data['isSpecialEvent'] = isset($acaraHolder["isSpecialEvent"]) ? 1 : 0;
				$data["opts"] = $opts;
				$data['acaraHolder'] = $acaraHolder;
				$data['divisions'] = $this->Division->loadAll();
				$data['templates'] = $this->Acara->loadAllTemplate();
				$data['today'] = date('d-m-Y');
				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_add_new';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				
				$this->load->view('acara/v_acara', $data);	
			}
			else if ($step == 'new') {	
				# clear acaraHolder
				$this->session->unset_userdata("acaraHolder");
				
				$data['isSameDate'] = 0;
				$data['isSameLocation'] = 0;
				$data['isSpecialEvent'] = 0;
				$data['acaraHolder'] = $this->session->userdata("acaraHolder");
				$data['divisions'] = $this->Division->loadAll();
				$data['templates'] = $this->Acara->loadAllTemplate();
				$data['today'] = date('d-m-Y');
				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_add_new';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				
				$this->load->view('acara/v_acara', $data);	
			}
			else if ($step == 'next') {
				$inputs = $this->input->post();
				if (!isset($inputs["divisionCode"])) {
					header("Location: " . base_url() . "acara/add");
					exit;
				}
				
				$this->session->set_userdata("acaraHolder", $inputs);
				$tmplSrc = substr($inputs["templateCode"], 0, 1);
				if ($tmplSrc == "S" || $tmplSrc == "C")
					$responsibilityDefault = "4060";	
				else
					$responsibilityDefault = "5050";
				
				$data['isSameDate'] = isset($inputs["isSameDate"]) ? 1 : 0;
				$data['isSameLocation'] = isset($inputs["isSameLocation"]) ? 1 : 0;
				$data['isSpecialEvent'] = isset($inputs["isSpecialEvent"]) ? 1 : 0;
				$data['categories'] = $this->Acara->loadCategoryByDivision($inputs["divisionCode"]);
				$data['stores'] = $this->Acara->loadAllStore();
				$data['locations'] = $this->Acara->loadAllLocation();
				$data['division'] = $inputs["divisionCode"];
				$data['today'] = date('d-m-Y');
				$data['responsibilityDefault'] = $responsibilityDefault;
				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_add_next';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				
				$this->load->view('acara/v_acara', $data);	
			}
			
		}
		
		public function edit($id, $step = null) {
			$data['trans_active'] = 'dcjq-parent active';
			$data['menu_input_active'] = 'color:#FFF';

			
			
			if ($step == null) {
				$acaraHolder = $this->session->userdata("acaraHolder");
				$aResult = $this->Acara->load($id);
				
				$data["id"] = $id;
				$event = $aResult["event"];
				$data["event"] = $event;
				$divisionCode = isset($event[0]->division_code) ? $event[0]->division_code : "";
				$firstSignature = isset($event[0]->first_signature) ? $event[0]->first_signature : "";
				$mds = $this->Acara->loadMdByDivision($divisionCode);
			    $opts = '<option value="">Pilih MD..</option>';
			    foreach($mds as $md) {
				    if ($firstSignature == $md->name) $sel = 'selected="selected"'; else $sel = '';
					$opts .= '<option ' . $sel . ' value="' . $md->name . '">' . $md->name . '</option>';
			    }
				
				if (isset($acaraHolder["eventNo"])) {
					$data['isSameDate'] = isset($acaraHolder["isSameDate"]) ? 1 : 0;
					$data['isSameLocation'] = isset($acaraHolder["isSameLocation"]) ? 1 : 0;
					$data['isSpecialEvent'] = isset($acaraHolder["isSpecialEvent"]) ? 1 : 0;
				}
				else {
					$data['isSameDate'] = isset($event[0]->is_same_date) ? $event[0]->is_same_date : 0;
					$data['isSameLocation'] = isset($event[0]->is_same_location) ? $event[0]->is_same_location : 0;
					$data['isSpecialEvent'] = isset($event[0]->is_special_event) ? $event[0]->is_special_event : 0;
				}
				
				// attach xinha editor
				// $this->load->file(APPPATH.'third_party/xinha_pi_acara.php');
				// $data['xinha_java']= javascript_xinha(array('notes')); // this line for the xinha

				$data["opts"] = $opts;
				$data["divisionDesc"] = $this->Acara->getDivisionName($divisionCode);
				$data['acaraHolder'] = $acaraHolder;
				$data['templates'] = $this->Acara->loadAllTemplate();
				//$data['divisions'] = $this->Division->loadAll();
				$data['today'] = date('d-m-Y');
				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_edit';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				
				$this->load->view('acara/v_acara', $data);	
			}
			else if ($step == 'next') {
				$inputs = $this->input->post();
				if (!isset($inputs["divisionCode"])) {
					header("Location: " . base_url() . "acara/list");
					exit;
				}
				
				$this->session->set_userdata("acaraHolder", $inputs);
				$tmplSrc = substr($inputs["templateCode"], 0, 1);
				if ($tmplSrc == "S" || $tmplSrc == "C")
					$responsibilityDefault = "4060";	
				else
					$responsibilityDefault = "5050";
				$aResult = $this->Acara->load($id);	
				$eventItem = $aResult["event_item"];
				
				$idx = 0;
				$tillcodeRows = "";
				foreach($eventItem as $eItem) {
					$tillcodeRows .= 	"<tr>" . 
											"<td class='eventNotes' id='eventNotes-" . $idx . "'>" . $eItem->notes . "</td>" . 
											"<td class='eventTillcode' id='eventTillcode-" . $idx . "'>" . $eItem->tillcode . "</td>" .
											"<td class='eventSupplierCode' id='eventSupplierCode-" . $idx . "'>" . $eItem->supp_code . "</td>" .
											"<td class='eventKota' id='eventKota-" . $idx . "'>" . $eItem->city . "</td>" .
											"<td class='eventCategoryCode' id='eventCategoryCode-" . $idx . "'>" . $eItem->category_desc . "</td>" .
											"<td class='eventSupplierResponsibility al-right' id='eventSupplierResponsibility-" . $idx . "'>" . $eItem->supp_responsibility . "</td>" .
											"<td class='eventYdsResponsibility al-right' id='eventYdsResponsibility-" . $idx . "'>" . $eItem->yds_responsibility . "</td>" .
											"<td class='eventIsPkp' id='eventIsPkp-" . $idx . "'>" . ($eItem->is_pkp == 1 ? "PKP" : "NPKP") . "</td>" .
											"<td class='eventMargin al-right' id='eventMargin-" . $idx . "'>" . $eItem->tax . "</td>" . 
											"<td class='eventSp al-right' id='eventSp-" . $idx . "'>" . ($eItem->special_price == 0 ? "&nbsp;" : $eItem->special_price) . "</td>" . 
											"<td class='al-center'>" . 
												"<a id=\"edit-" . $idx . "\"
													data-id=\"" . $idx . "\"
													data-notes=\"" . $eItem->notes . "\"
													data-tillcode=\"" . $eItem->tillcode . "\"
													data-supp_code=\"" . $eItem->supp_code . "\"
													data-kota=\"" . $eItem->city . "\"
													data-category_desc=\"" . $eItem->category_desc . "\"
													data-supp_responsibility=\"" . $eItem->supp_responsibility . "\"
													data-yds_responsibility=\"" . $eItem->yds_responsibility . "\"
													data-is_pkp=\"" . $eItem->is_pkp . "\"
													data-tax=\"" . $eItem->tax . "\"
													data-is_sp=\"" . $eItem->is_sp . "\"
													data-special_price=\"" . $eItem->special_price . "\"
													data-toggle='modal' data-target='#editForm' class='btn_update btn btn-xs editTrigger'>" . 
													"<i class='fa fa-pencil'></i> edit" . 
												"</a>" . 
											"</td>" . 
											"<td class='al-center'>" . 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" . 
													"<i class='fa fa-trash-o'></i> del" . 
												"</a>" . 
											"</td>" . 
										"</tr>";
					$idx++;
				}
				
				# rowcount for tillcode
				$cntX = $idx;
				
				$event = $aResult["event"];
				
				# values from db
				$isSameDate = isset($event[0]->is_same_date) ? $event[0]->is_same_date : 0;
				$isSameLocation = isset($event[0]->is_same_location) ? $event[0]->is_same_location : 0;
				$isSpecialEvent = isset($event[0]->is_special_event) ? $event[0]->is_special_event : 0;
				
				# load data
				if ($isSameDate)
					$eventDate = $aResult["event_same_date"];
				else
					$eventDate = $aResult["event_date"];
				
				if ($isSameLocation)
					$eventLocation = $aResult["event_same_location"];
				else
					$eventLocation = $aResult["event_location"];
				
				# change these values	
				$isSameDate = isset($inputs["isSameDate"]) ? 1 : 0;
				$isSameLocation = isset($inputs["isSameLocation"]) ? 1 : 0;
				$isSpecialEvent = isset($inputs["isSpecialEvent"]) ? 1 : 0;
				
				// ternyata ga boleh gini
				// khusus dr venditore
				//if ($event[0]->id_venditore !== "") {
				//	if ($isSameLocation)
				//		$eventLocation = $aResult["event_same_location"];
				//	else
				//		$eventLocation = $aResult["event_location"];
				//}
				
				$idx = 0;
				$dateRows = "";
				$tdHarga = "";
				$dataHarga = "";
				$isExc = "N";
				foreach($eventDate as $eDate) {
					
					if ($eDate->harga_faktur > 0 || $eDate->harga_jual > 0) {
						$isExc = "Y";
						$tdHarga = "<td class='dateHargaFaktur al-right' id='dateHargaFaktur-" . $idx . "'>" . $eDate->harga_faktur_f . "</td>" ;
						$tdHarga .= "<td class='dateHargaJual al-right' id='dateHargaJual-" . $idx . "'>" . $eDate->harga_jual_f . "</td>" ;
						$dataHarga = " data-harga_faktur=\"" . $eDate->harga_faktur_f . "\" ";
						$dataHarga .= " data-harga_jual=\"" . $eDate->harga_jual_f . "\" ";
					}
					
					if ($isSameDate) {
						$dateRows .= 	"<tr>" . 
											"<td class='dateEventStartDate' id='dateEventStartDate-" . $idx . "'>" . $eDate->date_start . "</td>" . 
											"<td class='dateEventEndDate' id='dateEventEndDate-" . $idx . "'>" . $eDate->date_end . "</td>" .
											$tdHarga . 
											"<td class='al-center'>" . 
												"<a id=\"edit3-" . $idx . "\"
													data-id=\"" . $idx . "\"
													data-tillcode=\"\"
													data-date_start=\"" . $eDate->date_start . "\"
													data-date_end=\"" . $eDate->date_end . "\"
													" . $dataHarga . "
													data-toggle='modal' data-target='#editForm3' class='btn_update btn btn-xs editTrigger3 link_edit3'>" . 
													"<i class='fa fa-pencil'></i> edit" . 
												"</a>" . 
											"</td>" . 
											"<td class='al-center'>" . 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" . 
													"<i class='fa fa-trash-o'></i> del" . 
												"</a>" . 
											"</td>" . 
										"</tr>";	
					}
					else {
						$tillcode = ($isSameDate ? "&nbsp;" : (isset($eDate->tillcode) ? $eDate->tillcode : "&nbsp;"));
						$dateRows .= 	"<tr>" . 
											"<td class='dateTillcode' id='dateTillcode-" . $idx . "'>" . $tillcode . "</td>" . 
											"<td class='dateEventStartDate' id='dateEventStartDate-" . $idx . "'>" . $eDate->date_start . "</td>" . 
											"<td class='dateEventEndDate' id='dateEventEndDate-" . $idx . "'>" . $eDate->date_end . "</td>" .
											$tdHarga . 
											"<td class='al-center'>" . 
												"<a id=\"edit3-" . $idx . "\"
													data-id=\"" . $idx . "\"
													data-tillcode=\"" . $tillcode . "\"
													data-date_start=\"" . $eDate->date_start . "\"
													data-date_end=\"" . $eDate->date_end . "\"
													" . $dataHarga . "
													data-toggle='modal' data-target='#editForm3' class='btn_update btn btn-xs editTrigger3 link_edit3'>" . 
													"<i class='fa fa-pencil'></i> edit" . 
												"</a>" . 
											"</td>" . 
											"<td class='al-center'>" . 
												"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" . 
													"<i class='fa fa-trash-o'></i> del" . 
												"</a>" . 
											"</td>" . 
										"</tr>";
					}
					$idx++;
				}
				
				# rowcount for date
				$cntY = $idx;
				
				$idx = 0;
				$locationRows = "";
				foreach($eventLocation as $eLocation) {
					if ($isSameLocation) {
						$locationRows .=   "<tr>" . 
												"<td class='locationLocationCode' id='locationLocationCode-" . $idx . "'>" . $eLocation->loc_desc . "</td>" . 
												"<td class='locationStoreCode' id='locationStoreCode-" . $idx . "'>" . $eLocation->store_desc . "</td>" . 
												"<td class='al-center'>" . 
													"<a id=\"edit2-" . $idx . "\"
														data-id=\"" . $idx . "\"
														data-tillcode=\"\"
														data-loc_desc=\"" . $eLocation->loc_desc . "\"
														data-store_desc=\"" . $eLocation->store_desc . "\"
														data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2 link_edit2'>" . 
														"<i class='fa fa-pencil'></i> edit" . 
													"</a>" . 
												"</td>" . 
												"<td class='al-center'>" . 
													"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" . 
														"<i class='fa fa-trash-o'></i> del" . 
													"</a>" . 
												"</td>" . 
											"</tr>";		
					}
					else {
						$tillcode = ($isSameLocation ? "&nbsp;" : (isset($eLocation->tillcode) ? $eLocation->tillcode : "&nbsp;"));
						$locationRows .=   "<tr>" . 
												"<td class='locationTillcode' id='locationTillcode-" . $idx . "'>" . $tillcode . "</td>" . 
												"<td class='locationLocationCode' id='locationLocationCode-" . $idx . "'>" . $eLocation->loc_desc . "</td>" . 
												"<td class='locationStoreCode' id='locationStoreCode-" . $idx . "'>" . $eLocation->store_desc . "</td>" .
												"<td class='al-center'>" . 
													"<a id=\"edit2-" . $idx . "\"
														data-id=\"" . $idx . "\"
														data-tillcode=\"" . $tillcode . "\"
														data-loc_desc=\"" . $eLocation->loc_desc . "\"
														data-store_desc=\"" . $eLocation->store_desc . "\"
														data-toggle='modal' data-target='#editForm2' class='btn_update btn btn-xs editTrigger2 link_edit2'>" . 
														"<i class='fa fa-pencil'></i> edit" . 
													"</a>" . 
												"</td>" . 
												"<td class='al-center'>" . 
													"<a data-id='' data-toggle='modal' data-target='#myModal' class='btn_update btn btn-xs btnRowDelete'>" . 
														"<i class='fa fa-trash-o'></i> del" . 
													"</a>" . 
												"</td>" . 
											"</tr>";	
					}
					$idx++;
				}
				
				# rowcount for location
				$cntZ = $idx;
				
				// attach xinha editor
				// $this->load->file(APPPATH.'third_party/xinha_pi_acara.php');
				// $data['xinha_java']= javascript_xinha(array('notes')); // this line for the xinha

				$data["isExc"] = $isExc;
				$data["cntX"] = $cntX;
				$data["cntY"] = $cntY;
				$data["cntZ"] = $cntZ;
				$data["id"] = $id;
				$data["isSameDate"] = $isSameDate;
				$data["isSameLocation"] = $isSameLocation;
				$data["isSpecialEvent"] = $isSpecialEvent;
				$data["dateRows"] = $dateRows;
				$data["locationRows"] = $locationRows;
				$data["tillcodeRows"] = $tillcodeRows;
				$data['categories'] = $this->Acara->loadCategoryByDivision($inputs["divisionCode"]);
				$data['stores'] = $this->Acara->loadAllStore();
				$data['locations'] = $this->Acara->loadAllLocation();
				$data['division'] = $inputs["divisionCode"];
				$data['today'] = date('d-m-Y');
				$data['responsibilityDefault'] = $responsibilityDefault;

				$data['head'] = 'acara/v_head';
				$data['top_menu'] = 'template/v_top_menu';
				$data['left_menu'] = 'template/v_left_menu';
				$data['content'] = 'acara/v_edit_next';
				$data['right_menu'] = 'acara/v_right_menu';
				$data['footer'] = 'template/v_footer';
				
				$this->load->view('acara/v_acara', $data);	
			}
			
		}
		
		function get_template($id){
			$list = $this->Event_model->get_template($id);
			$nama_supp = $this->get_supplier_header($id);

			if(count($list)>0){
				foreach($list as $r){
					//cek header
					$sex = explode(' ', $r->toward);
					$city = "";

					$rheader =  str_replace(
						array("#TGL_SURAT","#NOMOR_SURAT_ACARA","#LAMPIRAN",
							  "#ABOUT", "#TOWARD", "#NAMA_SUPPLIER", "#PURPOSE",
							  "#DPURPOSE", "#KOTA", "#FAX", "#SEX"
						),
						array($this->to_dMY($r->letter_date), $r->event_no, $r->attach,
							  $r->about, $r->toward, $nama_supp, ($r->purpose==""?"":" &rarr; ".$r->purpose),
							  $r->purpose, $city, ($r->fax==""?"":" - ".$r->fax), $sex[0]
						),
						$r->header
					);
					
					$ttd1 = $this->Event_model->get_signature1_data($r->first_signature);
					$jabatan = $ttd1[0];
					
					//sampah taro dulu
					$email = $ttd1[1];
					
					//cek acara khusus
					$khusus = 0;
					$isKhusus = $this->Event_model->get_calculate($id);

					foreach ($isKhusus as $v) {
						if (strpos(strtolower($v->disc_label), 'buy 1 get 1') || strpos(strtolower($v->disc_label), 'buy 1 get 2') || strpos(strtolower($v->disc_label), 'buy 2 get 1')  || strpos(strtolower($v->disc_label), 'buy one get one')) {
						    $khusus++;	
						} 
					}
					
					if ($khusus>=1) {
						$noteTambahan = "Notes : Harga jual yang tercantum dalam contoh perhitungan BUY 1 GET 1, BUY 1 GET 2, BUY 2 GET 1,  adalah harga nett yang dibayarkan oleh konsumen. ";
					} else {
						$noteTambahan = "";
					}

					//cek footer
					$rfooter =  str_replace(
						array("#PARENTNOTES", "#FIRST_SIGNATURE", "#SECOND_SIGNATURE", "#APPROVED_BY", "#CC", "#MD", "#TITLEMD", "#DMM", "#TITLEDMM"),
						array((trim($r->notes) != "" ? "Notes : <pre><code>".$r->notes."</code></pre><br>".$noteTambahan."<br><br>":($noteTambahan==""?"":$noteTambahan."<br><br>")), $r->first_signature,$r->second_signature,$r->approved_by, $r->cc, $r->first_signature, $jabatan, $r->dmm_name, $r->dmm_title ),
						$r->footer
					);
					
					//$rnotes =  $r->template_notes;
					$rnotes =  str_replace(
						array("#EMAIL"),
						array($email),
						$r->template_notes
					);;
					
				} 
			}
			else {
				$rheader = null; 
				$rfooter = null;
				$rnotes = null;

			}
			return array(
					    'rheader' => $rheader,
					    'rfooter' => $rfooter,
					    'rnotes' => $rnotes
					);
		}

		function format_tanggal_event_date($id, $date_start, $date_end, $tillcode, $index){
			$max_date_start = date("Y-m-d", strtotime($this->Event_model->get_max_datestart_event_date($id, $tillcode)));//25-01-2015
			$max_date_end = date("Y-m-d", strtotime($this->Event_model->get_max_dateend_event_date($id, $tillcode)));//27-01-2015
			$count = $this->Event_model->get_count_event_date($id, $tillcode);
			$count_all = $this->Event_model->get_count_event_date_all($id, $tillcode);

			if ($max_date_end < $max_date_start){
				$max_date = $max_date_start;
			} else {
				$max_date = $max_date_end;
			}

			$last_date = date("Y-m-t", strtotime($max_date));
			$date_start_fmt = date("Y-m-d", strtotime($date_start));

			if (($date_end==null) || ($date_end=="")){
				$date_end_fmt = null;
			} else {
				$date_end_fmt = date("Y-m-d", strtotime($date_end));
			}

			
			$date = "";
			if ($count_all>1){	
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
					if ($date_start_fmt<=$last_date){

						if ($count>1){
							//cek jika max dmy
							if ($count==$index){
								$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>';
							} else {
								$date .= '<td>'.$this->to_date($date_start_fmt).',</td>';
							}
						} else {
							$date .= '<td>'.$this->to_date($date_start_fmt).',</td>'; 
						}

					} else {
						$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>'; 
					}
				} 

				else {

					if ($date_start_fmt<=$date_end_fmt){
						$date .= '<td>'.$this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					} else{
						$date .= '<td>'.$this->to_dMY($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					}
					


				}
			}
			else {
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
					

					
						$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>'; 
					
				} 

				else {
					
					//jika dalam satu bulan
					if (substr($date_start_fmt, 5, 2) == substr($date_end_fmt, 5, 2)){
						$date .= '<td>'.$this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					} else{
						$date .= '<td>'.$this->to_dM($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					}
					


				}
			}	
			return $date;

		}

		function format_tanggal_event_same_date($id, $date_start, $date_end, $index){
			$max_date_start = date("Y-m-d", strtotime($this->Event_model->get_max_datestart_event_same_date($id)));//25-01-2015
			$max_date_end = date("Y-m-d", strtotime($this->Event_model->get_max_dateend_event_same_date($id)));//27-01-2015
			$count = $this->Event_model->get_count_event_same_date($id);
			$count_all = $this->Event_model->get_count_event_same_date_all($id);

			if ($max_date_end < $max_date_start){
				$max_date = $max_date_start;
			} else {
				$max_date = $max_date_end;
			}

			$last_date = date("Y-m-t", strtotime($max_date));
			$date_start_fmt = date("Y-m-d", strtotime($date_start));

			if (($date_end==null) || ($date_end=="")){
				$date_end_fmt = null;
			} else {
				$date_end_fmt = date("Y-m-d", strtotime($date_end));
			}

			
			$date = "";
			if ($count_all>1){	
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
					if ($date_start_fmt<=$last_date){

						if ($count>1){
							if ($count==$index){
								$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>'; 
							} else {
								$date .= '<td>'.$this->to_date($date_start_fmt).',</td>'; 
							}
						} else {
							
							$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>'; 
							
							
						}

					} else {
						$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>'; 
					}
				} 

				else {

					if ($date_start_fmt<=$date_end_fmt){
						$date .= '<td>'.$this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					} else{
						$date .= '<td>'.$this->to_dMY($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					}
					
				}

			} else {
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
					
						$date .= '<td>'.$this->to_dMY($date_start_fmt).',</td>'; 
					
				} 

				else {

					if (substr($date_start_fmt, 5, 2) == substr($date_end_fmt, 5, 2)){
						$date .= '<td>'.$this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					} else{
						$date .= '<td>'.$this->to_dM($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).',</td>'; 
					}

					


				}
			}	

			return $date;

		}
		
		function format_tanggal_event_date_new($id, $date_start, $date_end, $tillcode, $index){
			$max_date_start = date("Y-m-d", strtotime($this->Event_model->get_max_datestart_event_date($id, $tillcode)));//25-01-2015
			$max_date_end = date("Y-m-d", strtotime($this->Event_model->get_max_dateend_event_date($id, $tillcode)));//27-01-2015
			$count = $this->Event_model->get_count_event_date($id, $tillcode);
			$count_all = $this->Event_model->get_count_event_date_all($id, $tillcode);

			if ($max_date_end < $max_date_start){
				$max_date = $max_date_start;
			} else {
				$max_date = $max_date_end;
			}

			$last_date = date("Y-m-t", strtotime($max_date));
			$date_start_fmt = date("Y-m-d", strtotime($date_start));

			if (($date_end==null) || ($date_end=="")){
				$date_end_fmt = null;
			} else {
				$date_end_fmt = date("Y-m-d", strtotime($date_end));
			}

			$date = "";
			if ($count_all>1){	
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
					if ($date_start_fmt<=$last_date){

						if ($count>1){
							//cek jika max dmy
							if ($count==$index){
								$date .= $this->to_dMY($date_start_fmt).', ';
							} else {
								$date .= $this->to_dM($date_start_fmt).', ';
							}
						} else {
							$date .= $this->to_dMY($date_start_fmt).', '; //
						}

					} else {
						$date .= $this->to_dMY($date_start_fmt).', '; 
					}
				} 
				else {
					if ($date_start_fmt<=$date_end_fmt){
						$date .= $this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					} else{
						$date .= $this->to_dMY($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					}
				}
			}
			else {
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
						$date .= $this->to_dMY($date_start_fmt).', '; 
				} 
				else {	
					//jika dalam satu bulan
					if (substr($date_start_fmt, 5, 2) == substr($date_end_fmt, 5, 2)){
						$date .= $this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					} else{
						$date .= $this->to_dM($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					}
				}
			}
			
			return $date;
		}
		
		function format_tanggal_event_same_date_new($id, $date_start, $date_end, $index){
			$max_date_start = date("Y-m-d", strtotime($this->Event_model->get_max_datestart_event_same_date($id)));//25-01-2015
			$max_date_end = date("Y-m-d", strtotime($this->Event_model->get_max_dateend_event_same_date($id)));//27-01-2015
			$count = $this->Event_model->get_count_event_same_date($id);
			$count_all = $this->Event_model->get_count_event_same_date_all($id);

			if ($max_date_end < $max_date_start){
				$max_date = $max_date_start;
			} else {
				$max_date = $max_date_end;
			}

			$last_date = date("Y-m-t", strtotime($max_date));
			$date_start_fmt = date("Y-m-d", strtotime($date_start));

			if (($date_end==null) || ($date_end=="")){
				$date_end_fmt = null;
			} else {
				$date_end_fmt = date("Y-m-d", strtotime($date_end));
			}

			$date = "";
			if ($count_all>1){	
				if (($date_end_fmt==null) || ($date_end_fmt=="")){
					if ($date_start_fmt<=$last_date){
						if ($count>1){
							if ($count==$index){
								$date .= $this->to_dMY($date_start_fmt).', '; 
							} else {
								$date .= $this->to_dM($date_start_fmt).', '; 
							}
						} else {
							$date .= $this->to_dMY($date_start_fmt).', '; 						
						}
					} else {
						$date .= $this->to_dMY($date_start_fmt).', '; 
					}
				} 
				else {
					if ($date_start_fmt<=$date_end_fmt){
						//cek dalam bulan yang sama
						if (substr($date_start_fmt, 5, 2) == substr($date_end_fmt, 5, 2)){
							$date .= $this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
						}else {
							$date .= $this->to_dM($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
						}
					} else{
						$date .= $this->to_dMY($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					}
				}
			} else {
				if (($date_end_fmt==null) || ($date_end_fmt=="")){	
						$date .= $this->to_dMY($date_start_fmt).', '; 
				} 
				else {
					if (substr($date_start_fmt, 5, 2) == substr($date_end_fmt, 5, 2)){
						$date .= $this->to_date($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					} else{
						$date .= $this->to_dM($date_start_fmt).' - '.$this->to_dMY($date_end_fmt).', '; 
					}
				}
			}	

			return $date;
		}
		
		function get_event_date($id, $tillcode){
			//get tanggal by event id n tillcode
			$date = $this->Event_model->get_event_date($id, $tillcode);
			//$count = $this->Event_model->get_count_event_date($id);

			$rdate = "<table border='0' cellpadding='0' cellspacing='0' class='tb_event_date'>";				
			$date_tmp = "";					
			$x=0;
			
			$rdate .= "<tr><td>";
			foreach ($date as $res) :
				$x++;
				#if (($x%2 != 0)){
				#	$rdate .= "<tr>";
				#}
				
				#$rdate .= $this->format_tanggal_event_date($id, $res->date_start, $res->date_end, $res->tillcode, $x);
				$rdate .= $this->format_tanggal_event_date_new($id, $res->date_start, $res->date_end, $res->tillcode, $x);
			endforeach;
			$rdate .= "</td></tr>";
			
			$rdate .= '</table>';

			#$vlocation .= "<tr><td>Tanggal</td>
			#					<td>:</td>
			#					<td>".str_replace(",</td></table>", "</td></table>", $rdate)."</td>
			#				</tr>";
			
			$rdate = $this->str_lreplace(", ", "", $rdate);			
			$date_tmp .= "<tr><td>Tanggal</td>
							<td>:</td>
							<td>".$rdate."</td>
						</tr>";
			
			$same_location = $this->Event_model->is_same_location($id);
			if ($same_location=='1'){
				$date_tmp .=  "<tr><td colspan='3'><br></td></tr>";
			} 

			return $date_tmp;
				
		}

		function get_event_same_date($id){
			//get tanggal by event id
			$date = $this->Event_model->get_event_same_date($id);
			//$count = $this->Event_model->get_count_event_same_date($id);
			
			$rdate = "<table border='0' cellpadding='0' cellspacing='0' class='tb_event_same_date'>";				
			$date_tmp = "";			
			$x=0;
			
			$rdate .= "<tr><td>";
			$date_str = "";

			foreach ($date as $res) :
				$x++;
				#if (($x%2 != 0)){
				#	$rdate .= "<tr>";
				#}
				
				#$rdate .= $this->format_tanggal_event_same_date($id, $res->date_start, $res->date_end, $x);
				
				if ($res->harga_faktur!=null || $res->harga_faktur!="" ){
					$date_str .= $this->format_tanggal_event_same_date_new($id, $res->date_start, $res->date_end, $x);
				}else {
					$rdate .= $this->format_tanggal_event_same_date_new($id, $res->date_start, $res->date_end, $x);
				}
				
			endforeach;

			if ($res->harga_faktur!=null || $res->harga_faktur!="" ){
				$rdate .= $date_str = implode(', ',array_unique(explode(', ', $date_str)));
			}
			
			$rdate .= "</td></tr>";
			
			$rdate .= '</table>';
			
			#$date_tmp .= "<tr><td>Tanggal</td>
			#				<td>:</td>
			#				<td>".str_replace(",</td></table>", "</td></table>", $rdate)."</td>
			#			</tr>";
			
			$rdate = $this->str_lreplace(", ", "", $rdate);			
			$date_tmp .= "<tr><td>Tanggal</td>
							<td>:</td>
							<td>".$rdate."</td>
						</tr>";
			
			#$same_location = $this->Event_model->is_same_location($id);
			/*if ($same_location=='1'){
				$date_tmp .=  "<tr><td colspan='3'><br></td></tr>";	
			} */
			
			return $date_tmp;
		}
		
		function get_event_same_location($id){
			$rlocation = $this->Event_model->get_event_same_location($id);
			
			$i=0;
			$tmp_loc = "<table border=0 class='tb_event_same_location'>";
			$vlocation = "";
			
			foreach ($rlocation as $res) :
				$i++;
				if (($i%2 != 0)){
					$tmp_loc .= "<tr>";
				} 

				//$tmp_loc .= $res->loc_desc." <b>".$res->store_desc."</b>, ";
				$tmp_loc .= "<td>".$res->loc_desc." <b>".$res->store_desc.",</b></td>";				

			endforeach;
			
			$tmp_loc .= "</table>";

			$vlocation .= "<tr>
								<td>Tempat Acara</td>
								<td>:</td>
								<td>".str_replace(",</b></td></table>", "</b></td></table>", $tmp_loc)."</td>
							</tr>
							";
			return $vlocation;

		}
		
		function get_event_location($id, $tillcode){
			$rlocation = $this->Event_model->get_event_location($id, $tillcode);

			$i=0;
			$tmp_loc = "<table border=0 class='tb_event_location'>";
			$vlocation = "";
			foreach ($rlocation as $res) :
				$i++;
				if (($i%2 != 0)){
					$tmp_loc .= "<tr>";
				}
				
				$tmp_loc .= "<td>".$res->loc_desc." <b>".$res->store_desc.",</b></td>";	

			endforeach;

			$tmp_loc .= "</table>";

			$vlocation .= "<tr>
								<td>Tempat Acara</td>
								<td>:</td>
								<td>".str_replace(",</b></td></table>", "</b></td></table>", $tmp_loc)."</td>
							</tr>
							<tr><td colspan='3'>&nbsp;</td></tr>
							";
			return $vlocation;				
		}
		
		function get_event_location_new($id, $tillcode){
			$rlocation = $this->Event_model->get_event_location($id, $tillcode);
			$vlocation = "";
			
			for ($i = 0; $i < sizeof($rlocation); $i+=2) {
				
				if ($i == 0) {
					$label = "Tempat Acara";
					$colon = ":";
				}
				else {
					$label = "&nbsp;";
					$colon = "&nbsp;";		
				}
				
				$tblLoc = "<table cellpadding='0' cellspacing='0' style='width: 100%' border='0'><tr>";
				$tblLoc .= "<td style='width: 50%'>" . $rlocation[$i]->loc_desc . " <b>" . $rlocation[$i]->store_desc . ",</b></td>";
				$tblLoc .= "<td>" . (isset($rlocation[$i+1]) ? $rlocation[$i+1]->loc_desc . " <b>" . $rlocation[$i+1]->store_desc . ",</b>" : "&nbsp;") . "</td>";
				$tblLoc .= "</tr></table>";
				
				$vlocation .= "<tr>
								<td>" . $label . "</td>
								<td>" . $colon . "</td>
								<td>" . $tblLoc . "</td>
							  </tr>
							";
			}
			
			$vlocation = $this->str_lreplace(",", "", $vlocation);
			$vlocation .= "<tr><td colspan='3'>&nbsp;</td></tr>";
			
			return $vlocation;		
		}
		
		function get_event_same_location_new($id){
			$rlocation = $this->Event_model->get_event_same_location($id);

			$vlocation = "";
			
			for ($i = 0; $i < sizeof($rlocation); $i+=2) {
				
				if ($i == 0) {
					$label = "Tempat Acara";
					$colon = ":";
				}
				else {
					$label = "&nbsp;";
					$colon = "&nbsp;";		
				}
				
				$tblLoc = "<table cellpadding='0' cellspacing='0' style='width: 100%' border='0'><tr>";
				$tblLoc .= "<td style='width: 50%'>" . $rlocation[$i]->loc_desc . " <b>" . $rlocation[$i]->store_desc . ",</b></td>";
				$tblLoc .= "<td>" . (isset($rlocation[$i+1]) ? $rlocation[$i+1]->loc_desc . " <b>" . $rlocation[$i+1]->store_desc . ",</b>" : "&nbsp;") . "</td>";
				$tblLoc .= "</tr></table>";
				
				$vlocation .= "<tr>
								<td>" . $label . "</td>
								<td>" . $colon . "</td>
								<td>" . $tblLoc . "</td>
							  </tr>
							";
			}
			
			$vlocation = $this->str_lreplace(",", "", $vlocation);
			
			return $vlocation;	
		}
		

		function str_lreplace($search, $replace, $subject) {
			$pos = strrpos($subject, $search);

			if ($pos !== false) {
				$subject = substr_replace($subject, $replace, $pos, strlen($search));
			}
		
			return $subject;
		}

		function get_supplier_header($id){
			$supp_view ="";
			$supp = $this->Event_model->get_supplier_header($id);
			
			foreach ($supp as $res) :
				$supp_view .= $res->supp_desc."<br>";
			endforeach;
			
			
			return $supp_view;

		}

		function get_tillcode($id){
			$tillcode = $this->Event_model->get_tillcode($id);
			
			$rtillcode = "<table border='0' cellpadding='0' cellspacing='0' class='tb_tillcode'>";
			$vlocation = "";
			$x = 0;

			foreach ($tillcode as $res) :
				$x++;

				if (($x%2 != 0)){
					$rtillcode .= "<tr>";
				}
				
				$rtillcode .= "<td>".$res->tillcode." (".$res->disc_label."), </td>";

			endforeach;
			
			$rtillcode .= "</table>";

			$vlocation .= "<tr><td>Tillcode</td>
								<td>:</td>
								<td>".str_replace(", </td></table>", "</td></table>", $rtillcode)."</td>
							</tr>";	
			$vlocation .=  "<tr><td colspan='3'><br></td></tr>";

			return $vlocation;

		}
		
		function get_tillcode_new($id){
			$tillcode = $this->Event_model->get_tillcode($id);
			$vlocation = "";
			
			for ($i = 0; $i < sizeof($tillcode); $i+=2) {
				
				if ($i == 0) {
					$label = "Tillcode";
					$colon = ":";
				}
				else {
					$label = "&nbsp;";
					$colon = "&nbsp;";	
				}
				
				$tblLoc = "<table cellpadding='0' cellspacing='0' style='width: 100%' border='0'><tr>";
				$tblLoc .= "<td style='width: 50%'>" . $tillcode[$i]->tillcode . " (" . $tillcode[$i]->disc_label . "),</td>";
				$tblLoc .= "<td>" . (isset($tillcode[$i+1]) ? $tillcode[$i+1]->tillcode . " (" . $tillcode[$i+1]->disc_label . ")," : "&nbsp;") . "</td>";
				$tblLoc .= "</tr></table>";
				
				$vlocation .= "<tr>
								<td>" . $label . "</td>
								<td>" . $colon . "</td>
								<td>" . $tblLoc . "</td>
							  </tr>
							";
			}
			
			$vlocation = $this->str_lreplace(",", "", $vlocation);
			$vlocation .=  "<tr><td colspan='3'>&nbsp;</td></tr>";
			
			return $vlocation;
		}

		function get_perhitungan($id){
			$vcalculate = "";
			$vcalculate_gold = "";
			
			$aVcalculate = array();
			$aVcalculateGold = array();

			// cek is_exc
			$is_exc = $this->Event_model->get_exc_data($id);
			if ($is_exc[0]==NULL || $is_exc[0]=='N'){
				$list = $this->Event_model->get_calculate($id);

				$x = 0;
				$y = 0;
				$idx = 0;
				foreach ($list as $r) {
					$vcalculate = "";
					$vcalculate_gold = "";
					
					if ($r->sp_event=='1'){
						//explode sp
						$explode = explode('-', $r->sp_event_price);
						
						if (!empty($explode[1])){
							if ($explode[0]>$explode[1])
								$hrg = (is_numeric($explode[0])?$explode[0]:100000);
							else 
								$hrg = (is_numeric($explode[1])?$explode[1]:100000);
						} else {
							$hrg = (is_numeric($explode[0])?$explode[0]:100000);
						}
					} 
					else {
						$hrg = 100000;
					}
					
					if($r->is_pkp=='1'){
						$pmargin = $r->tax.'% PKP';
					} else {
						$pmargin = $r->tax.'% NPKP';
					}

					//cek jika tanpa pert
					$margin = $hrg * $r->tax/100; 

					$after_disc1 = $hrg-($hrg*$r->disc1/100);//harga setelah disc1
					$after_disc2 = $after_disc1-($after_disc1*$r->disc2/100);//harga setelah disc2 // 72000
					
					//cek/////////////////////////////////////////////////////////////
					if ($hrg==0){
						$cek = 100-($after_disc2);
					} else {
						$cek = 100-($after_disc2/$hrg*100);
					}
					
					//cek hanya yg kurang dr 30%
					$x++;
					if (($cek<=100)){
						# style='font-size:1em !important;' # -> tdnya style di table
						$vcalculate .= "<table border='0' style='width:92%' cellpadding='0' cellspacing='0'>";	
						$vcalculate_gold .= "<table border='0' style='width:92%' cellpadding='0' cellspacing='0'>";	
						
						if ($y==0){
							$vcalculate .= "<tr><td colspan='4'>Adapun contoh perhitungannya adalah :</td></tr>";
							$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";					
						} else {

						}

						$y++;

						if ($hrg==0){
							$jml_diskon = (1-($after_disc1));//1-0.8=0.2
						} else {
							$jml_diskon = (1-($after_disc1/$hrg));//1-0.8=0.2
						}
						
						$yds = $r->yds_responsibility/100*$jml_diskon;

						/* cons */
						$part1 = $yds*100;
						$tmp2 = $hrg*$jml_diskon;20.000-
						$sel = $hrg - $tmp2;

						// cek disc 2
						if ($r->disc2=="0"){
							if ($r->yds_responsibility!=0){
								$sel_margin = $sel - $margin;
							} else {
								$sel_margin = $sel - ($sel*$r->tax/100);
							}
							
							$yds2 = 0;
							$sel2=0;

							// cons
							$part2 = null;
						} else {
							$tambahan = $r->disc2/100*$sel;
							$sel2 = $sel-$tambahan;
							$sel_margin = $sel2 - $margin; //jika ada disc +an di kurangin dulu

							$yds2 = $r->yds_responsibility/100*$tambahan;

							if ($sel!=0){
								$part2 = ($yds2/($sel))*100;
							}else {
								$part2 = null;
							}
							
						}

						//cek jika tanpa pert
						if ($r->yds_responsibility!=0){
							$margin = $margin;
						} else {
							if ($r->disc2=="0"){
								$margin = $sel*$r->tax/100;
							} else {
								$margin = $sel2*$r->tax/100;
							}
						}

						$yds_res = $yds*$hrg;

						if ($r->yds_responsibility!=0){
							$bayar = $sel_margin + $yds_res + $yds2;
						} else {
							if ($r->disc2!=0){
								$bayar = $sel2 - $margin;
							} else {
								$bayar = $sel_margin + $yds_res + $yds2;
							}
						}

						if ($sel != "0"){
							if ($r->disc2=="0"){
								$nett_margin = round((($margin-($yds_res+$yds2)) / ($sel))*100, 2, PHP_ROUND_HALF_UP);
							} else {
								$nett_margin = round((($margin-($yds_res+$yds2)) / ($sel2))*100, 2, PHP_ROUND_HALF_UP);
							}
								
						} else {
							$nett_margin = null;
						}
						
						//pertanggungan
						if ($r->yds_responsibility!="0"){
							$pert_label = "(Pert ".$r->yds_responsibility."% : ".$r->supp_responsibility."%)";
						} else $pert_label = "";
						
						//cek acara khusus
						if (strpos(strtolower($r->disc_label), 'khusus') !== false || strpos(strtolower($r->disc_label), 'buy 1 get 1') || strpos(strtolower($r->disc_label), 'buy 1 get 2') || strpos(strtolower($r->disc_label), 'buy 2 get 1')  || strpos(strtolower($r->disc_label), 'buy one get one')) {
						    $label = substr($r->disc_label, strpos($r->disc_label, ",") + 1);
						    $tmp = explode(',', $label);

						    //WALRUS, T'SHIRT, BUY 1 GET 2 FREE
							if (count($tmp)>=2){
								$label = $tmp[1];
							} 
							else {
								$label = substr($label, strpos($label, ",") + 1);
							}

							//cek selain Special Price & Program khusus
							if (strpos(strtolower($r->disc_label), 'khusus') == false) {
								$noteBG = "Harga jual yang tercantum dalam contoh perhitungan adalah harga net yang dibayarkan oleh konsumen.";
							} 
						} else {
							if ($r->sp_event=='0'){
								$label1 = $r->disc1;
								($r->disc2=="0" ? $label2="":$label2="+ ".$r->disc2."%");

								$label = "Disc. ".$label1."% ".$label2;
							} else {
								if ($r->disc1=="0" || $r->disc1==null){
									$label1 = "";
								} else {
									$label1 = " + ".$r->disc1."%";
								}

								if ($r->disc2=="0" || $r->disc2==null){
									$label2 = "";
								} else {
									$label2 = " + ".$r->disc2."%";
								}

								$label = "SPECIAL PRICE" . $label1 . $label2;

							}	
						}

						


								$vcalculate .= "<tr><td colspan='4'><b><u>".$label." ".$pert_label." </u></b></td></tr>";	
								$vcalculate .= "<tr><td style='width:250px'>Harga Jual</td>
													<td>Rp. </td>
													<td align='right'>".number_format($hrg, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";	

								if ($r->disc1!=0){
									$vcalculate .= "<tr><td>Disc. ".$r->disc1."%</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($tmp2, 0, ",", ".")."</u></td>
														<td><u> - </u></td>
													</tr>";		
									$vcalculate .= "<tr><td>&nbsp;</td>
														<td>Rp. </td>
														<td align='right'>".number_format($sel, 0, ",", ".")."</td>
														<td>&nbsp;</td>
													</tr>";						
								}				
								
								
								////////////////// gold //////////////////////////
								if ($r->yds_responsibility!="0"){
									$vcalculate_gold .= "<tr><td colspan='4'><b><u>".$label." (GOLD)</u></b></td></tr>";	
									$vcalculate_gold .= "<tr><td style='width:250px'>Harga Jual</td>
															<td>Rp. </td>
															<td align='right'>".number_format($hrg, 0, ",", ".")."</td>
															<td>&nbsp;</td>
														</tr>";	
									if ($r->disc1!=0){
										$vcalculate_gold .= "<tr><td>Disc. ".$r->disc1."%</td>
																<td>Rp. </td>
																<td align='right'><u>".number_format($tmp2, 0, ",", ".")."</u></td>
																<td><u> - </u></td>
															</tr>";	
										$vcalculate_gold .= "<tr><td>&nbsp;</td>
																<td>Rp. </td>
																<td align='right'>".number_format($sel, 0, ",", ".")."</td>
																<td>&nbsp;</td>
															</tr>";			
									}					
												
								} else {
									//$vcalculate_gold .= $vcalculate;
								}

								if ($r->disc2!="0"){
									// 50+30
									$vcalculate .= "<tr><td>Disc. tambahan ".$r->disc2."%</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($tambahan, 0, ",", ".")."</u></td>
														<td><u> - </u></td>
													</tr>";	
									$vcalculate .= "<tr><td>&nbsp;</td>
														<td>Rp. </td>
														<td align='right'>".number_format($sel2, 0, ",", ".")."</td>
														<td>&nbsp;</td>
													</tr>";

									if ($r->yds_responsibility!="0"){
										$vcalculate_gold .= "<tr><td>Disc. tambahan ".$r->disc2."%</td>
																<td>Rp. </td>
																<td align='right'><u>".number_format($tambahan, 0, ",", ".")."</u></td>
																<td><u> - </u></td>
															</tr>";	
										$vcalculate_gold .= "<tr><td>&nbsp;</td>
																<td>Rp. </td>
																<td align='right'>".number_format($sel2, 0, ",", ".")."</td>
																<td>&nbsp;</td>
															</tr>";

										////////////////////// gold //////////////////
										
										$cek_margin = $nett_margin/100*$sel2;
										$cek_bayar = $sel2 - $cek_margin;

										if ($bayar != $cek_bayar){
											//tdk sama bulatkan
											$margin_gold = round($nett_margin/100*$sel2, -1);	
											$bayar_gold = $sel2 - $margin_gold;
										} else {
											$margin_gold = $nett_margin/100*$sel2;	
											$bayar_gold = $sel2 - $margin_gold;
										}

										$vcalculate_gold .= "<tr><td>Margin Yogya $nett_margin% </td>
																<td>Rp. </td>
																<td align='right'><u>".number_format($margin_gold, 0, ",", ".")."</u></td>
																<td><u> - </u></td>
															</tr>";		

										$vcalculate_gold .= "<tr><td>Yang dibayar Yogya</td>
																<td>Rp. </td>
																<td align='right'>".number_format($bayar_gold, 0, ",", ".")."</td>
																<td>&nbsp;</td>
															</tr>";	
																				
									}	

									

									
									if ($r->yds_responsibility!="0"){
										if ($r->disc2 != "0"){	
											$limit = 2;				
										} else $limit = 3;
									} else {
										if ($r->disc2 != "0"){	
											$limit = 9;				
										} else {
											if ($r->disc1=="0"){
												$limit = 5;
											} else $limit = 7;
										}
									}
								

									for ($i=1;$i<=$limit;$i++){
										$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
									}	

													
										
									//jika ada pertanggungan						
									if ($r->without_responsibility=='0'){
										$vcalculate_gold .= "<tr>
																<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))." = $nett_margin% 
																	</td>	
															</tr>";
										$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	

										$isset_nett_gold = 1;	
									} 											


								}	else {

									//gold
									if ($r->yds_responsibility!="0"){
										$cek_margin = $nett_margin/100*$sel;	
										$cek_bayar = $sel - $cek_margin;
										
										if ($bayar != $cek_bayar){
											$margin_gold = round($nett_margin/100*$sel, -1);	
											$bayar_gold = $sel - $margin_gold;
										} else {
											$margin_gold = $nett_margin/100*$sel;	
											$bayar_gold = $sel - $margin_gold;
										}	
										$vcalculate_gold .= "<tr><td>Margin Yogya $nett_margin% </td>
																<td>Rp. </td>
																<td align='right'><u>".number_format($margin_gold, 0, ",", ".")."</u></td>
																<td><u> - </u></td>
															</tr>";		

										$vcalculate_gold .= "<tr><td>Yang dibayar Yogya</td>
																<td>Rp. </td>
																<td align='right'>".number_format($bayar_gold, 0, ",", ".")."</td>
																<td>&nbsp;</td>
															</tr>";	
										
										$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	

										$vcalculate_gold .= "<tr>
																<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))."= $nett_margin% 
																	</td>	
															</tr>";		

										$isset_nett_gold = 1;						

									}		

										if ($r->yds_responsibility!="0"){
											if ($r->disc2 != "0"){	
												$limit = 4;				
											} else $limit = 1;
										} else {
											if ($r->disc2 != "0"){	
												$limit = 9;				
											} else {
												if ($r->disc1=="0"){
													$limit = 5;
												} else $limit = 7;
											}//cek disc1 jika ada maka 6
										}
									

										for ($i=1;$i<=$limit;$i++){
											$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
										}

												

								}
					
			//				$vcalculate_gold .= "</table>";
							$vcalculate .= "<tr><td>Margin Yogya ".$pmargin."</td>
												<td>Rp. </td>
												<td align='right'><u>".number_format($margin, 0, ",", ".")."</u></td>
												<td><u> - </u></td>
											</tr>";	
							//jgn muncul nol di perhitungan						
							if ($r->yds_responsibility!=0){
								$vcalculate .= "<tr><td></td>
													<td>Rp. </td>
													<td align='right'>".number_format($sel_margin, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";	
								if ($r->disc2!="0"){
									$vcalculate .= "<tr><td>Partisipasi Yogya ".$label."</td>
														<td>Rp. </td>
														<td align='right'>".number_format($yds_res, 0, ",", ".")."</td>
														<td>&nbsp;</td>
													</tr>";			
								} else {
									$vcalculate .= "<tr><td>Partisipasi Yogya ".$label."</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($yds_res, 0, ",", ".")."</u></td>
														<td><u> + </u></td>
													</tr>";	

								}				
								
							}										
							
							

							if ($r->disc2!="0"){

								if ($r->yds_responsibility!=0){
									$vcalculate .= "<tr><td>Partisipasi Yogya Disc Tamb.</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($yds2, 0, ",", ".")."</u></td>
														<td><u> + </u></td>
													</tr>";	
								}		
							}

							$vcalculate .= "<tr><td>Yang dibayar Yogya</td>
												<td>Rp. </td>
												<td align='right'>".number_format($bayar, 0, ",", ".")."</td>
												<td>&nbsp;</td>
											</tr>";

							//yg bruto saja				
							if ($r->without_responsibility=='0'){
								if (isset($isset_nett_gold) && $isset_nett_gold != 1){
									$vcalculate .= "<tr><td colspan='4'></td></tr>";		
									$vcalculate .= "<tr>
														<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))." = $nett_margin% 
															</td>	
													</tr>";	
								}
							} 

							$vcalculate .=  "<tr><td colspan='4'>&nbsp;</td></tr>";	
							$vcalculate .= "</table>";
							# tadinya ini ga ada
							$vcalculate_gold .= "</table>";	
							
							if ($r->tax == 0){
								$vcalculate_gold = null;
							}

							$aVcalculate[$idx] = $vcalculate;
							$aVcalculateGold[$idx] = $vcalculate_gold;
							$idx++;
							
					} else {
						//$vcalculate .="<table class='vcalculate'><tr><td colspan='4'></td></tr>";
						//$vcalculate_gold .="<table class='vcalculate_gold'><tr><td colspan='4'></td></tr>";
					}
					
					$cons = $this->Event_model->get_consignment($id, $r->notes);
					foreach ($cons as $val) {
						$this->Event_model->update_consignment($id, $val->event_item_notes, $part1, $part2);
					}

				}//end foreach

				
			}
			// is_exc =Y
			else {
				$list = $this->Event_model->get_calculate($id);

				$x = 0;
				$y = 0;
				$idx = 0;
				foreach ($list as $r) {
					$vcalculate = "";
					$vcalculate_gold = "";
										
				
					if ($r->sp_event=='1'){
						//explode sp
						$explode = explode('-', $r->sp_event_price);
						
						if (!empty($explode[1])){
							if ($explode[0]>$explode[1])
								$hrg = (is_numeric($explode[0])?$explode[0]:100000);
							else 
								$hrg = (is_numeric($explode[1])?$explode[1]:100000);
						} else {
							$hrg = (is_numeric($explode[0])?$explode[0]:100000);
						}
						
						if($r->is_pkp=='1'){
							$pmargin = $r->tax.'% PKP';
						} else {
							$pmargin = $r->tax.'% NPKP';
						}

						//cek jika tanpa pert
						$margin = $hrg * $r->tax/100; // 75.000

						$after_disc1 = $hrg-($hrg*$r->disc1/100);//harga setelah disc1
						$after_disc2 = $after_disc1-($after_disc1*$r->disc2/100);//harga setelah disc2 // 72000
						
						//cek/////////////////////////////////////////////////////////////
						if ($hrg==0){
							$cek = 100-($after_disc2);
						} else {
							$cek = 100-($after_disc2/$hrg*100);
						}
						

						//cek hanya yg kurang dr 30%
						$x++;
						if (($cek<=100)){
							# style='font-size:1em !important;' # -> tdnya style di table
							$vcalculate .= "<table border='0' style='width:92%' cellpadding='0' cellspacing='0'>";	
							$vcalculate_gold .= "<table border='0' style='width:92%' cellpadding='0' cellspacing='0'>";	
							
							if ($y==0){
								$vcalculate .= "<tr><td colspan='4'>Adapun contoh perhitungannya adalah :</td></tr>";
								$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";					
							} else {

							}

							$y++;

							if ($hrg==0){
								$jml_diskon = (1-($after_disc1));//1-0.8=0.2
							} else {
								$jml_diskon = (1-($after_disc1/$hrg));//1-0.8=0.2
							}
							
							$yds = $r->yds_responsibility/100*$jml_diskon;

							/* cons */
							$part1 = $yds*100;

							$tmp2 = $hrg*$jml_diskon;20.000-

							$sel = $hrg - $tmp2;

							// cek disc 2
							if ($r->disc2=="0"){
								if ($r->yds_responsibility!=0){
									$sel_margin = $sel - $margin;
								} else {
									$sel_margin = $sel - ($sel*$r->tax/100);
								}
								
								$yds2 = 0;
								$sel2=0;

								/* cons */
								$part2 = null;
							} else {
								$tambahan = $r->disc2/100*$sel;
								$sel2 = $sel-$tambahan;
								$sel_margin = $sel2 - $margin; //jika ada disc +an di kurangin dulu

								$yds2 = $r->yds_responsibility/100*$tambahan;

								/* cons */
								if ($sel!=0){
									$part2 = ($yds2/($sel))*100;
								}else {
									$part2 = null;
								}

							}

							//cek jika tanpa pert
							if ($r->yds_responsibility!=0){
								$margin = $margin;
							} else {
								if ($r->disc2=="0"){
									$margin = $sel*$r->tax/100;
								} else {
									$margin = $sel2*$r->tax/100;
								}
								
							}


							$yds_res = $yds*$hrg;

							//sel 2 = 72000 , $margin=10800
							if ($r->yds_responsibility!=0){
								$bayar = $sel_margin + $yds_res + $yds2;
							} else {
								if ($r->disc2!=0){
									$bayar = $sel2 - $margin;
								} else {
									$bayar = $sel_margin + $yds_res + $yds2;
								}
								
							}

							if ($sel != "0"){
								if ($r->disc2=="0"){
									$nett_margin = round((($margin-($yds_res+$yds2)) / ($sel))*100, 2, PHP_ROUND_HALF_UP);
								} else {
									$nett_margin = round((($margin-($yds_res+$yds2)) / ($sel2))*100, 2, PHP_ROUND_HALF_UP);
								}
									
							} else {
								$nett_margin = null;
							}
							
									
							//echo $sel_margin;	
							//pertanggungan
							if ($r->yds_responsibility!="0"){
								$pert_label = "(Pert ".$r->yds_responsibility."% : ".$r->supp_responsibility."%)";
							} else $pert_label = "";
							
							//cek acara khusus
							if (strpos(strtolower($r->disc_label), 'khusus') !== false || strpos(strtolower($r->disc_label), 'buy 1 get 1') || strpos(strtolower($r->disc_label), 'buy 1 get 2') || strpos(strtolower($r->disc_label), 'buy 2 get 1')  || strpos(strtolower($r->disc_label), 'buy one get one')) {
							    $label = substr($r->disc_label, strpos($r->disc_label, ",") + 1);
							    $tmp = explode(',', $label);

							    //WALRUS, T'SHIRT, BUY 1 GET 2 FREE
								if (count($tmp)>=2){
									$label = $tmp[1];
								} 
								else {
									$label = substr($label, strpos($label, ",") + 1);
								}
							} else {
								if ($r->sp_event=='0'){
									$label1 = $r->disc1;
									($r->disc2=="0" ? $label2="":$label2="+ ".$r->disc2."%");

									$label = "Disc. ".$label1."% ".$label2;
								} else {
									if ($r->disc1=="0" || $r->disc1==null){
										$label1 = "";
									} else {
										$label1 = " + ".$r->disc1."%";
									}

									if ($r->disc2=="0" || $r->disc2==null){
										$label2 = "";
									} else {
										$label2 = " + ".$r->disc2."%";
									}

									$label = "SPECIAL PRICE" . $label1 . $label2;

									
								//
								}	
							}

									$vcalculate .= "<tr><td colspan='4'><b><u>".$label." ".$pert_label." </u></b></td></tr>";	
									$vcalculate .= "<tr><td style='width:250px'>Harga Jual</td>
														<td>Rp. </td>
														<td align='right'>".number_format($hrg, 0, ",", ".")."</td>
														<td>&nbsp;</td>
													</tr>";	

									if ($r->disc1!=0){
										$vcalculate .= "<tr><td>Disc. ".$r->disc1."%</td>
															<td>Rp. </td>
															<td align='right'><u>".number_format($tmp2, 0, ",", ".")."</u></td>
															<td><u> - </u></td>
														</tr>";		
										$vcalculate .= "<tr><td>&nbsp;</td>
															<td>Rp. </td>
															<td align='right'>".number_format($sel, 0, ",", ".")."</td>
															<td>&nbsp;</td>
														</tr>";						
									}				
									
									
									////////////////// gold //////////////////////////
									if ($r->yds_responsibility!="0"){
										$vcalculate_gold .= "<tr><td colspan='4'><b><u>".$label." (GOLD)</u></b></td></tr>";	
										$vcalculate_gold .= "<tr><td style='width:250px'>Harga Jual</td>
																<td>Rp. </td>
																<td align='right'>".number_format($hrg, 0, ",", ".")."</td>
																<td>&nbsp;</td>
															</tr>";	
										if ($r->disc1!=0){
											$vcalculate_gold .= "<tr><td>Disc. ".$r->disc1."%</td>
																	<td>Rp. </td>
																	<td align='right'><u>".number_format($tmp2, 0, ",", ".")."</u></td>
																	<td><u> - </u></td>
																</tr>";	
											$vcalculate_gold .= "<tr><td>&nbsp;</td>
																	<td>Rp. </td>
																	<td align='right'>".number_format($sel, 0, ",", ".")."</td>
																	<td>&nbsp;</td>
																</tr>";			
										}					
													
									} else {
										//$vcalculate_gold .= $vcalculate;
									}

									if ($r->disc2!="0"){
										$vcalculate .= "<tr><td>Disc. tambahan ".$r->disc2."%</td>
															<td>Rp. </td>
															<td align='right'><u>".number_format($tambahan, 0, ",", ".")."</u></td>
															<td><u> - </u></td>
														</tr>";	
										$vcalculate .= "<tr><td>&nbsp;</td>
															<td>Rp. </td>
															<td align='right'>".number_format($sel2, 0, ",", ".")."</td>
															<td>&nbsp;</td>
														</tr>";

										if ($r->yds_responsibility!="0"){
											$vcalculate_gold .= "<tr><td>Disc. tambahan ".$r->disc2."%</td>
																	<td>Rp. </td>
																	<td align='right'><u>".number_format($tambahan, 0, ",", ".")."</u></td>
																	<td><u> - </u></td>
																</tr>";	
											$vcalculate_gold .= "<tr><td>&nbsp;</td>
																	<td>Rp. </td>
																	<td align='right'>".number_format($sel2, 0, ",", ".")."</td>
																	<td>&nbsp;</td>
																</tr>";

											////////////////////// gold //////////////////
											/*	
											*/
											
											$cek_margin = $nett_margin/100*$sel2;
											$cek_bayar = $sel2 - $cek_margin;

											if ($bayar != $cek_bayar){
												//tdk sama bulatkan
												$margin_gold = round($nett_margin/100*$sel2, -1);	
												$bayar_gold = $sel2 - $margin_gold;
											} else {
												$margin_gold = $nett_margin/100*$sel2;	
												$bayar_gold = $sel2 - $margin_gold;
											}

											$vcalculate_gold .= "<tr><td>Margin Yogya $nett_margin% </td>
																	<td>Rp. </td>
																	<td align='right'><u>".number_format($margin_gold, 0, ",", ".")."</u></td>
																	<td><u> - </u></td>
																</tr>";		

											$vcalculate_gold .= "<tr><td>Yang dibayar Yogya</td>
																	<td>Rp. </td>
																	<td align='right'>".number_format($bayar_gold, 0, ",", ".")."</td>
																	<td>&nbsp;</td>
																</tr>";	
																					
										}	

										

										
										if ($r->yds_responsibility!="0"){
											if ($r->disc2 != "0"){	
												$limit = 2;				
											} else $limit = 3;
										} else {
											if ($r->disc2 != "0"){	
												$limit = 9;				
											} else {
												if ($r->disc1=="0"){
													$limit = 5;
												} else $limit = 7;
											}
										}
									

										for ($i=1;$i<=$limit;$i++){
											$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
										}	

														
											
										//jika ada pertanggungan						
										if ($r->without_responsibility=='0'){
											$vcalculate_gold .= "<tr>
																	<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))." = $nett_margin% 
																		</td>	
																</tr>";
											$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	

											$isset_nett_gold = 1;	
										} 											


									}	else {

										//gold
										if ($r->yds_responsibility!="0"){
											$cek_margin = $nett_margin/100*$sel;	
											$cek_bayar = $sel - $cek_margin;
											
											if ($bayar != $cek_bayar){
												$margin_gold = round($nett_margin/100*$sel, -1);	
												$bayar_gold = $sel - $margin_gold;
											} else {
												$margin_gold = $nett_margin/100*$sel;	
												$bayar_gold = $sel - $margin_gold;
											}	
											$vcalculate_gold .= "<tr><td>Margin Yogya $nett_margin% </td>
																	<td>Rp. </td>
																	<td align='right'><u>".number_format($margin_gold, 0, ",", ".")."</u></td>
																	<td><u> - </u></td>
																</tr>";		

											$vcalculate_gold .= "<tr><td>Yang dibayar Yogya</td>
																	<td>Rp. </td>
																	<td align='right'>".number_format($bayar_gold, 0, ",", ".")."</td>
																	<td>&nbsp;</td>
																</tr>";	
											
											$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	

											$vcalculate_gold .= "<tr>
																	<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))."= $nett_margin% 
																		</td>	
																</tr>";		

											$isset_nett_gold = 1;						

										}		

											if ($r->yds_responsibility!="0"){
												if ($r->disc2 != "0"){	
													$limit = 4;				
												} else $limit = 1;
											} else {
												if ($r->disc2 != "0"){	
													$limit = 9;				
												} else {
													if ($r->disc1=="0"){
														$limit = 5;
													} else $limit = 7;
												}//cek disc1 jika ada maka 6
											}
										

											for ($i=1;$i<=$limit;$i++){
												$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
											}

											///
											//	
											///	
															

									}
						
				//				$vcalculate_gold .= "</table>";
								$vcalculate .= "<tr><td>Margin Yogya ".$pmargin."</td>
													<td>Rp. </td>
													<td align='right'><u>".number_format($margin, 0, ",", ".")."</u></td>
													<td><u> - </u></td>
												</tr>";	
								//jgn muncul nol di perhitungan						
								if ($r->yds_responsibility!=0){
									$vcalculate .= "<tr><td></td>
														<td>Rp. </td>
														<td align='right'>".number_format($sel_margin, 0, ",", ".")."</td>
														<td>&nbsp;</td>
													</tr>";	
									if ($r->disc2!="0"){
										$vcalculate .= "<tr><td>Partisipasi Yogya ".$label."</td>
															<td>Rp. </td>
															<td align='right'>".number_format($yds_res, 0, ",", ".")."</td>
															<td>&nbsp;</td>
														</tr>";			
									} else {
										$vcalculate .= "<tr><td>Partisipasi Yogya ".$label."</td>
															<td>Rp. </td>
															<td align='right'><u>".number_format($yds_res, 0, ",", ".")."</u></td>
															<td><u> + </u></td>
														</tr>";	

									}				
									
								}										
								
								

								if ($r->disc2!="0"){

									if ($r->yds_responsibility!=0){
										$vcalculate .= "<tr><td>Partisipasi Yogya Disc Tamb.</td>
															<td>Rp. </td>
															<td align='right'><u>".number_format($yds2, 0, ",", ".")."</u></td>
															<td><u> + </u></td>
														</tr>";	
									}		
								}

								$vcalculate .= "<tr><td>Yang dibayar Yogya</td>
													<td>Rp. </td>
													<td align='right'>".number_format($bayar, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";

								//yg bruto saja				
								if ($r->without_responsibility=='0'){
									if (isset($isset_nett_gold) && $isset_nett_gold != 1){
										$vcalculate .= "<tr><td colspan='4'></td></tr>";		
										$vcalculate .= "<tr>
															<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))." = $nett_margin% 
																</td>	
														</tr>";	
									}
								} 

								$vcalculate .=  "<tr><td colspan='4'>&nbsp;</td></tr>";	
								$vcalculate .= "</table>";
								# tadinya ini ga ada
								$vcalculate_gold .= "</table>";	
								
								$aVcalculate[$idx] = $vcalculate;
								$aVcalculateGold[$idx] = $vcalculate_gold;
								$idx++;
								
						} else {
							//$vcalculate .="<table class='vcalculate'><tr><td colspan='4'></td></tr>";
							//$vcalculate_gold .="<table class='vcalculate_gold'><tr><td colspan='4'></td></tr>";
						}
					}else {
						if ($is_exc[1]==1){
							$same_date = $this->Event_model->get_event_same_date_exc($id);
							$harga_faktur = $same_date[0];
							$harga_jual = $same_date[1];
						}else {
							$event_date = $this->Event_model->get_event_date_exc($id, $r->tillcode);
							$harga_faktur = $event_date[0];
							$harga_jual = $event_date[1];
						}	

						if($r->is_pkp=='1'){
							$pmargin = $r->tax.'% PKP';
						} else {
							$pmargin = $r->tax.'% NPKP';
						}

						//cek jika tanpa pert
						$margin = $harga_faktur * $r->tax/100; // 75.000
						
						$after_disc1 = $harga_jual-($harga_jual*$r->disc1/100);//harga setelah disc1
						$after_disc2 = $after_disc1-($after_disc1*$r->disc2/100);//harga setelah disc2 // 72000
						
						//cek/////////////////////////////////////////////////////////////
						if ($harga_jual==0){
							$cek = 100-($after_disc2);
						} else {
							$cek = 100-($after_disc2/$harga_jual*100);
						}
						
						//cek hanya yg kurang dr 30%
						$x++;
						if (($cek<=100)){
							$vcalculate .= "<table border='0' style='width:92%' cellpadding='0' cellspacing='0'>";	
							// $vcalculate_gold .= "<table border='0' style='width:92%' cellpadding='0' cellspacing='0'>";	
							
							if ($y==0){
								$vcalculate .= "<tr><td colspan='4'>Adapun contoh perhitungannya adalah :</td></tr>";
								// $vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";					
							} else {

							}

							$y++;

							if ($harga_jual==0){
								$jml_diskon = (1-($after_disc1));//1-0.8=0.2
							} else {
								$jml_diskon = (1-($after_disc1/$harga_jual));//1-0.8=0.2
							}
							
							$yds = $r->yds_responsibility/100*$jml_diskon;

							/* cons */
							$part1 = $yds;

							$tmp2 = $harga_jual*$jml_diskon;

							$sel = $harga_faktur - $tmp2;

							// cek disc 2
							if ($r->disc2=="0"){
								if ($r->yds_responsibility!=0){
									$sel_margin = $sel - $margin;
								} else {
									$sel_margin = $sel - ($sel*$r->tax/100);
								}
								
								$yds2 = 0;
								$sel2=0;

								/* cons */
								$part2 = null;
							} else {
								$tambahan = $r->disc2/100*($harga_jual - $tmp2);
								$sel2 = $sel-$tambahan;
								$sel_margin = $sel2 - $margin; //jika ada disc +an di kurangin dulu

								$yds2 = $r->yds_responsibility/100*$tambahan;

								/* cons */
								if ($sel!=0){
									$part2 = ($yds2/($sel))*100;
								}else {
									$part2 = ($yds2)*100;
								}

							}

							//cek jika tanpa pert
							if ($r->yds_responsibility!=0){
								$margin = $margin;
							} else {
								if ($r->disc2=="0"){
									$margin = $sel*$r->tax/100;
								} else {
									$margin = $sel2*$r->tax/100;
								}
								
							}

							$yds_res = $yds*$harga_jual;

							//sel 2 = 72000 , $margin=10800
							if ($r->yds_responsibility!=0){
								$bayar = $sel_margin + $yds_res + $yds2;
							} else {
								if ($r->disc2!=0){
									$bayar = $sel2 - $margin;
								} else {
									$bayar = $sel_margin + $yds_res + $yds2;
								}
								
							}

							//partisipasi_selisih
							$partisipasi_selisih = ($harga_faktur - $harga_jual)/2;
							$selisih_harga_faktur = $harga_faktur - $harga_jual;
							$bayar_bersih = $bayar - $partisipasi_selisih;

							if ($sel != "0"){
								if ($r->disc2=="0"){
									$nett_margin = round((($margin-($yds_res+$yds2)) / ($sel))*100, 2, PHP_ROUND_HALF_UP);
								} else {
									$nett_margin = round((($margin-($yds_res+$yds2)) / ($sel2))*100, 2, PHP_ROUND_HALF_UP);
								}
									
							} else {
								$nett_margin = null;
							}
												
							//pertanggungan
							if ($r->yds_responsibility!="0"){
								$pert_label = "(Pert ".$r->yds_responsibility."% : ".$r->supp_responsibility."%)";
							} else $pert_label = "";
							
							//cek acara khusus
							if (strpos(strtolower($r->disc_label), 'khusus') !== false || strpos(strtolower($r->disc_label), 'buy 1 get 1') || strpos(strtolower($r->disc_label), 'buy 1 get 2') || strpos(strtolower($r->disc_label), 'buy 2 get 1')  || strpos(strtolower($r->disc_label), 'buy one get one')) {
							    $label = substr($r->disc_label, strpos($r->disc_label, ",") + 1);
							    $tmp = explode(',', $label);

							    //WALRUS, T'SHIRT, BUY 1 GET 2 FREE
								if (count($tmp)>=2){
									$label = $tmp[1];
								} 
								else {
									$label = substr($label, strpos($label, ",") + 1);
								}
							} else {
								if ($r->sp_event=='0'){
									$label1 = $r->disc1;
									($r->disc2=="0" ? $label2="":$label2="+ ".$r->disc2."%");

									$label = "Disc. ".$label1."% ".$label2;
								} else {
									if ($r->disc1=="0" || $r->disc1==null){
										$label1 = "";
									} else {
										$label1 = " + ".$r->disc1."%";
									}

									if ($r->disc2=="0" || $r->disc2==null){
										$label2 = "";
									} else {
										$label2 = " + ".$r->disc2."%";
									}

									$label = "SPECIAL PRICE" . $label1 . $label2;

									
								//
								}	
							}

							// cek is_exc 
							if ($r->disc2!="0"){
								$vcalculate .= "<tr><td colspan='4'><b><u>".$label." ".$pert_label." </u></b></td></tr>";
								$vcalculate .= "<tr><td style='width:250px'>Harga Faktur</td>
													<td>Rp. </td>
													<td align='right'>".number_format($harga_faktur, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";		
								$vcalculate .= "<tr><td style='width:250px'>Harga Jual (Price Tag)</td>
													<td>Rp. </td>
													<td align='right'>".number_format($harga_jual, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";		
								$vcalculate .= "<tr><td style='width:250px'>Selisih Harga Faktur &amp; Price Tag</td>
													<td>Rp. </td>
													<td align='right'>".number_format($selisih_harga_faktur, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";		
								$vcalculate .= "<tr><td colspan='4'>&nbsp;</td></tr>";									
								$vcalculate .= "<tr><td style='width:250px'>Harga Faktur</td>
													<td>Rp. </td>
													<td align='right'>".number_format($harga_faktur, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";	
							}else {
								$vcalculate .= "<tr><td colspan='4'><b><u>".$label." ".$pert_label." </u></b></td></tr>";
								$vcalculate .= "<tr><td style='width:250px'>Harga Faktur</td>
													<td>Rp. </td>
													<td align='right'>".number_format($harga_faktur, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";		
								$vcalculate .= "<tr><td style='width:250px'>Harga Jual (Price Tag)</td>
													<td>Rp. </td>
													<td align='right'>".number_format($harga_jual, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";		
								$vcalculate .= "<tr><td colspan='4'>&nbsp;</td></tr>";					
								$vcalculate .= "<tr><td style='width:250px'>Harga Faktur</td>
													<td>Rp. </td>
													<td align='right'>".number_format($harga_faktur, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";		
							}
								
							// +2 tr								
							
							if ($r->disc1!=0){
								$vcalculate .= "<tr><td>Disc. ".$r->disc1."%</td>
													<td>Rp. </td>
													<td align='right'><u>".number_format($tmp2, 0, ",", ".")."</u></td>
													<td><u> - </u></td>
												</tr>";		
								$vcalculate .= "<tr><td>&nbsp;</td>
													<td>Rp. </td>
													<td align='right'>".number_format($sel, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";						
							}				
							
							
							// ////////////////// gold //////////////////////////
							// if ($r->yds_responsibility!="0"){
							// 	$vcalculate_gold .= "<tr><td colspan='4'><b><u>".$label." (GOLD)</u></b></td></tr>";	
							// 	$vcalculate_gold .= "<tr><td style='width:250px'>Harga Jual</td>
							// 							<td>Rp. </td>
							// 							<td align='right'>".number_format($harga_jual, 0, ",", ".")."</td>
							// 							<td>&nbsp;</td>
							// 						</tr>";	
							// 	if ($r->disc1!=0){
							// 		$vcalculate_gold .= "<tr><td>Disc. ".$r->disc1."%</td>
							// 								<td>Rp. </td>
							// 								<td align='right'><u>".number_format($tmp2, 0, ",", ".")."</u></td>
							// 								<td><u> - </u></td>
							// 							</tr>";	
							// 		$vcalculate_gold .= "<tr><td>&nbsp;</td>
							// 								<td>Rp. </td>
							// 								<td align='right'>".number_format($sel, 0, ",", ".")."</td>
							// 								<td>&nbsp;</td>
							// 							</tr>";			
							// 	}					
											
							// } else {
							// 	//$vcalculate_gold .= $vcalculate;
							// }

							if ($r->disc2!="0"){
								$vcalculate .= "<tr><td>Disc. tambahan ".$r->disc2."%</td>
													<td>Rp. </td>
													<td align='right'><u>".number_format($tambahan, 0, ",", ".")."</u></td>
													<td><u> - </u></td>
												</tr>";	
								$vcalculate .= "<tr><td>&nbsp;</td>
													<td>Rp. </td>
													<td align='right'>".number_format($sel2, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";

								// if ($r->yds_responsibility!="0"){
								// 	$vcalculate_gold .= "<tr><td>Disc. tambahan ".$r->disc2."%</td>
								// 							<td>Rp. </td>
								// 							<td align='right'><u>".number_format($tambahan, 0, ",", ".")."</u></td>
								// 							<td><u> - </u></td>
								// 						</tr>";	
								// 	$vcalculate_gold .= "<tr><td>&nbsp;</td>
								// 							<td>Rp. </td>
								// 							<td align='right'>".number_format($sel2, 0, ",", ".")."</td>
								// 							<td>&nbsp;</td>
								// 						</tr>";

								// 	////////////////////// gold //////////////////
								// 	$cek_margin = $nett_margin/100*$sel2;
								// 	$cek_bayar = $sel2 - $cek_margin;

								// 	if ($bayar != $cek_bayar){
								// 		//tdk sama bulatkan
								// 		$margin_gold = round($nett_margin/100*$sel2, -2);	
								// 		$bayar_gold = $sel2 - $margin_gold;
								// 	} else {
								// 		$margin_gold = $nett_margin/100*$sel2;	
								// 		$bayar_gold = $sel2 - $margin_gold;
								// 	}

								// 	$vcalculate_gold .= "<tr><td>Margin Yogya $nett_margin% </td>
								// 							<td>Rp. </td>
								// 							<td align='right'><u>".number_format($margin_gold, 0, ",", ".")."</u></td>
								// 							<td><u> - </u></td>
								// 						</tr>";		

								// 	$vcalculate_gold .= "<tr><td>Yang dibayar Yogya</td>
								// 							<td>Rp. </td>
								// 							<td align='right'>".number_format($bayar_gold, 0, ",", ".")."</td>
								// 							<td>&nbsp;</td>
								// 						</tr>";	
																			
								// }	
								
								// if ($r->yds_responsibility!="0"){
								// 	if ($r->disc2 != "0"){	
								// 		$limit = 2;				
								// 	} else $limit = 3;
								// } else {
								// 	if ($r->disc2 != "0"){	
								// 		$limit = 9;				
								// 	} else {
								// 		if ($r->disc1=="0"){
								// 			$limit = 5;
								// 		} else $limit = 7;
								// 	}
								// }
							

								// for ($i=1;$i<=$limit;$i++){
								// 	$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
								// }	

								// //jika ada pertanggungan						
								// if ($r->without_responsibility=='0'){
								// 	$vcalculate_gold .= "<tr>
								// 							<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))." = $nett_margin% 
								// 								</td>	
								// 						</tr>";
								// 	$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
								// 	$isset_nett_gold = 1;	
								// } 											


							} else {

								//gold
								// if ($r->yds_responsibility!="0"){
								// 	$cek_margin = $nett_margin/100*$sel;	
								// 	$cek_bayar = $sel - $cek_margin;
									
								// 	if ($bayar != $cek_bayar){
								// 		$margin_gold = round($nett_margin/100*$sel, -2);	
								// 		$bayar_gold = $sel - $margin_gold;
								// 	} else {
								// 		$margin_gold = $nett_margin/100*$sel;	
								// 		$bayar_gold = $sel - $margin_gold;
								// 	}	
								// 	$vcalculate_gold .= "<tr><td>Margin Yogya $nett_margin% </td>
								// 							<td>Rp. </td>
								// 							<td align='right'><u>".number_format($margin_gold, 0, ",", ".")."</u></td>
								// 							<td><u> - </u></td>
								// 						</tr>";		

								// 	$vcalculate_gold .= "<tr><td>Yang dibayar Yogya</td>
								// 							<td>Rp. </td>
								// 							<td align='right'>".number_format($bayar_gold, 0, ",", ".")."</td>
								// 							<td>&nbsp;</td>
								// 						</tr>";	
									
								// 	$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	

								// 	$vcalculate_gold .= "<tr>
								// 							<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))."= $nett_margin% 
								// 								</td>	
								// 						</tr>";		

								// 	$isset_nett_gold = 1;						

								// }		

								// if ($r->yds_responsibility!="0"){
								// 	if ($r->disc2 != "0"){	
								// 		$limit = 4;				
								// 	} else $limit = 1;
								// } else {
								// 	if ($r->disc2 != "0"){	
								// 		$limit = 9;				
								// 	} else {
								// 		if ($r->disc1=="0"){
								// 			$limit = 5;
								// 		} else $limit = 7;
								// 	}//cek disc1 jika ada maka 6
								// }
							

								// for ($i=1;$i<=$limit;$i++){
								// 	$vcalculate_gold .= "<tr><td colspan='4'>&nbsp;</td></tr>";	
								// }
													

							}
						
			//				$vcalculate_gold .= "</table>";
							$vcalculate .= "<tr><td>Margin Yogya ".$pmargin."</td>
												<td>Rp. </td>
												<td align='right'><u>".number_format($margin, 0, ",", ".")."</u></td>
												<td><u> - </u></td>
											</tr>";	
							//jgn muncul nol di perhitungan						
							if ($r->yds_responsibility!=0){
								$vcalculate .= "<tr><td></td>
													<td>Rp. </td>
													<td align='right'>".number_format($sel_margin, 0, ",", ".")."</td>
													<td>&nbsp;</td>
												</tr>";	
								if ($r->disc2!="0"){
									$vcalculate .= "<tr><td>Partisipasi Yogya ".$label."</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($yds_res, 0, ",", ".")."</u></td>
														<td><u> + </u></td>
													</tr>";		
									$vcalculate .= "<tr><td></td>
														<td>Rp. </td>
														<td align='right'>".number_format($yds_res + $sel_margin , 0, ",", ".")."</td>
														<td>&nbsp;</td>
													</tr>";					

								} else {
									$vcalculate .= "<tr><td>Partisipasi Yogya ".$label."</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($yds_res, 0, ",", ".")."</u></td>
														<td><u> + </u></td>
													</tr>";	

								}				
								
							}										
							
							

							if ($r->disc2!="0"){

								if ($r->yds_responsibility!=0){
									$vcalculate .= "<tr><td>Partisipasi Yogya Disc Tamb.</td>
														<td>Rp. </td>
														<td align='right'><u>".number_format($yds2, 0, ",", ".")."</u></td>
														<td><u> + </u></td>
													</tr>";	
								}		
							}

							$vcalculate .= "<tr><td></td>
												<td>Rp. </td>
												<td align='right'>".number_format($bayar, 0, ",", ".")."</td>
												<td>&nbsp;</td>
											</tr>";
							$vcalculate .= "<tr><td>Partisipasi selisih harga.</td>
												<td>Rp. </td>
												<td align='right'><u>".number_format($partisipasi_selisih, 0, ",", ".")."</u></td>
												<td><u> - </u></td>
											</tr>";					
							$vcalculate .= "<tr><td>Yang dibayar Yogya</td>
												<td>Rp. </td>
												<td align='right'>".number_format($bayar_bersih, 0, ",", ".")."</td>
												<td>&nbsp;</td>
											</tr>";				

							//ga pake nett margin
							//yg bruto saja				
							// if ($r->without_responsibility=='0'){
								
							// 	$vcalculate .= "<tr><td colspan='4'></td></tr>";		
							// 	$vcalculate .= "<tr>
							// 						<td colspan='4'>Nett Margin = (".number_format($margin, 0, ",", ".")." - ".number_format($yds_res, 0, ",", ".")." ".($r->disc2=="0"?"":"- ".number_format($yds2, 0, ",", ".")."").") / ".($r->disc2=="0"?number_format($sel, 0, ",", "."):number_format($sel2, 0, ",", "."))." = $nett_margin% 
							// 							</td>	
							// 					</tr>";	
								
							// } 

							$vcalculate .=  "<tr><td colspan='4'>&nbsp;</td></tr>";	
							$vcalculate .= "</table>";
							# tadinya ini ga ada
							$vcalculate_gold .= "";	
							
							$aVcalculate[$idx] = $vcalculate;
							$aVcalculateGold[$idx] = $vcalculate_gold;
							$idx++;
							
						} else {
							//$vcalculate .="<table class='vcalculate'><tr><td colspan='4'></td></tr>";
							//$vcalculate_gold .="<table class='vcalculate_gold'><tr><td colspan='4'></td></tr>";
						}
					}

					
					/* cons */
					$cons = $this->Event_model->get_consignment($id, $r->notes);
					foreach ($cons as $val) {
						$this->Event_model->update_consignment($id, $val->event_item_notes, $part1, $part2);
					}

					//echo $part2."<br>";
																	
				}

				

			}
			
			return array(
				"aVcalculate" => $aVcalculate,
				"aVcalculateGold" => $aVcalculateGold
			);
			
		}

		function preview($id, $isnew=null) {
			$data['trans_active'] = 'dcjq-parent active';
			$data['menu_daftar_active'] = 'color:#FFF';
			$data['head'] = 'acara/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'acara/v_preview';
			$data['right_menu'] = 'acara/v_right_menu_preview';
			$data['footer'] = 'template/v_footer';
			
			//send param isnew
			$data['isnew'] = $isnew;

			//get template
			$template = $this->get_template($id);
			$data['rheader'] = $template['rheader'];
			$data['rfooter'] = $template['rfooter'];
			$data['rnotes'] = $template['rnotes'];
			
			$vlocation = "";

			//cek location
			$same_location = $this->Event_model->is_same_location($id);
			if ($same_location=='1'){
				$list = $this->Event_model->get_same_location_content($id);
			} else {
				$list = $this->Event_model->get_diff_location_content($id);
			}
			
			//cek supp
			$cek_supp = $this->Event_model->get_jml_supplier($id);
			
			foreach ($list as $r) {
				// cek date
				$same_date = $this->Event_model->is_same_date($id);

				//hitung net margin
				$hrg = 100000;

				$bruto_price = $r->tax/100 * $hrg;
					
				$after_disc1 = $hrg-($hrg*$r->disc1/100);//100.000-20.000 = 80.000
				$after_disc2 = $after_disc1-($after_disc1*$r->disc2/100);//80.000-10.000

				$jml_diskon = (1-($after_disc2/$hrg));
				$yds = $r->yds_responsibility/100*$jml_diskon;
				$sup = $r->supp_responsibility/100*$jml_diskon;

				$pert="";
				if ($r->yds_responsibility!="0"){
					$pert = "<tr><td>Pertanggungan</td>
										<td>:</td>
										<td> YDS ".$r->yds_responsibility."% SUPPLIER ".$r->supp_responsibility."%</td>
									</tr>";	

				} // else $res_label = "YDS ".$r->yds_responsibility."% SUPPLIER ".$r->supp_responsibility."%";

				$yds_price = $yds*$hrg;//0.08*100.000

				$yds_res = $yds*100;
				$supplier_res = $sup*100;

				if ($after_disc2==0)
					$net_margin = round(($bruto_price - $yds_price), 2, PHP_ROUND_HALF_UP);////////////////////////////////////////////// nah nah nah
				else 
					//$net_margin = round(($bruto_price - $yds_price) / ($after_disc2 * 100), 2, PHP_ROUND_HALF_UP);
					
					$net_margin = round(($bruto_price - $yds_price) / $after_disc2*100, 2, PHP_ROUND_HALF_UP);
				
				//update margin
				$this->Event_model->get_event_item($id, $r->notes, $net_margin);
				
				if ($r->is_sp=="0"){
					//$acara_final = "DISCOUNT " . $r->disc1."%" . ($r->disc2=="0"?"":" + ".$r->disc2."%") . ($r->notes==""?"":" &rarr; ".$r->notes);
					$acara_final = $r->notes;
				} else {
					//$acara_final = "SPECIAL PRICE Rp. ".number_format($r->special_price, 0, ",", ".") . ($r->notes==""?"":" &rarr; ".$r->notes);
					$acara_final = $r->notes;
				}
				
				if ($r->without_responsibility=='1'){
					$jenis_margin = "(netto)";
				} else $jenis_margin = "(bruto)";

				if ($r->is_pkp == '1') 
					$pkp = '% PKP ';
				else 
					$pkp = '% NPKP ';

				// cek is_exc 
				$is_exc = $this->Event_model->get_exc_data($id);


				// acara PKP 0% 
				if ($r->tax == 0){
					$margin = $r->tax.$pkp.$jenis_margin;
				}
				else {
					// acara khusus Cardinal 
					if ($is_exc[0] == NULL || $is_exc[0]=='N'){
						$margin = $r->tax.$pkp.$jenis_margin.($r->yds_responsibility!='0'?' &rarr; <b> Nett margin = '.$net_margin.'% </b>':'');
					}else {
						$margin = $r->tax.$pkp.$jenis_margin;
					}
				}
							
				if ($r->is_sp=='1'){
					$vlocation .= "<tr><td width='14%'>Acara</td>
										<td width='2%'>:</td>
										<td><pre><code>".$acara_final."</code></pre></td>
									</tr>
									<tr><td>Margin Yogya</td>
										<td>:</td>
										<td>$margin</td>
									</tr>";
				} else {
					$vlocation .= "<tr><td width='14%'>Acara</td> 
										<td width='2%'>:</td>
										<td><pre><code>".$acara_final."</code></pre></td>
									</tr>";
					$vlocation .= $pert;				
					$vlocation .= "<tr><td>Margin Yogya</td>
										<td>:</td>
										<td>$margin</td>
									</tr>"; 
					if ($r->yds_responsibility!='0'){
						if ($same_date=='1'){
							if ($cek_supp==1 && $same_location==1){
								$vlocation .= "<tr><td colspan='3'>&nbsp;</td></tr>";	
							}
							
						}
						
					} 

				}

				//get supplier
				//$vlocation .= $this->get_supplier($id, $r->tillcode);
				
				if ($cek_supp!=1){
					$vlocation .= "<tr><td>Supplier</td>
									<td>:</td>
									<td>".$r->supp_code."</td>
								</tr>";	
					if ($same_location=='1'){
						$vlocation .= "<tr><td colspan='3'>&nbsp;</td></tr>";	
					}			
								
				} else {
					
					if ($same_location=='0'){
						/*if ($same_date=='1'){
							$vlocation .= "<tr><td colspan='3'>&nbsp;</td></tr>";
						}*/
					} 
					else {

						/*if ($same_date=='1' && ){
							$vlocation .= "<tr><td colspan='3'>&nbsp;</td></tr>";
						}*/

						if ($r->yds_responsibility=='0' && $same_date=='1'){
							$vlocation .= "<tr><td colspan='3'>&nbsp;</td></tr>";	
						} else {

						}
					
					}
							
				}				
				
				if ($same_date!='1'){
					$vlocation .= $this->get_event_date($id, $r->tillcode);					
				} else {
					$date_tmp = $this->get_event_same_date($id);
				}

				//tempat acara
				if ($same_location=='0'){
					#$vlocation .= $this->get_event_location($id, $r->tillcode);
					$vlocation .= $this->get_event_location_new($id, $r->tillcode);
				}

			} //end foreach
				
			$data['last'] = $this->db->last_query();

			//cek is same supp
			$cek_supp = $this->Event_model->get_jml_supplier($id);
			

			if ($cek_supp==1){
				$get_supplier_data = $this->Event_model->get_supplier_data($id);
				foreach ($get_supplier_data as $r) {
					$supp_code = $r->supp_code;
				}
				$vlocation .=  "<tr>
									<td>Supplier</td>
									<td>:</td>
									<td>".$supp_code."</td>
								</tr>";
				$vlocation .=  "<tr><td colspan='3'><br></td></tr>";
			} 

			//get tillcode
			#$vlocation .= $this->get_tillcode($id);
			$vlocation .= $this->get_tillcode_new($id);
			
			//cek date tmp 
			(isset($date_tmp)? $vlocation .= $date_tmp : "");
			
			//tempat acara
			if ($same_location=='1'){
				#$vlocation .= $this->get_event_same_location($id);
				$vlocation .= $this->get_event_same_location_new($id);
			}
			
			// edit untuk data rheader yg kosong
			if($data['rheader']!=null){
				$vlocation .=  "<tr><td colspan='3'><br></td></tr>";
				$data['vlocation'] = $vlocation;
			}else {
				$vlocation = null;
				$data['vlocation'] = null;
			}
			

			// tampilkan contoh perhitungan 
			$vcalculate = $this->get_perhitungan($id);
			
			#$data['vcalculate'] = $vcalculate['vcalculate']; 
			#$data['vcalculate_gold'] = $vcalculate['vcalculate_gold']; 
			$data['aVcalculate'] = $vcalculate['aVcalculate'];
			$data['aVcalculateGold'] = $vcalculate['aVcalculateGold'];
			
			//set file
			$event_no = $this->Event_model->get_event_no($id);
		    $data['file'] = str_replace("/", "_", $event_no);

		    //get minifieed preview
		    $vminified = "";
		    $preview = $this->Event_model->get_preview();
		    foreach ($preview as $r) {
		    	$vminified .= "<div class='desc'>
							      <div class='thumb'>
							        <span class='badge bg-theme'><i class='fa fa-envelope-o'></i></span>
							      </div>
								  <a href='".base_url()."acara/preview/".$r->id."'>
							        <div class='details'>
							          <p><muted>".$r->event_no."</muted><br/>".$r->about."</p>
							        </div>
								  </a>
							     </div>
							    ";

		    }

		    $data['vminified'] = $vminified;

		    $data['id'] = $id;

		    $data['active'] = $this->Event_model->is_active($id);

		    //get is printed
		    $data['is_printed'] = $this->Event_model->get_is_printed($id);

			//hitung char
		    $data['count'] = strlen($data['vlocation']);

			$style_header = 'margin-bottom: 20px;';
		    
		    if ($data['count']>= 6400){
		    	// $style_header = "page-break-after:auto";
		    	//$kelas = "newspaper";
		    } else {
		    	$style_header = "";
		    	//$kelas = "newspaper_fix";//ok
		    }

			$this->load->view('acara/v_acara', $data);
				
			//////////////////////////////////////////////////// create pdf //////////////////////////////////////////
			
        	$this->load->helper('mpdf_helper');

		    $logo =  "<img src='".base_url()."assets/img/yg_red.png' /><br />";
		    
		    #no need this css
			#$css  = "<link href='".base_url()."assets/css/bootstrap.css' rel='stylesheet'>";
		    $css  = "";
			$css  .=  "<link rel='stylesheet' href='".base_url()."assets/css/style-surat-new.css' />";
		  	
			$aVcalculate = $vcalculate["aVcalculate"];
			$aVcalculateGold = $vcalculate["aVcalculateGold"];
			$innerHtml = "";
			for ($i = 0; $i < sizeof($aVcalculate); $i++) {
				$innerHtml .= "<table style='width:100% !important;' border='0' cellpadding='0' cellspacing='0'><tr>";
				$innerHtml .= "<td style='width:50%; vertical-align:top;'>" . (isset($aVcalculate[$i]) ? $aVcalculate[$i] : "&nbsp;") . "</td>";
				$innerHtml .= "<td style='vertical-align:top;'>" . (isset($aVcalculateGold[$i]) ? $aVcalculateGold[$i] : "&nbsp;") . "</td>";
				$innerHtml .= "</tr></table>";
			}
			
			$html = "$css".$logo.
		    		$data['rheader'] . 
	    			"<table class='view_acara' border='0' >" .
						$data['vlocation'] .
					"</table>" .
					
					#"<table border=0 style='width:100%;height:auto'>".
					#	"<tr><td width='50%' valign='top' style='font-size:40px;'>".$data['vcalculate']."</td>" .
					#	"<td width='50%' valign='top' style='font-size:40px;'>".$data['vcalculate_gold']."</td></tr>" .
					#"</table>".
					
					$innerHtml . 
					
					"<div class='pdf_footer' style='page-break-inside:auto;clear:both;'>".$data['rfooter'] ."</div>" .
					"<div class='pdf_notes'>".$data['rnotes'] ."</div>" 
		    		
		    		;
	
		   // echo $html;
		   
		   pdf_create($html, $event_no);
		}
		
		public function refresh_minified(){
			//get minifieed preview
		    $vminified = "";
		    $preview = $this->Event_model->get_preview();
		    foreach ($preview as $r) {
		    	$vminified .= "<div class='desc'>
							      <div class='thumb'>
							        <span class='badge bg-theme'><i class='fa fa-envelope-o'></i></span>
							      </div>
								  <a href='".base_url()."acara/preview/".$r->id."'>
							        <div class='details'>
							          <p><muted>".$r->event_no."</muted><br/>".$r->about."</p>
							        </div>
								  </a>
							     </div>
							    ";

		    }

		    echo $vminified;
		    
		}
		
		public function duplicate($id){
			$duplicateNumber = $this->Event_model->duplicate($id);
			$this->session->set_userdata("duplicateNumber", $duplicateNumber);
			
			redirect('acara/list/1');
		}

		public function print_data($id, $file){
			$usr = $this->session->userdata['event_logged_in']['username'];
			$upd = date("Y-m-d H:i:s");
			
			//update is_printed to 1
			$this->Event_model->update_printed($id, $usr, $upd);
			     
			$this->load->helper('download');
		    $data = file_get_contents('assets/surat_acara/' . $file); // Read the file's contents
		   	force_download($file, $data);
		}

		public function save($id = 0) {

			$usr = $this->session->userdata['event_logged_in']['username'];
			$upd = date("Y-m-d H:i:s");
			
			$inputs = $this->session->userdata("acaraHolder");
			$inputDetails = $this->input->post();
			
			$source = strtoupper(substr($inputs["templateCode"], 0, 1)) == "Y" ? 1 : 0;
			$isManualSetting = isset($inputs["manualSetting"]) ? 1 : 0;
			
			$isSameDate = $inputDetails["isSameDate"];
			$isSameLocation = $inputDetails["isSameLocation"];
			#$isSameDate = isset($inputs["isSameDate"]) ? 1 : 0;
			#$isSameLocation = isset($inputs["isSameLocation"]) ? 1 : 0;
			$isExc = $inputDetails["isExc"];
			
			# date
			$dateTillcode = $inputDetails["dateTillcode"];
			$dateEventStartDate = $inputDetails["dateEventStartDate"];
			$dateEventEndDate = $inputDetails["dateEventEndDate"];
			$dateEventHargaFaktur = $inputDetails["dateEventHargaFaktur"];
			$dateEventHargaJual = $inputDetails["dateEventHargaJual"];
			
			$dateTillcodeArr = explode("#", $dateTillcode);
			$dateEventStartDateArr = explode("#", $dateEventStartDate);
			$dateEventEndDateArr = explode("#", $dateEventEndDate);
			$dateEventHargaFakturArr = explode("#", $dateEventHargaFaktur);
			$dateEventHargaJualArr = explode("#", $dateEventHargaJual);
			
			$detailDate = array();
			for ($i = 0; $i < sizeof($dateEventStartDateArr); $i++) {
				$detailDate[$i]["tillcode"] = isset($dateTillcodeArr[$i]) ? $dateTillcodeArr[$i] : "";
				$detailDate[$i]["dateStart"] = isset($dateEventStartDateArr[$i]) ? $dateEventStartDateArr[$i] : "";
				$detailDate[$i]["dateEnd"] = isset($dateEventEndDateArr[$i]) ? $dateEventEndDateArr[$i] : "";
				$detailDate[$i]["hargaFaktur"] = isset($dateEventHargaFakturArr[$i]) ? str_replace(",", "", $dateEventHargaFakturArr[$i]) : 0;
				$detailDate[$i]["hargaJual"] = isset($dateEventHargaJualArr[$i]) ? str_replace(",", "", $dateEventHargaJualArr[$i]) : 0;
			}
			
			# location
			$locationTillcode = $inputDetails["locationTillcode"];
			$locationLocationCode = $inputDetails["locationLocationCode"];
			$locationStoreCode = $inputDetails["locationStoreCode"];
			
			$locationTillcodeArr = explode("#", $locationTillcode);
			$locationLocationCodeArr = explode("#", $locationLocationCode);
			$locationStoreCodeArr = explode("#", $locationStoreCode);
			
			$detailLocation = array();
			for ($i = 0; $i < sizeof($locationLocationCodeArr); $i++) {
				$detailLocation[$i]["tillcode"] = isset($locationTillcodeArr[$i]) ? $locationTillcodeArr[$i] : "";
				$detailLocation[$i]["locationCode"] = isset($locationLocationCodeArr[$i]) ? $locationLocationCodeArr[$i] : "";
				$detailLocation[$i]["storeCode"] = isset($locationStoreCodeArr[$i]) ? $locationStoreCodeArr[$i] : "";
			}
			
			# event
			$eventTillcode = $inputDetails["eventTillcode"];
			$eventSupplierCode = $inputDetails["eventSupplierCode"];
			$eventKota = $inputDetails["eventKota"];
			$eventCategoryCode = $inputDetails["eventCategoryCode"];
			$eventSupplierResponsibility = $inputDetails["eventSupplierResponsibility"];
			$eventYdsResponsibility = $inputDetails["eventYdsResponsibility"];
			$eventIsPkp = $inputDetails["eventIsPkp"];
			$eventMargin = $inputDetails["eventMargin"];
			$eventSp = $inputDetails["eventSp"];
			$eventNotes = $inputDetails["eventNotes"];
			
			$eventTillcodeArr = explode("#", $eventTillcode);
			$eventSupplierCodeArr = explode("#", $eventSupplierCode);
			$eventKotaArr = explode("#", $eventKota);
			$eventCategoryCodeArr = explode("#", $eventCategoryCode);
			$eventSupplierResponsibilityArr = explode("#", $eventSupplierResponsibility);
			$eventYdsResponsibilityArr = explode("#", $eventYdsResponsibility);
			$eventIsPkpArr = explode("#", $eventIsPkp);
			$eventMarginArr = explode("#", $eventMargin);
			$eventSpArr = explode("#", $eventSp);
			$eventNotesArr = explode("#", $eventNotes);
			
			$detailEvent = array();
			for ($i = 0; $i < sizeof($eventTillcodeArr); $i++) {
				$detailEvent[$i]["tillcode"] = isset($eventTillcodeArr[$i]) ? $eventTillcodeArr[$i] : "";
				$detailEvent[$i]["suppCode"] = isset($eventSupplierCodeArr[$i]) ? $eventSupplierCodeArr[$i] : "";
				$detailEvent[$i]["kota"] = isset($eventKotaArr[$i]) ? $eventKotaArr[$i] : "";
				$detailEvent[$i]["categoryCode"] = isset($eventCategoryCodeArr[$i]) ? $eventCategoryCodeArr[$i] : "";
				$detailEvent[$i]["ydsResponsibility"] = isset($eventYdsResponsibilityArr[$i]) ? $eventYdsResponsibilityArr[$i] : 0;
				$detailEvent[$i]["suppResponsibility"] = isset($eventSupplierResponsibilityArr[$i]) ? $eventSupplierResponsibilityArr[$i] : 0;
				$detailEvent[$i]["isPkp"] = isset($eventIsPkpArr[$i]) ? $eventIsPkpArr[$i] : 0;
				$detailEvent[$i]["margin"] = isset($eventMarginArr[$i]) ? $eventMarginArr[$i] : 0;
				$detailEvent[$i]["sp"] = isset($eventSpArr[$i]) ? $eventSpArr[$i] : 0;
				$detailEvent[$i]["notes"] = isset($eventNotesArr[$i]) ? $eventNotesArr[$i] : "";
			}
			
			# remove these variables from inuput
			#$inputs["firstSignature"]
			#$inputs["secondSignature"]
			#$inputs["cc"]
			
			$isSpecialEvent = isset($inputs["isSpecialEvent"]) ? 1 : 0;
			$specialEventDesc = isset($inputs["specialEventDesc"]) ? $inputs["specialEventDesc"] : "";
			
			if ($id) {
				$seq = $this->Acara->update($id, $inputs["eventNo"],
						$inputs["about"], $inputs["purpose"], $inputs["attach"], $inputs["toward"], $inputs["department"], $inputs["divisionCode"], $source,
						$inputs["templateCode"], $inputs["firstSignature"], "", $inputs["notes"], "", $isManualSetting,
						$inputs["letterDate"], $isSameDate, $isSameLocation, $detailEvent, $detailDate, $detailLocation, $usr, $upd, $isSpecialEvent, $specialEventDesc, $isExc
				);
				if ($seq) $seq = $id;
			}
			else {
				$seq = $this->Acara->addNew(
						$inputs["about"], $inputs["purpose"], $inputs["attach"], $inputs["toward"], $inputs["department"], $inputs["divisionCode"], $source,
						$inputs["templateCode"], $inputs["firstSignature"], "", $inputs["notes"], "", $isManualSetting,
						$inputs["letterDate"], $isSameDate, $isSameLocation, $detailEvent, $detailDate, $detailLocation, $usr, $upd, $isSpecialEvent, $specialEventDesc, $isExc
				);	
			}
			
			# remove acaraHolder from session
			if ($seq) $this->session->unset_userdata("acaraHolder");
			
			echo $seq;
		}
		
		public function delete() {
			$input = $this->input->post();
			$ret = $this->Acara->remove($input["id"]);
			if ($ret) echo "success"; else echo "Gagal menghapus data.";
		}
		
		public function loadStores() {
			$stores = $this->Acara->loadAllStore();
			$sto = "";
			foreach($stores as $store) {
				$sto .= $store->store_desc . " (" . $store->store_init . ")|";
				//$sto .= $store->store_desc . "|";
			}
			$sto = substr($sto, 0, strlen($sto)-1);
			echo $sto;
		}
		
		public function loadSuppliers() {
			$suppliers = $this->Acara->loadAllSupplier();
			$supp = "";
			foreach($suppliers as $supplier) {
				$supp .= $supplier->supp_desc . " (" . $supplier->supp_code . ")|";
			}
			$supp = substr($supp, 0, strlen($supp)-1);
			echo $supp;
		}
		
		public function setMarginPkp($tillcode) {
			$marginPkp = $this->Acara->getMarginPkp($tillcode);
			echo $marginPkp["margin"] . "|" . $marginPkp["is_pkp"];
		}
		
		public function loadBrands() {
			$brands = $this->Acara->loadAllBrand();
			$brnd = "";
			foreach($brands as $brand) {
				$brnd .= $brand->brand_desc . " (" . $brand->brand_code . ")|";
			}
			$brnd = substr($brnd, 0, strlen($brnd)-1);
			echo $brnd;
		}
		
		public function loadBrandsBySupplier($supplier) {
			$brands = $this->Acara->loadBrandsBySupplier($supplier);
			$brnd = "";
			foreach($brands as $brand) {
				$brnd .= $brand->brand_desc . " (" . $brand->brand_code . ")|";
			}
			$brnd = substr($brnd, 0, strlen($brnd)-1);
			echo $brnd;
		}
		
		public function loadTillcodes($division) {
			$tillcodes = $this->Acara->loadTillcodeByDivision($division);
			$till = "";
			foreach($tillcodes as $tillcode) {
				$till .= $tillcode->tillcode . " (" . $tillcode->disc_label . ")|";
			}
			$till = substr($till, 0, strlen($till)-1);
			echo $till;
		}
		
		public function loadTillcodesBySupplier($division, $supplier) {
			$tillcodes = $this->Acara->loadTillcodeByDivision($division, $supplier);
			$till = "";
			foreach($tillcodes as $tillcode) {
				$till .= $tillcode->tillcode . " (" . $tillcode->disc_label . ")|";
			}
			$till = substr($till, 0, strlen($till)-1);
			echo $till;
		}
		
		public function loadTillcodesByBrand($division, $brand) {
			$brand = str_replace("~", " ", $brand);
			$tillcodes = $this->Acara->loadTillcodeByDivision($division, "", $brand);
			$till = "";
			foreach($tillcodes as $tillcode) {
				$till .= $tillcode->tillcode . " (" . $tillcode->disc_label . ")|";
			}
			$till = substr($till, 0, strlen($till)-1);
			echo $till;
		}
		
		public function loadTillcodesBySupplierAndBrand($division, $supplier, $brand) {
			$brand = str_replace("~", " ", $brand);
			$tillcodes = $this->Acara->loadTillcodeByDivision($division, $supplier, $brand);
			$till = "";
			foreach($tillcodes as $tillcode) {
				$till .= $tillcode->tillcode . " (" . $tillcode->disc_label . ")|";
			}
			$till = substr($till, 0, strlen($till)-1);
			echo $till;
		}

		//function by gie
		public function filterStoreByTillcode($tillcode) {
			$stores = $this->Acara->filterStoreByTillcode($tillcode);
			$store = "";
			$i = 0;
			foreach($stores as $r) {
				$store .= "<div class='col-sm-2'>&nbsp;&nbsp;&nbsp;";
				$store .= "<label title='".$r->store_desc."'><input class='check_store' name='check_store' id='check_store_$i' type='checkbox' checked  value='".$r->store_desc." (".$r->store_init.")'> ".$r->store_init."</input></label>";
				$store .= "</div>";
			}
			
			echo $store;
		}

		public function load_pic($supplierCode) {
			$pic = $this->Acara->load_pic($supplierCode);
			$ret = "";
			
			foreach($pic as $r) {
				$ret .= $r->name . ", ";
			}
			
			echo rtrim($ret, ", ");
		}
		
		public function filterSupplierByTillcode($tillcode) {
			$res = $this->Acara->filterSupplierByTillcode($tillcode);
			$supp = "";
			$i = 0;
			foreach($res as $r) {
				$supp .= $r->supp_desc." (".$r->supp_code.")";
			}
			
			echo $supp;
		}

		public function filterBrandByTillcode($tillcode) {
			$res = $this->Acara->filterBrandByTillcode($tillcode);
			$brand = "";
			$i = 0;
			foreach($res as $r) {
				$brand .= $r->brand_desc." (".$r->brand_code.")";
			}
			
			echo $brand;
		}

		public function filterKotaBySupplier($supplierCode = null) {
			$res = $this->Acara->filterKotaBySupplier($supplierCode);
			$kota = "";
			$i = 0;
			
			foreach($res as $r) {
				if (strtolower($r->city)=='bandung')
					$selected = "selected";
				else
					$selected = "";

				$kota .= "<option value='".$r->city."' $selected>".$r->city."</option>";
			}
			
			echo $kota;
		}

		public function filterKotaBySupplierEdit($supplierCode = null) {
			$res = $this->Acara->filterKotaBySupplier($supplierCode);
			$kota = "";
			$i = 0;
			
			foreach($res as $r) {
				$kota .= "<option value='".$r->city."' >".$r->city."</option>";
			}
			
			echo $kota;
		}
		
		public function cancel($id){
			$r = $this->Acara->cancel($id);
			if ($r=='1')
				$page = base_url() . 'acara/list/1';
			else 
				$page = base_url() . 'acara/list/3';

			redirect($page);
		}

		public function cancel2() {
			$r = $this->Acara->cancel($this->input->post('id'));

			/*if ($r=='1')
				$page = base_url() . 'acara/list/1';
			else 
				$page = base_url() . 'acara/list/3';*/

			if ($r=='1') echo "success"; else echo "Gagal menghapus data.";
		}
		
	}	
	

?>
