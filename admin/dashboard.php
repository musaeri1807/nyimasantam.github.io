<?php
// ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
session_start();

if (!isset($_SESSION['userlogin'])) {
  header("location: ../loginv2.php");
}
$idemploye = $_SESSION['idlogin'];
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin E JOIN tbldepartment D ON E.field_role=D.field_department_id
                                                               JOIN tblbranch B ON E.field_branch=B.field_branch_id
                                                               JOIN tblpermissions P ON E.field_role=P.role_id
                                                              WHERE E.field_user_id=:uid");
$select_stmt->execute(array(":uid" => $idemploye));
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);
$permission = $rows['add'];
$branchid = $rows['field_branch'];
// echo $branchid;


$SQL = "SELECT * FROM tblstatus ";
$STMT = $db->prepare($SQL);
$STMT->execute();
$ST = $STMT->fetchAll();


$Sqlmenu = "SELECT * FROM tblmenu M JOIN tblemployeaccessmenu AM ON M.field_idmenu=AM.field_idmenu
WHERE AM.field_role_id=:roleid AND M.field_is_active='Y'
ORDER BY AM.field_idmenu ASC ";
$Stmtmenu = $db->prepare($Sqlmenu);
$Stmtmenu->execute(array(":roleid" => $rows['field_role']));
$menu = $Stmtmenu->fetchAll();



// foreach ($menu as $menusub) {
//   echo $menusub['field_menu'];
//   echo '<br>';

//   $id = $menusub['field_idmenu'];
//   $Sqlsubmenu = "SELECT * FROM tblmenusub SM JOIN tblmenu M ON SM.field_idmenusub=M.field_idmenu
//   WHERE SM.field_idmenu=:menuid AND field_isactive='Y'";
//   $Stmtsubmenu  = $db->prepare($Sqlsubmenu);
//   $Stmtsubmenu->execute(array(":menuid" => $id));
//   $submenu = $Stmtsubmenu->fetchAll();
//   // $submenu =$Stmtmenu->fetch(PDO::FETCH_ASSOC);

//   // while ($submenu =$Stmtmenu->fetch(PDO::FETCH_ASSOC)){
//   //   echo $submenu['field_submenu'];
//   //   echo '<br>';
//   //   }
//   // //   die();

//   foreach ($submenu as $menusub) {
//     echo $menusub['field_submenu'];
//     echo '<br>';
//   }
// }
// die();
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BSP| Dashboard</title>
  <!-- <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="icon">
  <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="apple-touch-icon"> -->
  <link href="../image/icon_bspid.png" rel="icon">
  <link href="../image/icon_bspid.png" rel="apple-touch-icon">
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

