<?php
$host = "localhost";
$user = "u429834259_hinoindonesia";
$pass = "NatanaelH1no0504@@";
$db   = "u429834259_hino_indonesia";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>
