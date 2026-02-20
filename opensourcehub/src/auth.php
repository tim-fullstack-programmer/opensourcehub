<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
    csrf_start();
}

function login_user(int $user_id) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
}

function logout_user() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
}

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect(BASE_URL . 'login.php');
    }
}

function current_user() {
    if (!is_logged_in()) return null;

    $pdo = getPDO();
    $stmt = $pdo->prepare('
        SELECT id, username, email, role, created_at
        FROM users
        WHERE id = ?
        LIMIT 1
    ');
    $stmt->execute([$_SESSION['user_id']]);

    return $stmt->fetch() ?: null;
}

function is_admin(): bool {
    if (!is_logged_in()) return false;

    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch();
    return $user && $user['role'] === 'admin';
}

function require_admin() {
    require_login();

    if (!is_admin()) {
        http_response_code(403);
        redirect(BASE_URL . '403.php');
    }
}