<body class="hold-transition  skin-blue sidebar-mini">
  <!-- <body class="skin-blue sidebar-mini sidebar-collapse"> -->
  <!-- <body class="skin-red sidebar-mini fixed"> -->
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src=""></span>
        <!-- logo for regular state and mobile devices -->

        <span class="logo-lg"><b><img src=""></b></span>
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
            <i class="fa fa-gears"></i> Password</a>
          </li> -->

            <!-- <li>
            <a href="#"> 
            <i class="fa fa-user"></i> <?php echo $rows["field_name_officer"]; ?></a>
          </li>   -->

            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Menu <b class="caret"></b>
              </a>
              <ul class="dropdown-menu dropdown-user">
                <li>
                  <a href="?module=profileadmin"><i class="fa fa-user"></i>Profile</a>
                </li>
                <!-- <li>
                  <a href="#"><i class="fa fa-gear"></i>Settings</a>
                </li> -->
                <li>
                  <a href="?module=changepassword"><i class="fa fa-shield"></i>Security</a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                </li>
              </ul>
            </li>


            <!-- <li>
            <a href="../logout.php"> 
             <i class="fa fa-sign-out"></i></a>
          </li> -->
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
            <?php
            if ($rows['field_gender'] == 'L') {
              echo '<img src="../uploads/avatar5.png" class="img-circle" alt="User Image">';
            } elseif ($rows['field_gender'] == 'P') {
              echo '<img src="../uploads/avatar2.png" class="img-circle" alt="User Image">';
            } else {
              echo '<img src="../uploads/1.png" class="img-circle" alt="User Image">';
            }
            ?>
          </div>
          <div class="pull-left info">
            <p><?php echo $rows["field_name_officer"]; ?></p>
            <!-- <p>Muhammad Gavin Alhanan</p> -->
            <a href="#"><i class="fa fa-user o  text-success"></i><?php echo $rows['field_branch_name'] ?> </a> <br>
          </div>
        </div>
        <!-- search form -->

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">
            <center>Menu <?php echo $rows["field_department_name"] ?></center>
          </li>
          <!--  <li class="active treeview">--- -->


          <li>
            <a href="?module=home">
              <i class="fa fa-dashboard"></i> <span> |<?php echo $rows["field_department_name"] ?></span>
              <span class="pull-right-container">
                <!--  <i class="fa fa-angle-left pull-right">Dashboard</i> -->
              </span>
            </a>
          </li>

          <?php foreach ($menu as $menusidebar) { ?>
            <li class="treeview">
              <a href="#">
                <i class="<?php echo $menusidebar['field_icons']; ?>"></i> <span><?php echo $menusidebar['field_menu']; ?></span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php
                $id = $menusidebar['field_idmenu'];
                $Sqlsubmenu = "SELECT * FROM tblmenusub SM JOIN tblmenu M ON SM.field_idmenusub=M.field_idmenu
                WHERE SM.field_idmenu=:menuid AND field_isactive='Y'";
                $Stmtsubmenu = $db->prepare($Sqlsubmenu);
                $Stmtsubmenu->execute(array(":menuid" => $id));
                $submenu = $Stmtsubmenu->fetchAll();

                foreach ($submenu as $menusub) { ?>
                  <li><a href="<?php echo $menusub['field_url']; ?>"><i class="<?php echo $menusub['field_icon']; ?>"></i><?php echo $menusub['field_submenu']; ?></a></li>
                <?php
                }
                ?>

              </ul>
            </li>
          <?php  } ?>


          <!-- <li class="treeview">
            <a href="#">
              <i class="fa fa-database"></i> <span>Database</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-server"></i>Connection </a></li>
              <li><a href="?module=formcustomer"><i class="fa fa-window-restore"></i> Restore</a></li>
              <li><a href="?module=backupdatabase"><i class="fa fa-clone">  </i>Backup</a></li>
            </ul>
          </li> -->



          <li>
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

        $date = date('Y-m-d');
        $tanggal = mktime(date('m'), date("d"), date('Y'));
        echo "Tanggal : <b> " . date("d F Y", $tanggal) . "</b>";
        date_default_timezone_set("Asia/Jakarta");
        $jam = date("H:i:s");
        echo " | Pukul : <b> " . $jam . " WIB" . " </b> ";
        $a          = date("H:i");
        $awalpagi   = date("00:00");
        $akhirpagi  = date("11:59");
        $awalsiang  = date("12:00");
        $akhirsiang = date("14:59");
        $awalsore   = date("15:00");
        $akhirsore  = date("17:59");
        $awalmalam  = date("18:00");
        $akhirmalam = date("23:59");

        if (($a >= $awalpagi) && ($a <= $akhirpagi)) {
          echo "<b>, Selamat Pagi ! </b>";
        } else if (($a >= $awalsiang) && ($a <= $akhirsiang)) {
          echo "<b>, Selamat  Siang ! </b>";
        } elseif (($a >= $awalsore) && ($a <= $akhirsore)) {
          echo "<b>, Selamat Sore !</b>";
        } elseif (($a >= $awalmalam) && ($a <= $akhirmalam)) {
          echo ",<b> Selamat Malam !</b>";
        }
        ?>
      </center>

      <!-- Content Header (Page header) -->
      <!--  <section  class="content-header"> -->
      <!-- tanda GET -->
      <?php
      // require_once '../connectionuser.php';

      if ($_GET['module'] == "home") {
        include "module/home.php";
      } elseif ($_GET['module'] == "menu") {
        include "module/menu.php";
      } elseif ($_GET['module'] == "product") {
        include "module/product.php";
      } elseif ($_GET['module'] == "category") {
        include "module/category.php";
      } elseif ($_GET['module'] == "addproduct") {
        include "module/addproduct.php";
      } elseif ($_GET['module'] == "updproduct") {
        include "module/updproduct.php";
      } elseif ($_GET['module'] == "gold") {
        include "module/gold.php";
      } elseif ($_GET['module'] == "addgold") {
        include "module/addgold.php";
      } elseif ($_GET['module'] == "updgold") {
        include "module/updgold.php";
      } elseif ($_GET['module'] == "adminoffice") {
        include "module/adminoffice.php";
      } elseif ($_GET['module'] == "addadminoffice") {
        include "module/addadminoffice.php";
      } elseif ($_GET['module'] == "updadminoffice") {
        include "module/updadminoffice.php";
      } elseif ($_GET['module'] == "customer") {
        include "module/customer.php";
      } elseif ($_GET['module'] == "addcustomer") {
        include "module/addcustomer.php";
      } elseif ($_GET['module'] == "updcustomer") {
        include "module/updcustomer.php";
      } elseif ($_GET['module'] == "nasabah") {
        include "module/nasabah.php";
      } elseif ($_GET['module'] == "deposit") {
        include "module/deposit.php";
      } elseif ($_GET['module'] == "backupdatabase") {
        include "module/backupdatabase.php";
      } elseif ($_GET['module'] == "mutation") {
        include "module/mutation.php";
      } elseif ($_GET['module'] == "exporttrash") {
        include "module/exporttrash.php";
      } elseif ($_GET['module'] == "balance") {
        include "module/balance.php";
      } elseif ($_GET['module'] == "purchase") {
        include "module/adddeposit2.php";
      } elseif ($_GET['module'] == "buyback") {
        include "module/tarik_buyback.php";
      } elseif ($_GET['module'] == "mailbox") {
        include "module/mailbox.php";
      } elseif ($_GET['module'] == "adddeposit") {
        include "module/adddeposit.php";
      } elseif ($_GET['module'] == "formcustomer") {
        include "module/formcustomer.php";
      } elseif ($_GET['module'] == "payment") {
        include "module/payment.php";
      } elseif ($_GET['module'] == "changepassword") {
        include "module/newpassword.php";
      } elseif ($_GET['module'] == "profileadmin") {
        include "module/updadminofficeprofile.php";
      } elseif ($_GET['module'] == "goldbar") {
        include "module/goldbar.php";
      } elseif ($_GET['module'] == "withdrawsfisik") {
        include "module/tarik_fisik.php";
      } elseif ($_GET['module'] == "withdraws") {
        include "module/withdraw.php";
      } else {
        echo "<script>
                              
                                swal({
                                    title: 'Akses ditolak',
                                    text: 'Hanya akun email Google, Yahoo dan AOL Mail yang diperbolehkan',
                                    type: 'error'
                                }, function() {
                                    window.location = 'login.php';
                                });
                          
                                </script>";
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
      <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Admin</a>.</strong> All rights
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
    $(function() {
      $('#trxSemua').DataTable()
      $('#trxSemua2').DataTable()
      $('#trxTerakhir').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false
      })
    })

    var selectType = $("#select-type");
    var pc1 = $("#cf1");
    var pc2 = $("#cf2");
    var bp1 = $("#rp1");
    var bp2 = $("#rp2");

    var sw = $("#ab");

    selectType.change(function() {
      if (selectType.val() == 1000) {
        pc1.show();
        pc2.show();
        bp1.hide();
        bp2.hide();
        sw.hide();
      } else if (selectType.val() == 2000) {
        bp1.show();
        bp2.show();
        pc1.hide();
        pc2.hide();
        sw.hide();
      } else if (selectType.val() == 3000) {
        sw.show();
        bp1.hide();
        bp2.hide();
        pc1.hide();
        pc2.hide();
      }

    });

    $(document).on("keyup", "#cf1", function() {

      var beli = $("#nilai_emas").val();
      //var saldo_sekarang = $("#saldo_sekarang").val();
      var gram = $("#gram").val();
      var NR = beli * gram; //nilia rp
      //var sisa_saldo = saldo_sekarang-cetak_fisik;
      // alert(rupiah);
      $("#cf2").val(NR);
      //$("#sisa_saldo").val(sisa_saldo.toFixed(6)); // sisa saldo emas


    });


    $(document).on("keyup", "#rp1", function() {

      var jual = $("#nilai_emas").val();
      //var saldo_sekarang = $("#saldo_sekarang").val();
      // var cetak_fisik = $("#cetak_fisik").val();
      var rp = $("#rp1").val();
      var emas = rp / jual;
      //var sisa_saldo3 =saldo_sekarang-sisa_saldo2 ;
      $("#rp2").val(emas.toFixed(6));
      // alert(sisa_saldo2);//emas        
      //$("#sisa_saldo").val(sisa_saldo3.toFixed(6)); // buyback uang 

    });
  </script>
  </script>
  <!-- page script -->
  <script>
    $(function() {
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
      var areaChart = new Chart(areaChartCanvas)

      var areaChartData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Electronics',
            fillColor: 'rgba(210, 214, 222, 1)',
            strokeColor: 'rgba(210, 214, 222, 1)',
            pointColor: 'rgba(210, 214, 222, 1)',
            pointStrokeColor: '#c1c7d1',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data: [65, 59, 80, 81, 56, 55, 40, 100]
          },
          {
            label: 'Digital Goods',
            fillColor: 'rgba(60,141,188,0.9)',
            strokeColor: 'rgba(60,141,188,0.8)',
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: [28, 48, 40, 19, 86, 27, 90, 50]
          }
        ]
      }

      var areaChartOptions = {
        //Boolean - If we should show the scale at all
        showScale: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: false,
        //String - Colour of the grid lines
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - Whether the line is curved between points
        bezierCurve: true,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.3,
        //Boolean - Whether to show a dot for each point
        pointDot: false,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a color
        datasetFill: true,
        //String - A legend template
        legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true
      }

      //Create the line chart
      areaChart.Line(areaChartData, areaChartOptions)

      //-------------
      //- LINE CHART -
      //--------------
      var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
      var lineChart = new Chart(lineChartCanvas)
      var lineChartOptions = areaChartOptions
      lineChartOptions.datasetFill = false
      lineChart.Line(areaChartData, lineChartOptions)

      //-------------
      //- PIE CHART -
      //-------------
      // Get context with jQuery - using jQuery's .get() method.
      <?php
      $Chrome = 700;
      $IE = 700;
      $FireFox = 400;
      $Safari = 500;
      $Opera = 200;
      $Navigator = 100;
      ?>
      var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
      var pieChart = new Chart(pieChartCanvas)
      var PieData = [

        {
          value: <?php echo $Chrome; ?>,
          color: '#f56954',
          highlight: '#f56954',
          label: 'Chrome',
        },
        {
          value: <?php echo $IE; ?>,
          color: '#00a65a',
          highlight: '#00a65a',
          label: 'IE'
        },
        {
          value: <?php echo $FireFox; ?>,
          color: '#f39c12',
          highlight: '#f39c12',
          label: 'FireFox'
        },
        {
          value: <?php echo $Safari; ?>,
          color: '#00c0ef',
          highlight: '#00c0ef',
          label: 'Safari'
        },
        {
          value: <?php echo $Opera; ?>,
          color: '#3c8dbc',
          highlight: '#3c8dbc',
          label: 'Opera'
        },
        {
          value: <?php echo $Navigator; ?>,
          color: '#d2d6de',
          highlight: '#d2d6de',
          label: 'Navigator'
        }
      ]
      var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: '#fff',
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: 'easeOutBounce',
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
      }
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      pieChart.Doughnut(PieData, pieOptions)

      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvas = $('#barChart').get(0).getContext('2d')
      var barChart = new Chart(barChartCanvas)
      var barChartData = areaChartData
      barChartData.datasets[1].fillColor = '#00a65a'
      barChartData.datasets[1].strokeColor = '#00a65a'
      barChartData.datasets[1].pointColor = '#00a65a'
      var barChartOptions = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - If there is a stroke on each bar
        barShowStroke: true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth: 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing: 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing: 1,
        //String - A legend template
        legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        //Boolean - whether to make the chart responsive
        responsive: true,
        maintainAspectRatio: true
      }

      barChartOptions.datasetFill = false
      barChart.Bar(barChartData, barChartOptions)
    })
  </script>

  <!-- Email -->
  <script>
    $(function() {
      //Enable iCheck plugin for checkboxes
      //iCheck for checkbox and radio inputs
      $('.mailbox-messages input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
      });

      //Enable check and uncheck all functionality
      $(".checkbox-toggle").click(function() {
        var clicks = $(this).data('clicks');
        if (clicks) {
          //Uncheck all checkboxes
          $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
          $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
        } else {
          //Check all checkboxes
          $(".mailbox-messages input[type='checkbox']").iCheck("check");
          $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
        }
        $(this).data("clicks", !clicks);
      });

      //Handle starring for glyphicon and font awesome
      $(".mailbox-star").click(function(e) {
        e.preventDefault();
        //detect type
        var $this = $(this).find("a > i");
        var glyph = $this.hasClass("glyphicon");
        var fa = $this.hasClass("fa");

        //Switch states
        if (glyph) {
          $this.toggleClass("glyphicon-star");
          $this.toggleClass("glyphicon-star-empty");
        }

        if (fa) {
          $this.toggleClass("fa-star");
          $this.toggleClass("fa-star-o");
        }
      });
    });
  </script>
  <!-- email -->

  <!-- get wilayah -->
  <script type="text/javascript">
    $(document).ready(function() {
      $("#provinsi").append('<option value="<?php echo $DataUsers["Provinsi_N"]; ?>"><?php echo $DataUsers["Provinsi_N"]; ?></option>');
      $("#kabupaten").html('');
      $("#kecamatan").html('');
      $("#kelurahan").html('');
      $("#kabupaten").append('<option value="<?php echo $DataUsers["Kabupaten_N"]; ?>"><?php echo $DataUsers["Kabupaten_N"]; ?></option>');
      $("#kecamatan").append('<option value="<?php echo $DataUsers["Kecamatan_N"]; ?>"><?php echo $DataUsers["Kecamatan_N"]; ?></option>');
      $("#kelurahan").append('<option value="<?php echo $DataUsers["Kelurahan_N"]; ?>"><?php echo $DataUsers["Kelurahan_N"]; ?></option>');
      url = '../getphp/get_provinsi.php';
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#provinsi").append('<option value="' + result[i].id_prov + '">' + result[i].nama + '</option>');
        }
      });
    });
    $("#provinsi").change(function() {
      var id_prov = $("#provinsi").val();
      var url = '../getphp/get_kabupaten.php?id_prov=' + id_prov;
      $("#kabupaten").html('');
      $("#kecamatan").html('');
      $("#kelurahan").html('');
      $("#kabupaten").append('<option value="">Pilih</option>');
      $("#kecamatan").append('<option value="">Pilih</option>');
      $("#kelurahan").append('<option value="">Pilih</option>');
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#kabupaten").append('<option value="' + result[i].id_kab + '">' + result[i].nama + '</option>');
        }
      });
    });
    $("#kabupaten").change(function() {
      var id_kab = $("#kabupaten").val();
      var url = '../getphp/get_kecamatan.php?id_kab=' + id_kab;
      $("#kecamatan").html('');
      $("#kelurahan").html('');
      $("#kecamatan").append('<option value="">Pilih</option>');
      $("#kelurahan").append('<option value="">Pilih</option>');
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#kecamatan").append('<option value="' + result[i].id_kec + '">' + result[i].nama + '</option>');
        }
      });
    });
    $("#kecamatan").change(function() {
      var id_kec = $("#kecamatan").val();
      var url = '../getphp/get_kelurahan.php?id_kec=' + id_kec;
      $("#kelurahan").html('');
      $("#kelurahan").append('<option value="">Pilih</option>');
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#kelurahan").append('<option value="' + result[i].id_kel + '">' + result[i].nama + '</option>');
        }
      });
    });
  </script>
  <!-- get wilayah -->

  <!-- get wilayah -->
  <script type="text/javascript">
    $(document).ready(function() {
      $("#provinsi2").append('<option value="">Pilih</option>');
      $("#kabupaten2").html('');
      $("#kecamatan2").html('');
      $("#kelurahan2").html('');
      $("#kabupaten2").append('<option value="">Pilih</option>');
      $("#kecamatan2").append('<option value="">Pilih</option>');
      $("#kelurahan2").append('<option value="">Pilih</option>');
      url = '../getphp/get_provinsi.php';
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#provinsi2").append('<option value="' + result[i].id_prov + '">' + result[i].nama + '</option>');
        }
      });
    });
    $("#provinsi2").change(function() {
      var id_prov = $("#provinsi2").val();
      var url = '../getphp/get_kabupaten.php?id_prov=' + id_prov;
      $("#kabupaten2").html('');
      $("#kecamatan2").html('');
      $("#kelurahan2").html('');
      $("#kabupaten2").append('<option value="">Pilih</option>');
      $("#kecamatan2").append('<option value="">Pilih</option>');
      $("#kelurahan2").append('<option value="">Pilih</option>');
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#kabupaten2").append('<option value="' + result[i].id_kab + '">' + result[i].nama + '</option>');
        }
      });
    });
    $("#kabupaten2").change(function() {
      var id_kab = $("#kabupaten2").val();
      var url = '../getphp/get_kecamatan.php?id_kab=' + id_kab;
      $("#kecamatan2").html('');
      $("#kelurahan2").html('');
      $("#kecamatan2").append('<option value="">Pilih</option>');
      $("#kelurahan2").append('<option value="">Pilih</option>');
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#kecamatan2").append('<option value="' + result[i].id_kec + '">' + result[i].nama + '</option>');
        }
      });
    });
    $("#kecamatan2").change(function() {
      var id_kec = $("#kecamatan2").val();
      var url = '../getphp/get_kelurahan.php?id_kec=' + id_kec;
      $("#kelurahan2").html('');
      $("#kelurahan2").append('<option value="">Pilih</option>');
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          for (var i = 0; i < result.length; i++)
            $("#kelurahan2").append('<option value="' + result[i].id_kel + '">' + result[i].nama + '</option>');
        }
      });
    });
  </script>
  <!-- get wilayah -->

  <!-- add product -->
  <script>
    $(document).ready(function() {


      function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
      }

      // pilih customer
      $(document).on("click", ".modal-select-customer", function() {

        var id = $(this).attr('id');
        var account = $("#account_" + id).val();
        var member = $("#member_" + id).val();
        var customer = $("#customer_" + id).val();
        var saldo = $("#saldo_" + id).val();

        $("#add_id").val(id);
        $("#add_account").val(account);
        $("#add_memberid").val(member);
        $("#add_customer").val(customer);
        $("#add_saldo").val(saldo);
      });

      // pilih produk
      $(document).on("click", ".modal-pilih-produk", function() {

        var id = $(this).attr('id');
        var kode = $("#kode_" + id).val();
        var nama = $("#nama_" + id).val();
        var harga = $("#harga_" + id).val();

        $("#tambahkan_id").val(id);
        $("#tambahkan_kode").val(kode);
        $("#tambahkan_nama").val(nama);
        $("#tambahkan_harga").val(harga);
        $("#tambahkan_jumlah").val(1);
        $("#tambahkan_total").val(harga);

      });
      // ubah jumlah
      $(document).on("change keyup", "#tambahkan_jumlah", function() {

        // var id = $(this).attr('id');
        // var kode = $("#kode_"+id).val();
        // var nama = $("#nama_"+id).val();
        var harga = $("#tambahkan_harga").val();
        var jumlah = $("#tambahkan_jumlah").val();
        var total = harga * jumlah;
        $("#tambahkan_total").val(total);

      });


      // ubah jumlah
      $("body").on("keyup", "#tambahkan_kode", function() {
        var kode = $(this).val();
        var data = "kode=" + kode;
        $.ajax({
          type: "POST",
          url: "penjualan_cari_ajax.php",
          data: data,
          dataType: 'JSON',
          success: function(html) {
            $("#tambahkan_id").val(html[0].id);

            $("#tambahkan_nama").val(html[0].nama);
            $("#tambahkan_harga").val(html[0].harga);
            $("#tambahkan_jumlah").val(html[0].jumlah);
            $("#tambahkan_total").val(html[0].harga)

          }

        });
      });


      // tombol tambahkan produk
      $("body").on("click", "#tombol-tambahkan", function() {

        var id = $("#tambahkan_id").val();
        var kode = $("#tambahkan_kode").val();
        var nama = $("#tambahkan_nama").val();
        var harga = $("#tambahkan_harga").val();
        var jumlah = $("#tambahkan_jumlah").val();
        var total = $("#tambahkan_total").val();


        if (id.length == 0) {
          alert("Product belum dipilih");
        } else if (kode.length == 0) {
          alert("Kode produk harus diisi");
        } else if (jumlah == 0) {
          alert("Jumlah harus lebih besar dari 0");
        } else {
          var table_pembelian = "<tr id='tr_" + id + "'>" +
            "<td> <input  type='hidden' name='transaksi_produk[]' value='" + id + "'> <input type='hidden'  name='transaksi_harga[]' value='" + harga + "'> <input type='hidden' name='transaksi_jumlah[]' value='" + jumlah + "'> <input type='hidden' name='transaksi_total[]' value='" + total + "'>" +
            kode +
            "</td>" +
            "<td>" + nama + "</td>" +
            "<td align='center'>Rp." + formatNumber(harga) + ",-</td>" +
            "<td align='center'>" + formatNumber(jumlah) + "</td>" +
            "<td align='center'>Rp." + formatNumber(total) + ",-</td>" +
            "<td align='center'> <span class='btn btn-danger tombol-hapus-penjualan' total='" + total + "' jumlah='" + jumlah + "' harga='" + harga + "' id='" + id + "'><i class='fa fa-close'></i> Batal</span></td>" +
            "</tr>";
          $("#table-pembelian tbody").append(table_pembelian);

          var pricegold = $(".goldprice").attr("id");
          var fee = $(".total_fee").attr("id");
          // update total pembelian
          var pembelian_harga = $(".pembelian_harga").attr("id");
          var pembelian_jumlah = $(".pembelian_jumlah").attr("id");
          var pembelian_total = $(".pembelian_total").attr("id");

          // jumlahkan pembelian
          var jumlahkan_harga = eval(pembelian_harga) + eval(harga);
          var jumlahkan_jumlah = eval(pembelian_jumlah) + eval(jumlah);
          var jumlahkan_total = eval(pembelian_total) + eval(total);

          //gold
          var f = jumlahkan_total * fee / 100;
          var e = jumlahkan_total - f;
          var g = e / pricegold;

          // isi di table penjualan
          $(".pembelian_harga").attr("id", jumlahkan_harga);
          $(".pembelian_jumlah").attr("id", jumlahkan_jumlah);
          $(".pembelian_total").attr("id", jumlahkan_total);

          // tulis di table penjualan
          $(".pembelian_harga").text("Rp." + formatNumber(jumlahkan_harga) + ",-");
          $(".pembelian_jumlah").text(formatNumber(jumlahkan_jumlah));
          $(".pembelian_total").text("Rp." + formatNumber(jumlahkan_total) + ",-");

          // total
          $(".total_pembelian").text("Rp." + formatNumber(jumlahkan_total) + ",-");
          $(".sub_total_pembelian").text("Rp." + formatNumber(jumlahkan_total) + ",-");
          $(".total_pembelian").attr("id", jumlahkan_total);
          $(".sub_total_pembelian").attr("id", jumlahkan_total);

          $(".sub_total_form").val(jumlahkan_total);
          //$(".total_form").val(jumlahkan_total);  
          $(".total_form").val(e);
          $(".total_pembelian").text("Rp." + formatNumber(e) + ",-");


          //gold
          $(".total_fee").val(fee);
          $(".fee").text(formatNumber(fee) + "%");
          $(".fee_rp").text("Rp." + formatNumber(f) + ",-");
          $(".total_fee_rp").val(f);

          $(".total_gold").text(g.toFixed(6) + ",gr");
          $(".gold_form").val(g.toFixed(6));


          // kosongkan
          $("#tambahkan_id").val("");
          $("#tambahkan_kode").val("");
          $("#tambahkan_nama").val("");
          $("#tambahkan_harga").val("");
          $("#tambahkan_jumlah").val("");
          $("#tambahkan_total").val("")
        }

      });

      // tombol tambahkan Emas
      $("body").on("click", "#tombol-tambahkan-emas", function() {

        var id = $("#tambahkan_id").val();
        var kode = $("#tambahkan_kode").val();
        var nama = $("#tambahkan_nama").val();
        var harga = $("#tambahkan_harga").val();
        var jumlah = $("#tambahkan_jumlah").val();
        var total = $("#tambahkan_total").val();


        if (id.length == 0) {
          alert("Product belum dipilih");
        } else if (kode.length == 0) {
          alert("Kode produk harus diisi");
        } else if (jumlah == 0) {
          alert("Jumlah harus lebih besar dari 0");
        } else {
          var table_pembelian = "<tr id='tr_" + id + "'>" +
            "<td> <input  type='hidden' name='transaksi_produk[]' value='" + id + "'> <input type='hidden'  name='transaksi_harga[]' value='" + harga + "'> <input type='hidden' name='transaksi_jumlah[]' value='" + jumlah + "'> <input type='hidden' name='transaksi_total[]' value='" + total + "'>" +
            kode +
            "</td>" +
            "<td>" + nama + "</td>" +
            "<td align='center'>" + formatNumber(harga) + " gr</td>" +
            "<td align='center'>" + formatNumber(jumlah) + "</td>" +
            "<td align='center'>" + formatNumber(total) + " gr</td>" +
            "<td align='center'> <span class='btn btn-danger tombol-hapus-penjualan' total='" + total + "' jumlah='" + jumlah + "' harga='" + harga + "' id='" + id + "'><i class='fa fa-close'></i> Batal</span></td>" +
            "</tr>";
          $("#table-pembelian tbody").append(table_pembelian);

          var pricegold = $(".goldprice").attr("id");
          var fee = $(".total_fee").attr("id");
          // update total pembelian
          var pembelian_harga = $(".pembelian_harga").attr("id");
          var pembelian_jumlah = $(".pembelian_jumlah").attr("id");
          var pembelian_total = $(".pembelian_total").attr("id");

          // jumlahkan pembelian
          var jumlahkan_harga = eval(pembelian_harga) + eval(harga);
          var jumlahkan_jumlah = eval(pembelian_jumlah) + eval(jumlah);
          var jumlahkan_total = eval(pembelian_total) + eval(total);

          //gold
          // var f = jumlahkan_total * fee / 100;
          // var e = jumlahkan_total - f;
          // var g = e / pricegold;

          // isi di table penjualan
          $(".pembelian_harga").attr("id", jumlahkan_harga);
          $(".pembelian_jumlah").attr("id", jumlahkan_jumlah);
          $(".pembelian_total").attr("id", jumlahkan_total);

          // tulis di table penjualan
          $(".pembelian_harga").text("" + formatNumber(jumlahkan_harga) + " gr");
          $(".pembelian_jumlah").text(formatNumber(jumlahkan_jumlah));
          $(".pembelian_total").text("" + formatNumber(jumlahkan_total) + " gr");

          // total
          $(".total_pembelian").text(" " + formatNumber(jumlahkan_total) + " gr");
          $(".sub_total_pembelian").text(" " + formatNumber(jumlahkan_total) + " gr");
          $(".total_pembelian").attr("id", jumlahkan_total);
          $(".sub_total_pembelian").attr("id", jumlahkan_total);

          $(".sub_total_form").val(jumlahkan_total);
          //$(".total_form").val(jumlahkan_total);  
          // $(".total_form").val(e);
          // $(".total_pembelian").text(" " + formatNumber(e) + " gr");


          // //gold
          // $(".total_fee").val(fee);
          // $(".fee").text(formatNumber(fee) + "%");
          // $(".fee_rp").text("Rp." + formatNumber(f) + ",-");
          // $(".total_fee_rp").val(f);

          // $(".total_gold").text(g.toFixed(6) + ",gr");
          // $(".gold_form").val(g.toFixed(6));


          // kosongkan
          $("#tambahkan_id").val("");
          $("#tambahkan_kode").val("");
          $("#tambahkan_nama").val("");
          $("#tambahkan_harga").val("");
          $("#tambahkan_jumlah").val("");
          $("#tambahkan_total").val("")
        }

      });

      // tombol hapus penjualan
      $("body").on("click", ".tombol-hapus-penjualan", function() {

        var id = $(this).attr("id");
        var harga = $(this).attr("harga");
        var jumlah = $(this).attr("jumlah");
        var total = $(this).attr("total");

        var pricegold = $(".goldprice").attr("id");
        var fee = $(".total_fee").attr("id");

        // update total pembelian
        var pembelian_harga = $(".pembelian_harga").attr("id");
        var pembelian_jumlah = $(".pembelian_jumlah").attr("id");
        var pembelian_total = $(".pembelian_total").attr("id");

        // jumlahkan pembelian
        var kurangi_harga = eval(pembelian_harga) - eval(harga);
        var kurangi_jumlah = eval(pembelian_jumlah) - eval(jumlah);
        var kurangi_total = eval(pembelian_total) - eval(total);

        //gold
        var f = kurangi_total * fee / 100;
        var e = kurangi_total - f;
        var g = e / pricegold;

        // isi di table penjualan
        $(".pembelian_harga").attr("id", kurangi_harga);
        $(".pembelian_jumlah").attr("id", kurangi_jumlah);
        $(".pembelian_total").attr("id", kurangi_total);

        // tulis di table penjualan
        $(".pembelian_harga").text("" + formatNumber(kurangi_harga) + " gr");
        $(".pembelian_jumlah").text(formatNumber(kurangi_jumlah));
        $(".pembelian_total").text("" + formatNumber(kurangi_total) + " gr");

        // total
        $(".total_pembelian").text(" " + formatNumber(kurangi_total) + " gr");
        $(".sub_total_pembelian").text(" " + formatNumber(kurangi_total) + " gr");
        $(".total_pembelian").attr("id", kurangi_total);
        $(".sub_total_pembelian").attr("id", kurangi_total);

        // $(".total_form").val(kurangi_total);
        // $(".sub_total_form").val(kurangi_total);

        $(".sub_total_form").val(kurangi_total);
        //$(".total_form").val(jumlahkan_total);  
        $(".total_form").val(e);
        $(".total_pembelian").text(" " + formatNumber(e) + " gr");

        //gold
        $(".fee_rp").text("Rp." + formatNumber(f) + ",-");
        $(".total_fee_rp").val(f);

        $(".total_gold").text(g.toFixed(6) + ",gr");
        $(".gold_form").val(g.toFixed(6));


        $("#tr_" + id).remove();

      });

      // diskon
      $("body").on("keyup", ".total_fee", function() {
        var diskon = $(this).val();

        if (diskon.length != 0 && diskon != "") {

          var sub_total = $(".sub_total_pembelian").attr("id");
          var total = $(".total_pembelian").attr("id");
          var goldprice = $(".goldprice").attr("id");

          var hasil_diskon = sub_total * diskon / 100;
          var hasil2 = sub_total - hasil_diskon;
          var result = hasil2 / goldprice;
          $(".fee").text("Rp." + formatNumber(diskon) + ",-");

          $(".total_pembelian").text("Rp." + formatNumber(hasil2) + ",-");
          $(".total_form").val(hasil2);

          $(".total_gold").text(result.toFixed(6) + ",gram");
          $(".gold_form").val(result.toFixed(6));

        } else {

          var sub_total_pembelian = $(".sub_total_pembelian").attr("id");
          var gold_price = $(".goldprice").attr("id");
          var result2 = sub_total_pembelian / gold_price;

          $(".total_pembelian").text("Rp." + formatNumber(sub_total_pembelian) + ",-");
          $(".total_form").val(sub_total_pembelian);

          $(".total_gold").text(result2.toFixed(6) + ",Gram");
          $(".gold_form").val(result2.toFixed(6));
        }



      });

    });

    function cek() {
      var total = $(".total_pembelian").attr("id");
      var s = $("#form-control select").attr("id");
      let name = document.forms["ftrx"]["txt_rekening"].value;
      let Select = document.forms["ftrx"]["txt_select"].value;
      // if (x == "") {
      //   alert("Name must be filled out");
      //   return false;
      // }

      if (total == 0) {
        alert("Maaf Data Masih Kosong !!!");
        return false;
      } else if (name == "") {
        alert("Rekening Kosong");
        return false;
      } else if (s == "") {
        alert("Sumber Dana Kosong");
        return false;
      } else {
        return confirm('Apakah anda yakin ingin memproses transaksi?');
      }




      // if (total == 0 || name == "" || select == "") {
      //   alert("Pembelian Masih Kosong");
      //   return false;
      // } else {
      //   return confirm('Apakah anda yakin ingin memproses transaksi?');
      //   // return true;
      // }
    }
  </script>
  <!-- //Pemebelian Emas  -->
  <script type="text/javascript">
    $(document).ready(function() {
      $("#Beli").change(function() {
        var beli = $("#Beli").val();
        var qty = $("#Qty").val();
        var Gold = $("#Hargagold").val();
        var fee = $("#Oprasionalfree1").val();

        var subtotal = beli * qty;

        var Oprasionalfree2 = subtotal * fee / 100;
        var Total = subtotal - Oprasionalfree2;
        var Resultgold = Total / Gold;

        $("#Oprasionalfree2").val(Oprasionalfree2);
        $("#Subtotal").val(subtotal);
        $("#Total").val(Total);
        $("#Gold").val(Resultgold.toFixed(6));
      });
    });
  </script>


  <!-- Buyback Rupiah -->
  <script type="text/javascript">
    $(document).ready(function() {
      $("#Buyback-Rupiah").change(function() {
        var Buyback1 = $("#Buyback-Rupiah").val();
        var PriceGold = $("#Hargagold").val();
        var SaldoAwal = $("#add_saldo").val();

        // var Buyback_Gold = $("#Hargagold").val();

        var Buyback_Gold = Buyback1 / PriceGold;
        // var subtotal = parseInt(beli) * parseInt(qty);
        // var Resultgold = parseInt(Total) / parseInt(Gold);
        var SaldoAkhir = SaldoAwal - Buyback_Gold;

        $("#Buyback-Gold").val(Buyback_Gold.toFixed(6));
        $("#Saldo-A").val(SaldoAkhir.toFixed(6));
      });
    });
  </script>

</body>

</html>