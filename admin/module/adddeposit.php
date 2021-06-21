<?php
require_once("../config/connection.php");
require_once("../php/function.php");

//noReff
$sql = "SELECT field_no_referensi FROM tbltrxmutasisaldo ORDER BY field_no_referensi DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if($order['field_no_referensi']==""){         
  $no=1;                  
  $thn = date('Y');                 
  $thn = substr( $thn,-2);
  $reff = "Reff";                  
  $char = $thn.$reff;
  $noReff =$char.sprintf("%09s",$no);
}else{          
  $noreff = $order['field_no_referensi'];         
  $noUrut = substr($noreff, 6);               
  $no=$noUrut+1;                
  $thn = date('Y');
  $thn = substr( $thn,-2);
  $reff = "Reff"; 
  $char = $thn.$reff;
  $noReff =$char.sprintf("%09s",$no); 
}


$goldprice="990000";


// $id_invoice = mysqli_insert_id(mysqli_connect("localhost", "root", "" ,"dbcrudoop"));

// var_dump($id_invoice);
// echo $id_invoice;

// die();

// session_start();

if (isset($_POST['payment'])) {
  # code...
 echo $field_no_referensi       = $noReff;
 echo '<br>';
 echo $field_date_deposit       = date('Y-m-d');
 echo '<br>';
 echo $field_rekening_deposit   = $_POST['txt_rekening'];
 echo '<br>';
 echo $field_sumber_dana        = $_POST['txt_select']; 
 echo '<br>';
 echo $field_branch             = $branchid;
 echo '<br>';
 echo $field_officer_id         = $id;
 echo '<br>';
 echo $field_sub_total          = $_POST['txt_subtotal'];
 echo '<br>';  
 echo $field_operation_fee      = $_POST['txt_free'];
 echo '<br>';
 echo $field_operation_fee_rp   = $field_sub_total*$field_operation_fee/100;
 echo '<br>';
 echo $field_total_deposit      = $_POST['txt_total'];
 echo '<br>';
 echo $field_deposit_gold       = $field_total_deposit/$goldprice;
 echo '<br>';
 echo $field_gold_price         = $goldprice;
}

// // mysqli_query($koneksi, "insert into invoice values(NULL,'$nomor','$tanggal','$pelanggan','$kasir','$sub_total','$diskon','$total')")or die(mysqli_errno($koneksi));

// // $id_invoice = mysqli_insert_id($koneksi);

// $transaksi_produk   = $_POST['transaksi_produk'];
// $transaksi_harga    = $_POST['transaksi_harga'];
// $transaksi_jumlah   = $_POST['transaksi_jumlah'];
// $transaksi_total    = $_POST['transaksi_total'];

// $jumlah_pembelian = count($transaksi_produk);

// for($a=0;$a<$jumlah_pembelian;$a++){

// 	$t_produk = $transaksi_produk[$a];
// 	$t_harga = $transaksi_harga[$a];
// 	$t_jumlah = $transaksi_jumlah[$a];
// 	$t_total = $transaksi_total[$a];

// 	// // ambil jumlah produk
// 	// $detail = mysqli_query($koneksi, "select * from produk where produk_id='$t_produk'");
// 	// $de = mysqli_fetch_assoc($detail);
// 	// $jumlah_produk = $de['produk_stok'];

// 	// // kurangi jumlah produk
// 	// $jp = $jumlah_produk-$t_jumlah;
// 	// mysqli_query($koneksi, "update produk set produk_stok='$jp' where produk_id='$t_produk'");

// 	// simpan data pembelian
// 	mysqli_query($koneksi, "insert into transaksi values(NULL,'$id_invoice','$t_produk','$t_harga','$t_jumlah','$t_total')")or die(mysqli_errno($koneksi));

// }

// header("location:penjualan.php?alert=sukses");




$Stmt = $db->prepare("SELECT * FROM tblcustomer");
$Stmt->execute();
$Result = $Stmt->fetchAll();

$sql    ="SELECT * FROM tblproduct";
$stmt 	= $db->prepare($sql);
$stmt->execute();
$result  = $stmt->fetchAll();


