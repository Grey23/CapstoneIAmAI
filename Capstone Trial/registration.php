
<?php
// Start session
session_start();

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
        case 'PRINTING TEAM':
            header('Location: printing/dashboard.php');
            break;
        case 'Graphics Designer':
            header('Location: designing/dashboard.php');
            break;
        default:
            header('Location: dashboard.php');
    }
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "iamai_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    // Validate input
    if (empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }
    
    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
        exit;
    }
    
    // Validate password strength
    if (strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long']);
        exit;
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        $stmt->close();
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Extract name from email (temporary, you might want to add name fields to your form)
    $name_parts = explode('@', $email);
    $full_name = ucfirst(str_replace('.', ' ', $name_parts[0]));
    
    // Standardize role format for database
    $standardized_role = strtoupper(str_replace(' ', '_', $role));
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, password, full_name, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $hashed_password, $full_name, $standardized_role);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful! You can now login.', 'redirect' => 'login.php']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $stmt->error]);
    }
    
    $stmt->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM.AI - Registration</title>

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

        .registration-container {
            width: 100%;
            max-width: 450px;
            margin-bottom: 20px;
        }

        .registration-card {
            background-color: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(219, 112, 147, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(219, 112, 147, 0.1);
        }

        .registration-card:hover {
            box-shadow: 0 15px 40px rgba(219, 112, 147, 0.15);
        }

        .registration-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .registration-logo img {
            height: 60px;
        }

        .registration-title {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-link a {
            color: #ff69b4;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .login-link a:hover {
            color: #ff1493;
            text-decoration: underline;
        }

        .register-btn {
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

        .register-btn:hover {
            background-color: #ff1493;
            box-shadow: 0 6px 8px rgba(255, 105, 180, 0.3);
            transform: translateY(-2px);
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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ff69b4' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
            display: none;
        }

        .password-strength-meter {
            height: 5px;
            background-color: #f1f1f1;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .password-strength-meter div {
            height: 100%;
            width: 0;
            transition: width 0.3s;
        }

        .strength-weak {
            background-color: #f44336;
        }

        .strength-medium {
            background-color: #ff9800;
        }

        .strength-strong {
            background-color: #4caf50;
        }

        @media (max-width: 480px) {
            .registration-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body>
    <!-- Registration Form -->
    <div class="registration-container">
        <div class="registration-card">
            <div class="registration-logo">
                <a href="homepage.php">
                    <img src="logo.png" alt="IAM.AI" title="IAM.AI">
                </a>
            </div>
            <div id="registrationAlert" class="alert"></div>
            <h2 class="registration-title">Create an Account</h2>

            <form id="registrationForm" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="example@iam.ai" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <div class="password-strength">
                        <div class="password-strength-meter">
                            <div id="strengthBar"></div>
                        </div>
                        <span id="strengthText">Password strength: </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="" disabled selected>-- Select your role --</option>
                        <option value="CLIENT">Client</option>
                        <option value="Marketing Team">Marketing Team</option>
                        <option value="CSR">CSR</option>
                        <option value="PRINTING TEAM">Printing Team</option>
                        <option value="ADMIN">Admin</option>
                        <option value="Graphics Designer">Graphics Designer</option>
                    </select>
                </div>

                <button type="submit" class="register-btn">Create Account</button>

                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>

        <div class="help-section">
            <a href="help.php">Need Help?</a>
            <a href="support.php">Contact Support</a>
        </div>
    </div>

    <script>
        // Show alert message function
        function showAlert(message, type) {
            const alertBox = document.getElementById('registrationAlert');
            alertBox.textContent = message;
            alertBox.className = `alert alert-${type}`;
            alertBox.style.display = 'block';

            // Auto hide after 5 seconds
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000);
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const strengthDisplay = document.querySelector('.password-strength');
            
            if (password.length > 0) {
                strengthDisplay.style.display = 'block';
                
                // Calculate strength
                let strength = 0;
                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]+/)) strength += 25;
                if (password.match(/[A-Z]+/)) strength += 25;
                if (password.match(/[0-9]+/) || password.match(/[^a-zA-Z0-9]+/)) strength += 25;
                
                // Update strength meter
                strengthBar.style.width = strength + '%';
                
                // Remove previous classes
                strengthBar.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
                
                // Add appropriate class
                if (strength < 50) {
                    strengthBar.classList.add('strength-weak');
                    strengthText.textContent = 'Password strength: Weak';
                } else if (strength < 100) {
                    strengthBar.classList.add('strength-medium');
                    strengthText.textContent = 'Password strength: Medium';
                } else {
                    strengthBar.classList.add('strength-strong');
                    strengthText.textContent = 'Password strength: Strong';
                }
            } else {
                strengthDisplay.style.display = 'none';
            }
        });

        // Confirm password validation
        document.getElementById('confirm-password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Form submission using AJAX
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Use the current page URL instead of a hardcoded filename
            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Debug response
                    console.log("Response status:", response.status);
                    return response.json().catch(error => {
                        console.error("Error parsing JSON:", error);
                        throw new Error("Server response was not valid JSON");
                    });
                })
                .then(data => {
                    console.log("Response data:", data);
                    if (data.status === 'success') {
                        showAlert(data.message, 'success');
                        // Redirect after successful registration
                        setTimeout(() => {
                            window.location.href = data.redirect || 'login.php';
                        }, 2000);
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again: ' + error.message, 'error');
                });
        });
    </script>
</body>

</html>