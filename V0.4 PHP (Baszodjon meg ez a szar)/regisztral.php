<?php
include 'adatbazis.php';
if (isset($_POST['register'])) {
    $keresztnev = $_POST['keresztnev'];
    $vezeteknev = $_POST['vezeteknev'];
    $email = $_POST['email'];
    $jelszo = password_hash($_POST['jelszo'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO regisztralas (keresztnev, vezeteknev, email, jelszo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $keresztnev, $vezeteknev, $email, $jelszo);
    $stmt->execute();
    echo "Sikeres regisztráció!";
}
?>
