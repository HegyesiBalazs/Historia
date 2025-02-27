<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Historia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}
?>