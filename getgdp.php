<?php 
	include_once "codes.php";
	
	$wb_url = "http://api.worldbank.org/countries/%s/indicators/NY.GDP.PCAP.PP.CD?date=%d&format=json";
	$wb_curl = wbconnect($url);
	
	$prev_Gdp = 500;
	$hist_gdp = [];	
	if(!isset($_GET['country_code'])){
		echo "Failed to get country code";
		exit();
	}
	
	$country_code = $_GET['country_code'];
		
	for ($year=1988; $year<=2012; $year++){
		$url = sprintf($wb_url, $country_code, $year);
		curl_setopt($wb_curl, CURLOPT_URL, $url);
		$result = curl_exec($wb_curl);
		
		if($result){
			$result = json_decode($result,true);
			$gdp_pcap = $result[1][0]["value"] ;
		
			if (!$gdp_pcap){
				$gdp_pcap = $prev_gdp;
			}
		}
		//echo $gdp_pcap;
		$hist_gdp[] = $gdp_pcap;
		$prev_gdp = $gdp_pcap;
	}
	
	curl_close($wb_curl);
	echo json_encode($hist_gdp);
	
	function wbconnect($url){
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		return $curl;
	}
?>
