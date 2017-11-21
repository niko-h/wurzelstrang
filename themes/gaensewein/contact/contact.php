<?php
require 'PHPMailer/PHPMailerAutoload.php';

$data = $_POST['data'];

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtprelaypool.ispgateway.de';  // Specify main and backup server
//$mail->Host = 'smtp.gaensewein.com';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Port       = 465;
$mail->Username = 'noreply@gaensewein.com';                            // SMTP username
$mail->Password = '"Mzzy28Zm44n';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'noreply@gaensewein.com';
$mail->FromName = 'Gaensewein Anmeldung';
$mail->addAddress('beate@gaensewein.com');               // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Neue Anmeldung auf Gaensewein.com';
$mail->Body    = $data;
$mail->AltBody = $data;

if(!$mail->send()) {
   echo 'Ihre Anfrage konnte leider nicht gesendet werden. Bitte wenden Sie sich direkt per Email an mich.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Vielen Dank, Ihre Anmeldung wurde versendet.';
?>
