<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "iamai_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'true';
    
    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit;
    }
    
    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, email, password, role, full_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password (assuming passwords are hashed with password_hash())
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['logged_in'] = true;
                
                // Check if profile_picture column exists and get profile picture
                $check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'profile_picture'");
                if ($check_column->num_rows > 0) {
                    // Column exists, get the profile picture
                    $profile_stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
                    $profile_stmt->bind_param("i", $user['id']);
                    $profile_stmt->execute();
                    $profile_result = $profile_stmt->get_result();
                    $profile_data = $profile_result->fetch_assoc();
                    
                    if ($profile_data && !empty($profile_data['profile_picture'])) {
                        $_SESSION['profile_picture'] = $profile_data['profile_picture'];
                        error_log("Profile picture loaded into session: " . $profile_data['profile_picture']);
                    }
                    $profile_stmt->close();
                }
                
                // Set remember me cookie if selected
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60); // 30 days
                    
                    // Store token in database
                    $update_stmt = $conn->prepare("UPDATE users SET remember_token = ?, token_expires = ? WHERE id = ?");
                    $expiry_date = date('Y-m-d H:i:s', $expires);
                    $update_stmt->bind_param("ssi", $token, $expiry_date, $user['id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    // Set cookie
                    setcookie('remember_token', $token, $expires, '/');
                    setcookie('user_email', $email, $expires, '/');
                }
                
                // Determine redirect based on role
                $redirect = 'dashboard.php';
                switch ($user['role']) {
                    case 'ADMIN':
                        $redirect = 'admin/dashboard.php';
                        break;
                    case 'CSR':
                        $redirect = 'csr/dashboard.php';
                        break;
                    case 'CLIENT':
                        $redirect = 'client/dashboard.php';
                        break;
                    case 'PRINTING':
                        $redirect = 'printing/dashboard.php';
                        break;
                    case 'DESIGNING':
                        $redirect = 'designing/dashboard.php';
                        break;
                }
                
                echo json_encode(['status' => 'success', 'message' => 'Login successful', 'redirect' => $redirect]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Login error: ' . $e->getMessage()]);
    }
    exit;
}

