<?php
// Start session
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Check if user is logged in and has CLIENT role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CLIENT') {
    header('Location: ../login.php');
    exit;
}

// Database connection
require_once('../includes/db_connection.php');

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Get current user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 0) {
    // User not found in database - let's add some debugging
    error_log("User not found in database. User ID: " . $user_id);
    
    // Try to get user by email instead
    $email = $_SESSION['email'] ?? '';
    if (!empty($email)) {
        $email_query = "SELECT * FROM users WHERE email = ?";
        $email_stmt = $conn->prepare($email_query);
        $email_stmt->bind_param("s", $email);
        $email_stmt->execute();
        $email_result = $email_stmt->get_result();
        
        if ($email_result->num_rows > 0) {
            $user = $email_result->fetch_assoc();
            // Update session with correct user ID
            $_SESSION['user_id'] = $user['id'];
            $email_stmt->close();
        } else {
            $error_message = "User not found. Please contact support.";
            $user = [
                'full_name' => $_SESSION['full_name'] ?? 'Unknown User',
                'email' => $_SESSION['email'] ?? 'unknown@example.com',
                'phone' => '',
                'company' => '',
                'profile_picture' => $_SESSION['profile_picture'] ?? null
            ];
            $email_stmt->close();
        }
    } else {
        $error_message = "User not found. Please contact support.";
        $user = [
            'full_name' => $_SESSION['full_name'] ?? 'Unknown User',
            'email' => $_SESSION['email'] ?? 'unknown@example.com',
            'phone' => '',
            'company' => '',
            'profile_picture' => $_SESSION['profile_picture'] ?? null
        ];
    }
} else {
    $user = $result->fetch_assoc();
    // Store profile picture in session for use across pages
    $_SESSION['profile_picture'] = $user['profile_picture'];
    // Make sure user_id is correct in session
    $_SESSION['user_id'] = $user['id'];
}
$stmt->close();

