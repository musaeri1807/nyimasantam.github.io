<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}


if (isset($_REQUEST['btn_reset'])) {
  $id         = $_REQUEST['txt_id'];
  $email      = $_REQUEST['txt_email'];
  $password   = password_generate(8);

  if (empty($id)) {
    $errorMsg = "Silakan Masukkan ID";
  } elseif (empty($email)) {
    $errorMsg = "Silakan Masukkan Email";
  } else {
    try {
      $select_stmt = $db->prepare("SELECT * FROM tbluserlogin U WHERE U.field_user_id=:id AND U.field_email=:email ORDER BY U.field_user_id DESC "); //sql select query
      $select_stmt->bindParam(':id', $id);
      $select_stmt->bindParam(':email', $email);
      $select_stmt->execute();
      $data = $select_stmt->fetch(PDO::FETCH_ASSOC);
      $Num  = $select_stmt->rowCount();

      if ($Num == 1) {
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $UpdateNasabah = $db->prepare("UPDATE tbluserlogin U SET U.field_password=:newpassword,U.password=:passwordnew
                                        WHERE U.field_user_id=:id");
        $UpdateNasabah->execute(array(':newpassword' => $new_password, ':passwordnew' => $password, ':id' => $id));
        # code...
        $NAMA         = $data["field_nama"];
        $link         = 'bspintar.id';
        $isi          = 'Selamat akun anda sudah direset Sebagai berikut :';
        $subject      = 'Reset Password';
        $Username     = $data["field_handphone"];
        $Email        = $data["field_email"];
        $Password     = $password;
        include "../mail/SendEmail.php"; //email
        $Msg = "Successfully"; //execute query success message
        // echo "kirim EMail";
      } else {
        # code...
        echo "pesan Error";
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} else if (isset($_REQUEST['btn_unlock'])) {
  $id         = $_REQUEST['txt_id'];
  $Status     = 1;
  if (empty($id)) {
    $errorMsg = "Silakan Masukkan ID";
  } elseif (empty($Status)) {
    $errorMsg = "Silakan Masukkan Status";
  } else {
    try {
      $select_stmt = $db->prepare("SELECT * FROM tbluserlogin U WHERE U.field_user_id=:id ORDER BY U.field_user_id DESC "); //sql select query
      $select_stmt->bindParam(':id', $id);
      $select_stmt->execute();
      $data = $select_stmt->fetch(PDO::FETCH_ASSOC);
      $Num  = $select_stmt->rowCount();
      // var_dump($data);
      // die();
      if ($Num == 1) {
        $UpdateNasabah = $db->prepare("UPDATE tbluserlogin U SET U.field_status_aktif=:newstatus WHERE U.field_user_id=:id");
        $UpdateNasabah->execute(array(':newstatus' => $Status, ':id' => $id));
        // # code...
        // $NAMA         = $data["field_nama"];
        // $link         = 'bspintar.id';
        // $isi          = 'Selamat akun anda sudah direset Sebagai berikut :';
        // $subject      = 'Reset Password';
        // $Username     = $data["field_handphone"];
        // $Email        = $data["field_email"];
        // $Password     = $password;
        // include "../mail/SendEmail.php"; //email
        $Msg = "Successfully"; //execute query success message
        echo '<META HTTP-EQUIV="Refresh" Content="1;">';
        // echo "kirim EMail";
      } else {
        # code...
        echo "pesan Error";
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}

//delete
if (isset($_REQUEST['iddd'])) {
  $id = $_REQUEST['id'];

  $select_stmt = $db->prepare('SELECT * FROM tbluserlogin WHERE field_user_id =:id'); //sql select query
  $select_stmt->bindParam(':id', $id);
  $select_stmt->execute();
  $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
  if ($id == $row["field_user_id"]) {
    //echo "TRUE";
    $iduser   = $_SESSION['administrator_id']; //member_id
    $idmember = $_SESSION['administrator_login']; //id_member
    $aktifitas = "DELETE AKUN " . $row["field_member_id"];
    $date     = date("Y-m-d H:s:i");

    $delete_stmt = $db->prepare('DELETE FROM tbluserlogin WHERE field_user_id =:id');
    $delete_stmt->bindParam(':id', $id);

    if ($delete_stmt->execute()) {
      $insert = $db->prepare("INSERT INTO tbluserlog(field_aktifitas,field_member_id,field_user_id,field_waktu)VALUES(:aktifitas,:member_id,:user_id,:waktu)");
      $insert->bindParam(':aktifitas', $aktifitas);
      $insert->bindParam(':member_id', $idmember);
      $insert->bindParam(':user_id', $iduser);
      $insert->bindParam(':waktu', $date);
      $insert->execute();
      $tMsg = "Delete Successfully"; //execute query success message
      echo '<META HTTP-EQUIV="Refresh" Content="1;">';
    }
  } else {
    //echo "FALSE"; 
    echo '<META HTTP-EQUIV="Refresh" Content="1;">';
  }
}

//delete

$id = $_SESSION['idlogin'];
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin WHERE field_user_id=:uid");
$select_stmt->execute(array(":uid" => $id));
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);


$Sql_nasabah = "SELECT * FROM tbluserlogin U JOIN tblnasabah N
              ON U.field_user_id=N.id_UserLogin
              WHERE U.field_status_aktif!=:statuse
              ORDER BY U.field_user_id DESC";
$Stmt_nasabah = $db->prepare($Sql_nasabah);
$Stmt_nasabah->execute(array(":statuse" => 0));
$Stmt_nasabah->execute();
$result_nasabah = $Stmt_nasabah->fetchAll();
$no = 1;

// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=nasabah">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=nasabah">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=nasabah">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=nasabah">';
  }
}



?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Nasabah</h3>

        </div>
        <!-- Content -->

        <!-- /.box-header -->
        <div class="box-body">

          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="text-align:center ;">#</th>
                <th style="text-align:center ;">Foto</th>
                <th style="text-align:center ;">Rekening</th>
                <th style="text-align:center ;">Nama</th>
                <th style="text-align:center ;">Email</th>
                <th style="text-align:center ;">ID Number</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result_nasabah as $nasabah) {
              ?>

                <tr>
                  <td><?php echo $no++ ?></td>

                  <td><img src="../uploads/<?php echo $nasabah['field_photo'] ?>" width="30" height="30" class="img-circle" alt="User Image"></td>
                  <td><strong><?php echo $nasabah["No_Rekening"]; ?></strong></td>
                  <td data-title="Trx Id"><strong><?php echo $nasabah["field_nama"]; ?></strong></td>
                  <td data-title="Trx Id"><strong><?php echo $nasabah["field_email"]; ?></strong></td>
                  <td data-title="Trx Id"><?php echo $nasabah["field_handphone"]; ?></strong><br>

                  </td>

                  <td>
                    <?php
                    $nasabah["field_status_aktif"];
                    if ($nasabah["field_status_aktif"] == 1) {
                      echo '<span class="badge btn-info text-white">Aktif</span>';
                    } elseif ($nasabah["field_status_aktif"] == 2) {
                      echo '<span class="badge btn-danger text-white">Terkunci</span>';
                    }
                    ?>
                  </td>
                  <td ata-title="Trx Id">

                    <?php
                    $nasabah["field_status_aktif"];
                    if ($nasabah["field_status_aktif"] == 1) {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-reset' . $nasabah["field_user_id"] . '" class="btn btn-warning btn-sm"><i class="fa fa-user-circle-o"></i> Reset Password</a> &nbsp';
                    } elseif ($nasabah["field_status_aktif"] == 2) {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-unlock' . $nasabah["field_user_id"] . '" class="btn btn-success btn-sm"><i class="fa fa-user-circle-o"></i> Unlock Password</a> &nbsp';
                    }
                    ?>
                  </td>
                </tr>

                <!-- modal Approval-->
                <div class="modal fade" id="modal-default-reset<?php echo $nasabah["field_user_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Data <?php echo $nasabah["field_user_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-6 control-label">Password Dikirim Ke Alamat Email</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_id" class="form-control" value="<?php echo $nasabah["field_user_id"] ?> " readonly>
                                  <input type="text" name="txt_email" class="form-control" value="<?php echo $nasabah["field_email"] ?> " readonly>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Keluar</button>
                            <input type="submit" name="btn_reset" class="btn btn-success " value="Kirim">
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- modal Unlock-->
                <div class="modal fade" id="modal-default-unlock<?php echo $nasabah["field_user_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Data <?php echo $nasabah["field_user_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-6 control-label">Unlock User </label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_id" class="form-control" value="<?php echo $nasabah["field_user_id"] ?> " readonly>

                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Keluar</button>
                            <input type="submit" name="btn_unlock" class="btn btn-success " value="Unlock">
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
                <th style="text-align:center ;">#</th>
                <th style="text-align:center ;">Foto</th>
                <th style="text-align:center ;">Rekening</th>
                <th style="text-align:center ;">Nama</th>
                <th style="text-align:center ;">Email</th>
                <th style="text-align:center ;">ID Number</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">Action</th>
              </tr>
            </tfoot>
          </table>

        </div>
        <!-- contain -->
      </div>
    </div>
  </div>
</section>