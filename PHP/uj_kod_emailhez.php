<?php
session_start();
require_once 'adatbazis.php';
require_once 'email_kuldo.php';

header('Content-Type: application/json');
$valasz = ['success' => false];

if (!isset($_GET['email']) || $_GET['email'] !== $_SESSION['reg_email']) {
    $valasz['message'] = 'Érvénytelen email cím!';
    echo json_encode($valasz);
    exit();
}

$email = $_GET['email'];
$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

// Régi kódok törlése
$stmt = $kapcsolat->prepare("DELETE FROM email_hitelesites WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// Új kód generálása
$kod = sprintf("%06d", mt_rand(100000, 999999));
$lejarat = date("Y-m-d H:i:s", strtotime("+10 minutes"));

$stmt = $kapcsolat->prepare("INSERT INTO email_hitelesites (email, kod, lejarat) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $kod, $lejarat);
$stmt->execute();

// Email küldése
$targy = "Új hitelesítő kód";
$uzenet = "Kedves felhasználó!\n\nÚj hitelesítő kódod: $kod\n\nA kód 10 percig érvényes.";
if (kuldo_email($email, $targy, $uzenet)) {
    $valasz['success'] = true;
    $valasz['message'] = "Új kód elküldve!";
} else {
    $valasz['message'] = "Hiba történt az email küldése során.";
}

$stmt->close();
echo json_encode($valasz);
exit();
?>