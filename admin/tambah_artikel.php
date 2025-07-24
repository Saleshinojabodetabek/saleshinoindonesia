<?php
// Aktifkan error reporting saat development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cek login dan koneksi
include "cek_login.php";
include "koneksi.php";

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $judul       = trim($_POST['judul'] ?? '');
  $isi         = trim($_POST['isi'] ?? '');
  $kategori_id = intval($_POST['kategori_id'] ?? 0);

  if (empty($judul) || empty($isi) || $kategori_id <= 0) {
    $error = "Semua field wajib diisi.";
  } elseif (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    $error = "Gambar wajib diupload.";
  } else {
    // Proses upload gambar
    $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    $nama_file_bersih = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($_FILES['gambar']['name'], PATHINFO_FILENAME));
    $nama_file = time() . "_" . $nama_file_bersih . "." . $ext;

    $target_dir = __DIR__ . "/uploads/";
    $target_file = $target_dir . $nama_file;

    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
      $stmt = mysqli_prepare($koneksi, "INSERT INTO artikel (judul, isi, gambar, kategori_id) VALUES (?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "sssi", $judul, $isi, $nama_file, $kategori_id);

      if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php?success=1");
        exit;
      } else {
        $error = "Gagal menyimpan artikel. Error DB: " . mysqli_error($koneksi);
      }
      mysqli_stmt_close($stmt);
    } else {
      $error = "Gagal upload gambar. Pastikan folder 'uploads/' bisa ditulis.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Artikel</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    form {
      max-width: 600px;
      margin: auto;
    }
    input, textarea, select, button {
      display: block;
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
    }
    .error {
      color: red;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <h2>Tambah Artikel Baru</h2>

  <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <label for="judul">Judul Artikel:</label>
    <input type="text" name="judul" id="judul" required>

    <label for="isi">Isi Artikel:</label>
    <textarea name="isi" id="isi" rows="10" required></textarea>

    <label for="kategori_id">Kategori:</label>
    <select name="kategori_id" id="kategori_id" required>
      <option value="">-- Pilih Kategori --</option>
      <?php
        $kategori = mysqli_query($koneksi, "SELECT id, nama FROM kategori ORDER BY nama ASC");
        while ($row = mysqli_fetch_assoc($kategori)) {
          echo "<option value='{$row['id']}'>{$row['nama']}</option>";
        }
      ?>
    </select>

    <label for="gambar">Upload Gambar:</label>
    <input type="file" name="gambar" id="gambar" accept="image/*" required>

    <button type="submit">Simpan Artikel</button>
  </form>
</body>
</html>
