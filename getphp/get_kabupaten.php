<?php
require_once("../config/connection.php");
$id_prov = $_GET['id_prov'];
$data = array();                          
$select_stmt = $db->prepare("SELECT * FROM tblwilayahkabupaten WHERE `field_provinsi_id` = '$id_prov'");
$select_stmt->execute(array());  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

while($row=$select_stmt->fetch(PDO::FETCH_ASSOC)){
    $data[] = array("id_kab" => $row['field_kabupaten_id'], "nama" => $row['field_nama_kabupaten']);
}

echo json_encode($data);



?>

