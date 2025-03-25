<?php
session_start();
require_once 'adatbazis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vezeteknev = trim($_POST['vezeteknev']);
    $keresztnev = trim($_POST['keresztnev']);
    $email = trim($_POST['email']);
    $jelszo = password_hash(trim($_POST['jelszo']), PASSWORD_DEFAULT);

    // Ellenőrzés, hogy az email már létezik-e
    $stmt = $pdo->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['reg_error'] = "Ez az email már regisztrálva van!";
    } else {
        // Felhasználó mentése az adatbázisba
        $stmt = $pdo->prepare("INSERT INTO regisztralas (vezeteknev, keresztnev, email, jelszo) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$vezeteknev, $keresztnev, $email, $jelszo])) {
            $_SESSION['reg_success'] = "Sikeres regisztráció! Jelentkezz be!";
        } else {
            $_SESSION['reg_error'] = "Hiba történt a regisztráció során.";
        }
    }
}

header("Location: index.php");
exit();
?>