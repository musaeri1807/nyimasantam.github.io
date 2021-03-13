<?php 
//ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}    
        
        // session_start();

        // if(!isset($_SESSION['user_login'])) //check unauthorize user not access in "welcome.php" page
        // {
        //   header("location: home");
        // }
        
        // $id = $_SESSION['user_login'];        
        // $select_stmt = $db->prepare("SELECT * FROM tbluserlogin WHERE field_user_id=:uid");
        // $select_stmt->execute(array(":uid"=>$id));  
        // $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
        
        //       if(isset($_SESSION['user_login'])){              
             
        //         #code
        //       }


$SqlEmas2="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1,1";
$StmtEmas2 = $db->prepare($SqlEmas2);
$StmtEmas2->execute();
$ResultEmas2 = $StmtEmas2->fetch(PDO::FETCH_ASSOC);

$SqlEmas="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1";
$StmtEmas = $db->prepare($SqlEmas);
$StmtEmas->execute();
$ResultEmas = $StmtEmas->fetch(PDO::FETCH_ASSOC);


$HargaKemarin=$ResultEmas2['field_sell'];
$HargaTerkini=$ResultEmas['field_sell'];
$Selisi      =$HargaTerkini-$HargaKemarin;


// $trx_id_member=$_SESSION["login_member_id"];

//$sql = "SELECT * FROM tbluserlogin WHERE field_member_id=$trx_id_member ";
$sql = "SELECT * FROM tbluserlogin";
//$stmt = $db->prepare($sql);
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//saldo
//$sql100="SELECT * FROM  tbltrxmutasisaldo WHERE field_member_id=$trx_id_member AND field_status='Success'  ORDER BY field_id_saldo DESC LIMIT 1";
$sql100="SELECT * FROM  tbltrxmutasisaldo WHERE  field_status='Success'  ORDER BY field_id_saldo DESC LIMIT 1";
$stmt100 = $db->prepare($sql100);
$stmt100->execute();
$result100 = $stmt100->fetch(PDO::FETCH_ASSOC);

$Sql = "SELECT * FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 5";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$Result = $Stmt->fetchAll();


$Sql = "SELECT * FROM tblorder WHERE field_type_order IN ('1000','2000') AND field_status!='Success'  ORDER BY field_order_id DESC";
//$Sql = "SELECT * FROM tblorder WHERE field_member_id=$trx_id_member";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$ResultOrder = $Stmt->fetchAll();

$sql = "SELECT field_order_id FROM tblorder ORDER BY field_order_id DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if($order['field_order_id']==""){
  $idorder = 1000;//start
}else{  
  $idorder = $order['field_order_id']+1;
}

  //noReff
      $sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      $order = $stmt->fetch(PDO::FETCH_ASSOC);
      if($order['field_no_referensi']==""){         
        $no=1;                  
        $thn = date('Y');                 
        $thn = substr( $thn,-2);
        $reff = "Reff";                  
        $char = $thn.$reff;
        $noReff =$char.sprintf("%09s",$no);
      }else{          
        $noreff = $order['field_no_referensi'];         
        $noUrut = substr($noreff, 6);               
        $no=$noUrut+1;                
        $thn = date('Y');
        $thn = substr( $thn,-2);
        $reff = "Reff"; 
        $char = $thn.$reff;
        $noReff =$char.sprintf("%09s",$no); 
      }


$sqlT = "SELECT * FROM tbltrxmutasisaldo";
$stmtT = $db->prepare($sqlT);
$stmtT->execute();
$resultT = $stmtT->fetchAll();

$no=1;

$HrgB=$ResultEmas['field_sell'];
$HrgJ=$ResultEmas['field_buyback'];
$SldT=$result100['field_total_saldo'];
$Rupiah= $HrgJ*$SldT;


// function rupiah($angka){
//   $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
//   return $hasil_rupiah; 
// }

// function encrypt( $q ) {
//         $cryptKey  = 'MUSAERIMUSAERIMUSAERIMUSAERIMUSAERIMUSAERIMUSAERI';
//         $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
//         return( $qEncoded );
//     }

// function decrypt( $q ) {
//         $cryptKey  = 'MUSAERIMUSAERIMUSAERIMUSAERIMUSAERIMUSAERIMUSAERI';
//         $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
//         return( $qDecoded );
//     }



