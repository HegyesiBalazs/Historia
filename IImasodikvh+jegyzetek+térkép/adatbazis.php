<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=historia", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Adatbázis kapcsolati hiba: " . $e->getMessage());
}
?>