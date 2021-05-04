<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if(!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

if (isset($_REQUEST['id'])) {
    $id=$_REQUEST['id'];   

    $select_stmt= $db->prepare('SELECT * FROM tbluserlogin WHERE field_user_id =:id'); //sql select query
    $select_stmt->bindParam(':id',$id);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
      if ($id==$row["field_user_id"]) {
        //echo "TRUE";
            $iduser   =$_SESSION['administrator_id'];//member_id
            $idmember =$_SESSION['administrator_login'];//id_member
            $aktifitas="DELETE AKUN ".$row["field_member_id"];
            $date     = date("Y-m-d H:s:i");
            
            $delete_stmt = $db->prepare('DELETE FROM tbluserlogin WHERE field_user_id =:id');
            $delete_stmt->bindParam(':id',$id);

            if ($delete_stmt->execute()) 
            {
                $insert=$db->prepare("INSERT INTO tbluserlog(field_aktifitas,field_member_id,field_user_id,field_waktu)VALUES(:aktifitas,:member_id,:user_id,:waktu)");
                $insert->bindParam(':aktifitas',$aktifitas);
                $insert->bindParam(':member_id',$idmember);
                $insert->bindParam(':user_id',$iduser);
                $insert->bindParam(':waktu',$date);
                $insert->execute();
                $insertMsg="Delete Successfully"; //execute query success message
                echo '<META HTTP-EQUIV="Refresh" Content="1;">';
            }
      }else{
        //echo "FALSE"; 
        echo '<META HTTP-EQUIV="Refresh" Content="1;">';
      }      


}

if ($_SESSION['rolelogin']=='ADM' OR $_SESSION['rolelogin']=='MGR' ) {
  # code...
  $Sql = 'SELECT * FROM tbluserlogin WHERE field_status_aktif!="1" ORDER BY field_user_id DESC';
  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $result = $Stmt->fetchAll();
}elseif ($_SESSION['rolelogin']=='SPV' OR $_SESSION['rolelogin']=='BCO' OR $_SESSION['rolelogin']=='CMS') {
  # code...
  $Sql = 'SELECT * FROM tbluserlogin WHERE field_status_aktif!="1" AND field_branch=:idbranch ORDER BY field_user_id DESC';
  $Stmt = $db->prepare($Sql);
  // $Stmt->execute();
  $Stmt->execute(array(":idbranch"=>$branchid));
  $result = $Stmt->fetchAll();
}


// $sqlT = "SELECT * FROM tbltrxmutasisaldo WHERE field_member_id=$trx_id_member";
// $stmtT = $db->prepare($sqlT);
// $stmtT->execute();
// $resultT = $stmtT->fetchAll();

$no=1;



?>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-edit"></i>
              <h3 class="box-title">Customer</h3>
              <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
              <a href="?module=addcustomer" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Customer</a>          
            </div>     
              <!-- Content -->
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>                              
                  <th>Name</th>                 
                  <th>Username</th>
                  <th>Token</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
             <?php 
              foreach($result as $row) {        
                ?> 
             
                 <tr>
                  <td ><?php echo $no++?></td>
                                
                  
                  <td data-title="Trx Id"><strong><?php echo $row["field_nama"];?></strong><br><?php echo $row["field_member_id"];?></td>
                  <td data-title="Trx Id"><strong><?php echo $row["field_email"]; ?> | <?php echo $row["field_handphone"];?></strong><br>
                    <?php 
                    $status=$row["field_status_aktif"];
                      if ($status=="1") {
                        
                        echo '<span class="badge btn-info text-white">Aktif</span>';
                      }elseif ($status=="2") {
                        echo '<span class="badge btn-warning text-white">Tidak Aktif</span>';
                      }elseif ($status=="0") {
                        echo '<span class="badge btn-danger text-white">Verifikasi</span>';;
                      }
                     ?></td>
                  <td ><strong><?php echo $row["field_token_otp"];?></strong><br><?php echo $row["field_token_otp"];?></td>
                  <td ata-title="Trx Id" >                   

                    <?php 
                    if ($row["field_status_aktif"]=="1") {
                      echo '<a href="?module=updcustomer&id='.$row["field_user_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      // echo '<a href="?module=product&id='.$row["produk_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_user_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';                    
                     
                    }elseif ($row["field_status_aktif"]=="2") {
                      
                      echo '<a href="?module=updcustomer&id='.$row["field_user_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';                    
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_user_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';                      
                    }elseif ($row["field_status_aktif"]=="0") {
                      echo '<a href="../activasipdf.php?m='.$row['field_member_id'].'" class="btn btn-sm btn-warning"><i class="fa fa-print"></i> &nbsp Print</a>&nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_user_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';  
                    }
                     ?>                    
                  
                  </td> 
                                      
                </tr>

                    <div class="modal fade" id="modal-default<?php echo $row["field_user_id"];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Yakin Menghapus Data</h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
                    <div class="form-group">
                    <div class="box-header"> 

                    <center>
                    <h4>              
                    <?php 
                      echo "Uername ".$row["field_nama"]." Dengan ".$row["field_email"];                      
                    ?>
                    </h4>   
                    </center> 
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                    <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                    <a href="?module=customer&id=<?php echo $row['field_user_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
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
                  <th>Name</th>                 
                  <th>Username</th>
                  <th>Token</th>
                  <th>Action</th> 
                </tr>
                </tfoot>
              </table>
                              
            </div>
        </div>
      </div>
    </div> 
    </section>


