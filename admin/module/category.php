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


// if (isset($_REQUEST['id'])) {

//   $id=$_REQUEST['id'];    
//   $select_stmt= $db->prepare('SELECT * FROM tblcategory WHERE field_category_id =:id'); //sql select query
//   $select_stmt->bindParam(':id',$id);
//   $select_stmt->execute();
//   $row=$select_stmt->fetch(PDO::FETCH_ASSOC);

//   // echo $row['field_gold_id'];
//   $delete_stmt = $db->prepare('DELETE FROM tblcategory WHERE field_category_id =:id');
//   $delete_stmt->bindParam(':id',$id);
//   $delete_stmt->execute();

//   $Msg="Successfully";
//   echo '<div class="alert alert-success"><strong>SUCCESS !'.$Msg.'</strong></div>';
//   echo '<META HTTP-EQUIV="Refresh" Content="1">';

// }

$Sql = "SELECT * FROM tblcategory ORDER BY field_category_id DESC";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$kategori = $Stmt->fetchAll();
$no = 1;



// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=category">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=category">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=category">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=category">';
  }
}
?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Kategori</h3>
          <?php
          if ($rows['add'] == 'Y') {
            echo '<a data-toggle="modal" data-target="#modal-default-category" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add </a>';
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
                  <h4 class="modal-title">Add Kategori</h4>
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
                <th>#</th>
                <th>#</th>
                <th>Kode Kategori</th>
                <th>Kategori</th>
                <th>Group Kategori</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($kategori as $K) {
              ?>

                <tr>
                  <td data-title="Code C"><?php echo $no++; ?></td>
                  <td data-title="Code C">
                    <?php
                    if ($rows['edit'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-update-category' . $K["field_category_id"] . '"class="btn btn-success btn-sm"><i class="fa fa-refresh"></i></a> &nbsp';
                    }
                    if ($rows['view'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-view-price' . $K["field_category_id"] . '"class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> &nbsp';
                    }
                    if ($rows['delete'] == "Y") {
                      echo '<a data-toggle="modal" data-target="#modal-delete-category' . $K["field_category_id"] . '"class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> &nbsp';
                    }
                    ?>
                  </td>
                  <td data-title="Code C"><?php echo sprintf("%03s", $K['field_category_id']); ?></td>
                  <td data-title="Trx Id"><?php echo $K["field_name_category"] ?></td>
                  <td><?php echo $K["field_type_product"]; ?></td>
                  <td data-title="Action">
                    <?php
                    echo '<span class="badge btn-info text-white">Complete</span>';
                    ?>
                  </td>
                </tr>

                <!-- modal update-->
                <div class="modal fade" id="modal-update-category<?php echo $K["field_category_id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h4 class="modal-title">Update Kategori <?php echo $K["field_category_id"] ?></h4>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <input type="hidden" name="txt_idcategory" value="<?php echo $K['field_category_id'] ?>">
                              <input type="text" name="txt_category" class="form-control" value="<?php echo $K["field_name_category"] ?>" />
                            </div>
                            <div class="box-header">
                              <select class="form-control" name="txt_group_category">
                                <option value="<?php echo $K["field_type_product"] ?>"><?php echo $K["field_type_product"] ?></option>
                                <option value="Anorganic">Anorganic</option>
                                <option value="Organic">Organic</option>
                                <option value="B3">B3</option>
                                <option value="Rupiah">Rupiah</option>
                              </select>
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

                <!-- modal delete-->
                <div class="modal fade" id="modal-delete-category<?php echo $K["field_category_id"]; ?>">
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
                                  echo "Yakin Hapus Kategori " . $K["field_name_category"] . " Group " . $K["field_type_product"];

                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                            <a href="?module=category&id=<?php echo $K['field_category_id']; ?>" type="submit" class="text-white btn btn-success">&nbsp Iya &nbsp</a>
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
                <th>Kode Kategori</th>
                <th>Kategori</th>
                <th>Group Kategori</th>
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