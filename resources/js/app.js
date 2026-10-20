import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Lógica del Modo Oscuro
document.addEventListener('DOMContentLoaded', () => {
    // 1. Aplicar tema guardado o preferencia del sistema al cargar
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // 2. Escuchar el botón de toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            // íconos de Lucide, los recargamos
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }
});