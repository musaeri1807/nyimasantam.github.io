Skip to content
 
Search…
All gists
Back to GitHub
Sign in
Sign up
Instantly share code, notes, and snippets.

@Mauryashubham
Mauryashubham/index.php
Last active 17 months ago
1
0
 Code
 Revisions 2
 Stars 1
<script src="https://gist.github.com/Mauryashubham/a0e94511769f8e9e111ee651a3481f0d.js"></script>
PHPMailer Problem Solved for PHP 7+
index.php
<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

//require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
//$mail->SMTPDebug = 2;

$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
//or more succinctly:
$mail->Host = 'tls://smtp.gmail.com:587';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

 $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
 
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "";
//Password to use for SMTP authentication
$mail->Password = "";
//Set who the message is to be sent from
$mail->setFrom('mailid', 'First Last');

//Set an alternative reply-to address
// $mail->addReplyTo('maurya.shubham5@gmail.com', 'First Last');

//Set who the message is to be sent to
$mail->addAddress('id1', 'John Doe');
$mail->addAddress('id2', 'John Doe');
//Set the subject line
$mail->Subject = 'Web Checking Mail';


//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('image.html'), __DIR__);

// $mail->msgHTML("<h1>Congratulations</h1>
// <img src='http://192.168.1.250:8080/script/cat.gif'>");


//Replace the plain text body with one created manually
// $mail->AltBody = 'This is a plain-text message body,<h1>Congratulations</h1>';

//Attach an image file
// $mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    #if (save_mail($mail)) {
    #    echo "Message saved!";
    #}
}




?>

