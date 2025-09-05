<?php
// File: product_detail.php
session_start();

include "cek_login.php";
include "config.php";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Fungsi untuk mendapatkan produk dan spesifikasinya berdasarkan ID
function getProductWithSpecs($connection, $id) {
    $stmt = $connection->prepare("
        SELECT 
            p.*,
            GROUP_CONCAT(k.type) as karoseri_types,
            pf.kecepatan_maksimum, pf.daya_tanjak,
            mm.model as model_mesin, mm.tipe as tipe_mesin, mm.tenaga_maksimum, mm.daya_maksimum,
            mm.jumlah_silinder, mm.diameter_langkah_piston, mm.isi_silinder,
            kop.tipe as kopling_tipe, kop.diameter_cakram as kopling_diameter,
            t.tipe as transmisi_tipe, t.ke_1, t.ke_2, t.ke_3, t.ke_4, t.ke_5, t.mundur,
            kem.tipe as kemudi_tipe, kem.minimal_radius_putar,
            s.depan as sumbu_depan, s.belakang as sumbu_belakang, s.perbandingan_gigi_akhir,
            r.rem_utama, r.rem_pelambat, r.rem_parkir,
            rb.ukuran_ban, rb.ukuran_rim, rb.jumlah_ban,
            sl.accu,
            ts.kapasitas as tangki_solar_kapasitas,
            d.jarak_sumbu_roda, d.total_panjang, d.total_lebar, d.total_tinggi,
            d.lebar_jejak_depan, d.lebar_jejak_belakang, d.julur_depan, d.julur_belakang,
            d.kabin_kesumbu_roda_belakang,
            susp.depan_belakang as suspensi,
            bc.berat_kosong, bc.berat_total_kendaraan
        FROM products p
        LEFT JOIN karoseris k ON p.id = k.product_id
        LEFT JOIN performas pf ON p.id = pf.product_id
        LEFT JOIN model_mesins mm ON p.id = mm.product_id
        LEFT JOIN koplings kop ON p.id = kop.product_id
        LEFT JOIN transmisis t ON p.id = t.product_id
        LEFT JOIN kemudis kem ON p.id = kem.product_id
        LEFT JOIN sumbus s ON p.id = s.product_id
        LEFT JOIN rems r ON p.id = r.product_id
        LEFT JOIN roda_bans rb ON p.id = rb.product_id
        LEFT JOIN sistim_listriks sl ON p.id = sl.product_id
        LEFT JOIN tangki_solars ts ON p.id = ts.product_id
        LEFT JOIN dimensis d ON p.id = d.product_id
        LEFT JOIN suspensis susp ON p.id = susp.product_id
        LEFT JOIN berat_chassis bc ON p.id = bc.product_id
        WHERE p.id = ? AND p.is_active = 1
        GROUP BY p.id
    ");
    
    if (!$stmt) {
        die("Error preparing statement: " . $connection->error);
    }
    
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Ambil data produk jika ada parameter ID
$product = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product = getProductWithSpecs($conn, $product_id);
}

// Jika produk tidak ditemukan, tampilkan pesan error
if (!$product) {
    // Jangan redirect, tapi tampilkan pesan error
    $error_message = "Produk tidak ditemukan atau tidak aktif.";
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Title -->
    <title>
        <?php echo isset($product['name']) ? $product['name'] . ' | Sales Hino Indonesia' : 'Produk Tidak Ditemukan | Sales Hino Indonesia'; ?>
    </title>

    <!-- Meta Description -->
    <meta
      name="description"
      content="<?php echo isset($product['description']) ? $product['description'] : 'Dealer Resmi Hino Jakarta. Hubungi : 0859 7528 7684 / 0882 1392 5184 Untuk mendapatkan informasi produk Hino. Layanan Terbaik dan Jaminan Mutu.'; ?>"
    />

    <!-- Canonical URL -->
    <link rel="canonical" href="https://saleshinoindonesia.com/product_detail.php<?php echo isset($product['id']) ? '?id=' . $product['id'] : ''; ?>" />

    <!-- Open Graph (Facebook, LinkedIn) -->
    <meta
      property="og:title"
      content="<?php echo isset($product['name']) ? $product['name'] . ' | Sales Hino Indonesia' : 'Produk Tidak Ditemukan | Sales Hino Indonesia'; ?>"
    />
    <meta
      property="og:description"
      content="<?php echo isset($product['description']) ? $product['description'] : 'Dealer Resmi Hino Jakarta. Hubungi : 0859 7528 7684 / 0882 1392 5184 Untuk mendapatkan informasi produk Hino. Layanan Terbaik dan Jaminan Mutu.'; ?>"
    />
    <meta
      property="og:image"
      content="<?php echo isset($product['main_image']) ? $product['main_image'] : 'https://saleshinoindonesia.com/img/promohino1.jpg'; ?>"
    />
    <meta
      property="og:url"
      content="https://saleshinoindonesia.com/product_detail.php<?php echo isset($product['id']) ? '?id=' . $product['id'] : ''; ?>"
    />
    <meta property="og:type" content="website" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta
      name="twitter:title"
      content="<?php echo isset($product['name']) ? $product['name'] . ' | Sales Hino Indonesia' : 'Produk Tidak Ditemukan | Sales Hino Indonesia'; ?>"
    />
    <meta
      name="twitter:description"
      content="<?php echo isset($product['description']) ? $product['description'] : 'Dealer Resmi Hino Jakarta. Hubungi : 0859 7528 7684 / 0882 1392 5184 Untuk mendapatkan informasi produk Hino. Layanan Terbaik dan Jaminan Mutu.'; ?>"
    />
    <meta
      name="twitter:image"
      content="<?php echo isset($product['main_image']) ? $product['main_image'] : 'https://saleshinoindonesia.com/img/promohino1.jpg'; ?>"
    />

    <!-- Robots -->
    <meta name="robots" content="index, follow" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/img/favicon.png" />

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;700&display=swap"
      rel="stylesheet"
    />

    <!-- Structured Data JSON-LD -->
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "AutoDealer",
        "name": "Dealer Hino Indonesia - Jakarta",
        "image": "https://saleshinoindonesia.com/img/logo3.png",
        "url": "https://saleshinoindonesia.com",
        "logo": "https://saleshinoindonesia.com/img/logo3.png",
        "telephone": "+62-859-7528-7684",
        "email": "saleshinojabodetabek@gmail.com",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Golf Lake Ruko Venice, Jl. Lkr. Luar Barat No.78 Blok B, RT.9/RW.14, Cengkareng Tim.",
          "addressLocality": "Jakarta Barat",
          "addressRegion": "DKI Jakarta",
          "postalCode": "11730",
          "addressCountry": "ID"
        },
        "openingHours": "Mo-Sa 08:00-17:00",
        "priceRange": "$$",
        "sameAs": [
          "https://www.instagram.com/saleshinojabodetabek",
          "https://www.facebook.com/profile.php?id=61573843992250",
          "https://wa.me/6285975287684"
        ]
      }
    </script>

    <link rel="icon" type="image/png" href="img/logo.png" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/detail.css" />
    <link rel="stylesheet" href="css/sparepart_css/header_sparepart.css" />
    <link rel="stylesheet" href="css/sparepart_css/product_sparepart.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tambahan CSS Flex Layout -->
    <style>
      :root {
        --hino-blue: #0051a2;
        --hino-light-blue: #e6f0fa;
        --hino-dark: #333;
        --hino-gray: #f5f5f5;
      }
      
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
      }

      main {
        flex: 1;
      }
      
      /* Product Detail Styles */
      .product-detail-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 30px 20px;
      }
      
      .product-title {
        font-size: 2.2rem;
        color: var(--hino-blue);
        margin-bottom: 10px;
        font-weight: 700;
        text-align: center;
      }
      
      .product-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 30px;
        text-align: center;
      }
      
      .product-short-description {
        background-color: var(--hino-light-blue);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
      }
      
      /* Accordion Styles */
      .specification-section {
        margin-top: 40px;
      }

      .specification-title {
        font-size: 1.8rem;
        color: var(--hino-blue);
        margin-bottom: 20px;
        text-align: center;
        font-weight: 700;
      }
      
      .accordion-container {
        margin-top: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
      }
      
      .accordion-item {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 15px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      }
      
      .accordion-header {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 15px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s;
      }
      
      .accordion-header:hover {
        background-color: rgba(255, 255, 255, 1);
      }
      
      .accordion-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #000;
        font-weight: 700;
      }
      
      .accordion-icon {
        transition: transform 0.3s;
        color: #000;
      }
      
      .accordion-header.active .accordion-icon {
        transform: rotate(180deg);
      }
      
      .accordion-content {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        background-color: #fff;
      }
      
      .accordion-content.active {
        max-height: 1000px;
        padding: 20px;
      }
      
      .specs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
      }
      
      .specs-table th, .specs-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
      }
      
      .specs-table th {
        background-color: var(--hino-light-blue);
        width: 40%;
        font-weight: 600;
      }
      
      .karoseri-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 15px;
      }

      .karoseri-item {
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .karoseri-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }

      .karoseri-image {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
      }

      .karoseri-image img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
      }

      .karoseri-name {
        font-weight: 600;
        color: var(--hino-dark);
      }

      .error-message {
        text-align: center;
        padding: 40px;
        color: #d9534f;
        font-size: 1.2rem;
      }

      @media (max-width: 768px) {
        .product-title {
          font-size: 1.8rem;
        }
        
        .karoseri-grid {
          grid-template-columns: repeat(2, 1fr);
        }
        
        .specs-table th, .specs-table td {
          padding: 8px 10px;
          font-size: 0.9rem;
        }
      }

      @media (max-width: 480px) {
        .karoseri-grid {
          grid-template-columns: 1fr;
        }
      }
    </style>

    <script src="js/script.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
  </head>
  <body>
    <!-- Header -->
    <header>
      <div class="container header-content navbar">
        <div class="header-title">
          <a href="https://saleshinoindonesia.com">
            <img src="img/logo3.png" alt="Logo Hino" style="height: 60px" />
          </a>
        </div>

        <div class="hamburger-menu">&#9776;</div>

        <nav class="nav links">
          <a href="index.php">Home</a>
          <a href="hino300.html">Hino 300 Series</a>
          <a href="hino500.html">Hino 500 Series</a>
          <a href="hinobus.html">Hino Bus Series</a>
          <a href="artikel.php">Blog & Artikel</a>
          <a href="contact.html">Contact</a>
        </nav>
      </div>
    </header>

    <!-- Mulai konten utama -->
    <main>
      <!-- Sparepart Header Section -->
      <section
        class="about-hero"
        style="
          background-image: url('img/Euro 4 Hino 300.jpeg');
          background-size: cover;
          background-position: center;
        "
      ></section>

      <?php if (isset($error_message)): ?>
        <!-- Error Message -->
        <div class="product-detail-container">
          <div class="error-message">
            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 20px;"></i>
            <h2>Produk Tidak Ditemukan</h2>
            <p><?php echo $error_message; ?></p>
            <a href="hino300.html" class="btn btn-primary mt-3">Kembali ke Halaman Produk</a>
          </div>
        </div>
      <?php else: ?>
        <!-- Product Detail Content -->
        <div class="product-detail-container">
          <h1 class="product-title"><?php echo $product['name']; ?></h1>
          <p class="product-subtitle">Model: <?php echo $product['model']; ?></p>
          
          <!-- Short Description -->
          <div class="product-short-description">
            <p><?php echo nl2br($product['description']); ?></p>
          </div>
          
          <!-- Spesifikasi Section -->
          <div class="specification-section">
            <h2 class="specification-title">Spesifikasi</h2>
            
            <div class="accordion-container">
              <!-- KAROSERI -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>KAROSERI</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <div class="karoseri-grid">
                    <?php if (!empty($product['karoseri_types'])): ?>
                      <?php 
                      $karoseri_types = explode(',', $product['karoseri_types']);
                      foreach ($karoseri_types as $type): 
                        $type = trim($type);
                        $image_path = "img/karoseri/" . strtolower(str_replace(' ', '_', $type)) . ".jpg";
                      ?>
                      <div class="karoseri-item">
                        <div class="karoseri-image">
                          <img src="<?php echo file_exists($image_path) ? $image_path : 'img/placeholder.jpg'; ?>" alt="<?php echo $type; ?>">
                        </div>
                        <div class="karoseri-name"><?php echo $type; ?></div>
                      </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <p>Tidak ada data karoseri</p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              
              <!-- PERFORMA -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>PERFORMA</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['kecepatan_maksimum'])): ?>
                    <tr>
                      <th>Kecepatan Maksimum (Km/h)</th>
                      <td><?php echo $product['kecepatan_maksimum']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['daya_tanjak'])): ?>
                    <tr>
                      <th>Daya Tanjak (tan %)</th>
                      <td><?php echo $product['daya_tanjak']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- MODEL MESIN -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>MODEL MESIN</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['model_mesin'])): ?>
                    <tr>
                      <th>Model</th>
                      <td><?php echo $product['model_mesin']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['tipe_mesin'])): ?>
                    <tr>
                      <th>Model Tipe</th>
                      <td><?php echo $product['tipe_mesin']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['tenaga_maksimum'])): ?>
                    <tr>
                      <th>Tenaga Maksimum (PS/rpm)</th>
                      <td><?php echo $product['tenaga_maksimum']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['daya_maksimum'])): ?>
                    <tr>
                      <th>Daya Maksimum (Kgm/rpm)</th>
                      <td><?php echo $product['daya_maksimum']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['jumlah_silinder'])): ?>
                    <tr>
                      <th>Jumlah Silinder</th>
                      <td><?php echo $product['jumlah_silinder']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['diameter_langkah_piston'])): ?>
                    <tr>
                      <th>Diameter x Langkah Piston (mm)</th>
                      <td><?php echo $product['diameter_langkah_piston']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['isi_silinder'])): ?>
                    <tr>
                      <th>Isi Silinder (cc)</th>
                      <td><?php echo $product['isi_silinder']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- KOPLING -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>KOPLING</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['kopling_tipe'])): ?>
                    <tr>
                      <th>Tipe</th>
                      <td><?php echo $product['kopling_tipe']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['kopling_diameter'])): ?>
                    <tr>
                      <th>Diameter Cakram (mm)</th>
                      <td><?php echo $product['kopling_diameter']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- TRANSMISI -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>TRANSMISI</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['transmisi_tipe'])): ?>
                    <tr>
                      <th>Tipe</th>
                      <td><?php echo $product['transmisi_tipe']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['ke_1'])): ?>
                    <tr>
                      <th>Ke-1</th>
                      <td><?php echo $product['ke_1']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['ke_2'])): ?>
                    <tr>
                      <th>Ke-2</th>
                      <td><?php echo $product['ke_2']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['ke_3'])): ?>
                    <tr>
                      <th>Ke-3</th>
                      <td><?php echo $product['ke_3']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['ke_4'])): ?>
                    <tr>
                      <th>Ke-4</th>
                      <td><?php echo $product['ke_4']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['ke_5'])): ?>
                    <tr>
                      <th>Ke-5</th>
                      <td><?php echo $product['ke_5']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['mundur'])): ?>
                    <tr>
                      <th>Mundur</th>
                      <td><?php echo $product['mundur']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- KEMUDI -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>KEMUDI</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['kemudi_tipe'])): ?>
                    <tr>
                      <th>Tipe</th>
                      <td><?php echo $product['kemudi_tipe']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['minimal_radius_putar'])): ?>
                    <tr>
                      <th>Minimal Radius Putar (m)</th>
                      <td><?php echo $product['minimal_radius_putar']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- SUMBU -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>SUMBU</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['sumbu_depan'])): ?>
                    <tr>
                      <th>Depan</th>
                      <td><?php echo $product['sumbu_depan']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['sumbu_belakang'])): ?>
                    <tr>
                      <th>Belakang</th>
                      <td><?php echo $product['sumbu_belakang']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['perbandingan_gigi_akhir'])): ?>
                    <tr>
                      <th>Perbandingan Gigi Akhir</th>
                      <td><?php echo $product['perbandingan_gigi_akhir']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- REM -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>REM</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['rem_utama'])): ?>
                    <tr>
                      <th>Rem Utama</th>
                      <td><?php echo $product['rem_utama']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['rem_pelambat'])): ?>
                    <tr>
                      <th>Rem Pelambat</th>
                      <td><?php echo $product['rem_pelambat']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['rem_parkir'])): ?>
                    <tr>
                      <th>Rem Parkir</th>
                      <td><?php echo $product['rem_parkir']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- RODA & BAN -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>RODA & BAN</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['ukuran_ban'])): ?>
                    <tr>
                      <th>Ukuran Ban</th>
                      <td><?php echo $product['ukuran_ban']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['ukuran_rim'])): ?>
                    <tr>
                      <th>Ukuran Rim</th>
                      <td><?php echo $product['ukuran_rim']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['jumlah_ban'])): ?>
                    <tr>
                      <th>Jumlah Ban</th>
                      <td><?php echo $product['jumlah_ban']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- SISTEM LISTRIK -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>SISTEM LISTRIK</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['accu'])): ?>
                    <tr>
                      <th>Accu (V-Ah)</th>
                      <td><?php echo $product['accu']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- TANGKI SOLAR -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>TANGKI SOLAR</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['tangki_solar_kapasitas'])): ?>
                    <tr>
                      <th>Kapasitas (L)</th>
                      <td><?php echo $product['tangki_solar_kapasitas']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- DIMENSI -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>DIMENSI</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['jarak_sumbu_roda'])): ?>
                    <tr>
                      <th>Jarak Sumbu Roda (mm)</th>
                      <td><?php echo $product['jarak_sumbu_roda']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['total_panjang'])): ?>
                    <tr>
                      <th>Total Panjang (mm)</th>
                      <td><?php echo $product['total_panjang']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['total_lebar'])): ?>
                    <tr>
                      <th>Total Lebar (mm)</th>
                      <td><?php echo $product['total_lebar']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['total_tinggi'])): ?>
                    <tr>
                      <th>Total Tinggi (mm)</th>
                      <td><?php echo $product['total_tinggi']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['lebar_jejak_depan'])): ?>
                    <tr>
                      <th>Lebar Jejak Depan (mm)</th>
                      <td><?php echo $product['lebar_jejak_depan']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['lebar_jejak_belakang'])): ?>
                    <tr>
                      <th>Lebar Jejak Belakang (mm)</th>
                      <td><?php echo $product['lebar_jejak_belakang']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['julur_depan'])): ?>
                    <tr>
                      <th>Julur Depan (mm)</th>
                      <td><?php echo $product['julur_depan']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['julur_belakang'])): ?>
                    <tr>
                      <th>Julur Belakang (mm)</th>
                      <td><?php echo $product['julur_belakang']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['kabin_kesumbu_roda_belakang'])): ?>
                    <tr>
                      <th>Kabin ke Sumbu Roda Belakang (mm)</th>
                      <td><?php echo $product['kabin_kesumbu_roda_belakang']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- SUSPENSI -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>SUSPENSI</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['suspensi'])): ?>
                    <tr>
                      <th>Depan & Belakang</th>
                      <td><?php echo $product['suspensi']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
              
              <!-- BERAT CHASSIS -->
              <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                  <h3>BERAT CHASSIS</h3>
                  <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="accordion-content">
                  <table class="specs-table">
                    <?php if (!empty($product['berat_kosong'])): ?>
                    <tr>
                      <th>Berat Kosong (kg)</th>
                      <td><?php echo $product['berat_kosong']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($product['berat_total_kendaraan'])): ?>
                    <tr>
                      <th>Berat Total Kendaraan (kg)</th>
                      <td><?php echo $product['berat_total_kendaraan']; ?></td>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- CTA Section -->
      <div class="cta-full">
        <h2>Tidak menemukan apa yang kamu cari?</h2>
        <a
          href="https://wa.me/+6285975287684?text=Halo%20Saya%20Ingin%20Menanyakan%20Tentang%20Produk"
          class="cta-full-button"
          >Hubungi Kami</a
        >
      </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-section">
          <img src="img/logo3.png" alt="Logo" class="footer-logo" />
          <p>
            Nathan, Sales Hino Indonesia yang berpengalaman dan profesional,
            siap menjadi mitra terbaik Anda dalam memenuhi kebutuhan kendaraan
            niaga.
          </p>
        </div>

        <div class="footer-section">
          <h4>HUBUNGI KAMI</h4>
          <p>📞 0859-7528-7684</p>
          <p>📧 saleshinojabodetabek@gmail.com</p>
          <p>
            📍 Golf Lake Ruko Venice, Jl. Lkr. Luar Barat No.78 Blok B,
            RT.9/RW.14, Cengkareng Tim., Kecamatan Cengkareng, Jakarta
          </p>

          <div class="footer-social" style="margin-top: 20px">
            <h4>SOSIAL MEDIA</h4>
            <div class="social-icons">
              <a
                href="https://www.instagram.com/saleshinojabodetabek"
                target="_blank"
              >
                <i data-feather="instagram"></i>
              </a>
              <a
                href="https://wa.me/+6285975287684?text=Halo%20Saya%20Dapat%20Nomor%20Anda%20Dari%20Google"
                target="_blank"
              >
                <i data-feather="phone"></i>
              </a>
              <a
                href="https://www.facebook.com/profile.php?id=61573843992250"
                target="_blank"
              >
                <i data-feather="facebook"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="footer-section">
          <div class="google-map-container" style="margin-top: 20px">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.001117199873!2d106.72798237355298!3d-6.130550360104524!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f70ab03b3611%3A0x2e6e345ac4d4fd04!2sHINO%20CENGKARENG%20(DGMI)!5e0!3m2!1sid!2sid!4v1752934707067!5m2!1sid!2sid"
              width="600"
              height="450"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Sales Hino Indonesia. All Rights Reserved.</p>
      </div>
    </footer>

    <!-- Bootstrap & jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
      // Fungsi untuk accordion
      function toggleAccordion(element) {
        // Toggle active class pada header
        element.classList.toggle('active');
        
        // Dapatkan konten accordion
        var content = element.nextElementSibling;
        
        // Toggle active class pada konten
        content.classList.toggle('active');
      }
      
      // Buka accordion pertama secara default
      document.addEventListener('DOMContentLoaded', function() {
        var firstAccordion = document.querySelector('.accordion-header');
        if (firstAccordion) {
          firstAccordion.classList.add('active');
          firstAccordion.nextElementSibling.classList.add('active');
        }
      });
    </script>

    <!-- Elfsight WhatsApp Chat -->
    <script
      src="https://static.elfsight.com/platform/platform.js"
      async
    ></script>
    <div
      class="elfsight-app-1c150e27-6597-4113-becd-79df393b9756"
      data-elfsight-app-lazy
    ></div>

    <script>
      feather.replace();
    </script>
  </body>
</html>

<?php
$conn->close();
?>