const htmlElement = document.documentElement;

export function initThemeToggle(buttonId) {
    const themeToggleBtn = document.getElementById(buttonId);
    
    if (!themeToggleBtn) return;

    const toggleTheme = () => {
        if (htmlElement.classList.toggle('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    };

    themeToggleBtn.addEventListener('click', toggleTheme);
}
