	<?php
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		# code...
		$link = 'https://localhost/nyimasantam.github.io/loginv2';
	} else {
		$link = 'https://admins.bspid.id/loginv2';
	}
	require 'phpmailer/PHPMailerAutoload.php';
	require 'credential.php';

	$mail = new PHPMailer;

	// $mail->SMTPDebug = 4;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'mail.bspid.id';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = EMAIL;                 // SMTP username
	$mail->Password = PASSWORD;                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom(EMAIL, 'BSPID');
	$mail->addAddress($UsernameEmail);     // Add a recipient


	$mail->isHTML(true);                                // Set email format to HTML
	$mail->Subject = 'Register Akun';
	$mail->Body    = 'Email =' . $UsernameEmail . '<br> Username =' . $Username . '<br> Password =' . $Password . '<br>' . $link;

	if (!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		$Msg = 'Message has been sent';
	}
	?>
