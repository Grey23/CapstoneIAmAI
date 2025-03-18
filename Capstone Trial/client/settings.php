<?php
session_start();
require_once('../includes/db_connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CLIENT') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle data download
if (isset($_POST['download_data'])) {
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    // Get user's projects
    $projects_query = "SELECT * FROM projects WHERE client_id = ?";
    $stmt = $conn->prepare($projects_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $data = [
        'user_info' => $user_data,
        'projects' => $projects
    ];
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="my_data.json"');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

// Get user preferences
$query = "SELECT notification_preferences, theme_preference FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_preferences = $result->fetch_assoc();

// At the top of the file, after session_start()
if (!isset($_SESSION['theme_preference'])) {
    $_SESSION['theme_preference'] = 'light';
}

// Update the theme handling in the POST section
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_preferences'])) {
        $notification_preferences = isset($_POST['email_notifications']) ? 1 : 0;
        $theme = isset($_POST['theme']) && $_POST['theme'] === 'dark' ? 'dark' : 'light';

        $update_query = "UPDATE users SET notification_preferences = ?, theme_preference = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("isi", $notification_preferences, $theme, $user_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['theme_preference'] = $theme;
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $conn->error]);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - IAM.AI</title>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            
            // Apply initial theme
            if ('<?php echo $_SESSION["theme_preference"]; ?>' === 'dark') {
                document.documentElement.classList.add('dark');
                themeToggle.checked = true;
            }
    
            // Theme toggle handler
            themeToggle.addEventListener('change', function() {
                const newTheme = this.checked ? 'dark' : 'light';
                
                // Update UI immediately
                document.documentElement.classList.toggle('dark', this.checked);
                
                // Create form data
                const formData = new FormData();
                formData.append('theme', newTheme);
                formData.append('update_preferences', '1');
                formData.append('email_notifications', document.querySelector('input[name="email_notifications"]').checked ? '1' : '0');
                
                // Send AJAX request
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.setItem('theme', newTheme);
                    } else {
                        // Revert if failed
                        document.documentElement.classList.toggle('dark');
                        this.checked = !this.checked;
                        alert('Failed to update theme preference');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert on error
                    document.documentElement.classList.toggle('dark');
                    this.checked = !this.checked;
                });
            });
        });
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
</head>

<body class="bg-white min-h-screen flex">
    <?php include('sidebar.php'); ?>

    <div class="flex-1 ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="container mx-auto px-4 py-5 flex justify-between items-center">
                <button id="sidebarToggle" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="text-center flex-1">
                    <h1 class="text-3xl font-light tracking-wide text-gray-800">
                        <span class="text-pink font-medium">Account</span> 
                        <span class="italic">Settings</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm tracking-wider uppercase">Customize your experience</p>
                </div>
                <div class="w-6"></div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
    
    <div class="flex-1 ml-64">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Settings</h1>

            <?php if ($success_message): ?>
                <div class="bg-green-100 border border-gray-200 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Preferences Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Preferences</h2>
                    <form method="POST" class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-gray-700">Theme</label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="flex items-center justify-between">
                                    <span class="block text-gray-700">Dark Mode</span>
                                    <div class="relative inline-block w-14 h-8">
                                        <input type="checkbox" name="theme" id="themeToggle"
                                               class="sr-only"
                                               <?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'checked' : ''; ?>>
                                        <div class="toggle-background absolute inset-0 cursor-pointer rounded-full bg-gray-300 flex items-center justify-between px-1.5">
                                            <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                                            </svg>
                                            <svg class="h-5 w-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                            </svg>
                                            <div class="toggle-circle absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300"></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <h2 class="text-xl font-semibold mb-4">Data & Privacy</h2>
                        <button type="submit" name="download_data"
                                    class="w-full bg-gray-50 text-gray-700 px-4 py-3 rounded-lg hover:bg-pink-light hover:text-pink transition-colors flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Download my data
                            </button>

                        <button type="submit" name="update_preferences"
                                class="w-full bg-pink text-white px-4 py-2 rounded-lg hover:bg-pink-dark transition-colors">
                            Save Preferences
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>


</body>
</html>


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
    
    /* Toggle Switch Styles */
    .toggle-background {
        transition: background-color 0.3s;
    }
    
    input:checked + .toggle-background {
        background-color: #FF1493;
    }
    
    input:checked + .toggle-background .toggle-circle {
        transform: translateX(1.5rem);
    }
    
    .toggle-circle {
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: transform 0.3s ease-in-out;
    }

    /* Dark mode toggle specific */
    .toggle-background svg {
        z-index: 1;
    }

    input:checked + .toggle-background .text-yellow-500 {
        color: #ffffff;
    }

    input:checked + .toggle-background .text-gray-200 {
        color: #374151;
    }
</style>

<!-- Add this before closing body tag -->
<div id="notificationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-xl font-semibold text-gray-900">Notifications</h3>
            <button onclick="toggleNotificationModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="notificationList" class="mt-4 max-h-96 overflow-y-auto">
            <!-- Notifications will be populated here -->
        </div>
    </div>
</div>

<script>
    // Apply theme from localStorage on page load
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
    
    // Add notification functionality
    function toggleNotificationModal() {
        const modal = document.getElementById('notificationModal');
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            checkNotifications();
        }
    }
    
    function checkNotifications() {
        // Add your notification checking logic here
        const notificationList = document.getElementById('notificationList');
        notificationList.innerHTML = '<p class="text-gray-500 text-center py-4">No new notifications</p>';
    }
    
    // Theme toggle functionality
    document.getElementById('themeToggle').addEventListener('change', function() {
        if (this.checked) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    });
</script>