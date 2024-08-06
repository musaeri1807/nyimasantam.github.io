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
          <h3 class="box-title">Data Product</h3>
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
                      <a href="../export_trash?tanggal_dari=<?php echo $tgl_dari ?>&tanggal_sampai=<?php echo $tgl_sampai ?>" class="btn btn-sm btn-success"><i class="fa fa-download"></i> &nbsp Excel</a>
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
                    <th>Product</th>
                    <th>Kategori</th>
                    <th>Date</th>
                    <th>No Reff</th>
                    <th>Rekening</th>
                    <th>Cabang Nasabah</th>
                    <th>Nama</th>
                    <th>Trx_Cabang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Oprasional Fee</th>
                    <th>Total</th>
                    <th>Konversi Emas</th>
                    <th>Harga Emas</th>
                    <th>Petugas</th>

                    <!-- <th>Status</th> -->
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
                    $sqlT = "	SELECT  
                    T.field_deposit_id,
                    T.field_trx_deposit AS ID,                    
                    P.field_product_name AS PRODUK,
                    K.field_name_category AS KATEGORI,
                  	I.field_date_deposit AS TANGGAL,
                    I.field_no_referensi AS REFERENSI,
                    I.field_rekening_deposit AS REKENING,
                    N.No_Rekening,
                    U.field_branch AS IDNB_CABANG,
                    UB.field_branch_name AS NB_CABANG,
                    U.field_nama AS NAMA,
                    I.field_branch AS TRX_CABANG,
                    B.field_branch_name AS CABANG,
                    T.field_price_product AS HARGA,
                    T.field_quantity AS QTY,
                    T.field_total_price AS TOTAL,
                    I.field_operation_fee AS PERSEN_5,
                    T.field_total_price/100*5 AS RESULT_PERSEN,
                    T.field_total_price-T.field_total_price/100*5 AS DEPO,                                      
                    (T.field_total_price-T.field_total_price/100*5)/I.field_gold_price AS GOLD,                                    
                    I.field_gold_price AS HARGA_EMAS,
                    E.field_name_officer AS PETUGAS,                                          
                    E.field_role
                    FROM tbldepositdetail T 
						        LEFT JOIN tblproduct P ON  T.field_product=P.field_product_id
                    LEFT JOIN tblcategory K ON P.field_category=K.field_category_id
                    LEFT JOIN tbldeposit I ON T.field_trx_deposit=I.field_trx_deposit 
                    LEFT JOIN tblnasabah N ON I.field_rekening_deposit=N.No_Rekening
                    LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id 
                    LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id                                   
                    LEFT JOIN tblbranch UB ON U.field_branch=UB.field_branch_id                                          
                    WHERE  I.field_status='S' AND  date(I.field_date_deposit) >=:tgl_dari AND date(I.field_date_deposit) <= :tgl_sampai 
                    ORDER BY T.field_deposit_id ASC";
                    $stmtT = $db->prepare($sqlT);
                    $stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai));
                    $resultT = $stmtT->fetchAll();
                    # code...
                    // } elseif ($_SESSION['rolelogin'] == 'SVP' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
                  } else {
                    # code...
                    $sqlT = "SELECT  
                    T.field_deposit_id,
                    T.field_trx_deposit AS ID,                    
                    P.field_product_name AS PRODUK,
                    K.field_name_category AS KATEGORI,
                  	I.field_date_deposit AS TANGGAL,
                    I.field_no_referensi AS REFERENSI,
                    I.field_rekening_deposit AS REKENING,
                    N.No_Rekening,
                    U.field_branch AS IDNB_CABANG,
                    UB.field_branch_name AS NB_CABANG,
                    U.field_nama AS NAMA,
                    I.field_branch AS TRX_CABANG,
                    B.field_branch_name AS CABANG,
                    T.field_price_product AS HARGA,
                    T.field_quantity AS QTY,
                    T.field_total_price AS TOTAL,
                    I.field_operation_fee AS PERSEN_5,
                    T.field_total_price/100*5 AS RESULT_PERSEN,
                    T.field_total_price-T.field_total_price/100*5 AS DEPO,                                      
                    (T.field_total_price-T.field_total_price/100*5)/I.field_gold_price AS GOLD,                                    
                    I.field_gold_price AS HARGA_EMAS,
                    E.field_name_officer AS PETUGAS,                                          
                    E.field_role
                    FROM tbldepositdetail T 
						        LEFT JOIN tblproduct P ON  T.field_product=P.field_product_id
                    LEFT JOIN tblcategory K ON P.field_category=K.field_category_id
                    LEFT JOIN tbldeposit I ON T.field_trx_deposit=I.field_trx_deposit 
                    LEFT JOIN tblnasabah N ON I.field_rekening_deposit=N.No_Rekening
                    LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id 
                    LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id                                   
                    LEFT JOIN tblbranch UB ON U.field_branch=UB.field_branch_id 
                    WHERE I.field_status='S' AND date(I.field_date_deposit) >=:tgl_dari AND date(I.field_date_deposit) <= :tgl_sampai AND
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

                      <td><?php echo sprintf("%09s", $row['field_deposit_id']); ?></td>
                      <td><?php echo $row["PRODUK"]; ?></td>
                      <td><?php echo $row["KATEGORI"]; ?></td>
                      <td data-title=""><?php echo $row["TANGGAL"]; ?></td>
                      <td data-title="Status"><?php echo $row["REFERENSI"]; ?></td>
                      <td data-title="channel"><?php echo $row["No_Rekening"]; ?></td>
                      <td data-title="channel"><?php echo $row["NB_CABANG"]; ?></td>
                      <td data-title="Status"><?php echo $row["NAMA"]; ?></td>
                      <td data-title="channel"><?php echo $row["CABANG"]; ?></td>
                      <td data-title="Status"><?php echo rupiah($row["HARGA"]); ?></td>
                      <td data-title="channel"><?php echo $row["QTY"]; ?></td>
                      <td data-title="channel"><?php echo rupiah($row["TOTAL"]); ?></td>
                      <td data-title="channel"><?php echo rupiah($row["RESULT_PERSEN"]); ?></td>
                      <td data-title="channel"><?php echo rupiah($row["DEPO"]); ?></td>
                      <td data-title="channel"><?php echo number_format($row["GOLD"], 6); ?></td>
                      <td data-title="channel"><?php echo rupiah($row["HARGA_EMAS"]); ?></td>
                      <td data-title="channel"><?php echo $row["PETUGAS"]; ?></td>
                    </tr>

                  <?php



                    $sumP = $sumP + $row["QTY"];
                    $sumST = $sumST + $row["TOTAL"];
                    $sumFEE = $sumFEE + $row["RESULT_PERSEN"];
                    $sumT = $sumT + $row["DEPO"];
                    $sumGOLD = $sumGOLD + $row["GOLD"];
                  } ?>
                </tbody>
                <tfoot>
                  <tr class="bg-info">
                    <td colspan="9" class="text-right"><b>General Total</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"><strong><?php echo number_format($sumP); ?></strong></td>
                    <td class="text-center"><strong><?php echo rupiah($sumST); ?></strong></td>
                    <td class="text-center"><strong><?php echo rupiah($sumFEE); ?></strong></td>
                    <td class="text-center"><strong><?php echo rupiah($sumT); ?></strong></td>
                    <td colspan="2" class="text-left"><strong><?php echo number_format($sumGOLD, 6, ",", ","); ?> gr </strong></td>


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