<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}


if (isset($_REQUEST['btn_insert'])) { //Done
  $Nama    = strip_tags($_REQUEST['txt_firstname']);
  $nama     = ucwords($Nama);
  $email    = strip_tags($_REQUEST['txt_email']);
  $password   = password_generate(8);
  $angka    = strip_tags($_REQUEST['txt_angka']);
  $date    = date('Y-m-d');
  $time    = date('H:i:s');
  $random   = (rand(999, 9999));
  $tokenn    = hash('sha256', md5(date('Y-m-d h:i:s')));
  $cabang    = $_REQUEST['txt_cabang'];
  $member_id  = $cabang . $angka;
  $ipaddress   = $_SERVER['REMOTE_ADDR'];

  if (empty($nama)) {
    $errorMsg = "Silakan Memasukan Nama Anda";
  } else if (empty($email)) {
    $errorMsg = "Silakan Memasukan Email Anda";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMsg = "Silakan Memasukan Alamat Email yang Valid";
  } else if (strlen(is_numeric($angka)) == 0) {
    $errorMsg = "Silakan Memasukan Angka";
  } else if (strlen($angka) < 10) {
    $errorMsg = "Nomor Hp Tidak Sesuai";
  } else if (strlen($angka) > 12) {
    $errorMsg = "Nomor Hp Terlalu Panjang";
  } elseif ($cabang == "Pilih") {
    $errorMsg = "Silakan Pilih Kantor Cabang";
  } else {
    try {

      $select_stmt = $db->prepare("SELECT field_email,Field_handphone  FROM tbluserlogin 
										WHERE field_email=:uemail OR Field_handphone=:only"); // sql select query			
      $select_stmt->execute(array(
        ':uemail'  => $email,
        ':only'    => $angka
      )); //execute query 
      $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
      if ($row["field_email"] == $email) {
        $errorMsg = "Maaf Email Sudah Ada";  //check condition email already exists 
      } else if ($row["Field_handphone"] == $angka) {
        $errorMsg = "Maaf Nomor Hp Sudah Ada";  //check condition email already exists 
      } elseif (!isset($errorMsg)) {
        //rekening

        $cabang; //data cabanng daftar
        $cabangid = $db->prepare("SELECT * FROM tblbranch B
                                                  WHERE B.field_branch_id=:ubranch
                                                  ORDER BY B.field_branch_id DESC");
        $cabangid->execute(array(':ubranch' => $cabang));
        $kode_cabang               = $cabangid->fetch(PDO::FETCH_ASSOC);

        $Query_cabang            = $db->prepare("SELECT * FROM tbluserlogin U
                                                  JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                                                  JOIN tblbranch B ON U.field_branch=B.field_branch_id
                                                  WHERE B.field_branch_id=:ubranch
                                                  ORDER BY U.field_user_id DESC LIMIT 1");
        $Query_cabang->execute(array(':ubranch' => $cabang));
        $idaccount               = $Query_cabang->fetch(PDO::FETCH_ASSOC);

        if (empty($idaccount)) {
          $code                   = $kode_cabang["field_account_numbers"]; //cabang masing-masing
          $thn                    = substr(date("Y", strtotime($date)), -2);
          $bln                    = date("m", strtotime($date));
          $no                     = 1;
          $char                   = $code . $thn . $bln;
          $norek                  = $char . sprintf("%04s", $no);
          $norekening = $norek;
        } else {
          $ambildate = substr($idaccount['No_Rekening'], 4, 2);

          if ($ambildate !== date("m", strtotime($date))) {
            # code...
            $code                   = $kode_cabang["field_account_numbers"]; //cabang masing-masing
            $thn                    = substr(date("Y", strtotime($date)), -2);
            $bln                    = date("m", strtotime($date));
            $no                     = 1;
            $char                   = $code . $thn . $bln;
            $norek                  = $char . sprintf("%04s", $no);
            $norekening = $norek;
          } else {
            # code...
            $code                   = $kode_cabang["field_account_numbers"];
            $noseri                 = $idaccount['No_Rekening'];
            $noUrut                 = substr($noseri, 6);
            $thn                    = substr(date("Y", strtotime($date)), -2);
            $bln                    = date("m", strtotime($date));
            $no     = $noUrut + 1;
            $char   = $code . $thn . $bln;
            $norek  = $char . sprintf("%04s", $no);
            $norekening = $norek;
          }
        }
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $db->prepare("INSERT INTO tbluserlogin
											(field_nama,field_email,field_handphone,field_password,Password,field_tanggal_reg,field_token,field_member_id,field_time_reg,field_ipaddress,field_token_otp,field_branch) VALUES
											(:uname,:uemail,:only,:upassword,:password,:tgl,:rtoken,:id_member,:timee,:addresip,:random,:branch)");

        $insert_stmt->execute(array(
          ':uname'  => $nama,
          ':uemail'  => $email,
          ':only'   => $angka,
          ':upassword' => $new_password,
          ':password'  => $password,
          ':tgl'    => $date,
          ':rtoken'  => $tokenn,
          ':id_member' => $member_id,
          ':timee'  => $time,
          ':addresip'  => $ipaddress,
          ':random'  => $random,
          ':branch'  => $cabang

        ));
        $idusers = $db->lastinsertid();
        if ($idusers) {
          $nasabah = $db->prepare('INSERT INTO tblnasabah (id_UserLogin,No_Rekening)VALUES(:idusers,:rekening)');
          $nasabah->execute(array(':idusers' => $idusers, ':rekening' => $norekening));

          $pewaris = $db->prepare('INSERT INTO tblpewaris (id_UserLogin)VALUES(:idusers)');
          $pewaris->execute(array(':idusers' => $idusers));
        }

        $Msg = "Insert Successfully";
        echo '<META HTTP-EQUIV="Refresh" Content="1">';
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['btn_update'])) {

  $Nama         = strip_tags($_REQUEST['txt_firstname']);
  $nama         = ucwords($Nama);
  $email        = strip_tags($_REQUEST['txt_email']);
  $angka        = strip_tags($_REQUEST['txt_angka']);
  $cabang       = $_REQUEST['txt_cabang'];

  echo  $cabang ;
  var_dump($cabang );
  die();





  if (empty($nama)) {
    $errorMsg = "Silakan Memasukan Nama Anda";
  } else if (empty($email)) {
    $errorMsg = "Silakan Memasukan Email Anda";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMsg = "Silakan Memasukan Alamat Email yang Valid";
  } else if (strlen(is_numeric($angka)) == 0) {
    $errorMsg = "Silakan Memasukan Angka";
  } else if (strlen($angka) < 10) {
    $errorMsg = "Nomor Hp Tidak Sesuai";
  } else if (strlen($angka) > 12) {
    $errorMsg = "Nomor Hp Terlalu Panjang";
  } elseif ($cabang == "Pilih") {
    $errorMsg = "Silakan Pilih Kantor Cabang";
  } else {
    try {

      $select_stmt = $db->prepare("SELECT field_email,Field_handphone  FROM tbluserlogin 
										WHERE field_email=:uemail OR Field_handphone=:only"); // sql select query			
      $select_stmt->execute(array(
        ':uemail'  => $email,
        ':only'    => $angka
      )); //execute query 
      $caridata = $select_stmt->fetch(PDO::FETCH_ASSOC);
      

      // var_dump($caridata);
      // die();

      if ($caridata["field_email"] == $email) {
        $errorMsg = "Maaf Email Sudah Ada";  //check condition email already exists 
      } else if ($caridata["Field_handphone"] == $angka) {
        $errorMsg = "Maaf Nomor Hp Sudah Ada";  //check condition email already exists 
      } elseif (!isset($errorMsg)) {

        $Update_stmt = $db->prepare("INSERT INTO tbluserlogin
											(field_nama,field_email,field_handphone,field_password,Password,field_tanggal_reg,field_token,field_member_id,field_time_reg,field_ipaddress,field_token_otp,field_branch) VALUES
											(:uname,:uemail,:only,:upassword,:password,:tgl,:rtoken,:id_member,:timee,:addresip,:random,:branch)");

        if ($Update_stmt->execute(array(
          ':uname'  => $nama,
          ':uemail'  => $email,
          ':only'   => $angka,
          ':branch'  => $cabang


        ))) {
          $Msg = "Insert Successfully";
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  
} elseif (isset($_REQUEST['btn_aproval'])) { //account done

  $idaccount = $_REQUEST['txt_account'];
  $status = 0;
  if (empty($idaccount)) {
    $errorMsg = "Silakan Masukkan ID";
  } elseif (strlen(is_numeric($idaccount)) == 0) {
    $errorMsg = "Silakan Masukkan ID Yang Sesuai";
  } else {
    try {
      $select_stmt = $db->prepare("SELECT U.*,N.* FROM tbluserlogin U 
                                    JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                                    WHERE U.field_status_aktif=:statuse AND  U.field_user_id=:id
                                    ORDER BY U.field_user_id DESC LIMIT 1"); //sql select query
      $select_stmt->bindParam(':id', $idaccount);
      $select_stmt->bindParam(':statuse', $status);
      $select_stmt->execute();
      $data = $select_stmt->fetch(PDO::FETCH_ASSOC);
      $Num  = $select_stmt->rowCount();


      if ($Num == 1) {

        $member_id    = $data['field_member_id'];
        $nama_lg      = $data["field_nama"];
        $handphone    = $data["field_handphone"];
        $Query_norekening = $data["No_Rekening"];
        $date     = date('Y-m-d');
        $time     = date('H:i:s');

        $UpdateNasabah = $db->prepare("UPDATE tblnasabah N, tbluserlogin U SET N.Tgl_Nasabah=:tanggal,U.field_status_aktif=:satu 
                                        WHERE N.id_UserLogin=U.field_user_id AND U.field_user_id=:id");
        $UpdateNasabah->execute(array(':id' => $idaccount, ':tanggal' => $date, ':satu' => 1));

        if ($Num) {
          //noReff
          $sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
          $stmt = $db->prepare($sql);
          $stmt->execute();
          $nomor = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($nomor['field_no_referensi'] == "") {
            $no = 1;
            $thn = date('Y');
            $thn = substr($thn, -2);
            $reff = "Reff";
            $char = $thn . $reff;
            $noReff = $char . sprintf("%09s", $no);
          } else {
            //jika tahun pendaftaran user tidak sama dengan tahun hari ini maka nomor kereset menjadi awal jika tidak maka nomor melajutkan
            $tahun = substr($data['field_tanggal_reg'], 2, 2);
            $tahunSekarang = substr(date('Y'), 2, 2);
            if ($tahun !== $tahunSekarang) {
              $no = 1;
              $thn = date('Y');
              $thn = substr($thn, -2);
              $reff = "Reff";
              $char = $thn . $reff;
              $noReff = $char . sprintf("%09s", $no);
            } else {
              $noreff = $nomor['field_no_referensi'];
              $noUrut = substr($noreff, 6);
              $no = $noUrut + 1;
              $thn = date('Y');
              $thn = substr($thn, -2);
              $reff = "Reff";
              $char = $thn . $reff;
              $noReff = $char . sprintf("%09s", $no);
            }
          }

          $querysaldo = $db->prepare("INSERT INTO tbltrxmutasisaldo( 
                                    field_member_id,
                                    field_no_referensi,
                                    field_rekening,
                                    field_tanggal_saldo,
                                    field_time,
                                    field_status,
                                    field_comments) 
                                    VALUES
                                    (:id_member,
                                    :reff,
                                    :rek,
                                    :tgl,
                                    :timee,
                                    :status,
                                    :comment)");
          $querysaldo->execute(array(
            ':id_member' => $member_id,
            ':reff'   => $noReff,
            ':rek'    => $Query_norekening,
            ':tgl'    => $date,
            ':timee'  => $time,
            ':status' => 'S',
            ':comment'  => "Balance"
          )); //

          // $NAMA         = $data["field_nama"];
          // $link         = 'bspintar.id';
          // $isi          = 'Selamat akun anda sudah terdaftar Sebagai berikut :';
          // $subject      = 'Register';
          // $Username     = $data["field_handphone"];
          // $Email        = $data["field_email"];
          // $Password     = $data["Password"];

          $UsernameEmail = $data["field_email"];
          $Username = $data["field_handphone"];
          $Password = $data["Password"];
          include "../mail/SendEmailUser.php"; //email

          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="10;">';
        }
      } else {
        $errorMsg = "Data Tidak Ditemukan";
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  } //account
} elseif (isset($_REQUEST['idelete'])) {
  $id = $_REQUEST['idelete'];
  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {
        $id = $_REQUEST['idelete'];
        $select_stmt = $db->prepare('SELECT * FROM tbluserlogin WHERE field_user_id =:id'); //sql select query
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        if ($id == $row["field_user_id"]) {
          //echo "TRUE";
          $iduser   = $_SESSION['idlogin']; //member_id
          $idmember = $_SESSION['userlogin']; //id_member
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
            if ($insert->execute()) {
              $Msg = "Delete Successfully"; //execute query success message
              echo '<META HTTP-EQUIV="Refresh" Content="1;">';
            }
          }
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}
// WHERE U.field_status_aktif!="1" ORDER BY field_user_id DESC';

//show all data nasabah yang tidak sama dengan satu
if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  # code...
  $SQL_User = 'SELECT U.*,N.No_Rekening,N.Konfirmasi,B.field_branch_name AS Cabang FROM tbluserlogin U 
                JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                JOIN tblbranch B ON U.field_branch=B.field_branch_id
                WHERE U.field_status_aktif="0"
                ORDER BY U.field_user_id DESC';
  $Stmt = $db->prepare($SQL_User);
  $Stmt->execute();
  $User = $Stmt->fetchAll();
  // } elseif ($_SESSION['rolelogin'] == 'SPV' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
} else {
  # code...
  $SQL_User = 'SELECT U.*,N.No_Rekening,N.Konfirmasi,B.field_branch_name AS Cabang FROM tbluserlogin U 
                JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                JOIN tblbranch B ON U.field_branch=B.field_branch_id
                WHERE U.field_status_aktif="0" AND U.field_branch=:idbranch 
                ORDER BY U.field_user_id DESC';
  $Stmt = $db->prepare($SQL_User);
  // $Stmt->execute();
  $Stmt->execute(array(":idbranch" => $branchid));
  $User = $Stmt->fetchAll();
}


if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  $Sql = "SELECT * FROM tblbranch";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $resultbranch = $Stmt->fetchAll();
  // } elseif ($_SESSION['rolelogin'] == 'SPV' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
} else {
  $Sql = "SELECT * FROM tblbranch  WHERE field_branch_id=:idbranch";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute(array(":idbranch" => $branchid));
  $resultbranch = $Stmt->fetchAll();
}

$no = 1;

// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=customer">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=customer">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=customer">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=customer">';
  }
}

?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Register Nasabah</h3>
          <?php
          if ($rows['add'] == 'Y') {
            echo '<a data-toggle="modal" data-target="#modal-nasabah" class="btn btn-success  pull-right"><i class="fa fa-plus"></i>&nbsp Add &nbsp</a>';
          }
          ?>
          <!-- <a href="?module=addcustomer" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add</a> -->
        </div>
        <!-- modal add -->
        <div class="modal fade" id="modal-nasabah">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <center>
                  <h4 class="modal-title">Add Nasabah</h4>
                </center>
              </div>
              <div class="modal-body">
                <form method="post" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Nama</label>
                    <div class="col-sm-6">
                      <input type="text" name="txt_firstname" class="form-control" placeholder="Masukkan Nama" />
                    </div>

                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-6">
                      <input type="text" name="txt_email" class="form-control" placeholder="Masukkan Email" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Handphone</label>
                    <div class="col-sm-6">
                      <input type="text" name="txt_angka" class="form-control" placeholder="Masukkan No Hp 08XX" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Cabang</label>
                    <div class="col-sm-6">
                      <select class="form-control" type="text" name="txt_cabang">
                        <option>Pilih</option>
                        <?php foreach ($resultbranch as $branch) { ?>
                          <option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_id'] . "-";
                                                                                    echo $branch['field_branch_name']; ?></option>

                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger " data-dismiss="modal">Keluar</button>
                    <input type="submit" name="btn_insert" class="btn btn-success " value="Simpan">
                  </div>
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Content -->
        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="text-align:center ;">No</th>
                <th style="text-align:center ;">#</th>
                <th style="text-align:center ;">Nama</th>
                <th style="text-align:center ;">Account</th>
                <th style="text-align:center ;">Username</th>
                <th style="text-align:center ;">Cabang</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($User as $users) {
              ?>

                <tr>
                  <td style="text-align:center ;"><strong><?php echo $no++ ?></strong></td>
                  <td>
                    <?php

                    if ($rows['edit'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-nasabah-update' . $users["field_user_id"] . '"class="btn btn-success btn-sm"><i class="fa fa-refresh"></i></a>&nbsp';
                    }
                    if ($rows['view'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-nasabah-view' . $users["field_user_id"] . '"class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a>&nbsp';
                    }
                    if ($rows['delete'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-delete' . $users["field_user_id"] . '"class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>&nbsp';
                    }

                    ?>
                  </td>
                  <td data-title="Trx Id"><strong><?php echo $users["field_nama"]; ?></strong></td>
                  <td data-title="Trx Id"><strong><?php echo $users["No_Rekening"]; ?></strong></td>
                  <td data-title="Trx Id"><strong><?php echo $users["field_email"]; ?> | <?php echo $users["field_handphone"]; ?></strong><br>

                  </td>
                  <td><strong><?php echo $users["Cabang"]; ?></strong></td>
                  <td>
                    <?php
                    $status = $users["field_status_aktif"];
                    if ($status == "0") {
                      if ($users['Konfirmasi'] !== "Y") {
                        echo '<span class="badge btn-danger text-white">Lengkapi Data</span>';
                      } else {
                        echo '<span class="badge btn-warning text-white">Menunggu Rilis</span>';
                      }
                    } elseif ($status == "1") {
                      echo '<span class="badge btn-info text-white">Aktif</span>';
                    } elseif ($status == "2") {
                      echo '<span class="badge btn-warning text-white">Tidak Aktif</span>';
                    }
                    ?>
                  </td>
                  <td ata-title="Trx Id">
                    <?php
                    $status = $users["field_status_aktif"];
                    if ($status == "0") {
                      if ($users['Konfirmasi'] !== "Y") {
                        echo '<a href="?module=updcustomer&id=' . $users["field_user_id"] . '" class="btn btn-success btn-sm "><i class="fa fa-refresh"></i> Memperbarui</a>&nbsp';
                      } else {
                        echo '<a href="../formulirpdf.php?m=' . $users['field_user_id'] . '" class="btn btn-warning btn-sm"><i class="fa fa-download"></i></a>&nbsp';
                        if ($rows['approval'] == "Y") {
                          echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $users["field_user_id"] . '" class="btn btn-info btn-sm"><i class="fa fa-user-circle-o"></i> Rilis</a> &nbsp';
                        }
                      }
                    }

                    ?>
                  </td>
                </tr>

                <!-- modal Approval-->
                <div class="modal fade" id="modal-default-aproval<?php echo $users["field_user_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Username dan Password Kirim Ke Email</h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">ID</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_email" class="form-control" value="<?php echo $users["field_email"] ?>" readonly>
                                  <input type="hidden" name="txt_account" class="form-control" value="<?php echo $users["field_user_id"] ?>">
                                </div>
                              </div>

                            </div>



                            <!-- <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Cabang</label>
                                <div class="col-sm-6">
                                  <select class="form-control" type="text" name="txt_cabang">
                                    <option value=""><?php echo $users["Cabang"]; ?></option>
                                    <?php foreach ($resultbranch as $branch) { ?>
                                      <option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_id'] . "-";
                                                                                                echo $branch['field_branch_name']; ?></option>

                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div> -->
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Keluar</button>
                            <input type="submit" name="btn_aproval" class="btn btn-success " value="Kirim">
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- modal update-->
                <div class="modal fade" id="modal-nasabah-update<?php echo $users["field_user_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Data <?php echo $users["field_user_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Nama</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_firstname" class="form-control" value="<?php echo $users["field_nama"]; ?>">
                                </div>
                              </div>

                            </div>
                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_email" class="form-control" value="<?php echo $users["field_email"]; ?>">
                                </div>
                              </div>
                            </div>

                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Handphone</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_angka" class="form-control" value="<?php echo $users["field_handphone"]; ?>">
                                </div>
                              </div>
                            </div>
                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Cabang</label>
                                <div class="col-sm-6">
                                  <select class="form-control" type="text" name="txt_cabang">
                                    <option value=""><?php echo $users["Cabang"]; ?></option>
                                    <?php foreach ($resultbranch as $branch) { ?>
                                      <option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_id'] . "-";
                                                                                                echo $branch['field_branch_name']; ?></option>

                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Keluar</button>
                            <input type="submit" name="btn_update" class="btn btn-success " value="Perbarui">
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- modal view-->
                <div class="modal fade" id="modal-nasabah-view<?php echo $users["field_user_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Data <?php echo $users["field_user_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Nama</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_firstname" class="form-control" value="<?php echo $users["field_nama"]; ?>" readonly>
                                </div>
                              </div>

                            </div>
                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_email" class="form-control" value="<?php echo $users["field_email"]; ?>" readonly>
                                </div>
                              </div>
                            </div>

                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Handphone</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_angka" class="form-control" value="<?php echo $users["field_handphone"]; ?>" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="box-header">
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Cabang</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_angka" class="form-control" value="<?php echo $users["Cabang"]; ?>" readonly>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-warning " data-dismiss="modal">Keluar</button>

                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->


                <!-- Modal Delete -->
                <div class="modal fade" id="modal-delete<?php echo $users["field_user_id"]; ?>">
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
                                  echo "Uername " . $users["field_nama"] . " Dengan " . $users["field_email"];
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Tidak</button>
                            <a href="?module=customer&idelete=<?php echo $users['field_user_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp&nbsp Iya &nbsp&nbsp</a>
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
                <th style="text-align:center ;">#</th>
                <th style="text-align:center ;">Nama</th>
                <th style="text-align:center ;">Account</th>
                <th style="text-align:center ;">Username</th>
                <th style="text-align:center ;">Cabang</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">Action</th>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>
    </div>
  </div>
</section>