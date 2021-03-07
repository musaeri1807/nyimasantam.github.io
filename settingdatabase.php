<?php
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');


if(isset($_POST['submit'])){
  $HostName = filter_input(INPUT_POST, 'HostName', FILTER_SANITIZE_STRING);
  $UserName = filter_input(INPUT_POST, 'UserName', FILTER_SANITIZE_STRING);
  $Password = filter_input(INPUT_POST, 'Password', FILTER_SANITIZE_STRING);
  $DatabaseName = filter_input(INPUT_POST, 'DatabaseName', FILTER_SANITIZE_STRING);
  file_put_contents("config/localhost.txt",$HostName);
  file_put_contents("config/users.txt",$UserName);
  file_put_contents("config/password.txt",$Password);
  file_put_contents("config/database.txt",$DatabaseName);
  header("Location: index.php");
}
?>


<!DOCTYPE html>
<html>
<head>
   <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta content="Silakan Login Dengan Aman" name="descriptison">
  <meta content="Login nyimasantam" name="keywords">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
  <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="icon">
  <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="apple-touch-icon">
  
  <title>Nyimas Antam | Dashboard</title>
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="view/assetdashboard/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="view/assetdashboard/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="view/assetdashboard/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

 <!-- komponen pada table -->
  <!-- DataTables -->
  <link rel="stylesheet" href="view/assetdashboard/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="view/assetdashboard/https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="#"><b>Setting </b>Database</a>
  </div>
  <!-- User name -->
<!--   <div class="lockscreen-name">John Doe</div> -->

  <!-- START LOCK SCREEN ITEM -->
  <!-- <div class="lockscreen-item"> -->
    <div>
    <!-- lockscreen image -->
  <!--   <div class="lockscreen-image">
      <img src="dist/img/user1-128x128.jpg" alt="User Image">
    </div> -->
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
      <form role="form" method="POST" >              
          <div class="form-group" >
            <input type="text" class="form-control" name="HostName" value="localhost" placeholder="HostName" required>
            </div>
          <div class="form-group">           
            <input type="text" class="form-control" name="UserName" value="uruh7792_musaeri"  placeholder="UserName" required>
          </div>                    
          <div class="form-group">       
          <input type="text" class="form-control" name="Password" value="P@ssw0rd" placeholder="Password">
          </div>
          <div class="form-group">
          <input type="text" class="form-control" name="DatabaseName" value="uruh7792_VPS01NA" placeholder="DatabaseName" required>
          </div> 
           <center> 
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
          </center> 
      </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
<!--   <div class="help-block text-center">
    Enter your password to retrieve your session
  </div>
  <div class="text-center">
    <a href="login.html">Or sign in as a different user</a>
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; 2014-2016 <b><a href="https://adminlte.io" class="text-black">Almsaeed Studio</a></b><br>
    All rights reserved
  </div> -->
</div>
<!-- /.center -->

<!-- jQuery 3 -->
<script src="view/assetdashboard/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="view/assetdashboard/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="view/assetdashboard/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="view/assetdashboard/bower_components/raphael/raphael.min.js"></script>
<script src="view/assetdashboard/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="view/assetdashboard/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="view/assetdashboard/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="view/assetdashboard/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="view/assetdashboard/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="view/assetdashboard/bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="view/assetdashboard/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="view/assetdashboard/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="view/assetdashboard/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="view/assetdashboard/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="view/assetdashboard/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="view/assetdashboard/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="view/assetdashboard/dist/js/demo.js"></script>
<!-- Komponen pada table  -->           
<!-- DataTables -->
<script src="view/assetdashboard/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="view/assetdashboard/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
          
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
