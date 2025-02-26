<?php
session_start();
include 'adatbazis.php';
include 'teendok.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Bejelentkezik.php");
    exit();
}

$hibak = [];
$user_id = $_SESSION['user_id'];
$teendok = teendok_felhasznalo_szerint($conn, $user_id);

if (isset($_POST['uj_teendo'])) {
    $teendo = trim($_POST['teendo']);
    if ($teendo == '') {
        $hibak[] = 'A teendő nem lehet üres!';
    } else {
        uj_teendo_mentese($conn, $user_id, $teendo);
        header("Location: index.php");
        exit();
    }
}
?>

