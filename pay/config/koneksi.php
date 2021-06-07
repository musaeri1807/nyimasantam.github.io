<?php

if ($_SERVER['SERVER_NAME']=='localhost') {
    # code...
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "dbcrudoop";
} else {
    # code...
    $db_host = "localhost";
    $db_user = "musx1236_musaeri";
    $db_pass = "P@55w.rdmusaeri.123#";
    $db_name = "musx1236_VPS01NA";
}



try {    
    //create PDO connection 
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch(PDOException $e) {
    //show error
    die("Terjadi masalah: " . $e->getMessage());
}

