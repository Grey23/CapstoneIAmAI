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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Password validation
        if (strlen($new_password) < 8) {
            $error_message = "Password must be at least 8 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $error_message = "New passwords do not match.";
        } else {
            // Verify current password
            $query = "SELECT password FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (password_verify($current_password, $user['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                
                if ($update_stmt->execute()) {
                    $success_message = "Password updated successfully!";
                } else {
                    $error_message = "Failed to update password.";
                }
            } else {
                $error_message = "Current password is incorrect.";
            }
        }
    }

    if (isset($_POST['enable_2fa'])) {
        $update_query = "UPDATE users SET two_factor_enabled = 1 WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $success_message = "Two-factor authentication enabled!";
        }
    }
}

// Get login history
$history_query = "SELECT last_login, last_login_ip FROM users WHERE id = ?";
$stmt = $conn->prepare($history_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$login_history = $stmt->get_result()->fetch_assoc();

// Set page title
$page_title = "Security Settings";
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings - IAM.AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include('../includes/theme-styles.php'); ?>
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
                        <span class="italic">Security</span>
</h1>
                </div>
                <div class="w-6"></div>
            </div>
        </header>
    
        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <?php if ($success_message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password Change Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                    <form method="POST" class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-pink">
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-gray-700 mb-2">New Password</label>
                            <input type="password" name="new_password" required
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-pink">
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="confirm_password" required
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-pink">
                        </div>
                        <button type="submit" name="change_password"
                                class="w-full bg-pink text-white px-4 py-2 rounded-lg hover:bg-pink-dark transition-colors">
                            Update Password
                        </button>
                    </form>
                </div>

               

                    <!-- Security Tips Card -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Security Tips</h2>
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-800 mb-2">Strong Password Tips</h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-2">
                                    <li>Use at least 8 characters</li>
                                    <li>Include numbers and special characters</li>
                                    <li>Mix uppercase and lowercase letters</li>
                                    <li>Avoid using personal information</li>
                                </ul>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-800 mb-2">Account Security</h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-2">
                                    <li>Don't share your password</li>
                                    <li>Use unique passwords for different accounts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
    // Apply theme from localStorage on page load
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
</script>

<!-- Existing password strength indicator script -->
</body>
</html>

