<?php
include "cek_login.php";
include "config.php";

$judul = $_POST['judul'];
$isi = $_POST['isi'];
$kategori_id = $_POST['kategori_id'];

// Dapatkan nama file asli dan bersihkan
$ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
$nama_file_bersih = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($_FILES['gambar']['name'], PATHINFO_FILENAME));
$nama_file = time() . "_" . $nama_file_bersih . "." . $ext;

// Lokasi simpan
$target_dir = "uploads/";
$target_file = $target_dir . $nama_file;

if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
  $stmt = $conn->prepare("INSERT INTO artikel (judul, isi, gambar, kategori_id) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssi", $judul, $isi, $nama_file, $kategori_id);
  $stmt->execute();
  header("Location: dashboard.php");
} else {
  echo "Gagal upload gambar. Periksa permission folder uploads.";
}
?>
