<?php 

// function encrypt( $q ) {
//         $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';        
//         $qEncoded  = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
//         return( $qEncoded );
//     }

// function decrypt( $q ) {
//         $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';       
//         $qDecoded  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
//         return( $qDecoded );
//     }
// function encrypt( $q ) {
//   $cryptKey  = 'd8578edf8458ce06fbc5bb76a58c5ca4';
//   $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
//   return( $qEncoded );
// }

// function decrypt( $q ) {
//   $cryptKey  = 'd8578edf8458ce06fbc5bb76a58c5ca4';
//   $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
//   return( $qDecoded );
// }

function rupiah($angka){
  $hasil_rupiah = "Rp " . number_format($angka,0,',','.');
  return $hasil_rupiah; 
}

function password_generate($chars) 
{
  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($data), 0, $chars);
}



 ?>