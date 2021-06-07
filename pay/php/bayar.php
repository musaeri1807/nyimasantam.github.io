<?php
	ini_set('display_errors', 0);
	date_default_timezone_set('Asia/Jakarta');
	require_once("../config/koneksi.php");
	
	$id_order 	= $_POST['id_order'];
	$product 	= $_POST['product'];
	$quantity 	= $_POST['quantity'];
	$price 		= $_POST['price'];
	$comments 	= $_POST['comments'];
	
	// $api_key = file_get_contents("../config/apikey.txt");

	if ($_SERVER['SERVER_NAME']=='localhost') {
		$api_key = file_get_contents("../config/sandboxapikey.txt");
	} else {
		$api_key = file_get_contents("../config/apikey.txt");
	}

	$domain = file_get_contents("../config/domain.txt");
	$notif = $domain.'/notify.php';
	$callback = $domain.'/selesai.php?id_order='.$id_order;

	if ($_SERVER['SERVER_NAME']=='localhost') {
		$CURLOPT_URL="https://sandbox.ipaymu.com/payment";
	} else {
		$CURLOPT_URL="https://my.ipaymu.com/payment";
	}

	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	//   CURLOPT_URL => "https://my.ipaymu.com/payment",
	  CURLOPT_URL => "$CURLOPT_URL",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "key=$api_key&action=payment&format=json&product=$product&quantity=$quantity&price=$price&comments=$comments&ureturn=$callback&unotify=$notif&auto_redirect=10",
	  CURLOPT_HTTPHEADER => array(
	    "content-type: application/x-www-form-urlencoded"
	  )
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
		$obj = json_decode($response);
		$url = $obj->url;
		if(isset($url)){
		$sql = "INSERT INTO tblpesanan_ipaymu (product,quantity,price,comments,url,status,harga) 
				VALUES (:product,:quantity,:price,:comments,:url,:status,:harga)";
		$stmt = $db->prepare($sql);
		$params = array(
			":product" => $product,
			":quantity" => $quantity,
			":price" => $price,
			":comments" => $comments,
			":url" => $url,
			":status" => 'tertunda',
			":harga" => intval($price)*intval($quantity)
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