<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if(!isset($_SESSION['userlogin'])) {
    header("location: ../index.php");
}


if(isset($_REQUEST['id']))
{
	try
	{
		$id = $_REQUEST['id']; //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
		$select_stmt = $db->prepare("SELECT * FROM ((tblemployeeslogin employee JOIN  tbldepartment dept ON employee.field_role=dept.field_department_id) JOIN tblbranch  ON employee.field_branch=tblbranch.field_branch_id) WHERE field_user_id =:id "); //sql select query
		$select_stmt->bindParam(':id',$id);
		$select_stmt->execute(); 
		$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
		//extract($row);
	}
	catch(PDOException $e)
	{
		$e->getMessage();
	}
	
}


$Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='SPA' ";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$resultdept = $Stmt->fetchAll(); 

$Sql ="SELECT * FROM tblbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();
//extract($row);                

if(isset($_REQUEST['btn_update']))
{

	$idemployee	= $_REQUEST['txt_idemployee'];	//textbox name "txt_firstname"
	$firstname	= $_REQUEST['txt_firstname'];	//textbox name "txt_lastname"
	$lastname	= $_REQUEST['txt_lastname'];
	$email  	= $_REQUEST['txt_email'];
	$date		= date('Y-m-d');
	$role		= $_REQUEST['txt_role'];
	$cabang		= $_REQUEST['txt_cabang'];
	$name 		= $firstname." ".$lastname;
	$username 	= $firstname.(rand(10,100));
	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s')));
	//$password 	= password_generate(8);
	$status 	= $_REQUEST['txt_status'];

	//echo $role;



	// echo $cabang;
	// echo "<br>";
	// echo $kategori;
	// echo "<br>";
	// echo $kodeproduk;
	// die();

	if(empty($firstname)){
		$errorMsg="Silakan Masukkan Nama Depan";
	}
	else if(empty($lastname)){
		$errorMsg="Silakan Masukkan Nama Belakang";
	}elseif(empty($email)) {
		$errorMsg="Silakan Masukkan Email";
	}elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
		$errorMsg="Silakan Memasukan Alamat Email yang Valid";
	}elseif ($role=="Pilih") {
		$errorMsg="Silakan Pilih Posisi";
	}elseif ($cabang=="Pilih") {
		$errorMsg="Silakan Pilih Kantor Cabang";
	}
	else
	{
		try
		{
			if(!isset($errorMsg))
			{
				
				$update_stmt=$db->prepare('UPDATE tblemployeeslogin SET field_role=:urole, field_branch=:cabang, field_token=:token, field_status_aktif=:statuse WHERE field_user_id=:id');
				$update_stmt->bindParam(':id',$id);
				// $update_stmt->bindParam(':idemployee',$idemployee);
				// $update_stmt->bindParam(':namaemployee',$name); 
				// $update_stmt->bindParam(':username',$username);  
				$update_stmt->bindParam(':urole',$role);   
				// $update_stmt->bindParam(':udate',$date); 
				$update_stmt->bindParam(':cabang',$cabang);
				// $update_stmt->bindParam(':umail',$email);
				// $update_stmt->bindParam(':upassword',$password);
				// $update_stmt->bindParam(':passwordnew',$new_password);
				$update_stmt->bindParam(':token',$tokenn); 
				$update_stmt->bindParam(':statuse',$status);  
					  
					
				if($update_stmt->execute())
				{
					$insertMsg="Update Successfully"; //execute query success message

					// echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard.php?module=adminoffice">';
					echo '<META HTTP-EQUIV="Refresh" Content="1";>';
					
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

//forget password

if (isset($_REQUEST["btn_forget"])) {
	$idemployee	= $_REQUEST['txt_idemployee'];	//textbox name "txt_firstname"
	$firstname	= $_REQUEST['txt_firstname'];	//textbox name "txt_lastname"
	$username	= $_REQUEST['txt_lastname'];
	$email  	= $_REQUEST['txt_email'];
	$date		= date('Y-m-d');
	$role		= $_REQUEST['txt_role'];
	$cabang		= $_REQUEST['txt_cabang'];	
	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s')));
	$password 	= $_REQUEST['txt_password'];
	$status 	= $_REQUEST['txt_status'];

	$id=$_REQUEST['txt_id'];
	$select_stmt = $db->prepare("SELECT * FROM ((tblemployeeslogin employee JOIN  tbldepartment dept ON employee.field_role=dept.field_department_id) JOIN tblbranch  ON employee.field_branch=tblbranch.field_branch_id) WHERE field_user_id =:id "); //sql select query
	$select_stmt->bindParam(':id',$id);
	$select_stmt->execute(); 
	$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
	$role = $row["field_department_name"];
	$cabang = $row["field_branch_name"];

	include "../mail/mail_forget.php";
			if(!$mail->send()) {
    		$insertMsg="Forget Password..... Pesan Tidak dapat dikirim.".$mail->ErrorInfo;    				
			} else {
    		$insertMsg="Forget Password, Please Check Your Inbox Email ".$email;
			}
}

?>
<!-- Content Header (Page header) -->
     <section  class="content-header">
      <div class="row box-footer">
<!-- <section class="content"> -->
      <div class="row">
        <div class="col-xs-12">
          <!-- <div class="box"> -->

          		<?php
		if(isset($errorMsg))
		{
			?>
            <div class="alert alert-danger">
            	<strong>WRONG ! <?php echo $errorMsg; ?></strong>
            </div>
            <?php
		}
		if(isset($insertMsg)){
		?>
			<div class="alert alert-success">
				<strong>SUCCESS ! <?php echo $insertMsg; ?></strong>
			</div>
        <?php
		}
		?> 
            
           
            <!-- /.box-header -->
            <div class="box-header">
            		<center><h2>Update Employee</h2></center>
			<form method="post" class="form-horizontal">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">NIP</label>
				<div class="col-sm-3">
				<input type="text" name="txt_idemployee" class="form-control" value="<?php echo $row["field_employees_id"]; ?>" readonly />
				<input type="hidden" name="txt_id" class="form-control" value="<?php echo $row["field_user_id"]; ?>" />
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Nama</label>
				<div class="row">
				<div class="col-sm-3">
				<input type="text" name="txt_firstname" class="form-control" value="<?php echo $row["field_name_officer"]; ?>" />
				</div>				
				<div class="col-sm-2">
				<input type="text" name="txt_lastname" class="form-control" value="<?php echo $row["field_username"]; ?>" readonly />
				</div>
				</div>
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
				<input type="text" name="txt_email" class="form-control" value="<?php echo $row["field_email"]; ?>" readonly />
				<input type="hidden" name="txt_password" class="form-control" value="<?php echo $row["Password"]; ?>"/>
				</div>
				</div>


				<div class="form-group">
				<label class="col-sm-3 control-label">Role</label>
				<div class="col-sm-3">
					<select class="form-control" type="text" name="txt_role">
						<option value="<?php echo $row["field_role"]; ?>"><?php echo $row["field_role"]."-"; echo $row['field_department_name']; ?></option>
						<?php foreach($resultdept as $rows) { ?>
						<option  value="<?php echo $rows['field_department_id']; ?>"><?php echo $rows['field_department_id']."-"; echo $rows['field_department_name'] ; ?></option>
						<?php } ?>
					</select>
				</div>
				<!-- <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Role</a> -->
				<!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default"> -->
				</div>

				<!-- <div class="form-group">
				<label class="col-sm-3 control-label">Keterangan</label>
				<div class="col-sm-6">
				<textarea class="form-control" name="txt_keterangan" placeholder="Masukkan Keterangan Produk."></textarea>
				</div>
				</div> -->
				<div class="form-group">
				<label class="col-sm-3 control-label">Cabang Kantor</label>
				<div class="col-sm-6">
				<select class="form-control" type="text" name="txt_cabang">
						<option value="<?php echo $row["field_branch"]; ?>"><?php echo $row['field_branch_name']."-"; echo $row["field_branch"]; ?></option>
						<?php foreach($result as $branch) { ?> 
						<option  value="<?php echo $branch['field_branch_id'] ; ?>"><?php echo $branch['field_branch_name']."-";echo $branch['field_branch_id'] ; ?></option>
						
						<?php } ?>
					</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Status</label>
				<div class="col-sm-3">
							
				<select class="form-control" type="text" name="txt_status">

						<?php if ($row["field_status_aktif"]==1) { 

							echo '<option value="1">Active</option>';

						}elseif ($row["field_status_aktif"]==2) {

							echo '<option value="2">NotActive</option>';

						}?>					
					
						<option value="1">Active</option>
						<option value="2">NotActive</option>

					</select>
				</div>
				</div>
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_update" class="btn btn-success " value="Update">
				<input type="submit"  name="btn_forget" class="btn btn-info " value="Forget Password">
				<a href="?module=adminoffice" class="btn btn-danger">Cancel</a>
				</div>
				</div>
					
			</form>
    
    <!-- form -->
            </div>
            <!-- /.box-body -->
          

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- div ikut atas -->
    </div> 
    </section>
