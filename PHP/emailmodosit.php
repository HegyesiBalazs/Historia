<?php
session_start();
require_once 'adatbazis.php';

// PHPMailer betöltése
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$valasz = ['sikeres' => false];

if (!isset($_SESSION['user_id'])) {
    $valasz['uzenet'] = 'Jelentkezz be az email módosításához!';
    echo json_encode($valasz);
    exit();
}

if ($action === 'request_code') {
    $new_email = $_POST['new_email'] ?? '';
    
    if (!empty($new_email)) {
        // Ellenőrzés, hogy az új email már létezik-e
        $stmt = $db->kapcs_reg->prepare("SELECT * FROM regisztralas WHERE email = ?");
        $stmt->bind_param("s", $new_email);
        $stmt->execute();
        $eredmeny = $stmt->get_result();

        if ($eredmeny->num_rows > 0) {
            $valasz['uzenet'] = 'Ez az email cím már használatban van!';
        } else {
            $kod = bin2hex(random_bytes(4)); // 8 karakteres véletlenszerű kód
            $lejarat = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 óra érvényesség

            // Kód mentése a jelszo_db-be
            $stmt = $db->kapcs_jelszo->prepare("INSERT INTO jelszomodosit (kod, kod_lejar, reg_email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE kod = ?, kod_lejar = ?");
            $stmt->bind_param("sssss", $kod, $lejarat, $new_email, $kod, $lejarat);
            $stmt->execute();

            // PHPMailer beállítása és e-mail küldése
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'te.email@gmail.com'; // Cseréld ki
                $mail->Password = 'jelszo_vagy_app_jelszo'; // Cseréld ki
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('noreply@historia.hu', 'Historia');
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
        // Kód ellenőrzése
        $stmt = $db->kapcs_jelszo->prepare("SELECT * FROM jelszomodosit WHERE kod = ? AND reg_email = ? AND kod_lejar > NOW()");
        $stmt->bind_param("ss", $kod, $new_email);
        $stmt->execute();
        $eredmeny = $stmt->get_result();

        if ($eredmeny->num_rows > 0) {
            // Email frissítése a regisztralas_db-ben
            $user_id = $_SESSION['user_id'];
            $stmt = $db->kapcs_reg->prepare("UPDATE regisztralas SET email = ? WHERE id = ?");
            $stmt->bind_param("si", $new_email, $user_id);
            
            if ($stmt->execute()) {
                // Kód törlése a jelszo_db-ből
                $stmt = $db->kapcs_jelszo->prepare("DELETE FROM jelszomodosit WHERE kod = ? AND reg_email = ?");
                $stmt->bind_param("ss", $kod, $new_email);
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

echo json_encode($valasz);
?>