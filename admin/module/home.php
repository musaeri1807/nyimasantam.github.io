<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

$Sql = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute(array(":idbranch" => $branchid));
$result = $Stmt->fetch(PDO::FETCH_ASSOC);
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