<?php
include "cek_login.php";
include "config.php";

$judul = $_POST['judul'];
$isi = $_POST['isi'];

$nama_file = $_FILES['gambar']['name'];
$tmp_file = $_FILES['gambar']['tmp_name'];
$upload_error = $_FILES['gambar']['error'];

$target_dir = "uploads/";
$target_file = $target_dir . time() . "_" . basename($nama_file);

if ($upload_error !== UPLOAD_ERR_OK) {
  echo "Terjadi kesalahan saat upload file. Error kode: " . $upload_error;
  exit;
}

if (!is_dir($target_dir)) {
  mkdir($target_dir, 0755, true); // buat folder jika belum ada
}

if (move_uploaded_file($tmp_file, $target_file)) {
  $gambar = $target_file; // simpan path relatif
  $stmt = $conn->prepare("INSERT INTO artikel (judul, isi, gambar) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $judul, $isi, $gambar);
  $stmt->execute();
  header("Location: dashboard.php");
} else {
  echo "❌ Gagal upload gambar. Pastikan folder uploads/ bisa ditulis.";
}
?>
