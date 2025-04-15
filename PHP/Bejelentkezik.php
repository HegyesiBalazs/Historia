<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'adatbazis.php';

header('Content-Type: application/json');

$valasz = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $jelszo = $_POST['jelszo'] ?? '';
    $emlekezzRam = isset($_POST['emlekezzRam']) && $_POST['emlekezzRam'] === 'on';

    // Validálás
    if (empty($email) || empty($jelszo)) {
        $valasz['message'] = 'Email és jelszó megadása kötelező!';
        echo json_encode($valasz);
        exit();
    }

    // Adatbázis kapcsolat
    $db = new Adatbazis();
    $kapcsolat = $db->getKapcsolat();

    // Felhasználó ellenőrzése
    $stmt = $kapcsolat->prepare("SELECT id, jelszo, email_hitelesitve FROM regisztralas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (!$user['email_hitelesitve']) {
            $_SESSION['reg_email'] = $email; // Email tárolása új hitelesítéshez
            $valasz['message'] = 'Az email címed nincs hitelesítve! Kérjük, hitelesítsd az email címed.';
            echo json_encode($valasz);
            exit();
        }

        if (password_verify($jelszo, $user['jelszo'])) {
            $_SESSION['user_id'] = $user['id'];
            $valasz['success'] = true;
            $valasz['message'] = 'Sikeres bejelentkezés!';

            // Emlékezz rám funkció (opcionális, cookie-val)
            if ($emlekezzRam) {
                $token = bin2hex(random_bytes(16));
                setcookie('emlekezzRam', $token, time() + 30 * 24 * 3600, '/'); // 30 nap
                $stmt = $kapcsolat->prepare("UPDATE regisztralas SET emlekezz_token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $user['id']);
                $stmt->execute();
            }
        } else {
            $valasz['message'] = 'Hibás jelszó!';
        }
    } else {
        $valasz['message'] = 'Nincs ilyen email cím regisztrálva!';
    }

    $stmt->close();
} else {
    $valasz['message'] = 'Érvénytelen kérés!';
}

echo json_encode($valasz);
exit();
?>