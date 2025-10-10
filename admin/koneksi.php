<?php
$host = "localhost";
$user = "u868657420_root";
$pass = "NatanaelH1no0504@@";
$db   = "u868657420_db_dealer_hino";

// Buat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
