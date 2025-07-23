<?php include "cek_login.php"; ?>
<?php
// Ambil daftar kategori dari database
include "koneksi.php"; // pastikan file ini koneksi ke database
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Tambah Artikel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2>Tambah Artikel Baru</h2>
    <form action="proses_tambah.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Isi Artikel</label>
        <textarea name="isi" class="form-control" rows="6" required></textarea>
      </div>
      <div class="mb-3">
        <label>Gambar (jpg/png)</label>
        <input type="file" name="gambar" class="form-control" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label>Kategori</label>
        <select name="kategori_id" class="form-control" required>
          <option value="">-- Pilih Kategori --</option>
          <?php while ($row = mysqli_fetch_assoc($kategori)) { ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?></option>
          <?php } ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>
