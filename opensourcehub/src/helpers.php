<?php

require_once __DIR__ . '/config.php';

function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url) {
    header('Location: ' . $url);
    exit;
}

function ensure_repo_storage() {
    if (!is_dir(REPO_STORAGE)) {
        if (!mkdir(REPO_STORAGE, 0775, true) && !is_dir(REPO_STORAGE)) {
            throw new RuntimeException('Unable to create repo storage dir: ' . REPO_STORAGE);
        }
    }
}

function slugify(string $text): string {
    $text = strip_tags($text);
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text === '' ? 'repo' : $text;
}
