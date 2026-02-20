<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/users.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();
$user = current_user();

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $new_password = !empty($password) ? $password : null;

    try {
        if (update_user_profile($user['id'], $username, $email, $new_password)) {
            
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;

            header('Location: ' . BASE_URL . 'account_edit.php?success=1');
            exit;
        } else {
            $error = "Не удалось сохранить данные. Возможно, логин или email уже заняты.";
        }
    } catch (Exception $e) {
        $error = "Ошибка базы данных: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/icons/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }

        .panel { margin-bottom: 20px; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .alert-success { background: rgba(0, 184, 148, 0.15); color: #00b894; border: 1px solid #00b894; }
        .alert-danger { background: rgba(255, 71, 87, 0.15); color: #ff4757; border: 1px solid #ff4757; }

        footer {
            padding: 30px 0;
            background: rgba(10, 12, 20, 0.95);
            border-top: 1px solid var(--border);
            text-align: center;
        }
        .footer-nav {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 15px;
        }
        .footer-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .footer-nav a:hover { color: var(--accent); }
        .footer-copy {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .footer-icon { width: 18px; height: 18px; }
    </style>
</head>
<body>

<header class="topbar">
    <div class="container topbar-inner">
        <div class="brand">
            <a href="<?= BASE_URL ?>dashboard.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" alt="Logo" style="width: 28px; height: 28px; object-fit: contain;">
                <span>OpenSourceHub</span>
            </a>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>dashboard.php">Назад</a>
            <a href="<?= BASE_URL ?>admin_login.php">Админ панель</a>
            <a class="danger" href="<?= BASE_URL ?>logout.php">Выход</a>
        </nav>
    </div>
</header>

<main class="container">
    <div style="max-width: 600px; margin: 40px auto;">
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Изменения успешно сохранены!</div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="panel slide-in">
            <h2>Настройки аккаунта</h2>
            <form method="post" style="margin-top:20px;">
                <label>Логин 
                    <input type="text" name="username" value="<?= e($user['username']) ?>" required>
                </label><br>
                <label>Email 
                    <input type="email" name="email" value="<?= e($user['email']) ?>" required>
                </label><br>
                <label>Новый пароль 
                    <input type="password" name="password" placeholder="Оставьте пустым, если не меняете">
                </label>
                
                <button type="submit" class="btn-primary" style="margin-top:20px; width:100%;">Обновить профиль</button>
            </form>
        </div>

        <div class="panel" style="border-color: rgba(255, 71, 87, 0.2);">
            <h3>Удаление аккаунта</h3>
            <p style="color:var(--text-muted); font-size:0.9rem; margin: 10px 0;">Все данные будут удалены навсегда.</p>
            <a href="account_delete.php" class="danger" style="display: block; text-align: center;" onclick="return confirm('Удалить аккаунт?')">Удалить аккаунт</a>
        </div>
    </div>
</main>

<footer>
    <div class="footer-nav">
        <a href="<?= BASE_URL ?>about.php"><i class="fas fa-circle-info"></i> О нас</a>
        <a href="<?= BASE_URL ?>security.php"><i class="fas fa-shield-halved"></i> Безопасность</a>
    </div>
    <div class="footer-copy">
        <img src="<?= BASE_URL ?>/assets/icons/ico_white.ico" class="footer-icon" alt="icon">
        OpenSourceHub © 2026
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
<script>
    VANTA.NET({
        el: "body",
        mouseControls: true,
        touchControls: true,
        scale: 1.0,
        color: 0x6c5ce7,
        backgroundColor: 0x06070d
    });
</script>
</body>
</html>