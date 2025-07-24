<?php
// Aktifkan error reporting untuk debugging (hapus saat sudah live)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "cek_login.php";
include "koneksi.php"; // Pastikan ini file koneksi dengan variabel $koneksi

// Ambil data dari form
$judul = $_POST['judul'];
$isi = $_POST['isi'];
$kategori_id = $_POST['kategori_id'];

// Validasi input (opsional tambahan)
if (empty($judul) || empty($isi) || empty($kategori_id)) {
  die("Semua field wajib diisi.");
}

// Proses upload gambar
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
  $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
  $nama_file_bersih = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($_FILES['gambar']['name'], PATHINFO_FILENAME));
  $nama_file = time() . "_" . $nama_file_bersih . "." . $ext;

  $target_dir = "uploads/";
  $target_file = $target_dir . $nama_file;

  // Pastikan folder uploads ada
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
  }

  // Simpan file gambar ke folder
  if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
    // Simpan ke database
    $stmt = mysqli_prepare($koneksi, "INSERT INTO artikel (judul, isi, gambar, kategori_id) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $judul, $isi, $nama_file, $kategori_id);
    mysqli_stmt_execute($stmt);

    // Redirect ke dashboard
    header("Location: dashboard.php");
    exit;
  } else {
    die("Gagal upload gambar. Pastikan folder 'uploads/' memiliki permission yang benar.");
  }
} else {
  die("Gambar wajib diupload.");
}
?>
