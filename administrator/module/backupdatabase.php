<?php 
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");


if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}

$no=1;
$Sql = "SELECT * FROM tblbackup";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$Result = $Stmt->fetchAll();

                    // if (($a>=$awalpagi) && ($a<=$akhirpagi)) {
                    //     require "../backup.php";
                    // }else if(($a>=$awalsiang) && ($a<=$akhirsiang)){
                    //     require "../backup.php";
                    // }elseif(($a>=$awalsore) && ($a<=$akhirsore)){
                    //     require "../backup.php";
                    // }elseif(($a>=$awalmalam) && ($a<=$akhirmalam)) {
                    //     require "../backup.php";
                    // }


if (isset($_REQUEST['btn_backup'])) {
        
        $tables = array();
        $result = mysqli_query($connection,"SHOW TABLES");
        while($row = mysqli_fetch_row($result)){
          $tables[] = $row[0];
        }

        $return = '';
        foreach($tables as $table){
          $result = mysqli_query($connection,"SELECT * FROM ".$table);
          $num_fields = mysqli_num_fields($result);
          
          $return .= 'DROP TABLE '.$table.';';
          $row2 = mysqli_fetch_row(mysqli_query($connection,"SHOW CREATE TABLE ".$table));
          $return .= "\n\n".$row2[1].";\n\n";
          
          for($i=0;$i<$num_fields;$i++){
            while($row = mysqli_fetch_row($result)){
              $return .= "INSERT INTO ".$table." VALUES(";
              for($j=0;$j<$num_fields;$j++){
                $row[$j] = addslashes($row[$j]);
                if(isset($row[$j])){ $return .= '"'.$row[$j].'"';}
                else{ $return .= '""';}
                if($j<$num_fields-1){ $return .= ',';}
              }
              $return .= ");\n";
            }
          }
          $return .= "\n\n\n";
        }

        //save file

        $handle = fopen("../database/".$db_name.date("d_m_Y").".sql","w+");
        // $handle = fopen('./pages/backup-restore/backup/'.$nama_file,'w+');
        fwrite($handle,$return);
        fclose($handle);
        //echo $handle;

        

        $hariini=date("Y-m-d");
        $select_stmt=$db->prepare("SELECT * FROM tblbackup WHERE field_date=:Inihari ORDER BY field_backupid DESC");
        $select_stmt->execute(array(':Inihari'=>$hariini )); //execute query with bind parameter
        $row=$select_stmt->fetch(PDO::FETCH_ASSOC);   
        $data=$select_stmt->rowCount();
        echo $data ;

        if ($data>0) {
          echo " UPDATEDATA";
              $filenamedatabase=$db_name.date("d_m_Y").".sql";      
              $insert_stmt=$db->prepare('UPDATE tblbackup SET field_month=:month,field_file_name=:namefile,field_date_time=:udate WHERE field_date=:Inihari');

              $insert_stmt->bindParam(':Inihari',$hariini);       
              $insert_stmt->bindParam(':month',date("F"));
              $insert_stmt->bindParam(':namefile',$filenamedatabase);        
              $insert_stmt->bindParam(':udate',date("Y-m-d H:i:s"));          
              $insert_stmt->execute();
              
        echo "Successfully backed up";
        echo '<META HTTP-EQUIV="Refresh" Content="1;">';
        }else{
          echo " INSERTDATA";
              $filenamedatabase=$db_name.date("d_m_Y").".sql";       
              $insert_stmt=$db->prepare('INSERT INTO tblbackup (field_month,field_file_name,field_date_time,field_date)VALUES(:month,:namefile,:udatetim,:udate)');
              $insert_stmt->bindParam(':month',date("F"));
              $insert_stmt->bindParam(':namefile',$filenamedatabase);        
              $insert_stmt->bindParam(':udatetim',date("Y-m-d H:i:s"));
              $insert_stmt->bindParam(':udate',date("Y-m-d "));          
              $insert_stmt->execute();
        
        echo "Successfully backed up";
        echo '<META HTTP-EQUIV="Refresh" Content="1;">';
        }
    


}


if (isset($_GET['file'])) {
    $filename    = $_GET['file'];

    $back_dir    =".../../database/";
    $file = $back_dir.$_GET['file'];
     
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            
            exit;
        } 
        else {
            $_SESSION['pesan'] = "Oops! File - $filename - not found ...";
            //header("location:index.php");
        }
    }

 ?>

<!-- 
<style>

.center {
  margin: 0;
  position: absolute;
  top: 35%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  width: 220px;
  height: 220px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style> -->




                 
    
    <!-- Content Header (Page header) -->
     <section  class="content-header">
      <div class="row box-footer">
<!-- <section class="content"> -->
      <div class="row">
        <div class="col-xs-12">
          <!-- <div class="box"> -->
            <div class="">
            <div class="box-header">
            <center>
            <form method="post" class="form-horizontal">
            <h3 class="box-title">
            <input type="submit"  name="btn_backup" class="btn btn-success " value="Backup Database"></a></h3> 
            </form>

            </center>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="trxSemua" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Month</th>                               
                  <th>Name File</th>                 
                  <th>Date</th>                 
                  <th>Action</th>                   
                  
            
                </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach ($Result as $row ) {                    
                    
                  ?>
              
                 <tr>
                  <td ><?php echo $no++; ?></td>
                                
                  <td>
                      <?php echo $row['field_month']; ?>  
                   </td>
                  <td data-title="Trx Id"><?php echo $row['field_file_name']; ?></td>
                  <td data-title="Trx Id"> <strong></strong><br><?php echo $row['field_date_time']; ?><strong></strong></td>
                 
                  <td  >                   

                     <i class="fa fa-download"></i> 
                     <a href="?module=backupdatabase&file=<?=$row['field_file_name']?>">Download</a>            
                  
                  </td> 
                        <!-- <div class="center"><div class="loader"></div></div>               -->
                </tr>          
                <?php 
                  
                  }
                ?>
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


