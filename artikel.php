<?php
// Ambil data artikel dan kategori dari API
$kategoriData = json_decode(file_get_contents("https://saleshinoindonesia.com/admin/api/get_kategori.php"), true);
$search = $_GET['search'] ?? '';
$selectedKategori = $_GET['kategori'] ?? '';

// Bangun URL API dengan filter jika ada
$apiUrl = "https://saleshinoindonesia.com/admin/api/get_artikel.php";
$params = [];
if ($search !== '') {
  $params[] = "search=" . urlencode($search);
}
if ($selectedKategori !== '') {
  $params[] = "kategori=" . urlencode($selectedKategori);
}
if (!empty($params)) {
  $apiUrl .= '?' . implode('&', $params);
}

$artikel = json_decode(file_get_contents($apiUrl), true);
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dealer Hino Indonesia | Sales Truck Hino Terbaik di Jabodetabek</title>
    <meta name="description" content="Dealer Resmi Hino Jakarta. Hubungi : 0859 7528 7684 / 0882 1392 5184 Untuk mendapatkan informasi produk Hino. Layanan Terbaik dan Jaminan Mutu." />
    <link rel="icon" type="image/png" href="/img/favicon.png" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/home_css/blogcard.css" />
    <link rel="stylesheet" href="css/blog.css" />
    <script src="https://unpkg.com/feather-icons"></script>
  </head>
  <body>
    <!-- Header -->
    <header>
      <div class="container header-content navbar">
        <div class="header-title">
          <img src="img/logo3.png" alt="Logo Hino" style="height: 60px" />
        </div>
        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="index.html">Home</a>
          <a href="hino300.html">Hino 300 Series</a>
          <a href="hino500.html">Hino 500 Series</a>
          <a href="hinobus.html">Hino Bus Series</a>
          <a href="contact.html">Contact</a>
        </nav>
      </div>
    </header>

    <!-- Blog Filter -->
    <section class="content-section">
      <div class="container">
        <h1>Artikel dan Edukasi</h1>

        <form method="get" class="blog-filter" style="margin-bottom: 20px;">
          <input type="text" name="search" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>" />
          <select name="kategori">
            <option value="">Semua Kategori</option>
            <?php if (is_array($kategoriData)): ?>
              <?php foreach ($kategoriData as $kat): ?>
                <option value="<?= htmlspecialchars($kat['nama']) ?>" <?= $selectedKategori === $kat['nama'] ? 'selected' : '' ?>><?= htmlspecialchars($kat['nama']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <button type="submit">Filter</button>
        </form>

        <div class="blog-grid">
          <?php if (is_array($artikel) && count($artikel) > 0): ?>
            <?php foreach ($artikel as $row): ?>
              <div class="blog-post">
                <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
                <h2><?= htmlspecialchars($row['judul']) ?></h2>
                <p><?= substr(strip_tags($row['isi']), 0, 100) ?>...</p>
                <a href="detail_artikel.php?id=<?= $row['id'] ?>">Baca Selengkapnya →</a>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Tidak ada artikel yang ditemukan.</p>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-section">
          <img src="img/logo3.png" alt="Logo" class="footer-logo" />
          <p>Nathan, Sales Hino Indonesia yang berpengalaman dan profesional, siap menjadi mitra terbaik Anda dalam memenuhi kebutuhan kendaraan niaga.</p>
        </div>
        <div class="footer-section">
          <h4>HUBUNGI KAMI</h4>
          <p>📞 0859-7528-7684</p>
          <p>📧 saleshinojabodetabek@gmail.com</p>
          <p>📍 Golf Lake Ruko Venice, Jakarta 11730</p>
          <div class="footer-social" style="margin-top: 20px">
            <h4>SOSIAL MEDIA</h4>
            <div class="social-icons">
              <a href="https://www.instagram.com/saleshinojabodetabek" target="_blank"><i data-feather="instagram"></i></a>
              <a href="https://wa.me/+6285975287684" target="_blank"><i data-feather="phone"></i></a>
              <a href="https://www.facebook.com/profile.php?id=61573843992250" target="_blank"><i data-feather="facebook"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-section">
          <div class="google-map-container" style="margin-top: 20px">
            <iframe src="https://www.google.com/maps/embed?..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Sales Hino Indonesia. All Rights Reserved.</p>
      </div>
    </footer>

    <script>feather.replace();</script>
  </body>
</html>
