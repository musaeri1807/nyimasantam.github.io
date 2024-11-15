<?php
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");
//require_once 'connection.php';
session_start();
if(isset($_SESSION["user_login"]))	//check condition user login not direct back to index.php page
{
	header("location: location: customer/main?module=home");
}
if(isset($_REQUEST['btn_login']))	//button name is "btn_login" 
{
	$username	=strip_tags($_REQUEST["txt_username"]);	//textbox name "txt_username"
	$email		=strip_tags($_REQUEST["txt_username"]);	//textbox name "txt_username"
	$password	=strip_tags($_REQUEST["txt_password"]);	//textbox name "txt_password"
	$loglogin	=date('Y-m-d h:i:s');
	$ipaddress 	= $_SERVER['REMOTE_ADDR'];
	$satu 		= "1";

	if ($_SERVER['SERVER_NAME']=='localhost') {
		$secretKey = "6LfJec4ZAAAAACG1-fmobe88erF72OdXbAFN71jj"; //local        
	}elseif ($_SERVER['SERVER_NAME']=='urunanmu.my.id') {
		$secretKey = "6Ldi1lsaAAAAAELsOlpS__1jUbNTuXv0bbjhpD6L"; //urunanmu.my.id
	}elseif ($_SERVER['SERVER_NAME']=='nyimasantam.com') {
		$secretKey = "6Lf6eR0aAAAAABFKOeUrFysV3fvrrWcoTayg3R2j"; //nyimasantam.com
	}elseif ($_SERVER['SERVER_NAME']=='nyimasantam.my.id') {
		$secretKey = "6Lc9f84ZAAAAAEBSnQvoHzWcPvD0Tqcn0HD0izsO";//nyimasantam.my.id
	}
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
		$errorMsg[]="silakan Memasukan Akun";	//check "username/email" textbox not empty 
	}
	else if(empty($email)){
		$errorMsg[]="silakan Memasukan Akun";	//check "username/email" textbox not empty 
	}
	else if(empty($password)){
		$errorMsg[]="silakan Memasukan Password";	//check "passowrd" textbox not empty 
	}
	else
	{
		try
		{
			$select_stmt=$db->prepare('SELECT * FROM tbluserlogin WHERE field_email=:uemail OR field_handphone=:uname '); //sql select query
			$select_stmt->execute(array(':uemail'=>$email,':uname'=>$username ));	//execute query with bind parameter
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);		
			$data=$select_stmt->rowCount();			
			if($select_stmt->rowCount() > 0)	//check condition database record greater zero after continue
			{

				if($satu==$row["field_status_aktif"] OR $email==$row["field_email"] AND $username==$row["field_handphone"] ) //check condition user taypable "username or email" are both match from database "username or email" after continue
				{
					if(password_verify($password, $row["field_password"])) //check condition user taypable "password" are match from database "password" using password_verify() after continue
					{
						$update_stmt=$db->prepare("UPDATE tbluserlogin SET field_log=:loglogin, field_ipaddress=:addresip WHERE field_email=:uemail OR field_handphone=:uname ");
						  // execute the query
						$update_stmt->execute(array(':uname'	=>	$username,
													':uemail'	=>	$email,
													':loglogin'	=>	$loglogin,
													':addresip'	=>	$ipaddress
													));							

						session_start();
						$_SESSION["user_login"] 		= $row["field_user_id"];	//session name is "user_login"
						$_SESSION["login_member_id"] 	= $row["field_member_id"];	//session name is "login_member_id"
						$_SESSION["last_login_time"] 	= time();
						$loginMsg 						= "Successfully Login...";		//user login success message
						//header('location:loading');	//refresh 2 second after redirect to "welcome.php" page
						if ($_SERVER['SERVER_NAME']=='localhost') {                     
							echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/customer/main">';
						  }else{
							echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://urunanmu.my.id/customer/dashboard?module=home">';
						  }
					}
					else
					{
						
						$NLock=1;
						$_SESSION['lock']=$_SESSION['lock']+$NLock;
						if ($_SESSION['lock']>=3) {		

							$update_stmt=$db->prepare("UPDATE tbluserlogin SET field_status_aktif=:status, field_blokir_status=:blokir WHERE field_email=:uemail OR field_handphone=:uname ");
						  	// execute the query
							$update_stmt->execute(array(':uname'	=>	$username,
														':uemail'	=>	$email,
														':status'	=>	"2",
														':blokir'	=>	"B"
														));

							$errorMsg[]="Akun Terkunci silakan Hubungi Admin";
						}else{
							$errorMsg[]="Password Salah Percobaan Ke- ".$_SESSION['lock'];
							
						}						
						
						//........
					}
					//AND OR
				}
				else
				{
					$errorMsg[]="Akun Terkunci dan diblokir silakan Hubungi Admin";
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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Silakan Login Dengan Aman" name="descriptison">
    <meta content="Login nyimasantam" name="keywords">    
    <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="icon">
    <link href="https://nyimasantam.my.id/image/iconnyimas.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
     <!-- <link rel="stylesheet" href="fonts/icomoon/style.css"> -->
    <!-- <link rel="stylesheet" href="css/owl.carousel.min.css"> -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="view/assetlogin/css/bootstrap.min.css">    
    <!-- Style -->
    <link rel="stylesheet" href="view/assetlogin/css/style.css">
    <title>NYIMASANTAM</title>
</head>
	<body>
	<div class="wrapper">
	<div class="container">
		<div class="col-lg-12">
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
				<a href="home"><img  src="https://nyimasantam.my.id/image/logonyimas.png" width="170" ></a>
				<h3 >Login</h3>
			</center>			

			<form method="post" class="form-horizontal">
				<div class="form-group">
				<label class="col-sm-6 control-label">Email / No HP</label>
				<div class="col-sm-6">
				<input type="text" name="txt_username" class="form-control" placeholder="Masukan Email atau No Hp" />
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-3 control-label">Password</label>
				<div class="col-sm-6">
				<input type="password" name="txt_password" class="form-control" placeholder="Masukan Password" /> <br>
					<?php 
                    if ($_SERVER['SERVER_NAME']=='localhost') {
                       echo '<div class="g-recaptcha" data-sitekey="6LfJec4ZAAAAAPYZt2c-p6gu37D6weYdI8Kw1LqA"></div>';
                    }elseif ($_SERVER['SERVER_NAME']=='urunanmu.my.id') {
                       echo '<div class="g-recaptcha" data-sitekey="6Ldi1lsaAAAAALAritGVdd7xOXdf_mglkssD9RjR"></div>';
                    }elseif ($_SERVER['SERVER_NAME']=='nyimasantam.com') {
                      echo '<div class="g-recaptcha" data-sitekey="6Lf6eR0aAAAAAAXiPck77ymXUnqtLYj1dvtlli1B"></div>';
                    }elseif ($_SERVER['SERVER_NAME']=='nyimasantam.my.id') {
                      echo '<div class="g-recaptcha" data-sitekey="6Lc9f84ZAAAAANDLO3VFPiJEsa1trW4PwdE5fX0U"></div>';
                    }
                   ?>
				</div>
				</div>
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit" name="btn_login" class="btn btn-success" value="Login">
				<a href="lupapassword" type="submit" class="btn btn-primary">Lupa Password</a>
				</div>
				</div>
				<div class="form-group">

				<div class="col-sm-offset-3 col-sm-9 m-t-15">					
				Anda tidak punya akun mendaftar di sini? <a href="register"><p class="text-info">Daftar Akun</p></a>		

				</div>

				</div>				

			</form>		

		</div>

	</div>	

	</div>

	<script src='https://www.google.com/recaptcha/api.js'></script> 
    <script src="view/assetlogin/js/jquery-3.3.1.min.js"></script>
    <script src="view/assetlogin/js/popper.min.js"></script>
    <script src="view/assetlogin/js/bootstrap.min.js"></script>
    <script src="view/assetlogin/js/main.js"></script>
  </body>
</html>