<?php
require_once __DIR__ . '/../config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = db()->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (bool)$user['is_admin'];
        header('Location: /admin/dashboard.php');
        exit;
    }
    $error = 'Invalid email or password.';
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — Sandesh Art Arena</title>
<link rel="stylesheet" href="https://artarenastudio.com/assets/style.css">
<link rel="stylesheet" href="https://artarenastudio.com/assets/artful-theme.css">
<link rel="stylesheet" href="/assets/personal.css">
</head>
<body class="login-body">
<div class="login-box">
  <img class="login-logo" src="https://artarenastudio.com/assets/sandesh-art-arena-logo.jpg" alt="Sandesh Art Arena">
  <p class="login-eyebrow">Personal Studio</p>
  <h1>Admin Login</h1>
  <?php if ($error): ?><div class="flash err"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="login-form">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" placeholder="admin@artarenastudio.com" required autofocus>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" placeholder="••••••••" required>
    <button class="btn primary login-submit" type="submit">Log In →</button>
  </form>
  <p class="login-back"><a href="/">← Back to site</a></p>
</div>
</body>
</html>