// Check for remember me cookie
// Check for remember me cookie
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['user_email'])) {
    $token = $_COOKIE['remember_token'];
    $email = $_COOKIE['user_email'];
    
    $stmt = $conn->prepare("SELECT id, email, role, full_name, token_expires FROM users WHERE email = ? AND remember_token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if token is still valid
        if (strtotime($user['token_expires']) > time()) {
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Check if profile_picture column exists
            $check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'profile_picture'");
            if ($check_column->num_rows > 0) {
                // Column exists, get the profile picture
                $profile_stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
                $profile_stmt->bind_param("i", $user['id']);
                $profile_stmt->execute();
                $profile_result = $profile_stmt->get_result();
                $profile_data = $profile_result->fetch_assoc();
                
                if ($profile_data && !empty($profile_data['profile_picture'])) {
                    $_SESSION['profile_picture'] = $profile_data['profile_picture'];
                }
                $profile_stmt->close();
            }
            
            // Redirect based on role
            switch ($user['role']) {
                case 'ADMIN':
                    header('Location: admin/dashboard.php');
                    break;
                case 'CSR':
                    header('Location: csr/dashboard.php');
                    break;
                case 'CLIENT':
                    header('Location: client/dashboard.php');
                    break;
                case 'PRINTING':
                    header('Location: printing/dashboard.php');
                    break;
                case 'DESIGNING':
                    header('Location: designing/dashboard.php');
                    break;
                default:
                    header('Location: dashboard.php');
            }
            exit;
        }
    }
    
    $stmt->close();
}

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    switch ($_SESSION['role']) {
        case 'ADMIN':
            header('Location: admin/dashboard.php');
            break;
        case 'CSR':
            header('Location: csr/dashboard.php');
            break;
        case 'CLIENT':
            header('Location: client/dashboard.php');
            break;
        case 'PRINTING':
            header('Location: printing/dashboard.php');
            break;
        case 'DESIGNING':
            header('Location: designing/dashboard.php');
            break;
        default:
            header('Location: dashboard.php');
    }
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM.AI - Login Portal</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f5f7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 15px 30px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .back-btn {
            background: none;
            border: none;
            color: #ff69b4;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-btn:hover {
            color: #ff1493;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }

        .login-card {
            background-color: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(219, 112, 147, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(219, 112, 147, 0.1);
        }

        .login-card:hover {
            box-shadow: 0 15px 40px rgba(219, 112, 147, 0.15);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo img {
            height: 60px;
        }

        .login-title {
            text-align: center;
            color: #ff69b4;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #ff69b4;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255, 105, 180, 0.2);
            background-color: #fff;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(219, 112, 147, 0.05);
        }

        .form-control:focus {
            outline: none;
            border-color: #ff69b4;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }

        .forgot-link {
            text-align: right;
            margin-bottom: 20px;
        }

        .forgot-link a {
            color: #ff69b4;
            font-size: 12px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link a:hover {
            color: #ff1493;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(255, 105, 180, 0.2);
        }

        .login-btn:hover {
            background-color: #ff1493;
            box-shadow: 0 6px 8px rgba(255, 105, 180, 0.3);
            transform: translateY(-2px);
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .help-section {
            margin-top: 20px;
            text-align: center;
        }

        .help-section a {
            color: #ff69b4;
            font-size: 14px;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .help-section a:hover {
            color: #ff1493;
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            display: none;
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .alert-error {
            background-color: rgba(244, 67, 54, 0.1);
            color: #c62828;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 25px;
            }
        }

        .loading-spinner {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #ff69b4;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .loading-text {
        margin-top: 10px;
        color: #ff69b4;
        font-size: 14px;
        font-weight: 500;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
</head>

<body>

<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner"></div>
    <div class="loading-text">Logging in...</div>
</div>
     <!-- Unified Login Form -->
     <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <a href="Homepage/homepage.php">
                    <img src="Homepage/logo.png" alt="I AM AI" title="I AM AI">
                </a>
            </div>
            <div id="loginAlert" class="alert"></div>
            <h2 class="login-title">Login</h2>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="example@iam.ai" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <div class="forgot-link">
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>
        </div>

        <div class="help-section">
            <a href="help.php">Need Help?</a>
            <a href="support.php">Contact Support</a>
            <a href="registration.php">Create Account</a>
        </div>
    </div>

    <script>
        // Show alert message function
        function showAlert(message, type) {
            const alertBox = document.getElementById('loginAlert');
            alertBox.textContent = message;
            alertBox.className = `alert alert-${type}`;
            alertBox.style.display = 'block';

            // Auto hide after 5 seconds
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000);
        }

        // Form submission using AJAX
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('remember', document.getElementById('remember').checked ? 'true' : 'false');

            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        showAlert(data.message, 'success');
                        // Redirect after successful login
                        setTimeout(() => {
                            window.location.href = data.redirect || 'dashboard.php';
                        }, 1000);
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('An error occurred. Please try again.', 'error');
                    console.error('Error:', error);
                });
        });


    // Show alert message function
    function showAlert(message, type) {
        const alertBox = document.getElementById('loginAlert');
        alertBox.textContent = message;
        alertBox.className = `alert alert-${type}`;
        alertBox.style.display = 'block';

        // Auto hide after 5 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }

    // Show/hide loading spinner
    function toggleLoading(show) {
        const spinner = document.getElementById('loadingSpinner');
        spinner.style.display = show ? 'flex' : 'none';
    }

    // Form submission using AJAX
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        toggleLoading(true); // Show loading spinner

        const formData = new FormData(this);
        formData.append('remember', document.getElementById('remember').checked ? 'true' : 'false');

        fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    showAlert(data.message, 'success');
                    // Redirect after successful login
                    setTimeout(() => {
                        window.location.href = data.redirect || 'dashboard.php';
                    }, 1000);
                } else {
                    toggleLoading(false); // Hide loading spinner
                    showAlert(data.message, 'error');
                }
            })
            .catch(error => {
                toggleLoading(false); // Hide loading spinner
                showAlert('An error occurred. Please try again.', 'error');
                console.error('Error:', error);
            });
    });

    </script>
</body>
</html>