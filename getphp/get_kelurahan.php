<?php
require_once("../config/connection.php");
$id_kec = $_GET['id_kec'];
$data = array();                          
$select_stmt = $db->prepare("SELECT * FROM tblwilayahdesa WHERE `field_kecamatan_id` = '$id_kec'");
$select_stmt->execute(array());  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

while($row=$select_stmt->fetch(PDO::FETCH_ASSOC)){
    $data[] = array("id_kel" => $row['field_desa_id'], "nama" => $row['field_nama_desa']);
}

echo json_encode($data);


?>