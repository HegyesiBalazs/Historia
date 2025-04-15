<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'adatbazis.php';

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$valasz = ['sikeres' => false];

error_log("ujkod.php: Email kapott: $email"); // Naplózás

$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

if (!empty($email)) {
    $stmt = $kapcsolat->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $kod = bin2hex(random_bytes(4));
        $lejarat = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $kapcsolat->prepare("INSERT INTO jelszomodosit (kod, kod_lejar, reg_email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE kod = ?, kod_lejar = ?");
        $stmt->bind_param("sssss", $kod, $lejarat, $email, $kod, $lejarat);
        if (!$stmt->execute()) {
            error_log("SQL hiba: " . $stmt->error); // SQL hiba naplózása
            $valasz['uzenet'] = "Hiba történt az adatbázis művelet során.";
            echo json_encode($valasz);
            exit();
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'marosvolgyimartin@gmail.com';
            $mail->Password = 'ddzv mjuj xfds ukds';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('marosvolgyimartin@gmail.com', 'Historia');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Jelszó visszaállítási kód';
            $mail->Body = "Kedves Felhasználó!<br><br>A jelszó visszaállítási kódod: <strong>$kod</strong><br>Érvényes: $lejarat-ig<br><br>Üdvözlettel,<br>Historia Csapata";
            $mail->AltBody = "A kódod: $kod\nÉrvényes: $lejarat-ig";

            $mail->send();
            $valasz['sikeres'] = true;
        } catch (Exception $e) {
            error_log("Email küldési hiba: " . $mail->ErrorInfo); // Email hiba naplózása
            $valasz['uzenet'] = "Hiba történt az e-mail küldése közben: " . $mail->ErrorInfo;
        }
    } else {
        $valasz['uzenet'] = 'Nincs ilyen email cím regisztrálva!';
    }
} else {
    $valasz['uzenet'] = 'Add meg az email címedet!';
}

if (isset($stmt)) {
    $stmt->close();
}

echo json_encode($valasz);
?>