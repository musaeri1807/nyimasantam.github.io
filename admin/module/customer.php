<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

if (isset($_REQUEST['btn_insert'])) {
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
          $nasabah = $db->prepare('INSERT INTO tblnasabah (id_UserLogin)VALUES(:idusers)');
          $nasabah->execute(array(':idusers' => $idusers));

          $pewaris = $db->prepare('INSERT INTO tblpewaris (id_UserLogin)VALUES(:idusers)');
          $pewaris->execute(array(':idusers' => $idusers));
        }
        // die();
        $Msg = "Insert Successfully";
        echo '<META HTTP-EQUIV="Refresh" Content="1">';
        // email
        // include "../mail/mail_register.php";

        // if (!$mail->send()) {

        //   $insertMsg = "Daftar Berhasil ..... Pesan idak dapat dikirim." . $mail->ErrorInfo;
        // } else {
        //   $insertMsg = "Register Successfully, Please Check Your Inbox Email " . $email;
        // }

      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['btn_update'])) {
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
      $caridata = $select_stmt->fetch(PDO::FETCH_ASSOC);

      if ($caridata["field_email"] == $email) {
        $errorMsg = "Maaf Email Sudah Ada";  //check condition email already exists 
      } else if ($caridata["Field_handphone"] == $angka) {
        $errorMsg = "Maaf Nomor Hp Sudah Ada";  //check condition email already exists 
      } elseif (!isset($errorMsg)) {
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $db->prepare("INSERT INTO tbluserlogin
											(field_nama,field_email,field_handphone,field_password,Password,field_tanggal_reg,field_token,field_member_id,field_time_reg,field_ipaddress,field_token_otp,field_branch) VALUES
											(:uname,:uemail,:only,:upassword,:password,:tgl,:rtoken,:id_member,:timee,:addresip,:random,:branch)");

        if ($insert_stmt->execute(array(
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

        ))) {

          $insertMsg = "Insert Successfully";
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['btn_aproval'])) { //account

  echo $idaccount = $_REQUEST['txt_account'];
  die();

  $status = 0;
  if (empty($Token)) {
    $errorMsg = "Silakan Masukkan Token";
  } elseif (strlen(is_numeric($Token)) == 0) {
    $errorMsg = "Silakan Masukkan Token Yang Sesuai";
  } else {
    try {
      $select_stmt = $db->prepare("SELECT * FROM tbluserlogin u JOIN tblbranch b ON u.field_branch=b.field_branch_id WHERE field_status_aktif=:statuse AND field_token_otp =:idtoken ORDER BY field_user_id DESC "); //sql select query
      $select_stmt->bindParam(':idtoken', $Token);
      $select_stmt->bindParam(':statuse', $status);
      $select_stmt->execute();
      $data = $select_stmt->fetch(PDO::FETCH_ASSOC);
      $Num  = $select_stmt->rowCount();


      //echo $Num;
      if ($data['field_token_otp'] !== $Token) {
        $errorMsg = "Token Belum Sesuai";
      } elseif ($data['field_status_aktif'] !== $status) {
        $errorMsg = "Status Tidak Sesuai";
      } elseif (!isset($errorMsg)) {
        if ($Num == 1) {
          //echo "1";


          $no                     = 1;
          $thn                    = substr(date('Y'), -2);
          $bln                    = date("m");
          $branch                 = substr($data["field_member_id"], 0, 10);
          $code                   = $data["field_account_numbers"];
          $char                   = $code . $thn . $bln;
          $nomor                  = $char . sprintf("%04s", $no);


          $select_stmt = $db->prepare('SELECT field_rekening FROM tblcustomer WHERE field_rekening=:unomor'); //sql select query
          $select_stmt->execute(array(':unomor' => $nomor)); //execute query with bind parameter
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
          $DataNum = $select_stmt->rowCount();

          $stmt_rek               = $db->prepare("SELECT field_branch,field_rekening FROM tblcustomer WHERE field_branch=:ubranch ORDER BY field_customer_id DESC LIMIT 1");
          $stmt_rek->execute(array(':ubranch' => $branch));
          $rows_rek               = $stmt_rek->fetch(PDO::FETCH_ASSOC);
          $noseri                 = $rows_rek['field_rekening'];



          if ($DataNum == 0) {
            $no     = 1;
            $thn    = date('Y');
            $thn    = substr($thn, -2);
            $bln    = date('m');
            $code   = $data["field_account_numbers"];
            $char   = $code . $thn . $bln;
            $norek  = $char . sprintf("%04s", $no);
            $norekening = $norek;
          } else {
            $noseri = $rows_rek['field_rekening'];
            $noUrut = substr($noseri, 6);
            $no     = $noUrut + 1;
            $thn    = date('Y');
            $thn    = substr($thn, -2);
            $bln    = date('m');
            $code   = $data["field_account_numbers"];
            $char   = $code . $thn . $bln;
            $norek  = $char . sprintf("%04s", $no);
            $norekening = $norek;
          }



          $member_id    = $data['field_member_id'];
          $nama_lg      = $data["field_nama"];
          $handphone    = $data["field_handphone"];
          $date     = date('Y-m-d');
          $time     = date('H:i:s');
          // echo $norekening;
          // die();

          $update_stmt = $db->prepare("UPDATE tbluserlogin SET field_status_aktif='1' WHERE field_token_otp=:token AND field_status_aktif='0'");
          $update_stmt->execute(array(':token' => $Token));

          $select_stmt = $db->prepare("SELECT * FROM tbluserlogin WHERE field_token_otp=:token AND field_status_aktif='1'"); //sql select query
          $select_stmt->execute(array(':token' => $Token)); //execute query with bind parameter
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
          //Jika Status Rekening N akan di ubah menjadi Y dan Insert Rekening Ke tblcustomer
          if ($row['field_rekening_status'] == 'N') {
            //Update Status Rekening Menjadi Y
            $update_stmt = $db->prepare("UPDATE tbluserlogin SET field_rekening_status='Y' WHERE field_token_otp=:token AND field_status_aktif='1'");
            $update_stmt->execute(array(':token' => $Token));
            //Insert tblcustomer
            $querynasabah = $db->prepare("INSERT INTO tblcustomer (field_branch,field_member_id,field_rekening,field_nama,field_handphone) 
                                                      VALUES (:ubranch,:id_member,:rek,:aman,:hp)");
            $querynasabah->execute(array(':ubranch' => $branch, ':id_member' => $member_id, ':rek' => $norekening, ':aman' => $nama_lg, ':hp' => $handphone));
          }
          if ($row['field_status_aktif'] == 1 and $row['field_token_otp'] = $Token) {
            //noReff
            $sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($order['field_no_referensi'] == "") {
              $no = 1;
              $thn = date('Y');
              $thn = substr($thn, -2);
              $reff = "Reff";
              $char = $thn . $reff;
              $noReff = $char . sprintf("%09s", $no);
            } else {
              $noreff = $order['field_no_referensi'];
              $noUrut = substr($noreff, 6);
              $no = $noUrut + 1;
              $thn = date('Y');
              $thn = substr($thn, -2);
              $reff = "Reff";
              $char = $thn . $reff;
              $noReff = $char . sprintf("%09s", $no);
            }


            $querysaldo = $db->prepare("INSERT INTO tbltrxmutasisaldo 
                                    (field_member_id,field_no_referensi,field_rekening,field_tanggal_saldo,field_time,field_comments) VALUES
                                    (:id_member,:reff,:rek,:tgl,:timee,:comment)");
            $querysaldo->execute(array(
              ':id_member' => $member_id,
              ':reff'   => $noReff,
              ':rek'    => $norekening,
              ':tgl'    => $date,
              ':timee'  => $time,
              ':comment'  => "Balance"
            )); //
            $insertMsg = "Token! Valid";
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://' . $_SERVER["SERVER_NAME"] . '/Login-Register-PHP-PDO/admin/dashboard.php?module=activation">';
          }
        } else {
          echo "2";
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  } //account
} elseif (isset($_REQUEST['xxxx'])) {

  //$id = $_REQUEST['id'];

  if (empty($id)) {
    $errorMsg = "Silakan Id Category";
  } else {
    try {
      if (!isset($errorMsg)) {

        $select_stmt = $db->prepare('SELECT * FROM tblcategory WHERE field_category_id =:id'); //sql select query
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        // echo $row['field_gold_id'];
        $delete_stmt = $db->prepare('DELETE FROM tblcategory WHERE field_category_id =:id');
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();
        // if($delete_stmt->execute()){
        // 	$Msg="Successfully"; //execute query success message
        // 	// echo '<META HTTP-EQUIV="Refresh" Content="1">';
        //   echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';

        // }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['xxxx'])) {

  $idgold = $_REQUEST['xxx'];
  $typeaprove = "A";
  $id = $rows['field_user_id'];

  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {

        $update_stmt = $db->prepare('UPDATE tblgoldprice SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_gold_id=:id'); //sql insert query					

        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);
        $update_stmt->bindParam(':id', $idgold);

        if ($update_stmt->execute()) {
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
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



if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  # code...
  $SQL_User = 'SELECT U.*,N.No_Rekening,B.field_branch_name AS Cabang FROM tbluserlogin U 
                JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                JOIN tblbranch B ON U.field_branch=B.field_branch_id
                WHERE U.field_status_aktif!="1" ORDER BY field_user_id DESC';
  $Stmt = $db->prepare($SQL_User);
  $Stmt->execute();
  $User = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'SPV' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
  # code...
  $SQL_User = 'SELECT U.*,N.No_Rekening,B.field_branch_name AS Cabang FROm tbluserlogin U 
                JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                JOIN tblbranch B ON U.field_branch=B.field_branch_id 
                WHERE U.field_status_aktif!="1" AND U.field_branch=:idbranch ORDER BY field_user_id DESC';
  $Stmt = $db->prepare($SQL_User);
  // $Stmt->execute();
  $Stmt->execute(array(":idbranch" => $branchid));
  $User = $Stmt->fetchAll();
}


if ($_SESSION['rolelogin'] == 'ADM') {
  // $Sql ="SELECT * FROM tbldepartment WHERE field_department_id !='SPA'";
  // $Sql = "SELECT * FROM tbldepartment ";
  // $Stmt = $db->prepare($Sql);
  // $Stmt->execute();
  // $resultdept = $Stmt->fetchAll();
  # code...
  $Sql = "SELECT * FROM tblbranch";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $resultbranch = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'MGR') {
  # code...
  // $Sql = "SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR'";
  // $Stmt = $db->prepare($Sql);
  // $Stmt->execute();
  // $resultdept = $Stmt->fetchAll();

  $Sql = "SELECT * FROM tblbranch";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $resultbranch = $Stmt->fetchAll();
} elseif ($_SESSION['rolelogin'] == 'SPV' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
  # code...
  // $Sql = "SELECT * FROM tbldepartment WHERE field_department_id !='ADM' AND field_department_id !='MGR' AND field_department_id !='SPV'";
  // $Stmt = $db->prepare($Sql);
  // $Stmt->execute();
  // $resultdept = $Stmt->fetchAll();

  $Sql = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
  $Stmt = $db->prepare($Sql);
  //$Stmt->execute();
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
                <th>No</th>
                <th>#</th>
                <th>Nama</th>
                <th>Account</th>
                <th>Username</th>
                <th>Cabang</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($User as $users) {
              ?>

                <tr>
                  <td><?php echo $no++ ?></td>
                  <td>
                    <?php

                    if ($rows['edit'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-nasabah-update' . $users["field_user_id"] . '"class="btn btn-success btn-sm"><i class="fa fa-refresh"></i></a> &nbsp';
                    }
                    if ($rows['view'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-nasabah-view' . $users["field_user_id"] . '"class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> &nbsp';
                    }
                    if ($rows['delete'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-delete' . $users["field_user_id"] . '"class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> &nbsp';
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
                      echo '<span class="badge btn-danger text-white">Verifikasi</span>';
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
                      echo '<a href="../activasipdf.php?m=' . $users['field_user_id'] . '" class="btn btn-warning btn-sm"><i class="fa fa-download"></i></a>&nbsp';
                    } elseif ($status == "1") {
                      echo '<span class="badge btn-info text-white">Aktif</span>';
                    } elseif ($status == "2") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $users["field_member_id"] . '" class="btn btn-default btn-sm"><i class="fa fa-envelope-o "></i> Send Password </a> &nbsp';
                    }
                    echo '<a href="?module=updcustomer&id=' . $users["field_user_id"] . '" class="btn btn-success btn-sm "><i class="fa fa-refresh"></i> Memperbarui</a>&nbsp';
                    echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $users["field_user_id"] . '" class="btn btn-info btn-sm"><i class="fa fa-user-circle-o"></i> Setujui</a> &nbsp';

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
                          <h4 class="modal-title">Update Data <?php echo $users["field_user_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">ID</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_account" class="form-control" value="<?php echo $users["field_user_id"] ?>">
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
                            <input type="submit" name="btn_aproval" class="btn btn-success " value="Perbarui">
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
                <th>No</th>
                <th>#</th>
                <th>Nama</th>
                <th>Account</th>
                <th>Username</th>
                <th>Cabang</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>
    </div>
  </div>
</section>