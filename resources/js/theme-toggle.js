const htmlElement = document.documentElement;

// Initialize theme immediately to prevent FOUC
if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    htmlElement.classList.add('dark');
} else {
    htmlElement.classList.remove('dark');
}

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
