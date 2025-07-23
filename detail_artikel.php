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
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Dealer Hino Indonesia | Sales Truck Hino Terbaik di Jabodetabek
    </title>
    <meta
      name="description"
      content="Dealer Resmi Hino Jakarta. Hubungi : 0859 7528 7684 / 0882 1392 5184 Untuk mendapatkan informasi produk Hino. Layanan Terbaik dan Jaminan Mutu."
    />
    <link rel="icon" type="image/png" href="/img/favicon.png">


    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;700&display=swap"
      rel="stylesheet"
    />

    <link rel="icon" type="image/png" href="img/logo3.png" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/home_css/header.css" />
    <link rel="stylesheet" href="css/home_css/product.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/home_css/contactsec.css" />
    <link rel="stylesheet" href="css/home_css/companyprofilehome.css" />
    <link rel="stylesheet" href="css/home_css/ourcommitment.css" />
    <link rel="stylesheet" href="css/home_css/application.css" />
    <link rel="stylesheet" href="css/home_css/blogcard.css" />
    <link rel="stylesheet" href="css/home_css/keunggulankami.css" />
    <link rel="stylesheet" href="css/home_css/contact.css" />
    <link rel="stylesheet" href="css/home_css/ourclient.css" />

    <script src="js/script.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
  </head>
  <body>
    <!-- Header -->
    <header>
      <div class="container header-content navbar">
        <!-- Logo -->
        <div class="header-title">
          <img src="img/logo3.png" alt="Logo Hino" style="height: 60px" />
        </div>

        <!-- Hamburger Menu (Mobile Only) -->
        <div class="hamburger-menu">&#9776;</div>

        <!-- Nav Links -->
        <nav class="nav links">
          <a href="index.html">Home</a>
          <a href="hino300.html">Hino 300 Series</a>
          <a href="hino500.html">Hino 500 Series</a>
          <a href="hinobus.html">Hino Bus Series</a>
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
