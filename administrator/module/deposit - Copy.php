  <section class="content">
    <div class="row">
      <section class="col-lg-12">       
        <div class="box box-info">
          <div class="box-body" style="padding: 20px">
            <form action="penjualan_act.php" method="post" >
            <div class="row">
            <input type="hidden" name="pelayanan" value="<?php echo $_SESSION['id']; ?>">
            <input type="hidden" class="form-control" required="required" value="<?php echo $_SESSION['nama']; ?>" readonly>
            <div class="col-lg-2">
            <div class="form-group">
            <label>No. Reff</label>
            <input type="text" class="form-control" name="nomor" required="required" placeholder="Masukkan Nomor Invoice" value="<?php echo $noReff; ?>" readonly>
            <textarea  readonly="readonly" placeholder="Rp 1.000.000,-" > Rp 1.000.000,-</textarea>
            </div>
            </div>
            <div class="col-lg-2">
            <div class="form-group">
            <label>Tanggal</label>
            <input type="date" class="form-control" name="tanggal" required="required" placeholder="Masukkan Tanggal Pembelian .. (Wajib)" value="<?php echo date('Y-m-d') ?>" readonly>
             </div>

              </div>

               <div class="col-lg-2">

                <div class="form-group">
                  <label>Sumber Dana</label>
                  <SELECT class="form-control" name="sumber_dana">                  
                  <option value="Investasi">---Investasi---</option>
                  <option value="Gaji">---Gaji---</option>
                  <option value="Sampah">---Sampah---</option>
                  <option value="lain">---Lainnya---</option>
                  
                  </SELECT>
                </div>
                </div>

                <div class="col-lg-2">
                <div class="form-group">
                  <label>Nama Nasabah</label>
                  <input type="hidden" class="form-control" id="member_id" name="member_id" required="required" placeholder="Masukkan member_id" readonly>
                  <input type="text" class="form-control" id="tambahkan_nama_nasabah" name="nama_nasabah" required="required" placeholder="Masukkan Nama Nasabah" readonly>
                </div>
                </div>
                <div class="col-lg-2">
                <div class="form-group">
                  <label>Rekening Nasabah</label>
                  <input type="text" class="form-control" max="100" id="tambahkan_kode_nasabah" name="nasabah" required="required" placeholder="Masukkan Rekening">
                </div>
                </div>

                <div class="col-lg-2">
                <div class="form-group">
                  
                  <!-- <button style="margin-top: 25px" type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalNasabah"><b>Cari</b> <span class="glyphicon glyphicon-search"></span></button> -->

                  <button style="margin-top: 27px" type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#myModalNasabah">
                    <i class="fa fa-search"></i> &nbsp Cari nasabah
                  </button>
                </div>

                </div>            
<!-- in A -->
            </div>

<!-- ............................................................................ -->
<!-- modal nasabah -->
                  <div class="modal fade" id="myModalNasabah" tabindex="-1" role="dialog" aria-labelledby="cariNasabahLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          Pilih Nasabah
                        </div>
                        <div class="modal-body">


                          <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="table-datatable-nasabah">
                              <thead>
                                <tr>
                                  <th class="text-center">No</th>
                                  <th style="text-align: center">Rekening</th>
                                  <th style="text-align: center">Member Id</th>
                                  <th style="text-align: center">Nama</th>                               
                                  <th style="text-align: center">Pilih</th>
                                </tr>
                              </thead>
                              <tbody>
                           <!--      <?php 
                                $no=1;
                                $data = mysqli_query($koneksi,"SELECT * FROM tblnasabah ");
                                while($d = mysqli_fetch_array($data)){
                                  ?> -->
                                  <tr>
                                  <td width="1%" class="text-center"><?php echo $no++; ?></td>
                                  <td width="10%" class="text-center"><?php echo $d['field_rekening']; ?></td>
                                  <td width="10%"class="text-center" ><?php echo $d['field_handphone']; ?></td>
                                  <td width="10%" class="text-center"><?php echo $d['field_nama']; ?></td>
                                  <td width="1%">                                                  
                                  <?php                                 
                                      if($d['field_nasabah_id'] > 0){
                                  ?> 
                                  <!-- javascrip          -->
                                  <input type="hidden" id="kode_<?php echo $d['field_nasabah_id']; ?>" value="<?php echo $d['field_rekening']; ?>">
                                  <input type="hidden" id="nama_<?php echo $d['field_nasabah_id']; ?>" value="<?php echo $d['field_nama']; ?>">
                                  <input type="hidden" id="member_<?php echo $d['field_nasabah_id']; ?>" value="<?php echo $d['field_member_id']; ?>">
                                  <button type="button" class="btn btn-success btn-sm modal-pilih-nasabah" id="<?php echo $d['field_nasabah_id']; ?>" data-dismiss="modal">Pilih Nasabah</button>

                                       <!--    <?php 
                                        }
                                        ?> -->

                                    </td>
                                  </tr>

                              <!--   <?php 
                                }
                                ?> -->
                              </tbody>
                            </table>

                            </div>
                          </div>
                          </div>
                          </div>
                          </div>
