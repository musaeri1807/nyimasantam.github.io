<?php 
// ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../connectionuser.php");
require_once ("Classes/PHPExcel.php");

// $tgl_dari    =$_GET['tanggal_dari'];
// $tgl_sampai  =$_GET['tanggal_sampai'];

$tgl_dari    ="2020-07-01";
$tgl_sampai  ="2020-08-01";

// echo $tgl_dari ;
// echo "<br>";
// echo $tgl_sampai ;


// die();

$objPHPExcel  = new PHPExcel();

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
$result = $stmtT->fetchAll();

var_dump($result);
die();


$filename="Periode".$tgl_dari."-".$tgl_sampai;

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID_Trx_Trash');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nama Trash');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Tanggal');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'No Reff');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Rekening');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Nama Nasabah');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Branch');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Price');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Quantity');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Total');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Officer Create');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Officer Code');
$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getFont()->setBold(true);

$rowCount = 2;

foreach($result AS $row) {

// while($row = $result->fetch_assoc()){sprintf("%09s",$number)

  $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper(sprintf("%09s",$row['field_deposit_id']),'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['field_product_name'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper($row['field_date_deposit'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, mb_strtoupper($row['field_no_referensi'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, mb_strtoupper($row['field_rekening_deposit'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, mb_strtoupper($row['field_nama_customer'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, mb_strtoupper($row['field_branch_name'],'UTF-8'));  
  $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($row['field_price_product'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($row['field_quantity'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, mb_strtoupper($row['field_total_price'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, mb_strtoupper($row['field_name_officer'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, mb_strtoupper($row['field_role'],'UTF-8'));
  $rowCount++;
}


$objWriter  = new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');


die();
session_start();

if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}

// if($_SESSION['administrator']!="Administrator" ){
// echo '<script>
// alert(\'Anda Menyalahi Hak AKSES!\');
// window.location="../'.$_SESSION['administrator'].'?module=home";
// </script> '; 
// }

$id = $_SESSION['administrator_id'];                               
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin JOIN tbldepartment ON tblemployeeslogin.field_role=tbldepartment.field_department_id WHERE field_user_id=:uid");
$select_stmt->execute(array(":uid"=>$id));  
$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
       
  


  
        
        // require_once 'connection.php';
        
//         session_start();

//         if(!isset($_SESSION['user_login'])) //check unauthorize user not access in "welcome.php" page
//         {
//           header("location: index");
//         }
        
//         $id = $_SESSION['user_login'];        
//         $select_stmt = $db->prepare("SELECT * FROM tbluserlogin WHERE field_user_id=:uid");
//         $select_stmt->execute(array(":uid"=>$id));  
//         $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
        
//               if(isset($_SESSION['user_login'])){              
//                  //echo "Welcome,".$row['field_nama'];
//                  // echo "<br>";
//                  // echo '<a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a>';
//                  // echo "<br>";

//                  // echo "<br>";
//                  //    $tanggal = mktime(date('m'), date("d"), date('Y'));
//                  //    echo "Tanggal : <b> " . date("d-m-Y", $tanggal ) . "</b>";
//                  //    date_default_timezone_set("Asia/Jakarta");
//                  //    $jam = date ("H:i:s");
//                  //    echo " | Pukul : <b> " . $jam . " WIB" ." </b> ";
//                  //    $a = date ("H");

//                  //    if (($a>=6) && ($a<=11)) {
//                  //        echo " <b>, Selamat Pagi !! </b>";
//                  //    }else if(($a>=11) && ($a<=15)){
//                  //        echo " , Selamat  Pagi !! ";
//                  //    }elseif(($a>15) && ($a<=18)){
//                  //        echo ", Selamat Siang !!";
//                  //    }else{
//                  //        echo ", <b> Selamat Malam </b>";
//                  //    }

//               }


// $SqlEmas2="SELECT * FROM emas ORDER BY produk_emas_id DESC LIMIT 1,1";
// $StmtEmas2 = $db->prepare($SqlEmas2);
// $StmtEmas2->execute();
// $ResultEmas2 = $StmtEmas2->fetch(PDO::FETCH_ASSOC);

// $SqlEmas="SELECT * FROM emas ORDER BY produk_emas_id DESC LIMIT 1";
// $StmtEmas = $db->prepare($SqlEmas);
// $StmtEmas->execute();
// $ResultEmas = $StmtEmas->fetch(PDO::FETCH_ASSOC);


// $HargaKemarin=$ResultEmas2['beli_emas'];
// $HargaTerkini=$ResultEmas['beli_emas'];
// $Selisi      =$HargaTerkini-$HargaKemarin;

// // echo $ResultEmas['beli_emas']." HARGA EMAS KEMARIN";
// // echo "<br>";
// // echo $ResultEmasUpdate['beli_emas']." HARGA EMAS TERKINI";
// // echo "<br>";
// // echo $Selisi;
// // echo "<br>";
// // if ($HargaKemarin>$HargaTerkini) {
// //   echo "TURUN ".$Selisi;
// // }elseif ($HargaKemarin<$HargaTerkini) {
// //   echo "NAIK ".$Selisi;
// // }elseif ($HargaKemarin==$HargaTerkini) {
// //   echo "0".$Selisi;
// // }



          
//               // jika ada session
//               // if(isset($_SESSION["user_login"])){
              
//               //     // jika tidak ada aktivitas pada browser selama 60 detik, maka ...
//               //     if((time() - $_SESSION["last_login_time"]) > 5){
                  
//               //         // akan diarahkan kehalaman logout.php
//               //         header("location: logout.php");
//               //     }else{
//               //     // jika ada aktivitas, maka update tambah waktu session
//               //         $_SESSION["last_login_time"] = time();
                      
//               //         // echo "<h3>User : <u>".$_SESSION["user_login"]."</u></h3>";
//               //          echo "<h3>Session Time : <u>".$_SESSION["last_login_time"]."</u></h3>";
//               //         // echo "<a href='logout.php'>Logout</a>";
//               //     }
//               // }   






// // $sql = "SELECT order_id FROM pesanan ORDER BY order_id DESC LIMIT 1";
// // $stmt = $db->prepare($sql);
// // $stmt->execute();
// // $order = $stmt->fetch(PDO::FETCH_ASSOC);
// // if($order['order_id']==""){
// //   $id = 1;
// // }else{  
// //   $id = $order['order_id']+1;
// // }



// // echo $ResultEmas['buyback_emas'];

// // var_dump($ResultEmas['buyback_emas']);
// // die();
// //login
// $trx_id_member=$_SESSION["login_member_id"];
// // $trx_id_member='74536085799990456';
// $sql = "SELECT * FROM tbluserlogin WHERE field_member_id=$trx_id_member ";
// $stmt = $db->prepare($sql);
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);
// //saldo
// $sqlsaldo="SELECT * FROM  tbltrxmutasisaldo WHERE field_member_id=$trx_id_member AND field_status='Success'  ORDER BY field_id_saldo DESC LIMIT 1";
// $stmtsaldo = $db->prepare($sqlsaldo);
// $stmtsaldo->execute();
// $resultsaldo = $stmtsaldo->fetch(PDO::FETCH_ASSOC);


// $Sql = "SELECT * FROM tbltrxmutasisaldo WHERE field_member_id=$trx_id_member ORDER BY field_no_referensi DESC LIMIT 5";
// $Stmt = $db->prepare($Sql);
// $Stmt->execute();
// $Result = $Stmt->fetchAll();

// $Sql = "SELECT * FROM tblorder WHERE field_member_id=$trx_id_member AND field_status='Success' ORDER BY field_order_id DESC LIMIT 5";
// //$Sql = "SELECT * FROM tblorder WHERE field_member_id=$trx_id_member";
// $Stmt = $db->prepare($Sql);
// $Stmt->execute();
// $ResultOrder = $Stmt->fetchAll();

// $sql = "SELECT field_order_id FROM tblorder ORDER BY field_order_id DESC LIMIT 1";
// $stmt = $db->prepare($sql);
// $stmt->execute();
// $order = $stmt->fetch(PDO::FETCH_ASSOC);
// if($order['field_order_id']==""){
//   $idorder = 1000;//start
// }else{  
//   $idorder = $order['field_order_id']+1;
// }

//   //noReff
//       $sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
//       $stmt = $db->prepare($sql);
//       $stmt->execute();
//       $order = $stmt->fetch(PDO::FETCH_ASSOC);
//       if($order['field_no_referensi']==""){         
//         $no=1;                  
//         $thn = date('Y');                 
//         $thn = substr( $thn,-2);
//         $reff = "Reff";                  
//         $char = $thn.$reff;
//         $noReff =$char.sprintf("%09s",$no);
//       }else{          
//         $noreff = $order['field_no_referensi'];         
//         $noUrut = substr($noreff, 6);               
//         $no=$noUrut+1;                
//         $thn = date('Y');
//         $thn = substr( $thn,-2);
//         $reff = "Reff"; 
//         $char = $thn.$reff;
//         $noReff =$char.sprintf("%09s",$no); 
//       }

// //input product insert
// // if(isset($_POST['tambahorder'])){
// //   $product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_STRING);
// //   $sql = "INSERT INTO pesanan (product) 
// //            VALUES (:product)";
// //     $stmt = $db->prepare($sql);
// //   $params = array(
// //         ":product" => $product
// //     );
// //   $saved = $stmt->execute($params);
// //   if($saved) header("Location: index.php");
// // }

// // $trx_id_member='74536081210003701';
// $sqlT = "SELECT * FROM tbltrxmutasisaldo WHERE field_member_id=$trx_id_member";
// $stmtT = $db->prepare($sqlT);
// $stmtT->execute();
// $resultT = $stmtT->fetchAll();
// // var_dump($result);
// // die;
// $no=1;

//count
// $sqlcount="SELECT * FROM tblgold";
// $count=$db->prepare($sqlcount);
// $count->execute();
// $resultCount=$count->fetchAll();

// var_dump($resultCount=$count->fetchAll());
// die();

// //$datacount=$resultCount['field_status'];

// // foreach ($resultCount as $rows ) {
// //   $status=count($rows['field_status']);  
// //       if ($status=="Success") {
// //         echo $status."<br>";
// //       }elseif ($status=="Ready") {
// //         echo $status."<br>";
// //       }elseif ($status=="Pending") {       
// //         echo $status."<br>";
// //       }elseif ($status=="Cancel") {        
// //         echo $status."<br>";
// //       }

// // }//foreach

// // //$data=$count->rowCount();
// // //var_dump();
// // die();


// $HrgB=$ResultEmas['buyback_emas'];
// $SldT=$resultsaldo['field_total_saldo'];
// $Rupiah= $HrgB*$SldT;

// function rupiah($angka){
//   $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
//   return $hasil_rupiah; 
// }


?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>NYIMASANTAM | Dashboard</title>
  <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="icon">
  <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="apple-touch-icon"> 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../asset/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../asset/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../asset/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../asset/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../asset/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../asset/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../asset/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

 <!-- komponen pada table -->
  <!-- DataTables -->
  <link rel="stylesheet" href="../asset/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


<!-- ICON BULAT BULAT -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        

</head>
<!-- <body class="hold-transition  skin-blue sidebar-mini"> -->
<body class="hold-transition  skin-blue sidebar-mini">
<!-- <body class="skin-red sidebar-mini fixed"> -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">A</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $row["field_department_name"] ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <!-- <img src="image/Logo-LM-&-ANTAM-Warna.gif" width="" height="50"> -->
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-info">0</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 0 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
              <!--   <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>

                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul> -->
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>

           <!-- <li>
            <a href="setting.php">
            <i class="fa fa-gears"></i> Setting</a>
          </li> -->

          <li>
            <a href="profile"> 
            <i class="fa fa-user"></i> <?php echo $row["field_username"]; ?></a>
          </li>   
       
          <li>
            <a href="../logout.php"> 
             <i class="fa fa-sign-out"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../uploads/<?php echo $row["field_photo"]; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <!-- <p>Muhammad Gavin Alhanan</p> -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->


      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><center>MENU</center></li>
       <!--  <li class="active treeview">--- -->
        <li>
          <a href="?module=home">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
             <!--  <i class="fa fa-angle-left pull-right"></i> -->
            </span>
          </a>
         
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>List Produk</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="?module=gold"><i class="fa fa-diamond"></i>Harga Emas</a></li>            
            <li><a href="?module=product"><i class="fa fa-recycle"></i>Harga Sampah</a></li>                      
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-exchange"></i> <span>Transaksi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="withdraw"><i class="fa fa-server"></i>Penarikan</a></li>
            <li><a href="?module=deposit"><i class="fa fa-window-restore"></i> Deposit</a></li>
            <li><a href="mutasilaporan"><i class="fa fa-window-maximize"></i> Mutasi</a></li>                      
          </ul>
        </li>
        
        <li>
          <a href="development">
            <i class="fa fa-plus"></i> <span>Extra</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>

         <li>
          <a href="setting">
            <i class="fa fa-gears"></i> <span>Setting</span>
            <span class="pull-right-container">
             <!--  <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small> -->
            </span>
          </a>
        </li>

        <li>
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Users Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!-- <li><a href="development"><i class="fa fa-user"></i>Profile</a></li>  -->
            <li><a href="?module=adminoffice"><i class="fa fa-user-secret"></i>Login Admin Office</a></li> 
            <li><a href="?module=customer"><i class="fa fa-user-plus"></i>Login Customer </a></li>            
            <li><a href="?module=activation"><i class="fa fa fa-plus"></i>Activation Customer</a></li>          
          </ul>
        </li>

         <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="?module=exporttrash"><i class="fa fa-trash"></i>Report Trash </a></li>
            <!-- <li><a href="#"><i class="fa fa-credit-card"></i>Report Deposit</a></li>
            <li><a href="#"><i class="fa fa-credit-card"></i>Report withdraw</a></li> -->
            <li><a href="?module=mutasi"><i class="fa fa-window-maximize"></i>Report Mutation</a></li>              
          </ul>
        </li>

          <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-server"></i>Connection </a></li>
            <li><a href="#"><i class="fa fa-window-restore"></i> Restore</a></li>
            <li><a href="?module=backupdatabase"><i class="fa fa-database"></i>Backup</a></li>           
          </ul>
        </li>

          <li >
          <a href="../logout">
            <i class="fa fa-sign-out"></i><span> Sign out</span>
            
            <span class="pull-right-container">
              <!-- <small class="label pull-right bg-green">new</small> -->
            </span>
          </a>
        </li>
    
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>



  <!-- Content Wrapper. Contains page content -->
  <div style="background:url(../uploads/0.png)repeat;" class="content-wrapper">
  <!-- <div class="content-wrapper"> -->
   
    <!-- <img src="image/Logo-LM-&-ANTAM-Warna.gif" width="400"> <br>
     &nbsp;&nbsp; Nyimas Community Development  Program (NCDP) PT. Antam UBPP Logam Mulia -->
                  <center>
                    <?php
                    $tanggal = mktime(date('m'), date("d"), date('Y'));
                    echo "Tanggal : <b> " . date("d F Y", $tanggal ) . "</b>";
                    date_default_timezone_set("Asia/Jakarta");
                    $jam = date ("H:i:s");
                    echo " | Pukul : <b> " . $jam . " WIB" ." </b> ";
                    $a          = date ("H:i");
                    $awalpagi   = date ("00:00");
                    $akhirpagi  = date ("11:59");
                    $awalsiang  = date ("12:00");
                    $akhirsiang = date ("14:59");
                    $awalsore   = date ("15:00");
                    $akhirsore  = date ("17:59");
                    $awalmalam  = date ("18:00");
                    $akhirmalam = date ("23:59");
                    
                   if (($a>=$awalpagi) && ($a<=$akhirpagi)) {
                        echo "<b>, Selamat Pagi ! </b>";
                    }else if(($a>=$awalsiang) && ($a<=$akhirsiang)){
                        echo "<b>, Selamat  Siang ! </b>";
                    }elseif(($a>=$awalsore) && ($a<=$akhirsore)){
                        echo "<b>, Selamat Sore !</b>";
                    }elseif(($a>=$awalmalam) && ($a<=$akhirmalam)) {
                        echo ",<b> Selamat Malam !</b>";
                    }
                    ?>
                    </center>
    
   <?php 
  if(isset($_GET['page'])){
    $page = $_GET['page'];

    switch ($page) {
      case 'home':
        include "halaman/home.php";
        break;
      case 'tentang':
        include "halaman/tentang.php";
        break;
      case 'tutorial':
        include "halaman/tutorial.php";
        break;      
      default:
        echo "<center><h3>Maaf. Halaman tidak di temukan !</h3></center>";
        break;
    }
  }else{
    include "halaman/home.php";
  }

?>                                                                 <!-- Content Header (Page header) -->
                                                                    <!--  <section  class="content-header"> -->
                     <!-- tanda GET -->  
                    

                                 <?php
                              require_once '../connectionuser.php';

                              if ($_GET['module'] =="home") {
                                include "module/home.php";
                              }elseif ($_GET['module'] == "product") {
                                include "module/product.php";
                              }elseif ($_GET['module'] == "addproduct") {
                                include "module/addproduct.php";
                              }elseif ($_GET['module']=="updproduct") {
                                include "module/updproduct.php";
                              }elseif ($_GET['module']=="gold") {
                                include "module/gold.php";
                              }elseif ($_GET['module']=="addgold") {
                                include "module/addgold.php";
                              }elseif ($_GET['module']=="updgold") {
                                include "module/updgold.php";
                              }elseif ($_GET['module']=="adminoffice") {
                                include "module/adminoffice.php";
                              }elseif ($_GET['module']=="addadminoffice") {
                                include "module/addadminoffice.php";
                              }elseif ($_GET['module']=="updadminoffice") {
                                include "module/updadminoffice.php";
                              }elseif ($_GET['module']=="customer") {
                                include "module/customer.php";
                              }elseif ($_GET['module']=="addcustomer") {
                                include "module/addcustomer.php";
                              }elseif ($_GET['module']=="updcustomer") {
                                include "module/updcustomer.php";
                              }elseif ($_GET['module']=="activation") {
                                include "module/activation.php";
                              }elseif ($_GET['module']=="deposit") {
                                include "module/deposit.php";
                              }elseif ($_GET['module']=="backupdatabase") {
                                include "module/backupdatabase.php";
                              }elseif ($_GET['module']=="mutasi") {
                                include "module/mutasilaporan.php";
                              }elseif ($_GET['module']=="exporttrash") {
                                include "module/exporttrash.php";
                              }elseif ($_GET['module']=="exporttrashexcel") {
                                include "module/exporttrashexcel.php";
                              }

                                
                              ?>                                      
                                                                  <!--   </section> -->
                                                                    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.18
    </div>
    <strong>Copyright &copy; <?php echo date ("Y"); ?> <a href="#">Admin</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <!-- <div class="control-sidebar-bg"></div> -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../asset/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../asset/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../asset/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="../asset/bower_components/raphael/raphael.min.js"></script>
<script src="../asset/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="../asset/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../asset/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../asset/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../asset/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../asset/bower_components/moment/min/moment.min.js"></script>
<script src="../asset/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="../asset/bower_components/chart.js/Chart.js"></script>
<!-- FastClick -->
<script src="../asset/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../asset/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../asset/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../asset/dist/js/demo.js"></script>
<!-- Komponen pada table  -->           
<!-- DataTables -->
<script src="../asset/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../asset/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
          
<!-- page script -->
            <script>
              $(function () {
                $('#trxSemua').DataTable()
                $('#trxTerakhir').DataTable({
                  'paging'      : true,
                  'lengthChange': false,
                  'searching'   : false,
                  'ordering'    : true,
                  'info'        : true,
                  'autoWidth'   : false
                })
              })
            </script>
<!-- page script -->
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
    // This will get the first returned node in the jQuery collection.
    var areaChart       = new Chart(areaChartCanvas)

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Electronics',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40,100]
        }
        ,
        {
          label               : 'Digital Goods',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90,50]
        }
      ]
    }

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale               : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : false,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - Whether the line is curved between points
      bezierCurve             : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension      : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius          : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth     : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke           : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth      : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill             : true,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio     : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive              : true
    }

    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions)

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartOptions         = areaChartOptions
    lineChartOptions.datasetFill = false
    lineChart.Line(areaChartData, lineChartOptions)

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
      {
        value    : 700,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Chrome'
      },
      {
        value    : 700,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'IE'
      }
      ,
      {
        value    : 400,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'FireFox'
      },
      {
        value    : 600,
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Safari'
      },
      {
        value    : 300,
        color    : '#3c8dbc',
        highlight: '#3c8dbc',
        label    : 'Opera'
      },
      {
        value    : 0,
        color    : '#d2d6de',
        highlight: '#d2d6de',
        label    : 'Navigator'
      }
    ]
    var pieOptions     = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke    : true,
      //String - The colour of each segment stroke
      segmentStrokeColor   : '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth   : 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps       : 100,
      //String - Animation easing effect
      animationEasing      : 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate        : true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale         : false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive           : true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio  : true,
      //String - A legend template
      legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
  })
</script>

</body>
</html>
