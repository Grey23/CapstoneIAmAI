<?php
session_start();
require_once('../includes/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    $project_id = intval($_POST['project_id']);
    $user_id = $_SESSION['user_id'];
    
    // Prepare statement for better security
    $stmt = $conn->prepare("UPDATE projects SET project_status = 'Cancelled', updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    
    if ($stmt->execute()) {
        // Verify the update was successful
        $verify = $conn->query("SELECT project_status FROM projects WHERE id = $project_id");
        $project = $verify->fetch_assoc();

        if ($project && $project['project_status'] === 'Cancelled') {
            $_SESSION['success_message'] = "Project has been Cancelled successfully!";
        } else {
            $_SESSION['error_message'] = "Project status update failed to apply.";
        }
    } else {
        $_SESSION['error_message'] = "Error updating project: " . $stmt->error;
    }
    
    $stmt->close();
    
    // Redirect back to view project
    header("Location: view_project.php?id=$project_id");
    exit();
} else {
    // If no POST data, redirect to projects page
    header("Location: projects.php");
    exit();
}
?>