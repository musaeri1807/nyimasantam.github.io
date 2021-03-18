<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if(!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}


if (isset($_REQUEST['token'])) {
 
    $Token=$_REQUEST['token'];
    $status=0;
    if (empty($Token)) {
      $errorMsg="Silakan Masukkan Token";    
    }elseif (strlen(is_numeric($Token))==0) {
      $errorMsg="Silakan Masukkan Token Yang Sesuai";
    }else{
    try {
          $select_stmt= $db->prepare("SELECT * FROM tbluserlogin u JOIN tblbranch b ON u.field_branch=b.field_branch_id WHERE field_status_aktif=:statuse AND field_token_otp =:idtoken ORDER BY field_user_id DESC "); //sql select query
          $select_stmt->bindParam(':idtoken',$Token);
          $select_stmt->bindParam(':statuse',$status);
          $select_stmt->execute();
          $data =$select_stmt->fetch(PDO::FETCH_ASSOC);
          $Num  =$select_stmt->rowCount();
               

          //echo $Num;
          if ($data['field_token_otp']!==$Token) {
            $errorMsg="Token Belum Sesuai";
          }elseif ($data['field_status_aktif']!==$status) {
            $errorMsg="Status Tidak Sesuai";
          }elseif (!isset($errorMsg)) {
                if ($Num==1) {
                  //echo "1";


                      $no                     = 1;
                      $thn                    = substr(date('Y'),-2);
                      $bln                    = date("m");
                      $branch                 = substr($data["field_member_id"], 0,10);
                      $code                   = $data["field_account_numbers"];                                        
                      $char                   = $code.$thn.$bln;
                      $nomor                  = $char.sprintf("%04s",$no);                     


                      $select_stmt=$db->prepare('SELECT field_rekening FROM tblcustomer WHERE field_rekening=:unomor'); //sql select query
                      $select_stmt->execute(array(':unomor'=>$nomor)); //execute query with bind parameter
                      $row=$select_stmt->fetch(PDO::FETCH_ASSOC);   
                      $DataNum=$select_stmt->rowCount(); 

                      $stmt_rek               = $db->prepare("SELECT field_branch,field_rekening FROM tblcustomer WHERE field_branch=:ubranch ORDER BY field_customer_id DESC LIMIT 1");
                      $stmt_rek->execute(array(':ubranch'=>$branch));
                      $rows_rek               = $stmt_rek->fetch(PDO::FETCH_ASSOC);
                      $noseri                 = $rows_rek['field_rekening'];

                      
                      
                      if ($DataNum==0) {
                              $no     = 1;                  
                              $thn    = date('Y');                   
                              $thn    = substr( $thn,-2);
                              $bln    = date('m');
                              $code   = $data["field_account_numbers"];                 
                              $char   = $code.$thn.$bln;
                              $norek  = $char.sprintf("%04s",$no);
                              $norekening=$norek;
                      } else {
                              $noseri = $rows_rek['field_rekening'];                 
                              $noUrut = substr($noseri, 6);               
                              $no     = $noUrut+1;                
                              $thn    = date('Y');
                              $thn    = substr( $thn,-2);
                              $bln    = date('m');
                              $code   = $data["field_account_numbers"]; 
                              $char   = $code.$thn.$bln;
                              $norek  = $char.sprintf("%04s",$no);
                              $norekening=$norek;
                      }



                      $member_id    = $data['field_member_id'];
                      $nama_lg      = $data["field_nama"];
                      $handphone    = $data["field_handphone"];
                      $date     = date('Y-m-d');
                      $time     = date('H:i:s');
                      // echo $norekening;
                      // die();

                      $update_stmt=$db->prepare("UPDATE tbluserlogin SET field_status_aktif='1' WHERE field_token_otp=:token AND field_status_aktif='0'");         
                      $update_stmt->execute(array(':token'=>$Token));

                      $select_stmt=$db->prepare("SELECT * FROM tbluserlogin WHERE field_token_otp=:token AND field_status_aktif='1'"); //sql select query
                      $select_stmt->execute(array(':token'=>$Token)); //execute query with bind parameter
                      $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
                      //Jika Status Rekening N akan di ubah menjadi Y dan Insert Rekening Ke tblcustomer
                      if ($row['field_rekening_status']=='N') {
                          //Update Status Rekening Menjadi Y
                          $update_stmt=$db->prepare("UPDATE tbluserlogin SET field_rekening_status='Y' WHERE field_token_otp=:token AND field_status_aktif='1'");        
                          $update_stmt->execute(array(':token'=>$Token));
                          //Insert tblcustomer
                          $querynasabah=$db->prepare("INSERT INTO tblcustomer (field_branch,field_member_id,field_rekening,field_nama,field_handphone) 
                                                      VALUES (:ubranch,:id_member,:rek,:aman,:hp)");
                          $querynasabah->execute(array(':ubranch'=>$branch,':id_member'=>$member_id,':rek'=>$norekening,':aman'=>$nama_lg,':hp'=>$handphone));
                        }
                        if ($row['field_status_aktif']==1 AND $row['field_token_otp']=$Token) {     
                        //noReff
                        $sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                        $order = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($order['field_no_referensi']==""){         
                          $no=1;                  
                          $thn = date('Y');                 
                          $thn = substr( $thn,-2);
                          $reff = "Reff";                  
                          $char = $thn.$reff;
                          $noReff =$char.sprintf("%09s",$no);
                        }else{          
                          $noreff = $order['field_no_referensi'];         
                          $noUrut = substr($noreff, 6);               
                          $no=$noUrut+1;                
                          $thn = date('Y');
                          $thn = substr( $thn,-2);
                          $reff = "Reff"; 
                          $char = $thn.$reff;
                          $noReff =$char.sprintf("%09s",$no); 
                        }
                        

                        $querysaldo=$db->prepare("INSERT INTO tbltrxmutasisaldo 
                                    (field_member_id,field_no_referensi,field_rekening,field_tanggal_saldo,field_time,field_comments) VALUES
                                    (:id_member,:reff,:rek,:tgl,:timee,:comment)");
                              $querysaldo->execute(array( ':id_member'=>$member_id,
                                          ':reff'   =>$noReff,
                                          ':rek'    =>$norekening,
                                          ':tgl'    =>$date,
                                          ':timee'  =>$time,
                                          ':comment'  =>"Balance"
                                          ));//
                      $insertMsg = "Token! Valid";
                      echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://'.$_SERVER["SERVER_NAME"].'/Login-Register-PHP-PDO/administrator/dashboard.php?module=activation">';
                    }

                }else{
                  echo "2";
                }
                     
          }
          
      
    } catch(PDOException $e) {
      echo $e->getMessage();
    }
    }
}

//delete

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
                echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard?module=customer">';
            }
      }else{
        //echo "FALSE"; 
        echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard?module=customer">';
      }      


}

