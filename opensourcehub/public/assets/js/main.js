document.addEventListener('DOMContentLoaded', function () {
    if (typeof particlesJS !== 'undefined') {
        particlesJS('particles-js', {
            particles: {
                number: { value: 70, density: { enable: true, value_area: 800 } },
                color: { value: ["#6c5ce7", "#00cec9", "#a29bfe"] },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: "#6c5ce7", opacity: 0.2, width: 1 },
                move: { enable: true, speed: 2, direction: "none", random: true, straight: false, out_mode: "out" }
            },
            interactivity: {
                detect_on: "canvas",
                events: { onhover: { enable: true, mode: "grab" }, onclick: { enable: true, mode: "push" }, resize: true },
                modes: { grab: { distance: 140, line_linked: { opacity: 0.8 } }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });
    }
    const slideInElements = document.querySelectorAll('.slide-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                document.documentElement.classList.add('is-animating');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    slideInElements.forEach(el => {
        observer.observe(el);
    });
    function applyTheme(theme) {
        if (theme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    }
    window.setTheme = function(theme) {
        localStorage.setItem('codehub_theme', theme);
        applyTheme(theme);
    }
    
    const savedTheme = localStorage.getItem('codehub_theme') || 'dark';
    applyTheme(savedTheme);

    const repoSearchInput = document.getElementById('repoSearch');
    if (repoSearchInput) {
        repoSearchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const repoCards = document.querySelectorAll('.repo-card');
            repoCards.forEach(card => {
                const repoName = card.querySelector('.repo-card-name').textContent.toLowerCase();
                if (repoName.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});