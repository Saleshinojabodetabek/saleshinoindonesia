<?php
include "../config.php";
header('Content-Type: application/json');

// Ambil parameter pencarian dan filter kategori (jika ada)
$search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;
$kategori = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : null;

// Query dasar dengan JOIN ke tabel kategori
$query = "SELECT 
            a.id, 
            a.judul, 
            a.isi, 
            a.gambar, 
            a.tanggal, 
            k.nama AS kategori 
          FROM artikel a 
          LEFT JOIN kategori k ON a.kategori_id = k.id";

$conditions = [];

// Jika ada pencarian judul atau isi
if ($search) {
  $conditions[] = "(a.judul LIKE '$search' OR a.isi LIKE '$search')";
}

// Jika ada filter kategori (berdasarkan nama kategori)
if ($kategori) {
  $conditions[] = "k.nama = '$kategori'";
}

// Gabungkan kondisi
if (!empty($conditions)) {
  $query .= " WHERE " . implode(" AND ", $conditions);
}

// Urutkan berdasarkan artikel terbaru
$query .= " ORDER BY a.tanggal DESC";

// Eksekusi query
$result = $conn->query($query);

$artikel = [];

// Loop hasil dan ubah URL gambar jadi lengkap
while ($row = $result->fetch_assoc()) {
  $row['gambar'] = 'https://saleshinoindonesia.com/admin/uploads/' . $row['gambar'];
  $artikel[] = $row;
}

// Output dalam format JSON
echo json_encode($artikel);
?>
