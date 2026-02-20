<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/security.php';

function add_file_to_repo(int $repo_id, string $filename, string $content): int {
    $pdo = getPDO();
    $safe = sanitize_filename($filename);
    ensure_repo_storage();
    $repo_path = REPO_STORAGE . '/' . $repo_id;
    if (!is_dir($repo_path)) mkdir($repo_path, 0775, true);

    $fullpath = $repo_path . '/' . $safe;
    file_put_contents($fullpath, $content);

    $stmt = $pdo->prepare('INSERT INTO repo_files (repo_id, filename, path, content) VALUES (?, ?, ?, ?)');
    $stmt->execute([$repo_id, $safe, $safe, $content]);
    return (int)$pdo->lastInsertId();
}

function get_files_in_repo(int $repo_id): array {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM repo_files WHERE repo_id = ? ORDER BY created_at DESC');
    $stmt->execute([$repo_id]);
    return $stmt->fetchAll();
}

function get_file(int $file_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM repo_files WHERE id = ? LIMIT 1');
    $stmt->execute([$file_id]);
    return $stmt->fetch();
}

function update_file_content(int $file_id, string $content): bool {
    $pdo = getPDO();
    $stmt = $pdo->prepare('UPDATE repo_files SET content = ? WHERE id = ?');
    $ok = $stmt->execute([$content, $file_id]);

    $f = get_file($file_id);
    if ($f) {
        $repo_path = REPO_STORAGE . '/' . $f['repo_id'];
        $path = $repo_path . '/' . basename($f['path']);
        file_put_contents($path, $content);
    }
    return $ok;
}

function delete_file(int $file_id): bool {
    $pdo = getPDO();
    $f = get_file($file_id);
    if (!$f) return false;
    $repo_id = (int)$f['repo_id'];
    $path = REPO_STORAGE . '/' . $repo_id . '/' . basename($f['path']);
    if (file_exists($path)) @unlink($path);
    $stmt = $pdo->prepare('DELETE FROM repo_files WHERE id = ?');
    return $stmt->execute([$file_id]);
}
function get_files_by_repo(int $repo_id): array {
    return get_files_in_repo($repo_id);
}

function get_file_by_repo_and_name(int $repo_id, string $filename): ?array {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM files WHERE repo_id = ? AND filename = ? LIMIT 1");
    $stmt->execute([$repo_id, $filename]);
    return $stmt->fetch() ?: null;
}

