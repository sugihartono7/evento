<?php	
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set("display_errors", TRUE);
	ini_set("display_startup_errors", TRUE);
	
	// set the default timezone to use. Available since PHP 5.1
	date_default_timezone_set('Asia/Jakarta');
	
	define("EOL", (PHP_SAPI == "cli") ? PHP_EOL : "<br>");
	
	include_once ("db.conf.php");
	
	echo "start job at ", date("Y-m-d H:i:s"), EOL;
	
	echo "set web service.. ", EOL;
	$url = $webService . "/ws_load_stores.php?hash=vendit0re";
	
	echo "read content from web service.. ";
	$json = @file_get_contents($url);
	if ($json) {
		$obj = json_decode($json);
		if ($obj->detail) {
			
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
			
			/*
			echo "emptying table.. ";
			# -- empty first
			$sql = "truncate table mst_store";
			$res = pg_query($conn, $sql);
			echo "ok.", EOL;
			*/
			
			$lineNumber = 0;
			$totalInsertedRow = 0;
				
			echo "start loop.", EOL;
			foreach ($obj->detail as $row) {
				$lineNumber++;
				
				// YOGYA only
				if (($row->store_code < 400 || $row->store_code > 699) && strtoupper(substr($row->store_desc, 0, 6)) !== "YOMART") {
					# check exist
					$aParams = array($row->store_code);
					$sql = "select count(store_code) cnt from mst_store where store_code = $1";
					$res2 = pg_query_params($conn, $sql, $aParams);
					$cnt = 0;
					if ($row2 = pg_fetch_array($res2)) {
						$cnt = $row2["cnt"];
					}
					
					# exist, update
					if ($cnt) {
						$aParams = array($row->store_code, $row->store_init, $row->store_desc, $row->address, $row->city, $row->regional_code, 1);
					
						# -- UPDATE STORE --
						$sql = "update mst_store set store_init = $2, store_desc = $3, address = $4, city = $5, regional_code = $6, is_active = $7, created_date = current_timestamp where store_code = $1";
						$res = pg_query_params($conn, $sql, $aParams);
						if (!$res) {
							echo "failed at row ", $lineNumber, ", data: store_code, store_init, store_desc, address, city, regional_code => ";
							echo $row->store_code, ", ", $row->store_init, ", ", $row->store_desc, ", ", $row->address, ", ", $row->city, ", ", $row->regional_code, EOL;
							continue;
						}
						else {
							$totalInsertedRow++;
						}
					}
					# insert
					else {
						$aParams = array($row->store_code, $row->store_init, $row->store_desc, $row->address, $row->city, $row->regional_code, 1);
					
						# -- INSERT STORE --
						$sql = "insert into mst_store (store_code, store_init, store_desc, address, city, regional_code, is_active, created_date) values ($1, $2, $3, $4, $5, $6, $7, current_timestamp)";
						$res = pg_query_params($conn, $sql, $aParams);
						if (!$res) {
							echo "failed at row ", $lineNumber, ", data: store_code, store_init, store_desc, address, city, regional_code => ";
							echo $row->store_code, ", ", $row->store_init, ", ", $row->store_desc, ", ", $row->address, ", ", $row->city, ", ", $row->regional_code, EOL;
							continue;
						}
						else {
							$totalInsertedRow++;
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