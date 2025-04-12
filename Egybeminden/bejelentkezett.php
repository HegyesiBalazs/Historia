<?php
session_start();
header('Content-Type: text/plain');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
echo $isLoggedIn ? "true" : "false";
?>