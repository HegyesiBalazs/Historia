<?php
$conn = new mysqli("localhost", "root", "", "Historia");
if ($conn->connect_error) die("Kapcsolódási hiba: " . $conn->connect_error);
?>