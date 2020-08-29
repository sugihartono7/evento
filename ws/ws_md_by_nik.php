<?php

#header('Content-type: application/json');
date_default_timezone_set('Asia/Jakarta');
ini_set("display_errors", "0");

if (isset($_GET["p_nik"])) {
	
	$nik = $_GET["p_nik"];
	
	include_once("db.conf.php");
	
	$data["success"] = 0;
	$data["error_message"] = "";
	$data["count_md"] = 0;
	$data["md"] = null;
	
	// make connection
	$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
	
	if ($conn) {
		
		$aParams = array($nik);
		
		# -- acara from evento application
		$sql = "select cat_code, name, div_code, md_title, email from mst_md where nik = $1";
		
		$res = pg_query_params($conn, $sql, $aParams);
		if ($res) {
			$cnt = 0;
			while ($row = pg_fetch_assoc($res)) {
				$cnt++;
				$data["md"][] = $row;
			}
			
			$data["success"] = 1;
			$data["count_md"] = $cnt;
			
			pg_free_result($res);
		}
		else {
			$data["error_message"] = "Failed to do query.";
		}
		
		//not usually necessary
		//pg_close($conn);
	}
	else {
		$data["error_message"] = "Failed to connect to database.";
	}
	
	//echo json_encode($data, JSON_NUMERIC_CHECK);
	echo json_encode($data);
}

else {
    $data["success"] = 0;
    $data["error_message"] = "NIK empty.";
    
    //echo json_encode($data, JSON_NUMERIC_CHECK);
    echo json_encode($data);
}

?>