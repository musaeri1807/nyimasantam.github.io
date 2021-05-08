<?php
// include('../config.php');

$mysqli = new mysqli('localhost', 'root', '', 'vps01na'); //sesuaikan dengan konfigurasi database kamu ya
if (mysqli_connect_error()) { 
die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
}

$sql = "SELECT * FROM tblwilayahprovinsi";
$query = $mysqli->query($sql);
$data = array();
while($row = $query->fetch_array(MYSQLI_ASSOC)){
$data[] = array("id_prov" => $row['field_provinsi_id'], "nama" => $row['field_nama_provinsi']);
}
echo json_encode($data);?>