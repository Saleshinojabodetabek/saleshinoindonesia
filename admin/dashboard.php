<?php
session_start();
// Include koneksi dan dapatkan variabel $conn
include "login.php"
$conn = include "koneksi.php";

// Fungsi untuk mendapatkan semua produk
function getProducts($connection) {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $result = $connection->query($sql);
    
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Fungsi untuk mendapatkan produk berdasarkan ID
function getProductById($connection, $id) {
    $stmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Fungsi untuk mendapatkan spesifikasi produk
function getProductSpecs($connection, $product_id) {
    $specs = [];
    
    // Ambil data dari semua tabel spesifikasi
    $tables = [
        'karoseris', 'performas', 'model_mesins', 'koplings', 'transmisis',
        'kemudis', 'sumbus', 'rems', 'roda_bans', 'sistim_listriks',
        'tangki_solars', 'dimensis', 'suspensis', 'berat_chassis'
    ];
    
    foreach ($tables as $table) {
        $result = $connection->query("SELECT * FROM $table WHERE product_id = $product_id");
        if ($result && $result->num_rows > 0) {
            $specs[$table] = $table === 'karoseris' ? $result->fetch_all(MYSQLI_ASSOC) : $result->fetch_assoc();
        } else {
            $specs[$table] = null;
        }
    }
    
    return $specs;
}

// Tangani aksi edit dan hapus
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    
    if ($action == 'edit') {
        $editProduct = getProductById($conn, $id);
        if ($editProduct) {
            $editSpecs = getProductSpecs($conn, $id);
        }
    } elseif ($action == 'delete') {
        // Tangani penghapusan produk
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Produk berhasil dihapus";
            header("Location: admin_products.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal menghapus produk: " . $conn->error;
            header("Location: admin_products.php");
            exit();
        }
    }
}

// Ambil semua produk untuk ditampilkan
$products = getProducts($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin Produk - Sales Hino Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --hino-blue: #0051a2;
            --hino-light-blue: #e6f0fa;
            --hino-dark: #333;
            --hino-gray: #f5f5f5;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background-color: var(--hino-blue);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .admin-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .admin-card-header {
            background-color: var(--hino-blue);
            color: white;
            border-radius: 8px 8px 0 0 !important;
        }
        
        .btn-hino {
            background-color: var(--hino-blue);
            color: white;
        }
        
        .btn-hino:hover {
            background-color: #003d7a;
            color: white;
        }
        
        .spec-table th {
            background-color: var(--hino-light-blue);
            width: 30%;
        }
        
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
        
        .nav-tabs .nav-link {
            color: var(--hino-dark);
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--hino-blue);
            border-bottom: 3px solid var(--hino-blue);
            border-top: none;
            border-left: none;
            border-right: none;
            background: transparent;
        }
        
        .spec-card {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        
        .spec-card-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }
        
        .spec-card-body {
            padding: 15px;
        }
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

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="text-center">Sales Hino</h4>
                    <p class="text-center text-white-50 mb-4">Admin Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="panel_artikel.php">
                            <i class="fas fa-newspaper me-2"></i> Artikel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-truck me-2"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-image me-2"></i> Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog me-2"></i> Pengaturan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manajemen Produk</h2>
                    <button class="btn btn-hino" data-bs-toggle="modal" data-bs-target="#productModal">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </button>
                </div>

                <!-- Daftar Produk -->
                <div class="card admin-card">
                    <div class="card-header admin-card-header">
                        <h5 class="mb-0">Daftar Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Produk</th>
                                        <th>Model</th>
                                        <th>Status</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                    foreach ($products as $prod):
                                    ?>
                                    <tr>
                                        <td><?php echo $prod['id']; ?></td>
                                        <td><?php echo $prod['name']; ?></td>
                                        <td><?php echo $prod['model']; ?></td>
                                        <td><span class="badge bg-<?php echo $prod['is_active'] ? 'success' : 'secondary'; ?>"><?php echo $prod['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?></span></td>
                                        <td><?php echo date('d M Y', strtotime($prod['created_at'])); ?></td>
                                        <td>
                                            <a href="admin_products.php?action=edit&id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            <a href="../product_detail.php?id=<?php echo $prod['id']; ?>" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                            <a href="#" onclick="confirmDelete(<?php echo $prod['id']; ?>, '<?php echo addslashes($prod['name']); ?>')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Produk -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo isset($editProduct) ? 'Edit' : 'Tambah'; ?> Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="save_products.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo isset($editProduct) ? $editProduct['id'] : ''; ?>">
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Informasi Dasar</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">Spesifikasi</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content mt-3" id="productTabContent">
                            <!-- Tab Informasi Dasar -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="productName" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control" id="productName" name="name" value="<?php echo isset($editProduct) ? $editProduct['name'] : ''; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="productModel" class="form-label">Model</label>
                                        <input type="text" class="form-control" id="productModel" name="model" value="<?php echo isset($editProduct) ? $editProduct['model'] : ''; ?>">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="productDescription" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="productDescription" name="description" rows="3"><?php echo isset($editProduct) ? $editProduct['description'] : ''; ?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="is_active">
                                            <option value="1" <?php echo (isset($editProduct) && $editProduct['is_active']) ? 'selected' : ''; ?>>Aktif</option>
                                            <option value="0" <?php echo (isset($editProduct) && !$editProduct['is_active']) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tab Spesifikasi -->
                            <div class="tab-pane fade" id="specs" role="tabpanel">
                                <!-- Karoseri -->
                                <div class="spec-card">
                                    <div class="spec-card-header">KAROSERI</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="karoseri[]" value="MOBIL BOKS/BAK" id="karoseri1" <?php echo (isset($editSpecs['karoseris']) && in_array('MOBIL BOKS/BAK', array_column($editSpecs['karoseris'], 'type'))) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="karoseri1">MOBIL BOKS/BAK</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="karoseri[]" value="BAK TERBUKA" id="karoseri2" <?php echo (isset($editSpecs['karoseris']) && in_array('BAK TERBUKA', array_column($editSpecs['karoseris'], 'type'))) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="karoseri2">BAK TERBUKA</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="karoseri[]" value="LOS BAK" id="karoseri3" <?php echo (isset($editSpecs['karoseris']) && in_array('LOS BAK', array_column($editSpecs['karoseris'], 'type'))) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="karoseri3">LOS BAK</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="karoseri[]" value="BOKS BERPENDINGIN" id="karoseri4" <?php echo (isset($editSpecs['karoseris']) && in_array('BOKS BERPENDINGIN', array_column($editSpecs['karoseris'], 'type'))) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="karoseri4">BOKS BERPENDINGIN</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Performa -->
                                <div class="spec-card">
                                    <div class="spec-card-header">PERFORMA</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="kecepatan_maksimum" class="form-label">Kecepatan Maksimum (Km/h)</label>
                                                <input type="number" class="form-control" id="kecepatan_maksimum" name="kecepatan_maksimum" value="<?php echo isset($editSpecs['performas']['kecepatan_maksimum']) ? $editSpecs['performas']['kecepatan_maksimum'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="daya_tanjak" class="form-label">Daya Tanjak (tan %)</label>
                                                <input type="number" step="0.1" class="form-control" id="daya_tanjak" name="daya_tanjak" value="<?php echo isset($editSpecs['performas']['daya_tanjak']) ? $editSpecs['performas']['daya_tanjak'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- MODEL MESIN -->
                                <div class="spec-card">
                                    <div class="spec-card-header">MODEL MESIN</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="model_mesin" class="form-label">Model</label>
                                                <input type="text" class="form-control" id="model_mesin" name="model_mesin" value="<?php echo isset($editSpecs['model_mesins']['model']) ? $editSpecs['model_mesins']['model'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="tipe_mesin" class="form-label">Tipe</label>
                                                <input type="text" class="form-control" id="tipe_mesin" name="tipe_mesin" value="<?php echo isset($editSpecs['model_mesins']['tipe']) ? $editSpecs['model_mesins']['tipe'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="tenaga_maksimum" class="form-label">Tenaga Maksimum (PS/rpm)</label>
                                                <input type="text" class="form-control" id="tenaga_maksimum" name="tenaga_maksimum" value="<?php echo isset($editSpecs['model_mesins']['tenaga_maksimum']) ? $editSpecs['model_mesins']['tenaga_maksimum'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="daya_maksimum" class="form-label">Daya Maksimum (Kgm/rpm)</label>
                                                <input type="text" class="form-control" id="daya_maksimum" name="daya_maksimum" value="<?php echo isset($editSpecs['model_mesins']['daya_maksimum']) ? $editSpecs['model_mesins']['daya_maksimum'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="jumlah_silinder" class="form-label">Jumlah Silinder</label>
                                                <input type="number" class="form-control" id="jumlah_silinder" name="jumlah_silinder" value="<?php echo isset($editSpecs['model_mesins']['jumlah_silinder']) ? $editSpecs['model_mesins']['jumlah_silinder'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="diameter_langkah_piston" class="form-label">Diameter x Langkah Piston (mm)</label>
                                                <input type="text" class="form-control" id="diameter_langkah_piston" name="diameter_langkah_piston" value="<?php echo isset($editSpecs['model_mesins']['diameter_langkah_piston']) ? $editSpecs['model_mesins']['diameter_langkah_piston'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="isi_silinder" class="form-label">Isi Silinder (cc)</label>
                                                <input type="number" class="form-control" id="isi_silinder" name="isi_silinder" value="<?php echo isset($editSpecs['model_mesins']['isi_silinder']) ? $editSpecs['model_mesins']['isi_silinder'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- KOPLING -->
                                <div class="spec-card">
                                    <div class="spec-card-header">KOPLING</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="kopling_tipe" class="form-label">Tipe</label>
                                                <input type="text" class="form-control" id="kopling_tipe" name="kopling_tipe" value="<?php echo isset($editSpecs['koplings']['tipe']) ? $editSpecs['koplings']['tipe'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="kopling_diameter" class="form-label">Diameter Cakram (mm)</label>
                                                <input type="number" class="form-control" id="kopling_diameter" name="kopling_diameter" value="<?php echo isset($editSpecs['koplings']['diameter_cakram']) ? $editSpecs['koplings']['diameter_cakram'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- TRANSMISI -->
                                <div class="spec-card">
                                    <div class="spec-card-header">TRANSMISI</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="transmisi_tipe" class="form-label">Tipe</label>
                                                <input type="text" class="form-control" id="transmisi_tipe" name="transmisi_tipe" value="<?php echo isset($editSpecs['transmisis']['tipe']) ? $editSpecs['transmisis']['tipe'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="transmisi_ke1" class="form-label">Ke-1</label>
                                                <input type="text" class="form-control" id="transmisi_ke1" name="transmisi_ke1" value="<?php echo isset($editSpecs['transmisis']['ke_1']) ? $editSpecs['transmisis']['ke_1'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="transmisi_ke2" class="form-label">Ke-2</label>
                                                <input type="text" class="form-control" id="transmisi_ke2" name="transmisi_ke2" value="<?php echo isset($editSpecs['transmisis']['ke_2']) ? $editSpecs['transmisis']['ke_2'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="transmisi_ke3" class="form-label">Ke-3</label>
                                                <input type="text" class="form-control" id="transmisi_ke3" name="transmisi_ke3" value="<?php echo isset($editSpecs['transmisis']['ke_3']) ? $editSpecs['transmisis']['ke_3'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="transmisi_ke4" class="form-label">Ke-4</label>
                                                <input type="text" class="form-control" id="transmisi_ke4" name="transmisi_ke4" value="<?php echo isset($editSpecs['transmisis']['ke_4']) ? $editSpecs['transmisis']['ke_4'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="transmisi_ke5" class="form-label">Ke-5</label>
                                                <input type="text" class="form-control" id="transmisi_ke5" name="transmisi_ke5" value="<?php echo isset($editSpecs['transmisis']['ke_5']) ? $editSpecs['transmisis']['ke_5'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="transmisi_mundur" class="form-label">Mundur</label>
                                                <input type="text" class="form-control" id="transmisi_mundur" name="transmisi_mundur" value="<?php echo isset($editSpecs['transmisis']['mundur']) ? $editSpecs['transmisis']['mundur'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- KEMUDI -->
                                <div class="spec-card">
                                    <div class="spec-card-header">KEMUDI</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="kemudi_tipe" class="form-label">Tipe</label>
                                                <input type="text" class="form-control" id="kemudi_tipe" name="kemudi_tipe" value="<?php echo isset($editSpecs['kemudis']['tipe']) ? $editSpecs['kemudis']['tipe'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="kemudi_radius" class="form-label">Minimal Radius Putar (m)</label>
                                                <input type="number" step="0.1" class="form-control" id="kemudi_radius" name="kemudi_radius" value="<?php echo isset($editSpecs['kemudis']['minimal_radius_putar']) ? $editSpecs['kemudis']['minimal_radius_putar'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- SUMBU -->
                                <div class="spec-card">
                                    <div class="spec-card-header">SUMBU</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="sumbu_depan" class="form-label">Depan</label>
                                                <input type="text" class="form-control" id="sumbu_depan" name="sumbu_depan" value="<?php echo isset($editSpecs['sumbus']['depan']) ? $editSpecs['sumbus']['depan'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="sumbu_belakang" class="form-label">Belakang</label>
                                                <input type="text" class="form-control" id="sumbu_belakang" name="sumbu_belakang" value="<?php echo isset($editSpecs['sumbus']['belakang']) ? $editSpecs['sumbus']['belakang'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="sumbu_perbandingan" class="form-label">Perbandingan Gigi Akhir</label>
                                                <input type="number" step="0.001" class="form-control" id="sumbu_perbandingan" name="sumbu_perbandingan" value="<?php echo isset($editSpecs['sumbus']['perbandingan_gigi_akhir']) ? $editSpecs['sumbus']['perbandingan_gigi_akhir'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- REM -->
                                <div class="spec-card">
                                    <div class="spec-card-header">REM</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="rem_utama" class="form-label">Rem Utama</label>
                                                <textarea class="form-control" id="rem_utama" name="rem_utama" rows="2"><?php echo isset($editSpecs['rems']['rem_utama']) ? $editSpecs['rems']['rem_utama'] : ''; ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="rem_pelambat" class="form-label">Rem Pelambat</label>
                                                <textarea class="form-control" id="rem_pelambat" name="rem_pelambat" rows="2"><?php echo isset($editSpecs['rems']['rem_pelambat']) ? $editSpecs['rems']['rem_pelambat'] : ''; ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="rem_parkir" class="form-label">Rem Parkir</label>
                                                <textarea class="form-control" id="rem_parkir" name="rem_parkir" rows="2"><?php echo isset($editSpecs['rems']['rem_parkir']) ? $editSpecs['rems']['rem_parkir'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- RODA & BAN -->
                                <div class="spec-card">
                                    <div class="spec-card-header">RODA & BAN</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="roda_ban_ukuran" class="form-label">Ukuran Ban</label>
                                                <input type="text" class="form-control" id="roda_ban_ukuran" name="roda_ban_ukuran" value="<?php echo isset($editSpecs['roda_bans']['ukuran_ban']) ? $editSpecs['roda_bans']['ukuran_ban'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="roda_ban_rim" class="form-label">Ukuran Rim</label>
                                                <input type="text" class="form-control" id="roda_ban_rim" name="roda_ban_rim" value="<?php echo isset($editSpecs['roda_bans']['ukuran_rim']) ? $editSpecs['roda_bans']['ukuran_rim'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="roda_ban_jumlah" class="form-label">Jumlah Ban</label>
                                                <input type="text" class="form-control" id="roda_ban_jumlah" name="roda_ban_jumlah" value="<?php echo isset($editSpecs['roda_bans']['jumlah_ban']) ? $editSpecs['roda_bans']['jumlah_ban'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- SISTEM LISTRIK -->
                                <div class="spec-card">
                                    <div class="spec-card-header">SISTEM LISTRIK</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="sistem_listrik_accu" class="form-label">Accu (V-Ah)</label>
                                                <input type="text" class="form-control" id="sistem_listrik_accu" name="sistem_listrik_accu" value="<?php echo isset($editSpecs['sistim_listriks']['accu']) ? $editSpecs['sistim_listriks']['accu'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- TANGKI SOLAR -->
                                <div class="spec-card">
                                    <div class="spec-card-header">TANGKI SOLAR</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="tangki_solar_kapasitas" class="form-label">Kapasitas (L)</label>
                                                <input type="number" class="form-control" id="tangki_solar_kapasitas" name="tangki_solar_kapasitas" value="<?php echo isset($editSpecs['tangki_solars']['kapasitas']) ? $editSpecs['tangki_solars']['kapasitas'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- DIMENSI -->
                                <div class="spec-card">
                                    <div class="spec-card-header">DIMENSI</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_jarak_sumbu" class="form-label">Jarak Sumbu Roda (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_jarak_sumbu" name="dimensi_jarak_sumbu" value="<?php echo isset($editSpecs['dimensis']['jarak_sumbu_roda']) ? $editSpecs['dimensis']['jarak_sumbu_roda'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_panjang" class="form-label">Total Panjang (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_panjang" name="dimensi_panjang" value="<?php echo isset($editSpecs['dimensis']['total_panjang']) ? $editSpecs['dimensis']['total_panjang'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_lebar" class="form-label">Total Lebar (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_lebar" name="dimensi_lebar" value="<?php echo isset($editSpecs['dimensis']['total_lebar']) ? $editSpecs['dimensis']['total_lebar'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_tinggi" class="form-label">Total Tinggi (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_tinggi" name="dimensi_tinggi" value="<?php echo isset($editSpecs['dimensis']['total_tinggi']) ? $editSpecs['dimensis']['total_tinggi'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_jejak_depan" class="form-label">Lebar Jejak Depan (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_jejak_depan" name="dimensi_jejak_depan" value="<?php echo isset($editSpecs['dimensis']['lebar_jejak_depan']) ? $editSpecs['dimensis']['lebar_jejak_depan'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_jejak_belakang" class="form-label">Lebar Jejak Belakang (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_jejak_belakang" name="dimensi_jejak_belakang" value="<?php echo isset($editSpecs['dimensis']['lebar_jejak_belakang']) ? $editSpecs['dimensis']['lebar_jejak_belakang'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_julur_depan" class="form-label">Julur Depan (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_julur_depan" name="dimensi_julur_depan" value="<?php echo isset($editSpecs['dimensis']['julur_depan']) ? $editSpecs['dimensis']['julur_depan'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_julur_belakang" class="form-label">Julur Belakang (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_julur_belakang" name="dimensi_julur_belakang" value="<?php echo isset($editSpecs['dimensis']['julur_belakang']) ? $editSpecs['dimensis']['julur_belakang'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="dimensi_kabin" class="form-label">Kabin ke Sumbu Roda Belakang (mm)</label>
                                                <input type="number" class="form-control" id="dimensi_kabin" name="dimensi_kabin" value="<?php echo isset($editSpecs['dimensis']['kabin_kesumbu_roda_belakang']) ? $editSpecs['dimensis']['kabin_kesumbu_roda_belakang'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- SUSPENSI -->
                                <div class="spec-card">
                                    <div class="spec-card-header">SUSPENSI</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="suspensi" class="form-label">Depan & Belakang</label>
                                                <textarea class="form-control" id="suspensi" name="suspensi" rows="2"><?php echo isset($editSpecs['suspensis']['depan_belakang']) ? $editSpecs['suspensis']['depan_belakang'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- BERAT CHASSIS -->
                                <div class="spec-card">
                                    <div class="spec-card-header">BERAT CHASSIS</div>
                                    <div class="spec-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="berat_kosong" class="form-label">Berat Kosong (kg)</label>
                                                <input type="number" class="form-control" id="berat_kosong" name="berat_kosong" value="<?php echo isset($editSpecs['berat_chassis']['berat_kosong']) ? $editSpecs['berat_chassis']['berat_kosong'] : ''; ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="berat_total" class="form-label">Berat Total Kendaraan (kg)</label>
                                                <input type="number" class="form-control" id="berat_total" name="berat_total" value="<?php echo isset($editSpecs['berat_chassis']['berat_total_kendaraan']) ? $editSpecs['berat_chassis']['berat_total_kendaraan'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-hino"><?php echo isset($editProduct) ? 'Update' : 'Simpan'; ?> Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi konfirmasi hapus
        function confirmDelete(productId, productName) {
            if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?`)) {
                window.location.href = `admin_products.php?action=delete&id=${productId}`;
            }
        }

        // Buka modal otomatis jika dalam mode edit
        <?php if (isset($editProduct)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        });
        <?php endif; ?>

        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
    </script>
</body>
</html>
<?php
$conn->close();
?>