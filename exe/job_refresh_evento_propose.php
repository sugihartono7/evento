<?php	
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set("display_errors", TRUE);
	ini_set("display_startup_errors", TRUE);
	
	// set the default timezone to use. Available since PHP 5.1
	date_default_timezone_set('Asia/Jakarta');
	
	define("EOL", (PHP_SAPI == "cli") ? PHP_EOL : "<br>");
	
	include_once ("db.conf.php");
	
	function makeLetterNumber($num, $div) {
		#D9999/SA.YDS/YG.SB/07/2015
		
		$code = "";
		switch(strtoupper($div)) {
			case "A":
				$code = "A";
				break;
			case "B":
				$code = "B";
				break;
			case "C":
				$code = "C";
				break;
			case "D":
				$code = "SB";
				break;
			case "E":
				$code = "E";
				break;
		}
		
		return $div . str_pad($num, 5, "0", STR_PAD_LEFT) . "/SA.YDS/YG." . $code . "/" . date("m") . "/" . date("Y");
	}
	
	echo "start job at ", date("Y-m-d H:i:s"), EOL;
	
	echo "set web service.. ", EOL;
	$url = $webService . "/ws_load_evento_propose.php?hash=vendit0re";
	
	echo "read content from web service.. ";
	$json = @file_get_contents($url);
	
	if ($json) {
		$obj = json_decode($json);
		if ($obj->header) {
			
			echo "got.", EOL;
			echo "make connection to database.. ";
			
			// make connection
			$conn = pg_connect("host=$host port=5332 dbname=$dbname user=$user password=$pass");
			if (!$conn) {
				die ("Error: Could not establish a connection!");
			}
			echo "connected.", EOL;
			# eo make connection
			
			echo "start transaction.. ", EOL;
			# start transaction
			$res = pg_query($conn, "BEGIN");
			
			$lineNumber = 0;
			$totalInsertedRow = 0;
				
			echo "start loop.", EOL;
			foreach ($obj->header as $row) {
				$lineNumber++;
				
				# check exist
				$sql = "select id, event_no from event where id_venditore = $1";
				$aParams = array($row->SEQ);
				$res = pg_query_params($conn, $sql, $aParams);
				$existingId = 0;
				$existingEventNo = "";
				$cnt = 0;
				if ($row2 = pg_fetch_array($res)) {
					$cnt = 1;
					$existingId = $row2["id"];
					$existingEventNo = $row2["event_no"];
				}
				
				if ($cnt > 0) {
					// exist, update
				#	$sql = "delete from event_same_date where event_id = $1";
				#	$aParams = array($existingId);
				#	$res = pg_query_params($conn, $sql, $aParams);
					
				#	$sql = "delete from event_same_location where event_id = $1";
				#	$aParams = array($existingId);
				#	$res = pg_query_params($conn, $sql, $aParams);
					
				#	$sql = "delete from event where id = $1";
				#	$aParams = array($existingId);
				#	$res = pg_query_params($conn, $sql, $aParams);
					
				#	$seq = $existingId;
				#	$seqLetterNumber = $existingEventNo;
				}
				else {
					// insert new
					# get sequence number
					$seq = 0;
					$sql = "select nextval('event_seq') seq";        
					$res = pg_query($conn, $sql);
					if ($row2 = pg_fetch_assoc($res)) {
						$seq = $row2["seq"];
					}
					
					# create letter number
					$seqLetterNumber = 0;
					switch(strtoupper($row->DIVISION_CODE)) {
						case "A":
							$sql = "select nextval('letter_no_a_seq') seq";
							$res = pg_query($conn, $sql);
							if ($row2 = pg_fetch_assoc($res)) {
								$seqLetterNumber = makeLetterNumber($row2["seq"], $row->DIVISION_CODE); 
							}
							break;
						case "B":
							$sql = "select nextval('letter_no_b_seq') seq";
							$res = pg_query($conn, $sql);
							if ($row2 = pg_fetch_assoc($res)) {
								$seqLetterNumber = makeLetterNumber($row2["seq"], $row->DIVISION_CODE);    
							}
							break;
						case "C":
							$sql = "select nextval('letter_no_c_seq') seq";
							$res = pg_query($conn, $sql);
							if ($row2 = pg_fetch_assoc($res)) {
								$seqLetterNumber = makeLetterNumber($row2["seq"], $row->DIVISION_CODE);      
							}
							break;
						case "D":
							$sql = "select nextval('letter_no_d_seq') seq";
							$res = pg_query($conn, $sql);
							if ($row2 = pg_fetch_assoc($res)) {
								$seqLetterNumber = makeLetterNumber($row2["seq"], $row->DIVISION_CODE);     
							}
							break;
						case "E":
							$sql = "select nextval('letter_no_e_seq') seq";
							$res = pg_query($conn, $sql);
							if ($row2 = pg_fetch_assoc($res)) {
								$seqLetterNumber = makeLetterNumber($row2["seq"], $row->DIVISION_CODE);     
							}
							break;        
					}	
					
					if ($row->JUSAMI == 1) {
						$same_location = 1;
					}
					else {
						$same_location = 0;
					}
					
					// header
					$sql = "insert into event (id, event_no, about, department, division_code, source, first_signature, created_by, created_date, id_venditore, is_same_date, is_same_location, template_code, letter_date, active, propose_by, propose_brand, propose_notes, is_exc)
							values ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19)";
					
					$aParams = array($seq, $seqLetterNumber, $row->INFO, 'Fashion', $row->DIVISION_CODE, 0, $row->MD_NAME, $row->SUPP_CODE, $row->CREATED_AT, $row->SEQ, 0, $same_location, 'S05', $row->LETTER_DATE, 1, $row->SUPP_CODE, $row->BRAND_DESC, $row->NOTES, $row->IS_EXC);
					$res = pg_query_params($conn, $sql, $aParams);
					if (!$res) {
						echo "failed at row ", $lineNumber, ", data: id, event_no, about, division_code, first_signature, created_by, created_date, id_venditore, is_same_date, is_same_location, template_code, letter_date, active, propose_by, propose_brand, propose_notes, is_exc => ";
						echo $seq, ", ", $seqLetterNumber, ", ", $row->INFO, ", Fashion, ", $row->DIVISION_CODE, ", 0, ", $row->MD_NAME, ", ", $row->SUPP_CODE, ", ", $row->CREATED_AT, ", ", $row->SEQ, ", 0, ", $same_location, ", S05, ", $row->LETTER_DATE, ", 1, ", $row->SUPP_CODE, ", ", $row->BRAND_DESC, ", ", $row->NOTES, ", ", $row->IS_EXC, EOL;
						continue;
					}
					else {
						$totalInsertedRow++;
					}
					
					// dates
					$lineNumberDate = 0;
					$totalInsertedRowDate = 0;
					
					$idx = "detail_date_" . $row->SEQ;
					foreach ($obj->$idx as $row_date) {
						$lineNumberDate++;
							
						$aParams = array($seq, $row_date->START_PERIOD, $row_date->END_PERIOD, $row_date->LINE_NUMBER, $row_date->HARGA_FAKTUR, $row_date->HARGA_JUAL);
						$sql = "insert into event_date (event_id, date_start, date_end, tillcode, harga_faktur, harga_jual) values ($1, $2, $3, $4, $5, $6)";
						$res = pg_query_params($conn, $sql, $aParams);
						
						$aParams_2 = array($seq, $row_date->START_PERIOD, $row_date->END_PERIOD, $row_date->HARGA_FAKTUR, $row_date->HARGA_JUAL);
						$sql_2 = "insert into event_same_date (event_id, date_start, date_end, harga_faktur, harga_jual) values ($1, $2, $3, $4, $5)";
						$res_2 = pg_query_params($conn, $sql_2, $aParams_2);
						
						if ($res && $res_2) {
							$totalInsertedRowDate++;
						}
						else {
							echo "failed at row ", $lineNumberDate, ", data: event_id, date_start, date_end, tillcode, harga_faktur, harga_jual => ";
							echo $seq, ", ", $row_date->START_PERIOD, ", ", $row_date->END_PERIOD, ", ", $row_date->LINE_NUMBER, ", ", $row_date->HARGA_FAKTUR, ", ", $row_date->HARGA_JUAL, EOL;
							continue;
						}
						
					}
					
					// locations
					$lineNumberLocation = 0;
					$totalInsertedRowLocation = 0;
					$aCheckLocation = array();
					
					$idx = "detail_location_" . $row->SEQ;
					foreach ($obj->$idx as $row_location) {
						$lineNumberLocation++;
						
						if ($row_location->LOC_DESC == "Area Promosi")
							$location = "PRM";
						else if ($row_location->LOC_DESC == "Atrium")
							$location = "ATR";
						else
							$location = "CTR";
						
						// pnly distinct location
						if ($row->JUSAMI == 1) {
							$check = $location . $row_location->STORE_CODE;
							if (!in_array($check, $aCheckLocation)) {
								$aParams = array($seq, $row_location->STORE_CODE, $location, $row_location->LINE_NUMBER);
								$sql = "insert into event_location (event_id, store_code, location_code, tillcode) values ($1, $2, $3, $4)";
								$res = pg_query_params($conn, $sql, $aParams);
								
								$aParams_2 = array($seq, $row_location->STORE_CODE, $location);
								$sql_2 = "insert into event_same_location (event_id, store_code, location_code) values ($1, $2, $3)";
								$res_2 = pg_query_params($conn, $sql_2, $aParams_2);	
							}
						}
						else {
							$aParams = array($seq, $row_location->STORE_CODE, $location, $row_location->LINE_NUMBER);
							$sql = "insert into event_location (event_id, store_code, location_code, tillcode) values ($1, $2, $3, $4)";
							$res = pg_query_params($conn, $sql, $aParams);
							
							$aParams_2 = array($seq, $row_location->STORE_CODE, $location);
							$sql_2 = "insert into event_same_location (event_id, store_code, location_code) values ($1, $2, $3)";
							$res_2 = pg_query_params($conn, $sql_2, $aParams_2);	
						}
						
						if ($res && $res_2) {
							$totalInsertedRowLocation++;
							$aCheckLocation[] = $location . $row_location->STORE_CODE;
						}
						else {
							echo "failed at row ", $lineNumberLocation, ", data: event_id, store_code, location_code, tillcode => ";
							echo $seq, ", ", $row_location->STORE_CODE, ", ", $location, ", ", $row_location->LINE_NUMBER, EOL;
							continue;
						}
					}
					
				}
					
			}
			echo "end loop.", EOL;
			echo $totalInsertedRow . " rows inserted / updated.", EOL;
			
			echo "commit transaction.. ", EOL;
			# commit transaction
			$res = pg_query($conn, "COMMIT");
			
			echo "close connection.", EOL;			
			# php document said no need this call 
			pg_close($conn);
		
		}
	}
	
	echo "job done.. leave job.", EOL;
	echo "finished job at ", date("Y-m-d H:i:s"), EOL, EOL;
	
?>