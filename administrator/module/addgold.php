<?php
date_default_timezone_set('Asia/Jakarta');
require_once '../connectionuser.php';

if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}

// $SqlEmas="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1";
// $StmtEmas = $db->prepare($SqlEmas);
// $StmtEmas->execute();
// $ResultEmas = $StmtEmas->fetch(PDO::FETCH_ASSOC);
// $SqlEmas2="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1,1";
// $StmtEmas2 = $db->prepare($SqlEmas2);
// $StmtEmas2->execute();
// $ResultEmas2 = $StmtEmas2->fetch(PDO::FETCH_ASSOC);



// $HargaKemarin=$ResultEmas2['field_sell'];
// $HargaTerkini=$ResultEmas['field_sell'];
// $Selisi      =$HargaTerkini-$HargaKemarin;

// echo abs($Selisi);
// echo "<br>";
// echo $Selisi;



// $Sql ="SELECT * FROM kategori";
// $Stmt = $db->prepare($Sql);
// $Stmt->execute();
// $resultKategori = $Stmt->fetchAll(); 

// $Sql ="SELECT * FROM tblbranch";
// $Stmt = $db->prepare($Sql);
// $Stmt->execute();
// $result = $Stmt->fetchAll();
//extract($row);                

if(isset($_REQUEST['btn_insert']))
{

	$hargajual	= $_REQUEST['txt_hargajual'];
	$hargabeli	= $_REQUEST['txt_hargabeli'];	
	$date		= date('Y-m-d H:i:s');


	if(empty($hargajual)){
		$errorMsg="Silakan Masukkan Harga";
	}
	else if(empty($hargabeli)){
		$errorMsg="Silakan Masukkan Harga";
	}
	elseif(strlen(is_numeric($hargajual))==0) {
		$errorMsg="Silakan Masukkan Harga Benar";
	}
	elseif(strlen(is_numeric($hargabeli))==0) {
		$errorMsg="Silakan Masukkan Harga Benar";
	}
	else
	{
		try
		{
			if(!isset($errorMsg))
			{
				$insert_stmt=$db->prepare('INSERT INTO tblgoldprice (field_sell,field_buyback,field_date_gold)VALUES(:uhargajual,:uhargabeli,:udate)'); //sql insert query				
				$insert_stmt->bindParam(':uhargajual',$hargajual);
				$insert_stmt->bindParam(':uhargabeli',$hargabeli);				
				$insert_stmt->bindParam(':udate',$date);					
				if($insert_stmt->execute())
				{
					
					$SqlEmas="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1";
					$StmtEmas = $db->prepare($SqlEmas);
					$StmtEmas->execute();
					$ResultEmas = $StmtEmas->fetch(PDO::FETCH_ASSOC);
					$SqlEmas2="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1,1";
					$StmtEmas2 = $db->prepare($SqlEmas2);
					$StmtEmas2->execute();
					$ResultEmas2 = $StmtEmas2->fetch(PDO::FETCH_ASSOC);

					$HargaTerkini=$ResultEmas['field_sell'];
					$HargaKemarin=$ResultEmas2['field_sell'];
					$Selisi      =$HargaTerkini-$HargaKemarin;
					$trxid       =$ResultEmas['field_gold_id'];
					$abs         =abs($Selisi);

					if ($Selisi > 1) {
					  //echo "POSITIF";
					 $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
					 $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Naik"));     
					}else{
					  //echo "NEGATIF";
					 $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
					 $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Turun"));  
					}



					$insertMsg="Insert Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard.php?module=gold">';
					
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
            		<center><h2>Insert Gold Price</h2></center>
			<form method="post" class="form-horizontal">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">Tanggal</label>
				<div class="col-sm-3">
				<input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo date("d F Y"); ?>" readonly />
				</div>
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Harga Jual</label>
				<div class="col-sm-6">
				<input type="text" name="txt_hargajual" class="form-control" placeholder="Masukkan Harga Jual" />
				</div>
				</div>


				<div class="form-group">
				<label class="col-sm-3 control-label">Harga Beli</label>
				<div class="col-sm-6">
				<input type="text" name="txt_hargabeli" class="form-control" placeholder="Masukkan Harga Buyback" />
				</div>				
				</div>
		
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_insert" class="btn btn-success " value="Insert">
				<a href="?module=gold" class="btn btn-danger">Cancel</a>
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
											                <h4 class="modal-title">Tambah Kategori</h4>
											              </div>
											              <div class="modal-body">
											                <form method="post" class="form-horizontal">
											                	<div class="form-group">
											                		<div class="box-header">
																<input type="text" name="txt_tambahkategori" class="form-control" placeholder="Masukkan Nama Kategori" />
																	</div>
																</div>
														        <div class="modal-footer">
														        <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>														                
														        <input type="submit"  name="btn_insert2" class="btn btn-success " value="Insert">
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
