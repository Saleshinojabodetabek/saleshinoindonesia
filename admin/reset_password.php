<?php
include "config.php";

$newPassword = password_hash("admin123", PASSWORD_DEFAULT);

$sql = "UPDATE admin SET password = ? WHERE username = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $newPassword);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "Password admin berhasil direset ke admin123.";
} else {
  echo "Gagal reset password.";
}
?>