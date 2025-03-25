<?php
session_start();
require_once 'adatbazis.php';

// PHPMailer betöltése
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Composer autoload fájl, ha Composert használsz

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$response = ['success' => false];

if (!empty($email)) {
    $stmt = $conn->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $code = bin2hex(random_bytes(4)); // 8 karakteres véletlenszerű kód
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 óra érvényesség

        // Kód mentése az adatbázisba
        $stmt = $conn->prepare("UPDATE jelszomodosit SET kod = ?, kod_lejar = ? WHERE reg_email = ?");
        $stmt->bind_param("sss", $code, $expires, $email);
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
            $mail->Body = "Kedves Felhasználó!<br><br>A jelszó visszaállítási kódod: <strong>$code</strong><br>Érvényes: $expires-ig<br><br>Üdvözlettel,<br>Historia Csapata";
            $mail->AltBody = "A kódod: $code\nÉrvényes: $expires-ig"; // Szöveges verzió HTML nélküli klienseknek

            // E-mail küldése
            $mail->send();
            $response['success'] = true;
        } catch (Exception $e) {
            $response['message'] = "Hiba történt az e-mail küldése közben: " . $mail->ErrorInfo;
        }
    } else {
        $response['message'] = 'Nincs ilyen email cím regisztrálva!';
    }
} else {
    $response['message'] = 'Add meg az email címedet!';
}

echo json_encode($response);
?>