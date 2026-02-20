<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function create_repository(int $user_id, string $name, string $description = '', int $is_private = 0): int {
    $pdo = getPDO();
    $slug = slugify($name);
    $stmt = $pdo->prepare('INSERT INTO repositories (user_id, name, slug, description, is_private) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$user_id, $name, $slug, $description, $is_private]);
    $repo_id = (int)$pdo->lastInsertId();

    ensure_repo_storage();
    $path = REPO_STORAGE . '/' . $repo_id;
    if (!is_dir($path)) mkdir($path, 0775, true);
    return $repo_id;
}

function get_repositories_by_user(int $user_id): array {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM repositories WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_repository(int $repo_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM repositories WHERE id = ? LIMIT 1');
    $stmt->execute([$repo_id]);
    return $stmt->fetch();
}

function update_repository(int $repo_id, string $name, string $description, int $is_private = 0): bool {
    $pdo = getPDO();
    $slug = slugify($name);
    $stmt = $pdo->prepare('UPDATE repositories SET name = ?, slug = ?, description = ?, is_private = ? WHERE id = ?');
    return $stmt->execute([$name, $slug, $description, $is_private, $repo_id]);
}

function delete_repo(int $repo_id): bool {
    $pdo = getPDO();
    $stmt = $pdo->prepare('DELETE FROM repositories WHERE id = ?');
    $stmt->execute([$repo_id]);
    $path = REPO_STORAGE . '/' . $repo_id;
    if (is_dir($path)) {
        $it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) rmdir($file->getRealPath()); else unlink($file->getRealPath());
        }
        rmdir($path);
    }
    return true;
}
