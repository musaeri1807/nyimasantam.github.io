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


$select_stmt = $db->prepare("SELECT U.field_user_id,U.field_member_id,U.field_branch,U.field_nama,U.field_email,U.field_handphone,N.* ,W.* 
FROM tbluserlogin U JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
JOIN tblpewaris W ON U.field_user_id=W.id_UserLogin WHERE U.field_user_id=:id ORDER BY U.field_user_id DESC"); //sql select query		
$select_stmt->bindParam(':id', $idusers);
$select_stmt->execute();
$DataUsers = $select_stmt->fetch(PDO::FETCH_ASSOC);
echo $DataUsers['field_nama'];
// echo $_REQUEST['btn_update'];

// die();

// if (isset($_REQUEST['id'])) {
//   try {
//     $id = $_REQUEST['id']; //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
//     $select_stmt = $db->prepare("SELECT * FROM  tblcustomer n JOIN tbluserlogin u ON n.field_member_id=u.field_member_id  WHERE field_customer_id =:id "); //sql select query		
//     $select_stmt->bindParam(':id', $id);
//     $select_stmt->execute();
//     $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
//     //extract($row);
//     $idcabang = substr($row["field_member_id"], 0, 10);
//   } catch (PDOException $e) {
//     $e->getMessage();
//   }
// }

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
  echo $_REQUEST['id'];
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

  $select_stmt = $db->prepare("SELECT * FROM tblwilayahprovinsi PRO 
  JOIN tblwilayahkabupaten KAB ON PRO.field_provinsi_id=KAB.field_provinsi_id
	JOIN tblwilayahkecamatan KEC ON KAB.field_kabupaten_id=KEC.field_kabupaten_id
	JOIN tblwilayahdesa KEL ON KEC.field_kecamatan_id=KEL.field_kecamatan_id
  WHERE PRO.field_provinsi_id=:provinsi AND KAB.field_kabupaten_id=:kebupaten AND KEC.field_kecamatan_id=:kecamatan AND KEL.field_desa_id=:kelurahan");
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
                            Nik_Nasabah=:nik,
                            Jenis_Kelamin_N=:gender,
                            Alamat_Nasabah=:alamat,
                            Provinsi_N=:provinsi,
                            Kabupaten_N=:kabupaten,
                            Kecamatan_N=:kecamatan,
                            Kelurahan_N=:kelurahan,
                            Agama_N=:agama,
                            Status_N=:statuse     
                                WHERE id_UserLogin=:id');
  $update_nasabah->bindParam(':id', $id);
  $update_nasabah->bindParam(':nik', $nik);
  $update_nasabah->bindParam(':gender', $gender);
  $update_nasabah->bindParam(':alamat', $alamat);
  $update_nasabah->bindParam(':provinsi', $provinsi);
  $update_nasabah->bindParam(':kabupaten', $kabupaten);
  $update_nasabah->bindParam(':kecamatan', $kecamatan);
  $update_nasabah->bindParam(':kelurahan', $kelurahan);
  $update_nasabah->bindParam(':agama', $agama);
  $update_nasabah->bindParam(':statuse', $status);


  if ($update_nasabah->execute()) {
    $Msg = "Update Successfully"; //execute query success message
    echo '<META HTTP-EQUIV="Refresh" Content="1;">';
  }



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
      // $select_stmt = $db->prepare("SELECT field_email,Field_handphone  FROM tbluserlogin 
      // 							WHERE field_email=:uemail OR Field_handphone=:only"); // sql select query			
      // $select_stmt->execute(array(
      //   ':uemail'  => $email,
      //   ':only'    => $angka
      // )); //execute query 
      // $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

      // if ($row["field_email"] !== $email) {
      //   $errorMsg = "Maaf Email Sudah Ada";  //check condition email already exists 
      // } else if ($row["Field_handphone"] !== $angka) {
      //   $errorMsg = "Maaf Nomor Hp Sudah Ada";  //check condition email already exists 
      // } elseif (!isset($errorMsg)) {

      $update_nasabah = $db->prepare('UPDATE tblnasabah SET 
                                                Nik_Nasabah=:nik,
                                                Jenis_Kelamin_N=:gender,
                                                Alamat_Nasabah=:alamat,
                                                Provinsi_N=:provinsi,
                                                Kabupaten_N=:kabupaten,
                                                Kecamatan_N=:kecamatan,
                                                Kelurahan_N=:kelurahan,
                                                Agama_N=:agama,
                                                Status_N=:status      
                                       WHERE id_UserLogin=:id');
      $update_nasabah->bindParam(':id', $id);
      $update_nasabah->bindParam(':nik', $nik);
      $update_nasabah->bindParam(':gender', $gender);
      $update_nasabah->bindParam(':alamat', $alamat);
      $update_nasabah->bindParam(':provinsi', $provinsi);
      $update_nasabah->bindParam(':kabupaten', $kabupaten);
      $update_nasabah->bindParam(':kecamatan', $kecamatan);
      $update_nasabah->bindParam(':kelurahan', $kelurahan);
      $update_nasabah->bindParam(':agama', $agama);
      $update_nasabah->bindParam(':status', $status);


      if ($update_nasabah->execute()) {
        $Msg = "Update Successfully"; //execute query success message
        echo '<META HTTP-EQUIV="Refresh" Content="1;">';
      }
      // }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}


// if (isset($_REQUEST['btn-ektp'])) {
//   # code...
//   $username = "admin";
//   $password = "M4Potl0ZZCET2I5AsGrt6w==";
//   $CURLOPT_URL = "172.24.33.162:8089/gmkservice/ktpreader/services/bacaChip";
//   $curl = curl_init();
//   curl_setopt_array($curl, array(
//     CURLOPT_URL => "$CURLOPT_URL",
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => "",
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 30,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => "POST",
//     CURLOPT_POSTFIELDS => "username=$username&password=$password&format=json",
//     CURLOPT_HTTPHEADER => array(
//       "content-type: application/x-www-form-urlencoded"
//     )
//   ));
//   $response = curl_exec($curl);
//   $err = curl_error($curl);
//   curl_close($curl);

//   if ($err) {
//     echo "cURL Error #:" . $err;
//   } else {

//     $data = json_decode($response, true);
//   }
// } //end if btn-ektp

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
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">Id User</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" value="<?php echo $DataUsers["field_user_id"]; ?>" readonly>
                  </div>
                </div>
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
                      <option value="ISLAM">Islam</option>
                      <option value="PROTESTAN">Protestan</option>
                      <option value="KATOLIK">Katolik</option>
                      <option value="HINDU">Hindu</option>
                      <option value="BUDDHA">Buddha</option>
                      <option value="KHONGHUCU">Khonghucu</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputSkills" class="col-sm-2 control-label">Status Perkawinan</label>
                  <div class="col-sm-6">
                    <select class="form-control" type="text" name="txt_status" id="status">
                      <option value="<?php echo $DataUsers['Status_N']; ?>"><?php echo $DataUsers['Status_N']; ?></option>
                      <option value="KAWIN">Kawin</option>
                      <option value="BELUM KAWIN Kawin">Belum Kawin</option>
                      <option value="DUDA">Duda</option>
                      <option value="JANDA">Janda</option>

                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="btn_update" class="btn btn-success">Perbarui</button>
                    <!-- <input type="submit" name="btn_update" class="btn btn-success " value="Perbarui"> -->
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