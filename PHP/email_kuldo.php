<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload, ha PHPMailer-t használsz

function kuldo_email($cimzett, $targy, $uzenet) {
    $mail = new PHPMailer(true);
    try {
        // SMTP beállítások
        $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP szerver címe
            $mail->SMTPAuth = true;
            $mail->Username = 'te.email@gmail.com'; // A te e-mail címed
            $mail->Password = 'jelszo_vagy_app_jelszo'; // Jelszó vagy alkalmazásjelszó
            $mail->SMTPSecure = 'tls'; // Titkosítás: tls vagy ssl
            $mail->Port = 587; // Portszám

        // Feladó és címzett
        $mail->setFrom('noreply@historia.hu', 'Historia');
        $mail->addAddress($cimzett);

        // Email tartalom
        $mail->isHTML(false);
        $mail->Subject = $targy;
        $mail->Body = $uzenet;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>