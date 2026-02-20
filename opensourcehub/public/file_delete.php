<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/files.php';
require_once __DIR__ . '/../src/repos.php';

require_login();

$id = (int)($_GET['id'] ?? 0);
$f = get_file($id);

if ($f) {
    $repo = get_repository($f['repo_id']);

    if ($repo['user_id'] == $_SESSION['user_id']) {
        delete_file($id);
        header('Location: ' . BASE_URL . 'repo.php?id=' . $repo['id']);
        exit;
    }
}

header('Location: ' . BASE_URL . 'dashboard.php');
exit;
