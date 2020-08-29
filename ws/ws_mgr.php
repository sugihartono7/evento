<?php

#header('Content-type: application/json');
date_default_timezone_set('Asia/Jakarta');
ini_set("display_errors", "0");

include_once("db.conf.php");

$data["success"] = 0;
$data["error_message"] = "";
$data["count_mgr"] = 0;
$data["mgr"] = null;

// make connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");

if ($conn) {
	
	# -- acara from evento application
	$sql = "select div_code, dmm_name mgr_name, dmm_title mgr_title, nik from mst_dmm";
	
	$res = pg_query($conn, $sql);
	if ($res) {
		$cnt = 0;
		while ($row = pg_fetch_assoc($res)) {
			$cnt++;
			$data["mgr"][] = $row;
		}
		
		$data["success"] = 1;
		$data["count_mgr"] = $cnt;
		
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

?>