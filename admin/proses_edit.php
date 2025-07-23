<?php
include "cek_login.php";
include "config.php";

$id = $_POST['id'];
$judul = $_POST['judul'];
$isi = $_POST['isi'];

if ($_FILES['gambar']['name'] != "") {
  $nama_file = $_FILES['gambar']['name'];
  $tmp_file = $_FILES['gambar']['tmp_name'];
  $target_dir = "uploads/";
  $target_file = $target_dir . time() . "_" . basename($nama_file);

  if (move_uploaded_file($tmp_file, $target_file)) {
    $gambar = basename($target_file);
    $conn->query("UPDATE artikel SET judul='$judul', isi='$isi', gambar='$gambar' WHERE id=$id");
  }
} else {
  $conn->query("UPDATE artikel SET judul='$judul', isi='$isi' WHERE id=$id");
}

header("Location: dashboard.php");
?>
