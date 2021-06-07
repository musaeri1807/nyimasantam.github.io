<?php 
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");
$sql = "SELECT order_id FROM tblpesanan_ipaymu ORDER BY order_id DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if($order['order_id']==""){
	$id = 1;
}else{	
	$id = $order['order_id']+1;
}

if(isset($_POST['tambahorder'])){
	$product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_STRING);
	$sql = "INSERT INTO tblpesanan_ipaymu (product) 
           VALUES (:product)";
    $stmt = $db->prepare($sql);
	$params = array(
        ":product" => $product
    );
	$saved = $stmt->execute($params);
	if($saved) header("Location: index.php");
}

$sql = "SELECT * FROM tblpesanan_ipaymu";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

function rupiah($angka){
	$hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
	return $hasil_rupiah; 
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Integrasi PHP dengan API iPaymu.com">
    <meta name="author" content="Didin Studio">
	<title>PHP iPaymu</title>
	<link rel="icon" href="https://www.didinstudio.com/img/favicon.png" sizes="32x32">
    
     
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css"/>
	-->
	<link href="css/responsive-table.css" rel="stylesheet" type="text/css">
	<style>
	a.nav-link{color:blue;}
	a.nav-link:hover{color:white;background:blue;}
	.tab-content{border:1px solid #ddd;border-top:none;padding-bottom:30px;padding-top:10px;}
	</style>
  </head>

  <body class="bg-dark">
 

	
	
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        
		<div class="col-md-8">
          <div class="card my-4">
			
			<h2 class="card-header">
				<a href="" style="color:blue;text-decoration:none;">PHP iPaymu</a>
			</h2>
			
            <div class="card-body">
				<ul id="kiri" class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link " data-toggle="tab" href="#saldo">Saldo</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#transaksi">Transaksi</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#order">Order</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#status">Status Pembayaran</a>
					</li>
				</ul>
				
				<div class="tab-content">
					<div id="saldo" class="container tab-pane ">
						<br />
						<input id="ceksaldo" type="submit" value="Cek Saldo" class="btn btn-primary btn-block">
					</div> 
					<div id="transaksi" class="container tab-pane">

						<label>ID Transaksi</label>
						<br>
						<!-- <select id="trx" type="text" name="trx" class="form-control">
							<option value="692681">692681</option>
							<option value="680390">680390</option>
							<option value="697000">697000</option>
							<option value="17500">17500</option>
						</select> -->
						<input type="text" name="trx" id="trx" class="form-control">
						<br>

						<input id="cektransaksi" type="submit" value="Cek Transaksi" class="btn btn-primary btn-block">
					</div>            
		  
					<div id="order" class="container tab-pane active">
						<!-- <label>ID Produk</label> -->
						<!-- <br> -->
						<input id="id_order" type="hidden" name="id_order" value="<?php echo $id;?>" class="form-control" readonly>
						<br>
						<label>Nama Operator</label>
						<br>
						<!-- <input id="product" type="text" name="product" value="Kopi Gayo" class="form-control"> -->
						<select id="product" name="product" class="form-control" type="text">

						<option value="INDOSAT">INDOSAT</option>
						<option value="TELKOMSEL">TELKOMSEL</option>
						<option value="XL">XL</option>
						<option value="AXIS">AXIS</option>
						</select>
						<!-- <br>
						<label>Jumlah</label>
						<br> -->
						<input id="quantity" type="hidden" name="quantity" value="1" min="1" class="form-control">
						<br>
						<label>No Handphone</label>
						<br>
						<input id="handphone" type="number" name="handphone"  value="08121003701" class="form-control" placeholder="08121003701">
						<br>
						<label>Satuan</label>
						<br>
						<!-- <input id="price" type="number" name="price" value="5000" min="500" class="form-control"> -->
						<select type="number" name="price" id="price" class="form-control">
						<option value="12000">10K</option>					
						<option value="22000">20K</option>
						<option value="52000">50K</option>
						<option value="102000">100K</option>
						</select>
						<br>
						<label>Type</label>
						<br>
						<!-- <input id="comments" type="text" name="comments" value="" class="form-control"> -->
						<select type="text" id="comments" class="form-control" name="comments">
						<option value="qrs">QRS</option>
						<option value="transfer">Transfer</option>
						</select>
						<br>
						<input id="tambahorder" type="submit" name="tambahorder" value="Tambah Order" class="btn btn-primary btn-block">	
					</div>
			
					<div id="status" class="container tab-pane">
		
						<div id="no-more-tables">
						<table id="statuspembayaran" class="table table-bordered table-responsive">
							<thead class="bg-primary text-white">
								<tr>
									<td>No.</td>
									<td>TRX ID</td>
									<td>Pesanan</td>
									<td>Harga</td>
									<!-- <td>Pemasukan</td> -->
									<td>Status</td>
									<td>Tindakan</td>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($result as $row) {
										$status = $row["status"];
										if($status=="tertunda"){
											$status = '<span class="badge badge-warning text-white">Menunggu Pembayaran</span>';
											$tindakan = '<a href="'.$row["url"].'" class="text-white btn btn-primary btn-block">Bayar</a><br /><a href="detail.php?trx_id='.$row["trx_id"].'" class="text-white btn btn-info btn-block">Detail</a>';
										}else if($status=="berhasil"){
											$status = '<span class="badge badge-success text-white">Pembayaran Berhasil</span>';
											$tindakan = '<a href="detail.php?trx_id='.$row["trx_id"].'" class="text-white btn btn-info btn-block">Detail</a>';
										}else if($status=="gagal"){
											$status = '<span class="badge badge-danger text-white">Pembayaran Gagal</span>';
											$tindakan = '<a href="detail.php?trx_id='.$row["trx_id"].'" class="text-white btn btn-info btn-block">Detail</a>';
										}
								?>
								<tr>	
									<td data-title="No."><?php echo $row["order_id"];?>.</td>
									<td data-title="TRX ID"><?php echo $row["trx_id"];?></td>
									<td data-title="Pesanan"><?php echo  $row["product"];?></td>
									<td data-title="Harga"><?php echo  rupiah($row["harga"]);?></td>
									<!-- <td data-title="Pemasukan"><?php echo rupiah($row["harga"]-$row["potongan"]);?></td> -->
									<td data-title="Status"><?php echo $status;?></td>
									<td data-title="Aksi" style="text-align:right"><?php echo $tindakan;?></td>
								</tr>	
								<?php } ?>		
							</tbody>
						</table>
						</div>
					</div>
		    
				</div>
			</div>
		   </div>
		</div>
        <div class="col-md-4">
		  <div class="card my-4">
			<div class="card-header bg-primary text-white"><h2 style="text-align:center">Hasil</h2></div>
            <div class="card-body">
				<div id="loading"><img src="img/ajax-loader.gif"></div>
				<div id="ket"></div>
		   </div>
          </div>
        </div>

      </div>
    </div>

   

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/seribu.js"></script>
	<!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>-->
	<script type="text/javascript">
	$(document).ready(function(){
		$("#loading").hide();
		$("#ket").hide();
		
		$("#kiri .nav-item").click(function(){
			$("#loading").show();
			$("#ket").hide();
			$("#loading").hide();
		});
		
		$('#ceksaldo').click(function(){
			$("#loading").show();
			$("#ket").empty();
			$.ajax({
            	type : 'POST',
           		url : 'php/cek_saldo.php',
            	success: function (data) {
					$("#ket").show();
					var obj=$.parseJSON(data);
					if(obj['Keterangan']=="OK"){
						$("#ket").html('Saldo Total: Rp. '+seribu(obj['Saldo']));
						$("#ket").append('<br />');
						$("#ket").append('Saldo Toko: Rp. '+seribu(obj['MerchantBalance']));
						$("#ket").append('<br />');
						$("#ket").append('Saldo Member: Rp. '+seribu(obj['MemberBalance']));
					}else{
						$("#ket").html('API Key SALAH!');
					}
					$("#loading").hide();
				}
          	});
		});
		
		$('#cektransaksi').click(function(){
			$("#loading").show();
			$("#ket").empty();
			var trx = $('#trx').val();
			$.ajax({
            	type : 'GET',
           		url : 'php/cek_transaksi.php',
				data :  {'trx' : trx},
            	success: function (data) {
					$("#ket").show();
					var obj=$.parseJSON(data);
					if(obj['Keterangan']!="Transaksi tidak ditemukan"){
						$("#ket").html('Status: '+obj['Status']+' ('+obj['Keterangan']+')');
						$("#ket").append('<br />');
						$("#ket").append('Nominal: Rp. '+seribu(obj['Nominal']));
						$("#ket").append('<br />');
						$("#ket").append('Biaya: Rp. '+seribu(obj['Biaya']));
						$("#ket").append('<br />');
						$("#ket").append('Waktu Order: '+obj['Waktu']);
						$("#ket").append('<br />');
						$("#ket").append('Waktu Bayar: '+obj['WaktuBayar']);
						$("#ket").append('<br />');
						$("#ket").append('Tipe: '+obj['Tipe']);
					}else{
						$("#ket").html('Transaksi tidak ditemukan!');
					}
					$("#loading").hide();
				}
          	});
		});
		
		$('#tambahorder').click(function(){
			$("#loading").show();
			$("#ket").empty();
			var id_order 	= $('#id_order').val();
			var product 	= $('#product').val();
			var quantity 	= $('#quantity').val();
			var handphone 	= $('#handphone').val();
			var price 		= $('#price').val();
			var comments 	= $('#comments').val();
			$.ajax({
            	type : 'POST',
           		url : 'php/bayar.php',
				data :  {'id_order' : id_order, 'product' : product, 'quantity' : quantity, 'handphone' :handphone, 'price' : price, 'comments' : comments},
            	success: function (data) {
					$("#ket").show();
					var obj=$.parseJSON(data);
					if(obj['Status']==-1002){
						$("#ket").html(obj['Keterangan']);
					}else{
						$("#ket").html('<a href="'+obj['url']+'" class="btn btn-primary btn-block">Lakukan Pembayaran</a>');
					}
					$("#loading").hide();
				}
          	});
		});
		
		
		
	});
	</script>
	</body>
</html>
