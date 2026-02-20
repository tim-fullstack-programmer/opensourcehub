<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';

require_login();

$user = current_user();

if (!$user || !isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    header('Location: ' . BASE_URL . '403.php');
    exit;
}

$pdo = getPDO();

if (isset($_GET['delete_user'])) {
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([(int)$_GET['delete_user']]);
}

if (isset($_GET['delete_repo'])) {
    $pdo->prepare("DELETE FROM repositories WHERE id=?")->execute([(int)$_GET['delete_repo']]);
}

if (isset($_GET['delete_file'])) {
    $pdo->prepare("DELETE FROM repo_files WHERE id=?")
        ->execute([(int)$_GET['delete_file']]);
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
$repos = $pdo->query("SELECT * FROM repositories ORDER BY id DESC")->fetchAll();
$files = $pdo->query("SELECT * FROM repo_files ORDER BY id DESC")->fetchAll();
?>

<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Админ панель</title>
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
<span>Админ панель</span>
</a>
</div>
<nav>
<a href="<?= BASE_URL ?>admin_logout.php">Выход</a>
</nav>
</div>
</header>

<main class="container">

<div class="panel">
<h2>Пользователи</h2>

<?php foreach($users as $u): ?>
<div class="list-item">
ID <?= $u['id'] ?> | <?= $u['username'] ?> | <?= $u['email'] ?>
<a class="danger" href="?delete_user=<?= $u['id'] ?>">Удалить</a>
</div>
<?php endforeach; ?>

</div>

<div class="panel">
<h2>Репозитории</h2>

<?php foreach($repos as $r): ?>
<div class="list-item">
ID <?= $r['id'] ?> | <?= $r['name'] ?>
<a class="danger" href="?delete_repo=<?= $r['id'] ?>">Удалить</a>
</div>
<?php endforeach; ?>

</div>

<div class="panel">
<h2>Файлы</h2>

<?php foreach($files as $f): ?>
<div class="list-item">
ID <?= $f['id'] ?> | <?= $f['path'] ?>
<a class="danger" href="?delete_file=<?= $f['id'] ?>">Удалить</a>
</div>
<?php endforeach; ?>

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