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
$page_title = "CSR Dashboard";
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_SESSION['theme_preference']) && $_SESSION['theme_preference'] === 'dark' ? 'dark' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include('../includes/csr-theme.php'); ?>
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
                        <span class="text-pink font-medium">CSR</span>
                        <span class="italic">Dashboard</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm tracking-wider uppercase">Manage Dashboard</p>
                </div>
                <div class="w-6"></div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-6 py-8">
            <!-- Dashboard Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Total Projects</h3>
                    <p class="text-3xl font-bold text-pink-600 mt-2">
                        <?php
                        $query = "SELECT COUNT(*) as total FROM projects";
                        $result = $conn->query($query);
                        $total = $result->fetch_assoc()['total'];
                        echo $total;
                        ?>
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Active Clients</h3>
                    <p class="text-3xl font-bold text-pink-600 mt-2">
                        <?php
                        $query = "SELECT COUNT(*) as total FROM users WHERE role = 'CLIENT'";
                        $result = $conn->query($query);
                        $total = $result->fetch_assoc()['total'];
                        echo $total;
                        ?>
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Pending Approvals</h3>
                    <p class="text-3xl font-bold text-pink-600 mt-2">
                        <?php
                        $query = "SELECT COUNT(*) as total FROM projects WHERE project_status = 'Pending'";
                        $result = $conn->query($query);
                        $total = $result->fetch_assoc()['total'];
                        echo $total;
                        ?>
                    </p>
                </div>
            </div>

</body>

</html>