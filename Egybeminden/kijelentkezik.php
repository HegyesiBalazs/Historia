<?php
session_start(); // Munkamenet indítása

// Munkamenet teljes törlése
session_destroy();

// Átirányítás a kezdőlapra
header("Location: index.html");
exit(); // Script azonnali befejezése
?>