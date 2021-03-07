<?php
$svrname=$_SERVER['SERVER_NAME'];
// echo $svrname;
// die();

if ($_SERVER['SERVER_NAME']=='localhost') {
               
  echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/loginv2">';
}else{
  echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://'.$svrname.'/settingdatabase';
}

?>



