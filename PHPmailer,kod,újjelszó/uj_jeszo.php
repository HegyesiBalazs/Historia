<?php
session_start();
require_once 'adatbazis.php';

header('Content-Type: application/json');

$code = $_POST['code'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$response = ['success' => false];

if (!empty($code) && !empty($new_password)) {
    $stmt = $conn->prepare("SELECT * FROM jelszomodosit WHERE kod = ? AND kod_lejar > NOW()");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE jelszomodosit SET uj_jelszo = ?, kod = NULL, kod_lejar = NULL WHERE kod = ?");
        $stmt->bind_param("ss", $hashed_password, $code);
        $stmt->execute();

        $response['success'] = true;
    } else {
        $response['message'] = 'Érvénytelen vagy lejárt kód!';
    }
} else {
    $response['message'] = 'Töltsd ki az összes mezőt!';
}

echo json_encode($response);
?>