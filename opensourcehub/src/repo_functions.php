<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function create_repo($user_id, $name, $description = '', $is_private = 0) {
    $pdo = getPDO();
    $slug = slugify($name);
    $stmt = $pdo->prepare('INSERT INTO repositories (user_id, name, slug, description, is_private) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$user_id, $name, $slug, $description, $is_private]);
    $repo_id = $pdo->lastInsertId();

    $path = REPO_STORAGE . '/' . $repo_id;
    if (!is_dir($path)) mkdir($path, 0777, true);
    return $repo_id;
}

function get_repos_by_user($user_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM repositories WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_repo($repo_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM repositories WHERE id = ?');
    $stmt->execute([$repo_id]);
    return $stmt->fetch();
}

function delete_repo($repo_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('DELETE FROM repositories WHERE id = ?');
    $stmt->execute([$repo_id]);

    $path = REPO_STORAGE . '/' . $repo_id;
    if (is_dir($path)) {
        $it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()) rmdir($file->getRealPath()); else unlink($file->getRealPath());
        }
        rmdir($path);
    }
    return true;
}

function save_file_to_repo($repo_id, $filename, $content) {
    $pdo = getPDO();
    $path = REPO_STORAGE . '/' . $repo_id;
    if (!is_dir($path)) mkdir($path, 0777, true);
    $safe = basename($filename);
    $fullpath = $path . '/' . $safe;
    file_put_contents($fullpath, $content);

    $stmt = $pdo->prepare('INSERT INTO repo_files (repo_id, filename, path, content) VALUES (?, ?, ?, ?)');
    $stmt->execute([$repo_id, $safe, $safe, $content]);
    return $pdo->lastInsertId();
}
