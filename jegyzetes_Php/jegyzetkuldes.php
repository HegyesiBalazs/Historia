<?php
session_start();
require_once 'adatbazis.php';

header('Content-Type: text/plain');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "error:Nincs bejelentkezve!";
    exit();
}

// Felhasználó email címének lekérdezése
$db = new Adatbazis();
$stmt = $db->kapcs_reg->prepare("SELECT email FROM regisztralas WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "error:Felhasználó nem található!";
    exit();
}

$email = $user['email'];

// Legutóbbi jegyzet lekérdezése
$stmt = $db->kapcs_jegyzet->prepare("SELECT jegyzet FROM jegyzetek WHERE reg_email = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$jegyzet = $result->fetch_assoc();

if (!$jegyzet) {
    echo "error:Nincs mentett jegyzet!";
    exit();
}

$jegyzet_text = $jegyzet['jegyzet'];

// Email küldése
$to = $email;
$subject = "A te jegyzeted";
$message = "Kedves Felhasználó,\n\nÍme a jegyzeted:\n\n" . $jegyzet_text . "\n\nÜdvözlettel,\nHistoria Csapat";
$headers = "From: noreply@historia.com";

if (mail($to, $subject, $message, $headers)) {
    // Jegyzet törlése az adatbázisból
    $stmt = $db->kapcs_jegyzet->prepare("DELETE FROM jegyzetek WHERE reg_email = ? AND jegyzet = ?");
    $stmt->bind_param("ss", $email, $jegyzet_text);
    $stmt->execute();

    echo "success:Jegyzet elküldve és törölve!";
} else {
    echo "error:Hiba az email küldése során!";
}

$stmt->close();
?>