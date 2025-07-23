<?php
session_start();
include "config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  // Tambahan debug
  echo "<pre>";
  echo "Input password: " . $password . "\n";
  echo "Hash di DB: " . $user['password'] . "\n";
  echo "Verifikasi: " . (password_verify($password, $user['password']) ? 'true' : 'false') . "\n";
  echo "</pre>";
  exit();

  if (password_verify($password, $user['password'])) {
    $_SESSION['admin'] = $user['username'];
    header("Location: dashboard.php");
    exit();
  }
}

$_SESSION['error'] = "Username atau password salah!";
header("Location: login.php");
exit();
?>
