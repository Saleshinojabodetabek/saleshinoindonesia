<?php
include "cek_login.php";
include "config.php";

$judul = $_POST['judul'];
$isi = $_POST['isi'];

$nama_file = $_FILES['gambar']['name'];
$tmp_file = $_FILES['gambar']['tmp_name'];
$target_dir = "uploads/";
$target_file = $target_dir . time() . "_" . basename($nama_file);

if (move_uploaded_file($tmp_file, $target_file)) {
  $gambar = basename($target_file);
  $stmt = $conn->prepare("INSERT INTO artikel (judul, isi, gambar) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $judul, $isi, $gambar);
  $stmt->execute();
  header("Location: dashboard.php");
} else {
  echo "Gagal upload gambar.";
}
?>
