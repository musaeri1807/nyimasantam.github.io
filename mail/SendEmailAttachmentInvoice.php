	<?php
	/*call the FPDF library*/
	require('../library/fpdf181/fpdf.php');

	require_once("../config/connection.php");
	require_once("../php/function.php");

	require 'phpmailer/PHPMailerAutoload.php';
	require 'credential.php';


	$recipients = [
		["ID" => "1123", "Email" => "musaeri.kjt@gmail.com"],
		["ID" => "1124", "Email" => "musaeri1807@gmail.com"],
		["ID" => "1125", "Email" => "erick.java03@gmail.com"]
	];




	foreach ($recipients as $recipient) {
		$email = $recipient["Email"];

		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		//Server settings
		// $mail->SMTPDebug = 4;					                    //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'mx.mailspace.id';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = EMAIL;                     //SMTP username
		$mail->Password   = PASSWORD;                               //SMTP password
		$mail->SMTPSecure = 'tls';        						    //Enable implicit TLS encryption
		$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom(EMAIL, 'Customer MIGA');
		//$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
		$mail->addAddress($recipient["Email"]);               //Name is optional
		$mail->addReplyTo(INFO, 'Information');
		// $mail->addCC('musaeri1807@gmail.com');
		// $mail->addBCC('musaeri.kjt@gmail.com');

		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		$mail->addAttachment("../image/1222090001_September_2022" . ".pdf");    //Optional name

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'Billing Invoice';
		$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if (!$mail->send()) {
			echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo $Msg = 'Message has been sent';
		}
	}


	?>
