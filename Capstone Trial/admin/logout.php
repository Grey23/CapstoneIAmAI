<?php
// Start session
session_start();

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
?>