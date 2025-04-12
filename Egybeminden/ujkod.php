<?php
session_start();
require_once 'adatbazis.php';

// PHPMailer betöltése
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Composer autoload fájl, ha Composert használsz

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$valasz = ['sikeres' => false];

if (!empty($email)) {
    // Ellenőrzés a regisztralas_db-ben, hogy létezik-e az email
    $stmt = $db->kapcs_reg->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $kod = bin2hex(random_bytes(4)); // 8 karakteres véletlenszerű kód
        $lejarat = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 óra érvényesség

        // Kód mentése a jelszo_db-be, reg_email mezővel
        $stmt = $db->kapcs_jelszo->prepare("INSERT INTO jelszomodosit (kod, kod_lejar, reg_email) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE kod = ?, kod_lejar = ?");
        $stmt->bind_param("sssss", $kod, $lejarat, $email, $kod, $lejarat);
        $stmt->execute();

        // PHPMailer beállítása és e-mail küldése
        $mail = new PHPMailer(true); // True: kivételkezelés bekapcsolása

        try {
            // SMTP beállítások (pl. Gmail használata esetén)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP szerver címe
            $mail->SMTPAuth = true;
            $mail->Username = 'te.email@gmail.com'; // A te e-mail címed
            $mail->Password = 'jelszo_vagy_app_jelszo'; // Jelszó vagy alkalmazásjelszó
            $mail->SMTPSecure = 'tls'; // Titkosítás: tls vagy ssl
            $mail->Port = 587; // Portszám

            // Feladó és címzett
            $mail->setFrom('noreply@historia.hu', 'Historia'); // Feladó e-mail és név
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

echo json_encode($valasz);
?>