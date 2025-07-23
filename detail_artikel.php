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
  <link rel="stylesheet" href="css/blog.css">
  <style>
    .navbar {
      background-color: #ffffff;
      padding: 15px 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .navbar a {
      color: #333;
      margin-right: 15px;
      text-decoration: none;
      font-weight: bold;
    }
    .detail-artikel {
      padding: 40px 20px;
      background-color: #f9f9f9;
    }
    .detail-artikel .container {
      max-width: 800px;
      margin: auto;
    }
    .detail-artikel h1 {
      font-size: 32px;
      margin-bottom: 20px;
      font-weight: 700;
      text-align: center;
    }
    .featured-image {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .isi-artikel {
      font-size: 18px;
      line-height: 1.7;
      color: #333;
      margin-bottom: 40px;
    }
    .btn-kembali {
      display: inline-block;
      background-color: #007b2e;
      color: white;
      padding: 12px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .btn-kembali:hover {
      background-color: #005d23;
    }
  </style>
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
