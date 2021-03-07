<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

      
if(!isset($_SESSION['administrator_login'])) {
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
            <form method="POST" class="form-horizontal" >
              <!-- <div class="row"> -->
                <div class="col-md-2">

                  <div class="form-group">
                    <label>Mulai Tanggal</label>
                    <input autocomplete="off" type="date" value="<?php if(isset($_POST['tanggal_dari'])){echo $_POST['tanggal_dari'];}else{echo "";} ?>" name="tanggal_dari" class="form-control datepicker2" placeholder="Mulai Tanggal" required >
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
            <h3 class="box-title">Data Product</h3>
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
                    <th>Date</th>
                    <th>No Reff</th> 
                    <th>Rekening</th> 
                    <th>Customer</th> 
                    <th>Branch</th>
                    <th>Price</th>
                    <th>Qty</th>                   
                    <th>Total</th>
                    <th>Officer Create</th>
                  
                    <!-- <th>Status</th> -->
                </tr>
                  </thead>
                  <tbody>
                    <?php                 
                      $sumP=0;
                      $sumT=0;
                      
                      $sqlT = "SELECT field_deposit_id,
                                      field_product_name,
                                      field_date_deposit,
                                      field_no_referensi,
                                      field_rekening_deposit,
                                      field_nama_customer,
                                      field_branch_name,
                                      field_price_product,
                                      field_quantity,
                                      field_total_price,
                                      field_name_officer,
                                      field_role
                                      FROM tbldepositdetail T JOIN tblproduct P ON  T.field_product=P.field_product_id
                                      JOIN tbldeposit I ON T.field_trx_deposit=I.field_trx_deposit 
                                      JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening 
                                      JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
                                      JOIN tblbranch B ON E.field_branch=B.field_branch_id 
                                      WHERE  date(field_date_deposit) >=:tgl_dari AND date(field_date_deposit) <= :tgl_sampai 
                                      ORDER BY field_deposit_id ASC";
                      $stmtT = $db->prepare($sqlT);
                      $stmtT->execute(array(':tgl_dari'=> $tgl_dari,':tgl_sampai'=>$tgl_sampai));
                      $resultT = $stmtT->fetchAll(); 

                      foreach($resultT as $row) {
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
                
                  <td ><?php echo sprintf("%09s",$row['field_deposit_id']);?></td>
                  <td ><?php echo $row["field_product_name"];?></td>                   
                  <!-- <td data-title="TRX ID"><strong><?php //echo $row["field_no_referensi"];?></strong><br>
                    <?php //echo $row["field_date_deposit"];?>|<small><?php //echo $row["field_product_name"];?></small> </td> -->
                  <td data-title=""><?php echo $row["field_date_deposit"];?></td>
                  <td data-title="Status"><?php echo $row["field_no_referensi"];?></td>
                  <td data-title="channel"><?php echo $row["field_rekening_deposit"];?></td>          
                  <td data-title="Status"><?php echo $row["field_nama_customer"];?></td>
                  <td data-title="channel"><?php echo $row["field_branch_name"];?></td>
                  <td data-title="Status"><?php echo rupiah($row["field_price_product"]);?></td>
                  <td data-title="channel"><?php echo $row["field_quantity"];?></td>
                  <td data-title="channel"><?php echo rupiah($row["field_total_price"]);?></td>
                  <td data-title="channel"><?php echo $row["field_name_officer"];?></td>
                </tr>
                 
               <?php
                        $sumP=$sumP+$row["field_quantity"];
                        $sumT=$sumT+$row["field_total_price"];
                } ?> 
                  </tbody>
                  <tfoot>
                    <tr class="bg-info">
                      <td colspan="8" class="text-right"><b>Total</b></td>
                      
                      <td class="text-center"><strong><?php echo number_format( $sumP); ?> Kg</strong></td>
                      <td colspan="2" class="text-left"><strong><?php echo rupiah( $sumT); ?></strong></td>
                        
                  
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

