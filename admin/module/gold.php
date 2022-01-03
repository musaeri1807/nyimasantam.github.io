<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if(!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

// var_dump($id);
// die();

if (isset($_REQUEST['btn_insert2'])) {
  $branchid   = $rows['field_branch'];
	$hargajual	= $_REQUEST['txt_hargajual'];
	$hargabeli	= $_REQUEST['txt_hargabeli'];
	$date		    = date('Y-m-d');
  $datetime   = date('Y-m-d H:i:s');
  $users      = $rows['field_user_id'];

	if (empty($hargajual)) {
		$errorMsg = "Silakan Masukkan Harga";
	} else if (empty($hargabeli)) {
		$errorMsg = "Silakan Masukkan Harga";
	} elseif (strlen(is_numeric($hargajual)) == 0) {
		$errorMsg = "Silakan Masukkan Harga Benar";
	} elseif (strlen(is_numeric($hargabeli)) == 0) {
		$errorMsg = "Silakan Masukkan Harga Benar";
	} else
		{
		try
		{
			if(!isset($errorMsg))
			{
				$insert_stmt = $db->prepare('INSERT INTO tblgoldprice (field_branch,field_sell,field_buyback,field_datetime_gold,field_date_gold,field_officer_id)VALUES(:ubranch,:uhargajual,:uhargabeli,:udatetime,:udate,:users)'); //sql insert query				
				$insert_stmt->bindParam(':ubranch', $branchid);
        $insert_stmt->bindParam(':uhargajual', $hargajual);
				$insert_stmt->bindParam(':uhargabeli', $hargabeli);
				$insert_stmt->bindParam(':udate', $date);	
        $insert_stmt->bindParam(':udatetime', $datetime);			  
				$insert_stmt->bindParam(':users', $users);	  
					
				if($insert_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
					
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}elseif(isset($_REQUEST['btn_update'])){
  $idcategory=$_REQUEST['txt_idcategory'];
  $category=$_REQUEST['txt_category'];
	$typetrash=$_REQUEST['txt_group_category'];

	if (empty($category)) {
		$errorMsg="Silakan Masukkan Category";
	}elseif ($typetrash=="Pilih") {
		$errorMsg="Silakan Masukkan Type";
	}else
		{
		try
		{
			if(!isset($errorMsg))
			{
				$update_stmt=$db->prepare('UPDATE tblcategory SET field_name_category=:category,field_type_product=:typetrash WHERE field_category_id=:idcategory'); //sql insert query					
				$update_stmt->bindParam(':idcategory',$idcategory);
        $update_stmt->bindParam(':category',$category);
				$update_stmt->bindParam(':typetrash',$typetrash);				  
					  
					
				if($update_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
					
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

}elseif(isset($_REQUEST['btn_aprovel'])){
  $idprice=$_REQUEST['txt_idprice'];
  $note=$_REQUEST['txt_note'];
	$typeaprove=$_REQUEST['txt_aprovel'];
  $id;

	if (empty($idprice)) {
		$errorMsg="Silakan Masukkan id";
	}elseif ($typeaprove=="Pilih") {
		$errorMsg="Silakan Masukkan Type";
	}else
		{
		try
		{
			if(!isset($errorMsg))
			{
				$update_stmt=$db->prepare('UPDATE tblgoldprice SET field_status=:typeaprove,field_note=:note,field_approve=:idaprovel WHERE field_gold_id=:idprice'); //sql insert query					
				$update_stmt->bindParam(':idprice',$idprice);
        $update_stmt->bindParam(':note',$note);
				$update_stmt->bindParam(':typeaprove',$typeaprove);
        $update_stmt->bindParam(':idaprovel',$id);
					
				if($update_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
					
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

}elseif(isset($_REQUEST['id'])){

  $id=$_REQUEST['id'];  

	if (empty($id)) {
		$errorMsg="Silakan Id Category";
	}else	{
		try	{
			if(!isset($errorMsg))	{

        $select_stmt= $db->prepare('SELECT * FROM tblcategory WHERE field_category_id =:id'); //sql select query
        $select_stmt->bindParam(':id',$id);
        $select_stmt->execute();
        $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
      
        // echo $row['field_gold_id'];
        $delete_stmt = $db->prepare('DELETE FROM tblcategory WHERE field_category_id =:id');
        $delete_stmt->bindParam(':id',$id);
        $delete_stmt->execute();					
				// if($delete_stmt->execute()){
				// 	$Msg="Successfully"; //execute query success message
				// 	// echo '<META HTTP-EQUIV="Refresh" Content="1">';
        //   echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';
					
				// }
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

}elseif(isset($_REQUEST['goldid'])){

  $idgold=$_REQUEST['goldid'];  
  $typeaprove="A";
  $id;  

	if (empty($id)) {
		$errorMsg="Silakan Masukan Id ";
	}else	{
		try	{
			if(!isset($errorMsg))	{        

        $update_stmt=$db->prepare('UPDATE tblgoldprice SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_gold_id=:id'); //sql insert query					
				       
				$update_stmt->bindParam(':typeaprove',$typeaprove);
        $update_stmt->bindParam(':idaprovel',$id);
        $update_stmt->bindParam(':id',$idgold);
					
				if($update_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
          
					
				}
			
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

}elseif(isset($_REQUEST['idgold'])){

  $idgold=$_REQUEST['idgold'];  
  $typeaprove="R";
  $id;  

	if (empty($id)) {
		$errorMsg="Silakan Masukan Id ";
	}else	{
		try	{
			if(!isset($errorMsg))	{        

        $update_stmt=$db->prepare('UPDATE tblgoldprice SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_gold_id=:id'); //sql insert query					
				       
				$update_stmt->bindParam(':typeaprove',$typeaprove);
        $update_stmt->bindParam(':idaprovel',$id);
        $update_stmt->bindParam(':id',$idgold);
					
				if($update_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';        
					
				}
			
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

}

// $Sql = "SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC";

$Sql = "SELECT G.*,B.field_branch_id,B.field_branch_name,E.field_name_officer,E2.field_name_officer AS Aproval
        FROM tblgoldprice G LEFT JOIN tblbranch B ON G.field_branch=B.field_branch_id 
        LEFT JOIN tblemployeeslogin E ON G.field_officer_id=E.field_user_id
        LEFT JOIN tblemployeeslogin E2 ON G.field_approve=E2.field_user_id
        ORDER BY field_gold_id DESC";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();

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
  
 $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
              // execute the query
            $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Naik"));     
}else{
  
  $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
              // execute the query
            $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Turun"));  
}





// massege
if(isset($errorMsg)){
  echo'<div class="alert alert-danger"><strong>WRONG !'.$errorMsg.'</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=gold">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=gold">';
  }
}
if(isset($Msg)){
  echo'<div class="alert alert-success"><strong>SUCCESS !'.$Msg.'</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=gold">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=gold">';
  }

}

?> 
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-edit"></i>
              <h3 class="box-title">Gold Price</h3>  
                          
              <a data-toggle="modal" data-target="#modal-default-category" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Price</a>        
            </div>     
              <!-- Content --> 
              <!-- modal add -->
              <div class="modal fade" id="modal-default-category">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Add Price</h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
                      <div class="form-group">
                      <label class="col-sm-4 control-label">Tanggal =</label>
                      <div class="col-sm-5">
                        <input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo date("d F Y"); ?>" readonly />
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Harga Jual =</label>
                      <div class="col-sm-7">
                        <input type="text" name="txt_hargajual" class="form-control" placeholder="Masukkan Harga Jual" />
                      </div>
                    </div>


                    <div class="form-group">
                      <label class="col-sm-4 control-label">Harga Beli Buyback =</label>
                      <div class="col-sm-7">
                        <input type="text" name="txt_hargabeli" class="form-control" placeholder="Masukkan Harga Buyback" />
                      </div>
                    </div>





  
												<div class="modal-footer">
												<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>														                
												<input type="submit"  name="btn_insert2" class="btn btn-success " value="Add">
												</div>
										</form>
                    </div>
                    </div>
                    <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
            <!-- /Content -->
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>
                  <th >Ratio</th>                               
                  <th >Name Gold</th>                 
                  <th >Branch</th>
                  <th >Amount Price</th> 
                  <th >Status</th> 
                  <th >Submitter</th>                 
                  <th >Action</th>               
                </tr>
                </thead>
                <tbody>
                <?php
             $no=1; 
              foreach(array_slice($result,0,2) as $row) {        
                ?> 
             
             <tr>
                  <td ><?php echo $no++ ;?></td>
                                
                  <td>
                    <?php 

                   if ($row["field_rasio"]=="Naik") {
                        echo '<div class="glyphicon glyphicon-arrow-up btn btn-info"> </div>';
                      }elseif ($row["field_rasio"]=="Sama") {
                        echo '<div class="glyphicon glyphicon-align-justify btn btn-warning"> </div>';
                      }elseif ($row["field_rasio"]=="Turun") {
                        echo '<div class="glyphicon glyphicon-arrow-down btn btn-danger"> </div>';
                      }
                     echo '<strong> '.rupiah($row["field_fluktuasi"]).'<strong>'; 
                   ?>                     
                   </td>
                  <td data-title="Trx Id"><small>Harga Update <?php echo date("d F Y H:i:s",strtotime($row["field_datetime_gold"]));?></small><br><strong><?php echo $row["field_name"];?></strong></td>
                  <!-- <td data-title="Branchid"><small><?php echo $row["field_branch"];?></small><br><strong><?php echo $row["field_officer_id"];?></strong></td> -->
                  <td data-title="Trx Id"><small><?php echo $row["field_branch_name"];?></small><br><strong><?php echo $row["field_name_officer"];?></strong></td>
                  <td data-title="Trx Id">Jual <strong><?php echo rupiah($row["field_buyback"]);?></strong><br>Beli <strong><?php echo rupiah($row["field_sell"]);?></strong></td>
                  <td data-title="Trx Id"><strong>

                  <?php 
                                        
                    if ($row["field_status"]=="A") {
                      echo '<span class="badge btn-success text-white">Approved</span>';                  
                    }elseif ($row["field_status"]=="C") {                     
                      echo '<span class="badge btn-info text-white">Cancel</span>';                      
                    }elseif ($row["field_status"]=="P") {
                      echo '<span class="badge btn-warning text-white">Pending</span>';                  
                    }elseif ($row["field_status"]=="R") {                      
                      echo '<span class="badge btn-danger text-white">Reject</span>';  
                    }
                  ?>         
                  
                  
                
                
                
                </td>
                  <td data-title="Trx Id"><strong><?php echo $row["Aproval"];?></td>
                  
                  <td >              
                   <?php 
                    if ($rows["field_role"]=="ADM") {
                      // echo '<a href="?module=updproduct&id='.$row["field_category_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      echo '<a data-toggle="modal" data-target="#modal-update-category'.$row["field_gold_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a> &nbsp';
                      // echo '<a href="?module=product&id='.$row["field_product_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a data-toggle="modal" data-target="#modal-delete-category'.$row["field_gold_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $row["field_gold_id"] . '" class="text-white btn btn-warning "><i class="fa fa-check-square"></i> Approve </a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $row["field_gold_id"] . '" class="text-white btn btn-danger "><i class="fa fa-window-close"></i> Reject </a> &nbsp';                    
                      echo '<a href="#" data-toggle="modal" data-target="#modal-approvel-price'.$row["field_gold_id"].'" class="text-white btn btn-info "><i class="fa fa-info-circle"></i> Detail </a> &nbsp';
                    }elseif ($rows["field_role"]=="MGR") {
                      if ($row["field_status"]=="P") {
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $row["field_gold_id"] . '" class="text-white btn btn-warning "><i class="fa fa-check-square"></i> Approve </a> &nbsp';
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $row["field_gold_id"] . '" class="text-white btn btn-danger "><i class="fa fa-window-close"></i> Reject </a> &nbsp';
                                               
                      } else {
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }                  
                    }elseif ($rows["field_role"]=="AMR") {
                      if ($row["field_status"]=="P") {
                        # code...
                        // echo '<a href="#" data-toggle="modal" data-target="#modal-approvel-price'.$row["field_gold_id"].'" class="text-white btn btn-info "><i class="fa fa-info-circle"></i> Detail </a> &nbsp';
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>'; 
                      } else {
                        # code...
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }                   
                    }elseif ($rows["field_role"]=="SPV") {
                      if ($row["field_status"]=="P") {
                        # code...
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';                        
                      } else {
                        # code...
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }             
                    }elseif ($rows["field_role"]=="BCO") {
                      if ($row["field_status"]=="P") {                       
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>'; 
                      } else {
                        # code...
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }             
                    }elseif ($rows["field_role"]=="CMS") {
                      if ($row["field_status"]=="P") {
                        # code...                        
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>'; 
                      } else {
                        # code...
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }             
                    }
                     ?>           
                  </td>                                       
                </tr>
              
              <!-- modal update-->
              <div class="modal fade" id="modal-update-category<?php echo $row["field_gold_id"];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Update Price <?php echo $row["field_gold_id"]?></h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
											<div class="form-group">
											  <div class="box-header">
                          <input type="hidden" name="txt_idcategory" value="<?php echo $row['field_gold_id']?>">
													<input type="text" name="txt_category" class="form-control" value="<?php echo $row["field_gold_id"]?>" />
												</div>
												<div class="box-header">
												<select class="form-control" name="txt_group_category">
												<option value="<?php echo $row["field_gold_id"]?>"><?php echo $row["field_gold_id"]?></option>
												<option value="Anorganic">Anorganic</option>
												<option value="Organic">Organic</option>
												<option value="B3">B3</option>
												<option value="Rupiah">Rupiah</option>
												</select>
												</div>
												</div>
												<div class="modal-footer">
												<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>														                
												<input type="submit"  name="btn_update" class="btn btn-success " value="Update">
												</div>
										</form>
                    </div>
                    </div>
                    <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                     <!-- modal update approvel-->
                    <div class="modal fade" id="modal-approvel-price<?php echo $row["field_gold_id"];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Approve Id <?php echo $row["field_gold_id"]?></h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
											<div class="form-group">
											  <div class="box-header">
                          <input type="hidden" name="txt_idprice" value="<?php echo $row['field_gold_id']?>">
													
                          <textarea class="form-control" name="txt_note" id="textarea" rows="3"></textarea>
												</div>
												<div class="box-header">
												<select class="form-control" name="txt_aprovel">
                        <option value="Pilih">Pilih</option>
                        <?php 
                        foreach ($RESULT as $STATUS){

                          echo '<br>';
                          echo $STATUS['field_status'];
                         
                        ?>
												<option value="<?php echo $STATUS["field_cdstatus"]?>"><?php echo $STATUS["field_status"]?></option>
												<!-- <option value="A">A</option>
												<option value="R">R</option>
												<option value="C">C</option>
												<option value="P">P</option> -->
                        <?php }?>
												</select>
												</div>
												</div>
												<div class="modal-footer">
												<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>														                
												<input type="submit"  name="btn_aprovel" class="btn btn-success " value="OK">
												</div>
										</form>
                    </div>
                    </div>
                    <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
            
                <!-- modal delete-->
                    <div class="modal fade" id="modal-delete-category<?php echo $row["field_gold_id"];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Yakin Delete Data</h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
                    <div class="form-group">
                    <div class="box-header"> 

                    <center>
                    <h4>              
                    <?php 
                      echo "Nama product ".$row["field_gold_id"]." Dengan ".rupiah($row["field_gold_id"]);                      
                    ?>
                    </h4>   
                    </center> 
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                    <!-- <input type="submit"  name="btn_delete" class="btn btn-success " value="YES"> -->
                    <a href="?module=gold&id=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
                    </div>
                    </form>
                    </div>
                    </div>
                    <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal Approval -->
                <div class="modal fade" id="modal-default-aproval<?php echo $row["field_gold_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h3 class="modal-title">Anda Yakin Untuk Approve</h3>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">                            
                              <center>
                                <h4>
                                  <?php
                                  echo 'Harga Jual '.rupiah($row["field_buyback"]).'<br>'.'Harga Beli '.rupiah($row["field_sell"]);                                  
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href=" ?module=gold&goldid=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp YES &nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Modal Reject -->
                <div class="modal fade" id="modal-default-reject<?php echo $row["field_gold_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h3 class="modal-title">Anda Yakin Untuk Reject</h3>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">                            
                              <center>
                                <h4>
                                  <?php
                                  echo 'Harga Jual '.rupiah($row["field_buyback"]).'<br>'.'Harga Beli '.rupiah($row["field_sell"]);  
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=gold&idgold=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp YES &nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                
              <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                  <th >No</th>
                  <th >Ratio</th>                               
                  <th >Name Gold</th>                 
                  <th >Branch</th>
                  <th >Amount Price</th> 
                  <th >Status</th> 
                  <th >Submitter</th>                 
                  <th >Action</th> 
                </tr>
                </tfoot>
              </table>                              
            </div>
              <!-- Content -->
            </div>
        </div>
      </div>
    </section>


