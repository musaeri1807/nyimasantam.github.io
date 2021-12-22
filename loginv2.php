<?php

// if (!$_SERVER['SERVER_NAME']=='localhost') {

//   header("location: index.php");
// }
// ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("config/koneksi.php");
//require_once 'connection.php';
session_start();
if (isset($_SESSION["userlogin"])) //admin_login//check condition user login not direct back to index.php page
{
  header("location: admin/dashboard?module=home");
}

$domain = file_get_contents("config/domain.txt");
//$domain = "musaeri.my.id";

if (isset($_REQUEST['btn_login'])) //button name is "btn_login" 
{
  $username = strip_tags($_REQUEST["txt_username"]); //textbox name "txt_username"
  $email    = strip_tags($_REQUEST["txt_username"]); //textbox name "txt_username"
  $password = strip_tags($_REQUEST["txt_password"]); //textbox name "txt_password"
  $loglogin = date('Y-m-d h:i:s');
  $ipaddress  = $_SERVER['REMOTE_ADDR'];
  $satu     = "1";

  //$username = $_POST['username']; nyimasantam.my.id
  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $secretKey = "6LfJec4ZAAAAACG1-fmobe88erF72OdXbAFN71jj"; //local        
  } elseif ($_SERVER['SERVER_NAME'] == 'urunanmu.my.id') {
    $secretKey = "6Ldi1lsaAAAAAELsOlpS__1jUbNTuXv0bbjhpD6L"; //urunanmu.my.id
  } elseif ($_SERVER['SERVER_NAME'] == 'nyimasantam.com') {
    $secretKey = "6Lf6eR0aAAAAABFKOeUrFysV3fvrrWcoTayg3R2j"; //nyimasantam.com
  } elseif ($_SERVER['SERVER_NAME'] == 'nyimasantam.my.id') {
    $secretKey = "6Lc9f84ZAAAAAEBSnQvoHzWcPvD0Tqcn0HD0izsO"; //nyimasantam.my.id
  } elseif ($_SERVER['SERVER_NAME'] == 'musaeri.my.id') {
    $secretKey = "6LdCXhcbAAAAABj_ExKExLI_0h_1uz7tSCYdDHM-"; //musaeri.my.id
  } elseif ($_SERVER['SERVER_NAME'] == 'apps.musaeri.my.id') {
    $secretKey = "6LfkGOsbAAAAAGNct9U_gqaj7-FkyhZ9fmqocSJt"; //apps.musaeri.my.id
  }

  $responseKey = $_POST['g-recaptcha-response'];
  $userIP = $_SERVER['REMOTE_ADDR'];
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
  $response1 = file_get_contents($url);
  $response = json_decode($response1);
  if ($responseKey == 0) {
    $errorMsg[] = "Harap Periksa reCAPTCHA";
  }

  if ($response->success) {

    if (empty($username)) {
      $errorMsg[] = "Silakan Memasukan Akun Username Or Email"; //check "username/email" textbox not empty 
    } else if (empty($email)) {
      $errorMsg[] = "Silakan Memasukan Akun Username Or Email"; //check "username/email" textbox not empty 
    } else if (empty($password)) {
      $errorMsg[] = "Silakan Memasukan Password"; //check "passowrd" textbox not empty 
    } else {
      try {
        $select_stmt = $db->prepare('SELECT * FROM tblemployeeslogin WHERE field_email=:uemail OR field_username=:uname '); //sql select query
        $select_stmt->execute(array(':uemail' => $email, ':uname' => $username)); //execute query with bind parameter
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        $data = $select_stmt->rowCount();
        if ($select_stmt->rowCount() > 0)  //check condition database record greater zero after continue
        {

          if ($satu == $row["field_status_aktif"] or $email == $row["field_email"] and $username == $row["field_username"]) //check condition user taypable "username or email" are both match from database "username or email" after continue
          {
            if (password_verify($password, $row["field_password"])) //check condition user taypable "password" are match from database "password" using password_verify() after continue
            {
              $update_stmt = $db->prepare("UPDATE tblemployeeslogin SET field_log=:loglogin, field_ipaddress=:addresip WHERE field_email=:uemail OR field_username=:uname ");
              // execute the query
              $update_stmt->execute(array(
                ':uname'    =>  $username,
                ':uemail'   =>  $email,
                ':loglogin' =>  $loglogin,
                ':addresip' =>  $ipaddress
              ));

              // session_start();
              // $_SESSION["user_login"]    = $row["field_user_id"];  //session name is "user_login"
              // $_SESSION["login_member_id"]   = $row["field_member_id"];  //session name is "login_member_id"
              // $_SESSION["last_login_time"]   = time();
              // $loginMsg            = "Successfully Login...";    //user login success message
              // //header('location:loading');  //refresh 2 second after redirect to "welcome.php" page
              // echo '<META HTTP-EQUIV="Refresh" Content="2; URL=https://nyimasantam.com/loading">';
              // 'Superadmin','Administrator','Supervisor','Officer'

              switch ($row["field_role"]) {
                case 'ADM':
                  $_SESSION["rolelogin"]  = $row["field_role"];
                  $_SESSION["idlogin"]    = $row["field_user_id"];
                  $_SESSION["userlogin"]  = $row["field_email"];
                  $loginMsg = "Administrator..Successfully Login";
                  //header("refresh:1;../../superadmin/superadmin_home.php");
                  if ($_SERVER['SERVER_NAME'] == 'localhost') {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';
                  } else {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=home">';
                  }
                  break;
                case 'MGR':
                  $_SESSION["rolelogin"]  = $row["field_role"];
                  $_SESSION["idlogin"]    = $row["field_user_id"];
                  $_SESSION["userlogin"]  = $row["field_email"];
                  $loginMsg = "Manager..Successfully Login";
                  //header("refresh:1;../../superadmin/superadmin_home.php");
                  if ($_SERVER['SERVER_NAME'] == 'localhost') {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';
                  } else {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=home">';
                  }
                  break;
                case 'SPV':
                  $_SESSION["rolelogin"]          = $row["field_role"];
                  $_SESSION["idlogin"]            = $row["field_user_id"];
                  $_SESSION["userlogin"]          = $row["field_email"];
                  $_SESSION["branchlogin"]        = $row["field_branch"];
                  $loginMsg = "Supervisor..Successfully Login";
                  //header("refresh:1;../../admin/admin_home.php");
                  if ($_SERVER['SERVER_NAME'] == 'localhost') {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';
                  } else {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=home">';
                  }

                  break;
                case 'BCO':
                  $_SESSION["rolelogin"]          = $row["field_role"];
                  $_SESSION["idlogin"]            = $row["field_user_id"];
                  $_SESSION["userlogin"]          = $row["field_email"];
                  $_SESSION["branchlogin"]        = $row["field_branch"];
                  $loginMsg = "Back Office..Successfully Login";
                  //header("refresh:1;../../admin/admin_home.php");
                  if ($_SERVER['SERVER_NAME'] == 'localhost') {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';
                  } else {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=home">';
                  }

                  break;
                case 'CMS':
                  $_SESSION["rolelogin"]          = $row["field_role"];
                  $_SESSION["idlogin"]            = $row["field_user_id"];
                  $_SESSION["userlogin"]          = $row["field_email"];
                  $_SESSION["branchlogin"]        = $row["field_branch"];
                  $loginMsg = "Customer Service..Successfully Login";
                  //header("refresh:1;../../officer/officer_home.php");
                  if ($_SERVER['SERVER_NAME'] == 'localhost') {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=https://localhost/nyimasantam.github.io/admin/dashboard?module=home">';
                  } else {
                    echo '<META HTTP-EQUIV="Refresh" Content="1; URL=' . $domain . '/admin/dashboard?module=home">';
                  }

                  break;

                default:
                  $errorMsg[] = "Role Tidak Ada";
                  //break;
              }
            } else {

              $NLock = 1;
              $_SESSION['lock'] = $_SESSION['lock'] + $NLock;
              if ($_SESSION['lock'] >= 3) {

                $update_stmt = $db->prepare("UPDATE tblemployeeslogin SET field_status_aktif=:status, 
                                                                      field_blokir_status=:blokir 
                                                                  WHERE field_email=:uemail OR field_username=:uname ");
                // execute the query
                $update_stmt->execute(array(
                  ':uname'  =>  $username,
                  ':uemail' =>  $email,
                  ':status' =>  "2",
                  ':blokir' =>  "B"
                ));

                $errorMsg[] = "Akun Terkunci Silakan Hubungi Admin";
              } else {
                $errorMsg[] = "Password Salah Percobaan Ke- " . $_SESSION['lock'];
              }

              //........
            }
            //AND OR
          } else {
            $errorMsg[] = "Akun Terkunci dan diblokir Silakan Hubungi Admin";
          }
          //1>0
        } else {
          $errorMsg[] = "Akun Belum Terdaftar";
        }

        //try
      } catch (PDOException $e) {
        $e->getMessage();
      }

      //input   

    }
  } //google c

  //$loginMsgCapcha = " Login...";

}

