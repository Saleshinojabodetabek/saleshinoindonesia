<?php
// Ambil ID artikel dari URL
$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("https://saleshinoindonesia.com/admin/api/get_artikel.php"), true);
$artikel = null;

// Cari artikel berdasarkan ID
if ($id && is_array($data)) {
  foreach ($data as $item) {
    if ($item['id'] == $id) {
      $artikel = $item;
      break;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $artikel ? htmlspecialchars($artikel['judul']) : 'Artikel Tidak Ditemukan' ?> | Sales Hino Indonesia</title>
  <link rel="stylesheet" href="css/detailartikel.css">
</head>
<body>

<!-- Navbar -->
<header>
  <div class="navbar container">
    <a href="index.html"><img src="img/logo3.png" alt="Logo" style="height: 50px;"></a>
    <nav>
      <a href="index.html">Home</a>
      <a href="artikel.php">Artikel</a>
      <a href="contact.html">Contact</a>
    </nav>
  </div>
</header>

<!-- Konten Artikel -->
<section class="detail-artikel">
  <div class="container">
    <?php if($artikel): ?>
      <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
      <img src="<?= htmlspecialchars($artikel['gambar']) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" class="featured-image">
      <div class="isi-artikel">
        <?= nl2br($artikel['isi']) ?>
      </div>
      <a href="artikel.php" class="btn-kembali">← Kembali ke Daftar Artikel</a>
    <?php else: ?>
      <p>Artikel tidak ditemukan.</p>
    <?php endif; ?>
  </div>
</section>

</body>
</html>
