<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

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
		$errorMsg="Silakan Masukkan Id";
	}elseif ($typeaprove=="Pilih") {
		$errorMsg="Silakan Masukkan Type";
	}else
		{
		try
		{
			if(!isset($errorMsg))
			{
				$update_stmt=$db->prepare('UPDATE tblproduct SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_product_id=:idprice'); //sql insert query					
				$update_stmt->bindParam(':idprice',$idprice);
        
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

}elseif(isset($_REQUEST['productid'])){

  $idproduct=$_REQUEST['productid'];  
  $typeaprove="A";
  $id;  

	if (empty($id)) {
		$errorMsg="Silakan Masukan Id ";
	}else	{
		try	{
			if(!isset($errorMsg))	{        

        $update_stmt=$db->prepare('UPDATE tblproduct SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_product_id=:id'); //sql insert query					
				       
				$update_stmt->bindParam(':typeaprove',$typeaprove);
        $update_stmt->bindParam(':idaprovel',$id);
        $update_stmt->bindParam(':id',$idproduct);
					
				if($update_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
          if ($_SERVER['SERVER_NAME'] == 'localhost') {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=product">';
          } else {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=product">';
          }
					
				}
			
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

}elseif(isset($_REQUEST['idproduct'])){

  $idproduct=$_REQUEST['idproduct'];  
  $typeaprove="R";
  $id;  

	if (empty($id)) {
		$errorMsg="Silakan Masukan Id ";
	}else	{
		try	{
			if(!isset($errorMsg))	{        

        $update_stmt=$db->prepare('UPDATE tblproduct SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_product_id=:id'); //sql insert query		
				$update_stmt->bindParam(':typeaprove',$typeaprove);
        $update_stmt->bindParam(':idaprovel',$id);
        $update_stmt->bindParam(':id',$idproduct);
					
				if($update_stmt->execute())
				{
					$Msg="Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
          if ($_SERVER['SERVER_NAME'] == 'localhost') {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=product">';
          } else {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=product">';
          }
					
				}
			
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

}


if (isset($_REQUEST['id'])) {
  $id = $_REQUEST['id'];

  $select_stmt = $db->prepare('SELECT * FROM tblproduct WHERE field_product_id =:id'); //sql select query
  $select_stmt->bindParam(':id', $id);
  $select_stmt->execute();
  $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

  // echo $row['tblproduct_kode'];
  $delete_stmt = $db->prepare('DELETE FROM tblproduct WHERE field_product_id =:id');
  $delete_stmt->bindParam(':id', $id);
  $delete_stmt->execute();
}


if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  // $Sql = "SELECT * FROM tblproduct";

  $Sql = "SELECT P.*,E.field_name_officer,C.field_name_category,B.field_branch_name,E2.field_name_officer AS Aproval 
  FROM tblproduct P 
  LEFT JOIN tblcategory C ON P.field_category=C.field_category_id 
  LEFT JOIN tblbranch B ON P.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON P.field_officer_id=E.field_user_id 
  LEFT JOIN tblemployeeslogin E2 ON P.field_approve=E2.field_user_id
  ORDER BY P.field_product_id DESC";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $result = $Stmt->fetchAll();
} else {
  $Sql = "SELECT P.*,E.field_name_officer,C.field_name_category,B.field_branch_name,E2.field_name_officer AS Aproval 
  FROM tblproduct P 
  LEFT JOIN tblcategory C ON P.field_category=C.field_category_id 
  LEFT JOIN tblbranch B ON P.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON P.field_officer_id=E.field_user_id 
  LEFT JOIN tblemployeeslogin E2 ON P.field_approve=E2.field_user_id 
  WHERE P.field_branch=:idbranch 
  ORDER BY P.field_product_id DESC";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute(array(":idbranch" => $branchid));
  $result = $Stmt->fetchAll();
}


$no = 1;


// var_dump($result);
// die();


?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Product Price</h3>
          <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
          <a href="?module=addproduct" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Price</a>
        </div>
        <!-- Content -->
        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Name Product</th>
                <th>Amount Price</th>
                <th>Branch</th>
                <th>Status</th> 
                <th>Submitter</th>                 
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result as $row) {
              ?>

                <tr>
                  <td><?php echo $no++ ?></td>
                  <td data-title="Trx Id"><?php echo $row["field_name_category"]; ?>|<?php echo $row["field_product_code"]; ?><br><strong><?php echo $row["field_product_name"]; ?></strong></td>
                  <td data-title="Trx Id">/<?php echo $row["field_unit"]; ?><br><strong><?php echo rupiah($row["field_price"]); ?></strong> <br><small> Harga Update <?php echo date("d F Y", strtotime($row["field_date_price"]));  ?></small></td>
                  <td><?php echo $row["field_branch_name"]; ?><br> <strong><?php echo $row["field_name_officer"]; ?></strong></td>
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
                  <td><strong><?php echo $row["Aproval"];?></td>
                  <td ata-title="Trx Id">

                    <?php
                    if ($rows["field_role"] == "ADM") {
                      echo '<a href="?module=updproduct&id=' . $row["field_product_id"] . '" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      // echo '<a href="?module=product&id='.$row["field_product_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default' . $row["field_product_id"] . '" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $row["field_product_id"] . '" class="text-white btn btn-warning "><i class="fa fa-check-square"></i> Approve </a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $row["field_product_id"] . '" class="text-white btn btn-danger "><i class="fa fa-window-close"></i> Reject </a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-approvel-price'.$row["field_product_id"].'" class="text-white btn btn-info "><i class="fa fa-info-circle"></i> Detail </a> &nbsp';
                    } elseif ($rows["field_role"]=="MGR") {
                      if ($row["field_status"]=="P") {
                        # code...
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $row["field_product_id"] . '" class="text-white btn btn-warning "><i class="fa fa-check-square"></i> Approve </a> &nbsp';
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $row["field_product_id"] . '" class="text-white btn btn-danger "><i class="fa fa-window-close"></i> Reject </a> &nbsp';                        
                      } else {
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }                  
                    }elseif ($rows["field_role"]=="AMR") {
                      
                      if ($row["field_status"]=="P") {
                        # code...
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $row["field_product_id"] . '" class="text-white btn btn-warning "><i class="fa fa-check-square"></i> Approve </a> &nbsp';
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $row["field_product_id"] . '" class="text-white btn btn-danger "><i class="fa fa-window-close"></i> Reject </a> &nbsp';
                        
                        // echo " No Complete";
                      } else {
                        # code...
                        echo '<span class="badge btn-info text-white">Complete</span>'; 
                      }                   
                    }elseif ($rows["field_role"]=="SPV") {
                      if ($row["field_status"]=="P") {
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';                       
                      } else {
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }             
                    }elseif ($rows["field_role"]=="BCO") {
                      if ($row["field_status"]=="P") {
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';  
                      } else {
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }             
                    }elseif ($rows["field_role"]=="CMS") {
                      if ($row["field_status"]=="P") {
                        echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';  
                      } else {
                        echo '<span class="badge btn-info text-white">Complete</span>';
                      }
                    }   
                    ?>

                  </td>
                </tr>

                    <!-- modal update approvel-->
                    <div class="modal fade" id="modal-approvel-price<?php echo $row["field_product_id"];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Approve Id <?php echo $row["field_product_id"]?></h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
											<div class="form-group">
											  <div class="box-header">
                          <input type="hidden" name="txt_idprice" value="<?php echo $row['field_product_id']?>">
													
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


                <!-- Modal Delete -->
                <div class="modal fade" id="modal-default<?php echo $row["field_product_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Yakin Menghapus Data</h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <center>
                                <h4>
                                  <?php
                                  echo "Nama product " . $row["field_product_name"] . " Dengan " . rupiah($row["field_price"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=product&id=<?php echo $row['field_product_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
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
                <div class="modal fade" id="modal-default-aproval<?php echo $row["field_product_id"]; ?>">
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
                                  echo "Name Product " . $row["field_product_name"] . " Dengan Harga " . rupiah($row["field_price"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=product&productid=<?php echo $row['field_product_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp YES &nbsp</a>
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
                <div class="modal fade" id="modal-default-reject<?php echo $row["field_product_id"]; ?>">
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
                                  echo "Name Product " . $row["field_product_name"] . " Dengan Harga " . rupiah($row["field_price"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=product&idproduct=<?php echo $row['field_product_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp YES &nbsp</a>
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
                <th>No</th>
                <th>Name Product</th>
                <th>Amount Price</th>
                <th>Branch</th>
                <th >Status</th> 
                <th >Approval</th>                 
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