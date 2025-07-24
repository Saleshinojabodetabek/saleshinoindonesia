<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Artikel - Sales Hino Indonesia</title>
    <link rel="stylesheet" href="navbar.css" />
    <link rel="stylesheet" href="footer.css" />
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Poppins", sans-serif;
      }

      html,
      body {
        background-color: #fff;
        color: #333;
        line-height: 1.6;
        scroll-behavior: smooth;
      }

      a {
        text-decoration: none;
        color: inherit;
      }

      img {
        max-width: 100%;
        display: block;
        object-fit: cover;
      }

      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
      }

      .blog-detail-section {
        padding: 40px 0;
      }

      .blog-detail-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
      }

      .blog-content {
        flex: 1 1 65%;
      }

      .blog-content h1 {
        font-size: 28px;
        margin-bottom: 16px;
        font-weight: bold;
      }

      .blog-content p {
        font-size: 16px;
        color: #444;
        margin-bottom: 20px;
      }

      .sidebar {
        flex: 1 1 30%;
      }

      .sidebar-section {
        margin-bottom: 30px;
      }

      .sidebar-section h3 {
        font-size: 18px;
        margin-bottom: 15px;
        border-bottom: 2px solid #ccc;
        padding-bottom: 6px;
      }

      .sidebar-section ul {
        list-style: none;
        padding: 0;
      }

      .sidebar-section ul li {
        margin-bottom: 10px;
      }

      .sidebar-section ul li a {
        color: #007bff;
        font-size: 15px;
        transition: 0.3s;
      }

      .sidebar-section ul li a:hover {
        text-decoration: underline;
      }

      /* Responsive Mobile */
      @media (max-width: 768px) {
        .blog-detail-container {
          flex-direction: column;
        }

        .blog-content,
        .sidebar {
          flex: 1 1 100%;
        }

        .sidebar {
          order: 2;
        }
      }
    </style>
  </head>
  <body>
    <!-- NAVIGATION -->
    <header class="container">
      <nav class="nav">
        <a href="#" class="active">Beranda</a>
        <a href="#">Tentang Kami</a>
        <a href="#">Produk</a>
        <a href="#">Artikel</a>
        <a href="#">Kontak</a>
      </nav>
    </header>

    <!-- DETAIL ARTIKEL -->
    <section class="blog-detail-section container">
      <div class="blog-detail-container">
        <div class="blog-content">
          <h1>Judul Artikel yang Informatif dan Menarik</h1>
          <img src="https://via.placeholder.com/800x400" alt="Gambar Artikel" />
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
            veniam.
          </p>
          <p>
            Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.
          </p>
        </div>

        <aside class="sidebar">
          <div class="sidebar-section">
            <h3>Kategori</h3>
            <ul>
              <li><a href="#">Tips Perawatan</a></li>
              <li><a href="#">Berita Dealer</a></li>
              <li><a href="#">Event & Promo</a></li>
            </ul>
          </div>

          <div class="sidebar-section">
            <h3>Artikel Terkait</h3>
            <ul>
              <li><a href="#">Cara Merawat Truk Hino dengan Baik</a></li>
              <li><a href="#">Update Terbaru dari Hino Indonesia</a></li>
              <li><a href="#">Program Promo Akhir Tahun</a></li>
            </ul>
          </div>
        </aside>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-section">
          <img src="logo.png" alt="Logo" class="footer-logo" />
          <p>Dealer resmi Hino Indonesia wilayah Jabodetabek.</p>
        </div>

        <div class="footer-section">
          <h4>Kontak</h4>
          <p>Jl. Raya Hino No. 123, Jakarta</p>
          <p>Telp: 021-12345678</p>
          <p>Email: info@saleshinoindonesia.com</p>
        </div>

        <div class="footer-section google-map-container">
          <h4>Lokasi</h4>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253840.57518888977!2d106.68943105754167!3d-6.229728024786193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e3b9f6a79f%3A0xa65d70b2792de89f!2sJakarta!5e0!3m2!1sen!2sid!4v1623412517164!5m2!1sen!2sid"
            allowfullscreen=""
            loading="lazy"
          ></iframe>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Sales Hino Indonesia. All rights reserved.</p>
      </div>
    </footer>
  </body>
</html>