<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../loginv2.php");
}

$Sql = "SELECT I.*,C.field_nama_customer,field_name_officer,(SELECT G.field_sell FROM tblgoldprice G WHERE G.field_date_gold=I.field_date_deposit ORDER BY field_gold_id DESC LIMIT 1) AS PriceGold 
                                          FROM tbldeposit I 
                                          JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening
                                          JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
                                          ORDER BY I.field_trx_deposit DESC";
$Stmt = $db->prepare($Sql);
//$Stmt->execute(array(":statuse"=> $s,":idtoken"=>$t));
$Stmt->execute();
$result = $Stmt->fetchAll();
$no = 1;

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
                <th>Reff</th>
                <th>Date</th>
                <th>Account</th>
                <th>Customer</th>
                <th>Price Gold</th>
                <th>Sub Total</th>
                <th>Free</th>
                <th>Total</th>
                <th>Gold</th>
                <th>Officer</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result as $row) {
              ?>

                <tr>
                  <td><strong><?php echo $row["field_no_referensi"]; ?></strong></td>
                  <!-- <strong></strong> -->
                  <td><?php echo date("d-M-Y", strtotime($row["field_date_deposit"])); ?></td>
                  <td><?php echo $row["field_rekening_deposit"]; ?></td>
                  <td><?php echo $row["field_nama_customer"]; ?></td>
                  <td><strong><?php echo rupiah($row["PriceGold"]); ?></strong></td>

                  <td><?php echo rupiah($row["field_sub_total"]); ?></td>
                  <td><?php echo rupiah($row["field_operation_fee_rp"]); ?></td>
                  <td><?php echo rupiah($row["field_total_deposit"]); ?></td>
                  <td><strong><?php echo $row["field_deposit_gold"]; ?></strong></td>
                  <td><?php echo $row["field_name_officer"]; ?></td>
                  <td>

                    <?php if ($row['field_status'] == "pending") {
                      echo '<span class="label pull-center bg-yellow"><strong>pending</strong></span>';
                    } elseif ($row['field_status'] == "cancel") {
                      echo '<span class="label pull-center bg-red"><strong>cancel</strong></span>';
                    } elseif ($row['field_status'] == "success") {
                      echo '<span class="label pull-center bg-green"><strong>success</strong></span>';
                    }
                    ?>
                  </td>

                  <td>
                    <a href="../mutasicustomerpdf.php?m=<?php echo $row['field_trx_deposit']; ?>" class="btn btn-sm btn-info"><i class="fa fa-print"></i> &nbsp</a>
                  </td>
                </tr>

              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Reff</th>
                <th>Date</th>
                <th>Account</th>
                <th>Customer</th>
                <th>Price Gold</th>
                <th>Sub Total</th>
                <th>Free</th>
                <th>Total</th>
                <th>Gold</th>
                <th>Officer</th>
                <th>Status</th>
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