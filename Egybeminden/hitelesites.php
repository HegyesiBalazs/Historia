<?php
session_start();
require_once 'adatbazis.php';

if (!isset($_SESSION['reg_email'])) {
    header("Location: index.html");
    exit();
}

$email = $_SESSION['reg_email'];
$uzenet = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kod = trim($_POST['kod']);

    // Kód ellenőrzése
    $stmt = $db->kapcs_reg->prepare("SELECT * FROM email_hitelesites WHERE email = ? AND kod = ? AND lejarat > NOW()");
    $stmt->bind_param("ss", $email, $kod);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        // Email hitelesítése
        $stmt = $db->kapcs_reg->prepare("UPDATE regisztralas SET email_hitelesitve = TRUE WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Kód törlése
        $stmt = $db->kapcs_reg->prepare("DELETE FROM email_hitelesites WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $_SESSION['reg_success'] = "Sikeres hitelesítés! Jelentkezz be!";
        unset($_SESSION['reg_email']);
        header("Location: index.html");
        exit();
    } else {
        $uzenet = "Érvénytelen vagy lejárt kód!";
    }
}
?>