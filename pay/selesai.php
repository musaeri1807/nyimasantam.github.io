<?php
// ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");
$api_key = file_get_contents("config/apikey.txt");

$id_order = $_GET['id_order'];
$trx_id = $_GET['trx_id'];
$channel = $_GET['channel'];

$sql = "SELECT * FROM tblpesanan_ipaymu WHERE order_id=:id";
$stmt = $db->prepare($sql);
$params = array(
	":id" => $id_order
);
$stmt->execute($params);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
$bayar = $order['url'];

if(isset($channel)){
$sql = "UPDATE tblpesanan_ipaymu 
	SET trx_id=:trx_id,channel=:channel
	WHERE order_id=:id";
$stmt = $db->prepare($sql);
$params = array(
	":id" => $id_order,
    ":trx_id" => $trx_id,
	":channel" => $channel
);
$saved = $stmt->execute($params);
if($saved) { 
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://my.ipaymu.com/api/transaksi?key=$api_key&id=$trx_id&format=json",
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
		$potongan = $obj->Biaya;
		$sql = "UPDATE tblpesanan_ipaymu 
				SET potongan=:potongan
				WHERE trx_id=:trx_id";
		$stmt = $db->prepare($sql);
		$params = array(
			":trx_id" => $trx_id,
			":potongan" => $potongan
		);
		$saved = $stmt->execute($params);
	}
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta name="description" content="Integrasi PHP dengan API iPaymu.com">
    <meta name="author" content="Didin Studio">
	<title>PHP iPaymu</title>
	<link rel="icon" href="https://www.didinstudio.com/img/favicon.png" sizes="32x32">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/mode.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-dark" style="font-size:17px;">
	<div class="row" style="margin-left:0;margin-right:0;">
      
		<div class="col-md-12" style="padding-left:0;padding-right:0;">
			<form method="POST" class="bg-ipaymu">
			<p align="center" class="text-white" style="font-size:13px;">
				<img src="https://www.didinstudio.com/img/logo.png" height="128" />
			</p>
				<hr />
				<h2 style="text-align:center;font-weight:bold;">Transaksi Selesai</h2>
				<a href="<?php echo $bayar;?>" class="text-white btn btn-success btn-block" style="font-weight:bold;">Bayar Sekarang</a>
				<a href="./" class="text-white btn btn-primary btn-block" style="font-weight:bold;">Tambah Order Lagi</a>
			
			</form>		
		</div>
	</div>
<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
