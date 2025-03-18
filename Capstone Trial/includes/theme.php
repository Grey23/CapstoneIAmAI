<?php
function getThemePreference() {
    if (!isset($_SESSION['theme_preference'])) {
        $_SESSION['theme_preference'] = 'light';
    }
    return $_SESSION['theme_preference'];
}

function addThemeStyles() {
    echo '
    <script>
        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>
    <style>
        /* Dark mode styles */
        html.dark { background-color: #1a1a1a; }
        html.dark body { background-color: #1a1a1a; color: #ffffff; }
        html.dark .bg-white { background-color: #2d2d2d !important; }
        html.dark .text-gray-800 { color: #ffffff !important; }
        html.dark .text-gray-700 { color: #e5e5e5 !important; }
        html.dark .text-gray-600 { color: #e5e5e5 !important; }
        html.dark .bg-gray-50 { background-color: #374151 !important; }
        html.dark .bg-gray-100 { background-color: #374151 !important; }
        html.dark .bg-pink-light { background-color: #2d2d2d !important; }
        html.dark .border-gray-200 { border-color: #374151 !important; }
        html.dark .border-gray-100 { border-color: #374151 !important; }
        html.dark input,
        html.dark select,
        html.dark textarea { 
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border-color: #4b5563 !important;
        }
        html.dark .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important; }
        html.dark .hover\:bg-gray-50:hover { background-color: #374151 !important; }
        html.dark .hover\:bg-pink-light:hover { background-color: #2d2d2d !important; }
        html.dark table { border-color: #374151 !important; }
        html.dark th, 
        html.dark td { border-color: #374151 !important; }
    </style>';
}
?>