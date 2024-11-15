<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if (!isset($_SESSION['userlogin'])) {
	header("location: ../index.php");
}

if (isset($_REQUEST['btn_insert2'])) {
	$category = $_REQUEST['txt_tambahkategori'];
	$typetrash = $_REQUEST['txt_trash'];

	if (empty($category)) {
		$errorMsg = "Silakan Masukkan Category";
	} elseif ($typetrash == "Pilih") {
		$errorMsg = "Silakan Masukkan Type";
	} else {
		try {
			if (!isset($errorMsg)) {
				$insert_stmt = $db->prepare('INSERT INTO tblcategory (field_category,field_type_product) 
														VALUES(:category,:typetrash)'); //sql insert query					
				$insert_stmt->bindParam(':category', $category);
				$insert_stmt->bindParam(':typetrash', $typetrash);


				if ($insert_stmt->execute()) {
					$insertMsg = "Insert Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1">';
				}
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}


// mencari kode barang dengan nilai paling besar

$sql	= "SELECT max(field_product_code) AS maxKode FROM tblproduct";
$stmt   = $db->prepare($sql);
$stmt->execute();
$row    = $stmt->fetch(PDO::FETCH_ASSOC);


$kodeProduk = $row['maxKode'];
// mengambil angka atau bilangan dalam kode produk terbesar,
// dengan cara mengambil substring mulai dari karakter ke-1 diambil 6 karakter
// misal 'BRG001', akan diambil '001'
// setelah substring bilangan diambil lantas dicasting menjadi integer
$noUrut = substr($kodeProduk, 4, 3);
// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$noUrut++;
// membentuk kode produk baru
// perintah sprintf("%03s", $noUrut); digunakan untuk memformat string sebanyak 3 karakter
// misal sprintf("%03s", 12); maka akan dihasilkan '012'
// atau misal sprintf("%03s", 1); maka akan dihasilkan string '001'
$char = "PROD";
$kodeProduk = $char . sprintf("%03s", $noUrut);

$Sql = "SELECT * FROM tblcategory";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$resultKategori = $Stmt->fetchAll();


if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {
	$Sql = "SELECT * FROM tblbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute();
	$result = $Stmt->fetchAll();
} else {
	$Sql = "SELECT * FROM tblbranch WHERE field_branch_id=:idbranch";
	$Stmt = $db->prepare($Sql);
	$Stmt->execute(array(":idbranch" => $branchid));
	$result = $Stmt->fetchAll();
}
//extract($row);                

if (isset($_REQUEST['btn_insert'])) {

	$kodeproduk	= $_REQUEST['txt_kodeproduk'];	//textbox name "txt_firstname"
	$namaproduk	= $_REQUEST['txt_nama'];	//textbox name "txt_lastname"
	$hargaproduk = $_REQUEST['txt_harga'];
	$beratsampah = $_REQUEST['txt_berat'];
	$datetime		= date('Y-m-d H:i:s');
	$date			= date('Y-m-d');
	$kategori	= $_REQUEST['txt_kategori'];
	$cabang		= $_REQUEST['txt_cabang'];
	$Keterangan = $_REQUEST['txt_keterangan'];
	$officer 	= $_SESSION['idlogin'];


	// echo $cabang;
	// echo "<br>";
	// echo $kategori;
	// echo "<br>";
	// echo $beratsampah;
	// die();

	if (empty($namaproduk)) {
		$errorMsg = "Silakan Masukkan Nama Produk Sampah";
	} else if (empty($hargaproduk)) {
		$errorMsg = "Silakan Masukkan Harga Sampah";
	} elseif (strlen(is_numeric($hargaproduk)) == 0) {
		$errorMsg = "Silakan Masukkan Harga Benar";
	} elseif ($beratsampah == "Pilih") {
		$errorMsg = "Silakan Pilih Jenis Berat Sampah";
	} elseif ($kategori == "Pilih") {
		$errorMsg = "Silakan Pilih Kategori Sampah";
	} elseif ($cabang == "Pilih") {
		$errorMsg = "Silakan Pilih Kantor Cabang";
	} elseif (empty($Keterangan)) {
		$errorMsg = "Silakan Masukan Keterangan Sampah";
	} else {
		try {
			if (!isset($errorMsg)) {
				$insert_stmt = $db->prepare('INSERT INTO tblproduct 
													(field_product_code,
													 field_product_name,
													 field_unit,
													 field_date_price,
													 field_date,
													 field_category,
													 field_branch,
													 field_price,
													 field_note,
													 field_officer_id) 
											VALUES  (:kodeproduk,
													 :namaproduk,
													 :beratsampah,
													 :udatetime,
													 :udate,
													 :kategori,
													 :cabang,
													 :hargaproduk,
													 :Keterangan,
													 :petugas)');

				$insert_stmt->bindParam(':kodeproduk', $kodeproduk);
				$insert_stmt->bindParam(':namaproduk', $namaproduk);
				$insert_stmt->bindParam(':beratsampah', $beratsampah);
				$insert_stmt->bindParam(':udatetime', $datetime);
				$insert_stmt->bindParam(':udate', $date);
				$insert_stmt->bindParam(':kategori', $kategori);
				$insert_stmt->bindParam(':cabang', $cabang);
				$insert_stmt->bindParam(':hargaproduk', $hargaproduk);
				$insert_stmt->bindParam(':Keterangan', $Keterangan);
				$insert_stmt->bindParam(':petugas', $officer);

				if ($insert_stmt->execute()) {
					$insertMsg = "Insert Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1;">';
				}
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}

?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<i class="fa fa-edit"></i>
					<h3 class="box-title">Insert Product Price</h3>

				</div>
				<!-- Content -->
				<?php
				if (isset($errorMsg)) {
					echo '<div class="alert alert-danger"><strong>WRONG !' . $errorMsg . '</strong></div>';
				}
				if (isset($insertMsg)) {
					echo '<div class="alert alert-success"><strong>SUCCESS !' . $insertMsg . '</strong></div>';
				}
				?>

				<div class="box-body">
					<form method="post" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-3 control-label">Kode</label>
							<div class="col-sm-3">
								<input type="text" name="txt_kodeproduk" class="form-control" value="<?php echo $kodeProduk; ?>" readonly />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Name Sampah</label>
							<div class="col-sm-6">
								<input type="text" name="txt_nama" class="form-control" placeholder="Masukkan Nama Produk" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Harga</label>
							<div class="row">
								<div class="col-sm-3">
									<input type="text" name="txt_harga" class="form-control" placeholder="Masukkan Harga" />
								</div>
								<div class="col-sm-2">
									<select class="form-control" name="txt_berat">
										<option>Pilih</option>
										<option value="Kg">Kg</option>
										<option value="Rp">Rp</option>
										<option value="Pcs">Pcs</option>
										<option value="Liter">Liter</option>
									</select>

								</div>
							</div>

						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Kategori</label>
							<div class="col-sm-2">
								<select class="form-control" type="text" name="txt_kategori">
									<option>Pilih</option>
									<?php foreach ($resultKategori as $rows) { ?>
										<option value="<?php echo $rows['field_category_id']; ?>"><?php echo $rows['field_category_id'] . "-";
																									echo $rows['field_name_category']; ?></option>
									<?php } ?>
								</select>
							</div>

						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Catatan</label>
							<div class="col-sm-6">
								<textarea class="form-control" name="txt_keterangan" placeholder="Masukkan Keterangan Produk."></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Kantor Cabang</label>
							<div class="col-sm-6">
								<select class="form-control" type="text" name="txt_cabang">
									<option>Pilih</option>
									<?php foreach ($result as $branch) { ?>
										<option value="<?php echo $branch['field_branch_id']; ?>"><?php echo $branch['field_branch_name'] . "-";
																									echo $branch['field_branch_id']; ?></option>

									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 m-t-15">
								<input type="submit" name="btn_insert" class="btn btn-success " value="Simpan">
								<a href="?module=product" class="btn btn-danger">Batal</a>
							</div>
						</div>

					</form>
					<!-- Modal -->
					<div class="modal fade" id="modal-default">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Add Category</h4>
								</div>
								<div class="modal-body">
									<form method="post" class="form-horizontal">
										<div class="form-group">
											<div class="box-header">
												<input type="text" name="txt_tambahkategori" class="form-control" placeholder="Masukkan Nama Category" />
											</div>
											<div class="box-header">
												<select class="form-control" name="txt_trash">
													<option>Pilih</option>
													<option value="Anorganic">Anorganic</option>
													<option value="Organic">Organic</option>
													<option value="B3">B3</option>
													<option value="Rupiah">Rupiah</option>
												</select>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
											<input type="submit" name="btn_insert2" class="btn btn-success " value="Insert">
										</div>
									</form>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->

					<!-- form -->
				</div>
			</div>
		</div>
	</div>
</section>