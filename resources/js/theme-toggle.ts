const htmlElement = document.documentElement;

export function initThemeToggle(buttonId: string): void {
    const themeToggleBtn = document.getElementById(buttonId);

    if (!themeToggleBtn) {
        return;
    }

    const toggleTheme = (): void => {
        if (htmlElement.classList.toggle('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    };

    themeToggleBtn.addEventListener('click', toggleTheme);
}
