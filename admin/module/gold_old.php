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

$Sql = "SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();

$SqlEmas="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1";
$StmtEmas = $db->prepare($SqlEmas);
$StmtEmas->execute();
$ResultEmas = $StmtEmas->fetch(PDO::FETCH_ASSOC);
$SqlEmas2="SELECT * FROM tblgoldprice ORDER BY field_gold_id DESC LIMIT 1,1";
$StmtEmas2 = $db->prepare($SqlEmas2);
$StmtEmas2->execute();
$ResultEmas2 = $StmtEmas2->fetch(PDO::FETCH_ASSOC);



$HargaTerkini=$ResultEmas['field_sell'];
$HargaKemarin=$ResultEmas2['field_sell'];
$Selisi      =$HargaTerkini-$HargaKemarin;
$trxid       =$ResultEmas['field_gold_id'];
$abs         =abs($Selisi);

if ($Selisi > 1) {
  
 $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
              // execute the query
            $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Naik"));     
}else{
  
  $update_stmt=$db->prepare("UPDATE tblgoldprice SET field_fluktuasi=:fluktuasi, field_rasio=:rasio WHERE field_gold_id=:trxid ");
              // execute the query
            $update_stmt->execute(array(':trxid'=>$trxid ,':fluktuasi'=>$abs, ':rasio'=>"Turun"));  
}


?>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-edit"></i>
              <h3 class="box-title">Gold Price</h3>
              <!-- <button type="submit" class="btn btn-success pull-right">Add Transaction</button> -->
              <a href="?module=addgold" class="btn btn-success  pull-right"><i class="fa fa-plus"></i> Add Price</a>          
            </div>     
              <!-- Content -->                 
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th >No</th>
                  <th >Ratio</th>                               
                  <th >Name Gold</th>                 
                  <th >Amount Price</th>
                  <th >Status</th>
                  <th >Aprovel</th>             
                  <th>Action</th>         
                </tr>
                </thead>
                <tbody>
             <?php
             $no=1; 
              foreach(array_slice($result,0,2) as $row) {        
                ?> 
             
                 <tr>
                  <td ><?php echo $no++ ;?></td>
                                
                  <td>
                    <?php 

                   if ($row["field_rasio"]=="Naik") {
                        echo '<div class="glyphicon glyphicon-arrow-up btn btn-info"> </div>';
                      }elseif ($row["field_rasio"]=="Sama") {
                        echo '<div class="glyphicon glyphicon-align-justify btn btn-warning"> </div>';
                      }elseif ($row["field_rasio"]=="Turun") {
                        echo '<div class="glyphicon glyphicon-arrow-down btn btn-danger"> </div>';
                      }
                     echo '<strong> '.rupiah($row["field_fluktuasi"]).'<strong>'; 
                   ?>                     
                   </td>
                  <td data-title="Trx Id"><small>Harga Update <?php echo date("d F Y H:i:s",strtotime($row["field_date_gold"]));?></small><br><strong><?php echo $row["field_name"];?></strong></td>
                  <td data-title="Trx Id">Jual <strong><?php echo rupiah($row["field_buyback"]);?></strong><br>Beli <strong><?php echo rupiah($row["field_sell"]);?></strong></td>
                  <td data-title="Trx Id"><strong>
                    <?php 
                    
                    
                    if ($row["field_status"]=="A") {
                     
                      echo '<span class="badge btn-success text-white">Approve</span>';                  
                    }elseif ($row["field_status"]=="C") {                     
                      echo '<span class="badge btn-info text-white">Cancel</span>';                      
                    }elseif ($row["field_status"]=="P") {
                      echo '<span class="badge btn-warning text-white">Pending</span>';                  
                    }elseif ($row["field_status"]=="R") {                      
                      echo '<span class="badge btn-danger text-white">Reject</span>';  
                    }
                     ?>         
                  
                  
                
                
                
                </td>
                  <td data-title="Trx Id"><strong><?php echo $row["filed_approve"];?></td>
                  
                  <td data-title="Trx Id" >                   

                    <?php 
                    
                    if ($rows["field_role"]=="ADM") {
                      echo '<a href="?module=updgold&id='.$row["field_gold_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';                      
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_gold_id"].'" class="text-white btn btn-danger "><i class="fa fa-trash"></i></a> &nbsp'; 
                      echo '<a href="#" data-toggle="modal" data-target="#modal-default'.$row["field_gold_id"].'" class="text-white btn btn-info "><i class="fa fa-info-circle"></i> Detail </a> &nbsp';                  
                    }elseif ($rows["field_role"]=="MGR") {
                      echo '<a href="?module=updgold&id='.$row["field_gold_id"].'" class="text-white btn btn-success "><i class="fa fa-refresh"></i></a>&nbsp';                     
                    }elseif ($rows["field_role"]=="SPV") {
                      echo '';
                    }elseif ($rows["field_role"]=="BCO") {
                      echo '';
                    }elseif ($rows["field_role"]=="CMS") {
                      echo '';
                    }
                     ?>                    
                  
                  </td>                                       
                </tr>

                    <div class="modal fade" id="modal-default<?php echo $row['field_gold_id'];?>">
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
                      echo "Data Yang Didelete Harga Beli ".rupiah($row["field_buyback"])." Harga Jual ".rupiah($row["field_sell"]);                      
                    ?>
                    </h4>   
                    </center> 
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">No</button>
                    <!-- <input type="submit"  name="btn_insert2" class="btn btn-success " value="YES"> -->
                    <a href="?module=gold&id=<?php echo $row['field_gold_id']; ?>" type="submit" class="text-white btn btn-danger">YES</a>
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
                  <th >Ratio</th>                               
                  <th >Name Gold</th>                 
                  <th >Amount Price</th> 
                  <th >Status</th> 
                  <th >Aprovel</th>                 
                  <th >Action</th> 
                </tr>
                </tfoot>
              </table>                              
            </div>
              <!-- Content -->
              </div>
        </div>
      </div>
    </section>


