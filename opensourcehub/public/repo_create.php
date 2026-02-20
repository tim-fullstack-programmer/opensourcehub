<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/repos.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name !== '') {
        $repo_id = create_repository($_SESSION['user_id'], $name, $description, 0);
        header('Location: ' . BASE_URL . 'repo.php?id=' . $repo_id);
        exit;
    }
}

header('Location: ' . BASE_URL . 'dashboard.php');
exit;
