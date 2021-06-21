<?php

// $ch = curl_init();
// $secret_key = "JDJ5JDEzJFI4QzRWRGd1ZGpQM0sxNW84aHByYU9NLlJ5RlRTMzFBUmQ3d2x3ek9qc2g1YTg4eWlISGhL";

// curl_setopt($ch, CURLOPT_URL, "https://bigflip.id/big_sandbox_api/v2/general/balance");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HEADER, FALSE);

// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//   "Content-Type: application/x-www-form-urlencoded"
// ));

// curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");

// $response = curl_exec($ch);
// curl_close($ch);

// var_dump($response);

//


$ch = curl_init();
$secret_key = "JDJ5JDEzJFI4QzRWRGd1ZGpQM0sxNW84aHByYU9NLlJ5RlRTMzFBUmQ3d2x3ek9qc2g1YTg4eWlISGhL";

curl_setopt($ch, CURLOPT_URL, "https://bigflip.id/big_sandbox_api/v2/disbursement");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

$payloads = [
    "account_number" => "0437051936",
    "bank_code" => "bni",
    "amount" => "10000",
    "remark" => "testing",
    "recipient_city" => "391"
];

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/x-www-form-urlencoded"
));

curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);