<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

if (isset($_REQUEST['btn_insert2'])) {
  $kodeproduk  = $_REQUEST['txt_kodeproduk'];  //textbox name "txt_firstname"
  $namaproduk  = $_REQUEST['txt_nama'];  //textbox name "txt_lastname"
  $hargaproduk = $_REQUEST['txt_harga'];
  $beratsampah = $_REQUEST['txt_berat'];
  $datetime    = date('Y-m-d H:i:s');
  $date      = date('Y-m-d');
  $kategori  = $_REQUEST['txt_kategori'];
  $cabang    = $_REQUEST['txt_cabang'];
  $Keterangan = $_REQUEST['txt_keterangan'];
  $officer   = $_SESSION['idlogin'];


  // echo $cabang;
  // echo "<br>";
  // echo $kategori;
  // echo "<br>";
  // echo $beratsampah;
  // die();

  if (empty($namaproduk)) {
    $errorMsg = "Silakan Masukkan Nama Produk Sampah";
  } else if (empty($hargaproduk)) {
    $errorMsg = "Silakan Masukkan Harga Sampah";
  } elseif (strlen(is_numeric($hargaproduk)) == 0) {
    $errorMsg = "Silakan Masukkan Harga Benar";
  } elseif ($beratsampah == "Pilih") {
    $errorMsg = "Silakan Pilih Jenis Berat Sampah";
  } elseif ($kategori == "Pilih") {
    $errorMsg = "Silakan Pilih Kategori Sampah";
  } elseif ($cabang == "Pilih") {
    $errorMsg = "Silakan Pilih Kantor Cabang";
  } elseif (empty($Keterangan)) {
    $errorMsg = "Silakan Masukan Keterangan Sampah";
  } else {
    try {
      if (!isset($errorMsg)) {
        $insert_stmt = $db->prepare('INSERT INTO tblproduct 
													(field_product_code,
													 field_product_name,
													 field_unit,
													 field_date_price,
													 field_date,
													 field_category,
													 field_branch,
													 field_price,
													 field_note,
													 field_officer_id) 
											VALUES  (:kodeproduk,
													 :namaproduk,
													 :beratsampah,
													 :udatetime,
													 :udate,
													 :kategori,
													 :cabang,
													 :hargaproduk,
													 :Keterangan,
													 :petugas)');

        $insert_stmt->bindParam(':kodeproduk', $kodeproduk);
        $insert_stmt->bindParam(':namaproduk', $namaproduk);
        $insert_stmt->bindParam(':beratsampah', $beratsampah);
        $insert_stmt->bindParam(':udatetime', $datetime);
        $insert_stmt->bindParam(':udate', $date);
        $insert_stmt->bindParam(':kategori', $kategori);
        $insert_stmt->bindParam(':cabang', $cabang);
        $insert_stmt->bindParam(':hargaproduk', $hargaproduk);
        $insert_stmt->bindParam(':Keterangan', $Keterangan);
        $insert_stmt->bindParam(':petugas', $officer);

        if ($insert_stmt->execute()) {
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['btn_update'])) {
  $idcategory = $_REQUEST['txt_idcategory'];
  $category = $_REQUEST['txt_category'];
  $typetrash = $_REQUEST['txt_group_category'];

  if (empty($category)) {
    $errorMsg = "Silakan Masukkan Category";
  } elseif ($typetrash == "Pilih") {
    $errorMsg = "Silakan Masukkan Type";
  } else {
    try {
      if (!isset($errorMsg)) {
        $update_stmt = $db->prepare('UPDATE tblcategory SET field_name_category=:category,field_type_product=:typetrash WHERE field_category_id=:idcategory'); //sql insert query					
        $update_stmt->bindParam(':idcategory', $idcategory);
        $update_stmt->bindParam(':category', $category);
        $update_stmt->bindParam(':typetrash', $typetrash);


        if ($update_stmt->execute()) {
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['btn_aprovel'])) {
  $idprice = $_REQUEST['txt_idprice'];
  $note = $_REQUEST['txt_note'];
  $typeaprove = $_REQUEST['txt_aprovel'];
  $id = $rows['field_user_id'];

  if (empty($idprice)) {
    $errorMsg = "Silakan Masukkan Id";
  } elseif ($typeaprove == "Pilih") {
    $errorMsg = "Silakan Masukkan Type";
  } else {
    try {
      if (!isset($errorMsg)) {
        $update_stmt = $db->prepare('UPDATE tblproduct SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_product_id=:idprice'); //sql insert query					
        $update_stmt->bindParam(':idprice', $idprice);

        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);

        if ($update_stmt->execute()) {
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['productid'])) {

  $idproduct = $_REQUEST['productid'];
  $typeaprove = "A";
  $id = $rows['field_user_id'];

  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {

        $update_stmt = $db->prepare('UPDATE tblproduct SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_product_id=:id'); //sql insert query					

        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);
        $update_stmt->bindParam(':id', $idproduct);

        if ($update_stmt->execute()) {
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
          if ($_SERVER['SERVER_NAME'] == 'localhost') {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=product">';
          } else {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=product">';
          }
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['idproduct'])) {

  $idproduct = $_REQUEST['idproduct'];
  $typeaprove = "R";
  $id = $rows['field_user_id'];

  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {

        $update_stmt = $db->prepare('UPDATE tblproduct SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_product_id=:id'); //sql insert query		
        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);
        $update_stmt->bindParam(':id', $idproduct);

        if ($update_stmt->execute()) {
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
          if ($_SERVER['SERVER_NAME'] == 'localhost') {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=product">';
          } else {
            echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=product">';
          }
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}


if (isset($_REQUEST['id'])) {
  $idp = $_REQUEST['id'];

  $select_stmt = $db->prepare('SELECT * FROM tblproduct WHERE field_product_id =:id'); //sql select query
  $select_stmt->bindParam(':id', $idp);
  $select_stmt->execute();
  $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

  // echo $row['tblproduct_kode'];
  $delete_stmt = $db->prepare('DELETE FROM tblproduct WHERE field_product_id =:id');
  $delete_stmt->bindParam(':id', $idp);
  $delete_stmt->execute();
}


if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  // $Sql = "SELECT * FROM tblproduct";
  $Sql = "SELECT P.*,E.field_name_officer,C.field_name_category,B.field_branch_name AS Cabang,E2.field_name_officer AS Aproval 
  FROM tblproduct P 
  LEFT JOIN tblcategory C ON P.field_category=C.field_category_id 
  LEFT JOIN tblbranch B ON P.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON P.field_officer_id=E.field_user_id 
  LEFT JOIN tblemployeeslogin E2 ON P.field_approve=E2.field_user_id
  ORDER BY P.field_product_id DESC";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute();
  $PS = $Stmt->fetchAll();
} else {
  $Sql = "SELECT P.*,E.field_name_officer,C.field_name_category,B.field_branch_name AS Cabang,E2.field_name_officer AS Aproval 
  FROM tblproduct P 
  LEFT JOIN tblcategory C ON P.field_category=C.field_category_id 
  LEFT JOIN tblbranch B ON P.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON P.field_officer_id=E.field_user_id 
  LEFT JOIN tblemployeeslogin E2 ON P.field_approve=E2.field_user_id 
  WHERE P.field_branch=:idbranch 
  ORDER BY P.field_product_id DESC";
  $Stmt = $db->prepare($Sql);
  $Stmt->execute(array(":idbranch" => $branchid));
  $PS = $Stmt->fetchAll();
}

// mencari kode barang dengan nilai paling besar
$sql  = "SELECT max(field_product_code) AS maxKode FROM tblproduct";
$stmt   = $db->prepare($sql);
$stmt->execute();
$kode    = $stmt->fetch(PDO::FETCH_ASSOC);
$kodeProduk = $kode['maxKode'];
// mengambil angka atau bilangan dalam kode produk terbesar,
// dengan cara mengambil substring mulai dari karakter ke-1 diambil 6 karakter
// misal 'BRG001', akan diambil '001'
// setelah substring bilangan diambil lantas dicasting menjadi integer
$noUrut = substr($kodeProduk, 4, 3);
// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$noUrut++;
// membentuk kode produk baru
// perintah sprintf("%03s", $noUrut); digunakan untuk memformat string sebanyak 3 karakter
// misal sprintf("%03s", 12); maka akan dihasilkan '012'
// atau misal sprintf("%03s", 1); maka akan dihasilkan string '001'
$char = "PROD";
$kodeProduk = $char . sprintf("%03s", $noUrut);


$Sql_kategori = "SELECT * FROM tblcategory";
$Stmt_kategori = $db->prepare($Sql_kategori);
$Stmt_kategori->execute();
$resultKategori = $Stmt_kategori->fetchAll();

if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  $Sql_cabang = "SELECT * FROM tblbranch";
  $Stmt_cabang = $db->prepare($Sql_cabang);
  $Stmt_cabang->execute();
  $KC = $Stmt_cabang->fetchAll();
} else {
  $Sql_cabang = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
  $Stmt_cabang = $db->prepare($Sql_cabang);
  $Stmt_cabang->execute(array(":idbranch" => $branchid));
  $KC = $Stmt_cabang->fetchAll();
}

// var_dump($KC);
// die();


$no = 1;

// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=product">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=product">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=product">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=product">';
  }
}

?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Product Price</h3>

          <?php

          if ($rows['add'] == 'Y') {
            echo '<a data-toggle="modal" data-target="#modal-default-addproduct" class="btn btn-success  pull-right"><i class="fa fa-plus"></i>&nbsp Add &nbsp</a>';
          }
          ?>
          <!-- if ($rows['add'] == 'Y') {
          echo '<a href="?module=addproduct" class="btn btn-success  pull-right"><i class="fa fa-plus"></i>&nbsp Add &nbsp</a>';
          }
          ?> -->
        </div>
        <!-- Content -->
        <!-- modal add -->
        <div class="modal fade" id="modal-default-addproduct">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <center>
                  <h4 class="modal-title">Add Harga Sampah</h4>
                </center>
              </div>
              <div class="modal-body">
                <form method="post" class="form-horizontal">

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Kode</label>
                    <div class="col-sm-3">
                      <input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo $kodeProduk; ?>" readonly />
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Name Sampah</label>
                    <div class="col-sm-6">
                      <input type="text" name="txt_nama" class="form-control" placeholder="Masukkan Nama Produk" />
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Harga</label>
                    <div class="row">
                      <div class="col-sm-3">
                        <input type="text" name="txt_harga" class="form-control" placeholder="Masukkan Harga" />
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control" name="txt_berat">
                          <option>Pilih</option>
                          <option value="Kg">Kg</option>
                          <option value="Rp">Rp</option>
                          <option value="Pcs">Pcs</option>
                          <option value="Liter">Liter</option>
                        </select>

                      </div>
                    </div>

                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Kategori</label>
                    <div class="col-sm-4">
                      <select class="form-control" type="text" name="txt_kategori">
                        <option>Pilih</option>
                        <?php foreach ($resultKategori as $KT) { ?>
                          <option value="<?php echo $KT['field_category_id']; ?>"><?php echo $KT['field_category_id'] . "-";
                                                                                  echo $KT['field_name_category']; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Catatan</label>
                    <div class="col-sm-6">
                      <textarea class="form-control" name="txt_keterangan" placeholder="Masukkan Keterangan Produk."></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Kantor Cabang</label>
                    <div class="col-sm-6">
                      <select class="form-control" type="text" name="txt_cabang">
                        <!-- <option>ksd</option> -->
                        <?php foreach ($KC as $branch) { ?>
                          <option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_name'] . "-";echo $branch['field_branch_id']; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger " data-dismiss="modal">Keluar</button>
                    <input type="submit" name="btn_insert2" class="btn btn-success " value="Simpan">
                  </div>
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>#</th>
                <th>Name Product</th>
                <th>Amount Price</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Submitter</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($PS as $S) {
              ?>

                <tr>
                  <td><?php echo $no++ ?></td>
                  <td>
                    <?php
                    if ($rows["field_role"] == "ADM") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-approvel-price' . $S["field_product_id"] . '"class="btn btn-warning btn-sm"><i class="fa fa-check-circle "></i></a> &nbsp';
                    }
                    // ..............
                    if ($rows['edit'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-default-updateproduct' . $S["field_product_id"] . '"class="btn btn-success btn-sm"><i class="fa fa-refresh"></i></a> &nbsp';
                    }
                    if ($rows['view'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-view-price' . $S["field_product_id"] . '"class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> &nbsp';
                    }
                    if ($rows['delete'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default' . $S["field_product_id"] . '" class="btn btn-danger btn-sm "><i class="fa fa-trash"></i></a> &nbsp';
                    }
                    ?>
                  </td>
                  <td data-title="Trx Id"><?php echo $S["field_name_category"]; ?>|<?php echo $S["field_product_code"]; ?><br><strong><?php echo $S["field_product_name"]; ?></strong></td>
                  <td data-title="Trx Id">/<?php echo $S["field_unit"]; ?><br><strong><?php echo rupiah($S["field_price"]); ?></strong> <br><small> Harga Update <?php echo date("d F Y", strtotime($S["field_date_price"]));  ?></small></td>
                  <td><?php echo $S["Cabang"]; ?><br> <strong><?php echo $S["field_name_officer"]; ?></strong></td>
                  <td data-title="Trx Id"><strong>
                      <?php

                      if ($S["field_status"] == "A") {
                        echo '<span class="badge btn-success text-white">Disetujui</span>';
                      } elseif ($S["field_status"] == "C") {
                        echo '<span class="badge btn-info text-white">Cancel</span>';
                      } elseif ($S["field_status"] == "P") {
                        echo '<span class="badge btn-warning text-white">Menunggu</span>';
                      } elseif ($S["field_status"] == "R") {
                        echo '<span class="badge btn-danger text-white">Batal</span>';
                      }
                      ?>
                  </td>
                  <td><strong><?php echo $S["Aproval"]; ?></td>
                  <td ata-title="Trx Id">

                    <?php
                    if ($S['field_status'] == "P") {
                      if ($rows['approval'] == "Y") {
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $S["field_product_id"] . '" class="btn btn-warning btn-sm"><i class="fa fa-check-square"></i> Setuju </a> &nbsp';
                      }
                      if ($rows['reject'] == "Y") {
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $S["field_product_id"] . '"class="btn btn-danger btn-sm"><i class="fa fa-window-close"></i> Batal </a> &nbsp';
                      }
                    } else {
                      echo '<span class="badge btn-info text-white">Complete</span>';
                    }
                    ?>

                  </td>
                </tr>

                <!-- modal update approvel-->
                <div class="modal fade" id="modal-approvel-price<?php echo $S["field_product_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Approve Id <?php echo $S["field_product_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <input type="hidden" name="txt_idprice" value="<?php echo $S['field_product_id'] ?>">

                              <textarea class="form-control" name="txt_note" id="textarea" rows="3"></textarea>
                            </div>
                            <div class="box-header">
                              <select class="form-control" name="txt_aprovel">
                                <option value="Pilih">Pilih</option>
                                <?php
                                foreach ($ST as $STATUS) {

                                  echo '<br>';
                                  echo $STATUS['field_status'];

                                ?>
                                  <option value="<?php echo $STATUS["field_cdstatus"] ?>"><?php echo $STATUS["field_status"] ?></option>
                                  <!-- <option value="A">A</option>
												<option value="R">R</option>
												<option value="C">C</option>
												<option value="P">P</option> -->
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                            <input type="submit" name="btn_aprovel" class="btn btn-success " value="OK">
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
                <div class="modal fade" id="modal-default-updateproduct<?php echo $S["field_product_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Data <?php echo $S["field_product_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Kode</label>
                                <div class="col-sm-3">
                                  <input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo $kodeProduk; ?>" readonly />
                                </div>
                              </div>

                            </div>
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Name Sampah</label>
                                <div class="col-sm-6">
                                  <input type="text" name="txt_nama" class="form-control" value="<?php echo $S["field_product_name"]; ?>" />
                                </div>
                              </div>

                            </div>

                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Harga</label>
                                <div class="row">
                                  <div class="col-sm-4">
                                    <input type="text" name="txt_harga" class="form-control" value="<?php echo $S["field_price"]; ?>"/>
                                  </div>
                                  <div class="col-sm-3">
                                    <select class="form-control" name="txt_berat">
                                      <option><?php echo $S["field_unit"]; ?></option>
                                      <option value="Kg">Kg</option>
                                      <option value="Rp">Rp</option>
                                      <option value="Pcs">Pcs</option>
                                      <option value="Liter">Liter</option>
                                    </select>
                                  </div>
                                </div>
                              </div>

                            </div>
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Kategori</label>
                                <div class="col-sm-4">
                                  <select class="form-control" type="text" name="txt_kategori">
                                    <!-- <option>Pilih</option> -->
                                    <option value="<?php echo $S['field_category']; ?>"><?php echo $S['field_category'] . "-";
                                                                                        echo $S['field_name_category']; ?></option>
                                    <?php foreach ($resultKategori as $KT) { ?>
                                      <option value="<?php echo $KT['field_category_id']; ?>"><?php echo $KT['field_category_id'] . "-";
                                                                                              echo $KT['field_name_category']; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>

                            </div>
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Catatan</label>
                                <div class="col-sm-6">
                                  <input type="text" class="form-control" name="txt_keterangan" value="<?php echo $S["field_note"]; ?>">
                                </div>
                              </div>

                            </div>
                            <div class="box-header">

                              <div class="form-group">
                                <label class="col-sm-3 control-label">Kantor Cabang</label>
                                <div class="col-sm-6">
                                  <select class="form-control" type="text" name="txt_cabang">
                                    <option value="<?php echo $S["field_branch"]; ?>"><?php echo $S["Cabang"] . "-";
                                                                                      echo $S["field_branch"]; ?></option>
                                    <?php foreach ($KC as $branch) { ?>
                                      <option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_name'] . "-";
                                                                                                echo $branch['field_branch_id']; ?></option>
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




                <!-- Modal Delete -->
                <div class="modal fade" id="modal-default<?php echo $S["field_product_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>

                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <center>
                                <h4>
                                  <?php
                                  echo "Yakin Hapus Harga Sampah " . $S["field_product_name"] . " " . rupiah($S["field_price"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=product&id=<?php echo $S['field_product_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp Iya &nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Modal Approval -->
                <div class="modal fade" id="modal-default-aproval<?php echo $S["field_product_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h3 class="modal-title">Anda Yakin Untuk Menyetujui</h3>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <center>
                                <h4>
                                  <?php
                                  echo "Sampah " . $S["field_product_name"] . " Dengan Harga " . rupiah($S["field_price"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=product&productid=<?php echo $S['field_product_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp Iya &nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Modal Reject -->
                <div class="modal fade" id="modal-default-reject<?php echo $S["field_product_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h3 class="modal-title">Anda Yakin Untuk Membatalkan</h3>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <center>
                                <h4>
                                  <?php
                                  echo "Name Product " . $S["field_product_name"] . " Dengan Harga " . rupiah($S["field_price"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=product&idproduct=<?php echo $S['field_product_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp Iya &nbsp</a>
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
                <th>#</th>
                <th>#</th>
                <th>Name Product</th>
                <th>Amount Price</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Approval</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- Content -->
      </div>
    </div>
  </div>
</section>