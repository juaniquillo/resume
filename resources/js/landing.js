const themeToggleBtn = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const htmlElement = document.documentElement; // Target html element for dark class

// Define SVGs for moon and sun icons
const moonIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"></path>`;
const sunIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"></path>`;

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
