<?php

$db_host = file_get_contents("../config/localhost.txt");
$db_user = file_get_contents("../config/users.txt");
$db_password = file_get_contents("../config/password.txt");
$db_name = file_get_contents("../config/database.txt");

try
{
	$db=new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOEXCEPTION $e)
{
	$e->getMessage();
}

?>



