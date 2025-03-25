<?php
session_start();
require_once 'adatbazis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $jelszo = trim($_POST['jelszo']);

    $stmt = $pdo->prepare("SELECT * FROM regisztralas WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($jelszo, $user['jelszo'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['keresztnev'];
    } else {
        $_SESSION['login_error'] = "Hibás email vagy jelszó!";
    }
}

header("Location: index.php");
exit();
?>