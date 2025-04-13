<?php
session_start();
require_once 'adatbazis.php';

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

// Névtér importálása
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$valasz = ['sikeres' => false];

// Adatbázis kapcsolat inicializálása
$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

if (!empty($email)) {
    // Ellenőrzés a regisztralas táblában, hogy létezik-e az email
    $stmt = $kapcsolat->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $kod = bin2hex(random_bytes(4)); // 8 karakteres véletlenszerű kód
        $lejarat = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 óra érvényesség

        // Kód mentése a jelszomodosit táblába
        $stmt = $kapcsolat->prepare("INSERT INTO jelszomodosit (kod, kod_lejar, reg_email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE kod = ?, kod_lejar = ?");
        $stmt->bind_param("sssss", $kod, $lejarat, $email, $kod, $lejarat);
        $stmt->execute();

        // PHPMailer beállítása és e-mail küldése
        $mail = new PHPMailer(true); // True: kivételkezelés bekapcsolása

        try {
            // SMTP beállítások
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP szerver
            $mail->SMTPAuth = true;
            $mail->Username = 'marosvolgyimartin@gmail.com'; // A te email címed
            $mail->Password = 'ddzv mjuj xfds ukds'; // Alkalmazásjelszó
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Titkosítás
            $mail->Port = 587; // Portszám

            // Feladó és címzett
            $mail->setFrom('marosvolgyimartin@gmail.com', 'Historia'); // A te email címed
            $mail->addAddress($email); // Címzett (a felhasználó e-mail címe)

            // E-mail tartalom
            $mail->isHTML(true); // HTML formátumú e-mail
            $mail->Subject = 'Jelszó visszaállítási kód';
            $mail->Body = "Kedves Felhasználó!<br><br>A jelszó visszaállítási kódod: <strong>$kod</strong><br>Érvényes: $lejarat-ig<br><br>Üdvözlettel,<br>Historia Csapata";
            $mail->AltBody = "A kódod: $kod\nÉrvényes: $lejarat-ig"; // Szöveges verzió HTML nélküli klienseknek

            // E-mail küldése
            $mail->send();
            $valasz['sikeres'] = true;
        } catch (Exception $e) {
            $valasz['uzenet'] = "Hiba történt az e-mail küldése közben: " . $mail->ErrorInfo;
        }
    } else {
        $valasz['uzenet'] = 'Nincs ilyen email cím regisztrálva!';
    }
} else {
    $valasz['uzenet'] = 'Add meg az email címedet!';
}

// Statement lezárása
if (isset($stmt)) {
    $stmt->close();
}

echo json_encode($valasz);
?>