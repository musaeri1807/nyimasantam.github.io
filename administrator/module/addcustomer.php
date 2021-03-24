<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

// $tokenn		= hash('sha256', md5(date('Y-m-d h:i:s')));
// echo $tokenn;
// die();

if(!isset($_SESSION['userlogin'])) {
    header("location: ../index.php");
}

if ($_SERVER['SERVER_NAME']=='localhost') {
  	$svrname='localhost';
}elseif ($_SERVER['SERVER_NAME']=='urunanmu.my.id') {
  	$svrname='urunanmu.my.id';
}elseif ($_SERVER['SERVER_NAME']=='nyimasantam.com') {
	$svrname='nyimasantam.com';
}elseif ($_SERVER['SERVER_NAME']=='nyimasantam.my.id') {
	$svrname='nyimasantam.my.id';
}

if ($_SESSION['rolelogin']=='ADM') {
	// $Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='SPA'";
	$Sql ="SELECT * FROM tbldepartment ";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$resultdept = $Stmt->fetchAll(); 
	# code...
	$Sql ="SELECT * FROM tblbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
}elseif ($_SESSION['rolelogin']=='MGR') {
	# code...
	$Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR'";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$resultdept = $Stmt->fetchAll(); 

	$Sql ="SELECT * FROM tblbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
}elseif ($_SESSION['rolelogin']=='SPV' OR $_SESSION['rolelogin']=='BCO' OR $_SESSION['rolelogin']=='CMS') {
	# code...
	$Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR' AND field_department_id !='SPV'";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$resultdept = $Stmt->fetchAll();

	$Sql ="SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
	$Stmt = $db->prepare($Sql);
	//$Stmt->execute();
	$Stmt->execute(array(":idbranch"=>$branchid));
	$result = $Stmt->fetchAll();
}

//extract($row);               

if(isset($_REQUEST['btn_insert']))
{


	$Nama		= strip_tags($_REQUEST['txt_firstname']);	
	$nama   	= ucwords($Nama);	
	$email		= strip_tags($_REQUEST['txt_email']);	
	$password 	= password_generate(8);
	$angka		= strip_tags($_REQUEST['txt_angka']);	
	$date		= date('Y-m-d');
	$time		= date('H:i:s');
	$random 	= (rand(999,9999));
	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s'))) ;
	$cabang		= $_REQUEST['txt_cabang'];	
	$member_id	= $cabang.$angka;
	$ipaddress 	= $_SERVER['REMOTE_ADDR'];

	
	

	if(empty($nama)){
		$errorMsg="Silakan Memasukan Nama Anda";	 
	}
	else if(empty($email)){
		$errorMsg="Silakan Memasukan Email Anda";	
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errorMsg="Silakan Memasukan Alamat Email yang Valid";	 
	}
	
	else if(strlen(is_numeric($angka))==0){
		$errorMsg = "Silakan Memasukan Angka";			
	}	
	else if(strlen($angka)< 10){
		$errorMsg = "Nomor Hp Tidak Sesuai";	
	}
	else if (strlen($angka) > 12) {
		$errorMsg = "Nomor Hp Terlalu Panjang";		

	}elseif ($cabang=="Pilih") {
		$errorMsg="Silakan Pilih Kantor Cabang";
	}
	else
	{
		try
		{

			$select_stmt=$db->prepare("SELECT field_email,Field_handphone  FROM tbluserlogin 
										WHERE field_email=:uemail OR Field_handphone=:only" ); // sql select query			
			$select_stmt->execute(array(':uemail'	=>$email,
										':only'		=>$angka
										)); //execute query 
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);	
			
			if($row["field_email"]==$email){
				$errorMsg="Maaf Email Sudah Ada";	//check condition email already exists 
			}
			else if($row["Field_handphone"]==$angka){
				$errorMsg="Maaf Nomor Hp Sudah Ada";	//check condition email already exists 
			}			

			elseif(!isset($errorMsg))
			{
				$new_password = password_hash($password, PASSWORD_DEFAULT);
				$insert_stmt=$db->prepare("INSERT INTO tbluserlogin
											(field_nama,field_email,field_handphone,field_password,Password,field_tanggal_reg,field_token,field_member_id,field_time_reg,field_ipaddress,field_token_otp,field_branch) VALUES
											(:uname,:uemail,:only,:upassword,:password,:tgl,:rtoken,:id_member,:timee,:addresip,:random,:branch)");				
				
				if($insert_stmt->execute(array(	':uname'	=>$nama, 
												':uemail'	=>$email,
												':only' 	=>$angka,
												':upassword'=>$new_password,
												':password'	=>$password,
												':tgl'		=>$date,
												':rtoken'	=>$tokenn,
												':id_member'=>$member_id,
												':timee'	=>$time,
												':addresip'	=>$ipaddress,
												':random'	=>$random,
												':branch'	=>$cabang

												))){

					$insertMsg="Insert Successfully";
					// echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard?module=nasabah">';
					echo '<META HTTP-EQUIV="Refresh" Content="1">';

					include "../mail/mail_register.php";
					if(!$mail->send()) {
    				
    					$insertMsg="Daftar Berhasil ..... Pesan idak dapat dikirim.".$mail->ErrorInfo;    				
					} else {
    					$insertMsg="Register Successfully, Please Check Your Inbox Email ".$email;
					}			
				}	
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
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
            		<center><h2>Insert Customer</h2></center>
			<form method="post" class="form-horizontal">
					

				<div class="form-group">
				<label class="col-sm-3 control-label">Nama</label>
			
				<div class="col-sm-6">
				<input type="text" name="txt_firstname" class="form-control" placeholder="Masukkan Nama" />
				</div>				
				
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
				<input type="text" name="txt_email" class="form-control" placeholder="Masukkan Email" />
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">No Handphone</label>
				<div class="col-sm-3">
				<input type="text" name="txt_angka" class="form-control" placeholder="Masukkan No Hp 08XX" />
				</div>
				</div>

				
				<div class="form-group">
				<label class="col-sm-3 control-label">Cabang Kantor</label>
				<div class="col-sm-6">
				<select class="form-control" type="text" name="txt_cabang">
						<option>Pilih</option>
						<?php foreach($result as $branch) { ?> 
						<option  value="<?php echo $branch['field_branch_id'] ; ?>"><?php echo $branch['field_branch_name']."-";echo $branch['field_branch_id'] ; ?></option>
						
						<?php } ?>
					</select>
				</div>
				</div>
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_insert" class="btn btn-success " value="Insert">
				<a href="?module=customer" class="btn btn-danger">Cancel</a>
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
														        <input type="submit"  name="btn_insert2" class="btn btn-success " value="Save">
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
            <!-- /.box-body -->
          

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- div ikut atas -->
    </div> 
    </section>
