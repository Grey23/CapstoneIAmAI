<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Check if file parameter is provided
if (!isset($_GET['file']) || empty($_GET['file'])) {
    echo "File not found.";
    exit;
}

$file = basename($_GET['file']);
$file_path = '../uploads/' . $file;

// Check if file exists
if (!file_exists($file_path)) {
    // Try alternative path
    $file_path = '../uploads/projects/' . $file;
    if (!file_exists($file_path)) {
        echo "File not found.";
        exit;
    }
}

// Output PDF headers
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $file . '"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');

// Output the file
readfile($file_path);
exit;
?>