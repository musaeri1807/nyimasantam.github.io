<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

$Sql = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute(array(":idbranch" => $branchid));
$result = $Stmt->fetch(PDO::FETCH_ASSOC);


//Transaksi Hari Ini
$tanggal = date('Y-m-d');
// $tanggal = '2022-08-19';
$bulan   = date('m');
$tahun   = date('Y');
// echo $tanggal;
// echo $bulan;
// echo $tahun;
// die();
$query_hari = "SELECT SUM(field_total_deposit) AS TOTAL_HRI,SUM(field_deposit_gold) AS EMAS_HRI FROM tbldeposit WHERE field_status ='S' AND field_date_deposit=:hari";
$ST_hari = $db->prepare($query_hari);
$ST_hari->execute(array(':hari' => $tanggal));
$result_hari = $ST_hari->fetch(PDO::FETCH_ASSOC);

//Transaksi Bulan
$query_bulan = "SELECT SUM(field_total_deposit) AS TOTAL_BLN,SUM(field_deposit_gold) AS EMAS_BLN FROM tbldeposit WHERE field_status ='S' AND MONTH(field_date_deposit)=:bulan AND YEAR(field_date_deposit)=:tahun";
$ST_bulan = $db->prepare($query_bulan);
$ST_bulan->execute(array(

  ':bulan' => $bulan,
  ':tahun' => $tahun
));
$result_bulan = $ST_bulan->fetch(PDO::FETCH_ASSOC);

//Transaksi Tahun
$query_tahun = "SELECT SUM(field_total_deposit) AS TOTAL_THN ,SUM(field_deposit_gold) AS EMAS_THN FROM tbldeposit WHERE field_status ='S' AND YEAR(field_date_deposit)=:tahun";
$ST_tahun = $db->prepare($query_tahun);
$ST_tahun->execute(array(':tahun' => $tahun));
$result_tahun = $ST_tahun->fetch(PDO::FETCH_ASSOC);

//Transaksi Semua
$query_semua = "SELECT SUM(field_total_deposit) AS TOTAL_TRX,SUM(field_deposit_gold) AS EMAS_TRX FROM tbldeposit WHERE field_status ='S'";
$ST_semua = $db->prepare($query_semua);
$ST_semua->execute();
$result_semua = $ST_semua->fetch(PDO::FETCH_ASSOC);


echo $result_hari['TOTAL_HRI'];
echo '<br>';
echo $result_bulan['TOTAL_BLN'];
echo '<br>';
echo $result_tahun['TOTAL_THN'];
echo '<br>';
echo $result_semua['TOTAL_TRX'];
// die();



// SELECT * FROM tblwithdraw;
// SELECT SUM(field_rp_withdraw) AS TOTAL_HRI, SUM(field_withdraw_gold) AS EMAS_HRI FROM tblwithdraw 
// WHERE field_status ='S' AND field_date_withdraw='2021-04-19';

// SELECT SUM(field_rp_withdraw) AS TOTAL_BLN ,SUM(field_withdraw_gold) AS EMAS_HRI FROM tblwithdraw 
// WHERE field_status ='S' AND MONTH(field_date_withdraw)='01' AND YEAR(field_date_withdraw)='2022';

// SELECT SUM(field_rp_withdraw) AS TOTAL_THN ,SUM(field_withdraw_gold) AS EMAS_HRI FROM tblwithdraw 
// WHERE field_status ='S' AND YEAR(field_date_withdraw)='2022';

// SELECT SUM(field_rp_withdraw) AS TOTAL_TRX ,SUM(field_withdraw_gold) AS EMAS_HRI FROM tblwithdraw 
// WHERE field_status ='S';





?>
<div style="margin-right:10%;margin-left:15%" class="alert alert-info alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <Center>
    <h3><i class="icon fa fa-info"></i>
      Welcome ! <?php echo $rows["field_name_officer"]; ?> &nbsp;&nbsp;
      Anda berada di halaman "<?php echo $rows["field_department_name"]; ?>"
      <?php
      if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
        # code...
      } else {
        # code...
        echo 'Cabang ' .  $result['field_branch_name'];
      }
      ?>
    </h3>
  </Center>
</div>
<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_hari['TOTAL_HRI']) . " ,-" ?></h4>
          <p>Transaksi Hari Ini</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_bulan['TOTAL_BLN']) . " ,-" ?></h4>
          <p>Transaksi Bulan Ini</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_tahun['TOTAL_THN']) . " ,-" ?></h4>
          <p>Transaksi Tahun Ini</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-black">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_semua['TOTAL_TRX']) . " ,-" ?></h4>
          <p>Total Seluruh Transaksi</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">

          <h4 style="font-weight: bolder"><?php echo $result_hari['EMAS_HRI'] . " " . "Gram"; ?></h4>
          <p>Simpanan Emas Hari Ini</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">

          <h4 style="font-weight: bolder"><?php echo round($result_bulan['EMAS_BLN'], 6) . ",- gr"; ?></h4>
          <p>Simpanan Emas Bulan Ini</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo round($result_tahun['EMAS_THN'], 6) . ",- gr"; ?></h4>
          <p>Simpanan Emas Tahun <?php echo date('Y'); ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo round($result_semua['EMAS_TRX'], 6) . ",- gr"; ?></h4>
          <p>Total Simpanan Emas</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <?php
          $query = mysqli_query($koneksi, "SELECT sum(field_debit_saldo) AS debit FROM tbltrxmutasisaldo WHERE field_status='Success' ");
          $debit = mysqli_fetch_array($query);
          ?>
          <h4 style="font-weight: bolder"><?php echo round($debit['debit'], 6) . ",- gr"; ?></h4>
          <p>Total Penarikan</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <?php
          $invoice = mysqli_query($koneksi, "SELECT * from invoice");
          $i = mysqli_num_rows($invoice);
          ?>
          <h4 style="font-weight: bolder"><?php echo $i ?></h4>
          <p>Jumlah Invoice</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  <div class="row">
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Content -->
      <div class="box box-solid box-primary">
        <div class="box-header">
          <i class="fa fa-info"></i>Informasi
        </div>
        <div class="box-body">
          <h4>Hak Akses sebagai Admin:</h4>
          <li>Mengelola data User</li>
          <li>Mengelola data master lokasi kerja</li>
          <li>Mengelola data master unit kerja</li>
          <li>Mengelola data master jabatan</li>
          <li>Mengelola data master pangkat</li>
        </div>
      </div>
      <!-- /Content -->
      <div class="box-body">
      </div>
      <!-- Content -->

    </div>
  </div>
</section>
<!-- /.content -->