<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
error_reporting(E_ALL);
require_once 'adatbazis.php';

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

ob_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$valasz = ['sikeres' => false];

$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

if (!isset($_SESSION['user_id'])) {
    $valasz['uzenet'] = 'Jelentkezz be az email módosításához!';
    echo json_encode($valasz);
    ob_end_flush();
    exit();
}

if ($action === 'request_code') {
    $new_email = $_POST['new_email'] ?? '';
    
    if (!empty($new_email)) {
        $stmt = $kapcsolat->prepare("SELECT * FROM regisztralas WHERE email = ?");
        $stmt->bind_param("s", $new_email);
        $stmt->execute();
        $eredmeny = $stmt->get_result();

        if ($eredmeny->num_rows > 0) {
            $valasz['uzenet'] = 'Ez az email cím már használatban van!';
        } else {
            $kod = bin2hex(random_bytes(4));
            $lejarat = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $user_id = $_SESSION['user_id'];

            // Ideiglenesen mentsük az új emailt az uj_email mezőbe
            $stmt = $kapcsolat->prepare("UPDATE regisztralas SET uj_email = ? WHERE id = ?");
            $stmt->bind_param("si", $new_email, $user_id);
            if (!$stmt->execute()) {
                $valasz['uzenet'] = 'Hiba történt az adatbázis művelet során!';
                echo json_encode($valasz);
                ob_end_flush();
                exit();
            }

            // Most már használhatjuk a jelszomodosit táblát a régi email címmel
            $stmt = $kapcsolat->prepare("SELECT email FROM regisztralas WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $eredmeny = $stmt->get_result();
            $sor = $eredmeny->fetch_assoc();
            $reg_email = $sor['email'];

            $stmt = $kapcsolat->prepare("INSERT INTO jelszomodosit (kod, kod_lejar, reg_email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE kod = ?, kod_lejar = ?");
            $stmt->bind_param("sssss", $kod, $lejarat, $reg_email, $kod, $lejarat);
            if (!$stmt->execute()) {
                $valasz['uzenet'] = 'Hiba történt az adatbázis művelet során!';
                echo json_encode($valasz);
                ob_end_flush();
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
                $mail->addAddress($new_email);
                $mail->isHTML(true);
                $mail->Subject = 'Email módosítási kód';
                $mail->Body = "Kedves Felhasználó!<br><br>Az email módosítási kódod: <strong>$kod</strong><br>Érvényes: $lejarat-ig<br><br>Üdvözlettel,<br>Historia Csapata";
                $mail->AltBody = "A kódod: $kod\nÉrvényes: $lejarat-ig";

                $mail->send();
                $valasz['sikeres'] = true;
            } catch (Exception $e) {
                $valasz['uzenet'] = "Hiba az e-mail küldése közben: " . $mail->ErrorInfo;
            }
        }
    } else {
        $valasz['uzenet'] = 'Add meg az új email címet!';
    }
} elseif ($action === 'verify_code') {
    $kod = $_POST['code'] ?? '';
    $new_email = $_POST['new_email'] ?? '';

    if (!empty($kod) && !empty($new_email)) {
        $user_id = $_SESSION['user_id'];
        $stmt = $kapcsolat->prepare("SELECT * FROM jelszomodosit WHERE kod = ? AND kod_lejar > NOW()");
        $stmt->bind_param("s", $kod);
        $stmt->execute();
        $eredmeny = $stmt->get_result();

        if ($eredmeny->num_rows > 0) {
            $stmt = $kapcsolat->prepare("UPDATE regisztralas SET email = ?, uj_email = NULL WHERE id = ?");
            $stmt->bind_param("si", $new_email, $user_id);
            
            if ($stmt->execute()) {
                $stmt = $kapcsolat->prepare("DELETE FROM jelszomodosit WHERE kod = ?");
                $stmt->bind_param("s", $kod);
                $stmt->execute();
                
                $valasz['sikeres'] = true;
            } else {
                $valasz['uzenet'] = 'Hiba történt az email módosítása közben!';
            }
        } else {
            $valasz['uzenet'] = 'Érvénytelen vagy lejárt kód!';
        }
    } else {
        $valasz['uzenet'] = 'Add meg a kódot és az új email címet!';
    }
}

if (isset($stmt)) {
    $stmt->close();
}

echo json_encode($valasz);
ob_end_flush();
?>