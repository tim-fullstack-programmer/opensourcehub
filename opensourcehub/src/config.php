<?php
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'opensourcehub');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', 'root');
if (!defined('BASE_DIR')) define('BASE_DIR', realpath(__DIR__ . '/..'));
if (!defined('REPO_STORAGE')) define('REPO_STORAGE', BASE_DIR . '/data/repos');
if (!defined('UPLOADS_DIR')) define('UPLOADS_DIR', BASE_DIR . '/public/uploads');
if (!defined('APP_NAME')) define('APP_NAME', 'Local Github');
if (!defined('SESSION_NAME')) define('SESSION_NAME', 'localgithub_session');
if (!is_dir(REPO_STORAGE)) @mkdir(REPO_STORAGE, 0775, true);
if (!is_dir(UPLOADS_DIR)) @mkdir(UPLOADS_DIR, 0775, true);
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}