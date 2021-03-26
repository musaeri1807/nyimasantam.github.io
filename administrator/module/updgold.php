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
		$id=$_REQUEST['id']; //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
		$select_stmt = $db->prepare("SELECT * FROM tblgoldprice WHERE field_gold_id=:id"); //sql select query
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
// echo $id;
                

if(isset($_REQUEST['btn_update']))
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
				
														 //sql insert query	

				$update_stmt=$db->prepare('UPDATE tblgoldprice SET field_sell=:uhargajual,field_buyback=:uhargabeli,field_date_gold=:udate WHERE field_gold_id=:id');
				$update_stmt->bindParam(':id',$id);				
				$update_stmt->bindParam(':uhargajual',$hargajual);
				$update_stmt->bindParam(':uhargabeli',$hargabeli);				
				$update_stmt->bindParam(':udate',$date);  
					  
					
				if($update_stmt->execute())
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
					  // echo "POSITIF";
					 $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
					 $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Naik"));     
					}else{
					  // echo "NEGATIF";
					 $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
					 $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Turun"));  
					}



					$insertMsg="Insert Successfully"; //execute query success message
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
              <h3 class="box-title">Update Gold Price</h3>
                
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
				<label class="col-sm-3 control-label">Tanggal</label>
				<div class="col-sm-3">
				<input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo date("d F Y"); ?>" readonly />
				</div>
				</div>
						
				<div class="form-group">
				<label class="col-sm-3 control-label">Harga Jual</label>
				<div class="col-sm-6">
				<input type="text" name="txt_hargajual" class="form-control" value="<?php echo $row['field_sell']; ?>" />
				</div>
				</div>


				<div class="form-group">
				<label class="col-sm-3 control-label">Harga Beli</label>
				<div class="col-sm-6">
				<input type="text" name="txt_hargabeli" class="form-control" value="<?php echo $row['field_buyback']; ?>" />
				</div>				
				</div>
			
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_update" class="btn btn-success " value="Save">
				<a href="?module=gold" class="btn btn-danger">Cancel</a>
				</div>
				</div>
					
			</form>
            </div>
        </div>
      </div>
    </div> 
    </section>
