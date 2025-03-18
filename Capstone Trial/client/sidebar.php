<?php
// At the top of the file
if (!isset($_SESSION['theme_preference'])) {
    // Get theme preference from database if not in session
    $theme_query = "SELECT theme_preference FROM users WHERE id = ?";
    $theme_stmt = $conn->prepare($theme_query);
    $theme_stmt->bind_param("i", $_SESSION['user_id']);
    $theme_stmt->execute();
    $theme_result = $theme_stmt->get_result();
    $theme_data = $theme_result->fetch_assoc();
    $_SESSION['theme_preference'] = $theme_data['theme_preference'] ?? 'light';
    $theme_stmt->close();
}
?>
<!-- Sidebar Navigation -->
<aside class="w-64 bg-white shadow-md fixed h-full z-10 transition-all duration-300" id="sidebar">
    <div class="p-4 border-b border-gray-100">
        <a href="dashboard.php" class="flex items-center space-x-3">
            <img src="<?php echo !empty($_SESSION['profile_picture']) ? '../' . htmlspecialchars($_SESSION['profile_picture']) : '../logo.png'; ?>" 
                 alt="<?php echo htmlspecialchars($_SESSION['full_name']); ?>" 
                 class="h-10 w-10 rounded-full shadow-sm object-cover" />
            <div class="flex flex-col">
                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <span class="text-xs text-pink bg-pink-100 px-2 py-0.5 rounded-full inline-block">Client</span>
            </div>
        </a>
    </div>
    
    <nav class="mt-6 px-2">
        <!-- Update each link with the sidebar-link class -->
        <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span class="ml-3">Dashboard</span>
        </a>
        <a href="projects.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-light hover:border-r-4 hover:border-pink transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">My Projects</span>
        </a>
        
        <div class="px-4 mt-6 mb-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Account Management
        </div>
        <a href="profile.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-light hover:border-r-4 hover:border-pink transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">Profile</span>
        </a>
        <a href="settings.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-light hover:border-r-4 hover:border-pink transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">Settings</span>
        </a>
        <a href="security-settings.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-light hover:border-r-4 hover:border-pink transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">Security</span>
        </a>
        
        
        <form action="../includes/logout.php" method="POST" class="mt-6">
            <button type="submit" class="w-full flex items-center px-4 py-3 text-pink hover:bg-pink-light hover:border-r-4 hover:border-pink transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Logout</span>
            </button>
        </form>
        
        </nav>
    </aside>
