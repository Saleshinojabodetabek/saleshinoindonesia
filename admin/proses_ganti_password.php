<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['admin'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validasi password baru
if ($new_password !== $confirm_password) {
    $_SESSION['msg'] = "Konfirmasi password tidak cocok.";
    header("Location: change_password.php");
    exit();
}

// Ambil password lama dari database
$sql = "SELECT password FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifikasi password lama
    if (password_verify($old_password, $user['password'])) {
        // Update password baru (pakai hash)
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
        $update->bind_param("ss", $hashed_password, $username);
        $update->execute();

        $_SESSION['msg'] = "Password berhasil diubah!";
    } else {
        $_SESSION['msg'] = "Password lama salah!";
    }
} else {
    $_SESSION['msg'] = "User tidak ditemukan!";
}

header("Location: change_password.php");
exit();
?>
