<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../loginv2.php");
}



// var_dump($e);

// die();

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

// $Year    = date('Y-m-d', strtotime("-1 months"));
// $Yearold=substr($Year,0,4);

// if ($Yearold==date('Y')) {
//   echo "Lanjut";
// } else {
//   echo "Reset";
// }


// // $buah = array(date('Y'));
// // //count() untuk menghitung isi array.
// // for($x=0;$x<count($buah);$x++){
// // 	echo $buah[$x]."<br/>";
// // }
// print_r($Yearold);
// // ;
// // print_r($order['field_no_referensi']);
// die();

$query        = "SELECT * FROM tblgoldprice WHERE field_status='A' ORDER BY field_gold_id  DESC LIMIT 1 ";
$Gold         = $db->prepare($query);
$Gold->execute();
$Result       = $Gold->fetch(PDO::FETCH_ASSOC);
$goldprice    = $Result['field_sell'];

// echo $goldprice;
// die();

if (isset($_REQUEST['payment'])) {

  $memberid                 = $_REQUEST['txt_memberid'];
  $field_no_referensi       = $noReff;
  $field_date_deposit       = date('Y-m-d');
  $time                     = date('H:i:s');
  $field_rekening_deposit   = $_POST['txt_rekening'];
  $field_sumber_dana        = $_POST['txt_select'];
  $field_branch             = $branchid;
  $field_officer_id         = $id;
  $field_sub_total          = $_POST['txt_subtotal'];
  $field_operation_fee      = $_POST['txt_free'];
  $field_operation_fee_rp   = $field_sub_total * $field_operation_fee / 100;
  $field_operation_fee_rp   = $_POST['txt_free_rp'];
  $field_total_deposit      = $_POST['txt_total'];
  $field_deposit_gold       = $_POST['txt_gold'];
  $field_gold_price         = $goldprice;

  $transaksi_produk         = $_POST['transaksi_produk'];
  $transaksi_harga          = $_POST['transaksi_harga'];
  $transaksi_jumlah         = $_POST['transaksi_jumlah'];
  $transaksi_total          = $_POST['transaksi_total'];

  if (empty($memberid)) {
    $errorMsg             = "Mmember ID Belum Ada";
  } else {
    try {
      $query2         = "SELECT field_status FROM tbltrxmutasisaldo WHERE field_rekening =:rekening ORDER BY field_id_saldo DESC LIMIT 1";
      $select2        = $db->prepare($query2);
      $select2->execute(array(':rekening' => $field_rekening_deposit));
      $result2        = $select2->fetch(PDO::FETCH_ASSOC);
      // echo "SELECT DATA SALDO";
      if ($result2['field_status'] !== "P") {
        # code...

        $query        = "SELECT * FROM tbltrxmutasisaldo WHERE field_rekening =:rekening  AND field_status='S' ORDER BY field_id_saldo DESC LIMIT 1";
        $select       = $db->prepare($query);
        $select->execute(array(':rekening' => $field_rekening_deposit));
        $result       = $select->fetch(PDO::FETCH_ASSOC);
        $saldoAwal    = $result['field_total_saldo'];
        $saldoAkhir   = $saldoAwal + $field_deposit_gold;
        $data         = $select->rowCount();

        // echo $data    = $select2->rowCount();
        if ($data = 1) { //memastikan rekening hanya satu yang ter insert
          # code...
          // echo $field_no_referensi;
          // die();
          $insert = $db->prepare('INSERT INTO tbldeposit (
                field_no_referensi,
                field_date_deposit,
                field_rekening_deposit,
                field_sumber_dana,
                field_branch,
                field_officer_id,
                field_sub_total,
                field_operation_fee,
                field_operation_fee_rp,
                field_total_deposit,
                field_deposit_gold,
                field_gold_price,
                field_status,
                field_approve) 
              VALUES(   
                :no_referensi,
                :date_deposit,
                :rekening_deposit,
                :sumber_dana,
                :branch,
                :officer_id,
                :sub_total,
                :operation_fee,
                :operation_fee_rp,
                :total_deposit,
                :deposit_gold,
                :gold_price,
                :ustatus,
                :approval)');

          $insert->execute(array(
            ':no_referensi'       => $field_no_referensi,
            ':date_deposit'       => $field_date_deposit,
            ':rekening_deposit'   => $field_rekening_deposit,
            ':sumber_dana'        => $field_sumber_dana,
            ':branch'             => $field_branch,
            ':officer_id'         => $field_officer_id,
            ':sub_total'          => $field_sub_total,
            ':operation_fee'      => $field_operation_fee,
            ':operation_fee_rp'   => $field_operation_fee_rp,
            ':total_deposit'      => $field_total_deposit,
            ':deposit_gold'       => $field_deposit_gold,
            ':gold_price'         => $field_gold_price,
            ':ustatus'             => "S",
            ':approval'           => $field_officer_id
          ));
          $id = $db->lastinsertid();
          if ($id) {
            $jumlah_pembelian = count($transaksi_produk);
            for ($a = 0; $a < $jumlah_pembelian; $a++) {

              $t_produk   = $transaksi_produk[$a];
              $t_harga    = $transaksi_harga[$a];
              $t_jumlah   = $transaksi_jumlah[$a];
              $t_total    = $transaksi_total[$a];

              $insert = $db->prepare('INSERT INTO tbldepositdetail( 
                                                              field_trx_deposit,
                                                              field_product,
                                                              field_price_product,
                                                              field_quantity,
                                                              field_total_price) 
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
              field_kredit_saldo,
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
              :kredit_saldo,
              :total_saldo,
              :status)');
          $in->execute(array(
            ':trx_id'             => $id,
            ':memberid'           => $memberid,
            ':no_referensi'       => $field_no_referensi,
            ':rekening'           => $field_rekening_deposit,
            ':tanggal_saldo'      => $field_date_deposit,
            ':times'              => $time,
            ':type_saldo'         => 100,
            ':kredit_saldo'       => $field_deposit_gold,
            ':total_saldo'        => $saldoAkhir,
            ':status'              => "S"
          ));
          $Msg      = " Transaction Saldo Successfully"; //execute query success message
        } else {
          # code...
          $errorMsg = "Rekening lebih dari Satu";
        }
      } else {
        # code...
        // echo "TOLAK INSERT";
        // echo "<br>";
        $errorMsg     = "Transaksi Sebelumnya Masih Pending";
      }


      // if(!isset($errorMsg))	{        

      //   $update_stmt=$db->prepare('UPDATE  SET  WHERE '); //sql insert query					

      // 	$update_stmt->bindParam(':typeaprove',$typeaprove);
      //   $update_stmt->bindParam(':idaprovel',$id);
      //   $update_stmt->bindParam(':id',$idgold);					
      // 	if($update_stmt->execute()){
      // 		$Msg="Successfully"; //execute query success message
      // 		echo '<META HTTP-EQUIV="Refresh" Content="1">';
      // 	}

      // }

    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}

//batas
// die();

if (isset($_POST['payment1'])) {
  # code...
  echo "member Id ==" . $memberid = $_REQUEST['txt_memberid'];
  echo '<br>';
  echo "Nomor Reff ==" . $field_no_referensi       = $noReff;
  echo '<br>';
  echo "Tanggal Trx ==" . $field_date_deposit       = date('Y-m-d');
  echo '<br>';
  echo "Time Trx ==" . $time  = date('H:i:s');
  echo '<br>';
  echo "Nomor Rekening ==" . $field_rekening_deposit   = $_POST['txt_rekening'];
  echo '<br>';
  echo "Sumber Dana ==" . $field_sumber_dana        = $_POST['txt_select'];
  echo '<br>';
  echo "Cabang ==" . $field_branch             = $branchid;
  echo '<br>';
  echo "Create office ==" . $field_officer_id         = $id;
  echo '<br>';
  echo "Sub Total ==" . $field_sub_total          = $_POST['txt_subtotal'];
  echo '<br>';
  echo "Fee 5% ==" . $field_operation_fee      = $_POST['txt_free'];
  echo '<br>';
  echo "Fee Rp ==" . $field_operation_fee_rp   = $field_sub_total * $field_operation_fee / 100;
  echo '<br>';
  echo "Fee Rp java ==" . $field_operation_fee_rp   = $_POST['txt_free_rp'];
  echo '<br>';
  echo "Total ==" . $field_total_deposit      = $_POST['txt_total'];
  echo '<br>';
  echo "Gold ==" . $field_deposit_gold       = $_POST['txt_gold'];
  echo '<br>';
  echo "Harga Emas ==" . $field_gold_price         = $goldprice;

  $transaksi_produk   = $_POST['transaksi_produk'];
  $transaksi_harga    = $_POST['transaksi_harga'];
  $transaksi_jumlah   = $_POST['transaksi_jumlah'];
  $transaksi_total    = $_POST['transaksi_total'];



  $query    = "SELECT * FROM tbltrxmutasisaldo WHERE field_rekening =:rekening  AND field_status='S' ORDER BY field_id_saldo DESC LIMIT 1";
  $select   = $db->prepare($query);
  $select->execute(array(':rekening' => $field_rekening_deposit));
  $result   = $select->fetch(PDO::FETCH_ASSOC);



  $data   = $select->rowCount();
  $saldoAwal  = $result['field_total_saldo'];
  $saldoAkhir = $saldoAwal + $field_deposit_gold;


  echo '<br>';
  echo "Saldo Awal ==" . $saldoAwal;
  echo '<br>';
  echo "Saldo Akhir ==" . $saldoAkhir;
  echo '<br>';
  echo "Jumlah Select* ==" . $data;

  // die();
  //jika saldo status pending 
  $query2 = "SELECT field_status FROM tbltrxmutasisaldo WHERE field_rekening =:rekening ORDER BY field_id_saldo DESC LIMIT 1";
  $select2 = $db->prepare($query2);
  $select2->execute(array(':rekening' => $field_rekening_deposit));
  $result2 = $select2->fetch(PDO::FETCH_ASSOC);

  if ($result2['field_status'] !== "P") {
    # code...
    //echo " SUKSES";

    $insert = $db->prepare('INSERT INTO tbldeposit (
                                              field_no_referensi,
                                              field_date_deposit,
                                              field_rekening_deposit,
                                              field_sumber_dana,
                                              field_branch,
                                              field_officer_id,
                                              field_sub_total,
                                              field_operation_fee,
                                              field_operation_fee_rp,
                                              field_total_deposit,
                                              field_deposit_gold,
                                              field_gold_price,
                                              field_status,
                                              field_approve) 
                                        VALUES(   : no_referensi,
                                                  : date_deposit,
                                                  : rekening_deposit,
                                                  : sumber_dana,
                                                  : branch,
                                                  : officer_id,
                                                  : sub_total,
                                                  : operation_fee,
                                                  : operation_fee_rp,
                                                  : total_deposit,
                                                  : deposit_gold,
                                                  : gold_price,
                                                  : ustatus,
                                                  : approval)');

    $insert->execute(array(
      ' : no_referensi'       => $field_no_referensi,
      ' : date_deposit'       => $field_date_deposit,
      ' : rekening_deposit'   => $field_rekening_deposit,
      ' : sumber_dana'        => $field_sumber_dana,
      ' : branch'             => $field_branch,
      ' : officer_id'         => $field_officer_id,
      ' : sub_total'          => $field_sub_total,
      ' : operation_fee'      => $field_operation_fee,
      ' : operation_fee_rp'   => $field_operation_fee_rp,
      ' : total_deposit'      => $field_total_deposit,
      ' : deposit_gold'       => $field_deposit_gold,
      ' : gold_price'         => $field_gold_price,
      ' : ustatus'             => "S",
      ' : approval'           => $field_officer_id
    ));
    $id = $db->lastinsertid();
    if ($id) {
      $jumlah_pembelian = count($transaksi_produk);
      for ($a = 0; $a < $jumlah_pembelian; $a++) {

        $t_produk   = $transaksi_produk[$a];
        $t_harga    = $transaksi_harga[$a];
        $t_jumlah   = $transaksi_jumlah[$a];
        $t_total    = $transaksi_total[$a];

        $insert = $db->prepare('INSERT INTO tbldepositdetail 
                                                ( field_trx_deposit,
                                                  field_product,
                                                  field_price_product,
                                                  field_quantity,
                                                  field_total_price) 
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
      }
      $in = $db->prepare('INSERT INTO tbltrxmutasisaldo 
                                        (
      field_trx_id,
      field_member_id,
      field_no_referensi,
      field_rekening,
      field_tanggal_saldo,
      field_time,
      field_type_saldo,
      field_kredit_saldo,
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
        :kredit_saldo,
        :total_saldo,
        :status)');
      $in->execute(array(
        ':trx_id'             => $id,
        ':memberid'           => $memberid,
        ':no_referensi'       => $field_no_referensi,
        ':rekening'           => $field_rekening_deposit,
        ':tanggal_saldo'      => $field_date_deposit,
        ':times'              => $time,
        ':type_saldo'         => 100,
        ':kredit_saldo'       => $field_deposit_gold,
        ':total_saldo'        => $saldoAkhir,
        ':status'              => "S"
      ));
      $Msg = "Successfully"; //execute query success message
      echo '<META HTTP-EQUIV="Refresh" Content="1">';
    } else {
      # code...
      //echo " PENDING";
      $errorMsg = "Transaksi Masih ada yang pending ";
      echo '<META HTTP-EQUIV="Refresh" Content="1">';
    }
  }
}//batas

// die();
$Stmt = $db->prepare("SELECT * FROM tblcustomer");
$Stmt->execute();
$Result = $Stmt->fetchAll();




if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {

  $Sql    = "SELECT P.*,B.field_branch_name FROM tblproduct P 
  LEFT JOIN tblbranch B
  ON P.field_branch=B.field_branch_id 
  WHERE field_status='A'
  ORDER BY P.field_product_id DESC ";

  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $result = $Stmt->fetchAll();
} else {

  $Sql    = "SELECT P.*,B.field_branch_name FROM tblproduct P 
  LEFT JOIN tblbranch B
  ON P.field_branch=B.field_branch_id 
  WHERE field_status='A'
  AND P.field_branch=:idbranch
  ORDER BY P.field_product_id DESC ";


  $Stmt = $db->prepare($Sql);
  $Stmt->execute(array(":idbranch" => $branchid));
  $result = $Stmt->fetchAll();
}


// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=deposit">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=deposit">';
  }
}

?>



<!-- Main content -->
<section class="content">
  <!-- Content -->
  <!-- <?php
        if (isset($errorMsg)) {
          echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
        }
        if (isset($insertMsg)) {
          echo '<div class="alert alert-success"><strong>SUCCESS !' . $insertMsg . '</strong></div>';
        }
        ?> -->
  <div class="row">
    <form name="ftrx" method="POST" class="form-horizontal" onSubmit="return cek(this)">
      <div class="col-md-3">
        <div class="box box-primary">

          <div class="box-header with-border">
            <!-- <h3 class="box-title"></h3> -->
            <!-- <a href="" class="btn btn-success btn-block margin-bottom">Search Product</a> -->
            <button style="margin-top: 27px" type="button" class="btn btn-success btn-block margin-bottom" data-toggle="modal" data-target="#cariProduk">
              <i class="fa fa-search"></i> &nbsp Search Product
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
                      Select Product
                    </div>
                    <div class="modal-body">


                      <div class="table-responsive">
                        <!-- <table class="table table-bordered table-striped table-hover" id="table-datatable-produk"> -->
                        <table class="table table-bordered table-striped table-hover" id="trxSemua">
                          <thead>
                            <tr>
                              <th class="text-center">No</th>
                              <th>Code</th>
                              <th>Product</th>
                              <th class="text-center">Branch</th>
                              <th class="text-center">Unit</th>
                              <th class="text-center">Price</th>
                              <th class="text-center">Approval</th>
                              <th>Note</th>
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
                                <td width="1%"><?php echo $rows['field_product_code']; ?></td>
                                <td>
                                  <?php echo $rows['field_product_name']; ?>
                                  <br>
                                  <small class="text-muted"><?php echo $rows['field_category']; ?></small>
                                </td>
                                <td width="1%" class="text-center"><?php echo $rows['field_branch_name']; ?></td>
                                <td width="1%" class="text-center"><?php echo $rows['field_unit']; ?></td>
                                <td width="20%" class="text-center"><?php echo $rows['field_price']; ?></td>
                                <td width="15%">

                                  <?php

                                  if ($rows["field_status"] == "A") {
                                    echo '<span class="badge btn-success text-white">Approve</span>';
                                  } elseif ($rows["field_status"] == "C") {
                                    echo '<span class="badge btn-info text-white">Cancel</span>';
                                  } elseif ($rows["field_status"] == "P") {
                                    echo '<span class="badge btn-warning text-white">Pending</span>';
                                  } elseif ($rows["field_status"] == "R") {
                                    echo '<span class="badge btn-danger text-white">Reject</span>';
                                  }
                                  ?>

                                </td>
                                <td width="15%"><?php echo $rows['field_note']; ?></td>
                                <td width="1%">

                                  <input type="hidden" id="kode_<?php echo $rows['field_product_id']; ?>" value="<?php echo $rows['field_product_code']; ?>">
                                  <input type="hidden" id="nama_<?php echo $rows['field_product_id']; ?>" value="<?php echo $rows['field_product_name']; ?>">
                                  <input type="hidden" id="harga_<?php echo $rows['field_product_id']; ?>" value="<?php echo $rows['field_price']; ?>">
                                  <button type="button" class="btn btn-success modal-pilih-produk" id="<?php echo $rows['field_product_id']; ?>" data-dismiss="modal">Select</button>

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
              <li><a><input type="text" class="form-control" placeholder="Code Product" id="tambahkan_kode" readonly></a></li>
              <li><a><input type="text" class="form-control" placeholder="Name Product" id="tambahkan_nama" readonly></a></li>
              <li><a><input type="text" name="" class="form-control" placeholder="Price" id="tambahkan_harga" readonly></a></li>
              <li><a><input type="number" name="" class="form-control" placeholder="Qty" id="tambahkan_jumlah"></a></li>
              <li><a><input type="text" name="" class="form-control" placeholder="Total" id="tambahkan_total" readonly></a></li>
            </ul>
          </div>
          <!-- /.box-body -->
        </div>
        <a href="#" class="btn btn-primary btn-block margin-bottom" id="tombol-tambahkan">Add </a>
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
                      <select class="form-control" name="txt_select" required="required">
                        <option value="">--Pilih--</option>
                        <option value="Investasi">Investasi</option>
                        <option value="Investasi">Sampah</option>
                        <option value="Gaji">Gaji</option>

                      </select>
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
                        <i class="fa fa-users"></i> &nbsp Customer
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
                        Select Customer
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
                            <th class="text-center">Account Number</th>
                            <th class="text-center">Name Customer</th>

                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = 1;
                          foreach ($Result as $rows) {
                          ?>
                            <tr>
                              <td width="1%" class="text-center"><?php echo $no++; ?></td>
                              <td width="20%"><?php echo $rows['field_rekening']; ?></td>
                              <td width="20%"><?php echo $rows['field_nama_customer']; ?> </td>
                              <td width="1%">
                                <input type="hidden" id="member_<?php echo $rows['field_customer_id']; ?>" value="<?php echo $rows['field_member_id']; ?>">
                                <input type="hidden" id="account_<?php echo $rows['field_customer_id']; ?>" value="<?php echo $rows['field_rekening']; ?>">
                                <input type="hidden" id="customer_<?php echo $rows['field_customer_id']; ?>" value="<?php echo $rows['field_nama_customer']; ?>">
                                <button type="button" class="btn btn-success modal-select-customer" id="<?php echo $rows['field_customer_id']; ?>" data-dismiss="modal">Select</button>

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
                      <th>Code</th>
                      <th>Product</th>
                      <th style="text-align: center;">Price</th>
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
                      <td style="text-align: center;"><span class="pembelian_harga" id="0">Rp.0,-</span></td>
                      <td style="text-align: center;"><span class="pembelian_jumlah" id="0">0</span></td>
                      <td style="text-align: center;"><span class="pembelian_total" id="0">Rp.0,-</span></td>
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
                <p class="lead">Gold Price</p>
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                  <input type="hidden" value="<?php echo $goldprice; ?>">
                  <span class="goldprice" id="<?php echo $goldprice; ?>"><?php echo rupiah($goldprice); ?></span>
                </p>
              </div>
              <!-- /.col -->
              <div class="col-xs-6">
                <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td>
                        <input type="text" name="txt_subtotal" class="sub_total_form" value="0">
                        <span class="sub_total_pembelian" id="0">Rp.0,-</span>
                      </td>
                    </tr>
                    <tr>
                      <th id="txt_persen">Oprasional free (5%)</th>
                      <td>
                        <input class="total_fee" type="number" min="0" max="100" id="5" name="txt_free">
                        <span class="fee" id="0">0%</span>

                        <input class="total_fee_rp" type="text" value="0" id="5" name="txt_free_rp">
                        <span class="fee_rp" id="0">Rp.0,-</span>
                      </td>
                    </tr>
                    <tr>
                      <th>Total</th>
                      <td>
                        <input type="text" name="txt_total" class="total_form" value="0">
                        <span class="total_pembelian" id="0">Rp.0,-</span>
                      </td>
                    </tr>
                    <tr>
                      <th>Gold Customer</th>
                      <td>
                        <input type="text" name="txt_gold" class="gold_form" value="0">
                        <span class="total_gold" id="0">0,gram</span>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
              <div class="col-xs-12">
                <a href="?module=deposit" class="btn btn-danger"><i class="fa fa-reply "></i> Cancel</a>
                <button type="Submit" name="payment" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Payment
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