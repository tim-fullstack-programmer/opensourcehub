<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/users.php';
require_once __DIR__ . '/../src/helpers.php';

$error = '';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($value === '' || $password === '') {
        $error = 'Заполните все поля.';
    } else {
        $user = find_user_by_email_or_username($value);
        if ($user && password_verify($password, $user['password'])) {
            login_user((int)$user['id']);
            header('Location: ' . BASE_URL . 'dashboard.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль.';
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Вход</title>
<link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" href="<?= ASSETS_URL ?>icons/ico.ico" type="image/x-icon">
<style>
footer {
    padding: 30px 0;
    background: rgba(10, 12, 20, 0.95);
    border-top: 1px solid var(--border);
    text-align: center;
}
.footer-nav {
    display: flex;
    justify-content: center;
    gap: 25px;
    margin-bottom: 15px;
}
.footer-nav a {
    color: #fff;
    text-decoration: none;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
}
.footer-nav a:hover { color: var(--accent); }
.footer-copy {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: var(--text-muted);
    font-size: 0.9rem;
}
.footer-icon { width: 18px; height: 18px; }
</style>
</head>
<body>
<header class="topbar">
<div class="container topbar-inner">
<div class="brand">
<a href="<?= BASE_URL ?>dashboard.php" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
<img src="<?= ASSETS_URL ?>icons/ico_white.ico" alt="Logo" style="width:28px;height:28px;">
<span>OpenSourceHub</span>
</a>
</div>
</div>
</header>

<main class="container flex-center">
<div class="auth-card">
<h2>Авторизация</h2>
<?php if ($error): ?>
<div class="error" style="color:var(--danger);margin-bottom:15px;text-align:center;"><?= e($error) ?></div>
<?php endif; ?>
<form method="post">
<label>Имя пользователя или Email
<input name="email"placeholder="Введите имя пользователя или Email" required autofocus>
</label>
<label>Пароль
<input type="password" name="password" placeholder="Введите пароль"required>
</label>
<div style="display:flex;gap:10px;margin-top:20px;">
<button type="submit" class="btn-primary" style="flex:1;">Войти</button>
<a class="btn-ghost" href="<?= BASE_URL ?>register.php" style="flex:1;">Регистрация</a>
</div>
</form>
</div>
</main>

<footer>
<div class="footer-nav">
<a href="<?= BASE_URL ?>about.php"><i class="fas fa-circle-info"></i> О нас</a>
<a href="<?= BASE_URL ?>security.php"><i class="fas fa-shield-halved"></i> Безопасность</a>
</div>

<div class="footer-copy">
<img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" width="18">
OpenSourceHub © 2026
</div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
<script>
VANTA.NET({
    el: "body",
    mouseControls: true,
    touchControls: true,
    scale: 1.00,
    color: 0x6c5ce7,
    backgroundColor: 0x06070d,
    points: 12.00,
    maxDistance: 26.00,
    spacing: 14.00
});
</script>
</body>
</html>
