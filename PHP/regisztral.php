<?php
session_start();
require_once 'adatbazis.php';
require_once 'email_kuldo.php'; // Email küldő függvények

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vezeteknev = trim($_POST['vezeteknev']);
    $keresztnev = trim($_POST['keresztnev']);
    $email = trim($_POST['email']);
    $jelszo = password_hash(trim($_POST['jelszo']), PASSWORD_DEFAULT);

    // Ellenőrzés, hogy az email már létezik-e
    $stmt = $db->kapcs_reg->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $_SESSION['reg_error'] = "Ez az email már regisztrálva van!";
    } else {
        // Felhasználó mentése az adatbázisba, email_hitelesitve = FALSE
        $stmt = $db->kapcs_reg->prepare("INSERT INTO regisztralas (vezeteknev, keresztnev, email, jelszo, email_hitelesitve) VALUES (?, ?, ?, ?, FALSE)");
        $stmt->bind_param("ssss", $vezeteknev, $keresztnev, $email, $jelszo);

        if ($stmt->execute()) {
            // Hitelesítő kód generálása és mentése
            $kod = sprintf("%06d", mt_rand(100000, 999999)); // 6 jegyű kód
            $lejarat = date("Y-m-d H:i:s", strtotime("+10 minutes")); // 10 percig érvényes

            $stmt = $db->kapcs_reg->prepare("INSERT INTO email_hitelesites (email, kod, lejarat) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $kod, $lejarat);
            $stmt->execute();

            // Email küldése
            $targy = "Email hitelesítés";
            $uzenet = "Kedves $keresztnev!\n\nA regisztrációhoz szükséges hitelesítő kód: $kod\n\nA kód 10 percig érvényes.";
            if (kuldo_email($email, $targy, $uzenet)) {
                $_SESSION['reg_email'] = $email;
                header("Location: hitelesites.php");
                exit();
            } else {
                $_SESSION['reg_error'] = "Hiba történt az email küldése során.";
            }
        } else {
            $_SESSION['reg_error'] = "Hiba történt a regisztráció során.";
        }
    }
}

header("Location: index.html");
exit();
?>