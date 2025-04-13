<?php
session_start();
header('Content-Type: application/json');

$valasz = ['success' => true, 'message' => 'Sikeresen kijelentkeztél!'];
session_destroy();
echo json_encode($valasz);
?>