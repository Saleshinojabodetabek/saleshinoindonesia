<?php
include "cek_login.php";
include "config.php";

// Ambil data artikel beserta nama kategori-nya
$query = "
  SELECT a.*, k.nama_kategori 
  FROM artikel a
  LEFT JOIN kategori k ON a.kategori_id = k.id
  ORDER BY a.id DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Artikel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Daftar Artikel</h2>
      <div>
        <a href="change_password.php" class="btn btn-warning me-2">🔑 Ganti Password</a>
        <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
      </div>
    </div>

    <a href="tambah_artikel.php" class="btn btn-success mb-3">+ Tambah Artikel</a>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Judul</th>
          <th>Kategori</th>
          <th>Tanggal</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= htmlspecialchars($row['nama_kategori'] ?? 'Tidak ada') ?></td>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td>
              <?php 
                $gambar_path = "uploads/" . $row['gambar'];
                if (!empty($row['gambar']) && file_exists($gambar_path)):
              ?>
                <img src="<?= $gambar_path ?>" width="100">
              <?php else: ?>
                <em>Gambar tidak tersedia</em>
              <?php endif; ?>
            </td>
            <td>
              <a href="edit_artikel.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="hapus_artikel.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
