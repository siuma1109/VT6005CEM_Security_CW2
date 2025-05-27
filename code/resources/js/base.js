// Check for saved dark mode preference
function initDarkMode() {
    try {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            document.getElementById('moon-icon').classList.remove('hidden');
            document.getElementById('sun-icon').classList.add('hidden');
        } else {
            document.documentElement.classList.add('light');
            document.getElementById('moon-icon').classList.add('hidden');
            document.getElementById('sun-icon').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error initializing dark mode:', error);
    }
}

// Toggle dark mode
function toggleDarkMode() {
    try {
        const isDarkMode = document.documentElement.classList.toggle('dark');
        document.documentElement.classList.toggle('light', !isDarkMode);
        localStorage.setItem('darkMode', isDarkMode);

        // Toggle icons
        document.getElementById('moon-icon').classList.toggle('hidden', !isDarkMode);
        document.getElementById('sun-icon').classList.toggle('hidden', isDarkMode);
    } catch (error) {
        console.error('Error toggling dark mode:', error);
    }
}

// Initialize dark mode on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDarkMode);
} else {
    initDarkMode();
}