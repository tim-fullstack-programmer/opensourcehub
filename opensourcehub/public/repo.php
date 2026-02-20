<?php 
require_once __DIR__ . '/../src/config_path.php'; 
require_once __DIR__ . '/../src/auth.php'; 
require_once __DIR__ . '/../src/repos.php'; 
require_once __DIR__ . '/../src/files.php'; 
require_once __DIR__ . '/../src/helpers.php'; 

require_login(); 

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0); 
$repo = get_repository($id); 

if (!$repo || $repo['user_id'] != $_SESSION['user_id']) { 
    exit('Репозиторий не найден / доступ запрещён'); 
} 

$files = get_files_by_repo($id); 
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($repo['name']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
    <style>
        .drop-zone { border: 2px dashed var(--border); border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: 0.3s; background: rgba(255,255,255,0.02); margin-bottom: 20px; }
        .drop-zone:hover, .drop-zone.dragover { border-color: var(--accent); background: rgba(108, 92, 231, 0.1); }
        footer { padding: 30px 0; background: rgba(10, 12, 20, 0.95); border-top: 1px solid var(--border); text-align: center; }
        .footer-nav { display: flex; justify-content: center; gap: 25px; margin-bottom: 15px; }
        .footer-nav a { color: #fff; text-decoration: none; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
        .footer-nav a:hover { color: var(--accent); }
        .footer-copy { display: flex; align-items: center; justify-content: center; gap: 10px; color: var(--text-muted); font-size: 0.9rem; }
        .footer-icon { width: 18px; height: 18px; }
        .file-actions { display:flex; gap: 8px; margin-top:5px; }
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
            <a href="<?= BASE_URL ?>repo_edit.php?id=<?= $repo['id'] ?>">Настройки проекта</a>
            <a class="danger" href="<?= BASE_URL ?>logout.php">Выход</a>
        </nav>
    </div>
</header>

<main class="container">
    <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1><?= e($repo['name']) ?></h1>
                <p style="color: var(--text-muted);"><?= e($repo['description']) ?></p>
            </div>
            <a href="<?= BASE_URL ?>repo_zip.php?id=<?= $repo['id'] ?>" class="btn-primary">Скачать ZIP</a>
        </div>
    </div>

    <div class="grid-layout grid-two-cols">
        <div class="panel">
            <h3>Файлы проекта</h3>
            <div style="margin-top: 20px;">
                <?php if (empty($files)): ?>
                    <p style="color: var(--text-muted);">Репозиторий пуст.</p>
                <?php else: ?>
                    <?php foreach ($files as $f): ?>
                        <div class="list-item">
                            <a href="<?= BASE_URL ?>file_view.php?id=<?= $f['id'] ?>" style="color: #fff; text-decoration: none; font-weight: 600;">📄 <?= e($f['filename']) ?></a>
                            <div class="file-actions">
                                <a href="<?= BASE_URL ?>file_edit.php?id=<?= $f['id'] ?>" class="btn-ghost" style="padding: 5px 10px; font-size: 0.8rem;">Ред.</a>

                                <?php if (preg_match('/\.html$/i', $f['filename'])): ?>
                                    <a href="<?= BASE_URL ?>repo_preview.php?file_id=<?= $f['id'] ?>" class="btn-primary" target="_blank">Запустить</a>
                                <?php endif; ?>

                                <a href="<?= BASE_URL ?>file_delete.php?id=<?= $f['id'] ?>&repo_id=<?= $repo['id'] ?>" class="danger" style="padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Удалить файл?')">Удалить</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="panel">
            <h3>Новый файл</h3>
            <form method="post" action="<?= BASE_URL ?>/file_upload.php" style="margin-top: 15px;">
                <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
                
                <div class="drop-zone" id="drop-zone">
                    <p>Перетащите файл сюда или нажмите для выбора</p>
                    <input type="file" id="file-input" style="display: none;">
                </div>

                <label>Имя файла
                    <input name="filename" id="filename" required placeholder="index.html">
                </label>
                <label style="margin-top: 15px;">Содержимое
                    <textarea name="content" id="content" rows="8" placeholder="Код или текст..."></textarea>
                </label>
                <button class="btn-primary" style="width: 100%; margin-top: 20px;">Добавить в проект</button>
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
<script>
    VANTA.NET({ el: "body", mouseControls: true, touchControls: true, scale: 1, color: 0x6c5ce7, backgroundColor: 0x06070d });

    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const filenameInput = document.getElementById('filename');
    const contentTextarea = document.getElementById('content');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if(e.dataTransfer.files[0]) loadFile(e.dataTransfer.files[0]);
    });

    fileInput.addEventListener('change', () => { if(fileInput.files[0]) loadFile(fileInput.files[0]); });

    function loadFile(file) {
        filenameInput.value = file.name;
        const reader = new FileReader();
        reader.onload = () => contentTextarea.value = reader.result;
        reader.readAsText(file);
    }
</script>
</body>
</html>
