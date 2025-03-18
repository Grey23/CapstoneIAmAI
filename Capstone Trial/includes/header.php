<?php
if (!isset($_SESSION['theme_preference'])) {
    $_SESSION['theme_preference'] = 'light';
}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - IAM.AI' : 'IAM.AI'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        html.dark .bg-gray-50 { background-color: #374151 !important; }
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
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>