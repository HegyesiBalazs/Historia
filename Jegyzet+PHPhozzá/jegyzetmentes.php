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
$jegyzet = trim($_POST['jegyzet'] ?? '');

if (empty($jegyzet)) {
    echo "error:A jegyzet nem lehet üres!";
    exit();
}

// Jegyzet mentése az adatbázisba
$stmt = $db->kapcs_jegyzet->prepare("INSERT INTO jegyzetek (reg_email, jegyzet) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $jegyzet);

if ($stmt->execute()) {
    echo "success:Jegyzet sikeresen mentve!";
} else {
    echo "error:Hiba a mentés során!";
}

$stmt->close();
?>