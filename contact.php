<?php
// Konfigurasi database
$host = "localhost";
$username = "u429834259_admin";
$password = "@Adminasiatekindo123";
$database = "u429834259_asiatekindo";

// Buat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Lindungi dari SQL Injection
$name = $conn->real_escape_string($name);
$phone = $conn->real_escape_string($phone);
$message = $conn->real_escape_string($message);

// Simpan ke database
$sql = "INSERT INTO contact_messages (name, phone, message) VALUES ('$name', '$phone', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Pesan Anda berhasil dikirim.";
} else {
    echo "Terjadi kesalahan: " . $conn->error;
}

$conn->close();
?>