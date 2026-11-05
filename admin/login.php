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
<title>Admin Login</title>
<link rel="stylesheet" href="/assets/personal.css">
<style>body{background:#171820;display:flex;min-height:100vh;align-items:center;justify-content:center;font-family:'DM Sans',Arial,sans-serif}
.login-box{background:#fff;padding:2.2rem 2.4rem;border-radius:10px;width:100%;max-width:360px}
.login-box h1{font-family:'Playfair Display',serif;font-size:1.6rem;margin-bottom:1.2rem}
.login-box input{width:100%;padding:.7rem .8rem;border:1px solid var(--line);border-radius:6px;margin-bottom:1rem;font:400 .95rem 'DM Sans',sans-serif}
.login-box button{width:100%}
</style>
</head>
<body>
<div class="login-box">
  <h1>Admin Login</h1>
  <?php if ($error): ?><div class="flash err"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="btn" type="submit">Log In</button>
  </form>
</div>
</body>
</html>
