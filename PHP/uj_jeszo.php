<?php
session_start();
require_once 'adatbazis.php';

header('Content-Type: application/json');

$kod = $_POST['code'] ?? '';
$uj_jelszo = $_POST['new_password'] ?? '';
$valasz = ['sikeres' => false];

error_log("uj_jeszo.php: Kód: $kod, Új jelszó: $uj_jelszo"); // Naplózás

$db = new Adatbazis();
$kapcsolat = $db->getKapcsolat();

if (!empty($kod) && !empty($uj_jelszo)) {
    $stmt = $kapcsolat->prepare("SELECT * FROM jelszomodosit WHERE kod = ? AND kod_lejar > NOW()");
    $stmt->bind_param("s", $kod);
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny->num_rows > 0) {
        $sor = $eredmeny->fetch_assoc();
        $reg_email = $sor['reg_email'];
        $titkosított_jelszo = password_hash($uj_jelszo, PASSWORD_DEFAULT);

        error_log("uj_jeszo.php: Titkosított jelszó hossza: " . strlen($titkosított_jelszo)); // Jelszó hossza

        $stmt = $kapcsolat->prepare("UPDATE jelszomodosit SET uj_jelszo = ?, kod = NULL, kod_lejar = NULL WHERE kod = ?");
        $stmt->bind_param("ss", $titkosított_jelszo, $kod);
        
        if ($stmt->execute()) {
            $stmt_reg = $kapcsolat->prepare("UPDATE regisztralas SET jelszo = ? WHERE email = ?");
            $stmt_reg->bind_param("ss", $titkosított_jelszo, $reg_email);
            if (!$stmt_reg->execute()) {
                error_log("SQL hiba a regisztralas frissítésekor: " . $stmt_reg->error); // SQL hiba naplózása
                $valasz['uzenet'] = "Hiba történt a regisztralas tábla frissítése közben!";
                echo json_encode($valasz);
                exit();
            }
            
            $valasz['sikeres'] = true;
        } else {
            error_log("SQL hiba a jelszomodosit frissítésekor: " . $stmt->error); // SQL hiba naplózása
            $valasz['uzenet'] = 'Hiba történt a jelszó módosítása közben!';
        }
    } else {
        $valasz['uzenet'] = 'Érvénytelen vagy lejárt kód!';
    }
} else {
    $valasz['uzenet'] = 'Töltsd ki az összes mezőt!';
}

if (isset($stmt)) {
    $stmt->close();
}
if (isset($stmt_reg)) {
    $stmt_reg->close();
}

echo json_encode($valasz);
?>