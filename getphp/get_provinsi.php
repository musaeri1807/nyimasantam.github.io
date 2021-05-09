<?php
require_once("../config/connection.php");
$data = array();                          
$select_stmt = $db->prepare("SELECT * FROM tblwilayahprovinsi ");
$select_stmt->execute(array());  
$rows=$select_stmt->fetch(PDO::FETCH_ASSOC);

while($row=$select_stmt->fetch(PDO::FETCH_ASSOC)){
    $data[] = array("id_prov" => $row['field_provinsi_id'], "nama" => $row['field_nama_provinsi']);
}

echo json_encode($data);


?>