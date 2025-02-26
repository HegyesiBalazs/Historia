<?php
include 'adatbazis.php';
function teendok_felhasznalo_szerint($conn, $user_id) {
    $stmt = $conn->prepare("SELECT id, teendo FROM teendo WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function uj_teendo_mentese($conn, $user_id, $teendo) {
    $stmt = $conn->prepare("INSERT INTO teendo (user_id, teendo) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $teendo);
    return $stmt->execute();
}
?>