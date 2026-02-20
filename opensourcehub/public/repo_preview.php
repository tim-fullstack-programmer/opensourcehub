<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/files.php';
require_once __DIR__ . '/../src/repos.php';

require_login();

$file_id = (int)($_GET['file_id'] ?? 0);
$file = get_file($file_id);

if (!$file) {
    http_response_code(404);
    exit('Файл не найден');
}

$repo = get_repository($file['repo_id']);

if (!$repo || $repo['user_id'] !== $_SESSION['user_id']) {
    http_response_code(403);
    exit('Доступ запрещён');
}

/**
 * Подмена путей:
 * href="style.css" → /repo_assets.php?repo=ID&file=style.css
 * src="app.js"     → /repo_assets.php?repo=ID&file=app.js
 */
$content = $file['content'];

$content = preg_replace_callback(
    '/(href|src)="([^":]+)"/i',
    function ($m) use ($repo) {
        return $m[1] . '="' . BASE_URL . 'repo_assets.php?repo=' . $repo['id'] . '&file=' . urlencode($m[2]) . '"';
    },
    $content
);

header('Content-Type: text/html; charset=utf-8');
echo $content;
exit;
