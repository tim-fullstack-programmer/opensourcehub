<?php
function safe_post(string $key, $default = null) {
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

function safe_get(string $key, $default = null) {
    return isset($_GET[$key]) ? trim((string)$_GET[$key]) : $default;
}

function csrf_start() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(16));
    }
}

function csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return $_SESSION['_csrf_token'] ?? '';
}

function csrf_check(string $token): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
}

function sanitize_filename(string $name): string {
    $name = basename($name);
    $name = preg_replace('/[\\s]+/u', '_', $name);
    $name = preg_replace('/[^A-Za-z0-9._-]/u', '', $name);
    return $name ?: 'file';
}