// Check if profile_picture column exists, if not, add it to the database
if (!isset($user['profile_picture'])) {
    try {
        $alter_query = "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL";
        $conn->query($alter_query);
        $user['profile_picture'] = null;
    } catch (Exception $e) {
        // Column might already exist or other error
        $user['profile_picture'] = null;
    }
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check which form was submitted
    if (isset($_POST['update_profile'])) {
        // Profile update form
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $company = trim($_POST['company']);
        
        // Validate inputs
        $errors = [];
        
        if (empty($full_name)) {
            $errors[] = "Full name is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Check if email already exists (for another user)
        if ($email !== $user['email']) {
            $check_query = "SELECT id FROM users WHERE email = ? AND id != ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("si", $email, $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $errors[] = "Email is already in use by another account";
            }
            
            $check_stmt->close();
        }
        
        // Process profile picture if uploaded
        $profile_picture = $user['profile_picture']; // Keep existing by default
        
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['profile_picture'];
            
            // Check for upload errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "Error uploading profile picture. Error code: " . $file['error'];
            } else {
                // Validate file type
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) {
                    $errors[] = "Only JPG, PNG, and GIF images are allowed";
                }
                
                // Validate file size (max 2MB)
                if ($file['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Profile picture must be less than 2MB";
                }
                
                // If no errors, process the file
                if (empty($errors)) {
                    // Create uploads directory if it doesn't exist
                    $upload_dir = '../uploads/profile_pictures/';
                    
                    // Ensure the directory exists with proper permissions
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    // Generate unique filename
                    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $unique_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
                    $file_path = $upload_dir . $unique_filename;
                    
                    // Move uploaded file
                    if (move_uploaded_file($file['tmp_name'], $file_path)) {
                        // Delete old profile picture if exists
                        if (!empty($user['profile_picture']) && file_exists('../' . $user['profile_picture'])) {
                            @unlink('../' . $user['profile_picture']);
                        }
                        
                        $profile_picture = 'uploads/profile_pictures/' . $unique_filename;
                        
                        // Debug information
                        error_log("File uploaded successfully to: " . $file_path);
                    } else {
                        $error = error_get_last();
                        $errors[] = "Failed to save the profile picture. Error: " . ($error ? $error['message'] : 'Unknown error');
                        error_log("Failed to move uploaded file from {$file['tmp_name']} to {$file_path}. Error: " . ($error ? $error['message'] : 'Unknown error'));
                    }
                }
            }
        }
        
        // If no errors, update the profile
        if (empty($errors)) {
            // Make sure we're using the correct user_id
            $user_id = $_SESSION['user_id'];
            
            // Debug the values being saved
            error_log("Updating profile for user ID: " . $user_id);
            error_log("Profile picture path: " . $profile_picture);
            
            $update_query = "UPDATE users SET full_name = ?, email = ?, phone = ?, company = ?, profile_picture = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssssi", $full_name, $email, $phone, $company, $profile_picture, $user_id);
            
            if ($update_stmt->execute()) {
                // Update session variables
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email'] = $email;
                $_SESSION['profile_picture'] = $profile_picture;
                
                // Verify the update was successful
                $verify_query = "SELECT profile_picture FROM users WHERE id = ?";
                $verify_stmt = $conn->prepare($verify_query);
                $verify_stmt->bind_param("i", $user_id);
                $verify_stmt->execute();
                $verify_result = $verify_stmt->get_result();
                $verify_data = $verify_result->fetch_assoc();
                
                if ($verify_data && $verify_data['profile_picture'] === $profile_picture) {
                    error_log("Profile picture successfully updated in database: " . $profile_picture);
                } else {
                    error_log("Warning: Profile picture may not have been updated in database. DB value: " . 
                              ($verify_data ? $verify_data['profile_picture'] : 'null'));
                }
                $verify_stmt->close();
                
                $success_message = "Profile updated successfully!";
                
                // Refresh user data
                $user['full_name'] = $full_name;
                $user['email'] = $email;
                $user['phone'] = $phone;
                $user['company'] = $company;
                $user['profile_picture'] = $profile_picture;
            } else {
                $error_message = "Error updating profile: " . $conn->error;
                error_log("Database error when updating profile: " . $conn->error);
            }
            
            $update_stmt->close();
        } else {
            $error_message = implode("<br>", $errors);
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
                        <span class="text-pink font-medium">My</span> 
                        <span class="italic">Profile</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm tracking-wider uppercase">Manage your personal information</p>
                </div>
                <div class="w-6"></div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $success_message; ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $error_message; ?></span>
            </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Picture Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="mb-4">
                            <img src="<?php echo !empty($_SESSION['profile_picture']) ? '../' . htmlspecialchars($_SESSION['profile_picture']) : '../logo.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($user['full_name']); ?>" 
                                 class="h-32 w-32 rounded-full mx-auto object-cover border-4 border-pink-light" />
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                        <p class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="text-sm text-gray-500 mt-2">
                            <?php echo !empty($user['company']) ? htmlspecialchars($user['company']) : 'No company specified'; ?>
                        </p>
                        <p class="text-sm text-gray-500">
                            <?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'No phone number specified'; ?>
                        </p>
                    </div>
                </div>
                
                <!-- Profile Edit Form -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden md:col-span-2">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Profile</h2>
                        <form action="profile.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="full_name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink focus:border-pink" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink focus:border-pink" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink focus:border-pink">
                            </div>
                            
                            <div class="mb-4">
                                <label for="company" class="block text-gray-700 text-sm font-medium mb-2">Company</label>
                                <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($user['company'] ?? ''); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink focus:border-pink">
                            </div>
                            
                            <div class="mb-4">
                                <label for="profile_picture" class="block text-gray-700 text-sm font-medium mb-2">Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg,image/png,image/gif" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink focus:border-pink">
                                <p class="text-xs text-gray-500 mt-1">Max file size: 2MB. Allowed formats: JPG, PNG, GIF</p>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" name="update_profile" class="px-4 py-2 bg-pink text-white rounded-md hover:bg-pink-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Remove the Change Password Form div completely -->
            </div>
        </main>
    </div>

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




</body>
</html>

<!-- Apply theme from localStorage on page load -->
<script>
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
</script>
</body>
</html>
