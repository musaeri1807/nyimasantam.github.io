<?php
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");

$trx_id = $_GET['trx_id'];
$sql = "SELECT * FROM tblpesanan_ipaymu WHERE trx_id=:trx_id";
$stmt = $db->prepare($sql);
$params = array(
    ":trx_id" => $trx_id
);
$saved = $stmt->execute($params);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$status = $order["status"];
if($status=="tertunda"){
	$status = '<span class="badge badge-warning text-white">Menunggu Pembayaran</span>';
}else if($status=="berhasil"){
	$status = '<span class="badge badge-success text-white">Pembayaran Berhasil</span>';
}else if($status=="gagal"){
	$status = '<span class="badge badge-danger text-white">Pembayaran Gagal</span>';
}

$waktu_bayar = $order["waktu_bayar"];
if($waktu_bayar==""){
	$waktu_bayar = '<span class="badge badge-warning text-white">Menunggu Pembayaran</span>';
}else{
	$waktu_bayar= '<span class="badge badge-info text-white">'.$waktu_bayar.'</span>';
}

function rupiah($angka){
	$hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
	return $hasil_rupiah; 
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
</head>
<body class="bg-dark">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card my-4">
					<h2 class="card-header" style="text-align:center;">Detail Transaksi <?php echo $trx_id;?></h2>
					<div class="card-body">
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td>Pesanan</td>
									<td><?php echo $order['product'];?></td>
								</tr>
								<tr>
									<td>Jumlah</td>
									<td><?php echo $order['quantity'];?></td>
								</tr>
								<tr>
									<td>Catatan Pesanan</td>
									<td><?php echo $order['comments'];?></td>
								</tr>
								<tr>
									<td>Harga Satuan</td>
									<td><?php echo rupiah($order['price']);?></td>
								</tr>
								<tr>
									<td>Harga Total</td>
									<td><?php echo rupiah($order['harga']);?></td>
								</tr>
								<tr>
									<td>Biaya Admin</td>
									<td><?php echo rupiah($order['potongan']);?></td>
								</tr>
								<tr>
									<td>Pemasukan</td>
									<td><?php echo rupiah($order['harga']-$order['potongan']);?></td>
								</tr>
								<tr>
									<td>Status Pembayaran</td>
									<td><?php echo $status;?></td>
								</tr>
								<tr>
									<td>Metode Pembayaran</td>
									<td><font style="text-transform:uppercase;"><?php echo $order['channel'];?></font></td>
								</tr>
								<tr>
									<td>Waktu Pembayaran</td>
									<td><?php echo $waktu_bayar;?></td>
								</tr>
							</tbody>
						</table>
						<a href="./" class="btn btn-primary btn-block">Kembali</a>
					</div>
				</div>
			</div>		
		</div>
	</div>
<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
