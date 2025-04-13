<?php
session_start();
header('Content-Type: application/json');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
echo json_encode(['isLoggedIn' => $isLoggedIn]);
?>