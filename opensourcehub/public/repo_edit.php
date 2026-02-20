<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/repos.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$repo = get_repository($id);

if (!$repo || $repo['user_id'] != $_SESSION['user_id']) {
    exit('Репозиторий не найден / доступ запрещён');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $private = isset($_POST['is_private']) ? 1 : 0;

    update_repository($id, $name, $desc, $private);

    header('Location: ' . BASE_URL . 'repo.php?id=' . $id);
    exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки репозитория — <?= e($repo['name']) ?></title>
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
    <div style="max-width: 700px; margin: 0 auto;">
        <div class="panel">
            <h2>Настройки репозитория</h2>
            
            <form method="post" style="margin-top: 25px;">
                <input type="hidden" name="id" value="<?= $repo['id'] ?>">

                <label>Название репозитория
                    <input name="name" value="<?= e($repo['name']) ?>" required>
                </label>

                <label style="margin-top: 20px;">Описание
                    <input name="description" value="<?= e($repo['description']) ?>" placeholder="Кратко о проекте">
                </label>

                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <button type="submit" class="btn-primary" style="flex: 1;">Сохранить изменения</button>
                    <a class="btn-ghost" href="<?= BASE_URL ?>repo.php?id=<?= $repo['id'] ?>" style="flex: 1;">Отмена</a>
                </div>
            </form>
        </div>

        <div class="panel" style="border-color: rgba(255, 71, 87, 0.2); margin-top: 20px;">
            <h3>Удаление проекта</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">
                Все файлы и данные будут удалены навсегда.
            </p>
            <a href="<?= BASE_URL ?>repo_delete.php?id=<?= $repo['id'] ?>" class="danger" style="width: 100%; text-align: center;" onclick="return confirm('Вы уверены, что хотите удалить этот репозиторий?')">Удалить репозиторий</a>
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