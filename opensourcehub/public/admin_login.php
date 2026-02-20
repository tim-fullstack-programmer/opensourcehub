<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $ADMIN_LOGIN = 'admin';
    $ADMIN_PASSWORD = 'admin';

    if ($login === $ADMIN_LOGIN && $password === $ADMIN_PASSWORD) {
        $_SESSION['admin_logged'] = true;
        redirect(BASE_URL . 'admin.php');
    }

    $error = 'Неверные данные';
}
?>

<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Вход в Админ панель</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/icons/ico.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
.footer-icon { width: 18px; }
</style>
</head>
<body>
    
<header class="topbar">
<div class="container topbar-inner">
<div class="brand">
<a href="<?= BASE_URL ?>dashboard.php" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
<img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" width="28">
<span>OpenSourceHub</span>
</a>
</div>
<nav>
<a href="<?= BASE_URL ?>dashboard.php">На главную</a>
</nav>
</div>
</header>

<main class="container flex-center">
<div class="panel" style="max-width:400px;text-align:center;">

<h2>Вход в Админ панель</h2>

<?php if($error): ?>
<p style="color:#ff7675"><?= $error ?></p>
<?php endif; ?>

<form method="post">
<input name="login" placeholder="Логин" required>
<input type="password" name="password" placeholder="Пароль" required>

<button class="btn-primary" style="margin-top:15px;width:100%">
Войти
</button>
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
    scale: 1,
    color: 0x6c5ce7,
    backgroundColor: 0x06070d
});
</script>
</body>
</html>