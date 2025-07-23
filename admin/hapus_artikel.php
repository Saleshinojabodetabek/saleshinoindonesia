<?php
include "cek_login.php";
include "config.php";

$id = $_GET['id'];

// hapus gambar dulu
$cek = $conn->query("SELECT gambar FROM artikel WHERE id=$id");
$data = $cek->fetch_assoc();
if (file_exists("uploads/" . $data['gambar'])) {
  unlink("uploads/" . $data['gambar']);
}

// hapus dari database
$conn->query("DELETE FROM artikel WHERE id=$id");

header("Location: dashboard.php");
?>
