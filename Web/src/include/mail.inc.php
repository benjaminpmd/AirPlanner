<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

include_once "./include/utils.inc.php";

/**
 * Function that send a mail a return a confirmation of wether the mail has been sent or not.
 * @param toAddress the destination email address.
 * @param toName the name of the person intended to receive the message.
 * @param subject the subject of the email.
 * @param content the content of the email.
 */
function send_mail(string $toAddress, string $toName, string $subject, string $content): bool {
    // create a new object
    $mail = new PHPMailer(true);

    try {
        // initial setup
        $mail->isSMTP();
        $mail->Mailer = "smtp";
        $mail->Host = 'smtp-airplanner.alwaysdata.net';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->Username = $_ENV["MAIL_ADDRESS"];
        $mail->Password = $_ENV["MAIL_PASSWORD"];

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; 
        $mail->addAddress($toAddress, $toName);
        
        // set from
        $mail->setFrom('noreply@' . WEBSITE_NAME_URL . '.benjaminpmd.fr', WEBSITE_NAME);
        
        // set content
        $mail->Subject = $subject;
        $mail->Body = $content;
        $mail->send();
        $mail = null;
        return true;
    } catch (Exception $th) {
        return false;
    }
}