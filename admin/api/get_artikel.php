<?php
header("Content-Type: application/json");
$host = "localhost";
$user = "u868657420_root";
$pass = "Natanael110405";
$db   = "u868657420_db_dealer_hino";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die(json_encode(["error" => "Koneksi gagal"]));
}

$sql = "SELECT * FROM artikel ORDER BY tanggal DESC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
  $row['gambar'] = 'https://saleshinoindonesia.com/admin/uploads/' . $row['gambar']; // URL gambar
  $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
