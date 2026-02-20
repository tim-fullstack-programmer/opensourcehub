<?php
require_once __DIR__ . '/../src/config_path.php';
require_once __DIR__ . '/../src/auth.php';
require_login();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Генератор паролей</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tools-app { max-width: 600px; margin: 40px auto; }
        .result-box { 
            background: rgba(0, 0, 0, 0.3); 
            padding: 20px; 
            border-radius: 12px; 
            margin: 20px 0; 
            border: 1px solid var(--border);
            text-align: center;
        }
        #passwordOutput { 
            font-family: 'Fira Code', monospace;
            font-size: 1.5rem;
            color: var(--accent);
            word-break: break-all;
        }
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        .option-item { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .option-item input { width: auto; margin: 0; }
        
        input[type=range] {
            accent-color: var(--accent);
        }
        
        .option-item input[type="checkbox"]:checked {
            accent-color: var(--accent);
        }
        
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
            <a href="<?= BASE_URL ?>tools.php">Инструменты</a>
            <a class="danger" href="<?= BASE_URL ?>logout.php">Выход</a>
        </nav>
    </div>
</header>

<main class="container tools-app">
    <div class="panel slide-in">
        <h1><i class="fas fa-key"></i> Генератор паролей</h1>
        
        <div class="result-box">
            <div id="passwordOutput">********</div>
        </div>

        <div class="form-group">
            <label>Длина: <span id="lengthVal">16</span></label>
            <input type="range" id="pwdLength" min="4" max="50" value="16" style="padding:0">
        </div>

        <div class="options-grid">
            <label class="option-item"><input type="checkbox" id="lower" checked> a-z</label>
            <label class="option-item"><input type="checkbox" id="upper" checked> A-Z</label>
            <label class="option-item"><input type="checkbox" id="digits" checked> 0-9</label>
            <label class="option-item"><input type="checkbox" id="symbols"> !@#$%</label>
        </div>

        <div style="display: flex; gap: 10px;">
            <button class="btn-primary" style="flex: 2;" onclick="generatePassword()">Сгенерировать</button>
            <button class="btn-ghost" style="flex: 1;" onclick="copyPassword()">Копировать</button>
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
    VANTA.NET({ el: "body", mouseControls: true, touchControls: true, scale: 1, color: 0x6c5ce7, backgroundColor: 0x06070d });

    const pwdLength = document.getElementById('pwdLength');
    const lengthVal = document.getElementById('lengthVal');
    pwdLength.oninput = () => lengthVal.textContent = pwdLength.value;

    function generatePassword(){
        let chars = '';
        if(lower.checked) chars += 'abcdefghijklmnopqrstuvwxyz';
        if(upper.checked) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if(digits.checked) chars += '0123456789';
        if(symbols.checked) chars += '!@#$%^&*()_+-=[]{}<>?';
        if(!chars) return alert('Выберите настройки!');
        
        let password = '';
        for(let i=0; i<pwdLength.value; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('passwordOutput').textContent = password;
    }

    function copyPassword(){
        const txt = document.getElementById('passwordOutput').textContent;
        if(txt === '********') return;
        navigator.clipboard.writeText(txt);
        alert('Скопировано!');
    }
</script>
</body>
</html>