<?php
session_start();
include 'adatbazis.php';
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $jelszo = $_POST['jelszo'];

    $stmt = $conn->prepare("SELECT id, keresztnev, jelszo FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $keresztnev, $hashed_jelszo);
        $stmt->fetch();
        if (password_verify($jelszo, $hashed_jelszo)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['keresztnev'] = $keresztnev;
            echo "Sikeres bejelentkezés!";
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen email regisztrálva!";
    }
}
?>
