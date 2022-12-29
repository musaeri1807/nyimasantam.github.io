<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}



// $id = $_SESSION['administrator_id'];                               
// $select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin WHERE field_user_id=:uid");
// $select_stmt->execute(array(":uid"=>$id));  
// $rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

// $s=$row['field_status_aktif'];
// $t=$row['field_token_otp'];

$Sql = "SELECT DISTINCT(field_rekening),(SELECT field_total_saldo FROM tbltrxmutasisaldo aa 
                                                                  WHERE aa.field_rekening = bb.field_rekening AND aa.field_status='S'
                                                                  ORDER BY field_id_saldo DESC LIMIT 1) 
            AS TotalSaldo,us.field_nama,us.field_member_id,B.field_branch_name
            FROM tbltrxmutasisaldo bb 
            JOIN tbluserlogin us ON bb.field_member_id = us.field_member_id
            JOIN tblbranch B ON us.field_branch=B.field_branch_id
            ORDER BY bb.field_id_saldo DESC";
$Stmt = $db->prepare($Sql);
//$Stmt->execute(array(":statuse"=> $s,":idtoken"=>$t));
$Stmt->execute();
$Saldo = $Stmt->fetchAll();
$no = 1;
?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Saldo Nasabah</h3>
          <form action="">
            <input type="text">
            <input type="text">
            <input type="text">
            <input type="text">
            <input type="text">

          </form>
        </div>
        <!-- Content -->

        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>ID Nasabah</th>
                <th>Nama Nasabah</th>
                <th>Account</th>
                <th>Saldo</th>
                <th>Cabang</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($Saldo as $row) {
              ?>

                <tr>
                  <td><?php echo $no++ ?></td>
                  <strong></strong>
                  <td data-title="Trx Id"><?php echo $row["field_member_id"]; ?></td>
                  <td data-title="Trx Id"><?php echo $row["field_nama"]; ?></td>
                  <td><?php echo $row["field_rekening"]; ?></td>
                  <td data-title="Trx Id"><strong><?php echo $row["TotalSaldo"]; ?></strong></td>
                  <td data-title="Trx Id"><?php echo $row["field_branch_name"]; ?> </td>
                  <td data-title="Trx Id">
                    <a href="../mutasicustomerpdf.php?m=<?php echo $row['field_member_id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-print o text-warning"></i></a>
                  </td>
                </tr>

              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>#</th>
                <th>ID Nasabah</th>
                <th>Nama Nasabah</th>
                <th>Account</th>
                <th>Saldo</th>
                <th>Cabang</th>
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