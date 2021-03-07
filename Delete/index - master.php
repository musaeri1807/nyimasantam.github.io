<?php

// echo $_SERVER['SERVER_PORT'];

// die();
//ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");
//require_once 'connection.php';
session_start();
if(isset($_SESSION["administrator_login"]))	//admin_login//check condition user login not direct back to index.php page
{
	header("location: administrator/dashboard?module=home");
}
if(isset($_SESSION["supervisor_login"]))	//admin_login//check condition user login not direct back to index.php page
{
	header("location: supervisor/dashboard.php?module=home");
}
if(isset($_SESSION["officer_login"]))	//admin_login//check condition user login not direct back to index.php page
{
	header("location: officer/officer_home.php");
}

if(isset($_REQUEST['btn_login']))	//button name is "btn_login" 
{
	$username	=strip_tags($_REQUEST["txt_username"]);	//textbox name "txt_username"
	$email		=strip_tags($_REQUEST["txt_username"]);	//textbox name "txt_username"
	$password	=strip_tags($_REQUEST["txt_password"]);	//textbox name "txt_password"
	$loglogin	=date('Y-m-d h:i:s');
	$ipaddress 	= $_SERVER['REMOTE_ADDR'];
	$satu 		= "1";

	   	//$username = $_POST['username'];

    $secretKey = "6LfJec4ZAAAAACG1-fmobe88erF72OdXbAFN71jj"; //local
    //$secretKey = "6Lf6eR0aAAAAABFKOeUrFysV3fvrrWcoTayg3R2j"; //nyimasantam.com
    //$secretKey = "6Ldi1lsaAAAAAELsOlpS__1jUbNTuXv0bbjhpD6L"; //urunanmu.my.id
    
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $response1 = file_get_contents($url);
    $response = json_decode($response1);
 	if ($responseKey==0) {
    	$errorMsg[]="Harap Periksa reCAPTCHA";
    }

    if ($response->success) {		

	if(empty($username)){
		$errorMsg[]="Silakan Memasukan Akun Username Or Email";	//check "username/email" textbox not empty 
	}
	else if(empty($email)){
		$errorMsg[]="Silakan Memasukan Akun Username Or Email";	//check "username/email" textbox not empty 
	}
	else if(empty($password)){
		$errorMsg[]="Silakan Memasukan Password";	//check "passowrd" textbox not empty 
	}
	else
	{
		try
		{
			$select_stmt=$db->prepare('SELECT * FROM tblemployeeslogin WHERE field_email=:uemail OR field_username=:uname '); //sql select query
			$select_stmt->execute(array(':uemail'=>$email,':uname'=>$username ));	//execute query with bind parameter
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);		
			$data=$select_stmt->rowCount();			
			if($select_stmt->rowCount() > 0)	//check condition database record greater zero after continue
			{

				if($satu==$row["field_status_aktif"] OR $email==$row["field_email"] AND $username==$row["field_username"] ) //check condition user taypable "username or email" are both match from database "username or email" after continue
				{
					if(password_verify($password, $row["field_password"])) //check condition user taypable "password" are match from database "password" using password_verify() after continue
					{
						$update_stmt=$db->prepare("UPDATE tblemployeeslogin SET field_log=:loglogin, field_ipaddress=:addresip WHERE field_email=:uemail OR field_username=:uname ");
						  // execute the query
						$update_stmt->execute(array(':uname'	=>	$username,
													':uemail'	=>	$email,
													':loglogin'	=>	$loglogin,
													':addresip'	=>	$ipaddress
													));							

						// session_start();
						// $_SESSION["user_login"] 		= $row["field_user_id"];	//session name is "user_login"
						// $_SESSION["login_member_id"] 	= $row["field_member_id"];	//session name is "login_member_id"
						// $_SESSION["last_login_time"] 	= time();
						// $loginMsg 						= "Successfully Login...";		//user login success message
						// //header('location:loading');	//refresh 2 second after redirect to "welcome.php" page
						// echo '<META HTTP-EQUIV="Refresh" Content="2; URL=https://nyimasantam.com/loading">';
						// 'Superadmin','Administrator','Supervisor','Officer'

						switch ($row["field_role"]) 
						{
						case 'ADM':
							$_SESSION["administrator"]=$row["field_role"];
							$_SESSION["administrator_id"]=$row["field_user_id"];
							$_SESSION["administrator_login"]=$row["field_email"];
							$loginMsg="Administrator..Successfully Login";
							//header("refresh:1;../../superadmin/superadmin_home.php");
							echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/administrator/dashboard?module=home">';
							//echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://urunanmu.my.id/administrator/dashboard?module=home">';
							break;
						case 'SPV':
							$_SESSION["supervisor"]=$row["field_role"];
							$_SESSION["supervisor_id"]=$row["field_user_id"];
							$_SESSION["supervisor_login"]=$row["field_email"];
							$_SESSION["supervisor_cabang"]=$row["field_branch"];
							$loginMsg="Supervisor..Successfully Login";
							//header("refresh:1;../../admin/admin_home.php");
							echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/supervisor/dashboard.php?module=home">';

							break;
						case 'CSM':
							$_SESSION["officer_id"]=$row["field_user_id"];
							$_SESSION["officer_login"]=$row["field_email"];
							$_SESSION["officer_cabang"]=$row["field_branch"];
							$loginMsg="Officer..Successfully Login";
							//header("refresh:1;../../officer/officer_home.php");
							echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/Login-Register-PHP-PDO/officer/officer_home.php">';
								
							break;
											
						default:
						$errorMsg[]="Role Tidak Ada";
							//break;
						}
					}

					else

					{
						
						$NLock=1;
						$_SESSION['lock']=$_SESSION['lock']+$NLock;
						if ($_SESSION['lock']>=3) {		

							$update_stmt=$db->prepare("UPDATE tblemployeeslogin SET field_status_aktif=:status, field_blokir_status=:blokir WHERE field_email=:uemail OR field_username=:uname ");
						  	// execute the query
							$update_stmt->execute(array(':uname'	=>	$username,
														':uemail'	=>	$email,
														':status'	=>	"2",
														':blokir'	=>	"B"
														));

							$errorMsg[]="Akun Terkunci Silakan Hubungi Admin";
						}else{
							$errorMsg[]="Password Salah Percobaan Ke- ".$_SESSION['lock'];
							
						}						
						
						//........
					}
					//AND OR
				}
				else
				{
					$errorMsg[]="Akun Terkunci dan diblokir Silakan Hubungi Admin";
				}
				//1>0
			}
			else
			{
				$errorMsg[]="Akun Belum Terdaftar";
			}

			//try
		}
		catch(PDOException $e)
		{
			$e->getMessage();
		}

		//input		

	} 

} //google c

