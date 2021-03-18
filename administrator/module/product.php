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

    $select_stmt= $db->prepare('SELECT * FROM tblproduct WHERE field_product_id =:id'); //sql select query
    $select_stmt->bindParam(':id',$id);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);

    // echo $row['tblproduct_kode'];
    $delete_stmt = $db->prepare('DELETE FROM tblproduct WHERE field_product_id =:id');
    $delete_stmt->bindParam(':id',$id);
    $delete_stmt->execute();
}


if ($_SESSION['rolelogin']=='ADM' OR $_SESSION['rolelogin']=='MGR') {
    $Sql = "SELECT * FROM tblproduct P JOIN tblcategory C ON P.field_category=C.field_category_id 
                                       JOIN tblbranch B ON P.field_branch=B.field_branch_id 
                                       ORDER BY field_product_id DESC";
    $Stmt = $db->prepare($Sql);
    $Stmt->execute();
    $result = $Stmt->fetchAll();
}else{
    $Sql = "SELECT * FROM tblproduct P JOIN tblcategory C ON P.field_category=C.field_category_id 
                                       JOIN tblbranch B ON P.field_branch=B.field_branch_id 
                                       WHERE field_branch=:idbranch ORDER BY field_product_id DESC";
    $Stmt = $db->prepare($Sql);
    $Stmt->execute(array(":idbranch"=>$branchid));
    $result = $Stmt->fetchAll();
}


$no=1;



?>
                 
    
    <!-- Content Header (Page header) -->
     <section  class="content-header">
      <div class="row box-footer">
<!-- <section class="content"> -->
      <div class="row">
        <div class="col-xs-12">
          <!-- <div class="box"> -->
            <div class="">
            <div class="box-header">

              <h3 class="box-title"><a href="?module=addproduct" class="text-white btn btn-info "><i class="fa fa-plus"></i> Add Product Price</a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>                              
                  <th>Name Product</th>                 
                  <th>Amount Price</th>
                  <th>Price Branch</th>
                  <th>Action</th>                   
                  
            
                </tr>
                </thead>
                <tbody>
             <?php 
              foreach($result as $row) {        
                ?> 
             
                 <tr>
                  <td ><?php echo $no++?></td>
                                
                  
                  <td data-title="Trx Id"><?php echo $row["field_category"]; ?>|<?php echo $row["field_product_code"];?><br><strong><?php echo $row["field_product_name"];?></strong></td>
                  <td data-title="Trx Id"><?php echo $row["field_note"] ?>/<?php echo $row["field_unit"];?><br><strong><?php echo rupiah($row["field_price"]);?></strong> <br><small> Harga Update <?php echo date("d F Y",strtotime($row["field_date_price"]));  ?></small></td>
                  <td ><?php echo $row["field_branch_name"]; ?></td>
                  <td ata-title="Trx Id" >                   

                    <?php 
                    if ($rows["field_role"]=="ADM") {
                      echo '<a href="?module=updproduct&id='.$row["field_product_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                      // echo '<a href="?module=product&id='.$row["field_product_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_product_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp';                    
                     
                    }elseif ($rows["field_role"]=="MGR") {
                      echo '<a href="?module=updproduct&id='.$row["field_product_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';                      
                    }elseif ($rows["field_role"]=="SPV") {
                      echo '<a href="?module=updproduct&id='.$row["field_product_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';
                    }elseif ($rows["field_role"]=="BCO") {
                      echo "Proses";
                    }
                     ?>                    
                  
                  </td> 
                                      
                </tr>

                    <div class="modal fade" id="modal-default<?php echo $row["field_product_id"];?>">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title">Yakin Menghapus Data</h4></center>
                    </div>
                    <div class="modal-body">
                    <form method="post" class="form-horizontal">
                    <div class="form-group">
                    <div class="box-header"> 

                    <center>
                    <h4>              
                    <?php 
                      echo "Nama product ".$row["field_product_name"]." Dengan ".rupiah($row["field_price"]);                      
                    ?>
                    </h4>   
                    </center> 
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                    <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                    <a href="?module=product&id=<?php echo $row['field_product_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
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
         <!--        <tfoot>
                <tr>
                  <th>Trx</th>
                  <th>Rendering engine</th>
                  <th>Browser</th>
                  <th>Platform(s)</th>
                  <th>Engine version</th>
                  <th>CSS grade</th>
                </tr>
                </tfoot> -->
              </table>
                              
            </div>
            <!-- /.box-body -->
          </div>

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- div ikut atas -->
    </div> 
    </section>


