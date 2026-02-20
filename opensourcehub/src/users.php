<?php
require_once __DIR__ . '/db.php';

function create_user(string $username, string $email, string $password): int {
    $pdo = getPDO();
    $check = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
    $check->execute([$username, $email]);

    if ($check->fetch()) {
        throw new Exception('Пользователь с таким именем или email уже существует.');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hash]);
        return (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            throw new Exception('Пользователь с такими данными уже существует.');
        }
        throw $e;
    }
}

function find_user_by_email_or_username(string $value) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE (email = ? OR username = ?) AND deleted = 0 LIMIT 1');
    $stmt->execute([$value, $value]);
    return $stmt->fetch();
}

function find_user_by_id(int $id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, username, email, created_at FROM users WHERE id = ? AND deleted = 0 LIMIT 1');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function update_user_profile(int $id, string $username, string $email, ?string $password = null) {
    $pdo = getPDO();

    if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?');
        return $stmt->execute([$username, $email, $hash, $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
        return $stmt->execute([$username, $email, $id]);
    }
}

function delete_user_and_repos(int $user_id) {
    $pdo = getPDO();

    $stmt = $pdo->prepare('SELECT id FROM repositories WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $repos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($repos as $rid) {
        require_once __DIR__ . '/repos.php';
        delete_repo((int)$rid);
    }

    $stmt2 = $pdo->prepare('DELETE FROM users WHERE id = ?');
    return $stmt2->execute([$user_id]);
}