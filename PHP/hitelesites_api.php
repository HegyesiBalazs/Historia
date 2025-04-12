<?php
session_start();
require_once 'adatbazis.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $kod = trim($_POST['kod']);

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Email cím nem található!']);
        exit();
    }

    $stmt = $db->kapcs_reg->prepare("SELECT * FROM email_hitelesites WHERE email = ? AND kod = ? AND lejarat > NOW()");
    $stmt->bind_param("ss", $email, $kod);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $stmt = $db->kapcs_reg->prepare("UPDATE regisztralas SET email_hitelesitve = TRUE WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $stmt = $db->kapcs_reg->prepare("DELETE FROM email_hitelesites WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Érvénytelen vagy lejárt kód!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Érvénytelen kérés!']);
}
exit();
?>