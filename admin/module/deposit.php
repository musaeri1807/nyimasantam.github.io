<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../loginv2.php");
}

if(isset($_REQUEST['iddeposit'])){

  $iddeposit=$_REQUEST['iddeposit'];  
  $typeaprove="C";
  $id;  

	if (empty($id)) {
		$errorMsg="Silakan Masukan Id ";
	}else	{
		try	{
			if(!isset($errorMsg))	{        

        $update_stmt=$db->prepare('UPDATE tbldeposit SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_trx_deposit=:id'); //sql insert query					
				       
				$update_stmt->bindParam(':typeaprove',$typeaprove);
        $update_stmt->bindParam(':idaprovel',$id);
        $update_stmt->bindParam(':id',$iddeposit);
					
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

if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  $Sql = "SELECT I.*,C.field_nama_customer,E.field_name_officer,E2.field_name_officer AS Approval,field_branch_name,(SELECT G.field_sell FROM tblgoldprice G WHERE G.field_date_gold=I.field_date_deposit ORDER BY field_gold_id DESC LIMIT 1) AS PriceGold 
  FROM tbldeposit I 
  LEFT JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening
  LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
  LEFT JOIN tblemployeeslogin E2 ON I.field_approve=E2.field_user_id
  ORDER BY I.field_trx_deposit DESC";

  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $result = $Stmt->fetchAll();
} else {
  
  $Sql = "SELECT I.*,C.field_nama_customer,E.field_name_officer,E2.field_name_officer AS Approval,field_branch_name,(SELECT G.field_sell FROM tblgoldprice G WHERE G.field_date_gold=I.field_date_deposit ORDER BY field_gold_id DESC LIMIT 1) AS PriceGold 
  FROM tbldeposit I 
  LEFT JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening
  LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
  LEFT JOIN tblemployeeslogin E2 ON I.field_approve=E2.field_user_id
  WHERE I.field_branch=:idbranch
  ORDER BY I.field_trx_deposit DESC";
  
  $Stmt = $db->prepare($Sql);
  $Stmt->execute(array(":idbranch" => $branchid));
  $result = $Stmt->fetchAll();
}

$no = 1;


// massege
if(isset($errorMsg)){
  echo'<div class="alert alert-danger"><strong>WRONG !'.$errorMsg.'</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=deposit">';
  }
}
if(isset($Msg)){
  echo'<div class="alert alert-success"><strong>SUCCESS !'.$Msg.'</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=deposit">';
  }

}

?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Deposit Customer</h3>
          <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
          <a href="?module=adddeposit" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Transaction</a>
        </div>
        <!-- Content -->
        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>                
                <th></th>
                <th>Reff</th>
                <th>Account Customer</th>
                <!-- <th>Customer</th> -->
                <!-- <th>Price Gold</th> -->
                <th>Sub Total</th>
                <th>Free</th>
                <th>Total</th>
                <th>Gold</th>
                <th>Officer</th>
                <th>Status</th>
                <th>Submitter</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach (array_slice($result,0,10) as $row) {
              ?>

                <tr>
                  <td><?php echo '<a href="../mutasicustomerpdf.php?m='.$row["field_trx_deposit"].' "class="text-white btn btn-default"><i class="fa fa-print"></i></a>';?>

                  </td>
                  <td><strong><?php echo $row["field_no_referensi"]; ?></strong> <br><?php echo date("d-M-Y", strtotime($row["field_date_deposit"])); ?></td>                  
                  <!-- <td><?php echo date("d-M-Y", strtotime($row["field_date_deposit"])); ?></td> -->
                  <td><?php echo $row["field_rekening_deposit"]; ?><br><?php echo $row["field_nama_customer"]; ?></td>
                  <!-- <td><?php echo $row["field_nama_customer"]; ?></td> -->
                  <!-- <td><strong><?php echo rupiah($row["PriceGold"]); ?></strong></td> -->
                  
                  <td><?php echo rupiah($row["field_sub_total"]); ?></td>
                  <td><?php echo rupiah($row["field_operation_fee_rp"]); ?></td>
                  <td><?php echo rupiah($row["field_total_deposit"]); ?></td>
                  <td><strong><?php echo $row["field_deposit_gold"]; ?></strong></td>
                  <td><?php echo $row["field_name_officer"]; ?> <br><?php echo $row["field_branch_name"]; ?> </td>
                  <td>
                    
                    <?php 
                    // echo $row["Approval"];
                    if ($row['field_status'] == "P") {
                      echo '<span class="label pull-center bg-yellow"><strong>pending</strong></span>';
                    } elseif ($row['field_status'] == "C") {
                      echo '<span class="label pull-center bg-red"><strong>cancel</strong></span>';
                    } elseif ($row['field_status'] == "S") {
                      echo '<span class="label pull-center bg-green"><strong>success</strong></span>';
                    }
                    ?>
                  </td>
                  <td><strong><?php echo $row["Approval"]; ?></strong></td>

                  <td>
                    <?php
                    if ($row['field_status'] == "P") {
                      echo '<span class="label pull-center bg-yellow"><strong>pending</strong></span>';
                    } elseif ($row['field_status'] == "C") {
                      // echo '<span class="label pull-center bg-red"><strong>cancel</strong></span>';
                    } elseif ($row['field_status'] == "S") {
                      // echo '<span class="label pull-center bg-green"><strong>success</strong></span>';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-cancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-danger "><i class="fa fa-window-close"></i> Cancel </a> &nbsp';
                    }
                    
                    
                    
                    ?>
                  </td>
                </tr>

                <!-- Modal CANCEL -->
                <div class="modal fade" id="modal-default-cancel<?php echo $row["field_trx_deposit"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h3 class="modal-title">Anda Yakin Untuk Cancel</h3>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">                            
                              <center>
                                <h4>
                                  <?php
                                  // echo 'Harga Jual '.rupiah($row["field_buyback"]).'<br>'.'Harga Beli '.rupiah($row["field_sell"]);  
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=deposit&iddeposit=<?php echo $row['field_trx_deposit']; ?>" type="submit" class="text-white btn btn-success">&nbsp YES &nbsp</a>
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
                <th></th>
                <th>Reff</th>
                <th>Account Customer</th>
                <!-- <th></th> -->
                <!-- <th>Price Gold</th> -->
                <th>Sub Total</th>
                <th>Free</th>
                <th>Total</th>
                <th>Gold</th>
                <th>Officer</th>
                <th>Status</th>
                <th >Submitter</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- Content -->
      </div>
    </div>
  </div>
</section>