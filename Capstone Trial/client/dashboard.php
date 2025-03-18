<?php
session_start();
require_once('../includes/db_connection.php');

// Check if user is logged in and has CLIENT role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CLIENT') {
    header('Location: ../login.php');
    exit;
}

// Get user data for display
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Make sure profile_picture is in session
if (!isset($_SESSION['profile_picture']) && isset($user['profile_picture'])) {
    $_SESSION['profile_picture'] = $user['profile_picture'];
}

// Set page title
$page_title = "Client Dashboard";
include('../includes/header.php');
?>

<body class="bg-white min-h-screen flex">
    <?php include('sidebar.php'); ?>

    <!-- Remove the duplicate DOCTYPE, html, head, and body tags -->
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
                        <span class="italic">Dashboard</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm tracking-wider uppercase">Manage your projects with style</p>
                </div>
                <div class="w-6"></div>
            </div>
        </header>

        
    
            <!-- Project Progress and Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Project Progress -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Progress</h3>
                    <div class="space-y-4" id="progress-bars">
                        <?php
                        // Get total count first
                        $total_query = "SELECT COUNT(*) as total FROM projects WHERE client_id = ? OR (assigned_user_id = ? AND visible_to_client_only = 1)";
                        $stmt = $conn->prepare($total_query);
                        $stmt->bind_param("ii", $user_id, $user_id);
                        $stmt->execute();
                        $total = $stmt->get_result()->fetch_assoc()['total'];
                        $stmt->close();

                        if ($total > 0) {
                            foreach (['Pending' => 'yellow', 'Cancelled' => 'red', 'Completed' => 'green'] as $status => $color) {
                                $count_query = "SELECT COUNT(*) as count FROM projects 
                                              WHERE (client_id = ? OR (assigned_user_id = ? AND visible_to_client_only = 1))
                                              AND project_status = ?";
                                $stmt = $conn->prepare($count_query);
                                $stmt->bind_param("iis", $user_id, $user_id, $status);
                                $stmt->execute();
                                $count = $stmt->get_result()->fetch_assoc()['count'];
                                $percentage = ($count / $total) * 100;
                                ?>
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div class="text-sm font-semibold text-gray-700"><?php echo $status; ?></div>
                                        <div class="text-sm font-semibold text-gray-700"><?php echo round($percentage); ?>%</div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-<?php echo $color; ?>-100">
                                        <div style="width: <?php echo $percentage; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-<?php echo $color; ?>-500"></div>
                                    </div>
                                </div>
                                <?php
                                $stmt->close();
                            }
                        }
                        ?>
                    </div>
                </div>
    
                <!-- Recent Activities -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activities</h3>
                    <div class="space-y-4" id="recent-activities">
                        <?php
                        $recent_query = "SELECT p.*, DATE_FORMAT(p.updated_at, '%M %d, %Y') as formatted_date 
                                       FROM projects p 
                                       WHERE client_id = ? OR (assigned_user_id = ? AND visible_to_client_only = 1)
                                       ORDER BY updated_at DESC LIMIT 5";
                        $stmt = $conn->prepare($recent_query);
                        $stmt->bind_param("ii", $user_id, $user_id);
                        $stmt->execute();
                        $recent_activities = $stmt->get_result();
                        
                        while ($activity = $recent_activities->fetch_assoc()) {
                            $status_color = [
                                'Pending' => 'yellow',
                                'Cancelled' => 'red',
                                'Completed' => 'green'
                            ][$activity['project_status']] ?? 'gray';
                            ?>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-block h-8 w-8 rounded-full bg-<?php echo $status_color; ?>-100 text-<?php echo $status_color; ?>-500 text-center leading-8">
                                        <?php echo substr($activity['project_name'], 0, 1); ?>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <?php echo htmlspecialchars($activity['project_name']); ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Status: <span class="text-<?php echo $status_color; ?>-500"><?php echo $activity['project_status']; ?></span>
                                    </p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo $activity['formatted_date']; ?>
                                </div>
                            </div>
                            <?php
                        }
                        $stmt->close();
                        ?>
                    </div>
                </div>
            </div>
    
            <!-- Projects Table -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">My Projects</h3>
                        <a href="projects.php" class="text-pink-600 hover:text-pink-700 text-sm font-medium">View All</a>
                    </div>
                </div>
                <!-- Add your projects table content here -->
            </div>
        </main>
    </div>

    <?php
    // Close the database connection at the very end
    $conn->close();
    ?>

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
    <script>
        function updateDashboard() {
            fetch('get_dashboard_stats.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Server error:', data.error);
                        return;
                    }
                    
                    // Update statistics with fallback to 0
                    document.getElementById('total-projects').textContent = data.total_projects || '0';
                    document.getElementById('pending-projects').textContent = data.pending || '0';
                    document.getElementById('cancelled-projects').textContent = data.in_progress || '0';
                    document.getElementById('completed-projects').textContent = data.completed || '0';
                })
                .catch(error => {
                    console.error('Error updating dashboard:', error);
                });
        }

        // Update every 30 seconds
        setInterval(updateDashboard, 30000);

        // Initial update
        document.addEventListener('DOMContentLoaded', updateDashboard);
    </script>
</body>
</html>
