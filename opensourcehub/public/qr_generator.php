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
    <title>QR Code Генератор</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    
    <style>
        .tools-app { max-width: 600px; margin: 40px auto; }
        .qr-result { 
            text-align: center; 
            background: rgba(255, 255, 255, 0.05); 
            padding: 20px; 
            border-radius: 12px; 
            margin: 20px 0;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        canvas { 
            max-width: 100%; 
            height: auto !important; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .color-input-wrapper {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        input[type="color"] {
            height: 45px;
            padding: 2px;
            cursor: pointer;
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
            <a href="<?= BASE_URL ?>account_edit.php">Настройки</a>
            <a class="danger" href="<?= BASE_URL ?>logout.php">Выход</a>
        </nav>
    </div>
</header>

<main class="container tools-app">
    <div class="panel slide-in">
        <h1><i class="fas fa-qrcode"></i> QR Code Generator</h1>
        <p style="color: var(--text-muted); margin-bottom: 25px;">Создавайте QR-коды мгновенно прямо в браузере.</p>

        <div class="form-group">
            <label>Текст или URL</label>
            <textarea id="qrDataInput" rows="3" placeholder="Введите ссылку или текст..."></textarea>
        </div>

        <div class="settings-grid">
            <div class="color-input-wrapper">
                <label>Цвет кода</label>
                <input type="color" id="colorDark" value="#000000">
            </div>
            <div class="color-input-wrapper">
                <label>Цвет фона</label>
                <input type="color" id="colorLight" value="#ffffff">
            </div>
        </div>

        <button id="generateBtn" class="btn-primary" style="width: 100%;" disabled>
            <i class="fas fa-sync-alt"></i> Сгенерировать
        </button>

        <div class="qr-result">
            <canvas id="qrCanvas" style="display:none;"></canvas>
            <p id="qrMessage">Начните вводить текст выше...</p>
        </div>

        <button id="downloadBtn" class="btn-ghost" style="width: 100%;" disabled>
            <i class="fas fa-download"></i> Скачать PNG
        </button>
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

    const dom = {
        qrDataInput: document.getElementById('qrDataInput'),
        colorDark: document.getElementById('colorDark'),
        colorLight: document.getElementById('colorLight'),
        generateBtn: document.getElementById('generateBtn'),
        qrCanvas: document.getElementById('qrCanvas'),
        qrMessage: document.getElementById('qrMessage'),
        downloadBtn: document.getElementById('downloadBtn'),
    };

    function generateQrCode() {
        const data = dom.qrDataInput.value.trim();
        const colorDark = dom.colorDark.value;
        const colorLight = dom.colorLight.value;

        if (!data) {
            dom.qrMessage.style.display = 'block';
            dom.qrMessage.textContent = 'Введите текст или URL.';
            dom.generateBtn.disabled = true;
            dom.qrCanvas.style.display = 'none';
            dom.downloadBtn.disabled = true;
            return;
        }

        dom.qrMessage.style.display = 'none';
        dom.qrCanvas.style.display = 'block';
        dom.generateBtn.disabled = false;

        QRCode.toCanvas(dom.qrCanvas, data, {
            width: 256,
            margin: 2,
            color: {
                dark: colorDark,
                light: colorLight
            }
        }, function (error) {
            if (error) {
                dom.qrMessage.style.display = 'block';
                dom.qrMessage.textContent = 'Ошибка: ' + error.message;
                dom.downloadBtn.disabled = true;
            } else {
                dom.downloadBtn.disabled = false;
            }
        });
    }

    function downloadQrCode() {
        if (dom.downloadBtn.disabled) return;
        const link = document.createElement('a');
        link.download = 'qrcode.png';
        link.href = dom.qrCanvas.toDataURL("image/png");
        link.click();
    }

    dom.qrDataInput.addEventListener('input', generateQrCode);
    dom.colorDark.addEventListener('input', generateQrCode);
    dom.colorLight.addEventListener('input', generateQrCode);
    dom.generateBtn.addEventListener('click', generateQrCode);
    dom.downloadBtn.addEventListener('click', downloadQrCode);

    dom.qrDataInput.value = 'https://opensourcehub.ru';
    generateQrCode();
</script>
</body>
</html>