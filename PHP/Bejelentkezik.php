<?php
session_start();
require_once 'adatbazis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $jelszo = trim($_POST['jelszo']);

    // Regisztrációs adatbázis használata a felhasználó ellenőrzéséhez
    $stmt = $db->kapcs_reg->prepare("SELECT * FROM regisztralas WHERE email = ? AND email_hitelesitve = TRUE");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $eredmeny = $stmt->get_result();
    $felhasznalo = $eredmeny->fetch_assoc();

    if ($felhasznalo && password_verify($jelszo, $felhasznalo['jelszo'])) {
        // Sikeres bejelentkezés esetén session változók beállítása
        $_SESSION['user_id'] = $felhasznalo['id'];
        $_SESSION['username'] = $felhasznalo['keresztnev'];
    } else {
        // Hibaüzenet tárolása session-ben
        $_SESSION['login_error'] = "Hibás email, jelszó vagy az email nincs hitelesítve!";
    }
}

// Átirányítás az index.html-re
header("Location: index.html");
exit();
?>