<?php
// Koneksi ke database
$host = "localhost";
$user = "u868657420_root";
$pass = "Natanael110405";
$db   = "u868657420_db_dealer_hino";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Koneksi gagal"]);
    exit;
}

// Ambil data kategori
$sql = "SELECT id, nama FROM kategori ORDER BY nama ASC";
$result = $conn->query($sql);

$kategori = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kategori[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($kategori);
