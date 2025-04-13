<?php
// PHPMailer fájlok manuális betöltése
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

// Névtér importálása
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function kuldo_email($cimzett, $targy, $uzenet) {
    $mail = new PHPMailer(true);
    try {
        // SMTP beállítások
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP szerver
        $mail->SMTPAuth = true;
        $mail->Username = 'marosvolgyimartin@gmail.com'; // A te email címed
        $mail->Password = 'ddzv mjuj xfds ukds'; // Alkalmazásjelszó (NEM a normál Gmail jelszó!)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Titkosítás
        $mail->Port = 587; // Gmail SMTP port

        // Feladó és címzett
        $mail->setFrom('marosvolgyimartin@gmail.com', 'Historia'); // A te email címed és feladó neve
        $mail->addAddress($cimzett);

        // Email tartalom
        $mail->isHTML(false); // Szöveges email
        $mail->Subject = $targy;
        $mail->Body = $uzenet;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Hibakezelés, pl. naplózás vagy hibaüzenet
        error_log("Email küldési hiba: {$mail->ErrorInfo}");
        return false;
    }
}
?>