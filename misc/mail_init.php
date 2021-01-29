<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("../PHPMailer/src/Exception.php");
require("../PHPMailer/src/PHPMailer.php");
require("../PHPMailer/src/SMTP.php");
// send mail
$mailer = new PHPMailer(true);

//$mailer->SMTPDebug = 4;

$mailer->CharSet = "UTF-8";
//Server settings
$mailer->isSMTP();                                            // Send using SMTP
$mailer->Host       = $mail_server_host;                      // Set the SMTP server to send through
$mailer->SMTPAuth   = true;                                   // Enable SMTP authentication
$mailer->Username   = $mail_server_user;                      // SMTP username
$mailer->Password   = $mail_server_password;                  // SMTP password
$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
$mailer->Port       = $mail_server_SMTP_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

?>
