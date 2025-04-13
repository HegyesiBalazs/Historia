<?php
session_start();
require_once 'adatbazis.php';

header('Content-Type: application/json');

$kod = $_POST['code'] ?? '';
$uj_jelszo = $_POST['new_password'] ?? '';
$valasz = ['sikeres' => false];

// Adatbázis kapcsolat inicializálása
$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

if (!empty($kod) && !empty($uj_jelszo)) {
    // Kód ellenőrzése a jelszomodosit táblában
    $stmt = $kapcsolat->prepare("SELECT * FROM jelszomodosit WHERE kod = ? AND kod_lejar > NOW()");
    $stmt->bind_param("s", $kod);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $sor = $eredmeny->fetch_assoc();
        $reg_email = $sor['reg_email']; // Email cím lekérése a jelszomodosit táblából
        $titkosított_jelszo = password_hash($uj_jelszo, PASSWORD_DEFAULT);

        // Jelszó frissítése a jelszomodosit táblában
        $stmt = $kapcsolat->prepare("UPDATE jelszomodosit SET uj_jelszo = ?, kod = NULL, kod_lejar = NULL WHERE kod = ?");
        $stmt->bind_param("ss", $titkosított_jelszo, $kod);
        
        if ($stmt->execute()) {
            // Jelszó frissítése a regisztralas táblában
            $stmt_reg = $kapcsolat->prepare("UPDATE regisztralas SET jelszo = ? WHERE email = ?");
            $stmt_reg->bind_param("ss", $titkosított_jelszo, $reg_email);
            $stmt_reg->execute();
            
            $valasz['sikeres'] = true;
        } else {
            $valasz['uzenet'] = 'Hiba történt a jelszó módosítása közben!';
        }
    } else {
        $valasz['uzenet'] = 'Érvénytelen vagy lejárt kód!';
    }
} else {
    $valasz['uzenet'] = 'Töltsd ki az összes mezőt!';
}

// Statement lezárása
if (isset($stmt)) {
    $stmt->close();
}
if (isset($stmt_reg)) {
    $stmt_reg->close();
}

echo json_encode($valasz);
?>