//$loginMsgCapcha = " Login...";

}

?>



<!DOCTYPE html>

<html>

<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<title>NYIMASANTAM</title>
<meta content="Silakan Login Dengan Aman" name="descriptison">
<meta content="Login nyimasantam" name="keywords">
		
<link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="icon">
<link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="apple-touch-icon">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="js/jquery-1.12.4-jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</head>

	<body>
	<nav class="navbar navbar-default navbar-static-top">

      <div class="container">

        <div class="navbar-header">

          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>

          </button>

          <!-- <a class="navbar-brand" href="home"></a> -->

        </div>

        <div id="navbar" class="navbar-collapse collapse">

          <!-- <ul class="nav navbar-nav">

            <li class="active"><a href="register">Daftar</a></li>

          </ul> -->

        </div><!--/.nav-collapse -->

      </div>

    </nav>

	

	<div  class="wrapper">

	<div class="container">		

		<div  class="col-lg-12">

		

		<?php
		if(isset($errorMsg))
		{
			foreach($errorMsg as $error)
			{
			?>
				<div class="alert alert-danger">
					<strong><?php echo $error; ?></strong>
				</div>
            <?php
			}
		}
		if(isset($loginMsg))
		{
		?>
			<div class="alert alert-success">
				<strong><?php echo $loginMsg; ?></strong>
			</div>
        <?php

		}

	

		?>   
			<center>
				<a href=""><img  src="https://nyimasantam.my.id/image/logonyimas.png" width="170" ></a>
				<h3 >Login Admin</h3>
			</center>			

			<form method="post" class="form-horizontal">

					

				<div class="form-group">
				<label class="col-sm-3 control-label">Username Or Email</label>
				<div class="col-sm-6">
				<input type="text" name="txt_username" class="form-control" placeholder="Masukan Username OR Email" />
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-3 control-label">Password</label>
				<div class="col-sm-6">
				<input type="password" name="txt_password" class="form-control" placeholder="Masukan Password" /> <br>
				<!-- local -->

				<div class="g-recaptcha" data-sitekey="6LfJec4ZAAAAAPYZt2c-p6gu37D6weYdI8Kw1LqA"></div>

				<!-- domain nyimasantam.com -->

				<!-- <div class="g-recaptcha" data-sitekey="6Lf6eR0aAAAAAAXiPck77ymXUnqtLYj1dvtlli1B"></div> -->

				<!-- domain urunanmu.my.id -->

				<!-- <div class="g-recaptcha" data-sitekey="6Ldi1lsaAAAAALAritGVdd7xOXdf_mglkssD9RjR"></div> -->
				
				</div>
				</div>
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit" name="btn_login" class="btn btn-success" value="Login">
				<a href="settingdatabase" type="submit" class="btn btn-primary">Connection</a>
				</div>
				</div>
				<div class="form-group">

				<div class="col-sm-offset-3 col-sm-9 m-t-15">					
				Anda tidak punya akun Sebagai Officer? <a href="#"><p class="text-info">Silakan Daftar Akun</p></a>		

				</div>

				</div>				

			</form>		

		</div>

	</div>	

	</div>

	<script src='https://www.google.com/recaptcha/api.js'></script>	

		 <!-- Vendor JS Files -->
	  <script src="assets/vendor/jquery/jquery.min.js"></script>
	  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
	  <script src="assets/vendor/php-email-form/validate.js"></script>
	  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
	  <script src="assets/vendor/counterup/jquery.counterup.min.js"></script>
	  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
	  <script src="assets/vendor/typed.js/typed.min.js"></script>
	  <script src="assets/vendor/venobox/venobox.min.js"></script>

	  <!-- Template Main JS File -->
	  <script src="assets/js/main.js"></script>					

	</body>

</html>