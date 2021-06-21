<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if(!isset($_SESSION['userlogin'])) {
  header("location: ../index.php");
}

if (isset($_REQUEST['id'])) {

    $id=$_REQUEST['id'];    
    $select_stmt= $db->prepare('SELECT * FROM tblgoldprice WHERE field_gold_id =:id'); //sql select query
    $select_stmt->bindParam(':id',$id);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);

    // echo $row['field_gold_id'];
    $delete_stmt = $db->prepare('DELETE FROM tblgoldprice WHERE field_gold_id =:id');
    $delete_stmt->bindParam(':id',$id);
    $delete_stmt->execute();
}

$Sql = "SELECT * FROM tblorderipaymu ORDER BY order_id DESC";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();



?>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-edit"></i>
              <h3 class="box-title">Payment Customer</h3>
              <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
              <a href="#" data-toggle="modal" data-target="#modal-defaultadd" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Price</a>          
            </div>     
            <!-- Content --> 
              <!-- modal add -->
                    <div class="modal fade" id="modal-defaultadd">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Payment Order</h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
                    <div class="form-group">
                    <div class="box-header"> 
                                  

                      <label>Nama Operator</label>
                      <br>
                      <!-- <input id="product" type="text" name="product" value="Kopi Gayo" class="form-control"> -->
                      <select id="product" name="product" class="form-control" type="text">

                      <option value="INDOSAT">INDOSAT</option>
                      <option value="TELKOMSEL">TELKOMSEL</option>
                      <option value="XL">XL</option>
                      <option value="AXIS">AXIS</option>
                      </select>

                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    
                    <!-- <a href="?module=gold&id=23" type="submit" class="text-white btn btn-danger">YES</a> -->
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
                  <th >No</th>
                  <th >Id Trx</th>
                  <th >Date Trx</th>                               
                  <th >Name</th> 
                  <th >Email</th>
                  <th >Id Member</th>
                  <th >Metode Pembayaran</th>                
                  <th >Amount Price</th> 
                  <th >Status</th>                                    
                  <th>Action</th>         
                </tr>
                </thead>
                <tbody>
             <?php
             $no=1; 
              foreach($result as $rows) { 
                    $status = $rows["status"];
										if($status=="pending"){
											$status = '<span class="label pull-center bg-yellow">Menunggu Pembayaran</span>';
											$action = '<a href="'.$rows["url"].'" class="text-white btn btn-primary btn-block">Bayar</a><br /><a href="#" data-toggle="modal" data-target="#modal-default'.$rows["order_id"].'" class="text-white btn btn-info btn-block">Detail</a>';
										}else if($status=="berhasil"){
											$status = '<span class="label pull-center bg-green">Pembayaran Berhasil</span>';
											$action = '<a href="#" data-toggle="modal" data-target="#modal-default'.$rows["order_id"].'" class="text-white btn btn-info btn-block">Detail</a>';
										}else if($status=="gagal"){
											$status = '<span class="label pull-center bg-red">Pembayaran Gagal</span>';
											$action = '<a href="#" data-toggle="modal" data-target="#modal-default'.$rows["order_id"].'" class="text-white btn btn-info btn-block">Detail</a>';
										}       
                ?> 
             
                 <tr>
                  <td ><?php echo $no++ ;?></td>
                                
                  <td data-title="Id Trx"><strong><?php echo $rows["trx_id"]?></strong></td>
                  <td data-title="Date Trx"><?php echo $rows["date_trx"]?></td>
                  <td data-title="Name"><?php echo $rows["name"]?></td>
                  <td data-title="Name"><?php echo $rows["email"]?></td>
                  <td data-title="Name"><?php echo $rows["member_id"]?></td>
                  <td data-title="Via"><?php echo $rows["via"]?></td>
                  <td data-title="Price"><?php echo rupiah( $rows["price"])?></td>
                  <td data-title="Status"><?php echo $status;?></td>
                  <td data-title="Action" ><?php echo $action;?></td> 
                                      
                </tr>
                    <!-- modal detail -->

                    <div class="modal fade" id="modal-default<?php echo $rows['order_id'];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Detail Transaction</h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
                    <div class="form-group">
                    <div class="box-header"> 
                      <?php
                        echo 'g';
                        ?>
                      

                    </div>
                    </div>
                    <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default " data-dismiss="modal">No</button> -->
                    <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                    <!-- <a href="?module=gold&id=</?php echo $rows['field_gold_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a> -->
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
                  <th >No</th>
                  <th >Id Trx</th>
                  <th >Date Trx</th>                               
                  <th >Name</th> 
                  <th >Email</th>
                  <th >Id Member</th>
                  <th >Metode Pembayaran</th>                
                  <th >Amount Price</th> 
                  <th >Status</th>                 
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