?>



    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">       
          <div class="box box-primary">
          <form  method="POST" class="form-horizontal" >
            <div class="box-header with-border">
              <!-- <h3 class="box-title"></h3> -->
              <!-- <a href="" class="btn btn-success btn-block margin-bottom">Search Product</a> -->
              <button style="margin-top: 27px" type="button" class="btn btn-success btn-block margin-bottom" data-toggle="modal" data-target="#cariProduk">
                    <i class="fa fa-search"></i> &nbsp Search Product
              </button>
              <div class="box-tools">
                    <!-- modal -->
                    <!-- Modal -->
                  <div class="modal fade" id="cariProduk" tabindex="-1" role="dialog" aria-labelledby="cariProdukLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          Pilih Pembelian produk
                        </div>
                        <div class="modal-body">


                          <div class="table-responsive">
                            <!-- <table class="table table-bordered table-striped table-hover" id="table-datatable-produk"> -->
                            <table class="table table-bordered table-striped table-hover" id="trxSemua">
                              <thead>
                                <tr>
                                  <th class="text-center">NO</th>
                                  <th>KODE</th>
                                  <th>PRODUK</th>
                                  <th class="text-center">SATUAN</th>
                                  <th class="text-center">STOK</th>
                                  <th class="text-center">HARGA JUAL</th>
                                  <th>KETERANGAN</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                $no=1;
                                foreach($result AS $rows){                                
                                  ?>
                                  <tr>
                                    <td width="1%" class="text-center"><?php echo $no++; ?></td>
                                    <td width="1%"><?php echo $rows['field_product_code']; ?></td>
                                    <td>
                                      <?php echo $rows['field_product_name']; ?>
                                      <br>
                                      <small class="text-muted"><?php echo $rows['field_category']; ?></small>
                                    </td>
                                    <td width="1%" class="text-center"><?php echo $rows['field_unit']; ?></td>
                                    <td width="1%" class="text-center"><?php echo $rows['field_branch']; ?></td>
                                    <td width="20%" class="text-center"><?php echo $rows['field_price']; ?></td>
                                    <td width="15%"><?php echo $rows['field_note']; ?></td>
                                    <td width="1%">              
                                     
                                        <input type="hidden" id="kode_<?php echo $rows['field_product_id']; ?>" value="<?php echo $rows['field_product_code']; ?>">
                                        <input type="hidden" id="nama_<?php echo $rows['field_product_id']; ?>" value="<?php echo $rows['field_product_name']; ?>">
                                        <input type="hidden" id="harga_<?php echo $rows['field_product_id']; ?>" value="<?php echo $rows['field_price']; ?>">
                                        <button type="button" class="btn btn-success modal-pilih-produk" id="<?php echo $rows['field_product_id']; ?>" data-dismiss="modal">Select</button>
                                        
                                    </td>
                                  </tr>
                                  <?php } ?>
                              </tbody>
                            </table>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                <!-- modal -->
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">        
                <li><a><input type="hidden" class="form-control"                            id="tambahkan_id"></a></li>
                <li><a><input type="text" class="form-control" placeholder="Code Product"   id="tambahkan_kode" readonly></a></li>
                <li><a><input type="text" class="form-control" placeholder="Name Product"   id="tambahkan_nama" readonly></a></li>
                <li><a><input type="text" name="" class="form-control" placeholder="Price"  id="tambahkan_harga" readonly></a></li>
                <li><a><input type="number" name="" class="form-control" placeholder="Qty"  id="tambahkan_jumlah"></a></li>
                <li><a><input type="text" name="" class="form-control" placeholder="Total"  id="tambahkan_total" readonly></a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <a href="#" class="btn btn-primary btn-block margin-bottom" id="tombol-tambahkan">Add </a>
          <!-- /.box -->
        </div>
        <!-- /.col transaksi-->
        <div class="col-md-9"> 
          <div class="box box-primary">
            <div class="box-header with-border">              
                    <!-- title row -->
              <div class="row">
                <div class="col-xs-12">
                  <h3 class="page-header">
                   <!--  <i class="fa fa-globe"></i> AdminLTE, Inc. -->
                   <div class="row">
                    
                      <div class="col-xs-3">                      
                      <select class="form-control" name="txt_select">
                        <option value="">--Pilih--</option>
                        <option value="Investasi">Investasi</option>
                        <option value="Investasi">Sampah</option>
                        <option value="Gaji">Gaji</option>
                        
                      </select>
                      </div>
                      <div class="col-xs-3">
                      <input type="text" id="add_id" class="form-control" placeholder="IdCustomer" readonly>
                      <input type="text" name="txt_rekening" id="add_account" class="form-control" placeholder="Rekening" readonly>
                      </div>
                      <div class="col-xs-3">
                      <input type="text" name="txt_memberid" id="add_memberid" class="form-control" placeholder="member_id" readonly>
                      <input type="text" name="txt_customer" id="add_customer" class="form-control" placeholder="Customer" readonly>
                      </div>
                      <div class="col-xs-3">
                      <!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                      <i class="fa fa-users"></i> Customer
                      </button> -->
                      <button style="margin-right: 5px;" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#cariCustomer">
                      <i class="fa fa-users"></i> &nbsp Customer
                      </button>
                      </div>
                    
                   </div>
                    <!-- <small class="pull-right">Date: 2/10/2014</small> -->
                  </h3>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
                  <!-- Modal Customer-->
                  <div class="modal fade" id="cariCustomer" tabindex="-1" role="dialog" aria-labelledby="cariCustomerLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <center> <h5>
                          <i class="fa fa-users"></i>
                          Select Customer</h5></center>
                        </div>
                        <div class="modal-body">


                          <div class="table-responsive">
                            <!-- <table class="table table-bordered table-striped table-hover" id="table-datatable-produk"> -->
                            <table class="table table-bordered table-striped table-hover" id="trxSemua2">
                              <thead>
                                <tr>
                                  <th class="text-center">No</th>
                                  <th class="text-center">Account Number</th>
                                  <th class="text-center">Name Customer</th>
                                  
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                $no=1;
                                foreach($Result AS $rows){                                
                                  ?>
                                  <tr>
                                    <td width="1%" class="text-center"><?php echo $no++; ?></td>
                                    <td width="20%"><?php echo $rows['field_rekening']; ?></td>
                                    <td width="20%"><?php echo $rows['field_nama_customer']; ?> </td>                      
                                    <td width="1%">
                                        <input type="hidden" id="member_<?php echo $rows['field_customer_id']; ?>" value="<?php echo $rows['field_member_id']; ?>">                                      
                                        <input type="hidden" id="account_<?php echo $rows['field_customer_id']; ?>" value="<?php echo $rows['field_rekening']; ?>">
                                        <input type="hidden" id="customer_<?php echo $rows['field_customer_id']; ?>" value="<?php echo $rows['field_nama_customer']; ?>">
                                        <button type="button" class="btn btn-success modal-select-customer" id="<?php echo $rows['field_customer_id']; ?>" data-dismiss="modal">Select</button>
                                        
                                    </td>
                                  </tr>
                                  <?php } ?>
                              </tbody>
                            </table>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                <!-- modal Customer -->

              <!-- Table row -->
              <div class="row">
                <div class="col-xs-12 table-responsive">
                  <table class="table table-striped" id="table-pembelian">
                    <thead>
                    <tr>
                      <th>Code</th>
                      <th>Product</th>
                      <th style="text-align: center;">Price</th>
                      <th style="text-align: center;">Qty</th>
                      <th style="text-align: center;">Total</th>
                      <th width="1%" style="text-align: center;">Action</th>
                    </tr>
                    </thead>
                    <tbody>                  
                    
                    </tbody>
                    <tfoot>
                      <tr class="bg-info">
                        <td style="text-align: right;" colspan="2"><b>Total</b></td>
                        <td style="text-align: center;"><span class="pembelian_harga" id="0">Rp.0,-</span></td>
                        <td style="text-align: center;"><span class="pembelian_jumlah" id="0">0</span></td>
                        <td style="text-align: center;"><span class="pembelian_total" id="0">Rp.0,-</span></td>
                        <td style="text-align: center;"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-xs-6">
                  <p class="lead">Gold Price</p>


                  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                   <input type="hidden" value="<?php echo $goldprice;?>" > 
                   <span class="goldprice" id="<?php echo $goldprice;?>"><?php echo rupiah($goldprice);?></span>
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-xs-6">
                  <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>
                        <input type="text" name="txt_subtotal" class="sub_total_form" value="0">
                        <span class="sub_total_pembelian" id="0">Rp.0,-</span>
                        </td>
                      </tr>
                      <tr>
                        <th id="txt_persen">Oprasional free (5%)</th>
                        <td>
                          <input class="form-control total_fee" type="number" min="0" max="100" id="5" name="txt_free">
                          <span class="fee" id="0">5%</span>

                          <input class="form-control total_fee_rp" type="number" min="0" max="100" value="0" id="5" name="txt_free_rp" >
                          <span class="fee_rp" id="0">Rp.0,-</span>
                        </td>
                      </tr>
                      <tr>
                        <th>Total</th>
                        <td>
                          <input type="text" name="txt_total" class="total_form" value="0">
                          <span class="total_pembelian" id="0">Rp.0,-</span>
                        </td>
                      </tr>
                      <tr>
                        <th>Gold Customer</th>
                        <td>
                        <input type="text" name="txt_gold" class="gold_form" value="0">
                        <span class="total_gold" id="0">0,gram</span>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-xs-12">
                  <a href="?module=deposit" class="btn btn-danger"><i class="fa fa-reply "></i> Cancel</a>
                  <button type="Submit" name="payment" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Payment
                  </button>
                              <!--                   <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i> Generate PDF
                  </button> -->
                </div>
              </div>
              <!-- ....batas   -->
            </div>
              <!-- /. transaksi -->
          </form>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>




    <!-- Main content -->
 <!--    <section class="invoice"> -->

 <!--    </section> -->
    <!-- /.content -->
<!--     <div class="clearfix"></div> -->

  