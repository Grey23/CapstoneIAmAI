<?php
session_start();
require_once('../includes/db_connection.php');

// Check if user is logged in and has CSR role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CSR') {
    header('Location: ../login.php');
    exit;
}

// Get user data for display
$user_id = $_SESSION['user_id'];

// Set page title
$page_title = "Client List";

// Debug: Check if projects table exists
$check_table_query = "SHOW TABLES LIKE 'projects'";
$table_result = $conn->query($check_table_query);
$projects_table_exists = $table_result->num_rows > 0;

// Debug: If table exists, check its structure
$projects_columns = [];
if ($projects_table_exists) {
    $structure_query = "DESCRIBE projects";
    $structure_result = $conn->query($structure_query);
    while ($row = $structure_result->fetch_assoc()) {
        $projects_columns[] = $row['Field'];
    }
}

// Handle search and filtering
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Base query - Fix the project count issue
$query = "SELECT u.id, u.full_name, u.email, u.created_at, u.last_login,
          (SELECT COUNT(*) FROM projects WHERE client_id = u.id) as project_count
          FROM users u 
          WHERE u.role = 'CLIENT'";

// Add search condition if provided
if (!empty($search)) {
    $query .= " AND (u.full_name LIKE ? OR u.email LIKE ?)";
}

// Add status filter if provided
if ($status === 'active') {
    $query .= " AND EXISTS (SELECT 1 FROM projects p2 WHERE p2.client_id = u.id AND p2.project_status != 'Completed')";
} elseif ($status === 'inactive') {
    $query .= " AND NOT EXISTS (SELECT 1 FROM projects p2 WHERE p2.client_id = u.id AND p2.project_status != 'Completed')";
}

// Add ordering
$query .= " ORDER BY u.full_name ASC";

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind search parameters if needed
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
$clients = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - IAM.AI</title>
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
    <?php include('../includes/theme-styles.php'); ?>
    <style>
        /* Dark mode styles */
        html.dark { background-color: #1a1a1a; }
        html.dark body { background-color: #1a1a1a; color: #ffffff; }
        html.dark .bg-white { background-color: #2d2d2d !important; }
        html.dark .text-gray-800 { color: #ffffff !important; }
        html.dark .text-gray-700 { color: #e5e5e5 !important; }
        html.dark .text-gray-500 { color: #e5e5e5 !important; }
        html.dark .bg-gray-50 { background-color: #374151 !important; }
        html.dark .bg-pink-50 { background-color: #1a1a1a !important; }
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

<body class="bg-pink-50 min-h-screen flex">
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
                        <span class="text-pink font-medium">Client</span>
                        <span class="italic">List</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm tracking-wider uppercase">Manage your clients</p>
                </div>
                <div class="w-6"></div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-6 py-8">
            <!-- Debug Information (only visible to admins) -->
            <?php if ($_SESSION['role'] === 'CSR'): ?>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                <p class="font-bold">Debug Information:</p>
                <p>Projects table exists: <?php echo $projects_table_exists ? 'Yes' : 'No'; ?></p>
                <?php if ($projects_table_exists): ?>
                <p>Projects table columns: <?php echo implode(', ', $projects_columns); ?></p>
                <p>Sample project data: 
                    <?php 
                    $sample_query = "SELECT * FROM projects LIMIT 1";
                    $sample_result = $conn->query($sample_query);
                    if ($sample_result && $sample_result->num_rows > 0) {
                        $sample_project = $sample_result->fetch_assoc();
                        echo "Project ID: " . $sample_project['id'] . ", Client ID: " . $sample_project['client_id'];
                    } else {
                        echo "No projects found in database";
                    }
                    ?>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Search and Filter Section -->
            <div class="mb-8">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search by name or email" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent"
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="absolute right-2 top-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent">
                            <option value="" <?php echo $status === '' ? 'selected' : ''; ?>>All Clients</option>
                            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active Clients</option>
                            <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive Clients</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="px-6 py-2 bg-pink text-white rounded-lg hover:bg-pink-dark transition-colors">
                            Filter
                        </button>
                        <?php if (!empty($search) || !empty($status)): ?>
                            <a href="client-list.php" class="ml-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Client List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Projects
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Login
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Joined
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (count($clients) > 0): ?>
                                <?php foreach ($clients as $client): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-pink-light text-pink rounded-full flex items-center justify-center">
                                                    <?php echo strtoupper(substr($client['full_name'], 0, 1)); ?>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($client['full_name']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($client['email']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-pink-light text-pink">
                                                <?php echo (int)$client['project_count']; ?> projects
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $client['last_login'] ? date('M d, Y g:i A', strtotime($client['last_login'])) : 'Never'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($client['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="client-details.php?id=<?php echo $client['id']; ?>" class="text-pink hover:text-pink-dark mr-3">
                                                View Details
                                            </a>
                                            <a href="client-projects.php?id=<?php echo $client['id']; ?>" class="text-indigo-600 hover:text-indigo-900">
                                                Projects
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No clients found. <?php echo !empty($search) ? 'Try a different search term.' : ''; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Apply theme from localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
            
            // Toggle sidebar on mobile
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('aside').classList.toggle('-translate-x-full');
                });
            }
            
            // Auto-submit form when status changes
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
</body>
</html>