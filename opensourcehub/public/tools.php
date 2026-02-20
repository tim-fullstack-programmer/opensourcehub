<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';

require_login();
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Инструменты</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{background:#0b1020;color:#e5e7eb;font-family:Inter,system-ui,sans-serif}
.tools-home{max-width:900px;margin:100px auto;text-align:center}
.tools-home h1{margin-bottom:60px;font-size:2.2rem;color:#7a30ea}

.btn-tool{
  display:inline-block;
  width:220px;
  margin:20px;
  padding:40px 20px;
  font-size:1.2rem;
  font-weight:600;
  color:#fff;
  background:#7a30ea;
  border-radius:20px;
  text-decoration:none;
  transition:0.4s;
  position:relative;
}
.btn-tool i{
  display:block;
  font-size:2.5rem;
  margin-bottom:12px;
  transition:0.4s;
}
.btn-tool:hover{
  background:#5f27c8;
  transform:translateY(-5px);
}
.btn-tool:hover i{
  transform:rotate(15deg) scale(1.2);
}
.btn-tool:active{
  transform:translateY(-2px);
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
        .footer-icon { width: 18px; height: 18px; }
    </style>
</style>
</head>
<body>

<header class="topbar">
  <div class="container topbar-inner">
    <div class="brand">
            <a href="<?= BASE_URL ?>dashboard.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" alt="Logo" style="width: 28px; height: 28px; object-fit: contain;">
                <span>OpenSourceHub</span>
            </a>
        </div>
    <nav>
            <a href="<?= BASE_URL ?>dashboard.php">Главный экран</a>
            <a href="<?= BASE_URL ?>account_edit.php">Настройки</a>
            <a class="danger" href="<?= BASE_URL ?>logout.php">Выход</a>
    </nav>
  </div>
</header>

<main class="tools-home">
<h1>Выберите инструмент</h1>

<a href="<?= BASE_URL ?>zip_tool.php" class="btn-tool">
  <i class="fa-solid fa-file-zipper"></i>
  ZIP<br>Архиватор
</a>

<a href="<?= BASE_URL ?>password_generator.php" class="btn-tool">
  <i class="fa-solid fa-key"></i>
  Генератор паролей
</a>

<a href="<?= BASE_URL ?>qr_generator.php" class="btn-tool">
  <i class="fa-solid fa-qrcode"></i>
  QR Code Генератор
</a>

</main>

<footer>
    <div class="footer-nav">
        <a href="<?= BASE_URL ?>about.php"><i class="fas fa-circle-info"></i> О нас</a>
        <a href="<?= BASE_URL ?>security.php"><i class="fas fa-shield-halved"></i> Безопасность</a>
    </div>
    <div class="footer-copy">
        <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" class="footer-icon" alt="icon">
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
    gyroControls: false,
    scale: 1,
    color: 0x7a30ea,
    backgroundColor: 0x0
});
</script>
</body>
</html>
