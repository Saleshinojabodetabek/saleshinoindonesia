<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    
    // Hapus data spesifikasi lama
    $tables = [
        'karoseris', 'performas', 'model_mesins', 'koplings', 'transmisis',
        'kemudis', 'sumbus', 'rems', 'roda_bans', 'sistim_listriks',
        'tangki_solars', 'dimensis', 'suspensis', 'berat_chassis'
    ];
    
    foreach ($tables as $table) {
        $conn->query("DELETE FROM $table WHERE product_id = $product_id");
    }
    
    // Simpan data karoseri
    if (isset($_POST['karoseri_types'])) {
        foreach ($_POST['karoseri_types'] as $type) {
            $type = $conn->real_escape_string($type);
            $conn->query("INSERT INTO karoseris (product_id, type) VALUES ($product_id, '$type')");
        }
    }
    
    // Simpan data performa
    if (!empty($_POST['kecepatan_maksimum']) || !empty($_POST['daya_tanjak'])) {
        $kecepatan_maksimum = intval($_POST['kecepatan_maksimum']);
        $daya_tanjak = floatval($_POST['daya_tanjak']);
        $conn->query("INSERT INTO performas (product_id, kecepatan_maksimum, daya_tanjak) VALUES ($product_id, $kecepatan_maksimum, $daya_tanjak)");
    }
    
    // Simpan data model mesin
    if (!empty($_POST['model_mesin']) || !empty($_POST['tipe_mesin'])) {
        $model = $conn->real_escape_string($_POST['model_mesin']);
        $tipe = $conn->real_escape_string($_POST['tipe_mesin']);
        $tenaga_maksimum = $conn->real_escape_string($_POST['tenaga_maksimum']);
        $daya_maksimum = $conn->real_escape_string($_POST['daya_maksimum']);
        $conn->query("INSERT INTO model_mesins (product_id, model, tipe, tenaga_maksimum, daya_maksimum) VALUES ($product_id, '$model', '$tipe', '$tenaga_maksimum', '$daya_maksimum')");
    }
    
    // Tambahkan penyimpanan untuk tabel lainnya sesuai kebutuhan
    
    $_SESSION['message'] = "Spesifikasi berhasil disimpan";
    header("Location: admin_product_specs.php?id=$product_id");
    exit();
}

header("Location: admin_products.php");
exit();
?>