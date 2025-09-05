<?php
session_start();
include "koneksi.php";

// Pastikan ID produk diberikan
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID produk tidak diberikan";
    header("Location: admin_products.php");
    exit();
}

$product_id = intval($_GET['id']);

// Dapatkan informasi produk
$product = [];
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    $_SESSION['error'] = "Produk tidak ditemukan";
    header("Location: admin_products.php");
    exit();
}

// Dapatkan data spesifikasi dari berbagai tabel
$specs = [];

// Ambil data karoseri
$result = $conn->query("SELECT * FROM karoseris WHERE product_id = $product_id");
$specs['karoseris'] = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specs['karoseris'][] = $row;
    }
}

// Ambil data performa
$result = $conn->query("SELECT * FROM performas WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['performa'] = $result->fetch_assoc();
}

// Ambil data model mesin
$result = $conn->query("SELECT * FROM model_mesins WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['model_mesin'] = $result->fetch_assoc();
}

// Ambil data kopling
$result = $conn->query("SELECT * FROM koplings WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['kopling'] = $result->fetch_assoc();
}

// Ambil data transmisi
$result = $conn->query("SELECT * FROM transmisis WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['transmisi'] = $result->fetch_assoc();
}

// Ambil data kemudi
$result = $conn->query("SELECT * FROM kemudis WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['kemudi'] = $result->fetch_assoc();
}

// Ambil data sumbu
$result = $conn->query("SELECT * FROM sumbus WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['sumbu'] = $result->fetch_assoc();
}

// Ambil data rem
$result = $conn->query("SELECT * FROM rems WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['rem'] = $result->fetch_assoc();
}

// Ambil data roda & ban
$result = $conn->query("SELECT * FROM roda_bans WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['roda_ban'] = $result->fetch_assoc();
}

// Ambil data sistem listrik
$result = $conn->query("SELECT * FROM sistim_listriks WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['sistim_listrik'] = $result->fetch_assoc();
}

// Ambil data tangki solar
$result = $conn->query("SELECT * FROM tangki_solars WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['tangki_solar'] = $result->fetch_assoc();
}

// Ambil data dimensi
$result = $conn->query("SELECT * FROM dimensis WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['dimensi'] = $result->fetch_assoc();
}

// Ambil data suspensi
$result = $conn->query("SELECT * FROM suspensis WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['suspensi'] = $result->fetch_assoc();
}

// Ambil data berat chassis
$result = $conn->query("SELECT * FROM berat_chassis WHERE product_id = $product_id");
if ($result && $result->num_rows > 0) {
    $specs['berat_chassis'] = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spesifikasi Produk - Sales Hino Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tambahkan style sesuai kebutuhan */
    </style>
</head>
<body>
    <!-- Notifikasi -->
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <div class="container mt-4">
        <h2>Spesifikasi Produk: <?php echo $product['name']; ?></h2>
        
        <!-- Form untuk mengelola spesifikasi -->
        <form action="save_specs.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            
            <!-- Tab untuk setiap bagian spesifikasi -->
            <ul class="nav nav-tabs" id="specTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="karoseri-tab" data-bs-toggle="tab" data-bs-target="#karoseri" type="button" role="tab">Karoseri</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="performa-tab" data-bs-toggle="tab" data-bs-target="#performa" type="button" role="tab">Performa</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="mesin-tab" data-bs-toggle="tab" data-bs-target="#mesin" type="button" role="tab">Model Mesin</button>
                </li>
                <!-- Tambahkan tab lainnya sesuai kebutuhan -->
            </ul>
            
            <div class="tab-content mt-3" id="specTabContent">
                <!-- Tab Karoseri -->
                <div class="tab-pane fade show active" id="karoseri" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label">Tipe Karoseri</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="karoseri_types[]" value="MOBIL BOKS/BAK" id="kb1" <?php echo in_array('MOBIL BOKS/BAK', array_column($specs['karoseris'], 'type')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="kb1">MOBIL BOKS/BAK</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="karoseri_types[]" value="BAK TERBUKA" id="kb2" <?php echo in_array('BAK TERBUKA', array_column($specs['karoseris'], 'type')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="kb2">BAK TERBUKA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="karoseri_types[]" value="LOS BAK" id="kb3" <?php echo in_array('LOS BAK', array_column($specs['karoseris'], 'type')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="kb3">LOS BAK</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="karoseri_types[]" value="BOKS BERPENDINGIN" id="kb4" <?php echo in_array('BOKS BERPENDINGIN', array_column($specs['karoseris'], 'type')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="kb4">BOKS BERPENDINGIN</label>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Performa -->
                <div class="tab-pane fade" id="performa" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kecepatan_maksimum" class="form-label">Kecepatan Maksimum (Km/h)</label>
                            <input type="number" class="form-control" id="kecepatan_maksimum" name="kecepatan_maksimum" value="<?php echo isset($specs['performa']['kecepatan_maksimum']) ? $specs['performa']['kecepatan_maksimum'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="daya_tanjak" class="form-label">Daya Tanjak (tan %)</label>
                            <input type="number" step="0.01" class="form-control" id="daya_tanjak" name="daya_tanjak" value="<?php echo isset($specs['performa']['daya_tanjak']) ? $specs['performa']['daya_tanjak'] : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Tab Model Mesin -->
                <div class="tab-pane fade" id="mesin" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="model_mesin" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model_mesin" name="model_mesin" value="<?php echo isset($specs['model_mesin']['model']) ? $specs['model_mesin']['model'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipe_mesin" class="form-label">Tipe</label>
                            <input type="text" class="form-control" id="tipe_mesin" name="tipe_mesin" value="<?php echo isset($specs['model_mesin']['tipe']) ? $specs['model_mesin']['tipe'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tenaga_maksimum" class="form-label">Tenaga Maksimum (PS/rpm)</label>
                            <input type="text" class="form-control" id="tenaga_maksimum" name="tenaga_maksimum" value="<?php echo isset($specs['model_mesin']['tenaga_maksimum']) ? $specs['model_mesin']['tenaga_maksimum'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="daya_maksimum" class="form-label">Daya Maksimum (Kgm/rpm)</label>
                            <input type="text" class="form-control" id="daya_maksimum" name="daya_maksimum" value="<?php echo isset($specs['model_mesin']['daya_maksimum']) ? $specs['model_mesin']['daya_maksimum'] : ''; ?>">
                        </div>
                        <!-- Tambahkan field lainnya sesuai kebutuhan -->
                    </div>
                </div>
                
                <!-- Tambahkan tab lainnya sesuai kebutuhan -->
            </div>
            
            <button type="submit" class="btn btn-primary mt-3">Simpan Spesifikasi</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>