<?php
require_once __DIR__ . '/../src/config_path.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404</title>
<link rel="icon" href="<?= BASE_URL ?>/assets/icons/ico.ico">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
<img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" style="width:28px;height:28px;">
<span>OpenSourceHub</span>
</a>
</div>

<nav>
<a href="<?= BASE_URL ?>dashboard.php">На главную</a>
</nav>

</div>
</header>

<main class="container flex-center">

<div class="panel" style="text-align:center; max-width:600px;">

<h1 style="font-size:64px;margin-bottom:10px;">404</h1>

<h3 style="margin-bottom:15px;">Страница не найдена</h3>

<p style="color:var(--text-muted);margin-bottom:25px;">
Возможно страница была удалена, перемещена или ссылка неверная.
</p>

<a href="<?= BASE_URL ?>dashboard.php" class="btn-primary">
Вернуться на главную
</a>

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
el:"body",
mouseControls:true,
touchControls:true,
scale:1.0,
color:0x6c5ce7,
backgroundColor:0x06070d
});
</script>

</body>
</html>