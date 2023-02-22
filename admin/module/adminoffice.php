<?php
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
	header("location: ../loginv2.php");
}

if (isset($_REQUEST['id'])) {
  $id = $_REQUEST['id'];

  $select_stmt = $db->prepare('SELECT * FROM tblemployeeslogin WHERE field_user_id =:id'); //sql select query
  $select_stmt->bindParam(':id', $id);
  $select_stmt->execute();
  $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
  if ($id == $row["field_user_id"]) {
    //echo "TRUE";
    $iduser   = $_SESSION['idlogin']; //member_id
    $idmember = $_SESSION['userlogin']; //id_member
    $aktifitas = "DELETE AKUN " . $row["field_username"];
    $date     = date("Y-m-d H:s:i");

    $delete_stmt = $db->prepare('DELETE FROM tblemployeeslogin WHERE field_user_id =:id');
    $delete_stmt->bindParam(':id', $id);

    if ($delete_stmt->execute()) {
      $insert = $db->prepare("INSERT INTO tbluserlog(field_aktifitas,field_member_id,field_user_id,field_waktu)VALUES(:aktifitas,:member_id,:user_id,:waktu)");
      $insert->bindParam(':aktifitas', $aktifitas);
      $insert->bindParam(':member_id', $idmember);
      $insert->bindParam(':user_id', $iduser);
      $insert->bindParam(':waktu', $date);
      $insert->execute();
      $insertMsg = "Delete Successfully"; //execute query success message
      //echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard?module=adminoffice">';
      echo '<META HTTP-EQUIV="Refresh" Content="1;">';
    }
  }
  // else{
  //   //echo "FALSE"; 
  //   echo '<META HTTP-EQUIV="Refresh" Content="1;">';
  // }

}


if ($_SESSION['rolelogin'] == 'ADM') {
          $Sql = "SELECT
          E.field_user_id AS ID ,
          E.field_employees_id AS NIP,
          E.field_name_officer AS NAMA,
          E.field_username AS USERNAME,
          E.field_email AS MAIL,
          E.field_role AS ROLE,
          E.field_status_aktif AS STATUS,
          D.field_department_name AS JABATAN,
          W.field_nama_desa AS CABANG
          FROM tblemployeeslogin E 
          LEFT JOIN tblbranch B ON E.field_branch=B.field_branch_id 
          LEFT JOIN tblwilayahdesa W ON E.field_branch=W.field_desa_id
          LEFT JOIN tbldepartment D ON E.field_role=D.field_department_id 
          WHERE E.field_role !='ADM'
          ORDER BY E.field_user_id DESC";
          $Stmt = $db->prepare($Sql);
          $Stmt->execute();
          $result = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'MGR') {
          $Sql = "SELECT
          E.field_user_id AS ID ,
          E.field_employees_id AS NIP,
          E.field_name_officer AS NAMA,
          E.field_username AS USERNAME,
          E.field_email AS MAIL,
          E.field_role AS ROLE,
          E.field_status_aktif AS STATUS,
          D.field_department_name AS JABATAN,
          W.field_nama_desa AS CABANG
          FROM tblemployeeslogin E 
          LEFT JOIN tblbranch B ON E.field_branch=B.field_branch_id 
          LEFT JOIN tblwilayahdesa W ON E.field_branch=W.field_desa_id
          LEFT JOIN tbldepartment D ON E.field_role=D.field_department_id 
          WHERE E.field_role NOT IN('ADM','MGR')
          ORDER BY E.field_user_id DESC";
          $Stmt = $db->prepare($Sql);
          $Stmt->execute();
          $result = $Stmt->fetchAll();
}elseif ($_SESSION['rolelogin'] == 'AMR') {
          $Sql = "SELECT
          E.field_user_id AS ID ,
          E.field_employees_id AS NIP,
          E.field_name_officer AS NAMA,
          E.field_username AS USERNAME,
          E.field_email AS MAIL,
          E.field_role AS ROLE,
          E.field_status_aktif AS STATUS,
          D.field_department_name AS JABATAN,
          W.field_nama_desa AS CABANG
          FROM tblemployeeslogin E 
          LEFT JOIN tblbranch B ON E.field_branch=B.field_branch_id 
          LEFT JOIN tblwilayahdesa W ON E.field_branch=W.field_desa_id
          LEFT JOIN tbldepartment D ON E.field_role=D.field_department_id 
          WHERE E.field_role NOT IN('ADM','MGR','AMR') AND E.field_branch=:idbranch
          ORDER BY E.field_user_id DESC";
          $Stmt = $db->prepare($Sql);
          $Stmt->execute(array(':idbranch' => $branchid));
          $result = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'SPV') {
          $Sql = "SELECT
          E.field_user_id AS ID ,
          E.field_employees_id AS NIP,
          E.field_name_officer AS NAMA,
          E.field_username AS USERNAME,
          E.field_email AS MAIL,
          E.field_role AS ROLE,
          E.field_status_aktif AS STATUS,
          D.field_department_name AS JABATAN,
          W.field_nama_desa AS CABANG
          FROM tblemployeeslogin E 
          LEFT JOIN tblbranch B ON E.field_branch=B.field_branch_id 
          LEFT JOIN tblwilayahdesa W ON E.field_branch=W.field_desa_id
          LEFT JOIN tbldepartment D ON E.field_role=D.field_department_id 
          WHERE E.field_role NOT IN('ADM','MGR','AMR','SPV') AND E.field_branch=:idbranch
          ORDER BY E.field_user_id DESC";
          $Stmt = $db->prepare($Sql);
          $Stmt->execute(array(':idbranch' => $branchid));
          $result = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'BCO') {
          $Sql = "SELECT
          E.field_user_id AS ID ,
          E.field_employees_id AS NIP,
          E.field_name_officer AS NAMA,
          E.field_username AS USERNAME,
          E.field_email AS MAIL,
          E.field_role AS ROLE,
          E.field_status_aktif AS STATUS,
          D.field_department_name AS JABATAN,
          W.field_nama_desa AS CABANG
          FROM tblemployeeslogin E 
          LEFT JOIN tblbranch B ON E.field_branch=B.field_branch_id 
          LEFT JOIN tblwilayahdesa W ON E.field_branch=W.field_desa_id
          LEFT JOIN tbldepartment D ON E.field_role=D.field_department_id 
          WHERE E.field_role NOT IN('ADM','MGR','AMR','SPV','BCO') AND E.field_branch=:idbranch
          ORDER BY E.field_user_id DESC";
          $Stmt = $db->prepare($Sql);
          $Stmt->execute(array(':idbranch' => $branchid));
          $result = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'CMS') {
          $Sql = "SELECT
          E.field_user_id AS ID ,
          E.field_employees_id AS NIP,
          E.field_name_officer AS NAMA,
          E.field_username AS USERNAME,
          E.field_email AS MAIL,
          E.field_role AS ROLE,
          E.field_status_aktif AS STATUS,
          D.field_department_name AS JABATAN,
          W.field_nama_desa AS CABANG
          FROM tblemployeeslogin E 
          LEFT JOIN tblbranch B ON E.field_branch=B.field_branch_id 
          LEFT JOIN tblwilayahdesa W ON E.field_branch=W.field_desa_id
          LEFT JOIN tbldepartment D ON E.field_role=D.field_department_id 
          WHERE E.field_role NOT IN('ADM','MGR','AMR','SPV','BCO','CMS') AND E.field_branch=:idbranch
          ORDER BY E.field_user_id DESC";
          $Stmt = $db->prepare($Sql);
          $Stmt->execute(array(':idbranch' => $branchid));
          $result = $Stmt->fetchAll();
}
$no = 1;

