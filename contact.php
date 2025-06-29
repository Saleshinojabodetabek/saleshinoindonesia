<?php
// Ambil data dari form
$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Koneksi ke database
$host = "localhost";
$user = "u429834259_admin"; // Ganti dengan username database kamu
$password = "@Adminasiatekindo123";
$dbname = "u429834259_asiatekindo"; // Ganti dengan nama DB kamu

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Simpan data
$sql = "INSERT INTO contact_form (name, phone, message) VALUES ('$name', '$phone', '$message')";
if ($conn->query($sql) === TRUE) {
    echo "Pesan berhasil dikirim!";
} else {
    echo "Gagal mengirim pesan: " . $conn->error;
}

$conn->close();
?>