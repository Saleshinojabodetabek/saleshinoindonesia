<?php

include "../config.php";

header('Content-Type: application/json');

$query = "SELECT id, judul, isi, gambar, tanggal FROM artikel ORDER BY id DESC";
$result = $conn->query($query);

$artikel = [];

while ($row = $result->fetch_assoc()) {
  $row['gambar'] = 'https://saleshinoindonesia.com/admin/uploads/' . $row['gambar'];
  $artikel[] = $row;
}

echo json_encode($artikel);
?>