if(isset($_POST['submit']))
{       
        $rekening = $_POST['txt_rekening'];
        //$date     = $_POST['txt_tanggal'];
        $date     = date('Y,m,d');
        $time     = date('H:i:s');
        $qty      = $_POST['txt_Quantity'];
        $inRp     = $_POST['txt_Rupiah'];
        $gram     = $_POST['txt_Gram'];
        $select   = $_POST['txt_select_type'];
        $qtygram  = $gram*$qty;
        $goldprice= $qtygram*$HrgB;
        $goldn    = $inRp/$HrgJ;
        $gold     = round($goldn,6);
        $goldn    = $gold*1;
        $salgold1 = $SldT-$qtygram;
        $salgold2 = $SldT-$gold;

  
        if ($select==3000) {
            $errorMsg[]="Klik Pilih Terlebih dahulu";          
        }elseif ($select==2000) {
          if ($inRp !=0) {
            
            if ($SldT>=$gold) {
             
              $SaldoNgedap=$SldT-$gold;              
              if ($SaldoNgedap<0.05) {
                
                $errorMsg[]="Saldo Kurang Dari Ketentuan";
              } else {
                 
                $sql="INSERT INTO tblorder 
                (field_order_id,field_member_id,field_tanggal_order,field_trx_id,field_gold,field_price_gold,field_type_order,field_status,field_quantity,field_gold_total)
                VALUES
                (:idorder,:idmember,:ortggl,:reff,:gold,:goldprice,:typeselect,:status,:qty,:gramqty)";
                $insert=$db->prepare($sql);
                if ($insert->execute(array(
                        ':idorder'=>$idorder,
                        ':idmember'=>$trx_id_member,
                        ':ortggl'=>$date,
                        ':reff'=>$noReff,
                        ':gold'=>$gold,
                        ':goldprice'=>$inRp,
                        ':typeselect'=>$select,
                        ':status'=>"Pending",
                        ':qty'=>"1",
                        ':gramqty'=>$gold
                          ))
                    ){
                  
                    $sqlmutasi="INSERT INTO tbltrxmutasisaldo 
                        (field_member_id,field_no_referensi,field_rekening,field_tanggal_saldo,field_time,field_debit_saldo,field_total_saldo,field_type_saldo,field_comments) VALUES
                        (:id_member,:reff,:rek,:tgl,:timee,:gold,:goldsal,:debittype,:comment)";
                    $insertmutasi=$db->prepare($sqlmutasi);
                    $insertmutasi->execute(array(
                          ':id_member'=>$trx_id_member,
                          ':reff'=>$noReff,
                          ':rek'=>$rekening,
                          ':tgl'=>$date,
                          ':timee'=>$time,
                          ':gold'=>$gold,
                          ':goldsal'=>$salgold2,
                          ':debittype'=>200,
                          ':comment'=>"Order Debit"
                    ));
                    header("Location:withdraw");
                }//end#            
              }              

            } else {
              
              $errorMsg[]="Saldo Tidak Mencukupi";
            }

          }else{
            
            $errorMsg[]="Anda Belum Input Nilai Rupiah";
          }  
        }elseif ($select==1000) {
          if ($qtygram !=0) {
            
            if ($SldT>=$qtygram) {
             
              $SaldoNgedap=$SldT-$qtygram;              
              if ($SaldoNgedap<0.05) {
                
                $errorMsg[]="Saldo Kurang Dari Ketentuan";
              } else {
                
                $sql="INSERT INTO tblorder 
                  (field_order_id,field_member_id,field_tanggal_order,field_trx_id,field_gold,field_price_gold,field_type_order,field_status,field_quantity,field_gold_total)
                  VALUES
                  (:idorder,:idmember,:ortggl,:reff,:gold,:goldprice,:typeselect,:status,:qty,:gramqty)";
                $insert=$db->prepare($sql);
                if ($insert->execute(array(

                        ':idorder'=>$idorder,
                        ':idmember'=>$trx_id_member,
                        ':ortggl' =>$date,
                        ':reff'=>$noReff,
                        ':gold'=>$gram,
                        ':goldprice'=>$goldprice,
                        ':typeselect'=>$select,
                        ':status'=>"Pending",
                        ':qty'=>$qty,
                        ':gramqty'=>$qtygram
                          ))
                    ) {
                 
                   $sqlmutasi="INSERT INTO tbltrxmutasisaldo 
                        (field_member_id,field_no_referensi,field_rekening,field_tanggal_saldo,field_time,field_debit_saldo,field_total_saldo,field_type_saldo,field_comments) VALUES
                        (:id_member,:reff,:rek,:tgl,:timee,:gold,:goldsal,:debittype,:comment)";
                    $insertmutasi=$db->prepare($sqlmutasi);
                    $insertmutasi->execute(array(
                          ':id_member'=>$trx_id_member,
                          ':reff'=>$noReff,
                          ':rek'=>$rekening,
                          ':tgl'=>$date,
                          ':timee'=>$time,
                          ':gold'=>$qtygram,
                          ':goldsal'=>$salgold1,
                          ':debittype'=>200,
                          ':comment'=>"Order Debit"

                    ));              
                    header("Location:withdraw");
                }//End#
              }              

            } else {
              
              $errorMsg[]="Saldo Tidak Mencukupi";
            }
             
            
          }else{
            
            $errorMsg[]="Anda Belum Input Nilai Emas";
          }
        }
       
}
?>



