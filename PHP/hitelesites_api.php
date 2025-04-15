<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'adatbazis.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $kod = trim($_POST['kod']);

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Email cím nem található!']);
        exit();
    }

    $db = new Adatbazis();
    $kapcsolat = $db->getKapcsolat();

    $stmt = $kapcsolat->prepare("SELECT * FROM email_hitelesites WHERE email = ? AND kod = ? AND lejarat > NOW()");
    $stmt->bind_param("ss", $email, $kod);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $stmt = $kapcsolat->prepare("UPDATE regisztralas SET email_hitelesitve = TRUE WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $stmt = $kapcsolat->prepare("DELETE FROM email_hitelesites WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Érvénytelen vagy lejárt kód!']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Érvénytelen kérés!']);
}
exit();
?>