?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Petugas</h3>
          <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
          <a href="?module=addadminoffice" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Petugas</a>
        </div>
        <!-- Content -->
        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="text-align:center ;">No</th>
                <th style="text-align:center ;">NIP</th>
                <th style="text-align:center ;">Nama</th>
                <th style="text-align:center ;">Username</th>
                <th style="text-align:center ;">Jabatan</th>
                <th style="text-align:center ;">Cabang</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">Action</th>

              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result as $row) {
              ?>

                <tr>
                  <td style="text-align:center ;">
                    <?php echo $no++ ?>
                  </td>
                  <td>
                    <?php echo $row["NIP"] ?>
                  </td>
                  <td data-title="Trx Id"><strong><?php echo $row["NAMA"]; ?></strong><br></td>
                  <td><?php echo $row["MAIL"]; ?>| <strong><?php echo $row["USERNAME"]; ?></strong></td>
                  <td data-title="Trx Id"><strong><?php echo $row["JABATAN"]; ?></strong></td>                  
                  <td><strong><?php if(["CABANG"]==null){
                    echo "HEAD OFFICE";
                  }else{
                    echo $row["CABANG"];
                  }; ?></strong></td>
                  <td>
                    <?php
                    $status = $row["STATUS"];
                    if ($status == "1") {
                      echo '<span class="badge btn-info text-white">Aktif</span>';
                    } elseif ($status == "2") {
                      echo '<span class="badge btn-warning text-white">Tidak Aktif</span>';
                    } elseif ($status == "0") {
                      echo '<span class="badge btn-danger text-white">Verifikasi</span>';;
                    }
                    ?>
                  </td>

                  <td ata-title="Trx Id">
                    <?php
                    if ($rows["field_role"] == "ADM") {
                      echo '<a href="?module=updadminoffice&id=' . $row["ID"] . '" class="btn btn-sm btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default' . $row["ID"] . '" class="btn btn-sm btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                    } elseif ($rows["field_role"] == "MGR") {
                      echo '<a href="?module=updadminoffice&id=' . $row["ID"] . '" class="btn btn-sm btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                    } elseif ($rows["field_role"] == "AMR") {
                      echo '<a href="?module=updadminoffice&id=' . $row["ID"] . '" class="btn btn-sm btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                    }
                    ?>
                  </td>


                </tr>

                <div class="modal fade" id="modal-default<?php echo $row["field_user_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Yakin Menghapus Data</h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <center>
                                <h4>
                                  <?php
                                  echo "Username " . $row["USERNAME"] . " Dengan Email " . $row["MAIL"];
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=adminoffice&id=<?php echo $row['ID']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th style="text-align:center ;">No</th>
                <th style="text-align:center ;">NIP</th>
                <th style="text-align:center ;">Nama</th>
                <th style="text-align:center ;">Username</th>
                <th style="text-align:center ;">Jabatan</th>
                <th style="text-align:center ;">Cabang</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">Action</th>

              </tr>
            </tfoot>
          </table>

        </div>
        <!-- Content -->
      </div>
    </div>
  </div>
</section>