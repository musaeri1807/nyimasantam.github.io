<?php
if ($_SERVER['SERVER_NAME']=='localhost') {
    # code...
    $db_host = file_get_contents("config/localhost_1.txt");
    $db_user = file_get_contents("config/users_1.txt");
    $db_pass = file_get_contents("config/password_1.txt");
    $db_name = file_get_contents("config/database_1.txt");
} else {
    $db_host = file_get_contents("config/localhost.txt");
    $db_user = file_get_contents("config/users.txt");
    $db_pass = file_get_contents("config/password.txt");
    $db_name = file_get_contents("config/database.txt");
}


try {    
    //create PDO connection 
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) 
{
    //show error
    die("Terjadi masalah: " . $e->getMessage());
    // $msg="Connection Failed ";
    // header('location:index.php');
}

?>
