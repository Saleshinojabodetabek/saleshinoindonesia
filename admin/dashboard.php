<?php
include "cek_login.php";
include "config.php";
$result = $conn->query("SELECT * FROM artikel ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard Artikel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2>Daftar Artikel</h2>
    <a href="tambah_artikel.php" class="btn btn-success mb-3">+ Tambah Artikel</a>
    <a href="logout.php" class="btn btn-danger mb-3 float-end">Logout</a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Judul</th>
          <th>Tanggal</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= $row['tanggal'] ?></td>
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
