<?php
//ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");



$trx_id 	= $_POST['trx_id'];
$sid		= $_POST['sid'];
$status 	= $_POST['status'];
$via 		= $_POST['via'];

// $trx_id 	= "38669";
// $status 	= "berhasil";
// $via 		= "qris";



// echo $trx_id ;
// echo '<br>'	 ;
// echo $sid	;
// echo '<br>'	 ;
// echo $status ;
// echo '<br>'	 ;
// echo $via ;

// die();


// if($via=="qris"){
// 	$status = "berhasil";	
// }

$api_key = file_get_contents("config/sandboxapikey.txt");
$CURLOPT_URL = "https://sandbox.ipaymu.com/api/transaksi?key=$api_key&id=$trx_id&format=json";

// if ($_SERVER['SERVER_NAME']=='localhost') {	
// } else {	
// 	$CURLOPT_URL ="https://my.ipaymu.com/api/transaksi?key=$api_key&id=$trx_id&format=json";
// }


$sql = "UPDATE tblpesanan_ipaymu SET status=:status WHERE trx_id=:trx_id";
$stmt = $db->prepare($sql);
$params = array(
    ":trx_id" => $trx_id,
	":status" => $status
);
$saved = $stmt->execute($params);
if($saved) { 
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  //CURLOPT_URL => "https://my.ipaymu.com/api/transaksi?key=$api_key&id=$trx_id&format=json",
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
		$obj = json_decode($response);
		// $waktu_bayar = $obj->WaktuBayar;
		$waktu		 = $obj->Waktu;
		
		$sql = "UPDATE tblpesanan_ipaymu SET waktu=:waktu WHERE trx_id=:trx_id";
		$stmt = $db->prepare($sql);
		$params = array(
			":trx_id" 	=> $trx_id,
			":waktu" 	=> $waktu
		);
		$saved = $stmt->execute($params);
	}
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta name="description" content="Integrasi PHP dengan API iPaymu.com">
    <meta name="author" content="#">
	<title>PHP iPaymu</title>
	<link rel="icon" href="#" sizes="32x32">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/mode.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="#" style="font-size:17px;">
	<div class="row" style="margin-left:0;margin-right:0;">
      
		<div class="col-md-12" style="padding-left:0;padding-right:0;">
			<form method="POST" class="bg-ipaymu">
			<p align="center" class="text-white" style="font-size:13px;">
				<img src="https://nyimasantam.my.id/image/logonyimas.png" height="128" />
				<hr />
				<h2 style="text-align:center;font-weight:bold;">Transaksi Selesai</h2>
				<a href="./" class="text-white btn btn-primary btn-block" style="font-weight:bold;">Tambah Order Lagi</a>
			</p>
			</form>		
		</div>
	</div>
<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
