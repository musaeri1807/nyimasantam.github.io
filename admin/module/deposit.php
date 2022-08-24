<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if (!isset($_SESSION['userlogin'])) {
  header("location: ../loginv2.php");
}

if (isset($_REQUEST['iddepositp'])) {

  $iddeposit = $_REQUEST['iddepositp'];
  $typeaprove = "P";
  $id = $rows['field_user_id'];

  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {

        $update_stmt = $db->prepare('UPDATE tbldeposit SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_trx_deposit=:id'); //sql insert query				
        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);
        $update_stmt->bindParam(':id', $iddeposit);
        if ($update_stmt->execute()) {
          $update_saldo = $db->prepare('UPDATE tbltrxmutasisaldo SET field_status=:typeaprove WHERE field_trx_id=:id'); //sql insert query				
          $update_saldo->bindParam(':typeaprove', $typeaprove);
          $update_saldo->bindParam(':id', $iddeposit);
          $update_saldo->execute();
          $Msg = "Successfully"; //execute query success message field_trx_id
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['iddepositc'])) {

  $iddeposit = $_REQUEST['iddepositc'];
  $typeaprove = "C";
  $id = $rows['field_user_id'];

  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {

        $update_stmt = $db->prepare('UPDATE tbldeposit SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_trx_deposit=:id'); //sql insert query					

        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);
        $update_stmt->bindParam(':id', $iddeposit);

        if ($update_stmt->execute()) {
          $update_saldo = $db->prepare('UPDATE tbltrxmutasisaldo SET field_status=:typeaprove WHERE field_trx_id=:id'); //sql insert query				
          $update_saldo->bindParam(':typeaprove', $typeaprove);
          $update_saldo->bindParam(':id', $iddeposit);
          $update_saldo->execute();
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
} elseif (isset($_REQUEST['iddeposits'])) {

  $iddeposit = $_REQUEST['iddeposits'];
  $typeaprove = "S";
  $id = $rows['field_user_id'];

  if (empty($id)) {
    $errorMsg = "Silakan Masukan Id ";
  } else {
    try {
      if (!isset($errorMsg)) {

        $update_stmt = $db->prepare('UPDATE tbldeposit SET field_status=:typeaprove,field_approve=:idaprovel WHERE field_trx_deposit=:id'); //sql insert query					

        $update_stmt->bindParam(':typeaprove', $typeaprove);
        $update_stmt->bindParam(':idaprovel', $id);
        $update_stmt->bindParam(':id', $iddeposit);

        if ($update_stmt->execute()) {
          $update_saldo = $db->prepare('UPDATE tbltrxmutasisaldo SET field_status=:typeaprove WHERE field_trx_id=:id'); //sql insert query				
          $update_saldo->bindParam(':typeaprove', $typeaprove);
          $update_saldo->bindParam(':id', $iddeposit);
          $update_saldo->execute();
          $Msg = "Successfully"; //execute query success message
          echo '<META HTTP-EQUIV="Refresh" Content="1">';
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}

if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
  $Sql = "SELECT I.*,C.field_nama_customer,E.field_name_officer,E2.field_name_officer AS Approval,field_branch_name,(SELECT G.field_sell FROM tblgoldprice G WHERE G.field_date_gold=I.field_date_deposit ORDER BY field_gold_id DESC LIMIT 1) AS PriceGold 
  FROM tbldeposit I 
  LEFT JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening
  LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
  LEFT JOIN tblemployeeslogin E2 ON I.field_approve=E2.field_user_id
  -- WHERE I.field_date_deposit=:datenow
  ORDER BY I.field_trx_deposit DESC";

  $Stmt = $db->prepare($Sql);
  // $Stmt->execute();
  $Stmt->execute(array(":datenow" => $date));
  $result = $Stmt->fetchAll();
} else {

  $Sql = "SELECT I.*,C.field_nama_customer,E.field_name_officer,E2.field_name_officer AS Approval,field_branch_name,(SELECT G.field_sell FROM tblgoldprice G WHERE G.field_date_gold=I.field_date_deposit ORDER BY field_gold_id DESC LIMIT 1) AS PriceGold 
  FROM tbldeposit I 
  LEFT JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening
  LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
  LEFT JOIN tblemployeeslogin E2 ON I.field_approve=E2.field_user_id
  WHERE I.field_branch=:idbranch AND I.field_date_deposit=:datenow
  ORDER BY I.field_trx_deposit DESC";

  $Stmt = $db->prepare($Sql);
  $Stmt->execute(array(
    ":idbranch" => $branchid,
    ":datenow" => $date
  ));
  $result = $Stmt->fetchAll();
}

$no = 1;


// massege
if (isset($errorMsg)) {
  echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=deposit">';
  }
}
if (isset($Msg)) {
  echo '<div class="alert alert-success"><strong>SUCCESS !' . $Msg . '</strong></div>';
  //echo '<META HTTP-EQUIV="Refresh" Content="1">';
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=deposit">';
  } else {
    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=deposit">';
  }
}

?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-edit"></i>
          <h3 class="box-title">Deposit Customer</h3>
          <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
          <?php
          if ($rows['add'] == 'Y') {
            echo '<a href="?module=adddeposit" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add </a>';
          }
          ?>

        </div>
        <!-- Content -->
        <div class="box-body">
          <table id="trxSemua" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>#</th>
                <th>Reff</th>
                <th>Account</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Sub Total</th>
                <th>Free</th>
                <th>Total</th>
                <th>Gold</th>
                <th>Officer</th>
                <th>Status</th>
                <th>Submitter</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach (array_slice($result, 0, 100) as $row) {
              ?>

                <tr>
                  <td>
                    <?php echo $no++; ?>
                  </td>

                  <td>
                    <?php
                    // echo $row['field_trx_deposit'];
                    if ($row['field_status'] == "S") {
                      echo '<a href="../depositcustomerpdf?d=' . $row["field_trx_deposit"] . ' "class="text-white btn btn-default"><i class="fa fa-print"></i></a> &nbsp <br>';
                    } elseif ($row['field_status'] == "C") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-view-cancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-info "><i class="fa fa-info"></i> </a> &nbsp';
                    } elseif ($row['field_status'] == "P") {
                    }

                    ?>


                  </td>
                  <td><strong><?php echo $row["field_no_referensi"]; ?></strong></td>
                  <td><?php echo $row["field_rekening_deposit"]; ?></td>
                  <td><?php echo $row["field_nama_customer"]; ?></td>
                  <td><?php echo date("d/m/Y", strtotime($row["field_date_deposit"])); ?></td>
                  <!-- <td><strong><?php echo rupiah($row["PriceGold"]); ?></strong></td> -->

                  <td><?php echo rupiah($row["field_sub_total"]); ?></td>
                  <td><?php echo rupiah($row["field_operation_fee_rp"]); ?></td>
                  <td><?php echo rupiah($row["field_total_deposit"]); ?></td>
                  <td><strong><?php echo $row["field_deposit_gold"]; ?></strong></td>
                  <td><?php echo $row["field_name_officer"]; ?></td>
                  <td>
                    <!-- <br><?php echo $row["field_branch_name"]; ?>  -->
                    <?php
                    // echo $row["Approval"];
                    if ($row['field_status'] == "P") {
                      echo '<span class="label pull-center bg-yellow"><strong>Menunggu</strong></span>';
                    } elseif ($row['field_status'] == "C") {
                      echo '<span class="label pull-center bg-red"><strong>&nbsp&nbsp Gagal &nbsp&nbsp</strong></span>';
                    } elseif ($row['field_status'] == "S") {
                      echo '<span class="label pull-center bg-green"><strong>Berhasil</strong></span>';
                    }
                    ?>
                  </td>
                  <td><strong><?php echo $row["Approval"]; ?></strong></td>

                  <td>


                    <?php
                    if ($row["field_status"] == "P") {
                    } else {
                    }

                    if ($rows['approval'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-aprovalcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-success "><i class="fa fa-window-close"></i> Setuju Cancel </a> &nbsp';
                    }
                    if ($rows['reject'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-rejectcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-danger "><i class="fa fa-check-square"></i> Batal Cancel </a> &nbsp';
                    }
                    if ($rows['cancel'] == "Y") {
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Ajukan Cancel </a> &nbsp';
                    }


                    // if ($rows["field_role"] == "ADM") {
                    //   if ($row["field_status"] == "P") {
                    //     # code...
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-rejectcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-info "><i class="fa fa-check-square"></i> Reject Cancel </a> &nbsp';
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-aprovalcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-success "><i class="fa fa-window-close"></i> Approve Cancel </a> &nbsp';
                    //   } elseif ($row["field_status"] == "C") {
                    //     # code...
                    //     echo '<span class="badge btn-danger text-white">Cancelled Complete</span>';
                    //   } elseif ($row["field_status"] == "S") {
                    //     # code...
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Ajukan Cancel </a> &nbsp';
                    //   }
                    // } elseif ($rows["field_role"] == "MGR") {
                    //   if ($row["field_status"] == "P") {
                    //     # code...                        
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-aprovalcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-success "><i class="fa fa-window-close"></i> Approve Cancel </a> &nbsp';
                    //   } elseif ($row["field_status"] == "C") {
                    //     # code...
                    //     echo '<span class="badge btn-danger text-white">Cancelled Complete</span>';
                    //   } elseif ($row["field_status"] == "S") {
                    //     # code...
                    //     //echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Submit Cancel </a> &nbsp';
                    //   }
                    // } elseif ($rows["field_role"] == "AMR") {
                    //   if ($row["field_status"] == "P") {
                    //     # code...                        
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-aprovalcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-success "><i class="fa fa-window-close"></i> Approve Cancel </a> &nbsp';
                    //   } elseif ($row["field_status"] == "C") {
                    //     # code...
                    //     echo '<span class="badge btn-danger text-white">Cancelled Complete</span>';
                    //   } elseif ($row["field_status"] == "S") {
                    //     # code...
                    //     //echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Submit Cancel </a> &nbsp';
                    //   }
                    // } elseif ($rows["field_role"] == "SPV") {
                    //   if ($row["field_status"] == "P") {
                    //     # code...                        
                    //     echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';
                    //   } elseif ($row["field_status"] == "C") {
                    //     # code...
                    //     echo '<span class="badge btn-danger text-white">Cancelled Complete</span>';
                    //   } elseif ($row["field_status"] == "S") {
                    //     # code...
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Ajukan Cancel </a> &nbsp';
                    //   }
                    // } elseif ($rows["field_role"] == "BCO") {
                    //   if ($row["field_status"] == "P") {
                    //     # code...                        
                    //     echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';
                    //   } elseif ($row["field_status"] == "C") {
                    //     # code...
                    //     echo '<span class="badge btn-danger text-white">Cancelled Complete</span>';
                    //   } elseif ($row["field_status"] == "S") {
                    //     # code...
                    //     echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Ajukan Cancel </a> &nbsp';
                    //   }
                    // } elseif ($rows["field_role"] == "CMS") {
                    //   if ($row["field_status"] == "P") {
                    //     # code...                        
                    //     echo '<span class="badge btn-dafault text-white">Waiting Approval</span>';
                    //   } elseif ($row["field_status"] == "C") {
                    //     # code...
                    //     echo '<span class="badge btn-danger text-white">Cancelled Complete</span>';
                    //   } elseif ($row["field_status"] == "S") {
                    //     # code...
                    //     if ($row['field_approve'] == $row['field_officer_id']) {

                    //       echo '<a href="#" data-toggle="modal" data-target="#modal-default-submitcancel' . $row["field_trx_deposit"] . '" class="text-white btn btn-warning "><i class="fa fa-window-close"></i> Ajukan Cancel </a> &nbsp';
                    //     } else {
                    //       echo '<span class="badge btn-success text-white">Saldo dikembalikan</span>';
                    //     }
                    //   }
                    // }
                    ?>

                  </td>
                </tr>

                <!-- Modal aproval CANCEL -->
                <div class="modal fade" id="modal-default-aprovalcancel<?php echo $row["field_trx_deposit"]; ?>">
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
                                  echo 'Anda Setujui Pembatalan Transaksi ini ' . $row["field_deposit_gold"];
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=deposit&iddepositc=<?php echo $row['field_trx_deposit']; ?>" type="submit" class="text-white btn btn-success">&nbsp Iya &nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Modal submit CANCEL -->
                <div class="modal fade" id="modal-default-submitcancel<?php echo $row["field_trx_deposit"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <!-- <center>
                          <h3 class="modal-title">Anda Yakin Untuk Mengajukan Pembatalan Transaksi</h3>
                        </center> -->
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">
                              <center>
                                <h4>
                                  <?php
                                  echo 'Anda Yakin Untuk Mengajukan Pembatalan Transaksi';
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=deposit&iddepositp=<?php echo $row['field_trx_deposit']; ?>" type="submit" class="text-white btn btn-success">&nbsp&nbsp Iya &nbsp&nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Modal Batal CANCEL -->
                <div class="modal fade" id="modal-default-rejectcancel<?php echo $row["field_trx_deposit"]; ?>">
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
                                  echo 'Saldo Pelangan Akan Kembali Sebesar ' . $row["field_deposit_gold"];
                                  ?>
                                </h4>
                              </center>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                            <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                            <a href="?module=deposit&iddeposits=<?php echo $row['field_trx_deposit']; ?>" type="submit" class="text-white btn btn-success">&nbsp&nbsp Iya &nbsp&nbsp</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- View -->
                <div class="modal fade " id="modal-default-view-cancel<?php echo $row["field_trx_deposit"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <center>
                          <h3 class="modal-title">View Transaksi</h3>
                        </center>
                      </div>
                      <div class="modal-body">
                        <form method="post" class="form-horizontal">
                          <div class="form-group">
                            <div class="box-header">

                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Keluar</button>
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
                <th>Reff</th>
                <th>Account</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Sub Total</th>
                <th>Free</th>
                <th>Total</th>
                <th>Gold</th>
                <th>Officer</th>
                <th>Status</th>
                <th>Submitter</th>
                <th>#</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- Content -->
      </div>
    </div>
  </div>
</section>