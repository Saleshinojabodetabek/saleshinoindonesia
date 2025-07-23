<?php
include "cek_login.php";
include "config.php";

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM artikel WHERE id = $id");
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Artikel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2>Edit Artikel</h2>
    <form action="proses_edit.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $data['id'] ?>">
      <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Isi Artikel</label>
        <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']) ?></textarea>
      </div>
      <div class="mb-3">
        <label>Gambar Lama</label><br>
        <img src="uploads/<?= $data['gambar'] ?>" width="200"><br><br>
        <label>Ganti Gambar (Opsional)</label>
        <input type="file" name="gambar" class="form-control" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="dashboard.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</body>
</html>
