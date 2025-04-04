<?php
session_start();
require_once 'adatbazis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vezeteknev = trim($_POST['vezeteknev']);
    $keresztnev = trim($_POST['keresztnev']);
    $email = trim($_POST['email']);
    $jelszo = password_hash(trim($_POST['jelszo']), PASSWORD_DEFAULT);

    // Ellenőrzés, hogy az email már létezik-e a regisztralas_db-ben
    $stmt = $db->kapcs_reg->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        // Ha az email már létezik
        $_SESSION['reg_error'] = "Ez az email már regisztrálva van!";
    } else {
        // Új felhasználó mentése az adatbázisba
        $stmt = $db->kapcs_reg->prepare("INSERT INTO regisztralas (vezeteknev, keresztnev, email, jelszo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $vezeteknev, $keresztnev, $email, $jelszo);
        
        if ($stmt->execute()) {
            // Sikeres regisztráció esetén üzenet
            $_SESSION['reg_success'] = "Sikeres regisztráció! Jelentkezz be!";
        } else {
            // Hiba esetén üzenet
            $_SESSION['reg_error'] = "Hiba történt a regisztráció során.";
        }
    }
}

// Átirányítás a kezdőlapra
header("Location: index.html");
exit();
?>