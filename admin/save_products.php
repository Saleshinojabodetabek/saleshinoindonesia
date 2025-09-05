<?php
session_start();
include "koneksi.php";
include "config.php"

// Proses form jika data dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    // Escape data untuk mencegah SQL injection
    $name = $conn->real_escape_string($_POST['name']);
    $model = $conn->real_escape_string($_POST['model']);
    $description = $conn->real_escape_string($_POST['description']);
    $is_active = isset($_POST['is_active']) ? intval($_POST['is_active']) : 0;
    
    if ($product_id > 0) {
        // Update produk yang sudah ada
        $sql = "UPDATE products SET 
                name = '$name', 
                model = '$model', 
                description = '$description', 
                is_active = $is_active,
                updated_at = NOW()
                WHERE id = $product_id";
    } else {
        // Insert produk baru
        $sql = "INSERT INTO products (name, model, description, is_active) 
                VALUES ('$name', '$model', '$description', $is_active)";
    }
    
    if ($conn->query($sql) === TRUE) {
        // Jika produk baru, dapatkan ID yang baru saja dibuat
        if ($product_id === 0) {
            $product_id = $conn->insert_id;
        }
        
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
        if (isset($_POST['karoseri'])) {
            foreach ($_POST['karoseri'] as $type) {
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
            $model_mesin = $conn->real_escape_string($_POST['model_mesin']);
            $tipe_mesin = $conn->real_escape_string($_POST['tipe_mesin']);
            $tenaga_maksimum = $conn->real_escape_string($_POST['tenaga_maksimum']);
            $daya_maksimum = $conn->real_escape_string($_POST['daya_maksimum']);
            $jumlah_silinder = intval($_POST['jumlah_silinder']);
            $diameter_langkah_piston = $conn->real_escape_string($_POST['diameter_langkah_piston']);
            $isi_silinder = intval($_POST['isi_silinder']);
            
            $conn->query("INSERT INTO model_mesins (product_id, model, tipe, tenaga_maksimum, daya_maksimum, jumlah_silinder, diameter_langkah_piston, isi_silinder) 
                         VALUES ($product_id, '$model_mesin', '$tipe_mesin', '$tenaga_maksimum', '$daya_maksimum', $jumlah_silinder, '$diameter_langkah_piston', $isi_silinder)");
        }
        
        // Simpan data kopling
        if (!empty($_POST['kopling_tipe']) || !empty($_POST['kopling_diameter'])) {
            $kopling_tipe = $conn->real_escape_string($_POST['kopling_tipe']);
            $kopling_diameter = intval($_POST['kopling_diameter']);
            $conn->query("INSERT INTO koplings (product_id, tipe, diameter_cakram) VALUES ($product_id, '$kopling_tipe', $kopling_diameter)");
        }
        
        // Simpan data transmisi
        if (!empty($_POST['transmisi_tipe'])) {
            $transmisi_tipe = $conn->real_escape_string($_POST['transmisi_tipe']);
            $transmisi_ke1 = $conn->real_escape_string($_POST['transmisi_ke1']);
            $transmisi_ke2 = $conn->real_escape_string($_POST['transmisi_ke2']);
            $transmisi_ke3 = $conn->real_escape_string($_POST['transmisi_ke3']);
            $transmisi_ke4 = $conn->real_escape_string($_POST['transmisi_ke4']);
            $transmisi_ke5 = $conn->real_escape_string($_POST['transmisi_ke5']);
            $transmisi_mundur = $conn->real_escape_string($_POST['transmisi_mundur']);
            
            $conn->query("INSERT INTO transmisis (product_id, tipe, ke_1, ke_2, ke_3, ke_4, ke_5, mundur) 
                         VALUES ($product_id, '$transmisi_tipe', '$transmisi_ke1', '$transmisi_ke2', '$transmisi_ke3', '$transmisi_ke4', '$transmisi_ke5', '$transmisi_mundur')");
        }
        
        // Simpan data kemudi
        if (!empty($_POST['kemudi_tipe']) || !empty($_POST['kemudi_radius'])) {
            $kemudi_tipe = $conn->real_escape_string($_POST['kemudi_tipe']);
            $kemudi_radius = floatval($_POST['kemudi_radius']);
            $conn->query("INSERT INTO kemudis (product_id, tipe, minimal_radius_putar) VALUES ($product_id, '$kemudi_tipe', $kemudi_radius)");
        }
        
        // Simpan data sumbu
        if (!empty($_POST['sumbu_depan']) || !empty($_POST['sumbu_belakang'])) {
            $sumbu_depan = $conn->real_escape_string($_POST['sumbu_depan']);
            $sumbu_belakang = $conn->real_escape_string($_POST['sumbu_belakang']);
            $sumbu_perbandingan = floatval($_POST['sumbu_perbandingan']);
            $conn->query("INSERT INTO sumbus (product_id, depan, belakang, perbandingan_gigi_akhir) VALUES ($product_id, '$sumbu_depan', '$sumbu_belakang', $sumbu_perbandingan)");
        }
        
        // Simpan data rem
        if (!empty($_POST['rem_utama']) || !empty($_POST['rem_pelambat'])) {
            $rem_utama = $conn->real_escape_string($_POST['rem_utama']);
            $rem_pelambat = $conn->real_escape_string($_POST['rem_pelambat']);
            $rem_parkir = $conn->real_escape_string($_POST['rem_parkir']);
            $conn->query("INSERT INTO rems (product_id, rem_utama, rem_pelambat, rem_parkir) VALUES ($product_id, '$rem_utama', '$rem_pelambat', '$rem_parkir')");
        }
        
        // Simpan data roda & ban
        if (!empty($_POST['roda_ban_ukuran']) || !empty($_POST['roda_ban_rim'])) {
            $roda_ban_ukuran = $conn->real_escape_string($_POST['roda_ban_ukuran']);
            $roda_ban_rim = $conn->real_escape_string($_POST['roda_ban_rim']);
            $roda_ban_jumlah = $conn->real_escape_string($_POST['roda_ban_jumlah']);
            $conn->query("INSERT INTO roda_bans (product_id, ukuran_ban, ukuran_rim, jumlah_ban) VALUES ($product_id, '$roda_ban_ukuran', '$roda_ban_rim', '$roda_ban_jumlah')");
        }
        
        // Simpan data sistem listrik
        if (!empty($_POST['sistem_listrik_accu'])) {
            $sistem_listrik_accu = $conn->real_escape_string($_POST['sistem_listrik_accu']);
            $conn->query("INSERT INTO sistim_listriks (product_id, accu) VALUES ($product_id, '$sistem_listrik_accu')");
        }
        
        // Simpan data tangki solar
        if (!empty($_POST['tangki_solar_kapasitas'])) {
            $tangki_solar_kapasitas = intval($_POST['tangki_solar_kapasitas']);
            $conn->query("INSERT INTO tangki_solars (product_id, kapasitas) VALUES ($product_id, $tangki_solar_kapasitas)");
        }
        
        // Simpan data dimensi
        if (!empty($_POST['dimensi_jarak_sumbu']) || !empty($_POST['dimensi_panjang'])) {
            $dimensi_jarak_sumbu = intval($_POST['dimensi_jarak_sumbu']);
            $dimensi_panjang = intval($_POST['dimensi_panjang']);
            $dimensi_lebar = intval($_POST['dimensi_lebar']);
            $dimensi_tinggi = intval($_POST['dimensi_tinggi']);
            $dimensi_jejak_depan = intval($_POST['dimensi_jejak_depan']);
            $dimensi_jejak_belakang = intval($_POST['dimensi_jejak_belakang']);
            $dimensi_julur_depan = intval($_POST['dimensi_julur_depan']);
            $dimensi_julur_belakang = intval($_POST['dimensi_julur_belakang']);
            $dimensi_kabin = intval($_POST['dimensi_kabin']);
            
            $conn->query("INSERT INTO dimensis (product_id, jarak_sumbu_roda, total_panjang, total_lebar, total_tinggi, lebar_jejak_depan, lebar_jejak_belakang, julur_depan, julur_belakang, kabin_kesumbu_roda_belakang) 
                         VALUES ($product_id, $dimensi_jarak_sumbu, $dimensi_panjang, $dimensi_lebar, $dimensi_tinggi, $dimensi_jejak_depan, $dimensi_jejak_belakang, $dimensi_julur_depan, $dimensi_julur_belakang, $dimensi_kabin)");
        }
        
        // Simpan data suspensi
        if (!empty($_POST['suspensi'])) {
            $suspensi = $conn->real_escape_string($_POST['suspensi']);
            $conn->query("INSERT INTO suspensis (product_id, depan_belakang) VALUES ($product_id, '$suspensi')");
        }
        
        // Simpan data berat chassis
        if (!empty($_POST['berat_kosong']) || !empty($_POST['berat_total'])) {
            $berat_kosong = intval($_POST['berat_kosong']);
            $berat_total = intval($_POST['berat_total']);
            $conn->query("INSERT INTO berat_chassis (product_id, berat_kosong, berat_total_kendaraan) VALUES ($product_id, $berat_kosong, $berat_total)");
        }
        
        $_SESSION['message'] = "Produk berhasil " . ($product_id > 0 ? "diupdate" : "ditambahkan");
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
    
    $conn->close();
    header("Location: dashboard.php");
    exit();
}

$conn->close();
header("Location: dashboard.php");
exit();
?>