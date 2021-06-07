<?php
	// $trx = '17500';
	$trx = $_GET['trx'];
	if ($_SERVER['SERVER_NAME']=='localhost') {
		$api_key = file_get_contents("../config/sandboxapikey.txt");
	} else {
		$api_key = file_get_contents("../config/apikey.txt");
	}

	if ($_SERVER['SERVER_NAME']=='localhost') {
		$CURLOPT_URL="https://sandbox.ipaymu.com/api/transaksi?key=$api_key&id=$trx&format=json";
	} else {
		$CURLOPT_URL="https://my.ipaymu.com/api/transaksi?key=$api_key&id=$trx&format=json";
	}

	
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	//   CURLOPT_URL => "https://my.ipaymu.com/api/transaksi?key=$api_key&id=$trx&format=json",
	  CURLOPT_URL => "$CURLOPT_URL",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET"
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}
	
?>