?>



<!DOCTYPE html>
<html lang="en">

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
  <title>BANK SAMPAH PINTAR</title>
</head>

<body>
  <div class="d-lg-flex half">
    <div class="bg" style="background-image: url('');"></div>
    <div class="contents">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-6">
            <div class="form mx-auto">
              <!--    <div class="form-block mx-auto"> -->

              <div class="text-center mt-1">
                <img src="image/login.png" width="200">
                <br>
                <h3> <strong>Officer</strong></h3>
                <!-- <p class="mb-1">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p>  -->

                <?php
                if (isset($errorMsg)) {
                  foreach ($errorMsg as $error) {
                ?>
                    <div class="alert alert-danger">
                      <strong><?php echo $error; ?></strong>
                    </div>
                  <?php
                  }
                }
                if (isset($loginMsg)) {
                  ?>
                  <div class="alert alert-success">
                    <strong><?php echo $loginMsg; ?></strong>
                  </div>
                <?php
                }
                ?>
                <br>

              </div>

              <form method="post" class="form-horizontal">
                <div class="form-group first">
                  <!-- <label for="username">Username</label> -->
                  <!-- <input type="text" class="form-control" placeholder="your-email@gmail.com" id="username"> -->
                  <input type="text" name="txt_username" class="form-control" placeholder="Your Username OR Email" />
                </div>
                <div class="form-group last mb-3">
                  <!-- <label for="password">Password</label> -->
                  <!-- <input type="password" class="form-control" placeholder="Your Password" id="password"> -->
                  <input type="password" name="txt_password" class="form-control" placeholder="Masukan Password" />
                </div>

                <?php
                if ($_SERVER['SERVER_NAME'] == 'localhost') {
                  echo '<div class="g-recaptcha" data-sitekey="6LfJec4ZAAAAAPYZt2c-p6gu37D6weYdI8Kw1LqA"></div>';
                } elseif ($_SERVER['SERVER_NAME'] == 'urunanmu.my.id') {
                  echo '<div class="g-recaptcha" data-sitekey="6Ldi1lsaAAAAALAritGVdd7xOXdf_mglkssD9RjR"></div>';
                } elseif ($_SERVER['SERVER_NAME'] == 'nyimasantam.com') {
                  echo '<div class="g-recaptcha" data-sitekey="6Lf6eR0aAAAAAAXiPck77ymXUnqtLYj1dvtlli1B"></div>';
                } elseif ($_SERVER['SERVER_NAME'] == 'nyimasantam.my.id') {
                  echo '<div class="g-recaptcha" data-sitekey="6Lc9f84ZAAAAANDLO3VFPiJEsa1trW4PwdE5fX0U"></div>';
                } elseif ($_SERVER['SERVER_NAME'] == 'musaeri.my.id') {
                  echo '<div class="g-recaptcha" data-sitekey="6LdCXhcbAAAAAKhaHQouGGvtU6u4fJUSx8dpQUGv"></div>';
                } elseif ($_SERVER['SERVER_NAME'] == 'apps.musaeri.my.id') {
                  echo '<div class="g-recaptcha" data-sitekey="6LfkGOsbAAAAAOTf0zgH_fSj2fdFHebJuLx0uDfJ"></div>';
                }

                ?>

                <div class="d-sm-flex mb-5 align-items-center">
                  <!--  <label class="control control--checkbox mb-3 mb-sm-0"><span class="caption">Remember me</span>
                    <input type="checkbox" checked="checked"/>
                    <div class="control__indicator"></div>
                  </label>
                  <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>  -->

                </div>


                <input type="submit" name="btn_login" class="btn btn-primary" value="Login">

              </form>

              <hr>
              Belum Punya Akun ? <a href="">Daftar Ke Bank Sampah terdekat</a>

              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </div>
          </div>
        </div>
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