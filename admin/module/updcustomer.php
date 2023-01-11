<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}


echo $_REQUEST['id'];

$idusers = $_REQUEST['id'];

$select_stmt = $db->prepare("SELECT U.field_user_id,U.field_member_id,U.field_branch,U.field_nama,U.field_email,U.field_handphone,N.* ,W.*,B.field_account_numbers AS idcabang
                            FROM tbluserlogin U 
                            JOIN tblbranch B ON U.field_branch=B.field_branch_id
                            JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
                            JOIN tblpewaris W ON U.field_user_id=W.id_UserLogin 
                            WHERE U.field_user_id=:id ORDER BY U.field_user_id DESC"); //sql select query		
$select_stmt->bindParam(':id', $idusers);
$select_stmt->execute();
$DataUsers = $select_stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($DataUsers);
// die();

//echo $idcabang;
$select_stmt = $db->prepare("SELECT * FROM  tblbranch WHERE field_branch_id =:id "); //sql select query
$select_stmt->bindParam(':id', $idcabang);
$select_stmt->execute();
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);


$Sql = "SELECT * FROM tblbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();
//extract($row);                

if (isset($_REQUEST['btn_update'])) {

  $konfirmasi = $_REQUEST['txt_lengkap'];
  $id = $_REQUEST['id'];
  $nik  = $_REQUEST['txt_nik'];
  $gender = $_REQUEST['txt_gender'];
  $alamat = $_REQUEST['txt_alamat'];
  $provinsi = $_REQUEST['txt_provinsi'];
  $kabupaten = $_REQUEST['txt_kabupaten'];
  $kecamatan = $_REQUEST['txt_kecamatan'];
  $kelurahan = $_REQUEST['txt_kelurahan'];
  $agama = $_REQUEST['txt_agama'];
  $status = $_REQUEST['txt_status'];
  $date= date('Y-m-d');

  if (empty($id)) {
    $errorMsg = "Silakan Memasukan Id Anda";
  } else if (strlen(is_numeric($nik)) == 0) {
    $errorMsg = "Silakan Memasukan Angka";
  } else if (strlen($nik) < 16) {
    $errorMsg = "Nomor NIK Terlalun Pendek";
  } else if (strlen($nik) > 16) {
    $errorMsg = "Nomor NIK Terlalu Panjang";
  } elseif ($provinsi == "") {
    $errorMsg = "Silakan Pilih Provinsi";
  } elseif ($kabupaten == "") {
    $errorMsg = "Silakan Pilih kabupaten";
  } elseif ($kecamatan == "") {
    $errorMsg = "Silakan Pilih kecamatan";
  } elseif ($kelurahan == "") {
    $errorMsg = "Silakan Pilih kelurahan";
  } else {
    try {


      $select_stmt = $db->prepare("SELECT * FROM tblwilayahprovinsi PRO 
                                    JOIN tblwilayahkabupaten KAB ON PRO.field_provinsi_id=KAB.field_provinsi_id
                                    JOIN tblwilayahkecamatan KEC ON KAB.field_kabupaten_id=KEC.field_kabupaten_id
                                    JOIN tblwilayahdesa KEL ON KEC.field_kecamatan_id=KEL.field_kecamatan_id
                                    WHERE PRO.field_provinsi_id=:provinsi 
                                    AND KAB.field_kabupaten_id=:kebupaten 
                                    AND KEC.field_kecamatan_id=:kecamatan 
                                    AND KEL.field_desa_id=:kelurahan");
      $select_stmt->execute(array(
        ":provinsi" => $provinsi,
        ":kebupaten" => $kabupaten,
        ":kecamatan" => $kecamatan,
        ":kelurahan" => $kelurahan,
      ));
      $data = $select_stmt->fetch(PDO::FETCH_ASSOC);

      $provinsi  = $data['field_nama_provinsi'];
      $kabupaten = $data['field_nama_kabupaten'];
      $kecamatan = $data['field_nama_kecamatan'];
      $kelurahan = $data['field_nama_desa'];


      $update_nasabah = $db->prepare('UPDATE tblnasabah SET 
                            Tgl_Nasabah=:date,
                            Nik_Nasabah=:nik,
                            Jenis_Kelamin_N=:gender,
                            Alamat_Nasabah=:alamat,
                            Provinsi_N=:provinsi,
                            Kabupaten_N=:kabupaten,
                            Kecamatan_N=:kecamatan,
                            Kelurahan_N=:kelurahan,
                            Agama_N=:agama,
                            Status_N=:statuse,
                            Konfirmasi=:konfirmasi     
                                WHERE id_UserLogin=:id');
      $update_nasabah->bindParam(':id', $id);
      $update_nasabah->bindParam(':date', $date);
      $update_nasabah->bindParam(':nik', $nik);
      $update_nasabah->bindParam(':gender', $gender);
      $update_nasabah->bindParam(':alamat', $alamat);
      $update_nasabah->bindParam(':provinsi', $provinsi);
      $update_nasabah->bindParam(':kabupaten', $kabupaten);
      $update_nasabah->bindParam(':kecamatan', $kecamatan);
      $update_nasabah->bindParam(':kelurahan', $kelurahan);
      $update_nasabah->bindParam(':agama', $agama);
      $update_nasabah->bindParam(':statuse', $status);
      $update_nasabah->bindParam(':konfirmasi', $konfirmasi);

      if ($update_nasabah->execute()) {
        $Msg = "Update Successfully"; //execute query success message
        echo '<META HTTP-EQUIV="Refresh" Content="10;">';
      }
      // }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}


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
<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="../uploads/1.png" alt="User profile picture">
          <h3 class="profile-username text-center"><?php echo $DataUsers["field_nama"]; ?></h3>
          <p class="text-muted text-center"><?php echo $DataUsers["field_branch"]; ?></p>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- About Me Box -->
      <div class="box box-primary">
        <!-- <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div> -->

        <!-- /.box-header -->
        <div class="box-body">
          <hr>
          <strong><i class="fa fa-envelope  margin-r-5"></i> <?php echo $DataUsers["field_email"]; ?></strong>
          <hr>
          <strong><i class="fa fa-mobile margin-r-5"></i> <?php echo $DataUsers["field_handphone"]; ?></strong>
          <hr>
          <strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $DataUsers["field_member_id"]; ?></strong>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#activity" data-toggle="tab">Data Pelangan</a></li>
          <!-- <li><a href="#timeline" data-toggle="tab">Timeline</a></li> -->
          <!-- <li><a href="#settings" data-toggle="tab">Ahli Waris</a></li> -->
        </ul>
        <div class="tab-content">
          <div class="active tab-pane" id="activity">
            <!-- Post -->
            <div class="post clearfix">
              <div>
              </div>
              <form class="form-horizontal" method="POST">
                <!-- <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">Id User</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" value="<?php echo $DataUsers["field_user_id"]; ?>" readonly>
                    <input type="number" class="form-control" value="<?php echo $DataUsers["No_Rekening"]; ?>" readonly>
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">NIK</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" name="txt_nik" id="nik" value="<?php echo $DataUsers["Nik_Nasabah"]; ?>">
                  </div>
                </div>
                <!-- <div class="form-group">
                  <label for="inputEmail" class="col-sm-2 control-label">Tanggal Lahir</label>

                  <div class="col-sm-6">
                    <input type="date" class="form-control" value="12-12-2002" placeholder="Email">
                  </div>
                </div> -->


                <div class="form-group">
                  <label for="inputSkills" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_gender" id="gender">
                      <option value="<?php if ($DataUsers['Jenis_Kelamin_N'] == 'L') {
                                        echo "L";
                                      } elseif ($DataUsers['Jenis_Kelamin_N'] == 'P') {
                                        echo "P";
                                      } ?>">
                        <?php if ($DataUsers['Jenis_Kelamin_N'] == 'L') {
                          echo "Laki-Laki";
                        } elseif ($DataUsers['Jenis_Kelamin_N'] == 'P') {
                          echo "Perempuan";
                        }
                        ?>
                      </option>
                      <option value="L">Laki-Laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputExperience" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-6">
                    <!-- <input type="text" class="form-control" name="txt_alamat" id="alamat" value="<?php echo $DataUsers["Alamat_Nasabah"]; ?>"> -->
                    <textarea type="text" class="form-control" name="txt_alamat" id="alamat" value="<?php echo $DataUsers['Alamat_Nasabah']; ?>"><?php echo htmlentities($DataUsers['Alamat_Nasabah']); ?> </textarea>
                  </div>
                </div>


                <div class="form-group">
                  <label for="inputSkills" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_provinsi" id="provinsi"></select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Kabupaten</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_kabupaten" id="kabupaten">
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_kecamatan" id="kecamatan">
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_kelurahan" id="kelurahan">
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputSkills" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_agama" id="">
                      <option value="<?php echo $DataUsers['Agama_N']; ?>"><?php echo $DataUsers['Agama_N']; ?></option>
                      <option value="ISLAM">ISLAM</option>
                      <option value="PROTESTAN">PROTESTAN</option>
                      <option value="KATOLIK">KATOLIK</option>
                      <option value="HINDU">HINDU</option>
                      <option value="BUDDHA">BUDDHA</option>
                      <option value="KHONGHUCU">KHONGHUCU</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputSkills" class="col-sm-2 control-label">Status Perkawinan</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_status" id="status">
                      <option value="<?php echo $DataUsers['Status_N']; ?>"><?php echo $DataUsers['Status_N']; ?></option>
                      <option value="KAWIN">KAWIN</option>
                      <option value="BELUM KAWIN ">BELUM KAWIN</option>
                      <option value="DUDA">DUDA</option>
                      <option value="JANDA">JANDA</option>

                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputEmail" class="col-sm-2 control-label"></label>
                  <div class="col-sm-6">
                    <input type="checkbox" name="txt_lengkap" value="Y" required> Apakah Data Sudah lengkap Semua ?
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="btn_update" class="btn btn-success">Perbarui</button>
                    <a href="?module=customer" class="btn btn-danger">Keluar</a>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.post -->

          </div>
          <div class="tab-pane" id="settings">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">NIK</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputName" placeholder="Name" value="">
                </div>
              </div>

              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputName" placeholder="Name">
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Date of birth</label>

                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                  <input type="date" class="form-control" id="inputEmail" placeholder="Email">
                </div>
              </div>
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Gender</label>

                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputName" placeholder="Name">
                </div>
              </div>

              <div class="form-group">
                <label for="inputExperience" class="col-sm-2 control-label">Address</label>

                <div class="col-sm-10">
                  <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                </div>
              </div>


              <div class="form-group">
                <label for="inputSkills" class="col-sm-2 control-label">Provinsi</label>
                <div class="col-sm-6">
                  <select class="form-control" type="text" name="txt_provinsi" id="provinsi2"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Kabupaten</label>
                <div class="col-sm-6">
                  <select class="form-control" type="text" name="txt_kabupaten" id="kabupaten2">
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Kecamatan</label>
                <div class="col-sm-6">
                  <select class="form-control" type="text" name="txt_kecamatan" id="kecamatan2">
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Kelurahan</label>
                <div class="col-sm-6">
                  <select class="form-control" type="text" name="txt_kelurahan" id="kelurahan2">
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="inputSkills" class="col-sm-2 control-label">Agama</label>
                <div class="col-sm-6">
                  <select class="form-control" type="text" name="txt_agama" id="agama">
                    <option value="">Pilih</option>
                    <option value="">ISLAM</option>
                    <option value="">PROSTESTAN</option>
                    <option value="">KATOLIK</option>
                    <option value="">HINDU</option>
                    <option value="">BUDHA</option>
                    <option value="">KHONGHUCU</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="inputSkills" class="col-sm-2 control-label">Status Perkawinan</label>
                <div class="col-sm-6">
                  <select class="form-control" type="text" name="txt_status" id="">
                    <option value="">Pilih</option>
                    <option value="">KAWIN</option>
                    <option value="">BELUM KAWIN</option>
                    <option value="">DUDA</option>
                    <option value="">JANDA</option>

                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-danger">Submit</button>

                </div>
              </div>
            </form>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

</section>
<!-- /.content -->