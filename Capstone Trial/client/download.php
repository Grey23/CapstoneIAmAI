<?php
session_start();
require_once('../includes/db_connection.php');

// Check if user is logged in and has CLIENT role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'CLIENT') {
    header('Location: ../login.php');
    exit;
}

// Check if file parameter exists
if (!isset($_GET['file']) || empty($_GET['file'])) {
    header('Location: projects.php');
    exit;
}

$filename = basename($_GET['file']);
$filepath = "../uploads/projects/" . $filename;

// Check if file exists
if (!file_exists($filepath)) {
    die('File not found.');
}

// Get file extension and set appropriate content type
$file_extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
switch ($file_extension) {
    case 'pdf':
        $content_type = 'application/pdf';
        break;
    case 'doc':
        $content_type = 'application/msword';
        break;
    case 'docx':
        $content_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        break;
    case 'jpg':
    case 'jpeg':
        $content_type = 'image/jpeg';
        break;
    case 'png':
        $content_type = 'image/png';
        break;
    default:
        $content_type = 'application/octet-stream';
}

// Clear any output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Set headers for download
header('Content-Description: File Transfer');
header('Content-Type: ' . $content_type);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

// Output file
readfile($filepath);
exit;
?>