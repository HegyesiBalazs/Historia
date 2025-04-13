<?php
// Hibák naplózása, de ne jelenjenek meg a kimenetben
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Biztosítjuk, hogy ne legyen kimenet a fejléc előtt
ob_start();

session_start();

// Fájlok betöltése ellenőrzéssel
$required_files = ['adatbazis.php', 'email_kuldo.php'];
foreach ($required_files as $file) {
    if (!file_exists($file)) {
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => "Hiányzó fájl: $file"]);
        exit();
    }
    require_once $file;
}

header('Content-Type: application/json');

$valasz = ['success' => false];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Érvénytelen kérés!');
    }

    $vezeteknev = trim($_POST['vezeteknev'] ?? '');
    $keresztnev = trim($_POST['keresztnev'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jelszo = $_POST['jelszo'] ?? '';

    // Validálás
    if (empty($vezeteknev) || empty($keresztnev) || empty($email) || empty($jelszo)) {
        throw new Exception('Minden mezőt ki kell tölteni!');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Érvénytelen email cím!');
    }

    if (strlen($jelszo) < 8) {
        throw new Exception('A jelszónak legalább 8 karakter hosszúnak kell lennie!');
    }

    // Adatbázis kapcsolat
    $db = new Adatbazis();
    $kapcsolat = $db->getKapcsolat();
    if (!$kapcsolat) {
        throw new Exception('Adatbázis kapcsolat sikertelen: ' . mysqli_connect_error());
    }

    // Ellenőrizzük, hogy az email már létezik-e
    $stmt = $kapcsolat->prepare("SELECT id FROM regisztralas WHERE email = ?");
    if (!$stmt) {
        throw new Exception('SQL előkészítési hiba: ' . $kapcsolat->error);
    }
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception('SQL végrehajtási hiba: ' . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception('Ez az email cím már regisztrálva van!');
    }

    // Jelszó hashelése
    $titkosított_jelszo = password_hash($jelszo, PASSWORD_DEFAULT);
    if (!$titkosított_jelszo) {
        throw new Exception('Jelszó hashelési hiba!');
    }

    // Felhasználó mentése
    $stmt = $kapcsolat->prepare("INSERT INTO regisztralas (vezeteknev, keresztnev, email, jelszo, email_hitelesitve) VALUES (?, ?, ?, ?, FALSE)");
    if (!$stmt) {
        throw new Exception('SQL előkészítési hiba: ' . $kapcsolat->error);
    }
    $stmt->bind_param("ssss", $vezeteknev, $keresztnev, $email, $titkosított_jelszo);
    if (!$stmt->execute()) {
        throw new Exception('Hiba történt a regisztráció során: ' . $stmt->error);
    }

    // Hitelesítő kód generálása
    $kod = sprintf("%06d", mt_rand(100000, 999999));
    $lejarat = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $stmt = $kapcsolat->prepare("INSERT INTO email_hitelesites (email, kod, lejarat) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception('SQL előkészítési hiba: ' . $kapcsolat->error);
    }
    $stmt->bind_param("sss", $email, $kod, $lejarat);
    if (!$stmt->execute()) {
        throw new Exception('Hiba történt a hitelesítő kód mentése során: ' . $stmt->error);
    }

    // Email küldése
    $targy = "Email Hitelesítés";
    $uzenet = "Kedves $vezeteknev $keresztnev!\n\nA hitelesítő kódod: $kod\n\nA kód 10 percig érvényes.\nÜdvözlettel,\nHistoria Csapat";
    
    if (!kuldo_email($email, $targy, $uzenet)) {
        throw new Exception('Hiba történt az email küldése során!');
    }

    $_SESSION['reg_email'] = $email; // Email tárolása a hitelesítéshez
    $valasz['success'] = true;
    $valasz['message'] = 'Sikeres regisztráció! Kérjük, hitelesítsd az email címed.';

    $stmt->close();
} catch (Exception $e) {
    $valasz['message'] = $e->getMessage();
}

ob_end_clean();
echo json_encode($valasz);
exit();
?>