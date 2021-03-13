<?php 
// ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");      
session_start();
if(!isset($_SESSION['user_login'])) 
{
  header("location: ../index");
}

        

$id = $_SESSION['user_login'];
$select_stmt = $db->prepare("SELECT * FROM tbluserlogin WHERE field_user_id=:uid");
$select_stmt->execute(array(":uid"=>$id));
$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_SESSION['user_login'])){              

}





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
  

$trx_id_member=$_SESSION["login_member_id"];
// $trx_id_member='74536085799990456';
$sql = "SELECT * FROM tbluserlogin U JOIN tblbranch B ON U.field_branch=B.field_branch_id WHERE field_member_id=$trx_id_member ";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//saldo

$sqlsaldo="SELECT * FROM  tbltrxmutasisaldo WHERE field_member_id=$trx_id_member AND field_status='Success'  ORDER BY field_id_saldo DESC LIMIT 1";
$stmtsaldo = $db->prepare($sqlsaldo);
$stmtsaldo->execute();
$resultsaldo = $stmtsaldo->fetch(PDO::FETCH_ASSOC);

$Sql = "SELECT * FROM tbltrxmutasisaldo WHERE field_member_id=$trx_id_member ORDER BY field_no_referensi DESC LIMIT 5";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$Result = $Stmt->fetchAll();


$Sql = "SELECT * FROM tblorder WHERE field_member_id=$trx_id_member AND field_status='Success' ORDER BY field_order_id DESC LIMIT 5";
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


// $trx_id_member='74536081210003701';

$sqlT = "SELECT * FROM tbltrxmutasisaldo WHERE field_member_id=$trx_id_member";
$stmtT = $db->prepare($sqlT);
$stmtT->execute();
$resultT = $stmtT->fetchAll();
$no=1;

$sqlcount="SELECT * FROM tblorder WHERE field_member_id=$trx_id_member";
$count=$db->prepare($sqlcount);
$count->execute();
//$resultCount=$count->fetch(PDO::FETCH_ASSOC);
$resultCount=$count->fetchAll();

$HrgB=$ResultEmas['field_buyback'];
$SldT=$resultsaldo['field_total_saldo'];
$Rupiah= $HrgB*$SldT;



function rupiah($angka){

  $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');

  return $hasil_rupiah; 

}


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
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../view/assetdashboard/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../view/assetdashboard/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../view/assetdashboard/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

 <!-- komponen pada table -->
  <!-- DataTables -->
  <link rel="stylesheet" href="../view/assetdashboard/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- ICON BULAT BULAT -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        

</head>

<!-- <body class="hold-transition  skin-blue sidebar-mini"> -->

<body class="hold-transition  skin-red sidebar-mini">

<!-- <body class="skin-red sidebar-mini fixed"> -->

<div class="wrapper">



  <header class="main-header">

    <!-- Logo -->

    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="../uploads/iconnyimas.png"></span>
      <!-- logo for regular state and mobile devices -->
      <!-- <span class="logo-lg"><b><?php //echo $row["field_department_name"] ?></b></span> -->
      <span class="logo-lg"><b><img src="../uploads/logonyimas.png"></b></span>
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
            <i class="fa fa-user"></i> Profile</a>
          </li>         
          <li>
            <a href="logout"> 
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
          <img src="../uploads/<?php echo $result['field_photo'] ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form -->





      <!-- /.search form -->

      <!-- sidebar menu: : style can be found in sidebar.less -->

      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><center>MENU MAIN</center></li>
       <!--  <li class="active treeview">--- -->
        <li>
          <a href="?module=home">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