<!-- tanda GET -->

     <section  class="content-header">
      <div class="row box-footer">

        <div class="col-lg-6">
            <div class="col-lg-12">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">Harga Jual
              <h3 ><center><?php echo rupiah ($ResultEmas['field_buyback']); ?>,-</center> </h3>
              <p>Saldo Setara <?php echo rupiah ($Rupiah);?>,- </p>
            </div>
            <div class="icon">
              <!-- <i class="ion ion-stats-bars"></i> -->
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="#" target="_blank" class="small-box-footer">Info Buyback</a>
          </div>
        </div>
        </div>
                    
        <!--  <br> -->
        <div class="col-lg-6">
              <?php
                if(isset($errorMsg)){
                  foreach($errorMsg as $error){
              ?>
            <div class="form-group">             
              <center>
                <div class="btn-warning">
                <strong><?php echo $error; ?></strong>
                </div>
              </center>        
            </div>
              <?php
                  }
                }  
              ?> 
            <form method="POST" class="form-horizontal">
            <div class="form-group">
            <center>
            <select class="form-control" type="text" name="txt_rekening">            
            <option value="<?php echo $result100['field_rekening']; ?>"><?php echo $result100['field_rekening']; ?></option>     
            </select>
            </center>             
            </div>

            <div class="form-group">
            <center>
            <select class="form-control" id="select-type" name="txt_select_type">
            <option value="3000">Pilih</option>
            <option value="1000">Request Cetak Fisik</option>
            <option value="2000">Request Buyback</option>
            </select>
            </center>             
            </div>

            <div class="form-group" >
            <div class="row">
            <div class="col-xs-4">                              
            <select class="form-control" id="cf1" name="txt_Gram" style="display: none;">
            <option id="gram" value="0">0g</option>
            <option id="gram" value="1">Kepingan 1g</option>
            <option id="gram" value="2">Kepingan 2g</option>
            <option id="gram" value="3">Kepingan 3g</option>
            <option id="gram" value="5">Kepingan 5g</option>
            <option id="gram" value="10">Kepingan 10g</option>
            <option id="gram" value="25">Kepingan 25g</option>             
            </select>
            <!-- <input class="form-control" type="number" name="txt_Rupiah" placeholder="Buyback Rupiah" id="rp1" value="0" style="display:none;"> -->
            <select class="form-control" id="rp1" name="txt_Rupiah" style="display: none;">
            <option id="rupiah" value="0">Rp 0,-</option>
            <option id="rupiah" value="50000">Rp 50.000,-</option>
            <option id="rupiah" value="100000">Rp 100.000,-</option>
            <option id="rupiah" value="150000">Rp 150.000,-</option>
            <option id="rupiah" value="200000">Rp 200.000,-</option>
            <option id="rupiah" value="500000">Rp 500.000,-</option>
            <option id="rupiah" value="1000000">Rp 1.000.000,-</option>             
            </select>                             
            </div>
            <div class="col-xs-4">
            <select class="form-control" id="cf2" name="txt_Quantity" style="display: none;">
            <option value="1">1</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>            
            <!-- <input type="text" class="form-control" placeholder=".col-xs-4" id="cf2" value="Qty" style="display:none;" readonly> -->

            <input type="hidden" class="form-control" placeholder=".col-xs-4" id="rp2" value="Muncul Emas" style="display:none;" readonly>
            </div>
            <div class="col-xs-4">
            <input type="text" class="form-control" name="txt_tanggal" value="<?php echo date('d-F-y') ?>" readonly>
            </div>
            </div>
            </div>
            <!-- <div class="form-group">                   
            <div class="row">
            <div class="col-xs-4">
            <input type="text" class="form-control" placeholder=".col-xs-3">
            </div>
            <div class="col-xs-4">
            <input type="text" class="form-control" placeholder=".col-xs-4">
            </div>
            <div class="col-xs-4">
            <input type="text" class="form-control" placeholder=".col-xs-5">
            </div>
            </div>
            </div> -->
            <div class="form-group">
              <center>
            
            
            <input type="submit"  name="submit" class="btn btn-danger fa fa-money" value="Tambah Order">            
              </center>             
            </div>
            </form>
        </div>
      
     <!--  </div> -->
        
