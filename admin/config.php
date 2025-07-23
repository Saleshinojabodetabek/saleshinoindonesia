<?php
$host = 'localhost';
$user = 'u868657420_root';
$pass = 'Natanael110405';
$db = 'u868657420_db_dealer_hino';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('Koneksi gagal: ' . $conn->connect_error);
?>