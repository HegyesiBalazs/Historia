<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keresztnev = $_POST['keresztnev'];
    $vezeteknev = $_POST['vezeteknev'];
    $email = $_POST['email'];
    $jelszo = password_hash($_POST['jelszo'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO regisztralas (keresztnev, vezeteknev, email, jelszo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $keresztnev, $vezeteknev, $email, $jelszo);
    
    if ($stmt->execute()) {
        echo "Sikeres regisztráció!";
    } else {
        echo "Hiba történt: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
