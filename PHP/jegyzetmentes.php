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
$jegyzet = trim($_POST['jegyzet'] ?? '');

if (empty($jegyzet)) {
    $valasz['message'] = 'A jegyzet nem lehet üres!';
    echo json_encode($valasz);
    exit();
}

// Jegyzet mentése az adatbázisba
$stmt = $kapcsolat->prepare("INSERT INTO jegyzetek (reg_email, jegyzet) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $jegyzet);

if ($stmt->execute()) {
    $valasz['success'] = true;
    $valasz['message'] = 'Jegyzet sikeresen mentve!';
} else {
    $valasz['message'] = 'Hiba a mentés során!';
}

// Statement lezárása
$stmt->close();

echo json_encode($valasz);
?>