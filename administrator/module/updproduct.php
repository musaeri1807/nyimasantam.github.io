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
		$select_stmt = $db->prepare("SELECT * FROM ((produk JOIN kategori ON produk.produk_kategori=kategori.kategori_id) JOIN tblbranch ON produk.branch=tblbranch.field_branch_id) WHERE produk_id =:id"); //sql select query
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

$Sql ="SELECT * FROM tblbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();
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

				$update_stmt=$db->prepare('UPDATE produk SET produk_kode=:kodeproduk, produk_nama=:namaproduk,produk_satuan=:beratsampah,produk_date=:udate,produk_kategori=:kategori,branch=:cabang,produk_harga_jual=:hargaproduk,produk_keterangan=:Keterangan WHERE produk_id=:id');
				$update_stmt->bindParam(':id',$id);				
				$update_stmt->bindParam(':kodeproduk',$kodeproduk);
				$update_stmt->bindParam(':namaproduk',$namaproduk); 
				$update_stmt->bindParam(':beratsampah',$beratsampah);
				$update_stmt->bindParam(':udate',$date); 
				$update_stmt->bindParam(':kategori',$kategori);   
				$update_stmt->bindParam(':cabang',$cabang);
				$update_stmt->bindParam(':hargaproduk',$hargaproduk);
				$update_stmt->bindParam(':Keterangan',$Keterangan);  
					  
					
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
            		<center><h2>Update Product</h2></center>
			<form method="post" class="form-horizontal">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">Kode Produk</label>
				<div class="col-sm-3">
				<input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo $row["produk_kode"]; ?>" readonly />
				</div>
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Nama Produk sampah</label>
				<div class="col-sm-6">
				<input type="text" name="txt_nama" class="form-control" value="<?php echo $row["produk_nama"]; ?>" />
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Harga Sampah</label>
				<div class="row">
				<div class="col-sm-3">
				<input type="text" name="txt_harga" class="form-control" value="<?php echo $row["produk_harga_jual"]; ?>" />
				</div>
				<div class="col-sm-2">
					<select class="form-control" name="txt_berat">
						<option value="<?php echo $row["produk_satuan"]; ?>"><?php echo $row["produk_satuan"]; ?></option>
						<option value="Kg">Kg</option>
						<option value="Rp">Rp</option>
						<option value="Pcs">Pcs</option>
						<option value="Liter">Liter</option>
					</select>
				
				</div>
				</div>

				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Kategori Sampah</label>
				<div class="col-sm-2">
					<select class="form-control" type="text" name="txt_kategori">
						<option  value="<?php echo $row['produk_kategori']; ?>"><?php echo $row['produk_kategori']."-"; echo $row['kategori'] ;?></option>
						<?php foreach($resultKategori as $rows) { ?>
						<option  value="<?php echo $rows['kategori_id']; ?>"><?php echo $rows['kategori_id']."-"; echo $rows['kategori'] ; ?></option>
						<?php } ?>
					</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Keterangan</label>
				<div class="col-sm-6">
				<!-- <input type="text" name="txt_keterangan" class="form-control" value="<?php //echo $row["produk_keterangan"]; ?>" /> -->
				<input type="text" class="form-control" name="txt_keterangan" value="<?php echo $row["produk_keterangan"]; ?>" >
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-3 control-label">Cabang Kantor</label>
				<div class="col-sm-6">
				<select class="form-control" type="text" name="txt_cabang">
						<option value="<?php echo $row["branch"]; ?>"><?php echo $row["field_branch_name"]."-"; echo $row["field_branch_id"]; ?></option>
						<?php foreach($result as $branch) { ?> 
						<option  value="<?php echo $branch['field_branch_id'] ; ?>"><?php echo $branch['field_branch_name']."-";echo $branch['field_branch_id'] ; ?></option>		
						<?php } ?>
					</select>
				</div>
				</div>
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_update" class="btn btn-success " value="Update">
				<a href="?module=product" class="btn btn-danger">Cancel</a>
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
