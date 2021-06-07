<?php
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');


if(isset($_POST['simpan'])){
	$apikey = filter_input(INPUT_POST, 'apikey', FILTER_SANITIZE_STRING);
	$domain = filter_input(INPUT_POST, 'domain', FILTER_SANITIZE_STRING);
	file_put_contents("config/apikey.txt",$apikey);
	file_put_contents("config/domain.txt",$domain);
	header("Location: index.php");
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
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
</head>
<body class="bg-dark" style="font-size:17px;">
	<div class="row" style="margin-left:0;margin-right:0;">
      
		<div class="col-md-12" style="padding-left:0;padding-right:0;">
			<form method="POST" class="bg-ipaymu">							 
				<div class="form-group">
					
					<p align="center" class="text-white">
						<img src="https://ipaymu.com/wp-content/themes/ipaymu_v2/assets/img/logo/ipaymu_logo_blue_240x60.png" />
						<img src="https://ipaymu.com/wp-content/themes/ipaymu_v2/assets/img/logo/partner-ipaymu.png" height="128" />
					</p>
					<label>API Key iPaymu</label>
					<input id="apikey" type="text" class="form-control" name="apikey" placeholder="API Key iPaymu" required autofocus>
				</div>
				<div class="form-group">
					<label>Domain</label>
					<input id="domain" type="text" class="form-control" name="domain" placeholder="http://" required autofocus>
				</div>
				<div class="form-group no-margin">
					<input type="submit" class="btn btn-dark btn-block" name="simpan" value="Simpan" />
				</div>
				<p align="center" class="text-white" style="font-size:13px;">
					Belum punya API Key? <a href="https://my.ipaymu.com/register/ref/admin1234567" target="_blank" class="text-white" style="font-weight:bold;">Daftar Dulu!</a>
				</p>
			</form>		
		</div>
	</div>
<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
