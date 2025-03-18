<script>
    // Apply theme from localStorage
    function applyTheme() {
        const theme = localStorage.getItem('theme') || '<?php echo getThemePreference(); ?>';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // Apply theme on page load
    applyTheme();

    // Listen for theme changes from other tabs/windows
    window.addEventListener('storage', function(e) {
        if (e.key === 'theme') {
            applyTheme();
        }
    });
</script>