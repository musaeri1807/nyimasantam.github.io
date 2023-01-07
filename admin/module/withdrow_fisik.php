<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../loginv2.php");
}

//noReff
$sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order['field_no_referensi'] == "") {
  $no = 1;
  $thn = date('Y');
  $thn = substr($thn, -2);
  $reff = "Reff";
  $char = $thn . $reff;
  $noReff = $char . sprintf("%09s", $no);
} else {
  $noreff = $order['field_no_referensi'];
  $noUrut = substr($noreff, 6);
  $no = $noUrut + 1;
  $thn = date('Y');
  $thn = substr($thn, -2);
  $reff = "Reff";
  $char = $thn . $reff;
  $noReff = $char . sprintf("%09s", $no);
}


$query        = "SELECT * FROM tblgoldprice ORDER BY field_gold_id  DESC LIMIT 1 ";
$Gold         = $db->prepare($query);
$Gold->execute(array(":datenow" => $date));
$ResultGold   = $Gold->fetch(PDO::FETCH_ASSOC);
$goldprice    = $ResultGold['field_sell'];



// echo $goldprice;
// die();

if (isset($_REQUEST['InsertData'])) {

  $memberid                 = $_REQUEST['txt_memberid'];
  $field_no_referensi       = $noReff;
  $field_date_withdraw      = date('Y-m-d');
  $time                     = date('H:i:s');
  $field_rekening_withdraw  = $_POST['txt_rekening'];
  $field_type_withdraw      = $_POST['txt_select'];
  $field_branch             = $branchid;
  $field_officer_id         = $rows['field_user_id'];
  $field_gold_price         = $_POST['txt_pricegold'];
  $saldo                    = $_POST['txt_saldo'];
  $field_withdraw_gold      = $_POST['txt_total'];
  $field_rp_withdraw        = $field_withdraw_gold * $goldprice;

  $transaksi_produk         = $_POST['transaksi_produk'];
  $transaksi_harga          = $_POST['transaksi_harga'];
  $transaksi_jumlah         = $_POST['transaksi_jumlah'];
  $transaksi_total          = $_POST['transaksi_total'];


  if (empty($memberid)) {
    $errorMsg             = "Member ID Belum Ada";
  } else if (empty($field_gold_price)) {
    $errorMsg             = "Harga Emas Belum Update";
  } else if ($field_gold_price == 0) {
    $errorMsg             = "Harga Emas Belum Update";
  } else if ($saldo < $field_withdraw_gold) {
    $errorMsg             = "Saldo Anda Kurang";
  } else {
    try {
      $query2         = "SELECT field_status FROM tbltrxmutasisaldo WHERE field_rekening =:rekening ORDER BY field_id_saldo DESC LIMIT 1";
      $select2        = $db->prepare($query2);
      $select2->execute(array(':rekening' => $field_rekening_withdraw));
      $result2        = $select2->fetch(PDO::FETCH_ASSOC);
      // echo "SELECT DATA SALDO";
      if ($result2['field_status'] !== "P") {
        $query        = "SELECT * FROM tbltrxmutasisaldo WHERE field_rekening =:rekening  AND field_status='S' ORDER BY field_id_saldo DESC LIMIT 1";
        $select       = $db->prepare($query);
        $select->execute(array(':rekening' => $field_rekening_withdraw));
        $result       = $select->fetch(PDO::FETCH_ASSOC);
        $saldoAwal    = $result['field_total_saldo'];
        $saldoAkhir   = $saldoAwal - $field_withdraw_gold;
        $data         = $select->rowCount();

        // echo $data    = $select2->rowCount();
        if ($data = 1) { //memastikan rekening hanya satu yang ter insert
          # code...
          $insert = $db->prepare('INSERT INTO tblwithdraw (
                field_no_referensi,
                field_date_withdraw,
                field_rekening_withdraw,
                field_type_withdraw,
                field_branch,
                field_officer_id,
                field_gold_price,
                
                field_withdraw_gold,
                field_rp_withdraw,
            
                field_status,
                field_approve) 
              VALUES(   
                :no_referensi,
                :date_withdraw,
                :rekening_withdraw,
                :type_withdraw,
                :branch,
                :officer_id,
                :gold_price,

                :withdraw_gold,
                :rp_withdraw,

                :ustatus,
                :approval)');

          $insert->execute(array(
            ':no_referensi'        => $field_no_referensi,
            ':date_withdraw'       => $field_date_withdraw,
            ':rekening_withdraw'   => $field_rekening_withdraw,
            ':type_withdraw'       => $field_type_withdraw,
            ':branch'              => $field_branch,
            ':officer_id'          => $field_officer_id,
            ':gold_price'          => $field_gold_price,
            ':withdraw_gold'       => $field_withdraw_gold,
            ':rp_withdraw'         => $field_rp_withdraw,
            ':ustatus'             => "S",
            ':approval'            => $field_officer_id
          ));

          $id = $db->lastinsertid();
          if ($id) {
            $jumlah_pembelian = count($transaksi_produk);
            for ($a = 0; $a < $jumlah_pembelian; $a++) {

              $t_produk   = $transaksi_produk[$a];
              $t_harga    = $transaksi_harga[$a];
              $t_jumlah   = $transaksi_jumlah[$a];
              $t_total    = $transaksi_total[$a];

              $insert = $db->prepare('INSERT INTO tblwithdrawdetail( 
                                                              field_trx_withdraw,
                                                              field_product,
                                                              field_berat,
                                                              field_quantity,
                                                              field_total_berat) 
                                                      VALUES( :trx_deposit,
                                                              :product,
                                                              :price_product,
                                                              :quantity,
                                                              :total_price)');

              $insert->execute(array(
                ':trx_deposit'        => $id,
                ':product'            => $t_produk,
                ':price_product'      => $t_harga,
                ':quantity'           => $t_jumlah,
                ':total_price'        => $t_total
              ));
            } //tutup

          } else {
            $errorMsg = "id Deposit Transaksi tidak ditemukan";
          } //tututp             

          $in = $db->prepare('INSERT INTO tbltrxmutasisaldo 
                              (
              field_trx_id,
              field_member_id,
              field_no_referensi,
              field_rekening,
              field_tanggal_saldo,
              field_time,
              field_type_saldo,
              field_debit_saldo,
              field_total_saldo,
              field_status) 
                        VALUES 
              (
              :trx_id,   
              :memberid,  
              :no_referensi,
              :rekening,
              :tanggal_saldo,    
              :times,
              :type_saldo,
              :debit_saldo,
              :total_saldo,
              :status)');
          $in->execute(array(
            ':trx_id'             => $id,
            ':memberid'           => $memberid,
            ':no_referensi'       => $field_no_referensi,
            ':rekening'           => $field_rekening_withdraw,
            ':tanggal_saldo'      => $field_date_withdraw,
            ':times'              => $time,
            ':type_saldo'         => 200,
            ':debit_saldo'        => $field_withdraw_gold,
            ':total_saldo'        => $saldoAkhir,
            ':status'             => "S"
          ));
          $Msg      = " Transaction Saldo Successfully"; //execute query success message
        } else {
          $errorMsg = "Rekening lebih dari Satu";
        }
      } else {
        $errorMsg     = "Transaksi Sebelumnya Masih Pending";
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}

$Stmt = $db->prepare("SELECT DISTINCT(field_rekening),(SELECT S1.field_total_saldo FROM tbltrxmutasisaldo S1 WHERE S1.field_rekening = S2.field_rekening AND S1.field_status='S' ORDER BY S1.field_id_saldo DESC LIMIT 1)  
AS SALDO,U.field_nama AS NAMA,U.field_member_id AS MEMBERID,B.field_branch_name AS BRANCH, U.field_user_id AS ID 
            FROM tbltrxmutasisaldo S2 
            JOIN tbluserlogin U ON S2.field_member_id = U.field_member_id
            JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
            JOIN tblbranch B ON U.field_branch=B.field_branch_id
            ORDER BY S2.field_id_saldo DESC");
$Stmt->execute();
$DataNasabah = $Stmt->fetchAll();



// if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {

//   $Sql    = "SELECT P.*,B.field_branch_name FROM tblproduct P 
//   LEFT JOIN tblbranch B
//   ON P.field_branch=B.field_branch_id 
//   WHERE field_status='A'
//   ORDER BY P.field_product_id DESC ";

//   $Stmt = $db->prepare($Sql);
//   $Stmt->execute();
//   $result = $Stmt->fetchAll();
// } else {

//   $Sql    = "SELECT P.*,B.field_branch_name FROM tblproduct P 
//   LEFT JOIN tblbranch B
//   ON P.field_branch=B.field_branch_id 
//   WHERE field_status='A'
//   AND P.field_branch=:idbranch
//   ORDER BY P.field_product_id DESC ";


//   $Stmt = $db->prepare($Sql);
//   $Stmt->execute(array(":idbranch" => $branchid));
//   $result = $Stmt->fetchAll();
// }

$Sql    = "SELECT * FROM tblgoldbar ";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();



?>



<!-- Main content -->
<section class="content">
  <!-- Content -->
  <?php
  // massege
  if (isset($errorMsg)) {
    echo '<div class            = "alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
    //echo '<META HTTP-EQUIV="Refresh" Content="1">';
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
      echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=withdrowsfisik">';
    } else {
      echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=' . $domain . '/admin/dashboard?module=withdrowsfisikadddeposit">';
    }
  }
  if (isset($Msg)) {
    echo '<div class            = "alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
    //echo '<META HTTP-EQUIV="Refresh" Content="1">';
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
      echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=withdrowsfisik">';
    } else {
      echo '<META HTTP-EQUIV    = "Refresh" Content="3; URL=' . $domain . '/admin/dashboard?module=withdrowsfisik">';
    }
  }
  ?>
  <div class="row">
    <form name="ftrx" method="POST" class="form-horizontal" onSubmit="return cek(this)">
      <div class="col-md-3">
        <div class="box box-primary">

          <div class="box-header with-border">
            <!-- <h3 class="box-title"></h3> -->
            <!-- <a href="" class="btn btn-success btn-block margin-bottom">Search Product</a> -->
            <button style="margin-top: 27px" type="button" class="btn btn-success btn-block margin-bottom" data-toggle="modal" data-target="#cariProduk">
              <i class="fa fa-search"></i> &nbsp Cari Emas
            </button>
            <div class="box-tools">
              <!-- Modal -->
              <div class="modal fade" id="cariProduk" tabindex="-1" role="dialog" aria-labelledby="cariProdukLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      Pilih Produk
                    </div>
                    <div class="modal-body">


                      <div class="table-responsive">
                        <!-- <table class="table table-bordered table-striped table-hover" id="table-datatable-produk"> -->
                        <table class="table table-bordered table-striped table-hover" id="trxSemua">
                          <thead>
                            <tr>
                              <th class="text-center">No</th>
                              <th>Nama</th>
                              <th>Berat</th>
                              <th class="text-center">Status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $no = 1;
                            foreach ($result as $rows) {
                            ?>
                              <tr>
                                <td width="1%" class="text-center"><?php echo $no++; ?></td>
                                <td width="1%"><?php echo $rows['Name']; ?></td>
                                <td width="1%"><?php echo $rows['Berat']; ?></td>

                                <td width="1%">

                                  <?php

                                  if ($rows["Status"] == "Y") {
                                    echo '<span class="badge btn-success text-white">Ready</span>';
                                  } elseif ($rows["field_status"] == "N") {
                                    echo '<span class="badge btn-info text-white">Cancel</span>';
                                  }
                                  ?>

                                </td>
                                <td width="1%">

                                  <input type="hidden" id="kode_<?php echo $rows['id']; ?>" value="<?php echo $rows['Berat']; ?>">
                                  <input type="hidden" id="nama_<?php echo $rows['id']; ?>" value="<?php echo $rows['Name']; ?>">
                                  <input type="hidden" id="harga_<?php echo $rows['id']; ?>" value="<?php echo $rows['Berat']; ?>">
                                  <button type="button" class="btn btn-warning modal-pilih-produk" id="<?php echo $rows['id']; ?>" data-dismiss="modal">Pilih</button>

                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <!-- modal -->
            </div>
          </div>
          <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
              <li><a><input type="hidden" class="form-control" id="tambahkan_id"></a></li>
              <li><a><input type="text" class="form-control" placeholder="Kode Product" id="tambahkan_kode" readonly></a></li>
              <li><a><input type="text" class="form-control" placeholder="Name Product" id="tambahkan_nama" readonly></a></li>
              <li><a><input type="text" name="" class="form-control" placeholder="Berat" id="tambahkan_harga" readonly></a></li>
              <li><a><input type="number" name="" class="form-control" placeholder="Qty" id="tambahkan_jumlah"></a></li>
              <li><a><input type="text" name="" class="form-control" placeholder="Total" id="tambahkan_total" readonly></a></li>
            </ul>
          </div>
          <!-- /.box-body -->
        </div>
        <a href="#" class="btn btn-primary btn-block margin-bottom" id="tombol-tambahkan-emas">Tambah </a>
        <!-- /.box -->
      </div>
      <!-- /.col transaksi-->
      <div class="col-md-9">

        <div class="box box-primary">
          <div class="box-header with-border">
            <!-- title row -->
            <div class="row">
              <div class="col-xs-12">
                <h3 class="page-header">
                  <!--  <i class="fa fa-globe"></i> AdminLTE, Inc. -->
                  <div class="row">

                    <div class="col-xs-3">
                      <select class="form-control" name="txt_select" required="required" hidden>
                        <option value="201">Cetak Fisik</option>
                      </select>
                      <input type="text" name="txt_saldo" id="add_saldo" class="form-control" placeholder="Saldo" readonly>
                    </div>
                    <div class="col-xs-3">
                      <input type="text" id="add_id" required="required" class="form-control" placeholder="IdCustomer" readonly>
                      <input type="text" name="txt_rekening" required="required" id="add_account" class="form-control" placeholder="Rekening" readonly>
                    </div>
                    <div class="col-xs-3">
                      <input type="text" name="txt_memberid" required="required" id="add_memberid" class="form-control" placeholder="member_id" readonly>
                      <input type="text" name="txt_customer" required="required" id="add_customer" class="form-control" placeholder="Customer" readonly>
                    </div>
                    <div class="col-xs-3">
                      <!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                      <i class="fa fa-users"></i> Customer
                      </button> -->
                      <button style="margin-right: 5px;" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#cariCustomer">
                        <i class="fa fa-search"></i> &nbsp Cari Nasabah
                      </button>
                    </div>

                  </div>
                  <!-- <small class="pull-right">Date: 2/10/2014</small> -->
                </h3>
              </div>
              <!-- /.col -->
            </div>
            <!-- info row -->
            <!-- Modal Customer-->
            <div class="modal fade" id="cariCustomer" tabindex="-1" role="dialog" aria-labelledby="cariCustomerLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <center>
                      <h5>
                        <i class="fa fa-users"></i>
                        Pilih Nasabah
                      </h5>
                    </center>
                  </div>
                  <div class="modal-body">


                    <div class="table-responsive">
                      <!-- <table class="table table-bordered table-striped table-hover" id="table-datatable-produk"> -->
                      <table class="table table-bordered table-striped table-hover" id="trxSemua2">
                        <thead>
                          <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Rekening</th>
                            <th class="text-center">Nasabah</th>

                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = 1;
                          foreach ($DataNasabah as $Nasabah) {
                          ?>
                            <tr>
                              <td width="1%" class="text-center"><?php echo $no++; ?></td>
                              <td width="20%"><?php echo $Nasabah['field_rekening']; ?></td>
                              <td width="20%"><?php echo $Nasabah['NAMA']; ?> </td>
                              <td width="20%"><?php echo $Nasabah['SALDO']; ?> </td>
                              <td width="1%">
                                <input type="number" id="member_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['MEMBERID']; ?>">
                                <input type="number" id="account_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['field_rekening']; ?>">
                                <input type="text" id="customer_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['NAMA']; ?>">
                                <input type="text" id="saldo_<?php echo $Nasabah['ID']; ?>" value="<?php echo $Nasabah['SALDO']; ?>">
                                <button type="button" class="btn btn-info modal-select-customer" id="<?php echo $Nasabah['ID']; ?>" data-dismiss="modal">Pilih</button>

                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- modal Customer -->

            <!-- Table row -->
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table-pembelian">
                  <thead>
                    <tr>
                      <th>Kode</th>
                      <th>Product</th>
                      <th style="text-align: center;">Berat</th>
                      <th style="text-align: center;">Qty</th>
                      <th style="text-align: center;">Total</th>
                      <th width="1%" style="text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <td><input type="text" name="txt_contoh" value="add Data"></td>
                    <td><input type="text" name="txt_contoh2"></td>
                    <td style="text-align: center;"><input type="text" name=""></td>
                    <td style="text-align: center;"><input type="text" name=""></td>
                    <td style="text-align: center;"><input type="text" name=""></td>
                    <td width="1%" style="text-align: center;"><input type="text" name=""></td> -->
                  </tbody>
                  <tfoot>
                    <tr class="bg-info">
                      <td style="text-align: right;" colspan="2"><b>Total</b></td>
                      <td style="text-align: center;"><span class="pembelian_harga" id="0">0,-</span></td>
                      <td style="text-align: center;"><span class="pembelian_jumlah" id="0">0</span></td>
                      <td style="text-align: center;"><span class="pembelian_total" id="0">0,-</span></td>
                      <td style="text-align: center;"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- /.row -->

            <div class="row">
              <!-- accepted payments column -->
              <div class="col-xs-6">
                <p class="lead">Gold Price <?php echo date('d/m/Y'); ?></p>

                <?php
                if ($ResultGold['field_date_gold'] == $date) {
                  # code...
                  if ($ResultGold['field_status'] == "P") {
                    # code...
                    // echo "PENDING ";
                    echo '<div class= "alert alert-warning"><strong>Harga Sudah Update Tapi Masih Menunggu Approved</strong></div>';
                    $goldprice = 0;
                  } else {
                    # code...
                    echo '<div class= "alert alert-success"><strong> Harga Sudah Update</strong></div>';
                    $goldprice;
                  }
                } else {
                  # code...
                  $goldprice = 0;
                  echo '<div class= "alert alert-danger"><strong>Harga Hari ini Belum Update</strong></div>';
                }
                ?>
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                  <input type="hidden" name="txt_pricegold" value="<?php echo $goldprice; ?>">
                  <span class="goldprice" id="<?php echo $goldprice; ?>"><?php echo rupiah($goldprice); ?></span>
                </p>

              </div>
              <!-- /.col -->
              <div class="col-xs-6">
                <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th style="width:50%">Total:</th>
                      <td>
                        <input type="hidden" name="txt_total" class="sub_total_form" value="0" readonly>
                        <span class="sub_total_pembelian" id="0"> 0 gr</span>
                      </td>
                    </tr>

                    <!-- <tr>
                      <th>Total</th>
                      <td>
                        <input type="text" name="txt_total" class="total_form" value="0" readonly>
                        <span class="total_pembelian" id="0">0 gr</span>
                      </td>
                    </tr> -->
                    <!-- <tr>
                      <th>Saldo Nasabah</th>
                      <td>
                        <input type="text" name="txt_gold" class="gold_form" value="0" readonly>
                        <span class="total_gold" id="0">0,gr</span>
                      </td>
                    </tr> -->
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
              <div class="col-xs-12">
                <a href="?module=deposit" class="btn btn-danger"><i class="fa fa-reply "></i> Keluar</a>
                <button type="Submit" name="InsertData" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Simpan
                </button>

              </div>
            </div>
            <!-- ....batas   -->
          </div>
          <!-- /. transaksi -->
        </div>
        <!-- /. box -->
      </div>
      <!-- /.col -->
    </form>
  </div>
  <!-- /.row -->
</section>



<!-- modal -->