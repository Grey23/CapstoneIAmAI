<?php
// This file contains common theme settings for CSR pages
?>

<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    pink: {
                        light: '#FFE4E9',
                        DEFAULT: '#FF1493',
                        dark: '#ff1493',
                    }
                }
            }
        }
    }
</script>
<style>
    /* Dark mode styles */
    html.dark { background-color: #1a1a1a; }
    html.dark body { background-color: #1a1a1a; color: #ffffff; }
    html.dark .bg-white { background-color: #2d2d2d !important; }
    html.dark .text-gray-800 { color: #ffffff !important; }
    html.dark .text-gray-700 { color: #e5e5e5 !important; }
    html.dark .text-gray-500 { color: #e5e5e5 !important; }
    html.dark .bg-gray-50 { background-color: #374151 !important; }
    html.dark .bg-pink-50 { background-color: #1a1a1a !important; }
    html.dark .bg-pink-light { background-color: #2d2d2d !important; }
    html.dark .border-gray-100 { border-color: #374151 !important; }
    html.dark .border-gray-200 { border-color: #374151 !important; }
    html.dark select, 
    html.dark input { 
        background-color: #1a1a1a !important;
        color: #ffffff !important;
        border-color: #374151 !important;
    }
    html.dark .hover\:bg-pink-light:hover {
        background-color: #374151 !important;
    }
</style>
<script>
    // Apply theme from localStorage on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (savedTheme === 'light') {
            document.documentElement.classList.remove('dark');
        }
        
        // Toggle sidebar on mobile (if exists)
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('aside').classList.toggle('-translate-x-full');
            });
        }
    });
</script>