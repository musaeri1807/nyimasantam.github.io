<?php
$mysqli = new mysqli('localhost', 'root', '', 'vps01na'); //sesuaikan dengan konfigurasi database kamu ya
if (mysqli_connect_error()) { 
die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
}

$id_kec = $_GET['id_kec'];
$sql = "SELECT * FROM tblwilayahdesa WHERE `field_kecamatan_id` = '$id_kec'";
$query = $mysqli->query($sql);
$data = array();
while($row = $query->fetch_array(MYSQLI_ASSOC)){
$data[] = array("id_kel" => $row['field_desa_id'], "nama" => $row['field_nama_desa']);
}
echo json_encode($data);?>