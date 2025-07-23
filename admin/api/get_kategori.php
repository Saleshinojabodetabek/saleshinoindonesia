<?php
include "../config.php";
header('Content-Type: application/json');

// Ambil semua data kategori
$query = "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$result = $conn->query($query);

$kategori = [];

while ($row = $result->fetch_assoc()) {
  $kategori[] = $row;
}

echo json_encode($kategori);
?>
