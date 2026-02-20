<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/repos.php';
require_once __DIR__ . '/../src/files.php';

require_login();

$repoId = (int)($_GET['id'] ?? 0);

if ($repoId <= 0) {
    $errorMessage = 'Неверный ID репозитория.';
} else {
    $repo = get_repository($repoId);
    if (!$repo || (int)$repo['user_id'] !== (int)$_SESSION['user_id']) {
        $errorMessage = 'Нет доступа к этому репозиторию.';
    } else {
        $files = get_files_by_repo($repoId);
        if (!$files || count($files) === 0) {
            $errorMessage = 'В репозитории нет файлов для скачивания.';
        } else {
            // Всё ок, продолжаем создание ZIP
            $zipPath = sys_get_temp_dir() . '/repo_' . $repoId . '_' . time() . '.zip';
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($files as $f) {
                    $relativePath = ltrim($f['path'], '/');
                    $diskPath = REPO_STORAGE . '/' . $repoId . '/' . $relativePath;
                    if (is_file($diskPath)) {
                        $zip->addFile($diskPath, $relativePath);
                    } else {
                        $zip->addFromString($relativePath, (string)$f['content']);
                    }
                }
                $zip->close();
                while (ob_get_level()) { ob_end_clean(); }
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $repo['slug'] . '.zip"');
                header('Content-Length: ' . filesize($zipPath));
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Pragma: public');
                header('Expires: 0');
                readfile($zipPath);
                unlink($zipPath);
                exit;
            } else {
                $errorMessage = 'Ошибка создания архива.';
            }
        }
    }
}

?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Скачать репозиторий</title>
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
    <h3 style="margin-bottom:15px;">Не удалось скачать репозиторий</h3>
    <p style="color:var(--text-muted);margin-bottom:25px;">
        <?= e($errorMessage) ?>
    </p>
    <a href="<?= BASE_URL ?>repo.php?id=<?= $repoId ?>" class="btn-primary" style="padding:12px 25px;">Вернуться к репозиторию</a>
</div>
</main>

<footer>
<div class="footer-nav">
<a href="<?= BASE_URL ?>about.php"><i class="fas fa-circle-info"></i>О нас</a>
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
