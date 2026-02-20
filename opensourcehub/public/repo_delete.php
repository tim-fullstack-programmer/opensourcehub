<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/repos.php';

require_login();

$id = (int)($_GET['id'] ?? 0);
$repo = get_repository($id);

if ($repo && $repo['user_id'] == $_SESSION['user_id']) {
    delete_repo($id);
}

header('Location: ' . BASE_URL . 'dashboard.php');
exit;
