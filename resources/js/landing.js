const themeToggleBtn = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const htmlElement = document.documentElement; // Target html element for dark class

// Define SVGs for moon and sun icons
const moonIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 12.011a9 9 0 01-8.386 8.386L12 21l-3.646-3.646a9.003 9.003 0 018.386-8.386zm0 0l-.001-.001h-.001zm-4.094-3.613a6 6 0 00-7.482 0M9.5 16a9 9 0 0111.386-8.386L12 3"></path>`;
const sunIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1M4 12H3m18 0h-1M4 12a8 8 0 0116 0v0zM12 4v1m-7 7H3m18 0h-1M4 12a8 8 0 0116 0v0zM12 4v1m-7 7H3m18 0h-1M4 12a8 8 0 0116 0v0z"></path>`;

// Function to set theme based on localStorage or system preference
const setInitialTheme = () => {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark') {
        htmlElement.classList.add('dark');
    } else if (savedTheme === 'light') {
        htmlElement.classList.remove('dark');
    } else if (prefersDark) {
        htmlElement.classList.add('dark');
    }
    updateIcon();
};

// Function to toggle theme
const toggleTheme = () => {
    if (htmlElement.classList.toggle('dark')) {
        // Switched to dark mode
        localStorage.setItem('theme', 'dark');
    } else {
        // Switched to light mode
        localStorage.setItem('theme', 'light');
    }
    updateIcon();
};

// Function to update icon based on current theme
const updateIcon = () => {
    if (htmlElement.classList.contains('dark')) {
        themeIcon.innerHTML = moonIcon;
    } else {
        themeIcon.innerHTML = sunIcon;
    }
};

// Add event listener to the button
themeToggleBtn.addEventListener('click', toggleTheme);

// Set initial theme on page load
setInitialTheme();
