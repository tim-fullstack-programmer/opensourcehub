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
    <title>ZIP Архиватор</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="./assets/icons/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        .tools-app { max-width: 800px; margin: 40px auto; }
        .tab-nav { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab-btn { flex: 1; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: #fff; cursor: pointer; border-radius: 8px; transition: 0.3s; }
        .tab-btn.active { background: var(--accent); border-color: var(--accent); }
        
        .drop-zone { 
            border: 2px dashed var(--border); 
            padding: 40px; 
            text-align: center; 
            border-radius: 12px; 
            background: rgba(255,255,255,0.02);
            cursor: pointer;
            transition: 0.3s;
        }
        .drop-zone:hover { border-color: var(--accent); background: rgba(108, 92, 231, 0.1); }
        
        .file-list { margin-top: 20px; max-height: 250px; overflow-y: auto; background: rgba(0,0,0,0.2); border-radius: 8px; }
        .file-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .file-item:last-child { border-bottom: none; }
        
        .hidden { display: none; }
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
        <h1><i class="fas fa-file-zipper"></i> ZIP Архиватор</h1>
        
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('create')">Архивировать</button>
            <button class="tab-btn" onclick="switchTab('extract')">Разархивировать</button>
        </div>

        <div id="section-create">
            <div class="drop-zone" onclick="document.getElementById('inputCreate').click()">
                <i class="fas fa-plus-circle" style="font-size: 2.5rem; color: var(--accent);"></i>
                <p>Выберите файлы для упаковки в ZIP</p>
                <input type="file" id="inputCreate" multiple style="display:none">
            </div>
            <div class="file-list" id="listCreate"></div>
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <input type="text" id="zipName" placeholder="archive.zip" style="margin:0">
                <button class="btn-primary" onclick="createZip()">Скачать ZIP</button>
            </div>
        </div>

        <div id="section-extract" class="hidden">
            <div class="drop-zone" onclick="document.getElementById('inputExtract').click()">
                <i class="fas fa-box-open" style="font-size: 2.5rem; color: #ff9f43;"></i>
                <p>Выберите ZIP-архив для распаковки</p>
                <input type="file" id="inputExtract" accept=".zip" style="display:none">
            </div>
            <div class="file-list" id="listExtract"></div>
            <button class="btn-primary" id="downloadAllBtn" style="width: 100%; margin-top: 20px;" disabled onclick="downloadAllExtracted()">Скачать все файлы (ZIP)</button>
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

    let createFiles = new Map();
    let extractedFiles = [];

    function switchTab(tab) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById('section-create').classList.toggle('hidden', tab !== 'create');
        document.getElementById('section-extract').classList.toggle('hidden', tab !== 'extract');
    }

    document.getElementById('inputCreate').onchange = (e) => {
        for(let f of e.target.files) {
            createFiles.set(f.name, f);
            renderList('listCreate', createFiles);
        }
    };

    async function createZip() {
        if(createFiles.size === 0) return alert('Добавьте файлы!');
        const zip = new JSZip();
        createFiles.forEach((file, name) => zip.file(name, file));
        const blob = await zip.generateAsync({type: "blob"});
        saveAs(blob, document.getElementById('zipName').value || "archive.zip");
    }

    document.getElementById('inputExtract').onchange = async (e) => {
        const file = e.target.files[0];
        if(!file) return;

        const list = document.getElementById('listExtract');
        list.innerHTML = '<p style="padding:20px; text-align:center;">Чтение архива...</p>';
        extractedFiles = [];

        try {
            const zip = await JSZip.loadAsync(file);
            list.innerHTML = '';
            
            for (const [filename, fileData] of Object.entries(zip.files)) {
                if (fileData.dir) continue;
                
                extractedFiles.push({ name: filename, data: fileData });
                
                const item = document.createElement('div');
                item.className = 'file-item';
                item.innerHTML = `
                    <span>📄 ${filename}</span>
                    <button class="btn-ghost" style="padding: 5px 10px; font-size: 0.8rem;" onclick="downloadSingle('${filename}')">Скачать</button>
                `;
                list.appendChild(item);
            }
            document.getElementById('downloadAllBtn').disabled = false;
        } catch (err) {
            alert('Ошибка при чтении архива: ' + err.message);
        }
    };

    async function downloadSingle(name) {
        const fileObj = extractedFiles.find(f => f.name === name);
        const content = await fileObj.data.async("blob");
        saveAs(content, name.split('/').pop());
    }

    function renderList(id, map) {
        const el = document.getElementById(id);
        el.innerHTML = '';
        map.forEach((f, name) => {
            const item = document.createElement('div');
            item.className = 'file-item';
            item.innerHTML = `<span>📄 ${name}</span><i class="fas fa-check" style="color:var(--accent)"></i>`;
            el.appendChild(item);
        });
    }
</script>
</body>
</html>