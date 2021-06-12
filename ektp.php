<?php

	$username= "admin";
	$password= "M4Potl0ZZCET2I5AsGrt6w==";
	$CURLOPT_URL="172.24.33.162:8089/gmkservice/ktpreader/services/bacaChip";
	
	

	$curl = curl_init();
	  curl_setopt_array($curl, array(	
	  CURLOPT_URL => "$CURLOPT_URL",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "username=$username&password=$password&format=json",
	  CURLOPT_HTTPHEADER => array(
	     "content-type: application/x-www-form-urlencoded"
	   )
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {

	  $data=json_decode($response,true);
	}	

	echo $data['messagereturn'];
	echo "<br>";
	echo $data['nik'];
	echo "<br>";
	echo $data['nama'];
	echo "<br>";
	echo $data['gender'];
	echo "<br>";
	echo $data['alamat'];
	echo "<br>";
	echo $data['tempatlahir'];
	echo "<br>";
	echo $data['tgllahir'];
	echo "<br>";
	echo $data['rt'];
	echo "<br>";
	echo $data['rw'];
	echo "<br>";
	echo $data['kelurahan'];
	echo "<br>";
	echo $data['kecamatan'];
	echo "<br>";
	echo $data['kabupaten'];
	echo "<br>";
	echo $data['provinsi'];
	echo "<br>";
	echo $data['goldarah'];
	echo "<br>";
	echo $data['agama'];
	echo "<br>";
	echo $data['maritalstatus'];
	echo "<br>";
	echo $data['pekerjaan'];
	echo "<br>";
	echo $data['kewarganegaraan'];
	echo "<br>";
	echo $data['datafoto'];
	echo "<br>";
	echo $data['data_ttd'];
	echo "<br>";
	echo $data['uid'];
	echo "<br>";
	echo $data['aktivasi'];
	echo "<br>";
	echo $data['verified'];
	echo "<br>";
	echo $data['namaoperator'];
	echo "<br>";
	echo $data['petugasBypass'];
	echo "<br>";
	echo $data['errornumber'];
	echo "<br>";


	// var_dump($data);
?>