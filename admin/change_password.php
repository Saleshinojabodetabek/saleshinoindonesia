<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ganti Password Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container" style="max-width: 400px; margin-top: 100px;">
    <h3 class="text-center mb-4">Ganti Password</h3>

    <?php if (isset($_SESSION['msg'])): ?>
      <div class="alert alert-info"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <form method="POST" action="proses_ganti_password.php">
      <div class="mb-3">
        <label>Password Lama</label>
        <input type="password" name="old_password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Password Baru</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Ganti Password</button>

      <div class="d-flex justify-content-between">
        <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </form>
  </div>
</body>
</html>
