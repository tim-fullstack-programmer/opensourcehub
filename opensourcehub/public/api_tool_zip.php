<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/repos.php';
require_once __DIR__ . '/../src/tools_backend.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Неверный метод');
}

$repo_id = (int)($_POST['repo_id'] ?? 0);
$repo = get_repository($repo_id);

if (!$repo || $repo['user_id'] != $_SESSION['user_id']) {
    exit('Доступ запрещён');
}

$zip = create_repo_zip($repo_id);
if (!$zip) exit('Ошибка создания архива');

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="repo_' . $repo_id . '.zip"');
readfile($zip);

unlink($zip);
