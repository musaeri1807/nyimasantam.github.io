<?php
	//ini_set('display_errors', 0);
	date_default_timezone_set('Asia/Jakarta');
	require_once("../config/koneksi.php");
	
	$id_order 	= $_POST['id_order'];
	$product 	= $_POST['product'];
	$quantity 	= $_POST['quantity'];
	$handphone 	= $_POST['handphone'];
	$price 		= $_POST['price'];
	$comments 	= $_POST['comments'];

	// $id_order 	= "1";
	// $product 	= "XL";
	// $quantity 	= "1";
	// $handphone 	= "081210003701";
	// $price 		= "10000";
	// $comments 	= "transfer";
	$name		= "Customer";
	$email		= "musaeri1807@gmail.com";
	// $api_key = file_get_contents("../config/apikey.txt");


	// if ($_SERVER['SERVER_NAME']=='localhost') {
	// 	$api_key = file_get_contents("../config/apikey.txt");
	// } else {
	// 	$api_key = file_get_contents("../config/sandboxapikey.txt");
	// }


	$api_key = file_get_contents("../config/sandboxapikey.txt");
	$domain = file_get_contents("../config/domain.txt");
	$notif = $domain.'/notify.php';
	$callback = $domain.'/selesai.php?id_order='.$id_order;

	// if ($_SERVER['SERVER_NAME']=='localhost' && $comments=='qris' ) {
	// 	$CURLOPT_URL="https://my.ipaymu.com/api/payment/qris";
	// } elseif($_SERVER['SERVER_NAME']=='localhost' && $comments=='transfer' ) {
	// 	$CURLOPT_URL="https://my.ipaymu.com/api/bcatransfer";
	// } elseif($_SERVER['SERVER_NAME']!=='localhost' && $comments=='qris' ){
	// 	$CURLOPT_URL="https://sandbox.ipaymu.com/api/payment/qris";
	// } elseif($_SERVER['SERVER_NAME']!=='localhost' && $comments=='transfer' ){
	// 	$CURLOPT_URL="https://sandbox.ipaymu.com/api/bcatransfer";
	// }

	if ($comments=='qris') {
		$CURLOPT_URL="https://sandbox.ipaymu.com/api/payment/qris";
	} else {
		$CURLOPT_URL="https://sandbox.ipaymu.com/api/bcatransfer";
	}
		
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	//   CURLOPT_URL => "https://my.ipaymu.com/payment",
	  CURLOPT_URL 				=> "$CURLOPT_URL",
	  CURLOPT_RETURNTRANSFER 	=> true,
	  CURLOPT_ENCODING 			=> "",
	  CURLOPT_MAXREDIRS 		=> 10,
	  CURLOPT_TIMEOUT 			=> 30,
	  CURLOPT_HTTP_VERSION 		=> CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST 	=> "POST",
	  CURLOPT_POSTFIELDS 		=> "key=$api_key&format=json&name=$name&phone=$handphone&email=$email&amount=$price&notifyUrl=$notif&auto_redirect=10",
	  //CURLOPT_POSTFIELDS 		=> "key=$api_key&action=payment&format=json&product=$product&quantity=$quantity&price=$price&comments=$comments&ureturn=$callback&unotify=$notif&auto_redirect=10",
	  CURLOPT_HTTPHEADER 		=> array(
	    "content-type: application/x-www-form-urlencoded"
	  )
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);


	curl_close($curl);

	// echo $response;
	// die();
	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
		$obj = json_decode($response);
		// $url = $obj->url;
		$TransactionId = $obj->Data->TransactionId;
		$Via = $obj->Data->Via;
		$PaymentNo = $obj->Data->PaymentNo;
		$PaymentName = $obj->Data->PaymentName;
		$Fee = $obj->Data->Fee;
		$Expired = $obj->Data->Expired;

		if ($comments=='qris') {
			$url = $obj->Data->QrTemplate;
			$Channel = "";
		} else {
			$url = "";
			$Channel = $obj->Data->Channel;
		}		
	
		if(isset($url)){
		$sql = "INSERT INTO tblpesanan_ipaymu (trx_id,product,quantity,handphone,price,comments,url,status,channel,via,expired,harga,potongan) 
				VALUES (:trx_id,:product,:quantity,:handphone,:price,:comments,:url,:status,:channel,:via,:expired,:harga,:potongan)";
		$stmt = $db->prepare($sql);
		$params = array(
			":trx_id"		=> $TransactionId,
			":product" 		=> $product,
			":quantity" 	=> $quantity,
			":handphone" 	=> $handphone,
			":price" 		=> $price,
			":comments" 	=> $comments,
			":url" 			=> $url,
			//":url" 			=> 'https://sandbox.ipaymu.com/payment/E4D4685B-832E-4E60-835B-756832A244C9',
			":status" 		=> 'tertunda',
			":channel"		=> $Channel,
			":via"			=> $Via,
			":expired"		=> $Expired,
			":harga" 		=> intval($price)*intval($quantity),
			":potongan"		=> $Fee
		);
		$saved = $stmt->execute($params);
			if($saved) { 
				echo $response;
			}
		}else{
			echo $response;
		}
	}
	
	
?>