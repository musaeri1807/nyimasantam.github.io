<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

$Sql = "SELECT * FROM tblbranch C LEFT JOIN tblwilayahdesa W ON C.field_branch_id=W.field_desa_id WHERE C.field_branch_id=:idbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute(array(":idbranch" => $branchid));
$result = $Stmt->fetch(PDO::FETCH_ASSOC);


//Transaksi Hari Ini
$tanggal = date('Y-m-d');
// $tanggal = '2021-11-29';
$bulan   = date('m');
$tahun   = date('Y');

if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  // Simpanan
  $query_Dhari = "SELECT SUM(field_total_deposit) AS TOTALD_HRI,SUM(field_deposit_gold) AS EMASD_HRI FROM tbldeposit WHERE field_status ='S' AND field_date_deposit=:hari";
  $ST_Dhari = $db->prepare($query_Dhari);
  $ST_Dhari->execute(array(':hari' => $tanggal));
  $result_Dhari = $ST_Dhari->fetch(PDO::FETCH_ASSOC);
  // Pengembalian
  $query_Whari = "SELECT SUM(field_rp_withdraw) AS TOTALW_HRI, SUM(field_withdraw_gold) AS EMASW_HRI FROM tblwithdraw WHERE field_status ='S' AND field_date_withdraw=:hari";
  $ST_Whari = $db->prepare($query_Whari);
  $ST_Whari->execute(array(':hari' => $tanggal));
  $result_Whari = $ST_Whari->fetch(PDO::FETCH_ASSOC);
  
  $result_hari_RP = $result_Dhari['TOTALD_HRI'] - $result_Whari['TOTALW_HRI'];
  $result_hari_GOLD = $result_Dhari['EMASD_HRI'] - $result_Whari['EMASW_HRI'];
  
  //Transaksi Bulan
  // Simpanan
  $query_Dbulan = "SELECT SUM(field_total_deposit) AS TOTALD_BLN,SUM(field_deposit_gold) AS EMASD_BLN FROM tbldeposit WHERE field_status ='S' AND MONTH(field_date_deposit)=:bulan AND YEAR(field_date_deposit)=:tahun";
  $ST_Dbulan = $db->prepare($query_Dbulan);
  $ST_Dbulan->execute(array(
    ':bulan' => $bulan,
    ':tahun' => $tahun
  ));
  $result_Dbulan = $ST_Dbulan->fetch(PDO::FETCH_ASSOC);
  // Pengembalian
  $query_Wbulan = "SELECT SUM(field_rp_withdraw) AS TOTALW_BLN ,SUM(field_withdraw_gold) AS EMASW_BLN FROM tblwithdraw WHERE field_status ='S' AND MONTH(field_date_withdraw)=:bulan AND YEAR(field_date_withdraw)=:tahun";
  $ST_Wbulan = $db->prepare($query_Wbulan);
  $ST_Wbulan->execute(array(
    ':bulan' => $bulan,
    ':tahun' => $tahun
  ));
  $result_Wbulan = $ST_Wbulan->fetch(PDO::FETCH_ASSOC);
  
  $result_bulan_RP    = $result_Dbulan['TOTALD_BLN'] - $result_Wbulan['TOTALW_BLN'];
  $result_bulan_GOLD  = $result_Dbulan['EMASD_BLN'] - $result_Wbulan['EMASW_BLN'];
  
  //Transaksi Tahun
  $query_Dtahun = "SELECT SUM(field_total_deposit) AS TOTALD_THN ,SUM(field_deposit_gold) AS EMASD_THN FROM tbldeposit WHERE field_status ='S' AND YEAR(field_date_deposit)=:tahun";
  $ST_Dtahun = $db->prepare($query_Dtahun);
  $ST_Dtahun->execute(array(':tahun' => $tahun));
  $result_Dtahun = $ST_Dtahun->fetch(PDO::FETCH_ASSOC);
  
  $query_Wtahun = "SELECT SUM(field_rp_withdraw) AS TOTALW_THN ,SUM(field_withdraw_gold) AS EMASW_THN FROM tblwithdraw WHERE field_status ='S' AND YEAR(field_date_withdraw)=:tahun";
  $ST_Wtahun = $db->prepare($query_Wtahun);
  $ST_Wtahun->execute(array(':tahun' => $tahun));
  $result_Wtahun = $ST_Wtahun->fetch(PDO::FETCH_ASSOC);
  
  $result_tahun_RP    = $result_Dtahun['TOTALD_THN'] - $result_Wtahun['TOTALW_THN'];
  $result_tahun_GOLD  = $result_Dtahun['EMASD_THN'] - $result_Wtahun['EMASW_THN'];
  
  //Transaksi Semua
  $query_Dsemua = "SELECT SUM(field_total_deposit) AS TOTALD_TRX,SUM(field_deposit_gold) AS EMASD_TRX , SUM(field_operation_fee_rp) AS FEE FROM tbldeposit WHERE field_status ='S'";
  $ST_Dsemua = $db->prepare($query_Dsemua);
  $ST_Dsemua->execute();
  $result_Dsemua = $ST_Dsemua->fetch(PDO::FETCH_ASSOC);
  
  $query_Wsemua = "SELECT SUM(field_rp_withdraw) AS TOTALW_TRX,SUM(field_withdraw_gold) AS EMASW_TRX FROM tblwithdraw WHERE field_status ='S'";
  $ST_Wsemua = $db->prepare($query_Wsemua);
  $ST_Wsemua->execute();
  $result_Wsemua = $ST_Wsemua->fetch(PDO::FETCH_ASSOC);
  
  $result_semua_RP    = $result_Dsemua['TOTALD_TRX'] - $result_Wsemua['TOTALW_TRX'];
  $result_semua_GOLD  = $result_Dsemua['EMASD_TRX'] - $result_Wsemua['EMASW_TRX'];
  
  $Query_Cabang = "SELECT COUNT(field_branch_id) AS CABANG FROM tblbranch ";
  $ST_Dhari = $db->prepare($query_Dhari);
  $ST_Dhari->execute(array(':hari' => $tanggal));
  $result_Dhari = $ST_Dhari->fetch(PDO::FETCH_ASSOC);

} else {
  
  // Simpanan
  $query_Dhari = "SELECT SUM(field_total_deposit) AS TOTALD_HRI,SUM(field_deposit_gold) AS EMASD_HRI FROM tbldeposit WHERE field_status ='S' AND field_date_deposit=:hari AND field_branch=:idbranch";
  $ST_Dhari = $db->prepare($query_Dhari);
  $ST_Dhari->execute(array(':hari' => $tanggal,':idbranch' => $branchid));
  $result_Dhari = $ST_Dhari->fetch(PDO::FETCH_ASSOC);
  // Pengembalian
  $query_Whari = "SELECT SUM(field_rp_withdraw) AS TOTALW_HRI, SUM(field_withdraw_gold) AS EMASW_HRI FROM tblwithdraw WHERE field_status ='S' AND field_date_withdraw=:hari AND field_branch=:idbranch";
  $ST_Whari = $db->prepare($query_Whari);
  $ST_Whari->execute(array(':hari' => $tanggal,':idbranch' => $branchid));
  $result_Whari = $ST_Whari->fetch(PDO::FETCH_ASSOC);
  
  $result_hari_RP = $result_Dhari['TOTALD_HRI'] - $result_Whari['TOTALW_HRI'];
  $result_hari_GOLD = $result_Dhari['EMASD_HRI'] - $result_Whari['EMASW_HRI'];
  
  //Transaksi Bulan
  // Simpanan
  $query_Dbulan = "SELECT SUM(field_total_deposit) AS TOTALD_BLN,SUM(field_deposit_gold) AS EMASD_BLN FROM tbldeposit WHERE field_status ='S' AND MONTH(field_date_deposit)=:bulan AND YEAR(field_date_deposit)=:tahun AND field_branch=:idbranch";
  $ST_Dbulan = $db->prepare($query_Dbulan);
  $ST_Dbulan->execute(array(
    ':bulan' => $bulan,
    ':tahun' => $tahun,
    ':idbranch' => $branchid
  ));
  $result_Dbulan = $ST_Dbulan->fetch(PDO::FETCH_ASSOC);
  // Pengembalian
  $query_Wbulan = "SELECT SUM(field_rp_withdraw) AS TOTALW_BLN ,SUM(field_withdraw_gold) AS EMASW_BLN FROM tblwithdraw WHERE field_status ='S' AND MONTH(field_date_withdraw)=:bulan AND YEAR(field_date_withdraw)=:tahun AND field_branch=:idbranch";
  $ST_Wbulan = $db->prepare($query_Wbulan);
  $ST_Wbulan->execute(array(
    ':bulan' => $bulan,
    ':tahun' => $tahun,
    ':idbranch' => $branchid
  ));
  $result_Wbulan = $ST_Wbulan->fetch(PDO::FETCH_ASSOC);
  
  $result_bulan_RP    = $result_Dbulan['TOTALD_BLN'] - $result_Wbulan['TOTALW_BLN'];
  $result_bulan_GOLD  = $result_Dbulan['EMASD_BLN'] - $result_Wbulan['EMASW_BLN'];
  
  //Transaksi Tahun
  $query_Dtahun = "SELECT SUM(field_total_deposit) AS TOTALD_THN ,SUM(field_deposit_gold) AS EMASD_THN FROM tbldeposit WHERE field_status ='S' AND YEAR(field_date_deposit)=:tahun AND field_branch=:idbranch";
  $ST_Dtahun = $db->prepare($query_Dtahun);
  $ST_Dtahun->execute(array(':tahun' => $tahun,':idbranch' => $branchid));
  $result_Dtahun = $ST_Dtahun->fetch(PDO::FETCH_ASSOC);
  
  $query_Wtahun = "SELECT SUM(field_rp_withdraw) AS TOTALW_THN ,SUM(field_withdraw_gold) AS EMASW_THN FROM tblwithdraw WHERE field_status ='S' AND YEAR(field_date_withdraw)=:tahun AND field_branch=:idbranch";
  $ST_Wtahun = $db->prepare($query_Wtahun);
  $ST_Wtahun->execute(array(':tahun' => $tahun,':idbranch' => $branchid));
  $result_Wtahun = $ST_Wtahun->fetch(PDO::FETCH_ASSOC);
  
  $result_tahun_RP    = $result_Dtahun['TOTALD_THN'] - $result_Wtahun['TOTALW_THN'];
  $result_tahun_GOLD  = $result_Dtahun['EMASD_THN'] - $result_Wtahun['EMASW_THN'];
  
  //Transaksi Semua
  $query_Dsemua = "SELECT SUM(field_total_deposit) AS TOTALD_TRX,SUM(field_deposit_gold) AS EMASD_TRX , SUM(field_operation_fee_rp) AS FEE FROM tbldeposit WHERE field_status ='S' AND field_branch=:idbranch";
  $ST_Dsemua = $db->prepare($query_Dsemua);
  $ST_Dsemua->execute(array(':idbranch' => $branchid));
  $result_Dsemua = $ST_Dsemua->fetch(PDO::FETCH_ASSOC);
  
  $query_Wsemua = "SELECT SUM(field_rp_withdraw) AS TOTALW_TRX,SUM(field_withdraw_gold) AS EMASW_TRX FROM tblwithdraw WHERE field_status ='S' AND field_branch=:idbranch";
  $ST_Wsemua = $db->prepare($query_Wsemua);
  $ST_Wsemua->execute(array(':idbranch' => $branchid));
  $result_Wsemua = $ST_Wsemua->fetch(PDO::FETCH_ASSOC);
  
  $result_semua_RP    = $result_Dsemua['TOTALD_TRX'] - $result_Wsemua['TOTALW_TRX'];
  $result_semua_GOLD  = $result_Dsemua['EMASD_TRX'] - $result_Wsemua['EMASW_TRX'];
  
  $Query_Cabang = "SELECT COUNT(field_branch_id) AS CABANG FROM tblbranch ";
  $ST_Dhari = $db->prepare($query_Dhari);
  $ST_Dhari->execute(array(':hari' => $tanggal,':idbranch' => $branchid));
  $result_Dhari = $ST_Dhari->fetch(PDO::FETCH_ASSOC); 
}


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
        echo '<br>'.'Cabang ' .  $result['field_nama_desa'];
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
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_hari_RP); ?></h4>
          <p>Transaksi Tgl <?php echo date('d') ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_bulan_RP); ?></h4>
          <p>Transaksi Bln <?php echo date('M'); ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_tahun_RP); ?></h4>
          <p>Transaksi Thn <?php echo date('Y'); ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-black">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_semua_RP); ?></h4>
          <p>Total Transaksi</p>
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

          <h4 style="font-weight: bolder"><?php echo round($result_hari_GOLD, 6) . " gr"; ?></h4>
          <p>Emas Hari <?php echo date('d'); ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">

          <h4 style="font-weight: bolder"><?php echo round($result_bulan_GOLD, 6) . " gr"; ?></h4>
          <p>Emas Bulan <?php echo date('m'); ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo round($result_tahun_GOLD, 6) . " gr"; ?></h4>
          <p>Emas Tahun <?php echo date('Y'); ?></p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo round($result_semua_GOLD, 6) . " gr"; ?></h4>
          <p>Total Emas Unit</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo "Rp. " . number_format($result_Dsemua['FEE']); ?></h4>
          <p>Total Oprasional Fee </p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h4 style="font-weight: bolder"><?php echo round($result_Dsemua['EMASD_TRX'], 6) . " gr"; ?></h4>
          <p>Total Emas Masuk</p>
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
          <li>Mengelola data transaksi</li>
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