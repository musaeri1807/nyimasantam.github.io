<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if (!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

if (isset($_REQUEST['btn_insert2'])) {
  $category = $_REQUEST['txt_category'];
  $typetrash = $_REQUEST['txt_group_category'];

  if (empty($category)) {
    $errorMsg = "Silakan Masukkan Category";
  } elseif ($typetrash == "Pilih") {
    $errorMsg = "Silakan Masukkan Type";
  } else {
    try {
      if (!isset($errorMsg)) {
        $insert_stmt = $db->prepare('INSERT INTO tblcategory (field_name_category,field_type_product) 
														VALUES(:category,:typetrash)'); //sql insert query					
        $insert_stmt->bindParam(':category', $category);
        $insert_stmt->bindParam(':typetrash', $typetrash);
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
} elseif (isset($_REQUEST['id'])) {
  $id = $_REQUEST['id'];
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
}


$Sql = "SELECT * FROM tblmenu WHERE field_is_active='Y' ORDER BY field_idmenu ASC";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();
$no = 1;



// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  echo '<META HTTP-EQUIV="Refresh" Content="1">';
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  echo '<META HTTP-EQUIV="Refresh" Content="1">';
}
?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Menu Pengelolahan</h3>

          <a data-toggle="modal" data-target="#modal-default-category" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add</a>
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
                  <h4 class="modal-title">Add</h4>
                </center>
              </div>
              <div class="modal-body">
                <form method="post" class="form-horizontal">
                  <div class="form-group">
                    <div class="box-header">
                      <input type="text" name="txt_category" class="form-control" placeholder="Masukkan Nama Category" />
                    </div>
                    <div class="box-header">
                      <select class="form-control" name="txt_group_category">
                        <option>Pilih</option>
                        <option value="Anorganic">Anorganic</option>
                        <option value="Organic">Organic</option>
                        <option value="B3">B3</option>
                        <option value="Rupiah">Rupiah</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                    <input type="submit" name="btn_insert2" class="btn btn-success " value="Add">
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

                <th style="width:1%">#</th>
                <th style="width:20%">Menu</th>
                <th style="width:20%">Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              foreach ($result as $row) {
                $no++
              ?>

                <tr>

                  <td><?php echo $no; ?></td>
                  <td data-title="Trx Id"><?php echo $row["field_menu"] ?></td>
                  <td>
                    <?php if ($row["field_is_active"] == "Y") {
                      echo "Aktif";
                    } else {
                      echo "Pasif";
                    } ?>
                  </td>
                  <td data-title="Action">
                    <?php
                    if ($rows["field_role"] == "ADM") {

                      echo '<a data-toggle="modal" data-target="#modal-update-category' . $row["field_idmenu"] . '" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a> &nbsp';
                      echo '<a data-toggle="modal" data-target="#modal-delete-category' . $row["field_idmenu"] . '" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                    } elseif ($rows["field_role"] == "MGR") {
                      echo '<a data-toggle="modal" data-target="#modal-update-category' . $row["field_idmenu"] . '" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a> &nbsp';
                    } elseif ($rows["field_role"] == "AMR") {
                      echo '<a data-toggle="modal" data-target="#modal-update-category' . $row["field_idmenu"] . '" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a> &nbsp';
                    } elseif ($rows["field_role"] == "SPV") {
                      echo '<a data-toggle="modal" data-target="#modal-update-category' . $row["field_idmenu"] . '" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a> &nbsp';
                    } elseif ($rows["field_role"] == "BCO") {
                      echo '<a data-toggle="modal" data-target="#modal-update-category' . $row["field_idmenu"] . '" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a> &nbsp';
                    }
                    ?>

                  </td>
                </tr>

                <!-- modal update-->
                <div class="modal fade" id="modal-update-category<?php echo $row["field_idmenu"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Menu <?php echo $row["field_idmenu"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <input type="hidden" name="txt_idcategory" value="<?php echo $row['field_idmenu'] ?>">
                              <input type="text" name="txt_category" class="form-control" value="<?php echo $row["field_menu"] ?>" />
                            </div>
                            <div class="box-header">
                              <select class="form-control" name="txt_group_category">
                                <option value="<?php echo $row["field_is_active"] ?>">
                                  <?php if ($row["field_is_active"] == "Y") {
                                    echo "Aktif";
                                  } else {
                                    echo "Pasif";
                                  } ?>
                                </option>
                                <option value="Y">Aktif</option>
                                <option value="N">Pasif</option>

                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                            <input type="submit" name="btn_update" class="btn btn-success " value="Update">
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
                <div class="modal fade" id="modal-delete-category<?php echo $row["field_category_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Yakin Delete Data</h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                              <center>
                                <h4>
                                  <?php
                                  echo "Nama product " . $row["field_category_id"] . " Dengan " . rupiah($row["field_category_id"]);
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                            <!-- <input type="submit"  name="btn_delete" class="btn btn-success " value="YES"> -->
                            <a href="?module=category&id=<?php echo $row['field_category_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
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
                <th>Name</th>
                <th>Status</th>
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