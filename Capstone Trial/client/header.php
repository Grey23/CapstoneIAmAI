<?php require_once('../includes/theme_manager.php'); ?>
<!DOCTYPE html>
<html lang="en" class="<?php echo getThemeClass(); ?>">
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
    <?php addThemeHeader(); ?>
</head>
<body class="bg-pink-light min-h-screen font-sans <?php 
    $theme = getCurrentTheme();
    echo $theme === 'dark' ? 'dark-mode' : ''; 
    echo ' ' . getThemeClasses($theme)['body'];
?>