<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

$idemploye = $_SESSION['idlogin'];
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin E JOIN tbldepartment D ON E.field_role=D.field_department_id
                                                               JOIN tblbranch B ON E.field_branch=B.field_branch_id
                                                               JOIN tblpermissions P ON E.field_role=P.role_id
                                                              WHERE E.field_user_id=:uid LIMIT 1");
$select_stmt->execute(array(":uid" => $idemploye));
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);
$permission = $rows['add'];
$branchid = $rows['field_branch'];
// echo $branchid;

?>


<section class="content">
  <div class="row">
    <section class="col-lg-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Filter Mutasi</h3>
        </div>
        <div class="box-body">
          <form method="POST" class="form-horizontal">
            <!-- <div class="row"> -->
            <div class="col-md-2">

              <div class="form-group">
                <label>Mulai Tanggal</label>
                <input autocomplete="off" type="date" value="<?php if (isset($_POST['tanggal_dari'])) {
                                                                echo $_POST['tanggal_dari'];
                                                              } else {
                                                                echo "";
                                                              } ?>" name="tanggal_dari" class="form-control datepicker2" placeholder="Mulai Tanggal" required>
              </div>

            </div>

            <div class="col-md-2">

              <div class="form-group">
                <label>Sampai Tanggal</label>
                <input autocomplete="off" type="date" value="<?php if (isset($_POST['tanggal_sampai'])) {
                                                                echo $_POST['tanggal_sampai'];
                                                              } else {
                                                                echo "";
                                                              } ?>" name="tanggal_sampai" class="form-control datepicker2" placeholder="Sampai Tanggal" required>
              </div>

            </div>

            <div class="col-md-1">

              <div class="form-group">
                <input style="margin-top: 26px" type="submit" value="SHOW" name="date" class="btn btn-sm btn-primary btn-block">
              </div>

            </div>
            <!--  </div> -->
          </form>
        </div>
      </div>

      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Data Mutasi</h3>
        </div>
        <div class="box-body">

          <?php
          if (isset($_POST['tanggal_sampai']) && isset($_POST['tanggal_dari'])) {
            $tgl_dari = $_POST['tanggal_dari'];
            $tgl_sampai = $_POST['tanggal_sampai'];
          ?>

            <div class="row">
              <div class="col-lg-6">
                <table class="table table-bordered">
                  <tr>
                    <th width="10%">Dari Tanggal</th>
                    <th width="1%">:</th>
                    <td width="10%"><?php echo $tgl_dari; ?></td>
                  </tr>
                  <tr>
                    <th>Sampai Tanggal</th>
                    <th>:</th>
                    <td><?php echo $tgl_sampai; ?></td>
                  </tr>
                  <tr>

                    <td colspan="3" class="text-left">
                      <a href="../export_mutasi?tanggal_dari=<?php echo $tgl_dari ?>&tanggal_sampai=<?php echo $tgl_sampai ?>" class="btn btn-sm btn-success"><i class="fa fa-download"></i> &nbsp Excel</a>
                    </td>
                  </tr>
                </table>

              </div>
            </div>


            <div class="table-responsive">

              <table class="table table-bordered table-striped" id="trxTerakhir">
                <!-- <table id="trxTerakhir" class="table table-bordered table-hover"> -->
                <thead>
                  <tr>
                    <th width="10">ID_Trx</th>

                    <th>No Reff</th>
                    <th>Date</th>
                    <th>Rekening</th>
                    <th>Nasabah</th>
                    <th>Trx Cabang</th>
                    <th>Types</th>
                    <th>Amount</th>
                    <th>Saldo Akhir</th>
                    <th>Jual</th>
                    <th>Buyback</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sumK = 0;
                  $sumD = 0;
                  if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
                    echo "ADMIN";
                    $sqlT = "SELECT 
                      M.field_id_saldo AS ID,
                      M.field_no_referensi AS REFERENSI,
                      M.field_tanggal_saldo AS TANGGAL,
                      M.field_time AS TIMES,
                      M.field_rekening AS REKENING,
                      U.field_nama AS NAMA,
                      M.field_time AS TIMES,
                      B.field_branch_name AS TRX_CABANG,
                      M.field_type_saldo AS TIPE,
                      G.field_sell AS HARGA_EMAS,
                      G.field_buyback AS BUYBACK,
                      M.field_kredit_saldo AS KREDIT,
                      M.field_debit_saldo AS DEBIT,
                      M.field_total_saldo AS SALDO,
                      M.field_status AS STATUS
                      FROM tbltrxmutasisaldo M JOIN tbldeposit D ON M.field_no_referensi=D.field_no_referensi
                      JOIN tblnasabah N ON N.No_Rekening=M.field_rekening
                      JOIN tbluserlogin U ON U.field_user_id=N.id_UserLogin
                      JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
                      JOIN tblbranch B ON B.field_branch_id=D.field_branch
                      WHERE  date(M.field_tanggal_saldo) >= '$tgl_dari' AND date(M.field_tanggal_saldo) <= '$tgl_sampai'
                      AND M.field_status='S'
                      ORDER BY M.field_id_saldo ASC";
                    $stmtT = $db->prepare($sqlT);
                    $stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai));
                    $resultT = $stmtT->fetchAll();
                  } else {
                    // echo "AMR" . $branchid;
                    $sqlT = "SELECT 
                      M.field_id_saldo AS ID,
                      M.field_no_referensi AS REFERENSI,
                      M.field_tanggal_saldo AS TANGGAL,
                      M.field_time AS TIMES,
                      M.field_rekening AS REKENING,
                      U.field_nama AS NAMA,
                      M.field_time AS TIMES,
                      B.field_branch_name AS TRX_CABANG,
                      M.field_type_saldo AS TIPE,
                      G.field_sell AS HARGA_EMAS,
                      G.field_buyback AS BUYBACK,
                      M.field_kredit_saldo AS KREDIT,
                      M.field_debit_saldo AS DEBIT,
                      M.field_total_saldo AS SALDO,
                      M.field_status AS STATUS
                      FROM tbltrxmutasisaldo M JOIN tbldeposit D ON M.field_no_referensi=D.field_no_referensi
                      JOIN tblnasabah N ON N.No_Rekening=M.field_rekening
                      JOIN tbluserlogin U ON U.field_user_id=N.id_UserLogin
                      JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
                      JOIN tblbranch B ON B.field_branch_id=D.field_branch
                      WHERE  date(M.field_tanggal_saldo) >=:tgl_dari AND date(M.field_tanggal_saldo) <= :tgl_sampai
                      
                      AND D.field_branch=:idbranch AND M.field_status='S' 
                      ORDER BY M.field_id_saldo ASC";
                    $stmtT = $db->prepare($sqlT);
                    $stmtT->execute(array(
                      ':tgl_dari'   => $tgl_dari,
                      ':tgl_sampai' => $tgl_sampai,
                      ':idbranch'   => $branchid
                    ));
                    $resultT = $stmtT->fetchAll();
                  }


                  foreach ($resultT as $row) {
                    $status = $row["STATUS"];
                    if ($status == "Pending") {
                      $status = '<span class="badge btn-danger text-white">Menunggu Pembayaran</span>';
                      $tindakan = '<a href="' . $row["field_id_saldo"] . '" class="text-white btn btn-success btn"><i class="fa fa-credit-card"></i>  Payment</a>   &nbsp';
                    } else if ($status == "Success") {
                      $status = '<span class="badge btn-success text-white">Pembayaran Berhasil</span>';
                      $tindakan = '<a href="detail.php?trx_id=' . $row["field_id_saldo"] . '" class="text-white btn btn-info "><i class="fa fa-download"></i> Detail</a> &nbsp';
                    }
                    $Types = $row["TIPE"];
                    if ($Types == "200") {
                      $Types = '<span class="badge btn-warning text-white">Debit</span>';
                    } else if ($Types == "100") {
                      $Types = '<span class="badge btn-primary text-white">Kredit</span>';
                    } else if ($Types == "300") {
                      $Types = '<span class="badge btn-dark text-white">Balance</span>';
                    }

                  ?>

                    <tr>

                      <td><?php echo sprintf("%09s", $row['ID']); ?></td>

                      <td data-title="waktu_bayar"><?php echo $row["REFERENSI"]; ?></td>

                      <td data-title="Status"><?php echo $row["TANGGAL"]; ?></td>
                      <td data-title="Status"><?php echo $row["REKENING"]; ?></td>
                      <td data-title="Status"><?php echo $row["NAMA"]; ?></td>
                      <td data-title="Status"><?php echo $row["TRX_CABANG"]; ?></td>
                      <td data-title="Status"><?php echo $Types; ?></td>
                      <?php

                      if ($row['KREDIT'] == "0") {
                        echo '<td data-title="Saldo">' . '<strong>' . $row['DEBIT'] . '-g' . '</strong>' . '</td>';
                      } elseif ($row['DEBIT'] == "0") {
                        echo '<td data-title="Saldo">' . '<strong>' . $row['KREDIT'] . '-g' . '</strong>' . '</td>';
                      }
                      ?>
                      <td data-title="Status"><?php echo $row['SALDO']; ?></td>
                      <td data-title="Status"><?php echo rupiah($row['HARGA_EMAS']); ?></td>
                      <td data-title="Status"><?php echo rupiah($row['BUYBACK']); ?></td>
                      <td data-title="Status"><?php if ($row["STATUS"] == "S") {
                                                echo '<span class="badge btn-success text-white">Berhasil</span>';
                                              } ?></td>

                    </tr>

                  <?php
                    $sumK = $sumK + $row["KREDIT"];
                    $sumD = $sumD + $row["DEBIT"];
                  } ?>
                </tbody>
                <tfoot>
                  <tr class="bg-info">
                    <td colspan="7" class="text-right"><b> Total</b></td>
                    <td class="text-center"><strong><?php
                                                    $SUM = $sumK - $sumD;

                                                    echo $SUM; ?>,-g</strong></td>


                  </tr>
                </tfoot>
              </table>



            </div>

          <?php
          } else {
          ?>

            <div class="alert alert-info text-center">
              Silakan Filter Terlebih Dulu.
            </div>

          <?php
          }
          ?>

        </div>
      </div>
    </section>
  </div>
</section>