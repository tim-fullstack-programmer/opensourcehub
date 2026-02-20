<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/repos.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();
$user = current_user();
$repos = get_repositories_by_user((int)$user['id']);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/icons/ico.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <nav><a href="tools.php">Инструменты</a><a href="account_edit.php">Настройки</a><a class="danger" href="logout.php">Выход</a></nav>
    </div>
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
</header>
<main class="container">
    <div class="panel slide-in" style="text-align: center; padding: 50px 20px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 15px;">
        <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" alt="Logo" style="width: 32px; height: 32px; object-fit: contain;">
        <h1 style="font-size: 2.5rem; color: #a29bfe; margin: 0; font-weight: 700;">OpenSourceHub</h1>
    </div>
    
    <p style="max-width: 800px; margin: 0 auto; font-size: 1.2rem; color: var(--text-muted); line-height: 1.6;">
        — это репозиторий для создания, хранения и редактирования IT проектов. 
        Создавайте репозитории, добавляйте файлы, редактируйте код прямо в браузере.
    </p>
</div>

    <div class="grid-layout">
        <div class="panel">
            <h3>Мои репозитории</h3>
            <div style="margin-top:20px;">
                <?php foreach ($repos as $r): ?>
                <div class="list-item">
                    <a href="repo.php?id=<?= $r['id'] ?>" style="color:#a29bfe; text-decoration:none; font-weight:700;"><?= e($r['name']) ?></a>
                    <div class="actions" style="display:flex; gap:10px;">
                        <a href="repo_edit.php?id=<?= $r['id'] ?>" class="btn-ghost" style="padding: 5px 12px; font-size:0.8rem;">Ред.</a>
                        <a href="repo_delete.php?id=<?= $r['id'] ?>" class="danger" style="padding: 5px 12px; font-size:0.8rem;" onclick="return confirm('Удалить?')">Удалить</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="panel">
            <h3>Создать новый</h3>
            <form method="post" action="repo_create.php" style="margin-top:15px;">
                <input name="name" placeholder="Название репозитория" required>
                <input name="description" placeholder="Краткое описание">
                <button class="btn-primary" style="margin-top:15px; width:100%;">Создать репозиторий</button>
            </form>
        </div>
    </div>
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
<script>VANTA.NET({el:"body",mouseControls:true,touchControls:true,scale:1.0,color:0x6c5ce7,backgroundColor:0x06070d});</script>
</body>
</html>