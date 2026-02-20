<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/files.php';
require_once __DIR__ . '/../src/repos.php';

require_login();

$repo_id = (int)($_GET['repo'] ?? 0);
$filename = basename($_GET['file'] ?? '');

if (!$repo_id || !$filename) {
    http_response_code(400);
    exit;
}

$repo = get_repository($repo_id);

if (!$repo || $repo['user_id'] !== $_SESSION['user_id']) {
    http_response_code(403);
    exit;
}

$file = get_file_by_repo_and_name($repo_id, $filename);
if (!$file) {
    http_response_code(404);
    exit;
}

$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

$types = [
    'css' => 'text/css',
    'js'  => 'application/javascript',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg'=> 'image/jpeg',
    'svg' => 'image/svg+xml',
];

header('Content-Type: ' . ($types[$ext] ?? 'text/plain'));
echo $file['content'];
exit;
