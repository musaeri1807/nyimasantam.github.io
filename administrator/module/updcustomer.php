<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}


if(isset($_REQUEST['id']))
{
	try
	{
		$id = $_REQUEST['id']; //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
		$select_stmt = $db->prepare("SELECT * FROM  tblcustomer n JOIN tbluserlogin u ON n.field_member_id=u.field_member_id  WHERE field_customer_id =:id "); //sql select query		
		$select_stmt->bindParam(':id',$id);
		$select_stmt->execute(); 
		$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
		//extract($row);
		$idcabang=substr($row["field_member_id"], 0,10);

	}
	catch(PDOException $e)
	{
		$e->getMessage();
	}
	
}

//echo $idcabang;
$select_stmt = $db->prepare("SELECT * FROM  tblbranch WHERE field_branch_id =:id "); //sql select query
$select_stmt->bindParam(':id',$idcabang);
$select_stmt->execute(); 
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);


$Sql ="SELECT * FROM tblbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();
//extract($row);                

if(isset($_REQUEST['btn_update']))
{
	$id;	
	$Nama		= strip_tags($_REQUEST['txt_firstname']);	
	$nama   	= ucwords($Nama);	
	$email		= strip_tags($_REQUEST['txt_email']);	
	//$password 	= password_generate(8);
	$angka		= strip_tags($_REQUEST['txt_angka']);	
	$date		= date('Y-m-d');
	$time		= date('H:i:s');
	$random 	= (rand(10000,100));
	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s'))) ;
	$cabang		= $_REQUEST['txt_cabang'];	
	$member_id	= $cabang.$angka;
	$ipaddress 	= $_SERVER['REMOTE_ADDR'];
	$status 	= $_REQUEST['txt_status'];

		
	

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
			
			if($row["field_email"]!==$email){
				$errorMsg="Maaf Email Sudah Ada";	//check condition email already exists 
			}
			else if($row["Field_handphone"]!==$angka){
				$errorMsg="Maaf Nomor Hp Sudah Ada";	//check condition email already exists 
			}			

			elseif(!isset($errorMsg))
			{
				
				$update_stmt=$db->prepare('UPDATE tblcustomer SET field_document=:statuse WHERE field_customer_id=:id');
				$update_stmt->bindParam(':id',$id);
				// $update_stmt->bindParam(':idemployee',$idemployee);
				// $update_stmt->bindParam(':namaemployee',$name); 
				// $update_stmt->bindParam(':username',$username);  
				// $update_stmt->bindParam(':urole',$role);   
				// $update_stmt->bindParam(':udate',$date); 
				// $update_stmt->bindParam(':cabang',$cabang);
				// $update_stmt->bindParam(':umail',$email);
				// $update_stmt->bindParam(':upassword',$password);
				// $update_stmt->bindParam(':passwordnew',$new_password);
				//$update_stmt->bindParam(':token',$tokenn); 
				$update_stmt->bindParam(':statuse',$status);  
					  
					
				if($update_stmt->execute())
				{
					$insertMsg="Update Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard.php?module=activation">';
					
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
            		<center><h2>Update Customer</h2></center>
			<form method="post" class="form-horizontal">					
				

				<div class="form-group">
				<label class="col-sm-3 control-label">Nama</label>
			
				<div class="col-sm-6">
				<input type="text" name="txt_firstname" class="form-control" value="<?php echo $row["field_nama_customer"]; ?>" />
				</div>				
				
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
				<input type="text" name="txt_email" class="form-control" value="<?php echo $row["field_email"]; ?>" readonly />
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">No Handphone</label>
				<div class="col-sm-3">
				<input type="text" name="txt_angka" class="form-control" value="<?php echo $row["field_handphone"]; ?>" readonly/>
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-3 control-label">Swafoto</label>
				<div class="col-sm-3">
				
                
				<img src="../uploads/<?php echo $row['field_swafoto'] ?>" width="100" height="100" class="img-circle" alt="User Image" >
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-3 control-label">Document KTP</label>
				<div class="col-sm-3">
				
                <img src="../uploads/<?php echo $row['field_image_ktp'] ?>" width="150" height="100">
				
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Document Butang</label>
				<div class="col-sm-3">
				
                <img src="../uploads/<?php echo $row['field_image_butang'] ?>" width="150" height="100">
				
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Document Pewaris</label>
				<div class="col-sm-3">
				
                <img src="../uploads/<?php echo $row['field_image_pewaris'] ?>" width="150" height="100">
				
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Alamat</label>
				<div class="col-sm-6">
				<input type="text" name="txt_angka" class="form-control" value="<?php echo $row["field_alamat"]; ?>"/>
				<textarea></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Cabang Kantor</label>
				<div class="col-sm-6">
				<select class="form-control" type="text" name="txt_cabang" readonly>
					<option value="<?php echo $rows["field_branch_id"]; ?>"><?php echo $rows['field_branch_name']."-"; echo $rows["field_branch_id"]; ?></option>
				<!-- 		<?php foreach($result as $branch) { ?> 
					<option  value="<?php echo $branch['field_branch_id'] ; ?>"><?php echo $branch['field_branch_name']."-";echo $branch['field_branch_id'] ; ?></option>
						
						<?php } ?> -->
					</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Status Document</label>
				<div class="col-sm-3">
							
				<select class="form-control" type="text" name="txt_status">

						<?php if ($row["field_document"]=="Y") { 

							echo '<option value="Y">Verifikasi</option>';

						}elseif ($row["field_document"]=="N") {

							echo '<option value="N">Unverifikasi</option>';

						}?>					
					
						<option value="Y">Verifikasi</option>
						<option value="N">Unverifikasi</option>

					</select>
				</div>
				</div>
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_update" class="btn btn-success " value="Update">
				<input type="submit"  name="btn_forgot" class="btn btn-info " value="Forgot Password">
				<a href="?module=activation" class="btn btn-danger">Cancel</a>
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
