<?php
require_once '../connectionuser.php';

if ($_GET['module'] == "home") {
include "module/home.php";
}elseif ($_GET['module'] == "product") {
include "module/product.php";
}elseif ($_GET['module'] == "addproduct") {
include "module/addproduct.php";
}elseif ($_GET['module']=="updproduct") {
include "module/updproduct.php";
}elseif ($_GET['module']=="gold") {
include "module/gold.php";
}elseif ($_GET['module']=="addgold") {
include "module/addgold.php";
}

	
?>