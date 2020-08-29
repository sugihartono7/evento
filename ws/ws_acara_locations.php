<?php

#header('Content-type: application/json');
date_default_timezone_set('Asia/Jakarta');
ini_set("display_errors", "0");

if (isset($_GET["p_event_no"])) {
    
	include_once("db.conf.php");
    
    $data["success"] = 0;
    $data["error_message"] = "";
    $data["count_location"] = 0;
	$data["about"] = "";
	$data["division_code"] = "";
	$data["first_signature"] = "";
	$data["second_signature"] = "";
    $data["locations"] = array();
    
    // make connection
	$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
	
    if ($conn) {
        
        $aParams = array($_GET["p_event_no"]);

        # -- acara from evento application
        $sql = "select x.about, x.division_code, x.first_signature, (select coalesce(v.dmm_name, '') from mst_dmm v where v.div_code = x.division_code limit 1) second_signature,
				case when x.is_same_location = 0 then 
					get_event_location(x.id, y.tillcode) 
				else
					get_event_same_location(x.id) 
				end  site_group
				from event x inner join event_item y on x.id = y.event_id
				left join mst_tillcode z on z.tillcode = y.tillcode
				where x.event_no = $1";
		
		$res = pg_query_params($conn, $sql, $aParams);
        if ($res) {
            $site_group = "";
            $rowNumber = 1;
			while ($row = pg_fetch_assoc($res)) {
                if ($rowNumber == 1) {
					$data["about"] = $row["about"];
					$data["division_code"] = $row["division_code"];
					$data["first_signature"] = $row["first_signature"];
					$data["second_signature"] = $row["second_signature"];	
				}
				//$site_group = $row["site_group"];
				$site_groups[] = $row["site_group"];
				
				$rowNumber++;
            }
			
			$cnt = 0;
			foreach($site_groups as $site_group) {
				$sites = explode(", ", $site_group);
				foreach($sites as $site) {
					if (!empty($site)) {
						if (!in_array($site, $data["locations"])) {
							$cnt++;
							$data["locations"][] = $site;	
						}
					}
				}	
			}
			
            $data["success"] = 1;
            $data["count_location"] = $cnt;
            
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
    $data["error_message"] = "Invalid call.";
    
    //echo json_encode($data, JSON_NUMERIC_CHECK);
    echo json_encode($data);
}

?>