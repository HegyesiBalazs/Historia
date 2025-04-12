<?php
session_start();
require_once 'adatbazis.php';
require_once 'email_kuldo.php';

if (!isset($_GET['email']) || $_GET['email'] !== $_SESSION['reg_email']) {
    header("Location: index.html");
    exit();
}

$email = $_GET['email'];

// Régi kódok törlése
$stmt = $db->kapcs_reg->prepare("DELETE FROM email_hitelesites WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// Új kód generálása
$kod = sprintf("%06d", mt_rand(100000, 999999));
$lejarat = date("Y-m-d H:i:s", strtotime("+10 minutes"));

$stmt = $db->kapcs_reg->prepare("INSERT INTO email_hitelesites (email, kod, lejarat) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $kod, $lejarat);
$stmt->execute();

// Email küldése
$targy = "Új hitelesítő kód";
$uzenet = "Kedves felhasználó!\n\nÚj hitelesítő kódod: $kod\n\nA kód 10 percig érvényes.";
if (kuldo_email($email, $targy, $uzenet)) {
    $_SESSION['uzenet'] = "Új kód elküldve!";
} else {
    $_SESSION['uzenet'] = "Hiba történt az email küldése során.";
}

header("Location: index.html");
exit();
?>