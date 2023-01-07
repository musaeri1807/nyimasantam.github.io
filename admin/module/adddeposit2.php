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

// $Year    = date('Y-m-d', strtotime("-1 months"));
// $Yearold=substr($Year,0,4);

// if ($Yearold==date('Y')) {
//   echo "Lanjut";
// } else {
//   echo "Reset";
// }


// // $buah = array(date('Y'));
// // //count() untuk menghitung isi array.
// // for($x=0;$x<count($buah);$x++){
// // 	echo $buah[$x]."<br/>";
// // }
// print_r($Yearold);
// // ;
// // print_r($order['field_no_referensi']);
// die();



if (isset($_REQUEST['payment2'])) {

	$memberid                 = $_REQUEST['txt_memberid'];
	$field_no_referensi       = $noReff;
	$field_date_deposit       = date('Y-m-d');
	$time                     = date('H:i:s');
	$field_rekening_deposit   = $_POST['txt_rekening'];
	$field_sumber_dana        = $_POST['txt_select'];
	$field_branch             = $branchid;
	$field_officer_id         = $rows['field_user_id'];
	$field_sub_total          = $_POST['txt_subtotal'];
	$field_operation_fee      = $_POST['txt_free'];
	$field_operation_fee_rp   = $field_sub_total * $field_operation_fee / 100;
	// $field_operation_fee_rp   = $_POST['txt_free_rp'];
	$field_total_deposit      = $_POST['txt_total'];
	$field_deposit_gold       = $_POST['txt_gold'];
	$field_gold_price         = $_POST['txt_goldprice'];

	$transaksi_produk1        = 7;
	$transaksi_harga1         = $field_sub_total;
	$transaksi_jumlah1        = 1;
	$transaksi_total1         = $field_sub_total;

	// 	echo $transaksi_harga;

	// die();


	if (empty($memberid)) {
		$errorMsg             = "Member ID Belum Ada";
	} else if ($field_gold_price == 0) {
		$errorMsg             = "Harga Emas Belum Update";
	} else if ($field_deposit_gold == "Infinity") {
		$errorMsg             = "Harga Emas Belum Update";
	} else {
		try {
			$query2         = "SELECT field_status FROM tbltrxmutasisaldo WHERE field_rekening =:rekening ORDER BY field_id_saldo DESC LIMIT 1";
			$select2        = $db->prepare($query2);
			$select2->execute(array(':rekening' => $field_rekening_deposit));
			$result2        = $select2->fetch(PDO::FETCH_ASSOC);
			// echo "SELECT DATA SALDO";
			if ($result2['field_status'] !== "P") {
				# code...

				$query        = "SELECT * FROM tbltrxmutasisaldo WHERE field_rekening =:rekening  AND field_status='S' ORDER BY field_id_saldo DESC LIMIT 1";
				$select       = $db->prepare($query);
				$select->execute(array(':rekening' => $field_rekening_deposit));
				$result       = $select->fetch(PDO::FETCH_ASSOC);
				$saldoAwal    = $result['field_total_saldo'];
				$saldoAkhir   = $saldoAwal + $field_deposit_gold;
				$data         = $select->rowCount();

				// echo $data    = $select2->rowCount();
				if ($data = 1) { //memastikan rekening hanya satu yang ter insert
					# code...
					// echo $field_no_referensi;
					// die();
					$insert = $db->prepare('INSERT INTO tbldeposit (
                field_no_referensi,
                field_date_deposit,
                field_rekening_deposit,
                field_sumber_dana,
                field_branch,
                field_officer_id,
                field_sub_total,
                field_operation_fee,
                field_operation_fee_rp,
                field_total_deposit,
                field_deposit_gold,
                field_gold_price,
                field_status,
                field_approve) 
              VALUES(   
                :no_referensi,
                :date_deposit,
                :rekening_deposit,
                :sumber_dana,
                :branch,
                :officer_id,
                :sub_total,
                :operation_fee,
                :operation_fee_rp,
                :total_deposit,
                :deposit_gold,
                :gold_price,
                :ustatus,
                :approval)');

					$insert->execute(array(
						':no_referensi'       => $field_no_referensi,
						':date_deposit'       => $field_date_deposit,
						':rekening_deposit'   => $field_rekening_deposit,
						':sumber_dana'        => $field_sumber_dana,
						':branch'             => $field_branch,
						':officer_id'         => $field_officer_id,
						':sub_total'          => $field_sub_total,
						':operation_fee'      => $field_operation_fee,
						':operation_fee_rp'   => $field_operation_fee_rp,
						':total_deposit'      => $field_total_deposit,
						':deposit_gold'       => $field_deposit_gold,
						':gold_price'         => $field_gold_price,
						':ustatus'             => "S",
						':approval'           => $field_officer_id
					));
					$id = $db->lastinsertid();
					if ($id) {


						$t_produk   = $transaksi_produk1;
						$t_harga    = $transaksi_harga1;
						$t_jumlah   = $transaksi_jumlah1;
						$t_total    = $transaksi_total1;

						$insert = $db->prepare('INSERT INTO tbldepositdetail( 
                                                              field_trx_deposit,
                                                              field_product,
                                                              field_price_product,
                                                              field_quantity,
                                                              field_total_price) 
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
              field_kredit_saldo,
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
              :kredit_saldo,
              :total_saldo,
              :status)');
					$in->execute(array(
						':trx_id'             => $id,
						':memberid'           => $memberid,
						':no_referensi'       => $field_no_referensi,
						':rekening'           => $field_rekening_deposit,
						':tanggal_saldo'      => $field_date_deposit,
						':times'              => $time,
						':type_saldo'         => 100,
						':kredit_saldo'       => $field_deposit_gold,
						':total_saldo'        => $saldoAkhir,
						':status'              => "S"
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

$Stmt = $db->prepare("SELECT * FROM tblnasabah N JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id");
$Stmt->execute();
$Result = $Stmt->fetchAll();




if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {

	$Sql    = "SELECT P.*,B.field_branch_name FROM tblproduct P 
  LEFT JOIN tblbranch B
  ON P.field_branch=B.field_branch_id 
  WHERE field_status='A'
  ORDER BY P.field_product_id DESC ";

	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
} else {

	$Sql    = "SELECT P.*,B.field_branch_name FROM tblproduct P 
  LEFT JOIN tblbranch B
  ON P.field_branch=B.field_branch_id 
  WHERE field_status='A'
  AND P.field_branch=:idbranch
  ORDER BY P.field_product_id DESC ";


	$Stmt = $db->prepare($Sql);
	$Stmt->execute(array(":idbranch" => $branchid));
	$result = $Stmt->fetchAll();
}


$Stmt = $db->prepare("SELECT N.id_Nasabah,N.id_UserLogin,N.No_Rekening AS REKENING ,U.field_nama AS NAMA,U.field_member_id AS IDMEMEBER
FROM tblnasabah N JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id 
ORDER BY N.id_Nasabah DESC");
$Stmt->execute();
$DataNasabah = $Stmt->fetchAll();


//Harga Emas
$query        = "SELECT * FROM tblgoldprice ORDER BY field_gold_id  DESC LIMIT 1 ";
$Gold         = $db->prepare($query);
$Gold->execute(array(":datenow" => $date));
$ResultGold   = $Gold->fetch(PDO::FETCH_ASSOC);
$goldprice    = $ResultGold['field_sell'];
?>

<section class="content">
	<?php
	// massege
	if (isset($errorMsg)) {
		echo '<div class            = "alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
		//echo '<META HTTP-EQUIV="Refresh" Content="1">';
		if ($_SERVER['SERVER_NAME'] == 'localhost') {
			echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=purchase">';
		} else {
			echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=' . $domain . '/admin/dashboard?module=purchase">';
		}
	}
	if (isset($Msg)) {
		echo '<div class            = "alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
		//echo '<META HTTP-EQUIV="Refresh" Content="1">';
		if ($_SERVER['SERVER_NAME'] == 'localhost') {
			echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
		} else {
			echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=' . $domain . '/admin/dashboard?module=deposit">';
		}
	}
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<i class="fa fa-edit"></i>
					<h3 class="box-title">Pembelian</h3>

				</div>

				<div class="box-body">
					<form method="post" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-3 control-label">Nasabah</label>
							<div class="col-sm-3">
								<input type="text" name="txt_customer" required="required" id="add_customer" class="form-control" placeholder="Nasabah" readonly>
								<input type="text" name="txt_memberid" required="required" id="add_memberid" class="form-control" placeholder="Id Member" readonly>
							</div>
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#cariNasabah">
								<i class="fa fa-search"></i> &nbsp Cari Nasabah
							</button>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Rekening</label>
							<div class="col-sm-3">
								<input type="text" id="add_id" required="required" class="form-control" placeholder="IdNasabah" readonly>
								<input type="text" name="txt_rekening" required="required" id="add_account" class="form-control" placeholder="Rekening" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Harga Emas <br> Tanggal <?php echo date('d/m/Y'); ?></label>
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
								<input type="hidden" id="Hargagold" name="txt_goldprice" class="form-control" value="<?php echo $goldprice; ?>">
								<span class="goldprice" id="<?php echo $goldprice; ?>"><?php echo rupiah($goldprice); ?></span>
							</div>


						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Pilih Sumber Dana</label>
							<div class="col-sm-6">
								<select class="form-control" id="select_id" name="txt_select">
									<!-- <option value="">PILIH-</option> -->
									<!-- <option value="IN">IN</option> -->
									<option value="GAJI">GAJI</option>
								</select>

							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Pembelian</label>
							<div class="col-sm-6">
								<select class="form-control" id="Beli" name="txt_beli">
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

							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">QTY</label>
							<div class="col-sm-6">
								<input type="text" name="txt_Qty" id="Qty" class="form-control" value="1">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Oprasional free (5%)</label>
							<div class="col-sm-6">
								<input type="text" name="txt_free" id="Oprasionalfree1" class="form-control" placeholder="Oprasional free" value="5" readonly>%
								<input type="text" nama="txt_free_rp" id="Oprasionalfree2" class="form-control" placeholder="Oprasional free" readonly>
							</div>
						</div>
						<div class="form-group">
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
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Gold</label>
							<div class="col-sm-6">
								<input type="text" id="Gold" name="txt_gold" class="form-control" placeholder="gold" readonly>
							</div>
						</div>



						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 m-t-15">
								<input type="submit" name="payment2" class="btn btn-success " value="Simpan">
								<a href="?module=adminoffice" class="btn btn-danger">Batal</a>
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
														<td width="1%">
															<input type="number" id="member_<?php echo $Nasabah['id_Nasabah']; ?>" value="<?php echo $Nasabah['IDMEMEBER']; ?>">
															<input type="number" id="account_<?php echo $Nasabah['id_Nasabah']; ?>" value="<?php echo $Nasabah['REKENING']; ?>">
															<input type="text" id="customer_<?php echo $Nasabah['id_Nasabah']; ?>" value="<?php echo $Nasabah['NAMA']; ?>">

															<button type="button" class="btn btn-info modal-select-customer" id="<?php echo $Nasabah['id_Nasabah']; ?>" data-dismiss="modal">Pilih</button>

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