<?php
// Konfigurasi database
$host = "localhost";
$username = "u868657420_root";
$password = "Natanael110405";
$database = "u868657420_db_dealer_hino";

// Koneksi ke MySQL
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form (dengan validasi sederhana)
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validasi sederhana
if (empty($name) || empty($phone) || empty($message)) {
    die("❌ Semua field harus diisi.");
}

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("INSERT INTO contact_messages (name, phone, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $phone, $message);

if ($stmt->execute()) {
    echo "✅ Pesan Anda berhasil dikirim.";
} else {
    echo "❌ Terjadi kesalahan: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
