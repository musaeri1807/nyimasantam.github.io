<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");



// $tokenn		= hash('sha256', md5(date('Y-m-d h:i:s')));
// echo $tokenn;
// die();

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



	// echo $cabang;
	// echo "<br>";
	// echo $kategori;
	// echo "<br>";
	// echo $beratsampah;
	// die();

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
$Stmt = $db->prepare("SELECT * FROM tblcustomer");
$Stmt->execute();
$Result = $Stmt->fetchAll();

foreach( $Result As $rows){

	echo $rows['field_customer_id'];
	echo '<br>';
}
die();
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<i class="fa fa-edit"></i>
					<h3 class="box-title">Withdraw Customer</h3>

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
							<label class="col-sm-3 control-label">NIP</label>
							<div class="col-sm-3">
								<input type="text" name="txt_idemployee" class="form-control" value="<?php echo $idemployees; ?>" readonly />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Customer</label>
							<div class="col-sm-2">
							<input type="text" name="txt_idemployee" class="form-control" value="<?php echo $idemployees; ?>" readonly />
							</div>
						
								<a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Search</a>
							
							<!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default"> -->
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="row">
								<div class="col-sm-2">
									<input type="text" name="txt_firstname" class="form-control" placeholder="Masukkan Depan" />
								</div>
								<div class="col-sm-3">
									<input type="text" name="txt_lastname" class="form-control" placeholder="Masukkan Belakang" />
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-6">
								<input type="text" name="txt_email" class="form-control" placeholder="Masukkan Email" />
							</div>
						</div>



						<div class="form-group">
							<label class="col-sm-3 control-label">Branch Office</label>
							<div class="col-sm-6">
								<select class="form-control" type="text" name="txt_cabang">
									<option>Pilih</option>
									<?php foreach ($result as $branch) { ?>
										<option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_name'] . "-";
																									echo $branch['field_branch_id']; ?></option>

									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Pilihan Buyback</label>
							<div class="col-sm-6">
								<select class="form-control" id="select-type" name="txt_select_type">
									<option value="3000">Pilih</option>
									<option value="1000">Request Cetak Fisik</option>
									<option value="2000">Request Buyback</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">#</label>
							<div class="row">
								<div class="col-xs-2">
									<select class="form-control" id="cf1" name="txt_Gram" style="display: none;">
										<option id="gram" value="0">0g</option>
										<option id="gram" value="1">Kepingan 1g</option>
										<option id="gram" value="2">Kepingan 2g</option>
										<option id="gram" value="3">Kepingan 3g</option>
										<option id="gram" value="5">Kepingan 5g</option>
										<option id="gram" value="10">Kepingan 10g</option>
										<option id="gram" value="25">Kepingan 25g</option>
									</select>
									<!-- <input class="form-control" type="number" name="txt_Rupiah" placeholder="Buyback Rupiah" id="rp1" value="0" style="display:none;"> -->
									<select class="form-control" id="rp1" name="txt_Rupiah" style="display: none;">
										<option id="rupiah" value="0">Rp 0,-</option>
										<option id="rupiah" value="50000">Rp 50.000,-</option>
										<option id="rupiah" value="100000">Rp 100.000,-</option>
										<option id="rupiah" value="150000">Rp 150.000,-</option>
										<option id="rupiah" value="200000">Rp 200.000,-</option>
										<option id="rupiah" value="500000">Rp 500.000,-</option>
										<option id="rupiah" value="1000000">Rp 1.000.000,-</option>
									</select>
								</div>
								<div class="col-xs-2">
									<select class="form-control" id="cf2" name="txt_Quantity" style="display: none;">
										<option value="1">1 Qty</option>
										<option value="1">1 Qty</option>
										<option value="2">2 Qty</option>
										<option value="3">3 Qty</option>
										<option value="4">4 Qty</option>
										<option value="5">5 Qty</option>
										<!-- <input type="text" class="form-control" placeholder=".col-xs-4" id="cf2" value="Qty" style="display:none;" readonly> -->

										<input type="text" class="form-control" placeholder=".col-xs-4" id="rp2" value="Muncul Emas" style="display:none;" readonly>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 m-t-15">
								<input type="submit" name="btn_insert" class="btn btn-success " value="Save">
								<a href="?module=adminoffice" class="btn btn-danger">Cancel</a>
							</div>
						</div>

					</form>
					<!-- Modal -->
					<div class="modal fade" id="modal-default">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Add Role</h4>
								</div>
								<div class="modal-body">
									<form method="post" class="form-horizontal">
										<div class="form-group">
											<div class="box-header">
												<input type="text" name="txt_addrole1" class="form-control" placeholder="Masukkan ID " />
											</div>
										</div>
										<div class="form-group">
											<div class="box-header">
												<input type="text" name="txt_addrole2" class="form-control" placeholder="Masukkan Nama" />
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
											<input type="submit" name="btn_insert2" class="btn btn-success " value="Save">
										</div>
									</form>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->

					<!-- form -->
				</div>
				<!-- contain -->
			</div>
		</div>
	</div>
</section>