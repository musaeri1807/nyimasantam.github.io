<?php
// ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}


?>


<section class="content">
  <div class="row">
    <section class="col-lg-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Filter Periode</h3>
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
          <h3 class="box-title">Data</h3>
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
                      <a href="../export_withdraw?tanggal_dari=<?php echo $tgl_dari ?>&tanggal_sampai=<?php echo $tgl_sampai ?>" class="btn btn-sm btn-success"><i class="fa fa-download"></i> &nbsp Excel</a>
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
                    <th>Produk</th>
                    <th>Tanggal</th>
                    <th>No Reff</th>
                    <th>Rekening</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Trx_Cabang</th>
                    <th>Harga Emas</th>
                    <th>Qty</th>
                    <th>Berat</th>
                    <th>Total</th>
                    <th>Rupiah</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Aproval</th>

                  </tr>
                </thead>
                <tbody>
                  <?php

                  $sumP = 0;
                  $sumST = 0;
                  $sumFEE = 0;
                  $sumT = 0;
                  $sumGOLD = 0;

                  if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
                    echo $branchid;
                    $sqlT = "SELECT
                    WD.field_withdraw_id AS ID,
                    WD.field_trx_withdraw AS ID_TX,
                    WD.field_product AS PRODUK,
                    WD.field_berat AS BERAT,
                    WD.field_quantity AS QTY,
                    WD.field_total_berat AS TOTAL,
                    W.field_no_referensi AS REFERENSI,
                    W.field_date_withdraw AS TANGGAL,
                    W.field_rekening_withdraw AS REKENING,
                    U.field_nama AS NAMA,
                    W.field_type_withdraw AS TIPE,
                    B.field_branch_name AS Trx_CABANG,
                    E.field_name_officer AS PETUGAS,
                    W.field_gold_price AS HARGA,
                    W.field_withdraw_gold AS TARIK_EMAS,
                    W.field_rp_withdraw AS RUPIAH,
                    W.field_status AS STATUS,
                    EP.field_name_officer AS APROVAL
                    
                    FROM tblwithdrawdetail WD
                    LEFT JOIN tblwithdraw W ON WD.field_trx_withdraw=W.field_trx_withdraw
                    LEFT JOIN tblnasabah N ON W.field_rekening_withdraw=N.No_Rekening
                    LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    LEFT JOIN tblbranch B ON W.field_branch=B.field_branch_id
                    LEFT JOIN tblemployeeslogin E ON W.field_officer_id=E.field_user_id
                    LEFT JOIN tblemployeeslogin EP ON W.field_approve=EP.field_user_id
                    WHERE  date(W.field_date_withdraw) >=:tgl_dari AND date(W.field_date_withdraw) <= :tgl_sampai
                    ORDER BY WD.field_withdraw_id ASC";
                    $stmtT = $db->prepare($sqlT);
                    $stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai));
                    $resultT = $stmtT->fetchAll();
                    # code...
                    // } elseif ($_SESSION['rolelogin'] == 'SVP' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
                  } else {
                    echo $branchid;
                    $sqlT = "SELECT
                    WD.field_withdraw_id AS ID,
                    WD.field_trx_withdraw AS ID_TX,
                    WD.field_product AS PRODUK,
                    WD.field_berat AS BERAT,
                    WD.field_quantity AS QTY,
                    WD.field_total_berat AS TOTAL,
                    W.field_no_referensi AS REFERENSI,
                    W.field_date_withdraw AS TANGGAL,
                    W.field_rekening_withdraw AS REKENING,
                    U.field_nama AS NAMA,
                    W.field_type_withdraw AS TIPE,
                    B.field_branch_name AS Trx_CABANG,
                    E.field_name_officer AS PETUGAS,
                    W.field_gold_price AS HARGA,
                    W.field_withdraw_gold AS TARIK_EMAS,
                    W.field_rp_withdraw AS RUPIAH,
                    W.field_status AS STATUS,
                    EP.field_name_officer AS APROVAL
                    
                    FROM tblwithdrawdetail WD
                    LEFT JOIN tblwithdraw W ON WD.field_trx_withdraw=W.field_trx_withdraw
                    LEFT JOIN tblnasabah N ON W.field_rekening_withdraw=N.No_Rekening
                    LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    LEFT JOIN tblbranch B ON W.field_branch=B.field_branch_id
                    LEFT JOIN tblemployeeslogin E ON W.field_officer_id=E.field_user_id
                    LEFT JOIN tblemployeeslogin EP ON W.field_approve=EP.field_user_id
                    WHERE  date(I.field_date_deposit) >=:tgl_dari AND date(I.field_date_deposit) <= :tgl_sampai AND
                                      I.field_branch=:idbranch
                    ORDER BY T.field_deposit_id ASC";
                    $stmtT = $db->prepare($sqlT);
                    $stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai, ':idbranch' => $branchid));

                    $resultT = $stmtT->fetchAll();
                  }


                  foreach ($resultT as $row) {
                    // $status = $row["field_status"];
                    // if($status=="Pending"){
                    //   $status = '<span class="badge btn-danger text-white">Menunggu Pembayaran</span>';
                    //   $tindakan = '<a href="'.$row["field_id_saldo"].'" class="text-white btn btn-success btn"><i class="fa fa-credit-card"></i>  Payment</a>   &nbsp';


                    // }else if($status=="Success"){
                    //   $status = '<span class="badge btn-success text-white">Pembayaran Berhasil</span>';
                    //   $tindakan = '<a href="detail.php?trx_id='.$row["field_id_saldo"].'" class="text-white btn btn-info "><i class="fa fa-download"></i> Detail</a> &nbsp';              
                    // }
                    //     $Types = $row["field_type_saldo"];
                    //     if($Types=="200"){
                    //       $Types = '<span class="badge btn-warning text-white">Debit</span>';

                    //     }else if($Types=="100"){
                    //       $Types = '<span class="badge btn-primary text-white">Kredit</span>';

                    //     }else if($Types=="300"){
                    //       $Types = '<span class="badge btn-dark text-white">Balance</span>';

                    //     }

                  ?>

                    <tr>

                      <td><?php echo sprintf("%09s", $row['ID']); ?></td>
                      <td><?php if ($row["PRODUK"] == '0') {
                            echo "EMAS BATANGAN";
                          }; ?></td>

                      <td data-title=""><?php echo $row["TANGGAL"]; ?></td>
                      <td data-title="Status"><?php echo $row["REFERENSI"]; ?></td>
                      <td data-title="channel"><?php echo $row["REKENING"]; ?></td>
                      <td data-title="Status"><?php echo $row["NAMA"]; ?></td>

                      <?php
                      $Types = $row["TIPE"];
                      if ($Types == "201") {
                        $Types = '<span class="badge btn-warning text-white">Fisik</span>';
                      } else if ($Types == "202") {
                        $Types = '<span class="badge btn-primary text-white">Buyback</span>';
                      }
                      ?>
                      <td data-title="channel"><?php echo $Types; ?></td>
                      <td data-title="channel"><?php echo $row["Trx_CABANG"]; ?></td>
                      <td data-title="Status"><?php echo rupiah($row["HARGA"]); ?></td>
                      <td data-title="channel"><?php echo $row["QTY"]; ?></td>
                      <td data-title="channel"><?php echo $row["TOTAL"]; ?></td>
                      <td data-title="channel"><?php echo $row["TARIK_EMAS"]; ?></td>
                      <td data-title="channel"><?php echo rupiah($row["RUPIAH"]); ?></td>
                      <td data-title="channel"><?php echo $row["PETUGAS"]; ?></td>
                      <td data-title="Status"><?php if ($row["STATUS"] == "S") {
                                                echo '<span class="badge btn-success text-white">Berhasil</span>';
                                              } ?>
                      </td>
                      <td data-title="channel"><?php echo $row["APROVAL"]; ?></td>
                    </tr>

                  <?php



                    // $sumP = $sumP + $row["QTY"];
                    // $sumST = $sumST + $row["TOTAL"];
                    // $sumFEE = $sumFEE + $row["RESULT_PERSEN"];
                    // $sumT = $sumT + $row["DEPO"];
                    // $sumGOLD = $sumGOLD + $row["GOLD"];
                  } ?>
                </tbody>
                <tfoot>
                  <tr class="bg-info">
                    <td colspan="9" class="text-right"><b></b></td>
                    <td class="text-center"></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td class="text-center"><strong></strong></td>
                    <td colspan="2" class="text-left"><strong></strong></td>


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