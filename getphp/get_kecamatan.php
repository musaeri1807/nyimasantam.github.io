<?php
$mysqli = new mysqli('localhost', 'root', '', 'vps01na'); //sesuaikan dengan konfigurasi database kamu ya
if (mysqli_connect_error()) { 
die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
}

$id_kab = $_GET['id_kab'];
$sql = "SELECT * FROM tblwilayahkecamatan WHERE `field_kabupaten_id` = '$id_kab'";
$query = $mysqli->query($sql);
$data = array();
while($row = $query->fetch_array(MYSQLI_ASSOC)){
$data[] = array("id_kec" => $row['field_kecamatan_id'], "nama" => $row['field_nama_kecamatan']);
}
echo json_encode($data);?>