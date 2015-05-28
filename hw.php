<?php 
	include_once "codes.php";
	
	$wb_url = "http://api.worldbank.org/countries/%s/indicators/NY.GDP.PCAP.PP.CD?date=%d&format=json"
	$wb_curl = wbconnect($url);
	$dbconn = dbconnect();
	
	foreach($codes as $code=>$name){
		$query = "CREATE TABLE IF NOT EXISTS $name (year int, gdp numeric)";
		$rs = pg_query($dbconn, $query) or die("Couldn't create table");
	
		for($year=1988; $year<=2012; $year++){
			$url = sprintf($wb_url, $code, $year);
			curl_setopt($wb_curl,  CURLOPT_URL, $url);
			$result = curl_exec($wb_curl);
			
			if($result){
				$result = json_decode($result,true);
				$gdp_pcap = $result[1][0]["value"];
				
				$query = "INSERT INTO $name (year, gdp) VALUES ($year, $gdp)";
				$res = pg_query($dbconn, $query) or die("Couldn't insert values into $name db");
			}
			else {
				die("Unexpected data returned from World Bank");
			}
		}
	}
	
	pg_close($dbconn);
	curl_close($wb_curl);
	
	function wbconnect($url) {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		return $curl;
	}
	
	function dbconnect() {
		$user = "username";
		$pw = "password";
		$db = "dbname";
		$host = "host";
	
		$conn = pg_connect("host=$host port=5432 dbname=$db user=$user password=$pw sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());
	
		return $conn;
	}
	
	
?>
