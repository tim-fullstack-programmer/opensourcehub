<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>О нас</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/icons/ico.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.about-description {
    text-align: center;
    color: var(--text-muted);
    font-size: 1.05rem;
    line-height: 1.8;
    margin: 0 auto 40px;
}

.about-features {
    display: grid;
    gap: 18px;
    justify-content: center;
}

.feature-item {
    padding: 18px 22px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    background: rgba(255,255,255,0.02);

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    text-align: center;

    gap: 12px;
    transition: var(--transition);

    max-width: 520px;
    width: 100%;
}

.feature-item:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}

.feature-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(108,92,231,0.15);
    color: var(--accent);
    font-size: 18px;
}

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

<main class="container" style="max-width: 800px; margin-top: 40px;">

<div class="panel slide-in" style="text-align:center; padding:40px 30px;">

<h2 style="color:#fff; display:flex; flex-direction:column; align-items:center; gap:10px; margin-bottom:30px;">
<span style="font-size:1.5rem; color:var(--text-muted);">О проекте</span>
<span style="font-size:2.8rem; color:#a29bfe; display:flex; align-items:center; gap:12px;">
<img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" width="35">
OpenSourceHub
</span>
</h2>

<p class="about-description">
OpenSourceHub — это веб-платформа для управления программными проектами и репозиториями кода.
Система предоставляет удобный интерфейс для хранения, редактирования и распространения
программных решений через браузер.
</p>

<div class="about-features">

<div class="feature-item">
<div class="feature-icon"><i class="fas fa-folder"></i></div>
<div>
<strong>Управление репозиториями</strong><br>
Создание и хранение проектов со структурированной системой файлов.
</div>
</div>

<div class="feature-item">
<div class="feature-icon"><i class="fas fa-code"></i></div>
<div>
<strong>Редактирование файлов</strong><br>
Просмотр и изменение кода напрямую через веб-интерфейс.
</div>
</div>

<div class="feature-item">
<div class="feature-icon"><i class="fas fa-upload"></i></div>
<div>
<strong>Загрузка файлов</strong><br>
Быстрая загрузка файлов и управление содержимым проектов.
</div>
</div>

<div class="feature-item">
<div class="feature-icon"><i class="fas fa-download"></i></div>
<div>
<strong>Скачивание проектов</strong><br>
Экспорт репозиториев и доступ к исходному коду.
</div>
</div>

</div>
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