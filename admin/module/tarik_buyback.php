<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
	header("location: ../loginv2.php");
}

//noReff
$sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order['field_no_referensi'] == "") {
	$no = 1;
	$thn = date('Y');
	$thn = substr($thn, -2);
	$reff = "Reff";
	$char = $thn . $reff;
	$noReff = $char . sprintf("%09s", $no);
} else {
	$noreff = $order['field_no_referensi'];
	$noUrut = substr($noreff, 6);
	$no = $noUrut + 1;
	$thn = date('Y');
	$thn = substr($thn, -2);
	$reff = "Reff";
	$char = $thn . $reff;
	$noReff = $char . sprintf("%09s", $no);
}

$query        = "SELECT * FROM tblgoldprice ORDER BY field_gold_id  DESC LIMIT 1 ";
$Gold         = $db->prepare($query);
$Gold->execute(array(":datenow" => $date));
$ResultGold   = $Gold->fetch(PDO::FETCH_ASSOC);
$goldprice    = $ResultGold['field_buyback'];



if (isset($_REQUEST['btn_buyback'])) {

	$memberid                 = $_REQUEST['txt_memberid'];
	$field_no_referensi       = $noReff;
	$field_date_withdraw      = date('Y-m-d');
	$time                     = date('H:i:s');
	$field_rekening_withdraw  = $_POST['txt_rekening'];
	$field_type_withdraw      = 202;
	$field_branch             = $branchid;
	$field_officer_id         = $rows['field_user_id'];
	$field_gold_price         = $_POST['txt_pricegold'];
	$saldo                    = $_POST['txt_saldo'];
	$field_withdraw_gold      = $_POST['txt_buybackgold'];
	$field_rp_withdraw        = $_POST['txt_buyback'];

	$transaksi_produk1        = 0;
	$transaksi_harga1         = $field_withdraw_gold;
	$transaksi_jumlah1        = 1;
	$transaksi_total1         = $field_withdraw_gold;

	// 	echo $transaksi_harga;




	if (empty($memberid)) {
		$errorMsg             = "Member ID Belum Ada";
	} else if (empty($field_gold_price)) {
		$errorMsg             = "Harga Emas Belum Update";
	} else if ($field_gold_price == 0) {
		$errorMsg             = "Harga Emas Belum Update";
	} else if ($saldo < $field_withdraw_gold) {
		$errorMsg             = "Saldo Anda Kurang";
	} else {
		try {
			$query2         = "SELECT field_status FROM tbltrxmutasisaldo WHERE field_rekening =:rekening ORDER BY field_id_saldo DESC LIMIT 1";
			$select2        = $db->prepare($query2);
			$select2->execute(array(':rekening' => $field_rekening_withdraw));
			$result2        = $select2->fetch(PDO::FETCH_ASSOC);
			// echo "SELECT DATA SALDO";
			if ($result2['field_status'] !== "P") {
				$query        = "SELECT * FROM tbltrxmutasisaldo WHERE field_rekening =:rekening  AND field_status='S' ORDER BY field_id_saldo DESC LIMIT 1";
				$select       = $db->prepare($query);
				$select->execute(array(':rekening' => $field_rekening_withdraw));
				$result       = $select->fetch(PDO::FETCH_ASSOC);
				$saldoAwal    = $result['field_total_saldo'];
				$saldoAkhir   = $saldoAwal - $field_withdraw_gold;
				$data         = $select->rowCount();

				// echo $data    = $select2->rowCount();
				if ($data = 1) { //memastikan rekening hanya satu yang ter insert
					# code...
					$insert = $db->prepare('INSERT INTO tblwithdraw (
					field_no_referensi,
					field_date_withdraw,
					field_rekening_withdraw,
					field_type_withdraw,
					field_branch,
					field_officer_id,
					field_gold_price,
					
					field_withdraw_gold,
					field_rp_withdraw,
				
					field_status,
					field_approve) 
				  VALUES(   
					:no_referensi,
					:date_withdraw,
					:rekening_withdraw,
					:type_withdraw,
					:branch,
					:officer_id,
					:gold_price,
	
					:withdraw_gold,
					:rp_withdraw,
	
					:ustatus,
					:approval)');

					$insert->execute(array(
						':no_referensi'        => $field_no_referensi,
						':date_withdraw'       => $field_date_withdraw,
						':rekening_withdraw'   => $field_rekening_withdraw,
						':type_withdraw'       => $field_type_withdraw,
						':branch'              => $field_branch,
						':officer_id'          => $field_officer_id,
						':gold_price'          => $field_gold_price,
						':withdraw_gold'       => $field_withdraw_gold,
						':rp_withdraw'         => $field_rp_withdraw,
						':ustatus'             => "S",
						':approval'            => $field_officer_id
					));

					$id = $db->lastinsertid();
					if ($id) {


						$t_produk   = $transaksi_produk1;
						$t_harga    = $transaksi_harga1;
						$t_jumlah   = $transaksi_jumlah1;
						$t_total    = $transaksi_total1;

						$insert = $db->prepare('INSERT INTO tblwithdrawdetail( 
																  field_trx_withdraw,
																  field_product,
																  field_berat,
																  field_quantity,
																  field_total_berat) 
														  VALUES( :trx_deposit,
																  :product,
																  :price_product,
																  :quantity,
																  :total_price)');

						$insert->execute(array(
							':trx_deposit'        => $id,
							':product'            => $t_produk,
							':price_product'      => $t_harga,
							':quantity'           => $t_jumlah,
							':total_price'        => $t_total
						));
					} else {
						$errorMsg = "id Deposit Transaksi tidak ditemukan";
					} //tututp             

					$in = $db->prepare('INSERT INTO tbltrxmutasisaldo 
								  (
				  field_trx_id,
				  field_member_id,
				  field_no_referensi,
				  field_rekening,
				  field_tanggal_saldo,
				  field_time,
				  field_type_saldo,
				  field_debit_saldo,
				  field_total_saldo,
				  field_status) 
							VALUES 
				  (
				  :trx_id,   
				  :memberid,  
				  :no_referensi,
				  :rekening,
				  :tanggal_saldo,    
				  :times,
				  :type_saldo,
				  :debit_saldo,
				  :total_saldo,
				  :status)');
					$in->execute(array(
						':trx_id'             => $id,
						':memberid'           => $memberid,
						':no_referensi'       => $field_no_referensi,
						':rekening'           => $field_rekening_withdraw,
						':tanggal_saldo'      => $field_date_withdraw,
						':times'              => $time,
						':type_saldo'         => 200,
						':debit_saldo'        => $field_withdraw_gold,
						':total_saldo'        => $saldoAkhir,
						':status'             => "S"
					));
					$Msg      = " Transaction Saldo Successfully"; //execute query success message
				} else {
					$errorMsg = "Rekening lebih dari Satu";
				}
			} else {
				$errorMsg     = "Transaksi Sebelumnya Masih Pending";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}





if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
	$QUERY = "SELECT DISTINCT(field_rekening),(SELECT S1.field_total_saldo FROM tbltrxmutasisaldo S1 WHERE S1.field_rekening = S2.field_rekening AND S1.field_status='S' ORDER BY S1.field_id_saldo DESC LIMIT 1)  
	AS SALDO,
	U.field_nama AS NAMA,
	U.field_member_id AS MEMBER,
	N.No_Rekening AS REKENING,
	B.field_branch_name AS CABANG,
	U.field_user_id AS ID 
				FROM tbltrxmutasisaldo S2 
				JOIN tbluserlogin U ON S2.field_member_id = U.field_member_id
				JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
				JOIN tblbranch B ON U.field_branch=B.field_branch_id
				WHERE N.Konfirmasi='Y' AND U.field_status_aktif='1'
				ORDER BY S2.field_id_saldo DESC";
	$Stmt = $db->prepare($QUERY);
	$Stmt->execute();
	$DataNasabah = $Stmt->fetchAll();
} else {

	$QUERY = "SELECT DISTINCT(field_rekening),(SELECT S1.field_total_saldo FROM tbltrxmutasisaldo S1 WHERE S1.field_rekening = S2.field_rekening AND S1.field_status='S' ORDER BY S1.field_id_saldo DESC LIMIT 1)  
	AS SALDO,
	U.field_nama AS NAMA,
	U.field_member_id AS MEMBER,
	N.No_Rekening AS REKENING,
	B.field_branch_name AS CABANG,
	U.field_user_id AS ID 
				FROM tbltrxmutasisaldo S2 
				JOIN tbluserlogin U ON S2.field_member_id = U.field_member_id
				JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
				JOIN tblbranch B ON U.field_branch=B.field_branch_id
				WHERE N.Konfirmasi='Y' AND U.field_status_aktif='1' AND U.field_branch=:idbranch
				ORDER BY S2.field_id_saldo DESC";
	$Stmt = $db->prepare($QUERY);
	$Stmt->execute(array(":idbranch" => $branchid));
	$DataNasabah = $Stmt->fetchAll();
}





//Harga Emas
$query        = "SELECT * FROM tblgoldprice ORDER BY field_gold_id  DESC LIMIT 1 ";
$Gold         = $db->prepare($query);
$Gold->execute(array(":datenow" => $date));
$ResultGold   = $Gold->fetch(PDO::FETCH_ASSOC);
$goldprice    = $ResultGold['field_buyback'];
?>
<section class="content">
	<!-- Content -->
	<?php
	// massege
	if (isset($errorMsg)) {
		echo '<div class            = "alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
		//echo '<META HTTP-EQUIV="Refresh" Content="1">';
		if ($_SERVER['SERVER_NAME'] == 'localhost') {
			echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=buyback">';
		} else {
			echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=' . $domain . '/admin/dashboard?module=buyback">';
		}
	}
	if (isset($Msg)) {
		echo '<div class            = "alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
		//echo '<META HTTP-EQUIV="Refresh" Content="1">';
		if ($_SERVER['SERVER_NAME'] == 'localhost') {
			echo '<META HTTP-EQUIV    = "Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=withdraws">';
		} else {
			echo '<META HTTP-EQUIV    = "Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=withdraws">';
		}
	}
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<i class="fa fa-edit"></i>
					<h3 class="box-title">Buyback</h3>

				</div>
				<!-- Content -->
				<div class="box-body">
					<form method="post" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-3 control-label">Nasabah</label>
							<div class="col-sm-3">
								<input type="text" name="txt_customer" required="required" id="add_customer" class="form-control" placeholder="Nasabah" readonly>
								<input type="text" name="txt_memberid" required="required" id="add_memberid" class="form-control" placeholder="member_id" readonly>
							</div>

							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#cariNasabah">
								<i class="fa fa-search"></i> &nbsp Cari Nasabah
							</button>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Rekening</label>
							<div class="col-sm-3">
								<input type="text" id="add_id" required="required" class="form-control" placeholder="IdCustomer" readonly>
								<input type="text" name="txt_rekening" required="required" id="add_account" class="form-control" placeholder="Rekening" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Saldo</label>
							<div class="col-sm-3">
								<input type="text" name="txt_saldo" id="add_saldo" class="form-control" placeholder="Saldo" readonly>
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label">Harga Buyback <br> Tanggal <?php echo date('d/m/Y'); ?></label>
							<div class="col-sm-3">


								<?php

								if ($ResultGold['field_date_gold'] == $date) {
									# code...
									if ($ResultGold['field_status'] == "P") {
										# code...
										// echo "PENDING ";
										echo '<div class= "alert alert-warning"><strong>Harga Sudah Update Tapi Masih Menunggu Approved</strong></div>';
										$goldprice = 0;
									} else {
										# code...
										echo '<div class= "alert alert-success"><strong> Harga Sudah Update</strong></div>';
										$goldprice;
									}
								} else {
									# code...
									$goldprice = 0;
									echo '<div class= "alert alert-danger"><strong>Harga Hari ini Belum Update</strong></div>';
								}
								?>
								<input type="hidden" id="Hargagold" name="txt_pricegold" class="form-control" value="<?php echo $goldprice; ?>">
								<span class="goldprice" id="<?php echo $goldprice; ?>"><?php echo rupiah($goldprice); ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Buyback</label>
							<div class="col-sm-6">
								<select class="form-control" id="Buyback-Rupiah" name="txt_buyback">
									<option id="beli" value="0">Rp 0,-</option>
									<option id="beli" value="10000">Rp 10.000,-</option>
									<option id="beli" value="20000">Rp 20.000,-</option>
									<option id="beli" value="30000">Rp 30.000,-</option>
									<option id="beli" value="40000">Rp 40.000,-</option>
									<option id="beli" value="50000">Rp 50.000,-</option>
									<option id="beli" value="100000">Rp 100.000,-</option>
									<option id="beli" value="150000">Rp 150.000,-</option>
									<option id="beli" value="200000">Rp 200.000,-</option>
									<option id="beli" value="500000">Rp 500.000,-</option>
									<option id="beli" value="1000000">Rp 1.000.000,-</option>
								</select>
								<input type="text" name="txt_buybackgold" id="Buyback-Gold" class="form-control" readonly>
							</div>
						</div>

						<!-- <div class="form-group">
							<label class="col-sm-3 control-label">Subtotal</label>
							<div class="col-sm-6">
								<input type="text" id="Subtotal" name="txt_subtotal" class="form-control" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Total</label>
							<div class="col-sm-6">
								<input type="text" id="Total" name="txt_total" class="form-control" readonly>

							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-3 control-label">Saldo Akhir</label>
							<div class="col-sm-6">
								<input type="text" id="Saldo-A" name="SaldoA" class="form-control" placeholder="Saldo Akhir" readonly>
							</div>
						</div>



						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 m-t-15">
								<input type="submit" name="btn_buyback" class="btn btn-success " value="Simpan">
								<a href="?module=withdraws" class="btn btn-danger">Batal</a>
							</div>
						</div>

					</form>
					<!-- Modal Customer-->
					<div class="modal fade" id="cariNasabah" tabindex="-1" role="dialog" aria-labelledby="cariCustomerLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<center>
										<h5>
											<i class="fa fa-users"></i>
											Pilih Nasabah
										</h5>
									</center>
								</div>
								<div class="modal-body">


									<div class="table-responsive">
										<!-- <table class="table table-bordered table-striped table-hover" id="table-datatable-produk"> -->
										<table class="table table-bordered table-striped table-hover" id="trxSemua2">
											<thead>
												<tr>
													<th class="text-center">No</th>
													<th class="text-center">Rekening</th>
													<th class="text-center">Nasabah</th>
													<th class="text-center">Cabang</th>
													<th class="text-center">Saldo</th>

													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;
												foreach ($DataNasabah as $Nasabah) {
												?>
													<tr>
														<td width="1%" class="text-center"><?php echo $no++; ?></td>
														<td width="20%"><?php echo $Nasabah['REKENING']; ?></td>
														<td width="20%"><?php echo $Nasabah['NAMA']; ?> </td>
														<td width="20%"><?php echo $Nasabah['CABANG']; ?> </td>
														<td width="20%"><?php echo $Nasabah['SALDO']; ?> </td>
														<td width="1%">
															<input type="hidden" id="member_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['MEMBER']; ?>">
															<input type="hidden" id="account_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['REKENING']; ?>">
															<input type="hidden" id="customer_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['NAMA']; ?>">
															<input type="hidden" id="saldo_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['SALDO']; ?>">
															<button type="button" class="btn btn-info modal-select-customer" id="<?php echo $Nasabah['ID']; ?>" data-dismiss="modal">Pilih</button>

														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>

								</div>
							</div>
						</div>
					</div>
					<!-- modal Customer -->
					<!-- form -->
				</div>
				<!-- contain -->
			</div>
		</div>
	</div>
</section>