<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../connectionuser.php");


if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}



$id = $_SESSION['administrator_id'];                               
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin WHERE field_user_id=:uid");
$select_stmt->execute(array(":uid"=>$id));  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

$s=$row['field_status_aktif'];
$t=$row['field_token_otp'];

$Sql = "SELECT DISTINCT(field_rekening),
        (SELECT field_total_saldo FROM tbltrxmutasisaldo aa WHERE aa.field_rekening = bb.field_rekening AND aa.field_status='Success' ORDER BY field_id_saldo DESC LIMIT 1) 
        AS TotalSaldo,us.field_nama,us.field_member_id,B.field_branch_name
        FROM tbltrxmutasisaldo bb 
        JOIN tbluserlogin us ON bb.field_member_id = us.field_member_id
        JOIN tblbranch B ON us.field_branch=B.field_branch_id
        ORDER BY field_id_saldo DESC";
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


              <h3 class="box-title"><a href="#" class="text-white "><i class="fa fa-windows"></i> Balance Customer</a></h3>
            </div>
       
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>                              
                  <th>ID Number</th>
                  <th>Customer</th>                 
                  <th>Account</th>
                  <th>Balance</th>
                  <th>Branch</th>
                  <th>Action</th>                   
                  
            
                </tr>
                </thead>
                <tbody>
             <?php 
              foreach($result as $row) {        
                ?> 
             
                 <tr>
                  <td ><?php echo $no++?></td>                                
                  <strong></strong>
                  <td data-title="Trx Id"><?php echo $row["field_member_id"];?></td>
                  <td data-title="Trx Id"><?php echo $row["field_nama"];?></td>
                  <td ><?php echo $row["field_rekening"];?></td>
                  <td ata-title="Trx Id" ><strong><?php echo $row["TotalSaldo"];?></strong></td> 
                  <td ata-title="Trx Id" ><?php echo $row["field_branch_name"];?>             </td>  
                  <td ata-title="Trx Id" >
                    <a href="../mutasicustomerpdf.php?m=<?php echo $row['field_member_id'];?>" class="btn btn-sm btn-warning"><i class="fa fa-print"></i> &nbsp Print</a>
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
    </section>


