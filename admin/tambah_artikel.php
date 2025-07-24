<?php
// Aktifkan error reporting untuk debugging (hapus saat live)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "cek_login.php";
include "koneksi.php";

// Proses hanya jika form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data dengan pengamanan
  $judul = $_POST['judul'] ?? '';
  $isi = $_POST['isi'] ?? '';
  $kategori_id = $_POST['kategori_id'] ?? '';

  // Validasi input
  if (empty($judul) || empty($isi) || empty($kategori_id)) {
    die("Semua field wajib diisi.");
  }

  // Cek apakah file gambar diupload
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_file_bersih = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($_FILES['gambar']['name'], PATHINFO_FILENAME));
    $nama_file = time() . "_" . $nama_file_bersih . "." . $ext;

    $target_dir = "uploads/";
    $target_file = $target_dir . $nama_file;

    // Buat folder jika belum ada
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755, true);
    }

    // Upload file ke server
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
      // Simpan data ke database
      $stmt = mysqli_prepare($koneksi, "INSERT INTO artikel (judul, isi, gambar, kategori_id) VALUES (?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "sssi", $judul, $isi, $nama_file, $kategori_id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      // Redirect
      header("Location: dashboard.php");
      exit;
    } else {
      die("Gagal upload gambar. Pastikan folder 'uploads/' bisa ditulis.");
    }
  } else {
    die("Gambar wajib diupload.");
  }

} else {
  // Jika file diakses langsung, beri pesan aman
  echo "Akses langsung tidak diperbolehkan.";
  exit;
}
?>
