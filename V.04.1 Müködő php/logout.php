<?php
// logout.php - Kijelentkezés
session_start();
session_destroy();
header("Location: index.html");
exit();
?>