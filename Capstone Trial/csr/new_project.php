<?php
// Start session
session_start();

// Check if user is logged in and has CLIENT role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CSR') {
    header('Location: ../login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Database connection
require_once('../includes/db_connection.php');

// Initialize variables
$project_name = '';
$project_description = '';
$errors = [];
$success_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate project name
    if (empty($_POST['project_name'])) {
        $errors[] = 'Project name is required';
    } else {
        $project_name = trim($_POST['project_name']);
        if (strlen($project_name) > 255) {
            $errors[] = 'Project name must be less than 255 characters';
        }
    }

    // Validate project description
    if (empty($_POST['project_description'])) {
        $errors[] = 'Project description is required';
    } else {
        $project_description = trim($_POST['project_description']);
    }

    // Validate file upload
    $file_path = '';
    $file_name = '';
    $file_type = '';

    if (isset($_FILES['project_file']) && $_FILES['project_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['project_file'];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Error uploading file. Error code: ' . $file['error'];
        }

        // Validate file size (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            $errors[] = 'File size must be less than 10MB';
        }

        // If no errors, process the file
        if (empty($errors)) {
            // Create uploads directory if it doesn't exist
            $upload_dir = '../uploads/projects/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Get file information
            $file_name = basename($file['name']);
            $file_type = $file['type'];

            // Generate unique filename
            $unique_filename = uniqid('project_') . '_' . $file_name;
            $file_path = $upload_dir . $unique_filename;

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                $errors[] = 'Failed to save the uploaded file. Please check folder permissions.';
            }
        }
    }

    // Get assigned user if provided
    $assigned_user = isset($_POST['assigned_user']) && !empty($_POST['assigned_user']) ? 
                    intval($_POST['assigned_user']) : null;
                    
    // Get visibility setting
    $visible_to_client_only = isset($_POST['visible_to_client_only']) ? 1 : 0;

    // If no errors, insert the project
    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $status = 'Pending'; // Default status for new projects

        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert project with file information
            if (!empty($file_path)) {
                $query = "INSERT INTO projects (project_name, project_description, project_status, client_id, file_path, file_name, file_type, assigned_user_id, visible_to_client_only) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssisssii", $project_name, $project_description, $status, $user_id, $file_path, $file_name, $file_type, $assigned_user, $visible_to_client_only);
            } else {
                $query = "INSERT INTO projects (project_name, project_description, project_status, client_id, assigned_user_id, visible_to_client_only) 
                          VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssiii", $project_name, $project_description, $status, $user_id, $assigned_user, $visible_to_client_only);
            }

            if ($stmt->execute()) {
                $conn->commit();
                $success_message = 'Project created successfully!';
                // Clear form fields after successful submission
                $project_name = '';
                $project_description = '';
            } else {
                throw new Exception('Error creating project: ' . $conn->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = $e->getMessage();

            // Remove uploaded file if there was an error
            if (!empty($file_path) && file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Project</title>
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

<body class="bg-pink-50 min-h-screen flex">
    <?php include('sidebar.php'); ?>

    <div class="flex-1 ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="container mx-auto px-6 py-4">
                <h1 class="text-3xl font-bold text-gray-900">Create New Project</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-6 py-8">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6">
<h2 class="text-2xl font-bold text-pink mb-6">Start a New Project</h2>

                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Please fix the following errors:</p>
                            <ul class="list-disc ml-5 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p><?php echo $success_message; ?></p>
                            <p class="mt-2">
                                <a href="projects.php" class="text-green-700 underline">View all projects</a> or create another project below.
                            </p>
                        </div>
                    <?php endif; ?>

                    <form action="new_project.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-6">
                            <label for="project_name" class="block text-gray-700 font-medium mb-2">Project Name *</label>
                            <input type="text" id="project_name" name="project_name" value="<?php echo htmlspecialchars($project_name); ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent"
                                placeholder="Enter project name" required>
                        </div>

                        <div class="mb-6">
                            <label for="project_description" class="block text-gray-700 font-medium mb-2">Project Description *</label>
                            <textarea id="project_description" name="project_description" rows="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent"
                                placeholder="Describe your project in detail" required><?php echo htmlspecialchars($project_description); ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Please provide as much detail as possible about your project requirements.</p>
                        </div>

                        <div class="mb-6">
                            <label for="project_file" class="block text-gray-700 font-medium mb-2">Project Document (Optional)</label>
                            <input type="file" id="project_file" name="project_file"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent">
                            <p class="text-sm text-gray-500 mt-1">Upload a document with additional project details or requirements (PDF, Word, Images, etc).</p>
                        </div>

                        <div class="mb-6">
                            <label for="assigned_user" class="block text-gray-700 font-medium mb-2">Assign To User</label>
                            <select id="assigned_user" name="assigned_user"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent">
                                <option value="">Select a user to assign</option>
                                <?php
                                // Create a new database connection specifically for this dropdown
                                $db_host = 'localhost';
                                $db_user = 'root';
                                $db_pass = '';
                                $db_name = 'iamai_db';
                                
                                $dropdown_conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
                                
                                if ($dropdown_conn->connect_error) {
                                    echo '<option value="">Connection failed: ' . $dropdown_conn->connect_error . '</option>';
                                } else {
                                    // Modified query to only get users with CLIENT role
                                    $query = "SELECT id, full_name, role FROM users WHERE role = 'CLIENT' ORDER BY full_name";
                                    $result = $dropdown_conn->query($query);
                                    
                                    if (!$result) {
                                        echo '<option value="">Error: ' . $dropdown_conn->error . '</option>';
                                    } elseif ($result->num_rows == 0) {
                                        echo '<option value="">No client users found in database</option>';
                                    } else {
                                        // Display all client users
                                        while ($user = $result->fetch_assoc()) {
                                            echo '<option value="' . $user['id'] . '">' . 
                                                htmlspecialchars($user['full_name']) . ' (Client)</option>';
                                        }
                                    }
                                    
                                    $dropdown_conn->close();
                                }
                                ?>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Select a user who will be responsible for this project.</p>
                        </div>
                        
                        <!-- Project Visibility Option -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="visible_to_client_only" name="visible_to_client_only" 
                                    class="h-4 w-4 text-pink focus:ring-pink border-gray-300 rounded">
                                <label for="visible_to_client_only" class="ml-2 block text-gray-700">
                                    Make this project visible only to the assigned user
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-1 ml-6">
                                If checked, only the assigned user will be able to see this project. Otherwise, all users can see it.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 mb-4">
                            <button type="submit" name="submit_project"
                                class="w-full bg-pink hover:bg-pink-dark text-white font-bold py-4 px-8 rounded-md shadow-lg transition-colors focus:outline-none focus:ring-2 focus:ring-pink focus:ring-opacity-50 text-lg">
                                CREATE PROJECT
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-center mt-4">
                            <a href="dashboard.php" class="text-gray-600 hover:text-gray-800">
                                Cancel and return to dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white mt-12 py-8 border-t border-gray-200">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <img src="../logo.png" alt="IAM.AI" class="h-8 mr-3" />
                        <span class="text-xl font-semibold text-gray-800">IAM.AI</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-pink transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-pink transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-pink transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="dashboard.php" class="text-gray-500 hover:text-pink transition-colors">Dashboard</a></li>
                            <li><a href="projects.php" class="text-gray-500 hover:text-pink transition-colors">My Projects</a></li>
                            <li><a href="messages.php" class="text-gray-500 hover:text-pink transition-colors">Messages</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-500 hover:text-pink transition-colors">Help Center</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-pink transition-colors">Contact Us</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-pink transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Contact</h3>
                        <p class="text-gray-500 mb-2">Email: support@iamai.com</p>
                        <p class="text-gray-500 mb-2">Phone: +1 (555) 123-4567</p>
                        <p class="text-gray-500">Address: 123 AI Street, Tech City</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
                    <p>© 2023 IAM.AI. All rights reserved.</p>
                    <p class="mt-1">Designed with <span class="text-pink">♥</span> for our valued clients</p>
                </div>
            </div>
        </footer>
    </div>

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
</body>

</html>

</main>
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
        // Image preview functionality
        document.getElementById('project_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    openImageModal(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        function openImageModal(imagePath) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            modalImage.src = imagePath;
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</body>
</html>