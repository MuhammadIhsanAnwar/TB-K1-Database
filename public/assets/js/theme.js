(function () {
    function getSavedTheme() {
        return localStorage.getItem('darkMode') === 'true';
    }

    function setTheme(isDark) {
        document.documentElement.classList.toggle('dark', isDark);
        if (document.body) {
            document.body.classList.toggle('dark', isDark);
        }
        localStorage.setItem('darkMode', isDark ? 'true' : 'false');
        updateThemeIcons(isDark);
    }

    function updateThemeIcons(isDark) {
        document.querySelectorAll('#darkModeToggle i').forEach((icon) => {
            icon.classList.toggle('fa-moon', !isDark);
            icon.classList.toggle('fa-sun', isDark);
        });
    }

    function bindThemeToggle() {
        document.querySelectorAll('#darkModeToggle').forEach((button) => {
            if (button.dataset.boundTheme === 'true') {
                return;
            }

            button.dataset.boundTheme = 'true';
            button.addEventListener('click', () => {
                const isDark = !document.documentElement.classList.contains('dark');
                setTheme(isDark);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTheme(getSavedTheme());
        bindThemeToggle();
    });
})();
