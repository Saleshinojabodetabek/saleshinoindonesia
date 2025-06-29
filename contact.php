<?php
header('Content-Type: text/plain');

$host = "localhost";
$user = "u429834259_admin";
$password = "@Adminasiatekindo123";
$database = "u429834259_asiatekindo";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Koneksi gagal: " . $conn->connect_error;
    exit();
}

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$message = $_POST['message'] ?? '';

$sql = "INSERT INTO contact_messages (name, phone, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $phone, $message);

if ($stmt->execute()) {
    echo "Pesan Anda berhasil dikirim.";
} else {
    http_response_code(500);
    echo "Gagal mengirim pesan.";
}

$stmt->close();
$conn->close();
?>
