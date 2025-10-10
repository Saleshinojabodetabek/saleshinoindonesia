<?php
session_start();
include "config.php";

// === Konfigurasi Limit Login ===
$max_attempts = 10; // maksimal percobaan
$block_time = 600; // 600 detik = 10 menit

// Cek apakah user masih diblokir
if (isset($_SESSION['last_attempt_time']) && isset($_SESSION['login_attempts'])) {
    $elapsed_time = time() - $_SESSION['last_attempt_time'];
    if ($_SESSION['login_attempts'] >= $max_attempts && $elapsed_time < $block_time) {
        $remaining = ceil(($block_time - $elapsed_time) / 60);
        $_SESSION['error'] = "Terlalu banyak percobaan gagal. Coba lagi dalam $remaining menit.";
        header("Location: login.php");
        exit();
    } elseif ($elapsed_time >= $block_time) {
        // reset jika sudah lewat 10 menit
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_attempt_time']);
    }
}

// === CEK RECAPTCHA ===
$secretKey = "6LcA4uQrAAAAADohWlB8BZcjVSYU0Pbx7nmjjezc";
$responseKey = $_POST['g-recaptcha-response'] ?? '';
$userIP = $_SERVER['REMOTE_ADDR'];

$verifyURL = "https://www.google.com/recaptcha/api/siteverify";
$data = [
    'secret' => $secretKey,
    'response' => $responseKey,
    'remoteip' => $userIP
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($verifyURL, false, $context);
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
    $_SESSION['error'] = "Verifikasi reCAPTCHA gagal. Silakan coba lagi.";
    header("Location: login.php");
    exit();
}

// === CEK LOGIN USERNAME & PASSWORD ===
$username = trim($_POST['username']);
$password = trim($_POST['password']);

$sql = "SELECT * FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Reset percobaan gagal jika berhasil login
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_attempt_time']);

        $_SESSION['admin'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    }
}

// Jika login gagal
$_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
$_SESSION['last_attempt_time'] = time();

$remaining = $max_attempts - $_SESSION['login_attempts'];
if ($remaining > 0) {
    $_SESSION['error'] = "Username atau password salah! ($remaining percobaan tersisa)";
} else {
    $_SESSION['error'] = "Terlalu banyak percobaan gagal. Akun diblokir sementara 10 menit.";
}

header("Location: login.php");
exit();
?>
