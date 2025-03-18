<?php
if (!isset($_SESSION['theme_preference'])) {
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

<aside class="w-64 bg-white shadow-md fixed h-full z-10 transition-all duration-300" id="sidebar">
    <div class="p-4 border-b border-gray-100">
        <a href="dashboard.php" class="flex items-center space-x-3">
            <img src="<?php echo !empty($_SESSION['profile_picture']) ? '../' . htmlspecialchars($_SESSION['profile_picture']) : '../logo.png'; ?>" 
                 alt="<?php echo htmlspecialchars($_SESSION['full_name']); ?>" 
                 class="h-10 w-10 rounded-full shadow-sm object-cover" />
            <div class="flex flex-col">
                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <span class="text-xs text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full inline-block">CSR</span>
            </div>
        </a>
    </div>
    
    <nav class="mt-6 px-2">
        <a href="dashboard.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span class="ml-3">Dashboard</span>
        </a>

        <a href="client-list.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <span class="ml-3">Client List</span>
        </a>

        <a href="design-approval.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">Design Approval</span>
        </a>

        <a href="printing-progress.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
            </svg>
            <span class="ml-3">Printing Team Progress</span>
        </a>

        <a href="graphics-team.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">Graphics Design Team</span>
        </a>

        <a href="approved-designs.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="ml-3">Approved Designs</span>
        </a>

        <a href="new_project.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-pink-50 rounded-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span class="ml-3">New Project</span>
        </a>

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

        <div class="mt-6">
            <form action="../includes/logout.php" method="POST">
                <button type="submit" class="w-full flex items-center px-4 py-3 text-pink-600 hover:bg-pink-50 rounded-lg transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<script>
    // Check for theme toggle in sidebar
    document.addEventListener('DOMContentLoaded', function() {
        // Get theme from session or localStorage
        const currentTheme = '<?php echo isset($_SESSION["theme_preference"]) ? $_SESSION["theme_preference"] : "light"; ?>';
        
        // Apply theme on page load
        if (currentTheme === 'dark' || localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
</script>