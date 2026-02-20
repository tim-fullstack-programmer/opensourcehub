<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/users.php';
require_once __DIR__ . '/../src/repos.php';

require_login();

$user = current_user();
delete_user_and_repos($user['id']);

logout_user();

header('Location: ' . BASE_URL . 'register.php');
exit;
