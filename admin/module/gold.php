<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}




// var_dump($_SESSION['userlogin']);
// die();

if (isset($_REQUEST['btn_insert2'])) {
  $branchid   = $rows['field_branch'];
  $hargajual  = $_REQUEST['txt_hargajual'];
  $hargabeli  = $_REQUEST['txt_hargabeli'];
  $date       = date('Y-m-d');
  $datetime   = date('Y-m-d H:i:s');
  $users      = $rows['field_user_id'];

  if (empty($hargajual)) {
    $errorMsg = "Silakan Masukkan Harga";
  } else if (empty($hargabeli)) {
    $errorMsg = "Silakan Masukkan Harga";
  } elseif (strlen(is_numeric($hargajual)) == 0) {
    $errorMsg = "Silakan Masukkan Harga Benar";
  } elseif (strlen(is_numeric($hargabeli)) == 0) {
    $errorMsg = "Silakan Masukkan Harga Benar";
  } else {
    try {
      if (!isset($errorMsg)) {
        $insert_stmt = $db->prepare('INSERT INTO tblgoldprice (field_branch,field_sell,field_buyback,field_datetime_gold,field_date_gold,field_officer_id)VALUES(:ubranch,:uhargajual,:uhargabeli,:udatetime,:udate,:users)'); //sql insert query				
        $insert_stmt->bindParam(':ubranch', $branchid);
        $insert_stmt->bindParam(':uhargajual', $hargajual);
        $insert_stmt->bindParam(':uhargabeli', $hargabeli);
        $insert_stmt->bindParam(':udate', $date);
        $insert_stmt->bindParam(':udatetime', $datetime);
        $insert_stmt->bindParam(':users', $users);

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
  $branchid   = $rows['field_branch'];
  $hargajual  = $_REQUEST['txt_hargajual'];
  $hargabeli  = $_REQUEST['txt_hargabeli'];
  $date       = date('Y-m-d');
  $datetime   = date('Y-m-d H:i:s');
  $users      = $rows['field_user_id'];
  $idgold     = $_REQUEST['txt_idgold'];
  $status     = "P";
  $approval   = 0;

  if (empty($hargajual)) {
    $errorMsg = "Silakan Masukkan Harga";
  } else if (empty($hargabeli)) {
    $errorMsg = "Silakan Masukkan Harga";
  } elseif (strlen(is_numeric($hargajual)) == 0) {
    $errorMsg = "Silakan Masukkan Harga Benar";
  } elseif (strlen(is_numeric($hargabeli)) == 0) {
    $errorMsg = "Silakan Masukkan Harga Benar";
  } else {
    try {
      if (!isset($errorMsg)) {
        $update_stmt = $db->prepare('UPDATE tblgoldprice SET field_branch=:ubranch,field_sell=:sell,field_buyback=:buyback,field_officer_id=:users,field_status=:P,field_approve=:0 WHERE field_gold_id=:idgold'); //sql insert query					
        $update_stmt->bindParam(':ubranch', $branchid);
        $update_stmt->bindParam(':idgold', $idgold);
        $update_stmt->bindParam(':sell', $hargajual);
        $update_stmt->bindParam(':buyback', $hargabeli);
        // $update_stmt->bindParam(':udate', $date);
        // $update_stmt->bindParam(':udatetime', $datetime);
        $update_stmt->bindParam(':users', $users);
        $update_stmt->bindParam(':P', $status);
        $update_stmt->bindParam(':0', $approval);


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
    $errorMsg = "Silakan Masukkan id";
  } elseif ($typeaprove == "Pilih") {
    $errorMsg = "Silakan Masukkan Type";
  } else {
    try {
      if (!isset($errorMsg)) {
        $update_stmt = $db->prepare('UPDATE tblgoldprice SET field_status=:typeaprove,field_note=:note,field_approve=:idaprovel WHERE field_gold_id=:idprice'); //sql insert query					
        $update_stmt->bindParam(':idprice', $idprice);
        $update_stmt->bindParam(':note', $note);
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
} elseif (isset($_REQUEST['id'])) {

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
} elseif (isset($_REQUEST['goldid'])) {

  $idgold = $_REQUEST['goldid'];
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
} elseif (isset($_REQUEST['idgold'])) {

  $idgold = $_REQUEST['idgold'];
  $typeaprove = "R";
  $id = $rows['field_user_id'];;
  // echo $id;
  // die();
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
}


// ..................................................................................................................
$Sql = "SELECT G.*,B.field_branch_id,B.field_branch_name,E.field_name_officer,E2.field_name_officer AS Aproval
        FROM tblgoldprice G LEFT JOIN tblbranch B ON G.field_branch=B.field_branch_id 
        LEFT JOIN tblemployeeslogin E ON G.field_officer_id=E.field_user_id
        LEFT JOIN tblemployeeslogin E2 ON G.field_approve=E2.field_user_id
        ORDER BY field_gold_id DESC";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();

$SqlEmas = "SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1";
$StmtEmas = $db->prepare($SqlEmas);
$StmtEmas->execute();
$ResultEmas = $StmtEmas->fetch(PDO::FETCH_ASSOC);
$SqlEmas2 = "SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1,1";
$StmtEmas2 = $db->prepare($SqlEmas2);
$StmtEmas2->execute();
$ResultEmas2 = $StmtEmas2->fetch(PDO::FETCH_ASSOC);



$HargaTerkini = $ResultEmas['field_sell'];
$HargaKemarin = $ResultEmas2['field_sell'];
$Selisi      = $HargaTerkini - $HargaKemarin;
$trxid       = $ResultEmas['field_gold_id'];
$abs         = abs($Selisi);

if ($Selisi > 1) {
  $update_stmt = $db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
  // execute the query
  $update_stmt->execute(array(':trxid' => $trxid, ':fluktuasi' => $abs, ':rasio' => "Naik"));
} else {
  $update_stmt = $db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
  // execute the query
  $update_stmt->execute(array(':trxid' => $trxid, ':fluktuasi' => $abs, ':rasio' => "Turun"));
}


// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=gold">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=gold">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=gold">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=gold">';
  }
}

?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Gold Price</h3>
          <?php
          if ($rows['add'] == 'Y') {
            echo '<a data-toggle="modal" data-target="#modal-default-category" class="btn btn-success  pull-right"><i class="fa fa-plus"></i>&nbsp Add &nbsp</a>';
          }
          ?>
        </div>
        <!-- Content -->
        <!-- modal add -->
        <div class="modal fade" id="modal-default-category">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <center>
                  <h4 class="modal-title">Add Harga Emas</h4>
                </center>
              </div>
              <div class="modal-body">
                <form method="post" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal =</label>
                    <div class="col-sm-5">
                      <input type="text" name="txt_tanggal" class="form-control" value="<?php echo date("d F Y"); ?>" readonly />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Harga Jual =</label>
                    <div class="col-sm-7">
                      <input type="text" name="txt_hargajual" class="form-control" placeholder="Masukkan Harga Jual" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Harga Beli Buyback =</label>
                    <div class="col-sm-7">
                      <input type="text" name="txt_hargabeli" class="form-control" placeholder="Masukkan Harga Buyback" />
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
        <!-- /Content -->
        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="text-align:center ;">Action</th>
                <th style="text-align:center ;">Ratio</th>
                <th style="text-align:center ;">Gold</th>
                <th style="text-align:center ;">Mengajukan</th>
                <th style="text-align:center ;">Amount Price</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">disetujui</th>
                <th style="text-align:center ;">Submitter</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach (array_slice($result, 0, 2) as $row) {
              ?>

                <tr>
                  <td style="text-align:center ;">
                    <?php
                    // echo $no++;
                    if ($rows["field_role"] == "ADM") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-approvel-price' . $row["field_gold_id"] . '"class="btn btn-warning btn-sm"><i class="fa fa-check-circle "></i></a> &nbsp';
                    }
                    // ..............
                    if ($rows['edit'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-update-price' . $row["field_gold_id"] . '"class="btn btn-success btn-sm"><i class="fa fa-refresh"></i></a> &nbsp';
                    }
                    if ($rows['view'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-view-price' . $row["field_gold_id"] . '"class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> &nbsp';
                    }
                    if ($rows['delete'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-delete-category' . $row["field_gold_id"] . '"class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> &nbsp';
                    }
                    ?>
                  </td>

                  <td>
                    <?php

                    if ($row["field_rasio"] == "Naik") {
                      echo '<div class="glyphicon glyphicon-arrow-up btn-success btn-sm"> </div>';
                    } elseif ($row["field_rasio"] == "Sama") {
                      echo '<div class="glyphicon glyphicon-align-justify btn-warning btn-sm"> </div>';
                    } elseif ($row["field_rasio"] == "Turun") {
                      echo '<div class="glyphicon glyphicon-arrow-down btn-danger btn-sm"> </div>';
                    }
                    echo '<strong> ' . rupiah($row["field_fluktuasi"]) . '<strong>';
                    ?>
                  </td>
                  <td data-title="Trx Id"><small>Harga Update <?php echo date("d M Y /H:i:s", strtotime($row["field_datetime_gold"])); ?></small><br><strong><?php echo $row["field_name"]; ?></strong></td>
                  <!-- <td data-title="Branchid"><small><?php echo $row["field_branch"]; ?></small><br><strong><?php echo $row["field_officer_id"]; ?></strong></td> -->
                  <td data-title="Trx Id"><small><?php echo $row["field_branch_name"]; ?></small><br><strong><?php echo $row["field_name_officer"]; ?></strong></td>
                  <td data-title="Trx Id">Jual <strong><?php echo rupiah($row["field_sell"]); ?></strong><br>Beli <strong><?php echo rupiah($row["field_buyback"]); ?></strong></td>
                  <td data-title="Trx Id"><strong>

                      <?php
                      if ($row["field_status"] == "A") {
                        echo '<span class="badge btn-success text-white">Disetujui</span>';
                      } elseif ($row["field_status"] == "C") {
                        echo '<span class="badge btn-info text-white">Cancel</span>';
                      } elseif ($row["field_status"] == "P") {
                        echo '<span class="badge btn-warning text-white">Menunggu</span>';
                      } elseif ($row["field_status"] == "R") {
                        echo '<span class="badge btn-danger text-white">Batal</span>';
                      }
                      ?>
                  </td>
                  <td data-title="Trx Id"><strong><?php echo $row["Aproval"]; ?></td>

                  <td>
                    <?php


                    if ($row['field_status'] == "P") {
                      if ($rows['approval'] == "Y") {
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-aproval' . $row["field_gold_id"] . '" class="btn btn-warning btn-sm"><i class="fa fa-check-square"></i> Setuju </a> &nbsp';
                      }
                      if ($rows['reject'] == "Y") {
                        echo '<a href="#" data-toggle="modal" data-target="#modal-default-reject' . $row["field_gold_id"] . '"class="btn btn-danger btn-sm"><i class="fa fa-window-close"></i> Batal </a> &nbsp';
                      }
                    } else {
                      echo '<span class="badge btn-info text-white">Complete</span>';
                    }

                    ?>
                  </td>
                </tr>

                <!-- modal update-->
                <div class="modal fade" id="modal-update-price<?php echo $row["field_gold_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Price <?php echo $row["field_gold_id"]; ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <label class="col-sm-4 control-label">Tanggal =</label>
                              <div class="col-sm-5">
                                <input type="hidden" name="txt_idgold" value="<?php echo $row['field_gold_id'] ?>">
                                <input type="text" name="txt_tanggal" class="form-control" value="<?php echo date("d F Y"); ?>" readonly />
                              </div>
                            </div>
                            <div class="box-header">
                              <label class="col-sm-4 control-label">Harga Jual =</label>
                              <div class="col-sm-5">
                                <input type="text" name="txt_hargajual" class="form-control" value="<?php echo $row["field_sell"]; ?>" />
                              </div>
                            </div>
                            <div class="box-header">
                              <label class="col-sm-4 control-label">Harga Beli Buyback =</label>
                              <div class="col-sm-5">
                                <input type="text" name="txt_hargabeli" class="form-control" value="<?php echo $row["field_buyback"]; ?>" />
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
                <div class="modal fade" id="modal-view-price<?php echo $row["field_gold_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">View Harga Emas <?php echo $row["field_gold_id"]; ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <label class="col-sm-4 control-label">Tanggal =</label>
                              <div class="col-sm-5">
                                <input type="hidden" name="txt_idgold" value="<?php echo $row['field_gold_id'] ?>">
                                <input type="text" name="txt_tanggal" class="form-control" value="<?php echo date("d F Y"); ?>" readonly />
                              </div>
                            </div>
                            <div class="box-header">
                              <label class="col-sm-4 control-label">Harga Jual =</label>
                              <div class="col-sm-5">
                                <input type="text" name="txt_hargajual" class="form-control" value="<?php echo rupiah($row["field_sell"]); ?>" readonly />
                              </div>
                            </div>
                            <div class="box-header">
                              <label class="col-sm-4 control-label">Harga Beli Buyback =</label>
                              <div class="col-sm-5">
                                <input type="text" name="txt_hargabeli" class="form-control" value="<?php echo rupiah($row["field_buyback"]); ?>" readonly />
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

                <!-- modal update approvel-->
                <div class="modal fade" id="modal-approvel-price<?php echo $row["field_gold_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Approve ID <?php echo $row["field_gold_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <input type="hidden" name="txt_idprice" value="<?php echo $row['field_gold_id'] ?>">

                              <textarea class="form-control" name="txt_note" id="textarea" rows="3"></textarea>
                            </div>
                            <div class="box-header">
                              <select class="form-control" name="txt_aprovel">
                                <option value="Pilih">Pilih</option>
                                <?php
                                foreach ($RESULT as $STATUS) {

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

                <!-- modal delete-->
                <div class="modal fade" id="modal-delete-category<?php echo $row["field_gold_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title"></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <center>
                                <h4>
                                  <?php
                                  echo "Yakin Hapus Harga Emas Ini " . rupiah($row["field_sell"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_delete" class="btn btn-success " value="YES"> -->
                            <a href="?module=gold&id=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-danger">&nbsp&nbsp Iya &nbsp&nbsp</a>
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
                <div class="modal fade" id="modal-default-aproval<?php echo $row["field_gold_id"]; ?>">
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
                                  echo 'Harga Jual ' . rupiah($row["field_buyback"]) . '<br>' . 'Harga Beli ' . rupiah($row["field_sell"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href=" ?module=gold&goldid=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp&nbsp Iya &nbsp&nbsp</a>
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
                <div class="modal fade" id="modal-default-reject<?php echo $row["field_gold_id"]; ?>">
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
                                  echo 'Harga Jual ' . rupiah($row["field_buyback"]) . '<br>' . 'Harga Beli ' . rupiah($row["field_sell"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=gold&idgold=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp&nbsp Iya &nbsp&nbsp</a>
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
                <th style="text-align:center ;">Action</th>
                <th style="text-align:center ;">Ratio</th>
                <th style="text-align:center ;">Gold</th>
                <th style="text-align:center ;">Mengajukan</th>
                <th style="text-align:center ;">Amount Price</th>
                <th style="text-align:center ;">Status</th>
                <th style="text-align:center ;">disetujui</th>
                <th style="text-align:center ;">Submitter</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- Content -->
      </div>
    </div>
  </div>
</section>