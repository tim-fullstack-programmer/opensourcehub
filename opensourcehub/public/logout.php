<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';

logout_user();
header('Location: ' . BASE_URL . 'login.php');
exit;
