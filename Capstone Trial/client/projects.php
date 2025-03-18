<?php
// Start session
session_start();

// Check if user is logged in and has CLIENT role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CLIENT') {
    header('Location: ../login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Clear remember me cookies if they exist
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
    if (isset($_COOKIE['user_email'])) {
        setcookie('user_email', '', time() - 3600, '/');
    }
    
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Database connection
require_once('../includes/db_connection.php');

// Get current user ID
$user_id = $_SESSION['user_id'];

// Query to get all user's projects - without joining users table
$query = "SELECT p.* 
          FROM projects p 
          WHERE p.client_id = ? 
          OR (p.assigned_user_id = ? AND p.visible_to_client_only = 1)
          ORDER BY p.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$projects = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include('../includes/theme-styles.php'); ?>
</head>

<body class="bg-white min-h-screen text-gray-800 font-sans flex">
    <!-- Sidebar Navigation -->
    <?php include('sidebar.php'); ?>

    <!-- Main Content Area -->
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
                        <span class="text-pink font-medium">Welcome</span> to your 
                        <span class="italic">Projects</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm tracking-wider uppercase">View your project & Download</p>
                </div>
                <div class="w-6"></div> <!-- Empty div for balance -->
            </div>
        </header>
        

        
        
        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg  border border-gray-200 shadow-md overflow-hidden">
                <?php if (empty($projects)): ?>
                    <div class="p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No Active Projects</h3>
                        <p class="text-gray-600 mb-6">You currently don't have any projects in your dashboard. Please contact our customer service representative to initiate a new project request.</p>
                        <div class="space-x-4">
                            <a href="messages.php" class="inline-block bg-pink hover:bg-pink-dark text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors">
                                Contact CSR
                            </a>
                            <a href="dashboard.php" class="inline-block border border-pink text-pink hover:bg-pink-light font-medium py-2 px-6 rounded-md transition-colors">
                                Return to Dashboard
                            </a>
                        </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Project Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Last Updated
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <?php if ($project['file_path'] && $project['file_name']): ?>
                                                    <img class="h-12 w-12 rounded-lg object-cover" 
                                                         src="<?php echo htmlspecialchars($project['file_path'] . $project['file_name']); ?>" 
                                                         alt="Project File">
                                                <?php else: ?>
                                                    <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($project['project_name']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500 max-w-md truncate">
                                                    <?php echo htmlspecialchars(substr($project['project_description'], 0, 100)) . (strlen($project['project_description']) > 100 ? '...' : ''); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $status_color = '';
                                        switch ($project['project_status']) {
                                            case 'Pending':
                                                $status_color = 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'In Progress':
                                                $status_color = 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'Completed':
                                                $status_color = 'bg-green-100 text-green-800';
                                                break;
                                            case 'Cancelled':
                                                $status_color = 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                $status_color = 'bg-gray-100 text-gray-800';
                                        }
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_color; ?>">
                                            <?php echo htmlspecialchars($project['project_status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($project['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($project['updated_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="view_project.php?id=<?php echo $project['id']; ?>" class="text-pink hover:text-pink-dark mr-3">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        
       

    <!-- Sidebar Toggle Script -->
    <script>
        // Apply theme from localStorage on page load
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.flex-1');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-64');
            } else {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            }
        });

        // Responsive sidebar behavior
        function checkScreenSize() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.flex-1');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            } else {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-64');
            }
        }

        // Check on page load
        window.addEventListener('load', checkScreenSize);
        // Check on resize
        window.addEventListener('resize', checkScreenSize);
    </script>
</body>
</html>
