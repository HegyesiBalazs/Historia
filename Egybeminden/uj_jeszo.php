<?php
session_start();
require_once 'adatbazis.php';

header('Content-Type: application/json');

$kod = $_POST['code'] ?? '';
$uj_jelszo = $_POST['new_password'] ?? '';
$valasz = ['sikeres' => false];

if (!empty($kod) && !empty($uj_jelszo)) {
    // Jelszó adatbázis használata a jelszó módosításhoz
    $stmt = $db->kapcs_jelszo->prepare("SELECT * FROM jelszomodosit WHERE kod = ? AND kod_lejar > NOW()");
    $stmt->bind_param("s", $kod);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $titkosított_jelszo = password_hash($uj_jelszo, PASSWORD_DEFAULT);
        $stmt = $db->kapcs_jelszo->prepare("UPDATE jelszomodosit SET uj_jelszo = ?, kod = NULL, kod_lejar = NULL WHERE kod = ?");
        $stmt->bind_param("ss", $titkosított_jelszo, $kod);
        
        if ($stmt->execute()) {
            // Ha a regisztrációs táblában is frissíteni kell a jelszót
            $stmt_reg = $db->kapcs_reg->prepare("UPDATE regisztralas SET jelszo = ? WHERE jelszomod_id = (SELECT id FROM jelszo_db.jelszomodosit WHERE kod = ?)");
            $stmt_reg->bind_param("ss", $titkosított_jelszo, $kod);
            $stmt_reg->execute();
            
            $valasz['sikeres'] = true;
        }
    } else {
        $valasz['uzenet'] = 'Érvénytelen vagy lejárt kód!';
    }
} else {
    $valasz['uzenet'] = 'Töltsd ki az összes mezőt!';
}

echo json_encode($valasz);
?>