<hr>

<!-- ......................................................................................... -->


            <div class="row">

              <div class="col-lg-3">
                
                <div class="form-group">
                <span class="btn btn-sm btn-primary pull-right btn-block" id="tombol-tambahkan">TAMBAHKAN*</span>
              </div>

                <div class="row">

                 <div class="form-group col-lg-7">
                  <label></label>
                  <input type="hidden" class="form-control" id="tambahkan_id">
                  <input type="text" class="form-control" id="tambahkan_kode" placeholder="Masukkan Kode Produk ..">
                </div>

                <div class="col-lg-5">

                  <button style="margin-top: 24px" type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#cariProduk">
                    <i class="fa fa-search"></i> &nbsp Cari
                  </button>

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
                            <table class="table table-bordered table-striped table-hover" id="table-datatable-produk">
                              <thead>
                                <tr>
                                  <th class="text-center">NO</th>
                                  <th>KODE</th>
                                  <th>PRODUK</th>
                                  <th class="text-center">SATUAN</th>
                                  <!-- <th class="text-center">STOK</th> -->
                                  <th class="text-center">HARGA JUAL</th>
                                  <th>KETERANGAN</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>

                               <!--  <?php 
                                $no=1;
                                $data = mysqli_query($koneksi,"SELECT * FROM produk, kategori where produk_kategori=kategori_id order by produk_id desc");
                                while($d = mysqli_fetch_array($data)){
                                  ?> -->
                                  <tr>
                                    <td width="1%" class="text-center"><?php echo $no++; ?></td>
                                    <td width="1%"><?php echo $d['produk_kode']; ?></td>
                                    <td>
                                      <?php echo $d['produk_nama']; ?>
                                      <br>
                                      <small class="text-muted"><?php echo $d['kategori']; ?></small>
                                    </td>
                                    <td width="1%" class="text-center"><?php echo $d['produk_satuan']; ?></td>
                                    <!-- <td width="1%" class="text-center"><?php //echo $d['produk_stok']; ?></td> -->
                                    <td width="20%" class="text-center"><?php echo "Rp.".number_format($d['produk_harga_jual']).",-"; ?></td>
                                    <td width="15%"><?php echo $d['produk_keterangan']; ?></td>
                                    <td width="1%">
                                                  
                                      <?php 
                                      // if($d['produk_stok'] > 0){
                                      if($d['produk_id'] > 0){
                                        ?> 
              <!-- javascrip          -->
<input type="hidden" id="kode_<?php echo $d['produk_id']; ?>" value="<?php echo $d['produk_kode']; ?>">
<input type="hidden" id="nama_<?php echo $d['produk_id']; ?>" value="<?php echo $d['produk_nama']; ?>">
<input type="hidden" id="harga_<?php echo $d['produk_id']; ?>" value="<?php echo $d['produk_harga_jual']; ?>">
<button type="button" class="btn btn-success btn-sm modal-pilih-produk" id="<?php echo $d['produk_id']; ?>" data-dismiss="modal">Pilih</button>


                                    <!--     <?php 
                                      }
                                      ?> -->
                                    </td>
                                  </tr>

                              <!--     <?php 
                                }
                                ?> -->
                              </tbody>
                            </table>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>


              <div class="form-group">
                <label>Produk</label>
                <input type="text" class="form-control" id="tambahkan_nama" disabled>
              </div>

              <div class="form-group">
                <label>Harga*</label>
                <input type="text" class="form-control" id="tambahkan_harga" disabled>
              </div>

              <div class="form-group">
                <label>Jumlah</label>
                <input type="number" class="form-control" id="tambahkan_jumlah" min="1">
              </div>

              <div class="form-group">
                <label>Total*</label>
                <input type="text" class="form-control" id="tambahkan_total" disabled>
              </div>

              

            </div>