<!--         </section>
        <hr >

<section class="content"> -->
      <div class="row">
        <div class="col-xs-12"><hr> 
          <!-- <div class="box"> -->
            <div class="">
            <div class="box-header">

              <h3 class="box-title">Transaksi</h3>
            </div>
       <!-- /.box-header -->
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="20">Action</th>
              
                  <th>No Trx</th>                 
                  
                  <th>Amount</th>                  
                  
            
                </tr>
                </thead>
                <tbody>
                  <?php 
                  foreach($ResultOrder as $row) {
                    $status = $row["field_status"];
                    //$tindakan= $row["field_order_id"];
                    if($status  =="Pending"){
                            $status = '<span class="badge btn-danger text-white">Menunggu</span>';
                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-danger "><i class="fa fa-download"></i> Detail</a> &nbsp';     
                            $tindakan='';
                    }elseif($status =="Success"){
                            //$status   = '<span class="badge btn-success text-white">Success</span>';
                            $status   = '';
                            $tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-success "><i class="fa fa-download"></i> Detail</a> &nbsp';
                    }elseif($status =="Proses") {
                            $status ='<span class="badge btn-info text-white">Proses</span>';
                           //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-info "><i class="fa fa-download"></i> Detail</a> &nbsp';
                           $tindakan='';
                    }elseif($status =="Cancel") {
                            $status ='<span class="badge btn-dark text-white">Cancel</span>';
                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-danger "><i class="fa fa-download"></i> Detail</a> &nbsp';
                            $tindakan='';
                    }elseif($status =="Ready") {
                            $status ='<span class="badge btn-warning text-white">Ready</span>';
                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-warning "><i class="fa fa-download"></i> Detail</a> &nbsp';
                            $tindakan='';
                             
                    }


                    $Types = $row["field_type_order"];
                    if($Types=="1000"){
                    $Types = '<span class="badge btn-warning text-white">(-) Cetak Emas</span>';                                         
                    }elseif($Types=="2000"){
                    $Types = '<span class="badge btn-info text-white">(-) Buyback</span>';                        
                    }elseif ($Types=="4000") {
                      # code...
                      $Types = '<span class="badge btn-primary text-white">(+) Deposit Rp</span>';
                    }elseif ($Types=="5000") {
                      # code...
                      $Types = '<span class="badge btn-success text-white">(+) Deposit Emas</span>';
                    }

                ?>
             
                <tr>
                  <td data-title="Aksi"><?php echo $status;?>&nbsp<br>&nbsp<?php echo $tindakan;?></td>                  
                  
                  <td data-title="Trx Id"><?php echo $row["field_order_id"] ?>|<br>
                    <strong><?php echo $row["field_trx_id"];?></strong><br>
                    <?php echo $row["field_tanggal_order"];?></td>                  
                            <?php                            
                            if ($row["field_type_order"]==1000) {
                            echo '<td><strong>'.$row["field_gold"].'-g</strong><br><small>Qty='.$row["field_quantity"].'</small><br>'.$Types.'</td>';                             
                            }elseif ($row["field_type_order"]==2000) {
                            echo '<td><strong>'.rupiah($row["field_price_gold"]).'</strong><br>'.'---'.'<br>'.$Types.'</td>';
                            }elseif ($row["field_type_order"]==4000) {
                            echo '<td><strong>'.rupiah($row["field_price_gold"]).'</strong><br>'.'---'.'<br>'.$Types.'</td>';
                            }elseif ($row["field_type_order"]==5000) {
                             echo '<td><strong>'.$row["field_gold"].'-g</strong><br>'.'---'.'<br>'.$Types.'</td>';
                            }
                            ?>                 
                </tr>
                
               <?php } ?> 
                </tbody>
             <!--    <tfoot>
                <tr>
                  <th width="20">No</th>
                  <th>Trx Id</th>
                  <th>Harga</th>
                  <th>Status</th>
                  <th>Tindakan</th>
                </tr>
                </tfoot> -->
              </table>

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

 