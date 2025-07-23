<?php
include "../config.php";

header('Content-Type: application/json');

$search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;
$kategori = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : null;

$query = "SELECT id, judul, isi, gambar, tanggal, kategori FROM artikel";
$conditions = [];

if ($search) {
  $conditions[] = "judul LIKE '$search' OR isi LIKE '$search'";
}
if ($kategori) {
  $conditions[] = "kategori = '$kategori'";
}

if (!empty($conditions)) {
  $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY id DESC";
$result = $conn->query($query);

$artikel = [];

while ($row = $result->fetch_assoc()) {
  $row['gambar'] = 'https://saleshinoindonesia.com/admin/uploads/' . $row['gambar'];
  $artikel[] = $row;
}

echo json_encode($artikel);
?>
