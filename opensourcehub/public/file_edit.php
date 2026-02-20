<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/files.php';
require_once __DIR__ . '/../src/repos.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$f = get_file($id);

if (!$f) exit('Файл не найден');
$repo = get_repository($f['repo_id']);
if ($repo['user_id'] != $_SESSION['user_id']) exit('Доступ запрещён');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    update_file_content($id, $_POST['content']);
    header('Location: ' . BASE_URL . '/repo.php?id=' . $repo['id']);
    exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Редактировать <?= e($f['filename']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
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
            <a href="<?= BASE_URL ?>dashboard.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" alt="Logo" style="width: 28px; height: 28px; object-fit: contain;">
                <span>OpenSourceHub</span>
            </a>
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
        <h2>Редактировать файл: <span style="color: var(--accent);"><?= e($f['filename']) ?></span></h2>
        
        <form method="post" style="margin-top: 20px;">
            <input type="hidden" name="id" value="<?= $f['id'] ?>">
            <label>Содержимое файла</label>
            <textarea name="content" rows="20" style="font-family: monospace; font-size: 14px;"><?= e($f['content']) ?></textarea>
            
            <div style="margin-top: 20px; display: flex; gap: 15px;">
                <button class="btn-primary">Сохранить изменения</button>
                <a class="btn-ghost" href="<?= BASE_URL ?>repo.php?id=<?= $repo['id'] ?>">Отмена</a>
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
        <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" class="footer-icon" alt="icon">
        OpenSourceHub © 2026
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
<script>
    VANTA.NET({
        el: "body",
        mouseControls: true, touchControls: true, scale: 1.0,
        color: 0x6c5ce7, backgroundColor: 0x06070d
    });
</script>
</body>
</html>