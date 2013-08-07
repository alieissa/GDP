<?php
	
	$curl = curl_init();
	$options = array(
		CURLOPT_URL =>"http://api.worldbank.org/countries/sd/indicators/NY.GDP.PCAP.PP.CD?date=2012&format=json",
		CURLOPT_RETURNTRANSFER => true,
		);
	curl_setopt_array($curl, $options);
	$result = curl_exec($curl); 
	
	//json_decode($result,true)
	//true parameter makes json_decode return an associative array
	
	if($result){
		$decod_result = json_decode($result,true);
		$gdp_pcap = $decod_result[1][0]["value"];
		echo "<p>{$gdp_pcap}</p>";
		echo "Hello";
		
	}else{
		echo "Error";
	}
	curl_close($curl);
?>
