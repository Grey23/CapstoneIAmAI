<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Check if file parameter exists
if (!isset($_GET['file']) || empty($_GET['file'])) {
    header('Location: projects.php');
    exit;
}

$filename = basename($_GET['file']);
$file_path = '../uploads/' . $filename;

// Check if file exists
if (!file_exists($file_path)) {
    header('Location: projects.php');
    exit;
}

// Get file extension
$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

// Set appropriate content type
switch ($file_extension) {
    case 'pdf':
        header('Content-Type: application/pdf');
        break;
    case 'jpg':
    case 'jpeg':
        header('Content-Type: image/jpeg');
        break;
    case 'png':
        header('Content-Type: image/png');
        break;
    case 'gif':
        header('Content-Type: image/gif');
        break;
    default:
        header('Location: projects.php');
        exit;
}

// Output the file
readfile($file_path);
exit;
?>