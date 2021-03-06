<?php

$db_host = file_get_contents("../config/localhost.txt");
$db_user = file_get_contents("../config/users.txt");
$db_pass = file_get_contents("../config/password.txt");
$db_name = file_get_contents("../config/database.txt");

$connection = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

?>
