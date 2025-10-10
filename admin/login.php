<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Script reCAPTCHA -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-light">
  <div class="container" style="max-width: 400px; margin-top: 100px;">
    <h3 class="text-center mb-4">Login Admin Blog</h3>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="proses_login.php">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required />
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required />
      </div>

      <!-- Google reCAPTCHA -->
      <div class="mb-3 text-center">
        <div class="g-recaptcha" data-sitekey="6LcA4uQrAAAAAJsF0P5ymoeIstRipWwh5IQD2hBC"></div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>