<!--             <span class="pull-right-container">
             <i class="fa fa-angle-left pull-right"></i>
            </span> -->
          </a>         

        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>List Product</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="?module=gold"><i class="fa fa-diamond"></i>Price Gold</a></li>            
            <li><a href="?module=product"><i class="fa fa-recycle"></i>Price Trash</a></li>                      
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-exchange"></i> <span>Transaction</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="withdraw"><i class="fa fa-server"></i>Withdraw</a></li>
            <li><a href="deposit"><i class="fa fa-window-restore"></i>Deposit</a></li>
            <li><a href="mutasilaporan"><i class="fa fa-window-maximize"></i>Mutation</a></li>               
          </ul>

        </li>

        

     <!--    <li>

          <a href="development">

            <i class="fa fa-plus"></i> <span>Extra</span>

            <span class="pull-right-container">

              <small class="label pull-right bg-red">3</small>

              <small class="label pull-right bg-blue">17</small>

            </span>

          </a>

        </li> -->



         <li>

          <a href="setting">
            <i class="fa fa-gears"></i> <span>Setting</span>
            <span class="pull-right-container">
             <!--  <small class="label pull-right bg-red">3</small>

              <small class="label pull-right bg-blue">17</small> -->

            </span>

          </a>

        </li>



        <!-- <li>

          <a href="pages/mailbox/mailbox.html">

            <i class="fa fa-envelope"></i> <span>Mailbox</span>

            <span class="pull-right-container">

              <small class="label pull-right bg-yellow">12</small>

              <small class="label pull-right bg-green">16</small>

              <small class="label pull-right bg-red">5</small>

            </span>

          </a>

        </li> -->



       <!--  <li class="treeview">

          <a href="#">

            <i class="fa fa-users"></i> <span>Users Management</span>

            <span class="pull-right-container">

              <i class="fa fa-angle-left pull-right"></i>

            </span>

          </a>

          <ul class="treeview-menu">

            <li><a href="development"><i class="fa fa-user"></i>Profile</a></li> 

            <li><a href="development"><i class="fa fa-user-plus"></i>Nasabah </a></li>            

            <li><a href="setting.php"><i class="fa fa-user-secret"></i>Admin</a></li> 

            <li><a href="pages/calendar.html"><i class="fa fa fa-plus"></i>Extra</a></li>          

          </ul>

        </li> -->



        <!--  <li class="treeview">

          <a href="#">

            <i class="fa fa-gears"></i> <span>Setting</span>

            <span class="pull-right-container">

              <i class="fa fa-angle-left pull-right"></i>

            </span>

          </a>

          <ul class="treeview-menu">

            <li><a href="setting_db.php"><i class="fa fa-server"></i>Connection </a></li>

            <li><a href="setting_restore.php"><i class="fa fa-window-restore"></i> Restore</a></li>

            <li><a href="setting_backup.php"><i class="fa fa-database"></i>Backup</a></li>           

          </ul>

        </li> -->



          <li >

          <a href="./logout">
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

<!-- tanda GET -->



  <!-- Content Wrapper. Contains page content -->

  <!-- <div class="content-wrapper"> -->
  <div style="background:url(../uploads/0.png)repeat;" class="content-wrapper">


                  <center>

                    <?php 

                    $tanggal = mktime(date('m'), date("d"), date('Y'));
                    echo "Tanggal : <b> " . date("d-m-Y", $tanggal ) . "</b>";
                    date_default_timezone_set("Asia/Jakarta");
                    $jam = date ("H:i:s");
                    echo " | Pukul : <b> " . $jam . " WIB" ." </b> ";
                    $a          = date ("H:i");

                    $awalpagi   = date ("00:01");
                    $akhirpagi  = date ("12:00");
                    $awalsiang  = date ("12:01");
                    $akhirsiang = date ("15:00");
                    $awalsore   = date ("15:01");
                    $akhirsore  = date ("18:00");
                    $awalmalam  = date ("18:01");
                    $akhirmalam = date ("24:00");

                    

                   if (($a>=$awalpagi) && ($a<=$akhirpagi)) {
                        echo "<b>, Selamat Pagi !! </b>";
                    }else if(($a>=$awalsiang) && ($a<=$akhirsiang)){
                        echo "<b>, Selamat  Siang !! </b>";
                    }elseif(($a>=$awalsore) && ($a<=$akhirsore)){
                        echo "<b>, Selamat Sore !!</b>";
                    }elseif(($a>=$awalmalam) && ($a<=$akhirmalam)) {
                        echo ",<b> Selamat Malam !!</b>";
                    }
                    ?>

                    </center>

<!-- <?php
// require_once '../connectionuser.php';
//if ($_GET['module'] =="home") {
//include "module/home.php";
//}                  
?>  -->

    
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <div class="pull-right hidden-xs">

      <b>Version</b> 2.1.21

    </div>

    <strong>Copyright &copy; 2021 <a href="#">Admin</a>.</strong> All rights

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
<script src="../view/assetdashboard/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../view/assetdashboard/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../view/assetdashboard/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="../view/assetdashboard/bower_components/raphael/raphael.min.js"></script>
<script src="../view/assetdashboard/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="../view/assetdashboard/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../view/assetdashboard/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../view/assetdashboard/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../view/assetdashboard/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../view/assetdashboard/bower_components/moment/min/moment.min.js"></script>
<script src="../view/assetdashboard/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../view/assetdashboard/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../view/assetdashboard/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../view/assetdashboard/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="../view/assetdashboard/bower_components/chart.js/Chart.js"></script>
<!-- FastClick -->
<script src="../view/assetdashboard/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../view/assetdashboard/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../view/assetdashboard/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../view/assetdashboard/dist/js/demo.js"></script>
<!-- Komponen pada table  -->           
<!-- DataTables -->
<script src="../view/assetdashboard/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../view/assetdashboard/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

          

<!-- page script -->

            <script>

              $(function () {

                $('#example1').DataTable()
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



</body>

</html>

