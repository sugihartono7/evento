<?php

#header('Content-type: application/json');
date_default_timezone_set('Asia/Jakarta');
ini_set("display_errors", "0");

if (isset($_GET["p_date"]) && isset($_GET["p_date_2"])) {
    
    #$busDate = date('Y-m-d', strtotime("-1 days"));
    #$busDate = "2015-03-27";
    
    # -- format yyyy-mm-dd hh24:mi:ss, replace '_' from datetime parameters
    $busDate = str_replace("_", " ", $_GET["p_date"]);
	$busDate2 = str_replace("_", " ", $_GET["p_date_2"]);
    
	include_once("db.conf.php");
    
    $data["success"] = 0;
    $data["error_message"] = "";
    $data["count_acara"] = 0;
    $data["acara"] = array();
    
    // make connection
	$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
	
    if ($conn) {
        
        $aParams = array($busDate, $busDate2, $busDate, $busDate2);
        
        # -- acara from evento application
        $sql = "select distinct x.event_no, x.department, x.division_code, y.category_code, y.supp_code, x.source, x.first_signature, x.letter_date, x.id_venditore, to_char(x.updated_date, 'yyyy-mm-dd hh24:mi:ss') updated_date   
				from event x inner join event_item y on y.event_id = x.id
				where x.active = 1 and x.is_printed = 1 and ((to_char(x.created_date, 'yyyy-mm-dd hh24:mi:ss') between $1 and $2) or (to_char(x.updated_date, 'yyyy-mm-dd hh24:mi:ss') between $3 and $4))";
        
        $h = fopen("/tmp/test_evento.txt", "w");
        fwrite($h, $sql);
        fclose($h);	
        
		$res = pg_query_params($conn, $sql, $aParams);
        if ($res) {
            $cnt = 0;
            while ($row = pg_fetch_assoc($res)) {
                $cnt++;
                $data["acara"][] = $row;
            }
            
            $data["success"] = 1;
            $data["count_acara"] = $cnt;
            
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
    $data["error_message"] = "Interval date empty.";
    
    //echo json_encode($data, JSON_NUMERIC_CHECK);
    echo json_encode($data);
}

?>