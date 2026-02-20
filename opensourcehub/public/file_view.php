<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/files.php';
require_once __DIR__ . '/../src/repos.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$id = (int)($_GET['id'] ?? 0);
$f = get_file($id);

if (!$f) exit('Файл не найден');

$repo = get_repository($f['repo_id']);

if (!$repo || $repo['user_id'] != $_SESSION['user_id']) {
    exit('Доступ запрещён');
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($f['filename']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
    <style>
        .code-container {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
            margin: 20px 0;
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #d1d5db;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
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
        .file-buttons { display: flex; gap: 10px; margin-bottom: 15px; }
    </style>
</head>

<body>
<header class="topbar">
    <div class="container topbar-inner">
        <div class="file-buttons">
            <a href="<?= BASE_URL ?>repo.php?id=<?= $repo['id'] ?>" class="btn-ghost">← Назад</a>
            <a href="<?= BASE_URL ?>file_edit.php?id=<?= $f['id'] ?>" class="btn-primary">Редактировать</a>
            <?php if (preg_match('/\.html$/i', $f['filename'])): ?>
                <a href="<?= BASE_URL ?>repo_preview.php?file_id=<?= $f['id'] ?>" class="btn-primary" target="_blank">Запустить</a>
            <?php endif; ?>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>dashboard.php">Главная</a>
            <a href="<?= BASE_URL ?>account_edit.php">Настройки</a>
            <a class="danger" href="<?= BASE_URL ?>logout.php">Выход</a>
        </nav>
    </div>
</header>

<main class="container">
    <div class="panel">
        <h2>Просмотр файла: <span style="color: var(--accent);"><?= e($f['filename']) ?></span></h2>
        <div class="code-container">
            <pre><?= e($f['content']) ?></pre>
        </div>
        <a href="<?= BASE_URL ?>repo.php?id=<?= $repo['id'] ?>" class="btn-ghost">Вернуться к списку файлов</a>
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
<script>
    VANTA.NET({
        el: "body",
        mouseControls: true,
        touchControls: true,
        scale: 1.0,
        color: 0x6c5ce7,
        backgroundColor: 0x06070d
    });
</script>
</body>
</html>