<!-- ..................................................... -->

            <div class="col-lg-9">

              <!-- <h3>Daftar</h3> -->

              <table class="table table-bordered table-striped table-hover" id="table-pembelian">
                <thead>
                  <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th style="text-align: center;">Harga**</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Total** </th>
                    <th style="text-align: center;" width="1%">OPSI</th>
                  </tr>
                </thead>
                <tbody> 
                </tbody>
                <tfoot>
                  <tr class="bg-info">
                    <td style="text-align: right;" colspan="2"><b>**Total**</b></td>
                    <td style="text-align: center;"><span class="pembelian_harga" id="0">Rp.0,-</span></td>
                    <td style="text-align: center;"><span class="pembelian_jumlah" id="0">0</span></td>
                    <td style="text-align: center;"><span class="pembelian_total" id="0">Rp.0,-</span></td>
                    <td style="text-align: center;">****</td>
                  </tr>
                </tfoot>
              </table>

                  <div class="row">
                  <div class="col-lg-6">
                  <table class="table table-bordered table-striped">
                  <tr>
                  <th width="40%">Sub Total</th>
                  <td>
                  <input type="hidden" name="sub_total" class="sub_total_form" value="0">
                  <span class="sub_total_pembelian" id="0">Rp.0,-</span>
                  </td>
                  </tr>
                  <tr>
                  <th>Operation Fee</th>
                  <td>
                  <div class="row">
                  <div class="col-lg-7">
                  <input class="form-control operation_free" type="number" min="0" max="100" id="0" name="operation_free" placeholder="Wajib di Input" required="required">
                            <!-- <span class="operation_free_2"  id="operation_free_2" ></span> -->
                  </div>
                          <br>
                          <div class="col-1" >%
                            <input type="hidden" name="operation_free_rp" id="operation_free_rp"></div>
                        </div>
                      </td>
                    </tr>
                <tr>
                <th>Total</th>
                <td>
                <input type="hidden" name="total" class="total_form" value="0">
                <span class="total_pembelian" id="0">Rp.0,-</span>
                </td>
                </tr>
                </table>
                </div>

 <!-- ..................................................  -->
                <div class="row">
                <div class="col-lg-6">
                <table class="table table-bordered table-striped">
                <tr>
                <th width="50%">Sub Total</th>
                <td>
                <input type="hidden" name="sub_total" class="sub_total_form" value="0">
                <span class="sub_total_pembelian" id="0">Rp.0,-</span>
                </td>
                </tr>
                <tr>
                <th>Harga hari ini** 
                <span>
                <br> <b></b> 
                </span></th>
                <td>
                <div class="row">
                <div class="col-lg-10">
                <input type="hidden" class="form-control harga_emas" id="harga_emas" name="harga_emas" value="" readonly>
                <span></span>
                </div>
                <div>**</div>
                </div>
                </td>
                </tr>
                <tr>
                <th>Emas Anda</th>
                <td>
                <input style="border-style: none;" type="number" name="emas_anda" class="emas_anda" id="emas_anda"  placeholder="Emas Anda" readonly>
                <span class="emas_anda" name="emas_anda" readonly> </span>
                </td>
                </tr>
                </table>
                </div>
                </div>  
                <div class="form-group">
                <a href="penjualan_tambah.php" class="btn btn-danger"><i class="fa fa-close"></i> Batalkan Transaksi</a>
                <button class="btn btn-success pull-right"><i class="fa fa-check"></i> Buat Transaksi</button>
                </div>  
                </div>
                </div>
                </div>
                </form>
                </div>

    </div>
  </section>
</div>
</section>

