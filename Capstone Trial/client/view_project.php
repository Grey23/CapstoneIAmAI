<?php
// Start session
session_start();

// Check if logout is requested
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Clear all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Clear any remember me cookies if they exist
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

// Check if user is logged in and has CLIENT role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CLIENT') {
    header('Location: ../login.php');
    exit;
}

// Check if project ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: projects.php');
    exit;
}

$project_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Database connection
require_once('../includes/db_connection.php');

// Get project details - modified to allow viewing projects assigned to the user
try {
    $query = "SELECT p.*, u.full_name as assigned_user_name 
              FROM projects p 
              LEFT JOIN users u ON p.assigned_user_id = u.id 
              WHERE p.id = ? AND (p.client_id = ? OR p.assigned_user_id = ? OR p.visible_to_client_only = 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $project_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if project exists and is accessible to the current user
    if ($result->num_rows === 0) {
        header('Location: projects.php');
        exit;
    }

    $project = $result->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    // Handle the error - redirect to a setup page
    header('Location: ../setup_database.php');
    exit;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pink: {
                            light: '#f9f5f7',
                            DEFAULT: '#ff69b4',
                            dark: '#ff1493',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-pink-light min-h-screen text-gray-800 font-sans flex">
    <!-- Include Sidebar -->
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
                        <span class="italic">View & Download</span>
                    </h1>
                </div>
                <div class="w-6"></div> <!-- Empty div for balance -->
            </div>
        </header>

        <!-- Main Content -->

        <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-75 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg max-w-5xl w-full">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Image Preview</h3>
                    <button type="button" onclick="closeImageModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <img id="modalImage" src="" alt="Preview" class="max-w-full h-auto mx-auto">
                </div>
                <div class="flex items-center justify-end p-4 border-t">
                    <button type="button" onclick="closeImageModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Close
                    </button>
                    <a id="downloadLink" href="#" class="ml-3 px-4 py-2 bg-pink text-white rounded-md hover:bg-pink-dark">
                        Download
                    </a>
                </div>
            </div>
        </div>
        <main class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($project['project_name']); ?></h2>
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
                            <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo $status_color; ?>">
                                <?php echo htmlspecialchars($project['project_status']); ?>
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Created: <?php echo date('F j, Y', strtotime($project['created_at'])); ?></p>
                            <p class="text-sm text-gray-500">Last Updated: <?php echo date('F j, Y', strtotime($project['updated_at'])); ?></p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Project Description</h3>
                        <div class="prose max-w-none text-gray-600">
                            <p><?php echo nl2br(htmlspecialchars($project['project_description'])); ?></p>
                        </div>
                    </div>

                    <!-- Project File Section -->
                    <!-- Update the file path construction and download link -->
                    <?php if (!empty($project['file_path'])): ?>
                        <div class="border-t border-gray-200 mt-8 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Document</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <?php
                                        $file_extension = pathinfo($project['file_path'], PATHINFO_EXTENSION);
                                        $filename = basename($project['file_path']);
                                        $icon_class = 'text-gray-500';

                                        // Set icon based on file type
                                        if (in_array($file_extension, ['pdf'])) {
                                            $icon_class = 'text-red-500';
                                        } elseif (in_array($file_extension, ['doc', 'docx'])) {
                                            $icon_class = 'text-blue-500';
                                        } elseif (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $icon_class = 'text-green-500';
                                        }
                                        ?>

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 <?php echo $icon_class; ?> mr-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>

                                        <div>
                                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars(basename($project['file_path'])); ?></p>
                                            <p class="text-sm text-gray-500">
                                                <?php echo strtoupper($file_extension); ?> File
                                                <?php
                                                // Get file size if file exists
                                                $file_path = '../uploads/' . basename($project['file_path']);
                                                if (file_exists($file_path)) {
                                                    $file_size = filesize($file_path);
                                                    // Convert to KB or MB
                                                    if ($file_size < 1024 * 1024) {
                                                        echo ' • ' . round($file_size / 1024, 2) . ' KB';
                                                    } else {
                                                        echo ' • ' . round($file_size / (1024 * 1024), 2) . ' MB';
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <button type="button" onclick="openImageModal('<?php echo htmlspecialchars($project['file_path']); ?>')"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                                View
                                            </button>
                                        <?php endif; ?>

                                        <?php if (in_array($file_extension, ['pdf'])): ?>
                                            <button type="button" onclick="openPdfModal('<?php echo htmlspecialchars($project['file_path']); ?>')"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                                View PDF
                                            </button>
                                        <?php endif; ?>

                                        <a href="download.php?file=<?php echo basename($project['file_path']); ?>"
                                            class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-pink hover:bg-pink-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="border-t border-gray-200 mt-8 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Timeline</h3>
                    </div>

                    <div class="relative flex items-start mb-6">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-pink flex items-center justify-center z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-md font-medium text-gray-800">Project Created</h4>
                            <p class="text-sm text-gray-500"><?php echo date('F j, Y', strtotime($project['created_at'])); ?></p>
                            <p class="mt-1 text-sm text-gray-600">Your project has been submitted successfully.</p>
                        </div>
                    </div>

                    <?php if ($project['project_status'] != 'Pending'): ?>
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-medium text-gray-800">Status Updated</h4>
                                <p class="text-sm text-gray-500"><?php echo date('F j, Y', strtotime($project['updated_at'])); ?></p>
                                <p class="mt-1 text-sm text-gray-600">Project status changed to <span class="font-medium"><?php echo htmlspecialchars($project['project_status']); ?></span>.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <?php if ($project['project_status'] === 'Pending'): ?>
                        <form method="POST" action="approve_project.php">
                            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Approve Project
                            </button>
                        </form>

                        <!-- Disapprove Button -->
                        <form method="POST" action="disapprove_project.php">
                            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Disapprove Project
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($project['project_status'] === 'Pending' || $project['project_status'] === 'In Progress'): ?>
                        <a href="messages.php?project_id=<?php echo $project['id']; ?>"
                            class="inline-flex items-center px-4 py-2 bg-pink hover:bg-pink-dark text-white rounded-md shadow-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                            </svg>
                            Message
                        </a>
                    <?php endif; ?>
                    <a href="projects.php" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Projects
                    </a>
                </div>
            </div>

            <?php if ($project['project_status'] === 'Completed'): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Feedback</h3>

                        <?php if (isset($project['feedback']) && !empty($project['feedback'])): ?>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($project['feedback'])); ?></p>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600">No feedback has been provided for this project yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
    </div>

    
    </main>

    

    <!-- Sidebar Toggle Script -->
    <script>
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

    <!-- PDF Preview Modal -->
    <div id="pdfModal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-75 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg max-w-5xl w-full h-[80vh] max-h-screen overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">PDF Preview</h3>
                <button type="button" onclick="closePdfModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-0 flex items-center justify-center h-[calc(100%-8rem)]">
                <iframe id="pdfFrame" src="" class="w-full h-full border-0"></iframe>
            </div>
            <div class="flex items-center justify-end p-4 border-t">
                <button type="button" onclick="closePdfModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Close
                </button>
                <a id="pdfDownloadLink" href="#" class="ml-3 px-4 py-2 bg-pink text-white rounded-md hover:bg-pink-dark">
                    Download
                </a>
            </div>
        </div>
    </div>
    <!-- Image Preview Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-75 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg max-w-5xl w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Image Preview</h3>
                <button type="button" onclick="closeImageModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <img id="modalImage" src="" alt="Preview" class="max-w-full h-auto mx-auto">
            </div>
            <div class="flex items-center justify-end p-4 border-t">
                <button type="button" onclick="closeImageModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Close
                </button>
                <a id="downloadLink" href="#" class="ml-3 px-4 py-2 bg-pink text-white rounded-md hover:bg-pink-dark">
                    Download
                </a>
            </div>
        </div>
    </div>
    <script>
       function openImageModal(imagePath) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const downloadLink = document.getElementById('downloadLink');
            
            // Get just the filename from the path
            const fileName = imagePath.split('/').pop();
            
            // Construct the full path relative to the web root
            const fullPath = '../uploads/projects/' + fileName;
            
            modal.classList.remove('hidden');   
            document.body.classList.add('overflow-hidden');
            modalImage.src = fullPath;
            
            // Set download link
            downloadLink.href = 'download.php?file=' + fileName;
            
            // Debug log
            console.log('Opening image:', fullPath);
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            modalImage.src = ''; // Clear the image source
        }
        // PDF modal functions
        function openPdfModal(pdfPath) {
            console.log("Opening modal with PDF:", pdfPath); // Debug
            const modal = document.getElementById('pdfModal');
            const pdfFrame = document.getElementById('pdfFrame');
            const downloadLink = document.getElementById('pdfDownloadLink');

            // Get just the filename from the path
            const fileName = pdfPath.split('/').pop();

            // Show the modal first
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Set the download link
            downloadLink.href = 'download.php?file=' + fileName;

            // For direct viewing, use a dedicated PDF viewer page
            pdfFrame.src = 'pdf_viewer.php?file=' + fileName;

            console.log("Final PDF source:", pdfFrame.src); // Debug
        }

        function closePdfModal() {
            const modal = document.getElementById('pdfModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');

            // Clear the iframe source when closing
            setTimeout(() => {
                document.getElementById('pdfFrame').src = '';
            }, 300);
        }

        // Close modal when clicking outside of it
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        document.getElementById('pdfModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePdfModal();
            }
        });

        // Close modal when pressing ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closePdfModal();
            }
        });
    </script>
</body>

</html>