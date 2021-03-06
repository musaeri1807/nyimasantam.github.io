<?php 
// // ini_set('display_errors', 0);
// date_default_timezone_set('Asia/Jakarta');
// require_once("../connectionuser.php");
// session_start();

// if(!isset($_SESSION['administrator_login'])) {
//     header("location: ../index.php");
// }

$id = $_SESSION['supervisor_id'];
$branch=$_SESSION["supervisor_cabang"];                              
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin WHERE field_user_id=:uid");
$select_stmt->execute(array(":uid"=>$id));  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

//$Sql = "SELECT * FROM tblorder WHERE field_member_id=$trx_id_member AND field_status='Success' ORDER BY field_order_id DESC LIMIT 5";
$Sql = "SELECT * FROM ((produk  JOIN kategori   ON produk.produk_kategori=kategori.kategori_id ) JOIN tblbranch ON produk.branch=tblbranch.field_branch_id) WHERE branch='$branch'";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$ResultOrder = $Stmt->fetchAll();

// $sqlT = "SELECT * FROM tbltrxmutasisaldo WHERE field_member_id=$trx_id_member";
// $stmtT = $db->prepare($sqlT);
// $stmtT->execute();
// $resultT = $stmtT->fetchAll();

$no=1;
function rupiah($angka){
  $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
  return $hasil_rupiah; 
}


?>
                 
    
    <!-- Content Header (Page header) -->
     <section  class="content-header">
      <div class="row box-footer">
<!-- <section class="content"> -->
      <div class="row">
        <div class="col-xs-12">
          <!-- <div class="box"> -->
            <div class="">
            <div class="box-header">

              <h3 class="box-title"><a href="detail.php?trx_id=''" class="text-white btn btn-info "><i class="fa fa-plus"></i> Add Product Price</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>                              
                  <th>Name Product</th>                 
                  <th>Amount Price</th>
                  <th>Price Branch</th>
                  <th>Action</th>                   
                  
            
                </tr>
                </thead>
                <tbody>
             <?php 
              foreach($ResultOrder as $row) {        
                ?> 
             
                 <tr>
                  <td ><?php echo $no++?></td>
                                
                  
                  <td data-title="Trx Id"><?php echo $row["kategori"]; ?>|<?php echo $row["produk_kode"];?><br><strong><?php echo $row["produk_nama"];?></strong></td>
                  <td data-title="Trx Id"><?php echo $row["produk_keterangan"] ?>/<?php echo $row["produk_satuan"];?><br><strong><?php echo rupiah($row["produk_harga_jual"]);?></strong> <br><small> Harga Update <?php echo date("d F Y",strtotime($row["produk_date"]));  ?></small></td>
                  <td ><?php echo $row["field_branch_name"]; ?></td>
                  <td ata-title="Trx Id" >

                    <?php 
                    if ($rows["field_role"]=="Administrator") {
                      echo '<a href="detail.php?trx_id='.$rows["field_role"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      echo '<a href="detail.php?trx_id='.$rows["field_role"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                     
                    }elseif ($rows["field_role"]=="Supervisor") {
                      echo '<a href="detail.php?trx_id='.$rows["field_role"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';                     
                    }elseif ($rows["field_role"]=="Officer") {
                      echo "";
                    }elseif ($rows["field_role"]=="Superadmin") {
                      echo "Proses";
                    }
                     ?> 
                                      
                </tr>
                
              <?php } ?>
                </tbody>
         <!--        <tfoot>
                <tr>
                  <th>Trx</th>
                  <th>Rendering engine</th>
                  <th>Browser</th>
                  <th>Platform(s)</th>
                  <th>Engine version</th>
                  <th>CSS grade</th>
                </tr>
                </tfoot> -->
              </table>
            </div>
            <!-- /.box-body -->
          </div>

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- div ikut atas -->
    </div> 
    </section>


