<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $jelszo = $_POST['jelszo'];

    $sql = "SELECT id, keresztnev, jelszo FROM regisztralas WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $keresztnev, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($jelszo, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['keresztnev'] = $keresztnev;
            echo "Sikeres bejelentkezés!";
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen felhasználó!";
    }
    
    $stmt->close();
    $conn->close();
}
?>