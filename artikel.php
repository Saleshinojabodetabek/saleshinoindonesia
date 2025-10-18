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
    <title>Info dan Promo Truk Hino Terbaru | Sales Hino Indonesia</title>
    <meta name="description" content="Dealer Resmi Hino Jakarta. Hubungi : 0859 7528 7684 / 0882 1392 5184 Untuk mendapatkan informasi produk Hino. Layanan Terbaik dan Jaminan Mutu." />
    <link rel="icon" type="image/png" href="/img/favicon.png" />
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
    <link rel="stylesheet" href="css/blog.css" />
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
      .blog-filter {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
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

    <!-- Scripts -->
    <script src="js/script.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

  </head>
  <body>
    <!-- Header -->
    <header>
      <div class="container header-content navbar">
    <!-- Logo -->
    <div class="header-title">
      <a href="https://saleshinoindonesia.com">
        <img src="img/logo3.png" alt="Logo Hino" style="height: 60px" />
      </a>
    </div>

        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="https://saleshinoindonesia.com">Home</a>
          <a href="hino300.html">Hino 300 Series</a>
          <a href="hino500.html">Hino 500 Series</a>
          <a href="hinobus.html">Hino Bus Series</a>
          <a href="artikel.php">Blog & Artikel</a>
          <a href="contact.php">Contact</a>
        </nav>
      </div>
    </header>

    <!-- Hero Banner -->
    <section class="hero-banner" style="position: relative; overflow: hidden;">
      <img src="img/Euro 4 Hino 300.jpeg" alt="Banner Artikel Hino" style="width: 100%; height: auto; max-height: 400px; object-fit: cover;">
      <div style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7));
        display: flex;
        justify-content: center;
        align-items: center;
      ">
        <h1 style="color: white; font-size: 36px; font-weight: bold;">Blog & Artikel Hino Indonesia</h1>
      </div>
    </section>


    <!-- Blog Filter -->
    <section class="content-section">
      <div class="container">

        <form method="get" class="blog-filter" style="margin-bottom: 20px;">
          <input 
            type="text" 
            name="search" 
            placeholder="Cari artikel..." 
            value="<?= htmlspecialchars($search) ?>" 
          />
          <select name="kategori" onchange="this.form.submit()">
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
                <h2>
                  <a href="detail_artikel.php?id=<?= urlencode($row['id']) ?>">
                    <?= htmlspecialchars($row['judul']) ?>
                  </a>
                </h2>
                <p><?= substr(strip_tags($row['isi']), 0, 100) ?>...</p>
                <div class="card-footer">
                  <a href="detail_artikel.php?id=<?= urlencode($row['id']) ?>">Baca Selengkapnya</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Tidak ada artikel yang ditemukan.</p>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="<?= $i === $page ? 'active' : '' ?>" href="?search=<?= urlencode($search) ?>&kategori=<?= urlencode($selectedKategori) ?>&page=<?= $i ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
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
          <p>📍 Golf Lake Ruko Venice, Jl. Lkr. Luar Barat No.78 Blok B, RT.9/RW.14, Cengkareng Tim., Kecamatan Cengkareng, Jakarta</p>
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
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63471.95288843176!2d106.65860738294855!3d-6.131096504333846!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1d775401fa6d%3A0xc7e25a8d81b821ec!2sDealer%20Hino%20Jabodetabek%20Resmi!5e0!3m2!1sen!2sus!4v1760817261750!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Sales Hino Indonesia. All Rights Reserved.</p>
      </div>
    </footer>

<!-- Elfsight WhatsApp Chat | Untitled WhatsApp Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-1c150e27-6597-4113-becd-79df393b9756" data-elfsight-app-lazy></div>

    <script>feather.replace();</script>
  </body>
</html>
