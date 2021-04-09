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
		$select_stmt = $db->prepare("SELECT * FROM tblproduct P JOIN tblcategory C ON P.field_category=C.field_category_id 
																JOIN tblbranch B ON P.field_branch=B.field_branch_id 
																WHERE P.field_product_id =:id");
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


$Sql ="SELECT * FROM tblcategory";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$resultKategori = $Stmt->fetchAll(); 

if ($_SESSION['rolelogin']=='ADM' OR $_SESSION['rolelogin']=='MGR') {
	$Sql ="SELECT * FROM tblbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
	
}else{
	$Sql ="SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute(array(":idbranch"=>$branchid));
	$result = $Stmt->fetchAll();
}
//extract($row);                

if(isset($_REQUEST['btn_update']))
{

	$kodeproduk	= $_REQUEST['txt_kodeproduk'];	//textbox name "txt_firstname"
	$namaproduk	= $_REQUEST['txt_nama'];	//textbox name "txt_lastname"
	$beratsampah= $_REQUEST['txt_berat'];
	$date		= date('Y-m-d');
	$kategori	= $_REQUEST['txt_kategori'];
	$cabang		= $_REQUEST['txt_cabang'];
	$hargaproduk= $_REQUEST['txt_harga'];
	$Keterangan = $_REQUEST['txt_keterangan'];
	$officer 	= $_SESSION['idlogin'];


	// echo $cabang;
	// echo "<br>";
	// echo $kategori;
	// echo "<br>";
	// echo $kodeproduk;
	// die();

	if(empty($namaproduk)){
		$errorMsg="Silakan Masukkan Nama Produk Sampah";
	}
	else if(empty($hargaproduk)){
		$errorMsg="Silakan Masukkan Harga Sampah";
	}elseif(strlen(is_numeric($hargaproduk))==0) {
		$errorMsg="Silakan Masukkan Harga Benar";
	}elseif ($beratsampah=="Pilih") {
		$errorMsg="Silakan Pilih Jenis Berat Sampah";
	}elseif ($kategori=="Pilih") {
		$errorMsg="Silakan Pilih Kategori Sampah";
	}elseif ($cabang=="Pilih") {
		$errorMsg="Silakan Pilih Kantor Cabang";
	}elseif (empty($Keterangan)) {
		$errorMsg="Silakan Masukan Keterangan Sampah";
	}
	else
	{
		try
		{
			if(!isset($errorMsg))
			{
				
														 //sql insert query	

				$update_stmt=$db->prepare('UPDATE tblproduct SET field_product_code=:kodeproduk, 
																 field_product_name=:namaproduk,
																 field_unit=:beratsampah,
																 field_date_price=:udate,
																 field_category=:kategori,
																 field_branch=:cabang,
																 field_price=:hargaproduk,
																 field_note=:Keterangan,
																 field_officer=:petugas 
																 WHERE field_product_id=:id');
				$update_stmt->bindParam(':id',$id);				
				$update_stmt->bindParam(':kodeproduk',$kodeproduk);
				$update_stmt->bindParam(':namaproduk',$namaproduk); 
				$update_stmt->bindParam(':beratsampah',$beratsampah);
				$update_stmt->bindParam(':udate',$date); 
				$update_stmt->bindParam(':kategori',$kategori);   
				$update_stmt->bindParam(':cabang',$cabang);
				$update_stmt->bindParam(':hargaproduk',$hargaproduk);
				$update_stmt->bindParam(':Keterangan',$Keterangan); 
				$update_stmt->bindParam(':petugas',$officer);	 
					  
					
				if($update_stmt->execute())
				{
					$insertMsg="Update Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1;">';

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
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-edit"></i>
              <h3 class="box-title">Update Product Price</h3>
                
            </div>
              <!-- Content --> 
				<?php
				if(isset($errorMsg)){
				echo'<div class="alert alert-danger"><strong>WRONG !'.$errorMsg.'</strong></div>';
				}
				if(isset($insertMsg)){
				echo'<div class="alert alert-success"><strong>SUCCESS !'.$insertMsg.'</strong></div>';
				}
				?>         
           

            <div class="box-body"> 
			<form method="post" class="form-horizontal">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">Code Product</label>
				<div class="col-sm-3">
				<input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo $row["field_product_code"]; ?>" readonly />
				</div>
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Name Product</label>
				<div class="col-sm-6">
				<input type="text" name="txt_nama" class="form-control" value="<?php echo $row["field_product_name"]; ?>" />
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Price</label>
				<div class="row">
				<div class="col-sm-3">
				<input type="text" name="txt_harga" class="form-control" value="<?php echo $row["field_price"]; ?>" />
				</div>
				<div class="col-sm-2">
					<select class="form-control" name="txt_berat">
						<option value="<?php echo $row["field_unit"]; ?>"><?php echo $row["field_unit"]; ?></option>
						<option value="Kg">Kg</option>
						<option value="Rp">Rp</option>
						<option value="Pcs">Pcs</option>
						<option value="Liter">Liter</option>
					</select>
				
				</div>
				</div>

				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Category</label>
				<div class="col-sm-2">
					<select class="form-control" type="text" name="txt_kategori">
						<option  value="<?php echo $row['field_category']; ?>"><?php echo $row['field_category']."-"; echo $row['field_name_category'] ;?></option>
						<?php foreach($resultKategori as $rows) { ?>
						<option  value="<?php echo $rows['field_category_id']; ?>"><?php echo $rows['field_category_id']."-"; echo $rows['field_name_category'] ; ?></option>
						<?php } ?>
					</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Note</label>
				<div class="col-sm-6">
				<!-- <input type="text" name="txt_keterangan" class="form-control" value="<?php //echo $row["produk_keterangan"]; ?>" /> -->
				<input type="text" class="form-control" name="txt_keterangan" value="<?php echo $row["field_note"]; ?>" >
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-3 control-label">Branch Office</label>
				<div class="col-sm-6">
				<select class="form-control" type="text" name="txt_cabang">
						<option value="<?php echo $row["field_branch"]; ?>"><?php echo $row["field_branch_name"]."-"; echo $row["field_branch_id"]; ?></option>
						<?php foreach($result as $branch) { ?> 
						<option  value="<?php echo $branch['field_branch_id'] ; ?>"><?php echo $branch['field_branch_name']."-";echo $branch['field_branch_id'] ; ?></option>		
						<?php } ?>
					</select>
				</div>
				</div>
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_update" class="btn btn-success " value="Save">
				<a href="?module=product" class="btn btn-danger">Cancel</a>
				</div>
				</div>
					
			</form>    
    <!-- form -->
            </div>
			</div>
      </div>
    </div> 
    </section>
