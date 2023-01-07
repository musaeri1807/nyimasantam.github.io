<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
	header("location: ../index.php");
}

if (isset($_REQUEST['btn_insert2'])) {
	$datamodal1 = $_REQUEST['txt_addrole1'];
	$datamodal2 = $_REQUEST['txt_addrole2'];

	if (empty($datamodal1)) {
		$errorMsg = "Silakan Masukkan ID Jabatan ";
	} elseif (empty($datamodal2)) {
		$errorMsg = "Silakan Masukkan Nama Jabatan";
	} else {
		try {
			if (!isset($errorMsg)) {
				$insert_stmt = $db->prepare('INSERT INTO tbldepartment (field_department_id,field_department_name) 
														VALUES(:roleid,:rolename)'); //sql insert query					
				$insert_stmt->bindParam(':roleid', $datamodal1);
				$insert_stmt->bindParam(':rolename', $datamodal2);


				if ($insert_stmt->execute()) {
					$insertMsg = "Insert Successfully"; //execute query success message
					//echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard.php?module=addadminoffice">';
					echo '<META HTTP-EQUIV="Refresh" Content="1";>';
				}
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}


// mencari kode barang dengan nilai paling besar

$sql	= "SELECT field_employees_id FROM tblemployeeslogin ORDER BY field_employees_id DESC LIMIT 1 ";
$stmt 	= $db->prepare($sql);
$stmt->execute();
$row 	= $stmt->fetch(PDO::FETCH_ASSOC);

//$id = $row['field_employees_id'];
if ($row["field_employees_id"] == "") {
	$no = 1;
	$year = substr(date("Y"), -2);
	$mont = date("m");
	$nip = $year . $mont;
	$idemployees = $nip . sprintf("%04s", $no);
} else {
	$id = $row['field_employees_id'];
	$seri = substr($id, 4);
	$no = $seri + 1;
	$year = substr(date("Y"), -2);
	$mont = date("m");
	$nip = $year . $mont;
	$idemployees = $nip . sprintf("%04s", $no);
}


if ($_SESSION['rolelogin'] == 'ADM') {
	// $Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='SPA'";
	$Sql = "SELECT * FROM tbldepartment ";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$resultdept = $Stmt->fetchAll();
	# code...
	$Sql = "SELECT * FROM tblbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'MGR') {
	# code...
	$Sql = "SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR'";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$resultdept = $Stmt->fetchAll();

	$Sql = "SELECT * FROM tblbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'SPV') {
	# code...
	$Sql = "SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR' AND field_department_id !='SPV'";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$resultdept = $Stmt->fetchAll();

	$Sql = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
	$Stmt = $db->prepare($Sql);
	//$Stmt->execute();
	$Stmt->execute(array(":idbranch" => $branchid));
	$result = $Stmt->fetchAll();
}
//datanasabah
$Stmt = $db->prepare("SELECT * FROM tblnasabah N JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id");
$Stmt->execute();
$Result = $Stmt->fetchAll();
//extract($row);                

if (isset($_REQUEST['btn_insert'])) {

	$idemployee	= $_REQUEST['txt_idemployee'];	//textbox name "txt_firstname"
	$firstname	= $_REQUEST['txt_firstname'];	//textbox name "txt_lastname"
	$lastname	= $_REQUEST['txt_lastname'];
	$email  	= $_REQUEST['txt_email'];
	$date		= date('Y-m-d');
	$idrole		= $_REQUEST['txt_role'];
	$idcabang	= $_REQUEST['txt_cabang'];
	$name 		= $firstname . " " . $lastname;
	$username 	= $firstname . (rand(10, 100));
	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s')));
	$password 	= password_generate(8);


	if (empty($firstname)) {
		$errorMsg = "Silakan Masukkan Nama Depan";
	} else if (empty($lastname)) {
		$errorMsg = "Silakan Masukkan Nama Belakang";
	} elseif (empty($email)) {
		$errorMsg = "Silakan Masukkan Email";
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errorMsg = "Silakan Memasukan Alamat Email yang Valid";
	} elseif ($idrole == "Pilih") {
		$errorMsg = "Silakan Pilih Posisi";
	} elseif ($idcabang == "Pilih") {
		$errorMsg = "Silakan Pilih Kantor Cabang";
	} else {
		try {

			$select_stmt = $db->prepare("SELECT field_email,field_username  FROM tblemployeeslogin WHERE field_email=:uemail OR field_username=:username"); // sql select query			
			$select_stmt->execute(array(':uemail' => $email, ':username' => $username)); //execute query 
			$row = $select_stmt->fetch(PDO::FETCH_ASSOC);

			if ($row["field_email"] == $email) {
				$errorMsg = "Maaf Email Sudah Ada";	//check condition email already exists 
			} else if ($row["field_username"] == $username) {
				$errorMsg = "Maaf User Sudah Ada silakan Coba Lagi";	//check condition email already exists 
			} elseif (!isset($errorMsg)) {
				$new_password = password_hash($password, PASSWORD_DEFAULT);
				$sql_stmt	= "INSERT INTO tblemployeeslogin 
							(	field_employees_id,
								field_name_officer,
								field_username,
								field_role,
								field_date_reg,
								field_branch,
								field_email,
								Password,
								field_password,
								field_token
							)VALUES
							(	:idemployee,
								:namaemployee,
								:username,
								:urole,
								:udate,
								:cabang,
								:umail,
								:upassword,
								:passwordnew,
								:token
							)";
				$insert_stmt = $db->prepare($sql_stmt);
				$insert_stmt->bindParam(':idemployee', $idemployee);
				$insert_stmt->bindParam(':namaemployee', $name);
				$insert_stmt->bindParam(':username', $username);
				$insert_stmt->bindParam(':urole', $idrole);
				$insert_stmt->bindParam(':udate', $date);
				$insert_stmt->bindParam(':cabang', $idcabang);
				$insert_stmt->bindParam(':umail', $email);
				$insert_stmt->bindParam(':upassword', $password);
				$insert_stmt->bindParam(':passwordnew', $new_password);
				$insert_stmt->bindParam(':token', $tokenn);


				if ($insert_stmt->execute()) {
					$select_stmt = $db->prepare("SELECT * FROM ((tblemployeeslogin employee JOIN  tbldepartment dept ON employee.field_role=dept.field_department_id) JOIN tblbranch  ON employee.field_branch=tblbranch.field_branch_id) WHERE field_employees_id =:id ");

					$select_stmt->bindParam(':id', $idemployee);
					$select_stmt->execute();
					$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
					$role = $row["field_department_name"];
					$cabang = $row["field_branch_name"];


					include "../mail/mail_regoffice.php";
					if (!$mail->send()) {
						$insertMsg = "Register Successfully ..... Pesan idak dapat dikirim." . $mail->ErrorInfo;
					} else {
						$insertMsg = "Register Successfully, Please Check Your Inbox Email " . $email;
					}

					//$insertMsg="Insert Successfully"; //execute query success message

					echo '<META HTTP-EQUIV="Refresh" Content="3;">';
				}
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}

$Stmt = $db->prepare("SELECT DISTINCT(field_rekening),(SELECT S1.field_total_saldo FROM tbltrxmutasisaldo S1 WHERE S1.field_rekening = S2.field_rekening AND S1.field_status='S' ORDER BY S1.field_id_saldo DESC LIMIT 1)  
AS SALDO,U.field_nama AS NAMA,U.field_member_id AS MEMBERID,B.field_branch_name AS BRANCH, U.field_user_id AS ID 
            FROM tbltrxmutasisaldo S2 
            JOIN tbluserlogin U ON S2.field_member_id = U.field_member_id
            JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
            JOIN tblbranch B ON U.field_branch=B.field_branch_id
            ORDER BY S2.field_id_saldo DESC");
$Stmt->execute();
$DataNasabah = $Stmt->fetchAll();

$query        = "SELECT * FROM tblgoldprice   ORDER BY field_gold_id  DESC LIMIT 1 ";
$Gold         = $db->prepare($query);
$Gold->execute(array(":datenow" => $date));
$ResultGold   = $Gold->fetch(PDO::FETCH_ASSOC);
$goldprice    = $ResultGold['field_sell'];


?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<i class="fa fa-edit"></i>
					<h3 class="box-title">Buyback</h3>

				</div>
				<!-- Content -->
				<?php
				if (isset($errorMsg)) {
					echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
				}
				if (isset($insertMsg)) {
					echo '<div class="alert alert-success"><strong>SUCCESS !' . $insertMsg . '</strong></div>';
				}
				?>

				<div class="box-body">
					<form method="post" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-3 control-label">Nasabah</label>
							<div class="col-sm-3">
								<input type="text" name="txt_customer" required="required" id="add_customer" class="form-control" placeholder="Nasabah" readonly>
							</div>

							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#cariNasabah">
								<i class="fa fa-search"></i> &nbsp Cari Nasabah
							</button>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Rekening</label>
							<div class="col-sm-3">
								<input type="hidden" id="add_id" required="required" class="form-control" placeholder="IdCustomer" readonly>
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
								<input type="hidden" id="Hargagold" name="txt_gold" class="form-control" value="<?php echo $goldprice; ?>">
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
								<input type="text" id="Buyback-Gold" class="form-control" readonly>
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
								<input type="submit" name="btn_insert" class="btn btn-success " value="Simpan">
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
														<td width="20%"><?php echo $Nasabah['field_rekening']; ?></td>
														<td width="20%"><?php echo $Nasabah['NAMA']; ?> </td>
														<td width="20%"><?php echo $Nasabah['SALDO']; ?> </td>
														<td width="1%">
															<input type="number" id="member_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['MEMBERID']; ?>">
															<input type="number" id="account_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['field_rekening']; ?>">
															<input type="text" id="customer_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['NAMA']; ?>">
															<input type="text" id="saldo_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['SALDO']; ?>">
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