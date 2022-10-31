<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

include_once "./include/utils.inc.php";

function send_mail(string $toAddress, string $toName, string $subject, string $content): bool {
    $mail = new PHPMailer(true);
    try {
        // initial setup
        $mail->isSMTP();
        $mail->Mailer = "smtp";
        $mail->Host = 'smtp.gmail.com;';
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->Username = $_ENV["MAIL_ADDRESS"];
        $mail->Password = $_ENV["MAIL_PASSWORD"];

        $mail->isHTML(true);
        $mail->addAddress($toAddress, $toName);
        // set from
        $mail->setFrom('noreply@' . WEBSITE_NAME_URL . '.benjaminp.dev', WEBSITE_NAME);
        // set content
        
        $mail->Subject = $subject;
        $mail->msgHTML($content);
        $mail->send();
        var_dump($mail);
        return true;
    } catch (Exception $th) {
        //throw $th;
        return false;
    }
}