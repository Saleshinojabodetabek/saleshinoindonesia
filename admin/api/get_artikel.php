<?php
header("Content-Type: application/json");
include "../config.php";

$result = $conn->query("SELECT * FROM artikel ORDER BY id DESC");
$data = [];

while ($row = $result->fetch_assoc()) {
  $row['gambar'] = "https://saleshinoindonesia.com/admin/uploads/" . $row['gambar']; // sesuaikan domain kamu
  $data[] = $row;
}

echo json_encode($data);
?>
