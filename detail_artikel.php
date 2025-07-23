<?php
// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
  die("Artikel tidak ditemukan.");
}

// Ambil data dari API (ganti dengan endpoint API kamu jika berbeda)
$artikelList = json_decode(file_get_contents("https://saleshinoindonesia.com/admin/api/get_artikel.php"), true);

// Cari artikel berdasarkan ID
$artikel = null;
if (is_array($artikelList)) {
  foreach ($artikelList as $item) {
    if ($item['id'] == $id) {
      $artikel = $item;
      break;
    }
  }
}

if (!$artikel) {
  die("Artikel tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($artikel['judul']) ?> | Sales Hino Indonesia</title>
  <meta name="description" content="<?= htmlspecialchars(substr(strip_tags($artikel['isi']), 0, 150)) ?>" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/blog.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/footer.css" />
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <section class="content-section">
    <div class="container">
      <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
      <img src="<?= htmlspecialchars($artikel['gambar']) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" style="max-width:100%; height:auto; border-radius: 12px; margin: 20px 0;">
      <div class="artikel-isi">
        <?= $artikel['isi'] ?>
      </div>
      <a href="artikel.php" class="btn btn-contact" style="margin-top: 30px; display: inline-block;">← Kembali ke Daftar Artikel</a>
    </div>
  </section>

  <?php include 'partials/footer.php'; ?>
</body>
</html>
