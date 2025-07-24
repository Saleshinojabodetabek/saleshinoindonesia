<?php
// Ambil data artikel dan kategori dari API
$kategoriData = json_decode(file_get_contents("https://saleshinoindonesia.com/admin/api/get_kategori.php"), true);
$search = $_GET['search'] ?? '';
$selectedKategori = $_GET['kategori'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;

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

$artikelData = json_decode(file_get_contents($apiUrl), true);
$totalArtikel = is_array($artikelData) ? count($artikelData) : 0;
$totalPages = ceil($totalArtikel / $perPage);
$offset = ($page - 1) * $perPage;
$artikel = array_slice($artikelData, $offset, $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Artikel & Berita | Dealer Hino Indonesia</title>
  <meta name="description" content="Temukan artikel dan edukasi terbaru seputar Hino Indonesia. Informasi produk, layanan, dan tips bermanfaat dari Dealer Resmi Hino Jabodetabek." />
  <link rel="icon" href="/img/favicon.png" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/blog.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    .blog-filter {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
      margin-bottom: 20px;
    }
    .blog-filter input, .blog-filter select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    .blog-filter button {
      padding: 10px 20px;
      background-color: #007e33;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .blog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .blog-post {
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      background-color: #fff;
      transition: box-shadow 0.2s;
    }
    .blog-post:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .blog-post img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .blog-post h2 {
      font-size: 20px;
      padding: 10px 15px 0;
    }
    .blog-post p {
      padding: 0 15px 10px;
      font-size: 14px;
      color: #555;
    }
    .blog-post a {
      display: block;
      padding: 10px 15px;
      background-color: #f0f0f0;
      color: #007e33;
      text-decoration: none;
      font-weight: bold;
      border-top: 1px solid #ddd;
    }
    .pagination {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 30px;
    }
    .pagination a {
      padding: 8px 16px;
      background: #eee;
      text-decoration: none;
      border-radius: 6px;
      color: #333;
    }
    .pagination a.active {
      background-color: #007e33;
      color: #fff;
    }
  </style>
</head>
<body>
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

  <section class="content-section">
    <div class="container">
      <h1>Artikel dan Edukasi</h1>

      <form method="get" class="blog-filter">
        <input type="text" name="search" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>" />
        <select name="kategori">
          <option value="">Semua Kategori</option>
          <?php if (is_array($kategoriData)): ?>
            <?php foreach ($kategoriData as $kat): ?>
              <option value="<?= htmlspecialchars($kat['nama']) ?>" <?= $selectedKategori === $kat['nama'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($kat['nama']) ?>
              </option>
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

      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a class="<?= $i === $page ? 'active' : '' ?>" href="?search=<?= urlencode($search) ?>&kategori=<?= urlencode($selectedKategori) ?>&page=<?= $i ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
    </div>
  </section>

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