//delete

$id = $_SESSION['idlogin'];                               
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin WHERE field_user_id=:uid");
$select_stmt->execute(array(":uid"=>$id));  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

$s=$row['field_status_aktif'];
$t=$row['field_token_otp'];

$Sql = 'SELECT * FROM tblcustomer C JOIN tbluserlogin U ON C.field_member_id=U.field_member_id ORDER BY field_customer_id DESC LIMIT 10';
$Stmt = $db->prepare($Sql);
//$Stmt->execute(array(":statuse"=> $s,":idtoken"=>$t));
$Stmt->execute();
$result = $Stmt->fetchAll();



$no=1;



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
            <div class="">
            <div class="box-header-center">
              <br>
              <br>
              <br>
              <center>
              <form method="post" class="form-horizontal"> 
   
              <div class="form-group">
              <label class="col-sm-4 control-label"></label>
              <div class="row">
              <div class="col-sm-2">
              <input type="text" name="token" class="form-control" placeholder="Masukkan Token" />
              </div>        
              <div class="col-sm-1">
            
              <input type="submit"  name="btn_activ" class="btn btn-success " value="Activ">
              </div>
              </div>
              </div>
     
          
            </form>
              </center>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>                              
                  <th>Name</th>                 
                  <th>ID Number</th>
                  <th>Account</th>
                  <th>Action</th>                   
                  
            
                </tr>
                </thead>
                <tbody>
             <?php 
              foreach($result as $row) {        
                ?> 
             
                 <tr>
                  <td ><?php echo $no++?><br><img src="../uploads/<?php echo $row['field_photo'] ?>" width="30" height="30" class="img-circle" alt="User Image" ></td>
                                
                  
                  <td data-title="Trx Id"><strong><?php echo $row["field_nama"];?></strong><br><?php echo $row["field_member_id"];?></td>
                  <td data-title="Trx Id"><strong><?php echo $row["field_ktp"]; ?> | <?php echo $row["field_handphone"];?></strong><br>
                    <?php 
                    $status=$row["field_document"];
                      if ($status=="N") {
                        
                        echo '<span class="badge btn-warning text-white">Unverifikasi</span>';
                      }elseif ($status=="Y") {
                        echo '<span class="badge btn-success text-white">Terverifikasi</span>';
                      }
                     ?> 
                     <strong><?php echo $row["field_ktp"];?></strong></td>
                  <td ><strong><?php echo $row["field_rekening"];?></strong><br>
                      <?php 
                    $status=$row["field_status"];
                      if ($status=="A") {                        
                        echo '<span class="badge btn-info text-white">A</span>';
                      }elseif ($status=="B") {
                        echo '<span class="badge btn-danger text-white">B</span>';
                      }
                     ?>
                  </td>
                  <td ata-title="Trx Id" >                   

                    <?php 
                    if ($row["field_document"]=="N") {
                      echo '<a href="?module=updcustomer&id='.$row["field_customer_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      // echo '<a href="?module=product&id='.$row["produk_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_customer_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';                    
                     
                    }elseif ($row["field_document"]=="Y") {
                      
                      echo '<a href="?module=updcustomer&id='.$row["field_customer_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';                    
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_customer_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';                      
                    }elseif ($row["field_document"]=="0") {
                      echo '<a href="?module=updcustomer&id='.$row["field_customer_id"].'" class="text-white btn btn-primary "><i class="fa fa-play "></i></a>&nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_customer_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';  
                    }
                     ?>                    
                  
                  </td> 
                                      
                </tr>

              <?php } ?>
                </tbody>
         <!--        <tfoot>
                <tr>
                  <th>Trx</th>
                  <th>Rendering engine</th>
                  <th>Browser</th>
                  <th>Platform(s)</th>
                  <th>Engine version</th>
                  <th>CSS grade</th>
                </tr>
                </tfoot> -->
              </table>
                              
            </div>
            <!-- /.box-body -->
          </div>

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- div ikut atas -->
    </div> 
    </section>


