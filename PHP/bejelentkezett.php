<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
echo json_encode(['isLoggedIn' => $isLoggedIn]);
?>