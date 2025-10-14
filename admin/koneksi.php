<?php
$host = "localhost";
$user = "u429834259_hinoindonesia";
$pass = "NatanaelH1no0504@@";
$db   = "u429834259_hino_indonesia";

// Buat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
