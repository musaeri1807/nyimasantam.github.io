<?php


$mysqli = new mysqli('localhost', 'root', '', 'vps01na'); //sesuaikan dengan konfigurasi database kamu ya
if (mysqli_connect_error()) { 
die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
}

$id_prov = $_GET['id_prov'];
$sql = "SELECT * FROM tblwilayahkabupaten WHERE `field_provinsi_id` = '$id_prov'";
$query = $mysqli->query($sql);
$data = array();
while($row = $query->fetch_array(MYSQLI_ASSOC)){
$data[] = array("id_kab" => $row['field_kabupaten_id'], "nama" => $row['field_nama_kabupaten']);
}
echo json_encode($data);?>