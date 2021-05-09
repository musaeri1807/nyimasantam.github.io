<?php
require_once("../config/connection.php");
$id_kab = $_GET['id_kab'];
$data = array();                          
$select_stmt = $db->prepare("SELECT * FROM tblwilayahkecamatan WHERE `field_kabupaten_id` = '$id_kab'");
$select_stmt->execute(array());  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

while($row=$select_stmt->fetch(PDO::FETCH_ASSOC)){
    $data[] = array("id_kec" => $row['field_kecamatan_id'], "nama" => $row['field_nama_kecamatan']);
}

echo json_encode($data);


?>