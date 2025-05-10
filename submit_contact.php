<?php
$host = 'localhost';
$dbname = 'u429834259_asiatekindo';
$username = 'u429834259_adminasiatek';
$password = 'PASSWORD_ANDA'; // Ganti dengan password database Anda

// Buat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Simpan ke database
$sql = "INSERT INTO contact_form (name, phone, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $phone, $message);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
