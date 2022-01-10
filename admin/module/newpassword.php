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


// mencari kode barang dengan nilai paling besar

// $sql	= "SELECT field_employees_id FROM tblemployeeslogin ORDER BY field_employees_id DESC LIMIT 1 ";
// $stmt 	= $db->prepare($sql);
// $stmt->execute();
// $row 	= $stmt->fetch(PDO::FETCH_ASSOC);

// //$id = $row['field_employees_id'];
// if ($row["field_employees_id"] == "") {
// 	$no = 1;
// 	$year = substr(date("Y"), -2);
// 	$mont = date("m");
// 	$nip = $year . $mont;
// 	$idemployees = $nip . sprintf("%04s", $no);
// } else {
// 	$id = $row['field_employees_id'];
// 	$seri = substr($id, 4);
// 	$no = $seri + 1;
// 	$year = substr(date("Y"), -2);
// 	$mont = date("m");
// 	$nip = $year . $mont;
// 	$idemployees = $nip . sprintf("%04s", $no);
// }


// if ($_SESSION['rolelogin'] == 'ADM') {
// 	// $Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='SPA'";
// 	$Sql = "SELECT * FROM tbldepartment ";
// 	$Stmt = $db->prepare($Sql);
// 	$Stmt->execute();
// 	$resultdept = $Stmt->fetchAll();
// 	# code...
// 	$Sql = "SELECT * FROM tblbranch";
// 	$Stmt = $db->prepare($Sql);
// 	$Stmt->execute();
// 	$result = $Stmt->fetchAll();
// } elseif ($_SESSION['rolelogin'] == 'MGR') {
// 	# code...
// 	$Sql = "SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR'";
// 	$Stmt = $db->prepare($Sql);
// 	$Stmt->execute();
// 	$resultdept = $Stmt->fetchAll();

// 	$Sql = "SELECT * FROM tblbranch";
// 	$Stmt = $db->prepare($Sql);
// 	$Stmt->execute();
// 	$result = $Stmt->fetchAll();
// } elseif ($_SESSION['rolelogin'] == 'SPV') {
// 	# code...
// 	$Sql = "SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR' AND field_department_id !='SPV'";
// 	$Stmt = $db->prepare($Sql);
// 	$Stmt->execute();
// 	$resultdept = $Stmt->fetchAll();

// 	$Sql = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
// 	$Stmt = $db->prepare($Sql);
// 	//$Stmt->execute();
// 	$Stmt->execute(array(":idbranch" => $branchid));
// 	$result = $Stmt->fetchAll();
// }

//extract($row);                

if (isset($_REQUEST['btn_password'])) {

	$oldpassword		= $_REQUEST['txt_oldpassword'];	
	//$oldpassword		= "P@ssw0rd1";	
	$newpassword		= $_REQUEST['txt_newpassword'];	
	$confirmpassword	= $_REQUEST['txt_confirmpassword'];
	// $email  	= $_REQUEST['txt_email'];

	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s')));
	//$password 	= password_generate(8);
	$uid		= $rows['field_user_id'];
	// echo $rows['field_password'];
	// 		echo '<br>';
	// 		echo $oldpassword;
	// 		echo '<br>';
	// 		echo "Password New ".$new_password = password_hash($oldpassword, PASSWORD_DEFAULT);
	// 		echo '<br>';
	// 		echo "Very".password_verify($oldpassword, $rows["field_password"]);
	// 		die();

	if (empty($oldpassword)) {
		$errorMsg = "Silakan Masukkan Old Password";
	} else if (empty($newpassword)) {
		$errorMsg = "Silakan Masukkan New Password";
	} elseif (empty($confirmpassword)) {
		$errorMsg = "Silakan Masukkan Confirm Password";
	} elseif ($newpassword!==$confirmpassword) {
		$errorMsg = "Silakan Masukkan Password Valid";	
	} else {
		try {

			
			if (password_verify($oldpassword, $rows["field_password"])) {
				//$errorMsg = "Password old tidak sesuai";	//check co	ndition email already exists 
				$new_password = password_hash($newpassword, PASSWORD_DEFAULT);
				$sql_stmt	= " UPDATE tblemployeeslogin SET field_password=:newpassword,Password=:confirmpassword  WHERE field_user_id=:ide";
				$insert_stmt = $db->prepare($sql_stmt);
				$insert_stmt->bindParam(':ide', $uid);				
				$insert_stmt->bindParam(':newpassword', $new_password);
				$insert_stmt->bindParam(':confirmpassword', $confirmpassword);
				// $insert_stmt->bindParam(':token', $tokenn);
				if ($insert_stmt->execute()) {
					$insertMsg="Password Success di Ubah";
					echo '<META HTTP-EQUIV="Refresh" Content="3;">';
				}
				
				
			
			} else{

				$errorMsg = "Password old tidak sesuai";	//check co	ndition email already exists 

			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}

?>
<section class="content">
				<!-- Content -->
				<?php
				if (isset($errorMsg)) {
					echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
				}
				if (isset($insertMsg)) {
					echo '<div class="alert alert-success"><strong>SUCCESS !' . $insertMsg . '</strong></div>';
				}
				?>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<i class="fa fa-edit"></i>
					<h3 class="box-title">New Password</h3>
				</div>
				

				<div class="box-body">
					<form method="post" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-3 control-label">Old Password</label>
							<div class="col-sm-6">
								<input type="password" name="txt_oldpassword" class="form-control" placeholder="Old Password" />
							</div>
						</div>



						<div class="form-group">
							<label class="col-sm-3 control-label">New Password</label>
							<div class="col-sm-6">
								<input type="password" name="txt_newpassword" class="form-control" placeholder="New Password" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Confirm Password</label>
							<div class="col-sm-6">
								<input type="password" name="txt_confirmpassword" class="form-control" placeholder="Confirm Password" />
							</div>
						</div>



						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 m-t-15">
								<input type="submit" name="btn_password" class="btn btn-success " value="Save">
								<a href="?module=adminoffice" class="btn btn-danger">Cancel</a>
							</div>
						</div>

					</form>


					<!-- form -->
				</div>
				<!-- contain -->
			</div>
		</div>
	</div>
</section>