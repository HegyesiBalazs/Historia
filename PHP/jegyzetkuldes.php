<?php
session_start();
require_once 'adatbazis.php';

header('Content-Type: application/json');

$valasz = ['success' => false];

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $valasz['message'] = 'Nincs bejelentkezve!';
    echo json_encode($valasz);
    exit();
}

// Adatbázis kapcsolat inicializálása
$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

// Felhasználó email címének lekérdezése
$stmt = $kapcsolat->prepare("SELECT email FROM regisztralas WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $valasz['message'] = 'Felhasználó nem található!';
    echo json_encode($valasz);
    exit();
}

$email = $user['email'];

// Legutóbbi jegyzet lekérdezése
$stmt = $kapcsolat->prepare("SELECT jegyzet FROM jegyzetek WHERE reg_email = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$jegyzet = $result->fetch_assoc();

if (!$jegyzet) {
    $valasz['message'] = 'Nincs mentett jegyzet!';
    echo json_encode($valasz);
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
    $stmt = $kapcsolat->prepare("DELETE FROM jegyzetek WHERE reg_email = ? AND jegyzet = ?");
    $stmt->bind_param("ss", $email, $jegyzet_text);
    $stmt->execute();

    $valasz['success'] = true;
    $valasz['message'] = 'Jegyzet elküldve és törölve!';
} else {
    $valasz['message'] = 'Hiba az email küldése során!';
}

// Statement lezárása
$stmt->close();

echo json_encode($valasz);
?>