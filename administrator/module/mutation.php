<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

      
if(!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

?>


  <section class="content">
    <div class="row">
      <section class="col-lg-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Filter Mutasi</h3>
          </div>
          <div class="box-body">
            <form method="POST" class="form-horizontal" >
              <!-- <div class="row"> -->
                <div class="col-md-2">

                  <div class="form-group">
                    <label>Mulai Tanggal</label>
                    <input autocomplete="off" type="date" value="<?php if(isset($_POST['tanggal_dari'])){echo $_POST['tanggal_dari'];}else{echo "";} ?>" name="tanggal_dari" class="form-control datepicker2" placeholder="Mulai Tanggal" required>
                  </div>

                </div>

                <div class="col-md-2">

                  <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input autocomplete="off" type="date" value="<?php if(isset($_POST['tanggal_sampai'])){echo $_POST['tanggal_sampai'];}else{echo "";} ?>" name="tanggal_sampai" class="form-control datepicker2" placeholder="Sampai Tanggal" required>
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
              if(isset($_POST['tanggal_sampai']) && isset($_POST['tanggal_dari']) ){
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
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Types</th>
                    <th>Amount</th>
                    <th>Sell</th>
                    <th>Buyback</th>                  
                    <th>Status</th>
                </tr>
                  </thead>
                  <tbody>
                    <?php                 
                      $sumK=0;
                      $sumD=0;
                      $sqlT = "SELECT * FROM tbltrxmutasisaldo M JOIN tbluserlogin U ON M.field_member_id=U.field_member_id
                                                                 JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
                                                                 JOIN tblbranch B ON U.field_branch=B.field_branch_id
                                                                 WHERE  date(field_tanggal_saldo) >= '$tgl_dari' AND date(field_tanggal_saldo) <= '$tgl_sampai'
                                                                 ORDER BY field_id_saldo DESC";
                      $stmtT = $db->prepare($sqlT);
                      $stmtT->execute(array(':tgl_dari'=> $tgl_dari,':tgl_sampai'=>$tgl_sampai));
                      $resultT = $stmtT->fetchAll(); 

                    foreach($resultT as $row) {
                    $status = $row["field_status"];
                    if($status=="Pending"){
                      $status = '<span class="badge btn-danger text-white">Menunggu Pembayaran</span>';
                      $tindakan = '<a href="'.$row["field_id_saldo"].'" class="text-white btn btn-success btn"><i class="fa fa-credit-card"></i>  Payment</a>   &nbsp';
                     

                    }else if($status=="Success"){
                      $status = '<span class="badge btn-success text-white">Pembayaran Berhasil</span>';
                      $tindakan = '<a href="detail.php?trx_id='.$row["field_id_saldo"].'" class="text-white btn btn-info "><i class="fa fa-download"></i> Detail</a> &nbsp';              
                    }
                        $Types = $row["field_type_saldo"];
                        if($Types=="200"){
                          $Types = '<span class="badge btn-warning text-white">Debit</span>';
                         
                        }else if($Types=="100"){
                          $Types = '<span class="badge btn-primary text-white">Kredit</span>';
                         
                        }else if($Types=="300"){
                          $Types = '<span class="badge btn-dark text-white">Balance</span>';
                        
                        }

                ?>
             
                <tr>
                
                  <td ><?php echo sprintf("%09s",$row['field_id_saldo']);?></td>                  
                  
                  <td data-title="waktu_bayar"><?php echo $row["field_no_referensi"];?></td>

                  <td data-title="Status"><?php echo $row["field_tanggal_saldo"];?></td>
                  <td data-title="Status"><?php echo $row["field_rekening"];?></td>
                  <td data-title="Status"><?php echo $row["field_nama"];?></td>
                  <td data-title="Status"><?php echo $row["field_branch_name"];?></td>
                  <td data-title="Status"><?php echo $Types;?></td>
                    <?php                        
                         
                          if ($row['field_kredit_saldo']=="0") {                          
                            echo '<td data-title="Saldo">'.'<strong>'.$row['field_debit_saldo'].'-g'.'</strong>'.'</td>';
                          }elseif ($row['field_debit_saldo']=="0") {                          
                            echo '<td data-title="Saldo">'.'<strong>'.$row['field_kredit_saldo'].'-g'.'</strong>'.'</td>';
                          }                  
                    ?>                 
                  <td data-title="Status"><?php echo rupiah($row['field_sell']);?></td>
                  <td data-title="Status"><?php echo rupiah($row['field_buyback']);?></td>
                  <td data-title="Status"><?php echo $row["11"];?></td>
                  
                </tr>
                
               <?php 
                        $sumK=$sumK+$row["field_kredit_saldo"];
                        $sumD=$sumD+$row["field_debit_saldo"];
                } ?> 
                  </tbody>
                  <tfoot>
                    <tr class="bg-info">
                      <td colspan="7" class="text-right"><b>In Total Gold</b></td>
                      <td class="text-center"><strong><?php 
                        $SUM=$sumK-$sumD;

                        echo $SUM; ?>,-g</strong></td>
                     
                  
                    </tr>
                  </tfoot>
                </table>



              </div>

              <?php 
              }else{
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

