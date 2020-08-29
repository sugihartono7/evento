<?php	
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set("display_errors", TRUE);
	ini_set("display_startup_errors", TRUE);
	
	// set the default timezone to use. Available since PHP 5.1
	date_default_timezone_set('Asia/Jakarta');
	
	define("EOL", (PHP_SAPI == "cli") ? PHP_EOL : "<br>");
	
	include_once ("exe/db.conf.php");
	
	
	 
	echo "start job at ", date("Y-m-d H:i:s"), EOL;
	
	// make connection
	$conn = pg_connect("host=$host port=5332 dbname=$dbname user=$user password=$pass");
	if (!$conn) {
		
		$h = fopen("/tmp/test_eventox.txt", "w");
		fwrite($h, 'not connected');
		fclose($h);
		
		die ("Error: Could not establish a connection!");
	}
	
	$h = fopen("/tmp/test_eventox.txt", "w");
	fwrite($h, 'connected');
	fclose($h);
	
	
	echo "connected.", EOL;
	# eo make connection
	
	echo "close connection.", EOL;			
	# php document said no need this call 
	pg_close($conn);
	
	echo "job done.. leave job.", EOL;
	echo "finished job at ", date("Y-m-d H:i:s"), EOL, EOL;
	
?>