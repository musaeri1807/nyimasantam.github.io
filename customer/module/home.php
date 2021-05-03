



<!-- Content Header (Page header) -->

     <section  class="content-header">
      <div class="row box-footer">
        <div class="col-lg-4">

          <div class="icon">   

          <h3 class=""> <?php echo $result['field_nama'];?></h3></div>
          <h3 class=""><?php echo $resultsaldo['field_rekening'];?>| <?php echo $result['field_email'];?></h3>
          <h5 class=""><strong><?php echo $result['field_branch_name'];?></strong></h5>
          <br> 
          
          <?php 

              if ($result['field_status_aktif']==0) {
                 echo '<div type="button" class="btn btn-danger">Belum Verifikasi</div><a href="profile.php" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i> Info Detail </a> ';
              }elseif ($result['field_status_aktif']==1) {
                echo '<div type="button" class="btn btn-warning">UnVerifikasi</div><a href="profile.php" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i> Info Detail </a> ';
              }elseif ($result['field_status_aktif']==2 ){
                echo '<div type="button" class="btn btn-success">Ter-Verifikasi</div>';
              }       

          ?>        

       

        <br> 

        </div>

        <br>

        <div class="col-lg-8">
        <div class="col-lg-6">

          <!-- small box -->            

          <div class="">

            <div class="inner">

              

              <h4 class="btn-warning">Harga Emas <?php echo date("d-F-Y",strtotime($ResultEmas['field_date_gold'])); ?></h4>
              <div class="col-lg-12">

              <h4>Beli&nbsp&nbsp<i class="fa fa-money"></i> <?php echo rupiah($ResultEmas['field_sell']) ; ?></h4>

              <h4>Jual&nbsp<i class="fa fa-money"></i> <?php echo rupiah($ResultEmas['field_buyback']); ?></h4>

              </div>

              <!-- <div class="col-lg-6"> -->

              <?php  

              if ($HargaKemarin>$HargaTerkini) {

                

                echo  '<h4> <i class="fa fa-arrow-down btn-danger"> </i>' ." " .rupiah($Selisi).'</h4>';

              }elseif ($HargaKemarin<$HargaTerkini) {

                

                echo  '<h4> <i class="fa fa-arrow-up btn-success"> </i>'." " .rupiah($Selisi).'</h4>';

              }elseif ($HargaKemarin==$HargaTerkini) {

                

                echo  '<h4> <i class="fa fa-arrows-h btn-warning"> </i>'." " .rupiah($Selisi).'</h4>';

              }

              ?>

<!-- 

              </div> -->

             

            </div>

            

            <div class=""> 

          

            </div>            

          </div>          

        </div>

        <div class="col-lg-6">

          <!-- small box -->

           

          <div class="small-box bg-aqua">

            <div class="inner">Saldo

              <h3><center><?php echo $resultsaldo['field_total_saldo']; ?>-g</center> </h3>

              <p>Setara <?php echo rupiah ($Rupiah);?>,- </p>

              

            </div>

           <!--  <div class="icon">

              <i class="ion ion-stats-bars"></i>

            </div> -->

            <a href="mutasilaporan" class="small-box-footer">Info Mutasi<i class="fa fa-arrow-circle-right"></i></a>

          </div>

        </div>

        </div>

     <!--  </div> -->

        

<!--         </section>

        <hr >



<section class="content"> -->

      <div class="row">

        <div class="col-xs-12"><hr> 

          <!-- <div class="box"> -->

            <div class="">

            <div class="box-header">



              <h3 class="box-title">5 Transaksi Terakhir</h3>

            </div>

            <!-- /.box-header -->

            <div class="box-body">

              <table id="trxTerakhir" class="table table-bordered table-hover">

                <thead>

                <tr>

                  <th width="20">Action</th>              

                  <th>No Trx</th>                 

                  <th>Amount</th>                  

                  

            

                </tr>

                </thead>

                <tbody>

                  <?php 

                  foreach($ResultOrder as $row) {

                    $status = $row["field_status"];

                    //$tindakan= $row["field_order_id"];

                    if($status  =="Pending"){

                            $status = '<span class="badge btn-danger text-white">Menunggu</span>';

                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-danger "><i class="fa fa-download"></i> Detail</a> &nbsp';     

                            $tindakan='';

                    }elseif($status =="Success"){

                            $status   = '<span class="badge btn-success text-white">Success</span>';

                            

                            $tindakan='';

                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-success "><i class="fa fa-download"></i> Detail</a> &nbsp';

                    }elseif($status =="Proses") {

                            $status ='<span class="badge btn-info text-white">Proses</span>';

                           //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-info "><i class="fa fa-download"></i> Detail</a> &nbsp';

                           $tindakan='';

                    }elseif($status =="Cancel") {

                            $status ='<span class="badge btn-dark text-white">Cancel</span>';

                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-danger "><i class="fa fa-download"></i> Detail</a> &nbsp';

                            $tindakan='';

                    }elseif($status =="Ready") {

                            $status ='<span class="badge btn-warning text-white">Ready</span>';

                            //$tindakan = '<a href="detail.php?trx_id='.encrypt($row["field_order_id"]).'" class="text-white btn btn-warning "><i class="fa fa-download"></i> Detail</a> &nbsp';

                            $tindakan='';

                             

                    }





                    $Types = $row["field_type_order"];

                    if($Types=="1000"){

                    $Types = '<span class="badge btn-warning text-white">(-) Cetak Emas</span>';                                         

                    }elseif($Types=="2000"){

                    $Types = '<span class="badge btn-info text-white">(-) Buyback</span>';                        

                    }elseif ($Types=="4000") {

                      # code...

                      $Types = '<span class="badge btn-primary text-white">(+) Deposit Rp</span>';

                    }elseif ($Types=="5000") {

                      # code...

                      $Types = '<span class="badge btn-success text-white">(+) Deposit Emas</span>';

                    }



                ?>

             

                <tr>

                  <td data-title="Aksi"><?php echo $status;?>&nbsp<br>&nbsp<?php echo $tindakan;?></td>                  

                  

                  <td data-title="Trx Id"><?php echo $row["field_order_id"] ?>|<?php echo $row["field_tanggal_order"];?><br><strong><?php echo $row["field_trx_id"];?></strong></td>                  

                            <?php                            

                            if ($row["field_type_order"]==1000) {

                            echo '<td><strong>'.$row["field_gold"].'-g</strong><br><small>Qty='.$row["field_quantity"].'</small><br>'.$Types.'</td>';                             

                            }elseif ($row["field_type_order"]==2000) {

                            echo '<td><strong>'.rupiah($row["field_price_gold"]).'</strong><br>'.'---'.'<br>'.$Types.'</td>';

                            }elseif ($row["field_type_order"]==4000) {

                            echo '<td><strong>'.rupiah($row["field_price_gold"]).'</strong><br>'.'---'.'<br>'.$Types.'</td>';

                            }elseif ($row["field_type_order"]==5000) {

                             echo '<td><strong>'.$row["field_gold"].'-g</strong><br>'.'---'.'<br>'.$Types.'</td>';

                            }

                            ?>                 

                </tr>

                

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



    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->