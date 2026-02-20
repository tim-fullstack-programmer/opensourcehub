document.querySelectorAll(".btn-primary, .btn-outline, .btn, button").forEach(btn => {
    btn.addEventListener("mousemove", e => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        btn.style.transform = `translate(${x * 0.08}px, ${y * 0.08}px)`;
    });

    btn.addEventListener("mouseleave", () => {
        btn.style.transform = `translate(0, 0)`;
    });
});

document.querySelectorAll(".btn-primary").forEach(btn => {
    btn.addEventListener("mouseenter", () => {
        btn.style.boxShadow = "0 0 25px rgba(108,92,231,0.7)";
    });
    btn.addEventListener("mouseleave", () => {
        btn.style.boxShadow = "none";
    });
});

function generatePassword() {
    const length = parseInt(document.getElementById("pw-length").value);
    const up = document.getElementById("pw-upper").checked;
    const low = document.getElementById("pw-lower").checked;
    const dig = document.getElementById("pw-digits").checked;
    const sym = document.getElementById("pw-symbols").checked;

    let chars = "";
    if (up) chars += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if (low) chars += "abcdefghijklmnopqrstuvwxyz";
    if (dig) chars += "0123456789";
    if (sym) chars += "!@#$%^&*()_+-=[]{};:,.<>/?";

    if (!chars.length) return "";

    let pass = "";
    for (let i = 0; i < length; i++) {
        pass += chars[Math.floor(Math.random() * chars.length)];
    }

    return pass;
}

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("pw-gen");
    if (btn) {
        btn.addEventListener("click", () => {
            const out = document.getElementById("pw-out");
            const pass = generatePassword();
            out.textContent = pass;
            out.style.animation = "fadeIn 0.4s";
        });
    }
});

function formatHTML(input) {
    return input
        .replace(/></g, ">\n<")
        .split("\n")
        .map(line => "    " + line)
        .join("\n");
}

function formatCSS(input) {
    return input
        .replace(/\{/g, "{\n    ")
        .replace(/\}/g, "\n}\n")
        .replace(/;/g, ";\n    ");
}

function formatJS(input) {
    return input
        .replace(/;/g, ";\n")
        .replace(/\{/g, "{\n")
        .replace(/\}/g, "\n}");
}

document.addEventListener("DOMContentLoaded", () => {
    const run = document.getElementById("fmt-run");
    if (!run) return;

    run.addEventListener("click", () => {
        const type = document.getElementById("fmt-type").value;
        const input = document.getElementById("fmt-input").value;
        const out = document.getElementById("fmt-out");

        let result = input;

        if (type === "html") result = formatHTML(input);
        if (type === "css") result = formatCSS(input);
        if (type === "js") result = formatJS(input);

        out.textContent = result;
        out.style.animation = "fadeIn 0.4s";
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const zipBtn = document.getElementById("zip-btn");
    if (zipBtn) {
        zipBtn.addEventListener("click", e => {
            e.preventDefault();
            const rid = zipBtn.dataset.repo;
            const f = document.createElement("form");
            f.method = "POST";
            f.action = BASE_URL + "/api_tool_zip.php";

            const i = document.createElement("input");
            i.type = "hidden";
            i.name = "repo_id";
            i.value = rid;

            f.appendChild(i);
            document.body.appendChild(f);
            f.submit();
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const themeToggle = document.getElementById("theme-toggle");
    if (!themeToggle) return;

    themeToggle.addEventListener("click", () => {
        const cur = document.documentElement.getAttribute("data-theme") || "dark";
        const next = cur === "dark" ? "light" : "dark";
        document.documentElement.setAttribute("data-theme", next);
        localStorage.setItem("theme", next);
    });

    const saved = localStorage.getItem("theme");
    if (saved) {
        document.documentElement.setAttribute("data-theme", saved);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const bg = document.createElement("canvas");
    bg.id = "bg-anim";
    bg.style.position = "fixed";
    bg.style.top = 0;
    bg.style.left = 0;
    bg.style.zIndex = "-2";
    bg.style.width = "100%";
    bg.style.height = "100%";
    document.body.appendChild(bg);

    const c = bg.getContext("2d");
    const stars = [];

    for (let i = 0; i < 140; i++) {
        stars.push({
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            s: Math.random() * 2 + 0.5,
            v: Math.random() * 0.3 + 0.05
        });
    }

    function loop() {
        bg.width = window.innerWidth;
        bg.height = window.innerHeight;
        c.clearRect(0, 0, bg.width, bg.height);

        c.fillStyle = "rgba(255,255,255,0.8)";
        stars.forEach(st => {
            c.beginPath();
            c.arc(st.x, st.y, st.s, 0, Math.PI * 2);
            c.fill();
            st.y += st.v;
            if (st.y > window.innerHeight) st.y = 0;
        });

        requestAnimationFrame(loop);
    }

    loop();
});