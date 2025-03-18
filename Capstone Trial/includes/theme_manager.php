<?php
function getThemeClass() {
    return isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : '';
}

function addThemeHeader() {
    echo '
    <script>
        if (localStorage.getItem("theme") === "dark" || 
            (window.matchMedia("(prefers-color-scheme: dark)").matches)) {
            document.documentElement.classList.add("dark");
        }
    </script>
    <style>
        /* Dark mode styles */
        .dark { color-scheme: dark; }
        .dark body { background-color: #1a1a1a; color: #e5e5e5; }
        .dark .bg-white { background-color: #2d2d2d !important; }
        .dark .bg-pink-light { background-color: #1a1a1a !important; }
        .dark .text-gray-800 { color: #ffffff !important; }
        .dark .text-gray-700 { color: #e5e5e5 !important; }
        .dark .text-gray-600 { color: #d1d5db !important; }
        .dark .bg-gray-50 { background-color: #374151 !important; }
        .dark .bg-gray-100 { background-color: #374151 !important; }
        .dark .border-gray-200 { border-color: #374151 !important; }
        .dark .border-gray-100 { border-color: #374151 !important; }
        .dark input, 
        .dark select, 
        .dark textarea { 
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border-color: #4b5563 !important;
        }
        .dark .hover\:bg-gray-50:hover { background-color: #374151 !important; }
        .dark .hover\:bg-pink-light:hover { background-color: #2d2d2d !important; }
        .dark .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important; }
        .dark .card { background-color: #2d2d2d; border-color: #374151; }
        .dark .sidebar { background-color: #2d2d2d; border-color: #374151; }
    </style>';
}
?>