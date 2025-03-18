<?php
session_start();
require_once('../includes/db_connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CSR') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle data download
if (isset($_POST['download_data'])) {
    // ... existing download code ...
}

// Get user preferences
$query = "SELECT notification_preferences, theme_preference FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_preferences = $result->fetch_assoc();

// Set default theme preference if not set
if (!isset($_SESSION['theme_preference'])) {
    // Check if user has a preference in the database
    if ($user_preferences && isset($user_preferences['theme_preference'])) {
        $_SESSION['theme_preference'] = $user_preferences['theme_preference'];
    } else {
        $_SESSION['theme_preference'] = 'light';
    }
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
    <?php include('../includes/csr-theme.php'); ?>
    <style>
        /* Toggle Switch Styles */
        .toggle-background {
            transition: background-color 0.3s;
        }

        input:checked+.toggle-background {
            background-color: #FF1493;
        }

        input:checked+.toggle-background .toggle-circle {
            transform: translateX(1.5rem);
        }

        .toggle-circle {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        /* Dark mode toggle specific */
        .toggle-background svg {
            z-index: 1;
        }

        input:checked+.toggle-background .text-yellow-500 {
            color: #ffffff;
        }

        input:checked+.toggle-background .text-gray-200 {
            color: #374151;
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
        <main class="container mx-auto px-4 py-8">
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
                                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                                            </svg>
                                            <svg class="h-5 w-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
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
        </main>
    </div>

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
                formData.append('email_notifications', document.querySelector('input[name="email_notifications"]') ?
                    (document.querySelector('input[name="email_notifications"]').checked ? '1' : '0') : '0');

                // Send AJAX request
                fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Store theme in localStorage for all pages
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

            // Toggle sidebar on mobile
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('aside').classList.toggle('-translate-x-full');
                });
            }
        });
    </script>
</body>

</html>